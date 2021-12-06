<?php

namespace AlfaDevTeam\AuthApi\Tests\Feature;

use AlfaDevTeam\AuthApi\Tests\TestCase;

class LoginTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->registerUserWithToken(['email' => $this->email]);
    }

    public function testLogin()
    {
        $this->assertSuccessResponse(
            $this->postRequestJson(
                'login',
                [
                    'email' => $this->email,
                    'password' => $this->password,
                ]
            )
        )->assertJsonPath('response.two_factor_status', 'email');
    }
}
