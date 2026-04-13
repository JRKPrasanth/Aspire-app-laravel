@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Leave Reminder</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white fw-semibold"></div>
    <div class="card-body">
      <div class="row mt-4 align-items-end">
        <!-- Date Picker -->
        <div class="col-md-6 mb-3">
          <div class="form-group row">
            <label for="start_date" class="col-sm-4 col-form-label fw-semibold">Date</label>
            <div class="col-sm-8">
              <div class="input-group">
                <input type="text" class="form-control start_date1" id="start_date" name="start_date"
                  placeholder="YYYY-MM-DD" autocomplete="off" required>

              </div>
            </div>
          </div>
        </div>

        <!-- Search Button -->
        <div class="col-md-2 mb-3 text-center">
          <button type="button" class="btn btn-primary w-100 report_search" id="search">
            <i class="bi bi-search"></i> Search
          </button>
        </div>

        <!-- Email Button -->
        <div class="col-md-2 mb-3 text-center">
          <button type="button" class="btn btn-success w-100" id="email_leave" data-action="mailoffer">
            <i class="bi bi-envelope"></i> Email
          </button>
        </div>
      </div>
    </div>
  </div>


  <!-- Leave Reminder Modal -->

  <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content shadow-lg border-0">

        <!-- Modal Header -->
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="contactModalLabel">Leave Reminder</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal Body -->
        <form method="POST" id="enquirymail" enctype="multipart/form-data" class="needs-validation" novalidate>
          <div class="modal-body">

            <!-- Employee Name -->
            <div class="mb-3">
              <label for="employee_name" class="form-label fw-semibold">Employee Name</label>
              <input type="text" name="employee_name" class="form-control employee_name" readonly>
            </div>

            <!-- To Email -->
            <div class="mb-3">
              <label for="employee_mail" class="form-label fw-semibold">To</label>
              <input type="email" name="employee_mail" class="form-control employee_mail" required>
            </div>

            <!-- Hidden Inputs -->
            <input type="hidden" name="emp_id" class="emp_id">
            <input type="hidden" name="status" class="status">

            <!-- CC Email -->
            <div class="mb-3">
              <label for="cc" class="form-label fw-semibold">CC</label>
              <input type="text" name="cc[]" class="form-control cc" placeholder="Optional">
            </div>

            <textarea name="msg" class="form-control msg d-none"></textarea>
            <textarea name="msg1" class="form-control msg1 d-none"></textarea>
            <textarea name="msg2" class="form-control msg2 d-none"></textarea>
            <input type="hidden" name="hdr_id" class="msg hdr_id">

            <!-- PDF Preview -->
            <div class="mb-3">

              <iframe id="iframepdf" width="100%" height="400" class="border rounded" style="display: none;"></iframe>
            </div>

          </div>

          <!-- Modal Footer -->
          <div class="modal-footer bg-light">
            <button type="button" class="btn btn-success sendmail" id="sentmail_id">
              <i class="bi bi-send me-1"></i> Send
            </button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i class="bi bi-x-circle me-1"></i> Close
            </button>

          </div>

        </form>
      </div>
    </div>
  </div>


  <!-- model end -->


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="leaveTbl" class="table table-bordered table-striped w-100">
          <thead>
            <tr class="table-warning">
              <th><input type="checkbox" id="select_all"></th>
              <th>Date</th>
              <th>Employee Number</th>
              <th>Employee Name</th>
              <th>Biometric Number</th>
              <th>Employee Type</th>
              <th>Active</th>
              <th>Leave Type</th>

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

          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>


@endsection
@push('scripts')


  <script>

    $(document).on("focus", ".start_date1", function () {
      $(this).datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        minDate: new Date(2025, 1, 1),
        maxDate: -1,
        showAnim: "slideDown",
        yearRange: "-25:+0",
      });
    });


    //table data		

    var table = $('#leaveTbl').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: "{{ url('punchmissdatagrid') }}",
        type: "GET",
        data: function (d) {
          d.start_date = $('#start_date').val();

        }
      },

      columns: [
        {   // Checkbox column
          data: 'id',
          render: function (data, type, row) {
            return '<input type="checkbox" class="row_checkbox" value="' + data + '">';
          },
          orderable: false,
          searchable: false
        },
        { data: 'date', name: 'date' },
        { data: 'employee_number', name: 'employee_number' },
        { data: 'first_name', name: 'first_name' },
        { data: 'biometric_empno', name: 'biometric_empno' },
        { data: 'dept', name: 'dept' },
        { data: 'active', name: 'active' },
        {
          data: 'status',
          name: 'status',
          render: function (data, type, row) {

            if (!data) return '';

            let badgeClass = '';
            let label = data.toUpperCase().trim();

            switch (label) {
              case 'ABSENT':
                badgeClass = 'badge bg-danger';
                break;

              case 'ABSENT / HALF DAY':
                badgeClass = 'badge bg-warning text-dark';
                break;

              case 'PERMISSION':
                badgeClass = 'badge bg-primary';
                break;

              default:
                badgeClass = 'badge bg-secondary';
            }

            return '<span class="' + badgeClass + '">' + label + '</span>';
          }
        },


      ],
      order: [[1, 'desc']]
    });

    // Select all checkboxes
    $('#select_all').on('click', function () {
      $('.row_checkbox').prop('checked', this.checked);
    });


    // search table		

    $(document).on('click', '.report_search', function () {
      var start_date = $('.start_date1').val();

      if (start_date !== '') {
        // Update messages


        // Reload DataTable with new param
        $('#leaveTbl').DataTable().ajax.reload();
      } else {
        showCustomAlert("Please choose date", 'warning');
      }
    });

    // mail		
    $('#email_leave').on('click', function () {
      let selectedRows = [];
      let emp_name = [];
      let emp_mail = [];
      let manager_mail = [];
      let status = [];
      let start_date = $('.start_date1').val();

      $('#leaveTbl tbody input.row_checkbox:checked').each(function () {
        let rowData = table.row($(this).closest('tr')).data();
        selectedRows.push(rowData.id);
        emp_name.push(rowData.first_name);
        emp_mail.push(rowData.email);
        status.push(rowData.status);
        manager_mail.push(rowData.reporting_manager_email);
      });

      if (selectedRows.length > 0) {
        $('.employee_mail').val(emp_mail);
        $('.employee_name').val(emp_name);
        $('.cc').val(manager_mail);
        $('.status').val(status);

        let absentMsg = `<br><br>As per the attendance records you are marked as absent on <b>${start_date}</b> in our JRK ASPIRE ERP system.
              <br><br>In case you have availed leave or have been on duty, you are advised to apply and get the same duly approved immediately.
              <br><br><b>Failure to apply and get leave approved within 3 working days from the days of being absent will result in the said day(s) being taken on record as under loss of pay (LOP).</b>
              <br><br>All leave and approval subject to applicable leave policies.
              <br><br>Any system errors and omissions in recording your attendance on the said date may be reported to us for clarification and rectification on merits.
              <br><br>Please undertake the necessary steps in time.
              <br><br>Regards,<br>Team Payroll Admin`;

        let halfDayMsg = `<br><br>As per the attendance records you are marked as absent (HALF DAY) on <b>${start_date}</b> in our JRK ASPIRE ERP system.
              <br><br>In case you have availed leave or have been on duty, you are advised to apply and get the same duly approved immediately.
              <br><br><b>Failure to apply and get leave approved within 3 working days from the days of being absent will result in the said day(s) being taken on record as under loss of pay (LOP).</b>
              <br><br>All leave and approval subject to applicable leave policies.
              <br><br>Any system errors and omissions in recording your attendance on the said date may be reported to us for clarification and rectification on merits.
              <br><br>Please undertake the necessary steps in time.
              <br><br>Regards,<br>Team Payroll Admin`;

        let permissionMsg = `<br><br>As per the attendance records you are marked as Permission on <b>${start_date}</b> in our JRK ASPIRE ERP system.
              <br><br>Please apply your permission.
              <br><br>Regards,<br>Team Payroll Admin`;


        $('.msg').val(absentMsg);
        $('.msg1').val(permissionMsg);
        $('.msg2').val(halfDayMsg);


        $('#contactModal').modal('show');
      } else {
        showCustomAlert('Please Select Employee', 'warning');
      }
    });



    //send mail
    $('.sendmail').click(function () {
      $('#contactModal').modal('hide');
      $('.ajaxLoading').show();

      let selectedRows = [];
      let employee_name = [];
      let employee_mail = [];
      let cc = [];
      let status = [];

      $('#leaveTbl tbody input.row_checkbox:checked').each(function () {
        const rowData = $('#leaveTbl').DataTable().row($(this).closest('tr')).data();
        selectedRows.push(rowData.employee_id);
        employee_name.push(rowData.first_name);
        employee_mail.push(rowData.email);
        cc.push(rowData.reporting_manager_email);
        status.push(rowData.status);
      });

      let mail1 = employee_mail.join(',');
      let ccEmails = cc.join(',');
      let empNames = employee_name.join(',');
      let allStatus = status.join(',');

      let msg = $('.msg').val();
      let msg1 = $('.msg1').val();
      let msg2 = $('.msg2').val();
      let date = $('.start_date1').val();

      if (selectedRows.length > 0) {
        let url = "{{ URL::to('leaveremindmail') }}/" + selectedRows.join(',') +
          '?mail=' + encodeURIComponent(mail1) +
          '&cc=' + encodeURIComponent(ccEmails) +
          '&msg=' + encodeURIComponent(msg) +
          '&msg1=' + encodeURIComponent(msg1) +
          '&msg2=' + encodeURIComponent(msg2) +
          '&status=' + encodeURIComponent(allStatus) +
          '&employee_name=' + encodeURIComponent(empNames) +
          '&date=' + encodeURIComponent(date);

        $.get(url, function (data) {
          showCustomAlert("Mail Sent Successfully", 'success');
          $('#leaveTbl').DataTable().ajax.reload();
        }).done(function () {
          $('.ajaxLoading').hide();
        }).fail(function () {
          $('.ajaxLoading').hide();
        });
      } else {
        showCustomAlert("Please select Employee to send mail.", 'error');

      }

      return false;
    });


  </script>


@endpush