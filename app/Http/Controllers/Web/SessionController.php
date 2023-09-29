<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\API\V1\SessionController as V1SessionController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function setView()
    {
        return view('session_set')->with([
            'active_session' => activeSession()
        ]);
    } //setView




    public function set(Request $request)
    {
        $api = (new V1SessionController)->set($request);
        $api = json_decode($api->getContent());

        return redirect()->back()->with(
            (array) $api
        )->withErrors($api->errors);
    } //set




    public function getAll(Request $request)
    {
        $api = (new V1SessionController)->getAll($request);
        $api = json_decode($api->getContent());

        return view('session_view_all')->with([
            'sessions' => $api->sessions
        ]);
    } //getAll
}
