<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\StudentExamInterface;

class StudentExamController extends Controller
{
    private $StudentExamInterface;

    public function __construct(StudentExamInterface $StudentExamInterface)
    {
        $this->StudentExamInterface = $StudentExamInterface;
    }


    public function newExams()
    {
        return $this->StudentExamInterface->newExams();
    }
    public function oldExams()
    {
        return $this->StudentExamInterface->oldExams();
    }
    public function newStudentExam(Request $request)
    {
        return $this->StudentExamInterface->newStudentExam($request);
    }
    public function storeStudentExam(Request $request)
    {
        return $this->StudentExamInterface->storeStudentExam($request);
    }
}
