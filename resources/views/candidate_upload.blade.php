@extends('layouts.panel')

@section('page_title', 'Candidate Upload')


@section('content')
  <div class="page-content">
    <div class="container-fluid">

      <!-- start page title -->
      <div class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Candidate Upload</h4>

            <div class="page-title-right">
              <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="/{{ account_type() }}/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active">Candidate Upload</li>
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

            <x-form.input name="candidates_file" label="Candidates File" type="file"
              parentClass="mb-4 col-md-5 col-sm-6" accept=".xls,.xlsx" required
              bottomInfo="Only excel file (xls, xlxs) is accepted.<br/>All the sheets in the file will be traversed." />

            <x-form.button defaultText="Upload Candidates" id="uploadBtn" />

          </form>
        </div>
      </div>
      <!-- card -->

      <div class="card">
        <div class="card-body">
          <div class="error"><x-alert /></div>

          <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
            <thead>
              <tr>
                <th>SN</th>
                <th>Reg. No.</th>
                <th>Fullname</th>
                <th>Gender</th>
                <th>State</th>
                <th>Subjects</th>
                <th>Course</th>
                <th>UTME Score</th>
                <th>O'level Score</th>
                <th>PUTME Score</th>
                <th>PUTME Screening</th>
                <th>Aggregate</th>
                <th>Session Uploaded</th>
                <th>Created At</th>
              </tr>
            </thead>

            <tbody>
              @php $c = 0; @endphp
              @foreach ($candidates as $candidate)
                <tr>
                  <td>{{ ++$c }}</td>
                  <td>{{ $candidate->rg_num }}</td>
                  <td>{{ $candidate->fullname }}</td>
                  <td>{{ $candidate->rg_sex }}</td>
                  <td>{{ $candidate->state_name }}</td>
                  <td>{{ $candidate->subject_code_1 }}, {{ $candidate->subject_code_2 }}, {{ $candidate->subject_code_3 }}
                  </td>
                  <td>{{ $candidate->course }}</td>
                  <td>{{ $candidate->utme_score }}</td>
                  <td>{{ $candidate->olevel_score }}</td>
                  <td>{{ $candidate->putme_score }}</td>
                  <td>{{ $candidate->putme_screening }}</td>
                  <td>{{ $candidate->aggregate }}</td>
                  <td>{{ $candidate->session_updated }}</td>
                  <td>{{ date('d M, Y', strtotime($candidate->created_at)) }}</td>
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
  <style>
    .dataTables_wrapper {
      width: 100% !important;
    }
  </style>
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
