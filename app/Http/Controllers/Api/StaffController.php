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

        $this->staffInterface->addStaff($request);
    }
}
