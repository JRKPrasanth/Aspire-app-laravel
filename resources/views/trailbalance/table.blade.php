@extends('layouts.header')
@section('content')



<div id="accordion">
<div class="panel-heading" role="tab" id="headingOne">
    <h4 class="panel-title">
		
		<a role="button"><span class="header_part">Trial Balances</span></a>    </h4>
		<span class="ui_close_btn"><a class="collapse-close pull-right btn-danger close_vendor"></a></span>
</div>
</div>



<div class="panel panel-visible" id="spy1">

	<div class="panel-title ">
  <div class="row">
  <div class="col-md-6 col-md-offset-3">
      <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-3">Choose Year</label>
            <div class="col-md-4">
                <select name="active" id="active" rows="5" class="select2 " tabindex="-1" aria-hidden="true">
                    <option value="4">April - 2018</option>
                    <option value="5">Mat - 2018</option>
                    <option value="6">June - 2018</option>
                    <option value="7">July - 2018</option>
                    <option value="8">Auguest - 2018</option>
                    <option value="9">Sepetember - 2018</option>
                    <option value="10">Octomber - 2018</option>
                    <option value="11">November - 2018</option>
                    <option value="12">December - 2018</option>
                    
                </select>
            </div>
            <div class="col-md-2">
				<button type="button" class="btn-primary view">View</button>
		  </div>
		  
		  
           
           
            
     </div>
	  
	  
     
    
              
    </div>
</div>
</div>

	<div class="row">
            <div class="col-md-12">
            <hr class="xlg">
            </div>
	</div>
	
	<div class="sbox">   
             <div class="sbox-content">  
				 	<div class="testing" style="margin-left:532; margin-top:30px;">
						 <p class="report-title"><b> Trial Balance</b></p>
						<p class="report-title2 date_report"></p>    
				 </div>

                            <div class="" id="table">	
    <div class="col-md-12" style="height:160px;">	
<div class="test"></div>
        <div class="trail-balance" style="margin-top:20px;">
    <table>
        <tbody>
		<tr class="heading">
			<td>Account Title</td>
			<td>Debit <div class="label label-success">INR </div></td>
			<td>Credit <div class="label label-success"> INR </div></td>
        </tr>
		<tr>
		   <td><b>Total</b></td>
		   <td><b>₹ 0.00</b></td>
		   <td><b>₹ 0.00</b></td> 			   
		</tr>
		</tbody>
		</table> 
		</div>
    </div>

         <style>
        .trail-balance{
            background-color: #fff;
            max-width:1000px;
            margin:auto;
            padding:30px;
            border:1px solid #ccc;
            box-shadow: 10px 10px 10px #ccc;
            font-size:16px;
            line-height:24px;
            font-family:'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color:#555;

        }

        .trail-balance table{
            width:100%;
            /*text-align:left;*/
        }

        .trail-balance table td{
            padding:5px;
            vertical-align:top;
        }

        .trail-balance table tr.top table td{
            padding-bottom:20px;
        }




        .trail-balance table tr.heading td{
        background: #e8e8e8;
        border-bottom: 1px solid #dcdcdc;
       color: #000;
        }
        .trail-balance table tr.value td{

        border-bottom: 1px solid #e8e8e8;
        font-size: 12px;

        }



        </style></div>
                            <div style="clear:both "></div>

        </div>
    </div>

	<div class="row">
            <div class="col-md-12">
            <hr class="xlg">
            </div>
	</div>


</div>




<script type="text/javascript">
$( document ).ready(function() {
	
	$(document).on('click','.view',function(){
		
		
			var month = $('#active').select2('val');
			var year = "2018";
			var url = "{{('getdate')}}?month="+month+"&year="+year;
			$.get(url,function(data){
				
				(data[0][2]);
				(data[1][2]);
				var x = data[0]+" To "+data[1];
				$('.date_report').html(x);
			});
		
	});

});
  </script>
@endsection
