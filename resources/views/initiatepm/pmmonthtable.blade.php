@extends('layouts.header')
@section('content')
<h3 class="text-danger">Pm Checklist</h3>
@include('layouts.breadcrumb')

<div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="MonTbl" class="table table-bordered table-striped w-100">
    <thead>
    <tr class="table-warning">
      <th>PM No</th>
      <th>Machine Name</th>
      <th>Department Name</th>
      <th>Actual PM Date</th>
      <th>PM Cleared By</th>
      <th>Actions</th>
    </tr>
    <tr class="table-info">
      <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
      <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
      <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
      <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
      <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
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

var url = "";
var id ='';
var pageMethod = "{{$pageMethod}}";

  if (pageMethod == "pmmonthlycheckapproval") {
      url = "{{ route('pmmonthlycheckapprovalData') }}";
    id ='pm_checking_id';
  } else {
    url = "{{ route('pmmonthlycheckData') }}";
    id = 'initiate_pm_id';
  }

    var table = $('#MonTbl').DataTable({
      processing: true,
      serverSide: true,
      order: [[3, 'desc']],
      ajax: url,
      columns: [
        { data: 'pm_no', name: 'pm_no' },
        { data: 'machine_name', name: 'machine_name' },
        { data: 'department_name', name: 'department_name' },
        { data: 'actual_pm_date', name: 'actual_pm_date' },
        { data: 'first_name', name: 'first_name' },

        {
          data: id,
          name: 'actions',
          orderable: false,
          searchable: false,
		  className: 'text-center',
          width: '200px', 
          render: function (data, type, row) {
			   let buttons = '';
			    if (window.toolbarButtons?.some(btn => btn.attr.id === 'checking')) {
                    buttons += `
					<button  class="btn btn-sm btn-primary me-1 checking" data-id="${data}"> CHECK </button>`;
           		 }

                    if (window.toolbarButtons?.some(btn => btn.attr.id === 'approve')) {
                    buttons += `
					<button  class="btn btn-sm btn-success me-1 approved" data-id="${data}"><i class="bi bi-check-circle-fill"></i> APPROVE </button>`;
           		 }

	return buttons;
          }
  
        }
      ]
    });
  
    // Individual column search
    $('#MonTbl thead').on('keyup change', ".column-search", function() {
      var colIndex = $(this).parent().index();
      table.column(colIndex).search(this.value).draw();
    });
  });	
	


    $(document).on('click', '.checking', function () {
    const id = $(this).data('id');
    const url = "{{ url('pmmonthlycheckcreate') }}/" + id;
    window.location.href = url;
    });	
	

    $(document).on('click', '.approved', function () {
    const id = $(this).data('id');
    const url="{{ URL::to('pmmonthlycheckapprove') }}"+"/"+id+"?source=approve"
    window.location.href = url;
    });	

</script>
@endpush