<?php

namespace Modules\Peserta\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Pembayaran;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    protected MidtransService $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Create payment for Pendaftaran via Midtrans
     * 
     * @param Request $request
     * @param Pendaftaran $pendaftaran
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
            \Log::error('PembayaranController Error: ' . $e->getMessage() . ' | ' . $e->getTraceAsString());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Payment success callback
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
                        $pendaftaran->status_pembayaran = 'selesai';
                    } else {
                        $pendaftaran->status_pembayaran = 'dp';
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
     * Payment failed callback
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

    /**
     * Handle Midtrans Webhook Notification
     * This is called by Midtrans server when payment status changes
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleMidtransNotification(Request $request)
    {
        try {
            $notif = $request->all();
            $orderId = $notif['order_id'] ?? null;
            $transactionStatus = $notif['transaction_status'] ?? null;

            if (!$orderId || !$transactionStatus) {
                \Log::error('Midtrans Notification: Missing order_id or transaction_status', $notif);
                return response()->json(['status' => 'error', 'message' => 'Invalid notification'], 400);
            }

            \Log::info('Midtrans Notification Received', ['order_id' => $orderId, 'status' => $transactionStatus]);

            // Find payment record
            $payment = Payment::where('order_id', $orderId)->first();

            if (!$payment) {
                \Log::error('Midtrans Notification: Payment record not found', ['order_id' => $orderId]);
                return response()->json(['status' => 'error', 'message' => 'Payment record not found'], 404);
            }

            // Handle different transaction statuses
            if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {
                
                // Payment successful
                $payment->update(['status' => 'success']);

                // Update pendaftaran
                if ($payment->pendaftaran_id) {
                    $pendaftaran = Pendaftaran::findOrFail($payment->pendaftaran_id);
                    $pendaftaran->terbayar += $payment->amount;

                    // Check if fully paid
                    if ($pendaftaran->terbayar >= $pendaftaran->total_bayar) {
                        $pendaftaran->status_pembayaran = 'selesai';
                    } else {
                        $pendaftaran->status_pembayaran = 'dp';
                    }

                    $pendaftaran->save();

                    \Log::info('Payment updated successfully', [
                        'pendaftaran_id' => $pendaftaran->id,
                        'terbayar' => $pendaftaran->terbayar,
                        'status' => $pendaftaran->status_pembayaran
                    ]);
                }

                return response()->json(['status' => 'success', 'message' => 'Payment verified and updated'], 200);
            } elseif ($transactionStatus === 'pending') {
                $payment->update(['status' => 'pending']);
                return response()->json(['status' => 'success', 'message' => 'Payment pending'], 200);
            } elseif ($transactionStatus === 'deny' || $transactionStatus === 'cancel' || $transactionStatus === 'expire') {
                $payment->update(['status' => 'failed']);
                return response()->json(['status' => 'success', 'message' => 'Payment marked as failed'], 200);
            }

            return response()->json(['status' => 'success', 'message' => 'Notification received'], 200);
        } catch (\Exception $e) {
            \Log::error('Midtrans Notification Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store - OLD METHOD (Deprecated)
     * Kept for reference only - manual payment system removed
     */
    public function store(Request $request, $id)
    {
        return back()->with('error', 'Manual payment tidak lagi tersedia. Gunakan metode pembayaran online via Midtrans.');
    }
}
