<!doctype html>
<html lang="en">

<head>

  <meta charset="utf-8" />
  <title>@yield('page_title') - {{ config('app.name') }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- App favicon -->
  <link rel="shortcut icon" href="/assets/images/favicon.ico">

  <!-- Bootstrap Css -->
  <link href="/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
  <!-- Icons Css -->
  <link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
  <!-- App Css-->
  <link href="/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

  @yield('style')

</head>

<body data-sidebar="dark">

  <!-- <body data-layout="horizontal" data-topbar="dark"> -->

  <!-- Begin page -->
  <div id="layout-wrapper">

    <x-topnav />

    <!-- ========== Left Sidebar Start ========== -->
    <x-sidenav />
    <!-- Left Sidebar End -->



    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">
      @yield('content')

      <x-footer />
    </div>
    <!-- end main content-->

  </div>
  <!-- END layout-wrapper -->

  <!-- Right bar overlay-->
  <div class="rightbar-overlay"></div>

  <!-- JAVASCRIPT -->
  <script src="/assets/libs/jquery/jquery.min.js"></script>
  <script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="/assets/libs/metismenu/metisMenu.min.js"></script>
  <script src="/assets/libs/simplebar/simplebar.min.js"></script>
  <script src="/assets/libs/node-waves/waves.min.js"></script>

  <!-- App js -->
  <script src="/assets/js/app.js"></script>

  @yield('script')
</body>

</html>
