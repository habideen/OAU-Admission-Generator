<?php

use App\Http\Controllers\Web\AdmissionController;
use App\Http\Controllers\Web\Auth\LoginController;
use App\Http\Controllers\Web\Auth\LogoutController;
use App\Http\Controllers\Web\Auth\PasswordController;
use App\Http\Controllers\Web\CandidatesController;
use App\Http\Controllers\Web\CatchmentController;
use App\Http\Controllers\Web\CourseController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ELDSController;
use App\Http\Controllers\Web\FacultyController;
use App\Http\Controllers\Web\SessionController;
use App\Http\Controllers\Web\SubjectController;
use App\Http\Controllers\Web\UserManagementController;
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


Route::get('/logout', [LogoutController::class, 'logout'])->middleware(['auth']);
