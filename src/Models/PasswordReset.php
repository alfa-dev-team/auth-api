<?php

namespace AlfaDevTeam\AuthApi\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'code', 'created_at'
    ];

    public function scopeCheckCodeByUserId($query, int $userId, string $code): bool
    {
        $validTime = config('auth-api.password_reset.valid_time');

        return $query->where('code', $code)
            ->where('user_id', $userId)
            ->where('created_at', '>', now()->subMinutes($validTime))->exists();
    }

    public function user()
    {
        $userModel = config('auth-api.models.user');
        return $this->belongsTo($userModel);
    }
}