@extends('layouts.header')
@section('content')

<form>
  <div class="card shadow-lg border-0 rounded-3">
    <!-- Header -->
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h5 class="mb-0">
        <?php if($url=="purchasereplacementapproval") { ?>
          Purchase Return Approval Details
        <?php } else { ?>
          Purchase Replacement Details
        <?php } ?>
      </h5>
      <a href="{{url($url)}}" class="btn btn-sm btn-danger closeurl">
        <i class="bi bi-x-lg"></i> Close
      </a>
    </div>

    <!-- Body -->
    <div class="card-body">
      <div id="section-to-print" class="invoice-box">

        <!-- Top Details -->
        <div class="row mb-4">
          <div class="col-md-6">
            <p><strong>Replacement Invoice Number:</strong> {!! $row[0]->replacement_no !!}</p>
            <p><strong>Replacement Date:</strong> {!! $row[0]->replacement_date !!}</p>
            <p><strong>Replacement Status:</strong> {!! $row[0]->replacement_status !!}</p>
            <p><strong>GRN Number:</strong> {!! $row[0]->grn_number !!}</p>
          </div>
          <div class="col-md-6 text-md-end">
            <p><strong>Supplier Name:</strong> {!! $supplier_id !!}</p>
            <p><strong>PO Number:</strong> {!! $row[0]->po_number !!}</p>
            <p><strong>PO Date:</strong> {!! $row[0]->po_date !!}</p>
          </div>
        </div>

        <!-- Line Items -->
        <div class="table-responsive">
          <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th>Line No</th>
                <th>Product Name</th>
                <th>UOM Code</th>
                <th>Stock Update</th>
                <th>Batch Number</th>
                <th>Qty</th>
                <th>Comments</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($linedata as $key=>$value)
              <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $value->product_id }}</td>
                <td>{{ $value->uom_code_id }}</td>
                <td>{{ $value->stock_update }}</td>
                <td>{{ $value->batch_number }}</td>
                <td>{{ $value->qty }}</td>
                <td>{{ $value->comments }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</form>


@endsection
