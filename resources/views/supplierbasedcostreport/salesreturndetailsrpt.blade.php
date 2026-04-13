@extends('layouts.header')
@section('content')

  <h3 class="text-danger mb-4">Sales Return Details Report</h3>
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
              <th class="freeze">Return Date</th>
              <th>Return Month</th>
              <th>Customer Name</th>
              <th>SO Return Number</th>
              <th>Product Name</th>
              <th>Batch Number</th>
              <th>Mfg Date</th>
              <th>Expiry Date</th>
              <th>Return Qty</th>
              <th>Returned Qty</th>
              <th>Invoice Qty</th>
              <th>Rate</th>
              <th>Buy Discount</th>
              <th>Discount Amount</th>
              <th>Total Amount</th>
              <th>Taxable Amount</th>
              <th>Tax Amount</th>
              <th>Grand Total</th>
              <th>Tax Group</th>
              <th>CGST</th>
              <th>SGST</th>
              <th>IGST</th>
              <th>HSN Code</th>
              <th>Return Status</th>
              <th>Return Source</th>
              <th>Reference Number</th>
              <th>Paid Amount</th>
              <th>Remarks</th>
              <th>Created Date</th>

            </tr>
            <tr class="table-info">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Return Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Return
                  Month</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Customer
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">SO Return
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Batch
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Mfg
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Expiry
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Return
                  Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Returned
                  Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Invoice
                  Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Rate</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Buy
                  Discount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Discount
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Total
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Taxable
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Tax
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Grand
                  Total</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Tax
                  Group</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">CGST</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">SGST</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">IGST</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">HSN
                  Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Return
                  Status</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Return
                  Source</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Reference
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Paid
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Remarks</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Created
                  Date</span></th>

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
          url: "{{ url('getsalesreturndetails') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();

          }
        },
        columns: [
          { class: 'freeze', data: "return_date" },
          { data: "return_month" },
          { data: "customer_name" },
          { data: "rma_ref_no" },
          { data: "concatenated_product" },
          { data: "batch_number" },
          { data: "manufracture_date" },
          { data: "expiry_date" },
          { data: "return_qty" },
          { data: "returnqty" },
          { data: "invoice_qty" },
          { data: "rate" },
          { data: "buy_discount" },
          { data: "discount_amount" },
          { data: "total_amount" },
          { data: "taxable_amount" },
          { data: "tax_amount" },
          { data: "grand_total" },
          { data: "tax_group_name" },
          { data: "cgst" },
          { data: "sgst" },
          { data: "igst" },
          { data: "classification_code" },
          { data: "return_status" },
          { data: "return_source" },
          { data: "reference_no" },
          { data: "paid_amount" },
          { data: "remarks" },
          { data: "created_at" }
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