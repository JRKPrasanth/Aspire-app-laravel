@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Payment Terms</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white fw-bold">
    </div>
    <div class="card-body">
      <form id="save" action="">

        {{ csrf_field() }}

        <div class="row g-3">
          <!-- Payment Term Name -->
          <div class="col-md-6">
            <label for="payment_term_name" class="form-label">
              <span class="text-danger">*</span> Payment Term Name
            </label>
            <input type="hidden" class="form-control" id="payment_term_id" name="payment_term_id" value="" readonly>
            <input type="text" class="form-control payment_term_name" id="payment_term_name" name="payment_term_name"
              value="" tabindex="1" required>
            <div class="invalid-feedback d-none btn btn-danger dup_name mt-2"></div>
          </div>

          <!-- Days -->
          <div class="col-md-6">
            <label for="payment_days" class="form-label">
              <span class="text-danger">*</span> Days
            </label>
            <input type="text" class="form-control payment_days" id="payment_days" name="payment_days" value=""
              tabindex="2" required placeholder="Please enter minimum 0 days">
          </div>

          <!-- Remarks -->
          <div class="col-md-6">
            <label for="description" class="form-label">Remarks</label>
            <input type="text" class="form-control description" id="description" name="description" value="" tabindex="3">
          </div>

          <!-- Active -->
          <div class="col-md-6">
            <label for="active" class="form-label">Active</label>
            <select class="form-select select2 active" id="active" name="active" tabindex="4">
              <option value="Yes">Yes</option>
              <option value="No">No</option>
            </select>
          </div>

          <!-- Created By -->
          <div class="col-md-6" style="pointer-events:none;">
            <label for="created_by" class="form-label">Created By</label>
            <select class="form-select select2 created_by" id="created_by" name="created_by" tabindex="5">
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
      <table id="paytermTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            
            <th>Payment Term Name</th>
            <th>Payment Days</th>
            <th>Remarks</th>
            <th>Active</th>
            <th>Created By</th>
            <th>Payment Term Name</th>
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


    $(document).ready(function () {


      var dup_chk = true;
      function duplicate_validate() {
        var payment_term_name = $(".payment_term_name").val();
        var edit_id = $("#payment_term_id").val();
        $.ajax({
          cache: false,
          url: 'paymenttermschkname',
          type: 'GET',
          dataType: 'json',
          async: false,
          data: { payment_term_name: payment_term_name, edit_id: edit_id },
          success: function (response) {

            if (response == 1)
            {
                $('.dup_name')
                    .html('Payment Term Name: ' + payment_term_name + ' Already Exists')
                    .removeClass('d-none')
                    .addClass('d-block');

                $(".payment_term_name").val('');
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



      //  Purpose For Upper Case

      $('.payment_term_name').on('keyup', function () {
        this.value = this.value.toUpperCase();
        $('.dup_name').hide();
      });


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
            url: "{{ URL::to('paymentterms/save') }}",
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


      $(document).ready(function () {
        var table = $('#paytermTbl').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ route('getpaytermGridData') }}",
          columns: [
            { data: 'payment_term_name', name: 'm_payment_terms_t.payment_term_name' },
            { data: 'payment_days', name: 'payment_days' },
            { data: 'description', name: 'description' },
            { data: 'active', name: 'active' },
            { data: 'first_name', name: 'first_name' },
            { data: 'employee_id', name: 'employee_id', visible: false },
            {
              data: 'payment_term_id',
              name: 'actions',
              orderable: false,
              searchable: false,
              render: function (data, type, row) {
                let buttons = '';
                if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                  buttons += `
            <button type="button" class="btn btn-sm btn-info edit-btn"
              data-id="${row.payment_term_id}"
              data-code="${row.description}"
              data-name="${row.payment_term_name}"
              data-days="${row.payment_days}"
              data-user="${row.employee_id}"
              data-active="${row.active}">
              <i class="bi bi-pencil"></i>
            </button>`;
                }
                if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                  buttons += `
              <button type="button" class="btn btn-sm btn-danger delete-btn"
                data-id="${row.payment_term_id}">
                <i class="bi bi-trash"></i>
              </button>`;
                }
                return buttons;
              }

            }
          ]
        });


        $('#paytermTbl thead').on('keyup change', '.column-search', function () {
          let index = $(this).closest('th').index();
          table.column(index).search(this.value).draw();
        });
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
            url: "{{ url('paymenttermsdelete') }}/" + deleteId,
            type: "GET",
            success: function (data) {


              if (data == '0') {

                $('#globalDeleteModal').modal('hide');
                showCustomAlert('Delete Successfully', 'success');
                $('#paytermTbl').DataTable().ajax.reload();

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
        var days = $(this).data('days');
        var des = $(this).data('code');
        var act = $(this).data('active');
        var created_by = $(this).data('user');

        var url = "{{ url('paymenttermsedit') }}/" + id;

        $.get(url, function (data) {
          if ($.trim(data) === '1') {
            showCustomAlert("You Can't Edit. Used Somewhere.", 'info');

          } else {
            $('#edit_id').val(id);
            $('#payment_term_id').val(id);
            $('#payment_term_name').val(name);
            $('#description').val(des);
            $('#payment_days').val(days);
            $('#active').val(act).trigger('change');
            $('#created_by').val(created_by).trigger('change');
            $('#paytermTbl').DataTable().ajax.reload();
          }
        });
      });


    });

  </script>

@endpush