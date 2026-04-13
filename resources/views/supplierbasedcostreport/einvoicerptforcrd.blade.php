@extends('layouts.header')
@section('content')

  <h3 class="text-danger mb-4"> E-Invoice Report For Credit Note</h3>
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
              <th class="freeze">Supply Type code</th>
              <th>Igst On Intra</th>
              <th>Document Type</th>
              <th>Document Number</th>
              <th>Document Date (DD/MM/YYYY)</th>
              <th>Buyer GSTIN</th>
              <th>Buyer Legal Name</th>
              <th>Buyer Trade Name</th>
              <th>Buyer POS</th>
              <th>Buyer Addr1</th>
              <th>Buyer Addr2</th>
              <th>Buyer Location</th>
              <th>Buyer Pin Code</th>
              <th>Buyer State</th>
              <th>SI.NO.</th>
              <th>Product Description</th>
              <th>HSN code</th>
              <th>Quantity</th>
              <th>Unit</th>
              <th>Unit Price</th>
              <th>Gross Amount</th>
              <th>Discount</th>
              <th>Pre Tax Value</th>
              <th>GST Rate (%)</th>
              <th>Sgst Amt(Rs)</th>
              <th>Cgst Amt (Rs)</th>
              <th>Igst Amt (Rs)</th>
              <th>Cess Rate (%)</th>
              <th>Cess Amt Adval (Rs)</th>
              <th>Cess Non Adval Amt (Rs)</th>
              <th>State Cess Rate (%)</th>
              <th>State Cess Adval Amt (Rs)</th>
              <th>State Cess Non-Adval Amt (Rs)</th>
              <th>Other Charges</th>
              <th>Item Total</th>
              <th>Total Taxable value</th>
              <th>Sgst Amt</th>
              <th>Cgst Amt</th>
              <th>Igst Amt</th>
              <th>Cess Amt</th>
              <th>State Cess Amt</th>
              <th>Discount</th>
              <th>Other charges</th>
              <th>Round off</th>
              <th>Total Invoice value</th>
              <th>Error List</th>

            </tr>
            <tr class="table-info">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Supply Type code</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Igst On Intra</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Document Type</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Document Number</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Document Date (DD/MM/YYYY)</span>
              </th>
              <th><input type="text" class="column-search"><span style="display:none;">Buyer GSTIN</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Buyer Legal Name</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Buyer Trade Name</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Buyer POS</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Buyer Addr1</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Buyer Addr2</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Buyer Location</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Buyer Pin Code</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Buyer State</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">SI.NO.</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Product Description</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">HSN code</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Quantity</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Unit</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Unit Price</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Gross Amount</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Discount</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Pre Tax Value</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">GST Rate (%)</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Sgst Amt(Rs)</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Cgst Amt (Rs)</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Igst Amt (Rs)</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Cess Rate (%)</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Cess Amt Adval (Rs)</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Cess Non Adval Amt (Rs)</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">State Cess Rate (%)</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">State Cess Adval Amt (Rs)</span>
              </th>
              <th><input type="text" class="column-search"><span style="display:none;">State Cess Non-Adval Amt
                  (Rs)</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Other Charges</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Item Total</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Total Taxable value</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Sgst Amt</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Cgst Amt</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Igst Amt</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Cess Amt</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">State Cess Amt</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Discount</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Other charges</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Round off</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Total Invoice value</span></th>
              <th><input type="text" class="column-search"><span style="display:none;">Error List</span></th>

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
          url: "{{ url('geteinvoicerptforcrd') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();

          }
        },
        columns: [
          { class: 'freeze', data: "supplier_type_code" },
          { data: "igst_on_intra" },
          { data: "doc_type" },
          { data: "doc_number" },
          { data: "doc_date" },
          { data: "buyer_gst" },
          { data: "buyer_legal_name" },
          { data: "buyer_trade_name" },
          { data: "buyer_pos" },
          { data: "buyer_addr1" },
          { data: "buyer_addr2" },
          { data: "buyer_location" },
          { data: "buyer_pincode" },
          { data: "buyer_state" },
          { data: "s_no" },
          { data: "prd_desc" },
          { data: "hsn_cde" },
          { data: "quantity" },
          { data: "unit" },
          { data: "unit_price" },
          { data: "gross_amt" },
          { data: "discount" },
          { data: "pre_tax_value" },
          { data: "taxable_value" },
          { data: "gst_rate" },
          { data: "sgst" },
          { data: "cgst" },
          { data: "igst_amt" },
          { data: "cess_rate" },
          { data: "cess_amt_adval" },
          { data: "cess_non_adval_amt" },
          { data: "state_cess_rate" },
          { data: "state_cess_adval_amt" },
          { data: "state_cess_non_adval_amt" },
          { data: "other_charges" },
          { data: "item_total" },
          { data: "tot_sgst_amt" },
          { data: "tot_cgst_amt" },
          { data: "tot_igst_amt" },
          { data: "tot_cess_amt" },
          { data: "tot_state_cess_amt" },
          { data: "tot_discount" },
          { data: "tot_other_charges" },
          { data: "round_off" },
          { data: "tot_inv_value" },
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