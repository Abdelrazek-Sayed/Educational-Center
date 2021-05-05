<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\SessionInterface;
use Illuminate\Http\Request;

class SessionController extends Controller
{

    private $SessionInterface;

    public function __construct(SessionInterface $SessionInterface)
    {

        $this->SessionInterface = $SessionInterface;
    }

    public function allSessions()
    {

        return $this->SessionInterface->allSessions();
    }

    public function addSession(Request $request)
    {

        return $this->SessionInterface->addSession($request);
    }

    public function deleteSession(Request $request)
    {

        return $this->SessionInterface->deleteSession($request);
    }

}
