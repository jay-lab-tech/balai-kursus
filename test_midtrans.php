<?php
// Test Midtrans credentials
require 'vendor/autoload.php';

use Midtrans\Config;
use Midtrans\Snap;

// Set config from environment variables to avoid committing secrets
Config::$serverKey = getenv('MIDTRANS_SERVER_KEY') ?: 'REMOVED_MIDTRANS_SERVER_KEY';
Config::$clientKey = getenv('MIDTRANS_CLIENT_KEY') ?: 'REMOVED_MIDTRANS_CLIENT_KEY';
Config::$isProduction = false;
Config::$isSanitized = true;
Config::$is3ds = false;

echo "Testing Midtrans Credentials\n";
echo "Server Key: " . Config::$serverKey . "\n";
echo "Client Key: " . Config::$clientKey . "\n";
echo "Production: " . (Config::$isProduction ? 'Yes' : 'No') . "\n";
echo "---\n";

$transaction = [
    'transaction_details' => [
        'order_id' => 'TEST-' . time(),
        'gross_amount' => 100000,
    ],
    'customer_details' => [
        'first_name' => 'Test',
        'email' => 'test@example.com',
        'phone' => '08123456789',
    ],
];

try {
    echo "Requesting Snap Token...\n";
    $snapToken = Snap::getSnapToken($transaction);
    echo "SUCCESS! Snap Token: " . substr($snapToken, 0, 50) . "...\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Code: " . $e->getCode() . "\n";
}
