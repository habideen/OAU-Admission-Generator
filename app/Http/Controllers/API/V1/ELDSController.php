<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\elds;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ELDSController extends Controller
{
    public function add(Request $request)
    {
        $activeSession = activeSession();

        $validator = Validator::make($request->all(), [
            'state_id' => [
                'required', 'exists:states,id',
                Rule::unique('elds')
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

        $save = new elds;
        $save->state_id = $request->state_id;
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
            'message' => 'ELDS state added successfully for ' . $activeSession . ' session'
        ], Response::HTTP_CREATED);
    } //add



    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'elds_id' => ['required', 'integer', 'exists:elds,id'],
            'state_id' => [
                'required', 'exists:states,id',
                Rule::unique('elds')
                    ->where('session_updated', activeSession())
                    ->ignore($request->elds_id)
            ]
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => 'Invalid input submitted',
                'errors' => $validator->errors(),
            ], Response::HTTP_EXPECTATION_FAILED);
        }

        $save = elds::find($request->elds_id);
        $save->state_id = $request->state_id;
        $save->save();

        if (!$save) {
            return response([
                'status' => 'failed',
                'message' => 'Server error!'
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return response([
            'status' => 'success',
            'message' => 'ELDS state updated successfully'
        ], Response::HTTP_CREATED);
    } //edit



    public function list(Request $request)
    {
        $elds = elds::select(
            'elds.id',
            'elds.state_id',
            'states.name AS state',
            'elds.session_updated',
            'elds.created_at',
            'elds.updated_at'
        )
            ->join('states', 'states.id', '=', 'elds.state_id')
            ->orderBy('state', 'ASC')
            ->where('session_updated', $request->session ?? activeSession())
            ->get();

        return response([
            'status' => 'success',
            'message' => 'Retrieved successfully',
            'elds' => $elds
        ]);
    } //list




    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:elds']
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => 'Invalid input submitted',
                'errors' => $validator->errors(),
            ], Response::HTTP_EXPECTATION_FAILED);
        }

        $delete = elds::where('id', $request->id)->delete();

        if (!$delete) {
            return response([
                'status' => 'failed',
                'message' => 'Server error!'
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return response([
            'status' => 'success',
            'message' => 'ELDS deleted successfully'
        ], Response::HTTP_CREATED);
    } //delete
}
