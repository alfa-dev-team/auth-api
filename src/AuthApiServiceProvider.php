<?php

namespace AlfaDevTeam\AuthApi;

use AlfaDevTeam\AuthApi\Models\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use function config_path;

class AuthApiServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/auth-api.php' => config_path('auth-api.php'),
        ], 'configs');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }

    public function register()
    {
        $this->app->bind('authapi', function ($app) {
            return new AuthApi();
        });
    }
}