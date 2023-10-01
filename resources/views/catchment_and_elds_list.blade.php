@extends('layouts.panel')

@section('page_title', $type . ' List')


@section('content')
  <div class="page-content">
    <div class="container-fluid">

      <!-- start page title -->
      <div class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ $type }} List</h4>

            <div class="page-title-right">
              <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="/{{ account_type() }}/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active">{{ $type }} List</li>
              </ol>
            </div>
          </div>
        </div>
      </div>
      <!-- end page title -->

      <div class="card">
        <div class="card-body">
          <div class="error"><x-alert /></div>

          <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
            <thead>
              <tr>
                <th>State</th>
                <th>Session</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th></th>
              </tr>
            </thead>

            <tbody>
              @foreach ($rows as $row)
                <tr>
                  <td>{{ $row->state }}</td>
                  <td>{{ $row->session_updated }}</td>
                  <td>{{ date('d M, Y', strtotime($row->created_at)) }}</td>
                  <td>{{ date('d M, Y', strtotime($row->updated_at)) }}</td>
                  <td>
                    <x-form.delete action="/{{ strtolower($type) }}/delete" name="id" :value="$row->id"
                      :text="$row->state" />
                    <button type="button" class="btn btn-primary waves-effect waves-light ms-3" data-bs-toggle="modal"
                      data-bs-target="#updateModal"
                      data-{{ $type == 'Catchment' ? 'catchment_id' : 'elds_id' }}="{{ $row->id }}"
                      data-state_id="{{ $row->state_id }}" data-state="{{ $row->state }}"><i
                        class="bx bxs-edit"></i></button>
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



  <div class="modal fade" id="updateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updateModalLabel">Update {{ $type == 'Catchment' ? 'Catchment' : 'ELDS' }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <form method="post" action="/{{ account_type() }}/{{ $type == 'Catchment' ? 'catchment' : 'elds' }}/edit">
            @csrf

            <div class="error"><x-alert /></div>

            @if ($type == 'Catchment')
              <input type="hidden" name="catchment_id" id="catchment_id">
            @else
              <input type="hidden" name="elds_id" id="elds_id">
            @endif

            <input type="hidden" name="state_old" id="state_old">
            <div class="mb-3 h3">Current Name: <span class="d-inline-block" id="current_text"></span></div>

            <x-form.select name="state_id" label="States" :selected="old('state_id')" optionsType="object" :options="$states"
              objKey="id" objValue="name" requirZZZed parentClass="mb-4" />

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

    function confirmDelete(course) {
      if (!confirm("Delete " + course + "?")) {
        event.preventDefault();
      }
    }

    $('#updateModal').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget)

      var modal = $(this)

      if (button.data('state_id')) {
        $('.error').text("") //clear error if is set
        @if ($type == 'Catchment')
          modal.find('#catchment_id').val(button.data('catchment_id'))
        @else
          modal.find('#elds_id').val(button.data('elds_id'))
        @endif
        //#course_old: does not do anything. Used to retain original text in case of error
        modal.find('#current_text').text(button.data('state'))
        modal.find('#state_old').val(button.data('state'))
        modal.find('#state_id').val(button.data('state_id'))
      }
      //
      else if ('{!! old('state_old') !!}' != '') {
        @if ($type == 'Catchment')
          modal.find('#catchment_id').val("{!! old('catchment_id') !!}")
        @else
          modal.find('#elds_id').val("{!! old('elds_id') !!}")
        @endif
        //#course_old: does not do anything. Used to retain original text in case of error
        modal.find('#current_text').text("{!! old('state_old') !!}")
        modal.find('#state_old').val("{!! old('state_old') !!}")
        modal.find('#state_id').val("{!! old('state_id') !!}")
      }
    })
  </script>

  <script>
    $(document).ready(function() {
      @if (old('catchment_id') || old('elds_id'))
        $('#updateModal').modal('show');
      @endif
    });
  </script>
@endsection
