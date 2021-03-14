<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Interfaces\teacherInterface;

class teacherController extends Controller
{

    private $teacherInterface;

    function __construct(teacherInterface $teacherInterface)
    {

        $this->teacherInterface = $teacherInterface;
    }



    public function addTeacher(Request $request)
    {

        return $this->teacherInterface->addTeacher($request);
    }

    public function allTeacher()
    {

        return $this->teacherInterface->allTeacher();
    }

    public function updateTeacher(Request $request)
    {

        return $this->teacherInterface->updateTeacher($request);
    }

    public function deleteTeacher(Request $request)
    {

        return $this->teacherInterface->deleteTeacher($request);
    }

    public function specificTeacher(Request $request)
    {

        return $this->teacherInterface->specificTeacher($request);
    }
}
