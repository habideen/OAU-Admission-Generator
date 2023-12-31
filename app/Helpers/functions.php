<?php

use App\Models\Course;
use App\Models\EmailVerification;
use App\Models\Faculty;
use App\Models\Session;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session as FacadesSession;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;

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
      $faculty_id = Course::select('course')
        ->where('course', $request->type)
        ->where('faculty_id', Auth::user()->faculty_id)
        ->first();

      if ($faculty_id) {
        return true;
      }
    }

    return false;
  }
}

/**
 * account_type - get account account type
 * description - used to control url. this is also used in middleware as well
 * 
 * return - lower case account type
 */
if (!function_exists('account_type')) {
  function account_type()
  {
    if (Auth::user()) {
      $account_type = str_replace(' ', '', Auth::user()->account_type);
      return strtolower($account_type);
    }
  }
}



if (!function_exists('auth_messages')) {
  function auth_messages($message)
  {
    $messages = [
      'guest_reg_error' => 'You are not authorized to registered as an admin.',
      'registration' => 'Account registration was successful.',
      'login_error' => 'Login credentials is incorrect.',
      'throttle_message' => 'Too many attempts. Try again after 1 minute.',
      'verify_email' => 'This email is not verified.'
    ];

    return (array_key_exists($message, $messages)) ? $messages[$message] : '';
  }
}





if (!function_exists('verifyEmail')) {
  function verifyEmail(String $email)
  {
    $token = Str::random(100);

    $save = new EmailVerification;
    $save->id = Str::uuid()->toString();
    $save->user_email = $email;
    $save->code = Hash::make($token);
    $save->save();

    $verification_link = url('/verify_email/' . $save->id . '/' . $token);

    $name = User::select(
      'last_name',
      'first_name',
      'middle_name'
    )
      ->where('email', $email)->first();

    return dispatch(new VerifyEmail(
      [
        'verification_link' => $verification_link,
        'email' => $email,
        'fullname' => $name->last_name . ' ' . $name->first_name . ' ' . $name->middle_name,
      ]
    ));
  }
}





if (!function_exists('apiResponse')) {
  function apiResponse($api)
  {
    if ($api->status != 'success') {
      return redirect()->back()->with(
        (array) $api
      )->withErrors($api->errors ?? null)
        ->withInput();
    }

    return redirect()->back()->with(
      (array) $api
    );
  }
}





if (!function_exists('facultyName')) {
  function facultyName($id)
  {
    return Faculty::select('faculty')->where('id', $id)->first()->faculty ?? null;
  }
}





if (!function_exists('unadmittedList')) {
  function unadmittedList(array $admissionList, Collection $candidates)
  {
    $admissionList = collect($admissionList);

    return $candidates->whereNotIn(
      'rg_num',
      $admissionList->pluck('rg_num')
    );
  }
}





if (!function_exists('sessionReport')) {
  function sessionReport()
  {
    $report = FacadesSession::has('report_failed') ? FacadesSession::get('report_failed') : '';
    $count = (int) FacadesSession::get('success_count')
      . ' of '
      . (FacadesSession::get('success_count') + FacadesSession::get('failed_count'))
      . ' uploaded. &nbsp;&nbsp;&nbsp;'
      . (int) FacadesSession::get('failed_count') . ' failed.';

    $report = $report ?
      $report . '<br><br>' . $count
      : $count;

    return $report;
  }
}
