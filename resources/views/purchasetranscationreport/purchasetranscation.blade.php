@extends('layouts.header')
@section('content')
<style type="text/css">
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

</style>
<div class="card">


<div class="card-body card-block">
  <form method="post" action="" id="job_card_reprot" class="org_form" data-parsley-validate enctype="multipart/form-data">
 
{{ csrf_field() }}
    <div class="col-md-offset-2 col-md-4">

            <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4">From Date</label>
           <div class="col-md-6">
					<div class="input-group form_date " 	 data-date=""   data-link-format="yyyy-mm-dd">
							<input class="form-control start_date datepicker " id="start_date" name="start_date"  required type="text" value="" style="border-radius: 5px;">
							 <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
					</div>
			</div>

        </div>
      

    </div>
	  
	  <div class="col-md-4">

            <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4">To Date</label>
            <div class="col-md-6">
					<div class="input-group form_date " 	 data-date=""   data-link-format="yyyy-mm-dd">
							<input class="form-control end_date datepicker " id="end_date" name="end_date"  required type="text" value="" style="border-radius: 5px;">
							 <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
					</div>
			</div>

        </div>
      

    </div>
  

            <div class="col-lg-12 col-md-12">
                <div class="form-group text-center">
                    

                    <button type="button" class="btn save search" value="SAVE">Search</button>
                      
               </div>

            </div>
            </form>
	
            
</div>
	
	


</div>
<div class="divhide">
    
  	
    </div>
	<script type="text/javascript">
$( document ).ready(function() {
		
	    $(document).on('click','.search',function()
		{
		
			 var start_date = ($('.start_date').val() != '') ? $('.start_date').val() : '';
			 var end_date   =  ($('.end_date').val() != '') ? $('.end_date').val()  : ''; 
		    
		   
			var url="{{URL::to('pruchasetransreport')}}/?start_date="+start_date+"&end_date="+end_date;
			
			  $.get(url , function(data)
			  {
					$('.divhide').html(data);
				  
			  }); 
		   
			});
	
	
	
		});
     
	</script>
@include('layouts.php_js_validation')
@endsection




