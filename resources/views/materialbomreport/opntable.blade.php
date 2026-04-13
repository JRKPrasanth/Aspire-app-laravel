@extends('layouts.header')
@section('content')
  <h3 class="text-danger">
    Operation Report
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
              <th>Job No</th>
              <th>Job Created Date</th>
              <th>Product</th>
              <th>Plan Qty</th>
              <th>Shift</th>
              <th>Unit Pack</th>
              <th>Batch No</th>
              <th>Job Adjusted Qty</th>
              <th>Job Completed Qty</th>
              <th>Job Completion Date</th>
              <th>Process Level</th>
              <th>Process Name</th>
              <th>JC Open Emp Name</th>
              <th>M/c Hours based JC Qty</th>
              <th>JC Comp Emp Name</th>
              <th>Emp Hrs</th>
              <th>Emp Qty</th>
              <th>Dev Hrs</th>
              <th>M/c Name</th>
              <th>M/c Code</th>

            </tr>
            <tr class="table-info">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Plan No</span></th>
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
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Job Adjusted
                  Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Job Completed
                  Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Job Completion
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Process
                  Level</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Process
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">JC Open Emp
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">M/c Hours
                  based JC Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">JC Comp Emp
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Emp Hrs</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Emp Qty</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Dev Hrs</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">M/c
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">M/c
                  Code</span></th>


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
          url: "{{ url('operationreportdata') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [
          { class: 'freeze', data: "plan_no" },
          { data: "plan_date" },
          { data: "job_no" },
          { data: "job_date" },
          { data: "product_name" },
          { data: "production_qty" },
          { data: "shift" },
          { data: "pack_name" },
          { data: "batch_no" },
          { data: "job_adjusted_qty" },
          { data: "job_completed_qty" },
          { data: "job_completion_date" },
          { data: "bom_process" },
          { data: "job_process" },
          { data: "job_assigned_name" },
          { data: "machour" },
          { data: "qa_assigned_name" },
          { data: "working_hours" },
          { data: "empqty" },
          { data: "devhrs" },
          { data: "machine_name" },
          { data: "machine_code" }

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