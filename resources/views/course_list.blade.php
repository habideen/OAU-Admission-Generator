@extends('layouts.panel')

@section('page_title', 'Course List')


@section('content')
  <div class="page-content">
    <div class="container-fluid">

      <!-- start page title -->
      <div class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Course List</h4>

            <div class="page-title-right">
              <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="/{{ account_type() }}/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active">Course List</li>
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
                <th>ID</th>
                <th>Faculty</th>
                <th>Course</th>
                <th>Subject Combination</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th></th>
              </tr>
            </thead>

            <tbody>
              @foreach ($courses->data as $course)
                <tr>
                  <td>{{ $course->course_id }}</td>
                  <td>{{ $course->faculty }}</td>
                  <td>{{ $course->course }}</td>
                  <td>
                    @php
                      $c = 0;
                    @endphp
                    @if ($course->subject_1)
                      {{ ++$c . ': ' . $course->subject_1 }} <br>
                    @endif
                    @if ($course->subject_2)
                      {{ ++$c . ': ' . $course->subject_2 }} <br>
                    @endif
                    @if ($course->subject_3)
                      {{ ++$c . ': ' . $course->subject_3 }} <br>
                    @endif
                    @if ($course->subject_4)
                      {{ ++$c . ': ' . $course->subject_4 }} <br>
                    @endif
                    @if ($course->subject_5)
                      {{ ++$c . ': ' . $course->subject_5 }} <br>
                    @endif
                    @if ($course->subject_6)
                      {{ ++$c . ': ' . $course->subject_6 }} <br>
                    @endif
                    @if ($course->subject_7)
                      {{ ++$c . ': ' . $course->subject_7 }} <br>
                    @endif
                    @if ($course->subject_8)
                      {{ ++$c . ': ' . $course->subject_8 }}
                    @endif
                  </td>
                  <td>{{ date('d M, Y', strtotime($course->created_at)) }}</td>
                  <td>{{ date('d M, Y', strtotime($course->updated_at)) }}</td>
                  <td>
                    <x-form.delete action="/course/delete" name="course_id" :value="$course->course_id" :text="$course->course" />
                    <button type="button" class="btn btn-primary waves-effect waves-light ms-3" data-bs-toggle="modal"
                      data-bs-target="#updateCourseModal" data-course_id="{{ $course->course_id }}"
                      data-course="{{ $course->course }}" data-faculty_id="{{ $course->faculty_id }}"
                      data-subject_code_1="{{ $course->subject_code_1 }}"
                      data-subject_code_2="{{ $course->subject_code_2 }}"
                      data-subject_code_3="{{ $course->subject_code_3 }}"
                      data-subject_code_4="{{ $course->subject_code_4 }}"
                      data-subject_code_5="{{ $course->subject_code_5 }}"
                      data-subject_code_6="{{ $course->subject_code_6 }}"
                      data-subject_code_7="{{ $course->subject_code_7 }}"
                      data-subject_code_8="{{ $course->subject_code_8 }}"><i class="bx bxs-edit"></i></button>
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



  <div class="modal fade" id="updateCourseModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-labelledby="updateCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updateCourseModalLabel">Update Course</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <form method="post" action="/{{ account_type() }}/course/edit">
            @csrf

            <div class="error"><x-alert /></div>

            <input type="hidden" name="course_id" id="course_id">
            <input type="hidden" name="course_old" id="course_old">
            <div class="mb-3 h3">Current Name: <span class="d-inline-block" id="current_text"></span></div>

            @include('components.course_form')

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

    $('#updateCourseModal').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget)
      var recipient = button.data('faculty_id')

      var modal = $(this)

      if (button.data('faculty_id')) {
        $('.error').text("") //clear error if is set
        modal.find('#course_id').val(button.data('course_id'))
        modal.find('#course').val(button.data('course'))
        //#course_old: does not do anything. Used to retain original text in case of error
        modal.find('#course_old').val(button.data('course'))
        modal.find('#current_text').text(button.data('course'))
        modal.find('#faculty_id').val(button.data('faculty_id'))
        modal.find('#subject_code_1').val(button.data('subject_code_1'))
        modal.find('#subject_code_2').val(button.data('subject_code_2'))
        modal.find('#subject_code_3').val(button.data('subject_code_3'))
        modal.find('#subject_code_4').val(button.data('subject_code_4'))
        modal.find('#subject_code_5').val(button.data('subject_code_5'))
        modal.find('#subject_code_6').val(button.data('subject_code_6'))
        modal.find('#subject_code_7').val(button.data('subject_code_7'))
        modal.find('#subject_code_8').val(button.data('subject_code_8'))
      }
      //
      else if ('{!! old('course_id') !!}' != '') {
        modal.find('#course_id').val("{!! old('course_id') !!}")
        modal.find('#course').val("{!! old('course') !!}")
        //#course_old: does not do anything. Used to retain original text in case of error
        modal.find('#course_old').val("{!! old('course_old') !!}")
        modal.find('#current_text').text("{!! old('course_old') !!}")
        modal.find('#faculty_id').val("{!! old('faculty_id') !!}")
        modal.find('#subject_code_1').val("{!! old('subject_code_1') !!}")
        modal.find('#subject_code_2').val("{!! old('subject_code_2') !!}")
        modal.find('#subject_code_3').val("{!! old('subject_code_3') !!}")
        modal.find('#subject_code_4').val("{!! old('subject_code_4') !!}")
        modal.find('#subject_code_5').val("{!! old('subject_code_5') !!}")
        modal.find('#subject_code_6').val("{!! old('subject_code_6') !!}")
        modal.find('#subject_code_7').val("{!! old('subject_code_7') !!}")
        modal.find('#subject_code_8').val("{!! old('subject_code_8') !!}")
      }
    })
  </script>

  <script>
    $(document).ready(function() {
      @if (old('course_id'))
        $('#updateCourseModal').modal('show');
      @endif
    });
  </script>
@endsection
