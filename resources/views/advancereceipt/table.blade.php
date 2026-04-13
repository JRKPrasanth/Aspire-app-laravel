@extends('layouts.header')
@section('content')
<h3 class="text-danger"> Advance Receipt </h3>
@include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <table id="ReciptTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Sales Order No</th>
            <th>SO Date</th>
            <th>Order Status</th>
            <th>Customer Name</th>
            <th> Status</th>
            <th>Actions</th>
          </tr>
          <tr class="table-info">
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

      var table = $('#ReciptTbl').DataTable({
        processing: true,
        serverSide: true,
        order: [[1, 'desc']],
        ajax:  "advancesodata",
        columns: [
          { data: 'sales_order_no', name: 'sales_order_no' },
          { data: 'sales_order_date', name: 'sales_order_date' },
          { data: 'order_status_id', name: 'order_status_id' },
          { data: 'ship_to_customer_id', name: 'ship_to_customer_id' },
          { data: 'savestatus', name: 'savestatus' },
          {
            data: 'sales_hdr_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';
				
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `
        <button class="btn btn-sm btn-warning view-btn me-1" data-id="${row.sales_hdr_id}">
          <i class="bi bi-eye"></i>
        </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'create')) {
                buttons += `
        <button class="btn btn-sm btn-success create-btn" data-id="${row.sales_hdr_id}"
				data-bs-toggle="tooltip" 
		data-bs-placement="top" 
		title="Create Receipt">
          <i class="bi bi-plus"></i>
        </button>`;
              }
              return buttons;
            }
          }
        ]
      });

      // Individual column search
      $('#ReciptTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });
	

/* Purpose For Create*/
	$(document).on('click', '.create-btn', function () {
		
		 const id = $(this).data('id');
	  
       window.location.replace('advancereceiptcreate/' +id);


});

	
    /* Purpose For View Function*/
	
 		$(document).on('click', '.view-btn', function () {
		   
		    const id = $(this).data('id');
			var return1="{{$pageMethod}}";
			
		window.location.replace('soorderview/' +id+'?return='+return1);

});
   
	
</script>

@endpush