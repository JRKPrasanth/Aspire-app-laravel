@extends('layouts.header')
@section('content')
<h3 class="text-danger">Expense View</h3>
@include('layouts.breadcrumb')


			
<div class="container-fluid mt-4">

  <div class="card shadow-lg border-0 rounded-4">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h4 class="mb-0"><i class="bi bi-receipt"></i> Expenses</h4>
      <a href="../expenses" class="btn btn-danger btn-sm rounded-circle" title="Close">
        <i class="bi bi-x-lg"></i>
      </a>
    </div>

    <div class="card-body" id="section-to-print">
      <h5 class="text-uppercase text-primary fw-bold mb-3 border-bottom pb-2">Expense Summary</h5>

      <div class="row mb-4">
        <div class="col-md-6">
          <p><strong>Expense No:</strong> {!! $expense_no !!}</p>
          <p><strong>Expense Date:</strong> {!! $expense_date !!}</p>
          <p><strong>Expense Type:</strong> {!! $expense_type !!}</p>
          <p><strong>Expense Amount:</strong> {!! $expense_amount !!}</p>
          <p><strong>Reference No:</strong> {!! $invoice !!}</p>
          <p><strong>Remarks:</strong> {!! $remarks !!}</p>
        </div>

        <div class="col-md-6">
          <p><strong>Supplier Name:</strong> {!! $supplier_name !!}</p>
          <p><strong>Customer Name:</strong> {!! $customer_name !!}</p>
          <p><strong>Employee Name:</strong> {!! $employee_name !!}</p>
          <p><strong>TDS Applicable:</strong> {!! $tds_applicable !!}</p>
          <p><strong>TDS Percentage:</strong> {!! $tds_prcnt !!}</p>
          <p><strong>TDS Amount:</strong> {!! $tds_amount !!}</p>
          <p><strong>TDS Account:</strong> {!! $tds_account !!}</p>
          <p><strong>Created By:</strong> {!! $username !!}</p>
        </div>
      </div>

      <h5 class="text-uppercase text-primary fw-bold mb-3 border-bottom pb-2">Expense Line Details</h5>

      <div class="table-responsive">
        <table class="table table-bordered align-middle table-hover shadow-sm">
          <thead class="table-primary text-center">
            <tr>
              <th>Expense Account</th>
              <th>Expense Amount</th>
              <th>HSN Code</th>
              <th>Tax Group</th>
              <th>Tax Amount</th>
              <th>Remarks</th>
              <th>File Attachment</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($vlinesdata as $key=>$value)
            <tr>
              <td>{{ $value->expense_account }}</td>
              <td class="text-end">{{ number_format($value->expense_line_amount,2) }}</td>
              <td>{{ $value->classification_code }}</td>
              <td>{{ $value->tax_group_name }}</td>
              <td class="text-end">{{ number_format($value->tax_amount,2) }}</td>
              <td>{{ $value->remarks }}</td>
              <?php $v1 = str_replace(['[', '"', ']'], '', $value->choosefile); ?>
              <td>
                @if(!empty($v1))
                  <a href="{{ URL::to('') }}/Uploads/expense/{{ $value->expense_line_id }}/{{ $v1 }}" download class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-paperclip"></i> {{ $v1 }}
                  </a>
                @else
                  <span class="text-muted">No file</span>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

    </div>
  </div>

</div>



@endsection            