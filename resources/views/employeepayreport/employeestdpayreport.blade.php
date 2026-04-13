@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Employee Standard Pay Report</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3"></div>
      <div class="table-responsive">
        <table id="ReportTbl" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">
              <th>Employee Name</th>
              <th>Department</th>
              <th>Payroll Type</th>
              <th>Employee Type</th>
              <th>Date of Joining</th>
              <th>Date of Effective</th>
              <th>Basic</th>
              <th>HRA</th>
              <th>DA</th>
              <th>Annual Allowance</th>
              <th>Gratuity</th>
              <th>ESI</th>
              <th>PF</th>
              <th>PT</th>
              <th>ESI Amount</th>
              <th>PF Amount</th>
              <th>PT Amount</th>
              <th>Volunter PF Amount</th>
              <th>Gross Salary</th>
              <th>Net Pay</th>
              <th>CTC</th>


            </tr>
            <tr class="table-danger">
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Employee
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Department</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Payroll
                  Type</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Employee
                  Type</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Date of
                  Joining</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Date of
                  Effective</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Basic</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">HRA</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">DA</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Annual
                  Allowance</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Gratuity</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">ESI</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">PF</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">PT</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">ESI
                  AMOUNT</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">PF
                  AMOUNT</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">PT
                  AMOUNT</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Volunter PF
                  AMOUNT</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Gross
                  Salary</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Net
                  Pay</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">CTC</span>
              </th>

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

    $(document).ready(function () {

      var table = $('#ReportTbl').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        ajax: {
          url: "{{ url('getemployeestdpayrpt') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
            d.account_code_line = $('#account_line_id').val();
          }
        },
        columns: [
          { data: 'employee_number' },
          { data: 'department' },
          { data: 'employee_type_name' },
          { data: 'payroll_type_name' },
          { data: 'date_of_joining' },
          { data: 'effective_date' },
          { data: 'pay_basic' },
          { data: 'pay_hra' },
          { data: 'pay_da' },
          { data: 'pay_annual_allowance' },
          { data: 'pay_gratuity' },
          { data: 'pay_esi' },
          { data: 'pay_pf' },
          { data: 'pay_pt' },
          { data: 'esi_amount' },
          { data: 'pf_amount' },
          { data: 'pt_amount' },
          { data: 'volunter_pf' },
          { data: 'pay_gross_pay' },
          { data: 'pay_net_pay' },
          { data: 'pay_ctc_pay' }

        ]

      });

      // Trigger search
      $('.report_search').on('click', function () {
        $('#ReportTbl').DataTable().ajax.reload();
      });


      // Column search
      $('#ReportTbl thead').on('keyup change', ".column-search", function () {
        var index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });

  </script>

@endpush