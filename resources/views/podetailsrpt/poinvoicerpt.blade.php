@extends('layouts.header')
@section('content')
  <h3 class="text-danger"> Purchase Invoice Details Report</h3>
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
              <th class="freeze">Invoice Number</th>
              <th>Invoice Date</th>
              <th>Supplier</th>
              <th>Product Name</th>
              <th>Qty</th>
              <th>Uom Code</th>
              <th>TAX Credit</th>
              <th>Unit Price</th>
              <th>Discount Percentage</th>
              <th>Discount Amount</th>
              <th>HSN Code</th>
              <th>Tax Group</th>
              <th>Tax Amount</th>
              <th>Total Amount</th>
              <th>Promised Date</th>
              <th>Comments</th>
              <th>Pricelist</th>
              <th>PO Number</th>
              <th>PO Date</th>
              <th>GRN Number</th>
              <th>GRN Date</th>
              <th>Supplier Reference Number</th>
              <th>Supplier Invoice Date</th>
              <th>DC Number</th>
              <th>DC Date</th>
              <th>Transport Charges</th>
              <th>Invoice Grand Total</th>
              <th>Paid Amount</th>
              <th>Balance Amount</th>
              <th>Credit Note</th>
              <th>Debit Note</th>
              <th>Credit Note Balance</th>
              <th>Debit Note Balance</th>
            </tr>
            <tr class="table-success">
              <!-- Filter inputs for each column -->
              <th class="freeze"><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Invoice Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Invoice
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Supplier</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Product
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Qty</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Uom
                  Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">TAX
                  Credit</span></th>
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
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Total
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Promised
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Comments</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Pricelist</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">PO
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">PO
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">GRN
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">GRN
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Supplier
                  Reference Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Supplier
                  Invoice Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">DC
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">DC
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Transport
                  Charges</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Invoice
                  Grand Total</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Paid
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Balance
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Credit
                  Note</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Debit
                  Note</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Credit Note
                  Balance</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Debit Note
                  Balance</span></th>
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
          url: "{{ url('getpinvdatasrpt') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();

          }
        },
        columns: [

          { class: 'freeze', data: "bill_number" },
          { data: "invoice_date" },
          { data: "supname" },
          { data: "prdname" },
          { data: "qty" },
          { data: "uom_code" },
          { data: "tax_credit" },
          { data: "unit_price" },
          { data: "discount_percentage" },
          { data: "discount_amount" },
          { data: "classification_code" },
          { data: "tax_group_name" },
          { data: "tax_amount" },
          { data: "line_total" },
          { data: "promised_date" },
          { data: "comments" },
          { data: "pricelist_name" },
          { data: "po_number" },
          { data: "po_date" },
          { data: "grn_number" },
          { data: "grn_date" },
          { data: "supplier_invoice_no" },
          { data: "supplier_invoice_date" },
          { data: "dc_number" },
          { data: "dc_date" },
          { data: "transport_charges" },
          { data: "invoice_grand_total" },
          { data: "paid_amount" },
          { data: "balance_amount" },
          { data: "credit_note" },
          { data: "debit_note" },
          { data: "credit_note_balance" },
          { data: "debit_note_balance" }
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


      // Column search
      $('#ReportTbl thead').on('keyup change', ".column-search", function () {
        var index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });
  </script>
@endpush