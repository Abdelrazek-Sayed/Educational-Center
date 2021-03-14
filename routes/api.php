<?php

use App\Http\Controllers\Api\apiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\groupController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\teacherController;
use Illuminate\Support\Facades\Route;

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
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth',

], function ($router) {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('add/test/user', [AuthController::class, 'addTestUser']);
});

Route::group(['prefix' => 'admin', 'middleware'=>['jwt.Token', 'roles:Admin']], function () {
    // staff
    Route::post('staff/add', [StaffController::class, 'addStaff']);
    Route::get('staff/all', [StaffController::class, 'allStaff']);
    Route::post('staff/update', [StaffController::class, 'updateStaff']);
    Route::post('staff/delete', [StaffController::class, 'deleteStaff']);
    Route::get('staff/specific', [StaffController::class, 'specificStaff']);
});

 
Route::group(['prefix' => 'dashboard', 'middleware'=>['jwt.Token', 'roles:Admin.Support.Secretary']], function () {
    // teachers
    Route::post('teacher/add', [teacherController::class, 'addTeacher']);
    Route::get('teacher/all', [teacherController::class, 'allTeacher']);
    Route::post('teacher/update', [teacherController::class, 'updateTeacher']);
    Route::post('teacher/delete', [teacherController::class, 'deleteTeacher']);
    Route::get('teacher/specific', [teacherController::class, 'specificTeacher']);

    // groups
    Route::post('group/add', [groupController::class, 'addGroup']);
    Route::get('group/all', [groupController::class, 'allGroup']);
    Route::post('group/update', [groupController::class, 'updateGroup']);
    Route::post('group/delete', [groupController::class, 'deleteGroup']);
    Route::get('group/specific', [groupController::class, 'specificGroup']);

    // students
    Route::post('student/add', [StudentController::class, 'addStudent']);
    Route::get('student/all', [StudentController::class, 'allStudents']);
    Route::post('student/update', [StudentController::class, 'updateStudent'])->middleware('jwt.Token');
    Route::post('student/delete', [StudentController::class, 'deleteStudent']);
    Route::get('student/specific', [StudentController::class, 'specificStudent']);
    Route::post('studentGroup/update', [StudentController::class, 'updateStudentGroup']);
    Route::post('studentGroup/delete', [StudentController::class, 'deleteStudentGroup']);
});

// Route::prefix('admin')->group(function(){

//     Route::post('staff/add',[StaffController::class,'addStaff']);
// });
