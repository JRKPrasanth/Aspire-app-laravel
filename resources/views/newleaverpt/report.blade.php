@extends('layouts.header')
@section('content')
  <h3 class="text-danger">
    Leave Balance Report
  </h3>
  @include('layouts.breadcrumb')



  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body p-4">
      <form method="post" action="" id="job_card_reprot" class="needs-validation" novalidate
        enctype="multipart/form-data">
        @csrf

        <!-- First Row: Date Inputs -->
        <div class="row g-4 mb-3">
          <div class="col-md-2"></div>
          <div class="col-md-4">
            <label for="month" class="form-label fw-semibold">Month For</label>
            <input type="month" class="form-control month" id="month" name="month" required
              autocomplete="off">
            <div class="invalid-feedback">Please select a start date.</div>
          </div>

            <div class="col-md-4">
            <label for="end_date" class="form-label fw-semibold">Active</label>
            <select  class="form-control select2 active" id="active" name="active" autocomplete="off" required>
                <option value="">--Please Select</option>
                <option value="ALL">ALL</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option> </select> 
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
          <th class="freeze">Employee Name</th>
          <th>Department</th>
          <th>Joining Date</th>
          <th>Exit Date</th>
          <th>Opening CL</th>
          <th>Eligible CL</th>
          <th>CL Taken </th>
          <th>Balance CL</th>
          <th>Opening EL</th>
          <th>Eligible EL</th>
          <th>EL Taken </th>
          <th>Balance EL</th>
          <th>Opening Comp Off</th>
          <th>Eligible Comp Off </th>
          <th>Comp Off Taken </th>
          <th>Balance Comp Off</th>
          <th>LOP Taken </th>
          <th>Active </th>
          </tr>
          <tr class="table-danger">
          <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span></th>
           <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span></th>
           <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span></th>
           <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span></th>
           <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span></th>
           <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span></th>
           <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span></th>
           <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span></th>
           <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span></th>
           <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span></th>
           <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span></th>
           <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span></th>
           <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span></th>
           <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span></th>
           <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span></th>
           <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span></th>
           <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span></th>
           <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span></th>
           <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span></th>
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
          url: "{{ url('Leavedata') }}",
          type: "GET",
          data: function (d) {
            d.month = $('#month').val();
            d.active = $('#active').val();
          }
        },
        columns: [
                { data: 'employee_number' },
                { class:'freeze', data: 'first_name' },
                { data: 'sub_department_name'},
                { data: 'date_of_joining' },
                { data: 'date_of_leaving' },
                { data: 'ocl' },
                { data: 'cl_eligible' },
                { data: 'cl_taken' },
                { data: 'cl_bal' },
                { data: 'oel' },
                { data: 'el_eligible' },
                { data: 'el_taken' },
                { data: 'el_bal' },
                { data: 'ocol' },
                { data: 'col_eligible' },
                { data: 'col_taken' },
                { data: 'col_bal' },
                { data: 'lop_taken' },
                { data: 'active' }
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