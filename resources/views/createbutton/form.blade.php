@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Menu Button Creation</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white fw-semibold">
      Create Button
    </div>

    <div class="card-body">
      <form id="buttonForm">
        {{ csrf_field() }}
        <input type="hidden" id="edit_id" name="edit_id">

        <div class="row g-4">

          <div class="col-md-4">
            <label class="form-label">
              <span class="text-danger">*</span> Url
            </label>
            <input type="text" class="form-control" id="url" name="url" required>
          </div>


          <div class="col-md-4 mt-4 none">
            <label class="form-label">Created By</label>
            <select class="form-select select2" id="created_by" name="created_by">
              {!! $created_by !!}
            </select>
          </div>

          <div class="col-md-4">
            <label class="form-label">Active</label>
            <select class="form-select select2" id="active" name="active">
              <option value="Yes">Yes</option>
              <option value="No">No</option>
            </select>
          </div>
        </div>

        <div class="row mt-4">
          <div class="col-12 linetable">
            <div class="table-responsive" id="preview-area">
              <table class="table table-bordered clone_table">
                <thead class="table-light">
                  <tr>
                    <th>Line No</th>
                    <th>Button Name</th>
                    <th>Button Id</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody class="clone_lines_body">

                  <tr class="line-row">
                    <td><input type="text" class="form-control bulk_line_no" name="bulk_line_no[]" value="1" readonly>
                    </td>
                    <td><input type="text" class="form-control button_name" name="button_name[]" required></td>

                    <td><input type="text" class="form-control button_id" name="button_id[]" required></td>
                    <td class="text-center">
                      <button type="button" class="btn btn-sm btn-danger remove-row">
                        <i class="fas fa-minus-circle"></i>
                      </button>
                    </td>

                  </tr>

                </tbody>

              </table>

              <div class="text-end">
                <button type="button" class="btn btn-success btn-sm add-row">
                  <i class="fas fa-plus-circle"></i> Add Row
                </button>
              </div>
            </div>

          </div>
        </div>

        <!-- Submit -->
        <div class="text-center mt-4">
          <button type="button" class="btn btn-success px-4 saveform">
            Save
          </button>
        </div>

      </form>
    </div>
  </div>



  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <table id="buttonTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Url</th>
            <th>Button Name</th>
            <th>Button Id</th>
            <th>Actions</th>
          </tr>
          <tr class="table-info">
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

    // Add Row
    $(document).on('click', '.add-row', function () {
      const $tbody = $('.clone_lines_body');
      const $lastRow = $tbody.find('tr:last');

      // Clone the last row
      const $newRow = $lastRow.clone(false, false);

      // Clear INPUT values in the new row
      $newRow.find('input').val('');

      // Reset line_no (will be updated below)
      $newRow.find('.bulk_line_no').val('');

      // Fix duplicate IDs
      $newRow.find('[id]').each(function () {
        const newId = $(this).attr('id') + '_' + Date.now();
        $(this).attr('id', newId);
      });

      // Append new row
      $tbody.append($newRow);

      // Recalculate line numbers
      updateLineNumbers();
    });


    // Remove button
    $(document).on('click', '.remove-row', function () {
      const rowCount = $('.clone_lines_body tr').length;
      if (rowCount > 1) {
        $(this).closest('tr').remove();
        updateLineNumbers();
      } else {
        showCustomAlert("You Can't Delete Atleast One row should be There", "error");
      }
    });

    // Renumber Line Nos
    function updateLineNumbers() {
      $('.clone_lines_body tr').each(function (index) {
        $(this).find('.bulk_line_no').val(index + 1);
      });
    }


    // table data	
    $(document).ready(function () {


      var table = $('#buttonTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('getbuttonData') }}",
        columns: [
          { data: 'module_name', name: 'module_name' },
          { data: 'button_name', name: 'button_name' },
          { data: 'button_id_name', name: 'button_id_name' },

          {
            data: 'button_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
                <button type="button" class="btn btn-sm btn-primary edit-btn"
                  data-id="${row.button_id}"
                  data-source="${row.source_type_id}"
                  data-code="${row.remarks}"
                  data-name="${row.delivery_term_name}"
                  data-user="${row.employee_id}"
                  data-active="${row.active}">
                  <i class="bi bi-pencil"></i>
                </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
                  <button type="button" class="btn btn-sm btn-danger delete-btn"
                    data-id="${row.button_id}">
                    <i class="bi bi-trash"></i>
                  </button>`;
              }
              return buttons;
            }

          }
        ]
      });


      $('#buttonTbl thead').on('keyup change', '.column-search', function () {
        let index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });



    // save function


    $(document).on('click', '.saveform', function () {
      let dup_chk = true;
      var form = $("#buttonForm");
      form.parsley().validate();
      if (form.parsley().isValid() && dup_chk == true) {
        var $btn = $(this);
        $btn.prop('disabled', true);
        $.ajax({
          url: "{{ URL::to('savebutton') }}",
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
          url: "{{ url('buttondelete/') }}/" + deleteId,
          type: "GET",
          success: function (data) {

            if (data.status === 'success') {
              $('#globalDeleteModal').modal('hide');
              showCustomAlert(data.message, 'success');
              $('#buttonTbl').DataTable().ajax.reload();
            }

            if (data.status === 'error') {
              $('#globalDeleteModal').modal('hide');
              showCustomAlert(data.message, 'error');
              $('#buttonTbl').DataTable().ajax.reload();
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


    // edit function

    $(document).on('click', '.edit-btn', function () {
      var form = $("#save");

      // ✅ SAFE destroy
    if (form.length && form.data('Parsley')) {
        form.parsley().destroy();
    }

      var id = $(this).data('id');
      var name = $(this).data('name');
      var code = $(this).data('code');
      var source = $(this).data('source');
      var act = $(this).data('active');
      var created_by = $(this).data('user');

      var url = "{{ url('deliverytermedit2') }}/" + id + "/" + source;

      $.get(url, function (data) {
        if ($.trim(data) === '1') {
          showCustomAlert("You Can't Edit. Used Somewhere.", 'info');

        } else {
          $('#edit_id').val(id);
          $('#button_id').val(id);
          $('#delivery_term_name').val(name);
          $('#remarks').val(code);
          $('#active').val(act).trigger('change');
          $('#created_by').val(created_by).trigger('change');
          $('#source_type_id').val(source).trigger('change');
          $('#buttonTbl').DataTable().ajax.reload();
        }
      });
    });


  </script>

@endpush