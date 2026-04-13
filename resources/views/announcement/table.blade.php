@extends('layouts.header')
@section('content')
<h3 class="text-danger">    Announcement </h3>
@include('layouts.breadcrumb')
<div id="toolbar-container" class="create mb-3"></div>



<div class="card shadow-lg rounded-4 border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-3">
    </div>
    <div class="table-responsive">
      <table id="AnnTbl" class="table table-bordered table-striped w-100">
        <thead>
  <tr class="table-warning">
      <th>Announcement Name</th>
      <th>Valid From</th>
      <th>Valid To</th>
      <th>Status</th>
      <th>Actions</th>
  </tr>
  <tr class="table-info">
    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
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

    var table = $('#AnnTbl').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('getannData') }}",
      columns: [
        { data: 'ann_name', name: 'ann_name' },
        { data: 'start_date', name: 'start_date' },
        { data: 'end_date', name: 'end_date' },
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
					<button  class="btn btn-sm btn-primary me-1 edit-btn" data-id="${data}"><i class="bi bi-pencil"></i></button>`;
           		 }

			   if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
				buttons += `<button class="btn btn-sm btn-danger delete-btn" data-id="${data}" data-url="{{ url('companydelete') }}"><i class="bi bi-trash"></i>  </button>`;
            }
			  
	return buttons;
          }
        }
      ]
    });
  
    // Individual column search
    $('#AnnTbl thead').on('keyup change', ".column-search", function() {
      var colIndex = $(this).parent().index();
      table.column(colIndex).search(this.value).draw();
    });
  });



// create function
	 $(".create").click(function()
{
  var url="{{ URL::to('createannouncement/0')}}";
    window.location.replace(url);
});
	//edit function
    $(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');
    const url = "{{ url('createannouncement') }}/" + id;
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
					url: "{{ url('announcementdelete') }}/" + deleteId,
					type: "GET",
					success: function (response) {
						$('#globalDeleteModal').modal('hide');
						showCustomAlert('Deleted successfully!', 'success');
						$('#AnnTbl').DataTable().ajax.reload();

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