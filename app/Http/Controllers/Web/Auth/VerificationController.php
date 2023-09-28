<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class VerificationController extends Controller
{
    public function viewVerifyEmail()
    {
        return view('auth.verify_email');
    } //viewVerifyEmail





    public function sendEmailVerification(Request $request)
    {
        $account = User::select('email')
            ->where('email', $request->email)->first();

        if (!$account) {
            return redirect()->back()->with([
                'fail' => 'We will send you a link if email is found.'
            ]);
        }


        verifyEmail(strtolower($request->email));


        return redirect()->back()->with([
            'success' => 'We will send you a link if email is found.'
        ]);
    } //sendEmailVerification





    public function verifyEmail(Request $request, $verification_id, $email_code)
    {
        //verify from email
        $verification = EmailVerification::where('id', $verification_id)->first();
        if (!$verification) {
            return redirect('/verify_email')->with([
                'fail' => 'Invalid verification link'
            ]);
        }


        if (!Hash::check($email_code, $verification->code)) {
            return redirect('/verify_email')->with([
                'fail' => 'Invalid verification link'
            ]);
        }

        EmailVerification::where('user_email', $verification->user_email)->delete();
        User::where('email', $verification->user_email)->update(['email_verified_at' => date('Y-m-d H:i:s')]);
        return redirect('/login')->with([
            'success' => 'Email verification was successful'
        ]);
    } //verifyEmail
}
