<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\API\V1\SubjectController as V1SubjectController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function addView()
    {
        return view('subject_add');
    } //addView



    public function add(Request $request)
    {
        $api = (new V1SubjectController)->add($request);
        $api = json_decode($api->getContent());

        return apiResponse($api);
    } //add



    public function uploadView(Request $request)
    {
        return view('subject_upload')->with([]);
    } //uploadView


    public function upload(Request $request)
    {
        $api = (new V1SubjectController)->upload($request);
        $api = json_decode($api->getContent());

        return apiResponse($api);
    } //upload



    public function edit(Request $request)
    {
        $api = (new V1SubjectController)->edit($request);
        $api = json_decode($api->getContent());

        return apiResponse($api);
    } //edit



    public function list(Request $request)
    {
        $api = (new V1SubjectController)->list($request);
        $api = json_decode($api->getContent());

        return view('subject_list')->with([
            'subjects' => $api->subjects
        ]);
    } //list


    public function delete(Request $request)
    {
        $api = (new V1SubjectController)->delete($request);
        $api = json_decode($api->getContent());

        return apiResponse($api);
    }
}
