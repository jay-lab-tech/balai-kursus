
<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Peserta Module Routes
|--------------------------------------------------------------------------
*/

// Peserta Routes
Route::middleware(['auth'])
    ->prefix('peserta')
    ->name('peserta.')
    ->group(function () {
        Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

        Route::prefix('program')->name('program.')->group(function () {
            Route::get('/', 'ProgramController@index')->name('index');
            Route::get('{program}', 'ProgramController@show')->name('show');
            Route::post('{program}/daftar', 'ProgramController@daftar')->name('daftar');
        });

        Route::prefix('kursus')->name('kursus.')->group(function () {
            Route::get('/', 'KursusController@index')->name('index');
            Route::get('saya', 'KursusController@kursusSaya')->name('saya');
            Route::post('{kursus}/daftar', 'KursusController@daftar')->name('daftar');
            Route::get('{kursus}/detail', 'KursusController@showDetail')->name('detail');
            Route::get('{kursus}/risalah', 'KursusController@showRisalah')->name('risalah');
            Route::get('{kursus}', 'KursusController@show')->name('show');
        });

        Route::prefix('pendaftaran')->name('pendaftaran.')->group(function () {
            Route::get('/', 'PendaftaranController@index')->name('index');
        });

        Route::post('/pembayaran-online/{pendaftaran}', 'PembayaranController@createPaymentForPendaftaran')->name('pembayaran-online');
        Route::get('/pembayaran-success/{orderId}', 'PembayaranController@paymentSuccess')->name('pembayaran-success');
        Route::get('/pembayaran-failed/{orderId}', 'PembayaranController@paymentFailed')->name('pembayaran-failed');

        Route::prefix('riwayat-pembayaran')->name('riwayat.')->group(function () {
            Route::get('/', 'RiwayatController@index')->name('index');
        });
    });

// Midtrans Webhook Notification (NO AUTH REQUIRED)
Route::post('/peserta/pembayaran-notification', 'Modules\Peserta\Http\Controllers\PembayaranController@handleMidtransNotification')->name('pembayaran-notification');

// Admin Routes for Peserta Module
Route::middleware(['auth', 'admin'])
    ->prefix('admin/peserta')
    ->name('admin.peserta.')
    ->group(function () {
        Route::resource('', 'Admin\PesertaController')
            ->except(['show'])
            ->parameters(['' => 'peserta']);

    });
