@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Attendance Report</h3>
  @include('layouts.breadcrumb')



  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white fw-semibold">
    </div>
    <div class="card-body p-4">
      <form method="post" action="" id="job_card_reprot" class="needs-validation" novalidate=""
        enctype="multipart/form-data" autocomplete="off">
        <input type="hidden" name="_token" value="7HWOhmpofEJTFq3PQrHB8KPTrsHTuTkNk2oGhoqK">
        <!-- First Row: Date Inputs -->
        <div class="row g-4 mb-3">
          <div class="col-md-2"></div>
          <div class="col-md-4">
            <label for="start_date" class="form-label fw-semibold">From Date</label>
            <input type="text" class="form-control start_date" id="start_date" name="start_date" required=""
              autocomplete="off">
            <div class="invalid-feedback">Please select a start date.</div>
          </div>

          <div class="col-md-4">
            <label for="end_date" class="form-label fw-semibold">To Date</label>
            <input type="text" class="form-control end_date" id="end_date" name="end_date" required="" autocomplete="off"
              disabled="">
            <div class="invalid-feedback">Please select an end date.</div>
          </div>
        </div>

        <!-- Second Row: Centered Search Button -->
        <div class="row">
          <div class="col-md-12 text-center">
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
              <th>Employee Number</th>
              <th>Employee Name</th>
              <th>Biometric Number</th>
              <th>Date</th>
              <th>Month</th>
              <th>Day</th>
              <th>First In</th>
              <th>Last Out</th>
              <th>Early In</th>
              <th>Late In</th>
              <th>Early Out</th>
              <th>Late Out</th>
              <th>Gross Hours</th>
              <th>Net Work Hours</th>
              <th>Loss Hours</th>
              <th>Status</th>
              <th>Category</th>
            </tr>
            <tr class="table-danger">

              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Employee
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Employee
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Biometric
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Date</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Month</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Day</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">First
                  In</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Last
                  Out</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Early
                  In</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Late
                  In</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Early
                  Out</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Late
                  Out</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Gross
                  Hours</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Net Work
                  Hours</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Loss
                  Hours</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Status</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Category</span></th>

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
        ajax: {
          url: "{{ url('getattendancereportdata') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [
          { data: 'emp_id' },
          { data: 'first_name' },
          { data: 'bio_id' },
          { data: 'atten_date' },
          { data: 'month' },
          { data: 'day' },
          { data: 'check_in' },
          { data: 'check_out' },
          { data: 'early_in' },
          { data: 'late_in' },
          { data: 'early_out' },
          { data: 'late_out' },
          { data: 'wrk_hrs' },
          { data: 'off_hrs' },
          { data: 'loss_hrs' },
          { data: 'status' },
          { data: 'dept' }

        ],

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