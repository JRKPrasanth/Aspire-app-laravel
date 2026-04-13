@extends('layouts.header')
@section('content')

   <form>
  {{ csrf_field() }}

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
      <h5 class="mb-0">Machine Capacity Details</h5>
      <a href="{{ URL::to('materialequipments') }}" class="btn btn-sm btn-danger">
        <i class="fa fa-times"></i>
      </a>
    </div>

    <div class="card-body">

      <!-- Machine Info -->
      <div class="row mb-4">
        <div class="col-md-4">
          <p><strong>Machine Name:</strong> {!! $machine_name !!}</p>
        </div>
        <div class="col-md-4">
          <p><strong>Created By:</strong> {!! $created_by !!}</p>
        </div>
        <div class="col-md-4">
          <p><strong>Remarks:</strong> {!! $remarks !!}</p>
        </div>
      </div>

      <!-- Capacity Table -->
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
          <thead class="table-light">
            <tr>
              <th>Line No</th>
              <th>Product</th>
              <th>Range From</th>
              <th>Range To</th>
              <th>Hours</th>
              <th>Comments</th>
            </tr>
          </thead>
          <tbody>
            @foreach($linesdata as $key=>$value)
              <tr>
                <td>{{ $key+1 }}</td>
                <td>{!! $value->concatenated_product ?? '-' !!}</td>
                <td>{!! $value->range_from !!}</td>
                <td>{!! $value->range_to !!}</td>
                <td>{!! $value->hours !!}</td>
                <td>{!! $value->comments !!}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

    </div>
  </div>
</form>




@endsection


