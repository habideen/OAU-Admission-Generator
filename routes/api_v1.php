<?php

use App\Http\Controllers\API\V1\Auth\LoginController;
use App\Http\Controllers\API\V1\Auth\RegisterController;
use App\Http\Controllers\API\V1\Auth\UserManagementController;
use App\Http\Controllers\API\V1\CourseController;
use App\Http\Controllers\API\V1\FacultyController;
use App\Http\Controllers\API\V1\SessionController;
use App\Http\Controllers\API\V1\SubjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('test', function () {
    return 123;
});

Route::post('login', [LoginController::class, 'login']);


Route::middleware(['api_v1', 'auth:sanctum'])
    ->prefix('superadmin')
    ->group(function () {
        Route::post('user/register', [RegisterController::class, 'register']);
        Route::post('user/disable', [UserManagementController::class, 'disableOrEnable']);
        Route::post('user/enable', [UserManagementController::class, 'disableOrEnable']);
        Route::delete('user/delete', [UserManagementController::class, 'delete']);
        Route::get('user/list', [UserManagementController::class, 'listUsers']);

        //course
        Route::prefix('subject')
            ->group(function () {
                Route::post('add', [SubjectController::class, 'add']);
                Route::post('edit', [SubjectController::class, 'edit']);
                Route::get('list', [SubjectController::class, 'list']);
                Route::delete('delete', [SubjectController::class, 'delete']);
            });

        //course
        Route::prefix('session')
            ->group(function () {
                Route::post('set', [SessionController::class, 'set']);
                Route::get('get/all', [SessionController::class, 'getAll']);
                Route::get('get/active', [SessionController::class, 'getActive']);
            });

        //course
        Route::prefix('faculty')
            ->group(function () {
                Route::post('add', [FacultyController::class, 'add']);
                Route::post('edit', [FacultyController::class, 'edit']);
                Route::get('list', [FacultyController::class, 'list']);
                Route::delete('delete', [FacultyController::class, 'delete']);
            });

        //course
        Route::prefix('course')
            ->group(function () {
                Route::post('add', [CourseController::class, 'add']);
                Route::post('edit', [CourseController::class, 'edit']);
                Route::get('list', [CourseController::class, 'list']);
            });
    });


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
