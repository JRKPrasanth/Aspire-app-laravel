@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Employee Pay Report</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="ReportTbl" class="table table-bordered table-striped w-100">
          <thead>
            <tr class="table-warning">
              <th class="freeze">Employee Name</th>
              <th>Department</th>
              <th>Payroll Date</th>
              <th>Month</th>
              <th>Year</th>
              <th>Employee Type</th>
              <th>Payroll Type</th>
              <th>Basic</th>
              <th>HRA</th>
              <th>DA</th>
              <th>Other Components</th>
              <th>Annual Allowance</th>
              <th>Gratuity</th>
              <th>ESI</th>
              <th>PF</th>
              <th>VPF</th>
              <th>PT</th>
              <th>Gross Salary</th>
              <th>Net Pay</th>
              <th>CTC</th>
              <th>Total Days</th>
              <th>Attendance Days</th>
              <th>Basic</th>
              <th>HRA</th>
              <th>DA</th>
              <th>Other Components</th>
              <th>PF Amount</th>
              <th>VPF Amount</th>
              <th>ESI Amount</th>
              <th>PT Amount</th>
              <th>TDS Deduction</th>
              <th>Insurance Deduction</th>
              <th>Loan Deduction</th>
              <th>Other Deduction</th>
              <th>Salary Advance</th>
              <th>CTC</th>
              <th>Gross Salary</th>
              <th>Net Salary</th>
              <th>Arrear Status</th>


            </tr>
            <tr class="table-danger">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Employee Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Department</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Payroll
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Month</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Year</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Employee
                  Type</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Payroll
                  Type</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Basic</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">HRA</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">DA</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Other
                  Components</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Annual
                  Allowance</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Gratuity</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">ESI</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">PF</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">VPF</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">PT</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Gross
                  Salary</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Net
                  Pay</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">CTC</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Total
                  Days</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Attendance
                  Days</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Basic</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">HRA</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">DA</span>
              </th>
               <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Other Component</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">PF
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">VPF
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">ESI
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">PT
                  Amount</span></th>
                                    <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">
                TDS Deduction</span></th>
                  <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">
                 Insurance Deduction</span></th>
                  <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">
                 Loan Deduction</span></th>
                                   <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">
                 Other Deduction</span></th>
                                                    <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">
                 Salary Advance</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">CTC</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Gross
                  Salary</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Net
                  Salary</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Arrear
                  Status</span></th>


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

    $(document).ready(function () {

      var table = $('#ReportTbl').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        ajax: {
          url: "{{ url('getemployeepayrpt') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
            d.account_code_line = $('#account_line_id').val();
          }
        },
        columns: [
          { class: 'freeze', data: 'employee_number' },
          { data: 'department' },
          { data: 'date' },
          { data: 'month' },
          { data: 'year' },
          { data: 'employee_type_name' },
          { data: 'payroll_type_name' },
          { data: 'pay_basic' },
          { data: 'pay_hra' },
          { data: 'pay_da' },
          { data: 'total_allowance' },
          { data: 'pay_annual_allowance' },
          { data: 'pay_gratuity' },
          { data: 'pay_esi' },
          { data: 'pay_pf' },
          { data: 'pay_vpf' },
          { data: 'pay_pt' },
          { data: 'pay_gross_pay' },
          { data: 'pay_net_pay' },
          { data: 'pay_ctc_pay' },
          { data: 'totaldays' },
          { data: 'presentdays' },
          { data: 'list_basic_salary' },
          { data: 'list_hra' },
          { data: 'list_da' },
          { data: 'total_allowance_pay' },
          { data: 'list_pf_amount' },
          { data: 'list_vpf_amount' },
          { data: 'list_esi_amount' },
          { data: 'list_pt_amount' },
          { data: 'ded_tds_pay' },
          { data: 'ded_insurance_pay' },
          { data: 'ded_loan_pay' },
          { data: 'ded_other_pay' },
          { data: 'list_loan_deduction' },
          { data: 'list_ctc_salary' },
          { data: 'list_gross_salary' },
          { data: 'list_net_salary' },
          { data: 'arrear_status' }

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



      // Column search
      $('#ReportTbl thead').on('keyup change', ".column-search", function () {
        var index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });

  </script>


@endpush