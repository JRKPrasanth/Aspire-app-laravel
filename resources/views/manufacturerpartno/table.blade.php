@extends('layouts.header')
@section('content')
<h3 class="text-danger">Manufacturer Partno</h3>
@include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <table id="InvTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
			   <th></th>
            <th>Product Group</th>
            <th>Product Name</th>
            <th>Remarks</th>
            <th>Created By</th>
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
      var table = $('#InvTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('getGridmfgData') }}",
        columns: [
		 { data: 'manufacturer_product_id', name: 'manufacturer_product_id',visible:false },	
          { data: 'group_name', name: 'group_name' },
          { data: 'concatenated_product', name: 'concatenated_product' },
          { data: 'remarks', name: 'remarks' },
          { data: 'first_name', name: 'tb_users.first_name' },

          {
            data: 'product_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `
				        <button class="btn btn-sm btn-success create-btn" data-id="${row.product_id}"
                		data-bs-toggle="tooltip" 
		data-bs-placement="top" 
		title="Create">
          <i class="bi bi-plus-circle"></i> 
        </button>
				
        <button class="btn btn-sm btn-warning view-btn" data-id="${row.product_id}">
          <i class="bi bi-eye"></i> 
        </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
        <button class="btn btn-sm btn-primary edit-btn" data-id="${row.product_id}">
          <i class="bi bi-pencil"></i>
        </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
        <button class="btn btn-sm btn-danger delete-btn" data-id="${row.manufacturer_product_id}">
          <i class="bi bi-trash"></i>
        </button>`;
              }
              return buttons;
            }
          }
        ]
      });

      // Individual column search
      $('#InvTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });
	
	
    //edit function
    $(document).on('click', '.edit-btn', function () {
		
      const id = $(this).data('id');
		
		var url = "{{ URL::to('manunocheck') }}/"+id;
		$.post(url,function(data){
			var data = $.trim(data);
			if(data == 1){
				window.location.replace('manufacturerpartnoedit/' +id);
			}else{
				showCustomAlert('No Manufacture data for this product','info');
			}
		});	

    });	
	
	
    //create function
    $(document).on('click', '.create-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('manufacturerpartnocreate') }}/" + id;
      window.location.href = url;
    });	
	
	
    //view function
    $(document).on('click', '.view-btn', function () {

      const id = $(this).data('id');

		var url = "{{ URL::to('manunocheck') }}/"+id;
		$.post(url,function(data){
			var data = $.trim(data);
			if(data == 1){
				window.location.replace('manufacturerpartnoview/' +id);
			}else{
				showCustomAlert('No Manufacture data for this product','info');
			}
		});	

    });	
	
	
    // delete function
    let deleteId = null;

    $(document).on('click', '.delete-btn', function () {
      deleteId = $(this).data('id');
      $('#globalDeleteModal').modal('show');
    });

    $('#globalConfirmDeleteBtn').on('click', function () {
      if (deleteId) {
        $.ajax({
          url: "{{ url('mfgdelete') }}/" + deleteId,
          type: "GET",
          success: function (data) {
            if (data == '0') {
              $('#globalDeleteModal').modal('hide');
              showCustomAlert('Deleted successfully!', 'success');
              $('#InvTbl').DataTable().ajax.reload();
            }
            if (data == '1') {
              $('#globalDeleteModal').modal('hide');
              showCustomAlert("You Can't delete , Manufacture no  Used in SomeWhere.", 'error');
              $('#InvTbl').DataTable().ajax.reload();
            }
          },
          error: function (xhr) {
            $('#globalDeleteModal').modal('hide');
            const errorMsg = xhr.responseJSON?.message || 'Delete failed.';
            showCustomAlert(errorMsg, 'error');
          }
        });
      }
    });
	
</script>

@endpush
