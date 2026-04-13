@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Pay Slip</h3>
  @include('layouts.breadcrumb')


  <div class="card-body py-3">
    <div class="d-flex flex-wrap gap-2">
      <button type="button" class="btn btn-primary generate_slip" data-action="createoffer">
        <i class="fa fa-file-text me-1"></i> Pay Slip
      </button>

      <button type="button" class="btn btn-success mail mail_slip" data-action="mailoffer" id="email_slip">
        <i class="fa fa-envelope me-1"></i> Email
      </button>
    </div>
  </div>


  <!-- table -->

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="payrolltbl" class="table table-bordered table-striped" style="width:150% !important;">
          <thead>
            <tr class="table-warning">
              <th><input type="checkbox" id="select_all"></th>
              <th class="freeze">Employee Name</th>
              <th>Employee type</th>
              <th>Month</th>
              <th>Year</th>
              <th>Basic</th>
              <th>DA</th>
              <th>HRA</th>
              <th>Annual Allowance</th>
              <th>Esi</th>
              <th>PF</th>
              <th>VPF</th>
              <th>Professional tax</th>
              <th>Voluter PF</th>
              <th>ESI</th>
              <th>PF</th>
              <th>Professional tax</th>
              <th>Payroll Type</th>
              <th>Gross Pay</th>
              <th>Net Amount </th>
              <th>CTC Pay</th>
              <th>Mail</th>
            </tr>
            <tr class="table-danger">
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span>
              </th>
              <th class="freeze"><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Employee Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Employee
                  type</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Month</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Year</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Basic</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">DA</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">HRA</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Annual
                  Allowance</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Esi</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">PF</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">VPF</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Professional tax</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Voluter
                  PF</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">ESI</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">PF</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Professional tax</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Payroll
                  Type</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Gross
                  Pay</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Net Amount
                </span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">CTC
                  Pay</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Mail</span>
              </th>

            </tr>
          </thead>

          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>



  <!-- popup -->

  <!-- Send Payslip Modal -->
  <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content" style="max-height: 90vh; overflow-y: auto;">

        <!-- Modal Header -->
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title fw-bold" id="contactModalLabel">Send Payslip</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal Form -->
        <form method="POST" action="" id="enquirymail" enctype="multipart/form-data" data-parsley-validate>
          {{ csrf_field() }} <!-- Add this if you are using Blade -->

          <div class="modal-body">

            <div class="row g-3 mb-3">
              <!-- Employee Name -->
              <div class="col-md-6">
                <label class="form-label fw-semibold">Employee Name</label>
                <input type="text" name="employee_name" class="form-control employee_name" readonly>
              </div>

              <!-- To Email -->
              <div class="col-md-6">
                <label class="form-label fw-semibold">To</label>
                <input type="email" name="employee_mail" class="form-control employee_mail" required>
              </div>

              <input type="hidden" name="emp_id" class="emp_id">

              <!-- CC -->
              <div class="col-md-6">
                <label class="form-label fw-semibold">CC</label>
                <input type="text" name="cc[]" class="form-control cc">
              </div>

              <!-- Message -->
              <div class="col-md-12">
                <label class="form-label fw-semibold">Message</label>
                <textarea name="msg" class="form-control msg tinymce" rows="5">Here is Your Payslip Report</textarea>
                <input type="hidden" name="hdr_id" class="hdr_id">
              </div>
            </div>

            <!-- PDF Preview -->
            <div class="row mt-4">
              <div class="col-12">
                <iframe id="iframepdf" class="w-100" height="600" style="display: none;"></iframe>
              </div>
            </div>
          </div>

          <!-- Modal Footer -->
          <button type="button" class="btn btn-success sendmail" id="sentmail_id" style="margin-left: 25px;margin-top: -25px;">
            <i class="fa fa-paper-plane me-1"></i> Send
          </button>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i class="fa fa-times me-1"></i> Close
            </button>

          </div>

        </form>
      </div>
    </div>
  </div>



@endsection
@push('scripts')

  <script>

    // table data	
    $(document).ready(function () {

      var table = $('#payrolltbl').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        scrollY: "50vh",
         order: [[22, 'desc']],
        ajax: "{{ route('employeepayrollgrid') }}",
        columns: [
          {   // Checkbox column
            data: 'id',
            render: function (data, type, row) {
              return '<input type="checkbox" class="row_checkbox" value="' + data + '">';
            },
            orderable: false,
            searchable: false
          },
          { class: 'freeze', data: 'first_name' },
          { data: 'employee_type' },
          { data: 'month' },
          { data: 'year' },
          { data: 'basic_salary' },
          { data: 'da' },
          { data: 'hra' },
          { data: 'annual_allowance' },
          { data: 'esi1' },
          { data: 'pf1' },
          { data: 'vpf1' },
          { data: 'pt1' },
          { data: 'vpf' },
          { data: 'esi' },
          { data: 'pf' },
          { data: 'pt' },
          { data: 'lookup_code' },
          { data: 'gross_salary' },
          { data: 'net_salary' },
          { data: 'list_ctc_pay' },
          { data: 'email' },
          { data: 'id', visible: false } // Hidden column for sorting,
        ],
                        initComplete: function () {
          var api = this.api();

          // get the real visible header inside the scroll container
          var $scrollHead = $(api.table().container())
            .find('.dataTables_scrollHead thead');

          // second header row (index 1) has the inputs
          $scrollHead.find('tr:eq(1) th').each(function (colIndex) {
            var th = this;
            $('input.column-search', th).on('keyup change', function () {
              if (api.column(colIndex).search() !== this.value) {
                api.column(colIndex).search(this.value).draw();
              }
            });
          });
        }
      });

      // Select all checkboxes
      $('#select_all').on('click', function () {
        $('.row_checkbox').prop('checked', this.checked);
      });



      // Clear filter logic (like jqGrid clear)
      $(".clear").click(function () {
        $('#payrolltbl').DataTable().search('').draw();
      });

      $('#payrolltbl thead').on('keyup change', ".column-search", function () {
        var index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });

    });



    // generate pay slip

    $(document).on('click', '.generate_slip', function () {
      var selected = $('.row_checkbox:checked');

      if (selected.length === 1) {
        var payslip_id = selected.val();
        var url = "{{ url('payslipgenerate') }}/" + payslip_id;
        window.open(url, '_blank');
      } else {
        showCustomAlert("Please Select Only One Row", "error");
      }
    });


    // email

    $('#email_slip').on('click', function () {

      var selected = $('.row_checkbox:checked');
      var emp_name = [];
      var emp_mail = [];
      var ids = [];

      selected.each(function () {
        var row = $(this).closest('tr');
        ids.push($(this).val());
        emp_name.push(row.find('td:eq(1)').text().trim());
        emp_mail.push(row.find('td:eq(21)').text().trim());
      });

      if (ids.length > 0) {
        $('.employee_mail').val(emp_mail.join(","));
        $('.employee_name').val(emp_name.join(","));
        $('#contactModal').modal('show');
      } else {
        showCustomAlert('Please Select Row', 'error');
      }
    });


    //send mail

    $('.sendmail').click(function () {

      $('#contactModal').modal('hide');

      var cc = $('.cc').val();
      var mail1 = $('.employee_mail').val();
      var msg = $('.msg').val();
      var ids = [];

      $('.row_checkbox:checked').each(function () {
        ids.push($(this).val());
      });

      var url = "{{ url('payslipgenerate') }}/" + ids.join(",") + '?mail=' + encodeURIComponent(mail1) + '&cc=' + encodeURIComponent(cc) + '&msg=' + encodeURIComponent(msg);

      $.get(url, function (data) {
        showCustomAlert("Mail Sent Successfully", 'success');
        location.reload();
      });

      filesave();
    });


    // filesave
    function filesave() {
      var form_data = new FormData(document.getElementById('enquirymail'));

      $.ajax({
        url: "{{ URL::to('payslipfilesave') }}",
        type: "POST",
        data: form_data,
        enctype: 'multipart/form-data',
        processData: false,
        contentType: false,
        async: true,
        xhr: function () {
          var xhr = $.ajaxSettings.xhr();
          if (xhr.upload) {
            xhr.upload.addEventListener('progress', function (event) {
              var percent = 0;
              var position = event.loaded || event.position;
              var total = event.total;
              if (event.lengthComputable) {
                percent = Math.ceil(position / total * 100);
              }
            }, true);
          }
          return xhr;
        }
      }).done(function (data) {
        // Optional success handling
      });
    }


  </script>


@endpush