<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Pembayaran Module Routes
|--------------------------------------------------------------------------
*/

// Admin Routes for Pembayaran Management
Route::middleware(['auth'])
    ->prefix('admin/pembayaran')
    ->name('admin.pembayaran.')
    ->group(function () {
        Route::get('/', 'Admin\PembayaranController@index')->name('index');
        Route::post('{id}/verifikasi', 'Admin\PembayaranController@verifikasi')->name('verifikasi');
    });
