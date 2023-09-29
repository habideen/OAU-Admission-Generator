<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\API\V1\FacultyController as V1FacultyController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class FacultyController extends Controller
{
    public function addView()
    {
        return view('faculty_add');
    } //addView



    public function add(Request $request)
    {
        $api = (new V1FacultyController)->add($request);
        $api = json_decode($api->getContent());

        return redirect()->back()->with(
            (array) $api
        )->withErrors($api->errors ?? null);
    } //add
}
