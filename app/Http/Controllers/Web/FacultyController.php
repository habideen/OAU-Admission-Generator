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

        if ($api->status != 'success') {
            return redirect()->back()->with(
                (array) $api
            )->withErrors($api->errors ?? null)
                ->withInput();
        }

        return redirect()->back()->with(
            (array) $api
        );
    } //add



    public function edit(Request $request)
    {
        $api = (new V1FacultyController)->edit($request);
        $api = json_decode($api->getContent());

        if ($api->status != 'success') {
            return redirect()->back()->with(
                (array) $api
            )->withErrors($api->errors ?? null)
                ->withInput();
        }

        return redirect()->back()->with(
            (array) $api
        );
    } //edit



    public function list(Request $request)
    {
        $api = (new V1FacultyController)->list($request);
        $api = json_decode($api->getContent());

        return view('faculty_list')->with([
            'faculties' => $api->faculties
        ]);
    } //list


    public function delete(Request $request)
    {
        $api = (new V1FacultyController)->delete($request);
        $api = json_decode($api->getContent());

        return redirect()->back()->with(
            (array) $api
        )->withErrors($api->errors ?? null);
    }
}
