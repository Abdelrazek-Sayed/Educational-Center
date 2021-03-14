<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\groupInterface;

class groupController extends Controller
{

    private $groupInterface;

    function __construct(groupInterface $groupInterface)
    {

        $this->groupInterface = $groupInterface;
    }



    public function addGroup(Request $request)
    {

        return $this->groupInterface->addGroup($request);
    }

    public function allGroup()
    {

        return $this->groupInterface->allGroup();
    }

    public function updateGroup(Request $request)
    {

        return $this->groupInterface->updateGroup($request);
    }

    public function deleteGroup(Request $request)
    {

        return $this->groupInterface->deleteGroup($request);
    }

    public function specificGroup(Request $request)
    {

        return $this->groupInterface->specificGroup($request);
    }
}
