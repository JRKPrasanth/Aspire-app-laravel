@extends('layouts.header')
@section('content')
<h2 class="text-danger">Time Formats</h2>
@include('layouts.breadcrumb')
<div id="toolbar-container" class="create mb-3"></div>

<div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="DateTbl" class="table table-bordered table-striped w-100">
          <thead>
    <tr class="table-warning">
      <th>PHP Format</th>
      <th>JS Format</th>
      <th>Display Format</th>
      <th>Actions</th>
    </tr>
    <tr class="table-info">
      <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Format" /></th>
      <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Format" /></th>
      <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Format" /></th>
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

    var table = $('#DateTbl').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('gettimeformatsData') }}",
      columns: [
        { data: 'php_format', name: 'php_format' },
        { data: 'js_format', name: 'js_format' },
        { data: 'display_format', name: 'display_format' },

        {
          data: 'time_formats_id',
          name: 'actions',
          orderable: false,
          searchable: false,
		  className: 'text-center',
          render: function (data, type, row) {
			   let buttons = '';
			  console.log(window.toolbarButtons);
			    if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                    buttons += `
					<button  class="btn btn-sm btn-info me-1 edit-btn" data-id="${data}"><i class="bi bi-pencil"></i></button>`;
           		 }

			   if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
				buttons += `<button class="btn btn-sm btn-danger delete-btn" data-id="${data}" data-url="{{ url('timeformatdelete') }}"><i class="bi bi-trash"></i>  </button>`;
            }
			  
	return buttons;
          }
  
        }
      ]
    });
  
    // Individual column search
    $('#DateTbl thead').on('keyup change', ".column-search", function() {
      var colIndex = $(this).parent().index();
      table.column(colIndex).search(this.value).draw();
    });
  });

// create function
	 $(".create").click(function()
{
  var url="{{ URL::to('timeformatscreate')}}";
    window.location.replace(url);
});
	//edit function
    $(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');
    const url = "{{ url('timeformatsedit') }}/" + id;
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
					url: "{{ url('timeformatdelete') }}/" + deleteId,
					type: "GET",
					success: function (response) {
						$('#globalDeleteModal').modal('hide');
						showCustomAlert('Deleted successfully!', 'success');
						$('#DateTbl').DataTable().ajax.reload();

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