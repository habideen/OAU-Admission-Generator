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
        $subjects = (new SubjectController)->list($request, true);
        $subjects = json_decode($subjects->getContent())->subjects;

        $faculties = (new FacultyController)->list($request, true);
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
}
