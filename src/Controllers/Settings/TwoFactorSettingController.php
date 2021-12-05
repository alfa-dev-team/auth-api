<?php

namespace AlfaDevTeam\AuthApi\Controllers\Settings;

use AlfaDevTeam\AuthApi\Components\ConfirmationAuthentication;
use AlfaDevTeam\AuthApi\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;
use function auth;
use function config;

class TwoFactorSettingController extends ApiController
{
    public function getDataGoogleAuthentication()
    {
        $googleAuthenticationSecret = (new GoogleAuthenticator())->generateSecret();

        $qrUrl = GoogleQrUrl::generate(auth()->user()->name, $googleAuthenticationSecret, config('app.name'));

        return $this->respondSuccess([
            'google_authentication_secret' => $googleAuthenticationSecret,
            'qr_url' => $qrUrl
        ]);
    }

    public function sendCode()
    {
        $type = auth()->user()->getTypeConfirmationAuthentication();

        $confirmationAuthentication = ConfirmationAuthentication::getInstance($type)
            ->setUser(auth()->user());
        $responseMessage = $confirmationAuthentication->getResponseMessage();

        if ($confirmationAuthentication->sendCode()) {
            return $this->respondSuccessMessage($responseMessage);
        }

        return $this->respondErrorMessage($responseMessage);
    }

    public function enableGoogleAuthentication(Request $request)
    {
        $fields = $request->validate([
            'google_authentication_secret' => 'string',
            'code' => 'required|string|size:6',
        ]);

        $type = ConfirmationAuthentication::GOOGLE_AUTHENTICATION;

        return $this->runInTransaction(function () use ($fields, $type) {
            auth()->user()->google_authentication_secret = $fields['google_authentication_secret'];

            $confirmationAuthentication = ConfirmationAuthentication::getInstance($type)
                ->setUser(auth()->user());

            $authenticationSuccess = $confirmationAuthentication->authenticate($fields['code']);
            if ($authenticationSuccess) {
                auth()->user()->enableTwoFactorAuthentication($type);
                return $this->respondSuccessMessage('Enable google two-factor authentication');
            }

            return $this->respondValidationException(['code' => 'auth.incorrect']);
        });

    }

    public function enableEmailAuthentication(Request $request)
    {
        $fields = $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $type = auth()->user()->getTypeConfirmationAuthentication();

        return $this->runInTransaction(function () use ($fields, $type){
            $confirmationAuthentication = ConfirmationAuthentication::getInstance($type)
                ->setUser(auth()->user());

            $authenticationSuccess = $confirmationAuthentication->authenticate($fields['code']);

            if ($authenticationSuccess) {
                if ($type == ConfirmationAuthentication::USER_CONFIRMATION) {
                    auth()->user()->currentAccessToken()->confirm();
                }
                auth()->user()->enableTwoFactorAuthentication(ConfirmationAuthentication::EMAIL);
                return $this->respondSuccessMessage('Enable email two-factor authentication');
            }

            return $this->respondValidationException(['code' => 'auth.incorrect']);
        });
    }

    public function disable(Request $request)
    {
        $fields = $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $type = auth()->user()->two_factor_authentication_type;
        if (is_null($type)){
            return $this->respondErrorMessage('Two-factor authentication is already disabled');
        }

        $confirmationAuthentication = ConfirmationAuthentication::getInstance($type)
            ->setUser(auth()->user());

        $authenticationSuccess = $confirmationAuthentication->authenticate($fields['code']);
        if ($authenticationSuccess) {
            auth()->user()->disableTwoFactorAuthentication();
            return $this->respondSuccessMessage('Two-factor authentication disable');
        }
        return $this->respondValidationException(['code' => 'auth.incorrect']);
    }

    public function createBackupCodes()
    {
        $backupCodes = null;
        $this->runInTransaction(function () use (&$backupCodes) {
            auth()->user()->backupCodes()->delete();
            for ($i = 0; $i < 10; $i++) {
                $code = (string)mt_rand(100000, 999999);
                $backupCodes[] = $code;
                auth()->user()->backupCodes()->create([
                    'code' => Hash::make($code)
                ]);
            }
        });
        return $this->respondSuccess(['backup_codes' => $backupCodes]);
    }
}