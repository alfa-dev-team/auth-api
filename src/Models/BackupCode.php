<?php

namespace AlfaDevTeam\AuthApi\Models;

use Illuminate\Database\Eloquent\Model;

class BackupCode extends Model
{
    protected $fillable = [
        'user_id', 'code'
    ];

    public function user()
    {
        $userModel = config('auth-api.model.user');
        return $this->belongsTo($userModel);
    }
}
