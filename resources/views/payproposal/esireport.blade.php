@extends('layouts.header')
@section('content')
  <h3 class="text-danger">ESI Report</h3>
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
              <th>Date</th>
              <th>ESI Number</th>
              <th>Company Contribute</th>
              <th>Employee Contribute</th>
              <th>Month</th>
              <th>Year</th>

            </tr>
            <tr class="table-danger">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Employee Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Date</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">ESI
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Company
                  Contribute</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Employee
                  Contribute</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Month</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Year</span>
              </th>

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
          url: "{{ url('esireportgrid') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
            d.account_code_line = $('#account_line_id').val();
          }
        },
        columns: [
          { class: 'freeze', data: 'employee_number', name: 'employee_number' },
          { data: 'date', name: 'date' },
          { data: 'esi_no', name: 'esi_no' },
          { data: 'amount', name: 'amount' },
          { data: 'amount_employee', name: 'amount_employee' },
          { data: 'month', name: 'month' },
          { data: 'year', name: 'year' }

        ]

      });



      // Column search
      $('#ReportTbl thead').on('keyup change', ".column-search", function () {
        var index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });

  </script>

@endpush