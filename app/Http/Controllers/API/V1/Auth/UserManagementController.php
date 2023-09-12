<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserManagementController extends Controller
{
    public function disable(Request $request)
    {
        if (!isPassword($request->password)) {
            return response([
                'status' => 'failed',
                'message' => 'Invalid password. Please try again.'
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (User::where('id', $request->email)->update(['account_disabled' => 1])) {
            return response([
                'status' => 'success',
                'message' => 'User disabled successfully'
            ], Response::HTTP_OK);
        }

        return response([
            'status' => 'failed',
            'message' => 'User could not be disabled'
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    } // disable
}
