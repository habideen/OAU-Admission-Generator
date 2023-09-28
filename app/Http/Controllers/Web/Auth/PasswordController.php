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
        return view('panel.password');
    } //index



    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!(Hash::check($request->get('current_password'), Auth::user()->password))) {
            return redirect()->back()->with([
                'fail' => 'Your Current password does not matches with the password you provided. Please try again.'
            ]);
        } else {
            $user = User::where('id', Auth::user()->id)
                ->update(['password' => Hash::make($request->get('password'))]);
            if ($user) {
                Auth::user()->password = Hash::make($request->get('password'));

                //logout from other devices
                Auth::logoutOtherDevices($request->password);

                return redirect()->back()->with([
                    'success' => 'Password updated successfully!'
                ]);
            } else {
                return redirect()->back()->with([
                    'fail' => SERVER_ERROR
                ]);
            }
        }
    }
}
