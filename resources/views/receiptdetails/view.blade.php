@extends('layouts.header')
@section('content')
<h3 class="text-danger">Receipts Details</h3>
@include('layouts.breadcrumb')


<div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Receipts Index Details</h5>
        <a href="{{URL::to('receiptsindex')}}" class="btn btn-sm btn-danger">
            <i class="bi bi-x-circle"></i>
        </a>
    </div>

    <div class="card-body">
        <div class="row mb-3">
            <!-- Invoice & Customer -->
            <div class="col-md-4">
                <p class="mb-2"><strong>Invoice No:</strong> {!! $invoice_number !!}</p>
                <p class="mb-2"><strong>Customer Name:</strong> {!! $customer_name !!}</p>
                <p class="mb-2"><strong>Invoice Amount:</strong> {!! $invoice_amount !!}</p>
                <p class="mb-0"><strong>Receipt Date:</strong> {!! $receipt_date !!}</p>
            </div>

            <!-- Receipt Info -->
            <div class="col-md-4">
                <p class="mb-2"><strong>Receipt Status:</strong> {!! $receipt_status !!}</p>
                <p class="mb-2"><strong>Account Code:</strong> {!! $concatenated_segments !!}</p>
                <p class="mb-2"><strong>Receipt Type:</strong> {!! $receipt_type_id !!}</p>
                <p class="mb-0"><strong>Receipt Reference:</strong> {!! $receipt_reference !!}</p>
            </div>

            <!-- Amounts -->
            <div class="col-md-4">
                <p class="mb-2"><strong>Receipt Amount:</strong> {!! $receipt_amount !!}</p>
                <p class="mb-2"><strong>Paid Amount:</strong> {!! $paid_amount !!}</p>
                <p class="mb-2"><strong>Balance Amount:</strong> {!! $balance_amount !!}</p>
                <p class="mb-0"><strong>Remarks:</strong> {!! $remarks !!}</p>
            </div>
        </div>
    </div>
</div>


@endsection

