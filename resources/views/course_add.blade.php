@extends('layouts.panel')

@section('page_title', 'Add Course')


@section('content')
  <div class="page-content">
    <div class="container-fluid">

      <!-- start page title -->
      <div class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Add Course</h4>

            <div class="page-title-right">
              <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="/{{ account_type() }}/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active">Add Course</li>
              </ol>
            </div>
          </div>
        </div>
      </div>
      <!-- end page title -->

      <div class="card">
        <div class="card-body">
          <x-alert />

          <form method="post">
            @csrf

            <div class="text-muted mb-4 h5">Please select at least 4 subjects.</div>

            <div class="row">
              <x-form.select name="faculty_id" label="Faculty" :selected="old('faculty_id')" optionsType="object" :options="$faculties"
                objKey="id" objValue="faculty" parentClass="col-md-6 mb-4" required />

              <x-form.input name="course" label="Course" type="text" :value="old('course')" parentClass="col-md-6 mb-4"
                pattern="^[a-zA-Z0-9\-\# ]{2,255}$" minlength="2" maxlength="255" required />
            </div>

            <div class="row">
              <x-form.select name="subject_code_1" label="Subject 1" :selected="old('subject_code_1')" optionsType="object"
                :options="$subjects" objKey="subject_code" objValue="subject" parentClass="col-md-4 mb-4" />

              <x-form.select name="subject_code_2" label="Subject 2" :selected="old('subject_code_2')" optionsType="object"
                :options="$subjects" objKey="subject_code" objValue="subject" parentClass="col-md-4 mb-4" />

              <x-form.select name="subject_code_3" label="Subject 3" :selected="old('subject_code_3')" optionsType="object"
                :options="$subjects" objKey="subject_code" objValue="subject" parentClass="col-md-4 mb-4" />

              <x-form.select name="subject_code_4" label="Subject 4" :selected="old('subject_code_4')" optionsType="object"
                :options="$subjects" objKey="subject_code" objValue="subject" parentClass="col-md-4 mb-4" />

              <x-form.select name="subject_code_5" label="Subject 5" :selected="old('subject_code_5')" optionsType="object"
                :options="$subjects" objKey="subject_code" objValue="subject" parentClass="col-md-4 mb-4" />

              <x-form.select name="subject_code_6" label="Subject 6" :selected="old('subject_code_6')" optionsType="object"
                :options="$subjects" objKey="subject_code" objValue="subject" parentClass="col-md-4 mb-4" />

              <x-form.select name="subject_code_7" label="Subject 7" :selected="old('subject_code_7')" optionsType="object"
                :options="$subjects" objKey="subject_code" objValue="subject" parentClass="col-md-4 mb-4" />

              <x-form.select name="subject_code_8" label="Subject 8" :selected="old('subject_code_8')" optionsType="object"
                :options="$subjects" objKey="subject_code" objValue="subject" parentClass="col-md-4 mb-4" />
            </div>

            <x-form.button defaultText="Save Course" />

          </form>
        </div>
      </div>
      <!-- card -->
    </div>
    <!-- container-fluid -->
  </div>
  <!-- End Page-content -->
@endsection
