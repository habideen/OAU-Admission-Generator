<!doctype html>
<html lang="en">

<head>

  <meta charset="utf-8" />
  <title>@yield('page_title') - {{ config('app.name') }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
  <meta content="Themesbrand" name="author" />
  <!-- App favicon -->
  <link rel="shortcut icon" href="/assets/images/favicon.ico">

  <!-- Bootstrap Css -->
  <link href="/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
  <!-- Icons Css -->
  <link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
  <!-- App Css-->
  <link href="/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

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
    @yield('content')
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

  <!-- apexcharts -->
  <script src="/assets/libs/apexcharts/apexcharts.min.js"></script>

  <!-- dashboard init -->
  <script src="/assets/js/pages/dashboard.init.js"></script>

  <!-- App js -->
  <script src="/assets/js/app.js"></script>
</body>

</html>
