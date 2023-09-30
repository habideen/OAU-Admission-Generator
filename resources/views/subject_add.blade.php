@extends('layouts.panel')

@section('page_title', 'Add Subject')


@section('content')
  <div class="page-content">
    <div class="container-fluid">

      <!-- start page title -->
      <div class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Add Subject</h4>

            <div class="page-title-right">
              <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="/{{ account_type() }}/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active">Add Subject</li>
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

            {{-- <div class="row"> --}}
            <x-form.input name="subject_code" label="Subject Code" placeholder="e.g. MTH" type="text" :value="old('subject_code')"
              minlength="3" maxlength="3" pattern="^[a-zA-Z]{3,3}$" parentClass="mb-4 col-md-6" />

            <x-form.input name="subject" label="Subject" placeholder="e.g. Mathematics" type="text" :value="old('subject')"
              minlength="3" maxlength="100" pattern="^[a-zA-Z\-\(\) ]{3,100}$" parentClass="mb-4 col-md-6" />
            {{-- </div> --}}

            <x-form.button defaultText="Save Subject" />

          </form>
        </div>
      </div>
      <!-- card -->
    </div>
    <!-- container-fluid -->
  </div>
  <!-- End Page-content -->
@endsection
