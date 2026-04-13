@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Supplier Types</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white">
    </div>
    <div class="card-body">
      <form action="" id="save">
        <?php $data = \Session::get('data');
  if (isset($data[$pageMethod]['save'])) { ?>
        {{ csrf_field() }}

        <input type="hidden" class="form-control" id="edit_id" name="edit_id" value="" readonly>

        <div class="row g-4">
          <!-- Supplier Type -->
          <div class="col-md-4">
            <label for="suppliertype_name" class="form-label">
              <span class="text-danger">*</span> Supplier Type
            </label>
            <input type="text" id="suppliertype_name" name="suppliertype_name" class="form-control suppliertype_name"
              value="" required tabindex="1">
            <span class="badge bg-danger dup_name d-none"></span>
          </div>

          <!-- Created By -->
          <div class="col-md-4" style="pointer-events:none;">
            <label for="created_by" class="form-label">Created By</label>
            <select name="created_by" id="created_by" class="form-select select2">
              {!! $created_by !!}
            </select>
          </div>

          <!-- GST Required -->
          <div class="col-md-4">
            <label for="gst_required" class="form-label">
              <span class="text-danger">*</span> GST Required
            </label>
            <select name="gst_required" id="gst_required" class="form-select select2" required>
              <option value="">Please Select</option>
              <option value="Yes">Yes</option>
              <option value="No">No</option>
            </select>
          </div>

          <!-- Active -->
          <div class="col-md-4">
            <label for="active" class="form-label">Active</label>
            <select name="active" id="active" class="form-select select2">
              <option value="Yes">Yes</option>
              <option value="No">No</option>
            </select>
          </div>

          <!-- Remarks -->
          <div class="col-md-4">
            <label for="description" class="form-label">Remarks</label>
            <input type="text" id="description" name="description" class="form-control" value="" tabindex="2">
          </div>
        </div>

        <!-- Submit Button -->
        <div class="text-center mt-4">
          <button type="button" class="btn btn-success px-4 saveform">
            Save
          </button>
        </div>
        <?php } ?>
      </form>
    </div>
  </div>



  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <table id="supplierTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Supplier Type Name</th>
            <th>Supplier Type Name</th>
            <th>GST Required</th>
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

      var table = $('#supplierTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('getSuppliertypesData') }}",
        columns: [
          { data: 'employee_id', name: 'employee_id', visible: false },
          { data: 'suppliertype_name', name: 'suppliertype_name' },
          { data: 'gst_required', name: 'gst_required' },
          { data: 'description', name: 'description' },
          { data: 'active', name: 'active' },
          { data: 'first_name', name: 'first_name' },
          {
            data: 'suppliertype_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
            <button type="button" class="btn btn-sm btn-info edit-btn"
              data-id="${row.suppliertype_id}"
              data-code="${row.gst_required}"
              data-name="${row.suppliertype_name}"
              data-desc="${row.description}"
              data-user="${row.employee_id}"
              data-active="${row.active}">
              <i class="bi bi-pencil"></i>
            </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
              <button type="button" class="btn btn-sm btn-danger delete-btn"
                data-id="${row.suppliertype_id}">
                <i class="bi bi-trash"></i>
              </button>`;
              }
              return buttons;
            }

          }
        ]
      });


      $('#supplierTbl thead').on('keyup change', '.column-search', function () {
        let index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });

    // save function

    var dup_chk = true;
    function duplicate_validate() {
      var suppliertype_name = $(".suppliertype_name").val();
      var edit_id = $("#edit_id").val();

      $.ajax({
        cache: false,
        url: 'suppliertypes/checkname',
        type: 'GET',
        dataType: 'json',
        async: false,
        data: { suppliertype_name: suppliertype_name, edit_id: edit_id },
        success: function (response) {
          console.log(response);

          if (response == 1) {
            $('.dup_name')
              .html('Supplier type Name: ' + suppliertype_name + ' Already Exists')
              .removeClass('d-none')
              .addClass('d-block');

            $(".uom_code").val('');
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


    $(document).on('click', '.saveform', function () {
      let dup_chk = true;
      var form = $("#save");
      form.parsley().validate();
      duplicate_validate();
      if (form.parsley().isValid() && dup_chk == true) {
        $.ajax({
          url: "{{ URL::to('suppliertypes/save') }}",
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


    // delete function
    let deleteId = null;

    $(document).on('click', '.delete-btn', function () {
      deleteId = $(this).data('id');
      $('#globalDeleteModal').modal('show');
    });

    $('#globalConfirmDeleteBtn').on('click', function () {
      if (deleteId) {
        $.ajax({
          url: "{{ url('suppliertypesdelete') }}/" + deleteId,
          type: "GET",
          success: function (data) {


            if (data == '0') {

              $('#globalDeleteModal').modal('hide');
              showCustomAlert('Delete Successfully', 'success');
              $('#supplierTbl').DataTable().ajax.reload();

            }
            if (data == '2') {
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
      var desc = $(this).data('desc');
      var act = $(this).data('active');
      var created_by = $(this).data('user');

      var url = "{{ url('suppliertypeedit1') }}/" + id;

      $.get(url, function (data) {
        if ($.trim(data) === '1') {
          showCustomAlert("You Can't Edit. Used Somewhere.", 'info');

        } else {
          $('#edit_id').val(id);
          $('#suppliertype_id').val(id);
          $('#suppliertype_name').val(name);
          $('#description').val(desc);

          $('#active').val(act).trigger('change');
          $('#created_by').val(created_by).trigger('change');
          $('#gst_required').val(code).trigger('change');
          $('#supplierTbl').DataTable().ajax.reload();
        }
      });
    });


    $('.suppliertype_name').on('keyup', function () {

      this.value = this.value.toUpperCase();

    })
  </script>

@endpush