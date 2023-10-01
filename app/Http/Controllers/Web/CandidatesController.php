<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\API\V1\CandidatesController as V1CandidatesController;
use App\Http\Controllers\API\V1\SessionController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CandidatesController extends Controller
{
    public function uploadView(Request $request)
    {
        $api = (new V1CandidatesController)->list($request);
        $api = json_decode($api->getContent());

        return view('candidate_upload')->with([
            'candidates' => $api->candidates
        ]);
    } //uploadView



    public function upload(Request $request)
    {
        // return $request->all();
        $api = (new V1CandidatesController)->upload($request);
        $api = json_decode($api->getContent());

        return apiResponse($api);
    } //upload



    public function deleteView(Request $request)
    {
        $request->merge(['fetch_all' => 'true']);

        $api = (new SessionController)->getAll($request);
        $api = json_decode($api->getContent());

        return view('candidate_delete')->with([
            'sessions' => $api->sessions
        ]);
    } //deleteView



    public function delete(Request $request)
    {
        $api = (new V1CandidatesController)->delete($request);
        $api = json_decode($api->getContent());

        return apiResponse($api);
    }
}
