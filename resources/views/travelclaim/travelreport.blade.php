@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Travel Claim Report</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="RptTbl" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">
              <th class="freeze">Employee Name</th>
              <th>Claim Title</th>
              <th>Claim Date</th>
              <th>Description</th>
              <th>Travel Purpose</th>
              <th>Approve by</th>
              <th>Claim Status</th>
            </tr>

            <tr class="table-danger">

              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Employee
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Claim
                  Title</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Claim
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Description</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Travel
                  Purpose</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Approve
                  by</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Claim
                  Status</span></th>



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
        ajax: "{{ url('travelreportdata') }}",
        columns: [

          { class: 'freeze', data: "employee_name" },
          { data: "claim_title" },
          { data: "travel_date" },
          { data: "description" },
          { data: "travel_purpose" },
          { data: "reporting_name" },
          { data: "approved_status" },

        ]

      });

      // Column search
      $('#RptTbl thead').on('keyup change', ".column-search", function () {
        var index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });

  </script>

@endpush