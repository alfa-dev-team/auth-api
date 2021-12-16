<?php

namespace AlfaDevTeam\AuthApi\Traits;

use AlfaDevTeam\AbstractapiGeo\Services\Location;
use AlfaDevTeam\AuthApi\Components\ConfirmationAuthentication;
use AlfaDevTeam\AuthApi\Models\BackupCode;
use AlfaDevTeam\AuthApi\Models\TrustedDevice;
use AlfaDevTeam\AuthApi\Models\UserConfirmation;
use AlfaDevTeam\AuthApi\Models\UserConfirmationAttempt;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Jenssegers\Agent\Facades\Agent;
use Laravel\Sanctum\NewAccessToken;

trait AuthApiUser
{
    public function scopeFindByEmail(Builder $query, $email):Builder
    {
        return $query->where('email', $email);
    }

    public function confirmation()
    {
        return $this->hasOne(UserConfirmation::class);
    }

    public function confirmationAttempt()
    {
        return $this->hasOne(UserConfirmationAttempt::class);
    }

    public function backupCodes()
    {
        config('auth-api.model.blocked_user');
        return $this->hasMany(BackupCode::class);
    }

    public function trustedDevices()
    {
        return $this->hasMany(TrustedDevice::class);
    }

    public function createToken(string $name = null, array $abilities = ['*'])
    {
        $ip = request()->ip();
        $location = Location::getGeoByIp($ip)->saveToDb($this->id);
        $token = $this->tokens()->create([
            'name' => Agent::platform(),
            'is_mobile' => Agent::isMobile(),
            'token' => hash('sha256', $plainTextToken = Str::random(40)),
            'abilities' => $abilities,
            'ip' => $ip,
            'os' => Agent::platform(),
            'browser' => Agent::browser(),
            'country' => $location->getCountry(),
            'city' => $location->getCity()
        ]);

        return new NewAccessToken($token, $token->getKey() . '|' . $plainTextToken);
    }

    public function confirm(): bool
    {
        if ($this->email) {
            $this->email_confirmed_at = now();
        } elseif ($this->phone) {
            $this->phone_confirmed_at = now();
        }
        return $this->save();
    }

    public function getTypeConfirmationAuthentication()
    {
        if ($this->isConfirmed()) {
            return $this->two_factor_authentication_type;
        }
        return ConfirmationAuthentication::USER_CONFIRMATION;
    }

    public function isConfirmed(): bool
    {
        return $this->email_confirmed_at !== null || $this->phone_confirmed_at !== null;
    }

    public function enableTwoFactorAuthentication($type)
    {
        $this->two_factor_authentication_type = $type;
        return $this->save();
    }

    public function disableTwoFactorAuthentication()
    {
        $this->two_factor_authentication_type = null;
        return $this->save();
    }

    public function isDisabledTwoFactorAuthentication()
    {
        return is_null($this->two_factor_authentication_type);
    }
}