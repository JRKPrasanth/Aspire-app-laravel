@extends('layouts.header')
@section('content')
<h3 class="text-danger">Professional Tax</h3>
@include('layouts.breadcrumb')
<div id="toolbar-container" class="create mt-3"></div>


<div class="card shadow-lg rounded-4 border-0">
<div class="container mt-4">
  <table id="proTbl" class="table table-bordered table-striped w-100">
    <thead>
      <tr class="table-warning">
        <th>State</th>
        <th>Description</th>
        <th>From Value</th>
        <th>To Value</th>
        <th>Deduction Amount</th>
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
      {{-- DataTable will populate via AJAX --}}
    </tbody>
  </table>
</div>
</div>


@endsection
@push('scripts')


<script>
	
	
	$(document).ready(function () {
  var table = $('#proTbl').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('professionaltaxgrid') }}",
    columns: [
      { data: 'state_name', name: 'state_name' },
      { data: 'description', name: 'description' },
      { data: 'from_value', name: 'from_value' },
      { data: 'to_value', name: 'to_value' },
      { data: 'deduction_amount', name: 'deduction_amount' },

      {
        data: 'ptax_id',
        name: 'actions',
        orderable: false,
        searchable: false,
        render: function (data, type, row) {
            let buttons = '';
            if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                    buttons += `
				<button type="button" class="btn btn-sm btn-primary edit-btn"
				  data-id="${row.ptax_id}">
				  <i class="bi bi-pencil"></i>
				</button>`;
            }
            if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                    buttons += `
					<button type="button" class="btn btn-sm btn-danger delete-btn"
					  data-id="${row.ptax_id}">
					  <i class="bi bi-trash"></i>
					</button>`;
            }
            return buttons;
          }
          
      }
    ]
  });


  $('#proTbl thead').on('keyup change', '.column-search', function () {
    let index = $(this).closest('th').index();
    table.column(index).search(this.value).draw();
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
	 $(".create").click(function()
{
  var url="{{ URL::to('createprofessional/0')}}";
    window.location.replace(url);
});
	//edit function
    $(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');
    const url = "{{ url('createprofessional') }}/" + id;
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
					url: "{{ url('professionaldelete') }}/" + deleteId,
					type: "GET",
					success: function (response) {
						$('#globalDeleteModal').modal('hide');
						showCustomAlert('Deleted successfully!', 'success');
						$('#proTbl').DataTable().ajax.reload();

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