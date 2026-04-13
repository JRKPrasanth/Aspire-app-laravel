@extends('layouts.header')
@section('content')
<h3 class="text-danger">Purchase Invoice Table</h3>
@include('layouts.breadcrumb')

<a class='btn btn-danger px-4 mt-2' href = "{{url('purchasereturn')}}">Cancel</a>


<div class="card shadow-lg rounded-4 border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-3">
    </div>
    <div class="table-responsive">
      <table id="InvTbl" class="table table-bordered table-striped w-100">
        <thead>
  <tr class="table-warning">
      
      <th>QC Number</th>
      <th>Invoice Number</th>
      <th>Supplier Name</th>
      <th>Subcontractor Name</th>
      <th>GRN Number</th>
      <th>DC Number</th>
      <th>PO Number</th>
      <th>PO Date</th>
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
    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>

  </tr>
</thead>

        <tbody></tbody>
      </table>
    </div>
  </div>
</div>


@endsection
@push('scripts')

<script>

		$(document).ready(function() {

    var suppliername = "{{$suppliername}}";

    var table = $('#InvTbl').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('invoiceData') }}",
      columns: [
        { data: 'qc_number', name: 'qc_number' },
        { data: 'bill_number', name: 'bill_number', className: 'text-center' },
        { data: 'supplier_name', name: 'supplier_name', className: 'text-center' },
        { data: 'subcontract_name', name: 'subcontract_name', className: 'text-center' },
        { data: 'grn_number', name: 'grn_number', className: 'text-center' },
        { data: 'dc_number', name: 'dc_number', className: 'text-center' },
        { data: 'po_number', name: 'po_number', className: 'text-center' },
        { data: 'po_date', name: 'po_date', className: 'text-center' },


        {
          data: 'qc_header_id',
          name: 'actions',
          orderable: false,
          searchable: false,
		      className: 'text-center',
          width: '140px', 
          render: function (data, type, row) {
              return `
                  <button class="btn btn-sm btn-primary me-1 create-btn" data-id="${data}">
                      <i class="bi bi-plus"></i> Create
                  </button>`;
          }
        }
      ]
    });
  
    // Individual column search
    $('#InvTbl thead').on('keyup change', ".column-search", function() {
      var colIndex = $(this).parent().index();
      table.column(colIndex).search(this.value).draw();
    });
  });

	
// create
	
    $(document).on('click', '.create-btn', function () {

		 const id = $(this).data('id');

		window.location.replace('purchasereturncreate/' +id);


});	

	
	
	
	
	
	
	
	
	

/*Karthigaa Purpose For CREATE Function*/
$(document).on('click','.create_qc',function(){
 var index = $("#poinvoicegrid").jqGrid('getGridParam','selrow');
var grn_id = $("#poinvoicegrid").jqGrid ('getCell', index, 'grn_id');
var url="{{ URL::to('qualitychecking') }}/"+grn_id;

window.location.replace(url);
});

  
  
  /******************* Print *********************/
  
  $("#print").click(function(){
    
    var index=$('#poinvoicegrid').jqGrid('getGridParam','selrow'); 
    var pohdrid=$('#poinvoicegrid').jqGrid('getCell',index,'qc_header_id');
    if(index)
    {
       window.open('purchasereturnprint/' +pohdrid);

       }
      else
      {
        notyMsg("info","Please Select a Row");
      }
  });
  


	
</script>

@endpush
