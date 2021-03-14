<?php

namespace App\Http\Interfaces;

use Illuminate\Support\Facades\Request;

interface StaffInterface {


    public function addStaff($request);

    public function allStaff();

    public function updateStaff($request);

    public function deleteStaff($request);

    public function specificStaff($request);




}
