@extends('layouts.header')
@section('content')

<form>
	<div class="card shadow-lg rounded-4 border-0">
    
    <!-- Card Header -->
    <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
      <h5 class="mb-0">Dispatch Details</h5>
      <a href="{{ URL::to($return_url) }}" class="btn btn-danger btn-sm">
        <i class="bi bi-x-circle"></i>
      </a>
    </div>

    <!-- Card Body -->
    <div class="card-body">

      <!-- Dispatch Info -->
      <div class="row g-3 mb-4">
        <div class="col-md-4">
          <p><strong>Dispatch Number:</strong> {!! $dispatch_number !!}</p>
          <p><strong>Dispatch Source:</strong> {!! $dispatch_source !!}</p>
          <p><strong>Dispatch Date:</strong> {!! $dispatch_date !!}</p>
          <p><strong>Reference No:</strong> {!! $reference_no !!}</p>
          <p><strong>Pack Qty:</strong> {!! $packaging_qty !!}</p>
        </div>

        <div class="col-md-4">
          <p><strong>Freight Carrier:</strong> {!! $freight_carrier_id !!}</p>
          <p><strong>Pricelist Name:</strong> {!! $pricelist_id !!}</p>
          <p><strong>Dispatch Status:</strong> {!! $dispatch_status !!}</p>
          <p><strong>Pack Weight:</strong> {!! $pack_weight !!}</p>
        </div>

        <div class="col-md-4">
          <p><strong>Customer Name:</strong> {!! $ship_to_customer_id !!}</p>
          <?php if($ship_to_customer_id==""){ ?>
            <p><strong>Employee Name:</strong> {!! $empname !!}</p>
          <?php } ?>
          <p><strong>Location:</strong> {!! $location_id !!}</p>
          <p><strong>Deliver To Location:</strong> {!! $deliver_to_location_txt !!}</p>
        </div>
      </div>

      <!-- Additional Details -->
      <h6 class="border-bottom pb-2 mb-3">Additional Details</h6>
      <div class="row g-3 mb-4">
        <div class="col-md-4">
          <p><strong>Prepare Date:</strong> {!! $prepare_date !!}</p>
        </div>
        <div class="col-md-4">
          <p><strong>Preparer Name:</strong> {!! $preparer_id !!}</p>
        </div>
        <div class="col-md-4">
          <p><strong>Remarks:</strong> {!! $remarks !!}</p>
        </div>
      </div>

      <!-- Dispatch Lines -->
      <h6 class="border-bottom pb-2 mb-3">Dispatch Line Items</h6>
      <div class="table-responsive">
        <table class="table table-bordered table-hover table-sm align-middle">
          <thead class="table-light">
            <tr>
              <th>Line No</th>
              <th>Product</th>
              <th>UOM Code</th>
              <th>Batch Number</th>
              <th>Dispatch Qty</th>
              <th>SO Qty</th>
              <th>Dispatched Qty</th>
              <th>Comments</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($displndata as $key => $value): ?>
              <tr>
                <td>{!! $key+1 !!}</td>
                <td>{!! $value->product_code ." - ". $value->product_id !!}</td>
                <td>{!! $value->uom_code_id !!}</td>
                <td>{!! $value->batch_no !!}</td>
                <td>{!! $value->dispatch_qty !!}</td>
                <td>{!! $value->so_qty !!}</td>
                <td>{!! $value->dispatched_qty !!}</td>
                <td>{!! $value->comments !!}</td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</form>


@endsection