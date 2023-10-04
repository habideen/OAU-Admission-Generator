<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Imports\CandidatesImport;
use App\Models\Candidate;
use App\Models\Course;
use App\Rules\SessionValidation;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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



  public function list(Request $request)
  {
    $candidates = Candidate::select(
      'candidates.*',
      DB::raw('IF(admission_lists.rg_num IS NOT NULL, 
        "<span class=\'text-success\'>Admitted</span>", 
        "<span class=\'text-danger\'>No</span>") AS isAdmitted')
    )
      ->orderBy('course', 'ASC')
      ->orderBy('fullname', 'ASC')
      ->leftJoin('admission_lists', 'admission_lists.rg_num', '=', 'candidates.rg_num')
      ->where('session_updated', $request->session ?? activeSession());

    if (!in_array(Auth::user()->account_type, ['Admin', 'Super Admin'])) {
      // select only the departments in that faculty when user is Dean
      $courses = Course::select('course')
        ->where('faculty_id', Auth::user()->faculty_id)->get();
      $courses = count($courses) > 0 ? $courses->pluck('course') : [];

      $candidates = $candidates->whereIn('candidates.course', $courses);
    }

    return response([
      'status' => 'success',
      'message' => 'Retrieved successfully',
      'candidates' => $candidates->get()
    ]);
  } //list




  public function delete(Request $request)
  {
    $request->merge([
      'session' => ucwords($request->session) == 'Current Session' ? activeSession() : $request->session
    ]);

    $validator = Validator::make($request->all(), [
      'session' => ['required', new SessionValidation]
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
      'message' => $delete . ' record(s) where deleted for ' . $request->session . '.'
    ]);
  }
}
