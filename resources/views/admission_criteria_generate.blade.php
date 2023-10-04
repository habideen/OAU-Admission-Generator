@extends('layouts.panel')

@section('page_title', 'Generate Admission')


@section('content')
  <div class="page-content">
    <div class="container-fluid">

      <!-- start page title -->
      <div class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Generate Admission</h4>

            <div class="page-title-right">
              <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="/{{ account_type() }}/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active">Generate Admission</li>
              </ol>
            </div>
          </div>
        </div>
      </div>
      <!-- end page title -->

      <div class="card">
        <div class="card-body">
          <x-alert />

          <div class="row justify-content-between">
            @if (in_array(Auth::user()->account_type, ['Admin', 'Super Admin']))
              <div class="mb-4 col-md-5">
                <a href="/{{ account_type() }}/admission/calculate" class="btn btn-success btn-lg disableBtn"
                  aria-disabled="true">Generate Admission List</a>
              </div>
              <div class="col-12 mt-2 mb-2">
                <hr>
              </div>
            @endif

            <div class="mb-4 col-12">
              <div class="h5 text-muted mb-3">Filter admission list</div>
              <form>
                {{-- @csrf --}}

                <div class="row">
                  @if (Auth::user()->account_type == 'Super Admin' || Auth::user()->account_type == 'Admin')
                    <x-form.select name="faculty" label="Faculty" :selected="old('faculty')" optionsType="object" :options="$faculties"
                      objKey="id" objValue="faculty" parentClass="mb-4 col-lg-3 col-md-4 col-sm-6" firstOption="All"
                      firstOptionValue="-" />
                  @endif

                  <x-form.select name="course" label="Course" :selected="old('course')" optionsType="object" :options="$courses"
                    objKey="course" objValue="course" parentClass="mb-4 col-lg-3 col-md-4 col-sm-6" firstOption="All"
                    firstOptionValue="-" />

                  <x-form.select name="session" label="Session" :selected="old('session')" optionsType="object" :options="$sessions"
                    objKey="session" objValue="session" parentClass="mb-4 col-lg-3 col-md-4 col-sm-6" />
                </div>

                <x-form.button defaultText="Fetch" />
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- card -->

      <div class="card">
        <div class="card-body">
          @if (Request::get('faculty') || Request::get('course') || Request::get('session'))
            <div class="mb-3">
              <div>Filter options:</div>
              @if (Request::get('faculty'))
                <div>Faculty: {{ facultyName(Request::get('faculty')) }}</div>
              @endif
              @if (Request::get('course'))
                <div>Course: {{ Request::get('course') }}</div>
              @endif
              @if (Request::get('session'))
                <div>Session: {{ Request::get('session') }}</div>
              @endif
              <div class="mt-2 mb-2">
                <hr>
              </div>
            </div>
          @endif
          <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
            <thead>
              <tr>
                <th>SN</th>
                <th>Reg. No.</th>
                <th>Fullname</th>
                <th>Gender</th>
                <th>Course</th>
                <th>Category</th>
                <th>Aggregate</th>
                <th>Session Uploaded</th>
                <th>Date Created</th>
              </tr>
            </thead>

            <tbody>
              @php $c = 0; @endphp
              @foreach ($admissions as $candidate)
                <tr>
                  <td>{{ ++$c }}</td>
                  <td>{{ $candidate->rg_num }}</td>
                  <td>{{ $candidate->fullname }}</td>
                  <td>{{ $candidate->rg_sex }}</td>
                  <td>{{ $candidate->course }}</td>
                  <td>{{ $candidate->category }}</td>
                  <td>{{ $candidate->aggregate }}</td>
                  <td>{{ $candidate->session_updated }}</td>
                  <td>{{ date('d M, Y', strtotime($candidate->updated_at)) }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <!-- card -->
    </div>
    <!-- container-fluid -->
  </div>
  <!-- End Page-content -->
@endsection



@section('style')
  <!-- DataTables -->
  <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
  <link href="/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
@endsection



@section('script')
  <!-- Required datatable js -->
  <script src="/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
  <!-- Buttons examples -->
  <script src="/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
  <script src="/assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
  <script src="/assets/libs/jszip/jszip.min.js"></script>
  <script src="/assets/libs/pdfmake/build/pdfmake.min.js"></script>
  <script src="/assets/libs/pdfmake/build/vfs_fonts.js"></script>
  <script src="/assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
  <script src="/assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
  <script src="/assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>

  <!-- Responsive examples -->
  <script src="/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
  <script src="/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>

  <!-- Datatable init js -->
  <script src="/assets/js/pages/datatables.init.js"></script>

  <script>
    $("#uploadForm").submit(function() {
      $("#uploadBtn").prop("disabled", true);
    });
  </script>
@endsection
