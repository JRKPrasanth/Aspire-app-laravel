@extends('layouts.header')
@section('content')
<h3 class="text-danger">Sales Return Details</h3>
@include('layouts.breadcrumb')

<div class="card shadow-lg rounded-4 border-0">
  <!-- Card Header -->
  <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
    <h5 class="mb-0 fw-bold">Return Details</h5>
    <a href="{{ URL::to('salesreturnview') }}" class="btn btn-sm btn-danger">
		<i class="bi bi-x-circle"></i>
    </a>
  </div>

  <!-- Card Body -->
  <div class="card-body">
    <div class="container-fluid">
      <div class="row g-4">
        <!-- Column 1 -->
        <div class="col-md-4">
          <p><strong>RMA Ref Number:</strong> {{ $headerdata->rma_ref_no }}</p>
          <p><strong>Return Source:</strong> {{ $headerdata->return_source }}</p>
          <p>
            <strong>Return Date:</strong> 
            {{ date(\Session::get('p_date_format'), strtotime($headerdata->return_date)) }}
          </p>
          <p><strong>Return Status:</strong> {{ $headerdata->return_status }}</p>
        </div>

        <!-- Column 2 -->
        <div class="col-md-4">
          <p><strong>Reference No:</strong> {{ $headerdata->reference_no }}</p>
          <p><strong>Customer:</strong> {{ $headerdata->customer_name }}</p>
          <p><strong>Remarks:</strong> {{ $headerdata->remarks }}</p>
        </div>

        <!-- Column 3 -->
        <div class="col-md-4">
          <p><strong>Bill To Address:</strong><br> {{ $bill_to_address }}</p>
          <p><strong>Ship To Address:</strong><br> {{ $ship_to_address }}</p>
        </div>
      </div>
    </div>

    <!-- Line Items Table -->
    <div class="mt-4">
      <h6 class="fw-bold border-bottom pb-2">Return Line Items</h6>
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
          <thead class="table-light">
            <tr>
              <th>Line No</th>
              <th>Product</th>
              <th>UOM Code</th>
              <th>Invoice Qty</th>
              <th>Return Qty</th>
              <th>Returned Qty</th>
              <th>Reason Comments</th>
            </tr>
          </thead>
          <tbody>
            @foreach($vlinesdata as $value)
              <tr>
                <td>{{ $value->line_no }}</td>
                <td>{{ $value->concatenated_product }}</td>
                <td>{{ $value->uom_code }}</td>
                <td>{{ $value->invoice_qty }}</td>
                <td>{{ $value->return_qty }}</td>
                <td>{{ $value->returned_qty }}</td>
                <td>{{ $value->reason_comments }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>




@endsection

