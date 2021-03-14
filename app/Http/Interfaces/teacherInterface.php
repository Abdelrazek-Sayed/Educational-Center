<?php

namespace App\Http\Interfaces;

use Illuminate\Support\Facades\Request;

interface teacherInterface {


    public function addTeacher($request);

    public function allTeacher();

    public function updateTeacher($request);

    public function deleteTeacher($request);

    public function specificTeacher($request);




}
