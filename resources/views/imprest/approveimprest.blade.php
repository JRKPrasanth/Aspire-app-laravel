@extends('layouts.header')
@section('content')
<h3 class="text-danger">
 <?php if($pageMethod=="imprestjournal") { ?>
Imprest Journal/Entry
  <?php } else { ?>
Imprest Approval
     <?php } ?>	
</h3>
@include('layouts.breadcrumb')



<div class="card shadow-lg rounded-4 border-0">
<div class="container mt-4">
  <table id="ImpTbl" class="table table-bordered table-striped w-100">
    <thead>
      <tr class="table-warning">
      <th>Employee Id</th>
      <th>Employee Id</th>
      <th>Imprest Number</th>
      <th>Employee</th>
      <th>Reporting Employee</th>
      <th>Imprest Date</th>
      <th>Reason</th>
      <th>Amount</th>
      <th>Active</th>
      <th>Status</th>
      <th>Actions</th>

      </tr>
      <tr class="table-info">
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
      </tr>
    </thead>
    <tbody>
      {{-- DataTable will populate via AJAX --}}
    </tbody>
  </table>
</div>
</div>





@endsection
@push('scripts')


<script>
	
	
// table data	
	
$(document).ready(function () {
  var table = $('#ImpTbl').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('imprestgriddata') }}",
    columns: [
    { data: 'employee_id', name: 'employee_id', visible:false },
    { data: 'report_id', name: 'report_id', visible:false },
    { data: 'imprest_number', name: 'imprest_number' },
    { data: 'employee_number', name: 'employee_number' },
    { data: 'report_number', name: 'report_number' },
    { data: 'imprest_date', name: 'imprest_date' },
    { data: 'reason', name: 'reason' },
    { data: 'amount', name: 'amount' },
    { data: 'active', name: 'active' },
    { data: 'status', name: 'status' },

      {
        data: 'imprest_id',
        name: 'actions',
        orderable: false,
        searchable: false,
        render: function (data, type, row) {
            let buttons = '';
            if (window.toolbarButtons?.some(btn => btn.attr.id === 'approve')) {
                    buttons += `
					<button type="button" class="btn btn-sm btn-success approve-btn"
					  data-id="${row.imprest_id}">Approve
					 
					</button>`;
            }
			
					            <?php   if($pageModule=="imprestjournal"){ ?>
						
                    buttons += `
				<button type="button" class="btn btn-sm btn-success journalentry me-1" value="1"
				  data-id="${row.imprest_id}"
				  		data-bs-toggle="tooltip" 
						data-bs-placement="top" 
						title="Journal Entry">
				 <i class="bi bi-plus"></i>
				</button>`;
			
			                    buttons += `
				<button type="button" class="btn btn-sm btn-primary journalentry" value="2"
				  data-id="${row.imprest_id}"
				  		data-bs-toggle="tooltip" 
						data-bs-placement="top" 
						title="Expense Entry">
				 <i class="bi bi-plus"></i>
				</button>`;
						
            <?php   }  ?>
			
			
			
            return buttons;
          }
          
      }
    ]
  });


  $('#ImpTbl thead').on('keyup change', '.column-search', function () {
    let index = $(this).closest('th').index();
    table.column(index).search(this.value).draw();
  });
});		
	
	
	// approve
	
	$(document).on('click', '.approve-btn', function () {
		imprest_id = $(this).data('id'); 
		var url = "{{URL::to('approveimprest')}}/"+imprest_id;
                window.location.href=url;
	});	
	
	
	
// journal entry purpose	
	
            $(document).on('click', '.journalentry', function () {


             const id = $(this).data('id');
           

                var status=$(this).val();
                if(status==1){
                var url = "{{URL::to('imprestjournalcreate')}}/"+id;
            }else{
                 var url = "{{URL::to('imprestexpensecreate')}}/"+id;
            }
                window.location.href=url;

        });
        


</script>

@endpush
