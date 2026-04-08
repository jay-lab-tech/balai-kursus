<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Program Module Routes
|--------------------------------------------------------------------------
*/

// Admin Routes for Program Management
Route::middleware(['auth', 'admin'])
    ->prefix('admin/program')
    ->name('admin.program.')
    ->group(function () {
        Route::resource('', 'Admin\ProgramController')
            ->except(['show'])
            ->parameters(['' => 'program']);
        Route::get('{program}/levels', 'Admin\ProgramController@getLevels')->name('getLevels');
    });
