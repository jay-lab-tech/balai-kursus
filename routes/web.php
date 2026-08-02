<?php

use App\Http\Controllers\InformationBoardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/redirect');
    }

    return redirect()->route('login');
});

Route::get('/papan-informasi', InformationBoardController::class)->name('information-board');

// Alamat lama dari modul Pendaftaran yang sudah dihapus; tetap dialihkan agar
// tautan atau bookmark lama tidak berujung 404.
Route::redirect('/pendaftaran', '/peserta/pendaftaran');

/*
|--------------------------------------------------------------------------
| AUTH DEFAULT (BREEZE)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Sertifikat user
    Route::get('/profile/certificates', [\App\Http\Controllers\UserCertificateController::class, 'index'])->name('profile.certificates');
    Route::get('/profile/certificates/{id}', [\App\Http\Controllers\UserCertificateController::class, 'detail'])->name('profile.certificate.detail');
    Route::get('/profile/certificates/{id}/download', [\App\Http\Controllers\UserCertificateController::class, 'download'])->name('profile.certificate.download');
});

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| REDIRECT BASED ON ROLE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->get('/redirect', function () {
    $role = auth()->user()->role;

    if ($role == 'admin') {
        return redirect('/admin/dashboard');
    }
    if ($role == 'instruktur') {
        return redirect('/instruktur/dashboard');
    }
    if ($role == 'peserta') {
        return redirect('/peserta/dashboard');
    }

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

/*
 * Bantuan dokumentasi, hanya hidup di lingkungan lokal.
 *
 * Keadaan "pembayaran berhasil" tidak punya halaman sendiri: paymentSuccess()
 * selalu mengalihkan ke daftar pendaftaran sambil menitipkan pesan kilat.
 * Pesan itu baru muncul setelah Midtrans benar-benar menyelesaikan transaksi,
 * jadi tidak bisa ditangkap ulang oleh capture.mjs tanpa pemicu seperti ini.
 * Rute ini hanya menitipkan pesan yang sama persis lalu mengalihkan; tidak ada
 * data yang disentuh, dan di produksi rute ini tidak pernah terdaftar.
 */
if (app()->environment('local')) {
    Route::get('/dokumentasi/pembayaran-berhasil', function () {
        return redirect()->route('peserta.pendaftaran.index')
            ->with('success', 'Pembayaran berhasil! Terima kasih.');
    })->middleware(['auth', 'role:peserta'])->name('dokumentasi.pembayaran-berhasil');
}

if (method_exists(\App\Http\Controllers\CertificateController::class, 'verify')) {
    Route::get('/verify/{code}', [\App\Http\Controllers\CertificateController::class, 'verify'])->name('certificate.verify');
}

/*
|--------------------------------------------------------------------------
| ADMIN CERTIFICATE MANAGEMENT
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/score/export', [\Modules\Kursus\Http\Controllers\Admin\ScoreController::class, 'export'])->name('score.export');

    // Sertifikat admin
    Route::get('/certificates', [\App\Http\Controllers\CertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/create', [\App\Http\Controllers\CertificateController::class, 'create'])->name('certificates.create');
    Route::get('/certificates/batch/create', [\App\Http\Controllers\CertificateController::class, 'batchCreate'])->name('certificates.batch.create');
    Route::post('/certificates/batch', [\App\Http\Controllers\CertificateController::class, 'batchStore'])->name('certificates.batch.store');
    Route::post('/certificates/batch/publish', [\App\Http\Controllers\CertificateController::class, 'batchPublish'])->name('certificates.batch.publish');
    Route::get('/get-participants', [\App\Http\Controllers\CertificateController::class, 'getParticipants'])->name('certificates.participants');
    Route::post('/certificates', [\App\Http\Controllers\CertificateController::class, 'store'])->name('certificates.store');
    Route::get('/certificates/{id}/preview', [\App\Http\Controllers\CertificateController::class, 'preview'])->name('certificates.preview');
    Route::get('/certificates/{id}/edit', [\App\Http\Controllers\CertificateController::class, 'edit'])->name('certificates.edit');
    Route::put('/certificates/{id}', [\App\Http\Controllers\CertificateController::class, 'update'])->name('certificates.update');
    Route::delete('/certificates/{id}', [\App\Http\Controllers\CertificateController::class, 'destroy'])->name('certificates.destroy');
    Route::post('/certificates/{id}/publish', [\App\Http\Controllers\CertificateController::class, 'publish'])->name('certificates.publish');
    Route::post('/certificates/{id}/revoke', [\App\Http\Controllers\CertificateController::class, 'revoke'])->name('certificates.revoke');
    Route::post('/certificates/{id}/restore-draft', [\App\Http\Controllers\CertificateController::class, 'restoreDraft'])->name('certificates.restore-draft');

    if (class_exists(\App\Http\Controllers\Admin\CertificateTemplateController::class)) {
        Route::get('/certificate-templates', [\App\Http\Controllers\Admin\CertificateTemplateController::class, 'index'])->name('templates.index');
        Route::get('/certificate-templates/create', [\App\Http\Controllers\Admin\CertificateTemplateController::class, 'create'])->name('templates.create');
        Route::post('/certificate-templates', [\App\Http\Controllers\Admin\CertificateTemplateController::class, 'store'])->name('templates.store');
        Route::get('/certificate-templates/{template}/edit', [\App\Http\Controllers\Admin\CertificateTemplateController::class, 'edit'])->name('templates.edit');
        Route::put('/certificate-templates/{template}', [\App\Http\Controllers\Admin\CertificateTemplateController::class, 'update'])->name('templates.update');
        Route::delete('/certificate-templates/{template}', [\App\Http\Controllers\Admin\CertificateTemplateController::class, 'destroy'])->name('templates.destroy');
    }

    if (class_exists(\App\Http\Controllers\Admin\CertificateAnalyticsController::class)) {
        Route::get('/certificates/analytics/dashboard', [\App\Http\Controllers\Admin\CertificateAnalyticsController::class, 'index'])->name('certificates.analytics');
        Route::get('/certificates/analytics/export', [\App\Http\Controllers\Admin\CertificateAnalyticsController::class, 'export'])->name('certificates.analytics.export');
    }
    // Peserta export
    Route::get('/peserta/export', [\Modules\Peserta\Http\Controllers\Admin\PesertaController::class, 'export'])->name('peserta.export');
});
