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
            'subject_code' => ['required', 'string', 'min:3', 'max:3', 'exist:subjects,subject_code']
            //default password is surname
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
