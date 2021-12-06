<?php

namespace AlfaDevTeam\AuthApi\Tests\Feature;

use AlfaDevTeam\AuthApi\Tests\TestCase;
use App\Models\User\UserConfirmation;

class ConfirmationTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->registerUserWithToken([
            'email_confirmed_at' => null,
            'phone_confirmed_at' => null,
            'phone' => null,
        ]);
    }

    public function testSendConfirmationCode()
    {
        $this->assertSuccessResponse(
            $this->getAuthorizationJson('api/v1/confirmation/send-code')
        );

        $this->assertNotEmpty(UserConfirmation::where('user_id', $this->user->id)->first()->code);
    }

    public function testConfirmUser()
    {
        $code = mt_rand(100000, 999999);

        UserConfirmation::create([
            'user_id' => $this->user->id,
            'code' => $code,
        ]);

        $this->assertSuccessResponse(
            $this->postAuthorizationJson(
                'api/v1/confirmation/confirm',
                ['code' => (string)$code,]
            )
        );
    }

    public function testConfirmUserNotValidCode()
    {
        $code = mt_rand(100000, 999999);

        UserConfirmation::create([
            'user_id' => $this->user->id,
            'code' => $code,
        ]);

        $this->assertErrorResponse(
            $this->postAuthorizationJson(
                'api/v1/confirmation/confirm',
                ['code' => '000000',]
            )
        );

        $this->assertNotNull($this->user->confirmationAttempt()->first());
        $this->assertEquals(1, $this->user->confirmationAttempt()->first()->attempts);
    }
}
