<?php

namespace AlfaDevTeam\AuthApi\Components\TwoFactor;

use AlfaDevTeam\AuthApi\Components\ConfirmationAuthentication;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

class GoogleConfirmationAuthentication extends ConfirmationAuthentication
{
    public function sendCode(): bool
    {
        return true;
    }

    protected function checkCode($code): bool
    {
        return (new GoogleAuthenticator())
            ->checkCode(
                $this->user->google_authentication_secret,
                $code
            );
    }
}
