@extends('layouts.header')
@section('content')
<h3 class="text-danger">Application Viewers</h3>
@include('layouts.breadcrumb')

<div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
        <form method="post" id="searchForm">
            <div class="row g-3">

                <!-- Employee Name -->
                <div class="col-md-4">
                    <label for="emp_id" class="form-label">Employee Name</label>
                    <select name="emp_id" id="emp_id" class="form-select select2">
                        {!! $employee !!}
                    </select>
                </div>

                <!-- Start Date -->
                <div class="col-md-4">
                    <label for="start_date1" class="form-label">Start Date</label>
                    <input type="text" class="form-control start_date" id="start_date" name="start_date" required autocomplete="off">
                </div>

                <!-- End Date -->
                <div class="col-md-4">
                    <label for="end_date1" class="form-label">End Date</label>
                    <input type="text" class="form-control end_date" id="end_date" name="end_date" required autocomplete="off">
                </div>

                <!-- Search Button -->
                <div class="col-md-12 text-center mt-4">
                    <button type="button" class="btn btn-primary report_search px-4" id="report_search"><i class="bi bi-search"></i> Search</button>
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
            <th>Employee Name</th>
            <th>Report Name</th>
            <th>Month</th>
            <th>Date & Time</th>
          </tr>
          <tr class="table-success">
            <th><input type="text" placeholder="Search" /><span style="display: none;">Employee Name</span></th>
            <th><input type="text" placeholder="Search" /><span style="display: none;">Report Name</span></th>
            <th><input type="text" placeholder="Search" /><span style="display: none;">Month</span></th>
            <th><input type="text" placeholder="Search" /><span style="display: none;">Date & Time</span></th>
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
  ajax: {
    url: "{{ url('getviewdata') }}",
    type: "GET",
    data: function(d) {
      d.start_date = $('#start_date').val();
      d.end_date = $('#end_date').val();
      d.emp_id = $('#emp_id').val();
    }
  },
  columns: [
    { data: 'first_name'},
    { data: 'type'},
    { data: 'month'},
    { class:'text-primary fw-bold', data: 'date_time'}

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
