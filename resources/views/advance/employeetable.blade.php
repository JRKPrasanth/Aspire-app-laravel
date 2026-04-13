@extends('layouts.header')
@section('content')
<h3 class="text-danger">Employee F & F</h3>
@include('layouts.breadcrumb')



<div class="card shadow-lg rounded-4 border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-3">
    </div>
    <div class="table-responsive">
      <table id="fandfTbl" class="table table-bordered table-striped w-100">
        <thead>
  <tr class="table-warning">
      <th>Employee Number</th>
      <th>Employee Name</th>
      <th>Date Of Leaving</th>
      <th>Active</th>
      <th>Actions</th>
  </tr>
  <tr class="table-info">
    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
  </tr>
</thead>

        <tbody></tbody>
      </table>
    </div>
  </div>
</div>


<!-- popup -->

<!-- Bootstrap 5 Modal -->
<div class="modal fade" id="updateempstatus" tabindex="-1" aria-labelledby="updateStatusLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content rounded-3 shadow">

      <!-- Modal Header -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="updateStatusLabel">Update Status</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Hidden Inputs -->
      <input type="hidden" id="advance_id" name="advance_id" class="advance_id" value="">
      <input type="hidden" id="employee_number" name="employee_number" class="employee_number" value="">
      <input type="hidden" id="employee_name" name="employee_name" class="employee_name" value="">

      <!-- Modal Body -->
      <div class="modal-body">
        <div class="mb-3 row">
          <label for="employee_status" class="col-md-4 col-form-label">
            <span class="text-danger">*</span> Employee Status:
          </label>
          <div class="col-md-6">
            <select id="employee_status" name="employee_restatus" class="form-select employee_status select2" required>
              <option value="">-- Please Select --</option>
              <option value="1">Completed</option>
              <option value="0">Pending</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer">
        
        <button class="btn btn-success popup_save px-3">Save</button>
        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>


@endsection
@push('scripts')

<script>
	
	$(document).ready(function() {

    var table = $('#fandfTbl').DataTable({
      processing: true,
      serverSide: true,
      order: [[2, 'desc']],
      ajax: "{{ route('getemployeegenData') }}",
      columns: [
        { data: 'employee_number', name: 'employee_number' },
        { data: 'first_name', name: 'first_name' },
         { data: 'date_of_leaving', name: 'date_of_leaving' },
        { data: 'active', name: 'active' },

        {
          data: 'employee_id',
          name: 'actions',
          orderable: false,
          searchable: false,
		      className: 'text-center',
    
          render: function (data, type, row) {
              return `
                  <button class="btn btn-sm btn-primary me-1 update-btn" data-id="${data}">
                    Update
                  </button>
                          <button class="btn btn-sm btn-success me-1 generate-btn" data-id="${data}">
                     Generate
                  </button>
                  `;
                  
          }
        }
      ]
    });
  
    // Individual column search
    $('#fandfTbl thead').on('keyup change', ".column-search", function() {
      var colIndex = $(this).parent().index();
      table.column(colIndex).search(this.value).draw();
    });
  });	
	
	
// generate
	
$(document).on('click', '.generate-btn', function () {
  const id = $(this).data('id');
   var url= "{{URL::to('fandfcreate/0')}}?emp_id="+id;
  window.location.href = url;
});	
	
// update
	
$(document).on('click', '.update-btn', function () {
	
			var table = $('#fandfTbl').DataTable();
			var tr = $(this).closest('tr');
			var rowData = table.row(tr).data();

			var employee_number = rowData.employee_number;
			var advance_id = $(this).data('id');
			var employee_name = rowData.first_name;
			var employee_status = rowData.active;
			const myModal = new bootstrap.Modal(document.getElementById('updateempstatus'));
			myModal.show();
	
	                  $('#advance_id').val(advance_id);
                      $('#employee_number').val(employee_number);
                      $('#employee_name').val(employee_name);
	
});		
	
	
	
	
// popup save

	          		$(document).on('click','.popup_save',function(){
                 var employee_number = $('#employee_number').val();
                 var advance_id = $('#advance_id').val();
                 var employee_name = $('#employee_name').val();
                 var employee_status = $('#employee_status').val();
				
                 if(employee_status==""|| employee_status==null)
                 {
                    showCustomAlert('Please Enter Employee Status','info');
                 }
                 else
                 {
                 var url = "{{ URL::to('employeestatusupdate') }}/"+employee_status+"/"+advance_id+"/"+employee_number;
                 $.get(url,function(data)
                 {
                        if(data == 1)
                        {
                            showCustomAlert('Employee Status Updated Successfully','success');
                            location.reload();
                        }
                        else if(data == 2)
                        {
                            showCustomAlert('Employee Status Failed','error');
                            location.reload();
                        }
                    });
               }
                 
                 });	
	
	
	$('#updateempstatus').on('shown.bs.modal', function () {
  $('#employee_status').select2({
    dropdownParent: $('#updateempstatus') // VERY important inside modals
  });
});

	
</script>

@endpush
