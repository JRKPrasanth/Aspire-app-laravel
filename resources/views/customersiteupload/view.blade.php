@extends('layouts.header')
@section('content')
<h3 class="text-danger">Customer Site Upload Details</h3>
@include('layouts.breadcrumb')


<div class="card shadow-lg rounded-4 border-0" id="spy1">
  <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Customer Site Upload</h5>
    <a href="../customersiteupload" class="btn btn-danger btn-sm">Close</a>
  </div>

  <div class="card-body card-block" id="section-to-print">
    <div class="row">
      <!-- Left Column -->
      <div class="col-md-6">
        <p><strong>Product Name:</strong> {{ $values['item_name'] }}</p>
        <p><strong>Subinventory Name:</strong> {{ $values['subinventory_name'] }}</p>
        <p><strong>Locator Code:</strong> {{ $values['locator_code'] }}</p>
        <p><strong>Qty:</strong> {{ $values['qty'] }}</p>
      </div>

      <!-- Right Column -->
      <div class="col-md-6">
        <p><strong>Batch Name:</strong> {{ $values['batch_name'] }}</p>
        <p><strong>Batch Date:</strong> {{ $values['batch_date'] }}</p>
        <p><strong>Batch Status:</strong> {{ $values['batch_status'] }}</p>
        <p><strong>Batch Comments:</strong> {{ $values['batch_comments'] }}</p>
      </div>
    </div>
  </div>
</div>




@endsection