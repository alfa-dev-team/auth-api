<?php

namespace AlfaDevTeam\AuthApi\Tests;

use AlfaDevTeam\RestApiResponses\Tests\ApiRequestAssertions;
use Illuminate\Testing\TestResponse;

trait AuthApiRequestForTest
{
    use ApiRequestAssertions;

    protected $userModel;

    protected $email = 'andriy@1231.ua';

    protected $phone = '+380971307121';

    protected $password = '123456789Db;';

    protected $plainTextSanctumToken, $accessSanctumToken;

    protected function setAccessSanctumToken($accessSanctumToken): void
    {
        $this->accessSanctumToken = $accessSanctumToken;
        $this->accessSanctumToken->confirmed = true;
        $this->accessSanctumToken->save();
    }

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
        $this->user = $this->userModel::factory()->create($data);
        $token = $this->user->createToken();
        $this->setAccessSanctumToken($token->accessToken);
        $this->setPlainTextSanctumToken($token->plainTextToken);
        return $this;
    }

    protected function postAuthorizationJson($url, array $params = [])
    {
        return $this->requestAuthorizationJson($url, 'postJson', $params);
    }

    protected function getAuthorizationJson($url): TestResponse
    {
        return $this->requestAuthorizationJson($url, 'getJson');
    }

    protected function deleteAuthorizationJson($url)
    {
        return $this->requestAuthorizationJson($url, 'deleteJson');
    }

    protected function requestAuthorizationJson(string $url, string $method, array $params = [])
    {
        return $this->withHeaders(['Authorization' => 'Bearer ' . $this->plainTextSanctumToken])
            ->requestJson($url, $method, $params);
    }
}