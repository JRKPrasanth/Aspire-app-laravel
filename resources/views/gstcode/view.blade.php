@extends('layouts.header')
@section('content')
<h3 class="text-danger">GST Code Details</h3>
@include('layouts.breadcrumb')


<form>
  {{ csrf_field() }}
  <div class="col-lg-12">
    <div class="card shadow-sm border-0">
      <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
        <h5 class="mb-0">GST Code Details</h5>
        <a href="../gstcode" class="btn btn-sm btn-danger">
          <i class="bi bi-x-lg"></i>
        </a>
      </div>

      <div class="card-body">
        <!-- GST Info -->
        <div class="row mb-4">
          <div class="col-md-4">
            <p><strong>Classification Code:</strong> {!! $classification_code !!}</p>
            <p><strong>Description:</strong> {!! $description !!}</p>
          </div>
          <div class="col-md-4">
            <p><strong>Classification Name:</strong> {!! $classification_name !!}</p>
            <p><strong>Created By:</strong> {!! $username !!}</p>
          </div>
          <div class="col-md-4">
            <p><strong>Active:</strong> {!! $active !!}</p>
          </div>
        </div>

        <!-- Tax Group Table -->
        <div class="table-responsive">
          <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
              <tr>
                <th>Tax Group</th>
                <th>Tax Location Type</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Active</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($vlinesdata as $key => $value)
                <tr>
                  <td>{{ $value->tax_group_name }}</td>
                  <td>{{ $value->tax_location_type }}</td>
                  <td>{{ date("d-m-Y", strtotime($value->start_date)) }}</td>
                  <td>{{ date("d-m-Y", strtotime($value->end_date)) }}</td>
                  <td>{{ $value->active }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</form>




@endsection


