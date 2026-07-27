<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Level Module Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin/level')
    ->name('admin.level.')
    ->group(function () {
        Route::resource('', 'Admin\\LevelController')
            ->except(['show'])
            ->parameters(['' => 'level']);
    });
