@extends('layouts.header')
@section('content')

<span class="ui_close_btn"></span>




<div class="row">


<form method="post" action="" id="productionplan" data-parsley-validate>
<input type="hidden" value="" name="savestatus" id="savestatus" />

{{ csrf_field() }}
<div class="col-lg-12">
<div class="card">
<div class="card-header">
<h2>Production Plan</h2>	
	
<span class="ui_close_btn"><a href="{{ URL::to('productionplananalyse') }}" class="collapse-close pull-right btn-danger" ></a></span>
</div>
<div class="card-body card-block headerdiv1">
	<div class="col-md-12">
		<div class="col-md-4">
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-5">Plan No</label>
			<div class="col-md-7">
			<input class="form-control productionplan_hdr_id" id="productionplan_hdr_id" name="productionplan_hdr_id" size="16" type="hidden" value="{{ $row->productionplan_hdr_id }}" readonly>
				<input type="text" id="plan_no" name="plan_no" class="form-control plan_no" value="{{ $row->plan_no }}" readonly >
			</div>
		</div>	
				<div class="form-group row">
			<label for="active" class="form-control-label col-md-5">Batch No</label>
			<div class="col-md-7">

				<input type="text" id="batch_no" name="batch_no" class="form-control batch_no" value="{{ $row->batch_no}}" >
			</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-5">Plan Date</label>
			<div class="col-md-7">
			<div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
			<input class="form-control plan_date" id="plan_date" name="plan_date" size="16" type="text" value="{{ $row->plan_date }}" readonly="true">
			<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
			</div>
			<input type="hidden" id="plan_date" value="{{ $row->plan_date }}" />
			</div>
		</div>
			<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-5">Start Date</label>
			<div class="col-md-7">
			<div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
			<input class="form-control start_date" id="start_date" name="start_date" size="16" type="text" value="{{ $row->start_date }}" >
			<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
			</div>
			<input type="hidden" id="start_date" value="{{ $row->start_date }}" />
			</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-5">End Date</label>
			<div class="col-md-7">
			<div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
			<input class="form-control end_date" id="end_date" name="end_date" size="16" type="text" value="{{ $row->end_date }}" >
			<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
			</div>
			<input type="hidden" id="end_date" value="{{ $row->end_date }}" />
			</div>
		</div>
			</div>
	<div class="col-md-4">
	<div class="form-group row">
		<label for="inputIsValid" class="form-control-label col-md-5">Product</label>
        <div class="col-md-7">
            <select name="product_id" rows="5" class="form-control select2 product_id" data-show-subtext="true" data-live-search="true">
			{!! $productid !!}	
            </select>
        </div>
		
    </div>
	
	<div class="form-group row">
		<label for="inputIsValid" class="form-control-label col-md-5">Uom Code</label>
        <div class="col-md-7">
            <select name="uom_code_id" rows="5" class="form-control uom_code_id" data-show-subtext="true" data-live-search="true">
				{!! $row->uom_code_id !!}	
            </select>
        </div>
		
    </div>
		<div class="form-group row">
		<label for="inputIsValid" class="form-control-label col-md-5">Production Quantity</label>
        <div class="col-md-7">
         <input type="text" name="production_qty" id="production_qty" class="form-control production_qty col-md-7" value="{{ $row->production_qty }}">
        </div>
		
    </div>
	<div class="form-group row">
        <label for="inputIsValid" class="form-control-label col-md-5">Remarks</label>
        <div class="col-md-7">
			<textarea name="remarks" id="remarks" class="form-control remarks" row="5"></textarea>
        </div>
		
	</div>	
		
	</div>
	<div class="col-md-4">
<div class="form-group row">
			<label class="form-control-label col-md-5" for="workorder_hdr_id"> Wo Reference No</label>
			<div class="col-md-7">
			<input type="hidden" name="reference_id" id="reference_id" class="form-control reference_id" value="{{ $row->reference_id}}">
			<input type="text" name="reference_no" id="reference_no" class="form-control reference_no col-md-7" value="{{ $row->reference_no }}" readonly >
			</div>
		</div>
		
		<div class="form-group row">
    		<label class="form-control-label col-md-5" for="organization_id">Organization</label>
			<div class="col-md-7">
				<select name='organization_id'  class='form-control  organization_id' id="organization_id" readonly>
{!! $organization_id !!}
				</select>
			</div>
    		</div>
	</div>
</div>
<?php if($pagemode=="edit"){?>
<button type="button" class="btn add jobcard">Jobcard</button>	
<?php }?>
<!-------------------------Linedata -------------------------------->
<div class="row" >
	<div class="col-md-12">

<div class="table-responsive subgrid_div" style="margin-top: 20px;">
<table class="table table-striped productionplan_table table_scroll" >
<thead style="display: block;overflow: auto;">
    <tr>
        
        
        <th style="width: 75px;"><p style="width:68px;">Line No</p></th>
        <th style="width: 33px;"><p style="width:27px;">&nbsp;</p></th>
        <th class="pdtdiv"><p>Product</p> </th>
        <th><p>Uom Code</p></th>
        <th><p>Qty</p></th>
        <th><p>Qoh</p></th>
        
    </tr>
</thead>

<tbody class="productionplan_lines_body">
	<?php  if(count($linedata)>=1) { ?>
@foreach($linedata as $key=>$value)

<tr class="clone rcopy table{{$key}}">
    <td>
        <input type="hidden" name="bulk_productionplan_line_id[]" class="form-control input-sm bulk_productionplan_line_id" value="{{ $value->productionplan_line_id }}">
    </td>
   
    <td style="width: 33px !important;">
        <input type="radio" style="width: 33px !important;" class="pids" name="ids" value="<?php echo $value->productionplan_line_id;?>" <?php echo ($value->productionplan_line_id)? 'checked' :'' ?> /> </td>
    
    <td>
        <input type="text" style="width: 68px !important;" name="bulk_line_no[]" class="form-control bulk_line_no" value="{{ $key + 1 }}" readonly="readonly">
    </td>
  
    <td><i class="fa fa-plus subbomdetail" data-submodel="{{$key}}" data-index="1" data-value="{{$value->product}}"></i></td>
    
    <td class="pdtdiv">
      
        <select name="bulk_product_id[]" id="bulk_product_id" class="bulk_product_id select2" required="required">{!! $value->product_id !!}</select>
    </td>
    <td>
        <select name="bulk_uom_code_id[]" id="bulk_uom_code_id" class="select2 bulk_uom_code_id" data-show-subtext="true" data-live-search="true" style="pointer-events:none;">
            <option value=''> {!! $value->uom_code_id !!} </option>

        </select>
    </td>
    <td>
        <input type="text" name="bulk_qty[]" class="form-control input-sm bulk_qty input_qty_width" value="{{ $value->qty }}" minlength="1" maxlength="4" required="required">
    </td>
    <td>
        <input type="text" class="form-control input-sm bulk_qoh input_qty_width" value="">
    </td>
    <td style="width: 33px;">
        <a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
        <input type="hidden" name="counter[]">
    </td>
</tr>

@endforeach
<?php }?>
</tbody>
</table>

	<table class="table table-striped productionplan_subtable table_scroll" style="display:none">
<thead style="display: block;overflow: auto;">
<tr>
<th><p>Line No</p></th>
<th class="pdtdiv"><p>Product</p> </th>
<th><p>Uom Code</p></th>
<th><p>Qty</p></th>
<th><p>Qoh</p></th>
<th style="width: 33px;"><p style="width: 27px;">&nbsp;</p></th>
</tr>
</thead>
<tbody class="productionplan_sublines_body">
</tbody>
</table>
<input type="hidden" name="enable-masterdetail" value="true">
</div>

</div>
</div>
<!-------------------------Linedata End-------------------------------->
	
<div class="row">
	<div class="col-lg-12 col-md-12">
		<div class="form-group text-center">
          <!--  <button type="button" class="btn save saveform"   value="SAVENEW">Save and New</button>-->
			<button type="button" class="btn save saveform" value="SAVE">Save</button>
			  <a href="{{ URL::to('productionplan') }}" class='btn cancel'>Cancel</a>
		</div>
	</div>
</div>


</div>

</div>



</div>



	</form>
	
</div>


<!--deepika purpose:Product Search Modal-->
<div class="modal fade" id="productModal">
  <div class="modal-dialog" style="width:80%;">
    <div class="modal-content">
		<!--Moda Header-->
      <div class="modal-header">
		  <h4 class="modal-title"> Product Details </h4>
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
	  </div>
		<!-- Modal Body -->
	  <div class="modal-body">
	      <table id="productgrid"></table>
	  </div>
		 <!-- Modal footer -->
      <div class="modal-footer">
      </div>

    </div>
  </div>
</div>
<input type="hidden" class="pdtindex" value="" />
	<!--end-->
	<script>	
	

$(document).ready(function()
{
	changeclassfields();
	$(document).on('click','.add_row',function()
    {
            $('.onclickrel').trigger('click');
            changeclassfields();
    });
    $('#savestatus').val('');
	/*deepika purpose:qty validation*/
		$(document).on('keypress','.bulk_qty', function(ev){
			var regex = new RegExp("^[0-9.]+$");
					var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
					if (regex.test(str)) {
						return true;
					}
					ev.preventDefault();
					return false;
		});
	/*end*/
	$(document).on('change','.product_id',function()
    {
		var pdt_id = $(this).val();
		var woid = $('.reference_id').val();
		
		if(pdt_id !='')
		{
			$.get("{{ URL::to('planproductdetails') }}/"+pdt_id+"/"+woid,function(data)
			{
				
				$('.uom_code_id').val(data.uom_code_id);
				$('.production_qty').val(data.production_qty);
			});
		  	$.get("{{ URL::to('bomdetails') }}/"+pdt_id,function(data)
			{
				$('.productionplan_table tbody').html('');
                        $('.productionplan_lines_body').append(data);
                  
			//	$('.productionplan_table').find('tr:eq(1)').find("input[type=text],input[type=hidden], textarea,input[type=date],select").val("");
				changeclassfields();
			});
		}
		else
		{
			$('.productionplan_table tbody tr:not(:first)').remove();
			$('.uom_code_id').val('');
				$('.production_qty').val('');
		}
		
	});
	$(document).on('click','.subbomdetails',function()
    {
		
		alert("tuigy");
		var index=$(this).closest('tr').index();
		var sub = $(this).attr('data-submodel');
		
		var bprdid=$(this).data('value');
            
                $.get("{{ URL::to('subbomdetails') }}/"+bprdid,function(data)
                    {
                            $('.productionplan_subtable tbody').html('');
                            $('.productionplan_table > tbody > tr.table'+sub+':last').after(data);
                    //	$('.productionplan_table').find('tr:eq(1)').find("input[type=text],input[type=hidden], textarea,input[type=date],select").val("");
                    $(".subbomdetails"+sub).removeClass('fa-plus');
                    $(".subbomdetails"+sub).removeClass('subbomdetails');
                    $(".subbomdetails"+sub).addClass('fa-minus');
                    $(".subbomdetails"+sub).addClass('subbomdetailsremove');
                    });
	});

	$(document).on('click','.subbomdetailsremove',function()
    {
		
		
		var index=$(this).closest('tr').index();
        var sub = $(this).attr('data-submodel');
        var dataval = $(this).attr('data-value');
$('.sub'+dataval).remove();
  
		
                    $(".subbomdetails"+sub).addClass('fa-plus');
                    $(".subbomdetails"+sub).addClass('subbomdetails');
                    $(".subbomdetails"+sub).removeClass('fa-minus');
                    $(".subbomdetails"+sub).removeClass('subbomdetailsremove');
});

		$(document).on('click','.subbomdetailremove',function()
    {
		
		
		var index=$(this).closest('tr').index();
        var sub = $(this).attr('data-submodel');
        var dataval = $(this).attr('data-value');
$('.sub'+dataval).remove();
  
		
                    $(".subbomdetail"+sub).addClass('fa-plus');
                    $(".subbomdetail"+sub).addClass('subbomdetail');
                    $(".subbomdetail"+sub).removeClass('fa-minus');
                    $(".subbomdetail"+sub).removeClass('subbomdetailremove');
});
   	$(document).on('click','.subbomdetail',function()
    {
		
		alert("plan");
		var index=$(this).closest('tr').index();
		var sub = $(this).attr('data-submodel');
	        var index=$(this).data('index'); 
		var bprdid=$(this).data('value');
            
                $.get("{{ URL::to('subbomdetail') }}/"+bprdid,function(data)
                    {
                            $('.productionplan_subtable tbody').html('');
                            if(index=='1'){
                            $('.productionplan_table > tbody > tr.table'+sub+':last').after(data);
                        } else{
                           
                             $('.sub'+bprdid+'> tbody > tr.sub'+bprdid+':last').after(data);
                        }
                    //	$('.productionplan_table').find('tr:eq(1)').find("input[type=text],input[type=hidden], textarea,input[type=date],select").val("");
                     $(".subbomdetail"+sub).removeClass('fa-plus');
                    $(".subbomdetail"+sub).removeClass('subbomdetail');
                    $(".subbomdetail"+sub).addClass('fa-minus');
                    $(".subbomdetail"+sub).addClass('subbomdetailremove');
                    });
	});     
        
	$(document).on('click','.jobcard',function(){
		var plid=  $('.pids:checked').val();
               var batch=$('.batch_no').val();
		var url="{{URL::to('jobcardfromplan')}}/";
		window.location.replace(url+plid+"?source=PLAN&batch="+batch);
	});
	
    $(document).on('click','.saveform',function()
    {
            var btnval		= $(this).val();
            var url		= "{{ url('productionplansave') }}";
            var red_url		="{{ url('productionplananalyse') }}";
            var create_url	="{{ url('productionplancreate') }}/0";
            validationrule('productionplan');
            var formdata	= $('#productionplan').serialize();
            var form = $('#productionplan');


                form.parsley().validate();
                var form = $('#productionplan');
                form.parsley().validate();

                if (form.parsley().isValid())
                {
                    $.post(url,formdata,function(data)
                    {
                        var status      = data.status;
                        var msg     = '<span style="color:#090065">'+data.auto_no+'</span>  '+data.message;
                        var id          = data.id;
                        var edit_url	= "{{ url('productionplancreate') }}/"+id;
                        if(btnval =='SAVE')
                        {
                            notyMsg(status,msg);
                            setTimeout(function(){
                            window.location.href=red_url;
                            }, 1500);
                        }
                    });
                }
            
    });


$('.bulk_start_date,.bulk_end_date,.start_date,.plan_date,.end_date').datepicker({format: 'yyyy-mm-dd', autoClose: true});
$(document).on('click','.remove',function()
{
	var index = $(this).closest('tr').index();
	var rowCount = $('.productionplan_table tbody tr').length;
	if(rowCount > 1)
	{
		$($(this).closest("tr")).remove();
		removeclassfields();
	}
	else
	{
		alert("You Can't Delete Atleast One row should be there");
	}
});
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
				notyMsgs('info','Product Already Selected');
				$(".bulk_product_id" + index).val('').change();
				event.preventDefault();
			}
	}
        });

	});
function changeclassfields(){
changeClassName('bulk_workorder_line_id');
changeClassName('subbomdetails');
changeClassName('subbomdetail');
changeClassName('bulk_line_no');
changeClassName('bulk_product_id');
changeClassName('bulk_uom_code_id');
changeClassName('bulk_qty');
changeClassName('bulk_start_date');
changeClassName('bulk_end_date');
changeClassName('bulk_comments');
}
function removeclassfields(){
removeClass('bulk_workorder_line_id');
removeClass('bulk_line_no');
removeClass('subbomdetails');
removeClass('subbomdetail');
removeClass('bulk_product_id');
removeClass('bulk_uom_code_id');
removeClass('bulk_qty');
removeClass('bulk_start_date');
removeClass('bulk_end_date');
removeClass('bulk_comments');
}
/************ Maruthu purpose to remove row action ********************/
function removeClass(className)
{
	var rowCount = $('.productionplan_table tbody tr').length;
	for(var i=0;i<=rowCount;i++)
	{
	$('.productionplan_table tbody tr').find('.'+className).removeClass(className+i);
	}
	$('.' + className).each(function (index)
	{
		if (className == "bulk_line_no")
		{
		$(this).val(index + 1).attr("readonly", 1);
		}
		$(this).addClass(className + index);
	});
}

function changeClassName(className)
{
$('.' + className).each(function (index)
{
if (className == "bulk_line_no")
{
$(this).val(index + 1).attr("readonly", 1);
}

$(this).removeClass(className + '0');
$(this).addClass(className + index);
});
}

	</script>
<style>
#table_scroll tbody {
display:block;
max-height:300px;
overflow:auto;
}
#table_scroll table thead tr {
display:table;
}
.bulk_uom_code_id,.organization_id,.source,.uom_code_id{
pointer-events:none;	
	}
	input {
    background-color: transparent;
    border: 0px solid;
    height: 20px;
    width: 160px;
    color: #CCC;
}
</style>
@include('layouts.php_js_validation')
@endsection

