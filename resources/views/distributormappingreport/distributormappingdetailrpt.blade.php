@extends('layouts.header')
@section('content')
  <h3 class="text-danger"> Distributor Mapping Details Report</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="report_table" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">
              <th>Employee Number</th>
              <th>Employee Name</th>
              <th>Distributor Name</th>
              <th>State Name</th>
              <th>Town Name</th>
              <th>Employee Active Status</th>
              <th>Distributor Active Status</th>
              <th>Date of Leaving</th>


            </tr>
            <tr class="table-success">

              <th><input type="text" placeholder="Search" /><span style="display: none;">Employee Number</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Employee Name</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Distributor Name</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">State Name</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Town Name</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Employee Active Status</span>
              </th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Distributor Active Status</span>
              </th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Date of Leaving</span></th>

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
        ajax: '{{ url("getdistributormappingdetailrpt") }}',

        columns: [
          { data: 'employee_number', name: 'hr_employee_t.employee_number' },
          { data: 'first_name', name: 'hr_employee_t.first_name' },
          { data: 'customer_name', name: 'm_customers_t.customer_name' },
          { data: 'state_name', name: 'm_states_t.state_name' },
          { data: 'city_name', name: 'm_cities_t.city_name' },
          { data: 'active', name: 'hr_employee_t.active' },
          { data: 'dist_status', name: 'm_customers_t.active as dist_status' },
          { data: 'date_of_leaving', name: 'hr_employee_t.date_of_leaving' }

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