@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Payment Methods</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white fw-semibold"></div>

    <div class="card-body">
      <form id="paymentmethods" action="">
        <?php $data = \Session::get('data');
  if (isset($data[$pageMethod]['save'])) { ?>
        {{ csrf_field() }}

        <div class="row g-3">
          <!-- Payment Method Name -->
          <div class="col-md-6">
            <label for="payment_method_name" class="form-label">
              <span class="text-danger">*</span> Payment Method Name
            </label>
            <input type="hidden" class="form-control" id="payment_method_id" name="payment_method_id" value="" readonly>
            <input type="text" class="form-control payment_method_name" id="payment_method_name"
              name="payment_method_name" required tabindex="1">
            <div class="invalid-feedback d-none btn btn-danger dup_name mt-2"></div>
          </div>

          <!-- Remarks -->
          <div class="col-md-6">
            <label for="description" class="form-label">Remarks</label>
            <input type="text" class="form-control description" id="description" name="description" value="" tabindex="2">
          </div>

          <!-- Active -->
          <div class="col-md-6">
            <label for="active" class="form-label">Active</label>
            <select class="form-select select2 active" name="active" id="active" tabindex="3">
              <option value="Yes">Yes</option>
              <option value="No">No</option>
            </select>
          </div>

          <!-- Created By -->
          <div class="col-md-6" style="pointer-events:none;">
            <label for="created_by" class="form-label">Created By</label>
            <select class="form-select select2 created_by" name="created_by" id="created_by" tabindex="4">
              {!! $created_by !!}
            </select>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="row mt-4">
          <div class="col text-center">
            <button type="button" class="btn btn-success px-4 saveform" tabindex="5">
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
      <table id="paymethodtbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Payment Method Name</th>
            <th>Payment Method Name</th>
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
      var table = $('#paymethodtbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('getPaymentmethodsData') }}",
        columns: [
          { data: 'employee_id', name: 'employee_id', visible: false },
          { data: 'payment_method_name', name: 'payment_method_name' },
          { data: 'description', name: 'description' },
          { data: 'active', name: 'active' },
          { data: 'first_name', name: 'first_name' },
          {
            data: 'payment_method_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
            <button type="button" class="btn btn-sm btn-info edit-btn"
              data-id="${row.payment_method_id}"
              data-code="${row.description}"
              data-name="${row.payment_method_name}"
              data-user="${row.employee_id}"
              data-active="${row.active}">
              <i class="bi bi-pencil"></i>
            </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
              <button type="button" class="btn btn-sm btn-danger delete-btn"
                data-id="${row.payment_method_id}">
                <i class="bi bi-trash"></i>
              </button>`;
              }
              return buttons;
            }

          }
        ]
      });


      $('#paymethodtbl thead').on('keyup change', '.column-search', function () {
        let index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });


    // 	validate
    var dup_chk = true;

    function duplicate_validate() {
      var payment_method_name = $("#payment_method_name").val();
      var edit_id = $("#payment_method_id").val();
      $.ajax({
        cache: false,
        url: 'paymentnamechk',
        type: 'GET',
        dataType: 'json',
        async: false,
        data: { payment_method_name: payment_method_name, edit_id: edit_id },
        success: function (response) {
          if (response == 1)
            {
                $('.dup_name')
                    .html('Payment Method Name: ' + payment_method_name + ' Already Exists')
                    .removeClass('d-none')
                    .addClass('d-block');

                $(".payment_method_name").val('');
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
      var form = $("#paymentmethods");
      form.parsley().validate();
      duplicate_validate();
      if (form.parsley().isValid() && dup_chk == true) {
        var $btn = $(this);
        $btn.prop('disabled', true);
        $.ajax({
          url: "{{ URL::to('paymentmethods/save') }}",
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
          url: "{{ url('paymentmethodsdelete') }}/" + deleteId,
          type: "GET",
          success: function (data) {


            if (data == '0') {

              $('#globalDeleteModal').modal('hide');
              showCustomAlert('Delete Successfully', 'success');
              $('#paymethodtbl').DataTable().ajax.reload();

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
      var form = $("#paymentmethods");
      form.parsley().destroy();

      var id = $(this).data('id');
      var name = $(this).data('name');
      var des = $(this).data('code');
      var act = $(this).data('active');
      var created_by = $(this).data('user');



      var url = "{{ url('paymentmethodsedit2') }}/" + id + "/" + name;

      $.get(url, function (data) {
        if ($.trim(data) === '1') {
          showCustomAlert("You Can't Edit. Used Somewhere.", 'info');

        } else {
          $('#edit_id').val(id);
          $('#payment_method_id').val(id);
          $('#payment_method_name').val(name);
          $('#description').val(des);
          $('#active').val(act).trigger('change');
          $('#created_by').val(created_by).trigger('change');
          $('#paymethodtbl').DataTable().ajax.reload();
        }
      });
    });


    $('.payment_method_name').on('keyup', function () {
      this.value = this.value.toUpperCase();
      $('.dup_name').hide();
    });

  </script>

@endpush