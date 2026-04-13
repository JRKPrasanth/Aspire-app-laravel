@extends('layouts.header')
@section('content')
<!-- <style type="text/css">
th{
    width: 250px;
    text-align: center;
}
.table-responsive {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    -ms-overflow-style: -ms-autohiding-scrollbar;
}
.table-responsive > .table-bordered {
        text-align: center;
    border: 0;
}

.table {
    width: 100%;
    max-width: 100%;
    margin-bottom: 1rem;
    background-color: transparent;
}

.table.table-bordered thead {
    border: 1px solid #f2f2f2;
    border-bottom: none;
}

.table-responsive tbody tr > td:first-child {
    display: table-cell;
}
.card{
    border-radius: 5px;
    margin-bottom: 40px;
}
.card-body {
    padding: 14px 10px;
}
table tbody tr:nth-child(1) {
    background: none;
}
tbody{
    vertical-align: middle;
    font-size: 13px;
    line-height: 1;
    white-space: nowrap;
}
table-bordered td {
    border: 1px solid #f2f2f2;
}
.table th, .table td {
    padding: 18px 30px;
    
}
.table>thead:first-child>tr:first-child>th {
   color: #000;
    font-weight: bolder;
    text-align: center;
    background: none;
    padding: 10px 0px 10px 0px;
}
.table .progress{
    text-align: center;
    height: 8px;
    border-radius: 3px;
    width: 75%;
}

.table.table-bordered thead tr th {
    border-left: none;
    border-right: none;
}
.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #f2f2f2;
}
.table-bordered th, .table-bordered td {
    border: 1px solid #f2f2f2;
}
.table>tbody>tr>td{
    padding: 15px;
   
    vertical-align: middle;
    border-top: 1px solid #f2f2f2;
}
	

</style> -->
<style>
	table, th, td {
    border: 1px solid black;
}
	.card{
    border-radius: 5px;
    margin-bottom: 40px;
}
.card-body {
    padding: 14px 10px;
}
.invoice-box {
    background-color: #fff;
    margin: auto;
    margin-top: 7%;
    padding: 15px;
    border: 1px solid #ccc;
    max-width: 1000px;
    box-shadow: 3px 3px 4px #ccc;
}

.form-group {
    padding: 0px;
}
</style>

<h2 class="heads">Job Card Report <span class="ui_close_btn"><a class="collapse-close pull-right btn-danger" onclick='location.href="{{ url($pageModule) }}"'></a></span></h2>







<div class="card">


<div class="card-body card-block">
  <form method="post" action="" id="job_card_reprot" class="org_form" data-parsley-validate enctype="multipart/form-data">
 
{{ csrf_field() }}
    <div class="col-md-offset-4 col-md-4">

            <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4">Job Card No<span class="req">*</span></label>
            <div class="col-md-8">
           
           <select type="text" id="job_card_id" required name="job_card_id" class="select2 job_card_id" >{!! $jobcard_no!!}</select>
            </div>

        </div>
      

    </div>
  

            <div class="col-lg-12 col-md-12">
                <div class="form-group text-center">
                    <input type="hidden" name="submit_type" class="submit_type" id="submit_type">

                    <button type="button" class="btn save search" value="SAVE">Search</button>
                      
               </div>

            </div>
            </form>
	<div class="row report_show">
    <!--    <div class="invoice-box" id="section-to-print">
      
        <table cellpadding="0" cellspacing="0">
            <tbody>


            <h2 class="heads1">Report</h2>
            
            <tr class="information">
                <td colspan="6">

                    <table border='1' >
                                <tbody>
                                <tr>
                                    <td>
                                        <p><b>Product Name:</b> </p> <br>
                                        <p><b>Date:</b></p><br>

                                    </td>
                                </tr>
                                </tbody>
                    </table>

                    <table border='1' >
                        <tbody>
                        <tr>
                            <td >
                                <p><b>PLANNING COST</b></p> <br>
                                <p><b>Employee Code:</b>1000</p><br>
                                <p><b>Raw Material Cost:</b>1000 </p><br>
                                <p><b>Other Over heads:</b> 1000</p><br>
                            </td>
                            <td class="text-right">
                                <p><b>ACTUAL COST</b></p><br>
                                <p><b>:</b>1000</p><br>                        
                                <p><b>:</b>1000</p><br>                        
                                <p><b>:</b>1000</p><br>                        
                            </td>
                        </tr>
                    </tbody>
                </table>
                </td>
            </tr>  
           
                       

            
     
        </tbody></table>
        <br>
        <table class="table table-bordered table-hover">
                <thead>
                    <th width="25%" >Employee</th>
                    <th width="75%">&nbsp;</th>
                </thead>
                <tbody>
                    <tr>
                        <td>Employee Name</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Hour</td>
                        <td></td></tr>
                    <tr>
                        <td>Cost</td>
                        <td></td>
                    </tr>
                </tbody>
        </table>
    </div> -->
    </div>
            
</div>
	
	


</div>

	

	<script type="text/javascript">
	$(document).ready(function() 
	{
		 $(document).on('click','.search',function()
		 {    
			var form = $('#job_card_reprot');
			form.parsley().validate();
			var form = $('#job_card_reprot');
			form.parsley().validate();
			if (form.parsley().isValid())
			{			
				var job_card_id = $('#job_card_id').select2('val');
				var url	="{{URL::to('jobcardresultnew')}}/"+job_card_id;
				$.get(url,function(data1)
				{
					console.log(data1);
					$('.report_show').html(data1);
				});
			}
		});
	});     
	</script>
@include('layouts.php_js_validation')
@endsection




