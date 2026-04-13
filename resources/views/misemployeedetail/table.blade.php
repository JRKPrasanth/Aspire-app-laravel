@extends('layouts.header')
@section('content')
<h2 class="text-danger">Marketing  Employee Details</h2>
@include('layouts.breadcrumb')
<div id="toolbar-container" class="create mb-3"></div>


<div class="card shadow-lg rounded-4 border-0">
	<div class="container mt-4">
  <table id="DetailTbl" class="table table-bordered table-striped w-100">
    <thead>
      <tr class="table-warning">
        <th>Employee Name</th>
        <th>Desigination</th>
        <th>Zone</th>
        <th>Region</th>
        <th>State</th>
        <th>Hq</th>
        <th>Active</th>
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
        <th></th>
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
	
	$(document).ready(function() {

    var table = $('#DetailTbl').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('salesmisreportsdetildata') }}",
      columns: [
        { data: 'employee_name', name: 'employee_name' },
        { data: 'desigination', name: 'desigination' },
        { data: 'zone', name: 'zone' },
        { data: 'region', name: 'region' },
        { data: 'state', name: 'state' },
        { data: 'hq_name', name: 'hq_name' },
        { data: 'active', name: 'active' },
        {
          data: 'id',
          name: 'actions',
          orderable: false,
          searchable: false,
		  className: 'text-center',
          width: '140px', 
          render: function (data, type, row) {
			   let buttons = '';
			  console.log(window.toolbarButtons);
			    if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                    buttons += `
					<button  class="btn btn-sm btn-info me-1 edit-btn" data-id="${data}"><i class="bi bi-pencil"></i></button>`;
           		 }
			  

			   if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
				buttons += `<button class="btn btn-sm btn-danger delete-btn" data-id="${data}" data-url="{{ url('salesmisreportsdetildelete') }}"><i class="bi bi-trash"></i>  </button>`;
            }
			  
	return buttons;
          }
  
        }
      ]
    });
  
    // Individual column search
    $('#DetailTbl thead').on('keyup change', ".column-search", function() {
      var colIndex = $(this).parent().index();
      table.column(colIndex).search(this.value).draw();
    });
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
	
	    // create function
    $(".create").click(function () {
      var url = "{{ URL::to('createmaremployeedetail/0')}}";
      window.location.replace(url);
    });
		//edit function
    $(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');
    const url = "{{ url('createmaremployeedetail') }}/" + id;
    window.location.href = url;
    });
	
// delete function
	// delete function
	let deleteId = null; 

	$(document).on('click', '.delete-btn', function () {
		deleteId = $(this).data('id');
		$('#globalDeleteModal').modal('show'); 
	});

	$('#globalConfirmDeleteBtn').on('click', function () {
		if (deleteId) {
				$.ajax({
					url: "{{ url('salesmisreportsdetildelete') }}/" + deleteId,
					type: "GET",
					success: function (response) {
						$('#globalDeleteModal').modal('hide');
						showCustomAlert('Deleted successfully!', 'success');
						$('#DetailTbl').DataTable().ajax.reload();

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