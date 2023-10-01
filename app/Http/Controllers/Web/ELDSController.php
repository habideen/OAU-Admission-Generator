<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\API\V1\ELDSController as V1ELDSController;
use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\Request;

class ELDSController extends Controller
{
    public function addView(Request $request)
    {
        return view('catchment_and_elds_add')->with([
            'type' => 'ELDS',
            'states' => State::all(['id', 'name'])
        ]);
    } //addView



    public function add(Request $request)
    {
        $api = (new V1ELDSController)->add($request);
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
        $api = (new V1ELDSController)->edit($request);
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
        $api = (new V1ELDSController)->list($request);
        $api = json_decode($api->getContent());

        return view('catchment_and_elds_list')->with([
            'rows' => $api->elds,
            'type' => 'ELDS',
            'states' => State::all(['id', 'name'])
        ]);
    } //list


    public function delete(Request $request)
    {
        $api = (new V1ELDSController)->delete($request);
        $api = json_decode($api->getContent());

        return redirect()->back()->with(
            (array) $api
        )->withErrors($api->errors ?? null);
    }
}
