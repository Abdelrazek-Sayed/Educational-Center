<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Interfaces\AuthInterface;


class AuthController extends Controller
{


    private $authInterface;  // property

    public function __construct(AuthInterface $authInterface) // object
    {

        $this->authInterface = $authInterface;
    }


    public function login()
    {
        $this->authInterface->login();
    }


}
