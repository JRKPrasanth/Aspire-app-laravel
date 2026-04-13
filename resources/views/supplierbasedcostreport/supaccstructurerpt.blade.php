@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Supplier Account Structure Report</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="report_table" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">
              <th>Supplier Name</th>
              <th>Supplier Type Name</th>
              <th>Supplier Account Structure</th>


            </tr>
            <tr class="table-success">
              <th><input type="text" placeholder="Search" /><span style="display: none;">Supplier Name</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Supplier Type Name</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Supplier Account Structure</span>
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
        ajax: '{{ url("getsupaccstructurerpt") }}',
        columns: [

          { data: 'supplier_name', name: 'm_supplier_t.supplier_name' },
          { data: 'suppliertype_name', name: 'm_suppliertypes_t.suppliertype_name' },
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