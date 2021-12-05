<?php

namespace AlfaDevTeam\AuthApi\Controllers;

use AlfaDevTeam\RestApiResponses\Controllers\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

abstract class ApiController extends Controller
{
    use ApiResponses;

    protected $userModel;
}