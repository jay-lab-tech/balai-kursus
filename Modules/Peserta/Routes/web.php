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
        // Dashboard
        Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

        // Kursus Routes
        Route::prefix('kursus')->name('kursus.')->group(function () {
            Route::get('/', 'KursusController@index')->name('index');
            Route::get('{kursus}', 'KursusController@show')->name('show');
            Route::post('{kursus}/daftar', 'KursusController@daftar')->name('daftar');
        });

        // Pendaftaran Routes
        Route::prefix('pendaftaran')->name('pendaftaran.')->group(function () {
            Route::get('/', 'PendaftaranController@index')->name('index');
        });

        // Pembayaran Routes (Payment via Peserta Module)
        Route::post('/bayar/{id}', 'PembayaranController@store')->name('bayar');
        Route::post('/pembayaran-online/{pendaftaran}', 'PembayaranController@createPaymentForPendaftaran')->name('pembayaran-online');
        Route::get('/pembayaran-success/{orderId}', 'PembayaranController@paymentSuccess')->name('pembayaran-success');
        Route::get('/pembayaran-failed/{orderId}', 'PembayaranController@paymentFailed')->name('pembayaran-failed');

        // Riwayat Routes
        Route::prefix('riwayat-pembayaran')->name('riwayat.')->group(function () {
            Route::get('/', 'RiwayatController@index')->name('index');
        });
    });

// Admin Routes for Peserta Module
Route::middleware(['auth'])
    ->prefix('admin/peserta')
    ->name('admin.peserta.')
    ->group(function () {
        Route::resource('/', 'Admin\PesertaController');
    });
