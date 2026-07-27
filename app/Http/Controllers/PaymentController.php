<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Pendaftaran;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

            $amount = (int) ($validated['amount'] ?? $pendaftaran->sisa());

            // Check if already paid
            if ($pendaftaran->isLunas()) {
                return response()->json(['error' => 'Pembayaran sudah lunas'], 400);
            }

            if ($amount < 1) {
                return response()->json(['error' => 'Tidak ada tagihan yang perlu dibayar.'], 400);
            }

            // Check if amount exceeds outstanding balance
            if ($amount > $pendaftaran->sisa()) {
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
        $payment = Payment::where('order_id', $orderId)->first();

        if (! $payment) {
            return;
        }

        $previousStatus = $payment->status;
        $payment->update(['status' => $status]);

        // Notification Midtrans dapat dikirim ulang. Update pendaftaran hanya
        // dilakukan saat payment baru berubah menjadi success.
        if ($status === 'success' && $previousStatus !== 'success') {
            $this->updatePendaftaranStatus($payment);
        }
    }

    /**
     * Payment success callback (Web)
     */
    public function paymentSuccess(string $orderId): RedirectResponse
    {
        $payment = Payment::where('order_id', $orderId)->first();

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
        $payment = Payment::where('order_id', $orderId)->first();

        if ($payment) {
            $payment->update(['status' => 'failed']);
        }

        return redirect()->route('peserta.pendaftaran.index')->with('error', 'Pembayaran dibatalkan atau gagal. Silakan coba lagi.');
    }

    /**
     * Handle payment with lunas/cicilan flow
     * Update pendaftaran status based on payment completion
     */
    protected function updatePendaftaranStatus(Payment $payment): void
    {
        if (! $payment->pendaftaran_id) {
            return;
        }

        $pendaftaran = Pendaftaran::findOrFail($payment->pendaftaran_id);
        $pendaftaran->terbayar += $payment->amount;

        // Update status pembayaran
        if ($pendaftaran->terbayar >= $pendaftaran->total_bayar) {
            $pendaftaran->status_pembayaran = Pendaftaran::PAYMENT_LUNAS;
            $pendaftaran->status_pendaftaran = Pendaftaran::STATUS_AKTIF;
        } else {
            $pendaftaran->status_pembayaran = Pendaftaran::PAYMENT_CICIL;
        }

        $pendaftaran->save();
    }
}
