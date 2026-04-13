@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Employee EP Check </h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white fw-semibold">
    </div>
    <div class="card-body p-4">
      <form method="post" action="" id="job_card_reprot" class="needs-validation" novalidate=""
        enctype="multipart/form-data" autocomplete="off">
        <input type="hidden" name="_token" value="tfdiEpoBfETz785FMRCa8vcN5U2fNFqxcS5aXZRo">
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
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="ReportTbl" class="table table-bordered table-striped w-100" style="width: 200% !important;">
          <thead>
            <tr class="table-warning">

              <th>Emp Number</th>
              <th class="freeze">Employee Name</th>
              <th>Department</th>
              <th>Desigination</th>
              <th>Check In</th>
              <th>Check Out</th>
              <th>Day</th>
              <th>Working Hrs</th>
              <th>Mrng EP</th>
              <th>Evg EP</th>
              <th>Night EP</th>
              <th>Sunday/Holiday EP</th>
              <th>Attended EP</th>
              <th>Overall EP Hrs</th>
              <th>EP Amount</th>
              <th>Food Amount</th>
              <th>Total Amount</th>

            </tr>
            <tr class="table-danger">


              <th>
                <input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                <span style="display: none;">Emp Number</span>
              </th>
              <th class="freeze">
                <input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                <span style="display: none;">Employee Name</span>
              </th>
              <th>
                <input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                <span style="display: none;">Department</span>
              </th>
              <th>
                <input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                <span style="display: none;">Desigination</span>
              </th>
              <th>
                <input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                <span style="display: none;">Check In</span>
              </th>
              <th>
                <input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                <span style="display: none;">Check Out</span>
              </th>
              <th>
                <input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                <span style="display: none;">Day</span>
              </th>
              <th>
                <input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                <span style="display: none;">Work Hrs</span>
              </th>
              <th>
                <input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                <span style="display: none;">Mrng EP</span>
              </th>
              <th>
                <input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                <span style="display: none;">Evg EP</span>
              </th>
              <th>
                <input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                <span style="display: none;">Night EP</span>
              </th>
              <th>
                <input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                <span style="display: none;">Sunday/Holiday EP</span>
              </th>
              <th>
                <input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                <span style="display: none;">Attended EP</span>
              </th>
              <th>
                <input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                <span style="display: none;">Overall EP Hrs</span>
              </th>
              <th>
                <input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                <span style="display: none;">EP Amount</span>
              </th>
              <th>
                <input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                <span style="display: none;">Food Amount</span>
              </th>
              <th>
                <input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                <span style="display: none;">Total Amount</span>
              </th>
            </tr>
          </thead>

          <tbody></tbody>
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
          url: "{{ url('otreportgrid') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [

          { data: 'employee_number' },
          { class: 'freeze', data: 'first_name' },
          { data: 'sub_department_name' },
          { data: 'job_title_name' },
          { data: 'check_in' },
          { data: 'check_out' },
          { data: 'day' },
          { data: 'wrk_hrs' },
          { data: 'mrng_OT' },
          { data: 'evng_OT' },
          { data: 'nght_OT' },
          { data: 'sunday_OT' },
          { data: 'OT_type' },
          { data: 'overall_OT_hrs' },
          { data: 'ot_amt' },
          { data: 'food_amt' },
          { data: 'total_amt' }

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