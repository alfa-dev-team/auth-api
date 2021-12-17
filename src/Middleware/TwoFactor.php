<?php

namespace AlfaDevTeam\AuthApi\Middleware;

use AlfaDevTeam\RestApiResponses\Controllers\ApiResponses;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TwoFactor
{
    use ApiResponses;

    public function handle(Request $request, Closure $next)
    {
        if ($this->notConfirmedTwoFactor($request->user())) {
            abort($this->respondErrorMessage(
                'Unauthenticated user',
                JsonResponse::HTTP_UNAUTHORIZED
            ));
        }
        return $next($request);
    }

    private function notConfirmedTwoFactor($user)
    {
        return !$user->currentAccessToken()->confirmed
            && !$user->isDisabledTwoFactorAuthentication();
    }
}