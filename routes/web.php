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
Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [\Modules\Kursus\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/pembayaran', [\Modules\Pembayaran\Http\Controllers\Admin\PembayaranController::class, 'index']);
        Route::post('/pembayaran/{id}/verifikasi', [\Modules\Pembayaran\Http\Controllers\Admin\PembayaranController::class, 'verifikasi']);

        Route::get('/kursus/{kursus}/peserta', [\Modules\Kursus\Http\Controllers\Admin\KursusController::class, 'peserta'])
            ->name('kursus.peserta');
        Route::resource('/kursus', \Modules\Kursus\Http\Controllers\Admin\KursusController::class)
            ->parameters(['kursus' => 'kursus']);

        Route::get('/kursus/{kursus}/risalah', [\Modules\Kursus\Http\Controllers\Admin\KursusController::class, 'risalahs']);
        Route::get('/kursus/{kursus}/absensi', [\Modules\Kursus\Http\Controllers\Admin\KursusController::class, 'absensi']);

        // Jadwal management (admin)
        Route::get('/kursus/{kursus}/jadwal', [\Modules\Kursus\Http\Controllers\Admin\JadwalController::class, 'index']);
        Route::get('/kursus/{kursus}/jadwal/create', [\Modules\Kursus\Http\Controllers\Admin\JadwalController::class, 'create']);
        Route::post('/kursus/{kursus}/jadwal', [\Modules\Kursus\Http\Controllers\Admin\JadwalController::class, 'store']);
        Route::get('/kursus/{kursus}/jadwal/{jadwal}/edit', [\Modules\Kursus\Http\Controllers\Admin\JadwalController::class, 'edit']);
        Route::put('/kursus/{kursus}/jadwal/{jadwal}', [\Modules\Kursus\Http\Controllers\Admin\JadwalController::class, 'update']);
        Route::delete('/kursus/{kursus}/jadwal/{jadwal}', [\Modules\Kursus\Http\Controllers\Admin\JadwalController::class, 'destroy']);

        // Global admin listings (jadwal/risalah/absensi)
        Route::get('/jadwal', [\Modules\Kursus\Http\Controllers\Admin\JadwalController::class, 'indexAll']);
        Route::get('/risalah', [\Modules\Kursus\Http\Controllers\Admin\KursusController::class, 'allRisalahs']);
        Route::get('/absensi', [\Modules\Kursus\Http\Controllers\Admin\KursusController::class, 'allAbsensis']);

        Route::resource('/program', \Modules\Program\Http\Controllers\Admin\ProgramController::class);
        Route::get('/program/{program}/levels', [\Modules\Program\Http\Controllers\Admin\ProgramController::class, 'getLevels']);

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
        Route::get('/risalah/{risalah}/edit', [\Modules\Instruktur\Http\Controllers\RisalahController::class, 'edit']);
        Route::put('/risalah/{risalah}', [\Modules\Instruktur\Http\Controllers\RisalahController::class, 'update']);

        // Jadwal view (read-only) for instruktur
        Route::get('/jadwal', [\Modules\Instruktur\Http\Controllers\AbsensiController::class, 'jadwal']);
    });


/*
|--------------------------------------------------------------------------
| PESERTA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])
    ->prefix('peserta')
    ->name('peserta.')
    ->group(function () {

        Route::get('/dashboard', [\Modules\Peserta\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/kursus', [\Modules\Peserta\Http\Controllers\KursusController::class, 'index'])->name('kursus.index');
        Route::get('/kursus/{kursus}', [\Modules\Peserta\Http\Controllers\KursusController::class, 'show'])->name('kursus.show');
        Route::post('/kursus/{kursus}/daftar', [\Modules\Peserta\Http\Controllers\KursusController::class, 'daftar'])->name('kursus.daftar');

        Route::get('/pendaftaran', [\Modules\Peserta\Http\Controllers\PendaftaranController::class, 'index'])->name('pendaftaran.index');
        Route::post('/bayar/{id}', [\Modules\Peserta\Http\Controllers\PembayaranController::class, 'store'])->name('bayar');
        Route::post('/pembayaran-online/{pendaftaran}', [App\Http\Controllers\PaymentController::class, 'createPaymentForPendaftaran'])->name('pembayaran-online');
        Route::get('/pembayaran-success/{orderId}', [App\Http\Controllers\PaymentController::class, 'paymentSuccess'])->name('pembayaran-success');
        Route::get('/pembayaran-failed/{orderId}', [App\Http\Controllers\PaymentController::class, 'paymentFailed'])->name('pembayaran-failed');

        Route::get('/riwayat-pembayaran', [\Modules\Peserta\Http\Controllers\RiwayatController::class, 'index'])->name('riwayat.index');
    });
