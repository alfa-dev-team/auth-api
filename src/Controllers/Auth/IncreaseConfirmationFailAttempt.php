<?php

namespace AlfaDevTeam\AuthApi\Controllers\Auth;

use function auth;
use function config;
use function now;

trait IncreaseConfirmationFailAttempt
{
    protected function increaseFailAttempt()
    {
        $attempt = auth()->user()->confirmationAttempt()->firstOrCreate();
        $attempt->increment('attempts');
        $maxFail = config('auth-api.account_confirmation.max_fail');
        if ($attempt->attempts >= $maxFail) {
            config('auth-api.model.blocked_user')::firstOrCreate(
                ['user_id' => auth()->id()],
                ['blocked_until' => now()->addDay(), 'full_blocked' => false]
            );
            $attempt->resets();
        }
    }
}
