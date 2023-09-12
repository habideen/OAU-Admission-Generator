<?php

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