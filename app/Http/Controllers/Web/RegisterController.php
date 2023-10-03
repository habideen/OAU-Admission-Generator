<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\API\V1\FacultyController;
use App\Http\Controllers\API\V1\RegisterController as V1RegisterController;
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
        $api = (new V1RegisterController)->register($request);
        $api = json_decode($api->getContent());

        return apiResponse($api);
    } //register
}
