<?php

namespace AlfaDevTeam\AuthApi\Controllers;

use AlfaDevTeam\AuthApi\Models\User;
use AlfaDevTeam\RestApiResponses\Controllers\ApiResponses;
use AlfaDevTeam\RestApiResponses\Controllers\WrapperTransaction;
use Illuminate\Routing\Controller;

abstract class ApiController extends Controller
{
    use ApiResponses, WrapperTransaction;

    protected $userModel;

    public function __construct()
    {
        $this->userModel = config('auth-api.models.user')?? User::class;
    }
}