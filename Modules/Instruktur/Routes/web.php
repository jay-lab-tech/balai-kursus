<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Instruktur Module Routes
|--------------------------------------------------------------------------
*/

// Instruktur Routes
Route::middleware(['auth', 'role:instruktur'])
    ->prefix('instruktur')
    ->name('instruktur.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

        // Kursus Listing
        Route::get('/kursus', 'AbsensiController@index')->name('kursus.index');
        Route::get('/kursus/{kursus}', 'AbsensiController@show')->name('kursus.show');

        // Absensi Routes
        Route::prefix('risalah/{risalah}')->name('absensi.')->group(function () {
            Route::get('/absensi', 'AbsensiController@absensi')->name('show');
            Route::post('/absensi', 'AbsensiController@store')->name('store');
        });

        // Risalah Routes
        Route::prefix('kursus/{kursus}')->name('risalah.')->group(function () {
            Route::get('/risalah', 'RisalahController@index')->name('index');
        });
        Route::prefix('risalah')->name('risalah.')->group(function () {
            Route::get('{risalah}/edit', 'RisalahController@edit')->name('edit');
            Route::put('{risalah}', 'RisalahController@update')->name('update');
        });

        // Nilai Routes
        Route::prefix('kursus/{kursus}')->name('nilai.')->group(function () {
            Route::get('/nilai', 'NilaiController@index')->name('index');
        });
        Route::resource('nilai', 'NilaiController')->except(['index', 'create', 'edit']);

        // Jadwal (Read-only)
        Route::get('/jadwal', 'AbsensiController@jadwal')->name('jadwal.index');
    });

// Admin Routes for Instruktur Management
Route::middleware(['auth', 'admin'])
    ->prefix('admin/instruktur')
    ->name('admin.instruktur.')
    ->group(function () {
        Route::resource('', 'Admin\InstrukturController')
            ->except(['show'])
            ->parameters(['' => 'instruktur']);
    });

// Route download dokumen risalah untuk semua role yang login
Route::middleware(['auth'])
    ->prefix('instruktur/risalah')
    ->name('instruktur.risalah.')
    ->group(function () {
        Route::get('{risalah}/download', 'Modules\Instruktur\Http\Controllers\RisalahController@download')->name('download');
    });
