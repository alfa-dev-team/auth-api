<?php

namespace AlfaDevTeam\AuthApi\Middleware;

use AlfaDevTeam\RestApiResponses\Controllers\ApiResponses;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Blocked
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
        $blockedUser = $request->user()->blocked;
        if ($blockedUser !== null && $blockedUser->isBlocked()) {
            return $this->respondErrorMessage(
                'User is blocked',
                JsonResponse::HTTP_UNAUTHORIZED
            );
        }
        return $next($request);
    }
}
