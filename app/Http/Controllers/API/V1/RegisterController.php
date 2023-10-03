<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', Rule::in(USER_TITLE)],
            'last_name' => [
                'required', 'min:2', 'regex:/^[a-zA-Z\-]{2,70}$/'
            ],
            'first_name' => [
                'required', 'min:1', 'regex:/^[a-zA-Z\-]{1,70}$/'
            ],
            'middle_name' => [
                'nullable', 'min:1', 'regex:/^[a-zA-Z\-]{1,70}$/'
            ],
            'email' => [
                'nullable', 'email', 'max:255',
                Rule::unique('users')->ignore($request->user_id, 'id'),
            ],
            'phone' => [
                'required', 'regex:/^[0][7-8][0-9]{9,9}$/',
                Rule::unique('users', 'phone_1')->ignore($request->user_id, 'id'),
            ],
            'account_type' => ['required', Rule::in(USER_TYPE)],
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => 'Invalid input submitted',
                'errors' => $validator->errors(),
            ], Response::HTTP_EXPECTATION_FAILED);
        }

        if ($request->user_id) {
            $save = User::find($request->user_id);
        } else {
            $save = new User;
            $save->id = Str::uuid()->toString();
            $save->password = Hash::make($request->phone);
            $save->email_verified_at = now();
        }

        $save->title = $request->title;
        $save->last_name = $request->last_name;
        $save->first_name = $request->first_name;
        $save->middle_name = $request->middle_name;
        $save->account_type = ucwords($request->account_type);
        $save->email = strtolower($request->email);
        $save->phone_1 = $request->phone;
        $save->save();

        if (!$save) {
            return response([
                'status' => 'failed',
                'message' => 'Server error!'
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return response([
            'status' => 'success',
            'message' => 'User added successfully'
        ], Response::HTTP_CREATED);
    } //register
}
