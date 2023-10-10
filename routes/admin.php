<?php

use App\Http\Controllers\Web\AdmissionController;
use App\Http\Controllers\Web\Auth\PasswordController;
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



Route::middleware(['auth'])
    ->prefix('admin')
    ->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index']);

        Route::prefix('subject')
            ->group(function () {
                Route::get('add', [SubjectController::class, 'addView']);
                Route::post('add', [SubjectController::class, 'add']);
                Route::get('upload', [SubjectController::class, 'uploadView']);
                Route::post('upload', [SubjectController::class, 'upload']);
                Route::post('edit', [SubjectController::class, 'edit']);
                Route::get('list', [SubjectController::class, 'list']);
                Route::delete('delete', [SubjectController::class, 'delete']);
            });

        Route::prefix('session')
            ->group(function () {
                Route::get('set', [SessionController::class, 'setView']);
                Route::post('set', [SessionController::class, 'set']);
                Route::get('get/all', [SessionController::class, 'getAll']);
            });

        Route::prefix('faculty')
            ->group(function () {
                Route::get('add', [FacultyController::class, 'addView']);
                Route::post('add', [FacultyController::class, 'add']);
                Route::get('upload', [FacultyController::class, 'uploadView']);
                Route::post('upload', [FacultyController::class, 'upload']);
                Route::post('edit', [FacultyController::class, 'edit']);
                Route::get('list', [FacultyController::class, 'list']);
                Route::delete('delete', [FacultyController::class, 'delete']);
            });

        Route::prefix('programme')
            ->group(function () {
                Route::get('add', [CourseController::class, 'addView']);
                Route::post('add', [CourseController::class, 'add']);
                Route::get('upload', [CourseController::class, 'uploadView']);
                Route::post('upload', [CourseController::class, 'upload']);
                Route::post('edit', [CourseController::class, 'edit']);
                Route::get('list', [CourseController::class, 'list']);
                Route::delete('delete', [CourseController::class, 'delete']);
            });

        Route::prefix('catchment')
            ->group(function () {
                Route::get('add', [CatchmentController::class, 'addView']);
                Route::post('add', [CatchmentController::class, 'add']);
                Route::post('edit', [CatchmentController::class, 'edit']);
                Route::get('list', [CatchmentController::class, 'list']);
                Route::delete('delete', [CatchmentController::class, 'delete']);
            });

        Route::prefix('elds')
            ->group(function () {
                Route::get('add', [ELDSController::class, 'addView']);
                Route::post('add', [ELDSController::class, 'add']);
                Route::post('edit', [ELDSController::class, 'edit']);
                Route::get('list', [ELDSController::class, 'list']);
                Route::delete('delete', [ELDSController::class, 'delete']);
            });


        Route::prefix('candidate')
            ->group(function () {
                Route::get('upload', [CandidatesController::class, 'uploadView']);
                Route::post('upload', [CandidatesController::class, 'upload']);
                Route::get('delete', [CandidatesController::class, 'deleteView']);
                Route::delete('delete', [CandidatesController::class, 'delete']);
            });

        Route::prefix('admission')
            ->group(function () {
                Route::get('criteria/update', [AdmissionController::class, 'admissionCriteriaView']);
                Route::post('criteria/update', [AdmissionController::class, 'admissionCriteria']);
                Route::get('generate', [AdmissionController::class, 'generateAdmissionView']);
                Route::get('calculate', [AdmissionController::class, 'generateAdmission']);
                Route::get('statistics', [AdmissionController::class, 'admissionStat']);
                Route::get('discretion/upload', [AdmissionController::class, 'discretionUploadView']);
                Route::post('discretion/upload', [AdmissionController::class, 'discretionUpload']);
            });

        Route::get('password', [PasswordController::class, 'index']);
        Route::post('password', [PasswordController::class, 'updatePassword']);
    });
