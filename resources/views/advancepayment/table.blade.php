@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Advance Payment</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4 table-responsive">
      <table id="AccTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">

            <th>Actions</th>
            <th>PO Number</th>
            <th>PO Date</th>
            <th>PO Type</th>
            <th>Supplier Name</th>
            <th>PO Amount</th>
            <th>Advance Amount</th>
            <th>PO Status</th>
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

    // data table funcrion	
    $(document).ready(function () {

      var table = $('#AccTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "getPodetailsData",
        columns: [

          {
            data: 'po_hdr_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'create')) {
                buttons += `
          <button class="btn btn-sm btn-primary create-btn" data-id="${row.po_hdr_id}"
              data-bs-toggle="tooltip" 
      data-bs-placement="top" 
      title="Create Payment">
            <i class="bi bi-plus"></i>
          </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `
          <button class="btn btn-sm btn-warning view-btn" data-id="${row.po_hdr_id}"
              data-bs-toggle="tooltip" 
      data-bs-placement="top" 
      title="PO View">
            <i class="bi bi-eye"></i>
          </button>`;
              }



              return buttons;
            }
          },
          { data: 'po_number', name: 'po_number' },
          { data: 'po_date', name: 'po_date' },
          { data: 'po_type', name: 'po_type' },
          { data: 'supplier_name', name: 'supplier_name' },
          { data: 'po_grand_total', name: 'po_grand_total' },
          { data: 'balance_amount', name: 'balance_amount' },
          { data: 'po_status', name: 'po_status' },
          { data: 'remarks', name: 'remarks' }


        ]
      });

      // Individual column search
      $('#AccTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });


    //create function
    $(document).on('click', '.create-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('advancepaymentcreate') }}/" + id;
      window.location.href = url;
    });


    //view function
    $(document).on('click', '.view-btn', function () {
      const id = $(this).data('id');
      var module = "{{$pageMethod}}";

      const url = "{{ url('purchaseorderview') }}/" + id + '?return=' + module;
      window.location.href = url;
    });



  </script>

@endpush