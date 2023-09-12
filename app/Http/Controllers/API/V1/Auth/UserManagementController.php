<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserManagementController extends Controller
{
    public function disableOrEnable(Request $request)
    {
        if (!isPassword($request->password)) {
            return response([
                'status' => 'failed',
                'message' => 'Invalid password. Please try again.'
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (!User::where('email', $request->email)->first()) {
            return response([
                'status' => 'success',
                'message' => 'Email is invalid.'
            ], Response::HTTP_EXPECTATION_FAILED);
        }

        if ($request->disable) {
            if (User::where('email', $request->email)->update(['account_disabled' => 1])) {
                return response([
                    'status' => 'success',
                    'message' => 'User disabled successfully.'
                ], Response::HTTP_OK);
            }
        } elseif ($request->enable) {
            if (User::where('email', $request->email)->update(['account_disabled' => null])) {
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



    public function delete(Request $request)
    {
        if (!isPassword($request->password)) {
            return response([
                'status' => 'failed',
                'message' => 'Invalid password. Please try again.'
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (!User::where('email', $request->email)->first()) {
            return response([
                'status' => 'success',
                'message' => 'Email is invalid.'
            ], Response::HTTP_EXPECTATION_FAILED);
        }

        if (User::where('email', $request->email)->delete()) {
            return response([
                'status' => 'success',
                'message' => 'User deleted successfully.'
            ], Response::HTTP_OK);
        }

        return response([
            'status' => 'failed',
            'message' => 'We could not precess your request. Please try again.'
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    } //delete



    public function listUsers(Request $request)
    {
        $pag = $request->pagination && ctype_digit($request->pagination) ? $request->pagination : PAGINATION;

        if ($request->account_type && in_array($request->account_type, USER_TYPE)) {
            $users = User::where('account_type', $request->account_type);
        } else {
            $users = User::whereNotNull('created_at');
        }

        if ($request->account_disabled && $request->account_disabled == '1') {
            $users = User::where('account_disabled', 1);
        }

        

        $users = $users->paginate($pag);

        return response([
            'status' => 'success',
            'message' => 'Retrieved successfully',
            'users' => $users
        ]);
    } //listUsers
}
