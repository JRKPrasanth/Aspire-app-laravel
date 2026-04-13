@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Insurance Terms</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white fw-semibold">
    </div>

    <div class="card-body">
      <form id="save" action="">
        <?php $data = \Session::get('data');
  if (isset($data[$pageMethod]['save'])) { ?>
        {{ csrf_field() }}

        <div class="row g-3">
          <!-- Left Column -->
          <div class="col-md-6">
            <!-- Insurance Term Name -->
            <label for="insurance_term_name" class="form-label">
              <span class="text-danger">*</span> Insurance Term Name
            </label>
            <input type="hidden" class="form-control" id="insurance_term_id" name="insurance_term_id" readonly>
            <input type="text" id="insurance_term_name" name="insurance_term_name"
              class="form-control insurance_term_name" required tabindex="1">
            <div class="invalid-feedback d-none btn btn-danger dup_name mt-2"></div>

            <!-- Active -->
            <label for="active" class="form-label mt-3">Active</label>
            <select name="active" id="active" class="form-select select2">
              <option value="Yes">Yes</option>
              <option value="No">No</option>
            </select>
          </div>

          <!-- Right Column -->
          <div class="col-md-6">
            <!-- Remarks -->
            <label for="description" class="form-label">Remarks</label>
            <input type="text" id="description" name="description" class="form-control" tabindex="2">

            <!-- Created By -->
            <div style="pointer-events:none;">
              <label for="created_by" class="form-label mt-3">Created By</label>
              <select id="created_by" name="created_by" class="form-select select2">
                {!! $created_by !!}
              </select>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="row mt-4">
          <div class="col text-center">
            <button type="button" class="btn btn-success px-4 saveform">
              Save
            </button>
          </div>
        </div>
        <?php } ?>
      </form>
    </div>
  </div>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <table id="InsuranceTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Insurance Term Name</th>
            <th>Insurance Term Name</th>
            <th>Remarks</th>
            <th>Active</th>
            <th>Created By</th>
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

        </tbody>
      </table>
    </div>
  </div>

@endsection
@push('scripts')




  <script>

    // table data	

    $(document).ready(function () {

      var table = $('#InsuranceTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('getinsurancetermsgrid') }}",
        columns: [
          { data: 'employee_id', name: 'employee_id', visible: false },
          { data: 'insurance_term_name', name: 'insurance_term_name' },
          { data: 'description', name: 'description' },
          { data: 'active', name: 'active' },
          { data: 'first_name', name: 'first_name' },
          {
            data: 'insurance_term_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
          <button type="button" class="btn btn-sm btn-info edit-btn"
            data-id="${row.insurance_term_id}"
            data-code="${row.description}"
            data-name="${row.insurance_term_name}"
            data-user="${row.employee_id}"
            data-active="${row.active}">
            <i class="bi bi-pencil"></i>
          </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
            <button type="button" class="btn btn-sm btn-danger delete-btn"
              data-id="${row.insurance_term_id}">
              <i class="bi bi-trash"></i>
            </button>`;
              }
              return buttons;
            }

          }
        ]
      });


      $('#InsuranceTbl thead').on('keyup change', '.column-search', function () {
        let index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });

    // validations
    var dup_chk = true;
    function duplicate_validate() {
      var insurance_term_name = $(".insurance_term_name").val();
      var edit_id = $("#insurance_term_id").val();

      $.ajax({
        cache: false,
        url: 'insurancetermschkname',
        type: 'GET',
        dataType: 'json',
        async: false,
        data: { insurance_term_name: insurance_term_name, edit_id: edit_id },
        success: function (response) {
          if (response == 1) {
            $('.dup_name')
              .html('Insurence Term Name: ' + insurance_term_name + ' Already Exists')
              .removeClass('d-none')
              .addClass('d-block');

            $(".insurance_term_name").val('');
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
      let dup_chk = true;
      var form = $("#save");
      form.parsley().validate();
      duplicate_validate();
      if (form.parsley().isValid() && dup_chk == true) {
        var $btn = $(this);
        $btn.prop('disabled', true);
        $.ajax({
          url: "{{ URL::to('insurancesterm/save') }}",
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


    //
    $('.insurance_term_name').on('keyup', function () {
      this.value = this.value.toUpperCase();
      $('.dup_name').hide();
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
          url: "{{ url('insurancetermsdelete') }}/" + deleteId,
          type: "GET",
          success: function (data) {


            if (data == '0') {

              $('#globalDeleteModal').modal('hide');
              showCustomAlert('Delete Successfully', 'success');
              $('#InsuranceTbl').DataTable().ajax.reload();

            }
            if (data == '1') {
              $('#globalDeleteModal').modal('hide');
              showCustomAlert("You Can't delete  Used in SomeWhere", 'info');
              $('.clearsearch').trigger('click');
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


    // edit

    $(document).on('click', '.edit-btn', function () {
      var form = $("#save");
      form.parsley().destroy();

      var id = $(this).data('id');
      var name = $(this).data('name');
      var code = $(this).data('code');
      var act = $(this).data('active');
      var created_by = $(this).data('user');

      var url = "{{ url('insurancetermsedit2') }}/" + id;

      $.get(url, function (data) {
        if ($.trim(data) === '1') {
          showCustomAlert("You Can't Edit. Used Somewhere.", 'info');

        } else {
          $('#edit_id').val(id);
          $('#insurance_term_id').val(id);
          $('#insurance_term_name').val(name);
          $('#description').val(code);
          $('#active').val(act).trigger('change');
          $('#created_by').val(created_by).trigger('change');
          $('#InsuranceTbl').DataTable().ajax.reload();
        }
      });
    });




  </script>

@endpush