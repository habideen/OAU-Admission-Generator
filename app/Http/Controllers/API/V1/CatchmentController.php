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
            'state_id' => [
                'required', 'exists:states,id',
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
        $save->state_id = strtoupper($request->state_id);
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
            'state_id' => [
                'required', 'exists:states,id',
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
        $save->state_id = strtoupper($request->state_id);
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
        $catchment = Catchment::select(
            'catchments.id',
            'catchments.state_id',
            'states.name AS state',
            'catchments.session_updated',
            'catchments.created_at',
            'catchments.updated_at'
        )
            ->join('states', 'states.id', '=', 'catchments.state_id')
            ->orderBy('state', 'ASC')
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
