<?php

namespace AlfaDevTeam\AuthApi\Components;

use AlfaDevTeam\AuthApi\Actions\SendConfirmationCodeAction;
use Illuminate\Support\Facades\DB;

class UserConfirmationAuthentication extends ConfirmationAuthentication
{
    const SUCCESS_RESPONSE_MESSAGE = 'auth.confirm.confirmed';
    const FAIL_RESPONSE_MESSAGE = 'auth.confirm.fail';

    public function authenticate($code): bool
    {
        if ($this->checkCode($code)) {
            DB::beginTransaction();
            try {
                config('auth-api.models.trusted_device')::createDevice($this->user->id);
                $this->user->confirm();
                $this->responseMessage = self::SUCCESS_RESPONSE_MESSAGE;
                DB::commit();
                return true;
            } catch (\Exception $exception) {
                DB::rollBack();
            }
        }

        $this->responseMessage = self::FAIL_RESPONSE_MESSAGE;

        return false;
    }

    public function sendCode(): bool
    {
        return (new SendConfirmationCodeAction())
            ->setUser($this->user)
            ->execute();
    }

    public function getResponseMessage()
    {
        return $this->responseMessage;
    }
}
