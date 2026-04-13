@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Transaction Type</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white fw-semibold"></div>
    <div class="card-body">

      <form method="POST" action="" id="saveformdata" data-parsley-validate>
        <?php $data = \Session::get('data');
  if (isset($data[$pageMethod]['save'])) { ?>

        <input type="hidden" value="" name="savestatus" id="savestatus" />
        <div class="card-body card-block">
          <input type="hidden" name="edit_id" value="" id="edit_id" />
          {{ csrf_field()}}
          <div class="row">
            <div class="col-md-6">

              <div class="row mb-3">
                <label for="inputIsValid" class="col-form-label col-md-5">Transaction Type code<span
                    style="color: red;">&#42;</span></label>
                <div class="col-md-7">
                  <input type="text" id="transaction_type_code" name="transaction_type_code"
                    class="form-control transaction_type_code" tabindex="1" required>

                </div>
              </div>


              <div class="row mb-3">
                <label for="inputIsValid" class="col-form-label col-md-5">Transaction Type Name<span
                    style="color: red; ">&#42;</span></label>
                <div class="col-md-7">
                  <input type="text" id="transaction_type_name" name="transaction_type_name"
                    class="form-control 	transaction_type_name" tabindex="2" required>
                  <span class="btn btn-danger dup_name" style="display:none;"></span>
                </div>
              </div>


              <div class="row mb-3">
                <label for="inputIsValid" class="col-form-label col-md-5">Transaction Source<span
                    style="color: red; ">&#42;</span></label>
                <div class="col-md-7">
                  <select id="transaction_source_id" name="transaction_source_id" class="select2 transaction_source_id"
                    required style="width:100%;">
                    {!!$transaction_source_id!!}
                  </select>
                  <span class="btn btn-danger dup_name1" style="display:none;"></span>
                </div>
              </div>
              <div class="row mb-3">
                <label for="active" class="col-form-label col-md-5">Created By</label>
                <div class="col-md-7" style="pointer-events:none;">
                  <select name='created_by' rows='5' class='select2 created_by' id="created_by">
                    {!! $created_by !!}
                  </select>
                </div>
              </div>

            </div>

            <div class="col-md-6">
              <div class="row mb-3">
                <label for="inputIsValid" class="col-form-label col-md-5">Transaction Action<span
                    style="color: red; ">&#42;</span></label>
                <div class="col-md-7">
                  <select id="transaction_action_id" name="transaction_action_id" class="select2 transaction_action_id"
                    required>
                    {!!$transaction_action_id!!}
                  </select>
                  <span class="btn btn-danger dup_name2" style="display:none;"></span>
                </div>
              </div>



              <div class="row mb-3">
                <label for="inputIsValid" class="col-form-label col-md-5">Description</label>
                <div class="col-md-7">
                  <input type="text" id="description" name="description" class="form-control 	description" tabindex="3">
                </div>
              </div>


              <div class="row mb-3">
                <label for="inputIsValid" class="col-form-label col-md-5">Active</label>
                <div class="col-md-7">
                  <select name="active" class="form-control active select2">
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                  </select>
                </div>
              </div>


            </div>
          </div>

          <div class="row text-center mt-1">
            <div class="col-md-12">
              <button type="button" id="save" class="btn btn-success px-4 saveform" value="SAVE">Save</button>
            </div>

          </div>

        </div>
        <?php } ?>
      </form>


    </div>
  </div>



  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4 table-responsive">
      <table id="TransTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Type Code</th>
            <th>Type Name</th>
            <th>Source</th>
            <th>Trans Action</th>
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

    // data table funcrion	
    $(document).ready(function () {
      var table = $('#TransTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('getTransactionData') }}",
        columns: [
          { data: 'transaction_type_code', name: 'transaction_type_code' },
          { data: 'transaction_type_name', name: 'transaction_type_name' },
          { data: 'transaction_source_id', name: 'transaction_source_id' },
          { data: 'transaction_action_id', name: 'transaction_action_id' },
          { data: 'active', name: 'active' },
          // { data: 'description', name: 'description' },
          { data: 'first_name', name: 'first_name' },
          {
            data: 'transaction_type_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',
            width: '140px',
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
                <button class="btn btn-sm btn-info me-1 edit-btn" 
                  data-id="${row.transaction_type_id}" 
                  data-code="${row.transaction_type_code}"
                  data-name="${row.transaction_type_name}"
                  data-source="${row.transaction_source_id}"
                  data-action="${row.transaction_action_id}"
                  data-active="${row.active}"
                  data-description="${row.description}">
                  <i class="bi bi-pencil"></i>
                </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
        <button class="btn btn-sm btn-danger delete-btn" data-id="${row.transaction_type_id}">
          <i class="bi bi-trash"></i>
        </button>`;
              }
              return buttons;
            }
          }
        ]
      });

      // Individual column search
      $('#TransTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });


    /*Duplicate Check Function*/
    var dup_chk = true;
    function duplicate_validate() {
      var type_name = $(".transaction_type_name").val();
      var source_id = $(".transaction_source_id").val();
      var action_id = $(".transaction_action_id").val();
      var edit_id = $("#edit_id").val();
      var url = "{{URL::to('transactioncheckname')}}/";
      $.ajax({
        cache: false,
        url: url, /*this is your uri*/
        type: 'GET',
        dataType: 'json',
        async: false,
        data: { transaction_type_name: type_name, transaction_source_id: source_id, transaction_action_id: action_id, edit_id: edit_id },
        success: function (response) {

          $.each(response, function (index, value) {
            if (value == 1) {
              $('.dup_name').html('Tranaction Type Name:' + type_name + ' Already Exists');
              $('.dup_name').show();
              $(".transaction_type_name").val('');

              dup_chk = false;
            } else if (value == 2) {
              $('.dup_name1').html('Tranaction Source:' + source_id + ' Already Exists');
              $('.dup_name1').show();
              $(".transaction_source_id").val('').change();
              dup_chk = false;
            } else if (value == 3) {
              $('.dup_name2').html('Tranaction Action:' + action_id + ' Already Exists');
              $('.dup_name2').show();
              $(".transaction_action_id").val('').change();
              dup_chk = false;
            } else if (value == 0) {
              var html = "";
              $('.dup_name').hide();
              $('.dup_name1').hide();
              $('.dup_name2').hide();
              dup_chk = true;
            }
          });

        },
        error: function (xhr, resp, text) {
          console.log(xhr, resp, text);
        }
      });
    }


    $(document).ready(function () {

      $(document).on('click', '.saveform', function () {

        var form = $("#saveformdata");
        form.parsley();

        duplicate_validate();
        $('input[name=_token]').val("{{csrf_token()}}");
        var form_data = form.serialize();

        form.parsley().validate();
        var url = "{{URL::to('mtltransactiontypessave')}}";
        console.log(form_data);
        if (dup_chk == true) {
          var $btn = $(this);            
          $btn.prop('disabled', true);
          $.post(url, form_data, function (data1) {

            var status = data1.status;
            var msg = data1.message;
            showCustomAlert(msg,"success");
            form[0].reset();
            $('.select2').val('').trigger('change');
            // Reload DataTable
            window.location.reload();

          });
        }
        return false;
      });

      /*End*/
      $(".transaction_source_id").change(function () {
        $('.dup_name1').hide();
      });



      $(".transaction_action_id").change(function () {
        $('.dup_name2').hide();
      });

      $(".transaction_type_name").keyup(function () {
        $('.dup_name').hide();
      });


      $('.transaction_type_code').on('keyup', function () {
        this.value = this.value.toUpperCase();
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
            url: "{{ url('transactiondelete') }}/" + deleteId,
            type: "GET",
            success: function (data) {
              if (data == '0') {
                $('#globalDeleteModal').modal('hide');
                showCustomAlert('Deleted successfully!', 'success');
                $('#TransTbl').DataTable().ajax.reload();
              }
              if (data == '1') {
                $('#globalDeleteModal').modal('hide');
                showCustomAlert("You Cant't delete , Tranaction Type Used in SomeWhere!!!", "error");
                $('#TransTbl').DataTable().ajax.reload();
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

      //  Edit 
      $(document).on('click', '.edit-btn', function () {
        var id = $(this).data('id');
        var transaction_type_code = $(this).data('code');
        var transaction_type_name = $(this).data('name');
        var transaction_source_id = $(this).data('source');
        var transaction_action_id = $(this).data('action');
        var active = $(this).data('active');
        var description = $(this).data('description');
        var url = "{{ url('transactionedit') }}/" + id;

        $.get(url, function (data) {
          var response = $.trim(data);
          if (response == 0) {
            // Populate form fields
            $('#transaction_type_code').val(transaction_type_code);
            $('#transaction_type_name').val(transaction_type_name);
            $('#transaction_source_id').val(transaction_source_id).trigger('change');
            $('#transaction_action_id').val(transaction_action_id).trigger('change');
            $('#description').val(description);
            $('.active').val(active).trigger('change');
            $('#edit_id').val(id);
          } else {
            // Reset form
            $('#transaction_type_code').val('');
            $('#transaction_type_name').val('');
            $('#transaction_source_id').val('').trigger('change');
            $('#transaction_action_id').val('').trigger('change');
            $('#description').val('');
            $('.active').val('Yes').trigger('change');
            $('#edit_id').val('');
            showCustomAlert("You Can't edit this Transaction Type, Already used!!!", 'error');
          }
        });
      });

    });

  </script>

@endpush