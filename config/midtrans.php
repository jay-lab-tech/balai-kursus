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
     * Alamat Snap.js. Diturunkan dari is_production supaya halaman pembayaran
     * tidak pernah memuat skrip sandbox di lingkungan produksi.
     */
    'snap_url' => env('MIDTRANS_IS_PRODUCTION', false)
        ? 'https://app.midtrans.com/snap/snap.js'
        : 'https://app.sandbox.midtrans.com/snap/snap.js',

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
     *
     * Default mengarah ke rute aplikasi sendiri supaya callback tidak pernah
     * dikirim sebagai string kosong ketika .env belum lengkap.
     */
    'notification_url' => env('MIDTRANS_NOTIFICATION_URL')
        ?: rtrim((string) env('APP_URL', ''), '/').'/peserta/pembayaran-notification',

    /*
     * Alamat kembalinya browser setelah membayar TIDAK lagi diatur di sini.
     *
     * Rute tujuannya berbentuk /peserta/pembayaran-success/{orderId}, jadi
     * alamatnya berbeda untuk setiap transaksi dan mustahil ditulis sebagai
     * satu string tetap di .env. MIDTRANS_ERROR_REDIRECT_URL yang lama bahkan
     * kehilangan segmen {orderId} sehingga selalu berujung 404. Sekarang
     * ketiganya dibangun dengan route() di MidtransService::createTransaction().
     */
];
