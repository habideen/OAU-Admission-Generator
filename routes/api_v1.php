<?php

use App\Http\Controllers\API\V1\Auth\LoginController;
use App\Http\Controllers\API\V1\Auth\RegisterController;
use App\Http\Controllers\API\V1\Auth\UserManagementController;
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
        Route::post('register', [RegisterController::class, 'register']);
        Route::post('disable_user', [UserManagementController::class, 'disable']);
        Route::post('register', [RegisterController::class, 'register']);
    });


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
