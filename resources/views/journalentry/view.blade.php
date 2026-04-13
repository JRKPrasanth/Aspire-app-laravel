@extends('layouts.header')
@section('content')
<h3 class="text-danger">Journal Entry Details</h3>
@include('layouts.breadcrumb')


<div class="card shadow-lg rounded-4 border-0">
  <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white rounded-top-4">
    <h5 class="mb-0 fw-bold">Journal Entry Details</h5>
    <a href="../journalentry" class="btn btn-danger btn-sm">
      <i class="fa fa-times"></i> close
    </a>
  </div>

  <div class="card-body">

    <!-- Journal Info -->
    <div class="row mb-4">
      <div class="col-md-4">
        <p><strong>Journal Name:</strong> {!! $journal_name !!}</p>
        <p><strong>Journal Status:</strong> {!! $journal_status !!}</p>
      </div>
      <div class="col-md-4">
        <p><strong>Journal Date:</strong> {!! $journal_date !!}</p>
        <p><strong>Journal Category:</strong> {!! $journal_category !!}</p>
      </div>
      <div class="col-md-4">
        <p><strong>Journal Type:</strong> {!! $journal_type !!}</p>
      </div>
    </div>

    <!-- Journal Lines Table -->
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-light text-center">
          <tr>
            <th>Line No</th>
            <th>Journal Date</th>
            <th>Ref</th>
            <th>Ref Name</th>
            <th>Account</th>
            <th>Debit Amount</th>
            <th>Credit Amount</th>
          </tr>
        </thead>
        <tbody>
          <?php $debit_total = 0; $credit_total = 0; ?>
          @foreach ($vlinesdata as $key => $value)
          <?php 
            $debit_total += $value->debit_amount;
            $credit_total += $value->credit_amount;
          ?>
          <tr class="text-center">
            <td>{{ $key + 1 }}</td>
            <td>{{ $value->journal_date }}</td>
            <td>{{ $value->reference_source }}</td>
            <td>{{ $value->ref_name }}</td>
            <td>{{ $value->concatenated_segments }}</td>
            <td>{{ number_format($value->debit_amount, 2) }}</td>
            <td>{{ number_format($value->credit_amount, 2) }}</td>
          </tr>
          @endforeach
          <tr class="fw-bold text-center table-secondary">
            <td colspan="5">Total</td>
            <td>{{ number_format($debit_total, 2) }}</td>
            <td>{{ number_format($credit_total, 2) }}</td>
          </tr>
        </tbody>
      </table>
    </div>

  </div>
</div>

        	
        	

@endsection