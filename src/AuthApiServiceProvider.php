<?php

namespace AlfaDevTeam\AuthApi;

use AlfaDevTeam\AuthApi\Middleware\Blocked;
use AlfaDevTeam\AuthApi\Middleware\Confirmed;
use AlfaDevTeam\AuthApi\Middleware\TrustedDevice;
use AlfaDevTeam\AuthApi\Middleware\TwoFactor;
use AlfaDevTeam\AuthApi\Models\PersonalAccessToken;
use Illuminate\Routing\Router;
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
        $this->loadMiddlewares();
    }

    public function register()
    {
        $this->app->bind('authapi', function ($app) {
            return new AuthApi();
        });
    }

    protected function loadMiddlewares(){
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('user.confirmed', Confirmed::class);
        $router->aliasMiddleware('user.not_blocked', Blocked::class);
        $router->aliasMiddleware('user.device.trusted', TrustedDevice::class);
        $router->aliasMiddleware('user.two_factor', TwoFactor::class);
    }
}