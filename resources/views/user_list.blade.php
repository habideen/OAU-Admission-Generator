@extends('layouts.panel')

@section('page_title', 'User List')


@section('content')
  <div class="page-content">
    <div class="container-fluid">

      <!-- start page title -->
      <div class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">User List</h4>

            <div class="page-title-right">
              <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="/{{ account_type() }}/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active">User List</li>
              </ol>
            </div>
          </div>
        </div>
      </div>
      <!-- end page title -->

      <div class="card">
        <div class="card-body">
          <x-alert />

          <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
            <thead>
              <tr>
                <th>Email</th>
                <th>Fullname</th>
                <th>Phone</th>
                <th>Account Type</th>
                <th>Faculty</th>
                <th>Is Disabled</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th></th>
              </tr>
            </thead>

            <tbody>
              @foreach ($users as $user)
                <tr>
                  <td>{{ $user->email }}</td>
                  <td>
                    {{ $user->title . ' ' . $user->first_name . ' ' . $user->middle_name . ' ' . strtoupper($user->last_name) }}
                  </td>
                  <td>{{ $user->phone_1 }}</td>
                  <td>{{ $user->account_type }}</td>
                  <td>{{ $user->faculty }}</td>
                  <td>{{ $user->isDisabled }}</td>
                  <td>{{ date('d M, Y', strtotime($user->created_at)) }}</td>
                  <td>{{ date('d M, Y', strtotime($user->updated_at)) }}</td>
                  <td>
                    <x-form.delete action="/user/delete" name="id" :value="$user->id" :text="$user->title .
                        ' ' .
                        $user->first_name .
                        ' ' .
                        $user->middle_name .
                        ' ' .
                        strtoupper($user->last_name)" />
                    <button type="button" class="btn btn-primary waves-effect waves-light ms-3" data-bs-toggle="modal"
                      data-bs-target="#updateUserModal" data-id="{{ $user->id }}" data-email="{{ $user->email }}"
                      data-title="{{ $user->title }}" data-first_name="{{ $user->first_name }}"
                      data-middle_name="{{ $user->middle_name }}" data-last_name="{{ $user->last_name }}"
                      data-phone_1="{{ $user->phone_1 }}"><i class="bx bxs-edit"></i></button>
                  </td>
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

  <!-- Datatable init js -->
  <script>
    $(document).ready(function() {
      function confirmDelete(course) {
        if (!confirm("Delete " + course + "?")) {
          event.preventDefault();
        }
      }
    });
  </script>
@endsection
