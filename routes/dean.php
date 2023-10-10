<?php

use App\Http\Controllers\Web\AdmissionController;
use App\Http\Controllers\Web\Auth\LoginController;
use App\Http\Controllers\Web\Auth\LogoutController;
use App\Http\Controllers\Web\Auth\PasswordController;
use App\Http\Controllers\Web\CandidatesController;
use App\Http\Controllers\Web\DashboardController;
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

Route::middleware(['auth', 'isSetToLogout'])
    ->prefix('dean')
    ->group(function () {
        Route::get('dashboard', function () {
            return 'Dean';
        });
    });


Route::get('/logout', [LogoutController::class, 'logout'])->middleware(['auth']);


Route::middleware(['auth'])
    ->prefix('dean')
    ->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index']);

        Route::get('candidates', [CandidatesController::class, 'uploadView']);

        Route::prefix('admission')
            ->group(function () {
                Route::get('view', [AdmissionController::class, 'generateAdmissionView']);
                Route::get('statistics', [AdmissionController::class, 'admissionStat']);
            });

        Route::get('password', [PasswordController::class, 'index']);
        Route::post('password', [PasswordController::class, 'updatePassword']);
    });
