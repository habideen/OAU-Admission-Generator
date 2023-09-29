@extends('layouts.panel')

@section('page_title', 'Faculty List')


@section('content')
  <div class="page-content">
    <div class="container-fluid">

      <!-- start page title -->
      <div class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Faculty List</h4>

            <div class="page-title-right">
              <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="/{{ account_type() }}/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active">Faculty List</li>
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
                <th>ID</th>
                <th>Faculty</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th></th>
              </tr>
            </thead>

            <tbody>
              @foreach ($faculties->data as $faculty)
                <tr>
                  <td>{{ $faculty->id }}</td>
                  <td>{{ $faculty->faculty }}</td>
                  <td>{{ date('d M, Y', strtotime($faculty->created_at)) }}</td>
                  <td>{{ date('d M, Y', strtotime($faculty->updated_at)) }}</td>
                  <td>
                    <x-form.delete action="/faculty/delete" name="id" :value="$faculty->id" />
                    <button type="button" class="btn btn-primary waves-effect waves-light ms-3" data-bs-toggle="modal"
                      data-bs-target="#updateFacultyModal" data-faculty_id="{{ $faculty->id }}"
                      data-faculty="{{ $faculty->faculty }}"><i class="bx bxs-edit"></i></button>
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


  <div class="modal fade" id="updateFacultyModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-labelledby="updateFacultyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updateFacultyModalLabel">Update Faculty</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <form method="post" action="/{{ account_type() }}/faculty/edit">
            @csrf
            <input type="hidden" name="faculty_id" id="faculty_id">
            <input type="hidden" name="faculty_old" id="faculty_old">
            <div class="mb-3">Current Name: <span class="d-inline-block" id="current_text"></span></div>
            <x-form.input name="faculty" label="New Name" type="text" parentClass="mb-4" />

            <div class="d-flex mt-5">
              <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
              <div class="ms-auto">
                <button type="submit" class="btn btn-primary">Save Changes</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Static Backdrop Modal -->
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
          [1, 'asc']
        ]
      });
    });

    function confirmDelete() {
      if (!confirm("Delete faculty?")) {
        event.preventDefault();
      }
    }


    $('#updateFacultyModal').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget)
      var recipient = button.data('faculty_id')

      var modal = $(this)
      modal.find('#faculty_id').val({!! old('faculty_id') ?? "button.data('faculty_id')" !!})
      modal.find('#faculty').val({!! old('faculty') ?? "button.data('faculty')" !!})
      //#faculty_old: does not do anything. Used to retain original text in case of error
      modal.find('#faculty_old').val({!! old('faculty_old') ?? "button.data('faculty')" !!})
      modal.find('#current_text').text({!! old('faculty_old') ?? "button.data('faculty')" !!})
    })
  </script>
@endsection
