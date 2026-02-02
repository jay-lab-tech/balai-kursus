<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Instruktur;
use App\Http\Controllers\Peserta;

Route::get('/', function () {
    return view('welcome');
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

        Route::get('/dashboard', [Admin\DashboardController::class, 'index']);

        Route::get('/pembayaran', [Admin\PembayaranController::class, 'index']);
        Route::post('/pembayaran/{id}/verifikasi', [Admin\PembayaranController::class, 'verifikasi']);

        Route::get('/kursus/{kursus}/peserta', [Admin\KursusController::class, 'peserta'])
            ->name('kursus.peserta');
        Route::resource('/kursus', Admin\KursusController::class)
            ->parameters(['kursus' => 'kursus']);

        Route::resource('/program', Admin\ProgramController::class);

        Route::resource('/level', Admin\LevelController::class);

        Route::resource('/instruktur', Admin\InstrukturController::class);

        Route::resource('/peserta', Admin\PesertaController::class);


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

        Route::get('/dashboard', [Instruktur\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/kursus', [Instruktur\AbsensiController::class, 'index']);
        Route::get('/kursus/{kursus}', [Instruktur\AbsensiController::class, 'show']);

        Route::get('/risalah/{risalah}/absensi', [Instruktur\AbsensiController::class, 'absensi']);
        Route::post('/risalah/{risalah}/absensi', [Instruktur\AbsensiController::class, 'store']);

        Route::get('/kursus/{kursus}/risalah', [Instruktur\RisalahController::class, 'index']);
        Route::get('/kursus/{kursus}/risalah/create', [Instruktur\RisalahController::class, 'create']);
        Route::post('/kursus/{kursus}/risalah', [Instruktur\RisalahController::class, 'store']);
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

        Route::get('/dashboard', [Peserta\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/kursus', [Peserta\KursusController::class, 'index']);
        Route::post('/kursus/{kursus}/daftar', [Peserta\KursusController::class, 'daftar']);

        Route::get('/pendaftaran', [Peserta\PendaftaranController::class, 'index']);
        Route::post('/bayar/{id}', [Peserta\PembayaranController::class, 'store']);

        Route::get('/riwayat-pembayaran', [Peserta\RiwayatController::class, 'index']);
    });
