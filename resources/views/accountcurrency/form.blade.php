@extends('layouts.header')
@section('content')
<h3 class="text-danger">Account Currency</h3>
@include('layouts.breadcrumb')


<div class="card shadow-lg rounded-4 border-0">

  <div class="card-body card-block">
    <form id="acc_currency_save" action="" data-parsley-validate>
      <?php $data = \Session::get('data');
      if (isset($data[$pageMethod]['save'])) { ?>

        <input type="hidden" value="" name="savestatus" id="savestatus" />

        <input type="hidden" name="edit_id" value="{{$row->account_currency_id}}" id="edit_id"
          class="account_currency_id" /> {{ csrf_field()}}
        <!------------------------------------- Body content start here ---------------------------->
        <div class="row mt-2">
          <div class="col-md-12">



            <div class="row">
              <div class="col-md-4">
                <div class="form-group row">
                  <label for="inputIsValid" class="form-control-label col-md-5">
                    <span style="color:red;">*</span> Currency Name</label>
                  <div class="col-md-7">
                    <input type="text" name="currency_code" id="currency_code" value="{{$row->currency_code}}"
                      class="form-control currency_code" required>
                    <span class="btn btn-danger dup_name" style="display:none;"></span>
                  </div>
                </div>


              </div>
              <div class="col-md-4">
                <div class="form-group row">
                  <label for="inputIsValid" class="form-control-label col-md-5">Active</label>
                  <div class="col-md-7">
                    <select name="active" class="form-control active select2">
                      <option value="Yes" <?php if ($row->active == 'Yes') {
                        echo "selected";
                      } ?>>Yes</option>
                      <option value="No" <?php if ($row->active == 'No') {
                        echo "selected";
                      } ?>>No</option>
                    </select>
                  </div>
                </div>


              </div>
              <div class="col-md-4 none">
                <div class="form-group row">
                  <label for="created_by" class="form-control-label col-md-5">Created By</label>
                  <div class="col-md-7">
                    <select name='created_by' rows='5' class='form-control created_by select2' required>
                      {!! $created_by !!}
                    </select>
                  </div>
                </div>

              </div>
            </div>
            <div class="row mt-4">
              <div class="col-md-12 text-center">
                <button type="button" class="btn saveform btn-success px-4">Save</button>
              </div>
            </div>


          </div>
        </div>

      <?php } ?>

      <!------------------------------------------------------------------------------------------>

    </form>

  </div>
</div>


<!-- DataTable Card -->
<div class="card shadow-lg rounded-4 border-0 mt-4">
  <div class="container mt-4">
    <table id="AccountsTbl" class="table table-bordered table-striped w-100">
      <thead>
        <tr class="table-warning">
          <th>Currency Name</th>
          <th>Active</th>
          <th>Created By</th>
          <th>Actions</th>
        </tr>
        <tr class="table-danger">
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


  // Init DataTable
  var table = $('#AccountsTbl').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ URL::to('getAccountcurrencyData') }}",
    columns: [
      { data: 'currency_code', name: 'currency_code' },
      { data: 'active', name: 'active' },
      { data: 'first_name', name: 'tb_users.first_name' },
      {
        data: 'account_currency_id',
        name: 'actions',
        orderable: false,
        searchable: false,
        className: 'text-center',
        width: '140px',
        render: function (data, type, row) {
          let buttons = '';
          if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
            buttons += `
        <button class="btn btn-sm btn-primary edit-btn" 
        data-id="${row.account_currency_id}" 
        data-name="${row.currency_code}" 
        data-active="${row.active}">
          <i class="bi bi-pencil"></i>
        </button>`;
          }

          if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
            buttons += `
        <button class="btn btn-sm btn-danger delete-btn" data-id="${row.account_currency_id}">
          <i class="bi bi-trash"></i>
        </button>`;
          }
          return buttons;
        }
      }
    ]
  });

  // Individual column search
  $('#AccountsTbl thead').on('keyup change', ".column-search", function () {
    var colIndex = $(this).parent().index();
    table.column(colIndex).search(this.value).draw();
  });


  var dup_chk = true;
  function duplicate_validate() {
    var currency_code = $(".currency_code").val();
    var edit_id = $("#edit_id").val();
    $.ajax({
      cache: false,
      url: "{{ URL::to('accountcurrencycheckname/') }}",
      type: 'GET',
      dataType: 'json',
      async: false,
      data: { currency_code: currency_code, edit_id: edit_id },
      success: function (response) {
        if (response == 1) {
          $('.dup_name').html('Currency Code:' + currency_code + ' Already Exists');
          $('.dup_name').show();
          $('.currency_code').val('');
          dup_chk = false;
        } else if (response == 0) {
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


  // Save function
  $(document).on('click', '.saveform', function () {
    var form = $("#acc_currency_save");
    form.parsley().validate();

    if (form.parsley().isValid()) {
      $.ajax({
        url: "{{ URL::to('accountcurrencysave') }}",
        type: "POST",
        data: form.serialize(),
        success: function (data) {
          showCustomAlert(data.message, 'success');
          form[0].reset();
          $('.select2').val('').trigger('change');
          $('#AccountsTbl').DataTable().ajax.reload();
        },
        error: function (xhr) {
          showCustomAlert('Save failed. Try again.', 'error');
        }
      });
    }
  });


  $('.start_date,.end_date').datepicker({ format: 'yyyy-mm-dd', autoclose: true })
  /*Karthigaa Purpose for Upper Case */
  $('.currency_code').on('keyup', function () {
    this.value = this.value.toUpperCase();
  });


  // Edit button
  $(document).on('click', '.edit-btn', function () {
    const btn = $(this);
    $('#edit_id').val(btn.data('id'));
    $('#currency_code').val(btn.data('name'));
    $('select[name="active"]').val(btn.data('active')).trigger('change');
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
        url: "{{ url('accountcurrencydelete') }}/" + deleteId,
        type: "GET",
        success: function (data) {
          if (data == '0') {
            $('#globalDeleteModal').modal('hide');
            showCustomAlert('Deleted successfully!', 'success');
            $('#AccountsTbl').DataTable().ajax.reload();
          }
          if (data == '1') {
            $('#globalDeleteModal').modal('hide');
            showCustomAlert("You Can't delete , Subinventory Used in SomeWhere.", 'error');
            $('#AccountsTbl').DataTable().ajax.reload();
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

</script>

@endpush