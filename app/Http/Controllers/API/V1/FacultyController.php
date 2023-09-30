<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FacultyController extends Controller
{
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'faculty' => ['required', 'regex:/^[a-zA-Z0-9\-\(\) ]{3,255}$/', 'unique:faculties']
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => 'Invalid input submitted',
                'errors' => $validator->errors(),
            ], Response::HTTP_EXPECTATION_FAILED);
        }

        $save = new Faculty;
        $save->faculty = $request->faculty;
        $save->save();

        if (!$save) {
            return response([
                'status' => 'failed',
                'message' => 'Server error!'
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return response([
            'status' => 'success',
            'message' => 'Faculty added successfully'
        ], Response::HTTP_CREATED);
    } //add




    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'faculty_id' => ['required', 'integer', 'exists:faculties,id'],
            'faculty' => [
                'required', 'regex:/^[a-zA-Z0-9\-\(\) ]{3,255}$/',
                Rule::unique('faculties', 'faculty')->ignore($request->faculty_id)
            ]
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => 'Invalid input submitted',
                'errors' => $validator->errors(),
            ], Response::HTTP_EXPECTATION_FAILED);
        }

        $save = Faculty::where('id', $request->faculty_id)->update([
            'faculty' => $request->faculty
        ]);

        if (!$save) {
            return response([
                'status' => 'failed',
                'message' => 'Server error!'
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return response([
            'status' => 'success',
            'message' => 'Faculty updated successfully'
        ], Response::HTTP_CREATED);
    } //edit



    public function list(Request $request, $fetchAll = false)
    {
        $pag = $request->pagination && ctype_digit($request->pagination) ? $request->pagination : PAGINATION;

        $faculties = Faculty::orderBy('faculty', 'ASC');
        $faculties = $fetchAll ? $faculties->get() : $faculties->paginate($pag);

        return response([
            'status' => 'success',
            'message' => 'Retrieved successfully',
            'faculties' => $faculties
        ]);
    } //list




    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:faculties,id']
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => 'Invalid input submitted',
                'errors' => $validator->errors(),
            ], Response::HTTP_EXPECTATION_FAILED);
        }
        /**
         * TODO
         * check if a course has selected it before deleting
         */

        $delete = Faculty::where('id', $request->id)->delete();

        if (!$delete) {
            return response([
                'status' => 'failed',
                'message' => 'Server error!'
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return response([
            'status' => 'success',
            'message' => 'Faculty deleted successfully'
        ], Response::HTTP_CREATED);
    } //delete
}
