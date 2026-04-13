@extends('layouts.header')
@section('content')
<h3 class="text-danger"> Adjustment </h3>
@include('layouts.breadcrumb')


<div class="container-fluid py-4">
<div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="bi bi-sliders2 me-2"></i>Adjustment Details</h4>
            <a href="../journaladjustments" class="btn btn-danger btn-sm">
                <i class="bi bi-x-lg"></i> Close
            </a>
        </div>

        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="p-3 border rounded bg-light shadow-sm h-100">
                        <p class="mb-2"><strong>Adjustment Date:</strong> {!! $adjustment_date !!}</p>
                        <p class="mb-2"><strong>Adjustment Status:</strong> 
                            <span class="badge bg-{{ $adjustment_status == 'APPROVED' ? 'success' : ($adjustment_status == 'REJECTED' ? 'danger' : 'warning') }}">
                                {!! $adjustment_status !!}
                            </span>
                        </p>
                        <p class="mb-2"><strong>Account Type:</strong> {!! $account_type !!}</p>
                        <p class="mb-0"><strong>Adjustment Amount:</strong> 
                            <span class="text-success fw-semibold">₹ {!! number_format($adjustment_amount, 2) !!}</span>
                        </p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 border rounded bg-light shadow-sm h-100">
                        <p class="mb-2"><strong>Account Code:</strong> {!! $concatenated_segments !!}</p>
                        <p class="mb-2"><strong>Description:</strong> {!! $description !!}</p>
                        <p class="mb-0"><strong>Reason Code:</strong> {!! $reason_code !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
