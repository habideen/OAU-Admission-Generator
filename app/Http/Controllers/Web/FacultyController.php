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

        return apiResponse($api);
    } //add



    public function uploadView(Request $request)
    {
        return view('faculty_upload')->with([]);
    } //uploadView


    public function upload(Request $request)
    {
        $api = (new V1FacultyController)->upload($request);
        $api = json_decode($api->getContent());

        return apiResponse($api);
    } //upload



    public function edit(Request $request)
    {
        $api = (new V1FacultyController)->edit($request);
        $api = json_decode($api->getContent());

        return apiResponse($api);
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
