<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AdmissionList;
use App\Models\Candidate;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCandidates = Candidate::select(
            DB::raw('COUNT(candidates.rg_num) AS num')
        )
            ->where('session_updated', activeSession());


        $totalAdmitted = Candidate::select(
            DB::raw('COUNT(candidates.rg_num) AS num')
        )
            ->join('admission_lists', 'admission_lists.rg_num', '=', 'candidates.rg_num')
            ->where('session_updated', activeSession());

        $totalMerit = AdmissionList::select(DB::raw('COUNT(rg_num) AS num'))
            ->where('category', 'Merit');

        $totalCatchment = AdmissionList::select(DB::raw('COUNT(rg_num) AS num'))
            ->where('category', 'Catchment');

        $totalELDS = AdmissionList::select(DB::raw('COUNT(rg_num) AS num'))
            ->where('category', 'ELDS');

        $totalDiscretion = AdmissionList::select(DB::raw('COUNT(rg_num) AS num'))
            ->where('category', 'Discretion');

        $totalCapacity = Course::select(DB::raw('SUM(capacity) AS num'));

        $totalSpaceLeft = AdmissionList::select(DB::raw('COUNT(rg_num) AS num'))
            ->where('category', 'Merit');


        if (!in_array(Auth::user()->account_type, ['Admin', 'Super Admin'])) {
            // select only the departments in that faculty when user is Dean
            $courses = Course::select('course')
                ->where('faculty_id', Auth::user()->faculty_id)->get();
            $courses = count($courses) > 0 ? $courses->pluck('course') : [];

            $totalCandidates = $totalCandidates->whereIn('candidates.course', $courses);

            $totalAdmitted = $totalAdmitted->whereIn('candidates.course', $courses);

            $totalMerit = $totalMerit->whereIn('course', $courses);
            $totalCatchment = $totalCatchment->whereIn('course', $courses);
            $totalELDS = $totalELDS->whereIn('course', $courses);
            $totalDiscretion = $totalDiscretion->whereIn('course', $courses);
            $totalCapacity = $totalCapacity->whereIn('course', $courses);
        }




        return view('dashboard')->with([
            'totalCandidates' => $totalCandidates->first()->num,
            'totalAdmitted' => $totalAdmitted->first()->num,
            'totalMerit' => $totalMerit->first()->num,
            'totalCatchment' => $totalCatchment->first()->num,
            'totalELDS' => $totalELDS->first()->num,
            'totalDiscretion' => $totalDiscretion->first()->num,
            'totalCapacity' => $totalCapacity->first()->num
        ]);
    }
}
