@extends('layouts.panel')

@section('page_title', 'Dashboard')


@section('content')
<div class="main-content">

    <div class="page-content">
      <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
          <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
              <h4 class="mb-sm-0 font-size-18">Dashboard</h4>

              <div class="page-title-right">
                <ol class="breadcrumb m-0">
                  <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                  <li class="breadcrumb-item active">Dashboard</li>
                </ol>
              </div>

            </div>
          </div>
        </div>
        <!-- end page title -->
      </div>
      <!-- container-fluid -->
    </div>
    <!-- End Page-content -->


    <footer class="footer">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <script>
              document.write(new Date().getFullYear())
            </script> © Skote.
          </div>
          <div class="col-sm-6">
            <div class="text-sm-end d-none d-sm-block">
              Design & Develop by Themesbrand
            </div>
          </div>
        </div>
      </div>
    </footer>
  </div>
@endsection