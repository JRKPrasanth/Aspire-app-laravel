@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Employee Position</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <form id="save">
        <?php $data = \Session::get('data');
        if (isset($data[$pageMethod]['save'])) { ?>

        <input type="hidden" name="edit_id" id="edit_id" value="">
        {{ csrf_field() }}

        <div class="row g-4">
          <!-- Position -->
          <div class="col-md-4">
            <label for="job_title_name" class="form-label"><span class="text-danger">*</span> Position</label>
            <input type="text" id="job_title_name" name="job_title_name" class="form-control job_title_name" required>
            <span class="badge bg-danger dup_name d-none"></span>
          </div>

          <!-- Description -->
          <div class="col-md-4">
            <label for="job_description" class="form-label">Description</label>
            <input type="text" id="job_description" name="job_description" class="form-control job_description">
          </div>

          <!-- Active -->
          <div class="col-md-4">
            <label for="active" class="form-label">Active</label>
            <select name="active" id="active" class="form-select select2 active">
              <option value="Yes">Yes</option>
              <option value="No">No</option>
            </select>
          </div>
        </div>

        <!-- Save Button -->
        <div class="text-center mt-4">
          <button type="button" class="btn btn-success saveform px-4">
            Save
          </button>
        </div>
        <?php } ?>
      </form>
    </div>
  </div>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="PosTbl" class="table table-bordered table-striped w-100">
          <thead>
            <tr class="table-warning">
              <th>Position</th>
              <th>Description</th>
              <th>Active</th>
              <th>Actions</th>
            </tr>
            <tr class="table-info">
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
        ajax: "{{ route('employeejobtitlegrid') }}",
        columns: [
          { data: 'job_title_name', name: 'job_title_name' },
          { data: 'job_description', name: 'job_description' },
          { data: 'active', name: 'active' },
          {
            data: 'job_title_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
          <button type="button" class="btn btn-sm btn-primary edit-btn"
            data-id="${row.job_title_id}"
            data-code="${row.job_title_name}"
            data-name="${row.job_description}"
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


      $('#PosTbl thead').on('keyup change', '.column-search', function () {
        let index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });

        function duplicate_validate() {
      var job_title = $(".job_title_name").val();
      var edit_id = $("#edit_id").val();

      $.ajax({
        cache: false,
        url: 'employeejobtitle/checkname', //this is your uri
        type: 'GET',
        dataType: 'json',
        async: false,
        data: { job_title: job_title, edit_id: edit_id },
        success: function (response) {
          if (response == 1) {
            $('.dup_name')
              .html('Position Name: ' + job_title + ' Already Exists')
              .removeClass('d-none')
              .addClass('d-block');
            $(".job_title_name").val('');
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
    }

    // save function

    $(document).on('click', '.saveform', function () {
      var form = $("#save");
      form.parsley().validate();
      duplicate_validate();
      if (form.parsley().isValid() && dup_chk == true) {
        var $btn = $(this);
        $btn.prop('disabled', true);
        $.ajax({
          url: "{{ URL::to('employeejobtitle/save') }}",
          type: "POST",
          data: form.serialize(),
          success: function (data) {
            // Show success message
            showCustomAlert('Saved successfully!', 'success');
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
      const active = $(this).data('active');

      // Fill form fields
      $('input[name="job_title_id"]').val(id);
      $('input[name="job_title_name"]').val(code);
      $('input[name="job_description"]').val(name);
      $('#edit_id').val(id);
      // For select2 fields, use .val().trigger('change')
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
          url: "{{ url('employeejobtitledelete') }}/" + deleteId,
          type: "GET",
          success: function (response) {
            $('#globalDeleteModal').modal('hide');
            showCustomAlert(response.message, 'success');
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


    $(document).on('keyup', '.job_title_name', function () {

      $('.dup_name').hide();
    });

  </script>

@endpush