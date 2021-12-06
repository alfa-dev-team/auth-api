<?php

namespace AlfaDevTeam\AuthApi\Tests\Feature;

use AlfaDevTeam\AuthApi\Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Modules\Cabinet\Entities\PasswordReset;
use Modules\Cabinet\Entities\User\User;

class PasswordResetTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->registerUserWithToken();
    }

    public function testForgotPassword()
    {
        $this->assertSuccessResponse(
            $this->postRequestJson(
                '/api/v1/forgot-password',
                [
                    'email' => $this->user->email
                ]
            )
        );

        $this->assertNotEmpty(PasswordReset::where('user_id', $this->user->id));
    }

    public function testResetPassword()
    {
        PasswordReset::create(['user_id' => $this->user->id, 'code' => 12345678]);

        $this->assertSuccessResponse(
            $this->postRequestJson(
                '/api/v1/reset-password/',
                [
                    'email' => $this->user->email,
                    'code' => '12345678',
                    'password' => '12345678Db;',
                    'password_confirmation' => '12345678Db;'
                ]
            )
        );

        $this->assertNotTrue(Hash::check($this->password, User::find($this->user->id)->password));
    }

    public function testResetPasswordWithNotValidParameters()
    {
        $passwordReset = PasswordReset::create(['user_id' => $this->user->id, 'code' => 12345678]);

//        Not valid code
        $this->assertValidationExceptionResponse(
            $this->postRequestJson(
                '/api/v1/reset-password/',
                [
                    'email' => $this->user->email,
                    'code' => '87654321',
                    'password' => '12345678Db;',
                    'password_confirmation' => '12345678Db;'
                ]
            ),
            ['code' => ['validation.exists']]
        );


//      Not valid email
        $this->assertValidationExceptionResponse(
            $this->postRequestJson(
                '/api/v1/reset-password/',
                [
                    'email' => 'dsada@dsa.us',
                    'code' => '12345678',
                    'password' => '12345678Db;',
                    'password_confirmation' => '12345678Db;'
                ]),
            ['email' => ['validation.exists']]
        );


//      Not valid password
        $this->assertValidationExceptionResponse(
            $this->postRequestJson(
                '/api/v1/reset-password/',
                [
                    'email' => $this->user->email,
                    'code' => '12345678',
                    'password' => '12345678D',
                    'password_confirmation' => '12345678Db;'
                ]
            ),
            ['password' => ['validation.confirmed']]
        );

        // Code is very old.
        $passwordReset->update(['user_id' => $this->user->id, 'code' => 12345678,
            'created_at' => now()->subMinutes(50)]);

        $this->assertValidationExceptionResponse(
            $this->postRequestJson(
                '/api/v1/reset-password/',
                [
                    'email' => $this->user->email,
                    'code' => '12345678',
                    'password' => '12345678Db;',
                    'password_confirmation' => '12345678Db;'
                ]),
            ['code' => ['validation.exists']]
        );
    }
}
