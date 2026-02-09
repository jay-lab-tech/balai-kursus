<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Program Module Routes
|--------------------------------------------------------------------------
*/

// Admin Routes for Program Management
Route::middleware(['auth'])
    ->prefix('admin/program')
    ->name('admin.program.')
    ->group(function () {
        Route::resource('/', 'Admin\ProgramController');
        Route::get('{program}/levels', 'Admin\ProgramController@getLevels')->name('admin.program.getLevels');
    });
