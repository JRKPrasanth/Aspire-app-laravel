@extends('layouts.header')
@section('content')

  <h3 class="text-danger mb-4">E-Invoice Report for Header</h3>
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
              <th class="freeze">Supply Type Code</th>
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
              <th>Buyer Phone Number</th>
              <th>Buyer Email Id</th>
              <th>Shipping GSTIN</th>
              <th>Shipping Legal Name</th>
              <th>Shipping Trade Name</th>
              <th>Shipping Addr1</th>
              <th>Shipping Addr2</th>
              <th>Shipping Location</th>
              <th>Shipping Pin Code</th>
              <th>Shipping State</th>
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
              <th>Export Duty Amount</th>
              <th>Error List</th>

            </tr>
            <tr class="table-info">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Supply Type Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Igst On
                  Intra</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Document
                  Type</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Document
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Document Date (DD/MM/YYYY)</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Buyer
                  GSTIN</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Buyer Legal
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Buyer Trade
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Buyer
                  POS</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Buyer
                  Addr1</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Buyer
                  Addr2</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Buyer
                  Location</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Buyer Pin
                  Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Buyer
                  State</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Buyer Phone
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Buyer Email
                  Id</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Shipping GSTIN</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Shipping Legal Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Shipping Trade Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Shipping Addr1</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Shipping Addr2</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Shipping Location</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Shipping Pin Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Shipping State</span></th>    
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Total Taxable
                  value</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Sgst Amt</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Cgst Amt</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Igst Amt</span></th>  
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Cess
                  Amt</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">State Cess
                  Amt</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Discount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Other
                  charges</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Round
                  off</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Total Invoice
                  value</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Export Duty
                  Amount</span></th>
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
          url: "{{ url('geteinvoicerptforhdr') }}",
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
          { data: "buyer_ph_number" },
          { data: "buyer_email" },  
          { data: "ship_gst" },
          { data: "ship_legal_name" },
          { data: "ship_trade_name" },
          { data: "ship_addr1" },
          { data: "ship_addr2" },
          { data: "shipp_city" },
          { data: "ship_pincode" },
          { data: "shipp_state" },
          { data: "tot_tax_value" },
          { data: "sgst" },
          { data: "cgst" },
          { data: "igst_amt" },
          { data: "cess_amt" },
          { data: "state_cess_amt" },
          { data: "discount" },
          { data: "other_charges" },
          { data: "round_off" },
          { data: "tot_inv_value" },
          { data: "exp_duty_amt" },
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