@extends('layouts.header')
@section('content')
<h3 class="text-danger"> {{$pagemodule}} </h3>
@include('layouts.breadcrumb')

<style>

<?php if($pagemodule=='CREATE JOBCARD') { ?>
	.plan_start_date,.plan_end_date{
	pointer-events:none;	
	}
<?php } ?>


  .table thead th { vertical-align: middle; }
  .table td input[readonly].form-control { cursor: default; }
  .table td:first-child { width: 36px; white-space: nowrap; }
  .pdtdiv input[readonly] { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 420px; }
  .pdtdiv input[readonly]:hover { overflow: visible; white-space: normal; }



</style>


<form method="post" action="" id="productionplan" data-parsley-validate>
<input type="hidden" value="" name="savestatus" id="savestatus" />

{{ csrf_field() }}

<div class="card shadow-lg rounded-4 border-0">
<div class="card-header bg-primary text-white fw-semibold"></div>
	
<div class="card-body card-block headerdiv1">
	
	<?php  if($pagemodule =="PRODUCTION ANALYSE" || $url=="planapproval") {  
		?>
      <div class="form-group row" style="display:none;">
   <label class="form-control-label col-md-5" for="order_status_id">Plan Status</label>
        <div class="col-md-7">
            <select name='plan_status' rows='5' id="plan_status" class="form-control" readonly="readonly" style="pointer-events: none;">
               
                <option  <?php if($row->plan_status =="INITIATED") { echo "selected"; } else {echo ""; } ?> value="INITIATED">INITIATED</option>
                <option  <?php if($row->plan_status =="APPROVED") { echo "selected"; } else {echo ""; } ?> value="APPROVED">APPROVED</option>
                <option  <?php if($row->plan_status =="REJECTED") { echo "selected" ; } else {echo ""; } ?> value="REJECTED">REJECTED</option>
                
            </select>
        </div>
    </div>
            <?php  }  ?>  
<div class="container-fluid">
    <div class="row g-3">
        <!-- MRP PLAN Module -->
        <?php if($pagemodule =="MRP PLAN"){ ?>
        <div class="col-md-6">
            <div class="card shadow-sm rounded-3 p-3">
                <div class="mb-3 row align-items-center none">
                    <label class="col-sm-4 col-form-label">Product</label>
                    <div class="col-sm-8">
                        <select name="product_id" class="form-select select2 product_id" required>
                            {!! $productid !!}
                        </select>
                    </div>
                </div>

                <div class="mb-3 row align-items-center none">
                    <label class="col-sm-4 col-form-label">Production Qty</label>
                    <div class="col-sm-8">
                        <input type="text" name="production_qty" id="production_qty" class="form-control production_qty"
                            value="{{ $quantity }}">
                    </div>
                </div>
            </div>
        </div>
		

		
        <div class="col-12">
            <div class="d-flex justify-content-center mt-3">
                <button type="button" class="btn btn-primary px-4 save saveform" value="MRP INDENT">
                    <i class="bi bi-gear"></i> Generate Indent
                </button>
            </div>
        </div>
        <?php } else { ?>
        
        <!-- Other Modules -->
        <div class="col-md-12">
            <div class="card shadow-sm rounded-3 p-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <?php if($pagemodule =="PRODUCTION ANALYSE" || $url=="planapproval") { ?>
                        <div class="mb-3 none">
                            <label class="form-label">Product</label>
                            <select name="product_id" class="form-select select2 product_id" required>
                                {!! $productid !!}
                            </select>
                        </div>
                        <?php } ?>

                        <div class="mb-3">
                            <label class="form-label">Plan No</label>
                            <input type="hidden" name="productionplan_hdr_id" value="{{ $row->productionplan_hdr_id }}">
                            <input type="hidden" id="assproductid" value="{{ $assproductid }}">
                            <input type="text" name="plan_no" class="form-control plan_no" value="{{ $row->plan_no }}" readonly>
                        </div>

                        <div class="mb-3 none">
                            <label class="form-label">Plan Date</label>
                            <input type="text" class="form-control datepicker plan_date" name="plan_date"
                                value="{{ $row->plan_date }}">
                        </div>

                        <?php if($pagemodule =="PRODUCTION ANALYSE" || $url=="planapproval") { ?>
                        <div class="mb-3 none">
                            <label class="form-label">Workorder Due Date</label>
                            <input type="text" class="form-control datepicker workorder_due_date" name="workorder_due_date"
                                value="{{ $row->workorder_due_date }}">
                        </div>
                        <?php } ?>
                    </div>

                    <div class="col-md-4">
                        <?php if($pagemodule =="PRODUCTION ANALYSE" || $url=="planapproval") { ?>
                        <div class="mb-3 none">
                            <label class="form-label">UOM Code</label>
                            <select name="uom_code_id" class="form-select uom_code_id select2">
                                {!! $uom_code_id !!}
                            </select>
                        </div>
                        <?php } ?>

                        <div class="mb-3">
                            <label class="form-label text-danger">* Start Date</label>
                            <input type="text" name="start_date" class="form-control plan_start_date" value="{{ $row->start_date }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-danger">* End Date</label>
                            <input type="text" name="end_date" class="form-control plan_end_date" value="{{ $row->end_date }}" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <?php if($pagemodule =="PRODUCTION ANALYSE" || $url=="planapproval") { ?>
                        <div class="mb-3">
                            <label class="form-label">Production Qty</label>
                            <input type="text" name="production_qty" class="form-control" value="{{ $quantity }}" readonly>
                        </div>
                        <?php } ?>

                        <div class="mb-3">
                            <label class="form-label">WO Reference No</label>
                            <input type="hidden" name="reference_id" value="{{ $row->reference_id }}">
                            <input type="text" name="reference_no" class="form-control" value="{{ $row->reference_no }}" readonly>
									<input type="hidden" name="reference_line_id" id="reference_line_id" class="form-control reference_line_id" value="{{ $row->reference_line_id }}" readonly >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Remarks</label>
                            <input name="remarks" type="text" class="form-control remarks" value="{{ $row->remarks }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
	
	
<div class="row mt-2">
	<div class="col-md-12">
	<?php if($pagemode=="edit"){?>
		<button type="button" class="btn btn-primary jobcard mt-2 px-4"> Jobcard</button>	
	<?php }?>
</div>
</div>
<!-------------------------Linedata -------------------------------->
<div class="row mt-2">
<div class="col-md-12 mt-4">	

<div class="lines_Datas lines_data">
<?php echo $lines; ?>	
	</div>
</div>
</div>

<!-------------------------Linedata End-------------------------------->

        <!-- Buttons -->
        <div class="col-12 mt-4">
            <div class="d-flex justify-content-center gap-2">
                <?php if($pagemode!="edit"){ ?>
                    <?php if($returnurl=='PRODUCTION ANALYSE'){ ?>
                        <button type="button" class="btn btn-success saveform px-4 me-2" value="SAVE">
                         Submit
                        </button>
                    <?php } else { ?>
                        <button type="button" class="btn btn-success saveform px-4 me-2" value="APPROVED">
                            <i class="bi bi-hand-thumbs-up"></i> Approve
                        </button>
                        <button type="button" class="btn btn-danger saveform px-4 me-2" value="REJECTED">
                            <i class="bi bi-hand-thumbs-down"></i> Reject
                        </button>
                    <?php } ?>
                    <a href="{{ URL::to($close) }}" class="btn btn-danger px-4 me-2">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

	
</div>


@endsection
@push('scripts')	
	
<script src="https://cdn.jsdelivr.net/gh/sathomas/jquery-aCollapTable/jquery.aCollapTable.min.js"></script>
<script src="{{ asset('js/jquery.aCollapTable.min.js') }}"></script>
	
<script>
	
$(document).ready(function()
{

$(".hove").hover(function() {
var data=$(".product_id option:selected").text();
	 $(this).css('cursor','pointer').attr('title', data);
});
	
	// purpose mrp plan details load
	
	  $(document).on('change','.production_qty,.product_id',function()
        {
		 var production_qty= $('.production_qty').val();
		  var pdt_id = $('.product_id').val();
		
		  if(production_qty!='' && pdt_id!=''){
		  var returnurl="<?php echo $returnurl; ?>";
		                $.get("{{ URL::to('bomdetails') }}/"+pdt_id+"?production_qty="+production_qty+"&source="+returnurl,function(data)
			{
						       $('.hidesave').attr('disabled',false);
		        $('.lines_Datas').html(data);

                     
			});
		  }
	  });
	

/* purpose:allow approve based on qoh*/
	<?php if($returnurl=="PLAN APPROVAL") {
	         if($approval>0){ ?>
     	showCustomAlert('QOH is less than production qty so cant approve','info');
	$('.approve').attr("disabled",true);
		<?php	 }
	  }?>


/*purpose:to set disable fields when approval*/
	$('.rdnly').css("pointer-events",'none');
	var status='{{$status}}';
	if(status=="1"){
		$('#productionplan input').css('pointer-events', 'none');
		$('#productionplan select').css('pointer-events', 'none');
		$('.product').css('pointer-events', 'none');
		$('#remarks').css('pointer-events', '');
		
	}

   
/* purpose: load bom details based on production product */	
 <?php if($pagemode!="edit" && $pagemode!="mrpplan" && $pagemode!="approval"){ ?>
		var pdt_id = $('.product_id').val();
		var woid = $('.reference_id').val();
		var returnurl="<?php echo $returnurl; ?>";
	
		$('.collaptable').aCollapTable({
    startCollapsed: true,
    addColumn: false, 
    plusButton: '<span class="i">+</span>', 
    minusButton: '<span class="i">-</span>' 
  });  

		if(pdt_id !='')
		{      
	             
  		}
		else
		{
			$('.productionplan_table tbody tr:not(:first)').remove();
		
		}
		<?php } else if($pagemode=="approval"){ ?>
		var pdt_id = $('.product_id').val();
		var woid = $('.reference_id').val();
		var returnurl="<?php echo $returnurl; ?>";
		if(pdt_id !='')
		{      
	   
	  $('.collaptable').aCollapTable({
    startCollapsed: true,
    addColumn: false, 
    plusButton: '<span class="i">+</span>', 
    minusButton: '<span class="i">-</span>' 
  }); 
  		}

<?php } else if($pagemode=="edit"){ ?>

	
 	$('.collaptable').aCollapTable({
    startCollapsed: true,
    addColumn: false, 
    plusButton: '<span class="i">+</span>', 
    minusButton: '<span class="i">-</span>' 
  }); 
	
	<?php }?>

	

	$('.nobom').each(function()
	{
	$('.hidesave').attr('disabled',true);
	});

/* purpose to update plan status	*/
	$(document).on('click','.approved',function(){
            $(".plan_status").val("APPROVED").change();
	});

        $(document).on('click','.rejected',function(){
            $(".plan_status").val("REJECTED").change();
	});
/*end*/	   
/* purpose to create jobcard*/        
	$(document).on('click','.jobcard',function(){
		var isChecked =$('.job').prop('checked')?true:false;
		
		if($('input:radio:checked').length > 0)
		{
		var plid=  $('.job:checked').val();
               var batch=$('.batch_no').val();
               var hdr =$('.job:checked').data('hdr');
               var sub =$('.job:checked').data('sub');
               var pqty =$('.job:checked').data('qty');
               var prodqty =$('.job:checked').data('prodqty');
               var prodqtycr =$('.job:checked').data('prodqty');
			   var prodqtycur =$('.job:checked').data('prodqty');
			     var qoh =$('.job:checked').data('qoh');
               var process =$('.job:checked').data('process');
               var jobstatus =$('.job:checked').data('jobcardstatus');
               var key1 =parseInt($('.job:checked').data('key'));
               var pendingqty =parseFloat($('.job:checked').data('pendingqty'));
			   var product=$('.job:checked').val();
			   /*deepika purpose:check jobcard already created for previous process in packing*/
			var status = $('.job'+(key1-1)).data('jobcardstatus');
			var prodqty = $('.job'+(key1-1)).data('prodqty');
			var qohval = $('.job'+(key1-1)).data('qoh');
				var tblcnt=$('tr:visible').length-1;
			if(key1==0){
			   var status = $('.job'+(tblcnt-1)).data('jobcardstatus');
			   var prodqty = $('.job'+(tblcnt-1)).data('prodqty');
			    var qohval = $('.job'+(tblcnt-1)).data('qoh');
			   }else
			   if(key1==1){
				  var status = 1;	
				   var qohval=1;
				     var prodqty =$('.job:checked').data('prodqty');
					 if(prodqty==""){
                    prodqty=pqty-prodqty;
					 }
						}
			/*end*/
		       var a=0;
/* isac purpose:to check qoh when create job*/		
	$(".bulk_capacity_qty"+product+process).each(function(key,value){
	
		
	if(key==0)
	{
		a=parseFloat($(this).val()?$(this).val():0);
	}
	else
	{
		if(a>parseFloat($(this).val()?$(this).val():0))
		{
			a=$(this).val();
		}
	}
	
	});	
			if(jobtype=='production'){
			if(key1!=0)
			{
				key1=key1-1;
				
				if($(".bulk_capacity_qty"+key1).val()=='')
				{
					a=0;
				}
				else if($(".bulk_capacity_qty"+key1).val() < a)
				{
					a=$(".bulk_capacity_qty"+key1).val();
				}
				
			}
			}else{
				a=a;
			}
		/*end*/
			var jobtype="<?php echo $jobtype; ?>";
			if(jobtype=='production'){
				  var prodqty1 =$('.job:checked').data('prodqty');
				/* jobcard for production */
				if(jobstatus!=1 || prodqty1!=0 ){
				if(a!=0){
		var url="{{URL::to('jobcardfromplan')}}/";
		window.location.replace(url+plid+"?source=PLAN&batch="+batch+"&hdr="+hdr+"&sub="+sub+"&capacityqty="+a+"&pqty="+pqty+"&prs="+process+"&jobtype="+jobtype);
				}else{
				showCustomAlert("Qoh not available, You Can't Create Job Card..",'error');	
				}
				}else{
				showCustomAlert("Job Card already created For this product",'info');		
				}
				/*end*/	
	}else{
		/*jobcard for packing*/
	var pqty1=0;var curprodqtystatus;
		if(key1==1){
		pqty1=pendingqty;
		}else if(prodqtycur!=prodqty) {			pendingqty=pendingqty;
			prodqty1=prodqty;console.log(prodqty1);
			pqty1=prodqty1-pendingqty;
			if(pqty1>0 && pendingqty!=0){
			   pqty1=prodqty1-prodqtycr;
			   }else if(pendingqty!=0){
				pqty1=prodqty1;	 
				 }else
				 {
					pqty1=0; 
				 }
			 curprodqtystatus=0;
		}
		else
		{
			curprodqtystatus=1;
		}
		 var pqty1 =parseFloat($('.job:checked').data('pendingqty'));
		process="";
			if(jobstatus!=1 || pqty1!=0){
			    	var url="{{URL::to('jobcardfromplan')}}/";
		window.location.replace(url+plid+"?source=PLAN&batch="+batch+"&hdr="+hdr+"&sub="+sub+"&capacityqty="+a+"&pqty="+pqty1+"&prs="+process+"&jobtype="+jobtype);

	}else{
				showCustomAlert("Job Card already created  For this product",'info');		
				}
				/*end*/	
	}
		}else{
		showCustomAlert("Please Check any Product",'info');
	}
	});

		/* purpose:to call save function & validate form*/ 
	
	 $('#savestatus').val('');
    $(document).on('click','.saveform',function()
    {
            var btnval		= $(this).val();
		if(btnval == 'APPROVED')
		{
			$('.remarks').attr('required',false);
                $("#plan_status").val('APPROVED');
		}
           else if (btnval == 'REJECTED')
		   {
			   $('.remarks').attr('required',true);
			    $("#plan_status").val('REJECTED');
		   }
            else
			{
				$('.remarks').attr('required',false);
                $("#plan_status").val('INITIATED');
			}
		
            var url		= "{{ url('productionplansave') }}";
            var red_url		="{{ url($close) }}";
            var create_url	="{{ url('productionplancreate') }}/0";

            var form = $('#productionplan');

                form.parsley().validate();
                var form = $('#productionplan');
                form.parsley().validate();
              
                if (form.parsley().isValid())
                {
                    var $btn = $(this);            
                    $btn.prop('disabled', true);
					var formdata	= $('#productionplan').serialize();
                    $.post(url,formdata,function(data)
                    {
                        var status      = data.status;
                        var msg     =     data.message;
                        var id          = data.id;
                        var edit_url	= "{{ url('productionplancreate') }}/"+id;
                        if(btnval =='SAVE'  ||  btnval =='APPROVED' ||  btnval =='REJECTED' || btnval == 'SAVE INDENT' || btnval ==  "MRP INDENT")
                        {
							if(btnval == 'SAVE INDENT')
							{

								red_url = "{{URL::to('productionindentcreate')}}/0?production="+id;
								/*end*/
							}
							else if(btnval ==  "MRP INDENT"){
								
								red_url = "{{URL::to('productionindentcreate')}}/0?production="+id+"&status=MRP";
								msg="INDENT CREATED SUCCESSFULLY";
							}
                            showCustomAlert(msg,status);
                            setTimeout(function(){
                            window.location.href=red_url;
                            }, 1500);
                        }
                    });
				
                }
            
    });

	/* purpose:to check product already selected*/
        $(document).on('change','.bulk_product_id',function()
        {
          var product_id = $(this).val();
          var index = ($(this).closest('tr').index());
          var url = "{{ URL::to('workorderuom') }}/"+product_id;
			if(product_id !='')
	{
			var pdtcount = 0;
			var pdtcount = pdtcheck(product_id,index);
			if(pdtcount <= 0)
			{
               $.get(url , function(data)
					 {
			     var data = $.trim(data);
                 $('.bulk_uom_code_id'+index).select2('val',[data]);
               });
			}else
			{
				showCustomAlert('Product Already Selected','info');
				$(".bulk_product_id" + index).val('').change();
				event.preventDefault();
			}
	}
        });
/*end*/
	});

    $(document).on("focus", ".plan_start_date", function () {

        $(this).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd-mm-yy",
            minDate: 0, 
            showAnim: "slideDown",
            yearRange: "-25:+0",

        });
    });

        $(document).on("focus", ".plan_end_date", function () {

        $(this).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd-mm-yy",
            minDate: 0, 
            showAnim: "slideDown",
            yearRange: "-25:+0",

        });
    });
	

</script>

@endpush