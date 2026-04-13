@extends('layouts.header')
@section('content')

  <h3 class="text-danger"> ROL Report</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3"></div>
      <div class="table-responsive">
        <table id="ReportTbl" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">
              <th class="freeze">Group Name</th>
              <th>Category Name</th>
              <th>Subcategory Name</th>
              <th>Product Name</th>
              <th>Min Stock Level1</th>
              <th>Min Stock Level2</th>
              <th>Min Stock Level3</th>
              <th>Max Stock Level</th>
              <th>Reorder Quantity</th>
              <th>Qoh</th>
              <th>Critical Level</th>

            </tr>
            <tr class="table-danger">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Group Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Category
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Subcategory
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Min Stock
                  Level1</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Min Stock
                  Level2</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Min Stock
                  Level3</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Max Stock
                  Level</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Reorder
                  Quantity</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Qoh</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Critical
                  Level</span></th>

            </tr>
          </thead>
          <tbody>
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
        orderCellsTop: true,
        ajax: {
          url: "{{ url('getrolreport') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [
          { class: 'freeze', data: "group_name" },
          { data: "category_name" },
          { data: "subcategory_name" },
          { data: "concatenated_product" },
          { data: "min_order_qty" },
          { data: "min_stock_level2" },
          { data: "min_stock_level3" },
          { data: "max_order_qty" },
          { data: "re_order_level" },
          { data: "qoh" },
          { data: "critical_level" }



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

      // Trigger search
      $('.report_search').on('click', function () {
        $('#ReportTbl').DataTable().ajax.reload();
      });

    });

  </script>
@endpush