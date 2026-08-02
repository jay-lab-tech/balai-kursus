<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService
{
    /**
     * Initialize Midtrans Configuration
     */
    public function __construct()
    {
        Config::$serverKey = (string) config('midtrans.server_key');
        Config::$clientKey = (string) config('midtrans.client_key');
        Config::$isProduction = (bool) config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.sanitize');
        Config::$is3ds = config('midtrans.enable_3d_secure');
    }

    /**
     * Memastikan notifikasi yang datang benar-benar dibuat oleh Midtrans.
     * Midtrans membuat signature dari order_id, status_code, gross_amount,
     * dan server key aplikasi.
     */
    public function isValidNotification(array $notification): bool
    {
        $signatureKey = $notification['signature_key'] ?? null;
        $orderId = $notification['order_id'] ?? null;
        $statusCode = $notification['status_code'] ?? null;
        $grossAmount = $notification['gross_amount'] ?? null;

        if (! $signatureKey || ! $orderId || ! $statusCode || $grossAmount === null) {
            return false;
        }

        $expectedSignature = hash(
            'sha512',
            $orderId.$statusCode.$grossAmount.config('midtrans.server_key')
        );

        return hash_equals($expectedSignature, $signatureKey);
    }

    /**
     * Get Snap Token for creating payment page
     *
     *
     * @throws \Exception
     */
    public function getSnapToken(array $transaction): string
    {
        try {
            return Snap::getSnapToken($transaction);
        } catch (\Exception $e) {
            \Log::error('Snap Token Error: '.$e->getMessage(), [
                'transaction' => $transaction,
            ]);
            throw new \Exception('Midtrans Error: '.$e->getMessage());
        }
    }

    /**
     * Get Snap Redirect URL
     *
     *
     * @throws \Exception
     */
    public function getSnapRedirectUrl(array $transaction): string
    {
        try {
            return Snap::getSnapRedirectUrl($transaction);
        } catch (\Exception $e) {
            throw new \Exception('Midtrans Error: '.$e->getMessage());
        }
    }

    /**
     * Create transaction with basic parameters
     */
    public function createTransaction(
        string $orderId,
        int $amount,
        string $description,
        array $customerDetails,
        array $itemDetails = []
    ): array {
        $transaction = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
            'customer_details' => $customerDetails,
        ];

        if (! empty($itemDetails)) {
            $transaction['item_details'] = $itemDetails;
        }

        // Callback ini untuk browser user. Webhook server-to-server Midtrans
        // dikonfigurasi melalui Payment Notification URL, bukan callback Snap.
        //
        // Dibangun dengan route() dan orderId, bukan string tetap dari .env.
        // Dulu 'finish' langsung menuju daftar pendaftaran, sehingga rute
        // pembayaran-success yang justru memverifikasi status ke Midtrans tidak
        // pernah dilewati — status hanya ikut berubah bila webhook sampai.
        // 'unfinish' berarti pengguna membatalkan di tengah jalan; tidak ada
        // yang perlu diverifikasi, jadi cukup dikembalikan ke daftar.
        $transaction['callbacks'] = [
            'finish' => route('peserta.pembayaran-success', $orderId),
            'unfinish' => route('peserta.pendaftaran.index'),
            'error' => route('peserta.pembayaran-failed', $orderId),
        ];

        return $transaction;
    }

    /**
     * Get transaction status
     *
     *
     * @throws \Exception
     */
    public function getStatus(string $orderId): array|object
    {
        try {
            return Transaction::status($orderId);
        } catch (\Exception $e) {
            throw new \Exception('Midtrans Error: '.$e->getMessage());
        }
    }

    /**
     * Approve transaction
     *
     *
     * @throws \Exception
     */
    public function approve(string $orderId): array|object
    {
        try {
            return Transaction::approve($orderId);
        } catch (\Exception $e) {
            throw new \Exception('Midtrans Error: '.$e->getMessage());
        }
    }

    /**
     * Cancel transaction
     *
     *
     * @throws \Exception
     */
    public function cancel(string $orderId): array|object
    {
        try {
            return Transaction::cancel($orderId);
        } catch (\Exception $e) {
            throw new \Exception('Midtrans Error: '.$e->getMessage());
        }
    }

    /**
     * Refund transaction
     *
     *
     * @throws \Exception
     */
    public function refund(string $orderId, ?int $amount = null): array|object
    {
        try {
            if ($amount) {
                return Transaction::refund($orderId, $amount);
            }

            return Transaction::refund($orderId);
        } catch (\Exception $e) {
            throw new \Exception('Midtrans Error: '.$e->getMessage());
        }
    }

    /**
     * Deny transaction
     *
     *
     * @throws \Exception
     */
    public function deny(string $orderId): array|object
    {
        try {
            return Transaction::deny($orderId);
        } catch (\Exception $e) {
            throw new \Exception('Midtrans Error: '.$e->getMessage());
        }
    }
}
