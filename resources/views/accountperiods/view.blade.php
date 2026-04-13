@extends('layouts.header')
@section('content')
<h3 class="text-danger"></h3>
@include('layouts.breadcrumb')


<div class="card shadow-lg rounded-4 border-0">
    <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
        <h5 class="mb-0">Account Periods</h5>
        <a href="../accountperiods" class="btn btn-sm btn-danger">
            <i class="bi bi-x-lg"></i>
        </a>
    </div>

    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <p><strong>Month:</strong> {!! $month !!}</p>
                <p><strong>Year:</strong> {!! $year !!}</p>
                <p><strong>Period Name:</strong> {!! $period_name !!}</p>
                <p><strong>From Date:</strong> {!! $from_date !!}</p>
            </div>
            <div class="col-md-6">
                <p><strong>To Date:</strong> {!! $to_date !!}</p>
                <p><strong>Quarter No:</strong> {!! $quarter_no !!}</p>
                <p><strong>Period Status:</strong> {!! $period_status !!}</p>
                <p><strong>Company Name:</strong> {!! $company_name !!}</p>
                <p><strong>Created By:</strong> {!! $first_name !!}</p>
            </div>
        </div>
    </div>
</div>
				
        		

@endsection



