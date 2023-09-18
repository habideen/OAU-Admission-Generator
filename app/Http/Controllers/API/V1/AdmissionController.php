<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\AdmissionCriteria;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class AdmissionController extends Controller
{
    public function admissionCriteria(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'merit' => ['required', 'integer', 'min:0', 'max:100'],
            'catchment' => ['required', 'integer', 'min:0', 'max:100'],
            'elds' => ['required', 'integer', 'min:0', 'max:100'], //Educationally Less Developed States
            'discretion' => ['required', 'integer', 'min:0', 'max:100'], //flexible addmission
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => 'Invalid input submitted',
                'errors' => $validator->errors(),
            ], Response::HTTP_EXPECTATION_FAILED);
        }

        if (($request->merit + $request->catchment + $request->elds + $request->discretion) != 100) {
            return response([
                'status' => 'failed',
                'message' => 'The total should be 100.'
            ], Response::HTTP_EXPECTATION_FAILED);
        }


        $save = AdmissionCriteria::updateOrCreate(
            [
                'id' => '1' //only one column exist for this
            ],
            [
                'merit' => $request->merit,
                'catchment' => $request->catchment,
                'elds' => $request->elds,
                'discretion' => $request->discretion,
            ]
        );

        if (!$save) {
            return response([
                'status' => 'failed',
                'message' => 'Server error!'
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return response([
            'status' => 'success',
            'message' => 'Admission criteria updated successfully'
        ], Response::HTTP_CREATED);
    } //admissionCriteria
}
