<?php

namespace AlfaDevTeam\AuthApi\Providers;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/auth-api.php' => config_path('auth-api.php'),
        ], 'configs');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
//        $this->publishes([
//            __DIR__ . '/../../database/migrations' => database_path('migrations')
//        ], 'migrations');
    }
}