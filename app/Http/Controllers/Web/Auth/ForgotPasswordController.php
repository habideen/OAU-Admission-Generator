<?php

namespace App\Http\Controllers\Web\Auth;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Jobs\Email\ForgotPassword;
use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ForgotPasswordController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()) {
            return redirect(account_type() . '/dashboard');
        }


        return view('auth.forgot_password');
    }




    public function email(Request $request)
    {
        Session::flash('success', 'We will send you an reset password link if your email exist on our server!');


        $request->validate([
            'email' => ['required', 'email', 'exists:users'],
        ]);


        $token = Str::random(64);


        PasswordReset::insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now(),
            'expired_at' => now()->addDays(1)
        ]);


        $url_data = url('/reset_password/' . base64_encode($request->email) . '/' . $token);

        $arg = [
            'url_data' => $url_data,
            'email' => $request->email
        ];

        $emailJobs =  new ForgotPassword($arg);
        dispatch($emailJobs);

        return back()->with('success', 'We will send you an reset password link if your email exist on our server!');
    }
}
