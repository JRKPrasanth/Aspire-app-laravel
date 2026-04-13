@extends('layouts.header')
@section('content')

  <h3 class="text-danger mb-4"> Samples Order Template</h3>
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
              <th class="freeze">Order No</th>
              <th>Order Date</th>
              <th>Approved By</th>
              <th>Month</th>
              <th>Invoice Number</th>
              <th>Employee Name</th>
              <th>Thru</th>
              <th>Doc No</th>
              <th>Dispatch Date</th>
              <th>Boxes</th>
              <th>D/UD</th>
              <th>Weight</th>
              <th>TAT days Order_Invoice</th>
              <th>TAT days Invoice_Dispatch</th>
              <th>TAT days Dispatch_Delivery</th>

            </tr>
            <tr class="table-info">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Order No</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Order
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Approved
                  By</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Month</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Invoice
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Employee
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Thru</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Doc No</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Dispatch
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Boxes</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">D/UD</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Weight</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">TAT days
                  Order_Invoice</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">TAT days
                  Invoice_Dispatch</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">TAT days
                  Dispatch_Delivery</span></th>


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
          url: "{{ url('getsamplesorder') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();

          }
        },
        columns: [
          { class: 'freeze', data: "sales_order_no" },
          { data: "sales_order_date" },
          { data: "approved_by" },
          { data: "month", title: "Month" },
          { data: "invoice_number" },
          { data: "customer_name" },
          { data: "carrier_name" },
          { data: "lr_no" },
          { data: "dispatch_date" },
          { data: "packaging_qty" },
          { data: "delivery_date" },
          { data: "pack_weight" },
          { data: "TAT_days_Ord_Inv" },
          { data: "TAT_days_Inv_Disp" },
          { data: "TAT_days_Disp_Dlvy" }

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