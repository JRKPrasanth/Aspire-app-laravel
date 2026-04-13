@extends('layouts.header')
@section('content')
<h3 class="text-danger">Employee Details Report</h3>
@include('layouts.breadcrumb')


<div class="card shadow-lg rounded-4 border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-3">
    </div>
    <div class="table-responsive">
	<table id="RptTbl" class="table table-striped table-bordered">
    <thead>
    <tr class="table-warning">
    <th>Employee Code</th>
    <th>Prefix</th>
    <th class="freeze">First Name</th>
    <th>Last Name</th>
    <th>Email</th>
    <th>Company</th>
    <th>Reporting Manager</th>
    <th>Reporting Manager1</th>
    <th>Position</th>
    <th>Grade</th>
    <th>Date of Joining</th>
    <th>Date of Leaving</th>
    <th>ESI Number</th>
    <th>ESI Dispensary</th>
    <th>PF Date</th>
    <th>UAN Number</th>
    <th>Mobile Number</th>
    <th>Alternative Number</th>
    <th>Biometric Emp No</th>
    <th>Employee type</th>
    <th>Zone</th>
    <th>Group Type</th>
    <th>Active</th>
    <th>OT Formula</th>
    <th>Casual Leave</th>
    <th>Sick Leave</th>
    <th>Earn Leave</th>
    </tr>
	<tr class="table-danger">
    <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Employee Code</span></th>
    <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Prefix</span></th>
    <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span style="display:none;">First Name</span></th>
    <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Last Name</span></th>
    <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Email</span></th>
    <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Company</span></th>
    <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Reporting Manager</span></th>
    <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Reporting Manager1</span></th>
    <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Position</span></th>
    <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Grade</span></th>
    <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Date of Joining</span></th>
    <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Date of Leaving</span></th>
    <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">ESI Number</span></th>
    <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">ESI Dispensary</span></th>
    <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">PF Date</span></th>
    <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">UAN Number</span></th>
    <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Mobile Number</span></th>
    <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Alternative Number</span></th>
    <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Biometric Emp No</span></th>
    <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Employee type</span></th>
    <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Zone</span></th>
    <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Group Type</span></th>
    <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Active</span></th>
    <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">OT Formula</span></th>
    <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Casual Leave</span></th>
    <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Sick Leave</span></th>
    <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Earn Leave</span></th>
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
  scrollX: true,
  scrollY: "50vh",
  ajax: {
    url: "{{ url('employeegrid') }}",
    type: "GET",
    data: function(d) {
      d.id = $('#employee').val();
    }
  },
  columns: [
  { data: "employee_number" },
  { data: "prefix_name" },
  { class:"freeze", data: "first_name" },
  { data: "last_name" },
  { data: "email" },
  { data: "company_name" },
  { data: "rep_name" },
  { data: "rep1_name" },
  { data: "job_title_name" },
  { data: "position_name" },
  { data: "date_of_joining" },
  { data: "date_of_leaving" },
  { data: "esi_no" },
  { data: "esi_dispensary" },
  { data: "pf_date" },
  { data: "uan_no" },
  { data: "work_telephone_number" },
  { data: "alternative_telephone_number" },
  { data: "biometric_empno" },
  { data: "emp_type_name" },
  { data: "zone_name" },
  { data: "group_name" },
  { data: "active" },
  { data: "ot" },
  { data: "c_l" },
  { data: "s_l" },
  { data: "e_l" }

  ]

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