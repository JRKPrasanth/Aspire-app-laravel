@extends('layouts.header')
@section('content')
<h3 class="text-danger">Leave Report</h3>
@include('layouts.breadcrumb')


  <div class="card shadow-lg border-0 rounded-4 mb-4">
    <div class="card-header bg-primary bg-gradient text-white fw-semibold">
      <i class="bi bi-funnel me-2"></i>Filter Options
    </div>
    <div class="card-body">
      <form method="post" action="" id="job_card_reprot" class="needs-validation" novalidate
        enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
          <div class="col-md-2"></div>
          <div class="col-md-4">
            <label for="start_date" class="form-label fw-semibold">From Date</label>
            <input type="text" class="form-control start_date_report" id="start_date" name="start_date" required
              autocomplete="off">
            <div class="invalid-feedback">Please select a start date.</div>
          </div>
          <div class="col-md-4">
            <label for="end_date" class="form-label fw-semibold">To Date</label>
            <input type="text" class="form-control end_date_report" id="end_date" name="end_date" required autocomplete="off">
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
              <th></th>
              <th>Employee Name</th>
              <th>Reporting Name</th>
              <th>Department</th>
              <th class="freeze">Leave Type</th>
              <th>Start Date</th>
              <th>End Date</th>
              <th>Start Date Time</th>
              <th>End Date Time</th>
              <th>Leave Submmision date</th>
              <th>Leave Reason</th>
              <th>No Of Days</th>
              <th>Alloted Days</th>
              <th>No of Hrs</th>
              <th>Alloted Hrs</th>
              <th>OD start date</th>
              <th>OD end date</th>
              <th>Od No of days</th>
              <th>od alloted days</th>
              <th>Approve Reason</th>
              <th>Approve Comments</th>
              <th>Approve Status</th>
            </tr>
            <tr class="table-danger">
              <th></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Employee
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Reporting
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Department</span></th>
              <th class="freeze"><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Leave Type</span></th>
              <th><input type="text" class="column-search start_date" placeholder="Search" /><span
                  style="display: none;">Start Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">End
                  Date</span></th>
              <th><input type="text" class="column-search start_date" placeholder="Search" /><span
                  style="display: none;">Start Date Time</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">End Date
                  Time</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Leave
                  Submmision date</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Leave
                  Reason</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">No Of
                  Days</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Alloted
                  Days</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">No of
                  Hrs</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Alloted
                  Hrs</span></th>
              <th><input type="text" class="column-search start_date" placeholder="Search" /><span
                  style="display: none;">OD start date</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">OD end
                  date</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Od No of
                  days</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">od alloted
                  days</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Approve
                  Reason</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Approve
                  Comments</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Approve
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
        order: [[0, 'desc']],
        ajax: {
          url: "{{ url('getleavegrid') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [
          { data: 'leave_id', visible:false },
          { data: 'employee_name' },
          { data: 'reporting_name' },
          { data: 'department' },
          { class: 'freeze', data: 'leave_type' },
          { data: 'start_date' },
          { data: 'end_date' },
          { data: 'start_date_time' },
          { data: 'end_date_time' },
          { data: 'created_at' },
          { data: 'leave_reason' },
          { data: 'no_of_days' },
          { data: 'alloted_days' },
          { data: 'no_of_hrs' },
          { data: 'alloted_hrs' },
          { data: 'od_start_date' },
          { data: 'od_end_date' },
          { data: 'od_no_of_days' },
          { data: 'od_alloted_days' },
          { data: 'approval_reason' },
          { data: 'approvel_comments' },
          { data: 'leave_status' }
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
        },
      });

      // Trigger search
      $('.report_search').on('click', function () {
        $('#ReportTbl').DataTable().ajax.reload();
      });


      // Column search
      $('#ReportTbl thead').on('keyup change', ".column-search", function () {
        var index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });

        $(document).ready(function () {
      const dateFormat = "{{ \Session('j_date_format') ?? 'yy-mm-dd' }}";

      // Initially disable end_date
      $(".end_date_report").prop("disabled", true).val("");

      $(document).on("focus", ".start_date_report", function () {
        $(this).datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: dateFormat,
          minDate: new Date(2024, 3, 1),
          showAnim: "slideDown",
          yearRange: "-25:+0",
          onClose: function (selectedDate) {
            if (selectedDate) {
              const startDate = $(this).datetimepicker("getDate");

              // Enable end_date
              $(".end_date_report").prop("disabled", false);

              // Reinitialize end_date with updated minDate
              $(".end_date_report").datepicker("destroy").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: dateFormat,
                minDate: startDate, // Disallow dates before start time
                showAnim: "slideDown",
                yearRange: "-25:+0"
              });
            }
          }
        });
      });
    });

  </script>
@endpush