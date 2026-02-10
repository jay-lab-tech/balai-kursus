# Penambahan Tabel Database Baru

**Tanggal:** 10 Februari 2026  
**Status:** ✅ Selesai

---

## Tabel yang Ditambahkan

### 1. **Lokasi (lokasis)**
Function: Master data untuk lokasi/venue kursus

**File Migration:**  
`2026_02_10_000001_create_lokasis_table.php`

**Struktur:**
```php
- id (Primary Key)
- nama (String) - Nama lokasi
- alamat (String) - Alamat lengkap
- no_telp (String, Nullable) - Nomor telepon
- kota (String, Nullable) - Kota
- provinsi (String, Nullable) - Provinsi
- keterangan (Text, Nullable) - Keterangan tambahan
- timestamps
```

**Model:** `App\Models\Lokasi`  
**Table Name:** `lokasis`

**Relationships:**
```php
- hasMany(Jadwal) : jadwals
```

---

### 2. **Kelas (Classroom)**
Function: Master data untuk ruang/kelas tempat kursus

**File Migration:**  
`2026_02_10_000002_create_kelas_table.php`

**Struktur:**
```php
- id (Primary Key)
- nama (String) - Nama kelas
- kapasitas (Integer, Nullable) - Kapasitas ruangan
- fasilitas (String, Nullable) - Fasilitas yang tersedia
- keterangan (Text, Nullable) - Keterangan tambahan
- timestamps
```

**Model:** `App\Models\Kela`  
**Table Name:** `kelas`

---

### 3. **Scores (Academic Evaluation)**
Function: Menyimpan nilai dan evaluasi akademik peserta

**File Migration:**  
`2026_02_10_000003_create_scores_table.php`

**Struktur:**
```php
- id (Primary Key)
- pendaftaran_id (Foreign Key) → pendaftarans.id (CASCADE ON DELETE)
- listening (Integer, Nullable) - Nilai listening skill (0-100)
- speaking (Integer, Nullable) - Nilai speaking skill (0-100)
- reading (Integer, Nullable) - Nilai reading skill (0-100)
- writing (Integer, Nullable) - Nilai writing skill (0-100)
- assignment (Integer, Nullable) - Nilai tugas/assignment
- final_score (Decimal 5,2, Nullable) - Skor akhir
- status (Enum: 'pass', 'fail', 'pending', Default: 'pending')
- evaluated_by (Foreign Key, Nullable) → instrukturs.id - Instruktur pengevaluasi
- evaluated_at (Date, Nullable) - Tanggal evaluasi
- keterangan (Text, Nullable) - Catatan/feedback
- timestamps
```

**Model:** `App\Models\Score`

**Relationships:**
```php
- belongsTo(Pendaftaran)
- belongsTo(Instruktur, 'evaluated_by')
```

**Helper Methods:**
```php
- getAverageScore() : float|null - Menghitung rata-rata nilai skill
```

---

### 4. **Jadwal Update**
Migration: `2026_02_10_000004_add_lokasi_and_kela_to_jadwals_table.php`

**Kolom Baru:**
- `lokasi_id` (Foreign Key, Nullable) → lokasis.id  
- `kela_id` (Foreign Key, Nullable) → kelas.id

**Relationships Ditambahkan:**
```php
- belongsTo(Lokasi)
- belongsTo(Kela)
```

---

## Model Relationships (Relasi Update)

### Pendaftaran Model
**Relationship Baru:**
```php
public function scores()
{
    return $this->hasMany(Score::class);
}
```

### Jadwal Model
**Updated $fillable:**
```php
protected $fillable = [
    'kursus_id', 'lokasi_id', 'kela_id', 'pertemuan_ke', 
    'tgl_pertemuan', 'jam_mulai', 'jam_selesai', 'created_by'
];
```

**Relationships Baru:**
```php
public function lokasi()
{
    return $this->belongsTo(Lokasi::class);
}

public function kela()
{
    return $this->belongsTo(Kela::class);
}
```

---

## Langkah Selanjutnya

### 1. Jalankan Migration
```bash
php artisan migrate
```

### 2. Buat Factory (Opsional)
Untuk testing/seeding data dummy:
```bash
php artisan make:factory LokasiFactory --model=Lokasi
php artisan make:factory KelaFactory --model=Kela
php artisan make:factory ScoreFactory --model=Score
```

### 3. Buat Seeder (Opsional)
Untuk mengisi data master (lokasi, kelas):
```bash
php artisan make:seeder LokasiSeeder
php artisan make:seeder KelaSeeder
php artisan tinker
```

### 4. Map Data dari Database Lama
Gunakan migration script untuk transfer data dari:
- Old `kursus.id_lokasi` → New `jadwals.lokasi_id`
- Old `kursus.id_kelas` → New `jadwals.kela_id`
- Old scoring fields → New `scores` table

---

## Files Created

### Migration Files
- ✅ `database/migrations/2026_02_10_000001_create_lokasis_table.php`
- ✅ `database/migrations/2026_02_10_000002_create_kelas_table.php`
- ✅ `database/migrations/2026_02_10_000003_create_scores_table.php`
- ✅ `database/migrations/2026_02_10_000004_add_lokasi_and_kela_to_jadwals_table.php`

### Model Files
- ✅ `app/Models/Lokasi.php`
- ✅ `app/Models/Kela.php`
- ✅ `app/Models/Score.php`

### Files Modified
- ✅ `app/Models/Pendaftaran.php` - Added `scores()` relationship
- ✅ `app/Models/Jadwal.php` - Updated fillable & added relationships

---

## Database Diagram (Updated)

```
users
  ├── pesertas (user_id FK)
  |
programs
  └── kursuses (program_id FK)
        ├── levels (level_id FK)
        ├── instrukturs (instruktur_id FK)
        └── pendaftarans (kursus_id FK)
              ├── pesertas (peserta_id FK)
              ├── pembayarans (pendaftaran_id FK)
              ├── payments (pendaftaran_id FK)
              ├── absensis (pendaftaran_id FK)
              │     └── jadwals (jadwal_id FK)
              └── scores (pendaftaran_id FK) ⭐ NEW
                    └── instrukturs (evaluated_by FK)

jadwals (jadwal_id PK)
  ├── kursuses (kursus_id FK)
  ├── lokasis (lokasi_id FK) ⭐ NEW
  └── kelas (kela_id FK) ⭐ NEW

risalahs
  ├── kursuses (kursus_id FK)
  └── jadwals (jadwal_id FK)
```

---

## Verifikasi Kelengkapan Database

| Fitur | Status | Notes |
|-------|--------|-------|
| User Management | ✅ | users table |
| Course Management | ✅ | kursuses, programs, levels |
| Instructor Management | ✅ | instrukturs |
| Participant Management | ✅ | pesertas, pendaftarans |
| **Scheduling** | ✅ | jadwals + lokasis + kelas |
| **Location/Venue Master** | ✅ | lokasis |
| **Classroom Master** | ✅ | kelas |
| **Academic Scoring** | ✅ | scores |
| Payment Management | ✅ | pembayarans, payments |
| Attendance | ✅ | absensis |
| Meeting Documentation | ✅ | risalahs |

---

## Checklist Implementasi

- [x] Buat migration untuk Lokasi (lokasis)
- [x] Buat migration untuk Kelas
- [x] Buat migration untuk Scores
- [x] Update migration Jadwal (add lokasi_id, kela_id)
- [x] Buat Model Lokasi
- [x] Buat Model Kela
- [x] Buat Model Score
- [x] Update Model Pendaftaran (add scores relationship)
- [x] Update Model Jadwal (add relationships)
- [ ] Run migrations: `php artisan migrate`
- [ ] Create factories for testing (optional)
- [ ] Create seeders for master data (optional)
- [ ] Data migration from old database (if needed)

