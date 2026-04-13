@extends('layouts.header')
@section('content')

  <h3 class="text-danger"> ROL FG Report </h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3"></div>
      <div class="table-responsive">
        <table id="ReportTbl" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">
              <th class="freeze">

                Product Name
              </th>
              <th>

                Subcategory Name
              </th>
              <th>

                Re-Order Level
              </th>
              <th>

                Sales Order Qty
              </th>
              <th>

                Extra Order Qty
              </th>
              <th>

                FG Stock
              </th>
              <th>

                FG Rqmt as per Ord_Recvd
              </th>
              <th>

                FG Rqmt to Stock in FG
              </th>
              <th>

                Work in progress
              </th>
              <th>

                Maximum Qty in FG
              </th>
              <th>

                Batch To be Taken
              </th>

            </tr>
            <tr class="table-danger">
              <th class="freeze">
                <input type="text" class="column-search" placeholder="Search">
                <span style="display:none;">Product Name</span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search">
                <span style="display:none;">Subcategory Name</span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search">
                <span style="display:none;">Re-Order Level</span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search">
                <span style="display:none;">Sales Order Qty</span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search">
                <span style="display:none;">Extra Order Qty</span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search">
                <span style="display:none;">FG Stock</span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search">
                <span style="display:none;">FG Rqmt as per Ord_Recvd</span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search">
                <span style="display:none;">FG Rqmt to Stock in FG</span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search">
                <span style="display:none;">Work in progress</span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search">
                <span style="display:none;">Maximum Qty in FG</span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search">
                <span style="display:none;">Batch To be Taken</span>
              </th>

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
          url: "{{ url('getrolfgreport') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [
          { class: "freeze", data: "concatenated_product" },
          { data: "subcategory_name" },
          { data: "min_order_qty" },
          { data: "so_qty" },
          { data: "free_qty" },
          { data: "stock" },
          { data: "FG_Req" },
          { data: "FG_Req1" },
          { data: "wip" },
          { data: "max_order_qty" },
          { data: "batchqty" }
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