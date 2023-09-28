<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        User::where('id', Auth::user()->id)->update(['remember_token' => null]);

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->flush();

        $cookie = cookie(Auth::getRecallerName(), null, -2628000); // Delete the "remember me" cookie

        return redirect('/')->withCookie($cookie);
    }


    public function logoutAllWeb(Request $request)
    {
        // Auth::guard('web')->logoutOtherDevices($request->password);

        // Auth::session()->invalidate();

        // Auth::session()->regenerateToken();

        // Auth::session()->flush();
    } //logoutAllWeb
}
