<?php

return [
    /*
     * Midtrans Server Key
     */
    'server_key' => env('MIDTRANS_SERVER_KEY', ''),

    /*
     * Midtrans Client Key
     */
    'client_key' => env('MIDTRANS_CLIENT_KEY', ''),

    /*
     * Midtrans Merchant ID
     */
    'merchant_id' => env('MIDTRANS_MERCHANT_ID', ''),

    /*
     * Midtrans Environment
     * Set to 'production' for production environment
     * Set to 'sandbox' for sandbox/testing environment
     */
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),

    /*
     * Midtrans Sanitizer
     */
    'sanitize' => env('MIDTRANS_SANITIZE', true),

    /*
     * Midtrans 3D Secure
     */
    'enable_3d_secure' => env('MIDTRANS_3D_SECURE', true),

    /*
     * Midtrans Notification URLs
     */
    'notification_url' => env('MIDTRANS_NOTIFICATION_URL', ''),

    /*
     * Midtrans Finish Redirect URLs
     */
    'finish_redirect_url' => env('MIDTRANS_FINISH_REDIRECT_URL', ''),
    
    'unfinish_redirect_url' => env('MIDTRANS_UNFINISH_REDIRECT_URL', ''),
    
    'error_redirect_url' => env('MIDTRANS_ERROR_REDIRECT_URL', ''),
];
