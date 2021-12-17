<?php

namespace AlfaDevTeam\AuthApi\Middleware;

use AlfaDevTeam\RestApiResponses\Controllers\ApiResponses;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Confirmed
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
        if (!$request->user()->isConfirmed()) {
            abort( $this->respondErrorMessage(
                'Unconfirmed user',
                JsonResponse::HTTP_UNAUTHORIZED
            ));
        }
        return $next($request);
    }
}
