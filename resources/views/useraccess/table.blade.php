@extends('layouts.header')
@section('content')
<h3 class="text-danger">User Access</h3>
@include('layouts.breadcrumb')
<div class="card shadow-lg rounded-4 border-0">
<div class="container mt-4">
  <table id="UserTable" class="table table-bordered table-striped w-100">
    <thead>
      <tr class="table-warning dthfreeze">
        <th>User Name</th>
        <th>Employee Name</th>
        <th>Email</th>
        <th>Active</th>
        <th>Actions</th>
      </tr>
      <tr class="table-info">
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
		    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
		    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
         <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
</div>
</div>
@endsection
@push('scripts')
	
<script>
// data table funcrion	
$(document).ready(function() {
  var table = $('#UserTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('getUseraccessData') }}",
    columns: [
	{ data: 'username', name: 'tb_users.username' },
    { data: 'first_name', name: 'tb_users.first_name' },
    { data: 'email', name: 'tb_users.email' },
    { data: 'active', name: 'tb_users.active' },
      {
        data: 'a_user_access_id',
        name: 'actions',
        orderable: false,
        searchable: false,
		className: 'text-center',
        width: '140px', 
		render: function (data, type, row) {
		  return `
			<button class="btn btn-sm btn-primary me-1 edit-btn" 
			  data-id="${row.a_user_access_id}" 
			  data-name="${row.username}">
			  <i class="bi bi-pencil"></i>
			</button>`;
		}
      }
    ]
  });

  // Individual column search
  $('#UserTable thead').on('keyup change', ".column-search", function() {
    var colIndex = $(this).parent().index();
    table.column(colIndex).search(this.value).draw();
  });
});
	
	//edit function
    $(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');
    const url = "{{ url('useraccessedit') }}/" + id;
    window.location.href = url;
    });

    </script>
@endpush