<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/redirect');
    }

    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| AUTH DEFAULT (BREEZE)
|--------------------------------------------------------------------------
*/


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';


Route::get('/redirect', function () {
    $role = auth()->user()->role;

    if ($role == 'admin') return redirect('/admin/dashboard');
    if ($role == 'instruktur') return redirect('/instruktur/dashboard');
    if ($role == 'peserta') return redirect('/peserta/dashboard');

    abort(403);
});


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [\Modules\Kursus\Http\Controllers\Admin\DashboardController::class, 'index']);

        Route::get('/pembayaran', [\Modules\Pembayaran\Http\Controllers\Admin\PembayaranController::class, 'index']);
        Route::post('/pembayaran/{id}/verifikasi', [\Modules\Pembayaran\Http\Controllers\Admin\PembayaranController::class, 'verifikasi']);

        Route::get('/kursus/{kursus}/peserta', [\Modules\Kursus\Http\Controllers\Admin\KursusController::class, 'peserta'])
            ->name('kursus.peserta');
        Route::resource('/kursus', \Modules\Kursus\Http\Controllers\Admin\KursusController::class)
            ->parameters(['kursus' => 'kursus']);

        Route::resource('/program', \Modules\Program\Http\Controllers\Admin\ProgramController::class);

        Route::resource('/level', \Modules\Level\Http\Controllers\Admin\LevelController::class);

        Route::resource('/instruktur', \Modules\Instruktur\Http\Controllers\Admin\InstrukturController::class);

        Route::resource('/peserta', \Modules\Peserta\Http\Controllers\Admin\PesertaController::class);


    });



/*
|--------------------------------------------------------------------------
| INSTRUKTUR
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:instruktur'])
    ->prefix('instruktur')
    ->name('instruktur.')
    ->group(function () {

        Route::get('/dashboard', [\Modules\Instruktur\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/kursus', [\Modules\Instruktur\Http\Controllers\AbsensiController::class, 'index']);
        Route::get('/kursus/{kursus}', [\Modules\Instruktur\Http\Controllers\AbsensiController::class, 'show']);

        Route::get('/risalah/{risalah}/absensi', [\Modules\Instruktur\Http\Controllers\AbsensiController::class, 'absensi']);
        Route::post('/risalah/{risalah}/absensi', [\Modules\Instruktur\Http\Controllers\AbsensiController::class, 'store']);

        Route::get('/kursus/{kursus}/risalah', [\Modules\Instruktur\Http\Controllers\RisalahController::class, 'index']);
        Route::get('/kursus/{kursus}/risalah/create', [\Modules\Instruktur\Http\Controllers\RisalahController::class, 'create']);
        Route::post('/kursus/{kursus}/risalah', [\Modules\Instruktur\Http\Controllers\RisalahController::class, 'store']);
    });


/*
|--------------------------------------------------------------------------
| PESERTA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:peserta'])
    ->prefix('peserta')
    ->name('peserta.')
    ->group(function () {

        Route::get('/dashboard', [\Modules\Peserta\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/kursus', [\Modules\Peserta\Http\Controllers\KursusController::class, 'index']);
        Route::post('/kursus/{kursus}/daftar', [\Modules\Peserta\Http\Controllers\KursusController::class, 'daftar']);

        Route::get('/pendaftaran', [\Modules\Peserta\Http\Controllers\PendaftaranController::class, 'index']);
        Route::post('/bayar/{id}', [\Modules\Peserta\Http\Controllers\PembayaranController::class, 'store']);

        Route::get('/riwayat-pembayaran', [\Modules\Peserta\Http\Controllers\RiwayatController::class, 'index']);
    });
