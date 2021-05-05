<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\EndUserInterface;

class EndUserController extends Controller
{

    private $enduserinterface;
    public function __construct(EndUserInterface $endUserInterface)
    {
        $this->enduserinterface = $endUserInterface;
    }

    public function userGroups()
    {
        return $this->enduserinterface->userGroups();
    }
}
