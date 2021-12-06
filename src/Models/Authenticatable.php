<?php

namespace AlfaDevTeam\AuthApi\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Authenticatable extends \Illuminate\Foundation\Auth\User
{
    use HasApiTokens, HasFactory, Notifiable;
}