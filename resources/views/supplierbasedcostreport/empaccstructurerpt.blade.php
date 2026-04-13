@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Employee Account Structure Report</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="report_table" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">
              <th>Employee No</th>
              <th>Employee Name</th>
              <th>Employee Type</th>
              <th>Allowance Name</th>
              <th>Allowance Type</th>
              <th>Allowance</th>
              <th>Employee Account Structure</th>
              <th>Active</th>
            </tr>

            <tr class="table-success">
              <th><input type="text" placeholder="Search" /><span style="display: none;">Employee No</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Employee Name</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Employee Type</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Allowance Name</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Allowance Type</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Allowance</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Employee Account Structure</span>
              </th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Active</span></th>
            </tr>

          </thead>
        </table>
      </div>
    </div>
  </div>
@endsection
@push('scripts')

  <script>
    $(document).ready(function () {
      var table = $('#report_table').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: '{{ url("getempaccstructurerpt") }}',
        columns: [

          { data: 'employee_number', name: 'hr_employee_t.employee_number' },
          { data: 'first_name', name: 'hr_employee_t.first_name' },
          { data: 'lookup_code', name: 'a_lookuplines_t.lookup_code' },
          { data: 'allowance_name', name: 'm_allowance_tbl.allowance_name' },
          { data: 'type', name: 'm_allowance_tbl.type' },
          { data: 'allowance', name: 'hr_employee_payproposal.allowance' },
          { data: 'concatenated_segments', name: 'f_account_structure_t.concatenated_segments' },
          { data: 'active', name: 'hr_employee_t.active' }


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
      $('#report_table thead').on('keyup change', ".column-search", function () {
        var index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });
  </script>

@endpush