@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Breakdown Maintenance Report</h3>
  @include('layouts.breadcrumb')


  <div class="card">
    <div class="card-body">
      <form method="post" action="" id="job_card_reprot" class="break_form" data-parsley-validate
        enctype="multipart/form-data">
        @csrf

        <div class="row mb-4">
          <div class="col-md-4">
            <div class="row mb-3 align-items-center">
              <label for="start_date" class="col-sm-4 col-form-label">From Date</label>
              <div class="col-sm-8">
                <input type="text" class="form-control start_date" id="start_date" name="start_date" required
                  autocomplete="off" required>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="row mb-3 align-items-center">
              <label for="end_date" class="col-sm-4 col-form-label">To Date</label>
              <div class="col-sm-8">
                <input type="text" class="form-control end_date" id="end_date" name="end_date" required autocomplete="off"
                  required>
              </div>
            </div>
          </div>



          <div class="col-md-4">
            <button type="button" class="btn btn-primary report_search" id="report_search" value="SAVE"><i
                class="bi bi-search"></i> Search</button>
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
              <th>Machine Code</th>
              <th>Machine Name</th>
              <th>Breakdown Name</th>
              <th>Ticket No</th>
              <th>Issue Date</th>
              <th>Severity Name</th>
              <th>Causes</th>
              <th>Department Name</th>
              <th>Corrective Action</th>
              <th>Preventive Action</th>
              <th>Request Remarks</th>
              <th>Approve Remarks</th>
              <th>Error Code</th>
              <th>Repair Start Date</th>
              <th>Repair End Date</th>
              <th>Rectified on</th>
              <th>Closed On</th>
              <th>Files</th>
              <th>Issue Raised By</th>
              <th>Status</th>
            </tr>
            <tr class="table-success">
              <th><input type="text" placeholder="Search" /><span style="display: none;">Machine Code</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Machine Name</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Breakdown Name</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Ticket No</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Issue Date</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Severity Name</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Causes</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Department Name</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Corrective Action</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Preventive Action</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Request Remarks</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Approve Remarks</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Error Code</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Repair Start Date</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Repair End Date</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Rectified on</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Closed On</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Files</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Issue Raised By</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Status</span></th>
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
          url: "{{ url('databreakdownmaintenancereportget') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [
          { data: 'machine_code' },
          { data: 'machine_name' },
          { data: 'breakdown_name' },
          { data: 'ticket_number' },
          { data: 'issue_date' },
          { data: 'severity_name' },
          { data: 'causes' },
          { data: 'department_name' },
          { data: 'corrective_action' },
          { data: 'preventive_action' },
          { data: 'request_remark' },
          { data: 'approve_remarks' },
          { data: 'error_code' },
          { data: 'start_date' },
          { data: 'end_date' },
          { data: 'request_request_on' },
          { data: 'closed_engineer_on' },
          { data: 'files' },
          { data: 'first_name' },
          { data: 'request_status' }
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