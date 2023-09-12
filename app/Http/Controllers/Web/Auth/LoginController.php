<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LoginController extends Controller
{
    public function login(Request $request) {
        if (strpos($request->header('referer'), '/api/')) {
            return response([
                'status' => 'failed',
                'message' => 'Please login'
            ], Response::HTTP_UNAUTHORIZED);
        }
    } //login
}
