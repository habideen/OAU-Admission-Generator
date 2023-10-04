<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PasswordController extends Controller
{
    public function index()
    {
        return view('user_password');
    } //index



    public function updatePassword(Request $request)
    {
        Session::flash('status', 'failed');
        Session::flash('message', 'Invalid input submitted');

        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);


        if (!(Hash::check($request->get('current_password'), Auth::user()->password))) {
            return redirect()->back()->with([
                'status' => 'failed',
                'message' => 'Your Current password does not matches with the password you provided. Please try again.'
            ]);
        } else {
            $user = User::where('id', Auth::user()->id)
                ->update(['password' => Hash::make($request->get('password'))]);

            if ($user) {
                Auth::user()->password = Hash::make($request->get('password'));

                //logout from other devices
                Auth::logoutOtherDevices($request->password);

                return redirect()->back()->with([
                    'status' => 'success',
                    'message' => 'Password updated successfully!'
                ]);
            } else {
                return redirect()->back()->with([
                    'status' => 'failed',
                    'message' => SERVER_ERROR
                ]);
            }
        }
    }
}
