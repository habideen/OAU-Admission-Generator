@extends('layouts.panel')

@section('page_title', 'Admission Statistics')


@section('content')
  <div class="page-content">
    <div class="container-fluid">

      <!-- start page title -->
      <div class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Admission Statistics</h4>

            <div class="page-title-right">
              <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="/{{ account_type() }}/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active">Admission Statistics</li>
              </ol>
            </div>
          </div>
        </div>
      </div>
      <!-- end page title -->

      <div class="card">
        <div class="card-body">
          <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
            <thead>
              <tr>
                <th>Faculty</th>
                <th>Course</th>
                <th>Merit</th>
                <th>Catchment</th>
                <th>ELDS</th>
                <th>Discretion</th>
                <th>Capacity</th>
                <th>Space Left</th>
                <th>Session</th>
              </tr>
            </thead>

            <tbody>
              @foreach ($stats as $stat)
                <tr>
                  <td>{{ $stat->faculty }}</td>
                  <td>{{ $stat->course }}</td>
                  <td>{{ $stat->merit }}</td>
                  <td>{{ $stat->catchment }}</td>
                  <td>{{ $stat->elds }}</td>
                  <td>{{ $stat->discretion }}</td>
                  <td>{{ $stat->capacity }}</td>
                  <td>{{ $stat->capacity - $stat->merit - $stat->catchment - $stat->discretion - $stat->elds }}</td>
                  <td>{{ $stat->session_updated }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>

          <div class="mt-4 h5">
            <div class="h4 mb-2"><b><u>Total</u></b></div>
            <div class="mb-1">Merit: <span id="totalMerit"></span></div>
            <div class="mb-1">Catchment: <span id="totalCatchment"></span></div>
            <div class="mb-1">ELDS: <span id="totalELDS"></span></div>
            <div class="mb-3">Discretion: <span id="totalDiscretion"></span></div>

            <div class="mb-1">Capacity: <span id="totalCapacity"></span></div>
            <div class="mb-3">Space Left: <span id="totalSpaceLeft"></span></div>

            <div class="mb-1">Candidate Application: {{ $totalCandidates }}</div>
            <div class="mb-1">Candidate Admitted: <span id="totalAdmitted"></span></div>
          </div>
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




  totalCandidates
  totalAdmitted
  <script>
    $(document).ready(function() {
      // Function to calculate the sum of a column
      function calculateColumnSum(columnIndex) {
        var total = 0;
        $('#datatable-buttons tbody tr').each(function() {
          var cellValue = $(this).find('td:eq(' + columnIndex + ')').text();
          // Parse the cell value as a number and add it to the total
          total += parseFloat(cellValue) || 0;
        });
        return total;
      }
      let totalMerit = calculateColumnSum(2);
      let totalCatchment = calculateColumnSum(3);
      let totalELDS = calculateColumnSum(4);
      let totalDiscretion = calculateColumnSum(5);
      let totalCapacity = calculateColumnSum(6);
      let totalSpaceLeft = calculateColumnSum(7);

      // Calculate the sum of the second column (Column 2)
      $('#totalMerit').text(totalMerit);
      $('#totalCatchment').text(totalCatchment);
      $('#totalELDS').text(totalELDS);
      $('#totalDiscretion').text(totalDiscretion);

      $('#totalCapacity').text(totalCapacity);
      $('#totalSpaceLeft').text(totalSpaceLeft);

      $('#totalAdmitted').text(totalMerit + totalCatchment + totalELDS + totalDiscretion);
    });
  </script>
@endsection
