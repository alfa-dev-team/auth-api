<?php

namespace AlfaDevTeam\AuthApi\Controllers\Auth;

use AlfaDevTeam\AuthApi\Components\ConfirmationAuthentication;
use AlfaDevTeam\AuthApi\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use function auth;
use function config;

class LoginController extends ApiController
{
    public function __construct()
    {
        $this->userModel = config('auth-api.models.user');
    }

    public function login(Request $request)
    {
        $fields = $this->validate($request);

        $user = $this->userModel::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return $this->respondValidationException([
                'email' => 'auth.failed',
                'password' => 'auth.failed'
            ]);
        }

        $token = $user->createToken()->plainTextToken;

        if ($user->two_factor_authentication_type == ConfirmationAuthentication::EMAIL) {
            $confirmationAuthentication = ConfirmationAuthentication::getInstance(
                ConfirmationAuthentication::EMAIL
            )->setUser($user);

            $confirmationAuthentication->sendCode();
        }
        return $this->respondSuccess([
            'token' => $token,
            'two_factor_status' => $user->two_factor_authentication_type
        ]);
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();

        return $this->respondSuccessMessage('User logged out');
    }

    protected function validate(Request $request): array
    {
        return $request->validate([
            'email' => 'required|string|exists:users',
            'password' => 'required|string',
        ]);
    }
}
