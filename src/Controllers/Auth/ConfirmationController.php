<?php

namespace AlfaDevTeam\AuthApi\Controllers\Auth;

use AlfaDevTeam\AuthApi\Components\ConfirmationAuthentication;
use AlfaDevTeam\AuthApi\Controllers\ApiController;
use Illuminate\Http\Request;
use function auth;

class ConfirmationController extends ApiController
{
    use IncreaseConfirmationFailAttempt;

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendCode()
    {
        $type = $this->getTypeConfirmationAuthentication();

        $confirmationAuthentication = ConfirmationAuthentication::getInstance($type)
            ->setUser(auth()->user());
        $responseMessage = $confirmationAuthentication->getResponseMessage();

        if ($confirmationAuthentication->sendCode()) {
            return $this->respondSuccessMessage($responseMessage);
        }

        return $this->respondErrorMessage($responseMessage);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirm(Request $request)
    {
        $fields = $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $type = $this->getTypeConfirmationAuthentication();

        $confirmationAuthentication = ConfirmationAuthentication::getInstance($type)
            ->setUser(auth()->user());

        $authenticated = $confirmationAuthentication->authenticate($fields['code']);

        $responseMessage = $confirmationAuthentication->getResponseMessage();

        if ($authenticated) {
            return $this->respondSuccessMessage($responseMessage);
        }
        $this->increaseFailAttempt();
        return $this->respondErrorMessage($responseMessage);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function showConfirmationAuthenticationStatus()
    {
        $status = $this->getTypeConfirmationAuthentication();
        return $this->respondSuccess(['status' => $status]);
    }

    /**
     * @return string
     */
    protected function getTypeConfirmationAuthentication()
    {
        if (auth()->user()->isConfirmed()) {
            return auth()->user()->two_factor_authentication_type;
        }
        return ConfirmationAuthentication::USER_CONFIRMATION;
    }
}
