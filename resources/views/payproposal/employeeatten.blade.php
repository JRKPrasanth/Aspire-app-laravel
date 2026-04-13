@extends('layouts.header')
@section('content')
<h3 class="text-danger">Employee Attendence Report</h3>
@include('layouts.breadcrumb')

<div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white rounded-top">
        <h5 class="mb-0">Report Filters</h5>
    </div>
    <div class="card-body">
        <div class="row g-4">
            <!-- Month -->
			<div class="col-md-2"></div>
            <div class="col-md-4">
                <label for="month" class="form-label">Month</label>
                <select id="month" class="form-select select2">
                    <!-- Options here -->
                </select>
            </div>

            <!-- Year -->
            <div class="col-md-4">
                <label for="year" class="form-label">Year</label>
                <select id="year" class="form-select select2">
                    <!-- Options here -->
                </select>
            </div>
        </div>

        <!-- Search Button -->
        <div class="row mt-4">
            <div class="col text-center">
                <button name="submit" type="button" class="btn btn-primary report_search px-4">
                    <i class="fa fa-search"></i> Search
                </button>
                <div class="loader mt-3" align="center"></div>
            </div>
        </div>
    </div>
</div>



 <div class="report"></div>


@endsection
@push('scripts')




<script>
	
    $(document).ready(function()
    {

					var min = 2023,
					max = new Date().getFullYear(),
					select = document.getElementById('year');

				for (var i = max; i>=min; i--){
					var opt = document.createElement('option');
					opt.value = i;
					opt.innerHTML = i;
					select.appendChild(opt);
					
				}
		

var monthUrl = "{{ URL::to('jcomboformlogin') }}?table=month:id:description&order_by=id";

$.ajax({
    url: monthUrl,
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

        $('#month').html('<option value="">-- Select Month --</option>');

        $.each(data, function (i, item) {
            let selected = item.val == "<?php echo date('m'); ?>" ? 'selected' : '';
            $('#month').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
        });

        $('#month').trigger('change.select2');
    }
});


         $(document).on('click', '.report_search', function () {
		$('.loader').show();
        
        var month = $('#month').select2('val');
        var year = $('#year').select2('val');
         if(month=="")
             month=0;
          if(year=="")
             year=0;
         if(month!=0 && year!=0)
         {
             
             var url="{{URL::to('attendancereportdata')}}?month="+month+"&year="+year;
					$.get(url,function(data){
						$('.report').html(data);
						$('.loader').hide();
					});   
		 }
      
        else{
             notyMsg("info","Please Choose Feilds")
         }
    });
      
      });
    
    </script>

@endpush
