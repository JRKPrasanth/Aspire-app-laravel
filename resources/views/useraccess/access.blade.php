@extends('layouts.header')
@section('content')
<h3 class="text-danger">Employee Menu Report</h3>
@include('layouts.breadcrumb')

<div class="card shadow-lg rounded-4 border-0">
  <div class="card-header bg-primary bg-gradient text-white fw-semibold"></div>
  <div class="card-body mt-2">

    <div class="row mb-3">
      <div class="col-md-4">
        <label for="employee" class="form-label">Employee Name</label>
		   </div>
		  <div class="col-md-4">
        <select id="employee" class="form-select select2">
          {!! $employee !!}
        </select>
      </div>

      <div class="col-md-4">
        <button type="button" name="submit" class="btn btn-primary report_search px-4"> <i class="bi bi-search"></i> Search</button>
</div>

    </div>



  </div>
</div>


<div class="card shadow-lg rounded-4 border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-3">
    </div>
    <div class="table-responsive">
	<table id="menuTable" class="table table-striped table-bordered">
    <thead>
    <tr class="table-warning">
      <th>Employee Name</th>
      <th>Main Menu</th>
      <th>Sub1</th>
	  <th>Sub2</th>
	  <th>Action</th>
    </tr>
	<tr class="table-success">
    <th><input type="text" placeholder="Search" /><span style="display: none;">Employee Name</span></th>
    <th><input type="text" placeholder="Search" /><span style="display: none;">Main Menu</span></th>
	<th><input type="text" placeholder="Search" /><span style="display: none;">Sub1</span></th>
    <th><input type="text" placeholder="Search" /><span style="display: none;">Sub2</span></th>
    <th><input type="text" placeholder="Search" /><span style="display: none;">Action</span></th>
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

$('#menuTable').DataTable({
  processing: true,
  serverSide: false,
  ajax: {
    url: "{{ url('permissionreportdata') }}",
    type: "GET",
    data: function(d) {
      d.id = $('#employee').val();
    }
  },
  columns: [
    { data: 'employee_name' },
    { data: 'main_menu' },
    { data: 'sub1' },
    { data: 'sub2' },
    { data: 'action' }
  ],
});

// Trigger search
$('.report_search').on('click', function () {
  $('#menuTable').DataTable().ajax.reload();
});
  
  
    // Column search
    $('#menuTable thead').on('keyup change', ".column-search", function () {
      var index = $(this).closest('th').index();
      table.column(index).search(this.value).draw();
    });
  });
</script>
@endpush
