@extends('layouts.header')
@section('content')
<h3 class="text-danger">
<?php if($pageMethod=="consumable"){?>
Consumable
<?php }else{?>
Consumable Approval
<?php } ?>
</h3>
@include('layouts.breadcrumb')
<div id="toolbar-container" class="create mb-3 mt-1"></div>

  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <table id="ReturnTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Consumable Number</th>
            <th>Date</th>
            <th>Status</th>
            <th>Created By</th>
            <th>SubInventory Name</th>
            <th>Locator</th>
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

   var pageurl='<?php echo $pageMethod; ?>';

    $(document).ready(function () {
      var table = $('#ReturnTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax:  "getconsumableData?pageurl="+pageurl,
		order: [[2, 'desc']],
        columns: [

          { data: 'consumable_number', name: 'consumable_number' },
          { data: 'consumable_date', name: 'consumable_date' },
          { data: 'status', name: 'status' },
          { data: 'first_name', name: 'first_name' },
          { data: 'subinventory_name', name: 'subinventory_name' },
          { data: 'locator_name', name: 'locator_name' },

          {
            data: 'consumable_hdr_id',
			name: 'actions',
			orderable: false,
			searchable: false,
			className: 'text-center',

    render: function (data, type, row) {
        let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
        buttons += `
        <button class="btn btn-sm btn-warning view-btn me-1" data-id="${row.consumable_hdr_id}"
		data-status="${row.status}">
          <i class="bi bi-eye"></i>
        </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'approve')) {
        buttons += `
        <button class="btn btn-sm btn-success approve-btn me-1" data-id="${row.consumable_hdr_id}" data-status="${row.status}">
          Approve
        </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
        buttons += `
        <button class="btn btn-sm btn-primary edit-btn" data-id="${row.consumable_hdr_id}" data-status="${row.status}">
          <i class="bi bi-pencil"></i>
        </button>`;
              }

    return buttons;
            }
          }
        ]
      });

      // Individual column search
      $('#ReturnTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });	
	
	
    // create function
    $(".create").click(function () {
      var url = "{{ URL::to('consumablecreate')}}";
    window.location.replace(url);
    });	
	
	
	    // Add create button purpose
    $(document).ready(function () {
      if (window.toolbarButtons?.some(btn => btn.attr.id === 'create')) {
        $('#toolbar-container').append(`
              <button class="btn btn-success text-white px-4 create me-2">Create
                <i class="bi bi-plus-circle"></i> 
              </button>
            `);
      }
    });
	
	
    //view function
    $(document).on('click', '.view-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('consumableview') }}/" + id;
      window.location.href = url;
    });	
	
	
    //edit function
    $(document).on('click', '.edit-btn', function () {

      const id = $(this).data('id');
      const status = $(this).data('status');

    if(status=="INITIATED"){

		var url = "{{ URL::to('consumableedit') }}";
    var editUrl = url + '/' + id;
		window.location.replace(editUrl);

	}else{

			 showCustomAlert("Approved Data Cannot be Modified","info");

	}

  });
	
	
// Approval 	
	
	$(document).on('click', '.approve-btn', function () {
	
      const id = $(this).data('id');
      const status = $(this).data('status');
		
		if(status=="INITIATED")
		{
			
		var url = "{{ URL::to('approveconsumable') }}";
        var editUrl = url + '?approval=' + id;
		window.location.replace(editUrl);
			
	}else{
		
		showCustomAlert("Please Select INITIATED Records.","warning");
	}
	

});	

</script>

@endpush
