@extends('layouts.header')
@section('content')

  <h3 class="text-danger mb-4">Transport Track Report</h3>
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
              <th class="freeze">Invoice Number</th>
              <th>Invoice Date</th>
              <th>Invoice Type</th>
              <th>Invoice Status</th>
              <th>Customer Name</th>
              <th>Invoice Amount</th>
              <th>No. Of Box</th>
              <th>Pack Weight (In KGs)</th>
              <th>Dispatch Number</th>
              <th>Delivery Date</th>
              <th>Delivery State</th>
              <th>Transport Name</th>
              <th>LR Number</th>
              <th>LR Date</th>
              <th>LR Status</th>

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
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Customer
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Invoice
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">No. Of
                  Box</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Pack Weight
                  (In KGs)</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Dispatch
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Delivery
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Delivery
                  State</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Transport
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">LR
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">LR Date</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">LR
                  Status</span></th>
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
        order: [[1, 'desc']],
        ajax: {
          url: "{{ url('gettransporttrack') }}",
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
          { data: "customer_name" },
          { data: "invoice_grand_total" },
          { data: "packaging_qty" },
          { data: "pack_weight" },
          { data: "dispatch_number" },
          { data: "delivery_date" },
          { data: "state_name" },
          { data: "carrier_name" },
          { data: "lr_no" },
          { data: "lr_date" },
          { data: "lr_status" }
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