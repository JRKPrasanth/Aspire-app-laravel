@extends('layouts.header')
@section('content')
<h3 class="text-danger">Customer Upload Details</h3>
@include('layouts.breadcrumb')


<div class="card shadow-lg rounded-4 border-0">
  <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Customer Upload Details</h5>
    <a href="../customerupload" class="btn btn-danger btn-sm">Close</a>
  </div>

  <div class="card-body" id="section-to-print">
    <div class="row">
      <!-- Left Column -->
      <div class="col-md-6">
        <p><strong>Customer Name:</strong> {{ $values['customer_name'] }}</p>
        <p><strong>Customer Type:</strong> {{ $values['customer_type'] }}</p>
        <p><strong>Alternate Name:</strong> {{ $values['alternate_name'] }}</p>
        <p><strong>Sales Person:</strong> {{ $values['sales_person'] }}</p>
        <p><strong>GST No:</strong> {{ $values['gst_no'] }}</p>
        <p><strong>Billing Address:</strong> {{ $values['billing_address'] }}</p>
      </div>

      <!-- Right Column -->
      <div class="col-md-6">
        <p><strong>Contact Person:</strong> {{ $values['contact_person'] }}</p>
        <p><strong>Contact Number:</strong> {{ $values['contact_number'] }}</p>
        <p><strong>Overdue:</strong> {{ $values['overdue'] }}</p>
        <p><strong>Pricelist Name:</strong> {{ $values['pricelist'] }}</p>
        <p><strong>Default Payment Terms:</strong> {{ $values['default_payment_terms'] }}</p>
        <p><strong>Default Payment Method:</strong> {{ $values['default_payment_method'] }}</p>
      </div>
    </div>

    <hr class="my-4">

    <h5 class="text-secondary mb-3">Additional Details</h5>
    <div class="row">
      <!-- Left Column -->
      <div class="col-md-6">
        <p><strong>Customer Category:</strong> {{ $values['customer_category'] }}</p>
        <p><strong>Reward Opening Point:</strong> {{ $values['reward_opening_point'] }}</p>
        <p><strong>Reward Point:</strong> {{ $values['reward_point'] }}</p>
        <p><strong>Discount:</strong> {{ $values['ar_discount_hdr'] }}</p>
        <p><strong>Maximum Credit:</strong> {{ $values['maximum_credit'] }}</p>
      </div>

      <!-- Right Column -->
      <div class="col-md-6">
        <p><strong>Available Credit:</strong> {{ $values['available_credit'] }}</p>
        <p><strong>Credit Limit:</strong> {{ $values['credit_limit'] }}</p>
        <p><strong>Credit Check:</strong> {{ $values['credit_check'] }}</p>
        <p><strong>Freight Carriers:</strong> {{ $values['ar_frieghtcarriers_hdr'] }}</p>
        <p><strong>Company Additional Info:</strong> {{ $values['company_additional_info'] }}</p>
      </div>
    </div>
  </div>
</div>


@endsection