<?php

namespace AlfaDevTeam\AuthApi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserConfirmation extends Model
{
    protected $fillable = ['user_id', 'code'];

    public function user()
    {
        $userModel = config('auth-api.model.user');
        return $this->belongsTo($userModel);
    }

    public function save(array $options = [])
    {
        $this->code = Hash::make($this->code);
        return parent::save();
    }


    public function canSendCode(): bool
    {
        if ($this->exists) {
            $intervalSinceLastSend = $this->updated_at->diffInMinutes(now());
            $maxWaitIntervalForSendCode = config('cabinet.account_confirmation.max_interval_send_code');
            return $intervalSinceLastSend > $maxWaitIntervalForSendCode;
        }
        return true;
    }

    public function isCorrectCode($code)
    {
        if (Hash::check($code, $this->code)) {
            $this->delete();
            return true;
        }
        return false;
    }
}
