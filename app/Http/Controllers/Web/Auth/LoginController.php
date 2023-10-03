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

        Session::flash('status', 'failed');
        Session::flash('message', 'Username or password is incorrect');
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        Session::remove('status');
        Session::remove('message');

        if (!Auth::attempt($request->except(['_token', 'remember']), $request->input('remember')))
            return redirect()->back()->with('fail', auth_messages('login_error'));


        if (!Auth::user()->email_verified_at) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            $request->session()->flush();
            return redirect()->back()->with([
                'status' => 'failed',
                'message' => auth_messages('verify_email')
                    . ' <a href="/verify_email">Click here to verify now.</a>'
            ]);
        } elseif (Auth::user()->disabled) {
            // check if account is disabled
            Auth::logoutOtherDevices($request->password);

            $request->session()->invalidate();
            $request->session()->regenerateToken();
            $request->session()->flush();

            return redirect()->back()->with([
                'status' => 'failed',
                'message' => 'This account is disabled. Please contact support.'
            ]);
        } elseif (Auth::user()->force_logout) {
            Auth::logoutOtherDevices($request->password);
            User::where('email', $request->email)->update(['force_logout' => null]);
        }


        $request->session()->regenerate();

        return redirect()->intended(account_type() . '/dashboard');
    }
}
