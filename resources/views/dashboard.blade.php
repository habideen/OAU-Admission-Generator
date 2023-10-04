@extends('layouts.panel')

@section('page_title', 'Dashboard')


@section('content')
  <div class="page-content">
    <div class="container-fluid">

      <!-- start page title -->
      <div class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Dashboard</h4>

            <div class="page-title-right">
              <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
              </ol>
            </div>

          </div>
        </div>
      </div>
      <!-- end page title -->

      <div class="row">
        <div class="col-md-4">
          <div class="card mini-stats-wid">
            <div class="card-body">
              <div class="d-flex">
                <div class="flex-grow-1">
                  <p class="text-muted fw-medium">Active Session</p>
                  <h4 class="mb-0">{{ activeSession() }}</h4>
                </div>

                <div class="flex-shrink-0 align-self-center">
                  <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                    <span class="avatar-title">
                      <i class="bx bx-copy-alt font-size-24"></i>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card mini-stats-wid">
            <div class="card-body">
              <div class="d-flex">
                <div class="flex-grow-1">
                  <p class="text-muted fw-medium">Candidates</p>
                  <h4 class="mb-0">{{ $totalCandidates }}</h4>
                </div>

                <div class="flex-shrink-0 align-self-center ">
                  <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                    <span class="avatar-title rounded-circle bg-primary">
                      <i class="bx bx-archive-in font-size-24"></i>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card mini-stats-wid">
            <div class="card-body">
              <div class="d-flex">
                <div class="flex-grow-1">
                  <p class="text-muted fw-medium">Total Admitted</p>
                  <h4 class="mb-0">{{ $totalAdmitted }}</h4>
                </div>

                <div class="flex-shrink-0 align-self-center">
                  <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                    <span class="avatar-title rounded-circle bg-primary">
                      <i class="bx bx-purchase-tag-alt font-size-24"></i>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- end row -->

      <div class="row">
        <div class="col-xl-4">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title mb-5">My Profile</h4>
              <div class="h6">
                <p><b>Fullname:</b>
                  {{ Auth::user()->title .
                      ' ' .
                      Auth::user()->first_name .
                      ' ' .
                      Auth::user()->middle_name .
                      ' ' .
                      strtoupper(Auth::user()->last_name) .
                      ' ' }}
                </p>
                <p><b>Email:</b> {{ Auth::user()->email }}</p>
                <p><b>Phone:</b> {{ Auth::user()->phone_1 }}</p>
                <p><b>Account type:</b> {{ Auth::user()->account_type }}</p>
                @if (Auth::user()->faculty_id)
                  <p><b>Account type:</b> {{ facultyName(Auth::user()->faculty_id) }}</p>
                @endif
              </div>
            </div>
          </div>
        </div>

        <div class="col-xl-8">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title mb-5">Admission Statistics</h4>
              <div>
                <div class="h5">
                  <div class="mb-1">Merit: {{ $totalMerit }}</div>
                  <div class="mb-1">Catchment: {{ $totalCatchment }}</div>
                  <div class="mb-1">ELDS: {{ $totalELDS }}</div>
                  <div class="mb-3">Discretion: {{ $totalDiscretion }}</div>

                  <div class="mb-1">Capacity: {{ $totalCapacity }}</div>
                  <div class="mb-3">Space Left:
                    {{ $totalCapacity - ($totalMerit + $totalCatchment + $totalELDS + $totalDiscretion) }}
                  </div>

                  <div class="mb-1">Candidate Application: {{ $totalCandidates }}</div>
                  <div class="mb-1">Candidate Admitted:
                    {{ $totalMerit + $totalCatchment + $totalELDS + $totalDiscretion }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- end row -->
    </div>
    <!-- container-fluid -->
  </div>
  <!-- End Page-content -->
@endsection


@section('script')
  <!-- apexcharts -->
  <script src="/assets/libs/apexcharts/apexcharts.min.js"></script>

  <!-- dashboard init -->
  <script src="/assets/js/pages/dashboard.init.js"></script>
@endsection
