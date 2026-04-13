@extends('layouts.header')
@section('content')
<h3 class="text-danger">Approval Settings</h3>
@include('layouts.breadcrumb')
<div id="toolbar-container" class="create mb-3"></div>

<div class="card shadow-lg rounded-4 border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-3">
    </div>
    <div class="table-responsive">
      <table id="companyTable" class="table table-bordered table-striped w-100">
        <thead>
  <tr class="table-warning">
    <th>Module Name</th>
    <th>Actions</th>
  </tr>
  <tr class="table-info">
    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Module" /></th>
    <th></th>
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

var table = $('#companyTable').DataTable({
  processing: true,
  serverSide: true,
  ajax: "{{ route('getApprovalsettingsData') }}",
  columns: [
    { data: 'module_name', name: 'module_name' },

    {
      data: 'approvalsettings_hdr_id',
      name: 'actions',
      orderable: false,
      searchable: false,
      render: function (data, type, row) {
     let buttons = '';
    console.log(window.toolbarButtons);
      if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
      <button  class="btn btn-sm btn-primary me-1 edit-btn" data-id="${data}"><i class="bi bi-pencil"></i></button>`;
            }
    
       if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `<button class="btn btn-sm btn-warning me-1 view-btn" data-id="${data}"><i class="bi bi-eye"></i></button>`;
      }

     if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
    buttons += `<button class="btn btn-sm btn-danger delete-btn" data-id="${data}" data-url="{{ url('freightcarriershdrdelete') }}"><i class="bi bi-trash"></i>  </button>`;
        }
    
return buttons;
      }

    }
  ]
});

// Individual column search
$('#companyTable thead').on('keyup change', ".column-search", function() {
  var colIndex = $(this).parent().index();
  table.column(colIndex).search(this.value).draw();
});
});	
	
	
// create function
	 $(".create").click(function()
{
  var url="{{ URL::to('approvalsettingscreate')}}";
    window.location.replace(url);
});
	//edit function
    $(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');
    const url = "{{ url('approvalsettingsedit') }}/" + id;
    window.location.href = url;
    });
	
	//view function
$(document).on('click', '.view-btn', function () {
  const id = $(this).data('id');
  const url = "{{ url('approvalsettingsview') }}/" + id;
  window.location.href = url;
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
					url: "{{ url('freightcarriershdrdelete') }}/" + deleteId,
					type: "GET",
					success: function (response) {
						$('#globalDeleteModal').modal('hide');
						showCustomAlert('Deleted successfully!', 'success');
						$('#companyTable').DataTable().ajax.reload();

					},
					error: function (xhr) {
						 $('#globalDeleteModal').modal('hide');
						const errorMsg = xhr.responseJSON?.message || 'Delete failed.';
						showCustomAlert(errorMsg, 'error');
					}
				});
		}
	});	

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
	
  </script>
@endpush
