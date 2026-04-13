@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Workorder</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="ReportTbl" class="table table-bordered table-striped w-100">
          <thead>
            <tr class="table-warning">
              <th>Workorder No</th>
              <th>Workorder Date</th>
              <th>Plan Number</th>
              <th>Product Code</th>
              <th>Product Name</th>
              <th>Workorder Qty</th>
              <th>Completed Workorder Qty</th>
              <th>Pending Workorder Qty</th>


            </tr>
            <tr class="table-danger">
              <th>
                <input type="text" class="column-search" placeholder="Search Workorder No">
                <span style="display:none;">Workorder No</span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search Workorder Date">
                <span style="display:none;">Workorder Date</span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search Plan Number">
                <span style="display:none;">Plan Number</span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search Product Code">
                <span style="display:none;">Product Code</span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search Product Name">
                <span style="display:none;">Product Name</span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search Workorder Qty">
                <span style="display:none;">Workorder Qty</span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search Completed Qty">
                <span style="display:none;">Completed Workorder Qty</span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search Pending Qty">
                <span style="display:none;">Pending Workorder Qty</span>
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
        orderCellsTop: true,
        order: [[1, 'desc']],
        ajax: "{{ url('getWorkorderstatusData') }}",

        columns: [
          { data: 'workorder_no', name: 'workorder_no' },
          { data: 'workorder_date', name: 'workorder_date' },
          { data: 'plan_no', name: 'plan_no' },
          { data: 'product_code', name: 'product_code' },
          { data: 'concatenated_product', name: 'concatenated_product' },
          { data: 'qty', name: 'qty' },
          { data: 'plan_qty', name: 'plan_qty' },
          { data: 'pending_qty', name: 'pending_qty' }

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