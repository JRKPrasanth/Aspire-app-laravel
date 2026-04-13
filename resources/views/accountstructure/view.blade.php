@extends('layouts.header')
@section('content')
<h3 class="text-danger">Account Structure Details</h3>
@include('layouts.breadcrumb')

		
<form>
   <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
            <h5 class="mb-0">Account Structure</h5>
            <a href="../accountstructure" class="btn btn-sm btn-danger">
                <i class="bi bi-x-circle"></i>
            </a>
        </div>

        <div class="card-body">
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-6 mb-3">
                    <div class="mb-2"><strong>Company Name:</strong> {!! $company_name !!}</div>
                    <div class="mb-2"><strong>Location Name:</strong> {!! $location_name !!}</div>
                    <div class="mb-2"><strong>Costcenter Name:</strong> {!! $depart !!}</div>
                    <div class="mb-2"><strong>Sub Costcenter1 Name:</strong> {!! $depart1 !!}</div>
                    <div class="mb-2"><strong>Sub Costcenter2 Name:</strong> {!! $depart2 !!}</div>
                    <div class="mb-2"><strong>Sub Costcenter3 Name:</strong> {!! $depart3 !!}</div>
                    <div class="mb-2"><strong>Active:</strong> {!! $active !!}</div>
                    <div class="mb-2"><strong>Created By:</strong> {!! $first_name !!}</div>
                </div>

                <!-- Right Column -->
                <div class="col-md-6 mb-3">
                    <div class="mb-2"><strong>Main Account Name:</strong> {!! $main_account_code !!}</div>
                    <div class="mb-2"><strong>Sub Account1:</strong> {!! $account_code !!}</div>
                    <div class="mb-2"><strong>Sub Account2:</strong> {!! $future_reference1 !!}</div>
                    <div class="mb-2"><strong>Sub Account3:</strong> {!! $future_reference2 !!}</div>
                    <div class="mb-2"><strong>Sub Account4:</strong> {!! $subaccount4 !!}</div>
                    <div class="mb-2"><strong>Account Name:</strong> {!! $account_name !!}</div>
                    <div class="mb-2"><strong>Concatenated Segments:</strong> 
                        <span class="badge bg-info text-dark">{!! $concatenated_segments !!}</span>
                    </div>
                    <div class="mb-2"><strong>Account Description:</strong> {!! $account_description !!}</div>
                </div>
            </div>
        </div>
    </div>
</form>

		
		
	

@endsection