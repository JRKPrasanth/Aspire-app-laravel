@extends('layouts.header')
@section('content')
<style type="text/css">


.divhide .card{
    padding: 5px;
    border: 1px solid #ccc;
}
.divhide .table{
    width: 100%;
}
.card-header{
    border-bottom:1px solid #ccc;
}

</style>
<h2 class="heads">Stage Wise report</h2>
<div class="card">


<div class="card-body card-block">
  <form method="post" action="" id="job_card_reprot" class="org_form" data-parsley-validate enctype="multipart/form-data">
 
{{ csrf_field() }}
<div class="row">

    <div class="col-md-4">
        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4">Work Order</label>
            <div class="col-md-6">
                            <div class="input-group">
                                            <select name='work_order' rows='5' class='select2 work_order' id="work_order" ></select>
                                             
                            </div>
            </div>
        </div>
    </div>
	  
   

    <div class="col-md-4">
        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-2">Product</label>
            <div class="col-md-10">
                    <div class="input-group" style="pointer-events: none;">
                                   <select name='product_id' rows='5' class='select2 product_id' id="product_id" ></select>
                                   </div>
            </div>
        </div>
    </div>
   <div class="col-md-4">
        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4">Job No</label>
            <div class="col-md-6" style="pointer-events:none;">
                                    <select name='job_no' rows='5' class='select2 job_no' id="job_no" ></select>
            </div>
        </div>
    </div>

            <div class="col-lg-12 col-md-12">
                <div class="form-group text-center">
                    <button type="button" class="btn save search" value="SAVE">Search</button>
               </div>
            </div>
          </div>
    </form>


<div class="row">
<div class="col-md-12 divhide">
    
    
    </div>
</div>
</div>


</div>

	<script type="text/javascript">
$( document ).ready(function() {

var condition1='group by workorder_no';
  $("#work_order").jCombo("{{ URL::to('jcomboform1?table=w_workorder_hdr_t:workorder_hdr_id:workorder_no') }}&parent="+condition1+'&order_by=workorder_no asc',{selected_value:''});
var condition2='group by concatenated_product';
   $("#product_id").jCombo("{{ URL::to('jcomboform1?table=m_products_t:product_id:product_code|concatenated_product') }}&parent="+condition2+'&order_by=concatenated_product asc',{selected_value:''});
   $(".job_no").jCombo("{{ URL::to('jcomboformcomp?table=w_jobcard_hdr_t:w_jobs_hdr_id:job_no') }}",{selected_value:''});

  $('.work_order').change(function()
  {
    var workorder_no=$('.work_order option:selected').text();
    var workorder_id=$('.work_order').val();
    if(workorder_no!='-- Please Select --')
    {
      var url="{{URL::to('getjobnorpt')}}/"+workorder_no;
      $.get(url,function(data)
      {
		  var data=$.trim(data);
		  if(data!=0){
        $('.job_no').select2('val',[data]);
	  }else{
		notyMsg("info",'Job not created for this workorder');	
		    $('.job_no').val('').change();
			}
      });
    }
	  if(workorder_id!=""){
	  var url = "{{URL::to('getjobproduct')}}/"+workorder_id;
        $.get(url,function(data)
        { var data1=$.trim(data);
		  if(data1!=0){
          $('.product_id').select2('val',[data1]);
		  }
        });
  }
  });
		
	    $(document).on('click','.search',function()
		{
		
			 var workorder_no = $('.work_order').val();
       var job_no = $('.job_no').val();
       var product_id = $('.product_id').val();
			 var end_date   =  ($('.end_date').val() != '') ? $('.end_date').val()  : ''; 
		    
		   if(workorder_no!=''){
			var url="{{URL::to('stagewiseget')}}/?workorder_no="+workorder_no+"&job_no="+job_no+"&product_id="+product_id;
			
			  $.get(url , function(data)
			  {
					$('.divhide').html(data);
				  
			  }); 
                      }
                      else{
                          notyMsg("info","Please Select Work Order No.");
                      }
			});
	
	$(function()
{
  
  
  $('.start_date').datepicker({
    changeMonth: true,
      dateFormat: data,
      changeYear: true,   
      
  });
  $('.end_date').datepicker({
    changeMonth: true,
      dateFormat: data,
      changeYear: true,   
      
  });
});	
	
		});
     
	</script>
@include('layouts.php_js_validation')
@endsection