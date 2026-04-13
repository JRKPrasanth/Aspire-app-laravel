@extends('layouts.header')
@section('content')
  <h3 class="text-danger">SO Order Details Report</h3>
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
              <th class="freeze">SO Number</th>
              <th>So Date</th>
              <th>Code</th>
              <th>Customer</th>
              <th>Zone</th>
              <th>EMP_Zone</th>
              <th>State</th>
              <th>City</th>
              <th>Product Name</th>
              <th>Uom Code</th>
              <th>Qty</th>
              <th>Dispatched Qty</th>
              <th>Invoiced Qty</th>
              <th>Remaining Qty</th>
              <th>Pending Qty</th>
              <th>Free Qty</th>
              <th>Unit Price</th>
              <th>Discount Percentage</th>
              <th>Discount Amount</th>
              <th>HSN Code</th>
              <th>Tax Group</th>
              <th>Tax Amount</th>
              <th>Assessable Value</th>
              <th>Taxable Value</th>
              <th>Total Amount</th>
              <th>Delivery Date</th>
              <th>Comments</th>
              <th>Customer Po Number</th>
              <th>Pricelist</th>
              <th>SO Status</th>
              <th>Source</th>
              <th>Reference Number</th>
              <th>SO Tax Total</th>
              <th>SO Grand Total</th>
              <th>Advance Amount</th>
              <th>Balance Amount</th>

            </tr>
            <tr class="table-info">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">SO Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">So Date</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Code</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Customer</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Zone</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">EMP_Zone</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">State</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">City</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Uom
                  Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Qty</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Dispatched
                  Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Invoiced
                  Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Remaining
                  Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Pending
                  Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Free
                  Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Unit
                  Price</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Discount
                  Percentage</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Discount
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">HSN
                  Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Tax
                  Group</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Tax
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Assessable
                  Value</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Taxable
                  Value</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Total
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Delivery
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Comments</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Customer Po
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Pricelist</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">SO
                  Status</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Source</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Reference
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">SO Tax
                  Total</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">SO Grand
                  Total</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Advance
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Balance
                  Amount</span></th>


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
          url: "{{ url('getsodatasrpt') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();

          }
        },
        columns: [
          { class: 'freeze', data: "sales_order_no" },
          { data: "sales_order_date" },
          { data: "cuscode" },
          { data: "cusname" },
          { data: "cus_type" },
          { data: "department_name" },
          { data: "state" },
          { data: "city" },
          { data: "prdname" },
          { data: "uom_code" },
          { data: "qty" },
          { data: "dispatched_qty" },
          { data: "invoiced_qty" },
          { data: "remaining_qty" },
          { data: "pending_qty" },
          { data: "free_qty" },
          { data: "unit_price" },
          { data: "discount_percentage" },
          { data: "discount_amount" },
          { data: "classification_code" },
          { data: "tax_group_name" },
          { data: "tax_amount" },
          { data: "assessablevalue" },
          { data: "accessable_value" },
          { data: "line_total" },
          { data: "delivery_date" },
          { data: "comments" },
          { data: "customer_po_number" },
          { data: "pricelist_name" },
          { data: "order_status_id" },
          { data: "source" },
          { data: "reference_number" },
          { data: "order_tax" },
          { data: "order_total" },
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