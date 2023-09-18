<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Imports\CandidatesImport;
use App\Models\Candidate;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class CandidatesController extends Controller
{
  public function upload(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'candidates_file' => ['required', 'mimes:xls,xlsx']
    ]);

    if ($validator->fails()) {
      return response([
        'status' => 'failed',
        'message' => 'Invalid input submitted',
        'errors' => $validator->errors(),
      ], Response::HTTP_EXPECTATION_FAILED);
    }

    Session::flash('active_session', activeSession());

    Excel::import(new CandidatesImport(), $request->candidates_file);

    return response([
      'status' => 'success',
      'message' => 'Upload successfully.'
    ], Response::HTTP_CREATED);
  } //upload




  public function delete(Request $request)
  {
    $request->request->add([
      'session' => ucwords($request->session) == 'Current Session' ? activeSession() : $request->session
    ]);

    $validator = Validator::make($request->all(), [
      'session' => [
        'required',
        'regex:/^[2-9][0-9]{3,3}[\/][2-9][0-9]{3,3}$/',
        function (string $attribute, mixed $value, Closure $fail) {
          $years = explode('/', $value);

          if (
            count($years) !== 2
            || intval($years[0]) >= intval($years[1])
            || intval($years[0]) + 1 != intval($years[1])
            || intval($years[0]) >= date('Y') + 10
          ) {
            $fail("The {$attribute} is invalid.");
          }
        },
      ]
    ]);

    if ($validator->fails()) {
      return response([
        'status' => 'failed',
        'message' => 'Invalid session submitted',
        'errors' => $validator->errors(),
      ], Response::HTTP_EXPECTATION_FAILED);
    }

    /**
     * TODO
     * check if a course has selected it before deleting
     */
    if (!Candidate::where('session_updated', $request->session)->first()) {
      return response([
        'status' => 'success',
        'message' => 'No candidates found for ' . $request->session
      ]);
    }

    $delete = Candidate::where('session_updated', $request->session)->delete();

    if (!$delete) {
      return response([
        'status' => 'failed',
        'message' => 'Server error!'
      ], Response::HTTP_SERVICE_UNAVAILABLE);
    }

    return response([
      'status' => 'success',
      'message' => $request->session . ' uploaded candidates are deleted.'
    ]);
  }
}
