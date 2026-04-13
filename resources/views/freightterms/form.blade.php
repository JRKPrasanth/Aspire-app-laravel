@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Freight Terms</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">

    <div class="card-body">
      <form id="save">
        <input type="hidden" name="edit_id" value="" id="edit_id" />

        {{ csrf_field() }}

        <div class="row g-3">
          <!-- Column 1 -->
          <div class="col-md-4">
            <!-- FOB Point Name -->
            <label for="fob_point_name" class="form-label">
              <span class="text-danger">*</span> FOB Point Name
            </label>
            <input type="hidden" class="form-control frieghtterm_id" id="frieghtterm_id" name="frieghtterm_id" readonly>
            <input type="text" id="fob_point_name" name="fob_point_name" class="form-control fob_point_name" required
              tabindex="1">
            <div class="invalid-feedback d-none btn btn-danger dup_name mt-2"></div>

            <!-- FOB Barriers -->
            <label for="fob_barriers" class="form-label mt-3">
              <span class="text-danger">*</span> FOB Barriers
            </label>
            <input type="text" id="fob_barriers" name="fob_barriers" class="form-control fob_barriers" required
              tabindex="3">

            <!-- Active -->
            <label for="active" class="form-label mt-3">Active</label>
            <select id="active" name="active" class="form-select select2 active" tabindex="5">
              <option value="Yes" <?= ($active == "Yes") ? 'selected' : '' ?>>Yes</option>
              <option value="No" <?= ($active == "No") ? 'selected' : '' ?>>No</option>
            </select>

          </div>

          <!-- Column 2 -->
          <div class="col-md-4">
            <!-- FOB Payment -->
            <label for="fob_payment" class="form-label">FOB Payment</label>
            <input type="text" id="fob_payment" name="fob_payment" class="form-control fob_payment" tabindex="2">

            <!-- FOB Location -->
            <label for="fob_location" class="form-label mt-3">FOB Location</label>
            <div class="input-group">
              <select id="fob_location" name="fob_location" class="form-select select2 fob_location" tabindex="4">
                {!! $fob_location !!}
              </select>
            </div>
          </div>

          <!-- Column 3 -->
          <div class="col-md-4" style="pointer-events:none;">
            <!-- Source Type -->
            <label for="source_type_id" class="form-label">Source Type</label>
            <select id="source_type_id" name="source_type_id" class="form-select select2 source_type_id">
              <option value="0">--Please Select--</option>
              <option value="Purchase" <?= ($source_type_id == "Purchase") ? 'selected' : '' ?>>Purchase</option>
              <option value="Sales" <?= ($source_type_id == "Sales") ? 'selected' : '' ?>>Sales</option>
            </select>

            <!-- Created By -->
            <label for="created_by" class="form-label mt-3">Created By</label>
            <select id="created_by" name="created_by" class="form-select select2 created_by">
              {!! $created_by !!}
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
      </form>
    </div>
  </div>




  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <table id="freightTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Fob Location</th>
            <th>Fob Point Name</th>
            <th>Fob Barriers</th>
            <th>Fob Location</th>
            <th>Fob Payment</th>
            <th>Source Type</th>
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

    // table data	

    $(document).ready(function () {

      var source_type = "<?php echo $source_type_id;?>";
      if (source_type == "Purchase") {
        var payment_term_name = "Purchase";
      } else {
        var payment_term_name = "Sales";
      }


      var table = $('#freightTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('getfreighttermdata') }}/" + payment_term_name,

        columns: [
          { data: 'employee_id', name: 'employee_id', visible: false },
          { data: 'fob_point_name', name: 'fob_point_name' },
          { data: 'fob_barriers', name: 'fob_barriers' },
          { data: 'location_name', name: 'location_name' },
          { data: 'fob_payment', name: 'fob_payment' },
          { data: 'source_type_id', name: 'source_type_id' },
          { data: 'active', name: 'active' },
          { data: 'first_name', name: 'first_name' },
          {
            data: 'frieghtterm_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
              <button type="button" class="btn btn-sm btn-info edit-btn"
                data-id="${row.frieghtterm_id}"
                data-code="${row.fob_barriers}"
                data-source="${row.source_type_id}"
                data-name="${row.fob_point_name}"
                data-pay="${row.fob_payment}"
                 data-barrier="${row.fob_barriers}"
                data-user="${row.employee_id}"
                data-active="${row.active}">
                <i class="bi bi-pencil"></i>
              </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
                <button type="button" class="btn btn-sm btn-danger delete-btn"
                  data-id="${row.frieghtterm_id}">
                  <i class="bi bi-trash"></i>
                </button>`;
              }
              return buttons;
            }

          }
        ]
      });


      $('#freightTbl thead').on('keyup change', '.column-search', function () {
        let index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });


    // validations	

    var dup_chk = true;
    function duplicate_validate() {
      var fob_point_name = $(".fob_point_name").val();
      var source_type_id = $(".source_type_id").val();
      var edit_id = $("#edit_id").val();

      $.ajax({
        cache: false,
        url: 'freightterms/checkname',
        type: 'GET',
        dataType: 'json',
        async: false,
        data: { fob_point_name: fob_point_name, source_type_id: source_type_id, edit_id: edit_id },
        success: function (response) {
          console.log(response);
          if (response == 1) {
            $('.dup_name')
              .html('FOB Name: ' + fob_point_name + ' Already Exists')
              .removeClass('d-none')
              .addClass('d-block');

            $(".fob_point_name").val('');
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


    $(document).on('keypress', '.fob_payment', function (ev) {
      // number only allow
      var regex = new RegExp("^[0-9.]+$");
      var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
      if (regex.test(str)) {
        return true;
      }
      ev.preventDefault();
      return false;
    });


    $('.fob_payment').bind("cut copy paste", function (e) {
      e.preventDefault();
    });

    $('.fob_point_name').on('keyup', function () {
      this.value = this.value.toUpperCase();
    })


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
          url: "{{ URL::to('freightterms/save') }}",
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
          url: "{{ url('freighttermsdelete') }}/" + deleteId + '/' + source_type,
          type: "GET",
          success: function (data) {


            if (data == '0') {

              $('#globalDeleteModal').modal('hide');
              showCustomAlert('Delete Successfully', 'success');
              $('#freightTbl').DataTable().ajax.reload();

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
      form.parsley().destroy();

      var id = $(this).data('id');
      var name = $(this).data('name');
      var code = $(this).data('code');
      var pay = $(this).data('pay');
      var barrier = $(this).data('barrier');
      var source = $(this).data('source');
      var act = $(this).data('active');
      var created_by = $(this).data('user');

      var url = "{{ url('freighttermedit2') }}/" + id + "/" + source;

      $.get(url, function (data) {
        if ($.trim(data) === '1') {
          showCustomAlert("You Can't Edit. Used Somewhere.", 'info');

        } else {
          $('#edit_id').val(id);
          $('#frieghtterm_id').val(id);
          $('#fob_point_name').val(name);
          $('#fob_payment').val(pay);
          $('#fob_barriers').val(barrier);
          $('#remarks').val(code);
          $('#active').val(act).trigger('change');
          $('#created_by').val(created_by).trigger('change');
          $('#freightTbl').DataTable().ajax.reload();
        }
      });
    });




  </script>


@endpush