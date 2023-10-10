<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Imports\SubjectImport;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

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
        $save->subject_code = strtoupper($request->subject_code);
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




    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject_file' => ['required', 'mimes:xls,xlsx']
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => 'Invalid input submitted',
                'errors' => $validator->errors(),
            ], Response::HTTP_EXPECTATION_FAILED);
        }

        Excel::import(new SubjectImport(), $request->subject_file);

        $report = Session::has('report_failed') ? Session::get('report_failed') : '';
        $count = (int) Session::get('success_count')
            . ' of '
            . (Session::get('success_count') + Session::get('failed_count'))
            . ' uploaded. &nbsp;&nbsp;&nbsp;'
            . (int) Session::get('failed_count') . ' failed.';

        $report = $report ?
            $report . '<br><br>' . $count
            : $count;

        return response([
            'status' => 'success',
            'message' => $report
        ], Response::HTTP_CREATED);
    } //upload




    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_subject_code' => ['required', 'regex:/^[a-zA-Z]{3,3}$/', 'exists:subjects,subject_code'],
            'new_subject_code' => [
                'required', 'regex:/^[a-zA-Z]{3,3}$/',
                Rule::unique('subjects', 'subject_code')->ignore($request->old_subject_code, 'subject_code')
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
            'subject_code' => strtoupper($request->new_subject_code),
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



    public function list(Request $request)
    {
        $pag = $request->pagination && ctype_digit($request->pagination) ? $request->pagination : PAGINATION;

        $subjects = Subject::orderBy('subject', 'ASC');
        $subjects = strtolower($request->get('fetch_all')) == 'true'
            ? $subjects->get() : $subjects->paginate($pag);

        return response([
            'status' => 'success',
            'message' => 'Retrieved successfully',
            'subjects' => $subjects
        ]);
    } //list




    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject_code' => ['required', 'regex:/^[a-zA-Z]{3,3}$/', 'exists:subjects,subject_code']
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

        $delete = Subject::where('subject_code', $request->subject_code)->delete();

        if (!$delete) {
            return response([
                'status' => 'failed',
                'message' => 'Server error!'
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return response([
            'status' => 'success',
            'message' => 'Subject deleted successfully'
        ], Response::HTTP_CREATED);
    } //delete
}
