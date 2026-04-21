# Google Login Setup

Project ini sudah punya route dan controller untuk login Google:

- `GET /login/google`
- `GET /auth/google/callback`

Konfigurasi environment yang dipakai:

```env
APP_URL=http://localhost:8000
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
```

## Redirect URI yang harus didaftarkan

Jika project dibuka lewat `php artisan serve`:

```text
http://localhost:8000/auth/google/callback
```

Jika project dibuka lewat domain Laragon:

```text
http://balai-kursus-tes.test/auth/google/callback
```

Pastikan `APP_URL` sama persis dengan domain yang dipakai saat membuka aplikasi di browser.

## Langkah di Google Cloud

1. Buat atau pilih project di Google Cloud.
2. Buka bagian OAuth dan buat OAuth Client untuk tipe Web Application.
3. Tambahkan Authorized Redirect URI sesuai URL callback aplikasi.
4. Ambil `Client ID` dan `Client Secret`.
5. Tempel ke file `.env`.

## Setelah isi kredensial

Jalankan:

```bash
php artisan optimize:clear
```

Lalu uji:

1. Buka halaman login.
2. Klik `Masuk dengan Google`.
3. Pilih akun Google.
4. Setelah callback sukses, user akan otomatis dibuat atau login dengan email yang sama.
