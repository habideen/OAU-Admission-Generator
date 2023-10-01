<?php

use App\Http\Controllers\Web\AdmissionController;
use App\Http\Controllers\Web\Auth\LoginController;
use App\Http\Controllers\Web\CandidatesController;
use App\Http\Controllers\Web\CatchmentController;
use App\Http\Controllers\Web\CourseController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ELDSController;
use App\Http\Controllers\Web\FacultyController;
use App\Http\Controllers\Web\SessionController;
use App\Http\Controllers\Web\SubjectController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/', [LoginController::class, 'index']);
Route::get('/login', [LoginController::class, 'index']);

Route::middleware(['throttle:custom_auth'])
    ->group(function () {
        Route::post('/login', [LoginController::class, 'login'])->name('login');
    });



Route::middleware(['auth'])
    ->prefix('superadmin')
    ->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index']);
        //user
        Route::prefix('user')
            ->group(function () {
                Route::post('register', [RegisterController::class, 'register']);
                Route::post('disable', [UserManagementController::class, 'disableOrEnable']);
                Route::post('enable', [UserManagementController::class, 'disableOrEnable']);
                Route::delete('delete', [UserManagementController::class, 'delete']);
                Route::get('list', [UserManagementController::class, 'listUsers']);
            });

        //course ++++++++++++++++++++++++++++++++++++
        Route::prefix('subject')
            ->group(function () {
                Route::get('add', [SubjectController::class, 'addView']);
                Route::post('add', [SubjectController::class, 'add']);
                Route::post('edit', [SubjectController::class, 'edit']);
                Route::get('list', [SubjectController::class, 'list']);
                Route::delete('delete', [SubjectController::class, 'delete']);
            });

        //course ++++++++++++++++++++++++++++++++++++
        Route::prefix('session')
            ->group(function () {
                Route::get('set', [SessionController::class, 'setView']);
                Route::post('set', [SessionController::class, 'set']);
                Route::get('get/all', [SessionController::class, 'getAll']);
            });

        //course ++++++++++++++++++++++++++++++++++++
        Route::prefix('faculty')
            ->group(function () {
                Route::get('add', [FacultyController::class, 'addView']);
                Route::post('add', [FacultyController::class, 'add']);
                Route::post('edit', [FacultyController::class, 'edit']);
                Route::get('list', [FacultyController::class, 'list']);
                Route::delete('delete', [FacultyController::class, 'delete']);
            });

        //course ++++++++++++++++++++++++++++++++++++
        Route::prefix('course')
            ->group(function () {
                Route::get('add', [CourseController::class, 'addView']);
                Route::post('add', [CourseController::class, 'add']);
                Route::post('edit', [CourseController::class, 'edit']);
                Route::get('list', [CourseController::class, 'list']);
                Route::delete('delete', [CourseController::class, 'delete']);
            });

        //course ++++++++++++++++++++++++++++++++++++
        Route::prefix('catchment')
            ->group(function () {
                Route::get('add', [CatchmentController::class, 'addView']);
                Route::post('add', [CatchmentController::class, 'add']);
                Route::post('edit', [CatchmentController::class, 'edit']);
                Route::get('list', [CatchmentController::class, 'list']);
                Route::delete('delete', [CatchmentController::class, 'delete']);
            });

        //course ++++++++++++++++++++++++++++++++++++
        Route::prefix('elds')
            ->group(function () {
                Route::get('add', [ELDSController::class, 'addView']);
                Route::post('add', [ELDSController::class, 'add']);
                Route::post('edit', [ELDSController::class, 'edit']);
                Route::get('list', [ELDSController::class, 'list']);
                Route::delete('delete', [ELDSController::class, 'delete']);
            });


        //course ++++++++++++++++++++++++++++++++++++
        Route::prefix('candidate')
            ->group(function () {
                Route::get('upload', [CandidatesController::class, 'uploadView']);
                Route::post('upload', [CandidatesController::class, 'upload']);
                Route::get('delete', [CandidatesController::class, 'deleteView']);
                Route::delete('delete', [CandidatesController::class, 'delete']);
            });

        //admission
        Route::prefix('admission')
            ->group(function () {
                Route::get('criteria/update', [AdmissionController::class, 'admissionCriteriaView']);
                Route::post('criteria/update', [AdmissionController::class, 'admissionCriteria']);
                Route::get('criteria/get', [AdmissionController::class, 'getadmissionCriteria']);
                Route::get('generate', [AdmissionController::class, 'generateAdmission']);
                Route::get('download', [AdmissionController::class, 'downloadAdmission']);
                Route::get('add_by_discretion', [AdmissionController::class, 'addByAdmission']);
            });
    });
