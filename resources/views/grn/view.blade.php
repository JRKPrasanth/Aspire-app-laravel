@extends('layouts.header')
@section('content')
<h3 class="text-danger">Goods Receipt Note Details</h3>
@include('layouts.breadcrumb')


<form>
  <div class="card shadow border-0">
    
    <!-- Header -->
    <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
      <h5 class="mb-0">Goods Receipt Note Details</h5>
      <a href="{{ URL::to('grn') }}" class="btn btn-danger btn-sm">
        <i class="bi bi-x-circle"></i> Close
      </a>
    </div>

    <!-- Body -->
    <div class="card-body">
      
      <!-- GRN Details -->
      <div class="row mb-4">
        
        <!-- Left Column -->
        <div class="col-md-6">
          <p><strong>GRN Number:</strong> {!! $row->grn_number !!}</p>
          <p><strong>GRN Description:</strong> {!! $row->grn_description !!}</p>
          <p><strong>DC Number:</strong> {!! $row->dc_number !!}</p>
          <p><strong>DC Date:</strong> {!! $row->dc_date !!}</p>
          <p><strong>GRN Status:</strong> {!! $grn_status !!}</p>
          <p><strong>Supplier Name:</strong> {!! $supplier_name !!}</p>
          <p><strong>Subcontractor Name:</strong> {!! $subcontract_supplier_name !!}</p>
        </div>

        <!-- Right Column -->
        <div class="col-md-6">
          <p><strong>Reference Number:</strong> {!! $row->reference_number !!}</p>
          <p><strong>Source:</strong> {!! $row->source !!}</p>
          @if($row->reference_number != 'DIRECT GRN')
            <p><strong>PO No:</strong> {!! $po_number_name !!}</p>
          @endif
          @if($row->source == 'PO')
            <p><strong>PO Date:</strong> {!! $row->po_date !!}</p>
          @endif
          <p><strong>Total Product Packs:</strong> {!! $row->total_packs !!}</p>
        </div>

      </div>

      <!-- Line Items -->
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
          <thead class="table-light">
            <tr>
              <th>Line No</th>
              <th>Product Name</th>
              <th>Description</th>
              <th>UOM Code</th>
              <th>Ordered Qty</th>
              <th>Pending Qty</th>
              <th>Box Qty</th>
              <th>Box Product Qty</th>
              <th>Total Product Qty</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($linedata as $key => $value)
            <tr>
              <td>{{ $key+1 }}</td>
              <td>{{ $value->product_code }} - {{ $value->product_id }}</td>
              <td>{{ $value->packed_discription }}</td>
              <td>{{ $value->uom_code_id }}</td>
              <td>{{ $value->qty }}</td>
              <td>{{ $value->pending_qty }}</td>
              <td>{{ $value->box_qty }}</td>
              <td>{{ $value->box_product }}</td>
              <td>{{ $value->receive_qty }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

    </div>
  </div>
</form>



@endsection