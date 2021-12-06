<?php

namespace AlfaDevTeam\AuthApi\Models;

use Illuminate\Database\Eloquent\Model;

class UserConfirmationAttempt extends Model
{
    protected $fillable = ['attempts'];

    public function user()
    {
        $userModel = config('auth-api.model.user');
        return $this->belongsTo($userModel);
    }

    public function resets()
    {
        $this->attempts = 0;
        return $this->save();
    }
}
