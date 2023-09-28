<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()) {
            return redirect(account_type() . '/dashboard');
        }

        return view('auth.login');
    }


    public function login(Request $request)
    {
        if (strpos($request->header('referer'), '/api/')) {
            return response([
                'status' => 'failed',
                'message' => 'Please login'
            ], Response::HTTP_UNAUTHORIZED);
        }
        
        Session::flash('fail', 'Username or password is incorrect');
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        Session::remove('flash');

        //limit failed attempt /min to 5
        $executed = RateLimiter::attempt('web_auth:' . $request->ip(), $perMinute = 5, function () {
        });

        if (!$executed)
            return redirect()->back()->with('fail', auth_messages('throttle_message'));

        if (!Auth::attempt($request->except(['_token', 'remember']), $request->input('remember')))
            return redirect()->back()->with('fail', auth_messages('login_error'));


        if (!Auth::user()->email_verified_at) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            $request->session()->flush();
            return redirect()->back()->with('fail', auth_messages('verify_email')
                . ' <a href="/verify_email">Click here to verify now.</a>');
        } elseif (Auth::user()->disabled) {
            // check if account is disabled
            Auth::logoutOtherDevices($request->password);

            $request->session()->invalidate();
            $request->session()->regenerateToken();
            $request->session()->flush();

            return redirect()->back()->with('fail', 'This account is disabled. Please contact support.');
        }


        $request->session()->regenerate();

        RateLimiter::clear('web_auth:' . $request->ip());

        return redirect()->intended(account_type() . '/dashboard');
    }
}
