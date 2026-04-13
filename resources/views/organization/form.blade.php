@extends('layouts.header')
@section('content')
<h3 class="text-danger">Organization</h3>
@include('layouts.breadcrumb')

<div class="card shadow-lg rounded-4 border-0">
	<div class="card-body">
 <form method="post" action="" id="org_form" data-parsley-validate>
  @csrf
  <input type="hidden" name="savestatus" id="savestatus" />

  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label"><span class="text-danger">*</span>Organization Code</label>
      <input type="text" name="organization_code" class="form-control" value="{{ $row->organization_code }}" required />
      <input type="hidden" name="organization_id" value="{{ $row->organization_id }}">
    </div>

    <div class="col-md-6">
      <label class="form-label"><span class="text-danger">*</span>Organization Name</label>
      <input type="text" name="organization_name" class="form-control" value="{{ $row->organization_name }}" required />
      <small class="text-danger dup_name d-none"></small>
    </div>

    <div class="col-md-6">
      <label class="form-label">Organization Type</label>
      <select name="organization_type" class="form-select select2">
        {!! $row->organization_type !!} 
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label">Active</label>
      <select name="active" class="form-select select2">
        <option value="YES" {{ $row->active == 'YES' ? 'selected' : '' }}>YES</option>
        <option value="NO" {{ $row->active == 'NO' ? 'selected' : '' }}>NO</option>
      </select>
    </div>

    <div class="col-12 text-center mt-4">
      <button type="button" class="btn btn-success saveform px-4" value="SAVE">Save</button>
    </div>
  </div>
</form>
	</div>
	</div>
	
	
<div class="card shadow-lg rounded-4 border-0">
<div class="container mt-4">
  <table id="organizationTable" class="table table-bordered table-striped w-100">
    <thead>
      <tr class="table-warning dthfreeze">
        <th>Organization Code</th>
        <th>Organization Name</th>
        <th>Organization Type</th>
        <th>Active</th>
        <th>Actions</th>
      </tr>
      <tr class="table-info">
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Code" /></th>
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Name" /></th>
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Type" /></th>
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Status" /></th>
        <th></th>
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
// data table funcrion	
$(document).ready(function () {
  var table = $('#organizationTable').DataTable({
    processing: true,
    serverSide: true,
    colReorder: true,
    ajax: "{{ route('getOrganizationData') }}",
    columns: [
      { data: 'organization_code', name: 'm_organizations_t.organization_code' },
      { data: 'organization_name', name: 'm_organizations_t.organization_name' },
      { data: 'lookup_code', name: 'm_organizations_t.a_lookuplines_t.lookup_code' },
      { data: 'active', name: 'm_organizations_t.active' },
      {
        data: 'organization_id',
        name: 'actions',
        orderable: false,
        searchable: false,
        render: function (data, type, row) {
            let buttons = '';
            if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                    buttons += `
				<button type="button" class="btn btn-sm btn-primary edit-btn"
				  data-id="${row.organization_id}"
				  data-code="${row.organization_code}"
				  data-name="${row.organization_name}"
				  data-type="${row.lookuplines_id}"
				  data-active="${row.active}">
				  <i class="bi bi-pencil"></i>
				</button>`;
            }
            if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                    buttons += `
					<button type="button" class="btn btn-sm btn-danger delete-btn"
					  data-id="${row.organization_id}">
					  <i class="bi bi-trash"></i>
					</button>`;
            }
            return buttons;
          }
          
      }
    ]
  });


  $('#organizationTable thead').on('keyup change', '.column-search', function () {
    let index = $(this).closest('th').index();
    table.column(index).search(this.value).draw();
  });
});

// save function
	let dup_chk = true;
	
	$(document).on('click', '.saveform', function () {
	
    var form = $("#org_form");
    form.parsley().validate();

    if (form.parsley().isValid() && dup_chk == true) {
      
      var $btn = $(this);            
			$btn.prop('disabled', true);

        $.ajax({
            url: "{{ URL::to('organizationsave') }}",
            type: "POST",
            data: form.serialize(),
            success: function (data) {
                // Show success message
               showCustomAlert('Saved successfully!','success');
                // Clear the form (optional)
                form[0].reset();
                $('.select2').val('').trigger('change');
                // Reload DataTable
                window.location.reload();
            },
            error: function (xhr) {
              showCustomAlert('Save failed. Try again.', 'error');
            }
        });
    }
});
// edit function
$(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');
    const code = $(this).data('code');
    const name = $(this).data('name');
    const type = $(this).data('lookuplines_id'); // This should now be the ID
    const active = $(this).data('active');

    // Fill form fields
    $('input[name="organization_id"]').val(id);
    $('input[name="organization_code"]').val(code);
    $('input[name="organization_name"]').val(name);

    // For select2 fields, use .val().trigger('change')

	$('.organization_type').val(btn.data('type')).trigger('change');
    $('select[name="active"]').val(active).trigger('change');
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
					url: "{{ url('organizationdelete') }}/" + deleteId,
					type: "GET",
					success: function (response) {
						$('#globalDeleteModal').modal('hide');
						showCustomAlert(response.message, 'success');
						$('#organizationTable').DataTable().ajax.reload();

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
