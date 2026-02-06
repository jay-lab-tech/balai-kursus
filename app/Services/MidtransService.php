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
        $serverKey = config('midtrans.server_key');
        $clientKey = config('midtrans.client_key');
        $isProduction = config('midtrans.is_production');
        
        // Debug logging - FULL credentials
        \Log::info('Midtrans Config Loaded FULL', [
            'server_key' => $serverKey,
            'client_key' => $clientKey,
            'is_production' => $isProduction,
            'server_key_length' => strlen($serverKey),
        ]);
        
        Config::$serverKey = $serverKey;
        Config::$clientKey = $clientKey;
        Config::$isProduction = $isProduction;
        Config::$isSanitized = config('midtrans.sanitize');
        Config::$is3ds = config('midtrans.enable_3d_secure');
    }

    /**
     * Get Snap Token for creating payment page
     *
     * @param array $transaction
     * @return string
     * @throws \Exception
     */
    public function getSnapToken(array $transaction)
    {
        try {
            // Log transaction data
            \Log::info('Snap Token Request', [
                'transaction' => $transaction,
                'config' => [
                    'is_production' => Config::$isProduction,
                    'server_key_set' => !empty(Config::$serverKey),
                ]
            ]);
            
            return Snap::getSnapToken($transaction);
        } catch (\Exception $e) {
            \Log::error('Snap Token Error: ' . $e->getMessage(), [
                'transaction' => $transaction
            ]);
            throw new \Exception('Midtrans Error: ' . $e->getMessage());
        }
    }

    /**
     * Get Snap Redirect URL
     *
     * @param array $transaction
     * @return string
     * @throws \Exception
     */
    public function getSnapRedirectUrl(array $transaction)
    {
        try {
            return Snap::getSnapRedirectUrl($transaction);
        } catch (\Exception $e) {
            throw new \Exception('Midtrans Error: ' . $e->getMessage());
        }
    }

    /**
     * Create transaction with basic parameters
     *
     * @param string $orderId
     * @param int $amount
     * @param string $description
     * @param array $customerDetails
     * @param array $itemDetails
     * @return array
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

        if (!empty($itemDetails)) {
            $transaction['item_details'] = $itemDetails;
        }

        $transaction['callbacks'] = [
            'finish' => config('midtrans.finish_redirect_url'),
            'error' => config('midtrans.error_redirect_url'),
            'pending' => config('midtrans.notification_url'),
        ];

        return $transaction;
    }

    /**
     * Get transaction status
     *
     * @param string $orderId
     * @return array|object
     * @throws \Exception
     */
    public function getStatus(string $orderId)
    {
        try {
            return Transaction::status($orderId);
        } catch (\Exception $e) {
            throw new \Exception('Midtrans Error: ' . $e->getMessage());
        }
    }

    /**
     * Approve transaction
     *
     * @param string $orderId
     * @return array|object
     * @throws \Exception
     */
    public function approve(string $orderId)
    {
        try {
            return Transaction::approve($orderId);
        } catch (\Exception $e) {
            throw new \Exception('Midtrans Error: ' . $e->getMessage());
        }
    }

    /**
     * Cancel transaction
     *
     * @param string $orderId
     * @return array|object
     * @throws \Exception
     */
    public function cancel(string $orderId)
    {
        try {
            return Transaction::cancel($orderId);
        } catch (\Exception $e) {
            throw new \Exception('Midtrans Error: ' . $e->getMessage());
        }
    }

    /**
     * Refund transaction
     *
     * @param string $orderId
     * @param int|null $amount
     * @return array|object
     * @throws \Exception
     */
    public function refund(string $orderId, ?int $amount = null)
    {
        try {
            if ($amount) {
                return Transaction::refund($orderId, $amount);
            }
            return Transaction::refund($orderId);
        } catch (\Exception $e) {
            throw new \Exception('Midtrans Error: ' . $e->getMessage());
        }
    }

    /**
     * Deny transaction
     *
     * @param string $orderId
     * @return array|object
     * @throws \Exception
     */
    public function deny(string $orderId)
    {
        try {
            return Transaction::deny($orderId);
        } catch (\Exception $e) {
            throw new \Exception('Midtrans Error: ' . $e->getMessage());
        }
    }
}
