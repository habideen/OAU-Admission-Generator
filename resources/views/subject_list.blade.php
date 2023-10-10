@extends('layouts.panel')

@section('page_title', 'Subject List')


@section('content')
  <div class="page-content">
    <div class="container-fluid">

      <!-- start page title -->
      <div class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Subject List</h4>

            <div class="page-title-right">
              <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="/{{ account_type() }}/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active">Subject List</li>
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
                <th>Subject Code</th>
                <th>Subject</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th></th>
              </tr>
            </thead>

            <tbody>
              @foreach ($subjects->data as $subject)
                <tr>
                  <td>{{ $subject->subject_code }}</td>
                  <td>{{ $subject->subject }}</td>
                  <td>{{ date('d M, Y', strtotime($subject->created_at)) }}</td>
                  <td>{{ date('d M, Y', strtotime($subject->updated_at)) }}</td>
                  <td>
                    <x-form.delete action="/subject/delete" name="subject_code" :value="$subject->subject_code" :text="$subject->subject" />
                    <button type="button" class="btn btn-primary waves-effect waves-light ms-3" data-bs-toggle="modal"
                      data-bs-target="#updateSubjectModal" data-subject_code="{{ $subject->subject_code }}"
                      data-subject="{{ $subject->subject }}"><i class="bx bxs-edit"></i></button>
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


  <div class="modal fade" id="updateSubjectModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-labelledby="updateSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updateSubjectModalLabel">Update Faculty</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <form method="post" action="/{{ account_type() }}/subject/edit">
            @csrf

            <div class="error"><x-alert /></div>

            <input type="hidden" name="old_subject_code" id="old_subject_code">
            <input type="hidden" name="old_subject" id="old_subject">

            <div class="mb-1">Current Subject Code: <span class="d-inline-block" id="current_subject_code"></span></div>
            <div class="mb-3">Current Subject: <span class="d-inline-block" id="current_subject"></span></div>

            <x-form.input name="new_subject_code" label="New Subject Code" type="text" minlength="3" maxlength="3"
              pattern="^[a-zA-Z]{3,3}$" parentClass="mb-4" />

            <x-form.input name="subject" label="Subject" type="text" minlength="3" maxlength="100"
              pattern="^[a-zA-Z\-\(\) ]{3,100}$" parentClass="mb-4" />

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

    function confirmDelete(faculty) {
      if (!confirm("Delete " + faculty + "?")) {
        event.preventDefault();
      }
    }


    $('#updateSubjectModal').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget)
      var modal = $(this)

      if (button.data('subject_code')) {
        $('.error').text("") //clear error if is set
        modal.find('#new_subject_code').removeClass('is-invalid')
        modal.find('#subject').removeClass('is-invalid')
        modal.find('#new_subject_code').val(button.data('subject_code'))
        modal.find('#subject').val(button.data('subject'))
        //#course_old: does not do anything. Used to retain original text in case of error
        modal.find('#old_subject_code').val(button.data('subject_code'))
        modal.find('#old_subject').val(button.data('subject'))
        modal.find('#current_subject_code').text(button.data('subject_code'))
        modal.find('#current_subject').text(button.data('subject'))
      }
      //
      else if ('{!! old('old_subject_code') !!}' != '') {
        modal.find('#new_subject_code').val("{!! old('new_subject_code') !!}")
        modal.find('#subject').val("{!! old('subject') !!}")
        //#course_old: does not do anything. Used to retain original text in case of error
        modal.find('#old_subject_code').val("{!! old('old_subject_code') !!}")
        modal.find('#old_subject').val("{!! old('old_subject') !!}")
        modal.find('#current_subject_code').text("{!! old('current_subject_code') !!}")
        modal.find('#current_subject').text("{!! old('current_subject') !!}")
      }
    })
  </script>

  <script>
    $(document).ready(function() {
      @if (old('old_subject_code'))
        $('#updateSubjectModal').modal('show');
      @endif
    });
  </script>
@endsection
