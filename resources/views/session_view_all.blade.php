@extends('layouts.panel')

@section('page_title', 'Dashboard')


@section('content')
  <div class="page-content">
    <div class="container-fluid">

      <!-- start page title -->
      <div class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">All Sessions</h4>

            <div class="page-title-right">
              <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="/{{ account_type() }}/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active">All Session</li>
              </ol>
            </div>
          </div>
        </div>
      </div>
      <!-- end page title -->

      <div class="card">
        <div class="card-body">
          <x-alert />

          <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
            <thead>
              <tr>
                <th>Session</th>
                <th>Is Active</th>
                <th>Created At</th>
                <th>Updated At</th>
              </tr>
            </thead>

            <tbody>
              @foreach ($sessions->data as $session)
                <tr>
                  <td>{{ $session->session }}</td>
                  <td>{{ $session->is_active ? 'Active' : '' }}</td>
                  <td>{{ date('d M, Y', strtotime($session->created_at)) }}</td>
                  <td>{{ date('d M, Y', strtotime($session->updated_at)) }}</td>
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

  <!-- Datatable init js -->
  <script>
    $(document).ready(function() {
      $("#datatable").DataTable({
        order: [
          [1, 'desc']
        ]
      });
    });
  </script>
@endsection
