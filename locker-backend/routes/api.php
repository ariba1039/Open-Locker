<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login')->middleware(['throttle:6,1'])->name('auth.login');
    Route::post('password/email', 'sendPasswortEmail')->middleware(['throttle:6,1'])->name('password.email');

    Route::post('reset-password', 'storeNewPassword')
        ->name('password.store');

});

Route::middleware('auth:sanctum')->group(function () {

    Route::controller(AuthController::class)->group(function () {
        Route::post('register', 'register')->name('auth.register');
        Route::post('logout', 'logout')->name('auth.logout');
        Route::get('user', 'user')->name('auth.user');
        Route::get('verify-email/{id}/{hash}', 'verifyEmail')
            ->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
        Route::post('email/verification-notification', 'sendVerificationEmail')
            ->middleware('throttle:6,1')
            ->name('verification.send');

    });
    Route::controller(ItemController::class)->prefix('/items')->group(function () {
        Route::get('', 'index');
    });
});
