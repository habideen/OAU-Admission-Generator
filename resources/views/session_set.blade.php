@extends('layouts.panel')

@section('page_title', 'Activate Session')


@section('content')
  <div class="page-content">
    <div class="container-fluid">

      <!-- start page title -->
      <div class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Activate Session</h4>

            <div class="page-title-right">
              <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="/{{ account_type() }}/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active">Activate Session</li>
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

            <div class="col-sm-6 col-md-5 col-lg-4">
              <div class="mb-3">
                <label>Select session</label>
                <select class="form-select" name="session">
                  <option value=""></option>
                  @php
                    $date = date('Y', strtotime('+1 Years'));
                  @endphp
                  @for ($i = date('Y', strtotime('-3 Years')); $i < $date; $i++)
                    @php
                      $x = $i . '/' . ($i + 1);
                    @endphp ?>
                    <option value="{{ $x }}" @selected($active_session == $x)>{{ $x }}</option>
                  @endfor
                </select>
              </div>

              <x-form.button :isUpdate="Request::has('faculty_id')" updateText="Update Faculty" defaultText="Save Faculty" />
              <button type="submit" class="btn btn-primary waves-effect waves-light">Update</button>
            </div>
          </form>


          <h4 class="card-title mt-5">Active Session</h4>
          Active Session: {{ $active_session }}
        </div>
      </div>
      <!-- card -->
    </div>
    <!-- container-fluid -->
  </div>
  <!-- End Page-content -->
@endsection
