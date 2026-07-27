# Analisis Project Laravel Balai Kursus

Dokumen ini disusun dari pembacaan source code, konfigurasi, route, model, migration, view, service, observer, policy, test, dan dump struktur database. Analisis bersifat statis/read-only; database tidak dijalankan dan source aplikasi tidak diubah.

## 1. Ringkasan

Balai Kursus adalah aplikasi web manajemen kursus bahasa. Sistem mengelola akun, program, level, batch kursus, pendaftaran, placement test, penempatan kelas, pembayaran Midtrans, jadwal, risalah, absensi, nilai, dan sertifikat. Role: admin, instruktur, peserta.

Alur utama: peserta mendaftar program; admin/instruktur mengisi nilai placement; sistem mencocokkan nilai dengan rentang level dan mencari kelas yang kuotanya tersedia; peserta membayar melalui Midtrans; instruktur menjalankan pembelajaran; admin menerbitkan sertifikat.

## 2. Struktur Folder

    app/                    Model, controller inti/auth, middleware, service, observer, policy
    bootstrap/              Inisialisasi aplikasi
    config/                 Konfigurasi Laravel, database, auth, CAS, Midtrans, dll.
    database/               Factory, migration, seeder, dump balai_kursus.sql
    Modules/                Absensi, Instruktur, Kursus, Level, Pendaftaran, Peserta,
                            Program, Risalah
    public/                 Entry point, logo, asset sertifikat
    resources/views/        View inti, auth, admin, profil, sertifikat, publik
    resources/css,js/       Asset frontend
    routes/                 Route global, auth, API, channel, console
    tests/                  Feature test dan unit test
    docs/                   Dokumentasi dan screenshot

Setiap modul mengikuti pola Config, Database, Http/Controllers, Providers, Resources/views, dan Routes. View modul memakai namespace seperti kursus::admin.dashboard.index dan peserta::dashboard.index.

## 3. Framework, Library, Teknologi

- PHP 8.1+ dan Laravel 10.10.
- Eloquent ORM, Blade, Laravel Breeze, Laravel Sanctum, session authentication.
- nwidart/laravel-modules 10.0 untuk modularisasi.
- MySQL sebagai koneksi database default.
- Guzzle untuk HTTP request dan subfission/cas untuk CAS/SSO.
- midtrans/midtrans-php untuk Midtrans Snap.
- barryvdh/laravel-dompdf untuk PDF dan endroid/qr-code untuk QR sertifikat.
- maatwebsite/excel untuk export peserta dan nilai.
- Vite, Tailwind CSS, Alpine.js, Axios, PostCSS, Autoprefixer.
- PHPUnit 10, Faker, Laravel Pint, Sail, Mockery, Collision, Ignition.
- Konfigurasi dasar: timezone UTC, locale en, session file, queue sync, filesystem local.

## 4. Seluruh Route

### Route global

- GET /: redirect anonim ke login atau user login ke /redirect.
- GET /papan-informasi: papan informasi publik.
- GET /instruktur/kursus/{kursus}/nilai/export: export nilai.
- GET/PATCH /profile: form dan update profil.
- GET /profile/certificates: daftar sertifikat user.
- GET /profile/certificates/{id}: detail sertifikat.
- GET /profile/certificates/{id}/download dan GET /certificate/{id}/download: download.
- GET /redirect: admin ke /admin/dashboard, instruktur ke /instruktur/dashboard, peserta ke /peserta/dashboard.
- POST /peserta/pendaftaran/{pendaftaran}/create-payment: membuat pembayaran.
- GET /verify/{code}: verifikasi sertifikat publik.

Group admin pada prefix /admin dan middleware auth, admin menyediakan export score/peserta, CRUD sertifikat, batch create/store/publish, peserta eligible, preview/publish/revoke/restore draft, dan CRUD template sertifikat. Analytics hanya didaftarkan jika controller analytics tersedia; file controller tersebut tidak ditemukan.

### Authentication

Guest: GET/POST /login, GET/POST /register, GET/POST /forgot-password, GET/POST /reset-password/{token} dan /reset-password, GET /login/google, GET /auth/google/callback, GET /login/cas, GET /logout/cas.

Auth: DELETE /profile, GET /verify-email, GET /verify-email/{id}/{hash} dengan signed dan throttle, POST /email/verification-notification dengan throttle, GET/POST /confirm-password, PUT /password, POST /logout.

### API

- GET /api/user dengan auth:sanctum.
- POST /api/payment/create.
- POST /api/payment/notification.
- GET /api/payment/status/{orderId}.
- API memakai throttle dan route model binding. Webhook aktif juga tersedia pada POST /peserta/pembayaran-notification tanpa auth untuk panggilan Midtrans.

### Modul Instruktur

Prefix /instruktur, middleware auth dan role:instruktur: dashboard; daftar/detail kursus; GET/POST /risalah/{risalah}/absensi; daftar/edit/update risalah; daftar dan resource nilai; jadwal read-only. Prefix /admin/instruktur, middleware auth dan admin, menyediakan resource instruktur kecuali show. GET /instruktur/risalah/{risalah}/download tersedia bagi user login.

### Modul Kursus

Dengan auth dan admin pada prefix /admin: dashboard; resource kursus; peserta, risalah, absensi per kursus; CRUD jadwal per kursus; listing seluruh jadwal/risalah/absensi; resource score plus show; resource lokasi, kelas, hari; GET/POST /kursus/{kursus}/peserta/{pendaftaran}/assign-level.

### Modul Level, Program, Peserta

- /admin/level: resource CRUD level kecuali show.
- /admin/program: resource CRUD program kecuali show dan GET /admin/program/{program}/levels.
- /peserta/dashboard: dashboard.
- /peserta/program, /peserta/program/{program}, POST /peserta/program/{program}/daftar: katalog/detail/daftar program.
- /peserta/kursus, /peserta/kursus/saya, /peserta/kursus/{kursus}, /peserta/kursus/{kursus}/detail, /peserta/kursus/{kursus}/risalah, POST /peserta/kursus/{kursus}/daftar.
- /peserta/pendaftaran: daftar pendaftaran.
- POST /peserta/pembayaran-online/{pendaftaran}, callback pembayaran success/failed, /peserta/riwayat-pembayaran.
- /admin/peserta: resource CRUD peserta kecuali show dan export peserta.
- /pendaftaran mengarahkan ke /peserta/pendaftaran; /risalah dan /absensi adalah route scaffold lama.
- Channel App.Models.User.{id} hanya untuk user dengan ID yang sama; console route hanya command inspire.

## 5. Controller dan Fungsinya

### Controller inti

- InformationBoardController: papan informasi publik dan status relatif jadwal.
- PaymentController: membuat payment, notifikasi Midtrans, cek status, callback sukses/gagal, sinkronisasi pendaftaran.
- ProfileController: tampil/update profil dan hapus akun; view instruktur/peserta dibedakan.
- CertificateController: CRUD, batch, peserta eligible, preview PDF, publish, revoke, restore draft, verifikasi, download.
- Admin/CertificateTemplateController: CRUD template dan template aktif.
- UserCertificateController: daftar, detail, download sertifikat milik user.

### Authentication

- AuthenticatedSessionController: login/logout.
- RegisteredUserController: registrasi user, profil peserta, nomor peserta.
- GoogleLoginController: OAuth Google manual dengan Guzzle, buat/temukan user, login.
- CasLoginController: redirect, validasi identitas, login/logout CAS.
- ConfirmablePasswordController: konfirmasi password.
- EmailVerificationPromptController, EmailVerificationNotificationController, VerifyEmailController: verifikasi email.
- PasswordResetLinkController, NewPasswordController, PasswordController: reset dan update password.

### Kursus

- Admin/DashboardController: metrik peserta, instruktur, program, kursus, pendaftaran, payment bulanan.
- Admin/KursusController: CRUD kursus, peserta, risalah, absensi, listing global, assignment level.
- Admin/JadwalController: CRUD jadwal, listing global, validasi bentrok, sinkronisasi risalah.
- Admin/ScoreController: CRUD/search nilai, pengolahan nilai, export.
- Admin/LokasiController, Admin/KelaController, Admin/HariController: CRUD master lokasi, ruang, hari.

### Modul lain

- Instruktur/DashboardController: dashboard instruktur.
- Instruktur/AbsensiController: kursus, detail, jadwal, form/simpan absensi.
- Instruktur/RisalahController: daftar, edit, update, download risalah.
- Instruktur/NilaiController: daftar, create, show, update, delete, export nilai.
- Instruktur/Admin/InstrukturController: CRUD instruktur dan user terkait dalam transaction.
- Peserta/DashboardController: ringkasan pendaftaran.
- Peserta/ProgramController: katalog, detail, daftar program.
- Peserta/KursusController: kursus saya, detail, risalah, daftar kursus.
- Peserta/PendaftaranController: daftar pendaftaran login.
- Peserta/RiwayatController: riwayat payment.
- Peserta/PembayaranController: adapter/legacy payment dan webhook.
- Peserta/Admin/PesertaController: CRUD/export peserta dan user terkait.
- Program/Admin/ProgramController: CRUD program dan endpoint level program.
- Level/Admin/LevelController: CRUD level.

Controller generik root modul Kursus, Program, Level, Peserta, Pendaftaran, Risalah, dan Instruktur adalah scaffold nwidart lama. Route aktif memakai controller spesifik di atas.

## 6. Model dan Relasi Eloquent

| Model/tabel | Relasi dan peran |
|---|---|
| User/users | hasOne Peserta, hasOne Instruktur, hasMany TrustedDevice; akun auth. |
| Peserta/pesertas | belongsTo User, hasMany Pendaftaran dan PesertaKursusLevel. |
| Instruktur/instrukturs | belongsTo User, hasMany Kursus dan InstrukturKursusLevel. |
| Program/programs | hasMany Kursus/Pendaftaran; Level tidak langsung melalui kursus. |
| Level/levels | hasMany Kursus/Pendaftaran/pivot; matchesScore dan ordered. |
| Kursus/kursuses | belongsTo Program/Level; hasMany Pendaftaran/Risalah/Jadwal/pivot; openForRegistration. |
| Pendaftaran/pendaftarans | belongsTo Peserta/Program/Level/Kursus; hasMany Payment/Absensi/Score; helper status/payment. |
| PesertaKursusLevel | assignment peserta-kursus-level; unik peserta-kursus. |
| InstrukturKursusLevel | assignment instruktur-kursus-level; unik instruktur-kursus-level. |
| Jadwal/jadwals | belongsTo Kursus/Lokasi/Kela/Hari/User creator; conflictingSlot. |
| Hari/haris | hasMany Jadwal. |
| Lokasi/lokasis | hasMany Jadwal. |
| Kela/kelas | ruang fisik yang digunakan Jadwal. |
| Risalah/risalahs | belongsTo Kursus/Instruktur/Jadwal; hasMany Absensi. |
| Absensi/absensis | belongsTo Risalah/Pendaftaran; unik risalah-pendaftaran. |
| Score/scores | belongsTo Pendaftaran/Instruktur; jenis placement/course dan komponen nilai. |
| Payment/payments | belongsTo User/Pendaftaran; scope pending/success/failed. |
| Certificate/certificates | belongsTo Template/Kursus/Peserta/User; draft/published/revoked dan snapshot. |
| CertificateTemplate | hasMany Certificate; scope active. |
| TrustedDevice | belongsTo User; token device hashed. |

Tabel peserta_kursus masih ada dari desain legacy, tetapi model aktif menggunakan peserta_kursus_levels dan tidak mendefinisikan belongsToMany untuk tabel legacy itu.

## 7. Migration Database

### Migration dasar

2014_10_12_000000_create_users_table; 2014_10_12_100000_create_password_reset_tokens_table; 2019_08_19_000000_create_failed_jobs_table; 2019_12_14_000001_create_personal_access_tokens_table; 2025_02_06_000001_create_payments_table; 2026_01_30_000001_add_role_to_users_table; 2026_01_30_063244_create_pesertas_table; 2026_01_30_063245_create_instrukturs_table; 2026_01_30_063247_create_programs_table; 2026_01_30_063248_create_levels_table; 2026_01_30_063249_create_kursuses_table; 2026_01_30_063251_create_pendaftarans_table; 2026_01_30_063254_create_risalahs_table; 2026_01_30_063255_create_absensis_table.

### Migration fitur dan refactor

- 2026_02_04: create jadwals, add jadwal_id to risalahs, create peserta_kursus.
- 2026_02_10: create lokasis, kelas, scores, haris; add lokasi/kela/hari to jadwals; add periode/harga_upi/instruktur_id_2 to kursuses; add nomor to pendaftarans; legacy score fields; performance indexes.
- 2026_03_02: tanggal mulai/selesai kursus; percobaan level enum; revert ke level_id; perbaikan kolom dan struktur levels.
- 2026_03_10: create certificates, add status, add user_id.
- 2026_04_01 dan 2026_04_02: pivot peserta/instruktur-kursus-level dan FK level kursus.
- 2026_04_05_restructure_program_registration_flow: rentang level, jenis score, program/level/status pendaftaran, nullable kursus, migrasi data lama.
- 2026_04_07_drop_legacy_pembayarans_table: menghapus pembayaran manual legacy.
- 2026_04_14: jam pelajaran; certificate_templates; refactor sertifikat dengan template, nomor, serial, tanggal, PDF, snapshot; image path nullable.
- 2026_04_21: snapshot email pendaftar dan trusted_devices.

## 8. Schema dan Relasi Tabel

| Tabel | Field penting |
|---|---|
| users | name, email, password, role, email_verified_at |
| pesertas | user_id, nomor_peserta, no_hp, instansi |
| instrukturs | user_id, nama_instr, spesialisasi |
| programs | nama, warna |
| levels | nama, warna, urutan, nilai_min, nilai_max, deskripsi |
| kursuses | program/level, nama, periode/tanggal, jam_pelajaran, harga, harga_upi, kuota, status buka/tutup/berjalan |
| pendaftarans | nomor, peserta/program/level/kursus, status pendaftaran/pembayaran, total/terbayar, catatan, klasifikasi, snapshot email |
| jadwals | kursus, pertemuan, tanggal, waktu, lokasi, kelas, hari, pembuat |
| risalahs | kursus, instruktur, jadwal, pertemuan, tanggal, materi, catatan, dokumen |
| absensis | risalah, pendaftaran, status H/S/I/A, jam datang, catatan |
| scores | pendaftaran, jenis, komponen nilai, final_score, instruktur, catatan |
| payments | order, amount, customer, status, method, transaction ID, JSON response, user, pendaftaran |
| certificates | template, nomor/serial/tanggal, file, kursus, peserta, user, status, snapshot |
| certificate_templates | institusi, asset visual, penandatangan, prefix, aktif |

Relasi utama: users 1-1 pesertas; users 1-1 instrukturs; peserta 1-N pendaftaran; program 1-N kursus dan pendaftaran; kursus N-1 level; kursus 1-N jadwal/risalah; risalah 1-N absensi; pendaftaran 1-N payment/score/absensi; certificate_templates 1-N certificates; peserta dan kursus 1-N certificates; user 1-N trusted_devices. Jadwal masing-masing N-1 lokasi, kelas, dan hari. Pivot peserta_kursus_levels dan instruktur_kursus_levels menghubungkan peserta/instruktur dengan kursus-level.

Foreign key menggunakan cascade pada profil/pivot tertentu dan set null pada kursus/level pendaftaran, template sertifikat, serta user payment sesuai migration/dump. Beberapa relasi payment-to-pendaftaran tidak memiliki FK eksplisit pada dump sehingga integritasnya terutama ditangani aplikasi.

## 9. Blade/View

Terdapat 48 view pada resources/views dan 79 view pada Modules/*/Resources/views.

- resources/views/auth: login, register, forgot/reset password, confirm password, verify email.
- resources/views/layouts dan components: layout admin/app/bootstrap/guest/navigation, tombol, input, dropdown, modal, logo, error.
- resources/views/admin/certificates dan certificate-templates: CRUD, batch, partial form, preview/PDF.
- resources/views/certificates, user/certificates, profile: verifikasi publik, daftar/detail/download sertifikat, profil.
- resources/views/public: papan informasi.
- View Kursus: dashboard admin, kursus, jadwal, lokasi, kelas, hari, peserta, risalah, absensi, score.
- View Peserta: dashboard, program, kursus, pendaftaran, riwayat payment, admin peserta.
- View Instruktur: dashboard, kursus, jadwal, absensi, risalah, nilai, admin instruktur.
- View Level/Program: CRUD admin.
- View Absensi/Pendaftaran/Risalah: scaffold/legacy.

View memakai layout Blade, route name, CSRF, validation error, flash message, pagination, dan method spoofing PUT/PATCH/DELETE.

## 10. Middleware dan Authorization

Global: TrustProxies, CORS, maintenance mode, validasi ukuran POST, TrimStrings, ConvertEmptyStringsToNull.

Group web: encrypted cookies, queued cookies, session, share validation errors, CSRF, substitute bindings. Group api: throttle dan substitute bindings.

Alias: auth (session), guest, role (CheckRole), admin (AdminMiddleware), verified, signed, throttle. CheckRole menghasilkan 401 untuk user anonim dan 403 untuk role yang tidak sesuai. AdminMiddleware hanya mengizinkan user dengan role admin. JadwalPolicy membatasi view/create/update/delete jadwal kepada admin dan memiliki before hook; JadwalController menggunakan authorizeResource. Form web dilindungi CSRF.

## 11. Authentication

Guard default web berbasis session dengan provider Eloquent App\Models\User. User memakai HasApiTokens, HasFactory, Notifiable; password dicast hashed.

Metode login: email/password Breeze; registrasi lokal sekaligus membuat profil peserta; Google OAuth manual memakai Guzzle; CAS/SSO. Fitur pendukung: verifikasi email, reset password, konfirmasi/update password, logout, trusted device.

TrustedDeviceManager menyimpan hash token, user agent, IP, dan waktu terakhir dipakai pada trusted_devices. Cookie trusted_login menggunakan konfigurasi HttpOnly/SameSite dan token plaintext tidak disimpan di database.

## 12. Dashboard

- Admin: metrik peserta, instruktur, program, kursus, pendaftaran, serta agregat pembayaran per bulan.
- Instruktur: kursus yang terkait dengan instruktur serta akses ke jadwal, absensi, risalah, dan nilai.
- Peserta: pendaftaran milik peserta login serta akses program, kursus, payment, riwayat, profil, dan sertifikat.

## 13. Semua Fitur CRUD

| Entitas | Admin | Instruktur | Peserta |
|---|---|---|---|
| Program | CRUD | - | Read dan daftar |
| Level | CRUD kecuali show | - | Read melalui program/kursus |
| Kursus | CRUD, peserta, placement manual, risalah/absensi | Read terkait | Read kursus/risalah |
| Jadwal | CRUD + validasi bentrok | Read-only | Read |
| Lokasi/Kelas/Hari | CRUD | - | - |
| Peserta | CRUD dan export | Read terkait | Profil sendiri |
| Instruktur | CRUD | Profil sendiri | - |
| Pendaftaran | Proses placement/assignment | Read | Create/read status |
| Payment | Pantau status/agregat | - | Create, callback, riwayat |
| Risalah/Absensi | Read global | Edit risalah, input absensi | Read |
| Score | CRUD/export | CRUD/export sesuai kursus | Read |
| Sertifikat/Template | CRUD, batch, publish/revoke | - | Read/download sendiri |

Resource route Laravel memperluas CRUD standar menjadi index, create, store, edit, update, destroy, dan show sesuai pengecualian pada route.

## 14. Proses Bisnis

### Pendaftaran

Peserta memilih program dan membuat Pendaftaran. Model mengisi nomor REG-..., status menunggu_tes, pembayaran pending, total 0, terbayar 0, dan snapshot email.

### Placement otomatis

Score berjenis placement disimpan. ScoreObserver menjalankan PendaftaranPlacementService, yang mencocokkan final_score dengan nilai_min/nilai_max Level, memilih kursus pada program/level berstatus buka atau berjalan yang belum penuh, mengisi level/kursus/biaya/status, dan membuat PesertaKursusLevel. Jika penuh, status menjadi menunggu_penempatan. Penghapusan score placement mereset assignment ke menunggu_tes.

### Pembayaran

Pendaftaran dapat dibayar jika sudah memiliki kelas, biaya > 0, dan belum lunas. Aplikasi menyimpan payment pending, membuat transaksi/Snap token Midtrans, menerima webhook, memverifikasi status, lalu memperbarui payment dan status agregat pendaftaran: pending, dp, cicil, lunas.

### Pembelajaran dan penilaian

Admin membuat jadwal berdasarkan kursus, waktu, lokasi, kelas, dan hari; sistem memeriksa overlap lokasi/waktu. Instruktur mengisi risalah/materi dan absensi. Score dibedakan menjadi placement dan course serta dapat memiliki listening, speaking, reading, writing, final score, dan catatan.

### Sertifikat

Admin memilih template, kursus, dan peserta eligible. Sistem membuat draft dengan nomor/serial, tanggal, PDF, serta snapshot peserta, program, kursus, durasi, tanggal, kota, dan penandatangan. Admin dapat preview, publish, revoke, restore draft, atau hapus draft. Peserta hanya dapat melihat/download status published; /verify/{code} melayani verifikasi publik.

## 15. Alur Penggunaan

### Peserta

Login/registrasi lokal, Google, atau CAS -> dashboard -> pilih program -> kirim pendaftaran -> tes placement -> sistem menentukan level dan kelas -> pembayaran Midtrans -> kursus/jadwal/materi/absensi/nilai -> admin menerbitkan sertifikat -> peserta download/verifikasi.

### Admin

Login -> dashboard -> siapkan program, level, lokasi, kelas, hari -> buat kursus/jadwal -> kelola peserta/instruktur -> proses score placement dan payment -> pantau jadwal, risalah, absensi, nilai -> kelola template dan sertifikat.

### Instruktur

Login -> dashboard -> kursus -> jadwal -> risalah/materi -> absensi -> nilai -> export/rekap.

## 16. Struktur Menu

- Admin: Dashboard, Program, Level, Kursus, Jadwal, Peserta, Instruktur, Lokasi, Ruang/Kelas, Hari, Nilai, Risalah, Absensi, Sertifikat, Template Sertifikat, Profil, Logout.
- Instruktur: Dashboard, Kursus Saya, Jadwal, Risalah, Absensi, Nilai, Profil, Logout.
- Peserta: Dashboard, Program/Katalog, Kursus Saya, Pendaftaran Saya, Pembayaran, Riwayat Pembayaran, Sertifikat Saya, Profil, Logout.
- Publik: Login/registrasi, Papan Informasi, Verifikasi Sertifikat.

Nama menu dipetakan dari route dan view. Route scaffold/legacy bukan entry point utama.

## 17. Service, Observer, Policy, Export

- PendaftaranPlacementService: penentuan level/kursus dari score dan kuota, termasuk reset.
- MidtransService: konfigurasi Midtrans, Snap token/redirect, status, approve, cancel, refund, deny.
- TrustedDeviceManager: token device tepercaya secara hashed.
- ScoreObserver: placement otomatis saat score disimpan dan reset saat dihapus.
- KursusObserver: saat ini tidak melakukan otomasi; otomasi template lama telah dihapus.
- NilaiExport dan PesertaExport: export Excel.
- JadwalPolicy: authorization operasi jadwal.

## 18. Temuan Teknis

1. Project memakai arsitektur gabungan app dan 8 modul nwidart; masih terdapat scaffold generik.
2. Flow aktif adalah program -> placement -> level/kursus -> payment; route legacy hanya redirect/index sederhana.
3. payments adalah tabel aktif; pembayarans dihapus oleh migration legacy.
4. peserta_kursus dan kolom instruktur_id_2 merupakan jejak desain lama; assignment aktif memakai pivot level.
5. Route analytics sertifikat kondisional karena controller analytics tidak ditemukan.
6. Status sertifikat aktif draft, published, revoked; migration awal pernah memakai pending.
7. Snapshot email pendaftar dan snapshot sertifikat menjaga histori saat profil/template berubah.
8. Timezone UTC dan locale en perlu diperhatikan karena UI/dokumentasi banyak berbahasa Indonesia.
9. File .env tersedia untuk lokal; credential tidak disalin ke dokumen.

## 19. Kesimpulan

Project ini adalah platform manajemen kursus bahasa berbasis Laravel dengan tiga role. Fitur terintegrasi utamanya adalah pendaftaran-program-level, placement otomatis berbasis score, penempatan kelas berkuota, pembayaran Midtrans, operasional pembelajaran, penilaian, dan sertifikat PDF yang dapat diverifikasi. Untuk pengembangan berikutnya, route aktif dan controller spesifik modul perlu diprioritaskan dibanding controller scaffold legacy.
