<?php

namespace AlfaDevTeam\AuthApi\Components;

use AlfaDevTeam\AuthApi\Actions\SendConfirmationCodeAction;
use Illuminate\Support\Facades\DB;

class TrustedDeviceConfirmationAuthentication extends ConfirmationAuthentication
{

    public function sendCode(): bool
    {
        return (new SendConfirmationCodeAction())
            ->setUser($this->user)
            ->setReceiver('email', $this->user->email)
            ->execute();
    }

    public function authenticate($code): bool
    {
        if ($this->checkCode($code) || $this->checkBackupCodes($code)) {
            DB::beginTransaction();
            try {
                config('auth-api.models.trusted_device')::createDevice($this->user->id);
                $this->user->currentAccessToken()->confirm();
                DB::commit();
                return true;
            } catch (\Exception $exception) {
                DB::rollBack();
                return false;
            }
        }
        return false;
    }
}
