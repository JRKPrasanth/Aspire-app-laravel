@extends('layouts.header')
@section('content')
<h3 class="text-danger">GRN Table</h3>
@include('layouts.breadcrumb')
<a href="{{url('purchasereplacement')}}"><button class="btn btn-danger px-4 mt-2" > Cancel</button></a>
 

<div class="card shadow-lg rounded-4 border-0">
  <div class="container mt-4">
    <div class="table-responsive">
      <table id="GrnTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">

                <th>Actions</th>
                <th>Supplier Name</th>
                <th>Subcontractor Name</th>
                <th>GRN Number</th>
                <th>GRN Status</th>
                <th>DC Number</th>
                <th>PO Number</th>
                <th>PO Date</th>

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
          {{-- DataTable will populate via AJAX --}}
        </tbody>
      </table>
    </div>
  </div>
</div>



@endsection
@push('scripts')

<script>
	
  // table data

  $(document).ready(function () {

    var table = $('#GrnTbl').DataTable({
      processing: true,
      serverSide: true,
      ajax: "invoiceDatarplt",

      columns: [

        {

          data: 'grn_id',
          name: 'actions',
          orderable: false,
          searchable: false,
          render: function (data, type, row) {
              return `
					<button type="button" class="btn btn-sm btn-primary create_grns"
					  data-id="${row.grn_id}">
					  Create
					</button>`;
          }

        },

        { data: 'supplier_name', name: 'supplier_name' },
        { data: 'subcontract_name', name: 'subcontract_name' },
        { data: 'grn_number', name: 'grn_number' },
        { data: 'grn_status', name: 'grn_status' },
        { data: 'dc_number', name: 'dc_number' },
        { data: 'po_number', name: 'po_number' },
        { data: 'po_date', name: 'po_date' },
      ]
    });


    $('#GrnTbl thead').on('keyup change', '.column-search', function () {
      let index = $(this).closest('th').index();
      table.column(index).search(this.value).draw();
    });
  });	
	
	
// create
	
$(document).on('click', '.create_grns', function () {	

	 const id = $(this).data('id');	


		window.location.replace('purchasereplacementcreate/' +id);

});

	
// extra
  
  $("#print").click(function(){
    
    var index=$('#poinvoicegrid').jqGrid('getGridParam','selrow'); 
    var pohdrid=$('#poinvoicegrid').jqGrid('getCell',index,'qc_header_id');
    if(index)
    {
       window.open('purchasereplacementprint/' +pohdrid);

       }
      else
      {
        notyMsg("info","Please Select a Row");
      }
  });
  
	
	$(document).on('click','.create_qc',function() {
	 var index = $("#poinvoicegrid").jqGrid('getGridParam','selrow');
	var grn_id = $("#poinvoicegrid").jqGrid ('getCell', index, 'grn_id');
	var url="{{ URL::to('qualitychecking') }}/"+grn_id;

	window.location.replace(url);
		
	});
	
    </script>

@endpush
