@extends('layouts.header')
@section('content')
<h3 class="text-danger">Company Menu Access</h3>
@include('layouts.breadcrumb')
<button class="btn btn-success create" >Create <i class="bi bi-plus-circle"></i> </button>

<div class="card shadow-lg rounded-4 border-0">
<div class="container mt-4">
  <table id="CompanyTable" class="table table-bordered table-striped w-100">
    <thead>
      <tr class="table-warning">
        <th>Company Name</th>
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
  var table = $('#CompanyTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('getCompanyaccessData') }}",
    columns: [
	{ data: 'company_name', name: 'm_company_t.company_name' },
      {
        data: 'id',
        name: 'actions',
        orderable: false,
        searchable: false,
		className: 'text-center',
        width: '140px', 
		render: function (data, type, row) {
		  return `
			<button class="btn btn-sm btn-primary me-1 edit-btn" 
			  data-id="${row.id}" 
			  data-name="${row.company_name}">
			  <i class="bi bi-pencil"></i>
			</button>`;
		}
      }
    ]
  });

  // Individual column search
  $('#CompanyTable thead').on('keyup change', ".column-search", function() {
    var colIndex = $(this).parent().index();
    table.column(colIndex).search(this.value).draw();
  });
});
	
// create function
	 $(".create").click(function()
{
  var url="{{ URL::to('createcompanyaccess')}}";
    window.location.replace(url);
});
  
	//edit function
    $(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');
    const url = "{{ url('companyaccessedit') }}/" + id;
    window.location.href = url;
    });

    </script>
@endpush
