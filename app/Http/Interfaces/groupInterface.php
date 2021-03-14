<?php

namespace App\Http\Interfaces;


interface groupInterface {


    public function addGroup($request);

    public function allGroup();

    public function updateGroup($request);

    public function deleteGroup($request);

    public function specificGroup($request);




}
