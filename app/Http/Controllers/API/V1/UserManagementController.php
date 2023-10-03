<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class UserManagementController extends Controller
{
    public function registerOrEdit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => ['nullable', 'exists:users,id'],
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
            'faculty_id' => ['nullable', 'exists:faculties,id'],
            'phone' => [
                'required', 'regex:/^[0][7-8][0-9]{9,9}$/',
                Rule::unique('users', 'phone_1')->ignore($request->user_id, 'id'),
            ],
            'account_type' => ['required', Rule::in(USER_TYPE)],
            'password' => ['nullable', 'string', 'min:8', Rule::requiredIf(!$request->user_id)]
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
        $save->faculty_id = $request->faculty_id;
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
    } //registerOrEdit


    public function listUsers(Request $request)
    {
        $users = User::select(
            'users.*',
            DB::raw('IF(users.account_disabled IS NOT NULL, "Disabled", NULL) AS isDisabled'),
            'faculties.faculty',
            'faculties.id AS faculty_id'
        )
            ->leftJoin('faculties', 'faculties.id', '=', 'users.faculty_id');

        if ($request->account_status == 'disabled') {
            $users = $users->where('users.account_disabled', 1);
        }

        return response([
            'status' => 'success',
            'message' => 'Retrieved successfully',
            'users' => $users->get()
        ]);
    } //listUsers


    public function disableOrEnable(Request $request)
    {
        if (!isPassword($request->password)) {
            return response([
                'status' => 'failed',
                'message' => 'Invalid password. Please try again.'
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (!User::where('id', $request->id)->first()) {
            return response([
                'status' => 'success',
                'message' => 'User does not exist.'
            ], Response::HTTP_EXPECTATION_FAILED);
        }

        if ($request->disable) {
            if (User::where('id', $request->id)->update(['account_disabled' => 1])) {
                return response([
                    'status' => 'success',
                    'message' => 'User disabled successfully.'
                ], Response::HTTP_OK);
            }
        } elseif ($request->enable) {
            if (User::where('id', $request->id)->update(['account_disabled' => null])) {
                return response([
                    'status' => 'success',
                    'message' => 'User enabled successfully.'
                ], Response::HTTP_OK);
            }
        }

        return response([
            'status' => 'failed',
            'message' => 'We could not precess your request. Please try again.'
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    } // disableOrEnable
}
