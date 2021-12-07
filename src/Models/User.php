<?php

namespace AlfaDevTeam\AuthApi\Models;

use AlfaDevTeam\AuthApi\Database\Factories\UserFactory;
use AlfaDevTeam\AuthApi\Traits\AuthApiUser;

class User extends Authenticatable
{
    use AuthApiUser;

    protected static function newFactory()
    {
        return new UserFactory();
    }
}