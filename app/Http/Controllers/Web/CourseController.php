<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\API\V1\CourseController as V1CourseController;
use App\Http\Controllers\API\V1\FacultyController;
use App\Http\Controllers\API\V1\SubjectController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function addView(Request $request)
    {
        $request->merge(['fetch_all' => 'true']);

        $subjects = (new SubjectController)->list($request);
        $subjects = json_decode($subjects->getContent())->subjects;

        $faculties = (new FacultyController)->list($request);
        $faculties = json_decode($faculties->getContent())->faculties;


        return view('course_add')->with([
            'subjects' => $subjects,
            'faculties' => $faculties,
        ]);
    } //addView



    public function add(Request $request)
    {
        $api = (new V1CourseController)->add($request);
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
        $api = (new V1CourseController)->edit($request);
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
        $request->merge(['fetch_all' => 'true']);

        $api = (new V1CourseController)->list($request);
        $api = json_decode($api->getContent());

        $subjects = (new SubjectController)->list($request);
        $subjects = json_decode($subjects->getContent())->subjects;

        $faculties = (new FacultyController)->list($request);
        $faculties = json_decode($faculties->getContent())->faculties;

        return view('course_list')->with([
            'courses' => $api->courses,
            'subjects' => $subjects,
            'faculties' => $faculties,
        ]);
    } //list


    public function delete(Request $request)
    {
        $api = (new V1CourseController)->delete($request);
        $api = json_decode($api->getContent());

        return redirect()->back()->with(
            (array) $api
        )->withErrors($api->errors ?? null);
    }
}
