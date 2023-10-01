<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\API\V1\AdmissionController as V1AdmissionController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}
