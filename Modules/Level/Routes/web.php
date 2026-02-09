<?php
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Level Module Routes
|--------------------------------------------------------------------------
*/

// Admin Routes for Level Management
Route::middleware(['auth'])
    ->prefix('admin/level')
    ->name('admin.level.')
    ->group(function () {
        Route::resource('/', 'Admin\LevelController');
    });
