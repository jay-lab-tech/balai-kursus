# Audit: Database Baru vs Existing Laravel Code

**Status**: ❌ **INCOMPLETE** - Banyak gaps antara database schema baru dan controller/module implementation

**Generated**: 10 Feb 2026

---

## 1. Summary Gap Analysis

### Database Schema Baru (Added/Updated):
| Table | Columns | Status |
|-------|---------|--------|
| `lokasis` (NEW) | id, nama, alamat, no_telp, kota, provinsi, keterangan | ❌ No module/controller |
| `kelas` (NEW) | id, nama, kapasitas, fasilitas, keterangan | ❌ No module/controller |
| `haris` (NEW) | id, nama, urutan | ❌ No module/controller |
| `scores` (NEW) | id, pendaftaran_id, listening, speaking, reading, writing, assignment, uktp, ukap, var1-4, final_score, status, evaluated_by, evaluated_at, keterangan | ⚠️ Model exists, no CRUD |
| `kursuses` (UPDATED) | + periode, harga_upi, instruktur_id_2 | ⚠️ Partial - not in create/edit forms |
| `jadwals` (UPDATED) | + lokasi_id, kela_id, hari_id | ❌ Controller doesn't handle these |
| `pendaftarans` (UPDATED) | + nomor | ⚠️ Model updated, possibly ignored in views |

---

## 2. Module-by-Module Status

### ✅ Modules IMPLEMENTED (with existing logic):
1. **Kursus**
   - Controllers: KursusController (Admin), JadwalController (Admin)
   - Status: ⚠️ PARTIAL
   - Issues:
     - `KursusController@index()` tidak load `instruktur2`, `periode`, `harga_upi`
     - `create`/`edit` tidak pass data untuk `instruktur_id_2`, `periode`, `harga_upi`
     - **JadwalController** tidak handle `lokasi_id`, `kela_id`, `hari_id` sama sekali
     - Views likely tidak render field-field baru

2. **Pendaftaran**
   - Controllers: PendaftaranController (minimal/template)
   - Status: ❌ STUB - Hanya template kosong
   - Issues:
     - Tidak ada CRUD logic
     - Tidak handle field `nomor`
     - Tidak link ke `Peserta`, `Kursus`, `Pembayaran`

3. **Peserta**
   - Controllers: PesertaController, DashboardController, dll
   - Status: ⚠️ PARTIAL
   - Issues:
     - Tidak clear apakah sudah handle `pendaftaran` dengan field baru (`nomor`)

4. **Pembayaran**
   - Controllers: PembayaranController (Admin)
   - Status: ✅ IMPLEMENTED
   - Notes: Sudah handle verification logic, tapi tidak link ke `pendaftaran.nomor`

5. **Risalah**
   - Controllers: RisalahController (stub), instruktur RisalahController
   - Status: ⚠️ PARTIAL - Template + instruktur logic
   - Issues:
     - Tidak clear handling `instruktur_id` requirement
     - Views tidak lihat (belum di-check)

6. **Absensi**
   - Controllers: AbsensiController (stub + instruktur AbsensiController)
   - Status: ⚠️ PARTIAL - Instruktur path exists

7. **Program, Level, Instruktur**
   - Status: ✅ BASIC IMPLEMENTED (standard CRUD)

### ❌ Modules MISSING (NEW in Schema):
1. **Lokasi** - NO module, NO controller, NO CRUD routes
2. **Kelas** - NO module, NO controller, NO CRUD routes  
3. **Hari** - NO module, NO controller, NO CRUD routes
4. **Score** - NO module, NO controller, NO CRUD routes (only model exists)

---

## 3. Critical Implementation Gaps

### 3.1 Kursus Form Issues
**File**: `Modules/Kursus/Http/Controllers/Admin/KursusController.php`

```php
// CURRENT create() - MISSING fields:
public function create() {
    $program = Program::all();
    $level = Level::all();
    $instruktur = Instruktur::all();  // ← Missing instruktur2
    // ↓ Missing periode, harga_upi, instruktur_id_2 data
    return view('kursus::admin.kursus.create', compact(...));
}
```

**Required Updates**:
- ✅ Load `instruktur_id_2` (instruktur kedua)
- ✅ Pass `periode` list / allow free input
- ✅ Pass `harga_upi` for form
- ✅ Update `store()` to accept & validate these fields
- ✅ Update `edit()` similarly

### 3.2 Jadwal Form Issues (CRITICAL)
**File**: `Modules/Kursus/Http/Controllers/Admin/JadwalController.php`

```php
// CURRENT create() - MISSING 3 fields:
public function create(Kursus $kursus) {
    // ↓ Missing lokasi, kela, hari
    return view('kursus::admin.jadwal.create', compact('kursus'));
}

// CURRENT store() - MISSING field handling:
public function store(Request $request, Kursus $kursus) {
    Jadwal::create([
        'kursus_id' => $kursus->id,
        // ✅ pertemuan_ke, tgl_pertemuan, jam_mulai, jam_selesai
        // ❌ MISSING: lokasi_id, kela_id, hari_id
        'created_by' => auth()->id()
    ]);
}
```

**Required Updates**:
- ✅ Load & pass `Lokasi::all()`, `Kela::all()`, `Hari::all()`
- ✅ Add validation for `lokasi_id`, `kela_id`, `hari_id`
- ✅ Update `store()` to save these fields
- ✅ Same for `edit()` & `update()`

### 3.3 Score CRUD Missing (CRITICAL)
No controller/module exists for `Score` management despite new table.

**Required**:
- Create `Modules/Score/` or place CRUD in `Kursus` admin
- Add score create/edit form with fields: listening, speaking, reading, writing, assignment, uktp, ukap, var1-4, final_score, status, evaluated_by

### 3.4 Lokasi/Kelas/Hari CRUD Missing
No master data CRUD for these new lookup tables.

**Required**:
- Create `Modules/Lokasi/` with basic CRUD
- Create `Modules/Kelas/` with basic CRUD
- Create `Modules/Hari/` with basic CRUD (maybe read-only since seeded data)

---

## 4. View Files Status (NOT YET CHECKED IN DETAIL)

Need to verify these views render new fields:
- [ ] `Modules/Kursus/Resources/views/admin/kursus/create.blade.php`
- [ ] `Modules/Kursus/Resources/views/admin/kursus/edit.blade.php`
- [ ] `Modules/Kursus/Resources/views/admin/jadwal/create.blade.php`
- [ ] `Modules/Kursus/Resources/views/admin/jadwal/edit.blade.php`
- [ ] `Modules/Kursus/Resources/views/admin/kursus/index.blade.php`
- [ ] `Modules/Kursus/Resources/views/admin/jadwal/index.blade.php`

---

## 5. API Routes Status

- [ ] No `/api/*` routes checked yet for new fields
- Potential: `/api/kursus`, `/api/jadwal`, `/api/pedaftaran`, `/api/scores`

---

## 6. Model Relationships Status

### Already Updated ✅:
- `Kursus` - added `instruktur2()`, `periode`, `harga_upi` in fillable
- `Jadwal` - added `lokasi()`, `kela()`, `hari()` + fillable
- `Pendaftaran` - added `nomor`, `scores()` relationship
- `Score` - created with all fields
- `Lokasi`, `Kela`, `Hari` - models created

### Potential Issues ⚠️:
- Relationships might not be fully eager-loaded in controllers
- No `withCount()` or `with()` optimization in index views

---

## 7. Recommendations (Priority Order)

### CRITICAL (Block production use):
1. ✅ Update `JadwalController` to handle `lokasi_id`, `kela_id`, `hari_id`
2. ✅ Update `KursusController` create/edit to handle `instruktur_id_2`, `periode`, `harga_upi`
3. ✅ Create Score CRUD (either in Kursus admin or new Score module)
4. ✅ Verify all views render new fields correctly

### HIGH (Soon):
5. ✅ Create Lokasi CRUD module
6. ✅ Create Kelas CRUD module
7. ✅ Create Hari CRUD module (or read-only if seeded)

### MEDIUM (Nice to have):
8. Update API routes if using API frontend
9. Add unit tests for new fields
10. Update form validation rules

---

## 8. Next Steps (Proposed Work)

- [ ] Task 1: Update `JadwalController` + view forms
- [ ] Task 2: Update `KursusController` + view forms
- [ ] Task 3: Create Score CRUD (backend + frontend)
- [ ] Task 4: Create Lokasi/Kelas/Hari CRUD modules
- [ ] Task 5: Full test run through UI (create kursus → jadwal → scores)

---

**Prepared by**: AI Audit  
**Date**: 10 Feb 2026
