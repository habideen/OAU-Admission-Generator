@extends('layouts.panel')

@section('page_title', 'Candidate Delete')


@section('content')
  <div class="page-content">
    <div class="container-fluid">

      <!-- start page title -->
      <div class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Candidate Delete</h4>

            <div class="page-title-right">
              <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="/{{ account_type() }}/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active">Candidate Delete</li>
              </ol>
            </div>
          </div>
        </div>
      </div>
      <!-- end page title -->

      <div class="card">
        <div class="card-body">
          <x-alert />

          <form method="post" id="deleteForm">
            @csrf
            @method('delete')

            <x-form.select name="session" label="Session to delete" :selected="old('session')" optionsType="object"
              :options="$sessions" objKey="session" objValue="session" parentClass="col-md-4 mb-4" required />

            <x-form.button defaultText="Upload Candidates" id="deleteBtn" />

          </form>
        </div>
      </div>
      <!-- card -->
    </div>
    <!-- container-fluid -->
  </div>
  <!-- End Page-content -->
@endsection



@section('script')
  <script>
    $("#deleteForm").submit(function() {
      $("#deleteBtn").prop("disabled", true);
    });
  </script>
@endsection
