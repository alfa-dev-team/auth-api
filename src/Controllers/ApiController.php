<?php

namespace AlfaDevTeam\AuthApi\Controllers;

use AlfaDevTeam\RestApiResponses\Controllers\ApiResponses;
use AlfaDevTeam\RestApiResponses\Controllers\WrapperTransaction;
use Illuminate\Routing\Controller;

abstract class ApiController extends Controller
{
    use ApiResponses, WrapperTransaction;

    protected $userModel;
}