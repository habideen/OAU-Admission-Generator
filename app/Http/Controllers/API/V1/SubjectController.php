<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject_code' => ['required', 'regex:/^[a-zA-Z]{3,3}$/', 'unique:subjects'],
            'subject' => ['required', 'regex:/^[a-zA-Z\-\(\) ]{3,100}$/']
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => 'Invalid input submitted',
                'errors' => $validator->errors(),
            ], Response::HTTP_EXPECTATION_FAILED);
        }

        $save = new Subject;
        $save->subject_code = $request->subject_code;
        $save->subject = $request->subject;
        $save->save();

        if (!$save) {
            return response([
                'status' => 'failed',
                'message' => 'Server error!'
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return response([
            'status' => 'success',
            'message' => 'Subject added successfully'
        ], Response::HTTP_CREATED);
    } //add




    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_subject_code' => ['required', 'regex:/^[a-zA-Z]{3,3}$/', 'unique:subjects,subject_code'],
            'new_subject_code' => [
                'required', 'regex:/^[a-zA-Z]{3,3}$/',
                Rule::unique('subjects', 'subject_code')->ignore($request->old_subject_code)
            ],
            'subject' => ['required', 'regex:/^[a-zA-Z\-\(\) ]{3,100}$/']
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => 'Invalid input submitted',
                'errors' => $validator->errors(),
            ], Response::HTTP_EXPECTATION_FAILED);
        }

        $save = Subject::where('subject_code', $request->old_subject_code)->update([
            'subject_code' => $request->new_subject_code,
            'subject' => $request->subject
        ]);

        if (!$save) {
            return response([
                'status' => 'failed',
                'message' => 'Server error!'
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return response([
            'status' => 'success',
            'message' => 'Subject updated successfully'
        ], Response::HTTP_CREATED);
    } //edit
}
