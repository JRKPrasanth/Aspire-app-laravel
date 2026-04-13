@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Preventive Maintenance Report</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0 p-4">
    <div class="row g-4 align-items-end">
      <div class="col-md-4">
        <div class="form-group">
          <label for="start_date" class="form-label">From Date</label>
          <input type="text" class="form-control start_date" id="start_date" name="start_date" required autocomplete="off"
            placeholder="YYYY-MM-DD">
        </div>
      </div>

      <div class="col-md-4">
        <div class="form-group">
          <label for="end_date" class="form-label">To Date</label>
          <input type="text" class="form-control end_date" id="end_date" name="end_date" required autocomplete="off"
            placeholder="YYYY-MM-DD">
        </div>
      </div>

      <div class="col-md-2 text-start mt-md-0 mt-3">
        <button type="button" class="btn btn-primary report_search px-4" id="report_search" value="SAVE">Search</button>
      </div>
    </div>
  </div>


  <div class="card">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3"></div>
      <div class="table-responsive">
        <table id="ReportTbl" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">
              <th>PM No</th>
              <th>Actucal Date</th>
              <th>Initiate Date</th>
              <th>Postponed Date</th>
              <th>PM Done Date</th>
              <th>Shift Time</th>
              <th>Machine Name</th>
              <th>Department</th>
              <th>Allocated Agency / Engineer Name</th>
              <th>Clearanced by</th>
              <th>Status</th>
              <th>Reason for PM Moved</th>
            </tr>
            <tr class="table-success">
              <th><input type="text" placeholder="Search PM No" /></th>
              <th><input type="text" placeholder="Filter Actual Date" /></th>
              <th><input type="text" placeholder="Filter Initiate Date" /></th>
              <th><input type="text" placeholder="Filter Postponed Date" /></th>
              <th><input type="text" placeholder="Filter PM Done Date" /></th>
              <th><input type="text" placeholder="Search Shift Time" /></th>
              <th><input type="text" placeholder="Search Machine Name" /></th>
              <th><input type="text" placeholder="Search Department" /></th>
              <th><input type="text" placeholder="Search Agency" /></th>
              <th><input type="text" placeholder="Search Cleared By" /></th>
              <th><input type="text" placeholder="Search Status" /></th>
              <th><input type="text" placeholder="Search Reason" /></th>
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
          url: "{{ url('datapreventivemaintenancereportget') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [
          { data: 'pm_no' },
          { data: 'actual_pm_date' },
          { data: 'initiate_date' },
          { data: 'postponed_date' },
          { data: 'done_on_date' },
          { data: 'shift_timing' },
          { data: 'machine_name' },
          { data: 'sub_department' },
          { data: 'agency' },
          { data: 'cleared_by' },
          { data: 'staus' },
          { data: 'move_reason' }
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