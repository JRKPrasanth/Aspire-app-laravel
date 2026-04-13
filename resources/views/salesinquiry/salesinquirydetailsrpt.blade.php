@extends('layouts.header')
@section('content')

  <h3 class="text-danger mb-4">Sales Inquiry Details Report</h3>
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
              <th class="freeze">Inquiry Number</th>
              <th>Inquiry Date</th>
              <th>Inquiry Type</th>
              <th>Reference Number</th>
              <th>Source</th>
              <th>Remarks</th>
              <th>Inquiry Status</th>
              <th>Customer Name</th>
              <th>Product Name</th>
              <th>UOM</th>
              <th>Product Description</th>
              <th>Required Qty</th>
              <th>Need By Date</th>
              <th>Title Of Work</th>

            </tr>
            <tr class="table-info">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Inquiry Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Inquiry
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Inquiry
                  Type</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Reference
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Source</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Remarks</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Inquiry
                  Status</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Customer
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">UOM</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Description</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Required
                  Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Need By
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Title Of
                  Work</span></th>

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
          url: "{{ url('getsalesinquirydetails') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();

          }
        },
        columns: [
          { class: 'freeze', data: "inquiry_no" },
          { data: "inquiry_date" },
          { data: "inquiry_type" },
          { data: "reference_number" },
          { data: "source" },
          { data: "remarks" },
          { data: "inquiry_status" },
          { data: "customer_name" },
          { data: "concatenated_product" },
          { data: "uom_code" },
          { data: "product_description" },
          { data: "required_qty" },
          { data: "need_by_date" },
          { data: "tittle_of_work" }

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