@extends('layouts.header')
@section('content')
<h3 class="text-danger">Group Menu Access</h3>
@include('layouts.breadcrumb')
<div class="card shadow-lg rounded-4 border-0">
<div class="container mt-4">
  <table id="GrpTable" class="table table-bordered table-striped w-100">
    <thead>
      <tr class="table-warning">
        <th>Group Name</th>
        <th>Actions</th>
      </tr>
      <tr class="table-info">
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Name" /></th>
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
  var table = $('#GrpTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('getGroupaccessData') }}",
    columns: [
	{ data: 'group_name', name: 'a_m_group_t.group_name' },
      {
        data: 'a_group_menu_access_id',
        name: 'actions',
        orderable: false,
        searchable: false,
		className: 'text-center',
        width: '140px', 
		render: function (data, type, row) {
		  return `
			<button class="btn btn-sm btn-primary me-1 edit-btn" 
			  data-id="${row.a_group_menu_access_id}" 
			  data-name="${row.group_name}">
			  <i class="bi bi-pencil"></i>
			</button>`;
		}
      }
    ]
  });

  // Individual column search
  $('#GrpTable thead').on('keyup change', ".column-search", function() {
    var colIndex = $(this).parent().index();
    table.column(colIndex).search(this.value).draw();
  });
});
	
	//edit function
    $(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');
    const url = "{{ url('groupaccessedit') }}/" + id;
    window.location.href = url;
    });

    </script>
@endpush
