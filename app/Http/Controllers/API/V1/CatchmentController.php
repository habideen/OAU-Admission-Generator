<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Catchment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CatchmentController extends Controller
{
    public function add(Request $request)
    {
        $activeSession = activeSession();

        $validator = Validator::make($request->all(), [
            'state' => [
                'required', 'regex:/^[a-zA-Z\- ]{3,255}$/',
                Rule::unique('catchments')
                    ->where('session_updated', $activeSession)
            ]
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => 'Invalid input submitted',
                'errors' => $validator->errors(),
            ], Response::HTTP_EXPECTATION_FAILED);
        }

        $save = new Catchment;
        $save->state = strtoupper($request->state);
        $save->session_updated = $activeSession;
        $save->save();

        if (!$save) {
            return response([
                'status' => 'failed',
                'message' => 'Server error!'
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return response([
            'status' => 'success',
            'message' => 'Catchment state added successfully for ' . $activeSession . ' session'
        ], Response::HTTP_CREATED);
    } //add



    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'catchment_id' => ['required', 'integer', 'exists:catchments,id'],
            'state' => [
                'required', 'regex:/^[a-zA-Z\- ]{3,255}$/',
                Rule::unique('catchments')
                    ->where('session_updated', activeSession())
                    ->ignore($request->catchment_id)
            ]
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => 'Invalid input submitted',
                'errors' => $validator->errors(),
            ], Response::HTTP_EXPECTATION_FAILED);
        }

        $save = Catchment::find($request->catchment_id);
        $save->state = $request->state;
        $save->save();

        if (!$save) {
            return response([
                'status' => 'failed',
                'message' => 'Server error!'
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return response([
            'status' => 'success',
            'message' => 'Catchment state updated successfully'
        ], Response::HTTP_CREATED);
    } //edit



    public function list(Request $request)
    {
        $catchment = Catchment::orderBy('state', 'ASC')
            ->where('session_updated', $request->session ?? activeSession())
            ->get();

        return response([
            'status' => 'success',
            'message' => 'Retrieved successfully',
            'catchment' => $catchment
        ]);
    } //list




    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:catchments']
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => 'Invalid input submitted',
                'errors' => $validator->errors(),
            ], Response::HTTP_EXPECTATION_FAILED);
        }

        $delete = Catchment::where('id', $request->id)->delete();

        if (!$delete) {
            return response([
                'status' => 'failed',
                'message' => 'Server error!'
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return response([
            'status' => 'success',
            'message' => 'Catchment deleted successfully'
        ], Response::HTTP_CREATED);
    } //delete
}
