# Midtrans Payment Integration Setup

## Instalasi Selesai ✓

Package Midtrans telah berhasil diinstal dan dikonfigurasi di project ini.

## Komponen yang Telah Dibuat

### 1. **Config File** - `config/midtrans.php`
Konfigurasi pusat untuk semua pengaturan Midtrans yang dapat dikustomisasi melalui environment variables.

### 2. **Service Class** - `app/Services/MidtransService.php`
Class utilitas yang mengelola semua interaksi dengan API Midtrans:
- Membuat transaksi
- Mendapatkan Snap Token
- Cek status pembayaran
- Approve/Cancel/Refund transaksi
- dll

### 3. **Controller** - `app/Http/Controllers/PaymentController.php`
Controller untuk menangani endpoint pembayaran:
- `POST /api/payment/create` - Membuat pembayaran baru
- `POST /api/payment/notification` - Menerima webhook dari Midtrans
- `GET /api/payment/status/{orderId}` - Cek status pembayaran

### 4. **Model** - `app/Models/Payment.php`
Model untuk menyimpan data pembayaran di database dengan hubungan ke User dan Pendaftaran.

### 5. **Migration** - `database/migrations/2025_02_06_000001_create_payments_table.php`
Tabel untuk menyimpan data pembayaran dengan status tracking.

### 6. **Service Provider** - `app/Providers/MidtransServiceProvider.php`
Provider untuk mendaftarkan MidtransService di container aplikasi.

## Konfigurasi Environment Variables

Tambahkan kredensial Midtrans Anda di file `.env`:

```env
MIDTRANS_SERVER_KEY=your_server_key_here
MIDTRANS_CLIENT_KEY=your_client_key_here
MIDTRANS_MERCHANT_ID=your_merchant_id_here
MIDTRANS_IS_PRODUCTION=false  # true untuk production
MIDTRANS_SANITIZE=true
MIDTRANS_3D_SECURE=true
MIDTRANS_NOTIFICATION_URL=http://localhost/api/payment/notification
MIDTRANS_FINISH_REDIRECT_URL=http://localhost/payment/finish
MIDTRANS_UNFINISH_REDIRECT_URL=http://localhost/payment/unfinish
MIDTRANS_ERROR_REDIRECT_URL=http://localhost/payment/error
```

## Cara Menggunakan

### 1. Jalankan Migration
```bash
php artisan migrate
```

### 2. Contoh Implementasi di Controller

```php
<?php

namespace App\Http\Controllers;

use App\Services\MidtransService;
use Illuminate\Http\Request;

class MyController extends Controller
{
    protected MidtransService $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    public function processPayment(Request $request)
    {
        // Buat data transaksi
        $transaction = $this->midtrans->createTransaction(
            'ORDER-' . microtime(true),
            100000, // amount in rupiah
            'Pembayaran Kursus',
            [
                'first_name' => 'John',
                'email' => 'john@example.com',
                'phone' => '081234567890',
            ]
        );

        // Dapatkan Snap Token
        $snapToken = $this->midtrans->getSnapToken($transaction);

        return response()->json(['snap_token' => $snapToken]);
    }

    public function checkPaymentStatus($orderId)
    {
        $status = $this->midtrans->getStatus($orderId);
        return response()->json($status);
    }
}
```

### 3. Integrasi dengan Frontend (JavaScript/Vue)

```javascript
// Assuming you have Snap token from backend
const snapToken = response.data.snap_token;

// Show Midtrans payment popup
window.snap.pay(snapToken, {
    onSuccess: function(result) {
        console.log('Payment successful', result);
        // Handle success
    },
    onPending: function(result) {
        console.log('Payment pending', result);
        // Handle pending
    },
    onError: function(result) {
        console.log('Payment error', result);
        // Handle error
    },
    onClose: function() {
        console.log('Payment popup closed');
    }
});
```

### 4. Menambahkan Script Snap.js ke Frontend

Di blade template, tambahkan script Midtrans:

```blade
<!-- Midtrans Snap.js -->
@if(config('midtrans.is_production'))
    <script src="https://app.midtrans.com/snap/snap.js" 
            data-client-key="{{ config('midtrans.client_key') }}"></script>
@else
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>
@endif
```

## API Endpoints

### 1. Buat Pembayaran
**POST** `/api/payment/create`

Request:
```json
{
    "order_id": "ORDER-123",
    "amount": 100000,
    "description": "Pembayaran Kursus Laravel",
    "customer_name": "John Doe",
    "customer_email": "john@example.com",
    "customer_phone": "081234567890"
}
```

Response:
```json
{
    "snap_token": "3b565a1b-asdf-asdf-asdf-asdfasdfasdf",
    "order_id": "ORDER-123"
}
```

### 2. Cek Status Pembayaran
**GET** `/api/payment/status/{orderId}`

Response:
```json
{
    "order_id": "ORDER-123",
    "status": "settlement",
    "payment_type": "credit_card",
    "fraud_status": "accept"
}
```

## Status Pembayaran

- **pending** - Tunggu pembayaran dari customer
- **settlement** - Pembayaran berhasil
- **success** - Pembayaran berhasil
- **failed** - Pembayaran gagal
- **denied** - Pembayaran ditolak
- **refunded** - Dana dikembalikan

## Database Schema

Tabel `payments` menyimpan:
- `order_id` - ID unik dari pesanan
- `amount` - Jumlah pembayaran
- `description` - Deskripsi pembayaran
- `customer_name` - Nama customer
- `customer_email` - Email customer
- `customer_phone` - Nomor telepon customer
- `status` - Status pembayaran (pending, success, failed, refunded)
- `payment_method` - Metode pembayaran yang digunakan
- `transaction_id` - ID transaksi dari Midtrans
- `response_data` - Response lengkap dari Midtrans (JSON)
- `user_id` - FK ke tabel users
- `pendaftaran_id` - FK ke tabel pendaftaran

## Testing

### 1. Dengan Sandbox
Gunakan environment variable `MIDTRANS_IS_PRODUCTION=false` dan credentials sandbox Anda.

### 2. Test Card Numbers
Untuk sandbox testing, gunakan card numbers:
- **Approved**: 4811 1111 1111 1114
- **Denied**: 4911 1111 1111 1113

### 3. Manual Testing
```bash
# Check payment status via API
curl http://localhost/api/payment/status/ORDER-123
```

## Troubleshooting

### 1. "MIDTRANS_SERVER_KEY not set"
- Pastikan Server Key telah diisi di file `.env`
- Jalankan `php artisan config:cache`

### 2. "Snap Token not generated"
- Verifikasi kredensial Midtrans di `.env`
- Pastikan Server Key memiliki permission yang benar

### 3. Notification tidak diterima
- Pastikan `MIDTRANS_NOTIFICATION_URL` dapat diakses dari internet
- Untuk testing lokal, gunakan tunnel seperti ngrok

## Next Steps

1. Sesuaikan URL redirect di `MIDTRANS_NOTIFICATION_URL`, `MIDTRANS_FINISH_REDIRECT_URL` sesuai kebutuhan
2. Tambahkan validasi bisnis di `PaymentController` sesuai rules aplikasi Anda
3. Implementasikan payment status tracking di frontend
4. Integrate dengan proses registration/enrollment flow
5. Setup webhook handling untuk payment notifications

## Dokumentasi Resmi

- Midtrans Docs: https://docs.midtrans.com
- Snap Docs: https://docs.midtrans.com/en/snap/overview
- API Reference: https://docs.midtrans.com/en/technical-reference/api-overview
