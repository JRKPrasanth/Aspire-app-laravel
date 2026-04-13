@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Audit Trail Report</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="report_table" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">
              <th>Menu</th>
              <th>Reference No</th>
              <th>Action</th>
              <th>Date</th>
              <th>Employee Number</th>
              <th>Employee Name</th>
            </tr>
            <tr class="table-success">

              <th><input type="text" placeholder="Search Menu" /><span style="display: none;">Menu</span></th>
              <th><input type="text" placeholder="Search Ref No" /><span style="display: none;">Reference No</span></th>
              <th><input type="text" placeholder="Search Action" /><span style="display: none;">Action</span></th>
              <th><input type="text" placeholder="Search Date" /><span style="display: none;">Date</span></th>
              <th><input type="text" placeholder="Search Emp No" /><span style="display: none;">Employee Number</span>
              </th>
              <th><input type="text" placeholder="Search Employee Name" /><span style="display: none;">Employee
                  Name</span></th>

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
        serverSide: true,
        scrollX: true,
        scrollY: "50vh",
        ajax: '{{ url("getuserpermissionrptdata") }}',
        columns: [
          { data: 'module', name: 'module' },
          { data: 'ref_no', name: 'ref_no' },
          { data: 'action', name: 'action' },
          { data: 'created_at', name: 'created_at' },
          { data: 'employee_number', name: 'employee_number' },
          { data: 'first_name', name: 'first_name' }

        ],
        initComplete: function () {
          $('#report_table thead tr:eq(1) th').each(function (i) {
            $('input', this).on('keyup change', function () {
              table.column(i).search(this.value).draw();
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