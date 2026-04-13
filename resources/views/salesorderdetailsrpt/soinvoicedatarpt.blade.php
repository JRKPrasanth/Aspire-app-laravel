@extends('layouts.header')
@section('content')
  <h3 class="text-danger">SO Invoice Details Report</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body p-4">
      <form method="post" action="" id="job_card_reprot" class="needs-validation" novalidate
        enctype="multipart/form-data">
        @csrf
        <div class="row g-4 mb-3">
          <div class="col-md-2"></div>
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
        </div>

        <!-- Second Row: Centered Search Button -->
        <div class="row">
          <div class="col-md-12 text-center mt-2">
            <button type="button" class="btn btn-primary px-4 report_search" id="report_search">
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
              <th>Invoice Type</th>
              <th>Invoice Status</th>
              <th>Shipped Status</th>
              <th>Zone</th>
              <th>Customer Name</th>
              <th>Customer GST No</th>
              <th>Category Name</th>
              <th>Subcategory Name</th>
              <th>Product Name</th>
              <th>Uom Code</th>
              <th>Invoice Qty</th>
              <th>Free Qty</th>
              <th>Salesorder Qty</th>
              <th>Batch Number</th>
              <th>Unit Price</th>
              <th>Discount Percentage</th>
              <th>Discount Amount</th>
              <th>HSN Code</th>
              <th>Tax Excemption</th>
              <th>Tax Group</th>
              <th>IGST</th>
              <th>CGST</th>
              <th>SGST</th>
              <th>Assessable Value</th>
              <th>Taxable Value</th>
              <th>Tax Amount</th>
              <th>Total Amount</th>
              <th>Cash</th>
              <th>Cash Amount</th>
              <th>Trade Amount</th>
              <th>Trade Discount</th>
              <th>Comments</th>
              <th>Sales Order No</th>
              <th>Sales Order Date</th>
              <th>Pricelist</th>
              <th>Shipped Status</th>
              <th>Source</th>
              <th>Reference Number</th>
              <th>LR Number</th>
              <th>LR Date</th>
              <th>LR Status</th>
              <th>Delivery Date</th>
              <th>Cheque Number</th>
              <th>Cheque Amount</th>
              <th>Cheque Date</th>
              <th>Cheque Received Date</th>
              <th>ACK No</th>
              <th>ACK Date</th>
              <th>Eway Bill Number</th>
              <th>Eway Date</th>
              <th>State</th>
              <th>City</th>
              <th>Invoice Tax Total</th>
              <th>TCS Calculation Amount</th>
              <th>TCS Amount</th>
              <th>Transport Charges</th>
              <th>Invoice Grand Total</th>
              <th>Paid Amount</th>
              <th>Balance Amount</th>
              <th>MRP</th>
            </tr>
            <tr class="table-info">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Invoice Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Invoice
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Invoice
                  Type</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Invoice
                  Status</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Shipped
                  Status</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Zone</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Customer
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Customer GST
                  No</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Category
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Subcategory
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Uom
                  Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Invoice
                  Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Free
                  Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Salesorder
                  Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Batch
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Unit
                  Price</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Discount
                  Percentage</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Discount
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">HSN
                  Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Tax
                  Excemption</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Tax
                  Group</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">IGST</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">CGST</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">SGST</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Assessable
                  Value</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Taxable
                  Value</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Tax
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Total
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Cash</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Cash
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Trade
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Trade
                  Discount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Comments</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Sales Order
                  No</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Sales Order
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Pricelist</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Shipped
                  Status</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Source</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Reference
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">LR
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">LR Date</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">LR
                  Status</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Delivery
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Cheque
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Cheque
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Cheque
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Cheque
                  Received Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">ACK No</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">ACK
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Eway Bill
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Eway
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">State</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">City</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Invoice Tax
                  Total</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">TCS
                  Calculation Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">TCS
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Transport
                  Charges</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Invoice Grand
                  Total</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Paid
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Balance
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">MRP</span>
              </th>


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
          url: "{{ url('getsoinvoicedatarpt') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();

          }
        },
        columns: [

          { class: 'freeze', data: "invoice_number" },
          { data: "invoice_date" },
          { data: "invoice_type" },
          { data: "invoice_status" },
          { data: "shiped_status" },
          { data: "customer_type" },
          { data: "cusname" },
          { data: "gst_no" },
          { data: "category_name" },
          { data: "subcategory_name" },
          { data: "prdname" },
          { data: "uom_code" },
          { data: "qty" },
          { data: "free_qty" },
          { data: "salesorder_qty" },
          { data: "batch_number" },
          { data: "unit_price" },
          { data: "discount_percentage" },
          { data: "discount_amount" },
          { data: "classification_code" },
          { data: "tax_excemption" },
          { data: "tax_group_name" },
          { data: "igst" },
          { data: "cgst" },
          { data: "sgst" },
          { data: "assessablevalue" },
          { data: "accessablevalu" },
          { data: "tax_amount" },
          { data: "line_total" },
          { data: "cash" },
          { data: "cas_amount" },
          { data: "trade_amount" },
          { data: "trade_discount" },
          { data: "comments" },
          { data: "sales_order_no" },
          { data: "sales_order_date" },
          { data: "pricelist_name" },
          { data: "shiped_status" },
          { data: "source" },
          { data: "reference_number" },
          { data: "lr_no" },
          { data: "lr_date" },
          { data: "lr_status" },
          { data: "delivery_date" },
          { data: "cheque_no" },
          { data: "cheque_amount" },
          { data: "cheque_date" },
          { data: "cheque_received_date" },
          { data: "irn_no" },
          { data: "irn_date" },
          { data: "eway_billno" },
          { data: "eway_date" },
          { data: "statename" },
          { data: "city_name" },
          { data: "invoice_tax_total" },
          { data: "tcs_calc_amount" },
          { data: "tcs_amount" },
          { data: "transport_charges" },
          { data: "invoice_grand_total" },
          { data: "paid_amount" },
          { data: "balance_amount" },
          { data: "std_price" }

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