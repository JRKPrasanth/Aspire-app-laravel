@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Attendance Monthly Report</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white fw-semibold">
    </div>
    <div class="card-body">
      <form method="post" action="" id="job_card_reprot" class="org_form" data-parsley-validate
        enctype="multipart/form-data">
        @csrf
        <div class="row">
          <div class="col-md-2"></div>
          <div class="col-md-4">
            <div class="row mb-3 align-items-center">
              <label for="start_date" class="col-sm-4 col-form-label">As On Date</label>
              <div class="col-sm-8">
                <input type="text" class="form-control start_date" id="start_date" name="start_date" required
                  autocomplete="off" required>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <button type="button" class="btn btn-primary report_search" id="report_search" value="SAVE"><i
                class="bi bi-search me-1"></i> Search</button>
          </div>


      </form>
    </div>
  </div>
  </div>



  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3"></div>
      <div class="table-responsive">
        <table id="ReportTbl" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">
              <th>

                Date
              </th>
              <th>

                Day
              </th>
              <th>

                Employee No
              </th>
              <th>

                Bio-Metric No
              </th>
              <th>

                Employee Name
              </th>
              <th>

                Department
              </th>
              <th>

                Remarks
              </th>
              <th>

                Active
              </th>

            </tr>
            <tr class="table-danger">
              <th>
                <input type="text" class="column-search" placeholder="Search" />
                <span style="display: none;">Date</span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search" />
                <span style="display: none;">Day</span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search" />
                <span style="display: none;">Employee No</span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search" />
                <span style="display: none;">Bio-Metric No</span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search" />
                <span style="display: none;">Employee Name</span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search" />
                <span style="display: none;">Department</span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search" />
                <span style="display: none;">Remarks</span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search" />
                <span style="display: none;">Active</span>
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
        ajax: {
          url: "{{ url('datadailyattendancereportget') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
            d.account_code_line = $('#account_line_id').val();
          }
        },
        columns: [
          { data: 'date' },
          { data: 'day' },
          { data: 'employee_number' },
          { data: 'biometric_empno' },
          { data: 'first_name' },
          { data: 'dept' },
          { data: 'status' },
          { data: 'active' }

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