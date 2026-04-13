@extends('layouts.header')
@section('content')

<form>
<div class="card shadow-lg rounded-4 border-0">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0 text-danger">Quality Check</h4>
      <a href="{{ URL::to('purchaseqc') }}" class="btn btn-danger btn-sm">Close</a>
    </div>

    <div class="card-body">
      <div class="mb-4">
        <h5 class="text-primary border-bottom pb-2">QC Header Information</h5>
        <div class="row">
          <div class="col-md-4">
            <p><strong>QC No:</strong> {{ $row->qc_number }}</p>
            <p><strong>QC Date:</strong> {{ $row->qc_date }}</p>
            <p><strong>QC Status:</strong> {{ $row->qc_status }}</p>
          </div>
          <div class="col-md-4">
            <p><strong>Description:</strong> {{ $row->description }}</p>
            <p><strong>DC Number:</strong> {{ $row->dc_number }}</p>
            <p><strong>DC Date:</strong> {{ $row->dc_date }}</p>
          </div>
          <div class="col-md-4">
            <p><strong>GRN Number:</strong> {{ $row->grn_number }}</p>
            <p><strong>PO No:</strong> {{ $row->po_number }}</p>
          </div>
        </div>
      </div>

      <div class="mb-4">
        <h5 class="text-primary border-bottom pb-2">Line Item Details</h5>
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead class="table-light">
              <tr>
                <th>Line No</th>
                <th>Product Name</th>
                <th>UOM Code</th>
                <th>Box Qty</th>
                <th>Total Box Qty</th>
                <th>Accepted Qty</th>
                <th>Rejected Qty</th>
                <th>Reason</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($linedata as $key => $value)
                <tr>
                  <td>{{ $key + 1 }}</td>
                  <td>{{ $value->product_code }} - {{ $value->product_id }}</td>
                  <td>{{ $value->uom_code_id }}</td>
                  <td>{{ $value->box_qty }}</td>
                  <td>{{ $value->total_box_qty }}</td>
                  <td>{{ $value->accept_qty }}</td>
                  <td>{{ $value->reject_qty }}</td>
                  <td>{{ $value->reason }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      @if($spec != null)
        <div class="mb-4">
          <h5 class="text-primary border-bottom pb-2">Product Quality Check Details</h5>
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead class="table-light">
                <tr>
                  <th>Line No</th>
                  <th>Parameter</th>
                  <th>Specification Criteria</th>
                  <th>Spec Value From</th>
                  <th>Spec Value To</th>
                  <th>Measurement</th>
                  <th>Remarks</th>
                  <th>Comments</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($spec as $key1 => $value1)
                  <tr>
                    <td>{{ $key1 + 1 }}</td>
                    <td>{{ $param[$key1] }}</td>
                    <td>{{ $spec[$key1] }}</td>
                    <td>{{ $specvaluefrom[$key1] }}</td>
                    <td>{{ $specvalueto[$key1] }}</td>
                    <td>{{ $measure[$key1] }}</td>
                    <td>{{ $remarks }}</td>
                    <td>{{ $comments }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      @endif

    </div>
  </div>
</form>

@endsection






