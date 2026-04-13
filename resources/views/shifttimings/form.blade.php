@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Shift Timings</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <form action="" id="shift_form">
        <?php $data = \Session::get('data');
  if (isset($data[$pageMethod]['save'])) { ?>
        {{ csrf_field() }}
        <input type="hidden" name="edit_id" id="edit_id" value="">

        <div class="row mb-3">
          <!-- Shift Name -->
          <div class="col-md-4">
            <label for="shift_name" class="form-label">
              <span class="text-danger">*</span> Shift Name
            </label>
            <input type="text" id="shift_name" name="shift_name" class="form-control" required>
            <span class="btn btn-danger mt-2 dup_name" style="display: none;"></span>
          </div>

          <!-- Start Time -->
          <div class="col-md-4">
            <label for="start_time" class="form-label">
              <span class="text-danger">*</span> Start Time
            </label>
            <input type="text" id="start_time" name="start_time" class="form-control start_date_time" required readonly>
          </div>

          <!-- End Time -->
          <div class="col-md-4">
            <label for="end_time" class="form-label">
              <span class="text-danger">*</span> End Time
            </label>
            <input type="text" id="end_time" name="end_time" class="form-control end_date_time" required readonly>
          </div>
        </div>

        <!-- Save Button -->
        <div class="row mt-2">
          <div class="col-md-12 text-center">
            <button type="button" id="save_btn" class="btn btn-success px-4 save_form">
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
      <table id="UplTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Shift Name</th>
            <th>Start Time</th>
            <th>End Time</th>
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
          {{-- DataTable will populate via AJAX --}}
        </tbody>
      </table>
    </div>
  </div>




@endsection
@push('scripts')

  <script>

    // table data		

    $(document).ready(function () {
      var table = $('#UplTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('shifttiminggrid') }}",
        columns: [
          { data: 'shift_name', name: 'shift_name' },
          { data: 'start_time', name: 'start_time' },
          { data: 'end_time', name: 'end_time' },

          {
            data: 'shift_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
          <button type="button" class="btn btn-sm btn-primary edit-btn"
            data-id="${row.shift_id}"
            data-name="${row.shift_name}"
            data-stime="${row.start_time}"
            data-etime="${row.end_time}">
            <i class="bi bi-pencil"></i>
          </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
            <button type="button" class="btn btn-sm btn-danger delete-btn"
              data-id="${row.shift_id}">
              <i class="bi bi-trash"></i>
            </button>`;
              }
              return buttons;
            }

          }
        ]
      });


      $('#UplTbl thead').on('keyup change', '.column-search', function () {
        let index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });

    function duplicate_validate() {

      var shift_name = $(".shift_name").val();
      var edit_id = $("#edit_id").val();
      $.ajax({
        cache: false,
        url: 'shifttiming/checkname',
        type: 'GET',
        dataType: 'json',
        async: false,
        data: { shift_name: shift_name, edit_id: edit_id },
        success: function (response) {
          if (response == 1) {
            $('.dup_name').html('Shift Name: ' + shift_name + ' Already Exists');
            $('.dup_name').show();
            $(".shift_name").val('');
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
    $(".shift_name").keyup(function () {
      $('.dup_name').hide();
    });

    // save function
    var dup_chk = true;
    /** Shiftiming Save Start  **/
    $(document).on('click', '.save_form', function (e) {
      e.preventDefault();
      var data;
      duplicate_validate();
      data = $("#shift_form").serialize();
      var form = $('#shift_form');
      form.parsley().validate();
      if (form.parsley().isValid() && dup_chk) {
        var $btn = $(this);
        $btn.prop('disabled', true);
        $.post('shifttimingssave', data, function (data) {
          if (data == 1) {

            showCustomAlert('Saved Successfully', 'success');
            window.location.reload();
          }
          else if (data == 2) {

            showCustomAlert('Update Successfully', 'success');
            window.location.reload();

          }
        });
      }

    });


    // delete	

    $(document).on('click', '.delete-btn', function () {
      deleteId = $(this).data('id');
      $('#globalDeleteModal').modal('show');
    });

    $('#globalConfirmDeleteBtn').on('click', function () {
      if (deleteId) {
        $.ajax({
          url: "{{ url('timingdelete') }}/" + deleteId,
          type: "GET",
          success: function (response) {
            $('#globalDeleteModal').modal('hide');
            showCustomAlert('Deleted', 'success');
            $('#UplTbl').DataTable().ajax.reload();

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

      const id = $(this).data('id');
      const name = $(this).data('name');
      const stime = $(this).data('stime');
      const etime = $(this).data('etime');

      // Fill form fields
      $('input[name="edit_id"]').val(id);
      $('input[name="shift_name"]').val(name);
      $('input[name="start_time"]').val(stime);
      $('input[name="end_time"]').val(etime);


    });


  </script>

@endpush