@extends('layouts.header')
@section('content')
<h3 class="text-danger"> Payment For Invoice Balance </h3>
@include('layouts.breadcrumb')
  
  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4 table-responsive">
      <table id="AccTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Invoice Number</th>
            <th>Invoice Date</th>
			<th>Invoice Status</th>
            <th>Balance Amount</th>
		    <th>Customer Name</th>
            <th>Actions</th>
          </tr>
          <tr class="table-danger">
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
        ajax: "getBlnceInvoicedetailsData",
        columns: [
        { data: 'invoice_number' },
        { data: 'invoice_date' },
        { data: 'invoice_status' },
        { data: 'balance_amount' },
        { data: 'customer_name' },

          {
            data: 'invoice_hdr_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';
 
                buttons += `
        <button class="btn btn-sm btn-warning pay-btn" data-id="${row.invoice_hdr_id}"
		data-bs-toggle="tooltip" 
		data-bs-placement="top" 
		title="Create Payment">
          <i class="bi bi-plus"></i>
        </button>`;
             
              return buttons;
            }
          }
        ]
      });

      // Individual column search
      $('#AccTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });		
	

// payment	
    $(document).on('click', '.pay-btn', function () {
      const id = $(this).data('id');

   window.location.replace('paymentforinvoiceblncecreate/'+id);

    });	
	

 </script>

@endpush
