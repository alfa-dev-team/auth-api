<?php

namespace AlfaDevTeam\AuthApi\Models;

use Jenssegers\Agent\Facades\Agent;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'token', 'abilities', 'browser', 'os', 'ip', 'country', 'city', 'created_at', 'confirmed', 'is_mobile'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'abilities' => 'json',
        'last_used_at' => 'datetime',
    ];

    /** Checks the true user by IP, browser, OS, device.
     *
     * @return bool
     */
    public function isTrueDevice()
    {
        return $this->ip == request()->ip()
            && $this->os == Agent::platform()
            && $this->browser == Agent::browser()
            && $this->is_mobile == Agent::isMobile();
    }

    public function confirm(): bool
    {
        $this->confirmed = true;
        return $this->save();
    }

    public function save(array $options = [])
    {
        if ($this->onlyLastUsedAtUpdates() && $this->isExpiredLastUsedAtUpdateInterval()) {
            return false;
        }

        return parent::save();
    }

    protected function onlyLastUsedAtUpdates(): bool
    {
        $changes = $this->getDirty();
        return array_key_exists('last_used_at', $changes) && count($changes) == 1;
    }

    protected function isExpiredLastUsedAtUpdateInterval(): bool
    {
        $originalLastUsed = $this->getOriginal('last_used_at');

        $updateTime = config('sanctum.last_used_at_update_interval');
        return is_null($originalLastUsed) ? false : $originalLastUsed->gt(now()->subMinutes($updateTime));
    }
}
