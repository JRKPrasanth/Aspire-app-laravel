@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Customer Type</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-success bg-gradient text-white fw-semibold"></div>
    <div class="card-body">
      <form id="save">
        <?php $data = \Session::get('data');
  if (isset($data[$pageMethod]['save'])) { ?>

        {{ csrf_field() }}

        <div class="row g-4">
          <!-- Column 1 -->
          <div class="col-md-4">
            <div class="mb-3 row align-items-center">
              <label for="customer_type" class="col-md-5 col-form-label">
                <span class="text-danger ">*</span> Customer Type
              </label>
              <div class="col-md-7">
                <input type="hidden" class="form-control customer_type_id" id="customer_type_id" name="customer_type_id"
                  readonly>

                <input type="text" id="customer_type" name="customer_type" class="form-control customer_type" required
                  tabindex="1">

                <span class="btn btn-sm btn-danger dup_name mt-1" style="display:none;"></span>
              </div>
            </div>

            <div class="mb-3 row align-items-center">
              <label for="active" class="col-md-5 col-form-label">Active</label>
              <div class="col-md-7">
                <select name="active" id="active" class="form-select select2 active" tabindex="4">
                  <option value="Yes">Yes</option>
                  <option value="No">No</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Column 2 -->
          <div class="col-md-4">
            <div class="mb-3 row align-items-center">
              <label for="gst_required" class="col-md-5 col-form-label">
                <span class="text-danger">*</span> GST Required
              </label>
              <div class="col-md-7">
                <select name="gst_required" id="gst_required" class="form-select select2 gst_required" required
                  tabindex="2">
                  <option value="">Please Select</option>
                  <option value="Yes">Yes</option>
                  <option value="No">No</option>
                </select>
              </div>
            </div>

            <div class="mb-3 row align-items-center">
              <label for="created_by" class="col-md-5 col-form-label">Created By</label>
              <div class="col-md-7" style="pointer-events:none;">
                <select name="created_by" id="created_by" class="form-select select2 created_by">
                  {!! $created_by !!}
                </select>
              </div>
            </div>
          </div>

          <!-- Column 3 -->
          <div class="col-md-4">
            <div class="mb-3 row align-items-center">
              <label for="description" class="col-md-5 col-form-label">Description</label>
              <div class="col-md-7">
                <input type="text" id="description" name="description" class="form-control description" tabindex="3">
              </div>
            </div>

            <div class="mb-3 row align-items-center">
              <label for="account_id" class="col-md-5 col-form-label">Account Structure</label>
              <div class="col-md-7">
                <select name="account_id" id="account_id" class="form-select select2 account_id">
                  {!! $account_structure !!}
                </select>
              </div>
            </div>
          </div>
        </div>

        <!-- Save button -->
        <div class="row mt-4">
          <div class="col text-center">
            <button type="button" class="btn btn-success saveform px-4">Save</button>
          </div>
        </div>

        <?php } ?>
      </form>
    </div>
  </div>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <table id="CustomerTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th></th>
            <th></th>
            <th>Customer type</th>
            <th>GST Required</th>
            <th>Description</th>
            <th>Account</th>
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

    // data table funcrion	
    $(document).ready(function () {
      var table = $('#CustomerTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('mcustomertypesdata') }}",
        columns: [

          { data: 'account_id', name: 'account_id', visible: false },
          { data: 'created_id', name: 'created_id', visible: false },
          { data: 'customer_type', name: 'customer_type' },
          { data: 'gst_required', name: 'gst_required' },
          { data: 'description', name: 'description' },
          { data: 'concatenated_segments', name: 'concatenated_segments' },
          { data: 'active', name: 'active' },
          { data: 'first_name', name: 'first_name' },
          {
            data: 'customer_type_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',
            width: '140px',
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `<button class="btn btn-sm btn-info me-1 edit-btn" 
          data-id="${row.customer_type_id}" 
          data-type="${row.customer_type}" 
          data-desc="${row.description}" 
          data-account="${row.account_id}" 
          data-created="${row.created_id}" 
          data-gst="${row.gst_required}" 
          data-active="${row.active}">
          <i class="bi bi-pencil"></i>
        </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
        <button class="btn btn-sm btn-danger delete-btn" data-id="${row.customer_type_id}">
          <i class="bi bi-trash"></i>
        </button>`;
              }
              return buttons;
            }
          }
        ]
      });

      // Individual column search
      $('#CustomerTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });

    /* Start Duplicate validate */
    var dup_chk = true;
    function duplicate_validate() {
      var customer_type = $(".customer_type").val();
      var edit_id = $("#customer_type_id").val();
      if (!customer_type == '') {
        $.ajax({
          cache: false,
          url: 'mcustomertypes/checkname',
          type: 'GET',
          dataType: 'json',
          async: false,
          data: { customer_type: customer_type, edit_id: edit_id },
          success: function (response) {
            console.log(response);
            if (response == 1) {
              $('.dup_name').html('customer type:' + customer_type + ' Already Exists');
              $('.dup_name').show();
              $(".customer_type").val('');
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
      else {
        showCustomAlert('Please Enter the Customer Type', "warning");
      }
    }


    $(document).ready(function () {


      $('.customer_type').on('keyup', function () {
        $('.dup_name').hide();
        this.value = this.value.toUpperCase();
      });


      // save function

      $(document).on('click', '.saveform', function () {

        var form = $("#save");
        form.parsley().validate();
        duplicate_validate();
        if (form.parsley().isValid() && dup_chk == true) {
          var $btn = $(this);
          $btn.prop('disabled', true);
          $.ajax({
            url: "{{ URL::to('mcustomertypes/save') }}",
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


      // delete function
      let deleteId = null;

      $(document).on('click', '.delete-btn', function () {
        deleteId = $(this).data('id');
        $('#globalDeleteModal').modal('show');
      });

      $('#globalConfirmDeleteBtn').on('click', function () {
        if (deleteId) {
          $.ajax({
            url: "{{ url('mcustomertypes/delete/') }}/" + deleteId,
            type: "GET",
            success: function (data) {
              if (data == '0') {
                $('#globalDeleteModal').modal('hide');
                showCustomAlert('Deleted successfully!', 'success');
                $('#CustomerTbl').DataTable().ajax.reload();
              }
              if (data == '1') {
                $('#globalDeleteModal').modal('hide');
                showCustomAlert("You Can't delete , Used in SomeWhere.", 'error');
                $('#CustomerTbl').DataTable().ajax.reload();
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
        const id = $(this).data('id');
        const type = $(this).data('type');
        const desc = $(this).data('desc');
        const account = $(this).data('account');
        const created = $(this).data('created');
        const gst = $(this).data('gst');
        const active = $(this).data('active');

        var url = "{{ URL::to('editdata') }}/" + id;

        $.get(url, function (data) {
          var data = $.trim(data);
          if (data == 0) {

            // Fill form fields
            $('input[name="customer_type_id"]').val(id);
            $('input[name="customer_type"]').val(type);
            $('input[name="description"]').val(desc);
            $('select[name="gst_required"]').val(gst).trigger('change');
            $('select[name="created_by"]').val(created).trigger('change');
            $('select[name="account_id"]').val(account).trigger('change');
            $('select[name="active"]').val(active).trigger('change');

          } else {

            showCustomAlert("You Can't be Edit this Customer Type, Already used", "warning");

          }
        });

      });

    });	
  </script>

@endpush