<?php

namespace AlfaDevTeam\AuthApi\Providers;

use Illuminate\Support\Str;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const PATH_MIGRATIONS = __DIR__ . '/../../database/migrations/';

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
            __DIR__ . '/../../config/auth-api.php' => config_path('auth-api.php'),
        ], 'configs');
        $this->publishesMigrations();
        $this->loadRoutes();
    }

    protected function publishesMigrations()
    {
        foreach ($this->migrations as $migration) {
            $newMigrationName = $this->migrationsTimestamps().'_'.Str::remove('.stub', $migration);
            $this->publishes([
                self::PATH_MIGRATIONS . $migration =>
                    database_path('migrations/'. $newMigrationName)
            ],'migrations');
        }
    }

    protected function loadRoutes()
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/auth.php');
        $this->loadRoutesFrom(__DIR__.'/../../routes/auth.php');
    }

    private function migrationsTimestamps()
    {
        return date('Y_m_d_His', time());
    }
}