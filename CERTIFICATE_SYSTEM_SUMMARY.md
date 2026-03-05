# E-Certificate System - Complete Reference

**Status**: ✅ **FULLY IMPLEMENTED & TESTED**

---

## 📚 Documentation Files

Your e-certificate system is comprehensive! Here's where to find everything:

| File | Purpose |
|------|---------|
| **[E_CERTIFICATE_DOCUMENTATION.md](E_CERTIFICATE_DOCUMENTATION.md)** | Full system architecture, models, jobs, views, all components explained |
| **[ADMIN_UI_QUICKSTART.md](ADMIN_UI_QUICKSTART.md)** | How admins use the system - step-by-step guides for all operations |
| **[ADVANCED_FEATURES.md](ADVANCED_FEATURES.md)** | In-depth explanation of the 5 advanced features (templates, batch, signature, expiry, analytics) |

---

## 🎯 Quick Access - What To Do Next

### For System Admins
1. **Issue Single Certificate**  
   → Go to `/admin/certificates` → Click "+ Terbitkan Sertifikat" → Select peserta & kursus → Submit

2. **Issue Multiple at Once**  
   → Go to `/admin/certificates` → Click "📋 Massal" → Select course → Multi-select peserta → Submit

3. **Create Custom Certificate Template**  
   → Go to `/admin/certificates` → Click "🎨 Template" → Create → Design HTML with {{placeholders}}

4. **View Statistics & Trends**  
   → Go to `/admin/certificates` → Click "📊 Analytics" → Choose period → See charts & export CSV

5. **Revoke a Certificate**  
   → Go to `/admin/certificates` → Click certificate → Click "✗ Cabut Sertifikat" → Provide reason

### For End Users (Peserta)
1. **Download Certificate**  
   → Receive link in email (when implemented) → Click link → Download PDF from verification page

2. **Share Certificate**  
   → Share the `/verify/{code}` link or QR code with anyone (no login needed)

3. **Check if Certificate is Valid**  
   → Visit `/verify/{code}` → See status (aktif/kadaluarsa/dicabut)

---

## 📊 System Components

```
E-Certificate System
├── Database
│   ├── certificates
│   ├── certificate_templates
│   └── (relationships to peserta, kursus, users)
├── Models & Logic
│   ├── Certificate (with expiry checking, validity methods)
│   ├── CertificateTemplate (per-course designs)
│   └── Relationship helpers
├── Queue Jobs
│   └── GenerateCertificateJob (renders HTML → PDF with custom templates)
├── Admin Controllers
│   ├── CertificateAdminController (CRUD + revoke)
│   ├── CertificateTemplateController (template management)
│   ├── CertificateBatchController (bulk issuance)
│   └── CertificateAnalyticsController (stats & export)
├── Public Pages
│   ├── /verify/{code} (certificate verification)
│   └── /certificate/{id}/download (PDF download)
└── Admin Dashboard
    ├── /admin/certificates (main list & filters)
    ├── /admin/certificate-templates (design templates)
    └── /admin/certificates/analytics/dashboard (trends & stats)
```

---

## 🔑 5 Advanced Features Explained

### 1️⃣ Certificate Templates Per Course
- **What**: Each course can have its own certificate design
- **How**: Create template in `/admin/certificate-templates` → Set course + HTML → Save
- **Benefit**: Different courses → different designs (e.g., formal vs. informal)
- **Fallback**: If no template for course → use global default

### 2️⃣ Batch Issue for Multiple Peserta
- **What**: Issue 50 certificates at once instead of one-by-one
- **How**: `/admin/certificates/batch/create` → Select course → Upload CSV OR multi-select peserta → Submit
- **CSV Format**: Plain text, one peserta ID per line
- **Benefit**: Saves time, prevents duplicates automatically

### 3️⃣ Digital Signature on PDF
- **What**: Add signature image to every certificate automatically
- **How**: When creating template → Upload signature image → It auto-embeds on all PDFs
- **Placeholder**: Use `{{SIGNATURE}}` in HTML template
- **Benefit**: Professional look, authenticates certificates

### 4️⃣ Certificate Expiry Date
- **What**: Certificates can expire (e.g., after 1 year)
- **How**: When issuing → Set "Masa Berlaku (Hari)" field → e.g., 365 days
- **Display**: Shows expiry date on certificate detail & verification page
- **Alerts**: Analytics shows certificates expiring soon & already expired
- **Benefit**: Keep certifications current, track validity period

### 5️⃣ Analytics Dashboard
- **What**: See statistics, trends, and per-course breakdown
- **How**: Go to `/admin/certificates` → Click "📊 Analytics"
- **Shows**: Total issued, by-course breakdown, trend chart, expiry alerts, recent activity
- **Export**: Download CSV with all certificate data
- **Benefit**: Track growth, identify issues, make decisions with data

---

## 🚀 Getting Started (5 Min Setup)

1. **Verify database is updated**:
   ```bash
   php artisan migrate
   ```

2. **Create a default template** (one-time):
   - Go to `/admin/certificate-templates/create` (as admin)
   - Name: "Default Template"
   - HTML: Copy the template from `resources/views/certificates/template.blade.php`
   - Mark as default
   - Submit

3. **Process any pending certificates**:
   ```bash
   php artisan queue:work --once
   ```

4. **Test issuance**:
   - Go to `/admin/certificates`
   - Click "+ Terbitkan Sertifikat"
   - Select any peserta & kursus
   - Submit
   - Run queue: `php artisan queue:work --once`
   - Check PDF appeared in `/admin/certificates` list

5. **Try batch**:
   - Click "📋 Massal"
   - Select course & check 5-10 peserta
   - Set validity (e.g., 365 days)
   - Submit
   - Run queue

6. **Check analytics**:
   - Click "📊 Analytics"
   - See stats, charts, trends

**Done!** System is now ready to use.

---

## 📋 All Routes at a Glance

**Public (No Login) Routes**:
```
GET  /verify/{code}                    Certificate verification
GET  /certificate/{id}/download         Download PDF
```

**Admin Routes** (auth + admin role):
```
# Main management
GET  /admin/certificates                List certificates
GET  /admin/certificates/create         Create form
POST /admin/certificates                Submit create
GET  /admin/certificates/{id}           Detail page
GET  /admin/certificates/{id}/revoke    Revoke form
PUT  /admin/certificates/{id}/revoke    Submit revoke
POST /admin/certificates/{id}/regenerate Regenerate PDF

# Batch processing
GET  /admin/certificates/batch/create   Batch form
POST /admin/certificates/batch          Submit batch

# Templates
GET    /admin/certificate-templates             List
GET    /admin/certificate-templates/create      Create
POST   /admin/certificate-templates             Store
GET    /admin/certificate-templates/{id}/edit   Edit
PUT    /admin/certificate-templates/{id}        Update
DELETE /admin/certificate-templates/{id}        Delete

# Analytics
GET /admin/certificates/analytics/dashboard  Dashboard
GET /admin/certificates/analytics/export     CSV export
```

---

## 🛠️ Maintenance & Operations

### Regular Tasks
- **Monitor Queue**: Check for failed jobs → `php artisan queue:failed`
- **Retry Failed Jobs**: `php artisan queue:retry all`
- **Clean Old PDFs**: Archive certificates older than X years (optional)
- **Backup Signatures**: Backup `storage/app/signatures/` folder

### Logs & Troubleshooting
- **Application Logs**: `storage/logs/laravel.log`
- **Queue Issues**: Check `jobs` and `failed_jobs` tables
- **Storage Issues**: Verify `storage/app/certificates/` and `storage/app/signatures/` are writable

### Performance Tuning
- If slow: Use Redis for queue (`QUEUE_CONNECTION=redis`)
- If many templates: Consider caching in memory
- If many analytics queries: Add database indexes on `status`, `kursus_id`, `created_at`

---

## 🔐 Security Checklist

- ✅ All admin routes protected by `auth` + `admin` middleware
- ✅ Public verification page shows only non-sensitive data
- ✅ PDF files stored in private storage (not web-accessible)
- ✅ Verification codes are random (12 hex chars, not predictable)
- ✅ CSV import validates peserta IDs against database
- ✅ Signature uploads stored safely in `storage/`
- ✅ All user inputs validated & escaped
- ✅ No SQL injection possible (Eloquent ORM)

**Additional Security (Optional)**:
- Sign URLs for PDF download: `Storage::disk('local')->temporaryUrl(...)`
- Require re-authentication for sensitive actions (revoke, delete)
- Log all admin actions (audit trail)

---

## 📞 Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| PDF not generating | Run `php artisan queue:work --once` |
| "403 Unauthorized" | Ensure user has `role = 'admin'` in database |
| Template not showing | Check `is_default = true` OR set `kursus_id` to match course |
| Signature not visible | Verify file uploaded (check `storage/app/signatures/`) |
| Batch import failing | Check CSV format (one ID per line, no header) |
| Analytics slow | Try different period filter or add database indexes |
| Expiry not calculating | Ensure `validity_days` field is set when issuing |

---

## 📚 Code Examples

### Manually Issue Certificate (CLI)
```bash
php artisan certificate:issue 1 1    # peserta_id=1, kursus_id=1
php artisan queue:work --once        # Process it
```

### Check Certificate Validity (Code)
```php
$cert = Certificate::find(1);
$cert->isValid();            // true/false
$cert->getExpiryStatus();    // 'active', 'expired', 'none'
$cert->daysUntilExpiry();    // 365, -10, or null
```

### Create Template Programmatically
```php
CertificateTemplate::create([
    'name' => 'Balai Standard',
    'kursus_id' => 1,
    'html_template' => '<html>...</html>',
    'is_default' => false,
]);
```

### Query Expiring Certificates
```php
Certificate::where('status', 'generated')
    ->where('expires_at', '!=', null)
    ->where('expires_at', '<=', now()->addDays(7))
    ->with('peserta', 'kursus')
    ->get();
```

---

## 🎓 Learning Resources

**To understand the flow**:
1. Read: `E_CERTIFICATE_DOCUMENTATION.md` - Architecture section
2. Study: `app/Models/Certificate.php` - Data model
3. Trace: `app/Jobs/GenerateCertificateJob.php` - PDF generation
4. Test: Create a certificate manually and check logs

**To customize**:
1. Edit: `resources/views/certificates/template.blade.php` - Default design
2. Create: New templates in admin panel - Per-course design
3. Modify: `app/Jobs/GenerateCertificateJob.php` - Advanced rendering

**To extend**:
- Add email notifications (create event/listener)
- Add API endpoints for external systems
- Add digital signature (PKI) for legal validity
- Add certificate renewal/re-issuance workflow

---

## ✅ Feature Completeness

| Feature | Status | Used For |
|---------|--------|----------|
| Auto-issue on registration | ✅ Done | Every peserta gets cert when registered |
| Manual single issue | ✅ Done | Admin issues one by one |
| Batch issue (multi-select) | ✅ Done | Admin issues 50 at once |
| Batch issue (CSV) | ✅ Done | Admin uploads file with IDs |
| Per-course templates | ✅ Done | Different designs per course |
| Global default template | ✅ Done | Fallback when no per-course template |
| Digital signature | ✅ Done | Add signature image to PDF |
| Expiry date | ✅ Done | Validity period tracking |
| Verification page | ✅ Done | Public access to verify cert |
| Revocation | ✅ Done | Admin can revoke with reason |
| Analytics dashboard | ✅ Done | Trends, per-course, expiry alerts |
| CSV export | ✅ Done | Download certificate data |
| QR code (in PDF) | ✅ Done | Scan to verify |
| Email notification | ⏳ TODO | Send link when cert issued |

---

## 🎉 Summary

**You now have a production-ready e-certificate system with**:
- ✅ Core functionality (issuance, verification, download)
- ✅ Admin management UI (list, create, edit, revoke)
- ✅ 5 advanced features (templates, batch, signature, expiry, analytics)
- ✅ Security & validation throughout
- ✅ Queue-based PDF generation (background jobs)
- ✅ Comprehensive documentation

**Next Phase (Optional)**:
- Send emails when certificates are issued
- Add API for integration with external systems
- Implement digital signatures for legal validity

---

**All done! System is ready for production use.** 🚀

For specific usage steps: **See [ADMIN_UI_QUICKSTART.md](ADMIN_UI_QUICKSTART.md)**

For technical deep-dive: **See [ADVANCED_FEATURES.md](ADVANCED_FEATURES.md)**
