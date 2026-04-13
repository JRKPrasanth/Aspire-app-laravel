@extends('layouts.header')
@section('content')
<h3 class="text-danger">Material Plan</h3>
@include('layouts.breadcrumb')

<div class="card shadow-lg rounded-4 border-0">
  <div class="container mt-4">
    <div class="table-responsive">
      <table id="MRPTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">

                <th>Actions</th>
                <th>Product Code</th>
                <th>Product Name</th>
                <th>Workorder Qty</th>
                <th>Workorder No</th>
                <th>Workorder Date</th>
                <th>Due Date</th>
                <th>Created By</th>
                <th>Workorder Line Id</th>

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
	
  // table data

  $(document).ready(function () {

    var pagemodule="<?php echo $pageModule ?>";

    var table = $('#MRPTbl').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ URL::to('getWorkorderDatas') }}?pagemodule="+pagemodule, 

      columns: [

        {

          data: 'workorder_hdr_id',
          name: 'actions',
          orderable: false,
          searchable: false,
                render: function (data, type, row) {
                    let buttons = '';

                    if (window.toolbarButtons?.some(btn => btn.attr.id === 'prdplan')) {
                        buttons += `
                            <button type="button" class="btn btn-sm btn-primary edit-btn me-1"
                                data-id="${row.workorder_line_id}"
										data-bs-toggle="tooltip" 
										data-bs-placement="top" 
										title="Material Plan">
                                PLAN
                            </button>`;
                    }

                    if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                        buttons += `
                            <button type="button" class="btn btn-sm btn-warning view-btn"
                                data-id="${row.workorder_hdr_id}">
                                <i class="bi bi-eye"></i>
                            </button>`;
                    }

					
                    return buttons;
                },

        },

       
        { data: 'product_code', name: 'product_code' },
        { data: 'concatenated_product', name: 'concatenated_product' },
        { data: 'qty', name: 'qty' },
        { data: 'workorder_no', name: 'workorder_no' },
        { data: 'workorder_date', name: 'workorder_date' },
        { data: 'due_date', name: 'due_date' },
        { data: 'first_name', name: 'tb_users.first_name' },
        { data: 'workorder_line_id', name: 'workorder_line_id', visible: false },
      ]
    });


    $('#MRPTbl thead').on('keyup change', '.column-search', function () {
      let index = $(this).closest('th').index();
      table.column(index).search(this.value).draw();
    });
  });	
	
	
	//edit function
    $(document).on('click', '.edit-btn', function () {
		
    const id = $(this).data('id');
    var url="{{ URL::to('productionplancreate') }}";
	var pagemodule='<?php echo $pageModule; ?>';
		
		window.location.replace(url+"/"+id+"?source=WORKORDER&pagemodule="+pagemodule);	
		
    });	
	
	
	//view function
$(document).on('click', '.view-btn', function () {
  
		const id = $(this).data('id');
		var url="{{URL::to('workorderview')}}";
		var pageurl='<?php echo $pageModule; ?>';
	
		window.location.replace(url+"/"+id+"?pageurl="+pageurl);	
	
});	
	
</script>

@endpush