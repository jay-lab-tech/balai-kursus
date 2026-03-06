<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('pendaftaran')->group(function() {
    // redirect legacy module route to peserta module
    Route::get('/', function() {
        return redirect('/peserta/pendaftaran');
    });
});
