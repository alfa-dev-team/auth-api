<?php

use AlfaDevTeam\AuthApi\Controllers\Auth\ConfirmationController;
use AlfaDevTeam\AuthApi\Controllers\Auth\LoginController;
use AlfaDevTeam\AuthApi\Controllers\Auth\PasswordResetController;
use AlfaDevTeam\AuthApi\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);

Route::post('forgot-password', [PasswordResetController::class, 'sendCode']);
Route::post('reset-password', [PasswordResetController::class, 'reset']);

Route::middleware(['auth:sanctum'])->get('logout', [LoginController::class, 'logout']);

Route::prefix('confirmation')->middleware(['auth:sanctum', 'user.not_blocked'])
    ->group(function () {
        Route::get('send-code', [ConfirmationController::class, 'sendCode']);

        Route::post('confirm', [ConfirmationController::class, 'confirm']);

        Route::get('status', [ConfirmationController::class,
            'showConfirmationAuthenticationStatus']);
    });