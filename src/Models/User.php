<?php

namespace AlfaDevTeam\AuthApi\Models;

use AlfaDevTeam\AuthApi\Traits\AuthApiUser;

class User extends Authenticatable
{
    use AuthApiUser;
}