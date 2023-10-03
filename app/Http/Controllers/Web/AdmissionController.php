<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\API\V1\AdmissionController as V1AdmissionController;
use App\Http\Controllers\API\V1\FacultyController;
use App\Http\Controllers\API\V1\SessionController;
use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdmissionController extends Controller
{
    public function admissionCriteriaView(Request $request)
    {
        $api = (new V1AdmissionController)->getadmissionCriteria($request);
        $api = json_decode($api->getContent());

        return view('admission_criteria_set')->with([
            'criteria' => $api->criteria
        ]);
    } //admissionCriteriaView



    public function admissionCriteria(Request $request)
    {
        $api = (new V1AdmissionController)->admissionCriteria($request);
        $api = json_decode($api->getContent());

        return apiResponse($api);
    } //admissionCriteria



    public function generateAdmissionView(Request $request)
    {
        $request->merge(['fetch_all' => 'true']);

        $admissions = (new V1AdmissionController)->getCandidatesAdmitted($request);
        $admissions = json_decode($admissions->getContent());

        $faculties = (new FacultyController)->list($request);
        $faculties = json_decode($faculties->getContent());

        $sessions = (new SessionController)->getAll($request);
        $sessions = json_decode($sessions->getContent());

        $courses = Course::select('course');
        if (!in_array(Auth::user()->account_type, ['Admin', 'Super Admin'])) {
            // select only the departments in that faculty when user is Dean
            $courses = $courses->where('faculty_id', Auth::user()->faculty_id);
        }

        return view('admission_criteria_generate')->with([
            'courses' => $courses->get(),
            'faculties' => $faculties->faculties,
            'sessions' => $sessions->sessions,
            'admissions' => $admissions->admissions
        ]);
    } //generateAdmissionView



    public function generateAdmission(Request $request)
    {
        $api = (new V1AdmissionController)->generateAdmission($request);
        $api = json_decode($api->getContent());

        return apiResponse($api);
    } //generateAdmission



    public function admissionStat(Request $request)
    {
        $stats = (new V1AdmissionController)->admissionStat($request);
        $stats = json_decode($stats->getContent());

        $totalCandidates = (new V1AdmissionController)->totalCandidates($request);
        $totalCandidates = json_decode($totalCandidates->getContent());

        return view('admission_criteria_statistics')->with([
            'stats' => $stats->stats,
            'totalCandidates' => $totalCandidates->num,
        ]);
    } //admissionStat



    public function discretionUploadView()
    {
        return view('admission_discretion_upload');
    } //discretionUploadView



    public function discretionUpload(Request $request)
    {
        $api = (new V1AdmissionController)->discretionUpload($request);
        $api = json_decode($api->getContent());

        return apiResponse($api);
    } //discretionUpload
}
