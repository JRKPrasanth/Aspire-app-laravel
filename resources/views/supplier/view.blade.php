@extends('layouts.header')
@section('content')
<h3 class="text-danger">Supplier View</h3>
@include('layouts.breadcrumb')


<form>
  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Supplier Details</h5>
      <a href="../supplier" class="btn btn-sm btn-danger">Close</a>
    </div>

    <div class="card-body">
      <div class="row mb-4">
        <div class="col-12">
          <h5 class="text-secondary border-bottom pb-2 mb-3 text-danger">Supplier</h5>
        </div>

        <!-- Column 1 -->
        <div class="col-md-4">
          <p><strong>Supplier Number:</strong> {!! $supplier_number !!}</p>
          <p><strong>Supplier Name:</strong> {!! $supplier_name !!}</p>
          <p><strong>Supplier Alternate Name:</strong> {!! $supplier_alternate_name !!}</p>
          <p><strong>Supplier Type Name:</strong> {!! $suppliertype_name !!}</p>
          <p><strong>Default Payment Term:</strong> {!! $payment_term_name !!}</p>
          <p><strong>Freight Carriers:</strong> {!! $carrier_name !!}</p>
        </div>

        <!-- Column 2 -->
        <div class="col-md-4">
          <p><strong>PAN Number:</strong> {!! $pan_number !!}</p>
          <p><strong>Default Payment Method:</strong> {!! $payment_method_name !!}</p>
          <p><strong>Pricelist Name:</strong> {!! $pricelist_name !!}</p>
          <p><strong>Delivery Terms:</strong> {!! $delivery_term_name !!}</p>
          <p><strong>Insurance Terms:</strong> {!! $insurance_term_name !!}</p>
        </div>

        <!-- Column 3 -->
        <div class="col-md-4">
          <p><strong>Account Structure:</strong> {!! $supplier_account !!}</p>
          <p><strong>Active:</strong> {!! $active !!}</p>
          <p><strong>Created By:</strong> {!! $created_by !!}</p>
          <p><strong>Customer Name:</strong> {!! $customer_name !!}</p>
          <p><strong>Freight Term:</strong> {!! $freight_term !!}</p>
        </div>
      </div>

      <!-- Additional Details -->
      <div class="row mb-4">
        <div class="col-12">
          <h5 class="text-secondary border-bottom pb-2 mb-3 text-danger">Additional Details</h5>
        </div>

        <div class="col-md-4">
          <p><strong>Default Bank:</strong> {!! $bank_name !!}</p>
          <p><strong>TDS Applicable:</strong> {!! $tds_applicable !!}</p>
        </div>

        <div class="col-md-4">
          <p><strong>TDS Percentage:</strong> {!! $tds_percentage !!}</p>
          <p><strong>TDS Account:</strong> {!! $tds_account !!}</p>
          <p><strong>TCS Applicable:</strong> {!! $tcs_applicable !!}</p>
        </div>

        <div class="col-md-4">
          <p><strong>TCS Percentage:</strong> {!! $tcsper !!}</p>
          <p><strong>TCS Account:</strong> {!! $tcs_account !!}</p>
        </div>
      </div>

      <!-- Supplier Site Table -->
      <div class="row">
        <div class="col-12">
          <h5 class="text-secondary border-bottom pb-2 mb-3 text-danger">Supplier Site Details</h5>
          <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped align-middle">
              <thead class="table-light">
                <tr>
                  <th>Supplier Site No</th>
                  <th>Supplier Site Name</th>
                  <th>Address</th>
                  <th>Country</th>
                  <th>State</th>
                  <th>City</th>
                  <th>Pincode</th>
                  <th>Contact Number</th>
                  <th>Contact Name</th>
                  <th>Contact Mail</th>
                  <th>GST Number</th>
                  <th>TAN Number</th>
                  <th>Primary Address</th>
                  <th>Active</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($vlinesdata as $value)
                <tr>
                  <td>{{ $value->supplier_site_number }}</td>
                  <td>{{ $value->supplier_site_name }}</td>
                  <td>{{ $value->address }}</td>
                  <td>{{ $value->country_name }}</td>
                  <td>{{ $value->state_name }}</td>
                  <td>{{ $value->city_name }}</td>
                  <td>{{ $value->pincode }}</td>
                  <td>{{ $value->contact_number }}</td>
                  <td>{{ $value->contact_person }}</td>
                  <td>{{ $value->contact_mail }}</td>
                  <td>{{ $value->gst_number }}</td>
                  <td>{{ $value->tan_no }}</td>
                  <td>{{ $value->primary_address }}</td>
                  <td>{{ $value->active }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div> <!-- end card-body -->
  </div> <!-- end card -->
</form>




@endsection