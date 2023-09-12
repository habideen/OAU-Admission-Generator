<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($validation->fails()) {
            return response([
                'status' => 'failed',
                'errors' => $validation->errors(),
            ], Response::HTTP_EXPECTATION_FAILED);
        }


        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response([
                'status' => 'failed',
                'message' => 'Username or password is incorrect'
            ], Response::HTTP_UNAUTHORIZED);
        }


        if (Auth::user()->account_disabled) {
            return response([
                'status' => 'failed',
                'message' => 'Account is disabled'
            ], Response::HTTP_UNAUTHORIZED);
        } elseif (Auth::user()->email_verified_at) {
            return response([
                'status' => 'failed',
                'message' => 'Please verify your email'
            ], Response::HTTP_UNAUTHORIZED);
        }


        return response([
            'status' => 'success',
            'message' => 'Login was successful.',
            'token' => $request->user()->createToken('login_token')->plainTextToken,
            'user' => User::where('id', Auth::user()->id)->first()
        ]);
    } //login
}
