# 🗺️ Peta Fitur & File Sistem Balai Kursus

Dokumen ini berisi pemetaan lengkap antara fitur aplikasi, URL akses, dan file source code yang terhubung (Controller, View, Model).

---

## 1️⃣ Modul Autentikasi (Login & Register)

### **A. Login SSO (CAS)**
Fitur login menggunakan akun Universitas (Single Sign On).
- **URL:** `/cas/login`
- **Logic (Controller):** `app/Http/Controllers/Auth/CasLoginController.php`
- **Model Terkait:** 
  - `app/Models/User.php`
  - `app/Models/Peserta.php`

### **B. Login & Register Biasa**
Fitur login manual untuk pengguna umum.
- **URL:** `/login`, `/register`
- **Logic:** `app/Http/Controllers/Auth/LoginController.php` (Default Laravel)
- **Views:** `resources/views/auth/login.blade.php`

---

## 2️⃣ Modul Peserta (Area Siswa)

### **A. Katalog Kursus**
Menampilkan daftar semua kursus yang tersedia untuk publik/peserta.
- **URL:** `/peserta/kursus`
- **Logic:** `Modules/Peserta/Http/Controllers/KursusController.php` (Method: `index`)
- **View:** `Modules/Peserta/Resources/views/kursus/index.blade.php`
- **Model:** `app/Models/Kursus.php`

### **B. Detail Kursus (Publik)**
Halaman informasi detail kursus sebelum mendaftar (Harga, Jadwal, Instruktur).
- **URL:** `/peserta/kursus/{id}`
- **Logic:** `Modules/Peserta/Http/Controllers/KursusController.php` (Method: `show`)
- **View:** `Modules/Peserta/Resources/views/kursus/show.blade.php`

### **C. Proses Pendaftaran (Action)**
Menangani logika pendaftaran kursus, pengecekan kuota, dan pembuatan tagihan.
- **URL:** `POST /peserta/kursus/{id}/daftar`
- **Logic:** `Modules/Peserta/Http/Controllers/KursusController.php` (Method: `daftar`)
- **Model Terkait:** 
  - `app/Models/Pendaftaran.php`
  - `app/Models/Pembayaran.php`

### **D. Dashboard Kursus Saya**
Daftar kursus yang sedang diikuti oleh peserta yang login.
- **URL:** `/peserta/kursus-saya`
- **Logic:** `Modules/Peserta/Http/Controllers/KursusController.php` (Method: `kursusSaya`)
- **View:** `Modules/Peserta/Resources/views/kursus/kursus-saya.blade.php`

### **E. Ruang Belajar (Detail Kelas)**
Halaman utama kelas bagi peserta terdaftar. Berisi tab pertemuan dan status.
- **URL:** `/peserta/kursus/{id}/detail`
- **Logic:** `Modules/Peserta/Http/Controllers/KursusController.php` (Method: `showDetail`)
- **View:** `Modules/Peserta/Resources/views/kursus/detail.blade.php`

### **F. Baca Risalah Pertemuan**
Halaman untuk membaca materi dan catatan dari pertemuan tertentu.
- **URL:** `/peserta/kursus/{id}/risalah`
- **Logic:** `Modules/Peserta/Http/Controllers/KursusController.php` (Method: `showRisalah`)
- **View:** `Modules/Peserta/Resources/views/kursus/risalah.blade.php`
- **Model:** `app/Models/Risalah.php`

---

## 3️⃣ Modul Absensi (Admin & Instruktur)

### **A. Daftar Absensi**
Melihat rekap kehadiran peserta.
- **URL:** `/absensi`
- **Logic:** `Modules/Absensi/Http/Controllers/AbsensiController.php` (Method: `index`)
- **View:** `Modules/Absensi/Resources/views/index.blade.php`

### **B. Input Absensi**
Form untuk instruktur memasukkan data kehadiran.
- **URL:** `/absensi/create`
- **Logic:** `Modules/Absensi/Http/Controllers/AbsensiController.php` (Method: `create`, `store`)
- **View:** `Modules/Absensi/Resources/views/create.blade.php` (Perlu dibuat jika belum ada)

---

## 4️⃣ Database & Struktur Data

### **A. Tabel Utama**
- **Users:** `app/Models/User.php` (Akun login)
- **Peserta:** `app/Models/Peserta.php` (Profil siswa)
- **Kursus:** `app/Models/Kursus.php` (Data kelas/kursus)
- **Pendaftaran:** `app/Models/Pendaftaran.php` (Relasi Peserta-Kursus)
- **Jadwal:** `app/Models/Jadwal.php` (Waktu & Lokasi)

### **B. Tabel Baru (Update Terakhir)**
- **Lokasi:** `app/Models/Lokasi.php` (Gedung/Ruangan)
- **Kelas:** `app/Models/Kela.php` (Fasilitas Kelas)
- **Scores:** `app/Models/Score.php` (Nilai Akademik)

---

## 5️⃣ Konfigurasi Penting

- **Routes Web:** 
  - `routes/web.php` (Route utama)
  - `Modules/Peserta/Routes/web.php` (Route modul peserta)
  - `Modules/Absensi/Routes/web.php` (Route modul absensi)

- **Layouts:**
  - `Modules/Peserta/Resources/views/layouts/master.blade.php` (Template utama peserta)
  - `Modules/Absensi/Resources/views/layouts/master.blade.php` (Template utama absensi)

---

> **Catatan:** 
> Jika Anda ingin mengedit tampilan, fokus pada file di folder `Resources/views`.
> Jika ingin mengubah alur logika bisnis, fokus pada file di folder `Http/Controllers`.