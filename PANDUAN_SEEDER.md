# Panduan Seeder Database

**Tanggal Dibuat:** 10 Februari 2026

---

## 📋 Daftar Seeder yang Tersedia

### 1. **LokasiSeeder**
**File:** `database/seeders/LokasiSeeder.php`

**Fungsi:** Menambahkan data master lokasi/venue untuk kursus

**Data yang Ditambahkan:**
- 8 lokasi dengan berbagai tipe (Gedung A, Gedung B, Kantor Cabang di berbagai kota)
- Masing-masing lokasi dilengkapi: nama, alamat, no_telp, kota, provinsi, keterangan

**Contoh Data:**
```
- Gedung A - Lantai 1 (Bandung)
- Gedung A - Lantai 2 (Bandung)
- Gedung B - Ruang VIP (Bandung)
- Kantor Cabang - Jakarta (Jakarta)
- Kantor Cabang - Surabaya (Surabaya)
- dll...
```

---

### 2. **KelaSeeder**
**File:** `database/seeders/KelaSeeder.php`

**Fungsi:** Menambahkan data master kelas/classroom

**Data yang Ditambahkan:**
- 10 ruang kelas dengan berbagai tipe dan kapasitas
- Masing-masing kelas dilengkapi: nama, kapasitas, fasilitas, keterangan

**Contoh Data:**
```
- Kelas A-101 (Kapasitas: 20, Proyektor, Whiteboard, AC)
- Kelas A-102 (Kapasitas: 20, Proyektor, Whiteboard, AC)
- Kelas B-201 (Kapasitas: 15, Smart TV, Whiteboard Digital)
- Kelas VIP - Ruang Premium (Kapasitas: 10, Full Facilities)
- Ruang Seminar - Grand Hall (Kapasitas: 150, Multiple Projectors)
- dll...
```

---

### 3. **ScoreSeeder**
**File:** `database/seeders/ScoreSeeder.php`

**Fungsi:** Menambahkan sample data nilai/score untuk peserta yang sudah terdaftar

**Data yang Ditambahkan:**
- Nilai skill: Listening, Speaking, Reading, Writing
- Nilai assignment
- Final score (rata-rata)
- Status: pass/fail/pending
- Instruktur pengevaluasi
- Tanggal evaluasi
- Feedback/keterangan

**Fitur:**
- Hanya menambah score untuk pendaftaran yang belum memiliki score
- Maksimal 50 pendaftaran akan diberi score
- Data score dibuat random dengan nilai realistis (40-100)
- Status disesuaikan dengan score final
- Instruktur pengevaluasi dipilih secara random

---

## 🚀 Cara Menjalankan Seeder

### **PENTING: Jalankan Migration TERLEBIH DAHULU**

Sebelum menjalankan seeder, pastikan sudah menjalankan migration untuk tabel-tabel baru:

```bash
php artisan migrate
```

### **Opsi 1: Jalankan Seeder Individual**

Untuk menjalankan seeder satu per satu:

```bash
# Hanya Lokasi
php artisan db:seed --class=LokasiSeeder

# Hanya Kelas
php artisan db:seed --class=KelaSeeder

# Hanya Score
php artisan db:seed --class=ScoreSeeder
```

### **Opsi 2: Jalankan Semua Seeder Bersama**

Untuk menjalankan semua seeder (termasuk yang ada di DatabaseSeeder):

```bash
php artisan db:seed
```

Ini akan menjalankan `DatabaseSeeder.php` yang sudah diupdate untuk memanggil ketiga seeder baru.

### **Opsi 3: Fresh Database (Hapus & Buat Ulang)**

Jika ingin reset database sepenuhnya:

```bash
php artisan migrate:fresh
php artisan db:seed
```

⚠️ **PERINGATAN:** `migrate:fresh` akan **MENGHAPUS SEMUA DATA** di database yang sudah ada!

---

## 📊 Total Data yang Akan Ditambahkan

| Seeder | Tabel | Jumlah Record |
|--------|-------|---------------|
| LokasiSeeder | lokasis | 8 |
| KelaSeeder | kelas | 10 |
| ScoreSeeder | scores | Max 50 |
| DatabaseSeeder | users, pesertas, kursuses, dll | Auto-generated |

---

## ✅ Checklist Sebelum Menjalankan

- [ ] Pastikan sudah running server/artisan
- [ ] Pastikan database sudah dibuat
- [ ] Jalankan migration: `php artisan migrate`
- [ ] Verifikasi migrasi berhasil
- [ ] Baru jalankan seeder: `php artisan db:seed`

---

## 🔍 Verifikasi Data Setelah Seeding

Setelah seeding, Anda bisa mengecek data dengan:

```bash
# Terminal Tinker - Interactive Shell
php artisan tinker

# Lihat semua lokasi
>>> App\Models\Lokasi::all()

# Lihat semua kelas
>>> App\Models\Kela::all()

# Lihat semua scores
>>> App\Models\Score::all()

# Keluar
>>> exit
```

---

## 🛠️ Troubleshooting

### Error: "Class 'App\Models\Lokasi' not found"
**Solusi:** Pastikan model sudah dibuat dan file ada di `app/Models/`

### Error: "SQLSTATE[HY000]: General error: 1 table..."
**Solusi:** Migration belum dijalankan. Jalankan `php artisan migrate` terlebih dahulu

### Error: "Trying to get property of non-object"
**Solusi:** Pastikan ada instruktur di database sebelum menjalankan ScoreSeeder

### Data Duplikat
**Solusi:** ScoreSeeder menggunakan `firstOrCreate`, jadi data tidak akan duplikat jika dijalankan berkali-kali

---

## 📝 File Structure

```
database/
├── seeders/
│   ├── DatabaseSeeder.php          (Updated - memanggil 3 seeder baru)
│   ├── LokasiSeeder.php            ✅ NEW
│   ├── KelaSeeder.php              ✅ NEW
│   └── ScoreSeeder.php             ✅ NEW
│
└── migrations/
    ├── 2026_02_10_000001_create_lokasis_table.php
    ├── 2026_02_10_000002_create_kelas_table.php
    ├── 2026_02_10_000003_create_scores_table.php
    └── 2026_02_10_000004_add_lokasi_and_kela_to_jadwals_table.php
```

---

## 🎯 Urutan Eksekusi Yang Direkomendasikan

### Untuk Fresh Installation:
```bash
# Step 1: Bersihkan database
php artisan migrate:fresh

# Step 2: Jalankan semua seeder
php artisan db:seed

# Done! Database siap digunakan
```

### Untuk Database yang Sudah Ada:
```bash
# Step 1: Jalankan migration baru saja
php artisan migrate

# Step 2: Jalankan seeder pilihan
php artisan db:seed --class=LokasiSeeder
php artisan db:seed --class=KelaSeeder
php artisan db:seed --class=ScoreSeeder

# Done!
```

---

## 📞 Notes

- **LokasiSeeder** dan **KelaSeeder** menggunakan `firstOrCreate`, jadi aman untuk dijalankan berkali-kali tanpa duplikasi
- **ScoreSeeder** hanya menambah score untuk pendaftaran yang belum punya score
- Semua seeder sudah terintegrasi dengan baik dalam `DatabaseSeeder.php`
- Data yang dihasilkan realistis dan sesuai dengan business logic aplikasi

