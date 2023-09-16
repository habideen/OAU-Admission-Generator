<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course' => ['required', 'string', 'min:3', 'max:3', 'exist:subjects,subject_code']
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => 'Invalid input submitted',
                'errors' => $validator->errors(),
            ], Response::HTTP_EXPECTATION_FAILED);
        }
    } //add
}


/**
 * Add course
 * get last course before it
 * get the subject combination id
 * use for this
 * 
 */
