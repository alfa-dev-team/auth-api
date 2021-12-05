<?php

namespace AlfaDevTeam\AuthApi\Components\TwoFactor;

use AlfaDevTeam\AuthApi\Actions\SendConfirmationCodeAction;
use AlfaDevTeam\AuthApi\Components\ConfirmationAuthentication;
use Illuminate\Http\Request;

class PhoneConfirmationAuthentication extends ConfirmationAuthentication
{
    public function sendCode(): bool
    {
        return (new SendConfirmationCodeAction())
            ->setUser($this->user)
            ->setReceiver('phone', $this->user->phone)
            ->execute();
    }
}
