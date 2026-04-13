@extends('layouts.header')
@section('content')
  <h3 class="text-danger mb-4"> Customerwise Report</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-sm rounded-4 border-0">

    <div class="card-body p-4">
      <form id="filterForm">

        <div class="row g-4 align-items-end">

          <!-- From Date -->
          <div class="col-md-3">
            <label for="start_date" class="form-label fw-semibold">From Date</label>
            <input type="text" class="form-control start_date" id="start_date" name="start_date" required
              autocomplete="off">
          </div>

          <!-- To Date -->
          <div class="col-md-3">
            <label for="end_date" class="form-label fw-semibold">To Date</label>
            <input type="text" class="form-control end_date" id="end_date" name="end_date" required autocomplete="off">
          </div>

          <!-- Customer Name -->
          <div class="col-md-3 rtdis">
            <label for="cus_name" class="form-label fw-semibold">Customer Name</label>
            <select id="cus_name" name="cus_name" class="form-select select2 cus_name">
              {!! $cus_name !!}
            </select>
          </div>

          <!-- Employee Name -->
          <div class="col-md-3 ltdis">
            <label for="emp_name" class="form-label fw-semibold">Employee Name</label>
            <select id="emp_name" name="emp_name" class="form-select select2 emp_name">
              {!! $emp_name !!}
            </select>
          </div>

        </div>

        <!-- Search Button -->
        <div class="row mt-4">
          <div class="col text-center">
            <button type="button" class="btn bg-success bg-gradient text-white px-4 report_search" id="search">
              <i class="bi bi-search"></i> Search
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
              <th class="freeze">Customer Name</th>
              <th>State</th>
              <th>Order Type</th>
              <th>Sales Order Number</th>
              <th>Sales Order Date</th>
              <th>Order Status</th>
              <th>Order Amount</th>
              <th>Dispatch Number</th>
              <th>Dispatch Date</th>
              <th>Dispatch Status</th>
              <th>Invoice Number</th>
              <th>Invoice Date</th>
              <th>Invoice Amount</th>
              <th>Receipt Number</th>
              <th>Receipt Date</th>
              <th>Receipt Amount</th>
              <th>Balance Amount</th>
              <th>Invoice Balance Amount</th>
              <th>Receipt BRS Status</th>

            </tr>
            <tr class="table-danger">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Customer Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">State</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Order
                  Type</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Sales Order
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Sales Order
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Order
                  Status</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Order
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Dispatch
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Dispatch
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Dispatch
                  Status</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Invoice
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Invoice
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Invoice
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Receipt
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Receipt
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Receipt
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Balance
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Invoice
                  Balance Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Receipt BRS
                  Status</span></th>
            </tr>
          </thead>
          <tbody>
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
          url: "{{ url('customerwisereportdata') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
            d.emp_name = $('#emp_name').val();
            d.cus_name = $('#cus_name').val();
          }
        },
        columns: [
          { class: 'freeze', data: "customer_name" },
          { data: "state" },
          { data: "order_type_id" },
          { data: "sales_order_no" },
          { data: "sales_order_date" },
          { data: "order_status_id" },
          { data: "order_total" },
          { data: "dispatch_number" },
          { data: "dispatch_date" },
          { data: "dispatch_status" },
          { data: "invoice_number" },
          { data: "invoice_date" },
          { data: "invoice_grand_total" },
          { data: "receipt_number" },
          { data: "receipt_date" },
          { data: "receipt_amount" },
          { data: "balance_amount" },
          { data: "inv_bal_amount" },
          { data: "brs_receipt" }
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