@extends('layouts.header')
@section('content')
<h3 class="text-danger">Debit/Credit Note View</h3>
@include('layouts.breadcrumb')

		
<form>
  <div class="card shadow-lg border-0 rounded-4">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h4 class="mb-0"><i class="bi bi-receipt"></i> Debit/Credit Note </h4>
      <a href="../debitcreditnote" class="btn btn-danger btn-sm rounded-circle" title="Close">
        <i class="bi bi-x-lg"></i>
      </a>
    </div>

    <div class="card-body card-block normalform">
      <div class="row">
        <div class="col-md-12">
          <div class="invoice-box" id="section-to-print">

            <table class="table border-0">
              <tr>
                <td>
                  <p><b>Debit/Credit No:</b> {!! $debitcredit_no !!}</p>
                  <p><b>Debit/Credit Date:</b> {!! $debitcredit_date !!}</p>
                  <p><b>Debit/Credit Type:</b> {!! $debitcredit_type !!}</p>
                  <p><b>Debit/Credit Amount:</b> {!! $debitcredit_amount !!}</p>
                  <p><b>Reference No:</b> {!! $invoice !!}</p>
                </td>
                <td class="text-right">
                  <p><b>Source:</b> {!! $source_type !!}</p>
                  <p><b>Invoice Number:</b> {!! $invoice !!}</p>
                  <p><b>Supplier Name:</b> {!! $supplier_name !!}</p>
                  <p><b>Customer Name:</b> {!! $customer_name !!}</p>
                  <p><b>Created By:</b> {!! $username !!}</p>
                </td>
              </tr>
            </table>

            <div id="preview-area" class="mt-4">
              <table class="table table-bordered table-hover">
                <thead class="table-warning">
                  <tr>
                    <th>Description</th>
                    <th>Account Code</th>
                    <th>Line Amount</th>
                    <th>HSN Code</th>
                    <th>Tax Group</th>
                    <th>Tax Amount</th>
                    <th>Remarks</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($vlinesdata as $key => $value)
                    <tr>
                      <td>{{ $value->description }}</td>
                      <td>{{ $value->creditdebit_account }}</td>
                      <td>{{ $value->debitcredit_line_amount }}</td>
                      <td>{{ $value->classification_code }}</td>
                      <td>{{ $value->tax_group_name }}</td>
                      <td>{{ $value->tax_amount }}</td>
                      <td>{{ $value->remarks }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>


			




@endsection
                 