<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\SubjectCombination;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'faculty_id' => ['required', 'integer', 'exists:faculties,id'],
            'course' => [
                'required', 'string', 'min:2', 'max:255',
                'regex:/^[a-zA-Z0-9\-\# ]{2,255}$/', 'unique:courses'
            ],
            'subject_code_1' => [
                'nullable',
                'regex:/^[a-zA-Z]{3,3}$/',
                'regex:/^[a-zA-Z]{3,3}$/',
                'exists:subjects,subject_code',
                'different:subject_code_2', 'different:subject_code_3',
                'different:subject_code_4', 'different:subject_code_5',
                'different:subject_code_6', 'different:subject_code_7',
                'different:subject_code_8',
            ],
            'subject_code_2' => [
                'nullable',
                'regex:/^[a-zA-Z]{3,3}$/',
                'regex:/^[a-zA-Z]{3,3}$/',
                'exists:subjects,subject_code',
                'different:subject_code_1', 'different:subject_code_3',
                'different:subject_code_4', 'different:subject_code_5',
                'different:subject_code_6', 'different:subject_code_7',
                'different:subject_code_8',
            ],
            'subject_code_3' => [
                'nullable',
                'regex:/^[a-zA-Z]{3,3}$/',
                'regex:/^[a-zA-Z]{3,3}$/',
                'exists:subjects,subject_code',
                'different:subject_code_1', 'different:subject_code_2',
                'different:subject_code_4', 'different:subject_code_5',
                'different:subject_code_6', 'different:subject_code_7',
                'different:subject_code_8',
            ],
            'subject_code_4' => [
                'nullable',
                'regex:/^[a-zA-Z]{3,3}$/',
                'regex:/^[a-zA-Z]{3,3}$/',
                'exists:subjects,subject_code',
                'different:subject_code_1', 'different:subject_code_2',
                'different:subject_code_3', 'different:subject_code_5',
                'different:subject_code_6', 'different:subject_code_7',
                'different:subject_code_8',
            ],
            'subject_code_5' => [
                'nullable',
                'regex:/^[a-zA-Z]{3,3}$/',
                'regex:/^[a-zA-Z]{3,3}$/',
                'exists:subjects,subject_code',
                'different:subject_code_1', 'different:subject_code_2',
                'different:subject_code_3', 'different:subject_code_4',
                'different:subject_code_6', 'different:subject_code_7',
                'different:subject_code_8',
            ],
            'subject_code_6' => [
                'nullable',
                'regex:/^[a-zA-Z]{3,3}$/',
                'regex:/^[a-zA-Z]{3,3}$/',
                'exists:subjects,subject_code',
                'different:subject_code_1', 'different:subject_code_2',
                'different:subject_code_3', 'different:subject_code_4',
                'different:subject_code_5', 'different:subject_code_7',
                'different:subject_code_8',
            ],
            'subject_code_7' => [
                'nullable',
                'regex:/^[a-zA-Z]{3,3}$/',
                'regex:/^[a-zA-Z]{3,3}$/',
                'exists:subjects,subject_code',
                'different:subject_code_1', 'different:subject_code_2',
                'different:subject_code_3', 'different:subject_code_4',
                'different:subject_code_5', 'different:subject_code_6',
                'different:subject_code_8',
            ],
            'subject_code_8' => [
                'nullable',
                'regex:/^[a-zA-Z]{3,3}$/',
                'regex:/^[a-zA-Z]{3,3}$/',
                'exists:subjects,subject_code',
                'different:subject_code_1', 'different:subject_code_2',
                'different:subject_code_3', 'different:subject_code_4',
                'different:subject_code_5', 'different:subject_code_6',
                'different:subject_code_7',
            ]
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => 'Invalid input submitted',
                'errors' => $validator->errors(),
            ], Response::HTTP_EXPECTATION_FAILED);
        }

        $save = new Course;
        $save->faculty_id = $request->faculty_id;
        $save->course = $request->course;
        $save->session_updated = activeSession();
        $save->save();

        if (!$save) {
            return response([
                'status' => 'failed',
                'message' => 'Server error! We couldn\'t add the course.'
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        $subjects = [];
        for ($i = 1; $i <= 8; $i++) {
            array_push($subjects, $request->get('subject_code_' . $i));
        }

        sort($subjects);

        $insert = [];
        $i = 0;
        $subjects_len = count($subjects);
        while ($i++ < $subjects_len) {
            $insert['subject_code_' . $i] = $subjects[$i - 1];
        }

        unset($subjects, $i, $subjects_len);

        $insert['course_id'] = $save->id;
        $insert['session_updated'] = activeSession();
        $insert['created_at'] = now();
        $insert['updated_at'] = now();

        $save = SubjectCombination::insert($insert);


        if (!$save) {
            return response([
                'status' => 'failed',
                'message' => 'Server error! We couldn\'t create the subject combinations. Please edit the course to continue.'
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return response([
            'status' => 'success',
            'message' => 'Course created'
        ], Response::HTTP_CREATED);
    } //add
}


/**
 * Add course
 * get last course before it
 * get the subject combination id
 * use for this
 * 
 */
