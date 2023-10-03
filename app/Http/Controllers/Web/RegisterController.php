<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\API\V1\FacultyController;
use App\Http\Controllers\API\V1\UserManagementController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller
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
        $api = (new UserManagementController)->register($request);
        $api = json_decode($api->getContent());

        return apiResponse($api);
    } //register


    public function listUsers(Request $request)
    {
        $users = (new UserManagementController)->listUsers($request);
        $users = json_decode($users->getContent())->users;

        return view('user_list')->with([
            'users' => $users
        ]);
    } //listUsers
}
