@extends('layouts.header')
@section('content')
<h3 class="text-danger">Goods Receipt Note</h3>
@include('layouts.breadcrumb')
<div id="toolbar-container" class="mb-3 mt-2"></div>


<div class="card shadow-lg rounded-4 border-0">
  <div class="container mt-4">
    <div class="table-responsive">
      <table id="grnTbl" class="table table-bordered table-striped w-100" style="width:130% !important;">
        <thead>
          <tr class="table-warning">

            <th>Actions</th>
            <th>GIN Number</th>
            <th>GRN Status</th>
            <th>Source</th>
            <th>DC Number</th>
            <th>DC Date</th>
            <th>Supplier Name</th>
            <th>Subcontract Name</th>
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

    if (window.toolbarButtons?.some(btn => btn.attr.id === 'create_po')) {
      $('#toolbar-container').append(`
            <button class="btn btn-secondary create_po me-2">Create From PO
               <i class="bi bi-plus-circle"></i> 
            </button>
          `);
    }

    if (window.toolbarButtons?.some(btn => btn.attr.id === 'create_gin')) {
      $('#toolbar-container').append(`
            <button class="btn btn-success create_gin me-2">Create From GIN
               <i class="bi bi-plus-circle"></i> 
            </button>
          `);
    }

        if (window.toolbarButtons?.some(btn => btn.attr.id === 'create_grn')) {
      $('#toolbar-container').append(`
            <button class="btn btn-danger create_grns me-2">Direct GRN
               <i class="bi bi-plus-circle"></i> 
            </button>
          `);
    }

  });
	
	  // table data

  $(document).ready(function () {

    var table = $('#grnTbl').DataTable({
      processing: true,
      serverSide: false,
      orderable: true,   
      order: [[0, 'desc']], 
      scrollX: true,
      scrollY: "50vh",
      orderCellsTop: true,  
      ajax: "getGrnData",

      columns: [

        {

          data: 'grn_id',
          name: 'actions',
          orderable: false,
          searchable: false,
          render: function (data, type, row) {

             if (type === 'sort' || type === 'type') {
                   return data; // IMPORTANT: return numeric id for sorting
                  }

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

			              if (window.toolbarButtons?.some(btn => btn.attr.id === 'print')) {
              buttons += `
					<button type="button" class="btn btn-sm btn-success print-btn"
					  data-id="${row.grn_id}"
					  data-status="${row.po_invoice_status}">
					  <i class="bi bi-printer"></i>
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
        { data: 'subcontract_name', name: 'subcontract_name' },
        { data: 'po_number', name: 'po_number' },
        { data: 'po_invoice_status', name: 'po_invoice_status', visible:false },
      ],
                    initComplete: function () {
                    var api = this.api();

                    // get the real visible header inside the scroll container
                    var $scrollHead = $(api.table().container())
                        .find('.dataTables_scrollHead thead');

                    // second header row (index 1) has the inputs
                    $scrollHead.find('tr:eq(1) th').each(function (colIndex) {
                        var th = this;
                        $('input.column-search', th).on('keyup change', function () {
                            if (api.column(colIndex).search() !== this.value) {
                                api.column(colIndex).search(this.value).draw();
                            }
                        });
                    });
                }

    });

  });
	
// create po 
	
$(document).on('click', '.create_po', function () {	
	
	    var url="{{ url('purchasetable') }}";
    window.location.replace(url);
	
});
	
// create gin 	
	
$(document).on('click', '.create_gin', function () {	
	
    var url="{{ url('Gintable') }}";
    window.location.replace(url);
	
});
	
// create grn 	
	
$(document).on('click', '.create_grns', function () {	
	
	window.location.replace('genrategrn/0');
	
});	
	
	
//edit
	
		$(document).on('click', '.edit-btn', function () {
	
			    const id = $(this).data('id');
				const status = $(this).data('status');
			
			
			            if( status !="INITIATED") {
					window.location.replace('grnedit/'+id);
            }else{
				
                 showCustomAlert("Submitted GRN Cannot Be Edit",'info');
            }

});	
	
	
// view
	
		$(document).on('click', '.view-btn', function () {
	
			    const id = $(this).data('id');

		window.location.replace('grnview/' +id+'/2');

});		
	
// print 

 $(document).on('click', '.print-btn', function () {
	
				    const id = $(this).data('id');
				const status = $(this).data('status');

				   if(status =="APPROVED")
		   {
		   window.open('grnprint/' +id,'_blank');
		   }
		   else
		   {
			   showCustomAlert("Please Approve the Invoice First",'warning');
		   }

});	
	
	
</script>

@endpush
