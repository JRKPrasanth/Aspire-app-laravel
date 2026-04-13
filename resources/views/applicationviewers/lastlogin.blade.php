@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Users Login Report</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <form method="post" id="searchForm">
        <div class="row g-3">

          <!-- Employee Name -->
          <div class="col-md-4">
            <label for="emp_id" class="form-label">Employee Name</label>
          </div>
          <div class="col-md-4 text-center">
            <select name="emp_id" id="emp_id" class="form-select select2">
              {!! $employee !!}
            </select>
          </div>

          <!-- Search Button -->

          <div class="col-md-4 text-center">
            <button type="button" class="btn btn-primary report_search px-4" id="report_search"><i
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
              <th>Employee Number</th>
              <th>Employee Name</th>
              <th>Last Login</th>
              <th>Last Logout</th>
              <th>IP Address</th>
              <th>Browser Name</th>
            </tr>
            <tr class="table-info">
              <th><input type="text" placeholder="Search" /><span style="display: none;">Employee Number</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Employee Name</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Last Login</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Last Logout</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">IP Address</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Browser Name</span></th>
            </tr>
          </thead>
          <tbody>
            <!-- Your dynamic row data goes here -->
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- END -->

@endsection
@push('scripts')

  <script>
    $(document).ready(function () {

      $('#ReportTbl').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        ajax: {
          url: "{{ url('GetloginData') }}",
          type: "GET",
          data: function (d) {
            d.emp_id = $('#emp_id').val();
          }
        },
        columns: [
          { data: 'employee_number' },
          { data: 'first_name' },
          { data: 'last_login' },
          { data: 'last_logout' },
          { data: 'ip_address' },
          { data: 'login_browser' }

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