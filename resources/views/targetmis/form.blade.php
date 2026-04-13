@extends('layouts.header')
@section('content')
<h3 class="text-danger">MIS  Target Name Upload</h3>
@include('layouts.breadcrumb')

<div class="card shadow-lg rounded-4 border-0">
  <div class="card-body">
    <form method="POST" action="" id="target_form" class="target_form" data-parsley-validate>
      @csrf
      <div class="row g-3">

        <!-- Financial Year -->
        <div class="col-md-6">
          <div class="row align-items-center">
            <label for="f_year" class="col-md-4 col-form-label">
              <span class="text-danger">*</span> Financial Year
            </label>
            <div class="col-md-8">
              <input name="fy_year" type="text" class="form-control" id="f_year" required>
            </div>
          </div>
        </div>

        <!-- Target Name -->
        <div class="col-md-6">
          <div class="row align-items-center">
            <label for="target" class="col-md-4 col-form-label">Target Name</label>
            <div class="col-md-8">
              <input name="target" type="text" class="form-control" id="target" required>
            </div>
          </div>
        </div>

        <!-- Active -->
        <div class="col-md-6">
          <div class="row align-items-center">
            <label for="status" class="col-md-4 col-form-label">Active</label>
            <div class="col-md-8">
              <select name="status" id="status" class="form-select select2">
                <option value="yes">Yes</option>
                <option value="no">No</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Created By -->
        <div class="col-md-6">
          <div class="row align-items-center">
            <label for="created_by" class="col-md-4 col-form-label">Created By</label>
            <div class="col-md-8" style="pointer-events: none;">
              <select name="created_by" id="created_by" class="form-select select2" readonly>
                {!! $created_by !!}
              </select>
            </div>
          </div>
        </div>

      </div>

      <!-- Submit Button -->
      <div class="row mt-4">
        <div class="col text-center">
          <button name="save" type="button" class="btn btn-success saveform px-4">
            <i class="bi bi-save me-1"></i> Save
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

	
	
<div class="card shadow-lg rounded-4 border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-3">
    </div>
    <div class="table-responsive">
      <table id="Table1" class="table table-bordered table-striped w-100">
        <thead>
  <tr class="table-warning">
    <th>Fy Year</th>
    <th>Target Name</th>
    <th>Active</th>
	<th>Actions</th>
  </tr>
  <tr class="table-info">
    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Code" /></th>
    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Name" /></th>
    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Website" /></th>
  
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
	
$(document).ready(function () {
  var table = $('#Table1').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: "{{ route('gettargetData') }}",
      type: 'GET'
    },
    columns: [
      { data: 'f_year', name: 'f_year' },
      { data: 'target_name', name: 'target_name' },
      { data: 'active', name: 'active' },
      {
        data: 'id',
        name: 'actions',
        orderable: false,
        searchable: false,
        render: function (data, type, row, meta) {
          return `
            <button type="button" class="btn btn-sm btn-primary me-1 edit-btn" data-id="${data}">
              <i class="bi bi-pencil"></i>
            </button>
            <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="${data}">
              <i class="bi bi-trash"></i>
            </button>
          `;
        }
      }
    ]
  });

  // Column search
  $('#Table1 thead').on('keyup change', '.column-search', function () {
    let index = $(this).closest('th').index();
    table.column(index).search(this.value).draw();
  });
});

// save function	
// save function
	let dup_chk = true;
	
	$(document).on('click', '.saveform', function () {
	
    var form = $("#target_form");
    form.parsley().validate();

    if (form.parsley().isValid() && dup_chk == true) {

      	var $btn = $(this);            
			  $btn.prop('disabled', true);

        $.ajax({
            url: "{{ URL::to('targetmissave') }}",
            type: "POST",
            data: form.serialize(),
            success: function (data) {
                // Show success message
               showCustomAlert('Saved successfully!', 'success');
                // Clear the form (optional)
                form[0].reset();
                $('.select2').val('').trigger('change');
                // Reload DataTable
                $('#Table1').DataTable().ajax.reload();
            },
            error: function (xhr) {
              showCustomAlert('Save failed. Try again.', 'error');
            }
        });
        window.location.reload();
    }
});
	
</script>

@endpush