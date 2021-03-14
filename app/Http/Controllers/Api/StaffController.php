<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\StaffInterface;

class StaffController extends Controller
{
    private $staffInterface;

    public function __construct(StaffInterface $staffInterface)
    {

        $this->staffInterface = $staffInterface;
    }

    public function addStaff(Request $request)
    {

        return $this->staffInterface->addStaff($request);
    }

    public function allStaff()
    {
        return $this->staffInterface->allStaff();
    }

    public function updateStaff(Request $request)
    {
        return $this->staffInterface->updateStaff($request);
    }

    public function deleteStaff(Request $request)
    {
        return $this->staffInterface->deleteStaff($request);
    }

    public function specificStaff(Request $request)
    {

        return $this->staffInterface->specificStaff($request);
    }
}
