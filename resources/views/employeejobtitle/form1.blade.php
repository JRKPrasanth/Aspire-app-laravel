@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Employee Allowance</h3>
  @include('layouts.breadcrumb')


  <form action="" id="save">
    <?php $data = \Session::get('data');
  if (isset($data[$pageMethod]['save'])) { ?>

    <input type="hidden" name="edit_id" value="" id="edit_id" />
    {{ csrf_field() }}

    <div class="card shadow-lg rounded-4 border-0">
      <div class="card-body">
        <div class="row g-3">

          <!-- Employee Type -->
          <div class="col-md-3">
            <label class="form-label"><span class="text-danger">*</span> Employee Type</label>
            <div class="input-group">
              <select id="employee_type" name="employee_type" class="form-select employee_type select2" required>
                {!! $employee_type !!}
              </select>
            </div>
          </div>

          <!-- Type -->
          <div class="col-md-3">
            <label class="form-label"><span class="text-danger">*</span> Type</label>
            <select id="type" name="type" class="form-select select2 type" required>
              <option value="">--Please Select--</option>
              <option value="Allowance">Allowance</option>
              <option value="Deduction">Deduction</option>
            </select>
          </div>

          <!-- Allowance Name -->
          <div class="col-md-4">
            <label class="form-label"><span class="text-danger">*</span> Allowance Name</label>
            <input type="text" id="allowance_name" name="allowance_name" class="form-control allowance_name" required />
            <span class="badge bg-danger dup_name d-none"></span>
          </div>

          <!-- Active -->
          <div class="col-md-2">
            <label class="form-label">Active</label>
            <select name="active" id="active" class="form-select select2 active">
              <option value="Yes">Yes</option>
              <option value="No">No</option>
            </select>
          </div>
        </div>

        <!-- Save Button -->
        <div class="text-center mt-4">
          <button type="button" class="btn btn-success saveform px-4">Save</button>
        </div>
      </div>
    </div>

    <?php } else { ?>
    <div class="alert alert-warning text-center mt-3">You do not have permission to save this form.</div>
    <?php } ?>
  </form>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="PosTbl" class="table table-bordered table-striped w-100">
          <thead>
            <tr class="table-warning">
              <th>Employee Type</th>
              <th>Type</th>
              <th>Allowance</th>
              <th>Active</th>
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

    $(document).ready(function () {

      var table = $('#PosTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('employeeallowancegrid') }}",
        columns: [
          { data: 'lookup_code', name: 'a_lookuplines_t.lookup_code' },
          { data: 'type', name: 'type' },
          { data: 'allowance_name', name: 'allowance_name' },
          { data: 'active', name: 'active' },

          {
            data: 'allowance_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',
            width: '140px',
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
          <button type="button" class="btn btn-sm btn-primary edit-btn"
            data-id="${row.allowance_id}"
            data-code="${row.lookup_code}"
            data-name="${row.type}"
            data-aname="${row.allowance_name}"
            data-active="${row.active}">
            <i class="bi bi-pencil"></i>
          </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
            <button type="button" class="btn btn-sm btn-danger delete-btn"
              data-id="${row.job_title_id}">
              <i class="bi bi-trash"></i>
            </button>`;
              }
              return buttons;
            }
          }
        ]
      });

      // Individual column search
      $('#PosTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });


    function duplicate_validate() {

      var allowance_name = $(".allowance_name").val();
      var edit_id = $("#edit_id").val();
      var employee_type = $('#employee_type').select2('val');
      var result = true;

      $.ajax({
        cache: false,
        url: 'employeeallowance/checkname?employee_type=' + employee_type,
        type: 'GET',
        dataType: 'json',
        async: false, // blocking check
        data: { allowance_name: allowance_name, edit_id: edit_id },
        success: function (response) {
          if (response == 1) {
            $('.dup_name')
              .html('Allowance: ' + allowance_name + ' already exists')
              .removeClass('d-none')
              .addClass('d-block');

            $(".allowance_name").val('');
            dup_chk = false;
          }
          else if (response == 0) {
            var html = "";
            $('.dup_name').hide();
            dup_chk = true;

          }
        },
        error: function (xhr, resp, text) {
          console.log(xhr, resp, text);
        }
      });

      return result;
    }

    // Save Form

    $(document).on('click', '.saveform', function () {


    const form = $("#save");
 		form.parsley().validate();
		duplicate_validate();
    
		if (form.parsley().isValid() && dup_chk == true) {

        var $btn = $(this);
        $btn.prop('disabled', true);
        $.ajax({
          url: "{{ url('employeallowance/save') }}",
          type: "POST",
          data: form.serialize(),
          success: function (data) {
            showCustomAlert('Saved successfully!','success');
            form[0].reset();
            $('.select2').val('').trigger('change');
            window.location.reload();
          },
          error: function (xhr) {
            let errorMsg = 'Unexpected error occurred.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
              errorMsg = xhr.responseJSON.message;
            }
            showCustomAlert(errorMsg, 'error');
          }
        });
      }
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
          url: "{{ url('employeeallowance/delete') }}/" + deleteId,
          type: "GET",
          success: function (response) {
            $('#globalDeleteModal').modal('hide');
            showCustomAlert('Deleted successfully','success');
            $('#PosTbl').DataTable().ajax.reload();

          },
          error: function (xhr) {
            $('#globalDeleteModal').modal('hide');
            const errorMsg = xhr.responseJSON?.message || 'Delete failed.';
            showCustomAlert(errorMsg, 'error');
          }
        });
      }
    });


    // edit function
    $(document).on('click', '.edit-btn', function () {
      const id = $(this).data('id');
      const code = $(this).data('code');
      const name = $(this).data('name');
      const aname = $(this).data('aname');
      const type = $(this).data('lookuplines_id'); // This should now be the ID
      const active = $(this).data('active');

      // Fill form fields
      $('input[name="allowance_id"]').val(id);
      $('input[name="allowance_name"]').val(aname);
      $('input[name="organization_name"]').val(name);

      // For select2 fields, use .val().trigger('change')

      $('.organization_type').val(btn.data('type')).trigger('change');
      $('select[name="active"]').val(active).trigger('change');
    });


    $(document).on('keypress', '.allowance_name', function (ev) {
      var regex = new RegExp("^[a-z,A-Z.]+$");
      var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
      if (regex.test(str)) {
        return true;
      }
      ev.preventDefault();
      return false;
    });


  </script>

@endpush