<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:api'])->group(function () {
    Route::post('user', [UserController::class, 'store']);
    Route::get('user', [UserController::class, 'index']);
    Route::get('user/{id}', [UserController::class, 'show']);
    Route::put('user/{id}', [UserController::class, 'update']);
    Route::delete('user/{id}', [UserController::class, 'destroy']);

    Route::post('payment/', [PaymentController::class, 'store']);
    Route::get('payment', [PaymentController::class, 'index']);
    Route::get('payment/{id}', [PaymentController::class, 'show']);
    Route::put('payment/{id}', [PaymentController::class, 'update']);
    Route::delete('payment/{id}', [PaymentController::class, 'destroy']);

    Route::post('credit/', [CreditController::class, 'store']);
    Route::get('credit', [CreditController::class, 'index']);
    Route::get('credit/{id}', [CreditController::class, 'show']);
    Route::put('credit/{id}', [CreditController::class, 'update']);
    Route::delete('credit/{id}', [CreditController::class, 'destroy']);
});
