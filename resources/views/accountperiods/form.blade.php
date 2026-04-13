@extends('layouts.header')
@section('content')
<h3 class="text-danger"> Account Periods </h3>
@include('layouts.breadcrumb')

<form method="post" action="" id="accperiod_form" data-parsley-validate>
    {{ csrf_field() }}

    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center"></div>

        <div class="card-body">
            <input type="hidden" class="account_period_id" id="account_period_id" 
                   name="account_period_id" value="{{ $row->account_period_id }}" readonly>

            <div class="row g-4">
                <!-- Left column -->
                <div class="col-md-6">

                    <div class="mb-3">
                        <label class="form-label"><span class="text-danger">*</span> Month</label>
                        <select name="month" class="form-select select2 month" required>
                            {!! $month !!}
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><span class="text-danger">*</span> Year</label>
                        <input type="text" id="year" name="year" 
                               class="form-control year" value="{{ $row->year }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Period Name</label>
                        <input type="text" id="period_name" name="period_name" 
                               class="form-control period_name chckclick" 
                               value="{{ $row->period_name }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">From Date</label>
                        <input type="text" id="from_date" name="from_date" 
                               class="form-control datepicker from_date" 
                               value="{{ $row->from_date }}">
                    </div>
                </div>

                <!-- Right column -->
                <div class="col-md-6">

                    <div class="mb-3">
                        <label class="form-label">To Date</label>
                        <input type="text" id="to_date" name="to_date" 
                               class="form-control datepicker to_date" 
                               value="{{ $row->to_date }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Quarter No</label>
                        <input type="text" id="quarter_no" name="quarter_no" 
                               class="form-control quarter_no" 
                               value="{{ $row->quarter_no }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Period Status</label>
                        <select name="period_status" id="period_status" class="form-select select2 period_status">
                            <option value="">-- Please Select --</option>
                            <option value="OPEN" {{ $row->period_status=="OPEN" ? "selected" : "" }}>OPEN</option>
                            <option value="CLOSE" {{ $row->period_status=="CLOSE" ? "selected" : "" }}>CLOSE</option>
                            <option value="FINALLY CLOSED" {{ $row->period_status=="FINALLY CLOSED" ? "selected" : "" }}>FINALLY CLOSED</option>
                        </select>
                    </div>

                    <div class="mb-3 none">
                        <label class="form-label">Company Name</label>
                        <select name="company_id" class="form-select company_id select2">
                            {!! $company_id !!}
                        </select>
                    </div>

                    <div class="mb-3 none">
                        <label class="form-label">Created By</label>
                        <select name="created_by" class="form-select created_by select2">
                            {!! $created_by !!}
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer with actions -->
        <div class="card-footer text-center">
            <input type="hidden" name="submit_type" class="submit_type" value="">
            <button type="button" class="btn btn-success px-4 me-2 saveform">Save</button>
            <a href="{{ url($pageModule) }}" class="btn btn-secondary px-4">Cancel</a>
        </div>
    </div>
</form>


          
@endsection
@push('scripts')

<script>

    $(document).ready(function(){

        
    var company = '<?php echo Session::get('companyid'); ?>';
    $('.company_id').val(company).change();
    
	$('.company_id').css("pointer-events","none");
            

<?php 

$selectedMonth = ($row->account_period_id == "") ? "" : $row->month;
?>


	function loadDropdown(selector, url, selectedValue = "") {
		$.ajax({
			url: url,
			type: 'GET',
			success: function (data) {
				if (typeof data === "string") {
					try {
						data = JSON.parse(data);
					} catch (e) {
						console.error("Invalid JSON response:", data);
						return;
					}
				}

				var $dropdown = $(selector);
				$dropdown.html('<option value="">-- Please Select --</option>');

				$.each(data, function (i, item) {
					let selected = (item.val == selectedValue) ? 'selected' : '';
					$dropdown.append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
				});

				// Trigger change event (useful if select2 applied)
				$dropdown.trigger('change.select2');
			}
		});
	}

	$(document).ready(function () {
		var url = "{{ URL::to('jcomboformlogin') }}?table=a_lookuplines_t:lookup_code:lookup_code"
				+ "&parent=lookup_type='MONTH'"
				+ "&order_by=lookuplines_id";

		// Load dropdown with preselected value (if any)
		loadDropdown(".month", url, "{{ $selectedMonth }}");
	});

		
 /* Purpose :For Concat month and year in Period name*/	
		
            $(document).on('change','.month',function(){
            var month_name = "";
            var concat_code='';
            month_name = $('.month option:selected').text().split(' ')[0];
            var year_name = $('.year').val();
            if ($('.month option:selected').text() != '-- Please Select --'){
                  month_name = $('.month option:selected').text();
             }
             else {
                 month_name ="";
             }

          concat_code = month_name + '-' + year_name;
        
          $('.period_name').val(concat_code);
    });
    
    $('.year').keyup(function(){
	$('.month option:selected').trigger('change');
        });
    /*END*/
    
    
	$(document).on('change','.year,.month',function(){
	var month=$('.month').val();
	var year =$('.year').val();
	if(year!=""&& month!=""){
		var url="{{url::to('getcurrentdate')}}"+"/"+year+"/"+month;
	$.get(url,function(data){
		$('#to_date').val(data.to_date);
		$('#from_date').val(data.from_date);
		$('.quarter_no').val(data.quater);
	 })
	}
	});

 /*Karthigaa Purpose For Save Function*/      
      
    $(document).on('click', '.saveform', function() {
    var btnval = $(this).val();
    $('#savestatus').val(btnval);
    var url = "{{ url('accountperiodssave') }}";
    var red_url = "{{url('accountperiods')}}"
    var formdata = $('#accperiod_form').serialize();
    var form = $('#accperiod_form');
            form.parsley().validate();
            var form = $('#accperiod_form');
            form.parsley().validate();
            if (form.parsley().isValid())
            {
            $.post(url, formdata, function(data)
            {
            var status = data.status;
            var msg =  data.message;
            var id = data.id;
            var edit_url = "{{ url('accountperiodscreate') }}/" + id;
            if (btnval != 'SAVE' && btnval != 'DRAFT')
            {
            showCustomAlert(msg,status);
            setTimeout(function(){
            window.location.href = red_url;
            }, 1500);
            }
            else
            {
            showCustomAlert(msg,status);
            setTimeout(function(){
            window.location.href = red_url;
            }, 1500);
            }
            });
            }
    });
    });

</script>


@endpush
