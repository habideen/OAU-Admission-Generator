<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\API\V1\CatchmentController as V1CatchmentController;
use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\Request;

class CatchmentController extends Controller
{
    public function addView(Request $request)
    {
        $catchment = (new V1CatchmentController)->list($request);
        $catchment = json_decode($catchment->getContent())->catchment;

        return view('catchment_and_elds_add')->with([
            'type' => 'Catchment',
            'catchment' => $catchment,
            'states' => State::all(['id', 'name'])
        ]);
    } //addView



    public function add(Request $request)
    {
        $api = (new V1CatchmentController)->add($request);
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
    } //add


    public function edit(Request $request)
    {
        $api = (new V1CatchmentController)->edit($request);
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
    } //edit



    public function list(Request $request)
    {
        $api = (new V1CatchmentController)->list($request);
        $api = json_decode($api->getContent());

        return view('catchment_and_elds_list')->with([
            'rows' => $api->catchment,
            'type' => 'Catchment',
            'states' => State::all(['id', 'name'])
        ]);
    } //list


    public function delete(Request $request)
    {
        $api = (new V1CatchmentController)->delete($request);
        $api = json_decode($api->getContent());

        return redirect()->back()->with(
            (array) $api
        )->withErrors($api->errors ?? null);
    }
}
