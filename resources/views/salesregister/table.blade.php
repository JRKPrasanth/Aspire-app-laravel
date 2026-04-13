@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Sales Register Report</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body p-4">
      <form method="post" action="" id="job_card_reprot" class="needs-validation" novalidate
        enctype="multipart/form-data">
        @csrf

        <!-- First Row: Date Inputs -->
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
              <th>Invoice Type</th>
              <th>Invoice Date</th>
              <th>Customer Name</th>
              <th>Customer GST NO</th>
              <th>Product Name</th>
              <th>HSN Code</th>
              <th>Product Qty</th>
              <th>Free Qty</th>
              <th>Product Rate</th>
              <th>Trade Discount</th>
              <th>Cash Discount</th>
              <th>Taxable Value</th>
              <th>Discount Amount</th>
              <th>Tax Group</th>
              <th>CGST</th>
              <th>SGST</th>
              <th>IGST</th>
              <th>Invoice Tax Total</th>
              <th>Invoice Grand Total</th>
            </tr>
            <tr class="table-info">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Invoice Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Invoice
                  Type</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Invoice
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Customer
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Customer GST
                  NO</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">HSN
                  Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Free
                  Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Rate</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Trade
                  Discount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Cash
                  Discount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Taxable
                  Value</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Discount
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Tax
                  Group</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">CGST</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">SGST</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">IGST</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Invoice Tax
                  Total</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Invoice Grand
                  Total</span></th>

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

      $('#ReportTbl').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: {
          url: "{{ url('getsalesregister') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();

          }
        },
        columns: [
          { class: 'freeze', data: "invoice_number" },
          { data: "invoice_type" },
          { data: "invoice_date" },
          { data: "cusname" },
          { data: "gst_no" },
          { data: "prdname" },
          { data: "classification_code" },
          { data: "qty" },
          { data: "free_qty" },
          { data: "unit_price" },
          { data: "trade_amount" },
          { data: "cas_amount" },
          { data: "accessablevalu" },
          { data: "discount_amount" },
          { data: "tax_group_name" },
          { data: "cgst" },
          { data: "sgst" },
          { data: "igst" },
          { data: "invoice_tax_total" },
          { data: "invoice_grand_total" }
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