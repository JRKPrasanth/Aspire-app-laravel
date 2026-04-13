@extends('layouts.header')
@section('content')
<h3 class="text-danger">Sales Return</h3>
@include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <table id="SalesTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Invoice Number</th>
            <th>Invoice Date </th>
            <th>Invoice Type </th>
            <th>Invoice Status </th>
            <th>Customer Name</th>
            <th>Pricelist</th>
            <th>SO Invoice Status</th>
            <th>Actions</th>
          </tr>
          <tr class="table-info">
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


      var table = $('#SalesTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "salesinvoicereturndata",
        columns: [

          { data: 'invoice_number', name: 'invoice_number' },
          { data: 'invoice_date', name: 'invoice_date' },
          { data: 'invoice_type', name: 'invoice_type' },
          { data: 'invoice_status', name: 'invoice_status' },
          { data: 'customer_name', name: 'customer_name' },
          { data: 'pricelist_name', name: 'pricelist_name' },
          { data: 'savestatus', name: 'savestatus' },
          {
            data: 'invoice_hdr_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'return')) {
                buttons += `
                <button class="btn btn-sm btn-danger return-btn" data-id="${row.invoice_hdr_id}">
                  Return
                </button>`;
              }

              return buttons;
            }
          }
        ]
      });

      // Individual column search
      $('#SalesTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });
	
	
        //Return function
        $(document).on('click', '.return-btn', function () {
        
                const id = $(this).data('id');

            window.location.replace('salesreturnfrominvoice/' +id+"?status=INVOICE");

            });	
	
</script>

@endpush
