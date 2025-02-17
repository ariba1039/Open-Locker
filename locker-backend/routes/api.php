<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login')->middleware(['throttle:6,1']);

});

Route::middleware('auth:sanctum')->group(function () {

    Route::controller(AuthController::class)->group(function () {
        Route::post('register', 'register');
        Route::post('logout', 'logout');
        Route::get('user', 'user');
        Route::get('verify-email/{id}/{hash}', 'verifyEmail')
            ->middleware(['signed', 'throttle:6,1'])->name('auth.verify-email');
    });
    Route::controller(ItemController::class)->prefix('/items')->group(function () {
        Route::get('', 'index');
    });
});
