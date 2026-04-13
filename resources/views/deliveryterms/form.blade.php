@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Delivery Terms</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white fw-semibold"></div>

    <div class="card-body">
      <form action="" id="save">
        <input type="hidden" id="edit_id" name="edit_id" value="">

        <?php $data = \Session::get('data');
  if (isset($data[$pageMethod]['save'])) { ?>
        {{ csrf_field() }}

        <div class="row g-3">
          <!-- Column 1 -->
          <div class="col-md-4">
            <!-- Delivery Term Name -->
            <label for="delivery_term_name" class="form-label">
              <span class="text-danger">*</span> Delivery Term Name
            </label>
            <input type="hidden" class="form-control" id="delivery_terms_id" name="delivery_terms_id" readonly>
            <input type="text" class="form-control delivery_term_name" id="delivery_term_name" name="delivery_term_name"
              value="" tabindex="1" required>
            <div class="invalid-feedback d-none btn btn-danger dup_name mt-2"></div>

            <!-- Remarks -->
            <label for="remarks" class="form-label mt-3">Remarks</label>
            <input type="text" class="form-control remarks" id="remarks" name="remarks" value="" tabindex="2">
          </div>

          <!-- Column 2 -->
          <div class="col-md-4" style="pointer-events:none;">
            <!-- Source Type -->
            <label for="source_type_id" class="form-label">Source Type</label>
            <select class="form-select select2 source_type_id" id="source_type_id" name="source_type_id" tabindex="3">
              <option value="">--select--</option>
              <option value="Purchase" <?= ($source_type_id == 'Purchase') ? 'selected' : '' ?>>Purchase</option>
              <option value="Sales" <?= ($source_type_id == 'Sales') ? 'selected' : '' ?>>Sales</option>
            </select>

            <!-- Created By -->
            <label for="created_by" class="form-label mt-3">Created By</label>
            <select class="form-select select2 created_by" id="created_by" name="created_by" tabindex="5">
              {!! $created_by !!}
            </select>
          </div>

          <!-- Column 3 -->
          <div class="col-md-4">
            <!-- Active -->
            <label for="active" class="form-label">Active</label>
            <select class="form-select select2 active" id="active" name="active" tabindex="4">
              <option value="Yes" {{ (isset($deliveryterms) && $deliveryterms->active == "Yes") ? 'selected' : '' }}>Yes
              </option>
              <option value="No" {{ (isset($deliveryterms) && $deliveryterms->active == "No") ? 'selected' : '' }}>No
              </option>

            </select>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="row mt-4">
          <div class="col text-center">
            <button type="button" class="btn btn-success px-4 saveform" tabindex="6">
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
      <table id="paytermtbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Delivery Term Name</th>
            <th>Delivery Term Name</th>
            <th>Source Type</th>
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

      var source_type = "<?php echo $source_type_id;?>";
      if (source_type == "Purchase") {
        var payment_term_name = "Purchase";
      }
      else {
        var payment_term_name = "Sales";
      }


      var table = $('#paytermtbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('getDeliverytermsData') }}/" + payment_term_name,
        columns: [
          { data: 'employee_id', name: 'employee_id', visible: false },
          { data: 'delivery_term_name', name: 'delivery_term_name' },
          { data: 'source_type_id', name: 'source_type_id' },
          { data: 'remarks', name: 'remarks' },
          { data: 'active', name: 'active' },
          { data: 'first_name', name: 'first_name' },
          {
            data: 'delivery_terms_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
            <button type="button" class="btn btn-sm btn-info edit-btn"
              data-id="${row.delivery_terms_id}"
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
                data-id="${row.delivery_terms_id}">
                <i class="bi bi-trash"></i>
              </button>`;
              }
              return buttons;
            }

          }
        ]
      });


      $('#paytermtbl thead').on('keyup change', '.column-search', function () {
        let index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });

    // Purpose for Duplicate Function for Payment term Name
    var dup_chk = true;
    function duplicate_validate() {
      var delivery_term_name = $(".delivery_term_name").val();
      var edit_id = $("#edit_id").val();
      var source_type_id = $("#source_type_id").select2('val');

      $.ajax({
        cache: false,
        url: 'deliveryterms/checkname',
        type: 'GET',
        dataType: 'json',
        async: false,
        data: { delivery_term_name: delivery_term_name, edit_id: edit_id, source_type_id: source_type_id },
        success: function (response) {
          if (response == 1) {
            $('.dup_name')
              .html('Delivery Term Name: ' + delivery_term_name + ' already exists')
              .removeClass('d-none')
              .addClass('d-block');

            $(".delivery_term_name").val('');
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
          url: "{{ URL::to('deliveryterms/save') }}",
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
      source_type = $(this).data('source');
      $('#globalDeleteModal').modal('show');
    });

    $('#globalConfirmDeleteBtn').on('click', function () {
      if (deleteId) {
        $.ajax({
          url: "{{ url('deliveryterms/delete') }}/" + deleteId + '/' + source_type,
          type: "GET",
          success: function (data) {


            if (data == '0') {

              $('#globalDeleteModal').modal('hide');
              showCustomAlert('Delete Successfully', 'success');
              $('#paytermtbl').DataTable().ajax.reload();

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


    // edit function

    $(document).on('click', '.edit-btn', function () {
      var form = $("#save");
      form.parsley().destroy(); // reset validation

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
          $('#delivery_terms_id').val(id);
          $('#delivery_term_name').val(name);
          $('#remarks').val(code);
          $('#active').val(act).trigger('change');
          $('#created_by').val(created_by).trigger('change');
          $('#source_type_id').val(source).trigger('change');
          $('#paytermtbl').DataTable().ajax.reload();
        }
      });
    });


    $('.delivery_term_name').on('keyup', function () {
      this.value = this.value.toUpperCase();
      $('.dup_name').hide();
    });

  </script>

@endpush