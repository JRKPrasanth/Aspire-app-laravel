@extends('layouts.header')
@section('content')
  <h3 class="text-danger"> RTV Report</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3"></div>
      <div class="table-responsive">
        <table id="ReportTbl" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">
              <th class="freeze">Return Invoice Number</th>
              <th>Return Date</th>
              <th>PO Number</th>
              <th>GRN Number</th>
              <th>PO Invoice Number</th>
              <th>QC Number</th>
              <th>Supplier Name</th>
              <th>Product Name</th>
              <th>Return Qty</th>
              <th>Unit Price</th>
              <th>Tax Group</th>
              <th>Tax Amount</th>
              <th>Total</th>
              <th>Reason</th>
              <th>RTV Status</th>
            </tr>
            <tr class="table-success">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Return Invoice Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Return
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">PO
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">GRN
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">PO Invoice
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">QC
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Supplier
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Product
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Return
                  Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Unit
                  Price</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Tax
                  Group</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Tax
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Total</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Reason</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">RTV
                  Status</span></th>
            </tr>
          </thead>

          <tbody>
            <!-- Your dynamic row data goes here -->
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
          url: "{{ url('getrtv') }}",
          type: "GET",
        },
        columns: [

          { class: 'freeze', data: "return_invoice_number" },
          { data: "return_date" },
          { data: "po_number" },
          { data: "grn_number" },
          { data: "bill_number" },
          { data: "qc_number" },
          { data: "supplier_name" },
          { data: "concatenated_product" },
          { data: "reject_qty" },
          { data: "unit_price" },
          { data: "tax_group_name" },
          { data: "tax_amount" },
          { data: "line_total" },
          { data: "reason" },
          { data: "p_return_status" }

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
      $('#ReportTbl thead').on('keyup change', ".column-search", function () {
        var index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });
  </script>
@endpush