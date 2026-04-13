@extends('layouts.header')
@section('content')
<h3 class="text-danger">Attandance Month Report</h3>
@include('layouts.breadcrumb')


<div class="card shadow-lg rounded-4 border-0">
	<div class="card-header bg-primary text-white fw-semibold">
     
    </div>
	<div class="card-body">
<div class="row align-items-end mb-3">
    <!-- Employee Name -->
    <div class="col-md-4">
        <label for="emp_id" class="form-label">Employee Name</label>
        <?php $groupname = \Session::get('groupname'); ?>
        <select name="emp_id" id="emp_id" class="form-select select2 emp_id"
            {{ in_array($groupname, ['1', '4', '2', '15']) ? '' : 'disabled' }}>
            {!! $emp_id !!}
        </select>
    </div>

    <!-- Year -->
    <div class="col-md-4">
        <label for="year" class="form-label">Year</label>
        <select name="year" id="year" class="form-select select2 year">
            <!-- Year options here -->
        </select>
    </div>

    <!-- Month -->
    <div class="col-md-4">
        <label for="month" class="form-label">Month</label>
        <select name="month" id="month" class="form-select select2 month">
            <!-- Month options here -->
        </select>
    </div>
</div>

<!-- Search Button -->
<div class="text-center">
    <button type="button" id="save" class="btn btn-primary rptsearch">
        <i class="fa fa-search"></i> Search
    </button>
</div>

</div>
</div>

<div class="row g-4">
    <!-- Days Summary -->
    <div class="col-md-4">
        <div class="card h-100 shadow-lg rounded-4 border-0">
            <div class="card-body">
                <h5 class="card-title text-success mb-3">Attendance Summary</h5>
                <p><strong>Calculated Days:</strong> <span class="total_days text-primary text-center fw-bold"></span></p>
                <p><strong>Present Days:</strong> <span class="present_days text-primary text-center fw-bold"></span></p>
                <p><strong>Absent Days:</strong> <span class="absent_days text-primary text-center fw-bold"></span></p>
                <p><strong>Holiday Days:</strong> <span class="holidays text-primary text-center fw-bold"></span></p>
            </div>
        </div>
    </div>

    <!-- Leave Summary -->
    <div class="col-md-4">
        <div class="card h-100 shadow-lg rounded-4 border-0">
            <div class="card-body">
                <h5 class="card-title text-primary mb-3">Leave Summary</h5>
                <p><strong>Sunday Days:</strong> <span class="weekoff text-primary text-center fw-bold"></span></p>
                <p><strong>Taken CL:</strong> <span class="casual text-primary text-center fw-bold"></span></p>
                <p><strong>Taken SL:</strong> <span class="sick text-primary text-center fw-bold"></span></p>
                <p><strong>Taken EL:</strong> <span class="earn text-primary text-center fw-bold"></span></p>
            </div>
        </div>
    </div>

    <!-- Remaining Leave Summary -->
    <div class="col-md-4">
        <div class="card h-100 shadow-lg rounded-4 border-0">
            <div class="card-body">
                <h5 class="card-title text-success mb-3">Remaining Leaves</h5>
                <p><strong>ON-DUTY:</strong> <span class="ond text-primary text-center fw-bold"></span></p>
                <p><strong>Remaining CL:</strong> <span class="remaincl text-primary text-center fw-bold"></span></p>
                <p><strong>Remaining SL:</strong> <span class="remainsl text-primary text-center fw-bold"></span></p>
                <p><strong>Remaining EL:</strong> <span class="remainel text-primary text-center fw-bold"></span></p>
            </div>
        </div>
    </div>

</div>



<div class="detail" style="margin-top:50px !important">
</div>

@endsection
@push('scripts')



<script>
	
$(document).ready(function()
{
     var min = 2024,
    max = new Date().getFullYear(),
    select = document.getElementById('year');

    for (var i = max; i>=min; i--)
    {
        var opt = document.createElement('option');
        opt.value = i;
        opt.innerHTML = i;
        select.appendChild(opt);
    }  
    	
		
		
// month		

var url = "{{ URL::to('jcomboformlogin?table=month:id:description') }}&order_by=id";

var month = "{{ date('m') }}";

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

            let selected = (item.val == month) ? 'selected' : '';

            $('.month').append(
                `<option value="${item.val}" ${selected}>${item.option_name}</option>`
            );
        });

        $('.month').trigger('change.select2');

    }
});
	

	
        $(document).on('click',".rptsearch",function() {

   			var emp_id = $("#emp_id").val();
			var month = $("#month").val();
		    var year = $('#year').val();
			
    if (emp_id != "" && month != "" && year!='')
    {
    $('.detail').html("");
    url = '{{URL::to("reportmonthatten")}}/' + emp_id + '/' + month+'/'+year;
    $.getJSON(url, function(data)
    {
    $('.detail').append(data.data);
    $('.total_days').html(data.days.total_days);
    $('.present_days').html(data.days.present_days);
    $('.absent_days').html(data.days.absent_days);
    $('.holidays').html(data.days.holidays);
    $('.weekoff').html(data.days.weekoffs);
    $('.casual').html(data.days.casual);
    $('.sick').html(data.days.sick);
    $('.earn').html(data.days.earn);
    $('.ond').html(data.days.od);
    $('.remaincl').html(data.days.remcl);
    $('.remainsl').html(data.days.remsl);
    $('.remainel').html(data.days.remel);
    });
    }else{
		
        showCustomAlert("Please select Fields",'warning');
    }
    });
});
</script>
@endpush
