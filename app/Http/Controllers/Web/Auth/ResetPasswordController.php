<?php

namespace App\Http\Controllers\Web\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Jobs\Email\PasswordResetSuccessful;

class ResetPasswordController extends Controller
{
    public function index(Request $request, $email_base64, $token)
    {
        $is_present = DB::table('password_resets')
                        ->whereDate('expired_at', '>=', now())
                        ->where('email', base64_decode($email_base64))
                        ->where('token', $token)->count();

        if ($is_present < 1)
            return redirect('/login') -> with('fail', 'The reset password link has expired or does not exist! Please generate a new link.');

        return view('auth.reset_password') 
        ->with([
                'email_base64' => $email_base64,
                'token' => $token
        ]);
    }





    public function change_password(Request $request, $email_base64, $token)
    {
        $is_present = DB::table('password_resets')
                        ->whereDate('expired_at', '>=', now())
                        ->where('email', base64_decode($email_base64))
                        ->where('token', $token)->count();
                        
        if ($is_present < 1)
            return redirect('/login') -> with('fail', 'The reset password link has expired or does not exist! Please generate a new link.');

        Session::flash('fail', 'Password data is incorrect');
        
        $request->validate([
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password'
        ]);

        Session::remove('fail');

        
        $user = [];
        $user['password'] = Hash::make($request->new_password);

        $is_update = User::where('email', base64_decode($email_base64)) -> update( $user );

        if ($is_update)
        {
            DB::table('password_resets')->where('email', base64_decode($email_base64)) -> update([ 'token' => '' ]);

            $url_data = url('/login');
            
            $arg = [
                'url_data' => $url_data,
                'email' => base64_decode($email_base64)
            ];
    
            $emailJobs =  new PasswordResetSuccessful($arg);
            dispatch($emailJobs);

            return redirect('/login')->with('success', 'Password Updated successfully');
        }

        else
            return redirect('/login')->with('fail', 'Something went wrong');
    }



}
