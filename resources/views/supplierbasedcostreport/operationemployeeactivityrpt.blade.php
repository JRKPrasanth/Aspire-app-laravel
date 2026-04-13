@extends('layouts.header')
@section('content')

  <h3 class="text-danger mb-4"> Operation Employee Activity Report </h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg border-0 rounded-4 mb-4">
    <div class="card-body">
      <form method="post" action="" id="job_card_reprot" class="needs-validation" novalidate
        enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
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

        <div class="d-flex justify-content-center mt-4">
          <button type="button" class="btn bg-primary bg-gradient text-white px-4 report_search" id="report_search">
            <i class="bi bi-search-heart me-1"></i> Search
          </button>
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
              <th>Plan No</th>
              <th>Type</th>
              <th>Month Year</th>
              <th>Plan Date</th>
              <th>Job No</th>
              <th>Job Created Date</th>
              <th>Product</th>
              <th>Plan Qty</th>
              <th>Shift</th>
              <th>Unit Pack</th>
              <th>Batch No</th>
              <th>Job Qty</th>
              <th>Job Completion Date</th>
              <th>Process Level</th>
              <th>Process Name</th>
              <th>Process Date</th>
              <th>JC Open Emp Name</th>
              <th>M/c Hours based JC Qty</th>
              <th>JC Comp Emp Name</th>
              <th>Start Time</th>
              <th>End Time</th>
              <th>Emp Hrs</th>
              <th>Emp Qty</th>
              <th>Dev Hrs</th>
              <th>M/C Hrs</th>
              <th>M/c Name</th>
              <th>M/c Code</th>
              <th>Calibration Checked By</th>


            </tr>
            <tr class="table-danger">
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Plan No</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Type</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Month
                  Year</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Plan
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Job No</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Job Created
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Plan
                  Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Shift</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Unit
                  Pack</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Batch
                  No</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Job Qty</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Job Completion
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Process
                  Level</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Process
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Process
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">JC Open Emp
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">M/c Hours
                  based JC Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">JC Comp Emp
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Start
                  Time</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">End
                  Time</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Emp Hrs</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Emp Qty</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Dev Hrs</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">M/C Hrs</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">M/c
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">M/c
                  Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Calibration
                  Checked By</span></th>
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
          url: "{{ url('getoperationemployeeactivity') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [
          { data: "plan_no" },
          { data: "type" },
          { data: "yr_month" },
          { data: "plan_date" },
          { data: "job_no" },
          { data: "job_date" },
          { data: "product_name" },
          { data: "production_qty" },
          { data: "shift" },
          { data: "pack_name" },
          { data: "batch_no" },
          { data: "job_qty" },
          { data: "job_completion_date" },
          { data: "process_level" },
          { data: "process_name" },
          { data: "process_date" },
          { data: "job_assigned_name" },
          { data: "machour" },
          { data: "qa_assigned_name" },
          { data: "starttime" },
          { data: "endtime" },
          { data: "working_hours" },
          { data: "empqty" },
          { data: "devhrs" },
          { data: "machine_time" },
          { data: "machine_name" },
          { data: "machine_code" },
          { data: "calibration_checked" }
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