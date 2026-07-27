# Hasil Review Laporan PKL terhadap Project Balai Kursus

## Ruang lingkup review

Review ini membandingkan isi `LAPORAN KEGIATAN PKL RPL (UPI) - RAPI.docx` dengan `ANALISIS_PROJECT.md` dan struktur source code yang dirujuk oleh analisis tersebut. Review bersifat statis/read-only. File Word tidak diubah.

Penilaian istilah:

- **Sudah benar**: isi laporan selaras dengan implementasi project.
- **Kurang/lebih**: isi masih perlu dilengkapi, dipersempit, atau diberi penanda konteks agar tidak menyesatkan.
- **Salah**: isi bertentangan dengan implementasi project atau menyatakan fitur yang tidak ditemukan.

## 1. Bagian yang sudah benar

### 1.1 Identitas dan teknologi utama

Laporan menyebut pembangunan ulang aplikasi Balai Kursus berbasis web dengan Laravel 10. Ini sesuai dengan `composer.json` dan analisis project yang mencatat PHP 8.1+, Laravel 10.10, MySQL, Blade, Eloquent, Vite, Tailwind CSS, Alpine.js, Composer, dan Laragon.

Laporan juga menyebut `nwidart/laravel-modules`, Laravel Breeze, Laravel Sanctum, Dompdf, Maatwebsite Excel, Midtrans, CAS, Guzzle, Git, dan GitHub. Sebagian besar dependency tersebut memang tercantum di `composer.json` atau `package.json`, sehingga bagian daftar teknologi sudah berada pada arah yang benar.

### 1.2 Tiga role pengguna

Laporan konsisten menyebut tiga role: Admin, Instruktur, dan Peserta. Ini sesuai dengan field `users.role`, middleware role/admin, route dashboard, serta pembagian akses pada project.

### 1.3 Modul administrasi dan master data

Uraian tentang Program, Level, Kursus, Peserta, Instruktur, Lokasi, Kelas/Ruang, Hari, dan Jadwal sesuai dengan route, controller, view, model, dan tabel yang dianalisis. Laporan juga tepat ketika menjelaskan bahwa Admin menjadi pengelola utama data master.

### 1.4 Penjadwalan

Laporan menyebut jadwal memiliki tanggal/pertemuan, jam, lokasi, ruang kelas, dan hari, serta bahwa sistem mencegah bentrok jadwal. Hal ini sesuai dengan model `Jadwal`, `JadwalPolicy`, `Admin/JadwalController`, relasi ke lokasi/kelas/hari, dan feature test konflik jadwal.

### 1.5 Risalah, absensi, dan nilai

Uraian tentang instruktur yang mengisi risalah, materi, catatan, dokumen, absensi dengan status Hadir/Sakit/Izin/Alpa, serta penilaian listening/speaking/reading/writing dan nilai akhir sesuai dengan model, controller, view, dan tabel `risalahs`, `absensis`, dan `scores`.

### 1.6 Alur placement dan penempatan kelas

Laporan telah menangkap adanya level, rentang nilai, penempatan peserta, serta keterkaitan peserta dengan kursus. Ini sesuai dengan `ScoreObserver`, `PendaftaranPlacementService`, model `Level`, status pendaftaran, kuota kursus, dan pivot aktif `peserta_kursus_levels`.

### 1.7 Pembayaran Midtrans

Pada bagian implementasi dan pembahasan peserta, laporan menjelaskan pembayaran online, halaman berhasil/gagal, webhook/notifikasi, dan perubahan status pembayaran. Uraian ini sesuai dengan `PaymentController`, `MidtransService`, route API/webhook, model `Payment`, dan status pendaftaran `pending`, `dp`, `cicil`, dan `lunas`.

### 1.8 Sertifikat

Laporan benar dalam menyebut adanya template sertifikat, penerbitan satuan/batch, draft, preview, publish, revoke, restore draft, PDF, dan verifikasi publik melalui kode/QR. Fitur tersebut ditemukan pada `CertificateController`, `CertificateTemplateController`, `UserCertificateController`, route verifikasi, model `Certificate`, dan status aktif `draft`, `published`, `revoked`.

### 1.9 Struktur tabel inti

Sebagian besar daftar 20 tabel inti dan uraian kolom/relasinya sudah cocok dengan schema yang dianalisis: `users`, `pesertas`, `instrukturs`, `programs`, `levels`, `kursuses`, `lokasis`, `kelas`, `haris`, `jadwals`, `pendaftarans`, `payments`, `peserta_kursus_levels`, `instruktur_kursus_levels`, `risalahs`, `absensis`, `scores`, `certificate_templates`, dan `certificates`.

### 1.10 Alur umum per role

Alur Admin, Instruktur, dan Peserta pada bagian pembahasan cukup representatif: Admin menyiapkan data dan menerbitkan sertifikat, Instruktur mengelola pembelajaran/absensi/nilai, sedangkan Peserta mendaftar, membayar, melihat kursus, dan mengunduh sertifikat.

## 2. Bagian yang kurang atau lebih

### 2.1 Batasan masalah terlalu sempit

Pada Bab I, laporan membatasi cakupan hanya pada data peserta, jadwal, dan instruktur. Implementasi aktual juga mencakup program, level, kursus/batch, pendaftaran, placement, pembayaran, risalah, absensi, nilai, template sertifikat, sertifikat, autentikasi, dan verifikasi sertifikat.

**Alasan perbaikan:** batasan masalah harus menggambarkan ruang lingkup pekerjaan yang benar-benar dibahas. Jika dibiarkan, pembaca akan mengira sebagian besar fitur pada Bab IV berada di luar proyek.

### 2.2 Penjelasan alur bisnis belum cukup rinci

Laporan menjelaskan pendaftaran dan pembayaran, tetapi belum menerangkan alur aktif secara lengkap: program -> pendaftaran `menunggu_tes` -> score placement -> pencocokan rentang level -> pencarian kursus berdasarkan kuota -> penetapan kelas -> pembayaran -> pembelajaran -> nilai -> sertifikat.

**Alasan perbaikan:** alur ini adalah pembeda utama project. Penjelasan yang lebih lengkap akan menghubungkan tabel, fitur, dan proses bisnis sehingga laporan tidak terlihat hanya sebagai kumpulan halaman CRUD.

### 2.3 Placement otomatis belum ditegaskan

Laporan menyebut level dan penempatan, tetapi belum menjelaskan bahwa `ScoreObserver` memicu `PendaftaranPlacementService`, mencocokkan `final_score` dengan `nilai_min`/`nilai_max`, memeriksa kursus yang terbuka/berjalan dan kuota, lalu dapat menghasilkan status menunggu penempatan.

**Alasan perbaikan:** tanpa keterangan ini, kontribusi logika bisnis inti project menjadi kurang terlihat dan pembaca dapat mengira penempatan seluruhnya manual.

### 2.4 Otorisasi dan keamanan perlu diperdalam

Laporan menyebut keamanan dan autentikasi secara umum, tetapi belum menjelaskan middleware `auth`, `role`, `admin`, `verified`, `signed`, CSRF, `JadwalPolicy`, pembatasan akses peserta terhadap sertifikat miliknya, serta trusted device.

**Alasan perbaikan:** rumusan masalah laporan memasukkan keamanan/validasi. Karena itu, hasil implementasi perlu menunjukkan mekanisme nyata yang menjawab rumusan tersebut, bukan hanya menyebut login.

### 2.5 Status dan aturan pendaftaran perlu dijelaskan

Laporan memuat status pembayaran, tetapi belum membedakan status pembayaran dari `status_pendaftaran`, misalnya `menunggu_tes`, `menunggu_penempatan`, `menunggu_pembayaran`, `aktif`, `selesai`, dan `dibatalkan`.

**Alasan perbaikan:** dua kelompok status tersebut memiliki fungsi berbeda. Menyatukannya akan menyulitkan pembaca memahami kapan peserta dapat membayar, mengikuti kursus, atau menjadi kandidat sertifikat.

### 2.6 Relasi instruktur pada kursus perlu diberi konteks legacy

Tabel `kursuses` dalam laporan menonjolkan `instruktur_id_2` sebagai relasi instruktur pengampu. Analisis project menyatakan field tersebut adalah jejak desain lama; assignment aktif menggunakan `instruktur_kursus_levels`.

**Alasan perbaikan:** field tersebut boleh tetap didokumentasikan karena masih ada di schema, tetapi harus diberi label legacy/non-utama. Jika tidak, laporan memberi kesan bahwa field itu adalah sumber penugasan instruktur yang dipakai oleh alur aktif.

### 2.7 Tabel `peserta_kursus` perlu diberi label legacy

Laporan memasukkan `peserta_kursus` sebagai pivot peserta-kursus aktif. Analisis project menyatakan tabel tersebut masih tersisa dari desain lama; model aktif menggunakan `peserta_kursus_levels` dan tidak mendefinisikan relasi many-to-many aktif ke tabel legacy itu.

**Alasan perbaikan:** pembaca perlu dapat membedakan tabel yang masih ada secara historis dari tabel yang dipakai fitur berjalan. Ini penting untuk menghindari kesalahan saat membaca schema atau melakukan pengembangan lanjutan.

### 2.8 Pembahasan database terlalu dominan dan berulang

Bab IV menguraikan 20 tabel dengan format hampir sama, sementara controller, service, observer, policy, route, dan alur bisnis belum dibahas sedalam itu.

**Alasan perbaikan:** detail tabel tetap berguna sebagai lampiran atau dokumentasi teknis, tetapi laporan kegiatan akan lebih seimbang jika ruang diberikan untuk proses kerja, implementasi logika, validasi, pengujian, dan hasil.

### 2.9 Beberapa penjelasan tool lebih bersifat generik daripada bukti proyek

Penjelasan Visual Studio Code, Git, GitHub, Chrome, phpMyAdmin, dan beberapa library berisi uraian fungsi umum yang panjang, tetapi tidak selalu menyebut penggunaannya yang dapat diverifikasi pada project.

**Alasan perbaikan:** laporan PKL sebaiknya membedakan “fungsi umum alat” dari “bagaimana alat dipakai pada project”. Ini membuat laporan lebih faktual dan mengurangi klaim yang tidak didukung source code atau bukti kegiatan.

### 2.10 Bagian pengujian belum tampak dalam laporan yang dibaca

Analisis project menemukan banyak feature test, termasuk autentikasi, otorisasi admin, pendaftaran, pembayaran webhook, sertifikat, konflik jadwal, dashboard, dan manajemen instruktur. Bagian laporan yang diekstrak terutama menampilkan deskripsi fitur dan screenshot, belum menampilkan skenario, data uji, hasil, dan status lulus/gagal.

**Alasan perbaikan:** hasil pengujian merupakan bukti bahwa fitur benar-benar berjalan. Tanpa tabel atau narasi pengujian, kesimpulan implementasi sulit diverifikasi.

## 3. Bagian yang salah atau bertentangan

### 3.1 Pernyataan bahwa project tidak mencakup pembayaran daring

Pada **Batasan Masalah**, laporan menyatakan: sistem tidak mencakup modul/proses pembayaran daring dan keuangan dilakukan di luar sistem. Ini bertentangan langsung dengan project.

Project memiliki:

- `PaymentController` dan `MidtransService`;
- tabel `payments`;
- route pembuatan pembayaran, callback berhasil/gagal, status, dan webhook/notifikasi;
- integrasi `midtrans/midtrans-php`;
- view pembayaran dan riwayat pembayaran;
- feature test payment webhook.

**Alasan perbaikan:** kontradiksi ini adalah kesalahan substantif. Batasan harus diubah menjadi, misalnya, “pembayaran daring dibatasi pada integrasi Midtrans dan pemutakhiran status pembayaran; rekonsiliasi keuangan/akuntansi di luar ruang lingkup.”

### 3.2 Klaim adanya analitik penerbitan sertifikat

Pada pembahasan Manajemen Sertifikat, laporan menyatakan Admin dapat memantau analitik penerbitan sertifikat. `routes/web.php` memang memiliki pendaftaran route analitik secara kondisional, tetapi analisis project menyatakan `CertificateAnalyticsController` tidak ditemukan sehingga route tersebut tidak aktif.

**Alasan perbaikan:** laporan tidak boleh menyatakan fitur sebagai tersedia jika controller dan implementasi aktifnya tidak ada. Kalimat tersebut sebaiknya dihapus atau diubah menjadi catatan “route analitik disiapkan secara kondisional, tetapi belum tersedia pada source saat review.”

### 3.3 Klaim bahwa Dompdf digunakan untuk slip/kartu pendaftaran

Pada bagian library, laporan menyatakan `barryvdh/laravel-dompdf` digunakan untuk mencetak bukti pendaftaran/slip atau kartu peserta. Berdasarkan analisis source, penggunaan yang teridentifikasi adalah pembuatan/unduhan PDF sertifikat melalui controller/view sertifikat.

**Alasan perbaikan:** fungsi library harus dikaitkan dengan penggunaan aktual. Klaim slip/kartu pendaftaran perlu dihapus atau didukung dengan route, controller, view, dan screenshot yang benar-benar ada.

### 3.4 Klaim bahwa QR Code digunakan pada bukti pendaftaran untuk presensi

Pada bagian `endroid/qr-code`, laporan menyebut QR Code dibuat pada bukti pendaftaran sebagai validasi data peserta saat presensi. Analisis project mendokumentasikan QR/kode unik untuk verifikasi sertifikat publik, bukan QR pada bukti pendaftaran untuk presensi.

**Alasan perbaikan:** objek dan tujuan QR Code harus tepat. Klaim yang benar adalah QR/kode verifikasi digunakan untuk memverifikasi sertifikat.

### 3.5 Klaim impor Excel dan impor data massal

Laporan menyebut Maatwebsite Excel digunakan untuk ekspor dan impor data massal. Analisis project menemukan `NilaiExport` dan `PesertaExport`, sedangkan fitur impor aktif tidak tercatat.

**Alasan perbaikan:** kata “impor” menyatakan kapabilitas yang memerlukan endpoint, handler, validasi, dan alur UI. Jika komponen tersebut tidak ada, klaim harus dibatasi menjadi ekspor Excel.

### 3.6 Klaim bahwa Axios mengirim formulir pendaftaran dan mengambil informasi secara real-time

Laporan menyatakan Axios digunakan untuk pengiriman formulir dan pengambilan informasi real-time. Dependency Axios memang ada, tetapi analisis route/controller tidak menunjukkan bahwa seluruh alur pendaftaran berjalan sebagai AJAX real-time.

**Alasan perbaikan:** keberadaan dependency tidak otomatis membuktikan semua fungsi tersebut digunakan. Uraian harus menyebut penggunaan Axios yang spesifik dan dapat ditunjukkan, atau diubah menjadi “tersedia sebagai dependency frontend”.

### 3.7 Pernyataan bahwa Admin memiliki CRUD penuh untuk pembayaran

Laporan menggambarkan Admin mengelola pembayaran sebagai bagian dari operasi CRUD penuh. Project menyediakan pemantauan/agregasi status pembayaran dan pemrosesan melalui Midtrans; route yang dianalisis tidak menunjukkan resource CRUD Admin untuk membuat, mengedit, atau menghapus transaksi pembayaran secara bebas.

**Alasan perbaikan:** pembayaran adalah transaksi yang statusnya berasal dari proses gateway/webhook. Menyebutnya CRUD penuh dapat memberi gambaran keliru tentang kewenangan Admin dan integritas transaksi.

### 3.8 Pernyataan yang berpotensi menyesatkan tentang LMS

Bab I menyatakan project tidak menyediakan pengelolaan materi pembelajaran daring/LMS. Pernyataan ini benar jika maksudnya adalah tidak ada LMS penuh seperti modul materi, kuis, dan pembelajaran online. Namun laporan kemudian menyebut risalah, materi yang diajarkan, dokumen, absensi, dan nilai.

**Alasan perbaikan:** kalimat perlu dipersempit agar tidak terbaca bahwa project tidak memiliki fitur pembelajaran sama sekali. Formulasi yang tepat: project tidak menyediakan LMS/e-learning penuh, tetapi menyediakan administrasi pelaksanaan kursus, risalah, absensi, dan penilaian.

### 3.9 Klaim alur “pemilihan program dan level” oleh peserta

Pada pembahasan peserta, laporan menggambarkan peserta memilih program dan level sebelum penempatan kursus. Analisis project menggambarkan flow aktif program -> placement -> pencocokan level/kursus; level/kursus dapat diisi berdasarkan hasil placement dan ketersediaan kuota.

**Alasan perbaikan:** urutan ini menentukan perilaku aplikasi. Laporan harus membedakan pilihan program oleh peserta dari penentuan level/kursus oleh proses placement/admin agar tidak salah menjelaskan pengalaman pengguna.

## 4. Prioritas perbaikan

1. **Wajib diperbaiki:** hapus kontradiksi “tidak ada pembayaran daring”; koreksi klaim analitik sertifikat; koreksi fungsi QR Code, Dompdf, dan impor Excel.
2. **Penting dilengkapi:** jelaskan alur placement otomatis, status pendaftaran, otorisasi/keamanan, dan hasil pengujian.
3. **Perlu dirapikan:** beri label legacy pada `peserta_kursus` dan `instruktur_id_2`; kurangi uraian tool generik dan pindahkan detail schema berulang ke lampiran bila diperlukan.
4. **Tidak perlu mengubah substansi:** uraian role, modul CRUD/master data, jadwal, risalah, absensi, nilai, pembayaran, sertifikat, dan sebagian besar tabel inti sudah sesuai dengan project.

## Kesimpulan

Laporan sudah menggambarkan sebagian besar fitur yang benar-benar ada pada project, terutama modul role, master data, penjadwalan, pembelajaran, pembayaran, dan sertifikat. Namun laporan belum siap dianggap konsisten sepenuhnya karena terdapat satu kontradiksi besar pada batasan pembayaran serta beberapa overclaim pada analitik sertifikat, fungsi QR Code, Dompdf, impor Excel, Axios, dan CRUD pembayaran. Perbaikan paling penting adalah menyelaraskan Bab I dan uraian library dengan flow aktif project, lalu menambahkan bukti logika placement, keamanan, dan pengujian.
