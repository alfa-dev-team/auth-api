<?php

use AlfaDevTeam\AuthApi\Controllers\Settings\TwoFactorSettingController;
use Illuminate\Routing\Route;

Route::prefix('two-factor-authentication')->group(function () {
    Route::get('send-code', [TwoFactorSettingController::class, 'sendCode']);
    Route::post('disable', [TwoFactorSettingController::class, 'disable']);

    Route::prefix('enable')->group(function () {
        Route::get('google', [TwoFactorSettingController::class, 'getDataGoogleAuthentication']);
        Route::post('google', [TwoFactorSettingController::class, 'enableGoogleAuthentication']);
        Route::post('email', [TwoFactorSettingController::class, 'enableEmailAuthentication']);
    });
    Route::get('backup-codes', [TwoFactorSettingController::class, 'createBackupCodes']);
});