<?php

use App\Models\Course;
use App\Models\Session;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

if (!function_exists('isPassword')) {
  function isPassword($password)
  {
    return Hash::check(
      $password,
      User::select('password')->where('id', Auth::user()->id)->first()->password
    );
  }
}

if (!function_exists('activeSession')) {
  function activeSession()
  {
    return Session::select('session')
      ->whereNotNull('is_active')->first()->session ?? null;
  }
}

if (!function_exists('canDownload')) {
  function canDownload(Request $request)
  {
    if (Auth::user()->account_type == 'Super Admin' || Auth::user()->account_type == 'Admin') {
      return true;
    }

    if ($request->type != 'All') {
      $faculty_id = Course::select('faculty_id')->first();

      if ($faculty_id && $faculty_id->faculty_id == Auth::user()->faculty_id) {
        return true;
      }
    }

    return false;
  }
}
