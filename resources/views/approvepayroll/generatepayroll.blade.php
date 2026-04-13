@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Generate Payroll</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white fw-semibold"></div>
    <div class="card-body">

      <!-- Filter Section -->
      <div class="row g-3 align-items-end mt-2">

        <!-- Department -->
        <div class="col-md-2">
          <label for="department" class="form-label fw-semibold">Department</label>
          <select id="department" class="form-select select2 department">
            <!-- Options -->
          </select>
        </div>

        <!-- Employee -->
        <div class="col-md-2">
          <label for="employee" class="form-label fw-semibold">Employee</label>
          <select id="employee" class="form-select select2 employee">
            <!-- Options -->
          </select>
        </div>

        <!-- Source -->
        <div class="col-md-2">
          <label for="source" class="form-label fw-semibold">Source</label>
          <select id="source" class="form-select select2 source">
            <!-- Options -->
          </select>
        </div>

        <!-- Month -->
        <div class="col-md-2">
          <label for="month" class="form-label fw-semibold">Month</label>
          <select id="month" class="form-select select2 month">
            <!-- Options -->
          </select>
        </div>

        <!-- Year -->
        <div class="col-md-2">
          <label for="year" class="form-label fw-semibold">Year</label>
          <select id="year" class="form-select select2 year">
            <!-- Options -->
          </select>
        </div>

        <!-- Generate Button -->
        <div class="col-md-2">
          <button type="button" class="btn btn-primary w-100 generated">
            <i class="bi bi-search me-1"></i> Generate
          </button>
        </div>

      </div>

    </div>
  </div>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3"></div>
      <div class="table-responsive">
        <table id="payrolltbl" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">
              <th>Employee Number</th>
              <th class="freeze">Employee Name</th>
              <th>Status</th>
              <th>Month</th>
              <th>Year</th>
              <th>Department Name</th>
              <th>Working Days</th>
              <th>Basic</th>
              <th>DA</th>
              <th>HRA</th>
              <th>Annual Allowance</th>
              <th>Voluter PF</th>
              <th>ESI Value</th>
              <th>PF Value</th>
              <th>Professional tax</th>
              <th>Payroll Type</th>
              <th>Esi</th>
              <th>PF</th>
              <th>Professional tax</th>
              <th>Gross Pay</th>
              <th>Net Amount </th>
              <th>CTC Pay</th>


            </tr>
            <tr class="table-danger">
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Employee
                  Number</span></th>
              <th class="freeze"><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Employee Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Status</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Month</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Year</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Department
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Working
                  Days</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Basic</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">DA</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">HRA</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Annual
                  Allowance</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Voluter
                  PF</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">ESI
                  Value</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">PF
                  Value</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Professional tax</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Payroll
                  Type</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Esi</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">PF</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Professional tax</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Gross
                  Pay</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Net Amount
                </span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">CTC
                  Pay</span></th>


            </tr>
          </thead>
          <tbody>
            <!-- Your dynamic row data goes here -->
          </tbody>
        </table>
      </div>
    </div>
  </div>





@endsection
@push('scripts')

  <script>

    // on change 
    // year
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

    var condition2 = "parent_class_id=0";
    var deptUrl = "{{ URL::to('jcomboform') }}?table=m_department_lines_t:department_line_id:sub_department_code|sub_department_name&order_by=department_line_id asc&parent=" + encodeURIComponent(condition2);
    loadDropdown("#department", deptUrl, '', '-- Select Department --');

    var empUrl = "{{ URL::to('jcomboform') }}?table=hr_employee_t:employee_id:employee_number|first_name&order_by=employee_id asc";
    loadDropdown("#employee", empUrl, '', '-- Select Employee --');


    var condition = 'lookup_type="payroll_type"';
    var sourceUrl = "{{ URL::to('jcomboformlogin') }}?table=a_lookuplines_t:lookuplines_id:lookup_code&order_by=lookuplines_id&parent=" + encodeURIComponent(condition);
    loadDropdown("#source", sourceUrl, '', '-- Select Source --');
    loadDropdown("#source1", sourceUrl, '', '-- Select Source --');


    function loadDropdown(selector, url, selectedValue, defaultText = "-- Select --") {
      $.ajax({
        url: url,
        type: 'GET',
        success: function (data) {
          if (typeof data === "string") {
            try {
              data = JSON.parse(data);
            } catch (e) {
              console.error("Invalid JSON format:", data);
              return;
            }
          }

          $(selector).html(`<option value="">${defaultText}</option>`);
          $.each(data, function (i, item) {
            let selected = item.val == selectedValue ? 'selected' : '';
            $(selector).append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
          });

          $(selector).trigger('change.select2');
        }
      });
    }


    // table data
    $(document).ready(function () {

      var table = $('#payrolltbl').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        ajax: "{{ url('employeepayrollgrid1') }}",

        columns: [
          { data: 'employee_number' },
          { class: 'freeze', data: 'first_name' },
          { data: 'approved_status' },
          { data: 'month' },
          { data: 'year' },
          { data: 'sub_department_name' },
          { data: 'attendance_days' },
          { data: 'basic_salary' },
          { data: 'da' },
          { data: 'hra' },
          { data: 'annual_allowance' },
          { data: 'volunter_pf' },
          { data: 'esi_val' },
          { data: 'pf_val' },
          { data: 'pt_val' },
          { data: 'lookup_code' },
          { data: 'esi1' },
          { data: 'pf1' },
          { data: 'pt1' },
          { data: 'gross_salary' },
          { data: 'net_salary' },
          { data: 'ctc_pay' }

        ]
      });


      // Column search
      $('#payrolltbl thead').on('keyup change', ".column-search", function () {
        var index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });



    //  generate  click function start
    $(document).on('click', '.generated', function () {

      var department = $('#department').select2('val');
      var source = $('#source').select2('val');
      var month = $('#month').select2('val');
      var year = $('#year').select2('val');
      var employee = $('#employee').select2('val');

      if (employee != '') {
        employee = employee;
      } else {
        employee = '0';

      }
      if (department != '' && source != '' && month != '' && year != '') {
        var url = "{{URL::to('generatepayroll')}}?department=" + department + "&source=" + source + "&month=" + month + "&year=" + year + "&employee=" + employee;
        $.get(url, function (data) {
          var content = '';

          // genertaed payroll list
          if (data.emp_data_payproposal && data.emp_data_payproposal.length !== 0) {
            $.each(data.emp_data_payproposal, function (index, val) {
              content += val + ',';
            });
            content = content.replace(/,\s*$/, "");
            content += ' Generate Payproposal for these employee. ';
          }

          // already payroll exists
          if (data.emp_data_payroll && data.emp_data_payroll.length !== 0) {
            $.each(data.emp_data_payroll, function (index, val) {
              content += val + ',';
            });
            content = content.replace(/,\s*$/, "");
            content += ' Already payroll generated for these employee. ';
          }

          // attendance not sync
          if (data.emp_data_attend && data.emp_data_attend.length !== 0) {
            $.each(data.emp_data_attend, function (index, val) {
              content += val + ',';
            });
            content = content.replace(/,\s*$/, "");
            content += ' Attendance not synced for these employees. ';
          }

          // shift type missing
          if (data.emp_data_shift && data.emp_data_shift.length !== 0) {
            $.each(data.emp_data_shift, function (index, val) {
              content += val + ',';
            });
            content = content.replace(/,\s*$/, "");
            content += ' Shift not available for these employees. ';
          }

          // employee type missing
          if (data.emp_data_type && data.emp_data_type.length !== 0) {
            $.each(data.emp_data_type, function (index, val) {
              content += val + ',';
            });
            content = content.replace(/,\s*$/, "");
            content += ' Employee Type not available for these employees. ';
          }
          var status = data.status;
          var message = data.message + ".  " + content;
          showCustomAlert(message,status);

          setTimeout(function () {
            location.reload();
          }, 1000);

        });
      }
      else {
        showCustomAlert("Please Choose All the Fields", 'info');
      }
    });



  </script>

@endpush