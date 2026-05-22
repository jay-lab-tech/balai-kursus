# Dokumentasi Penggunaan - Balai Kursus

**Versi:** 1.0  
**Terakhir Diperbarui:** Mei 2026

---

## 📑 Daftar Isi

### Umum
1. [Pendahuluan](#pendahuluan)
2. [Login & Akun](#login--akun)
3. [Dashboard](#dashboard)

### Untuk Admin
4. [Panduan Admin](#panduan-admin)
5. [Manajemen Program & Kursus](#manajemen-program--kursus)
6. [Manajemen Peserta](#manajemen-peserta)
7. [Manajemen Instruktur](#manajemen-instruktur)
8. [Manajemen Jadwal & Kelas](#manajemen-jadwal--kelas)
9. [Sertifikat & Penerbitan](#sertifikat--penerbitan)

### Untuk Instruktur
10. [Panduan Instruktur](#panduan-instruktur)
11. [Input Absensi](#input-absensi)
12. [Input Nilai](#input-nilai)
13. [Catatan Pembelajaran](#catatan-pembelajaran)

### Untuk Peserta
14. [Panduan Peserta](#panduan-peserta)
15. [Pendaftaran Kursus](#pendaftaran-kursus)
16. [Pembayaran Online](#pembayaran-online)
17. [Tracking Progress](#tracking-progress)
18. [Download Sertifikat](#download-sertifikat)

### Bantuan
19. [FAQ](#faq)
20. [Troubleshooting](#troubleshooting)

---

## Pendahuluan

**Balai Kursus** adalah platform manajemen pembelajaran online yang dirancang khusus untuk mengelola program kursus bahasa. Platform ini memudahkan pengelolaan program bahasa dari pendaftaran hingga penerbitan sertifikat kompetensi bahasa.

**⚠️ Status:** Platform sedang dalam pengembangan dan berjalan di localhost.  
**📍 Akses:** http://localhost:8000

### Fitur Utama

✅ **Program Kursus Bahasa** - Kelola berbagai bahasa (Inggris, Mandarin, Jepang, dsb)  
✅ **Multi-Role** - Hak akses berbeda untuk Admin, Pengajar, dan Peserta  
✅ **Placement Test** - Penentuan level otomatis berdasarkan kemampuan  
✅ **Pembayaran Online** - Integrasi Midtrans untuk transaksi aman  
✅ **Tracking Real-time** - Monitor absensi, nilai, dan progress belajar  
✅ **Sertifikat Kompetensi** - Penerbitan sertifikat bahasa otomatis  

### Browser yang Didukung

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

---

## Login & Akun

### Cara Login

#### 1. Menggunakan Email & Password

```
1. Buka http://localhost:8000
2. Klik "Login" 
3. Masukkan email dan password
4. Klik "Masuk"
```

**Tips:**
- Pastikan CAPS LOCK tidak aktif
- Password case-sensitive
- Gunakan email yang sudah terdaftar

#### 2. Login SSO (CAS)

Jika organisasi Anda menggunakan CAS:

```
1. Buka http://localhost:8000/login/cas
2. Masukkan username organisasi
3. Sistem akan redirect ke halaman CAS
4. Otomatis login jika sudah authenticated
```

#### 3. Lupa Password

```
1. Di halaman login, klik "Lupa Password?"
2. Masukkan email Anda
3. Cek email untuk link reset password
4. Klik link dan buat password baru
5. Login dengan password baru
```

### Manajemen Profil

#### Mengubah Profil

```
1. Klik nama pengguna di pojok kanan atas
2. Pilih "Profil" atau "Profile"
3. Edit informasi yang diperlukan
4. Klik "Simpan" atau "Save"
```

#### Mengubah Password

```
1. Buka halaman Profil
2. Cari bagian "Keamanan" atau "Security"
3. Klik "Ubah Password"
4. Masukkan password lama
5. Masukkan password baru (min. 8 karakter)
6. Konfirmasi password baru
7. Klik "Update Password"
```

#### Logout

```
Klik nama pengguna di pojok kanan atas
→ Pilih "Logout" atau "Keluar"
→ Sistem akan mengalihkan ke halaman login
```

---

## Dashboard

### Halaman Utama Setelah Login

Setiap role akan melihat dashboard yang berbeda:

#### Admin Dashboard
```
┌─────────────────────────────────────┐
│ Statistik Umum                      │
├─────────────────────────────────────┤
│ • Total Peserta: 250                │
│ • Total Kursus: 12                  │
│ • Pembayaran Bulan Ini: Rp 50jt     │
│ • Sertifikat Diterbitkan: 180       │
├─────────────────────────────────────┤
│ Aksi Cepat                          │
│ [+ Buat Program] [+ Buat Kursus]    │
│ [+ Daftar Peserta] [Lihat Laporan]  │
└─────────────────────────────────────┘
```

#### Instruktur Dashboard
```
┌─────────────────────────────────────┐
│ Kelas Saya                          │
├─────────────────────────────────────┤
│ • Kelas A - Senin (09:00-11:00)     │
│ • Kelas B - Rabu (14:00-16:00)      │
│ • Kelas C - Jumat (10:00-12:00)     │
├─────────────────────────────────────┤
│ Aksi Cepat                          │
│ [Input Absensi] [Input Nilai]       │
│ [Tulis Catatan] [Lihat Jadwal]      │
└─────────────────────────────────────┘
```

#### Peserta Dashboard
```
┌─────────────────────────────────────┐
│ Kursus Saya                         │
├─────────────────────────────────────┤
│ • Kursus A - Progress: 75%          │
│ • Kursus B - Progress: 50%          │
│ • Kursus C - Menunggu Pembayaran    │
├─────────────────────────────────────┤
│ Aksi Cepat                          │
│ [Cari Kursus] [Lihat Jadwal]        │
│ [Download Sertifikat] [Upload Berkas]│
└─────────────────────────────────────┘
```

---

## Panduan Admin

Panduan lengkap untuk administrator sistem.

### Menu Admin

**Akses:** Klik menu "Admin" atau navigasi ke `/admin`

```
Admin Menu:
├── Dashboard              → Statistik & overview
├── Program               → Manajemen program pelatihan
├── Kursus                → Manajemen kursus/mata pelajaran
├── Level                 → Setting level kesulitan
├── Kelas                 → Manajemen kelas
├── Jadwal                → Jadwal pembelajaran
├── Lokasi                → Lokasi/ruang kelas
├── Peserta               → Database peserta
├── Instruktur            → Database instruktur
├── Pembayaran            → Monitor transaksi
├── Sertifikat            → Manajemen sertifikat
├── Export                → Export laporan
└── Pengaturan            → Konfigurasi sistem
```

---

## Manajemen Program & Kursus

### Membuat Program Bahasa Baru

**Program** adalah kurikulum induk bahasa (contoh: "English Course", "Mandarin Basics", "Nihongo 101")

```
Langkah:
1. Buka Admin Dashboard
2. Pilih "Program" dari menu
3. Klik tombol "+ Buat Program" atau "+ Program Baru"
4. Isi form:
   - Nama Program: "English Conversation"
   - Bahasa: English / 英语 / 日本語
   - Deskripsi: Program khusus untuk meningkatkan kemampuan speaking
   - Durasi Total: 60 jam
   - Biaya: Rp 2.500.000
5. Klik "Simpan"
```

### Menambah Kursus Bahasa ke Program

**Kursus** adalah topik/skill dalam program bahasa (Conversation, Grammar, Listening, Writing, dll)

```
Langkah:
1. Di halaman Program, pilih program bahasa yang sudah dibuat
2. Scroll ke bawah → Tab "Kursus"
3. Klik "+ Tambah Kursus"
4. Isi form:
   - Nama Kursus: "Speaking Skills"
   - Fokus Skill: Speaking / Listening / Grammar / Writing
   - Deskripsi: Pelatihan percakapan sehari-hari dengan native speaker
   - Durasi: 15 jam
   - Instruktur: Pilih instruktur/native speaker
5. Klik "Simpan"
```

### Mengelola Level Bahasa

**Level** adalah tingkat kemampuan bahasa (A1 Beginner, A2 Elementary, B1 Intermediate, B2 Upper Intermediate, C1 Advanced)

```
Langkah:
1. Pilih menu "Level"
2. Pilih program/kursus bahasa yang akan diatur level-nya
3. Klik "+ Buat Level"
4. Isi form:
   - Nama Level: "A1 Beginner (CEFR)"
   - Deskripsi: Pemula - Bisa memahami & menggunakan frasa dasar
   - Skor Minimum: 0
   - Skor Maksimum: 40
5. Klik "Simpan"
6. Ulangi untuk level A2, B1, B2, C1
```

### Mengelola Kelas Bahasa

**Kelas** adalah jadwal pembelajaran spesifik dengan peserta terbatas (Max 15-20 untuk intensive language class)

```
Langkah:
1. Pilih menu "Kelas"
2. Klik "+ Buat Kelas Baru"
3. Isi form:
   ┌──────────────────────────────────┐
   │ Nama Kelas: EN-A1-001            │
   │ Program: English Conversation    │
   │ Level: A1 Beginner               │
   │ Kapasitas: 15 peserta            │
   │ Instruktur: John Smith (Native)  │
   │ Tanggal Mulai: 01/06/2026        │
   │ Tanggal Selesai: 31/07/2026      │
   │ Status: [Aktif/Draft/Tutup]      │
   └──────────────────────────────────┘
4. Klik "Simpan"
```

### Edit & Publikasi Kursus

```
Langkah:
1. Buka halaman Kursus
2. Cari kursus yang ingin diedit
3. Klik tombol "Edit" atau ketuk baris kursus
4. Ubah informasi sesuai kebutuhan
5. Untuk publikasi:
   - Ubah status menjadi "Dipublikasikan"
   - Kursus akan muncul di halaman pencarian peserta
6. Klik "Simpan Perubahan"
```

---

## Manajemen Peserta

### Melihat Daftar Peserta (Learner)

```
Langkah:
1. Pilih menu "Peserta"
2. Akan tampil tabel peserta dengan kolom:
   - Nama
   - Email
   - No. Identitas
   - Bahasa Belajar
   - Level Saat Ini
   - Status Pembayaran
   - Tanggal Daftar
3. Gunakan search/filter untuk mencari peserta tertentu:
   - Filter berdasarkan bahasa (English, Mandarin, dll)
   - Filter berdasarkan level
   - Filter berdasarkan status pembayaran
```

### Menambah Peserta Manual

```
Langkah:
1. Di halaman Peserta, klik "+ Daftar Peserta Baru"
2. Isi form pendaftaran:
   ┌──────────────────────────────┐
   │ Nama Lengkap: [________]     │
   │ Email: [________]            │
   │ No. Identitas: [________]    │
   │ No. Telepon: [________]      │
   │ Alamat: [________]           │
   │ Kursus: [Pilih dropdown]     │
   │ Status: [Aktif/Inactive]     │
   └──────────────────────────────┘
3. Klik "Daftar Peserta"
4. Sistem akan mengirim email notifikasi ke peserta
```

### Melihat Detail Peserta

```
Langkah:
1. Buka daftar Peserta
2. Klik nama peserta atau tombol "Detail"
3. Akan tampil informasi:
   - Profil peserta
   - Kursus yang diikuti
   - Status pembayaran
   - Absensi & nilai
   - Sertifikat yang diterima
```

### Mengubah Status Peserta

```
Langkah:
1. Buka detail peserta
2. Klik "Edit Status" atau tombol edit
3. Pilih status baru:
   • Aktif - Peserta dapat akses sistem
   • Nonaktif - Akses dinonaktifkan
   • Suspended - Peserta ditangguhkan
4. Klik "Simpan"
```

### Export Data Peserta

```
Langkah:
1. Buka halaman Peserta
2. Klik tombol "Export ke Excel"
3. Pilih filter (opsional):
   - Program
   - Status
   - Tanggal pendaftaran
4. Klik "Download"
5. File Excel akan ter-download ke komputer
```

---

## Manajemen Instruktur

### Mendaftar Instruktur

```
Langkah:
1. Pilih menu "Instruktur"
2. Klik "+ Daftar Instruktur Baru"
3. Isi form:
   ┌──────────────────────────────┐
   │ Nama: [________]             │
   │ Email: [________]            │
   │ Keahlian: [________]         │
   │ Bio/Deskripsi: [________]    │
   │ No. Telepon: [________]      │
   │ Alamat: [________]           │
   │ Status: [Aktif/Nonaktif]    │
   └──────────────────────────────┘
4. Klik "Daftar"
```

### Mengassign Instruktur ke Kelas

```
Langkah:
1. Buka detail Kelas
2. Scroll ke bagian "Instruktur"
3. Klik "+ Tambah Instruktur" atau "+ Assign"
4. Pilih instruktur dari dropdown
5. Tentukan peranan (Primary/Secondary)
6. Klik "Simpan"
```

### Melihat Schedule Instruktur

```
Langkah:
1. Buka halaman Instruktur
2. Klik nama instruktur
3. Buka tab "Jadwal" atau "Schedule"
4. Akan tampil semua jadwal mengajar instruktur
5. Dapat melihat kelas, hari, jam, dan lokasi
```

---

## Manajemen Jadwal & Kelas

### Membuat Jadwal Pembelajaran

```
Langkah:
1. Pilih menu "Jadwal"
2. Klik "+ Jadwal Baru" atau "+ Schedule"
3. Isi form:
   ┌──────────────────────────────┐
   │ Kelas: [Pilih dropdown]      │
   │ Hari: [Senin/Selasa/...]     │
   │ Jam Mulai: [09:00]           │
   │ Jam Selesai: [11:00]         │
   │ Lokasi: [Pilih ruang]        │
   │ Instruktur: [Pilih]          │
   │ Catatan: [opsional]          │
   └──────────────────────────────┘
4. Klik "Simpan"
```

### Melihat Kalender Jadwal

```
Langkah:
1. Buka halaman Jadwal
2. Lihat view Kalender (Calendar View)
3. Setiap sel hari menampilkan jadwal:
   - Nama kelas
   - Jam & lokasi
   - Instruktur
4. Klik jadwal untuk melihat detail/edit
```

### Mengubah atau Membatalkan Jadwal

```
Langkah:
1. Buka halaman Jadwal
2. Cari jadwal yang ingin diubah
3. Klik tombol "Edit" atau "Ubah"
4. Ubah informasi:
   - Jam
   - Lokasi
   - Instruktur
   - Atau cek "Batalkan jadwal ini"
5. Klik "Simpan Perubahan"
6. Jika membatalkan, notifikasi akan dikirim ke peserta & instruktur
```

---

## Sertifikat & Penerbitan

### Membuat Template Sertifikat

```
Langkah:
1. Pilih menu "Sertifikat"
2. Tab "Template Sertifikat"
3. Klik "+ Template Baru"
4. Isi form:
   ┌──────────────────────────────┐
   │ Nama Template: [________]    │
   │ Program/Kursus: [Pilih]      │
   │ Deskripsi: [________]        │
   │ Background/Desain: [Upload]  │
   │ Font: [Pilih]                │
   └──────────────────────────────┘
5. Klik "Simpan"
```

### Menerbitkan Sertifikat ke Peserta

```
Langkah:
1. Buka menu "Sertifikat"
2. Tab "Sertifikat Peserta"
3. Klik "+ Terbitkan Sertifikat"
4. Isi form:
   ┌──────────────────────────────┐
   │ Program/Kursus: [Pilih]      │
   │ Peserta: [Multi-select]      │
   │ Template: [Pilih template]   │
   │ Nomor Sertifikat: [Auto/Manual]
   │ Tanggal Terbit: [date]       │
   └──────────────────────────────┘
5. Review daftar peserta yang akan menerima
6. Klik "Terbitkan"
7. Peserta akan menerima notifikasi & bisa download
```

### Melihat Status Sertifikat

```
Langkah:
1. Menu "Sertifikat" → Tab "Sertifikat Peserta"
2. Filter berdasarkan:
   - Program
   - Status (Draft/Published/Revoked)
   - Tanggal
3. Klik sertifikat untuk detail:
   - Nama peserta
   - Nomor sertifikat
   - Tanggal terbit
   - QR code
   - Status
```

### Mencabut/Revoke Sertifikat

```
Langkah:
1. Buka sertifikat yang ingin dicabut
2. Klik tombol "Revoke" atau "Cabut"
3. Masukkan alasan pencabutan
4. Konfirmasi dengan password admin
5. Klik "Ya, Cabut Sertifikat"
6. Peserta tidak bisa lagi download sertifikat
```

---

## Panduan Instruktur

Panduan penggunaan untuk Instruktur/Pengajar.

### Menu Instruktur

```
Instruktur Menu:
├── Dashboard              → Overview kelas & statistik
├── Jadwal Saya            → Jadwal mengajar
├── Input Absensi          → Catat kehadiran peserta
├── Input Nilai            → Input score peserta
├── Lihat Nilai            → Review nilai yang diinput
├── Catatan Pembelajaran   → Risalah pembelajaran
└── Profil                 → Edit profil instruktur
```

---

## Input Absensi

### Cara Input Absensi

```
Langkah:
1. Pilih menu "Input Absensi"
2. Pilih Kelas: [dropdown]
3. Pilih Jadwal: [date picker]
4. Sistem akan tampilkan daftar peserta:

┌───────────────────────────────┐
│ Kelas: A-001 | Hari: Senin    │
│ Jam: 09:00 - 11:00            │
├───────────────────────────────┤
│ ☐ 1. Budi Santoso  [v Hadir]  │
│ ☐ 2. Ani Wijaya    [v Hadir]  │
│ ☐ 3. Citra Kusuma  [Sakit]    │
│ ☐ 4. Doni Pratama  [Izin]     │
│ ☐ 5. Eka Putri     [ Alfa ]   │
│ ☐ 6. Fajar Ananda  [Lainnya]  │
├───────────────────────────────┤
│ Catatan: [_____________]      │
├───────────────────────────────┤
│   [Batal]        [Simpan]     │
└───────────────────────────────┘

5. Pilih status untuk setiap peserta:
   • Hadir - Peserta hadir
   • Sakit - Izin karena sakit
   • Izin - Izin tanpa alasan medis
   • Alfa - Tanpa keterangan
   • Lainnya - Keterangan khusus

6. (Opsional) Tambah catatan pembelajaran
7. Klik "Simpan"
```

### Status Kehadiran & Keterangan

| Status | Keterangan | Simbol |
|--------|-----------|--------|
| Hadir | Peserta hadir | ✓ |
| Sakit | Izin karena sakit (butuh surat) | 🏥 |
| Izin | Izin (ada alasan) | ⏸ |
| Alfa | Tidak hadir tanpa keterangan | ✗ |

### Edit Absensi

```
Langkah:
1. Buka "Input Absensi"
2. Pilih kelas dan jadwal yang sama
3. Data absensi sebelumnya akan dimuat
4. Ubah status peserta yang salah
5. Klik "Simpan" untuk update
```

---

## Input Nilai

### Cara Input Nilai Peserta

```
Langkah:
1. Buka menu "Input Nilai"
2. Pilih Kursus: [dropdown]
3. Sistem akan menampilkan daftar peserta:

┌──────────────────────────────┐
│ Kursus: Excel Dasar          │
├──────────────────────────────┤
│ No. | Nama         | Nilai   │
│ 1   | Budi Santoso | [___]   │
│ 2   | Ani Wijaya   | [___]   │
│ 3   | Citra Kusuma | [___]   │
│ 4   | Doni Pratama | [___]   │
│ 5   | Eka Putri    | [___]   │
│ 6   | Fajar Ananda | [___]   │
├──────────────────────────────┤
│   [Batal]      [Simpan]      │
└──────────────────────────────┘

4. Masukkan nilai untuk setiap peserta
   - Range: 0 - 100
5. Klik "Simpan"
```

### Kriteria Penilaian

```
Nilai      Status      Interpretasi
90 - 100   A/Lulus     Sangat Memuaskan
80 - 89    B/Lulus     Memuaskan
70 - 79    C/Lulus     Cukup
0 - 69     D/TL        Kurang (Tidak Lulus)
```

### Edit Nilai

```
Langkah:
1. Buka menu "Lihat Nilai"
2. Pilih Kursus
3. Data nilai akan ditampilkan
4. Klik tombol "Edit" atau ["Ubah Nilai"]
5. Ubah nilai yang salah
6. Klik "Update"
```

### Export Nilai ke Excel

```
Langkah:
1. Di halaman Input Nilai
2. Klik tombol "Export Nilai"
3. Pilih filter (optional):
   - Kursus
   - Rentang nilai
4. Klik "Download Excel"
5. File akan ter-download
```

---

## Catatan Pembelajaran

### Membuat Catatan Pembelajaran (Risalah)

```
Langkah:
1. Buka menu "Catatan Pembelajaran"
2. Klik "+ Catatan Baru" atau "+ Risalah Baru"
3. Isi form:

┌──────────────────────────────┐
│ Kelas: [Pilih dropdown]      │
│ Tanggal: [date picker]       │
│ Jam Mulai: [time]            │
│ Jam Selesai: [time]          │
│ Materi Pokok:                │
│ [___________________]        │
│ [___________________]        │
│                              │
│ Penjelasan Detail:           │
│ [Mulai mengetik atau paste]  │
│ - Poin 1                     │
│ - Poin 2                     │
│ - Poin 3                     │
│                              │
│ Hambatan/Catatan:            │
│ [___________________]        │
│                              │
│ [Batal]      [Simpan]        │
└──────────────────────────────┘

4. Klik "Simpan"
```

### Melihat Catatan Sebelumnya

```
Langkah:
1. Buka "Catatan Pembelajaran"
2. Pilih kelas
3. Akan menampilkan daftar risalah sebelumnya
4. Klik untuk membuka detail
5. Bisa melihat materi & hambatan
```

---

## Panduan Peserta

Panduan penggunaan untuk Peserta/Siswa.

### Menu Peserta

```
Peserta Menu:
├── Dashboard              → Overview kursus & progress
├── Cari Kursus            → Jelajahi program yang tersedia
├── Kursus Saya            → Kursus yang diikuti
├── Jadwal Saya            → Jadwal pembelajaran
├── Pembayaran             → Riwayat pembayaran
├── Profil                 → Edit profil & data pribadi
├── Nilai Saya             → Lihat score/penilaian
├── Sertifikat Saya        → Download sertifikat
└── Pengaturan             → Notifikasi & preferensi
```

---

## Pendaftaran Kursus

### Mencari Kursus Bahasa

```
Langkah:
1. Login sebagai peserta
2. Pilih menu "Cari Kursus" atau "Jelajahi"
3. Tampilan:
   ┌──────────────────────────────┐
   │ 🔍 Cari: [_________]         │
   │ Filter: [Bahasa ▼] [Level ▼] │
   ├──────────────────────────────┤
   │ ┌─────────────────────────┐  │
   │ │ Kursus Bahasa Inggris   │  │
   │ │ 🇬🇧 English Conversation │  │
   │ │ ⏱️ 60 Jam               │  │
   │ │ 💰 Rp 2.500.000        │  │
   │ │ ⭐ 4.9/5 (85 review)    │  │
   │ │ [Lihat Detail] [Daftar] │  │
   │ └─────────────────────────┘  │
   │ ┌─────────────────────────┐  │
   │ │ Kursus Mandarin         │  │
   │ │ 🇨🇳 Bahasa Mandarin     │  │
   │ │ [...]                   │  │
   │ └─────────────────────────┘  │
   └──────────────────────────────┘

4. Gunakan search atau filter berdasarkan bahasa & level
5. Klik "Lihat Detail" untuk informasi lengkap
```

### Melihat Detail Kursus

```
Langkah:
1. Klik "Lihat Detail" pada kursus yang diminati
2. Akan menampilkan:

   📋 DETAIL KURSUS
   ────────────────────
   Judul: Pelatihan Excel Dasar
   Program: Pelatihan Digital
   Durasi: 40 jam
   Biaya: Rp 1.000.000
   Status: Aktif
   
   📝 Deskripsi:
   Pembelajaran komprehensif tentang fungsi dasar Excel,
   formula, chart, dan data analysis...
   
   👨‍🏫 Instruktur:
   - Budi Hartono (Excel Expert)
   
   📅 Jadwal:
   - Senin 09:00 - 11:00 (Ruang A)
   - Rabu 14:00 - 16:00 (Ruang B)
   - Jumat 10:00 - 12:00 (Ruang A)
   
   ✅ Persyaratan:
   - Minimal tingkat SMA
   - Bisa menggunakan komputer
   
   [Daftar Kursus]

3. Klik "Daftar Kursus" untuk melanjutkan
```

### Proses Pendaftaran Kursus Bahasa

```
Langkah:
1. Dari detail kursus, klik "Daftar Kursus"
2. Sistem akan menampilkan form verifikasi:

   ┌──────────────────────────────┐
   │ VERIFIKASI PENDAFTARAN       │
   ├──────────────────────────────┤
   │ Nama: Budi Santoso           │
   │ Email: budi@email.com        │
   │ No. Identitas: 3307xxxxxx    │
   │ No. Telepon: 081234567890    │
   │ Alamat: Jl. Merdeka No.123   │
   │ Kursus: English Conversation │
   │ Level: Beginner              │
   │ Biaya: Rp 2.500.000          │
   │ Jadwal: Senin, Rabu, Jumat   │
   │ Mulai: 01 Juni 2026          │
   │                              │
   │ 📋 Anda akan mengikuti:      │
   │ • Placement test (jika baru) │
   │ • 60 jam pelajaran           │
   │ • Pre & post test            │
   │ • Sertifikat (jika lulus)   │
   │                              │
   │ Saya setuju dengan syarat &  │
   │ ketentuan [✓]                │
   │                              │
   │ [Batal]     [Lanjut Bayar]   │
   └──────────────────────────────┘

3. Verifikasi data Anda
4. Centang "Saya setuju dengan syarat & ketentuan"
5. Klik "Lanjut Bayar"
6. Akan redirect ke halaman pembayaran
```

---

## Pembayaran Online

### Alur Pembayaran

```
1. Pilih Kursus → Input Data → Lanjut Bayar
        ↓
2. Halaman Pembayaran (Midtrans Snap)
   - Pilih metode pembayaran
   - Input data pembayaran
        ↓
3. Konfirmasi Pembayaran
        ↓
4. Tunggu validasi (instan untuk kartu kredit)
        ↓
5. Selesai - Akses kursus diberikan otomatis
```

### Metode Pembayaran yang Tersedia

```
1. Kartu Kredit
   - Visa, Mastercard, American Express
   - Cicilan 3, 6, 12 bulan (jika tersedia)

2. Transfer Bank
   - BCA, Mandiri, BNI, BTN, CIMB
   - Biaya admin: Rp 2.500

3. E-Wallet
   - GCash, OVO, DANA, LinkAja
   - Proses instan

4. Virtual Account
   - Nomor rekening unik untuk setiap transaksi
   - Valid 24 jam

5. Cicilan Tanpa Kartu Kredit
   - Akulaku
   - Kredivo
```

### Cara Membayar

```
Langkah (Contoh: Kartu Kredit):
1. Klik "Lanjut Bayar" di halaman pendaftaran
2. Pilih metode: "Kartu Kredit"
3. Isi data:
   ┌──────────────────────────────┐
   │ Nama Pemilik Kartu:          │
   │ [_____________________]      │
   │                              │
   │ Nomor Kartu: [__ __ __ __]   │
   │ Bulan Exp: [__]              │
   │ Tahun Exp: [__]              │
   │ CVV: [___]                   │
   │                              │
   │ [Proses Pembayaran]          │
   └──────────────────────────────┘

4. Sistem akan memproses pembayaran
5. Tunggu konfirmasi (biasanya < 1 menit)
6. Jika berhasil → Akan diarahkan ke halaman sukses
7. Jika gagal → Coba lagi atau ubah metode
```

### Melihat Status Pembayaran

```
Langkah:
1. Buka menu "Pembayaran"
2. Akan tampil riwayat:

   ┌────────────────────────────┐
   │ Status Pembayaran          │
   ├────────────────────────────┤
   │ Tanggal: 15 Mei 2026       │
   │ Kursus: Excel Dasar        │
   │ Biaya: Rp 1.000.000        │
   │ Metode: Kartu Kredit       │
   │ Status: ✅ SUKSES          │
   │ Order ID: TXN-123456789    │
   │ [Lihat Invoice] [Download] │
   └────────────────────────────┘

3. Klik "Lihat Invoice" untuk detail lengkap
```

### Troubleshooting Pembayaran

**Jika pembayaran gagal:**

```
1. Pastikan saldo/limit kartu kredit cukup
2. Cek koneksi internet Anda
3. Coba metode pembayaran lain
4. Hubungi customer service jika berulang

Nomor Layanan Midtrans: 1500-662-2626
```

---

## Tracking Progress

### Melihat Progress Pembelajaran

```
Langkah:
1. Buka menu "Kursus Saya"
2. Klik kursus untuk melihat detail

   KURSUS: Excel Dasar
   ────────────────────────
   Status: Dalam Proses
   Progress: ████████░░ 75%
   
   📅 Jadwal:
   ✅ Senin, 01 Juni - Selesai
   ✅ Rabu, 03 Juni - Selesai
   ⏳ Jumat, 05 Juni - Akan Datang
   
   👤 Instruktur: Budi Hartono
   
   📊 Nilai: 85 (Lulus)
   ✅ Sertifikat: Diterbitkan
   
   [Lihat Jadwal] [Unduh Materi] [Chat]

3. Informasi yang ditampilkan:
   - Jadwal pembelajaran
   - Materi yang sudah dipelajari
   - Kehadiran (attendance)
   - Nilai sementara
   - Catatan instruktur
```

### Melihat Jadwal Pembelajaran Bahasa

```
Langkah:
1. Buka menu "Jadwal Saya"
2. Tampilan Kalender:

   Juni 2026
   ┌──────────────────────────────┐
   │ Min  Sel  Rab  Kam  Jum  Sab │
   ├──────────────────────────────┤
   │              1   2   3   4   5
   │ 6   7   8   9   10  11  12  13
   │ 14  15  16  17  18  19  20  21
   │ 21  22  23  24  25  26  27  28
   │ 29  30
   └──────────────────────────────┘

   📅 Jadwal Hari Ini (Senin, 01 Juni):
   • 18:00 - 20:00 | English Speaking | Ruang A
     Instruktur: John Smith (Native Speaker)
   • 19:00 - 21:00 | Mandarin Conversation | Ruang B
     Instruktur: Li Wei (Native Speaker)

3. Klik jadwal untuk:
   - Melihat detail lengkap
   - Material pembelajaran
   - Assignment/homework
   - Chat dengan instruktur
```

### Melihat Kehadiran (Absensi)

```
Langkah:
1. Di halaman detail kursus
2. Scroll ke bagian "Kehadiran" atau "Attendance"
3. Tampilan:

   Kehadiran: 9 dari 10 pertemuan (90%)
   ┌──────────────────────────────────────┐
   │ Pertemuan | Tanggal    | Skill Focus │
   ├──────────────────────────────────────┤
   │ 1         | 01 Juni    | ✅ Speaking │
   │ 2         | 03 Juni    | ✅ Speaking │
   │ 3         | 05 Juni    | ⏸️ Listening│
   │ 4         | 08 Juni    | ✅ Speaking │
   │ 5         | 10 Juni    | ✅ Writing  │
   │ 6         | 12 Juni    | ✅ Speaking │
   │ 7         | 15 Juni    | ✅ Grammar  │
   │ 8         | 17 Juni    | ✅ Speaking │
   │ 9         | 19 Juni    | ✅ Listening│
   │ 10        | 22 Juni    | ⏳ Speaking │
   └──────────────────────────────────────┘

4. Status:
   ✅ Hadir - Peserta hadir
   ⏸️ Izin - Peserta ada alasan valid
   🏥 Sakit - Peserta sakit
   ✗ Alfa - Tidak hadir tanpa keterangan
```

### Melihat Nilai

```
Langkah:
1. Menu "Nilai Saya"
2. Akan menampilkan nilai per kursus:

   Kursus: English Conversation (A1)
   Skor Akhir: 85/100 (Lulus)
   
   ┌──────────────────────────┐
   │ Komponen Penilaian:      │
   ├──────────────────────────┤
   │ • Kehadiran: 90%         │
   │ • Participation: 85      │
   │ • Listening Test: 80     │
   │ • Speaking Test: 88      │
   │ • Final Exam: 85         │
   ├──────────────────────────┤
   │ Skor Rata-rata: 85       │
   │ Status: ✅ PASSED        │
   │ Level Berikutnya: A2     │
   └──────────────────────────┘

3. Nilai yang ditampilkan:
   - Nilai komponen (jika breakdown tersedia)
   - Nilai rata-rata
   - Status lulus/tidak lulus
   - Saran dari instruktur (jika ada)
```

---

## Download Sertifikat

### Melihat Sertifikat yang Diterima

```
Langkah:
1. Buka menu "Sertifikat Saya"
2. Tampilan:

   ┌─────────────────────────────────┐
   │ SERTIFIKAT BAHASA SAYA          │
   ├─────────────────────────────────┤
   │ ┌───────────────────────────┐   │
   │ │ English Conversation (A1) │   │
   │ │ Nomor: CERT-2026-00123    │   │
   │ │ Tanggal: 30 Juni 2026     │   │
   │ │ Level: A1 (CEFR)          │   │
   │ │ Status: ✅ Diterbitkan    │   │
   │ │ QR Code: [████████████]   │   │
   │ │ [👁️ Preview] [⬇️Download] │   │
   │ └───────────────────────────┘   │
   │ ┌───────────────────────────┐   │
   │ │ Mandarin Basics (A2)      │   │
   │ │ Nomor: CERT-2026-00145    │   │
   │ │ Tanggal: 15 Mei 2026      │   │
   │ │ Level: A2 (CEFR)          │   │
   │ │ Status: ✅ Diterbitkan    │   │
   │ │ [👁️ Preview] [⬇️Download] │   │
   │ └───────────────────────────┘   │
   └─────────────────────────────────┘

3. Informasi yang ditampilkan:
   - Nama program/kursus
   - Nomor sertifikat unik
   - Tanggal penerbitkan
   - Status (Draft/Published/Revoked)
```

### Download Sertifikat (PDF)

```
Langkah:
1. Di halaman "Sertifikat Saya"
2. Klik tombol "⬇️ Download" pada sertifikat yang ingin diunduh
3. Browser akan download file PDF
   Nama file: "Sertifikat_[Nama_Kursus]_[Nama_Anda].pdf"
4. File dapat dibuka dengan:
   - Adobe Reader
   - Preview (Mac)
   - Windows Photo Viewer
   - Browser (Chrome, Firefox, dll)
```

### Print Sertifikat

```
Langkah:
1. Download sertifikat (PDF)
2. Buka file PDF
3. Tekan Ctrl+P (atau Cmd+P di Mac)
4. Pilih printer:
   ┌──────────────────────────┐
   │ Printer: [Pilih Printer] │
   │ Copies: 1                │
   │ Pages: All               │
   │ Orientation: Portrait    │
   │ Paper Size: A4           │
   │ Color: Color             │
   └──────────────────────────┘
5. Klik "Print"
6. Pilih lokasi penyimpanan file (jika Print to File)
```

### Bagikan Sertifikat

```
Langkah:
1. Download file sertifikat PDF
2. Kirim melalui:
   - Email ke employer/institusi
   - Upload ke LinkedIn
   - Bagikan di media sosial (opsional)
   - Print & lamirkan ke berkas
3. Gunakan nomor sertifikat untuk verifikasi

Contoh Share di LinkedIn:
"Saya telah menyelesaikan Pelatihan Excel Dasar
dari Balai Kursus dengan sertifikat no. CERT-2026-00123.
[Link profil atau file PDF]"
```

### Verifikasi Sertifikat Online

```
Jika Balai Kursus menyediakan verifikasi online:

Langkah:
1. Buka https://balai-kursus.com/verify
2. Masukkan:
   - Nomor Sertifikat: CERT-2026-00123
   - Nama Peserta: Budi Santoso
3. Klik "Verifikasi"
4. Sistem akan menampilkan:
   ✅ Sertifikat Valid
   • Nama: Budi Santoso
   • Program: Excel Dasar
   • Tanggal Terbit: 30 Juni 2026
   • Issued by: Balai Kursus
```

---

## FAQ

### Pertanyaan Umum

**P: Apakah saya bisa belajar lebih dari satu bahasa sekaligus?**  
A: Ya, Anda bisa mendaftar ke multiple bahasa. Jadwal dapat disesuaikan agar tidak bentrok. Pembayaran dilakukan terpisah untuk setiap program bahasa.

**P: Bagaimana jika saya terlambat membayar?**  
A: Pembayaran harus dilakukan dalam 24 jam setelah pendaftaran. Jika tidak, pendaftaran akan dibatalkan otomatis.

**P: Apakah ada refund jika saya cancel kursus?**  
A: Kebijakan refund tergantung timing cancellation. Hubungi admin untuk detail.

**P: Berapa lama saya dapat download sertifikat?**  
A: Sertifikat dapat diunduh seumur hidup setelah diterbitkan.

**P: Apa yang dilakukan jika saya tidak lulus kursus?**  
A: Anda dapat mendaftar ulang pada batch berikutnya dengan biaya yang sama. Instruktur juga bisa memberikan remedial.

**P: Bagaimana cara menghubungi instruktur?**  
A: Melalui fitur chat di dalam aplikasi atau email resmi yang terdaftar.

**P: Berapa skor minimum untuk lulus dan naik level?**  
A: Biasanya 70/100 untuk lulus level saat ini. Untuk naik ke level berikutnya diperlukan skor 75+. Standar CEFR berlaku untuk semua bahasa.

**P: Apakah sertifikat bahasa ini diakui internasional?**  
A: Sertifikat dari Balai Kursus mengikuti standar CEFR (Common European Framework of Reference) dan dapat digunakan untuk keperluan akademik atau pekerjaan. Pengakuan lebih lanjut tergantung institusi/perusahaan.

**P: Bagaimana jika sistem error saat pembayaran?**  
A: Hubungi admin. Cek status pembayaran dan tunggu verifikasi manual jika diperlukan.

**P: Apakah ada aplikasi mobile?**  
A: Saat ini aplikasi web responsive untuk mobile. Aplikasi native sedang dalam pengembangan.

---

## Troubleshooting

### Masalah Login

**Masalah:** "Email atau password salah"  
**Solusi:**
1. Pastikan email sudah terdaftar
2. Check CAPS LOCK
3. Reset password melalui "Lupa Password?"
4. Clear browser cache (Ctrl+Shift+Del)

**Masalah:** "Email belum diverifikasi"  
**Solusi:**
1. Cek email untuk link verifikasi
2. Jika tidak ada, minta resend email
3. Cek folder Spam/Junk

### Masalah Pembayaran

**Masalah:** "Pembayaran gagal"  
**Solusi:**
1. Cek saldo/limit kartu kredit
2. Hubungi bank (jika kartu)
3. Coba metode pembayaran lain
4. Hubungi admin

**Masalah:** "Pembayaran berhasil tapi akses belum diberikan"  
**Solusi:**
1. Tunggu 5-10 menit
2. Refresh halaman
3. Logout dan login ulang
4. Hubungi admin jika masih tidak dapat akses

### Masalah Akses

**Masalah:** "Anda tidak punya akses halaman ini"  
**Solusi:**
1. Pastikan Anda sudah login
2. Verifikasi role/peran Anda
3. Hubungi admin untuk hak akses

**Masalah:** "Sesi expired"  
**Solusi:**
1. Logout
2. Login ulang
3. Jangan matikan browser terlalu lama

### Masalah Download

**Masalah:** "Download sertifikat tidak bisa"  
**Solusi:**
1. Gunakan browser yang berbeda
2. Disable ad blocker
3. Check internet connection
4. Hubungi admin

**Masalah:** "File PDF corrupt/tidak bisa dibuka"  
**Solusi:**
1. Download ulang
2. Gunakan PDF reader lain
3. Hubungi admin

### Masalah Technical

**Masalah:** "Halaman load lambat"  
**Solusi:**
1. Clear browser cache
2. Gunakan internet yang lebih cepat
3. Coba di browser lain
4. Hubungi admin

**Masalah:** "Tombol tidak respond"  
**Solusi:**
1. Refresh halaman
2. Logout dan login ulang
3. Clear cache browser
4. Restart browser

---

## Kontak Dukungan

### Development Status
⚠️ **CATATAN:** Aplikasi masih dalam tahap pengembangan dan berjalan di **localhost**.  
🔗 **Akses:** http://localhost:8000

Saat ini dukungan teknis dapat dihubungi melalui:

### Tim Pengembang

👨‍💻 **Technical Lead:** [Nama Developer]  
📧 **Email:** tech@balai-kursus.local  
💬 **Internal Communication:** Team chat/messaging  

### Untuk Production Deployment

Segera informasikan persiapan untuk:
- Domain production
- Email support resmi
- Live chat support
- Social media channels
- Phone support line

**Target Production Launch:** [Sesuaikan timeline]  

---

**Terima kasih telah menggunakan Balai Kursus!**

*Dokumentasi ini akan terus diperbarui seiring pengembangan aplikasi.*

---

## ℹ️ Informasi Penting

### Status Aplikasi
🔧 **Development Phase** - Platform sedang dalam tahap pengembangan  
📍 **Lokasi:** http://localhost:8000  
🔐 **Akses:** Terbatas untuk tim internal dan testing

### Fokus Platform
Balai Kursus dirancang khusus untuk:
- ✅ Manajemen **Kursus Bahasa** (English, Mandarin, Japanese, dll)
- ✅ Pendaftaran & penempatan level otomatis
- ✅ Tracking progress pembelajaran bahasa
- ✅ Penilaian berdasarkan CEFR Framework
- ✅ Penerbitan sertifikat kompetensi bahasa

### Fitur Bahasa yang Didukung
| Bahasa | Status | Level |
|--------|--------|-------|
| 🇬🇧 English | ✅ Active | A1 - C1 |
| 🇨🇳 Mandarin | ✅ Active | A1 - C1 |
| 🇯🇵 Japanese | ✅ Active | A1 - C1 |
| 🇪🇸 Spanish | 🔄 Coming Soon | - |
| 🇫🇷 French | 🔄 Coming Soon | - |

### Informasi Kontak untuk Feedback
Untuk bugs, suggestions, atau feedback tentang platform:
- 💬 Hubungi tim development
- 📧 Komunikasi internal melalui tim chat
- 🐛 Report issues ke GitHub repository

---

**Versi:** 1.0 (Beta)  
**Terakhir Diperbarui:** Mei 2026  
**Status:** 🔧 In Development
