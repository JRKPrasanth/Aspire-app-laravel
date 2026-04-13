@extends('layouts.header')
@section('content')
  <h3 class="text-danger">
    Jobcard Status Detail Report
  </h3>
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
              <th class="freeze">Plan No</th>
              <th>Plan Date</th>
              <th>Product Code</th>
              <th>Product Name</th>
              <th>Start Date</th>
              <th>End Date</th>
              <th>Planned Qty</th>
              <th>Job No</th>
              <th>Batch No</th>
              <th>Job date</th>
              <th>Job Adjusted Qty</th>
              <th>Job Qty</th>
              <th>Previous Moved Qty</th>
              <th>Moved Qty</th>
              <th>Job Balance Qty</th>
              <th>Process</th>
              <th>JC start date</th>
              <th>JC End date</th>
              <th>Duration</th>
              <th>Job Status</th>


            </tr>
            <tr class="table-info">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Plan No</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Plan
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Start
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">End
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Planned
                  Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Job No</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Batch
                  No</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Job
                  date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Job Adjusted
                  Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Job Qty</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Previous Moved
                  Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Moved
                  Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Job Balance
                  Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Process</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">JC start
                  date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">JC End
                  date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Duration</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Job
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

      var table = $('#ReportTbl').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: {
          url: "{{ url('getjobcardstatusdetaileddata') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [
          { class: 'freeze', data: "plan_no" },
          { data: "plan_date" },
          { data: "product_code" },
          { data: "product_name" },
          { data: "job_date" },
          { data: "job_completion_date" },
          { data: "plan_qty" },
          { data: "job_no" },
          { data: "batch_no" },
          { data: "job_date" },
          { data: "job_adjusted_qty" },
          { data: "job_qty" },
          { data: "moved_qty" },
          { data: "store_move_qty" },
          { data: "jobbalqty" },
          { data: "process_name" },
          { data: "process_start_date" },
          { data: "process_end_date" },
          { data: "duration" },
          { data: "status" }


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