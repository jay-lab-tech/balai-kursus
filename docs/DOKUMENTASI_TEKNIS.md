# Dokumentasi Teknis - Balai Kursus

**Versi:** 1.0  
**Terakhir Diperbarui:** Mei 2026  
**Maintenance:** Technical Team

---

## 📑 Daftar Isi

1. [Pengenalan Sistem](#pengenalan-sistem)
2. [Technology Stack](#technology-stack)
3. [Arsitektur Aplikasi](#arsitektur-aplikasi)
4. [Setup & Instalasi](#setup--instalasi)
5. [Struktur Database](#struktur-database)
6. [Struktur Project](#struktur-project)
7. [Routing & API](#routing--api)
8. [Konfigurasi Penting](#konfigurasi-penting)
9. [Autentikasi & Authorization](#autentikasi--authorization)
10. [Integrasi Eksternal](#integrasi-eksternal)
11. [Development Guide](#development-guide)
12. [Troubleshooting](#troubleshooting)

---

## 1. Pengenalan Sistem

### Deskripsi Umum

**Balai Kursus** adalah aplikasi manajemen pembelajaran terintegrasi yang dibangun dengan Laravel 10. Sistem ini dirancang khusus untuk mengelola **program kursus bahasa** dengan fitur-fitur lengkap dari pendaftaran hingga penerbitan sertifikat.

**Status Pengembangan:** Currently in development (localhost)  
**Domain:** http://localhost:8000

Sistem ini memfasilitasi:

- Pengelolaan program bahasa (Inggris, Mandarin, Jepang, dsb)
- Pendaftaran peserta dan placement test untuk level
- Manajemen penempatan level pembelajaran bahasa
- Pembayaran online menggunakan Midtrans
- Tracking absensi dan penilaian skill bahasa
- Penerbitan dan distribusi sertifikat kompetensi bahasa

### Fitur Inti

| Fitur | Deskripsi |
|-------|-----------|
| **Multi-Role Dashboard** | Dashboard terpisah untuk Admin, Instruktur, dan Peserta |
| **Manajemen Kursus Bahasa** | Buat, edit, publikasikan program bahasa dan kelas |
| **Pendaftaran Online** | Self-service registration dengan placement test otomatis |
| **Pembayaran Online** | Integrasi Midtrans untuk transaksi aman |
| **Manajemen Level Bahasa** | Penempatan otomatis peserta ke level (Beginner, Intermediate, Advanced) |
| **Absensi Digital** | Pencatatan kehadiran real-time oleh instruktur |
| **Penilaian & Nilai** | Input nilai dan tracking progress peserta |
| **Sertifikat Digital** | Pembuatan dan distribusi sertifikat otomatis |
| **SSO Login** | Support CAS authentication untuk enterprise |
| **Export Data** | Export peserta dan nilai ke Excel |

---

## 2. Technology Stack

### Backend

```
PHP 8.1+          Framework runtime
Laravel 10        Web framework & MVC
MySQL 5.7+        Relational database
```

### Frontend

```
Blade Templates   Server-side templating
Vite 5.0          Module bundler
Tailwind CSS 3.1  Utility-first CSS framework
Alpine.js 3.4     JavaScript framework
```

### Core Libraries

| Library | Versi | Fungsi |
|---------|-------|--------|
| laravel/framework | ^10.10 | Core framework |
| laravel/sanctum | ^3.3 | API authentication |
| nwidart/laravel-modules | 10.0 | Modular architecture |
| barryvdh/laravel-dompdf | ^3.1 | PDF generation |
| midtrans/midtrans-php | ^2.6 | Payment gateway |
| maatwebsite/excel | ^3.1 | Excel export |
| endroid/qr-code | 5.1 | QR code generation |
| subfission/cas | ^5.1 | CAS authentication |

### Development Tools

```
Laravel Breeze    Authentication scaffolding
PHPUnit 10.1      Testing framework
Laravel Pint      Code formatting
Laravel Sail      Docker development
```

---

## 3. Arsitektur Aplikasi

### Pola Arsitektur: Modular MVC + Service Layer

```
┌─────────────────────────────────────────┐
│         Routes (web.php, api.php)       │
└────────────────────┬────────────────────┘
                     │
┌────────────────────v────────────────────┐
│      Controllers (HTTP Layer)            │
│  - Request validation                   │
│  - Orchestration logic                  │
└────────────────────┬────────────────────┘
                     │
┌────────────────────v────────────────────┐
│      Services (Business Logic)          │
│  - Domain logic                         │
│  - External integrations                │
└────────────────────┬────────────────────┘
                     │
┌────────────────────v────────────────────┐
│      Models & Repositories              │
│  - Data access layer                    │
│  - Relationships                        │
└────────────────────┬────────────────────┘
                     │
┌────────────────────v────────────────────┐
│      Database Layer (MySQL)             │
│  - Data persistence                     │
└─────────────────────────────────────────┘
```

### Modular Structure

Project menggunakan `nwidart/laravel-modules` untuk pemisahan fitur:

```
Modules/
├── Absensi/          → Manajemen kehadiran
├── Instruktur/       → Management instruktur
├── Kursus/           → Manajemen program & kelas
├── Level/            → Penempatan level peserta
├── Pendaftaran/      → Pendaftaran & verifikasi
├── Peserta/          → Profil & dashboard peserta
├── Program/          → Kurikulum & program
└── Risalah/          → Dokumentasi pembelajaran
```

Setiap modul memiliki struktur independen:

```
Modul/
├── Config/
├── Database/
│   ├── Migrations/
│   └── Seeders/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   └── Requests/
├── Models/
├── Routes/
│   ├── api.php
│   └── web.php
├── Services/
└── Views/
```

---

## 4. Setup & Instalasi

### Prasyarat

- PHP 8.1 atau lebih baru
- Composer 2.0+
- Node.js 16+
- MySQL 5.7+
- Git

### Langkah Instalasi

#### 1. Clone Repository

```bash
git clone https://github.com/jay-lab-tech/balai-kursus.git
cd balai-kursus
```

#### 2. Install Dependencies

```bash
# PHP dependencies
composer install

# Node.js dependencies
npm install
```

#### 3. Setup Environment

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

#### 4. Konfigurasi Database

Edit `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=balai_kursus
DB_USERNAME=root
DB_PASSWORD=
```

#### 5. Database Migration & Seeding

```bash
# Run migrations
php artisan migrate

# Seed database (untuk data awal)
php artisan db:seed
```

#### 6. Build Assets

```bash
# Development
npm run dev

# Production
npm run build
```

#### 7. Jalankan Server

```bash
# Terminal 1 - Laravel server
php artisan serve

# Terminal 2 - Vite dev server
npm run dev
```

Aplikasi akan tersedia di `http://localhost:8000`

### Akun Default (Dari Seeder)

```
Admin:
  Email: admin@balai.com
  Password: password123
  Role: Administrator

Instruktur:
  Email: instruktur@balai.com
  Password: password123
  Role: Pengajar Bahasa

Peserta:
  Email: peserta@balai.com
  Password: password123
  Role: Pelajar Bahasa
```

---

## 5. Struktur Database

### Entity Relationship Diagram (ERD)

```
┌─────────────┐
│    Users    │◄─────────────────────┐
└─────────────┘                       │
      ▲                               │
      │                          ┌────────────┐
      │                          │   Peserta  │
      │                          └────────────┘
      ├─── Role (admin, instruktur, peserta)
      │
      ├─────────────────► Program
      │                     │
      │                     ├─────► Level
      │                     │
      │                     └─────► Kursus
      │                              │
      │                              ├─► Kelas
      │                              │    │
      ├──────────── Instruktur ───────┤    ├─► Jadwal
      │                              │    │
      │                              └─► Lokasi
      │
      ├──────────── Absensi
      ├──────────── Risalah
      ├──────────── Nilai
      └──────────── Certificate
```

### Tabel Utama

#### Users
```sql
CREATE TABLE users (
  id BIGINT PRIMARY KEY,
  name VARCHAR(255),
  email VARCHAR(255) UNIQUE,
  password VARCHAR(255),
  role ENUM('admin', 'instruktur', 'peserta'),
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

#### Peserta
```sql
CREATE TABLE peserta (
  id BIGINT PRIMARY KEY,
  user_id BIGINT FOREIGN KEY,
  nik VARCHAR(16),
  phone VARCHAR(20),
  address TEXT,
  status ENUM('active', 'inactive'),
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

#### Program
```sql
CREATE TABLE programs (
  id BIGINT PRIMARY KEY,
  name VARCHAR(255),
  description TEXT,
  status ENUM('draft', 'published'),
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

#### Kursus
```sql
CREATE TABLE kursus (
  id BIGINT PRIMARY KEY,
  program_id BIGINT FOREIGN KEY,
  name VARCHAR(255),
  description TEXT,
  duration INT (jam),
  status ENUM('draft', 'active', 'inactive'),
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

#### Level
```sql
CREATE TABLE levels (
  id BIGINT PRIMARY KEY,
  kursus_id BIGINT FOREIGN KEY,
  name VARCHAR(255),
  min_score DECIMAL(5,2),
  max_score DECIMAL(5,2),
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

#### Kelas
```sql
CREATE TABLE kelas (
  id BIGINT PRIMARY KEY,
  kursus_id BIGINT FOREIGN KEY,
  level_id BIGINT FOREIGN KEY,
  name VARCHAR(255),
  max_peserta INT,
  status ENUM('planning', 'active', 'completed'),
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

#### Jadwal
```sql
CREATE TABLE jadwal (
  id BIGINT PRIMARY KEY,
  kelas_id BIGINT FOREIGN KEY,
  hari_id BIGINT FOREIGN KEY,
  jam_mulai TIME,
  jam_selesai TIME,
  lokasi_id BIGINT FOREIGN KEY,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

#### Pendaftaran
```sql
CREATE TABLE pendaftaran (
  id BIGINT PRIMARY KEY,
  peserta_id BIGINT FOREIGN KEY,
  kursus_id BIGINT FOREIGN KEY,
  status ENUM('pending', 'verified', 'rejected'),
  registered_at TIMESTAMP,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

#### Payments
```sql
CREATE TABLE payments (
  id BIGINT PRIMARY KEY,
  peserta_id BIGINT FOREIGN KEY,
  kursus_id BIGINT FOREIGN KEY,
  amount DECIMAL(10,2),
  method VARCHAR(50),
  midtrans_order_id VARCHAR(255),
  status ENUM('pending', 'success', 'failed'),
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

#### Absensi
```sql
CREATE TABLE absensi (
  id BIGINT PRIMARY KEY,
  peserta_id BIGINT FOREIGN KEY,
  jadwal_id BIGINT FOREIGN KEY,
  status ENUM('hadir', 'sakit', 'izin', 'alfa'),
  catatan TEXT,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

#### Nilai
```sql
CREATE TABLE nilai (
  id BIGINT PRIMARY KEY,
  peserta_id BIGINT FOREIGN KEY,
  kursus_id BIGINT FOREIGN KEY,
  score DECIMAL(5,2),
  status ENUM('lulus', 'tidak_lulus'),
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

#### Certificate
```sql
CREATE TABLE certificates (
  id BIGINT PRIMARY KEY,
  peserta_id BIGINT FOREIGN KEY,
  kursus_id BIGINT FOREIGN KEY,
  status ENUM('draft', 'published', 'revoked'),
  certificate_number VARCHAR(50),
  issue_date DATE,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

---

## 6. Struktur Project

### Directory Tree

```
balai-kursus/
├── app/
│   ├── Console/
│   │   └── Kernel.php           # Scheduled tasks
│   ├── Exceptions/
│   │   └── Handler.php          # Global exception handler
│   ├── Exports/
│   │   ├── NilaiExport.php      # Excel export for scores
│   │   └── PesertaExport.php    # Excel export for participants
│   ├── Http/
│   │   ├── Controllers/         # Global controllers
│   │   ├── Kernel.php           # HTTP middleware
│   │   ├── Middleware/          # Custom middleware
│   │   └── Requests/            # Form requests (validation)
│   ├── Models/                  # Core models
│   ├── Observers/               # Model observers
│   ├── Policies/                # Authorization policies
│   ├── Providers/               # Service providers
│   ├── Services/                # Business logic services
│   └── View/                    # View composers
│
├── Modules/
│   ├── Absensi/
│   ├── Instruktur/
│   ├── Kursus/
│   ├── Level/
│   ├── Pendaftaran/
│   ├── Peserta/
│   ├── Program/
│   └── Risalah/
│
├── bootstrap/
│   └── app.php                  # Application bootstrap
│
├── config/
│   ├── app.php                  # Application config
│   ├── database.php             # Database config
│   ├── mail.php                 # Mailing config
│   ├── midtrans.php             # Midtrans config
│   ├── modules.php              # Modules config
│   ├── session.php              # Session config
│   ├── cas.php                  # CAS authentication
│   └── services.php             # Third-party services
│
├── database/
│   ├── migrations/              # Database migrations
│   ├── seeders/                 # Database seeders
│   └── balai_kursus.sql        # SQL dump
│
├── public/
│   ├── index.php               # Entry point
│   ├── images/
│   ├── documents/
│   └── certificates/
│
├── resources/
│   ├── css/                    # Tailwind styles
│   ├── js/                     # Vite bundled JS
│   └── views/                  # Blade templates
│
├── routes/
│   ├── api.php                 # API routes
│   ├── auth.php                # Auth routes
│   ├── channels.php            # Broadcasting
│   ├── console.php             # Console commands
│   └── web.php                 # Web routes
│
├── storage/
│   ├── app/
│   ├── framework/
│   └── logs/
│
├── tests/
│   ├── Feature/                # Feature tests
│   └── Unit/                   # Unit tests
│
├── vendor/                     # Composer packages
├── .env.example               # Environment template
├── .gitignore                 # Git ignore
├── artisan                    # Laravel CLI
├── composer.json              # PHP dependencies
├── package.json               # Node.js dependencies
├── phpunit.xml               # PHPUnit config
├── vite.config.js            # Vite config
├── tailwind.config.js        # Tailwind config
└── README.md
```

---

## 7. Routing & API

### Web Routes

#### Public Routes
```
GET  /                          → Home page
GET  /login                     → Login form
POST /login                     → Login process
GET  /register                  → Register form
POST /register                  → Register process
GET  /login/cas                 → CAS login
```

#### Protected Routes (Middleware: auth)

**Admin Routes** (Prefix: `/admin`)
```
GET    /dashboard              → Admin dashboard
GET    /programs               → List programs
POST   /programs               → Create program
PUT    /programs/{id}          → Update program
DELETE /programs/{id}          → Delete program

GET    /kursus                 → List courses
GET    /level                  → Manage levels
GET    /kelas                  → Manage classes
GET    /certificates           → Certificate management
GET    /peserta                → Manage participants
GET    /instruktur             → Manage instructors
GET    /export/peserta         → Export participants to Excel
GET    /export/nilai           → Export scores to Excel
```

**Instruktur Routes** (Prefix: `/instruktur`)
```
GET    /dashboard              → Instructor dashboard
GET    /jadwal                 → My schedules
POST   /absensi                → Record attendance
GET    /nilai                  → Score input
POST   /nilai                  → Submit scores
GET    /risalah                → Lesson notes
POST   /risalah                → Create lesson notes
```

**Peserta Routes** (Prefix: `/peserta`)
```
GET    /dashboard              → Student dashboard
GET    /profile                → My profile
PUT    /profile                → Update profile
GET    /kursus                 → Available courses
POST   /daftar/{kursus_id}     → Register for course
GET    /pembayaran             → Payment page
POST   /pembayaran             → Create payment
GET    /pembayaran-notification → Webhook from Midtrans
GET    /jadwal                 → My schedules
GET    /nilai                  → My scores
GET    /sertifikat             → My certificates
GET    /sertifikat/{id}/download → Download certificate PDF
```

### API Routes

Semua API routes menggunakan prefix `/api` dan middleware `api`.

#### Authentication
```
POST   /api/login              → API login
POST   /api/logout             → API logout
GET    /api/user               → Get current user
```

#### Program Management
```
GET    /api/programs           → List programs
POST   /api/programs           → Create program
GET    /api/programs/{id}      → Get program details
PUT    /api/programs/{id}      → Update program
DELETE /api/programs/{id}      → Delete program
```

#### Courses
```
GET    /api/kursus             → List courses
POST   /api/kursus             → Create course
GET    /api/kursus/{id}        → Get course details
PUT    /api/kursus/{id}        → Update course
```

#### Participants
```
GET    /api/peserta            → List participants
POST   /api/peserta            → Create participant
GET    /api/peserta/{id}       → Get participant details
PUT    /api/peserta/{id}       → Update participant
```

#### Scores
```
GET    /api/nilai              → List scores
POST   /api/nilai              → Create/update score
GET    /api/nilai/{id}         → Get score details
```

#### Attendance
```
GET    /api/absensi            → List attendance
POST   /api/absensi            → Record attendance
GET    /api/absensi/{id}       → Get attendance details
```

#### Certificates
```
GET    /api/sertifikat         → List certificates
POST   /api/sertifikat         → Create certificate
GET    /api/sertifikat/{id}    → Get certificate
PUT    /api/sertifikat/{id}    → Update certificate
GET    /api/sertifikat/{id}/generate → Generate PDF
```

---

## 8. Konfigurasi Penting

### ⚠️ Development Status

**CATATAN PENTING:** Aplikasi saat ini masih dalam tahap **Development** dan berjalan di **localhost**.

```
Status: 🔧 In Development
Domain: http://localhost:8000
Environment: Local (Laragon)
Database: MySQL Local
Focus: Language Learning Courses (English, Mandarin, Japanese, etc)
```

Sebelum deployment ke production, pastikan:
- ✅ Semua fitur testing selesai
- ✅ Security audit dilakukan
- ✅ SSL certificate disiapkan
- ✅ Email service configured
- ✅ Backup strategy implemented
- ✅ Performance optimization completed

### Environment Variables

#### Application (Development)
```env
APP_NAME=Balai Kursus
APP_ENV=local          # change to 'production' for live
APP_KEY=               # Generated by: php artisan key:generate
APP_DEBUG=true         # Set to false in production
APP_URL=http://localhost:8000
```

#### Database
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=balai_kursus
DB_USERNAME=root
DB_PASSWORD=your_password
```

#### Midtrans Payment Gateway
```env
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_MERCHANT_ID=your_merchant_id
MIDTRANS_IS_PRODUCTION=false   # false untuk sandbox, true untuk production
MIDTRANS_NOTIFICATION_URL=https://your-domain.com/peserta/pembayaran-notification
MIDTRANS_FINISH_REDIRECT_URL=https://your-domain.com/peserta/pembayaran/finish
MIDTRANS_UNFINISH_REDIRECT_URL=https://your-domain.com/peserta/pembayaran/unfinish
MIDTRANS_ERROR_REDIRECT_URL=https://your-domain.com/peserta/pembayaran/error
```

#### CAS Authentication
```env
CAS_HOSTNAME=cas.your-domain.com
CAS_PORT=443
CAS_URI=/cas/
CAS_VALIDATE=V2
```

#### Mail
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@balai-kursus.com
```

#### Google OAuth (Optional)
```env
GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_client_secret
GOOGLE_REDIRECT_URI=${APP_URL}/auth/google/callback
```

### Configuration Files

#### config/midtrans.php
```php
return [
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'merchant_id' => env('MIDTRANS_MERCHANT_ID'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'notification_url' => env('MIDTRANS_NOTIFICATION_URL'),
    'finish_redirect_url' => env('MIDTRANS_FINISH_REDIRECT_URL'),
];
```

#### config/cas.php
```php
return [
    'hostname' => env('CAS_HOSTNAME'),
    'port' => env('CAS_PORT'),
    'uri' => env('CAS_URI'),
    'validate' => env('CAS_VALIDATE'),
];
```

---

## 9. Autentikasi & Authorization

### Authentication Methods

#### 1. Laravel Breeze (Default)
- Email & password authentication
- Session-based
- Built-in forgot password

#### 2. CAS (Corporate/Enterprise)
- Central Authentication Service
- SSO capability
- Route: `/login/cas`

#### 3. Google OAuth (Optional)
- Social login
- Requires Google credentials
- Route: `/auth/google`

### Authorization & Roles

#### Role-Based Access Control (RBAC)

```php
// In middleware or policy
if ($user->role === 'admin') {
    // Admin access
}

if ($user->role === 'instruktur') {
    // Instructor access
}

if ($user->role === 'peserta') {
    // Student access
}
```

#### Policies

Located in `app/Policies/`, policies define what actions users can perform:

```php
// Example: CertificatePolicy
public function download(User $user, Certificate $certificate)
{
    return $user->id === $certificate->peserta_id || $user->role === 'admin';
}
```

#### Middleware

- `auth` - Requires authentication
- `admin` - Requires admin role
- `instruktur` - Requires instructor role
- `peserta` - Requires student role

### Session Management

- Session driver: File (configurable to DB, Redis)
- Session lifetime: 120 minutes (configurable)
- Remember me functionality available

---

## 10. Integrasi Eksternal

### Midtrans Payment Gateway

#### Setup

1. Daftar di https://midtrans.com
2. Dapatkan Server Key dan Client Key
3. Simpan di environment variables
4. Webhook configuration di dashboard Midtrans

#### Payment Flow

```
1. Peserta submit pendaftaran + pilih kursus
   ↓
2. Create payment order di database (payments table)
   ↓
3. Redirect ke Midtrans payment page
   ↓
4. Peserta melakukan pembayaran
   ↓
5. Midtrans send webhook notification
   ↓
6. Update payment status di database
   ↓
7. Enroll peserta ke kelas otomatis
```

#### Integration Code Location

- Controller: `Modules/Peserta/Http/Controllers/PembayaranController.php`
- Service: `app/Services/MidtransService.php`
- Webhook handler: `Modules/Peserta/Http/Controllers/PembayaranController.php@notification`

### DOMPDF (PDF Generation)

#### Usage

```php
use Barryvdh\DomPDF\Facade\Pdf;

// Generate PDF
$pdf = Pdf::loadView('certificate.template', [
    'peserta' => $peserta,
    'certificate' => $certificate,
]);

// Download
return $pdf->download('sertifikat_' . $peserta->name . '.pdf');

// Store
$pdf->save('storage/certificates/' . $filename);
```

#### Integration

- Certificate PDF generation: `Modules/Peserta/Services/CertificateService.php`
- Template: `resources/views/certificate/template.blade.php`

### QR Code

#### Usage

```php
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

$qrCode = new QrCode('https://balai-kursus.com/verify/' . $certificate->id);
$writer = new PngWriter();
$result = $writer->write($qrCode);
$result->saveToFile('storage/qrcodes/cert_' . $certificate->id . '.png');
```

### Excel Export

#### Usage

```php
use App\Exports\PesertaExport;
use Maatwebsite\Excel\Facades\Excel;

// Export to browser
return Excel::download(new PesertaExport($kursus_id), 'peserta.xlsx');

// Export to file
Excel::store(new PesertaExport($kursus_id), 'peserta.xlsx', 'public');
```

---

## 11. Development Guide

### Common Development Tasks

#### Membuat Model Baru

```bash
php artisan make:model Models/NewModel -m
```

#### Membuat Migration

```bash
php artisan make:migration create_table_name
```

#### Membuat Controller

```bash
php artisan make:controller ModuleNameController
```

#### Membuat Service

```bash
# Buat file manual di app/Services/ atau Modules/*/Services/
```

#### Membuat Middleware

```bash
php artisan make:middleware CheckRole
```

### Code Standards

#### PSR-12 Compliance

- Use spaces for indentation (4 spaces)
- Follow Laravel naming conventions
- Use type hints for parameters and returns

#### Example Function

```php
/**
 * Menghitung nilai akhir peserta
 *
 * @param int $peserta_id
 * @param int $kursus_id
 * @return float|null
 */
public function calculateFinalScore(int $peserta_id, int $kursus_id): ?float
{
    $nilai = Nilai::where('peserta_id', $peserta_id)
        ->where('kursus_id', $kursus_id)
        ->first();

    return $nilai?->score;
}
```

#### Eloquent Best Practices

```php
// ✅ Good: Use relationships
$kursus->peserta;  // Lazy loading

// ✅ Better: Eager loading
$kursus = Kursus::with('peserta')->find($id);

// ❌ Avoid: N+1 queries
foreach ($kursus as $course) {
    echo $course->peserta->count();  // Query inside loop!
}
```

### Testing

#### Run Tests

```bash
# All tests
php artisan test

# Specific test file
php artisan test tests/Feature/CertificateTest.php

# Specific test method
php artisan test --filter=testDownloadCertificate
```

#### Writing Tests

```php
class CertificateTest extends TestCase
{
    public function testDownloadCertificate()
    {
        $user = User::factory()->create(['role' => 'peserta']);
        $certificate = Certificate::factory()->create(['peserta_id' => $user->peserta->id]);

        $response = $this->actingAs($user)
            ->get("/peserta/sertifikat/{$certificate->id}/download");

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }
}
```

### Debugging

#### Laravel Debugbar

```php
// Enable in .env
APP_DEBUG=true

// In code
dd($variable);  // Die and dump
dump($variable);  // Dump without dying
```

#### Logs

```php
\Log::info('Payment created', ['order_id' => $order_id]);
\Log::error('Payment failed', $exception);

// View logs
tail -f storage/logs/laravel-*.log
```

---

## 12. Troubleshooting

### Common Issues

#### Error: "SQLSTATE[HY000]: General error: 2006 MySQL server has gone away"

**Solusi:**
```bash
# Restart MySQL
sudo systemctl restart mysql

# Clear cache
php artisan cache:clear
php artisan config:clear
```

#### Error: "Class not found" atau "Method not found"

**Solusi:**
```bash
# Regenerate autoloader
composer dumpautoload

# Clear cache
php artisan cache:clear
```

#### Migration Error: "There are no commands defined in the "migrate" namespace"

**Solusi:**
```bash
# Verify Laravel installation
composer install
php artisan

# Run migrations
php artisan migrate:refresh --seed
```

#### Payment Webhook Not Received

**Solusi:**
1. Pastikan notification URL di Midtrans config valid
2. Check firewall/router settings
3. Verify Midtrans credentials di `.env`
4. Test webhook di Midtrans dashboard
5. Check logs: `storage/logs/laravel-*.log`

#### Permission Denied on Storage Folder

**Solusi:**
```bash
# Set proper permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Or change owner
chown -R www-data:www-data storage
```

#### Certificate PDF Not Generating

**Solusi:**
1. Verify font files exist
2. Check file permissions on storage folder
3. Ensure Blade template syntax is correct
4. Test with simple view first
5. Check error logs

### Performance Optimization

#### Database Query Optimization

```php
// ❌ Slow: Loading all relationships
$peserta = Peserta::all();

// ✅ Fast: Only needed data
$peserta = Peserta::select('id', 'name', 'email')
    ->with('user:id,name')
    ->paginate(50);
```

#### Cache Strategy

```php
// Cache expensive queries
$programs = Cache::remember('programs_list', 60, function () {
    return Program::with('kursus')->get();
});

// Invalidate when needed
Cache::forget('programs_list');
```

#### Asset Optimization

```bash
# Production build
npm run build

# Generates minified CSS/JS in public/build/
```

---

## Helpful Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Tailwind CSS Docs](https://tailwindcss.com/docs)
- [Midtrans Integration Guide](https://docs.midtrans.com)
- [Laravel Modules Package](https://nwidart.com/laravel-modules)

## Contact & Support

**Project Maintainer:** Jay Lab Tech  
**Repository:** https://github.com/jay-lab-tech/balai-kursus  
**Issues:** Report bugs on GitHub Issues

---

---

**Last Updated:** Mei 2026  
**Version:** 1.0  
**Status:** 🔧 In Development (Localhost)

---

## Development Notes

### Current Focus

Platform ini dikembangkan khusus untuk **mengelola kursus bahasa** dengan fitur-fitur berikut:

**Bahasa yang Didukung:**
- 🇬🇧 English (Inggris)
- 🇨🇳 Mandarin Chinese
- 🇯🇵 Japanese (Nihongo)
- 🇪🇸 Spanish (dapat ditambah)
- 🇫🇷 French (dapat ditambah)

**Level yang Digunakan (CEFR Standard):**
- A1 Beginner (Elementary)
- A2 Elementary
- B1 Intermediate
- B2 Upper Intermediate
- C1 Advanced

### Deployment Roadmap

**Phase 1 (Current):** Local development & testing  
**Phase 2:** Staging environment setup  
**Phase 3:** Production deployment with SSL  
**Phase 4:** Mobile app development  
**Phase 5:** Advanced features (Live class, Recording, etc)  

### Known Limitations (Development)

- Email notifications currently disabled
- File storage using local disk
- No backup system in place
- Rate limiting not configured
- Caching not optimized for production

### Next Steps Before Production

1. Configure production database
2. Setup CDN for assets
3. Implement proper logging & monitoring
4. Setup automated backups
5. Configure SSL certificate
6. Setup email service (SMTP)
7. Performance testing & optimization
8. Security audit & penetration testing
9. Documentation for DevOps/SysAdmin
10. Setup CI/CD pipeline
