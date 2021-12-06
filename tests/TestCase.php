<?php

namespace AlfaDevTeam\AuthApi\Tests;

use AlfaDevTeam\AuthApi\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use AlfaDevTeam\RestApiResponses\Tests\ApiRequestAssertions;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use ApiRequestAssertions, RefreshDatabase;

    protected function getPackageProviders($app)
    {
        return [
            \AlfaDevTeam\AuthApi\Providers\ServiceProvider::class,
        ];
    }

    public function ignorePackageDiscoveriesFrom()
    {
        return [];
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