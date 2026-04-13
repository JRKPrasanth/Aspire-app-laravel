@extends('layouts.header')
@section('content')
<h2 class="text-danger">Product Access</h2>
@include('layouts.breadcrumb')
<button class="btn btn-success create" >Create <i class="bi bi-plus-circle"></i> </button>

<div class="card shadow-lg rounded-4 border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-3">
    </div>
    <div class="table-responsive">
      <table id="ProTable" class="table table-bordered table-striped w-100">
        <thead>
  <tr class="table-warning">
    <th>Employee Number</th>
    <th>Employee Name</th>
    <th>Menu Name</th>
    <th>Actions</th>
  </tr>
  <tr class="table-info">
    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Number" /></th>
    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Name" /></th>
    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Menu" /></th>
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

    var table = $('#ProTable').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('ProductaccessData') }}",
      columns: [
        { data: 'employee_number', name: 'tb_users.employee_number' },
        { data: 'first_name', name: 'tb_users.first_name' },
        { data: 'menuname', name: 'a_product_menu_access_t.menuname' },
        {
          data: 'menus',
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
    $('#ProTable thead').on('keyup change', ".column-search", function() {
      var colIndex = $(this).parent().index();
      table.column(colIndex).search(this.value).draw();
    });
  });

// create function
	 $(".create").click(function()
{
  var url="{{ URL::to('createproductaccess')}}";
    window.location.replace(url);
		 
});
	//edit function
    $(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');
    const url = "{{ url('productaccessedit') }}/" + id;
    window.location.href = url;
    });



</script>
@endpush
