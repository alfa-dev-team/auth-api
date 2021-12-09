<?php

namespace AlfaDevTeam\AuthApi;

use AlfaDevTeam\AuthApi\Models\PersonalAccessToken;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use function config_path;
use function database_path;

class AuthApiServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const PATH_MIGRATIONS = __DIR__ . '/../database/migrations/';

    protected $migrations = [
        'add_auth_api_columns_to_password_resets_table.php.stub',
        'add_auth_api_columns_to_personal_access_tokens_table.php.stub',
        'add_auth_api_columns_to_users_table.php.stub',
        'create_backup_codes_table.php.stub',
        'create_blocked_users_table.php.stub',
        'create_trusted_devices_table.php.stub',
        'create_user_confirmation_attempts_table.php.stub',
        'create_user_confirmations_table.php.stub'
    ];

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/auth-api.php' => config_path('auth-api.php'),
        ], 'configs');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
//        $this->publishesMigrations();
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        $this->loadRoutes();
    }

    protected function publishesMigrations()
    {
        foreach ($this->migrations as $migration) {
            $newMigrationName = $this->migrationsTimestamps() . '_' . Str::remove('.stub', $migration);
            $this->publishes([
                self::PATH_MIGRATIONS . $migration =>
                    database_path('migrations/' . $newMigrationName)
            ], 'migrations');
        }
    }

    protected function loadRoutes()
    {
        Route::group($this->routeConfiguration('auth'), function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/auth.php');
        });
        Route::group($this->routeConfiguration('settings'), function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/settings.php');
        });


    }

    protected function routeConfiguration(string $name)
    {
        return [
            'prefix' => config('auth-api.route_prefix.'.$name),
            'middleware' => config('auth-api.route_middleware.'.$name),
        ];
    }

    private function migrationsTimestamps()
    {
        return date('Y_m_d_His', time());
    }
}