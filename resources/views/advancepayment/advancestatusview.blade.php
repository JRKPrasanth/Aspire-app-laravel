@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Advance Payment Status</h3>
  @include('layouts.breadcrumb')
  <div id="toolbar-container" class="create mb-3 mt-1"></div>
  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4 table-responsive">
      <table id="AccTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>PO Number</th>
            <th>PO Date</th>
            <th>PO Type</th>
            <th>Supplier Name</th>
            <th>PO Amount</th>
            <th>Advance Amount</th>
            <th>Remarks</th>
          </tr>
          <tr class="table-danger">
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>


@endsection
@push('scripts')



  <script>

    $(document).ready(function () {

      var table = $('#AccTbl').DataTable({
        processing: true,
        serverSide: true,
        order:[[1, 'desc']],
        ajax: "getadvancestatusviewData",
        columns: [
          { data: 'po_number', name: 'po_number', className: 'text-center' },
          { data: 'po_date', name: 'po_date', className: 'text-center' },
          { data: 'po_type', name: 'po_type', className: 'text-center' },
          { data: 'supplier_name', name: 'supplier_name', className: 'text-center' },
          { data: 'po_grand_total', name: 'po_grand_total', className: 'text-center' },
          { data: 'advance_amount', name: 'advance_amount', className: 'text-center' },
          { data: 'remarks', name: 'remarks', className: 'text-center' }
        ]
      });

      // Individual column search
      $('#AccTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });


  </script>

@endpush