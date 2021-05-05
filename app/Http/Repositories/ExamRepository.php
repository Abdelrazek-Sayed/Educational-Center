<?php

namespace App\Http\Repositories;

use App\Models\User;
use App\Http\Traits\ApiDesignTrait;
use App\Http\Interfaces\ExamInterface;
use App\Models\Exam;
use App\Models\ExamType;
use App\Models\Question;
use App\Models\QuestionImage;
use App\Models\StudentGroup;
use App\Models\systemAnswers;
use Illuminate\Support\Facades\Validator;

class ExamRepository implements ExamInterface
{
    use ApiDesignTrait;

    private $examModel;
    private $userModel;
    private $examTypeModel;
    private $studentGroupModel;
    private $questionModel;
    private $answerModel;

    public function  __construct(Exam $examModel, User $userModel, ExamType $examTypeModel, StudentGroup $studentGroupModel, Question $questionModel, systemAnswers $answerModel)
    {
        $this->examModel = $examModel;
        $this->userModel = $userModel;
        $this->examTypeModel = $examTypeModel;
        $this->studentGroupModel = $studentGroupModel;
        $this->questionModel = $questionModel;
        $this->answerModel = $answerModel;
    }


    public function examTypes()
    {
        $exams  = $this->examTypeModel::get();
        return $this->ApiResponse(200, 'all types', null, $exams);
    }

    public function createExam($request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'start' => 'required',
            'end' => 'required',
            'time' => 'required',
            'degree' => 'required',
            'count' => 'required',
            'exam_type_id' => 'required|exists:exam_types,id',
            'group_id' => 'required|exists:groups,id',
            // 'teacher_id' => 'required|exists:users,id',
        ]);
        if ($validation->fails()) {
            return $this->ApiResponse(400, 'error validation', $validation->errors());
        }

        $exam =  $this->examModel::create([
            'name' => $request->name,
            'start' => $request->start,
            'end' => $request->end,
            'time' => $request->time,
            'degree' => $request->degree,
            'count' => $request->count,
            'exam_type_id' => $request->exam_type_id,
            'group_id' => $request->group_id,
            'teacher_id' => auth()->user()->id,
        ]);

        return $this->ApiResponse(200, 'Exam created', null, $exam);
    }
    public function allExams()
    {
        $userRole = auth()->user()->roleName->name;
        $userId = auth()->user()->id;

        if ($userRole == 'Teacher') {
            $exams  = $this->examModel::where('teacher_id', $userId)->get();
        } elseif ($userRole == 'Student') {
            $groups = $this->studentGroupModel::where([['student_id', $userId], ['count', '>', 0]])->get();
            $groupIds =  [];

            foreach ($groups as $group) {
                $groupIds[] = $group->group_id;
            }
            $exams  = $this->examModel::whereIn('group_id', $groupIds)->get();
        }
        return $this->ApiResponse(200, 'all Exams', null, $exams);
    }
    public function deleteExam($request)
    {
        $validation = Validator::make($request->all(), [
            'exam_id' => 'required|exists:exams,id',
        ]);
        if ($validation->fails()) {
            return $this->ApiResponse(422, 'validation error', $validation->errors());
        }
        $exam =  $this->examModel::where('id', $request->exam_id)->first();

        $exam->delete();
        return $this->ApiResponse(200, 'exam deleted');
    }
    public function updateExam($request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'start' => 'required',
            'end' => 'required',
            'time' => 'required',
            'degree' => 'required',
            'count' => 'required',
            'group_id' => 'required|exists:groups,id',
            'exam_id' => 'required|exists:exams,id',
        ]);
        if ($validation->fails()) {
            return $this->ApiResponse(422, 'error validation', $validation->errors());
        }

        $exam = $this->examModel::find($request->exam_id);
        $exam->update([
            'name' => $request->name,
            'start' => $request->start,
            'end' => $request->end,
            'time' => $request->time,
            'degree' => $request->degree,
            'count' => $request->count,
            'group_id' => $request->group_id,
        ]);

        return $this->ApiResponse(200, 'exam updated');
    }
    public function updateExamStatus($request)
    {
        $validation = Validator::make($request->all(), [
            'exam_id' => 'required|exists:exams,id',
            // 'status' => 'required',
        ]);
        if ($validation->fails()) {
            return $this->ApiResponse(422, 'error validation', $validation->errors());
        }

        $exam = $this->examModel::find($request->exam_id);
        $exam->update([
            'status' => !$exam->status,
        ]);

        return $this->ApiResponse(200, 'exam status updated ', null, $exam->status);
    }

    /**
     * 
     * questions part 
     */

    public function addQuestion($request)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required|min:5',
            'exam_id' => 'required|exists:exams,id',
        ]);
        if ($validation->fails()) {
            return $this->ApiResponse(422, 'error validation', $validation->errors());
        }

        $question =  $this->questionModel::create([
            'title' => $request->title,
            'exam_id' => $request->exam_id,
        ]);

        $exam = $this->examModel::find($request->exam_id);
        $exam_type = $exam->exam_type_id;

        // $exam_type = $this->examModel::whereHas('examType', function ($query) use($exam_type) {
        //     $query->where('id', $exam_type);
        // })->first();

        // dd($exam_type);

        if (!$request->has('answer')) {
            if ($exam_type == 1 or $exam_type == 2) {
                return $this->ApiResponse(400, 'you must enter answers');
            }
        }

        if ($request->has('answer')) {
            $this->answerModel::create([
                'question_id' => $question->id,
                'answers' => $request->answer,
            ]);
        }

        if ($request->has('image')) {
            QuestionImage::create([
                'question_id' => $question->id,
                'type' => $exam->exam_type_id,
                'image' => 'test.png',
            ]);
        }
        return $this->ApiResponse(200, 'created');
    }
    public function allQuestions()
    {
    }
    public function updateQuestion($request)
    {
    }
    public function deleteQuestion($request)
    {
    }
}
