<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    protected MidtransService $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Create payment for Pendaftaran (Web)
     *
     * @param Request $request
     * @param \App\Models\Pendaftaran $pendaftaran
     * @return \Illuminate\Http\JsonResponse
     */
    public function createPaymentForPendaftaran(Request $request, Pendaftaran $pendaftaran)
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return response()->json(['error' => 'User tidak login'], 401);
            }

            $validated = $request->validate([
                'amount' => 'required|integer|min:1',
            ]);

            // Check if already paid
            if ($pendaftaran->isLunas()) {
                return response()->json(['error' => 'Pembayaran sudah lunas'], 400);
            }

            // Check if amount exceeds outstanding balance
            if ($validated['amount'] > $pendaftaran->sisa()) {
                return response()->json(['error' => 'Jumlah pembayaran melebihi sisa yang harus dibayar'], 400);
            }

            $orderId = 'PEMBAYARAN-' . $pendaftaran->id . '-' . microtime(true);

            // Get phone - safe fallback
            $phone = '-';
            if ($user->peserta && $user->peserta->no_hp) {
                $phone = $user->peserta->no_hp;
            }

            // Create transaction
            $transaction = $this->midtransService->createTransaction(
                $orderId,
                $validated['amount'],
                'Pembayaran Pendaftaran Kursus: ' . ($pendaftaran->kursus->nama ?? 'Kursus'),
                [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $phone,
                ]
            );

            // Get Snap Token
            $snapToken = $this->midtransService->getSnapToken($transaction);

            // Save payment record
            Payment::create([
                'order_id' => $orderId,
                'amount' => $validated['amount'],
                'description' => 'Pembayaran Pendaftaran Kursus: ' . ($pendaftaran->kursus->nama ?? 'Kursus'),
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
            \Log::error('PaymentController Error: ' . $e->getMessage() . ' | ' . $e->getTraceAsString());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Create payment and redirect to Midtrans (API)
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function createPayment(Request $request)
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
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle Midtrans notification/webhook
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function notification(Request $request)
    {
        try {
            $notif = $request->all();
            
            if (empty($notif)) {
                return response()->json(['message' => 'No notification data'], 400);
            }

            $orderId = $notif['order_id'] ?? null;
            $transactionStatus = $notif['transaction_status'] ?? null;
            
            if (!$orderId) {
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
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check payment status
     *
     * @param string $orderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkStatus($orderId)
    {
        try {
            $status = $this->midtransService->getStatus($orderId);
            
            return response()->json([
                'order_id' => $orderId,
                'status' => $status->transaction_status,
                'payment_type' => $status->payment_type ?? null,
                'fraud_status' => $status->fraud_status ?? null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update payment status in database
     *
     * @param string $orderId
     * @param string $status
     * @return void
     */
    protected function updatePaymentStatus(string $orderId, string $status)
    {
        // Update payment status in your database
        // Example: Payment::where('order_id', $orderId)->update(['status' => $status]);
    }

    /**
     * Payment success callback (Web)
     *
     * @param string $orderId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function paymentSuccess($orderId)
    {
        $payment = Payment::where('order_id', $orderId)->first();

        if (!$payment) {
            return redirect()->route('peserta.pendaftaran.index')->with('error', 'Pembayaran tidak ditemukan');
        }

        try {
            // Verify status with Midtrans
            $status = $this->midtransService->getStatus($orderId);

            if ($status->transaction_status === 'settlement' || $status->transaction_status === 'capture') {
                // Update payment status
                $payment->update(['status' => 'success']);

                // Update pendaftaran
                if ($payment->pendaftaran_id) {
                    $pendaftaran = Pendaftaran::findOrFail($payment->pendaftaran_id);
                    $pendaftaran->terbayar += $payment->amount;

                    // Check if fully paid
                    if ($pendaftaran->terbayar >= $pendaftaran->total_bayar) {
                        $pendaftaran->status_pembayaran = 'lunas';
                    } else {
                        $pendaftaran->status_pembayaran = 'cicilan';
                    }

                    $pendaftaran->save();
                }

                return redirect()->route('peserta.pendaftaran.index')->with('success', 'Pembayaran berhasil! Terima kasih.');
            }
        } catch (\Exception $e) {
            return redirect()->route('peserta.pendaftaran.index')->with('error', 'Gagal memverifikasi pembayaran: ' . $e->getMessage());
        }

        return redirect()->route('peserta.pendaftaran.index')->with('error', 'Status pembayaran tidak valid');
    }

    /**
     * Payment failed callback (Web)
     *
     * @param string $orderId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function paymentFailed($orderId)
    {
        $payment = Payment::where('order_id', $orderId)->first();

        if ($payment) {
            $payment->update(['status' => 'failed']);
        }

        return redirect()->route('peserta.pendaftaran.index')->with('error', 'Pembayaran dibatalkan atau gagal. Silakan coba lagi.');
    }
}
