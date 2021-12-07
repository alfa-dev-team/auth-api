<?php

namespace AlfaDevTeam\AuthApi\Controllers\Auth;

use AlfaDevTeam\AuthApi\Controllers\ApiController;
use AlfaDevTeam\AuthApi\Rules\ComplexPassword;
use AlfaDevTeam\RestApiResponses\Controllers\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use function config;

class RegisterController extends ApiController
{
    private $trustedDeviceModel;

    public function __construct()
    {
        $this->trustedDeviceModel = config('');

    }

    public function register(Request $request)
    {
        $fields = $this->validate($request);

        $token = $this->runInTransaction(function () use ($fields, $request) {
            $user = $this->create($fields);

            $token = $user->createToken()->plainTextToken;

            $this->trustedDeviceModel::createDevice($user->id);

            return $token;
        });
        return $this->respondSuccess(['token' => $token]);
    }

    protected function validate(Request $request): array
    {
        return $request->validate([
            'email' => 'required|string|email|unique:users',
            'password' => ['required', 'string', 'confirmed', 'min:8', new ComplexPassword],
            'password_confirmation' => 'required|string|min:8'
        ]);
    }

    protected function create(array $data)
    {
        $user = $this->userModel::create([
            'email' => $data['email'],
            'name' => $this->generateLogin(),
            'password' => Hash::make($data['password']),
        ]);

        return $user;
    }

    protected function generateLogin(): string
    {
        return 'CS' . mt_rand(100000, 999999);
    }
}
