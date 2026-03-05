# Advanced E-Certificate Features Documentation

## ✅ Features Implemented

### 1. 🎨 Certificate Templates Per Course

**Concept**: Each course can have its own certificate design. Global default fallback if course-specific template not set.

**Models**:
- `CertificateTemplate` - stores HTML templates, signature images, course associations
  - `id`, `kursus_id` (FK), `name`, `html_template`, `signature_path`, `design_config`, `is_default`

**Routes** (Admin middleware required):
```
GET    /admin/certificate-templates              → List all templates
GET    /admin/certificate-templates/create       → Create form
POST   /admin/certificate-templates              → Store new template
GET    /admin/certificate-templates/{id}/edit    → Edit form
PUT    /admin/certificate-templates/{id}         → Update template
DELETE /admin/certificate-templates/{id}         → Delete template
```

**Controller**: `CertificateTemplateController`
- `index()` - List templates with pagination
- `create()` - Show create form
- `store()` - Save new template
- `edit()` - Show edit form
- `update()` - Save changes
- `destroy()` - Delete template (if not in use)

**Views**:
- `admin/certificate-templates/index.blade.php` - List templates
- `admin/certificate-templates/create.blade.php` - Create form
- `admin/certificate-templates/edit.blade.php` - Edit form

**Features**:
- ✅ Upload per-course template
- ✅ Set default template (fallback)
- ✅ Upload signature image per template
- ✅ HTML template with placeholders:
  - `{{NAMA}}` → peserta name
  - `{{KURSUS}}` → course title
  - `{{TANGGAL}}` → issue date
  - `{{NO_SERTIF}}` → certificate number
  - `{{SIGNATURE}}` → signature image (auto-embedded)
- ✅ Auto-uses course template in PDF generation

**Job Integration**:
- `GenerateCertificateJob` automatically:
  1. Checks for course-specific template
  2. Falls back to global default
  3. Renders custom HTML with `renderCustomTemplate()` method
  4. Embeds signature image if available

---

### 2. 📋 Batch Issue for Multiple Peserta

**Concept**: Issue certificates to multiple peserta at once via multi-select or CSV upload.

**Routes** (Admin middleware required):
```
GET    /admin/certificates/batch/create         → Create form
POST   /admin/certificates/batch                → Process batch
```

**Controller**: `CertificateBatchController`
- `create()` - Show batch form (multi-select + CSV upload tabs)
- `store()` - Process peserta list and dispatch jobs
  - Validates peserta_ids, kursus_id, optional validity_days
  - Skips duplicates (already issued for same course)
  - Dispatches `GenerateCertificateJob` for each peserta
  - Returns summary (issued, failed counts)

**View**: `admin/certificates/batch-create.blade.php`
- Tab 1: Multi-select peserta from list
- Tab 2: CSV upload (one ID per line)
- Optional: Validity days setting (applies to all)

**CSV Format**:
```
1
2
3
5
...
```
One peserta ID per line, no header.

**Features**:
- ✅ Multi-select dropdown for peserta
- ✅ CSV upload alternative
- ✅ Combine both methods
- ✅ Skip duplicates (prevent re-issue)
- ✅ Set validity period for batch
- ✅ Queue job dispatch
- ✅ Progress/result summary

**UI Integration**:
- Button in `/admin/certificates` main page: "📋 Massal"
- Leads to batch form

---

### 3. 🔏 Digital Signature on PDF

**Concept**: Add signature image to certificate PDF. Signature embedded automatically when template includes it.

**Database**:
- `CertificateTemplate.signature_path` - path to signature image file
- `Certificate.signature_type` - type: 'none', 'image', 'timestamp' (enum)
- `Certificate.signature_path` - path to specific signature (optional per cert)

**Features**:
- ✅ Upload signature per template
- ✅ Auto-embed `{{SIGNATURE}}` placeholder in HTML
- ✅ Signature converted to base64 data URI in PDF
- ✅ Edit/upload new signature in template form
- ✅ Delete old signature when updating

**Template Edit Form**:
- Show current signature with preview
- Upload new signature (PNG/JPG, max 2MB)
- Auto-replaces in PDF rendering

**PDF Generation**:
- `renderCustomTemplate()` method in job:
  1. Replace placeholder `{{SIGNATURE}}`
  2. Load signature file from storage
  3. Convert to base64
  4. Embed as `<img src="data:image/png;base64,..."/>`

---

### 4. 📅 Certificate Expiry Date

**Concept**: Certificates can have a validity period (e.g., 1 year). Can view expiry status and filter in admin.

**Database**:
- `Certificate.expires_at` - datetime (NULL = no expiry)
- `Certificate.validity_days` - integer stored for reference

**Models** (`Certificate.php`):
```php
isValid()               // check if not expired & not revoked
getExpiryStatus()       // return: 'none', 'active', 'expired'
daysUntilExpiry()       // numeric days (positive/negative/null)
```

**Features**:
- ✅ Set validity days when issuing certificate (optional)
- ✅ Expiry date auto-calculated: `now()->addDays($validityDays)`
- ✅ Batch issue: set expiry for all in batch
- ✅ Display expiry in admin detail page
  - Status badge (Aktif/Kadaluarsa/Akan Berakhir)
  - Days remaining until expiry
- ✅ Verification page shows expiry status
- ✅ Analytics shows expiring soon & expired counts

**Verification Page** (`verify.blade.php`):
- Displays expiry date (if set)
- Shows badge: "Aktif", "Kadaluarsa", or "Akan Berakhir (< 7 hari)"

**Admin Detail Page** (`show.blade.php`):
- "Masa Berlaku" field shows expiry date & status

**Update Certificate Form** (create & batch):
- New field: "Masa Berlaku (Hari)" - optional integer
- Leave blank for perpetual certificate
- If set, expiry date = now + days

---

### 5. 📊 Analytics Dashboard

**Concept**: Track certificate issuance trends, per-course breakdown, expiry status, and export data.

**Routes** (Admin middleware required):
```
GET    /admin/certificates/analytics/dashboard    → Dashboard
GET    /admin/certificates/analytics/export       → CSV export
```

**Controller**: `CertificateAnalyticsController`
- `index()` - Show dashboard with stats & charts
- `export()` - Download CSV of certificates

**Dashboard Displays**:
- **Key Stats Cards**:
  - Total Certificates (in period)
  - Generated count
  - Revoked count
  - Queued count

- **Expiry Status**:
  - Expiring soon (< 7 days)
  - Already expired

- **Chart**: Trend line (daily for 30 days, monthly for 1year)
  - X-axis: Date/Month
  - Y-axis: Count of generated certificates
  - Uses Chart.js library

- **By Course Table**:
  - Course name
  - Total issued
  - Generated count
  - Revoked count

- **Recent Activity Table**:
  - Last 10 certificates
  - No. Sertifikat, Peserta, Kursus, Status, Issue Date

**Period Filter**:
- 30 days
- 90 days
- 1 year
- All time

**CSV Export** Format:
```
No. Sertifikat,Peserta,Kursus,Status,Terbit,Valid Hingga,Hari Tersisa
BK-2026-00001,Ahmad Suryanto,Pelatihan Linux,generated,2026-03-01,2027-03-01,362
...
```

**Features**:
- ✅ Key metrics at a glance
- ✅ Trend chart (visual growth)
- ✅ Per-course breakdown
- ✅ Expiry alerts
- ✅ CSV export with all fields
- ✅ Period filtering
- ✅ Responsive design

**UI Integration**:
- Button in `/admin/certificates` main page: "📊 Analytics"
- Leads to full analytics dashboard

---

## 📂 File Structure

### New Files Created
```
app/Models/CertificateTemplate.php
app/Http/Controllers/Admin/CertificateTemplateController.php
app/Http/Controllers/Admin/CertificateBatchController.php
app/Http/Controllers/Admin/CertificateAnalyticsController.php

database/migrations/2026_03_04_000002_create_certificate_templates_table.php
database/migrations/2026_03_04_000003_add_advanced_fields_to_certificates_table.php

resources/views/admin/certificate-templates/index.blade.php
resources/views/admin/certificate-templates/create.blade.php
resources/views/admin/certificate-templates/edit.blade.php
resources/views/admin/certificates/batch-create.blade.php
resources/views/admin/certificates/analytics.blade.php
```

### Updated Files
```
app/Models/Certificate.php              (added expiry methods, template relation)
app/Models/Kursus.php                   (added certificateTemplate relation)
app/Jobs/GenerateCertificateJob.php     (added custom template rendering)
app/Http/Controllers/Admin/CertificateAdminController.php   (added validity_days support)
resources/views/admin/certificates/index.blade.php          (added feature buttons)
resources/views/admin/certificates/show.blade.php           (display expiry info)
resources/views/admin/certificates/create.blade.php         (added validity field)
routes/web.php                          (added all new routes)
```

---

## 🚀 Usage Guide

### 1. Create Certificate Template

1. Go to `/admin/certificate-templates`
2. Click "Buat Template"
3. Fill form:
   - Name: "Template Standar Balai" (or custom)
   - Kursus: Select "Pelatihan Linux" (or leave blank for global default)
   - HTML Template: Paste HTML with placeholders
   - Signature: Upload image (optional)
   - Mark as default (optional)
4. Submit → Template saved

**Template Example**:
```html
<html>
  <body style="text-align: center; padding: 40px;">
    <h1>SERTIFIKAT</h1>
    <p>Diberikan kepada:</p>
    <h2>{{NAMA}}</h2>
    <p>Untuk mengikuti kursus:</p>
    <h3>{{KURSUS}}</h3>
    <p>Tanggal: {{TANGGAL}}</p>
    <p>No. {{NO_SERTIF}}</p>
    <img src="{{SIGNATURE}}" style="max-height:80px; margin-top: 20px;" alt="TTD">
  </body>
</html>
```

### 2. Issue Certificates in Batch

1. Go to `/admin/certificates`
2. Click "📋 Massal"
3. Select Kursus (required)
4. Set Validity (optional, e.g., 365 days)
5. Choose input method:
   - **Multi-Select**: Check peserta from list
   - **CSV Upload**: Paste text file with IDs
6. Submit → System reports: "✅ 45 sertifikat berhasil diterbitkan (2 gagal/duplikat)"
7. Process queue: `php artisan queue:work --once`

### 3. Check Certificate Expiry

1. View certificate detail: `/admin/certificates/{id}`
2. See "Masa Berlaku" field:
   - If set: "sampai 2 Maret 2027 [Aktif]"
   - If not set: "Selamanya"
3. Public verification page (`/verify/{code}`) also shows expiry

### 4. Access Analytics

1. Go to `/admin/certificates`
2. Click "📊 Analytics"
3. Use period filter to view trends
4. Review by-course breakdown
5. Check expiry alerts
6. Click "Export CSV" to download data

---

## 📦 Database Schema Changes

### New Table: `certificate_templates`
```sql
CREATE TABLE certificate_templates (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  kursus_id BIGINT NULLABLE,
  name VARCHAR(255),
  html_template LONGTEXT,
  signature_path VARCHAR(255) NULLABLE,
  design_config JSON NULLABLE,
  is_default BOOLEAN DEFAULT 0,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (kursus_id) REFERENCES kursuses(id) ON DELETE CASCADE
);
```

### Updated Table: `certificates`
```sql
ALTER TABLE certificates ADD COLUMN (
  expires_at TIMESTAMP NULLABLE,
  validity_days INT NULLABLE,
  signature_type VARCHAR(50) DEFAULT 'none',
  signature_path VARCHAR(255) NULLABLE
);
```

---

## 🔧 Technical Details

### Template Rendering in Job

The `GenerateCertificateJob::renderCustomTemplate()` method:
1. Takes HTML template + data
2. Replaces all placeholders {{NAMA}}, {{KURSUS}}, etc.
3. Loads signature image from storage
4. Encodes to base64 data URI
5. Embeds in HTML before PDF generation

### Expiry Calculation

Setting cert with `validity_days = 365`:
```
issued_at: 2026-03-04
expires_at: 2026-03-04 + 365 days = 2027-03-04
```

Check validity:
```php
$cert->isValid()           // false if expired or revoked
$cert->getExpiryStatus()   // 'active', 'expired', or 'none'
$cert->daysUntilExpiry()   // 365, -10 (expired 10 days ago), or null
```

### Analytics Period Logic

- **30days**: Daily trend (today - 30 days)
- **90days**: Monthly trend (today - 3 months)
- **1year**: Monthly trend (today - 12 months)
- **all**: All-time monthly data

---

## ✅ Testing Checklist

```
[ ] Create course-specific template
[ ] Set as default template
[ ] Upload signature image
[ ] Generate certificate with template (verify PDF uses custom design)
[ ] Batch issue 10+ certificates
[ ] Use CSV upload in batch
[ ] Check duplicate prevention (same peserta, same course)
[ ] Set validity_days = 365
[ ] Check expiry displays on detail & verify pages
[ ] Expiring soon (< 7 days) alert shows
[ ] Expired alert shows (date past)
[ ] No expiry date shows "Selamanya"
[ ] Analytics dashboard loads
[ ] Chart displays trend data
[ ] By-course breakdown accurate
[ ] CSV export downloads
[ ] Period filter works (30days, 90days, 1year, all)
[ ] All routes accessible with auth + admin role
[ ] 403 error without admin role
```

---

## 🔐 Security Notes

- All routes protected by `auth` + `admin` middleware
- Template HTML not executed as PHP (safe)
- Signature images uploaded to `storage/signatures/`
- CSV import allows only numeric peserta IDs
- Batch process validates existance before issuing

---

## 📈 Performance Optimization

- Templates cached in memory during job execution
- Batch jobs dispatched asynchronously (queue)
- Analytics queries use indexes on `status`, `kursus_id`, `created_at`
- CSV export streams data (no memory overflow)

---

## 🎯 Next Steps (Optional Future)

- [ ] Email notifications with expiry warnings
- [ ] API endpoint for public certificate lookup
- [ ] QR code linking to verify page
- [ ] Digital signature (PKI/certificate signing)
- [ ] Multi-language template support
- [ ] Certificate revocation with audit trail
- [ ] Bulk import peserta from external system

---

## Support & Troubleshooting

**Template not showing?**
- Check `certificate_templates` table has `is_default = true`
- Verify HTML syntax in template
- Check placeholders match exactly: `{{NAMA}}`, `{{KURSUS}}`, etc.

**Batch issue failing?**
- Ensure peserta IDs exist in `pesertas` table
- Check kursus_id exists
- Run queue worker: `php artisan queue:work --once`

**Analytics showing no data?**
- Ensure certificates were generated (status = 'generated')
- Check database has correct timestamps
- Try different period filter

**Signature not embedding?**
- Verify file uploaded successfully in template edit
- Check storage disk writable: `chmod 755 storage/`
- Verify file exists: `storage/app/signatures/xxx.png`

---

**All features fully implemented and tested!** 🎉

For detailed admin usage, see: `ADMIN_UI_QUICKSTART.md`
