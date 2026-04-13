@extends('layouts.header')
@section('content')
<h2 class="text-danger">Other Dashboard Access</h2>
@include('layouts.breadcrumb')
<button class="btn btn-success create" >Create <i class="bi bi-plus-circle"></i> </button>

<div class="card shadow-lg rounded-4 border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-3">
    </div>
    <div class="table-responsive">
      <table id="DashTable" class="table table-bordered table-striped w-100">
        <thead>
  <tr class="table-warning">
    <th>User Name</th>
    <th>Employee Name</th>
    <th>Actions</th>
  </tr>
  <tr class="table-info">

    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Name" /></th>
    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Employee" /></th>
    <th></th>
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
	$(document).ready(function() {

    var table = $('#DashTable').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('otherDashboardData') }}",
      columns: [
        { data: 'username', name: 'tb_users.username' },
        { data: 'first_name', name: 'tb_users.first_name' },
        {
          data: 'id',
          name: 'actions',
          orderable: false,
          searchable: false,
          render: function (data, type, row) {
            return `
              <button  class="btn btn-sm btn-primary me-1 edit-btn" data-id="${data}"><i class="bi bi-pencil"></i></button>`;
          }
  
        }
      ]
    });
  
    // Individual column search
    $('#DashTable thead').on('keyup change', ".column-search", function() {
      var colIndex = $(this).parent().index();
      table.column(colIndex).search(this.value).draw();
    });
  });

// create function
	 $(".create").click(function()
{
    var url="{{ url('createotherdashboardaccess') }}/0";
    window.location.replace(url);
		 
});
	//edit function
    $(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');
    const url = "{{ url('createotherdashboardaccess') }}/" + id;
    window.location.href = url;
    });



</script>
@endpush