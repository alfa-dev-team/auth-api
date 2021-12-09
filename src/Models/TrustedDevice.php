<?php

namespace AlfaDevTeam\AuthApi\Models;

use AlfaDevTeam\AbstractapiGeo\Services\Location;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Agent\Facades\Agent;

class TrustedDevice extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'is_mobile', 'browser', 'os', 'ip', 'country', 'city'
    ];

    public static function createDevice($userId)
    {
        $ip = request()->ip();
        $location = Location::getGeoByIp($ip);
        return TrustedDevice::create([
            'user_id' => $userId,
            'is_mobile' => Agent::isMobile(),
            'browser' => Agent::browser(),
            'os' => Agent::platform(),
            'ip' => $ip,
            'country' => $location->getCountry(),
            'city' => $location->getCity()
        ]);
    }

    public function user()
    {
        $userModel = config('auth-api.model.user');
        return $this->belongsTo($userModel);
    }

    public function scopeIsTrustedDevice($query)
    {
        $ip = request()->ip();
        $location = Location::getGeoByIp($ip);
        return $query->where('ip', $ip)
            ->where('browser', Agent::browser())
            ->where('os', Agent::platform())
            ->where('is_mobile', Agent::isMobile())
            ->where('country', $location->getCountry())
            ->where('city', $location->getCity())
            ->exists();
    }
}
