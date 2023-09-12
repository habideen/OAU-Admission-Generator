<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function register(Request $request, $isWeb = false)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['nullable', Rule::in(['Prof', 'Dr', 'Mrs', 'Mr', 'Miss'])],
            'last_name' => ['required', 'min:2', 'max:30', 'regex:/^[a-zA-Z\- ]{2,30}$/'],
            'first_name' => ['required', 'min:2', 'max:30', 'regex:/^[a-zA-Z\- ]{2,30}$/'],
            'middle_name' => ['nullable', 'min:2', 'max:30', 'regex:/^[a-zA-Z\- ]{2,30}$/'],
            'phone_1' => ['nullable', 'min:11', 'max:11', 'regex:/^[0][7-9][0-9]{9,9}$/'],
            'phone_2' => ['nullable', 'min:11', 'max:11', 'regex:/^[0][7-9][0-9]{9,9}$/'],
            'email' => ['required', 'email', 'unique:users,email'],
            'account_type' => ['required', Rule::in(['Admin', 'Dean'])]
            //default password is surname
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => 'Invalid input submitted',
                'errors' => $validator->errors(),
            ], Response::HTTP_EXPECTATION_FAILED);
        }


        $save = new User;
        $save->id = Str::uuid()->toString();
        $save->title = $request->title ?? null;
        $save->last_name = $request->last_name;
        $save->first_name = $request->first_name;
        $save->middle_name = $request->middle_name ?? null;
        $save->phone_1 = $request->phone_1 ?? null;
        $save->phone_2 = $request->phone_2 ?? null;
        $save->email = $request->email;
        $save->password = '12345'; //Str::random(18);
        $save->account_type = $request->account_type;
        $save->save();

        if (!$save) {
            return response([
                'status' => 'failed',
                'message' => 'Server error!'
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return response([
            'status' => 'success',
            'message' => 'User registered successfully'
        ], Response::HTTP_CREATED);
    } //register
}
