<?php

namespace AlfaDevTeam\AuthApi;

use Illuminate\Support\Facades\Route;

class AuthApi
{
    public function authRoutes(string $prefix = '', array $middleware = [])
    {
        Route::prefix($prefix)->middleware($middleware)->group(__DIR__.'/../routes/auth.php');
    }

    public function settingsRoutes(string $prefix = '', array $middleware = [])
    {
        Route::prefix($prefix)->middleware($middleware)->group(__DIR__.'/../routes/settings.php');
    }
}