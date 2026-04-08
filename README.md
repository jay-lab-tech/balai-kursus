# Balai Kursus

Aplikasi manajemen kursus berbasis Laravel untuk mengelola peserta, program, kelas, penempatan level, pembayaran, absensi, nilai, dan sertifikat.

## Fitur Utama

- Login Laravel Breeze dan SSO CAS.
- Dashboard terpisah untuk admin, instruktur, dan peserta.
- Manajemen program, level, kursus, lokasi, kelas, dan jadwal.
- Pendaftaran peserta dan penempatan ke kursus.
- Pembayaran online Midtrans.
- Absensi, risalah, dan penilaian instruktur.
- Penerbitan sertifikat dan unduh sertifikat peserta.

## Stack

- PHP 8.1+
- Laravel 10
- MySQL
- Blade, Vite, Tailwind
- `nwidart/laravel-modules`
- Midtrans Snap
- DOMPDF
- Laravel Sanctum

## Struktur

- `app/` berisi model inti, controller umum, middleware, observer, service, dan export.
- `Modules/` berisi fitur domain per area seperti `Kursus`, `Peserta`, `Instruktur`, `Program`, dan lain-lain.
- `routes/web.php` berisi route global lintas modul.
- `database/migrations/` menyimpan histori schema proyek.

## Alur Aktif yang Perlu Diketahui

### Role

- `admin` untuk area administrasi.
- `instruktur` untuk absensi, risalah, nilai, dan jadwal.
- `peserta` untuk kursus, pendaftaran, pembayaran, dan sertifikat milik sendiri.

### Pembayaran

- Alur pembayaran aktif menggunakan tabel `payments` dan model `App\Models\Payment`.
- Webhook Midtrans masuk ke `/peserta/pembayaran-notification`.
- Artefak pembayaran manual lama sudah dihapus dari alur aktif aplikasi.

### Sertifikat

- Admin mengelola sertifikat dari `/admin/certificates`.
- Peserta melihat sertifikat pada halaman profil.
- Sertifikat harus berstatus `published` agar dapat diunduh peserta.

## Menjalankan Proyek

```bash
composer install
npm install
php artisan migrate
php artisan serve
npm run dev
```

Sesuaikan `.env` untuk database, CAS, dan Midtrans sebelum menjalankan aplikasi.

## Konfigurasi Penting

### Midtrans

Variabel `.env` yang umum dipakai:

- `MIDTRANS_SERVER_KEY`
- `MIDTRANS_CLIENT_KEY`
- `MIDTRANS_MERCHANT_ID`
- `MIDTRANS_IS_PRODUCTION`
- `MIDTRANS_NOTIFICATION_URL`
- `MIDTRANS_FINISH_REDIRECT_URL`
- `MIDTRANS_UNFINISH_REDIRECT_URL`
- `MIDTRANS_ERROR_REDIRECT_URL`

Referensi setup tambahan tersedia di `MIDTRANS_SETUP.md`.

### CAS

Login CAS tersedia di route `login/cas`. Pastikan atribut identitas CAS sesuai kebutuhan aplikasi.

## Testing

Menjalankan seluruh suite:

```bash
php artisan test
```

## Catatan Teknis

- Histori migration memuat beberapa refactor lama, terutama di area `level`, pembayaran, dan flow pendaftaran. Jangan menghapus migration lama sembarangan pada proyek yang sudah memiliki data.
- Saat mengembangkan fitur baru, prioritaskan alur yang aktif dipakai route dan test saat ini, terutama area pembayaran online berbasis `payments`.
- Jika command Laravel gagal karena log file atau cache, periksa permission folder `storage/` dan `bootstrap/cache/`.
