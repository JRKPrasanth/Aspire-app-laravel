@extends('layouts.header')
@section('content')
<h3 class="text-danger">Job Activity Details</h3>
@include('layouts.breadcrumb')

<form>
  {{ csrf_field() }}

  <div class="card shadow-lg border-0 rounded-3">

    <!-- Card Header -->
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h5 class="mb-0">
        <i class="fa fa-briefcase me-2"></i> Job Activity
      </h5>
      <a href="../jobactivity" class="btn btn-danger btn-sm">
        <i class="fa fa-times"></i>
      </a>
    </div>

    <!-- Card Body -->
    <div class="card-body normalform">

      <!-- Employee Info -->
      <div class="row mb-3">
        <div class="col-md-6">
          <p class="mb-1">
            <strong>Employee Name:</strong> {!! $headerdata->employee_number !!}
          </p>
        </div>
        <div class="col-md-6">
          <p class="mb-1">
            <strong>Remarks:</strong> {!! $headerdata->remarks !!}
          </p>
        </div>
      </div>

      <!-- Additional Details -->
      <div class="mb-3">
        <h6 class="text-secondary border-bottom pb-2">
          <i class="fa fa-info-circle me-2"></i> Additional Details
        </h6>
      </div>

      <!-- Job Activity Table -->
      <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle text-center">
          <thead class="table-primary">
            <tr>
              <th scope="col">Line No</th>
              <th scope="col">Activity Name</th>
              <th scope="col">Start DateTime</th>
              <th scope="col">End DateTime</th>
              <th scope="col">Duration</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($linesdata as $key => $value) { ?>
              <tr>
                <td>{!! $key+1 !!}</td>
                <td>{!! $value->activity_name !!}</td>
                <td>{!! $value->start_datetime !!}</td>
                <td>{!! $value->end_datetime !!}</td>
                <td>{!! $value->duration !!}</td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</form>

	
	
@endsection