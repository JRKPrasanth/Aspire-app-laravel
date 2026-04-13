@extends('layouts.header')
@section('content')
  <h3 class="text-danger"> Leave Report For Overall Leave Request</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg border-0 rounded-4 mb-4">
    <div class="card-header bg-primary bg-gradient text-white fw-semibold">
    </div>
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
              <th class="freeze">Employee Name</th>
              <th>Reporting Name</th>
              <th>Leave Type</th>
              <th>Start Date</th>
              <th>End Date</th>
              <th>Leave Submmision date</th>
              <th>Leave Reason</th>
              <th>No Of Days</th>
              <th>Alloted Days</th>
              <th>Approve Reason</th>
              <th>Approve Comments</th>
              <th>Approve Status</th>


            </tr>
            <tr class="table-danger">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Employee Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Reporting
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Leave
                  Type</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Start
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">End
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Leave
                  Submmision date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Leave
                  Reason</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">No Of
                  Days</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Alloted
                  Days</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Approve
                  Reason</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Approve
                  Comments</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Approve
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

      var table = $('#ReportTbl').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        ajax: {
          url: "{{ url('getleaveoverallgrid') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [
          { data: 'employee_name', name: 'employee_name' },
          { data: 'reporting_name', name: 'reporting_name' },
          { data: 'leave_type', name: 'leave_type' },
          { data: 'start_date', name: 'start_date' },
          { data: 'end_date', name: 'end_date' },
          { data: 'created_at', name: 'created_at' },
          { data: 'leave_reason', name: 'leave_reason' },
          { data: 'no_of_days', name: 'no_of_days' },
          { data: 'alloted_days', name: 'alloted_days' },
          { data: 'approval_reason', name: 'approval_reason' },
          { data: 'approvel_comments', name: 'approvel_comments' },
          { data: 'leave_status', name: 'leave_status' },

        ]
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

  </script>
@endpush