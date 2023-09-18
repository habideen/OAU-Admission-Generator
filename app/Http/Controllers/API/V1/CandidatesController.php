<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Imports\CandidatesImport;
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
}
