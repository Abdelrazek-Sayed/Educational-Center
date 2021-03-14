<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\StudentInterface;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    private $StudentInterface;

    public function __construct(StudentInterface $StudentInterface)
    {

        $this->StudentInterface = $StudentInterface;
    }


    public function addStudent(Request $request)
    {
        return $this->StudentInterface->addStudent($request);
    }

    public function allStudents()
    {

        return $this->StudentInterface->allStudents();
    }

    public function updateStudent(Request $request)
    {
        return $this->StudentInterface->updateStudent($request);
    }

    public function deleteStudent(Request $request)
    {
        return $this->StudentInterface->deleteStudent($request);
    }

    public function specificStudent(Request $request)
    {
        return $this->StudentInterface->specificStudent($request);
    }


    public function updateStudentGroup(Request $request)
    {

        return $this->StudentInterface->updateStudentGroup($request);
    }



    public function deleteStudentGroup(Request $request)
    {

        return $this->StudentInterface->deleteStudentGroup($request);
    }
}
