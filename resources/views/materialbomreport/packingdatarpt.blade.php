@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Packing Details Report</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body p-4">
      <form method="post" action="" id="job_card_reprot" class="needs-validation" novalidate
        enctype="multipart/form-data">
        @csrf
        <div class="row g-4 align-items-end">
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

          <div class="col-md-2 d-grid">
            <button type="button" class="btn btn-primary report_search" id="report_search">
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
              <th class="freeze">Job No</th>
              <th>Job Date</th>
              <th>Job Completion Date</th>
              <th>Job Status</th>
              <th>Batch Number</th>
              <th>Reference Number</th>
              <th>Product Name</th>
              <th>Uom Code</th>
              <th>Job Qty</th>
              <th>Job Adjusted Qty</th>
              <th>Bom Product</th>
              <th>Machine Name</th>
              <th>Machine Capacity</th>
              <th>Hour</th>
              <th>Process Level</th>
              <th>Process Name</th>
              <th>QA Reference Number</th>
              <th>QA Status</th>
              <th>Production Qty</th>

            </tr>
            <tr class="table-info">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Job No</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Job
                  Date</span></th>
              <th><input type="text" class="column-search start_date" placeholder="Search"><span style="display:none;">Job
                  Completion Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Job
                  Status</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Batch
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Reference
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Uom
                  Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Job Qty</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Job Adjusted
                  Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Bom
                  Product</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Machine
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Machine
                  Capacity</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Hour</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Process
                  Level</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Process
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">QA Reference
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">QA
                  Status</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Production
                  Qty</span></th>
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

      var table = $('#ReportTbl').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: {
          url: "{{ url('getpackingdatasrpt') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [
          { class: 'freeze', data: "job_no" },
          { data: "job_date" },
          { data: "job_completion_date" },
          { data: "job_status" },
          { data: "batch_no" },
          { data: "plan_no" },
          { data: "prdname" },
          { data: "uom_code" },
          { data: "job_qty" },
          { data: "job_adjusted_qty" },
          { data: "bomproduct" },
          { data: "macname" },
          { data: "machine_capacity" },
          { data: "hour" },
          { data: "bom_process" },
          { data: "job_process" },
          { data: "reference_no" },
          { data: "qa_status" },
          { data: "production_qty" }


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