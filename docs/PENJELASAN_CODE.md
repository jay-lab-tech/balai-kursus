# Penjelasan Code Project Balai Kursus

Dokumen ini menjelaskan cara membaca project, fungsi setiap bagian utama, alur data, dan aturan sederhana yang digunakan saat mengembangkan fitur baru.

## 1. Gambaran umum

Project ini adalah aplikasi Laravel dengan tiga role utama:

- `admin`: mengelola master data, jadwal, peserta, pembayaran, dan sertifikat.
- `instruktur`: mengelola risalah, absensi, nilai, dan melihat jadwal kursus.
- `peserta`: mendaftar program, mengikuti placement, membayar, melihat kursus, dan mengakses sertifikat.

Alur utama aplikasi:

```text
Browser
  -> Route
  -> Middleware autentikasi/role
  -> Controller
  -> Form Request atau validasi
  -> Service / Model
  -> Database
  -> View atau JSON response
```

## 2. Penjelasan folder

### `app/Models`

Berisi representasi tabel database menggunakan Eloquent. Model menyimpan nama tabel, field yang boleh diisi, cast data, relationship, scope query, dan konstanta status.

Contoh:

```php
class Kursus extends Model
{
    protected $fillable = ['program_id', 'level_id', 'nama'];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
```

Artinya, model `Kursus` boleh menerima field tertentu dan setiap kursus memiliki satu `Program`.

### `app/Http/Controllers`

Berisi pengatur alur request. Controller sebaiknya hanya melakukan empat hal:

1. menerima request;
2. memanggil validasi atau service;
3. menentukan response;
4. mengembalikan view, redirect, atau JSON.

Logika bisnis yang panjang sebaiknya dipindahkan ke `app/Services`.

### `app/Services`

Berisi proses bisnis yang dapat digunakan ulang dan tidak bergantung langsung pada tampilan.

- `PendaftaranPlacementService`: mencocokkan nilai placement dengan level dan kursus yang masih memiliki kuota.
- `MidtransService`: membungkus konfigurasi dan komunikasi dengan Midtrans, termasuk verifikasi signature webhook.

Service membantu controller tetap pendek dan membuat logika lebih mudah diuji.

### `app/Observers`

Berisi aksi otomatis ketika model disimpan atau dihapus. Contohnya `ScoreObserver` menjalankan proses placement setelah score disimpan.

### `app/Policies` dan `app/Http/Middleware`

- Policy memeriksa apakah user boleh melakukan aksi terhadap model tertentu.
- Middleware memeriksa kondisi umum request, seperti login dan role.
- `AdminMiddleware` membatasi area admin.
- `CheckRole` membatasi akses berdasarkan role.

### `Modules`

Berisi kode yang dikelompokkan berdasarkan domain fitur. Contohnya:

- `Program`: data program kursus.
- `Level`: level dan rentang nilai placement.
- `Kursus`: batch kursus, jadwal, lokasi, dan kelas.
- `Peserta`: profil, pendaftaran, pembayaran, dan kursus peserta.
- `Instruktur`: risalah, absensi, nilai, dan jadwal instruktur.
- `Risalah` dan `Absensi`: bagian pendukung proses pembelajaran.

Di dalam setiap module biasanya terdapat `Http/Controllers`, `Routes`, `Resources/views`, `Database`, dan `Providers`.

### `database`

- `migrations`: perubahan struktur database secara berurutan.
- `factories`: pembuat data contoh untuk test.
- `seeders`: data awal atau data demo.

Migration lama tidak boleh dihapus sembarangan jika database sudah pernah digunakan.

### `resources/views` dan `Modules/*/Resources/views`

Berisi tampilan Blade. View hanya bertugas menampilkan data, form, pesan validasi, dan navigasi. Query database sebaiknya tidak ditulis langsung di dalam Blade.

### `routes`

- `routes/web.php`: route global dan route lintas module.
- `routes/auth.php`: login, registrasi, reset password, dan logout.
- `routes/api.php`: endpoint API.
- `Modules/*/Routes/web.php`: route khusus masing-masing module.

Route menentukan URL, middleware, controller, dan nama route.

### `tests`

Berisi pengujian otomatis. Feature test memeriksa alur dari request sampai database atau response. Test payment webhook, sertifikat, otorisasi, jadwal, dan autentikasi sudah tersedia.

## 3. Alur pembayaran

```text
Peserta memilih pendaftaran
  -> PaymentController membuat order
  -> MidtransService meminta Snap Token
  -> Peserta membayar di Midtrans
  -> Midtrans mengirim webhook
  -> signature_key diverifikasi
  -> status payment diperbarui
  -> status pendaftaran diperbarui
```

Webhook harus diproses secara idempotent. Artinya, pengiriman notifikasi yang sama berulang kali tidak boleh menambah nilai `terbayar` lebih dari sekali.

## 4. Alur placement

```text
Score placement disimpan
  -> ScoreObserver memanggil PendaftaranPlacementService
  -> level dicari berdasarkan rentang nilai
  -> kursus yang tersedia dicari berdasarkan program dan level
  -> pendaftaran diperbarui
  -> peserta-kursus-level dibuat atau diperbarui
```

Jika kursus penuh, status pendaftaran menjadi menunggu penempatan.

## 5. Aturan clean code

Saat menambah fitur baru:

1. gunakan nama class dan method yang menjelaskan tujuan;
2. validasi input sebelum menjalankan proses bisnis;
3. gunakan route model binding daripada menerima `$id` lalu melakukan query manual;
4. gunakan Form Request untuk validasi yang panjang atau dipakai berulang;
5. gunakan return type pada method baru;
6. hindari query di Blade;
7. hindari controller yang terlalu panjang;
8. jangan mengirim pesan exception asli kepada user;
9. tambahkan test untuk alur berhasil dan alur gagal;
10. jalankan `vendor/bin/pint` sebelum commit.

## 6. Bagian legacy

Beberapa controller module masih merupakan scaffold bawaan generator dan tidak menjadi route aktif. Contohnya controller resource umum pada module `Pendaftaran`, `Risalah`, `Level`, dan beberapa module lain. Route aktif perlu dijadikan acuan sebelum mengubah atau menghapus controller tersebut.

Controller legacy boleh dihapus setelah seluruh route dan pemanggilannya dipastikan tidak digunakan.

## 7. Perintah pengembangan

```bash
composer install
npm install
php artisan migrate
php artisan serve
npm run dev
```

Untuk production:

```bash
composer install --no-dev --optimize-autoloader
npm run build
php artisan storage:link
php artisan optimize
```

Jangan pernah memasukkan `.env`, credential Midtrans, atau password database ke repository.

## 8. Pemeriksaan sebelum deployment

- `APP_DEBUG=false`.
- `APP_URL` menggunakan HTTPS.
- document root server diarahkan ke folder `public`.
- database production sudah dibuat dan backup tersedia.
- `storage` dan `bootstrap/cache` dapat ditulis Laravel.
- URL webhook Midtrans sudah diarahkan ke domain production.
- mode Midtrans dipastikan sandbox atau production sesuai kebutuhan.
- feature test dijalankan setelah service MySQL aktif.
