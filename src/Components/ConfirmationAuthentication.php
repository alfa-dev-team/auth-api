<?php

namespace AlfaDevTeam\AuthApi\Components;

use AlfaDevTeam\AuthApi\Components\TwoFactor\EmailConfirmationAuthentication;
use AlfaDevTeam\AuthApi\Components\TwoFactor\GoogleConfirmationAuthentication;
use AlfaDevTeam\AuthApi\Components\TwoFactor\PhoneConfirmationAuthentication;
use Illuminate\Support\Facades\Hash;

abstract class ConfirmationAuthentication
{
    const EMAIL = 'email';
    const PHONE = 'phone';
    const GOOGLE_AUTHENTICATION = 'google_authentication';
    const USER_CONFIRMATION = 'user_confirmation';

    protected $user;

    protected string $responseMessage = '';

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public abstract function sendCode(): bool;

    public function authenticate($code): bool
    {
        if ($this->checkCode($code) || $this->checkBackupCodes($code)) {
            return $this->user->currentAccessToken()->confirm();
        }
        return false;
    }

    protected function checkCode($code): bool
    {
        return optional($this->user->confirmation)->isCorrectCode($code)?? false;
    }

    protected function checkBackupCodes($code): bool
    {
        $backupCodes = $this->user->backupCodes()->get();
        foreach ($backupCodes as $backupCode) {
            if (Hash::check($code, $backupCode->code)) {
                $backupCode->delete();
                return true;
            }
        }
        return false;
    }

    public function getResponseMessage()
    {
        return $this->responseMessage;
    }

    public static function getInstance($type): ConfirmationAuthentication
    {
        if ($type == self::EMAIL) {
            return new EmailConfirmationAuthentication();
        } elseif ($type == self::PHONE) {
            return new PhoneConfirmationAuthentication();
        } elseif ($type == self::GOOGLE_AUTHENTICATION) {
            return new GoogleConfirmationAuthentication();
        } elseif ($type == self::USER_CONFIRMATION) {
            return new UserConfirmationAuthentication();
        }
        return new TrustedDeviceConfirmationAuthentication();
    }
}
