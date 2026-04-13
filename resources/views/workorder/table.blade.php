@extends('layouts.header')
@section('content')
<h3 class="text-danger">Workorder</h3>
@include('layouts.breadcrumb')
<div id="toolbar-container" class="create mb-3 mt-2"></div>

<div class="card shadow-lg rounded-4 border-0">
  <div class="container mt-4">
    <div class="table-responsive">
      <table id="WrkTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">

                <th>Actions</th>
                <th>Workorder No</th>
                <th>Workorder Date</th>
                <th>Source</th>
                <th>Shift</th>
                <th>Created By</th>
          </tr>

          <tr class="table-info">

            <th></th>
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
	
// Add create button purpose
	      $(document).ready(function () {
        if (window.toolbarButtons?.some(btn => btn.attr.id === 'create')) {
          $('#toolbar-container').append(`
            <button class="btn btn-primary create me-2">Create
              <i class="bi bi-plus-circle"></i> 
            </button>
          `);
        }
      });
	
	
// create function
	 $(".create").click(function()
{
  var url="{{ URL::to('workordercreate/0')}}";
    window.location.replace(url);
});	
	
	
  // table data

  $(document).ready(function () {

    var table = $('#WrkTbl').DataTable({
      processing: true,
      serverSide: true,
      ajax: "getWorkorderData",

      columns: [

        {

          data: 'workorder_hdr_id',
          name: 'actions',
          orderable: false,
          searchable: false,
                render: function (data, type, row) {
                    let buttons = '';

                    if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                        buttons += `
                            <button type="button" class="btn btn-sm btn-primary edit-btn"
                                data-id="${row.workorder_hdr_id}">
                                <i class="bi bi-pencil"></i>
                            </button>`;
                    }

                    if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                        buttons += `
                            <button type="button" class="btn btn-sm btn-warning view-btn"
                                data-id="${row.workorder_hdr_id}">
                                <i class="bi bi-eye"></i>
                            </button>`;
                    }


                    if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                        buttons += `
                            <button type="button" class="btn btn-sm btn-danger delete-btn"
                                data-id="${row.workorder_hdr_id}">
                                <i class="bi bi-eye"></i>
                            </button>`;
                    }
					
                    return buttons;
                },

        },

        { data: 'workorder_no', name: 'workorder_no' },
        { data: 'workorder_date', name: 'workorder_date' },
        { data: 'source', name: 'source' },
        { data: 'shift', name: 'shift' },
        { data: 'first_name', name: 'first_name' },


      ]
    });


    $('#WrkTbl thead').on('keyup change', '.column-search', function () {
      let index = $(this).closest('th').index();
      table.column(index).search(this.value).draw();
    });
  });		
		
	
	//view function
	
$(document).on('click', '.view-btn', function () {
  const id = $(this).data('id');
  var pageurl="<?php echo $pageModule;?>";
  var url="{{URL::to('workorderview')}}/"+id+"?pageurl="+pageurl;
  window.location.href = url;
});			
	
	
    //edit function
    $(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');
		
		
			var editurl="{{ URL::to('editcheck') }}/"+ id;
		  $.get(editurl,function(data){
			var data=$.trim(data);
			if(data==0){
			   var url = "{{ URL::to('workorderedit') }}";
               var editUrl = url + '/' + id;
	        	window.location.replace(editUrl);
			   }else{
				 showCustomAlert("Workorder Used in Material Plan You Can't Edit",'info');  
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
					url: "{{ url('workorderdelete') }}/" + deleteId,
					type: "GET",
					success: function (data) {
						
					if(data =='0')
					{
						$('#globalDeleteModal').modal('hide');
						showCustomAlert('Deleted successfully!', 'success');
						$('#WrkTbl').DataTable().ajax.reload();
					}
					if(data =='1')
					{
						$('#globalDeleteModal').modal('hide');
						showCustomAlert('Machine Used in SomeWhere!', 'error');
						$('#WrkTbl').DataTable().ajax.reload();
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
