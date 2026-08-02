<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rute modul Peserta
|--------------------------------------------------------------------------
|
| Berkas ini dulu diawali satu baris kosong sebelum tag <?php, sehingga
| setiap permintaan menghasilkan satu newline liar di awal keluaran.
|
| Grup di bawah dulu hanya memakai 'auth'. Setiap controller lalu memeriksa
| sendiri apakah pengguna punya profil peserta, kecuali katalog kelas yang
| tidak memeriksa apa pun. Penjagaan peran dipindah ke satu tempat, sama
| seperti grup instruktur.
*/

Route::middleware(['auth', 'role:peserta'])
    ->prefix('peserta')
    ->name('peserta.')
    ->group(function () {
        Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

        Route::prefix('program')->name('program.')->group(function () {
            Route::get('/', 'ProgramController@index')->name('index');
            Route::get('{program}', 'ProgramController@show')->name('show');
            Route::post('{program}/daftar', 'ProgramController@daftar')->name('daftar');
        });

        /*
         * Peserta tidak memilih kelas sendiri, jadi tidak ada katalog kelas
         * dan tidak ada aksi daftar langsung. Rute index/show/daftar yang lama
         * hanya menjadi pengalihan dan halaman yatim tanpa tautan masuk.
         */
        Route::prefix('kursus')->name('kursus.')->group(function () {
            Route::get('saya', 'KursusController@kursusSaya')->name('saya');
            Route::get('{kursus}/detail', 'KursusController@showDetail')->name('detail');
            Route::get('{kursus}/risalah', 'KursusController@showRisalah')->name('risalah');
        });

        Route::get('/pendaftaran', 'PendaftaranController@index')->name('pendaftaran.index');

        Route::post('/pembayaran-online/{pendaftaran}', 'PembayaranController@createPaymentForPendaftaran')->name('pembayaran-online');
        Route::get('/pembayaran-success/{orderId}', 'PembayaranController@paymentSuccess')->name('pembayaran-success');
        Route::get('/pembayaran-failed/{orderId}', 'PembayaranController@paymentFailed')->name('pembayaran-failed');

        Route::get('/riwayat-pembayaran', 'RiwayatController@index')->name('riwayat.index');
    });

// Webhook Midtrans — dipanggil server Midtrans, bukan peramban, jadi tanpa auth.
Route::post('/peserta/pembayaran-notification', 'Modules\Peserta\Http\Controllers\PembayaranController@handleMidtransNotification')
    ->name('pembayaran-notification');

Route::middleware(['auth', 'admin'])
    ->prefix('admin/peserta')
    ->name('admin.peserta.')
    ->group(function () {
        Route::resource('', 'Admin\PesertaController')
            ->except(['show'])
            ->parameters(['' => 'peserta']);
    });
