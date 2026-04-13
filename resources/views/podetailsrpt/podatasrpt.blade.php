@extends('layouts.header')
@section('content')
  <h3 class="text-danger">PO Details Report</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body p-4">
      <form method="post" action="" id="job_card_reprot" class="needs-validation" novalidate
        enctype="multipart/form-data">
        @csrf
        <div class="row g-4 align-items-end">
          <div class="col-md-4">
            <label for="start_date" class="form-label fw-semibold">From Date</label>
            <input type="text" class="form-control start_date" id="start_date" name="start_date" required
              autocomplete="off">
            <div class="invalid-feedback">Please select a start date.</div>
          </div>

          <div class="col-md-4">
            <label for="end_date" class="form-label fw-semibold">To Date</label>
            <input type="text" class="form-control end_date" id="end_date" name="end_date" required autocomplete="off">
            <div class="invalid-feedback">Please select an end date.</div>
          </div>

          <div class="col-md-2 d-grid">
            <button type="button" class="btn btn-primary report_search" id="report_search">
              <i class="bi bi-search-heart me-1"></i> Search
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3"></div>
      <div class="table-responsive">
        <table id="ReportTbl" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">
              <th class="freeze">PO Number</th>
              <th>Po Date</th>
              <th>Supplier</th>
              <th>Product Name</th>
              <th>Uom Code</th>
              <th>Qty</th>
              <th>Received Qty</th>
              <th>Pending Qty</th>
              <th>Unit Price</th>
              <th>Discount Percentage</th>
              <th>Discount Amount</th>
              <th>HSN Code</th>
              <th>Tax Group</th>
              <th>Tax Amount</th>
              <th>Accessable Value</th>
              <th>Total Amount</th>
              <th>Promised Date</th>
              <th>Comments</th>
              <th>Supplier Reference Number</th>
              <th>Pricelist</th>
              <th>PO Status</th>
              <th>Source</th>
              <th>Reference Number</th>
              <th>PO Tax Total</th>
              <th>PO Grand Total</th>
              <th>Advance Amount</th>
              <th>Unadjusted Advance</th>
            </tr>
            <tr class="table-success">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">PO Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Po
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Supplier</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Product
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Uom
                  Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Qty</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Received
                  Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Pending
                  Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Unit
                  Price</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Discount
                  Percentage</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Discount
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">HSN
                  Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Tax
                  Group</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Tax
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Accessable
                  Value</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Total
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Promised
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Comments</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Supplier
                  Reference Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Pricelist</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">PO
                  Status</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Source</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Reference
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">PO Tax
                  Total</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">PO Grand
                  Total</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Advance
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Unadjusted
                  Advance</span></th>
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
          url: "{{ url('getpodatasrpt') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();

          }
        },
        columns: [

          { class: 'freeze', data: "po_number" },
          { data: "po_date" },
          { data: "supname" },
          { data: "prdname" },
          { data: "uom_code" },
          { data: "qty" },
          { data: "received_qty" },
          { data: "pending_qty" },
          { data: "unit_price" },
          { data: "discount_percentage" },
          { data: "discount_amount" },
          { data: "classification_code" },
          { data: "tax_group_name" },
          { data: "tax_amount" },
          { data: "accessable_value" },
          { data: "line_total" },
          { data: "promised_date" },
          { data: "comments" },
          { data: "supplier_reference_no" },
          { data: "pricelist_name" },
          { data: "po_status" },
          { data: "source" },
          { data: "reference_number" },
          { data: "po_tax_total" },
          { data: "po_grand_total" },
          { data: "advance_amount" },
          { data: "balance_amount" }
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