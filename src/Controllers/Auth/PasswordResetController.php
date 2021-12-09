<?php

namespace AlfaDevTeam\AuthApi\Controllers\Auth;

use AlfaDevTeam\AuthApi\Controllers\ApiController;
use AlfaDevTeam\AuthApi\Models\PasswordReset;
use AlfaDevTeam\AuthApi\Notifications\PasswordResetNotification;
use AlfaDevTeam\AuthApi\Rules\ComplexPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends ApiController
{
    protected $passwordResetModel;

    public function __construct()
    {
        parent::__construct();
        $this->passwordResetModel = config('auth-api.models.password_reset')?? PasswordReset::class;
    }

    public function sendCode(Request $request)
    {
        $fields = $request->validate([
            'email' => 'bail|required|string|exists:users'
        ]);

        $user = $this->userModel::findByEmail($fields['email'])->first();

        $code = $this->getGeneratedCode();

        $this->passwordResetModel::create([
            'user_id' => $user->id,
            'code' => $code
        ]);

        $user->notify(new PasswordResetNotification($code));
        return $this->respondSuccess();
    }

    public function reset(Request $request)
    {
        $fields = $request->validate([
            'email' => 'bail|required|string',
            'code' => 'required|size:8',
            'password' => ['bail', 'required', 'string', 'min:8', 'confirmed', new ComplexPassword],
        ]);

        $user = $this->userModel::findByEmail($fields['email'])->first();

        if (is_null($user)) {
            $this->respondValidationException(['email' => 'validation.exists']);
        }

        if (!$this->passwordResetModel::checkCodeByUserId($user->id, $fields['code'])) {
            $this->respondValidationException(['code' => 'validation.exists']);
        }

        $user->update(['password' => Hash::make($fields['password'])]);
        return $this->respondSuccess();
    }

    protected function getGeneratedCode()
    {
        return mt_rand(00000000, 99999999);
    }
}
