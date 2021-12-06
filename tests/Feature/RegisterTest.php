<?php

namespace AlfaDevTeam\AuthApi\Tests\Feature;

use AlfaDevTeam\AuthApi\Tests\TestCase;
use Illuminate\Http\JsonResponse;

class RegisterTest extends TestCase
{
    public function testRegister()
    {
        $this->assertSuccessResponse(
            $this->postRequestJson(
                'api/v1/register',
                [
                    'email' => $this->email,
                    'password' => $this->password,
                    'password_confirmation' => $this->password,
                ]
            )
        );

        $this->assertErrorResponse(
            $this->postRequestJson(
                'api/v1/register',
                [
                    'email' => '$this->email',
                    'password' => $this->password,
                    'password_confirmation' => $this->password,
                ]
            ),
            JsonResponse::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
