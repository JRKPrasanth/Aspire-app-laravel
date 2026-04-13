@extends('layouts.header')
@section('content')
<h3 class="text-danger">Customer Details</h3>
@include('layouts.breadcrumb')


<form>
  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-header d-flex justify-content-end bg-primary text-white">
	
      <a class="btn btn-sm btn-danger closeurl" href="{{URL::to($return_url)}}"><i class="bi bi-x-lg"></i></a>
    </div>

    <div class="card-body">

      <!-- Customer Basic Info -->
      <div class="row g-4">
        <div class="col-md-4">
          <p><strong>Customer Number:</strong> {!! $customer_number !!}</p>
          <p><strong>Customer Name:</strong> {!! $customer_name !!}</p>
          <p><strong>Alternate Name:</strong> {!! $alternate_name !!}</p>
          <p><strong>Customer Type:</strong> {!! $customer_type_id !!}</p>
          <p><strong>Active:</strong> {!! $active !!}</p>
        </div>
        <div class="col-md-4">
          <p><strong>Payment Term:</strong> {!! $default_payment_terms_id !!}</p>
          <p><strong>Payment Method:</strong> {!! $default_payment_method_id !!}</p>
          <p><strong>PAN No:</strong> {!! $pan_no !!}</p>
          <p><strong>Pricelist:</strong> {!! $pricelist_id !!}</p>
          <p><strong>Created By:</strong> {!! $created_by !!}</p>
        </div>
        <div class="col-md-4">
          <p><strong>Account Code:</strong> {!! $tds_account_id !!}</p>
          <p><strong>Delivery Term:</strong> {!! $delivery_terms_id !!}</p>
          <p><strong>Sales Person:</strong> {!! $sales_person !!}</p>
          <p><strong>Freight Term:</strong> {!! $frieghtterm_id !!}</p>
        </div>
      </div>

      <!-- Overdue Section -->
      @if($overdue == "Yes")
      <div class="mt-4">
        <h4 class="head-style-1 text-primary">Over Due Details</h4>
        <table class="table table-bordered table-hover mt-2">
          <thead class="table-light">
            <tr>
              <th colspan="2">Discount for Before Payment Due Date</th>
              <th colspan="2">Interest for After Payment Due Date</th>
            </tr>
            <tr>
              <th>Days</th>
              <th>Discount</th>
              <th>Days</th>
              <th>Interest</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($overdata as $k=>$v)
            <tr>
              <td>{{ $v->before_days }}</td>
              <td>{{ $v->before_dis }}</td>
              <td>{{ $v->after_days }}</td>
              <td>{{ $v->after_int }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      @endif

      <!-- Additional Details -->
      <div class="mt-4">
        <h4 class="head-style-1 text-primary">Additional Details</h4>
        <div class="row g-4">
          <div class="col-md-4">
            <p><strong>Customer Category:</strong> {!! $customer_category !!}</p>
            <p><strong>Line Of Business:</strong> {!! $line_of_business !!}</p>
            <p><strong>Freight Carriers:</strong> {!! $ar_frieghtcarriers_hdr_id !!}</p>
            <p><strong>TDS Applicable:</strong> {!! $tds_applicable !!}</p>
            <p><strong>TCS Applicable:</strong> {!! $tcs_applicable !!}</p>
            <p><strong>Default Bank:</strong> {!! $default_bank !!}</p>
          </div>
          <div class="col-md-4">
            <p><strong>Reward Opening Point:</strong> {!! $reward_opening_point !!}</p>
            <p><strong>Discounts:</strong> {!! $ar_discount_hdr_id !!}</p>
            <p><strong>Company Additional Info:</strong> {!! $company_additional_info !!}</p>
            <p><strong>TDS %:</strong> {!! $tds_percentage !!}</p>
            <p><strong>TCS %:</strong> {!! $tcs_percentage !!}</p>
          </div>
          <div class="col-md-4">
            <p><strong>Reward Point:</strong> {!! $reward_point !!}</p>
            <p><strong>Maximum Credit:</strong> {!! $maximum_credit !!}</p>
            <p><strong>Credit Check:</strong> {!! $credit_check !!}</p>
            <p><strong>TDS Account Code:</strong> {!! $tds_account_id !!}</p>
            <p><strong>TCS Account Code:</strong> {!! $tcs_account_id !!}</p>
          </div>
        </div>
      </div>

      <!-- Customer Site Details -->
      <div class="mt-4">
        <h4 class="head-style-1 text-primary">Customer Site Details</h4>
        <table class="table table-bordered table-hover mt-2">
          <thead class="table-light">
            <tr>
              <th>Site No</th>
              <th>Site Name</th>
              <th>Type</th>
              <th>Address</th>
              <th>Country</th>
              <th>State</th>
              <th>City</th>
              <th>GST No</th>
              <th>TAN No</th>
              <th>Pincode</th>
              <th>Contact</th>
              <th>Primary Address</th>
              <th>Active</th>
            </tr>
          </thead>
          <tbody>
            @if(count($vlinesdata)>0)
              @foreach ($vlinesdata as $key=>$value)
              <tr>
                <td>{{ $value->customer_site_number }}</td>
                <td>{{ $value->customer_site_name }}</td>
                <td>{{ $value->site_type }}</td>
                <td>{{ $value->address }}</td>
                <td>{{ $country }}</td>
                <td>{{ $state }}</td>
                <td>{{ $city }}</td>
                <td>{{ $value->gst_no }}</td>
                <td>{{ $value->tan_no }}</td>
                <td>{{ $value->pincode }}</td>
                <td>
                  @foreach($contact[$key] as $k=>$v)
                    {{ $k+1 }} - {{ $v }}
                  @endforeach
                </td>
                <td>{{ $value->primary_address }}</td>
                <td>{{ $value->active }}</td>
              </tr>
              @endforeach
            @endif
          </tbody>
        </table>
      </div>

    </div>
  </div>
</form>



@endsection