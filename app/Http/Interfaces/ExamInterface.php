<?php

namespace App\Http\Interfaces;


interface ExamInterface
{

    
    public function allExams();
    public function examTypes();
    public function createExam($request);
    public function updateExam($request);
    public function deleteExam($request);
    public function updateExamStatus($request);


    // questions 

    public function addQuestion($request);
    public function allQuestions();
    public function updateQuestion($request);
    public function deleteQuestion($request);
}
