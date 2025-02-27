<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login')->middleware(['throttle:6,1'])->name('auth.login');
    Route::post('password/email', 'sendPasswortEmail')->middleware(['throttle:6,1'])->name('password.email');

    Route::post('reset-password', 'storeNewPassword')
        ->name('password.store');

});

Route::middleware('auth:sanctum')->group(function () {

    Route::controller(AuthController::class)->group(function () {
        Route::post('logout', 'logout')->name('auth.logout');
        Route::get('user', 'user')->name('auth.user');
        Route::get('verify-email/{id}/{hash}', 'verifyEmail')
            ->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
        Route::post('email/verification-notification', 'sendVerificationEmail')
            ->middleware('throttle:6,1')
            ->name('verification.send');

    });
    Route::controller(ItemController::class)->prefix('/items')->group(function () {
        Route::get('', 'index')->name('items.index');
        Route::get('borrowed', 'getBorrowedItemsFromUser')->name('items.borrowed');

        Route::post('{item}/borrow', 'borrowItem')->name('items.borrow');
        Route::post('{item}/return', 'returnItem')->name('items.return');

        Route::get('loan-history', 'getLoanHistoryForUser')->name('items.loanHistory');
    });

    // Admin-Routen
    Route::middleware(AdminMiddleware::class)->prefix('admin')->name('admin.')->group(function () {
        Route::controller(AdminController::class)->group(function () {
            Route::get('users', 'getAllUsers')->name('users.index');
            Route::post('users/{user}/make-admin', 'makeAdmin')->name('users.make-admin');
            Route::post('users/{user}/remove-admin', 'removeAdmin')->name('users.remove-admin');
            Route::get('statistics', 'getStatistics')->name('statistics');
        });

        Route::post('users/register', [AuthController::class, 'register'])->name('users.register');
    });
});
