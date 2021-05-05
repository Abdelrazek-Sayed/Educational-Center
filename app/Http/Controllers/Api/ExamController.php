<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\ExamInterface;
use Illuminate\Http\Request;

class ExamController extends Controller
{

    private $examinterface;

    public function __construct(ExamInterface $examInterface)
    {
        $this->examinterface = $examInterface;
    }

    public function examTypes()
    {
        return $this->examinterface->examTypes();
    }

    public function createExam(Request $request)
    {
        return $this->examinterface->createExam($request);
    }
    public function allExams()
    {
        return $this->examinterface->allExams();
    }
    public function deleteExam(Request $request)
    {
        return $this->examinterface->deleteExam($request);
    }
    public function updateExam(Request $request)
    {
        return $this->examinterface->updateExam($request);
    }
    public function updateExamStatus(Request $request)
    {
        return $this->examinterface->updateExamStatus($request);
    }

    /**
     * q part
     */

    public function addQuestion(Request $request)
    {
        return $this->examinterface->addQuestion($request);
    }
    public function allQuestions()
    {
        return $this->examinterface->allQuestions();
    }
    public function updateQuestion(Request  $request)
    {
        return $this->examinterface->updateQuestion($request);
    }
    public function deleteQuestion(Request $request)
    {
        return $this->examinterface->deleteQuestion($request);
    }
}
