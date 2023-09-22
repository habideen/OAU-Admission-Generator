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
        $sessionUpdated = '2022/2023';
        $timestamp = now();

        // Step 1: Retrieve the distinct courses for the specified session_updated and sort them in ascending order
        $distinctCourses = DB::table('candidates')
            ->where('session_updated', $sessionUpdated)
            ->distinct()
            ->orderBy('course', 'asc')
            ->pluck('course');

        $catchmentStates = Catchment::select('state')->get();
        $catchmentStates = count($catchmentStates) > 0 ? (array)$catchmentStates->pluck('state') : [];

        $eldsStates = elds::select('state')->get();
        $eldsStates = count($eldsStates) > 0 ? (array)$eldsStates->pluck('state') : [];

        foreach ($distinctCourses as $course) {
            // Step 2: Select rg_num, aggregate, state_name from Candidates table for the current course
            $candidates = DB::table('candidates')
                ->select('rg_num', 'aggregate', 'state_name')
                ->where('course', $course)
                ->where('session_updated', $sessionUpdated)
                ->orderBy('aggregate', 'desc')
                ->get();

            // Step 3: Retrieve the admission criteria percentages from admission_criterias table
            $admissionCriteria = DB::table('admission_criterias')
                ->select('merit', 'catchment', 'elds')
                ->where('session', $request->session ?? activeSession())
                ->first();

            $meritPercentage = $admissionCriteria->merit;
            $catchmentPercentage = $admissionCriteria->catchment;
            $eldsPercentage = $admissionCriteria->elds;

            // Calculate the number of candidates to admit based on the merit percentage
            $meritCount = (int)(count($candidates) * ($meritPercentage / 100));

            // Initialize the admission list
            $admissionList = [];

            // Admit candidates based on merit
            for ($i = 0; $i < $meritCount; $i++) {
                if ($i < count($candidates)) {
                    $admissionList[] = [
                        'rg_num' => $candidates[$i]->rg_num,
                        'category' => 'Merit',
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp
                    ];
                }
            }

            // Calculate the number of candidates to admit based on the catchment percentage
            $catchmentCount = (int)((count($candidates) - $meritCount) * ($catchmentPercentage / 100));

            // Admit remaining candidates based on catchment and state criteria
            for ($i = $meritCount; $i < $meritCount + $catchmentCount; $i++) {
                if ($i < count($candidates) && in_array($candidates[$i]->state_name, $catchmentStates)) {
                    $admissionList[] = [
                        'rg_num' => $candidates[$i]->rg_num,
                        'category' => 'Catchment',
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp
                    ];
                }
            }

            // Calculate the number of candidates to admit based on the ELDS percentage
            $eldsCount = (int)((count($candidates) - $meritCount - $catchmentCount) * ($eldsPercentage / 100));

            // Admit remaining candidates based on ELDS and state criteria
            for ($i = $meritCount + $catchmentCount; $i < $meritCount + $catchmentCount + $eldsCount; $i++) {
                if ($i < count($candidates) && in_array($candidates[$i]->state_name, $eldsStates)) {
                    $admissionList[] = [
                        'rg_num' => $candidates[$i]->rg_num,
                        'category' => 'ELDS',
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




    public function temp(Request $request)
    {
        return (new GetAdmissionList([
            'session' => '2022/2023',
            'type' => 'Computer Engineering'
        ]))->download('invoices.xlsx');
    }
}
