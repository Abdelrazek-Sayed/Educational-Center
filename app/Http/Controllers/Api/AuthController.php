<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Interfaces\AuthInterface;
use Illuminate\Support\Facades\Request;

class AuthController extends Controller
{


    private $authInterface;  // property

    public function __construct(AuthInterface $authInterface) // object
    {

        $this->authInterface = $authInterface;
    }


    public function login()
    {
        return  $this->authInterface->login();
    }


    public function updatePassword(Request $request)
    {

        return $this->authInterface->updatePassword($request);
    }
}
