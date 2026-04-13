@extends('layouts.header')
@section('content')
<h3 class="text-danger">Sales Inquiry Details</h3>
@include('layouts.breadcrumb')


			
<form>
  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Sales Inquiry Details</h5>
      <a href="{{ URL::to($closeredirect) }}" class="btn btn-danger btn-sm">Close</a>
    </div>

    <div class="card-body card-block" id="section-to-print">

      <!-- Inquiry Header -->
      <div class="row mb-4">
        <div class="col-md-4">
          <p><strong>Enquiry No:</strong> {!! $headerdata->inquiry_no !!}</p>
          <p><strong>Customer:</strong> {!! $headerdata->customer_number !!} - {!! $headerdata->customer_name !!}</p>
        </div>
        <div class="col-md-4">
          <p><strong>Enquiry Date:</strong> {{ date(\Session::get('p_date_format'), strtotime($headerdata->inquiry_date)) }}</p>
          <p><strong>Project Name:</strong> {!! $headerdata->project_name !!}</p>
        </div>
        <div class="col-md-4">
          <p><strong>Enquiry Type:</strong> {!! $headerdata->inquiry_type !!}</p>
          <p><strong>Remarks:</strong> {!! $headerdata->remarks !!}</p>
        </div>
      </div>

      <!-- Additional Details -->
      <h5 class="text-secondary mb-3">Additional Details</h5>
      <div class="row mb-4">
        <div class="col-md-4">
          <p><strong>Tender Id:</strong> {{ $headerdata->tender_id }}</p>
          <p><strong>EMD Details:</strong> {{ $headerdata->emd_details }}</p>
        </div>
        <div class="col-md-4">
          <p><strong>Tender Ref No:</strong> {{ $headerdata->tender_ref_no }}</p>
          <p><strong>Title Of Work:</strong> {{ $headerdata->tittle_of_work }}</p>
        </div>
        <div class="col-md-4">
          <p><strong>Submission Due Date:</strong> {{ date(\Session::get('p_date_format'), strtotime($headerdata->submission_duedate)) }}</p>
          <p><strong>Enquiry Source:</strong> {{ $headerdata->source_type_id }}</p>
        </div>
      </div>

      <!-- Line Items -->
      <h5 class="text-secondary mb-3">Line Items</h5>
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
          <thead class="table-light">
            <tr>
              <th>Line No</th>
              @if ($headerdata->inquiry_type == "STANDARD")
                <th>Product Name</th>
                <th>Customer Part No</th>
                <th>UOM Code</th>
              @else
                <th>Product</th>
                <th>UOM</th>
                <th>Product Description</th>
              @endif
              <th>Required Qty</th>
              <th>Need By Date</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($linesdata as $key => $value)
              <tr>
                <td>{{ $key+1 }}</td>
                @if ($headerdata->inquiry_type == "STANDARD")
                  <td>{{ $value->product_code }} - {{ $value->concatenated_product }}</td>
                  <td>{{ $value->part_no }}</td>
                  <td>{{ $value->uom_code }}</td>
                @else
                  <td>{{ $value->product_code }} - {{ $value->concatenated_product }}</td>
                  <td>{{ $value->uom_code }}</td>
                  <td>{{ $value->product_description }}</td>
                @endif
                <td>{{ $value->required_qty }}</td>
                <td>{{ date(\Session::get('p_date_format'), strtotime($value->need_by_date)) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

    </div>
  </div>
</form>

		
		

@endsection