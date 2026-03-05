# Admin Management UI - Quick Start Guide

## 🎯 What Was Built

A complete admin panel for managing e-sertifikat with the following features:
- ✅ List all certificates with filters (status, kursus, search)
- ✅ View certificate details with QR code preview
- ✅ Manually issue certificates to any peserta/kursus
- ✅ Revoke certificates with reason documentation
- ✅ Regenerate failed/revoked certificates
- ✅ Public verification page (shows revocation status)
- ✅ Download certificate PDF from admin panel

---

## 🚀 Quick Start

### 1. Access Admin Panel
```
URL: http://localhost/admin/certificates
Username: admin@balai.test
Password: [sesuai password di DB]
```

### 2. Routes Available
| Action | Route | Method |
|--------|-------|--------|
| List Certificates | `/admin/certificates` | GET |
| Create Form | `/admin/certificates/create` | GET |
| Submit Create | `/admin/certificates` | POST |
| View Detail | `/admin/certificates/{id}` | GET |
| Revoke Form | `/admin/certificates/{id}/revoke` | GET |
| Submit Revoke | `/admin/certificates/{id}/revoke` | PUT |
| Regenerate | `/admin/certificates/{id}/regenerate` | POST |

### 3. Manual Issue Certificate (Step-by-step)
1. Open `/admin/certificates`
2. Click "**+ Terbitkan Sertifikat**" button
3. Select Peserta from dropdown
4. Select Kursus from dropdown
5. Click "Terbitkan"
6. Sertifikat di-queue, PDF akan di-generate di background
7. Check status in list → should show "Generated" after queue processed

### 4. Process Background Jobs
```bash
# Process one job (for development/testing)
php artisan queue:work --once

# Or keep worker running
php artisan queue:work
```

### 5. Revoke Certificate (Step-by-step)
1. From `/admin/certificates`, click "Detail" for any certificate
2. Click "**✗ Cabut Sertifikat**" button
3. Provide reason (min 10 characters)
4. Click "Cabut Sertifikat" → confirmation dialog
5. Certificate status changes to "Revoked"
6. Reason is stored & visible in detail page
7. Public verification page shows revocation message

### 6. Regenerate Certificate
1. From detail page, if status is "Antri" (queued) or PDF missing:
2. Click "**🔄 Regenerate**" button
3. Confirmation dialog → click confirm
4. Status reset to queued, PDF cleared
5. Job dispatched again
6. Process with `php artisan queue:work --once`

---

## 📋 Features Detail

### List Page (`/admin/certificates`)
- **Filters**:
  - Search by No. Sertifikat or Peserta name
  - Filter by Status (Antri, Generated, Dicabut)
  - Filter by Kursus
- **Table**: Shows no, peserta, kursus, status, issued date, detail link
- **Pagination**: 20 items per page

### Detail Page (`/admin/certificates/{id}`)
- Left column: Full certificate info (peserta, kursus, dates, kode verifikasi, revocation info)
- Right column:
  - Download PDF button (if generated)
  - Public verification link
  - Regenerate button (conditional)
  - Revoke button (conditional)
  - QR code preview

### Create Page (`/admin/certificates/create`)
- Dropdown untuk memilih Peserta
- Dropdown untuk memilih Kursus
- Submit button → trigger job

### Revoke Page (`/admin/certificates/{id}/revoke`)
- Warning alert
- Text area untuk alasan pencabutan
- Confirm button (dengan JS confirm dialog)
- Back button

---

## 🗄️ Database Changes

### New Columns in `certificates` table:
```sql
ALTER TABLE certificates ADD COLUMN (
  revoked_reason TEXT NULL,
  revoked_at TIMESTAMP NULL,
  revoked_by BIGINT UNSIGNED NULL
);
```

### Migration Applied:
```
2026_03_04_000001_add_revoked_reason_to_certificates_table
```

Check status:
```bash
php artisan migrate:status
```

---

## 🔐 Middleware & Authorization

### Admin Middleware
**File**: `app/Http/Middleware/AdminMiddleware.php`

Checks:
- User must be authenticated (`auth` middleware)
- User must have `role = 'admin'` in `users` table

If not admin → **403 Unauthorized**

### Controller Protection
`CertificateAdminController` constructor:
```php
$this->middleware(['auth', 'admin']);
```

---

## 📝 public.verification Page

### URL Format
```
/verify/{verification_code}
```

### Example
```
/verify/bb94299c89ae
```

### Shows
- Status badge (Valid/Dicabut/Diproses)
- Certificate details (No, Peserta, Kursus, Tanggal, Status)
- Download button (if status = generated)
- **If Revoked**: Alert showing revocation date + reason

---

## 🧪 Testing Checklist

```
[ ] Admin login & access /admin/certificates
[ ] List page loads with filters working
[ ] Create new certificate manually
[ ] Queue process (php artisan queue:work --once)
[ ] Certificate PDF generated in storage/app/certificates/2026/
[ ] Detail page shows certificate info & QR
[ ] Download PDF works
[ ] Public /verify/{code} page accessible
[ ] Revoke certificate & provide reason
[ ] Detail page shows revocation info
[ ] Public /verify page shows revocation alert
[ ] Regenerate certificate → status resets to "Antri"
```

---

## 📦 Deployed Files

### Controllers
- `app/Http/Controllers/Admin/CertificateAdminController.php` (new)
- `app/Http/Controllers/CertificateController.php` (modified)

### Models
- `app/Models/Certificate.php` (modified - added columns/relationships)
- `app/Models/Pendaftaran.php` (modified - added boot listener)

### Middleware
- `app/Http/Middleware/AdminMiddleware.php` (new)
- `app/Http/Kernel.php` (modified - registered admin alias)

### Migrations
- `database/migrations/2026_03_04_000000_create_certificates_table.php`
- `database/migrations/2026_03_04_000001_add_revoked_reason_to_certificates_table.php`

### Jobs
- `app/Jobs/GenerateCertificateJob.php` (modified - wkhtmltopdf fallback)

### Commands
- `app/Console/Commands/IssueCertificate.php` (new)

### Routes
- `routes/web.php` (modified - added admin routes)

### Views
- `resources/views/admin/certificates/index.blade.php` (new)
- `resources/views/admin/certificates/show.blade.php` (new)
- `resources/views/admin/certificates/create.blade.php` (new)
- `resources/views/admin/certificates/revoke.blade.php` (new)
- `resources/views/certificates/verify.blade.php` (modified - added revocation info)
- `resources/views/certificates/template.blade.php` (modified - QR code update)

---

## 🐛 Troubleshooting

### "403 Unauthorized" error
- Check user role: `select id, email, role from users;` in DB
- Ensure admin user has `role = 'admin'`

### Admin page blank or 404
- Run migrations: `php artisan migrate`
- Check routes: `php artisan route:list | findstr certificate`

### PDF not generating
- Check queue: `php artisan queue:work --once`
- Check storage: `dir storage\app\certificates\`
- Check logs: `storage\logs\laravel.log`

### Certificate not appearing in admin list
- Ensure `Peserta` and `Kursus` exist in DB
- Check migrations run: `php artisan migrate:status`
- Create fake data for testing

### "Class not found" errors
- Run composer autoload: `composer dump-autoload`
- Check PHP syntax: `php -l app/path/to/file.php`

---

## 📚 Documentation
Full documentation: `E_CERTIFICATE_DOCUMENTATION.md`

---

## ✅ Summary

**Admin Management UI is now fully functional!**

- ✅ All routes registered & middleware configured
- ✅ All views created with proper Bootstrap styling
- ✅ Database migrations applied
- ✅ Admin controller with all CRUD operations
- ✅ Revocation workflow implemented
- ✅ Public verification page updated
- ✅ QR codes integrated in detail page

**Next step**: Implement email notifications (send link when cert generated).

---

Contact: [Development Team]
