# E-Sertifikat System Documentation

## Overview
Sistem e-sertifikat otomatis untuk peserta kursus di Balai Kursus. Sertifikat diterbitkan dalam format PDF dan dapat diverifikasi secara publik melalui kode verifikasi unik + QR code.

---

## Architecture & Flow

### 1. **Automatic Issuance** (Trigger)
Ketika peserta mendaftar kursus (`Pendaftaran` created):
- Otomatis membuat record di tabel `certificates` dengan status `queued`
- Job `GenerateCertificateJob` di-dispatch ke queue
- PDF di-generate saat job diproses

### 2. **Manual Issuance** (Admin)
Admin dapat menerbitkan sertifikat manual via:
- Route: `/admin/certificates/create`
- Pilih peserta dan kursus → submit → sertifikat diterbitkan

### 3. **Certificate Lifecycle**
```
queued → generated → [published/viewable] → revoked (optional)
```

---

## Database Schema

### `certificates` table
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | Primary key |
| no_sertifikat | string | Unique, format: `BK-YYYY-#####` |
| peserta_id | bigint | FK ke `pesertas` |
| kursus_id | bigint | FK ke `kursuses` |
| issued_at | timestamp | Tanggal penerbitan |
| generated_at | timestamp | Tanggal PDF di-generate |
| file_path | string | Path PDF di storage (local/S3) |
| verification_code | string | Kode unik untuk verifikasi publik |
| status | enum | `queued`, `generated`, `revoked` |
| revoked_reason | text | Alasan pencabutan (opsional) |
| revoked_at | timestamp | Tanggal pencabutan |
| revoked_by | bigint | FK ke `users` (admin yang cabut) |
| meta | json | Data tambahan |

---

## Key Components

### Models
- **`Certificate`**: Main model dengan relationships ke Peserta, Kursus, dan User (revokedBy)
- **`Pendaftaran`**: Boot listener untuk auto-issue sertifikat pada creation

### Jobs
- **`GenerateCertificateJob`**: Queue job yang:
  - Render template HTML ke PDF (wkhtmltopdf fallback dompdf)
  - Generate QR code untuk verifikasi
  - Simpan PDF ke storage
  - Update certificate status

### Controllers
- **`CertificateController`**: Public routes
  - `verify($code)`: Halaman verifikasi publik
  - `download($id)`: Unduh PDF sertifikat
- **`CertificateAdminController`**: Admin routes (middleware: auth + admin)
  - `index()`: List sertifikat dengan filter
  - `show($certificate)`: Detail sertifikat
  - `create()` & `store()`: Manual issuance
  - `editRevoke()` & `revoke()`: Pencabutan
  - `regenerate()`: Re-generate PDF

### Middleware
- **`AdminMiddleware`**: Cek role === 'admin'

### Views
- `certificates/template.blade.php`: Desain sertifikat PDF
- `certificates/verify.blade.php`: Halaman verifikasi publik
- `admin/certificates/index.blade.php`: List admin
- `admin/certificates/show.blade.php`: Detail + aksi
- `admin/certificates/revoke.blade.php`: Formulir pencabutan
- `admin/certificates/create.blade.php`: Manual issuance

### Routes
#### Public
- `GET /verify/{code}` → `certificate.verify` - Halaman verifikasi publik
- `GET /certificate/{id}/download` → `certificate.download` - Unduh PDF

#### Admin (requires auth + admin role)
- `GET /admin/certificates` → `admin.certificates.index` - List
- `GET /admin/certificates/create` → `admin.certificates.create` - Form manual issue
- `POST /admin/certificates` → `admin.certificates.store` - Submit manual issue
- `GET /admin/certificates/{certificate}` → `admin.certificates.show` - Detail
- `GET /admin/certificates/{certificate}/revoke` → `admin.certificates.editRevoke` - Form revoke
- `PUT /admin/certificates/{certificate}/revoke` → `admin.certificates.revoke` - Submit revoke
- `POST /admin/certificates/{certificate}/regenerate` → `admin.certificates.regenerate` - Re-generate

---

## Usage Guide

### For Users / Peserta
1. **Daftar Kursus**: Otomatis sertifikat di-queue saat pendaftaran
2. **Tunggu Generate**: Job queue memproses PDF (background)
3. **Unduh Verifikasi Link**: Dimulai dari email notifikasi (fitur belum diimplementasi)
4. **Share**: Kasih link `/verify/{code}` atau QR code ke pihak lain
5. **Download**: Dari halaman verifikasi publik atau akun pribadi

### For Admins
#### View & Manage
1. Go to `/admin/certificates`
2. Filter by status, kursus, atau search nama/no sertifikat
3. Klik "Detail" untuk melihat detail sertifikat
4. Download PDF, lihat QR, atau aksi lain

#### Manual Issue
1. Go to `/admin/certificates/create`
2. Pilih peserta dan kursus
3. Submit → sertifikat di-queue & PDF di-generate

#### Revoke
1. Go to `/admin/certificates/{id}` (detail page)
2. Klik "Cabut Sertifikat"
3. Isi alasan pencabutan (min 10 karakter)
4. Submit → status berubah ke `revoked`, tampil di verifikasi publik

#### Regenerate
1. Dari detail page, klik "Regenerate"
2. Status kembali ke `queued`, PDF di-reset
3. Job di-dispatch ulang

---

## PDF Generation

### Template
- File: `resources/views/certificates/template.blade.php`
- Format: HTML/Blade yang di-render ke PDF
- Menggunakan: wkhtmltopdf (jika tersedia) atau fallback DOMPDF

### Customization
Edit template untuk:
- Mengubah desain/layout
- Menambah logo, tanda tangan digital
- Ubah ukuran kertas (`setPaper('a4', 'landscape')`)

### Packages
- `barryvdh/laravel-dompdf`: Pure PHP PDF generator
- `endroid/qr-code`: QR code generation
- Optional: Install `wkhtmltopdf` di sistem untuk rendering lebih akurat

---

## Commands

### CLI Commands
```bash
# Manual issue via CLI
php artisan certificate:issue <peserta_id> <kursus_id>

# Process queue (background jobs)
php artisan queue:work
php artisan queue:work --once  # Process 1 job & exit

# Run migrations
php artisan migrate
php artisan migrate:refresh  # Reset (dev only)
```

---

## Broadcasting & Notifications (TODO)

### Email Notification (Not yet implemented)
Plan:
- Event `CertificateGenerated` fired saat status → `generated`
- Listener kirim email ke peserta dengan:
  - Link download `/certificate/{id}/download`
  - Link verifikasi `/verify/{code}`
  - QR code image

### Implementation steps (untuk tahap selanjutnya):
1. Create event: `App\Events\CertificateGenerated`
2. Create listener: `App\Listeners\SendCertificateEmail`
3. Register listener di `EventServiceProvider`
4. Update job: dispatch event setelah generated
5. Create mail template: `CertificateIssuedMail`

---

## Testing

### Local Testing
```bash
# Create test pendaftaran & sertifikat
php artisan tinker
>> $p = \App\Models\Peserta::first();
>> $k = \App\Models\Kursus::first();
>> $pend = \App\Models\Pendaftaran::create(['peserta_id' => $p->id, 'kursus_id' => $k->id, 'nomor' => 'TEST-001']);
# → Auto-creates certificate

# Or manual via CLI
php artisan certificate:issue 1 1

# Process queue
php artisan queue:work --once

# Check result
>> \App\Models\Certificate::latest()->first()->toArray();
```

### Verification
- Visit: `http://localhost/verify/{verification_code}`
- Download: `http://localhost/certificate/{id}/download`
- Admin: `http://localhost/admin/certificates`

---

## Production Considerations

### Storage
- Dev: `storage/app/certificates/`
- Prod: Consider S3 atau storage eksternal
  - Update `.env`: `FILESYSTEM_DISK=s3`
  - Configure S3 di `config/filesystems.php`
  - Use signed URLs untuk secure download

### Queue
- Dev: `QUEUE_CONNECTION=sync` (instant) atau `database`
- Prod: `redis` untuk performance
  - Setup Redis
  - `QUEUE_CONNECTION=redis`
  - Run queue worker: `php artisan queue:work redis`

### wkhtmltopdf
- Install di server: `apt-get install wkhtmltopdf` (Linux) atau download Windows installer
- Path harus di system PATH
- Fallback ke DOMPDF jika tidak tersedia

### Performance
- Queue workers for background processing
- Monitor job failures: `php artisan queue:failed`
- Cleanup old PDFs: Archive/delete after X years

### Security
- Verification code: Random 12 hex chars, tidak mudah ditebak
- Download: Private storage, requires auth (optional)
- QR code: Expired atau time-limited URL (optional)

---

## Troubleshooting

### PDF tidak generate
1. Check queue: `php artisan queue:work --once`
2. Check storage directory: `storage/app/certificates/` must exist
3. Check logs: `storage/logs/laravel.log`
4. If wkhtmltopdf missing: fallback to DOMPDF (jika ada)

### Sertifikat tidak muncul di admin
- Check migrations: `php artisan migrate:status`
- Check Peserta & Kursus exist di DB
- Check queue processed: `php artisan queue:work --once`

### Middleware auth errors
- Ensure admin user has `role = 'admin'`
- Check `app/Http/Middleware/AdminMiddleware.php`

---

## Next Steps

### Email Notifications
[ ] Send email when certificate generated  
[ ] Include download & verification links  
[x] Include QR code (in template) ← Done

### Advanced Features
[ ] Digital signature on PDF
[ ] Certificate templates per course
[ ] Batch issue for multiple peserta
[ ] Certificate validity period (exp date)
[ ] Integration with Peserta profile/dashboard
[ ] Analytics: certificates issued/revoked per course

### Tests
[ ] Unit tests for Certificate model
[ ] Feature tests for admin flow
[ ] API tests (if needed)

---

## Contact & Support
Untuk pertanyaan atau bug, hubungi tim development.
