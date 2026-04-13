@extends('layouts.header')
@section('content')

<h2 class="heads">Pickorder
<span class="ui_close_btn"><a class="collapse-close pull-right btn-danger" onclick="location.href = '{{URL::to('pickorder')}}'"></a></span>
</h2>

<form method="post" action="" id="pickorder" data-parsley-validate>
{{ csrf_field() }}


<div class="card">
<div class="card-body card-block headerdiv1">
<div class="row">
	<div class="col-md-4">
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Pickorder Satus</label>
			<div class="col-md-6 read">
				<input class="form-control so_pickrelease_hdr_id" id="so_pickrelease_hdr_id" name="so_pickrelease_hdr_id" type="hidden" readonly >
				<input class="form-control so_pickrelease_hdr_id" id="so_pickrelease_hdr_id" name="so_pickrelease_hdr_id" type="hidden" readonly >
				<input class="form-control ar_sales_hdr_id" id="ar_sales_hdr_id" name="ar_sales_hdr_id" type="hidden" readonly value="{{$ar_sales_hdr_id}}" >
				
				<select type="text" name="release_status" id="release_status" class="form-control release_status select2" >
					<option value="">--Please Select--</option>
					<option <?php if($release_status =="PICK ORDER") { echo "selected"; } else { echo ""; } ?> value="PICK ORDER">PICK ORDER</option>
					<option <?php if($release_status =="MANUAL") { echo "selected"; } else { echo ""; } ?> value="MANUAL">MANUAL</option>
				</select>
			</div>
			<div class="col-md-2">
			</div>
		</div>
		
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Pickorder Source</label>
			<div class="col-md-6 read">
				<select type="text" name="release_source" id="release_source" class="form-control release_source select2" >
					<option value="">--Please Select--</option>
					<option <?php if($release_source =="PICK ORDER") { echo "selected"; } else { echo ""; } ?> value="PICK ORDER">PICK ORDER</option>
					<option <?php if($release_source =="DIRECT") { echo "selected"; } else { echo ""; } ?> value="DIRECT">DIRECT</option>
				</select>
			</div>
		</div>	
		
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Pickorder Date</label>
			<div class="col-md-6">
				<div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
					<input class="form-control datepicker release_date" id="release_date" name="release_date" size="16" type="text" value="{{ $release_date }}" >
				</div>
				<div class="col-md-2"></div>
			</div>
		</div>
	</div>

	<div class="col-md-4">
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red;">*</span>Freight Carrier</label>
			<div class="col-md-6">
			<select name='freight_carrier_id' rows='5' class='select2 freight_carrier_id' data-show-subtext="true" data-live-search="true"  required >
				{!! $freight_carrier_id!!}
				</select>
			</div>
			<div class="col-md-2 showinline">
						<span class="showspan"><i class="fa fa-refresh jcr_freight_carrier_id"></i></span>
			</div>
		</div>

		<div class="form-group row" >
			<label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red;">*</span>Pricelist</label>
			<div class="col-md-6 read">
				<select name='pricelist_id' rows='5' class='form-control pricelist_id select2' required>
					{!! $pricelist_id !!}
				</select>
			</div>
			<div class="col-md-2 showline">

			</div>
		</div>
	</div>

	<div class="col-md-4">
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4"><span style=width:20px;color:red;>*</span>Customer</label>
			<div class="col-md-6 read">
				<select name='ship_to_customer_id' rows='5' class='form-control ship_to_customer_id select2' required>
				{!! $ship_to_customer_id !!}
				</select>
			</div>
			<div class="col-md-2 showinline"></div>
		</div>
		
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Deliver To Location</label>
			<div class="col-md-6">
				<div class="form-group hidethis" style="display:none;">
         			<input type="text" name="deliver_to_location" id="deliver_to_location" value="{{ $deliver_to_location }}" class="form-control deliver_to_location" >
        		</div>
				<textarea name='deliver_to_location_txt' rows='5' id='deliver_to_location_txt' class='form-control deliver_to_location_txt' readonly="true">	</textarea>
			</div>


			<button type="button" class=" shipto btn-success btn-xs" value="shipto" style="margin-left: 204px; margin-top: 5px;"><i class="fa fa-address-book" aria-hidden="true"></i> Change Address</button>
			<input type="hidden" id="custype" value="" class="form-control custype">
		</div>
	</div>


<div class="row">
	<div class="col-md-12">
		<h3 class="myheaders">Additional Details</h3>
	</div>
</div>

<div class="row">		
	<div class="col-md-4">
		<?php $i=0;
			$j=0;
			$show_div = 0;
			foreach($enabled_columns as $index=>$val)
			{
				if($val->action=='1')
					$required="required";
				else 
					$required='';
				if($val->action=='1')
				{
					$required="required";
					$show_div = 1;
				}
				else
					$required='';

				if($i!=$j) { $j=$i; ?>
		</div>
		<?php if($index == 2)
				$col=4;
			else
				$col=4; ?>
		<div class="col-md-{{$col}}">
			<?php } if($val->column_name=='prepare_date' && $val->active==1) { $i++; ?>
				<div class="form-group row prepare_date_cfg" >
					<label for="inputIsValid" class="form-control-label col-md-4">Prepare Date<?php if($required != '' ) { ?>
					<span style="color:red;">*</span> <?php } ?> </label>
					<div class="col-md-6">
						<div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
							<input class="form-control prepare_date datepicker" id="prepare_date" name="prepare_date" size="16" type="text" value="{{ $prepare_date }}" readonly>
						</div>
					</div>
				</div>
			<?php } if($val->column_name=='preparer_id' && $val->active==1) { $i++; ?>
				<div class="form-group row created_by_cfg">
					<label for="inputIsValid" class="form-control-label col-md-4">Preparer Name<?php if($required != '' ) { ?>
						<span style="color:red;">*</span> <?php } ?>
					</label>
					<div class="col-md-6 prep">
						<select  name='preparer_id' <?php echo $required; ?>  class='form-control preparer_id select2' data-show-subtext="true" data-live-search="true" >
							{!! $preparer_id !!}
						</select>
					</div>
				</div>
		 	<?php } if($val->column_name=='remarks' && $val->active==1) { $i++; ?>
				<div class="form-group row created_by_cfg">
					<label for="inputIsValid" class="form-control-label col-md-4">Remarks<?php if($required != '' ) { ?>
						<span style="color:red;">*</span> <?php } ?>
					</label>
					<div class="col-md-6">
						<input type="text" name="remarks" id="remarks" value="" class="form-control remarks">
					</div>
				</div>
			<?php } } ?>
		</div>	
</div>


  	<!--*****************  End  *********** -->

</div>
</div>


<!--*******************-Linedata ************************-->
  	<div class="row">
	<div class="col-md-12">
	<h4>Dispatch Details</h4>
	<div id="preview-area" class="chandru">
	<table class="overflow-y preview so_inq_table">
		<thead>
			<tr>
				<th>Line No</th>
				<th class="pdtdiv" >Product</th>
				<th ></th>
				<th>Uom Code </th>
				<th >So Qty</th>
				<th>Picke Order Details</th>
				<th class="pick"></th>
				<th>Release Qty</th>
				<th>Picked Qty</th>
				<th>QOH</th>
				<th>Comments</th>
				<th></th>
			</tr>
		</thead>
		<tbody class="so_inq_lines_body">
		<?php if(count($linedata)>=1) { ?>
		@foreach($linedata as $key=>$value)
		<tr class="clone clonedInput">
			<td >
				<input type="hidden" name="bulk_so_pickrelease_line_id[]" class="form-control input-sm bulk_so_pickrelease_line_id" value="{{ $value->so_pickrelease_line_id}}">
				<input type="hidden" name="bulk_ar_sales_hdr_id[]" class="form-control input-sm bulk_ar_sales_hdr_id" value="{{ $value->ar_sales_hdr_id}}">
				<input type="hidden" name="bulk_ar_sales_line_id[]" class="form-control input-sm bulk_ar_sales_line_id" value="{{ $value->ar_sales_line_id}}">
			</td>
			<td>
				<input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="{{ $key + 1 }}" readonly="readonly" >
			</td>
			<td  class="pdtdiv" >
				<select name="bulk_product_id[]" id="bulk_product_id" class="bulk_product_id form-control parsley-validated" required="required" >
					{!! $value->product_id !!}
				</select>
			</td>
			<td>
				<i class="fa fa-search productsearch"></i>
			</td>
			<td >
				<select name="bulk_uom_code_id[]" id="bulk_uom_code_id" class="form-control form-control bulk_uom_code_id" >
					{!! $value->uomcode_id !!}
				</select>
			</td>
			<td >
				<input type="text" name="bulk_so_qty[]" class="form-control input-sm bulk_so_qty input_qty_width" value="{{ $value->so_qty }}" readonly="readonly" >
			</td>
			<td >
		    	<a href="#" class="pickqty" title="Add Pick Qty"> <i class="fa fa-plus"></i></a>
		    	<input type="hidden" name="sowise_qty[]" class="sowise_qty">
		    	<input type="hidden" name="soorder_id[]" class="soorder_id">
		    	<input type="hidden" name="soorder_lineid[]" class="soorder_lineid">
		    	<input type="hidden" name="sototqty[]" class="sototqty">
		    	<input type="hidden" name="soorder_qty[]" class="soorder_qty">
		    </td>
		    <td class="pick">
		    	<a href="#" class="salesdetails" title="Add Release Qty"> <i class="fa fa-plus"></i></a>
		    	<input type="hidden" name="p_line_no[]" class="p_line_no" value="">
		    	<input type="hidden" name="p_box_no[]" class="p_box_no" value="">
		    	<input type="hidden" name="" class="p_box_no_edit" value="">
		        <input type="hidden" name="p_kit_pack_no[]" class="p_kit_pack_no" value="">
		        <input type="hidden" name="p_batch_no[]" class="p_batch_no" value="">
		        <input type="hidden" name="p_subinventory_id[]" class="p_subinventory_id" value="">
		        <input type="hidden" name="p_sublocator_id[]" class="p_sublocator_id" value="">
		        <input type="hidden" name="p_issue_qoh[]" class="p_issue_qoh" value="">
		    </td>
		    <td>
				<input type="text" name="bulk_release_qty[]" class="form-control input-sm bulk_release_qty input_qty_width" value="{{ $value->release_qty }}"  required="required" readonly >
			</td>
			<td >
				<input type="text" name="bulk_picked_qty[]" class="form-control input-sm bulk_picked_qty input_qty_width"  readonly value="{{ $value->picked_qty}}">
			</td>
			<td >
				<input type="text" name="bulk_qoh[]" class="form-control input-sm bulk_qoh input_qty_width" readonly value="{{ $value->qoh_qty }}" >
			</td>
			<td >
				<input type="text" name="bulk_comments[]" row="5" class="form-control input-sm bulk_comments input_qty_width" value="{{ $value->comments }}" >
			</td>
			<td style="width:33px;">
				<a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
				<input type="hidden" name="counter[]">
			</td>
		</tr>
		@endforeach
		<?php } if(count($linedata) < 1 ) {  ?>
			<tr class="cloneRow clone clonedInput">
				<td >
					<input type="hidden" name="bulk_so_pickrelease_line_id[]" class="form-control input-sm bulk_so_pickrelease_line_id" value="">
					<input type="hidden" name="reference_hdr_id[]" class="form-control input-sm reference_hdr_id" value="{{ $value->reference_hdr_id}}">
					<input type="hidden" name="reference_line_id[]" class="form-control input-sm reference_line_id" value="{{ $value->reference_line_id}}">
					<input type="hidden" name="ar_sales_line_id[]" class="form-control input-sm ar_sales_line_id" value="{{ $value->ar_sales_line_id}}">
				</td>
				<td  class="pdtdiv">
					<select  name="bulk_product_id[]" id="bulk_product_id" class="bulk_product_id select2 form-control parsley-validated" required="required" >
						{!! $product_id !!}
					</select>
				</td>
				<td>
					<i class="fa fa-search productsearch"></i>
				</td>
				<td >
					<select name="bulk_uom_code_id[]" id="bulk_uom_code_id" class="form-control form-control bulk_uom_code_id" >
						{!! $uomcode_id !!}
					</select>
				</td>
				<td >
					<input type="text" name="bulk_so_qty[]" class="form-control input-sm bulk_so_qty input_qty_width" value="" readonly="readonly"  >
				</td>
				<td class="dis">
			    	<a href="#" class="pickqty" title="Add Pick Qty"> <i class="fa fa-plus"></i></a>
			    	<input type="hidden" name="sowise_qty[]" class="sowise_qty">
		    		<input type="hidden" name="soorder_id[]" class="soorder_id">
		    		<input type="hidden" name="soorder_lineid[]" class="soorder_lineid">
		    		<input type="hidden" name="sototqty[]" class="sototqty">
		    		<input type="hidden" name="soorder_qty[]" class="soorder_qty">
			    </td>
				<td class="pick">
			        <a href="#" class="salesdetails" title="Add Release Qty"> <i class="fa fa-plus"></i></a>
			        <input type="hidden" name="p_line_no[]" class="p_line_no" value="">
			        <input type="hidden" name="p_box_no[]" class="p_box_no" value="">
			        <input type="hidden" name="" class="p_box_no_edit" value="">
			        <input type="hidden" name="p_kit_pack_no[]" class="p_kit_pack_no" value="">
			        <input type="hidden" name="p_batch_no[]" class="p_batch_no" value="">
			        <input type="hidden" name="p_subinventory_id[]" class="p_subinventory_id" value="">
			        <input type="hidden" name="p_sublocator_id[]" class="p_sublocator_id" value="">
			        <input type="hidden" name="p_issue_qoh[]" class="p_issue_qoh" value="">
			    </td>
			    <td>
					<input type="text" name="bulk_release_qty[]" class="form-control input-sm bulk_release_qty input_qty_width" value=""  required="required" readonly >
				</td>
				<td >
					<input type="text" name="bulk_picked_qty[]" class="form-control input-sm bulk_picked_qty input_qty_width"  readonly value="">
				</td>
				<td >
					<input type="text" name="bulk_qoh[]" class="form-control input-sm bulk_qoh input_qty_width " readonly value="" >
				</td>
				<td >
					<input type="text" name="bulk_comments[]" row="5" class="form-control input-sm bulk_comments input_qty_width" value="" >
				</td>

				<td style="width:33px;">
					<a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
					<input type="hidden" name="counter[]">
				</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
	<input type="hidden" name="enable-masterdetail" value="true">
	</div>
	</div>
	</div>
<!--******************Linedata End *****************************-->

<div class="row">
	<div class="col-lg-12 col-md-12">
		<div class="form-group text-center">
			<button type="button" class="btn save saveform"  value="save">SUBMIT</button>
			  <a href="{{ url('pickorder') }}" class='btn cancel'>Cancel</a>
		</div>
	</div>
</div>

</div>

<!-- Rajalakshmi purpose customer search jqgrid model-->
<div class="modal fade" id="customerModal">
  	<div class="modal-dialog" style="width:80%;">
	    <div class="modal-content">
			<!--Moda Header-->
	      	<div class="modal-header">
			  	<h4 class="modal-title"> Customer Details </h4>
			  	<button type="button" class="close" data-dismiss="modal">&times;</button>
		  	</div>
			<!-- Modal Body -->
		  	<div class="modal-body">
		      	<table id="customergrid"></table>
		  	</div>
			 <!-- Modal footer -->
	      	<div class="modal-footer">
	      	</div>
	    </div>
  	</div>
</div>
<!--end-->
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

</form>

<input type="hidden" class="pdtindex" value="" />



<div class="modal fade" id="pickorderdetailsModal">
  	<div class="modal-dialog" style="width:1000px;">
	    <div class="modal-content">
			<!--Moda Header-->
	      	<div class="modal-header">
			  	<h4 class="modal-title"> Qoh Details </h4>
			  	<button type="button" class="close" data-dismiss="modal">&times;</button>
			  	<input type="hidden" class="soindex" value="">
		  	</div>
			<!-- Modal Body -->
		  	<div class="modal-body sodetail">
		  	</div>
			
			<!-- Modal footer -->
	      	<div class="modal-footer">
	      	</div>
		</div>
  	</div>
</div>

<!--end-->


<!-- purpose for dispatch qty start -->
<div class="modal fade" id="pickqtyModal"  > 
  	<div class="modal-dialog" style="width:100%;">
    	<div class="modal-content">
		<!--Moda Header-->
      		<div class="modal-header">
		  		<h4 class="modal-title"> Pickorder Qty Details </h4>
		  		<button type="button" class="close" data-dismiss="modal">&times;</button>
		  		<input type="hidden" class="qtyindex" value="">
	  		</div>
		<!-- Modal Body -->
	  		<div class="modal-body qtydetail">
	  		</div>
		<!-- Modal footer -->
      		<div class="modal-footer">
      		</div>
    	</div>
  	</div>
</div>
<!--end-->
<div class="col-lg-1">
</div>

<style>
.bulk_qoh,.dispatch_source,.dispatch_status,.organization_id,.prep,.location_id,.deliver_to_location{
	pointer-events:none;
}
@media only screen and (min-width: 1500px) {
	.bulk_line_no{
		width:100px !important;
	}
	.bulk_product_id{
		width:250px !important;
	}
	.bulk_uom_code_id{
		width:250px !important;
	}
	.bulk_so_qty{width:200px !important;}
	.bulk_release_qty{width:200px !important;}
	.bulk_picked_qty{width:200px !important;}
	.bulk_qoh{width:200px !important;}
	.bulk_comments{width:250px !important;}
}

@media only screen and (min-width: 2000px) {
	.bulk_line_no{
		width:50px !important;
	}
	.bulk_product_id{
		width:350px !important;
	}
	.bulk_uom_code_id{
		width:350px !important;
	}
	.bulk_so_qty{width:300px !important;}
	.bulk_release_qty{width:300px !important;}
	.bulk_picked_qty{width:300px !important;}
	.bulk_qoh{width:300px !important;}
	.bulk_comments{width:350px !important;}
}
	.bulk_line_no{
		width:50px;
	}
	.bulk_product_id{
		width:265px;
	}
	.bulk_uom_code_id{
		width:90px;
	}
	.bulk_so_qty{width:80px;}
	.bulk_release_qty{width:80px;}
	.bulk_picked_qty{width:80px;}
	.bulk_qoh{width:80px;}
	.bulk_comments{width:120px;}
 .modal-body td,th{
 	padding: 4px;
 }

</style>
	<script>

$(document).ready(function(){
	
		$('.bulk_product_id,.bulk_uom_code_id,.customer_id').css('pointer-events','none');
		$('.bulk_dispatched_qty').attr('readonly',true);
	
		$(".read").css('pointer-events','none');
		$('.remove,.pick').hide();
	
	var customer_id = $('.ship_to_customer_id').val();
	var url = "{{ URL::to('sodispatchaddress') }}/"+customer_id;
	if(customer_id != ''){
		$.get(url,function(data)
		{
            if(data.length != 0)
            {
                if(data[0] != '')
                {
                    var result_data=data[0].split('~');
                    $('.deliver_to_location_txt').val(result_data[0]);
                    $('.deliver_to_location').val(result_data[1]);
                }
                else
                {
                    $('.deliver_to_location_txt').val('');
                    $('.deliver_to_location').val('');
					notyMsg("info","There is no Ship to address for this customer...");
                }
            }
            else{
                 $('.deliver_to_location_txt').val('');
				notyMsg("info","There is no Bill to and Ship to address for this customer...");
            }
	   	});
	}

	$(document).on('change','.ship_to_customer_id',function(){
		var cus_id = $('.ship_to_customer_id').val();
		var url = "{{ URL::to('customerpricelist') }}/"+cus_id;
		var price_url = "{{ URL::to('sodispatchaddress') }}/"+cus_id;
		if(cus_id != ''){
			$.get(url,function(data)
			{
				var data =$.trim(data);
				$('.pricelist_id').select2('val',[data]);
			});
			$.get(price_url,function(data1)
			{
				$('.deliver_to_location_txt').val(data1[0]);
			});
		}
	});

	$(document).on('change','.pricelist_id',function(){
		var price = $('.pricelist_id').val();
		var pro_url = "{{ URL::to('getpriceproduct') }}/"+price+"/0";
		if(price != "")
		{
			$.get(pro_url,function(data3){
				$('.bulk_product_id').html(data3);
			});
		}
	});

	
	$(document).on('click','.saveform',function(e)
	{
	
		var btnval		= $(this).val();
		var url			="{{ url('pickordersave') }}";
		var red_url		="{{ url('pickorder') }}";
		var create_url	="{{ url('pickordercreate') }}/0";
		validationrule('pickorder');
		if(btnval == 'save')
		{
			var form = $('#pickorder');
			form.parsley().validate();
			if(form.parsley().isValid())
			{
				change_date();
				var formdata	= $('#pickorder').serialize();

				$.post(url,formdata,function(data)
				{
					var status = data.status;
					var msg    = data.message;
					notyMsg(status,msg);
					setTimeout(function(){
						window.location.href=red_url;
					}, 1500);
				});
			}
		}
	});

	
	$('.bulk_qoh').each(function(d){
		var qh = $(this).val();
		if(qh == 0){
			qoh =0;
		}else{
			qoh =1;
		}
	});
	if(qoh == 0){
		$('.saveform').attr('disabled',true);
		notyMsg('error',"QOH is empty you can't dispatch this order");
	}else{
		$('.saveform').attr('disabled',false);
	}
		
	$(document).on('click','.jcr_freight_carrier_id',function()
	{
		$(".freight_carrier_id").jCombo("{{ URL::to('jcomboform?table=m_frieghtcarriers_hdr_t:ar_frieghtcarriers_hdr_id:carrier_name') }}&order_by=carrier_name asc",
		{selected_value:""});
	});

	//$(".add_row").relCopy({});
	$(document).on('click','.add_row',function()
	{
	    cloneRow('so_inq_table','so_inq_lines_body');
		changeclassfields();
	});

	
	$('.salesdetails').click(function(){
		var  sototqty= $('.sototqty').val();
		if(sototqty != 0 ){
			$('#pickorderdetailsModal').modal('show');
			$('#pickorderdetailsModal').width("70%").css('margin','auto');
			var index=$(this).closest('tr').index();
			$('.soindex').val(index);
			var p_box_no = $('.p_box_no_edit'+index).val();
			var p_kit_pack_no = $('.p_kit_pack_no'+index).val();
			var p_issue_qoh = $('.p_issue_qoh'+index).val();
			setTimeout(function(){
				if(p_box_no){
					var box_no =p_box_no.split(',');
					$('.box_no').each(function(q){
						if(box_no[q] != 0 )
							$(this).val(box_no[q]);
						else
							$(this).val('');
					});
				}
				if(p_kit_pack_no){
					var kit_pack_no =p_kit_pack_no.split('~');
					$('.kit_pack_no').each(function(l){
						if(kit_pack_no[l] != 0 )
							$(this).val(kit_pack_no[l]);
						else
							$(this).val('');
					});
				}
				if(p_issue_qoh){
					var issue_qoh =p_issue_qoh.split(',');
					$('.issue_qoh').each(function(d){
						if(issue_qoh[d] != 0 )
							$(this).val(issue_qoh[d]);
						else
							$(this).val('');
					});
				}
			},1000);
		}else{
			notyMsg('error','Please enter qty in sales order wise');
		}
	});

	$('.pickqty').click(function(){
		$('#pickqtyModal').modal('show');
		$('#pickqtyModal').width("37%").css('margin','auto');
		var index=$(this).closest('tr').index();
		$('.qtyindex').val(index);
		var soqty = $('.sowise_qty'+index).val();
		setTimeout(function(){
			if(soqty){
				var s_qty = soqty.split(',');
				$('.dis_issue_qty').each(function(a){
					if(s_qty[a] != 0 ){
						$(this).val(s_qty[a]);
					}else{
						$(this).val('');
					}
				});
			}
		},1000);		
	});
	
	$('#pickorderdetailsModal').on('shown.bs.modal', function () {	
		var index = ($(this).closest('tr').index());
		var index=$('.soindex').val();
		var sohdrid=$('.so_pickrelease_hdr_id').val();
		var solnid=$('.bulk_so_pickrelease_line_id'+index).val();
		var prdid=$('.bulk_product_id'+index).val();
		$.get("{{URL::to('dispatchlines') }}/"+prdid,function(data){
			$('.sodetail').html(data);
		});
	});

	$(document).on('keyup','.issue_qty',function(){
		var iss_qty = $(this).val();
		var index = ($(this).closest('tr').index());
		var qty = $('.qty'+index).val();
		if( parseInt(iss_qty) > parseInt(qty) ){
			notyMsg('info','Issue Qty not more than qty');
			$('.issue_qty'+index).val('');
		}
	});

	$(document).on('click','.qtyok',function(){
		var add = 0;
		var issue=[];
		var sorder_id=[];
		var sorder_lineid=[];
		var sorder_qty=[];
		$('.dis_issue_qty').each(function(k){			
			var val = parseInt($(this).val());
			if(isNaN(val))
	 		{
		 		val=0;
	 		}	
			add=add+val;
			issue[k]=val;
		});
		$('.sales_hdr_id').each(function(k,v){
			if(($(this).val()) !='')
			{
				var so_id = $(this).val();
			}
			else
			{
				var so_id = 0;
			}
			sorder_id[k]=so_id;
		});
		$('.sales_line_id').each(function(g,l){
			if(($(this).val()) !='')
			{
				var so_lineid = $(this).val();
			}
			else
			{
				var so_lineid = 0;
			}
			sorder_lineid[g]=so_lineid;
		});
		$('.qty').each(function(k,v){
			if(($(this).val()) !='')
			{
				var qty = $(this).val();
			}
			else
			{
				var qty = 0;
			}
			sorder_qty[k]=qty;
		});
		var index = $('.qtyindex').val();
		$('.sowise_qty'+index).val(issue);
		$('.soorder_id'+index).val(sorder_id);
		$('.reference_hdr_id'+index).val(sorder_id);
		$('.reference_line_id'+index).val(sorder_lineid);
		$('.soorder_qty'+index).val(sorder_qty);
		$('.sototqty'+index).val(add);
		$('.bulk_release_qty'+index).val(add);
		$('#pickqtyModal').modal('hide');
	});


	$('#pickqtyModal').on('shown.bs.modal', function () {	
		var index = $(this).closest('tr').index();
		var index=$('.qtyindex').val();
		var prdid=$('.bulk_product_id'+index).val();
		var soid=$('.ar_sales_hdr_id').val();
		$.get("{{URL::to('dispatchqty') }}/"+prdid+"/"+soid,function(data){
			$('.qtydetail').html(data);
		});		
	});
        
        
   	$(document).on('keyup','.issue_qoh',function(){
        var index_qoh =$(this).closest('tr').index();
        var qoh=$('.qoh'+index_qoh).val(); 
        var issue_qoh=$(this).val();
        
        if(parseFloat(qoh ) < parseFloat(issue_qoh)){
             notyMsg('error','exceed qoh qty');
             $(this).val(0);

        }                  
    })  
	
	$(document).on('click','.addqohqty',function(){
		var add=0;
		var i_qoh_id=[];
		var k_pack_val='';
		var line_val=[];
		var box_val='';
		var bat_val=[];
		var sub_val=[];
		var loc_val=[];
		var box_edit_val=[];
		var k_pack ='';
		var box_s_val ='';
		var index=$('.soindex').val();
 		$('.issue_qoh').each(function (k,v)
		{
 			var val=parseFloat($(this).val());
 			if(isNaN(val))
	 		{
		 		val=0;
	 		}	
			add=add+val;
			if(val){
				var i_qoh = val;
				var box_req = $('.box_no'+k).val();
				var kit_req = $('.kit_pack_no'+k).val();
				if( box_req == "" ){
					notyMsg("info","Please enter box number");
					$('#pickorderdetailsModal').modal('show');
				}
				if(kit_req == ""){
					notyMsg("info","Please enter kit pack number");
					$('#pickorderdetailsModal').modal('show');	
				}
			}else{
				var i_qoh = 0;
			}
			i_qoh_id[k]=i_qoh;
	 	});     

		$('.box_no').each(function(m){
			var box_no=[];
			var kit_pack =[];
		 	var box = $(this).val();
		 	if(box){
		 		var n=box.match(/-/g);
		 		var ind = $(this).closest('tr').index();
		 		var kit = $('.kit_pack_no'+ind).val();
		 		var tot_box=[];
		 		if(n != null){			 					 		
				 	box_no = box.split('-');
				 	var b_f = box_no[0];
				 	var b_to = box_no[1];
				 	kit_pack = kit.split(',');
				 	var k_len = kit_pack.length;				 	
				 	for(b_f; b_f<=b_to; b_f++){
				 		tot_box.push(b_f);
				 	}         
				 	var b_len = tot_box.length;
				 	box_val +=tot_box+"~";
				 	if(b_len != k_len){
				 		notyMsg('error','box no and kit pack not equal');
				 	}
				}else{
					tot_box.push(box);
					box_val +=tot_box+"~";
				}
				box_edit_val[m]=box;
            }
		});
		
		$('.kit_pack_no').each(function(u){
			var val=$(this).val();
			if(val != ""){
				k_pack += val+"~";
			}else{
				k_pack += 0+"~";
			}
		});	
		var k_pack_val =k_pack.substring("~",(k_pack.length - 1));
		var box_s_val =box_val.substring("~",(box_val.length - 1));
		
		$('.batch_no').each(function(y){
			var val = $(this).val();
			if(val){
				var bat_v = val;
			}else{
				var bat_v = 0;
			}
			bat_val[y]=bat_v;	
		});
		$('.subinventory_id').each(function(x){
			var val = $(this).val();
			if(val){
				var sub_id = val;
			}else{
				var sub_id = 0;
			}
			sub_val[x]=sub_id;				
		});
		$('.sublocator_id').each(function(z){
			var val = $(this).val();
			if(val){
				var loc_id = val;
			}else{
				var loc_id = 0;
			}
			loc_val[z]=loc_id;	
		});
		$('.dis_line_no').each(function(b){
			var val = $(this).val();
			if(val){
				var li_id = val;
			}else{
				var li_id = 0;
			}
			line_val[b]=li_id;	
		});

		var  dis_qty= $('.sototqty'+index).val();
		
		if(parseInt(add) != parseInt(dis_qty)) {
			notyMsg('info','Dispatch qty and issue qty must be same qty');
			$('.issue_qoh').val('');
			$('#pickorderdetailsModal').modal('show');
		}else{
			$('.p_line_no'+index).val(line_val);
			$('.p_box_no'+index).val(box_s_val);
			$('.p_box_no_edit'+index).val(box_edit_val);
			$('.p_kit_pack_no'+index).val(k_pack_val);
			$('.p_batch_no'+index).val(bat_val);
			$('.p_subinventory_id'+index).val(sub_val);
			$('.p_sublocator_id'+index).val(loc_val);
			$('.p_issue_qoh'+index).val(i_qoh_id);
			$('.bulk_dispatch_qty'+index).val(add);
			$('#pickorderdetailsModal').modal('hide');
		}
		
	});
	
	$(document).on('click','.remove',function()
	{
		var index = $(this).closest('tr').index();
		var rowCount = $('.so_inq_table tbody tr').length;
		if(rowCount > 1)
		{
			$($(this).closest("tr")).remove();
			changeclassfields();
		}
		else
		{
			notyMsg("error","You Can't Delete Atleast One row should be there");
		}
	});

	$(document).on('change','.bulk_product_id',function()
	{
	  	var product_id = $(this).val();
	  	var index = ($(this).closest('tr').index());
	  	var url = "{{ URL::to('dispatchproductqoh') }}/"+product_id;
       	$.get(url , function(data)
	   	{
		 	$('.bulk_uom_code_id'+index).val(data['uomcode']).trigger('change');
         	$('.bulk_qoh'+index).val(data['qoh_qty']);
         	$('.bulk_dispatched_qty'+index).val(data['dispatched']);
       	});
	});

	$('.customersearch').click(function(){
		$('#customerModal').modal('show');
		$('#customerModal').width("100%");
	});
	
	$(document).on('click','.productsearch',function()
	{
		var customer_id=$('.ship_to_customer_id option:selected').val();
		if(customer_id!=''){
			var index = ($(this).closest('tr').index());
			$('.pdtindex').val(index);
	 		$('#productModal').modal('show');
	 		$('#productModal').width("100%");
	 		var mypdtgrid = $("#productgrid"),
	 			pagerSelector = "#pager",
	 			myAddButton = function(options) {
	 				mypdtgrid.jqGrid('navButtonAdd',pagerSelector,options);
        			mypdtgrid.jqGrid('navButtonAdd','#'+mypdtgrid[0].id+"_toppager",options);
	 			};
 			var groupname="'FINISHED GOODS'";
 			var pricelist_id=$('.pricelist_id option:selected').val();
			var grp=[];
			grp.push(groupname);
			var prdcatopt="{{ $prdcatopt}}";
			var prdnameopt="{{ $prdnameopt }}";
			mypdtgrid.jqGrid({
				url: "{{ URL::to('getProductgridData') }}?prggrp="+grp+"&pricelist_id="+pricelist_id,
				datatype: "json",
				mtype: "GET",
				height: 320,
				width: 1000,
             	colModel: [
             		{ name: "product_code", label: "Product Code", width:55},
		 			{ name: "product_group_id", label: "Product Group", width:55},
					{ name: "product_category_id", label: "Product Category",stype:'select', editoptions:{value:prdcatopt}, width:55},
		 			{ name: "concatenated_product", label: "Product Name",stype:'text', editoptions:{value:prdnameopt}, width:55},
					{ name: "product_id", label: "id",hidden:true, width:55}
				],
				iconSet: "fontAwesome",
				rowNum: 10,
				rowList: [10,20,100,1000],
				sortorder: "asc",
				viewrecords: true,
				gridview: true,
				rownumbers:true,
				caption: "Product",
				pager: pagerSelector,
				toppager:true,
				searching: {
					defaultSearch: "cn"
				}
			});
			jQuery(mypdtgrid).jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
			jQuery("#gs_productgrid_product_category_id").select2();
			mypdtgrid.jqGrid('navGrid',pagerSelector,
				{cloneToTop:true,edit:false,add:false,del:false,search:true});
			myAddButton ({
				caption:"Select Product",
				title:"Product",
				buttonicon :'fa fa-plus',
				onClickButton:function()
				{
					var index = $('.pdtindex').val();
					var gr = jQuery(mypdtgrid).jqGrid('getGridParam','selrow');
					var product = jQuery(mypdtgrid).jqGrid ('getCell', gr, 'product_id');
					if(product != false )
					{
						$('.bulk_product_id'+index).val(product);
						$('.bulk_product_id'+index).trigger('change');
						$('#productModal').modal('hide');
					}else{
						notyMsg('info','Please Select one row');
					}
				}
			});
		}else{
			notyMsg('info','Please select Customer');
		}
	});



	var index = $('.clone').closest('tr').index();
	changeclassfields();




	var cusnameopt="{{ $cusnameopt }}";
	var custypeopt="{{ $custypeopt }}";
	var country="{{ $country }}";
	var state="{{ $state }}";
	var city="{{ $city }}";

	var mygrid = $("#customergrid"),
    pagerSelector = "#pager",
    myAddButton = function(options) {
        mygrid.jqGrid('navButtonAdd',pagerSelector,options);
        mygrid.jqGrid('navButtonAdd','#'+mygrid[0].id+"_toppager",options);
    };
          mygrid.jqGrid({
          url: "{{ URL::to('getCustomergridData') }}",
			datatype: "json",
			mtype: "GET",
			height: 320,
			width: 1000,
             colModel: [
				{ name: "customer_id", label: "id",hidden:true, width:55},
				{ name: "pincode", label: "pincode",hidden:true, width:55},
				{ name: "contact_number", label: "contact_number",hidden:true, width:55},
				{ name: "customer_site_id", label: "id",hidden:true, width:55},
		        { name: "customer_number", label: "Customer Number", width:55},
 				{ name: "customer_name", label: "Customer Name",stype:'select', editoptions:{value:cusnameopt}, width:55},
		 	    { name: "customer_type_id", label: "Customer Type", width:55,stype:'select', editoptions:{value:custypeopt}},
 				{ name: "customer_site_name", label: "Customer Site Name", width:55,},
 				{ name: "site_type", label: "Customer Site Type", width:55},
 				{ name: "address", label: "Address", width:55},
 				{ name: "city", label: "City", width:55,stype:'select', editoptions:{value:city}},
 				{ name: "state", label: "State", width:55,stype:'select', editoptions:{value:state}},
 				{ name: "country", label: "Country", width:55,stype:'select', editoptions:{value:country}}
		             ],

				iconSet: "fontAwesome",
				rowNum: 10,
				rowList: [10,20,100,1000],
				sortorder: "asc",
				viewrecords: true,
				gridview: true,
				rownumbers:true,
				caption: "Customer",
				pager: pagerSelector,
				toppager:true,
				searching: {
				defaultSearch: "cn"
				}
		   });
	//	(".ui-search-toolbar").hide();
	jQuery(mygrid).jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
	$('#gs_customergrid_customer_name').select2();
	$('#gs_customergrid_customer_type_id').select2();
	$('#gs_customergrid_city').select2();
	$('#gs_customergrid_state').select2();
	$('#gs_customergrid_country').select2();

	$('.customersearch').click(function(){
	  	$('#customerModal').modal('show');
	  	$('#customerModal').width("100%");
	  	var ct=$(this).val(); //alert(ct);
	  	$('.custype').val(ct);
	  	$(mygrid).jqGrid('setGridParam', {
        	postData: {"site_type":null,"cid":null }
   		}).trigger('reloadGrid');
	});

	$('.shipto').click(function(){
		$('#customerModal').modal('show');
		$('#customerModal').width("100%");
		var ct=$(this).val();
		$('.custype').val(ct);
	    var custype= $('.custype').val();
		var cid=$('.ship_to_customer_id').val();
			//alert(cid);
		   	if(ct=="shipto"){
		    	var site_type="SHIP_TO";
		    }
			$(mygrid).jqGrid('setGridParam', {
		        postData: {"site_type":site_type,"cid":cid }
		 	}).trigger('reloadGrid');
	});

		mygrid.jqGrid('navGrid',pagerSelector,
		{cloneToTop:true,edit:false,add:false,del:false,search:true});
		myAddButton ({
			caption:"Select Customer",
			title:"Customer",
			buttonicon :'ui-icon-plus',
			onClickButton:function()
			{
				var gr = jQuery(mygrid).jqGrid('getGridParam','selrow');
				var customerdata = jQuery(mygrid).jqGrid ('getRowData', gr,'customer_id');
					//console.log(customerdata.customer_id);
				if( customerdata.customer_id != false )
				{
					$('.customer_id').val(customerdata.customer_id);
					$('.deliver_to_location_txt').val(" "+customerdata.customer_site_name+","+customerdata.address+","+customerdata.city+","+customerdata.state+"-"+customerdata.pincode+","+customerdata.country+"."+"Contact No:"+customerdata.contact_number);
					$('#customerModal').modal('hide');
				}
				else
				{
					notyMsg('info','Please Select one row');
				}
			}
		});


});


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

	function changeclassfields(){
		changeClassName('bulk_so_pickrelease_line_id');
		changeClassName('bulk_ar_sales_hdr_id');
		changeClassName('bulk_ar_sales_line_id');
		changeClassName('bulk_line_no');
		changeClassName('bulk_product_id');
		changeClassName('bulk_uom_code_id');
		changeClassName('bulk_so_qty');
		changeClassName('sowise_qty');
		changeClassName('soorder_id');
		changeClassName('soorder_lineid');
		changeClassName('sototqty');
		changeClassName('soorder_qty');
		changeClassName('p_line_no');
		changeClassName('p_box_no');
		changeClassName('p_box_no_edit');
		changeClassName('p_kit_pack_no');
		changeClassName('p_batch_no');
		changeClassName('p_subinventory_id');
		changeClassName('p_sublocator_id');
		changeClassName('p_issue_qoh');
		changeClassName('bulk_release_qty');
		changeClassName('bulk_picked_qty');
		changeClassName('bulk_qoh');
		changeClassName('bulk_comments');		
	}

</script>
@include('layouts.php_js_validation')
@endsection