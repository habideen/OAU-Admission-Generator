<?php

namespace App\Http\Controllers\API\V1;

use App\Exports\GetAdmissionList;
use App\Http\Controllers\Controller;
use App\Models\AdmissionCriteria;
use App\Models\AdmissionList;
use App\Models\Candidate;
use App\Models\Catchment;
use App\Models\Course;
use App\Models\elds;
use App\Rules\AccountTypeValidation;
use App\Rules\SessionValidation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use function PHPSTORM_META\type;

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
                'session' => activeSession() //only one column exist for this
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



    public function getadmissionCriteria(Request $request)
    {
        return response([
            'status' => 'success',
            'message' => 'Retrieved successfully',
            'criteria' => AdmissionCriteria::where('session', $request->session ?? activeSession())->first()
        ]);
    }



    public function generateAdmission(Request $request)
    {
        $sessionUpdated = activeSession();
        $timestamp = now();

        AdmissionList::whereNotNull('rg_num')->delete();

        // Step 1: Retrieve the distinct courses for the specified session_updated and sort them in ascending order
        $distinctCourses = DB::table('candidates')
            ->select('candidates.course', 'courses.capacity')
            ->join('courses', 'courses.course', '=', 'candidates.course')
            ->where('candidates.session_updated', $sessionUpdated)
            ->distinct()
            ->orderBy('candidates.course', 'asc')
            ->get();

        $catchmentStates = Catchment::select(DB::raw('UPPER(states.name) AS state'))
            ->join('states', 'states.id', '=', 'catchments.state_id')
            ->get();
        $catchmentStates = count($catchmentStates) > 0 ? $catchmentStates->pluck('state')->toArray() : [];

        $eldsStates = elds::select(DB::raw('UPPER(states.name) AS state'))
            ->join('states', 'states.id', '=', 'elds.state_id')
            ->get();
        $eldsStates = count($eldsStates) > 0 ? $eldsStates->pluck('state')->toArray() : [];

        // Retrieve the admission criteria percentages from admission_criterias table
        $admissionCriteria = DB::table('admission_criterias')
            ->select('merit', 'catchment', 'elds')
            ->where('session', $request->session ?? activeSession())
            ->first();

        $meritPercentage = $admissionCriteria->merit;
        $catchmentPercentage = $admissionCriteria->catchment;
        $eldsPercentage = $admissionCriteria->elds;


        foreach ($distinctCourses as $course) {
            // Step 2: Select rg_num, aggregate, state_name from Candidates table for the current course
            $candidates = DB::table('candidates')
                ->select(
                    'rg_num',
                    'aggregate',
                    DB::raw('UPPER(state_name) AS state_name')
                )
                ->where('course', $course->course)
                ->where('session_updated', $sessionUpdated)
                ->orderBy('aggregate', 'desc')
                ->get();

            // Calculate the number of candidates to admit based on the merit percentage
            $meritCount = floor($course->capacity * ($meritPercentage / 100));

            // Initialize the admission list
            $admissionList = [];

            // Admit candidates based on merit
            for ($i = 0; $i < $meritCount; $i++) {
                if ($i < count($candidates)) {
                    $admissionList[] = [
                        'rg_num' => $candidates[$i]->rg_num,
                        'category' => 'Merit',
                        'course' => $course->course,
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp
                    ];
                }
            }

            $candidates = unadmittedList($admissionList, $candidates);

            // Calculate the number of candidates to admit based on the catchment percentage
            $catchmentCount = floor($course->capacity * ($catchmentPercentage / 100));

            // Admit remaining candidates based on catchment and state criteria
            for ($i = $meritCount; $i < $meritCount + $catchmentCount; $i++) {
                if ($i < count($candidates) && in_array($candidates[$i]->state_name, $catchmentStates)) {
                    $admissionList[] = [
                        'rg_num' => $candidates[$i]->rg_num,
                        'category' => 'Catchment',
                        'course' => $course->course,
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp
                    ];
                }
            }

            $candidates = unadmittedList($admissionList, $candidates);

            // Calculate the number of candidates to admit based on the ELDS percentage
            $eldsCount = floor($course->capacity * ($eldsPercentage / 100));

            // Admit remaining candidates based on ELDS and state criteria
            for ($i = $meritCount + $catchmentCount; $i < $meritCount + $catchmentCount + $eldsCount; $i++) {
                if ($i < count($candidates) && in_array($candidates[$i]->state_name, $eldsStates)) {
                    $admissionList[] = [
                        'rg_num' => $candidates[$i]->rg_num,
                        'category' => 'ELDS',
                        'course' => $course->course,
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp
                    ];
                }
            }

            // generate admission for the generated course
            $save = AdmissionList::upsert(
                $admissionList,
                ['rg_num'],
                ['category', 'created_at', 'updated_at']
            );
        } //loop through courses


        if (!$save) {
            return response([
                'status' => 'failed',
                'message' => 'Server error!'
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return response([
            'status' => 'success',
            'message' => 'Admission generated successfully'
        ], Response::HTTP_CREATED);
    } //generateAdmission




    public function getCandidatesAdmitted(Request $request)
    {
        $query = AdmissionList::select(
            'candidates.rg_num',
            'candidates.fullname',
            'candidates.rg_sex',
            'candidates.aggregate',
            'admission_lists.course',
            'admission_lists.category',
            'candidates.session_updated',
            'admission_lists.updated_at'
        )
            ->join('candidates', 'candidates.rg_num', '=', 'admission_lists.rg_num')
            ->join('courses', 'courses.course', '=', 'candidates.course');


        if ($request->faculty) {
            $query = $query->join('faculties', 'faculties.id', '=', 'courses.faculty_id');
            $query = $query->where('faculties.id', $request->faculty);
        }

        if ($request->course) {
            $query = $query->where('candidates.course', $request->course);
        } elseif ($request->course == 'All') { //elseif ($request->course == 'All')
            if (!in_array(Auth::user()->account_type, ['Admin', 'Super Admin'])) {
                // select only the departments in that faculty when user is Dean
                $courses = Course::select('course')
                    ->where('faculty_id', Auth::user()->faculty_id)->get();
                $courses = count($courses) > 0 ? $courses->pluck('course') : [];

                $query = $query->whereIn('candidates.course', $courses);
            }
        }

        $query = $query->where('candidates.session_updated', $request->session ?? activeSession())
            ->orderBy('admission_lists.category', 'DESC');

        return response([
            'status' => 'success',
            'message' => 'Retrieved successfully',
            'admissions' => $query->get()
        ]);
    } //getCandidatesAdmitted




    public function downloadAdmission(Request $request)
    {
        $request->merge([
            'session' => ucwords($request->session) == 'Current Session' ? activeSession() : $request->session,
            'type' => ucwords($request->type)
        ]);

        $validator = Validator::make($request->all(), [
            'type' => ['required', 'string'],
            'session' => ['required', new SessionValidation]
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => 'Invalid input submitted',
                'errors' => $validator->errors(),
            ], Response::HTTP_EXPECTATION_FAILED);
        }

        if ($request->type != 'All') {
            $isCourse = Course::select('course')->where('course', $request->type)->first();

            if (!$isCourse) {
                return response([
                    'status' => 'failed',
                    'message' => 'Invalid course selected'
                ], Response::HTTP_EXPECTATION_FAILED);
            }
        }

        if (!canDownload($request)) {
            return response([
                'status' => 'failed',
                'message' => 'You don\'t have permission to perform this operations'
            ], Response::HTTP_EXPECTATION_FAILED);
        }


        return (new GetAdmissionList([
            'session' => $request->session,
            'type' => $request->type
        ]))->download($request->type . ' - ' . $request->session . '.xlsx');
    } //downloadAdmission




    public function admissionStat(Request $request)
    {
        if (!in_array(Auth::user()->account_type, ['Admin', 'Super Admin'])) {
            $courses = Course::select('course')
                ->where('faculty_id', Auth::user()->faculty_id)->get();
        } else {
            $courses = Course::select('course')->get();
        }

        $stats = AdmissionList::select(
            'faculties.faculty',
            'candidates.course',
            DB::raw("SUM(admission_lists.category = 'Merit') AS merit"),
            DB::raw("SUM(admission_lists.category = 'Catchment') AS catchment"),
            DB::raw("SUM(admission_lists.category = 'ELDS') AS elds"),
            DB::raw("SUM(admission_lists.category = 'Discretion') AS discretion"),
            'courses.capacity',
            'candidates.session_updated'
        )
            ->join('candidates', 'candidates.rg_num', '=', 'admission_lists.rg_num')
            ->join('courses', 'courses.course', '=', 'candidates.course')
            ->join('faculties', 'faculties.id', '=', 'courses.faculty_id')
            ->where('candidates.session_updated', activeSession())
            ->whereIn('candidates.course', $courses->pluck('course') ?? [])
            ->groupBy('course', 'capacity', 'faculty', 'session_updated')
            ->get();


        return response([
            'status' => 'success',
            'message' => 'Retrieved successfully',
            'stats' => $stats
        ]);
    } //admissionStat




    public function totalCandidates()
    {
        $courses = null;

        if (!in_array(Auth::user()->account_type, ['Admin', 'Super Admin'])) {
            $courses = Course::select('course')
                ->where('faculty_id', Auth::user()->faculty_id)->get();
        }
        //  else {
        //     $courses = Course::select('course')->get();
        // }

        $num = Candidate::select(DB::raw('COUNT(course) AS num'));
        if ($courses) {
            $num = $num->whereIn('course', $courses->pluck('course') ?? []);
        }
        $num = $num->first()->num;

        return response([
            'status' => 'success',
            'message' => 'Retrieved successfully',
            'num' => $num
        ]);
    } //totalCandidates
}
