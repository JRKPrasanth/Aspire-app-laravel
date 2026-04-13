@extends('layouts.header')
@section('content')
<h3 class="text-danger">Service Receipt Note</h3>
@include('layouts.breadcrumb')
<div id="toolbar-container" class="mb-3 mt-2"></div>


<div class="card shadow-lg rounded-4 border-0">
  <div class="container mt-4">
    <div class="table-responsive">
      <table id="SrnTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">

            <th>Actions</th>
            <th>GRN Number</th>
            <th>GRN Status</th>
            <th>Source</th>
            <th>DC Number</th>
            <th>DC Date</th>
            <th>Supplier Name</th>
            <th>PO Number</th>
            <th></th>
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
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
          </tr>
        </thead>
        <tbody>
          {{-- DataTable will populate via AJAX --}}
        </tbody>
      </table>
    </div>
  </div>
</div>


@endsection
@push('scripts')

<script>


		   // button purpose
  // Add create button purpose
  $(document).ready(function () {

    if (window.toolbarButtons?.some(btn => btn.attr.id === 'create_labourpo')) {
      $('#toolbar-container').append(`
            <button class="btn btn-success generate-btn me-2">Create From Labour PO
               <i class="bi bi-plus-circle"></i> 
            </button>
          `);
    }
	  
	  });	  
	  // table data

  $(document).ready(function () {

    var table = $('#SrnTbl').DataTable({
      processing: true,
      serverSide: true,
      ajax: "getSrnData",

      columns: [

        {

          data: 'grn_id',
          name: 'actions',
          orderable: false,
          searchable: false,
          render: function (data, type, row) {
            let buttons = '';
            if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
              buttons += `
				<button type="button" class="btn btn-sm btn-primary edit-btn"
				  data-id="${row.grn_id}"
				  data-status="${row.grn_status}">
				  <i class="bi bi-pencil"></i>
				</button>`;
            }

            if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
              buttons += `
					<button type="button" class="btn btn-sm btn-warning view-btn"
					  data-id="${row.grn_id}">
					  <i class="bi bi-eye"></i>
					</button>`;
            }


            return buttons;
          }

        },

        { data: 'grn_number', name: 'grn_number' },
        { data: 'grn_status', name: 'grn_status' },
        { data: 'source', name: 'source' },
         { data: 'dc_number', name: 'dc_number' },
        { data: 'dc_date', name: 'dc_date' },
        { data: 'supplier_name', name: 'supplier_name' },
         { data: 'po_number', name: 'po_number' },
         { data: 'po_invoice_status', name: 'po_invoice_status', visible:false },
      ]
    });


    $('#SrnTbl thead').on('keyup change', '.column-search', function () {
      let index = $(this).closest('th').index();
      table.column(index).search(this.value).draw();
    });
  });	
	

	// edit
	$(document).on('click', '.edit-btn', function () {
	
  const id = $(this).data('id');
  const status = $(this).data('status');


            if( status !="INITIATED") {
		window.location.replace('srnedit/'+id);
            }else{
                 showCustomAlert("Submitted GRN Cannot Be Edit",'warning');
            }
	
});	
	
	// view
	$(document).on('click', '.view-btn', function () {
	
  const id = $(this).data('id');
	
	window.location.replace('grnview/' +id+'/2');
	
});	
	
	// create
	
	$(document).on('click', '.generate-btn', function () {
	
    var url="{{ url('purchaselabourtable') }}";
    window.location.replace(url);
	
});	
	
	

	
	
// extra
	
	
/*Karthigaa Purpose For CREATE FROM GIN Function*/
$("#create_gin").click(function(){
    var url="{{ url('Gintable') }}";
    window.location.replace(url);
});
	
/*Karthigaa Purpose For Direct Generate GRN Function*/
$("#create_grn").click(function(){
		window.location.replace('genrategrn/0');
});

	
	$("#print").click(function(){
        var index = $("#srngrid").jqGrid('getGridParam','selrow');
	     var grn_id = $("#srngrid").jqGrid ('getCell', index, 'grn_id');
	     var po_invoice_status = $("#srngrid").jqGrid ('getCell', index, 'po_invoice_status');
       if( index )
       {
		   if(po_invoice_status =="APPROVED")
		   {
		   window.open('grnprint/' +grn_id,'_blank');
		   }
		   else
		   {
			   notyMsg("info","Please Approve the Invoice First");
		   }

       }
      else
      {
        notyMsg("info","Please Select a Row");
      }

});


	
</script>

@endpush
