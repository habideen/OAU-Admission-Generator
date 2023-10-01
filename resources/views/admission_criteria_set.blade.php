@extends('layouts.panel')

@section('page_title', 'Admission Criteria')


@section('content')
  <div class="page-content">
    <div class="container-fluid">

      <!-- start page title -->
      <div class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Admission Criteria</h4>

            <div class="page-title-right">
              <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="/{{ account_type() }}/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active">Admission Criteria</li>
              </ol>
            </div>
          </div>
        </div>
      </div>
      <!-- end page title -->

      <div class="card">
        <div class="card-body">
          <x-alert />

          <form method="post" id="criteriaForm">
            @csrf

            <div class="h5 mb-4">Session: {{ activeSession() }}</div>

            <div class="row">
              <x-form.input name="merit" label="Merit (%)" type="number" parentClass="mb-4 col-sm-6" min="0"
                max="100" step="1" placeholder="0 - 100" required :value="$criteria->merit" />

              <x-form.input name="catchment" label="Catchment (%)" type="number" parentClass="mb-4 col-sm-6"
                min="0" max="100" step="1" placeholder="0 - 100" required :value="$criteria->catchment" />

              <x-form.input name="elds" label="ELDS (%)" type="number" parentClass="mb-4 col-sm-6" min="0"
                max="100" step="1" placeholder="0 - 100" required :value="$criteria->elds" />

              <x-form.input name="discretion" label="Discretion (%)" type="number" parentClass="mb-4 col-sm-6"
                min="0" max="100" step="1" placeholder="0 - 100" required :value="$criteria->discretion" />
            </div>

            <div class="h5 text-muted mb-4" id="criteriaResult">Total:
              {{ (int) ($criteria->merit + $criteria->catchment + $criteria->elds + $criteria->discretion) }} %</div>

            <x-form.button defaultText="Save Changes" id="criteriaBtn" />
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
    document.addEventListener('DOMContentLoaded', function() {
      // Get the input elements by their IDs
      var meritInput = document.getElementById('merit');
      var catchmentInput = document.getElementById('catchment');
      var eldsInput = document.getElementById('elds');
      var discretionInput = document.getElementById('discretion');
      var submitButton = document.getElementById('criteriaBtn');
      var resultDiv = document.getElementById('criteriaResult');

      // Add an input event listener to all input fields
      meritInput.addEventListener('input', updateSubmitButtonState);
      catchmentInput.addEventListener('input', updateSubmitButtonState);
      eldsInput.addEventListener('input', updateSubmitButtonState);
      discretionInput.addEventListener('input', updateSubmitButtonState);

      // Function to update the submit button state based on the sum
      function updateSubmitButtonState() {
        var meritValue = parseFloat(meritInput.value) || 0;
        var catchmentValue = parseFloat(catchmentInput.value) || 0;
        var eldsValue = parseFloat(eldsInput.value) || 0;
        var discretionValue = parseFloat(discretionInput.value) || 0;

        var sum = meritValue + catchmentValue + eldsValue + discretionValue;

        // Disable the submit button if the sum is greater than 100
        if (sum != 100) {
          submitButton.disabled = true;
        } else {
          submitButton.disabled = false;
        }

        // Display the total sum in the resultDiv
        resultDiv.innerHTML = 'Total: ' + sum + ' %';
      }
    });
  </script>
@endsection
