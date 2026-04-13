@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Sales Order Details</h3>
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
              <th class="freeze">Sales Order Number</th>
              <th>SO Date</th>
              <th>Status</th>
              <th>order Type</th>
              <th>TCS Applicable</th>
              <th>Customer Name</th>
              <th>Zone</th>
              <th>EMP_Zone</th>
              <th>State</th>
              <th>City</th>
              <th>Conversion Exchange Rate</th>
              <th>Assessable Value</th>
              <th>Taxable Value</th>
              <th>Tax Amount</th>
              <th>Amount</th>
            </tr>
            <tr class="table-info">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Sales Order Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">SO Date</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Status</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">order
                  Type</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">TCS
                  Applicable</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Customer
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Zone</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">EMP_Zone</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">State</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">City</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Conversion
                  Exchange Rate</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Assessable
                  Value</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Taxable
                  Value</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Tax
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Amount</span>
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

      $('#ReportTbl').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: {
          url: "{{ url('getsodetailsData') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
            d.account_code_line = $('#account_line_id').val();
          }
        },
        columns: [
          { class: 'freeze', data: "sales_order_no" },
          { data: "sales_order_date" },
          { data: "order_status_id" },
          { data: "order_type_id" },
          { data: "tcs_applicable" },
          { data: "ship_to_customer_id" },
          { data: "cus_type" },
          { data: "department_name" },
          { data: "state" },
          { data: "city" },
          { data: "con_exc_rate" },
          { data: "acsessable_value" },
          { data: "assval" },
          { data: "order_tax" },
          { data: "order_total" }

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