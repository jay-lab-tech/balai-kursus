<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Pendaftaran;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected MidtransService $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Create payment for Pendaftaran (Web)
     */
    public function createPaymentForPendaftaran(Request $request, Pendaftaran $pendaftaran): JsonResponse
    {
        try {
            $user = auth()->user();

            if (! $user) {
                return response()->json(['error' => 'User tidak login'], 401);
            }

            if (! $user->peserta || $pendaftaran->peserta_id !== $user->peserta->id) {
                return response()->json(['error' => 'Pendaftaran ini tidak dapat diakses.'], 403);
            }

            $validated = $request->validate([
                'amount' => 'nullable|integer|min:1',
            ]);

            // Check if already paid
            if ($pendaftaran->isLunas()) {
                return response()->json(['error' => 'Pembayaran sudah lunas'], 400);
            }

            // Tagihan yang masih menggantung di Midtrans ikut mengurangi sisa yang
            // boleh ditagih lagi, supaya peserta tidak membayar dua kali untuk
            // porsi yang sama. Snap kedaluwarsa dalam 24 jam, jadi tagihan lama
            // tidak memblokir peserta selamanya.
            $pendingAmount = (int) Payment::query()
                ->where('pendaftaran_id', $pendaftaran->id)
                ->where('status', 'pending')
                ->where('created_at', '>=', now()->subDay())
                ->sum('amount');

            $payable = max(0, (int) $pendaftaran->sisa() - $pendingAmount);

            if ($payable < 1) {
                return response()->json([
                    'error' => $pendingAmount > 0
                        ? 'Masih ada tagihan yang belum diselesaikan. Selesaikan atau tunggu tagihan itu kedaluwarsa.'
                        : 'Tidak ada tagihan yang perlu dibayar.',
                ], 400);
            }

            $amount = (int) ($validated['amount'] ?? $payable);

            if ($amount < 1) {
                return response()->json(['error' => 'Tidak ada tagihan yang perlu dibayar.'], 400);
            }

            // Check if amount exceeds outstanding balance
            if ($amount > $payable) {
                return response()->json(['error' => 'Jumlah pembayaran melebihi sisa yang harus dibayar'], 400);
            }

            if (! $pendaftaran->canBePaid()) {
                return response()->json(['error' => 'Pembayaran baru tersedia setelah admin menempatkan peserta ke kelas.'], 400);
            }

            if (! $pendaftaran->kursus) {
                return response()->json(['error' => 'Peserta belum mendapatkan kelas untuk dibayar.'], 400);
            }

            $orderId = 'KELAS-'.$pendaftaran->id.'-'.str_replace('.', '', (string) microtime(true));

            // Get phone - safe fallback
            $phone = '-';
            if ($user->peserta && $user->peserta->no_hp) {
                $phone = $user->peserta->no_hp;
            }

            $courseDescription = 'Pembayaran Kelas: '.$pendaftaran->kursus->nama;

            // Create transaction
            $transaction = $this->midtransService->createTransaction(
                $orderId,
                $amount,
                $courseDescription,
                [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $phone,
                ],
                [[
                    'id' => 'kursus-'.$pendaftaran->kursus_id,
                    'price' => $amount,
                    'quantity' => 1,
                    'name' => substr($pendaftaran->kursus->nama, 0, 50),
                ]]
            );

            // Get Snap Token
            $snapToken = $this->midtransService->getSnapToken($transaction);

            // Save payment record
            Payment::create([
                'order_id' => $orderId,
                'amount' => $amount,
                'description' => $courseDescription,
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => $phone,
                'status' => 'pending',
                'user_id' => $user->id,
                'pendaftaran_id' => $pendaftaran->id,
            ]);

            return response()->json([
                'snap_token' => $snapToken,
                'order_id' => $orderId,
            ]);
        } catch (\Exception $e) {
            Log::error('Payment creation failed.', [
                'pendaftaran_id' => $pendaftaran->id,
                'exception' => $e,
            ]);

            return response()->json(['error' => 'Pembayaran tidak dapat dibuat.'], 500);
        }
    }

    /**
     * Create payment and redirect to Midtrans (API)
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function createPayment(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'order_id' => 'required|string|unique:payments,order_id',
                'amount' => 'required|integer|min:1',
                'description' => 'required|string',
                'customer_name' => 'required|string',
                'customer_email' => 'required|email',
                'customer_phone' => 'required|string',
            ]);

            // Create transaction
            $transaction = $this->midtransService->createTransaction(
                $validated['order_id'],
                $validated['amount'],
                $validated['description'],
                [
                    'first_name' => $validated['customer_name'],
                    'email' => $validated['customer_email'],
                    'phone' => $validated['customer_phone'],
                ]
            );

            // Get Snap Token
            $snapToken = $this->midtransService->getSnapToken($transaction);

            return response()->json([
                'snap_token' => $snapToken,
                'order_id' => $validated['order_id'],
            ]);
        } catch (\Exception $e) {
            Log::error('Generic payment creation failed.', ['exception' => $e]);

            return response()->json([
                'error' => 'Pembayaran tidak dapat dibuat.',
            ], 500);
        }
    }

    /**
     * Handle Midtrans notification/webhook
     */
    public function notification(Request $request): JsonResponse
    {
        try {
            $notif = $request->all();

            if (empty($notif)) {
                return response()->json(['message' => 'No notification data'], 400);
            }

            if (! $this->midtransService->isValidNotification($notif)) {
                Log::warning('Invalid Midtrans notification signature.', [
                    'order_id' => $notif['order_id'] ?? null,
                ]);

                return response()->json(['message' => 'Invalid notification signature'], 403);
            }

            $orderId = $notif['order_id'] ?? null;
            $transactionStatus = $notif['transaction_status'] ?? null;

            if (! $orderId) {
                return response()->json(['message' => 'Invalid notification data'], 400);
            }

            // Get transaction status from Midtrans
            $status = $this->midtransService->getStatus($orderId);

            // Handle based on transaction status
            switch ($transactionStatus) {
                case 'capture':
                    if ($status->fraud_status == 'accept') {
                        // Mark transaction as success
                        $this->updatePaymentStatus($orderId, 'success');
                    }
                    break;

                case 'settlement':
                    // Mark transaction as settled
                    $this->updatePaymentStatus($orderId, 'success');
                    break;

                case 'pending':
                    // Mark transaction as pending
                    $this->updatePaymentStatus($orderId, 'pending');
                    break;

                case 'deny':
                    // Mark transaction as denied
                    $this->updatePaymentStatus($orderId, 'failed');
                    break;

                case 'cancel':
                    // Mark transaction as cancelled
                    $this->updatePaymentStatus($orderId, 'failed');
                    break;

                case 'expire':
                    // Mark transaction as expired
                    $this->updatePaymentStatus($orderId, 'failed');
                    break;

                case 'refund':
                    // Mark transaction as refunded
                    $this->updatePaymentStatus($orderId, 'refunded');
                    break;
            }

            return response()->json(['message' => 'Notification processed']);
        } catch (\Exception $e) {
            Log::error('Midtrans notification processing failed.', ['exception' => $e]);

            return response()->json([
                'error' => 'Notification tidak dapat diproses.',
            ], 500);
        }
    }

    /**
     * Check payment status
     */
    public function checkStatus(string $orderId): JsonResponse
    {
        try {
            $payment = Payment::query()
                ->where('order_id', $orderId)
                ->where('user_id', auth()->id())
                ->first();

            if (! $payment) {
                return response()->json(['error' => 'Pembayaran tidak ditemukan.'], 404);
            }

            $status = $this->midtransService->getStatus($orderId);

            return response()->json([
                'order_id' => $orderId,
                'status' => $status->transaction_status,
                'payment_type' => $status->payment_type ?? null,
                'fraud_status' => $status->fraud_status ?? null,
            ]);
        } catch (\Exception $e) {
            Log::error('Payment status check failed.', [
                'order_id' => $orderId,
                'exception' => $e,
            ]);

            return response()->json([
                'error' => 'Status pembayaran tidak dapat diperiksa.',
            ], 500);
        }
    }

    /**
     * Update payment status in database and linked Pendaftaran
     */
    protected function updatePaymentStatus(string $orderId, string $status): void
    {
        // Midtrans dapat mengirim notifikasi berulang, bahkan bersamaan. Baris
        // payment dan pendaftaran dikunci agar hanya satu proses yang menghitung.
        DB::transaction(function () use ($orderId, $status) {
            $payment = Payment::where('order_id', $orderId)->lockForUpdate()->first();

            if (! $payment || $payment->status === $status) {
                return;
            }

            $payment->update(['status' => $status]);

            $this->syncPendaftaranPayment($payment);
        });
    }

    /**
     * Payment success callback (Web)
     */
    public function paymentSuccess(string $orderId): RedirectResponse
    {
        $payment = $this->findOwnedPayment($orderId);

        if (! $payment) {
            return redirect()->route('peserta.pendaftaran.index')->with('error', 'Pembayaran tidak ditemukan');
        }

        try {
            // Verify status with Midtrans
            $status = $this->midtransService->getStatus($orderId);

            if ($status->transaction_status === 'settlement' || $status->transaction_status === 'capture') {
                // Update payment status and pendaftaran
                $this->updatePaymentStatus($orderId, 'success');

                return redirect()->route('peserta.pendaftaran.index')->with('success', 'Pembayaran berhasil! Terima kasih.');
            }
        } catch (\Exception $e) {
            Log::error('Payment success callback verification failed.', [
                'order_id' => $orderId,
                'exception' => $e,
            ]);

            return redirect()->route('peserta.pendaftaran.index')
                ->with('error', 'Gagal memverifikasi pembayaran. Silakan coba lagi.');
        }

        return redirect()->route('peserta.pendaftaran.index')->with('error', 'Status pembayaran tidak valid');
    }

    /**
     * Payment failed callback (Web)
     */
    public function paymentFailed(string $orderId): RedirectResponse
    {
        $payment = $this->findOwnedPayment($orderId);

        if (! $payment) {
            return redirect()->route('peserta.pendaftaran.index')->with('error', 'Pembayaran tidak ditemukan');
        }

        // Callback ini hanya sinyal dari browser, bukan dari Midtrans. Karena itu
        // ia cuma boleh menutup tagihan yang masih pending; pembayaran yang sudah
        // berhasil tidak boleh diturunkan dari sini.
        if ($payment->status === 'pending') {
            $payment->update(['status' => 'failed']);
        }

        return redirect()->route('peserta.pendaftaran.index')->with('error', 'Pembayaran dibatalkan atau gagal. Silakan coba lagi.');
    }

    /**
     * Ambil payment milik user yang sedang login. Order id ada di URL, jadi
     * kepemilikan wajib diperiksa sebelum statusnya boleh disentuh.
     */
    protected function findOwnedPayment(string $orderId): ?Payment
    {
        if (! auth()->id()) {
            return null;
        }

        return Payment::query()
            ->where('order_id', $orderId)
            ->where('user_id', auth()->id())
            ->first();
    }

    /**
     * Hitung ulang posisi pembayaran pendaftaran dari catatan payment yang
     * berhasil. Dihitung ulang (bukan ditambah) supaya notifikasi berulang atau
     * refund tidak membuat angka terbayar melenceng.
     */
    protected function syncPendaftaranPayment(Payment $payment): void
    {
        if (! $payment->pendaftaran_id) {
            return;
        }

        $pendaftaran = Pendaftaran::query()
            ->whereKey($payment->pendaftaran_id)
            ->lockForUpdate()
            ->first();

        if (! $pendaftaran) {
            return;
        }

        $terbayar = (int) Payment::query()
            ->where('pendaftaran_id', $pendaftaran->id)
            ->where('status', 'success')
            ->sum('amount');

        $total = (int) $pendaftaran->total_bayar;

        $pendaftaran->terbayar = $terbayar;

        if ($total > 0 && $terbayar >= $total) {
            $pendaftaran->status_pembayaran = Pendaftaran::PAYMENT_LUNAS;

            if ($pendaftaran->status_pendaftaran === Pendaftaran::STATUS_MENUNGGU_PEMBAYARAN) {
                $pendaftaran->status_pendaftaran = Pendaftaran::STATUS_AKTIF;
            }
        } else {
            $pendaftaran->status_pembayaran = $terbayar > 0
                ? Pendaftaran::PAYMENT_CICIL
                : Pendaftaran::PAYMENT_PENDING;

            // Pembayaran dibatalkan/refund setelah kelas aktif: kembalikan ke
            // antrean pembayaran, tanpa mengubah kelas yang sudah selesai.
            if ($pendaftaran->status_pendaftaran === Pendaftaran::STATUS_AKTIF) {
                $pendaftaran->status_pendaftaran = Pendaftaran::STATUS_MENUNGGU_PEMBAYARAN;
            }
        }

        $pendaftaran->save();
    }
}
