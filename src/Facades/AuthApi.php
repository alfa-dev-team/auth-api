<?php

namespace AlfaDevTeam\AuthApi\Facades;

class AuthApi extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'authapi';
    }
}