@extends('layouts.header')
@section('content')


<form>
  {{ csrf_field() }}

  <div class="card shadow-lg rounded-3 border-0">
    
    <!-- Card Header -->
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Material Issue Details</h5>
      <a href="{{ URL::to($pageurl) }}" class="btn btn-sm btn-danger">
        <i class="bi bi-x-lg"></i>
      </a>
    </div>

    <!-- Card Body -->
    <div class="card-body">

      <!-- Job Info -->
      <div class="row mb-4">
        <div class="col-md-6">
          <p><strong>Product:</strong> {!! $assembly_product !!}</p>
          <p><strong>Job No:</strong> {!! $job_no !!}</p>
          <p><strong>Batch No:</strong> {!! $batch_no !!}</p>
          <p><strong>Material Issue Date:</strong> {!! date(\Session::get('p_date_format'), strtotime($mtl_issue_date)) !!}</p>
        </div>
        <div class="col-md-6">
          <p><strong>Uom Code:</strong> {!! $uom_code_id !!}</p>
          <p><strong>Job Qty:</strong> {!! $job_qty !!}</p>
          <p><strong>Job Status:</strong> {!! $job_status !!}</p>
          <p><strong>Remarks:</strong> {!! $remarks !!}</p>
        </div>
      </div>

      <!-- Table -->
      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>Line No</th>
              <th>Product</th>
              <th>Component Qty</th>
              <th>Needed Qty</th>
              <th>Issued Qty</th>
              <th>Balance Qty</th>
              <th>Issue Qty</th>
              <th>Locator</th>
              <th>Comments</th>
            </tr>
          </thead>
          <tbody>
            @foreach($vlinesdata as $key => $value)
            <tr>
              <td>{{ $value->line_no }}</td>
              <td>{{ $value->product_code.' - '.$value->concatenated_product }}</td>
              <td>{{ $value->qty }}</td>
              <td>{{ $value->issue_qty }}</td>
              <td>{{ $value->issued_qty }}</td>
              <td>{{ $value->balance_qty }}</td>
              <td>{{ $value->issueqty }}</td>
              <td>{{ $value->locator_codes }}</td>
              <td>{{ $value->comments }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

    </div>
  </div>
</form>



@endsection