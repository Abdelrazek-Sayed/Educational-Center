<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\apiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExamController;
use App\Http\Controllers\Api\groupController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\Api\EndUserController;
use App\Http\Controllers\Api\SessionController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\teacherController;
use App\Http\Controllers\Api\StudentExamController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

// test
Route::get('test/{name}', [apiController::class, 'testApi']);

// login
// Route::group(['middleware' => ['api'],'prefix' => 'auth',], function ($router) {
Route::group(['middleware' => ['api'], 'prefix' => 'auth',], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
});

Route::group(['prefix' => 'admin', 'middleware' => ['jwt.Token', 'roles:Admin']], function () {
    // staff
    Route::post('staff/add', [StaffController::class, 'addStaff']);
    Route::get('staff/all', [StaffController::class, 'allStaff']);
    Route::post('staff/update', [StaffController::class, 'updateStaff']);
    Route::post('staff/delete', [StaffController::class, 'deleteStaff']);
    Route::get('staff/specific', [StaffController::class, 'specificStaff']);
});

Route::group(['prefix' => 'dashboard', 'middleware' => ['jwt.Token', 'roles:Admin.Support.Secretary']], function () {
    // teachers
    Route::prefix('teacher')->group(function () {

        Route::post('/add', [teacherController::class, 'addTeacher']);
        Route::get('/all', [teacherController::class, 'allTeacher']);
        Route::post('/update', [teacherController::class, 'updateTeacher']);
        Route::post('/delete', [teacherController::class, 'deleteTeacher']);
        Route::get('/specific', [teacherController::class, 'specificTeacher']);
    });

    // groups
    Route::prefix('group')->group(function () {
        Route::post('/add', [groupController::class, 'addGroup']);
        Route::get('/all', [groupController::class, 'allGroup']);
        Route::post('/update', [groupController::class, 'updateGroup']);
        Route::post('/delete', [groupController::class, 'deleteGroup']);
        Route::get('/specific', [groupController::class, 'specificGroup']);
    });

    // students
    Route::prefix('student')->group(function () {
        Route::post('/add', [StudentController::class, 'addStudent']);
        Route::get('/all', [StudentController::class, 'allStudents']);
        Route::post('/update', [StudentController::class, 'updateStudent'])->middleware('jwt.Token');
        Route::post('/delete', [StudentController::class, 'deleteStudent']);
        Route::get('student/specific', [StudentController::class, 'specificStudent']);
    });
    Route::post('studentGroup/update', [StudentController::class, 'updateStudentGroup']);
    Route::post('studentGroup/delete', [StudentController::class, 'deleteStudentGroup']);

    // sessions
    Route::prefix('session')->group(function () {
        Route::get('/all', [SessionController::class, 'allSessions']);
        Route::post('/add', [SessionController::class, 'addSession']);
        Route::post('/delete', [SessionController::class, 'deleteSession']);
    });

    //complaints
    Route::prefix('complaint')->group(function () {
        Route::get('/all', [ComplaintController::class, 'allComplaints']);
        Route::post('/get', [ComplaintController::class, 'getComplaint']);
        Route::post('/delete', [ComplaintController::class, 'deleteComplaint']);
    });
});

Route::prefix('enduser')->middleware(['jwt.Token', 'roles:Teacher.Student'])->group(function () {
    Route::get('groups', [EndUserController::class, 'userGroups']);

    Route::prefix('exams')->group(function () {
        Route::get('types', [ExamController::class, 'examTypes']);
        Route::get('all', [ExamController::class, 'allExams']);
        Route::post('add', [ExamController::class, 'createExam'])->middleware('roles:Teacher');
        Route::post('update', [ExamController::class, 'updateExam'])->middleware('roles:Teacher');
        Route::post('delete', [ExamController::class, 'deleteExam'])->middleware('roles:Teacher');
        Route::post('status/update', [ExamController::class, 'updateExamStatus'])->middleware('roles:Teacher');

        // questions 
        Route::post('questions/add', [ExamController::class, 'addQuestion'])->middleware('roles:Teacher');
        Route::post('new', [StudentExamController::class, 'newExams'])->middleware('roles:Student');
        Route::post('student/new', [StudentExamController::class, 'newStudentExam'])->middleware('roles:Student');
        Route::post('student/store', [StudentExamController::class, 'storeStudentExam'])->middleware('roles:Student');
    });
});

 

// Route::prefix('admin')->group(function(){

//     Route::post('staff/add',[StaffController::class,'addStaff']);
// });
