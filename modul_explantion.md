
## 1️⃣ **Alur Request (dari URL sampai tampil halaman)**

```
User ketik URL di browser
    ↓
Route menangkap request
    ↓
Controller jalankan logic
    ↓
View tampilkan halaman
```

---

## 2️⃣ **File-File Penting dalam Modul**

### **A. Routes (Rute URL)**
**File:** `Modules/Absensi/Routes/web.php`

```php
Route::prefix('absensi')->group(function () {
    Route::get('/', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::get('/create', [AbsensiController::class, 'create'])->name('absensi.create');
    Route::post('/', [AbsensiController::class, 'store'])->name('absensi.store');
});
```

**Artinya:**
- `GET /absensi` → Jalankan fungsi `index()` di controller → Tampilkan daftar absensi
- `GET /absensi/create` → Jalankan `create()` → Tampilkan form tambah absensi
- `POST /absensi` → Jalankan `store()` → Simpan data ke database

---

### **B. Controller (Logika)**
**File:** `Modules/Absensi/Http/Controllers/AbsensiController.php`

```php
class AbsensiController extends Controller
{
    public function index()  // Tampilkan daftar
    {
        $absensi = Absensi::all();  // Ambil semua data dari database
        return view('absensi::index', ['absensi' => $absensi]);
        // Kirim data ke view 'index'
    }

    public function create()  // Tampilkan form tambah
    {
        return view('absensi::create');
    }

    public function store(Request $request)  // Simpan data
    {
        Absensi::create($request->all());
        return redirect()->route('absensi.index');
        // Redirect kembali ke halaman daftar
    }
}
```

**Artinya:**
- `index()` = Ambil data dari database, kirim ke view untuk ditampilkan
- `create()` = Tampilkan form kosong
- `store()` = Terima data dari form, simpan ke database, redirect ke daftar

---

### **C. Views (Tampilan)**
**File:** `Modules/Absensi/Resources/views/index.blade.php`

```blade
@extends('absensi::layouts.master')

@section('content')
<div class="container">
    <h1>Daftar Absensi</h1>
    
    @foreach($absensi as $item)
        <p>{{ $item->nama }}</p>
    @endforeach
</div>
@endsection
```

**Artinya:**
- Tampilkan data yang dikirim controller
- Loop setiap data dan tampilkan

---

## 3️⃣ **Contoh Praktis: Membuat Fitur Baru**

### **Kasus: Tambah halaman "List Peserta Absensi"**

#### **Step 1: Tambah Route**
**File:** `Modules/Absensi/Routes/web.php`

```php
Route::prefix('absensi')->group(function () {
    Route::get('/', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::get('/peserta', [AbsensiController::class, 'peserta'])->name('absensi.peserta');  // ← TAMBAH INI
});
```

**Hasilnya:** URL `/absensi/peserta` siap diakses

---

#### **Step 2: Tambah Fungsi di Controller**
**File:** `Modules/Absensi/Http/Controllers/AbsensiController.php`

```php
public function peserta()  // ← TAMBAH FUNGSI INI
{
    $peserta = Peserta::all();  // Ambil semua peserta dari DB
    return view('absensi::peserta', ['peserta' => $peserta]);
    // Kirim ke view 'peserta.blade.php'
}
```

**Hasilnya:** Saat user buka `/absensi/peserta`, fungsi ini dijalankan

---

#### **Step 3: Buat View**
**File:** `Modules/Absensi/Resources/views/peserta.blade.php`

```blade
@extends('absensi::layouts.master')

@section('content')
<div class="container mt-4">
    <h1>Data Peserta</h1>
    
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peserta as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->user->name }}</td>
                    <td>{{ $p->user->email }}</td>
                </tr>
            @empty
                <tr><td colspan="3">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
```

---

## 4️⃣ **Struktur Folder Modul (FINAL - Sudah Cleanup)**

### **Struktur Setiap Module**
```
Modules/Absensi/
├── Http/
│   └── Controllers/
│       └── AbsensiController.php     ← CONTROLLER
├── Resources/
│   └── views/
│       ├── layouts/
│       │   └── master.blade.php      ← LAYOUT (extends layouts.app-bootstrap)
│       ├── index.blade.php           ← VIEW
│       ├── create.blade.php          ← VIEW
│       └── peserta.blade.php         ← VIEW BARU
├── Routes/
│   └── web.php                       ← ROUTE
└── Models/
    └── Absensi.php                   ← MODEL (database)
```

### **Folder Aplikasi SUDAH DIHAPUS** ✅
**File duplikat yang sudah dihapus:**
- ❌ `app/Http/Controllers/Admin/` (DashboardController, InstrukturController, KursusController, dll)
- ❌ `app/Http/Controllers/Instruktur/` (AbsensiController, RisalahController, dll)
- ❌ `resources/views/admin/` (semua subfolder & view files)
- ❌ `resources/views/instruktur/` (semua subfolder & view files)
- ❌ `resources/views/dashboard.blade.php`

**Sekarang:**
- ✅ Semua controllers hanya ada di dalam `Modules/`
- ✅ Semua views hanya ada di dalam `Modules/*/Resources/views/`
- ✅ Tidak ada duplikat atau file tertinggal
- ✅ Struktur clean dan rapi

---

## 5️⃣ **Naming Convention (Penamaan)**

| Tujuan | Format | Contoh |
|--------|--------|--------|
| Route name | `module.action` | `absensi.index` |
| URL | `/module/action` | `/absensi/peserta` |
| View | `module::view` | `absensi::peserta` |
| Controller class | `ModuleController` | `AbsensiController` |
| Function | `actionName()` | `peserta()` |

---

## 6️⃣ **Checklist Saat Bikin Fitur Baru**

```
✅ Tambah route di Routes/web.php
✅ Tambah fungsi di Controller
✅ Buat file view (.blade.php)
✅ Run: php artisan cache:clear
✅ Test di browser
```

---

## 7️⃣ **Contoh Lengkap: CRUD Absensi**

### **Routes**
```php
Route::prefix('absensi')->group(function () {
    Route::get('/', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::get('/create', [AbsensiController::class, 'create'])->name('absensi.create');
    Route::post('/', [AbsensiController::class, 'store'])->name('absensi.store');
    Route::get('/{id}', [AbsensiController::class, 'show'])->name('absensi.show');
    Route::get('/{id}/edit', [AbsensiController::class, 'edit'])->name('absensi.edit');
    Route::put('/{id}', [AbsensiController::class, 'update'])->name('absensi.update');
    Route::delete('/{id}', [AbsensiController::class, 'destroy'])->name('absensi.destroy');
});
```

### **Controller - Contoh Lengkap**
```php
class AbsensiController extends Controller
{
    // READ - Tampilkan daftar
    public function index()
    {
        $absensi = Absensi::all();
        return view('absensi::index', compact('absensi'));
    }

    // CREATE - Tampilkan form tambah
    public function create()
    {
        return view('absensi::create');
    }

    // STORE - Simpan data baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'kursus_id' => 'required|exists:kursus,id',
        ]);

        Absensi::create($validated);

        return redirect()->route('absensi.index')
                        ->with('success', 'Data absensi berhasil ditambah');
    }

    // UPDATE - Edit data
    public function update(Request $request, $id)
    {
        $absensi = Absensi::findOrFail($id);
        
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'kursus_id' => 'required|exists:kursus,id',
        ]);

        $absensi->update($validated);

        return redirect()->route('absensi.index')
                        ->with('success', 'Data absensi berhasil diubah');
    }

    // DELETE - Hapus data
    public function destroy($id)
    {
        Absensi::destroy($id);
        return redirect()->route('absensi.index')
                        ->with('success', 'Data absensi berhasil dihapus');
    }
}
```

## 8️⃣ **Layout Hierarchy (Cascading)**

Semua module views inherit dari shared layout untuk consistency:

```
resources/views/layouts/app-bootstrap.blade.php
                    ↑
                    │ (di-extends oleh)
                    │
Modules/*/Resources/views/layouts/master.blade.php
                    ↑
                    │ (di-extends oleh)
                    │
Modules/*/Resources/views/[nama-module]/index.blade.php
```

**Artinya:**
- Navbar, styling Bootstrap, dan footer **sama di semua halaman**
- Setiap module bisa punya master layout sendiri tapi tetap inherit navbar/styling
- Konsisten di seluruh aplikasi

---

## 9️⃣ **Daftar Module yang Ada**

| Module | URL Prefix | Controllers | Views |
|--------|-----------|-------------|-------|
| **Absensi** | `/absensi` | AbsensiController | index, create, show, edit |
| **Instruktur** | `/instruktur` | AbsensiController, DashboardController | admin, instruktur |
| **Kursus** | `/kursus` | DashboardController, KursusController | admin/dashboard, admin/kursus |
| **Program** | `/admin/program` | ProgramController | admin/program |
| **Level** | `/admin/level` | LevelController | admin/level |
| **Peserta** | `/admin/peserta` | PesertaController | admin/peserta |
| **Pembayaran** | `/admin/pembayaran` | PembayaranController | admin/pembayaran |
| **Pendaftaran** | `/pendaftaran` | PendaftaranController | index |
| **Risalah** | `/risalah` | RisalahController | index |

---

## 🔟 **Perintah Penting**

```bash
# Refresh routes & modules
php artisan optimize

# Clear cache & views
php artisan cache:clear
php artisan view:clear

# Regenerate autoload (setelah create/delete files)
composer dump-autoload

# Lihat semua routes yang terdaftar
php artisan route:list
```

---

**Paham gak? Tanya lagi jika ada yang membingungkan!**