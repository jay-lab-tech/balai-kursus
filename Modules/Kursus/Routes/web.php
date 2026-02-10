<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Kursus Module Routes
|--------------------------------------------------------------------------
*/

// Admin Routes
Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', 'Admin\DashboardController@index')->name('dashboard');

        // Kursus Resource
        Route::get('/kursus/{kursus}/peserta', 'Admin\KursusController@peserta')->name('kursus.peserta');
        Route::resource('/kursus', 'Admin\KursusController')->parameters(['kursus' => 'kursus']);
        Route::get('/kursus/{kursus}/risalah', 'Admin\KursusController@risalahs');
        Route::get('/kursus/{kursus}/absensi', 'Admin\KursusController@absensi');

        // Jadwal Routes
        Route::prefix('kursus/{kursus}/jadwal')->name('jadwal.')->group(function () {
            Route::get('/', 'Admin\JadwalController@index')->name('index');
            Route::get('/create', 'Admin\JadwalController@create')->name('create');
            Route::post('/', 'Admin\JadwalController@store')->name('store');
            Route::get('{jadwal}/edit', 'Admin\JadwalController@edit')->name('edit');
            Route::put('{jadwal}', 'Admin\JadwalController@update')->name('update');
            Route::delete('{jadwal}', 'Admin\JadwalController@destroy')->name('destroy');
        });

        // Global Listings (Jadwal/Risalah/Absensi)
        Route::get('/jadwal', 'Admin\JadwalController@indexAll')->name('jadwal.all');
        Route::get('/risalah', 'Admin\KursusController@allRisalahs')->name('risalah.all');
        Route::get('/absensi', 'Admin\KursusController@allAbsensis')->name('absensi.all');

        // Score Routes
        Route::resource('/score', 'Admin\ScoreController')->except(['show']);
        Route::get('/score/{score}', 'Admin\ScoreController@show')->name('score.show');

        // Master Data Routes
        Route::resource('/lokasi', 'Admin\LokasiController');
        Route::resource('/kelas', 'Admin\KelaController');
        Route::resource('/hari', 'Admin\HariController')->except(['show']);
    });
