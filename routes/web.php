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
