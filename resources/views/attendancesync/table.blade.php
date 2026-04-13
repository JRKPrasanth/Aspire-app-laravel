@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Attendance Details</h3>
  @include('layouts.breadcrumb')

  <button type="button" class="btn btn-primary bio_sync mt-2"><i class="bi bi-search"></i> Biometric Sync</button>

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="RptTbl" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">

              <th class="freeze">Employee Name</th>
              <th>Date</th>
              <th>Check In</th>
              <th>Check Out</th>
              <th>Workinh Hrs</th>
            </tr>
            <tr class="table-danger">

              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Employee Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Date</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Check
                  In</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Check
                  Out</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Workinh
                  Hrs</span></th>


            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>


@endsection
@push('scripts')


  <script>


    $(document).ready(function () {

      var table = $('#RptTbl').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
          url: "{{ url('attendancedetailsgrid') }}",
          type: "GET",
          data: function (d) {
            d.month = $('#month').val();
            d.year = $('#year').val();
          }
        },
        columns: [

          { class: "freeze", data: "first_name" },
          { data: "atten_date" },
          { data: "check_in" },
          { data: "check_out" },
          { data: "working_hours" }

        ],

      });

      // Trigger search
      $('.report_search').on('click', function () {
        $('#RptTbl').DataTable().ajax.reload();
      });


      // Column search
      $('#RptTbl thead').on('keyup change', ".column-search", function () {
        var index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });


    //search

    $(document).on('click', '.bio_sync', function () {
      var url = "{{URL::to('attedancesyncdata')}}";
      window.location.href = url;
    });


  </script>

@endpush