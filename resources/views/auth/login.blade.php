@extends('layouts.auth')

@section('page_title', 'Login')


@section('content')
  <div class="account-pages my-5 pt-sm-5 mb-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 col-xl-5">
          <div class="card overflow-hidden">
            <div class="bg-primary bg-soft">
              <div class="row">
                <div class="col-7">
                  <div class="text-primary p-4">
                    <h4 class="text-primary">OAU Admission Management</h4>
                    <p>Sign in to continue.</p>
                  </div>
                </div>
                <div class="col-5 align-self-end">
                  <img src="/assets/images/profile-img.png" alt="" class="img-fluid">
                </div>
              </div>
            </div>
            <div class="card-body pt-0">
              <div class="auth-logo">
                <div class="avatar-md profile-user-wid mb-4">
                  <span class="avatar-title rounded-circle bg-light">
                    <img src="assets/images/logo.png" alt="" class="rounded-circle" height="54">
                  </span>
                </div>
              </div>
              
              <x-alert/>
              
              <div class="p-2">
                <form class="form-horizontal" action="/login" method="post">
                  @csrf

                  <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" class="form-control" name="email" id="email" value="sup.admin@gmail.com" placeholder="Enter username">
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group auth-pass-inputgroup">
                      <input type="password" name="password" class="form-control" value="12345" placeholder="Enter password"
                        aria-label="Password" aria-describedby="password-addon">
                      <button class="btn btn-light " type="button" id="password-addon"><i
                          class="mdi mdi-eye-outline"></i></button>
                    </div>
                  </div>

                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember-check">
                    <label class="form-check-label" for="remember-check">
                      Remember me
                    </label>
                  </div>

                  <div class="mt-4 d-grid">
                    <button class="btn btn-primary waves-effect waves-light" type="submit">Log In</button>
                  </div>

                  <div class="mt-4 text-center mt-5">
                    <a href="auth-recoverpw.html" class="text-muted"><i class="mdi mdi-lock me-1"></i> Forgot your
                      password?</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
