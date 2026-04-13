@extends('layouts.header')
@section('content')

<div class="container">
<div class="card shadow-lg rounded-4 border-0">
    <div class="card-header d-flex justify-content-between align-items-center bg-primary">
      <h4 class="mb-0 text-white">Company Details</h4>
      <a href="{{ url($pageModule) }}" class="btn btn-sm btn-danger">Close</a>
    </div>

    <div class="card-body">
      <div class="row">
        <!-- Left Column -->
        <div class="col-md-6">
          <p><strong>Company Name:</strong> {{ $headerdata->company_name }}</p>
          <p><strong>Company Code:</strong> {{ $headerdata->company_code }}</p>
          <p><strong>Website Address:</strong> {{ $headerdata->website_address }}</p>
          <p><strong>Email Id:</strong> {{ $headerdata->email_id }}</p>
          <p><strong>Contact No:</strong> {{ $headerdata->contact_no }}</p>
          <p><strong>GST No:</strong> {{ $headerdata->gst_no }}</p>
        </div>

        <!-- Right Column -->
        <div class="col-md-6">
          <p><strong>Service Tax Reg No:</strong> {{ $headerdata->tax_reg_no }}</p>
          <p><strong>Excise Reg No:</strong> {{ $headerdata->excise_registration_no }}</p>
          <p><strong>CIN No:</strong> {{ $headerdata->cin_no }}</p>
          <p><strong>PAN No:</strong> {{ $headerdata->pan_no }}</p>
          <p><strong>Active:</strong> {{ $headerdata->active }}</p>
        </div>
      </div>

      <hr class="my-4">

      <h5 class="mb-3 text-danger">Company Locations</h5>
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
          <thead class="table-light">
            <tr>
              <th scope="col">Line No</th>
              <th scope="col">Location Name</th>
              <th scope="col">Description</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($linesdata as $key => $value)
            <tr>
              <td>{{ $key + 1 }}</td>
              <td>{{ $value->location_name }}</td>
              <td>{{ $value->description }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

@endsection













