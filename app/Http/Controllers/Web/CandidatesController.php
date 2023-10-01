<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\API\V1\CandidatesController as V1CandidatesController;
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

        if ($api->status != 'success') {
            return redirect()->back()->with(
                (array) $api
            )->withErrors($api->errors ?? null)
                ->withInput();
        }

        return redirect()->back()->with(
            (array) $api
        );
    } //upload
}
