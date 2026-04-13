@extends('layouts.header')
@section('content')
  <h3 class="text-danger"> Product Account Structure Report</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="report_table" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">
              <th>Product Name</th>
              <th>Product Group Name</th>
              <th>Product Control Account</th>
              <th>Control Account Name</th>
              <th>Product Account Code</th>
              <th>Account Code Name</th>
              <th>Product Discount Account Code</th>
              <th>Discount Account Name</th>
            </tr>
            <tr class="table-success">
              <th><input type="text" placeholder="Search" /><span style="display: none;">Product Name</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Product Group Name</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Product Control Account</span>
              </th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Control Account Name</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Product Account Code</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Account Code Name</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Product Discount Account
                  Code</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Discount Account Name</span></th>

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
        ajax: '{{ url("getprdaccstructurerpt") }}',
        columns: [

          { data: 'concatenated_product', name: 'm_products_t.concatenated_product' },
          { data: 'group_name', name: 'm_product_groups_t.group_name' },
          { data: 'prd_control_account_id', name: 'prd_control_f_account_structure_t.concatenated_segments' },
          { data: 'prd_control_account_name', name: 'prd_control_f_account_structure_t.account_name' },
          { data: 'prd_account_name', name: 'prd_account_f_account_structure_t.account_name' },
          { data: 'disc_account_code', name: 'm_products_t.disc_account_code' },
          { data: 'prd_disc_account_code', name: 'prd_disc_f_account_structure_t.concatenated_segments' },
          { data: 'prd_disc_account_name', name: 'prd_disc_f_account_structure_t.account_name' }

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