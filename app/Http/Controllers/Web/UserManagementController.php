<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\API\V1\FacultyController;
use App\Http\Controllers\API\V1\UserManagementController as V1UserManagementController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function registerView(Request $request)
    {
        $request->merge(['fetch_all' => 'true']);

        $faculties = (new FacultyController)->list($request);
        $faculties = json_decode($faculties->getContent())->faculties;

        return view('user_add')->with([
            'faculties' => $faculties,
        ]);
    } //registerView


    public function register(Request $request)
    {
        $api = (new V1UserManagementController)->registerOrEdit($request);
        $api = json_decode($api->getContent());

        return apiResponse($api);
    } //register


    public function listUsers(Request $request)
    {
        $request->merge(['fetch_all' => 'true']);

        $faculties = (new FacultyController)->list($request);
        $faculties = json_decode($faculties->getContent())->faculties;

        $users = (new V1UserManagementController)->listUsers($request);
        $users = json_decode($users->getContent())->users;

        return view('user_list')->with([
            'faculties' => $faculties,
            'users' => $users
        ]);
    } //listUsers


    public function edit(Request $request)
    {
        $api = (new V1UserManagementController)->registerOrEdit($request);
        $api = json_decode($api->getContent());

        return apiResponse($api);
    } //edit
}
