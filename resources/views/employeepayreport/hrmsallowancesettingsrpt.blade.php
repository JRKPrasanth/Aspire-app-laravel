@extends('layouts.header')
@section('content')
  <h3 class="text-danger"> HRMS Allowance Settings Report</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="report_table" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">
              <th>Employee Type</th>
              <th>Department Name</th>
              <th>Allowance Name</th>
              <th>Allowance Type</th>
              <th>Account ID</th>
              <th>Account Name</th>



            </tr>
            <tr class="table-success">

              <th><input type="text" placeholder="Search" /><span style="display: none;">Employee Type</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Department Name</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Allowance Name</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Allowance Type</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Account ID</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Account Name</span></th>


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
        ajax: '{{ url("gethrmsallowancesettingsrpt") }}',

        columns: [
          { data: 'lookup_code', name: 'a_lookuplines_t.lookup_code' },
          { data: 'sub_department_name', name: 'm_department_lines_t.sub_department_name' },
          { data: 'allowance_name', name: 'm_allowance_tbl.allowance_name' },
          { data: 'type', name: 'm_allowance_tbl.type' },
          { data: 'account_structure_id', name: 'f_hr_account_allowance_setting_lines_t.account_structure_id' },
          { data: 'concatenated_segments', name: 'f_account_structure_t.concatenated_segments' }


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