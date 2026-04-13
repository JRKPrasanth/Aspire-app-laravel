@extends('layouts.header')
@section('content')

<style type="text/css">
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
a.btn-link {
    cursor: pointer;
}
</style>
<span class="ui_close_btn"><a class="collapse-close pull-right btn-danger close_btn close"></a></span>
<h5 class="heads">Ageing Summary By Bill Due Date</h5>
<div class="ajaxLoading"></div>
<div class="divhide">
	</div>


   
           
              <div class="card divshow">
                <div class="card-body">
                <form>
                  <div class="col-md-12">    
       <!--    <div class="col-md-6">    
           <div class="form-group row">
                  <label for="inputIsValid" class="form-control-label col-md-4">From Date</label>
                    <div class="col-md-6">
                <div class="input-group date form_date  col-md-12 "  data-link-field="dtp_input2" data-link-format="yyyy-mm-dd"> 
                    <input class="form-control from_date " id="from_date" name="from_date" size="16" type="text" value="" readonly>
                        
                     </div>
                        <input type="hidden" id="from_date" name="from_date" value="" />
                  </div>
                 <div class="col-md-2 showinline"></div>
               </div>       
          </div> -->

          <div class="col-md-6">    
           <div class="form-group row">
                  <label for="inputIsValid" class="form-control-label col-md-4">AS On Date</label>
                    <div class="col-md-6">
                  <div class="input-group date form_date col-md-12" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">                    <input class="form-control to_date " id="to_date" name="to_date" size="16" type="text" value="" readonly>
                        
                     </div>
                        <input type="hidden" id="to_date" name="to_date" value="" />
                  </div>
                 <div class="col-md-2 showinline"></div>
               </div>       
          </div>
<div class="row text-center">
          <button type='button' id="searchdata" class='btn search searchdata'> Search </button>
        </div>
      </div>
 </form>
                
                
                  
                  <div class="table-responsive">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>
                              Vendor Name
                          </th>
                          <th>
                           Current
                          </th>
                          <th>
                           Advance Amount
                          </th>
                          <th>
                           1-15 Days
                          </th>
                          <th>
                            16-30 Days
                          </th>
                          <th>
                            31-45 Days
                          </th>
                          <th>
                            >45Days
                          </th>
                         <th>
                            Total
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                       <?php // dd($result);
						  foreach($result as $key=>$value) { ?>
						  <tr >
							
						  <td class="font-weight-medium">
                          <a class="btn-link supplier" data-value="{{$value['supplier_id']}}" data-myvalue="0">{{ $value['supplier_name'] }}</a> 
                          </td>
							 <td class="font-weight-medium">
                          <a class="btn-link supplier" data-value="{{$value['supplier_id']}}" data-myvalue="1">
							  <?php 
								  if($value['current']== null){ echo "0.00";} else { echo number_format($value['current'],2);}?></a> 
							  </td>
                <td class="font-weight-medium">
                          <a class="btn-link supplier" data-value="{{$value['supplier_id']}}" data-myvalue="1">
                <?php 
                  if($value['advance_amount']== null){ echo "0.00";} else { echo number_format($value['advance_amount'],2);}?></a> 
                </td>
							  
							   <td class="font-weight-medium">
                          <a class="btn-link supplier" data-value="{{$value['supplier_id']}}" data-myvalue="2">
							 <?php 
								  if($value['lessthan16']== null){ echo "0.00";} else { echo number_format($value['lessthan16'],2);}?></a> 
							  </td>
							   <td class="font-weight-medium">
                          <a class="btn-link supplier" data-value="{{$value['supplier_id']}}" data-myvalue="3">
                         <?php 
								  if($value['lessthan31']== null){ echo "0.00";} else { echo number_format($value['lessthan31'],2);}?></a> 
                          </td>
							  <td class="font-weight-medium">
                          <a class="btn-link supplier" data-value="{{$value['supplier_id']}}" data-myvalue="4">
								<?php 
								  if($value['lessthan46']== null){ echo "0.00";} else { echo number_format($value['lessthan46'],2);}?></a> 
							  </td>
							  
							  <td class="font-weight-medium">
                          <a class="btn-link supplier" data-value="{{$value['supplier_id']}}" data-myvalue="5">
								<?php 
								  if($value['above46']== null){ echo "0.00";} else { echo number_format($value['above46'],2);}?></a> 
							  </td>
                          <td class="font-weight-medium">
                          <a class="btn-link supplier" data-value="{{$value['supplier_id']}}" data-myvalue="0">
								<?php 
								   echo number_format($value['total'],2);?></a>
							  </td>
							  
						  </tr>
                       <?php } ?>
                       <tr>
                         <td>Total</td>
                         <td>{{number_format($current,2)}}</td>
                         <td>{{number_format($advance_amount,2)}}</td>
                          <td>{{number_format($total15,2)}}</td>
                          <td>{{number_format($total30,2)}}</td>
                          <td>{{number_format($total45,2)}}</td>
                          <td>{{number_format($total46,2)}}</td>
                          <td>{{number_format($total,2)}}</td>
                       </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                
              </div>
            

     




<script type="text/javascript">
$( document ).ready(function() {
	$('.divhide').hide();

$(document).on('click','.searchdata', function(){
  $(".ajaxLoading").show();   
// var from_date=$('.from_date').val(); 
      var to_date=$('.to_date').val(); 
    var urldate="{{ URL::to('poinvoiceagingsummaryrptdate')}}?to_date="+to_date;
    $.get(urldate,function(data){
      $(".ajaxLoading").hide();   
           window.location.href=urldate;
    });    
});


	$(document).on('click','.supplier', function(ev){
			var supplier=$(this).data('value');
			var val=$(this).data('myvalue');
		var url="{{ URL::to('pendingpurchasepayment')}}?supplier_id="+supplier+"&days="+val;
		$.get(url,function(data){
			$(".divhide").html(data);
			$('.divhide').show();
			$('.divshow').hide();
		});
	});
	$(document).on('click','.close', function(ev){
		
			$('.divhide').hide();
			$('.divshow').show();
		});
	


});
$(function()
{
  
  
  // $('.from_date').datepicker({
  //   changeMonth: true,
  //     dateFormat: data,
  //     changeYear: true,   
      
  // });
  var date_now = new Date();
  var data = "<?php echo \Session('j_date_format'); ?>";
  var grid_min_date="{{\Session::get('js_griddate')}}";
  var grid_max_date="{{\Session::get('js_gridenddate')}}";
  $('.to_date').datepicker({
    					changeMonth: true,
     					dateFormat: data,
     					changeYear: true,
      					minDate: grid_min_date,
      					maxDate: grid_max_date,
      					onClose: function( selectedDate ) {
        				jQuery( "#end_date" ).datepicker( "option", "minDate", selectedDate );
        }
      
  });
//   $('.to_date').datepicker({
//     changeMonth: true,
//       dateFormat: data,
//       changeYear: true,   
//       minDate: "01-04-2019",
//             maxDate: date_now
//   });
});
  </script>
@endsection
