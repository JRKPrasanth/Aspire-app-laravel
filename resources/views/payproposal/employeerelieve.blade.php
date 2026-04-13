@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Employee Relieve Report</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white fw-semibold">
      <i class="bi bi-funnel me-2"></i>Filter Options
    </div>
    <div class="card-body">

      <div class="mb-4">
        <div class="row g-3 align-items-center">
          <div class="col-md-2"></div>
          <div class="col-md-4 col-lg-4">
            <label for="month" class="form-label">Month</label>
            <select id="month" class="form-select select2">
              <!-- Month options -->
            </select>
          </div>

          <div class="col-md-6 col-lg-4">
            <label for="year" class="form-label">Year</label>
            <select id="year" class="form-select select2">
              <!-- Year options -->
            </select>
          </div>

        </div>
      </div>

      <div class="text-center">
        <button type="button" class="btn btn-primary report_search">
          <i class="bi bi-search"></i> Search
        </button>
      </div>

    </div>
  </div>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="RptTbl" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">
              <th>UAN No</th>
              <th>PF No</th>
              <th>Date Of Birth</th>
              <th class="freeze">Name of Employee</th>
              <th>Department</th>
              <th>Designation</th>
              <th>Zone</th>
              <th>Father's Name</th>
              <th>Date of Leaving Service</th>
              <th>Actual Date of Leaving Service</th>
              <th>Reason for leaving service</th>
              <th>Date of Joining the Fund</th>
              <th>ESI Number</th>


            </tr>
            <tr class="table-danger">
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">UAN No</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">PF No</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Date Of
                  Birth</span></th>
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Name of Employee</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Department</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Designation</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Zone</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Father's
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Date of
                  Leaving Service</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Actual Date of
                  Leaving Service</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Reason for
                  leaving service</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Date of
                  Joining the Fund</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">ESI
                  Number</span></th>


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

      // for year load and selected
      var min = 2020,
        max = new Date().getFullYear(),
        select = document.getElementById('year');

      for (var i = max; i >= min; i--) {
        var opt = document.createElement('option');
        opt.value = i;
        opt.innerHTML = i;
        select.appendChild(opt);

      }


      var url = "{{ URL::to('jcomboformlogin') }}?table=month:id:description";

      $.ajax({
        url: url,
        type: 'GET',
        success: function (data) {
          // Parse JSON string if needed
          if (typeof data === "string") {
            try {
              data = JSON.parse(data);
            } catch (e) {
              console.error("Invalid JSON response:", data);
              return;
            }
          }

          $('#month').html('<option value="">-- Select Month --</option>');

          $.each(data, function (i, item) {
            let selected = item.val == "{{ $row->month ?? '' }}" ? 'selected' : '';
            $('#month').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
          });

          $('#month').trigger('change.select2');
        }


      });


    });
    $(document).ready(function () {

      var table = $('#RptTbl').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        ajax: {
          url: "{{ url('employeerelievereportgrid') }}",
          type: "GET",
          data: function (d) {
            d.month = $('#month').val();
            d.year = $('#year').val();
          }
        },
        columns: [
          { data: "uan_no" },
          { data: "pf_no" },
          { data: "dob" },
          { class: "freeze", data: "emp_nmae" },
          { data: "department" },
          { data: "job_title_name" },
          { data: "zone_name" },
          { data: "father_name" },
          { data: "date_of_leaving" },
          { data: "releive_date_actual" },
          { data: "reason" },
          { data: "date_of_joining" },
          { data: "esi_no" }

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
        $('#RptTbl').DataTable().ajax.reload();
      });


      // Column search
      $('#RptTbl thead').on('keyup change', ".column-search", function () {
        var index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });
  </script>

@endpush