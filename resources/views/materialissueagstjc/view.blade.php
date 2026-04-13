@extends('layouts.header')
@section('content')
<h3 class="text-danger">Material Re Issue Details</h3>
@include('layouts.breadcrumb')


<form>
  {{ csrf_field() }}

  <div class="card shadow-lg border-0 rounded-3">

    <!-- Header -->
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h5 class="mb-0">
        <i class="fa fa-clipboard-list me-2"></i> Material Issue Against JC
      </h5>
      <a href="{{ url('materialissueagainstjc') }}" class="btn btn-danger btn-sm">
        <i class="fa fa-times"></i>
      </a>
    </div>

    <!-- Body -->
    <div class="card-body">

      <!-- Job Information -->
      <div class="row mb-4">
        <div class="col-md-6">
          <p class="mb-1"><strong>Product:</strong> {!! $assembly_product !!}</p>
          <p class="mb-1"><strong>Job No:</strong> {!! $job_no !!}</p>
          <p class="mb-1"><strong>Batch No:</strong> {!! $batch_no !!}</p>
          <p class="mb-1">
            <strong>Material Issue Date:</strong>
            {!! date(\Session::get('p_date_format'), strtotime($mtl_issue_date)) !!}
          </p>
        </div>
        <div class="col-md-6">
          <p class="mb-1"><strong>UOM Code:</strong> {!! $uom_code_id !!}</p>
          <p class="mb-1"><strong>Job Qty:</strong> {!! $job_qty !!}</p>
          <p class="mb-1"><strong>Job Status:</strong> {!! $job_status !!}</p>
          <p class="mb-1"><strong>Remarks:</strong> {!! $remarks !!}</p>
        </div>
      </div>

      <!-- Line Items Table -->
      <h6 class="text-secondary border-bottom pb-2 mb-3">
        <i class="fa fa-cubes me-2"></i> Material Line Details
      </h6>

      <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle text-center">
          <thead class="table-primary">
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
            @foreach($vlinesdata as $key=>$value)
              <tr>
                <td>{{ $value->line_no }}</td>
                <td>{{ $value->product_code.'-'.$value->concatenated_product }}</td>
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