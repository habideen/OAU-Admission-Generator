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
                    @if (Auth::user()->id != $user->id)
                      <x-form.delete action="/user/delete" name="id" :value="$user->id" :text="$user->title .
                          ' ' .
                          $user->first_name .
                          ' ' .
                          $user->middle_name .
                          ' ' .
                          strtoupper($user->last_name)" />
                      <button type="button" class="btn btn-primary waves-effect waves-light ms-3" data-bs-toggle="modal"
                        data-bs-target="#updateUserModal" 
                        data-user_id="{{ $user->id }}" 
                        data-email="{{ $user->email }}"                      
                        data-title="{{ $user->title }}" 
                        data-first_name="{{ $user->first_name }}"                      
                        data-middle_name="{{ $user->middle_name }}" 
                        data-last_name="{{ $user->last_name }}"
                        data-phone="{{ $user->phone_1 }}"
                        data-account_type="{{ $user->account_type }}"
                        data-faculty_id="{{ $user->faculty_id }}"><i class="bx bxs-edit"></i></button>
                        
                      <button type="button" class="btn btn-primary waves-effect waves-light ms-3" data-bs-toggle="modal"
                        data-bs-target="#changPasswordModal" 
                        data-user_id="{{ $user->id }}" 
                        data-email="{{ $user->email }}"                      
                        data-fullname="{{ $user->title . ' ' . $user->first_name . ' ' . $user->middle_name . ' ' . strtoupper($user->last_name) }}"
                        ><i class="bx bx-key"></i></button>
                        
                      @if ($user->isDisabled)
                        <a href="/{{account_type()}}/user/disable_or_enable?user_id={{ $user->id }}&new_status=enable&fullname={{ urlencode($user->title . ' ' . $user->first_name . ' ' . $user->middle_name . ' ' . strtoupper($user->last_name)) }}" 
                          class="btn btn-success ms-3">Enable</a>
                      @else
                        <a href="/{{account_type()}}/user/disable_or_enable?user_id={{ $user->id }}&new_status=disable&fullname={{ urlencode($user->title . ' ' . $user->first_name . ' ' . $user->middle_name . ' ' . strtoupper($user->last_name)) }}" 
                          class="btn btn-danger ms-3">Disable</a>
                      @endif
                    @endif
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



  <div class="modal fade" id="changPasswordModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  role="dialog" aria-labelledby="changPasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="changPasswordModalLabel">Update Password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <form method="post" action="/{{ account_type() }}/user/password">
          @csrf

          <div class="error"><x-alert /></div>

          <input type="hidden" name="user_id" id="user_id">
          <input type="hidden" name="email_old" id="email_old">
          <input type="hidden" name="fullname" id="fullname">
          <div class="mb-3 h5">Fullname: <span id="fullname_text"></span></div>
          <div class="mb-3 h5">Current Email: <span id="current_email"></span></div>

          <x-form.input name="password" label="Password *" type="text" parentClass="col-sm-6 mb-4"
            placeholder="e.g. => 8 chars" minlength="2" required />

          <div class="d-flex mt-5">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            <div class="ms-auto">
              <button type="submit" class="btn btn-primary">Change Password</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Change Password Modal -->


  <div class="modal fade" id="updateUserModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  role="dialog" aria-labelledby="updateUserModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateUserModalLabel">Update User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <form method="post" action="/{{ account_type() }}/user/edit">
          @csrf

          <div class="error"><x-alert /></div>

          <input type="hidden" name="user_id" id="user_id">
          <input type="hidden" name="email_old" id="email_old">
          <div class="mb-3 h3">Current Email: <span class="d-inline-block" id="current_text"></span></div>

          @include('components.user_form')

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
      

      $('#updateUserModal').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget)

      var modal = $(this)

      if (button.data('user_id')) {
        $('.error').text("") //clear error if is set
        modal.find('#user_id').val(button.data('user_id'))
        modal.find('#email_old').val(button.data('email'))
        //#email_old: does not do anything. Used to retain original text in case of error
        modal.find('#current_text').text(button.data('email'))
        modal.find('#title').val(button.data('title'))
        modal.find('#last_name').val(button.data('last_name'))
        modal.find('#first_name').val(button.data('first_name'))
        modal.find('#middle_name').val(button.data('middle_name'))
        modal.find('#faculty_id').val(button.data('faculty_id'))
        modal.find('#email').val(button.data('email'))
        modal.find('#phone').val(button.data('phone'))
        modal.find('#account_type').val(button.data('account_type'))
      }
      //
      else if ('{!! old('user_id') !!}' != '') {
        modal.find('#user_id').val("{!! old('user_id') !!}")
        modal.find('#email_old').val("{!! old('email_old') !!}")
        //#email_old: does not do anything. Used to retain original text in case of error
        modal.find('#current_text').text("{!! old('email_old') !!}")
        modal.find('#title').val("{!! old('title') !!}")
        modal.find('#last_name').val("{!! old('last_name') !!}")
        modal.find('#first_name').val("{!! old('first_name') !!}")
        modal.find('#middle_name').val("{!! old('middle_name') !!}")
        modal.find('#faculty_id').val("{!! old('faculty_id') !!}")
        modal.find('#email').val("{!! old('email') !!}")
        modal.find('#phone').val("{!! old('phone') !!}")
        modal.find('#account_type').val("{!! old('account_type') !!}")
      }
    }) //updateUserModal


    $('#changPasswordModal').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget)
      
      var modal = $(this)
      
      if (button.data('user_id')) {
        $('.error').text("") //clear error if is set
        modal.find('#user_id').val(button.data('user_id'))
        modal.find('#email_old').val(button.data('email'))
        //#email_old: does not do anything. Used to retain original text in case of error
        modal.find('#current_email').text(button.data('email'))
        modal.find('#fullname_text').text(button.data('fullname'))
        modal.find('#fullname').val(button.data('fullname'))
      }
      //
      else if ('{!! old('user_id') !!}' != '') {
        modal.find('#user_id').val("{!! old('user_id') !!}")
        modal.find('#email_old').val("{!! old('email_old') !!}")
        //#email_old: does not do anything. Used to retain original text in case of error
        modal.find('#current_email').text("{!! old('email_old') !!}")
        modal.find('#fullname_text').text("{!! old('fullname') !!}")
        modal.find('#fullname').val("{!! old('fullname') !!}")
      }
    }) //changPasswordModal

    }); //documentReady
    
    $(document).ready(function() {
      @if (old('user_id') && !old('password'))
        $('#updateUserModal').modal('show');
      @endif
      
      @if (old('password'))
        $('#changPasswordModal').modal('show');
      @endif

      $('#account_type').change(function() {
        if(!confirm('Make this user ' + this.value)) {
          this.value = "";
        }
      })
    });
  </script>
@endsection
