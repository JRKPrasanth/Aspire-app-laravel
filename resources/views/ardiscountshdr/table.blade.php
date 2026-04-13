@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Discounts</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white rounded-top-4">

    </div>
    <div class="card-body">
      <form id="dis_form" action="" data-parsley-validate>
        <?php $data = \Session::get('data');
  if (isset($data[$pageMethod]['save'])) { ?>
        {{ csrf_field()}}

        <div class="row g-4">
          <!-- Left Column -->
          <div class="col-md-4">
            <!-- Discount Name -->
            <div class="mb-3">
              <label for="discount_name" class="form-label fw-semibold">
                <span class="text-danger">*</span> Discount Name
              </label>
              <input type="hidden" class="form-control ar_discount_hdr_id" id="ar_discount_hdr_id"
                name="ar_discount_hdr_id" value="">
              <input type="text" id="discount_name" name="discount_name" class="form-control discount_name" required>
              <div class="form-text text-danger d-none dup_name"></div>
            </div>

            <!-- Start Date -->
            <div class="mb-3">
              <label for="start_date" class="form-label fw-semibold">
                <span class="text-danger">*</span> Start Date
              </label>
              <div class="input-group">
                <input type="text" id="start_date" name="start_date" class="form-control discount_date"
                  value="{{ $start_date }}" required>
              </div>
            </div>

            <!-- Remarks -->
            <div class="mb-3">
              <label for="remarks" class="form-label fw-semibold">Remarks</label>
              <input type="text" id="remarks" name="remarks" class="form-control remarks" placeholder="Enter remarks">
            </div>
          </div>

          <!-- Middle Column -->
          <div class="col-md-4">
            <!-- Discount Percentage -->
            <div class="mb-3">
              <label for="default_discount_amount" class="form-label fw-semibold">
                <span class="text-danger">*</span> Discount %
              </label>
              <div class="input-group">
                <input type="text" id="default_discount_amount" name="default_discount_amount"
                  class="form-control default_discount_amount" required>

              </div>
            </div>

            <!-- End Date -->
            <div class="mb-3">
              <label for="end_date" class="form-label fw-semibold">
                <span class="text-danger">*</span> End Date
              </label>
              <div class="input-group">
                <input type="text" id="end_date" name="end_date" class="form-control discount_date" required>
              </div>
            </div>
          </div>

          <!-- Right Column -->
          <div class="col-md-4">
            <!-- Created By -->
            <div class="mb-3 none">
              <label for="created_by" class="form-label fw-semibold">Created By</label>
              <select name="created_by" id="created_by" class="form-select select2">
                {!! $created_by !!}
              </select>
            </div>

            <!-- Active -->
            <div class="mb-3">
              <label for="active" class="form-label fw-semibold">Active</label>
              <select name="active" id="active" class="form-select select2">
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>

          </div>
        </div>

        <div class="text-center mt-4">
          <button type="button" id="save" class="btn btn-success px-4 saveform">
            <i class="bi bi-check-circle me-2"></i> Save
          </button>
        </div>

        <?php } ?>
      </form>
    </div>
  </div>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <table id="InvTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Discount Name</th>
            <th>Discount Percentage</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Created By</th>
            <th>Remarks</th>
            <th>Active</th>
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
      var table = $('#InvTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "getDiscountsData",
        columns: [
          { data: 'discount_name', name: 'discount_name' },
          { data: 'default_discount_amount', name: 'default_discount_amount' },
          { data: 'start_date', name: 'start_date' },
          { data: 'end_date', name: 'end_date' },
          { data: 'username', name: 'username' },
          { data: 'remarks', name: 'remarks' },
          { data: 'active', name: 'active' },

          {
            data: 'ar_discount_hdr_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `<button class="btn btn-sm btn-info me-1 edit-btn" 
                      data-id="${row.ar_discount_hdr_id}" 
                      data-code="${row.default_discount_amount}" 
                      data-name="${row.discount_name}" 
                      data-sdate="${row.start_date}" 
                      data-edate="${row.end_date}" 
                      data-active="${row.active}"
              data-remarks="${row.remarks}">
                      <i class="bi bi-pencil"></i>
                    </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
                    <button class="btn btn-sm btn-danger delete-btn" data-id="${row.ar_discount_hdr_id}">
                      <i class="bi bi-trash"></i>
                    </button>`;
              }
              return buttons;
            }
          }
        ]
      });

      // Individual column search
      $('#InvTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });


    // delete function
    let deleteId = null;

    $(document).on('click', '.delete-btn', function () {
      deleteId = $(this).data('id');
      const type = $(this).data('type');
      $('#globalDeleteModal').modal('show');
    });

    $('#globalConfirmDeleteBtn').on('click', function () {
      if (deleteId) {
        $.ajax({
          url: "{{ url('ardiscountshdrdelete') }}/" + deleteId,
          type: "GET",
          success: function (data) {
            if (data == '0') {
              $('#globalDeleteModal').modal('hide');
              showCustomAlert('Deleted successfully!', 'success');
              $('#InvTbl').DataTable().ajax.reload();
            }
            if (data == '1') {
              $('#globalDeleteModal').modal('hide');
              showCustomAlert("You Can't delete , Used in SomeWhere.", 'error');
              $('#InvTbl').DataTable().ajax.reload();
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



    var dup_chk = true;
    function duplicate_validate() {
      var discount_name = $(".discount_name").val();
      var default_discount_amount = $(".default_discount_amount").val();
      var edit_id = $("#ar_discount_hdr_id").val();

      $.ajax({
        cache: false,
        url: 'discountchkname',
        type: 'GET',
        dataType: 'json',
        async: false,
        data: { discount_name: discount_name, edit_id: edit_id, default_discount_amount: default_discount_amount },
        success: function (response) {

          if (response == 1) {
            $('.dup_name').html('Discount Name:' + discount_name + ' and Discount Amount' + default_discount_amount + ' Already Exists');
            $('.dup_name').show();
            $(".discount_name").val('');
            $(".default_discount_amount").val('');
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



    $(document).ready(function () {


      $('.discount_name').keyup(function () {
        this.value = this.value.toUpperCase();
      })


      $(document).on('keypress', '.default_discount_amount', function (ev) {
        var regex = new RegExp("^[0-9.]+$");
        var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
        if (regex.test(str)) {
          return true;
        }
        ev.preventDefault();
        return false;
      });

      $('.default_discount_amount').bind("cut copy paste", function (e) {
        e.preventDefault();
      });


      $(document).on('click', '.saveform', function () {
        var btnval = $(this).val();

        $('#save_status').val(btnval);

        var url = "{{ url('ardiscountshdrsave') }}";
        var red_url = "{{ url('ardiscountshdr') }}";
        var form = $('#dis_form');
        form.parsley().validate();
        duplicate_validate();
        if (form.parsley().isValid()) {
          if (dup_chk == true) {
            var $btn = $(this);
            $btn.prop('disabled', true);
            var formdata = $('#dis_form').serialize();
            $.post(url, formdata, function (data) {

              var status = data.status;
              var msg = data.message;
              var id = data.id;

              showCustomAlert(msg, status);
              setTimeout(function () {
                window.location.href = red_url;
              }, 1500);

            });
          }
          return false;
        }

      });



      $(document).on('click', '.create', function () {
        //var inquirytype = $(this).val();
        var url = "{{ url('ardiscountshdrcreate') }}/0";
        var red_url = "{{ url('ardiscountshdr') }}";
        window.location.replace(url);
      });






      $('#viewdata').click(function () {

        var gr = $('#grid1').jqGrid('getGridParam', 'selrow');
        var cellValue = $("#grid1").jqGrid('getCell', gr, 'ar_discount_hdr_id');  //alert(cellValue);

        if (gr) {
          var url = "ardiscountshdrview";
          var viewurl = url + '/' + cellValue + '/view';
          window.location.replace('ardiscountshdrview/' + cellValue);
        }
        else {
          notyMsg("info", "Please Select Row");
        }
      });



      // edit function
      $(document).on('click', '.edit-btn', function () {
        const id = $(this).data('id');
        const code = $(this).data('code');
        const name = $(this).data('name');
        const remarks = $(this).data('remarks');
        const active = $(this).data('active');
        const sdate = $(this).data('sdate');
        const edate = $(this).data('edate');

        var url = "{{ URL::to('editdata') }}/" + id;

        $.get(url, function (data) {
          var data = $.trim(data);
          if (data == 0) {

            // Fill form fields
            $('input[name="edit_id"]').val(id);
            $('input[name="discount_name"]').val(code);
            $('input[name="start_date"]').val(sdate);
            $('input[name="end_date"]').val(edate);
            $('input[name="default_discount_amount"]').val(name);
            $('input[name="remarks"]').val(remarks);
            $('select[name="active"]').val(active).trigger('change');

          } else {

            showCustomAlert("You Can't be Edit this  Discount, Already used", "warning");

          }
        });

      });


    });

    $(document).on("focus", ".discount_date", function () {

      $(this).datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        minDate: 0,
        maxDate: +1000,
        showAnim: "slideDown",
        yearRange: "-25:+0",

      });
    });

  </script>


@endpush