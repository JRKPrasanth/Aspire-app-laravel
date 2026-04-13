@extends('layouts.header')
@section('content')
<h3 class="text-danger">User Details</h3>

<form>
<div class="card shadow-lg rounded-4 border-0">
    <div class="card-header d-flex justify-content-between align-items-center">
		<h5 class="mb-0"></h5>
      <a href="{{ url('user') }}" class="btn btn-sm btn-white bg-primary">
        <i class="bi bi-x-lg"></i> Close
      </a>
    </div>

    <div class="card-body">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12 mb-3">
            <h4 class="text-primary border-bottom pb-2">User Information</h4>
          </div>

          <div class="col-md-4 mb-3">
            <p><strong>User Name:</strong> {!! $user_name !!}</p>
            <p><strong>First Name:</strong> {!! $first_name !!}</p>
            <p><strong>Location:</strong> {!! $loc_id !!}</p>
            <p><strong>Mobile No:</strong> {!! $mobile_no !!}</p>
          </div>

          <div class="col-md-4 mb-3">
            <p><strong>Group Name:</strong> {!! $group_id !!}</p>
            <p><strong>Last Name:</strong> {!! $last_name !!}</p>
            <p><strong>Department:</strong> {!! $admindept_id !!}</p>
          </div>

          <div class="col-md-4 mb-3">
            <p><strong>Organization:</strong> {!! $org_id !!}</p>
            <p><strong>Company:</strong> {!! $company_id !!}</p>
            <p><strong>Email:</strong> {!! $email !!}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>

@endsection
