@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Expense Account Structure Report</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="report_table" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">
              <th>Expense Account Name</th>
              <th>Expense Account Structure</th>
            </tr>
            <tr class="table-success">
              <th><input type="text" placeholder="Search" /><span style="display: none;">Expense Account Name</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Expense Account Structure</span>
              </th>
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
        ajax: '{{ url("getexpaccstructurerpt") }}',
        columns: [

          { data: 'account_name', name: 'f_account_structure_t.account_name' },
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