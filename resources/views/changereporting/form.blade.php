@extends('layouts.header')
@section('content')
<h3 class="text-danger">Change Reporting</h3>
@include('layouts.breadcrumb')


<div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
        <form action="" id="save">
            <?php $data = \Session::get('data'); if (isset($data[$pageMethod]['save'])) { ?>
                {{ csrf_field() }}
                <input type="hidden" name="reporting_id" id="reporting_id">

                <div class="row g-4">
                    <!-- Left Side -->
                    <div class="col-md-6">
                        <!-- Reporting Manager -->
                        <div class="mb-3 row align-items-center">
                            <label class="col-md-5 col-form-label"><span class="text-danger">*</span> Reporting Name</label>
                            <div class="col-md-6">
                                <select name="reporting_manager" id="reporting_manager" class="form-select select2" required>
                                    {!! $reporting_list !!}
                                </select>
                            </div>
                        </div>

                        <!-- Employee List -->
                        <div class="mb-3 row align-items-start">
                            <label class="col-md-5 col-form-label"><span class="text-danger">*</span> Employee List</label>
                            <div class="col-md-7 employee_list">
                                <!-- Dynamic employee list content -->
                            </div>
                        </div>
                    </div>

                    <!-- Right Side -->
                    <div class="col-md-6">
                        <!-- Assign Reporting -->
                        <div class="mb-3 row align-items-center">
                            <label class="col-md-5 col-form-label"><span class="text-danger">*</span> Assign Reporting</label>
                            <div class="col-md-6">
                                <select name="assing_employee" id="assing_employee" class="form-select select2" required>
                                    <!-- Populated dynamically -->
                                </select>
                            </div>
                        </div>

                        <!-- Previous Reporting -->
                        <div class="mb-3 row oldhide align-items-start">
                            <label class="col-md-5 col-form-label"><span class="text-danger">*</span> Previous Reporting Employee</label>
                            <div class="col-md-7">
                                <div class="reportingprevious"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="row mt-4">
                    <div class="col-12 text-center">
                        <button type="button" class="btn btn-success px-4 save_form">Save</button>
                    </div>
                </div>
            <?php } else { ?>
                <div class="row text-center">
                   
                </div>
            <?php } ?>
        </form>
    </div>
</div>

<div class="card shadow-lg rounded-4 border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-3">
    </div>
    <div class="table-responsive">
      <table id="RptTbl" class="table table-bordered table-striped w-100">
        <thead>
  <tr class="table-warning">
      <th></th>
      <th></th>
      <th></th>
	  <th>Reporting Manager</th>
	  <th>Assign Employee</th>
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
		
		$(document).ready(function () {
  var table = $('#RptTbl').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('changereportinggriddata') }}",
    columns: [
		{ data: 'reporting_manager', name: 'reporting_manager',visible:false },
		{ data: 'assing_employee', name: 'assing_employee',visible:false },
		{ data: 'employees', name: 'employees',visible:false },
        { data: 'report_name', name: 'report_name' },
        { data: 'ass_name', name: 'ass_name' },
		
		
      {
        data: 'reporting_id',
        name: 'actions',
        orderable: false,
        searchable: false,
        render: function (data, type, row) {
            let buttons = '';
            if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                    buttons += `
				<button type="button" class="btn btn-sm btn-primary edit-btn"
            data-id="${row.reporting_id}"
            data-rname_id="${row.reporting_manager}" 
            data-aname_id="${row.assing_employee}"   
            data-employees="${row.employees}">
				  <i class="bi bi-pencil"></i>
				</button>`;
            }
            return buttons;
          }
          
      }
    ]
  });


  $('#RptTbl thead').on('keyup change', '.column-search', function () {
    let index = $(this).closest('th').index();
    table.column(index).search(this.value).draw();
  });
});
		
		
/*** edit function **/	
$(document).on('click', '.edit-btn', function () {
    var form = $("#save");
    form[0].reset(); // Reset form fields
    form.parsley().destroy();

    $('.employeelist').text('New Employee Lists');
    $('.oldhide').show();

    let reporting_id = $(this).data('id');
    let reporting_manager = $(this).data('rname_id'); // should be ID not name
    let assing_employee = $(this).data('aname_id');   // should be ID not name
    let employees = $(this).data('employees'); // comma-separated names string

    $('#reporting_id').val(reporting_id);
    $('#reporting_manager').val(reporting_manager).trigger('change');

    setTimeout(function () {
        $('#assing_employee').val(assing_employee).trigger('change');
    }, 1000);

    // Render previous employees
    var result = '<table border="1" class="table myTable"><thead><tr>';
    result += '<th width="2%">Sno</th>';
    result += '<th width="20%">Employee Name</th>';
    result += '</tr></thead><tbody>';

    var emp = employees ? employees.split(',') : [];
    $.each(emp, function (index, val) {
        result += '<tr><td>' + (index + 1) + '</td><td><strong>' + val + '</strong></td></tr>';
    });

    result += '</tbody></table>';
    $('.reportingprevious').html(result);
});

	

            $(document).on('click','#check',function()
            {
                if ($(this).prop('checked')==true)
                { 
                    
                   var i=0;
                    $(".check_employee").each(function(){
                    
                      $('.check_employee'+i).prop('checked',true);
                      i++;
                    });
                }
                else{
                    
                     var i=0;
                    $(".check_employee").each(function(){
                    
                      $('.check_employee'+i).prop('checked',false);
                      i++;
                    });
                }
                
            });
		
            /** without check while save validate */
            var dup_chk = true;
            function check()
            {
                var i=0;
                 $('.check_employee').each(function(){
                      if($(this).is(":checked")){ 
                          i=i+1;
                      }
              });
              if(i==0)
              {
                 dup_chk = false; 
                  notyMsg('warning','Pleae select the Employees to reassign');
            }
        }
        /** save function **/
            $(document).on('click','.save_form',function(){
                    var form = $('#save');
                    form.parsley().validate();
                    check();
              
                    if(form.parsley().isValid() && dup_chk)
                    {	
                         	var $btn = $(this);            
                            $btn.prop('disabled', true);
                            var data = $("#save").serialize();
                            var url = "{{URL::to('/savereportingchanges/')}}";
                          
                            $.post(url, data, function(data)
                            {
                                if(data == 1)
                                {		
                                    showCustomAlert('Reporting Manager Reassign Saved Successfully','success');
                                    setTimeout(function(){
                                           var url = "{{URL::to('separation')}}";
                                           location.reload();
                                    }, 100);
                                } 
                            });
                    }
            });
		
           /**** reporting manager load data ***/ 
             $(document).on('change','#reporting_manager',function(){
                     $('.employee_list').show();
                 var  reporting_id = $('#reporting_manager').select2('val');
                $.get('reportingchange?employee_id='+reporting_id, function(data)
                {
                  
                    var data = data;
                     $('#assing_employee').html(data['reporting_list']);
                     $('.employee_list').html(data['result']);
                });
            
            
	    });


$(".edit_btn").click(function(){
var form=$("#save");
   form.parsley().destroy();

 });
 
</script>

@endpush
