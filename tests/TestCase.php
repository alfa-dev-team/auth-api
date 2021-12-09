<?php

namespace AlfaDevTeam\AuthApi\Tests;

use AlfaDevTeam\AuthApi\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use AlfaDevTeam\RestApiResponses\Tests\ApiRequestAssertions;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use ApiRequestAssertions;

    protected function getPackageProviders($app)
    {
        return [
            \AlfaDevTeam\AuthApi\AuthApiServiceProvider::class,
        ];
    }

    public function ignorePackageDiscoveriesFrom()
    {
        return [];
    }

    protected function defineDatabaseMigrations()
    {
//        $this->loadLaravelMigrations();
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->loadMigrationsFrom(__DIR__ . '../vendor/laravel/sanctum/database/migrations');
        $this->loadMigrationsFrom(__DIR__ . '../vendor/alfa-dev-team/abstractapi-geo/database/migrations');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    public function getEnvironmentSetUp($app)
    {
//        include_once __DIR__ . '/../database/migrations/';
        // import the CreatePostsTable class from the migration
//        include_once __DIR__ . '/../database/migrations/add_auth_api_columns_to_password_resets_table.php.stub';
//        include_once __DIR__ . '/../database/migrations/add_auth_api_columns_to_personal_access_tokens_table.php.stub';
//        include_once __DIR__ . '/../database/migrations/add_auth_api_columns_to_users_table.php.stub';
//        include_once __DIR__ . '/../database/migrations/create_backup_codes_table.php.stub';
//        include_once __DIR__ . '/../database/migrations/create_blocked_users_table.php.stub';
//        include_once __DIR__ . '/../database/migrations/create_trusted_devices_table.php.stub';
//        include_once __DIR__ . '/../database/migrations/create_user_confirmations_table.php.stub';
//        include_once __DIR__ . '/../database/migrations/create_user_confirmation_attempts_table.php.stub';

//        // run the up() method of that migration class
//        (new \AddAuthApiColumnsToPasswordResetsTable)->up();
//        (new \AddAuthApiColumnsToPersonalAccessTokenTable)->up();
//        (new \AddAuthApiColumnsToPasswordResetsTable)->up();
//        (new \AddAuthApiColumnsToPasswordResetsTable)->up();
//        (new \AddAuthApiColumnsToPasswordResetsTable)->up();
    }

    protected $user;

//    protected $seed = true;

    protected $email = 'andriy@1231.ua';

    protected $phone = '+380971307121';

    protected $password = '123456789Db;';

    protected $plainTextSanctumToken, $accessSanctumToken;

    /**
     * @param mixed $accessSanctumToken
     */
    protected function setAccessSanctumToken($accessSanctumToken): void
    {
        $this->accessSanctumToken = $accessSanctumToken;
        $this->accessSanctumToken->confirmed = true;
        $this->accessSanctumToken->save();
    }

    /**
     * @param mixed $plainTextSanctumToken
     */
    protected function setPlainTextSanctumToken($plainTextSanctumToken)
    {
        $this->plainTextSanctumToken = $plainTextSanctumToken;
        return $this;
    }

    protected function setTwoFactorAuthenticationType($type)
    {
        $this->user->two_factor_authentication_type = $type;
        $this->user->save();
        return $this;
    }

    protected function registerUserWithToken(array $data = [])
    {
        $data['two_factor_authentication_type'] = 'email';
        $this->user = User::factory()->create($data);
        $token = $this->user->createToken();
        $this->setAccessSanctumToken($token->accessToken);
        $this->setPlainTextSanctumToken($token->plainTextToken);
        return $this;
    }

    protected function addWalletsToUser($balance, $currency_id)
    {
        $this->user->wallets()->attach(
            $currency_id,
            [
                'balance' => $balance,
                'bonus_balance' => $balance
            ]
        );
    }
}