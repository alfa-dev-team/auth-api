<?php

namespace AlfaDevTeam\AuthApi\Middleware;

use AlfaDevTeam\RestApiResponses\Controllers\ApiResponses;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrustedDevice
{
    use ApiResponses;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (config('app.env') == 'local') {
            return $next($request);
        }

        $isNotTrustedDevice = !$request->user()->trustedDevices()->isTrustedDevice();
        $isDisabledTwoFactorAuthentication = $request->user()->isDisabledTwoFactorAuthentication();

        if ($isDisabledTwoFactorAuthentication && $isNotTrustedDevice) {
            abort($this->respondErrorMessage(
                'Does not have a trusted device',
                JsonResponse::HTTP_UNAUTHORIZED
            ));
        }
        return $next($request);
    }
}
