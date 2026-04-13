@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Training Report</h3>
  @include('layouts.breadcrumb')



  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3"></div>
      <div class="table-responsive">
        <table id="ReportTbl" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">
              <th class="freeze">Training Topic</th>
              <th>Participants Name</th>
              <th>Head Quarter</th>
              <th>Zone</th>
              <th>Training Month</th>
              <th>Year</th>
              <th>Attendance Status</th>
              <th>Training Start Date</th>
              <th>Training End Date</th>
              <th>Duration (Hrs)</th>
              <th>Session Type</th>
              <th>Trainers</th>

            </tr>
               <tr class="table-danger">
        <th class="freeze"><input type="text" class="column-search" placeholder="Search"> </th>
        <th><input type="text" class="column-search" placeholder="Search">
        </th>
        <th><input type="text" class="column-search" placeholder="Search">
        </th>
        <th><input type="text" class="column-search" placeholder="Search">
        </th>
        <th><input type="text" class="column-search" placeholder="Search"></th>
        <th><input type="text" class="column-search" placeholder="Search">
        </th>
        <th><input type="text" class="column-search" placeholder="Search">
        </th>
        <th><input type="text" class="column-search" placeholder="Search">
        </th>
        <th><input type="text" class="column-search" placeholder="Search">
        </th>
        <th><input type="text" class="column-search" placeholder="Search">
        </th>
        <th><input type="text" class="column-search" placeholder="Search">
        </th>
        <th><input type="text" class="column-search" placeholder="Search">
        </th>

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
          url: "{{ url('trainingreportdata') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [
          { class: 'freeze', data: "topic_name" },
          { data: "participant" },
          { data: "area_name" },
          { data: "zone_name" },
          { data: "month" },
          { data: "year" },
          { data: "attend_status" },
          { data: "sdate" },
          { data: "edate" },
          { data: "duration" },
          { data: "schedule_type" },
          { data: "trainer_name" }

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