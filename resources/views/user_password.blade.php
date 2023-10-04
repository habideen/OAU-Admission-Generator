@extends('layouts.panel')

@section('page_title', 'Change Password')


@section('content')
  <div class="page-content">
    <div class="container-fluid">

      <!-- start page title -->
      <div class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Change Password</h4>

            <div class="page-title-right">
              <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="/{{ account_type() }}/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active">Change Password</li>
              </ol>
            </div>
          </div>
        </div>
      </div>
      <!-- end page title -->

      <div class="card">
        <div class="card-body">
          <x-alert />

          <form method="post">
            @csrf

            <x-form.input name="current_password" label="Current Password" type="password" required
              parentClass="col-sm-5 col-md-4 col-lg-3 mb-5" placeholder="Enter current password" />
            <x-form.input name="password" label="New Password" type="password" bottomInfo="8 characteres or more" required
              parentClass="col-sm-5 col-md-4 col-lg-3 mb-4" placeholder="Enter new password" minlength="8" />
            <x-form.input name="password_confirmation" label="Confirm Password" type="password" required
              parentClass="col-sm-5 col-md-4 col-lg-3 mb-4" placeholder="Enter new password again" />

            <x-form.button defaultText="Update Password" />

          </form>
        </div>
      </div>
      <!-- card -->
    </div>
    <!-- container-fluid -->
  </div>
  <!-- End Page-content -->
@endsection


@section('script')
  <script>
    // Get references to the password and password_confirmation input fields
    const passwordInput = document.getElementById('password');
    const passwordConfirmationInput = document.getElementById('password_confirmation');

    passwordInput.addEventListener('input', function() {
      // Check if password and password_confirmation match
      if (passwordInput.value.length >= 8) {
        // If they don't match, add the 'is-invalid' class
        passwordInput.classList.remove('is-invalid');
        passwordInput.classList.add('is-valid');
      } else {
        // If they match, remove the 'is-invalid' class
        passwordInput.classList.remove('is-valid');
        passwordInput.classList.add('is-invalid');
      }
    })

    // Add an input event listener to the password_confirmation field
    passwordConfirmationInput.addEventListener('input', function() {
      // Check if password and password_confirmation match
      if (passwordInput.value !== passwordConfirmationInput.value) {
        // If they don't match, add the 'is-invalid' class
        passwordConfirmationInput.classList.add('is-invalid');
      } else {
        // If they match, remove the 'is-invalid' class
        passwordConfirmationInput.classList.remove('is-invalid');
        passwordConfirmationInput.classList.add('is-valid');
      }
    });
  </script>
@endsection
