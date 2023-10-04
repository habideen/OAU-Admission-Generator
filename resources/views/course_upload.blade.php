@extends('layouts.panel')

@section('page_title', 'Upload Course')


@section('content')
  <div class="page-content">
    <div class="container-fluid">

      <!-- start page title -->
      <div class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Upload Course</h4>

            <div class="page-title-right">
              <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="/{{ account_type() }}/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active">Upload Course</li>
              </ol>
            </div>
          </div>
        </div>
      </div>
      <!-- end page title -->

      <div class="card">
        <div class="card-body">
          <x-alert />

          <form method="post" enctype="multipart/form-data" id="uploadForm">
            @csrf

            <x-form.input name="course_file" label="Course File" type="file" accept=".xls,.xlsx" required
              bottomInfo="Only excel file (xls, xlxs) is accepted.<br/>All the sheets in the file will be traversed."
              parentClass="mb-4" />

            <x-form.button defaultText="Upload Course" id="uploadBtn" />

          </form>
        </div>
      </div>
      <!-- card -->
    </div>
    <!-- container-fluid -->
  </div>
  <!-- End Page-content -->
@endsection
