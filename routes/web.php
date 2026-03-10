<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CasLoginController;

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
});

require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| REDIRECT BASED ON ROLE
|--------------------------------------------------------------------------
*/

Route::get('/redirect', function () {
    $role = auth()->user()->role;

    if ($role == 'admin') return redirect('/admin/dashboard');
    if ($role == 'instruktur') return redirect('/instruktur/dashboard');
    if ($role == 'peserta') return redirect('/peserta/dashboard');

    abort(403);
})->name('redirect');

/*
|--------------------------------------------------------------------------
| MODULE ROUTES
|--------------------------------------------------------------------------
| All module routes are auto-loaded from Modules folders
|
*/

/*
|--------------------------------------------------------------------------
| PAYMENT ROUTES (PESERTA)
|--------------------------------------------------------------------------
| Route untuk menangani pembuatan pembayaran oleh peserta
|
*/
Route::post('/peserta/pendaftaran/{pendaftaran}/create-payment', [\App\Http\Controllers\PaymentController::class, 'createPaymentForPendaftaran'])
    ->middleware('auth')
    ->name('peserta.pendaftaran.create-payment');

// Admin Certificate Management
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/certificates', [\App\Http\Controllers\CertificateController::class, 'index'])->name('admin.certificates.index');
    Route::get('/admin/certificates/create', [\App\Http\Controllers\CertificateController::class, 'create'])->name('admin.certificates.create');
    Route::post('/admin/certificates', [\App\Http\Controllers\CertificateController::class, 'store'])->name('admin.certificates.store');
    Route::get('/admin/certificates/{certificate}/edit', [\App\Http\Controllers\CertificateController::class, 'edit'])->name('admin.certificates.edit');
    Route::put('/admin/certificates/{certificate}', [\App\Http\Controllers\CertificateController::class, 'update'])->name('admin.certificates.update');
    Route::delete('/admin/certificates/{certificate}', [\App\Http\Controllers\CertificateController::class, 'destroy'])->name('admin.certificates.destroy');
    Route::post('/admin/certificates/{certificate}/publish', [\App\Http\Controllers\CertificateController::class, 'publish'])->name('admin.certificates.publish');
    Route::get('/admin/get-participants', function (Illuminate\Http\Request $request) {
        $pesertas = \App\Models\Peserta::whereHas('pendaftarans', function($q) use ($request) {
            $q->where('kursus_id', $request->course_id);
        })
        ->with('user')
        ->get(['id', 'nomor_peserta', 'user_id']);
        // Ambil nama
        foreach ($pesertas as $peserta) {
            $peserta->nama = $peserta->user->name ?? '';
        }
        return response()->json($pesertas);
    });
});

// User Certificate Management
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/certificates', [\App\Http\Controllers\UserCertificateController::class, 'index'])->name('profile.certificates');
    Route::get('/profile/certificates/{id}/download', [\App\Http\Controllers\UserCertificateController::class, 'download'])->name('profile.certificates.download');
    Route::get('/profile/certificates/{id}/detail', [\App\Http\Controllers\UserCertificateController::class, 'detail'])->name('profile.certificates.detail');
    Route::get('/my-certificates', [\App\Http\Controllers\CertificateController::class, 'myCertificates'])->name('user.certificates.index');
    Route::get('/my-certificates/{id}/download', [\App\Http\Controllers\CertificateController::class, 'download'])->name('user.certificates.download');
});