<?php

namespace App\Http\Repositories;

use App\Models\Exam;
use App\Models\User;
use App\Models\ExamType;
use App\Models\Question;
use App\Models\StudentGroup;
use App\Models\systemAnswers;
use App\Http\Traits\ApiDesignTrait;
use Illuminate\Support\Facades\Validator;
use App\Http\Interfaces\StudentExamInterface;
use App\Models\studentExam;
use App\Models\studentExamAnswers;

class StudentExamRepository implements StudentExamInterface
{
    use ApiDesignTrait;

    private $examModel;
    private $userModel;
    private $examTypeModel;
    private $studentGroupModel;
    private $questionModel;
    private $answerModel;
    private $studentExamModel;
    private $studentExamAnswerModel;

    public function  __construct(Exam $examModel, User $userModel, ExamType $examTypeModel, studentExam $studentExamModel, studentExamAnswers $studentExamAnswerModel, StudentGroup $studentGroupModel, Question $questionModel, systemAnswers $answerModel)
    {
        $this->examModel = $examModel;
        $this->userModel = $userModel;
        $this->examTypeModel = $examTypeModel;
        $this->studentGroupModel = $studentGroupModel;
        $this->questionModel = $questionModel;
        $this->answerModel = $answerModel;
        $this->studentExamModel = $studentExamModel;
        $this->studentExamAnswerModel = $studentExamAnswerModel;
    }

    public function newExams()
    {
        $userId = auth()->user()->id;

        $exams = $this->examModel::where('close', 0)->whereHas('studentGroups', function ($query) use ($userId) {
            $query->where([['student_id', $userId], ['count', '>=', 1]]);
        })->get();

        // return $this->ApiResponse(200,'student exams','null',$exams);
        return $this->ApiResponse(200, 'student Exams', null, $exams);
    }
    public function oldExams()
    {
    }

    public function newStudentExam($request)
    {
        $validation = Validator::make($request->all(), [
            'exam_id' => 'required|exists:exams,id'
        ]);
        if ($validation->fails()) {
            return $this->ApiResponse(422, 'Validation error', $validation->errors());
        }
        $questionCount = $this->examModel::select('count')->find($request->exam_id);
        $questions  = $this->questionModel::where('exam_id', $request->exam_id)
            ->inRandomOrder()->limit($questionCount->count)->with('questionImage')->get();
        return $this->ApiResponse(200, 'exam questions', null, $questions);
    }

    public function storeStudentExam($request)
    {
        $validation = Validator::make($request->all(), [
            'exam_id' => 'required|exists:exams,id',
            'questions.*' => 'required',
        ]);

        if ($validation->fails()) {
            return $this->ApiResponse(422, 'Validation error', $validation->errors());
        }

        $examData = $this->examModel::whereHas('examType', function ($query) {
            $query->where('is_mark', 1);
        })->select('exam_type_id')->find($request->exam_id);

        $setStudentExam = $this->studentExamModel::create([
            'exam_id' => $request->exam_id,
            'student_id' => auth()->user()->id,
        ]);

        if ($examData) {

            $questiondegree =  $examData->degree / $examData->count;

            foreach ($request->questions as $question) {
                $getSystemAnswer = $this->answerModel::where('question_id', $question['question'])->first();
                if ($question['answer'] == $getSystemAnswer['answer']) {
                    $degree = $questiondegree;
                    $totaldegree = +$degree;
                } else {
                    $degree = 0;
                }
                $storeQyestionAnswer = $this->studentExamAnswerModel::create([
                    'student_exam_id' => $setStudentExam,
                    'question_id' => $question['question'],
                    'degree' => $degree,
                ]);
            }
            $setStudentExam->update([
                'total_degree' => $totaldegree,
            ]);
            return $this->ApiResponse(200, 'Done', null, $totaldegree);
        } else {
            foreach ($request->questions as $question) {
            }
        }
    }
}
