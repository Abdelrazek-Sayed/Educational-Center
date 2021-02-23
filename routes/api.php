<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\apiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StaffController;

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
Route::get('test/{name}',[apiController::class , 'testApi']);

// login
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('add/test/user', [AuthController::class, 'addTestUser']);;
});

Route::group(['prefix' => 'admin',],function(){
    // staff
    Route::post('staff/add',[StaffController::class,'addStaff']);
});

// Route::prefix('admin')->group(function(){


//     Route::post('staff/add',[StaffController::class,'addStaff']);
// });

