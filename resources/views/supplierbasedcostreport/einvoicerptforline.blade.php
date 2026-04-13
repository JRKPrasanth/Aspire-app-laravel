@extends('layouts.header')
@section('content')

  <h3 class="text-danger mb-4">E-Invoice Report for Line Items</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg border-0 rounded-4 mb-4">

    <div class="card-body">
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
              <th class="freeze">Document Type</th>
              <th>Document Number</th>
              <th>Document Date (DD/MM/YYYY)</th>
              <th>SI.No.</th>
              <th>Product Description</th>
              <th>HSN code</th>
              <th>Quantity</th>
              <th>Free Quantity</th>
              <th>Unit</th>
              <th>Unit Price</th>
              <th>Gross Amount</th>
              <th>Discount</th>
              <th>Pre Tax Value</th>
              <th>Taxable value</th>
              <th>GST Rate (%)</th>
              <th>Sgst Amt(Rs)</th>
              <th>Cgst Amt(Rs)</th>
              <th>Igst Amt(Rs)</th>
              <th>Cess Rate(%)</th>
              <th>Cess Amt Adval (Rs)</th>
              <th>Cess Non Adval Amt (Rs)</th>
              <th>State Cess Rate (%)</th>
              <th>State Cess Adval Amt (Rs)</th>
              <th>State Cess Non-Adval Amt (Rs)</th>
              <th>Other Charges</th>
              <th>Item Total</th>
              <th>Batch Name</th>
              <th>Batch Expiry Dt</th>
              <th>Warranty Dt</th>
              <th>Error List</th>

            </tr>
            <tr class="table-info">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Document Type</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Document
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Document Date
                  (DD/MM/YYYY)</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">SI.No.</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Description</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">HSN
                  code</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Quantity</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Free
                  Quantity</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Unit</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Unit
                  Price</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Gross
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Discount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Pre Tax
                  Value</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Taxable
                  value</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">GST Rate
                  (%)</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Sgst
                  Amt(Rs)</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Cgst
                  Amt(Rs)</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Igst
                  Amt(Rs)</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Cess
                  Rate(%)</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Cess Amt Adval
                  (Rs)</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Cess Non Adval
                  Amt (Rs)</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">State Cess
                  Rate (%)</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">State Cess
                  Adval Amt (Rs)</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">State Cess
                  Non-Adval Amt (Rs)</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Other
                  Charges</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Item
                  Total</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Batch
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Batch Expiry
                  Dt</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Warranty
                  Dt</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Error
                  List</span></th>

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
          url: "{{ url('geteinvoicerptforline') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();

          }
        },
        columns: [
          { class: 'freeze', data: "doc_type" },
          { data: "doc_number" },
          { data: "doc_date" },
          { data: "s_no" },
          { data: "prd_desc" },
          { data: "hsn_cde" },
          { data: "quantity" },
          { data: "free_qty" },
          { data: "unit" },
          { data: "unit_price" },
          { data: "gross_amt" },
          { data: "discount" },
          { data: "pre_tax_value" },
          { data: "taxable_value" },
          { data: "gst_rate" },
          { data: "sgst" },
          { data: "cgst" },
          { data: "igst" },
          { data: "cess_rate" },
          { data: "cess_amt_adval" },
          { data: "cess_non_adval_amt" },
          { data: "state_cess_rate" },
          { data: "state_cess_adval_amt" },
          { data: "state_cess_non_adval_amt" },
          { data: "other_charges" },
          { data: "item_total" },
          { data: "batch_name" },
          { data: "batch_expiry_dt" },
          { data: "warranty_dt" },
          { data: "error_list" }

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