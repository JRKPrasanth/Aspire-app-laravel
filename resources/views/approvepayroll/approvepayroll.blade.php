@extends('layouts.header')
@section('content')
<h3 class="text-danger">Approve Payroll</h3>
@include('layouts.breadcrumb')


<div class="card shadow-lg rounded-4 border-0">
	<div class="card-header bg-success text-white fw-semibold"></div>
  <div class="card-body">
    <div class="row g-3 align-items-end">

      <!-- Company -->
      <div class="col-md-2">
        <label for="company" class="form-label fw-semibold">Company</label>
        <select id="company" class="form-select select2 company">
          <!-- Options go here -->
        </select>
      </div>

      <!-- Source -->
      <div class="col-md-2">
        <label for="source" class="form-label fw-semibold">Source</label>
        <select id="source" class="form-select select2 source">
          <!-- Options go here -->
        </select>
      </div>

      <!-- Month -->
      <div class="col-md-2">
        <label for="month" class="form-label fw-semibold">Month</label>
        <select id="month" class="form-select select2 month">
          <!-- Options go here -->
        </select>
      </div>

      <!-- Year -->
      <div class="col-md-2">
        <label for="year" class="form-label fw-semibold">Year</label>
        <select id="year" class="form-select select2 year">
          <!-- Options go here -->
        </select>
      </div>

      <!-- Approve Button -->
      <div class="col-md-2">
        <button type="button" class="btn btn-success w-100 approvesource">
          <i class="fa fa-check-circle me-1"></i> Approve Payroll
        </button>
      </div>

    </div>
  </div>
</div>


<button type='button'  class='btn btn-success mt-4 approved_selected'><i class="bi bi-check2-circle"></i> Approve Selected</button>


<div class="card shadow-lg rounded-4 border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-3">
    </div>
    <div class="table-responsive">
      <table id="payrolltbl" class="table table-bordered table-striped" style="width:200% !important;">
        <thead>
  <tr class="table-warning">
            <th><input type="checkbox" id="select_all"></th>
            <th>Employee Number</th>
            <th class="freeze">Employee Name</th>
            <th>Status</th>
            <th>Month</th>
            <th>Year</th>
            <th>Department Name</th>
            <th>Working Days</th>
            <th>Basic</th>
            <th>DA</th>
            <th>HRA</th>
            <th>Annual Allowance</th>
            <th>Voluter PF</th>
            <th>ESI Value</th>
            <th>PF Value</th>
            <th>Professional tax</th>
            <th>Payroll Type</th>
            <th>Esi</th>
            <th>PF</th>
            <th>Professional tax</th>
            <th>Gross Pay</th>
            <th>Net Amount </th>
            <th>CTC Pay</th>
  </tr>
  <tr class="table-info">
	<th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
    <th class="freeze"><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
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

        <tbody></tbody>
      </table>
    </div>
  </div>
</div>

@endsection
@push('scripts')

<script>
	
// on change 
  // year
        var min = 2024,
					max = new Date().getFullYear(),
					select = document.getElementById('year');

				for (var i = max; i>=min; i--){
					var opt = document.createElement('option');
					opt.value = i;
					opt.innerHTML = i;
					select.appendChild(opt);
					
				}		
		
	// month	
	var url = "{{URL::to('jcomboformlogin?table=month:id:description') }}&order_by=id";

			$.ajax({
				url: url,
				type: 'GET',
	success: function (data) {
		// Parse JSON string if needed
		if (typeof data === "string") {
			try {
				data = JSON.parse(data);
			} catch (e) {
				console.error("Invalid JSON response:", data);
				return;
			}
		}

		$('.month').html('<option value="">-- Select Month --</option>');

		$.each(data, function (i, item) {
			let selected = item.val == "{{ $row->month ?? '' }}" ? 'selected' : '';
			$('.month').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
		});

		$('.month').trigger('change.select2'); 
	}		
		
	});			
	
	// source
var condition = 'lookup_type="payroll_type"';
var sourceUrl = "{{ URL::to('jcomboformlogin') }}?table=a_lookuplines_t:lookuplines_id:lookup_code&order_by=lookuplines_id&parent=" + encodeURIComponent(condition);
loadDropdown("#source", sourceUrl, '', '-- Select Source --');

//company
var companyid = "{{ \Session::get('companyid') }}";
var companyUrl = "{{ URL::to('jcomboformlogin') }}?table=m_company_t:company_id:company_code";

loadDropdown("#company", companyUrl, companyid, '-- Select Company --');
	
	
	
function loadDropdown(selector, url, selectedValue, defaultText = "-- Select --") {
    $.ajax({
        url: url,
        type: 'GET',
        success: function (data) {
            if (typeof data === "string") {
                try {
                    data = JSON.parse(data);
                } catch (e) {
                    console.error("Invalid JSON format:", data);
                    return;
                }
            }

            $(selector).html(`<option value="">${defaultText}</option>`);
            $.each(data, function (i, item) {
                let selected = item.val == selectedValue ? 'selected' : '';
                $(selector).append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
            });

            $(selector).trigger('change.select2');
        }
    });
}
	
	
// table data	
$(document).ready(function () {
	
    var table = $('#payrolltbl').DataTable({
        processing: true,
        serverSide: true,

        ajax: "{{ route('employeepayrollgrid1') }}",
        columns: [
            {   // Checkbox column
                data: 'id',
                render: function (data, type, row) {
                    return '<input type="checkbox" class="row_checkbox" value="' + data + '">';
                },
                orderable: false,
                searchable: false
            },
        { data: 'employee_number' },
        { class:'freeze', data: 'first_name' },
        { data: 'approved_status' },
        { data: 'month' },
        { data: 'year' },
        { data: 'sub_department_name' },
        { data: 'attendance_days' },
        { data: 'basic_salary' },
        { data: 'da' },
        { data: 'hra' },
        { data: 'annual_allowance' },
        { data: 'volunter_pf' },
        { data: 'esi_val' },
        { data: 'pf_val' },
        { data: 'pt_val' },
        { data: 'lookup_code' },
        { data: 'esi1' },
        { data: 'pf1' },
        { data: 'pt1' },
        { data: 'gross_salary' },
        { data: 'net_salary' },
        { data: 'ctc_pay' },
        ],
        order: [[1, 'desc']]
    });

    // Select all checkboxes
    $('#select_all').on('click', function () {
        $('.row_checkbox').prop('checked', this.checked);
    });



    // Clear filter logic (like jqGrid clear)
    $(".clear").click(function () {
        $('#payrolltbl').DataTable().search('').draw();
    });
	
	  $('#payrolltbl thead').on('keyup change', ".column-search", function () {
       var index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
    });
	
});
	
	
	
                // approve from source
		  $(document).on('click','.approvesource',function()
		{	
			var company      =  $('#company').select2('val');
			var source 		 =  $('#source').select2('val');
			var month 		 =  $('#month').select2('val');
			var year         =  $('#year').select2('val');
			
                        if(company != '' && source != '' && month != '' && year!='' ){
			var url = "{{URL::to('approvepayrollsource')}}?company="+company+"&source="+source+"&month="+month+"&year="+year;
			$.get(url,function(data)
                        {
                         
                            showCustomAlert("Approved Successfully","success");
							$('#payrolltbl').DataTable().ajax.reload();
                            setTimeout(function(){
                            location.reload();
                            }, 1000);
                		
                        });
                        }
                        else{
                            showCustomAlert("Please Choose All the Fields",'info');
                        }
		});	
	
	
	
// select approve	
	
	    $(document).on('click', '.approved_selected', function () {
        var selectedIds = [];
			
        $('.row_checkbox:checked').each(function () {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length > 0) {
            var url = "{{ URL('approvedpayroll') }}/?row_id=" + selectedIds.join(',');
            $.get(url, function (data) {
                if (data == 1) {
                    showCustomAlert('Payroll Approved Successfully','success');
                  	$('#payrolltbl').DataTable().ajax.reload();
                }
            });
        } else {
            showCustomAlert('Please select a row','info');
        }
    });
	
	
</script>

@endpush
