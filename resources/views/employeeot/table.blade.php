@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Employee EP Generate</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white fw-semibold"></div>
    <div class="card-body">

      <div class="container my-4">
        <div class="row align-items-center g-3">
          <div class="col-md-2"></div>
          <div class="col-md-3">
            <label for="month" class="form-label mb-0">Month:</label>
            <select id="month" class="form-select select2 month">
              <!-- Options will be populated dynamically -->
            </select>
          </div>

          <div class="col-md-3">
            <label for="year" class="form-label mb-0">Year:</label>
            <select id="year" class="form-select select2 year">
              <!-- Options will be populated dynamically -->
            </select>
          </div>

          <div class="col-md-2 mt-4">
            <button type="button" class="btn btn-primary w-100 px-4 generated"><i class="bi bi-check2-circle"></i>
              Generate</button>
          </div>

        </div>
      </div>
    </div>
  </div>



  <div class="container my-3">
    <div class="row g-3">

      <div class="col-md-2">
        <button type="button" class="btn btn-success w-100 validated_selected" id="validated_selected"><i
            class="bi bi-check-all"></i>
          Validate Selected
        </button>
      </div>

      <div class="col-md-2">
        <button type="button" class="btn btn-secondary w-100 text-white" data-action="mailoffer" id="ot_email"><i
            class="bi bi-envelope"></i>
          Email
        </button>
      </div>

    </div>
  </div>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="OtTbl" class="table table-bordered table-striped w-100" style="width: 200% !important;">
          <thead>
            <tr class="table-warning">
              <th><input type="checkbox" id="select_all"></th>
              <th style="display:none;">Emp Id</th>
              <th>Emp Number</th>
              <th class="freeze">Employee Name</th>
              <th>Department</th>
              <th>Desigination</th>
              <th>Check In</th>
              <th>Check Out</th>
              <th>Mrng EP</th>
              <th>Evg EP</th>
              <th>Night EP</th>
              <th>Sunday/Holiday EP</th>
              <th>Attended EP</th>
              <th>Overall EP Hrs</th>
              <th>EP Amount</th>
              <th>Food Amount</th>
              <th>Total Amount</th>
              <th>Status</th>

            </tr>
            <tr class="table-info">

              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th style="display:none;"><input type="text" class="form-control form-control-sm column-search"
                  placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th class="freeze"><input type="text" class="form-control form-control-sm column-search"
                  placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
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

          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Edit Employee EP Modal -->
  <div class="modal fade" id="creditModal" tabindex="-1" aria-labelledby="creditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content shadow-lg rounded-4">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="creditModalLabel">Edit Employee EP</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <form id="emp_update_form" method="post" data-parsley-validate>
          <div class="modal-body">
            <div class="row mb-3">
              <div class="col-md-6">
                <h6>Employee Number: <strong class="employee_id"></strong></h6>
              </div>
              <div class="col-md-6">
                <h6>Employee Name: <strong class="employee_name"></strong></h6>
              </div>
            </div>

            <input type="hidden" class="Id" value="">

            <div class="mb-3 row">
              <label class="col-md-4 col-form-label">EP Amount</label>
              <div class="col-md-6">
                <input type="text" class="form-control ep_amount_edit" id="ep_amount_edit" name="ep_amount_edit">
              </div>
            </div>

            <div class="mb-3 row">
              <label class="col-md-4 col-form-label">Food Amount</label>
              <div class="col-md-6">
                <input type="text" class="form-control food_amount_ep" id="food_amount_ep" name="food_amount_ep">
              </div>
            </div>

            <div class="row">
              <div class="col emppopup">
                <!-- Optional content -->
              </div>
            </div>
          </div>

          <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-success px-4" id="update_val">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Approval Mail Modal -->
  <div class="modal fade" id="mailModal" tabindex="-1" aria-labelledby="mailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content shadow-lg rounded-4">
        <div class="modal-header bg-info text-white">
          <h5 class="modal-title" id="mailModalLabel">Approval Mail</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <form id="mail_update_form" method="post" data-parsley-validate>
          <div class="modal-body">
            <div class="mb-3 row">
              <label class="col-md-4 col-form-label">EP Month For</label>
              <div class="col-md-6">
                <input type="text" class="form-control ep_month" id="ep_month" name="ep_month">
              </div>
            </div>

            <div class="row">
              <div class="col emppopup">
                <!-- Optional content -->
              </div>
            </div>
          </div>

          <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-primary px-4" id="send_app_mail">Send</button>
          </div>
        </form>
      </div>
    </div>
  </div>


@endsection
@push('scripts')

  <script>

    /** current year selected and dropdown load **/
    var min = 2024,
      max = new Date().getFullYear(),
      select = document.getElementById('year');

    for (var i = max; i >= min; i--) {
      var opt = document.createElement('option');
      opt.value = i;
      opt.innerHTML = i;
      select.appendChild(opt);

    }


    // month		

    var url = "{{URL::to('jcomboformlogin?table=month:id:description') }}&order_by=id";

    $.ajax({
      url: url,
      type: 'GET',
      success: function (data) {
        // Parse JSON string if needed
        if (typeof data === "string") {
          try {
            data = JSON.parse(data);
          } catch (e) {
            console.error("Invalid JSON response:", data);
            return;
          }
        }

        $('.month').html('<option value="">-- Select Month --</option>');

        $.each(data, function (i, item) {
          let selected = item.val == "{{ $row->month ?? '' }}" ? 'selected' : '';
          $('.month').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
        });

        $('.month').trigger('change.select2');
      }

    });


    // Generate click function start
    $(document).on('click', '.generated', function () {


      var month = $('#month').select2('val');
      var year = $('#year').select2('val');


      if (month && year) {
        var $btn = $(this);
        $btn.prop('disabled', true);
        var url = "{{ URL::to('generateot') }}";

        $.ajax({
          url: url,
          type: 'GET',
          data: {
            month: month,
            year: year
          },
          success: function (response) {
            if (response.success) {
              showCustomAlert('OT generated successfully.', 'success');
            } else {
              showCustomAlert('Failed to generate OT. Please try again.', 'error');
            }

            // Reload the page after 1 second
            setTimeout(function () {
              location.reload();
            }, 1000);
          },
          error: function () {
            showCustomAlert('An error occurred while generating the OT.', 'error');
          }
        });
      } else {
        showCustomAlert('Please Select Month', 'info');
      }
    });


    //table data		

    var table = $('#OtTbl').DataTable({
      processing: true,
      serverSide: true,
      dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
      ajax: "{{ url('employeeotgrid') }}",
      lengthMenu: [[10, 25, 50, 100, 500, 1000], [10, 25, 50, 100, 500, 1000]],
      columns: [
        {
          data: 'id',
          render: function (data, type, row) {
            return '<input type="checkbox" class="row_checkbox me-2" value="' + data + '">' +
              '<button class="btn btn-sm btn-primary ms-2 emp_update" data-id="' + data + '">' +
              '<i class="bi bi-pencil"></i>' +
              '</button>';
          },

          orderable: false,
          searchable: false
        },

        { data: 'emp_id', name: 'emp_id', visible: false },
        { data: 'emp_number', name: 'emp_number' },
        { class: 'freeze', data: 'emp_name', name: 'emp_name' },
        { data: 'sub_department_name', name: 'sub_department_name' },
        { data: 'job_title_name', name: 'job_title_name' },
        { data: 'check_in', name: 'check_in' },
        { data: 'check_out', name: 'check_out' },
        { data: 'mrng_ot', name: 'mrng_ot' },
        { data: 'evng_ot', name: 'evng_ot' },
        { data: 'night_ot', name: 'night_ot' },
        { data: 'sunday_ot', name: 'sunday_ot' },
        { data: 'ot_type', name: 'ot_type' },
        { data: 'overall_ot_hrs', name: 'overall_ot_hrs' },
        { data: 'ot_amount', name: 'ot_amount' },
        { data: 'food_amount', name: 'food_amount' },
        { data: 'total_amt', name: 'total_amt' },
        { data: 'status', name: 'status' },

      ],

      buttons: [
        {
          extend: 'colvis',
          text: '<i class="bi bi-layout-three-columns"></i> Columns',
          className: 'btn bg-primary btn-sm',
          postfixButtons: ['colvisRestore'] // adds "Restore Columns" button
        },
        { extend: 'excelHtml5', title: "Employee EP", exportOptions: { columns: ':visible' } }
      ]
    });

    // Select all checkboxes
    $('#select_all').on('click', function () {
      $('.row_checkbox').prop('checked', this.checked);
    });


    // send mail 	
    $(document).on('click', '#send_app_mail', function () {

      var url = "{{URL::to('otmailsend')}}";
      var formdata = $('#mail_update_form').serialize();

      $.post(url, formdata, function (data) {
        var status = data.status;
        var msg = data.message;

        showCustomAlert(msg, status);
        var red_url = "{{ URL::to('employeeotcal') }}";
        window.location.href = red_url;

      });


    });

    // validate	
    $(document).on('click', '#validated_selected', function () {
      var table = $('#OtTbl').DataTable();

      // Collect checked row IDs
      var selectedIds = [];
      $('.row_checkbox:checked').each(function () {
        selectedIds.push($(this).val());
      });

      if (selectedIds.length > 0) {
        var url = "{{ URL('validateot') }}?row_id=" + selectedIds.join(',');

        $.get(url, function (data) {
          if (data == 1) {
            showCustomAlert('EP Validated Successfully', 'success');
            table.ajax.reload(); // Reload DataTable via AJAX
          }
        });
      } else {
        showCustomAlert('Please select a row', 'warning');
      }
    });


    // edit function

    $(document).on('click', '.emp_update', function () {

      var selectedId = $(this).data('id');

      // Get DataTable instance
      var table = $('#OtTbl').DataTable();

      // Find row data by ID
      var rowData = table.rows().data().toArray().find(row => row.id == selectedId);

      if (rowData) {
        // Populate modal fields
        $('.employee_id').text(rowData.emp_number);
        $('.employee_name').text(rowData.emp_name);
        $('.ep_amount_edit').val(rowData.ot_amount);
        $('.food_amount_ep').val(rowData.food_amount);
        $('.Id').val(rowData.id);

        // Show the modal
        $('#creditModal').modal('show');
      } else {
        showCustomAlert("Could not fetch row data.", 'warning');
      }
    });

    // updated function
    $(document).on('click', '#update_val', function () {
      var Id = $('.Id').val();

      var url = "{{URL::to('empep_update')}}/" + Id;
      var formdata = $('#emp_update_form').serialize();

      $.post(url, formdata, function (data) {
        var status = data.status;
        var msg = data.message;

        showCustomAlert(msg, status);
        var red_url = "{{ URL::to('employeeotcal') }}";
        window.location.href = red_url;

      });

    });

    // mail purpose

    $('#ot_email').on('click', function () {
      $('#mailModal').modal('show');
    });


  </script>

@endpush