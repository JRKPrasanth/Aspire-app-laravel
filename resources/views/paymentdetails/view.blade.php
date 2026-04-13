@extends('layouts.header')
@section('content')
<h3 class="text-danger">Payments</h3>
@include('layouts.breadcrumb')


<form>
	
  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h5 class="mb-0"><i class="fa fa-money-bill-wave me-2"></i>Payment Details</h5>
      <a href="{{ URL::to('paymentsindex') }}" class="btn btn-sm btn-danger">
        <i class="fa fa-times"></i>
      </a>
    </div>

    <div class="card-body">

      <div class="row g-4">
        <!-- Column 1 -->
        <div class="col-md-3">
          <dl class="row mb-0">
            <dt class="col-6 fw-semibold">Payment No:</dt>
            <dd class="col-6">{!! $row->payment_number !!}</dd>

            <dt class="col-6 fw-semibold">Payment Date:</dt>
            <dd class="col-6">{!! $row->payment_date !!}</dd>

            <dt class="col-6 fw-semibold">Payment Type:</dt>
            <dd class="col-6">{!! $row->payment_type_id !!}</dd>

            <dt class="col-6 fw-semibold">Supplier Name:</dt>
            <dd class="col-6">{!! $row->supplier_name !!}</dd>

            <dt class="col-6 fw-semibold">Supplier No:</dt>
            <dd class="col-6">{!! $row->supplier_account_no !!}</dd>

            <dt class="col-6 fw-semibold">IFSC Code:</dt>
            <dd class="col-6">{!! $row->supplier_ifsc_code !!}</dd>
          </dl>
        </div>

        <!-- Column 2 -->
        <div class="col-md-3">
          <dl class="row mb-0">
            <dt class="col-6 fw-semibold">Invoice No:</dt>
            <dd class="col-6">{!! $row->bill_number !!}</dd>

            <dt class="col-6 fw-semibold">Payment Amount:</dt>
            <dd class="col-6">{!! $row->payment_amount !!}</dd>

            <dt class="col-6 fw-semibold">Bank Name:</dt>
            <dd class="col-6">{!! $row->bank_name !!}</dd>

            <dt class="col-6 fw-semibold">Account No:</dt>
            <dd class="col-6">{!! $row->account_number !!}</dd>

            <dt class="col-6 fw-semibold">Account Name:</dt>
            <dd class="col-6">{!! $row->supplier_account_name !!}</dd>
          </dl>
        </div>

        <!-- Column 3 -->
        <div class="col-md-3">
          <dl class="row mb-0">
            <dt class="col-6 fw-semibold">Invoice Amt:</dt>
            <dd class="col-6">{!! $row->invoice_amount !!}</dd>

            <dt class="col-6 fw-semibold">Advance Amt:</dt>
            <dd class="col-6">{!! $row->advance_amount !!}</dd>

            <dt class="col-6 fw-semibold">Paid Amt:</dt>
            <dd class="col-6">{!! $row->paid_amount ?? $row->advance_amount !!}</dd>

            <dt class="col-6 fw-semibold">Bank Date:</dt>
            <dd class="col-6">{!! $row->bank_date !!}</dd>
          </dl>
        </div>

        <!-- Column 4 -->
        <div class="col-md-3">
          <dl class="row mb-0">
            <dt class="col-6 fw-semibold">Balance Amt:</dt>
            <dd class="col-6">{!! $row->balance_amount !!}</dd>

            <dt class="col-6 fw-semibold">UTR No:</dt>
            <dd class="col-6">{!! $row->payment_reference !!}</dd>

            <dt class="col-6 fw-semibold">Account Code:</dt>
            <dd class="col-6">{!! $row->account_code_id !!}</dd>

            <dt class="col-6 fw-semibold">Narration:</dt>
            <dd class="col-6">{!! $row->remarks !!}</dd>

            <dt class="col-6 fw-semibold">Cheque No:</dt>
            <dd class="col-6">{!! $row->cheque_no !!}</dd>
          </dl>
        </div>
      </div>
    </div>
  </div>
	
</form>

     

@endsection