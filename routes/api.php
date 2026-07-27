<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Legacy payment API. Endpoint create/status hanya dapat dipakai user
// yang memiliki token Sanctum. Webhook tetap publik karena dipanggil Midtrans.
Route::middleware('auth:sanctum')->prefix('payment')->group(function () {
    Route::post('/create', [PaymentController::class, 'createPayment'])->name('payment.create');
    Route::get('/status/{orderId}', [PaymentController::class, 'checkStatus'])->name('payment.status');
});

Route::post('/payment/notification', [PaymentController::class, 'notification'])
    ->name('payment.notification');
