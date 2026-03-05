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

// certificate verification
Route::get('/verify/{code}', [\App\Http\Controllers\CertificateController::class, 'verify'])->name('certificate.verify');

Route::get('/certificate/{id}/download', [\App\Http\Controllers\CertificateController::class, 'download'])
    ->name('certificate.download');

/*
|--------------------------------------------------------------------------
| ADMIN CERTIFICATE MANAGEMENT
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/certificates', [\App\Http\Controllers\Admin\CertificateAdminController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/create', [\App\Http\Controllers\Admin\CertificateAdminController::class, 'create'])->name('certificates.create');
    Route::post('/certificates', [\App\Http\Controllers\Admin\CertificateAdminController::class, 'store'])->name('certificates.store');
    Route::get('/certificates/{certificate}', [\App\Http\Controllers\Admin\CertificateAdminController::class, 'show'])->name('certificates.show');
    Route::get('/certificates/{certificate}/revoke', [\App\Http\Controllers\Admin\CertificateAdminController::class, 'editRevoke'])->name('certificates.editRevoke');
    Route::put('/certificates/{certificate}/revoke', [\App\Http\Controllers\Admin\CertificateAdminController::class, 'revoke'])->name('certificates.revoke');
    Route::post('/certificates/{certificate}/regenerate', [\App\Http\Controllers\Admin\CertificateAdminController::class, 'regenerate'])->name('certificates.regenerate');

    // Batch issue
    Route::get('/certificates/batch/create', [\App\Http\Controllers\Admin\CertificateBatchController::class, 'create'])->name('certificates.batch.create');
    Route::post('/certificates/batch', [\App\Http\Controllers\Admin\CertificateBatchController::class, 'store'])->name('certificates.batch.store');

    // Templates
    Route::get('/certificate-templates', [\App\Http\Controllers\Admin\CertificateTemplateController::class, 'index'])->name('templates.index');
    Route::get('/certificate-templates/create', [\App\Http\Controllers\Admin\CertificateTemplateController::class, 'create'])->name('templates.create');
    Route::post('/certificate-templates', [\App\Http\Controllers\Admin\CertificateTemplateController::class, 'store'])->name('templates.store');
    Route::get('/certificate-templates/{template}/edit', [\App\Http\Controllers\Admin\CertificateTemplateController::class, 'edit'])->name('templates.edit');
    Route::put('/certificate-templates/{template}', [\App\Http\Controllers\Admin\CertificateTemplateController::class, 'update'])->name('templates.update');
    Route::delete('/certificate-templates/{template}', [\App\Http\Controllers\Admin\CertificateTemplateController::class, 'destroy'])->name('templates.destroy');

    // Analytics
    Route::get('/certificates/analytics/dashboard', [\App\Http\Controllers\Admin\CertificateAnalyticsController::class, 'index'])->name('certificates.analytics');
    Route::get('/certificates/analytics/export', [\App\Http\Controllers\Admin\CertificateAnalyticsController::class, 'export'])->name('certificates.analytics.export');
});