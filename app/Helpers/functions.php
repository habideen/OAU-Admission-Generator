<?php

use App\Models\Session;
use App\Models\User;
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

// if (!function_exists('candidateInfo')) {
//   function candidateInfo($row)
//   {
//     $subject = str_replace(',,', ',', $row['subject_combo']);
    

//     if (
//       !preg_match('/^[2-9][0-9]{3,3}[0-9]{8,8}[a-zA-Z]{2,2}$/', $row['rg_num'])
//       || !preg_match('/^[a-zA-Z\-\_ ]{2,255}$/', $row['fullname'])
//       || !preg_match('/^[F]|[M]{1,1}$/', strtoupper($row['rg_sex']))
//       || !preg_match('/^[a-zA-Z\- ]$/', strtoupper($row['state_name']))
//       || !preg_match('/^[a-zA-Z\- ]$/', strtoupper($row['state_name']))
//     ) {
//     }
//   }
// }
