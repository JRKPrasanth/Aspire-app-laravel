@extends('layouts.header')
@section('content')

<style>
@media only screen and (min-width: 1500px)
{
		.bulk_line_no{
		  width: 100px !important;
		}
		.bulk_product_code{
			width: 240px !important;
		}
		.bulk_product_pack_id{
			width: 240px !important;
		}
		.bulk_concatenated_product{
			width: 240px !important;
		}
		.bulk_primary_uom_id{
			width: 240px !important;
		}
		.bulk_trx_uom_id{
			width: 240px !important;
		}
		.bulk_subinventory_id{
			width: 240px !important;
		}
		.bulk_sublocator_id{
			width: 240px !important;
		}
		.bulk_hsn_code {width: 240px !important;}
}


.bulk_line_no{
	width: 100px;
}
.bulk_product_code{
	width: 140px;
}
.bulk_product_pack_id{
	width: 140px;
}
.bulk_concatenated_product{
	width: 140px;
}
.bulk_primary_uom_id{
	width: 140px;
}
.bulk_trx_uom_id{
	width: 140px;
}
.bulk_subinventory_id{
	width: 140px;
}
.bulk_sublocator_id{
	width: 140px;
}
.bulk_hsn_code {width: 140px;}
</style>

	<!------------------------- breadcrumbs start here --------------------------->

	<!---------------------------------------------------------------------------->

<div class="row">
	<form action="" method="post" id="productform" data-parsley-validate>
	<input type="hidden" value="" name="savestatus" id="savestatus" />
    <input type="hidden" value="" name="linescheck" id="linescheck">
{{ csrf_field() }}
<div class="col-lg-12">
<div class="card">
<div class="card-header">
<h2>Product</h2>
	<span class="ui_close_btn"><a class="collapse-close pull-right btn-danger" onclick="location.href = '{{URL::to('product')}}'"></a></span>

</div>

<div class="card-body card-block">

	    <div class="col-md-4">

		 <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Product group Name</label>
			<div class="col-md-6">
			<select name='product_group_id' rows='5' class='form-control product_group_id select2' data-show-subtext="true" data-live-search="true" >
				{!! $product_group_id !!}
				</select>
			</div>
			<div class="col-md-2 showline">
					<span class="showspan"> <i class="fa fa-refresh jcr_product_group_id"></i></span>
				</div>
		</div>
		 <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Product category </label>
			<div class="col-md-6">
			<select name='product_category_id' rows='5' class='form-control product_category_id select2' data-show-subtext="true" data-live-search="true" >
			{!!$product_category_id !!}
			</select>
			</div>
			<div class="col-md-2 showline">
			<span class="showspan"> <i class="fa fa-refresh jcr_product_category_id"></i></span>
			</div>
		</div>
			
			<div class="form-group row pt ">
			     <label for="inputIsValid" class="form-control-label col-md-4"><span style="color: red;">*</span>Product Type </label>
			   <div class="col-md-6">
			   <select name='product_type_id' rows='5' class='form-control product_type_id select2' data-show-subtext="true" data-live-search="true" required>
				  {!!$product_type_id !!}
			   </select>
			  </div>
			<div class="col-md-2 showline">
			  <span class="showspan"> <i class="fa fa-refresh jcr_product_type_id"></i></span>
			</div>
		    </div>
			

	     <div class="fdivold">
		<div class="form-group row fdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Product code</label>
			<div class="col-md-6">
			<input class="form-control product_id" id="product_id" name="product_id" size="16" type="hidden" value="{{ $product_id }}" readonly>
			<input type="text" id="product_code" name="product_code" class="form-control product_code" value="{{$productdata['product_code'] }}">
			</div>
			<div class="col-md-2">
			</div>
		</div>

		<div class="form-group row rdiv">
			<label for="inputIsValid" class="form-control-label col-md-4"><span style="color: red;  ">*</span>Product Variant </label>
			<div class="col-md-6">
			<select name='product_variant_id' rows='5' class='form-control product_variant_id select2' data-show-subtext="true" data-live-search="true" required style="width:100%;">
				{!!$product_variant_id !!}
			</select>
			</div>
			<div class="col-md-2 showline">
			<span class="showspan"> <i class="fa fa-refresh jcr_product_variant_id"></i></span>
			</div>
		</div>

		<div class="form-group row rdiv">
			<label for="inputIsValid" class="form-control-label col-md-4"><span style="color: red;  ">*</span>Product Pack Type </label>
			<div class="col-md-6">
			<select name='product_packtype_id' rows='5' class='form-control product_packtype_id select2' data-show-subtext="true" data-live-search="true" required style="width:100%;">
			{!!$product_packtype_id !!}
			</select>
			</div>
			<div class="col-md-2 showline">
			<span class="showspan"> <i class="fa fa-refresh jcr_product_packtype_id"></i></span>
			</div>
		</div>

		<div class="form-group row fdiv product_pack">
			<label for="inputIsValid" class="form-control-label col-md-4"><span style="color: red;  ">*</span>Product Pack </label>
			<div class="col-md-6">
			<select name='product_pack_id' rows='5' class='form-control product_pack_id select2' data-show-subtext="true" data-live-search="true" required>
			{!!$product_pack_id !!}
			</select>
			</div>
			<div class="col-md-2 showline">
			<span class="showspan"> <i class="fa fa-refresh jcr_product_pack_id"></i></span>
			</div>
		</div>

	    <div class="form-group row fdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Concatenated product</label>
			<div class="col-md-6">

			<textarea style=" width: 140px; height: 78px;" id="concatenated_product" name="concatenated_product" data-value="0" class="form-control concatenated_product">{{ $productdata['concatenated_product'] }}</textarea>
			</div>
			<div class="col-md-2">
			</div>
		</div>
	  </div>
  	</div>

	    <div class="stdivold">
		  <div class="col-md-4" >
			<div class="form-group row stdiv">
				<label for="inputIsValid" class="form-control-label col-md-4">Product alternate name</label>
				<div class="col-md-6">
				  <input type="text" id="product_alternate_name" name="product_alternate_name" class="form-control product_alternate_name" value="{{ $productdata['product_alternate_name'] }}">
				</div>
				<div class="col-md-2">
				</div>
			</div>
			<div class="form-group row stdiv">
				<label for="inputIsValid" class="form-control-label col-md-4">Primary uom</label>
				<div class="col-md-6">
				<select name='primary_uom_id' rows='5' class='form-control primary_uom_id select2' data-show-subtext="true" data-live-search="true" >
				{!!$primary_uom_id !!}
				</select>
				</div>
				<div class="col-md-2 showline">
				<span class="showspan"> <i class="fa fa-refresh jcr_uom_code_id"></i></span>
				</div>
			</div>

			<div class="form-group row stdiv">
				<label for="inputIsValid" class="form-control-label col-md-4">Trx uom</label>
				<div class="col-md-6">
				<select name='trx_uom_id' rows='5' class='form-control trx_uom_id select2' data-show-subtext="true" data-live-search="true" >
				{!!$trx_uom_id!!}
				</select>
				</div>
				<div class="col-md-2 showline">
				<span class="showspan"> <i class="fa fa-refresh jcr_trx_uom_id"></i></span>
				</div>
			</div>

			<div class="form-group row stdiv">
				<label for="inputIsValid" class="form-control-label col-md-4">Hsn code</label>
				<div class="col-md-6">
					<select name='hsn_code' rows='5' class='form-control hsn_code select2' >
						{!!$hsn_code!!}
					</select>
				</div>
				<div class="col-md-2">
					<span class="showspan"> <i class="fa fa-refresh jcr_hsn_code"></i></span>
				</div>
</div>

			<div class="form-group row stdiv">
				<label for="inputIsValid" class="form-control-label col-md-4">Account code</label>
				<div class="col-md-6">
					<select name='account_code_id' rows='5' class='form-control account_code_id select2' >
					{!!$account_code_id!!}
					</select>
				</div>
				<div class="col-md-2">
					<span class="showspan"> <i class="fa fa-refresh jcr_account_code_id"></i></span>
				</div>
</div>

			<div class="form-group row stdiv">
				<label for="inputIsValid" class="form-control-label col-md-4">Subinventory</label>
				<div class="col-md-6">
				<select name='subinventory_id' rows='5' class='form-control subinventory_id select2' data-show-subtext="true" data-live-search="true" >
				{!!$subinventory_id !!}
				</select>
				</div>
				<div class="col-md-2 showline">
				<span class="showspan"> <i class="fa fa-refresh jcr_subinventory_id"></i></span>
				</div>
</div>

			<!--<div class="form-group row stdiv">
				<label for="inputIsValid" class="form-control-label col-md-4">Locator control</label>
				<div class="col-md-6">

				<div class="section">
				<input id='watch_me' name='overdue' type='radio' value="1" checked="checked" /> Yes
					<input id='watch_me' name='overdue' type='radio' value="2" /> No
				</div>
				</div>
            </div>-->
			  
			  <div class="form-group row stdiv">
				<label for="inputIsValid" class="form-control-label col-md-4">Locator control</label>
				<div class="col-md-6">

				<div class="section">
					<input id='locator_control' name='locator_control' type='radio' value="Yes" <?php echo ($productdata['locator_control'] =='Yes')?'checked':'' ?> /> Yes
					<input id='locator_control' name='locator_control' type='radio' value="No" <?php echo ($productdata['locator_control'] =='No')?'checked':'' ?>/> No
				</div>
				</div>
            </div>

			<div class="form-group row sublocate stdiv">
				<label for="inputIsValid" class="form-control-label col-md-4"><span style="color: red;  ">*</span>Locator Code</label>
				<div class="col-md-6">
				<select name='sublocator_id' rows='5' class='form-control sublocator_id select2' data-show-subtext="true" data-live-search="true" required>
				{!!$sublocator_id!!}
				</select>
				</div>
				<div class="col-md-2 showline">
				<span class="showspan"> <i class="fa fa-refresh jcr_sublocator_id"></i></span>
				</div>
			</div>
			  
			
			  
		</div>

		  <div class="col-md-4">
	

             
              

			<div class="form-group row stdiv">
				<label for="inputIsValid" class="form-control-label col-md-4">Min order qty</label>
				<div class="col-md-6">
				<input type="text" id="min_order_qty" name="min_order_qty" class="form-control min_order_qty" value="{{ $productdata['min_order_qty'] }}">
				</div>
				<div class="col-md-2">
				</div>
			</div>

			<div class="form-group row stdiv">
				<label for="inputIsValid" class="form-control-label col-md-4">Max order qty</label>
				<div class="col-md-6">

				<input type="text" id="max_order" name="max_order_qty" class="form-control max_order_qty" value="{{ $productdata['max_order_qty'] }}">
				</div>
				<div class="col-md-2">
				</div>
</div>

			<div class="form-group row stdiv">
				<label for="inputIsValid" class="form-control-label col-md-4">Re order qty</label>
				<div class="col-md-6">

				<input type="text" id="re_order_level" name="re_order_level" class="form-control re_order_level" value="{{ $productdata['re_order_level'] }}">
				</div>
				<div class="col-md-2">
				</div>
</div>

			 <!--<div class="form-group row stdiv">
				<label for="inputIsValid" class="form-control-label col-md-4">serial control</label>
				<div class="col-md-6">
				<div class="section">
				<input id='watch_now' name='overdue' type='radio' value="Yes" checked="checked" /> Yes
				<input id='watch_now' name='overdue' type='radio' value="No" /> No
				</div>
				</div>
				<div class="col-md-2">
				</div>
              </div>-->
			  
			  <div class="form-group row stdiv">
				<label for="inputIsValid" class="form-control-label col-md-4">serial control</label>
				<div class="col-md-6">
				<div class="section">
				<input id='serial_control' name='serial_control' type='radio' value="Yes" <?php echo ($productdata['serial_control'] =='Yes')?'checked':'' ?> /> Yes
				<input id='serial_control' name='serial_control' type='radio' value="No" <?php echo ($productdata['serial_control'] =='No')?'checked':'' ?> /> No
				</div>
				</div>
				<div class="col-md-2">
				</div>
            </div>

			<div class="form-group row serpre stdiv">
				<label for="inputIsValid" class="form-control-label col-md-4">Serial prefix</label>
				<div class="col-md-6">

				<input type="text" id="serial_prefix" name="serial_prefix" class="form-control serial_prefix" value="{{ $productdata['serial_prefix'] }}">
				</div>
				<div class="col-md-2">
				</div>
</div>

			<div class="form-group row">
				<label for="inputIsValid" class="form-control-label col-md-4">Organization</label>
				<div class="col-md-6">
				<select name='organization_id' rows='5' class='form-control organization_id select2' data-show-subtext="true" data-live-search="true" >
				{!!$organization_id!!}
				</select>
				</div>
				<div class="col-md-2 showline">
				<!-- <span class="showspan"> <i class="fa fa-refresh jcr_organization_id"></i></span> -->
				</div>
			</div>

		</div>
	</div>
<br>

	    <!-- raja Code for lines level data-->
	           <div class="row linesdiv">
				   <div class="col-md-12">
					   <a href="javascript:void(0);"  class="add_row additem"  rel=".rcopy"><i class="fa fa-plus"></i> New Item</a>



								 <div id="preview-area" class="chandru">
							<table class="overflow-y preview product_tbl">

							   <thead >



								<tr>
								<th>Line No</th>
								<th>Product Code</th>
								<th>Product Pack</th>
								<th>Concat Product</th>
								<th>HSN Code</th>
								<th>Primary Uom</th>
								<th>Trx Uom</th>
								<th>Subinventory</th>
								<th>Locator</th>
								<th>&nbsp;</th>
									<th></th>
								</tr>
								</thead>  <?php //dd($productdata); ?>
							   <tbody class="product_tbl_lines_body">
								   <?php if($parent_id>=1)
                                      { ?>
									 @foreach($productdata as $key=>$value)
								     <tr>

								     </tr>
									 @endforeach
                                    <?php  }
								       if($parent_id < 1 ) {
								      ?>

								 <tr class="rcopy clone">

								   <td><input type="hidden" name="bulk_product_id[]" class="form-control input-sm bulk_product_id" value=""></td>
								   <td><input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="1" readonly="readonly"></td>
								   <td><input type="text" name="bulk_product_code[]" class="form-control input-sm bulk_product_code"></td>
				<td><select name='bulk_product_pack_id[]' rows='5' class='form-control bulk_product_pack_id select2' data-show-subtext="true" data-live-search="true" required>
			                  {!!$product_pack_id !!}
			    </select></td>
							 <td><input type="text" name="bulk_concatenated_product[]" class="form-control input-sm bulk_concatenated_product" data-value="0" readonly="readonly"></td>
							       <td><select name='bulk_hsn_code[]' rows='5' class='form-control bulk_hsn_code select2' >
					                 	{!!$hsn_code!!}
					                  </select></td>
					<td><select name='bulk_primary_uom_id[]' rows='5' class='form-control bulk_primary_uom_id select2' data-show-subtext="true" data-live-search="true">
				                   {!!$primary_uom_id !!}
				                </select></td>
				   <td><select name='bulk_trx_uom_id[]' rows='5' class='form-control bulk_trx_uom_id select2' data-show-subtext="true" data-live-search="true">
				                   {!! $trx_uom_id !!}
				                </select></td>
			      <td><select name='bulk_subinventory_id[]' rows='5' class='form-control bulk_subinventory_id select2' data-show-subtext="true" data-live-search="true" >
				               {!!$subinventory_id !!}
				   </select></td>
	             <td><select name='bulk_sublocator_id[]' rows='5' class='form-control bulk_sublocator_id select2' data-show-subtext="true" data-live-search="true" required>
				              {!!$sublocator_id!!}
				          </select></td>
								   <td><a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a></td>
                                  <td>   <a class=""><i class="fa btn-xs fa-2x fa-plus-circle addbtn" data-toggle="modal" data-target="#prdaddinfo" aria-hidden="true"></i></a>
									   <input type="hidden" name="counter[]">
                                   </td>
								 </tr>
								   <?php } ?>
							   </tbody>
						   </table>
					   </div>
				   </div>
	           </div>
	    <!--end -->
    <div class="modal fade" id="prdaddinfo" role="dialog">
    <div class="modal-dialog" style="width:700px;">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Additional Details</h4>
        </div>
		  
        <div class="">
		  <div class="col-md-12">
			  
		  <div class="col-md-6">   
              <div class="form-group">
				<label for="inputIsValid" class="form-control-label col-md-4">Min order qty</label>
				<div class="col-md-6">
				<input type="text" id="bulk_min_order_qty" name="bulk_min_order_qty[]" class="form-control bulk_min_order_qty" value="">
				</div>
		     </div> 
		   </div>
			  
		  <div class="col-md-6"> 	
			  <div class="form-group">
				<label for="inputIsValid" class="form-control-label col-md-4">Max order qty</label>
				<div class="col-md-6">
				<input type="text" id="bulk_max_order_qty" name="bulk_max_order_qty[]" class="form-control bulk_max_order_qty" value="">
				</div>
		     </div>  
		  </div>
			  
		 <div class="col-md-6"> 	
			  <div class="form-group">
				<label for="inputIsValid" class="form-control-label col-md-4">Reorder qty</label>
				<div class="col-md-6">
		<input type="text" id="bulk_re_order_level" name="bulk_re_order_level[]" class="form-control bulk_re_order_level" value="">
				</div>
		     </div>  
		  </div>	  
			  
			  
		 <div class="col-md-6"> 	
			  <div class="form-group">
				<label for="inputIsValid" class="form-control-label col-md-4">Serial prefix</label>
				<div class="col-md-6">
				<input type="text" id="bulk_serial_prefix" name="bulk_serial_prefix[]" class="form-control bulk_serial_prefix" value="">
				</div>
		     </div>  
		  </div>	  	  
			  
		</div>
        </div>
		  
        <div>
			<div class="col-md-offset-5">
			  <button type="reset" class="btn btn-default reset">Reset</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>		
        </div>
		  
      </div>
      
    </div>
  </div>
	    <div class="row">
		<div class="col-lg-12 col-md-12">
			<div class="form-group text-center">

				<input type="hidden" name="submit_type" class="submit_type" id="submit_type">
				<?php if($product_id==""){ ?>
                <button type="button"  name="apply" class='btn applychanges saveform' value="APPLYCHANGES" >Apply Changes</button>
				<?php  }  ?>
				<button type="button" id="save" class="btn save saveform" value="SAVE">Save</button>
				 <a class='btn cancel' onclick="location.href = '{{URL::to('product')}}'">Cancel</a>

			</div>
		</div>
	</div>

</div>

</div>
	</div>
	</form>
</div>


	<script>
	$(document).ready(function(){

		 $('.linesdiv').hide();
		$('.concatenated_product').attr('readonly',true);


		
	$(".jcr_product_group_id").click(function(){
		$(".product_group_id").jCombo("{{ URL::to('jcomboform?table=m_product_groups_t:product_group_id:group_name')}}",
			{selected_value:""});
	});

	$(".jcr_product_category_id").click(function(){
		var prdgroup=$('.product_group_id').select2('val');
			var condition = ' product_group_id='+prdgroup;
			$(".product_category_id").jCombo("{{ URL::to('jcomboform?table=m_product_category_t:product_category_id:category_name') }}&order_by=category_name asc"+'&parent='+condition,
	{selected_value:""});
	});

	$(".jcr_uom_code_id").click(function(){
		$(".primary_uom_id").jCombo("{{ URL::to('jcomboform?table=m_uom_codes_t:uom_code_id:uom_code')}}",
			{selected_value:""});
	});

	$(".jcr_trx_uom_id").click(function(){
		$(".trx_uom_id").jCombo("{{ URL::to('jcomboform?table=m_uom_codes_t:uom_code_id:uom_code')}}",
			{selected_value:""});
	});


	$(".jcr_subinventory_id").click(function(){
		$(".subinventory_id").jCombo("{{ URL::to('jcomboform?table=m_subinventory_t:subinventory_id:subinventory_name')}}",
			{selected_value:""});
	});


	$(".jcr_sublocator_id").click(function(){
		$(".sublocator_id").jCombo("{{ URL::to('jcomboform?table=m_sublocators_t:sublocator_id:locator_code')}}",
			{selected_value:""});
	});


		/*$(document).on('change','.product_group_id',function(){
			var prdgroup=$('.product_group_id').select2('val');
			var condition = ' product_group_id='+prdgroup;
			$(".product_category_id").jCombo("{{ URL::to('jcomboform?table=m_product_category_t:product_category_id:category_name') }}&order_by=category_name asc"+'&parent='+condition,
	{selected_value:""});

		});*/

		$(document).on('change','.product_category_id',function(){
		   var prdgroup=$('.product_group_id').select2('val');
			if(prdgroup=="")
			{
			     notyMsg("info","Please select product group name");
				$('.product_category_id').val('').select2();
			}
		});


	// $(".jcr_organization_id").click(function(){
	// 	$(".organization_id").jCombo("{{ URL::to('jcomboform?table=m_organizations_t:organization_id:organization_name')}}",
	// 		{selected_value:""});
	// });

     /* raja code for concat product create*/
		$(document).on('change keyup','.product_variant_id,.product_pack_id,.product_packtype_id,.bulk_product_pack_id,.product_type_id',function(){
			  var index = $(this).closest('tr').index();

			var product_variant_id =$('.product_variant_id option:selected').text();
			var product_pack_id =$('.product_pack_id option:selected').text();
			var product_packtype_id =$('.product_packtype_id option:selected').text();
			var bulk_product_pack_id =$('.bulk_product_pack_id'+index+' option:selected').text();
			var product_type_id =$('.product_type_id option:selected').text();
            
			  var product_group_id= $('.product_group_id option:selected').text();
			   var product_category_id= $('.product_category_id option:selected').text(); 
			
			 if(product_group_id =="FINISHED GOODS" && product_category_id=="FINISHED")
			 {
		     	var lineconcatenated_productdata =(product_type_id+'-'+product_variant_id+'-'+product_packtype_id+'-'+bulk_product_pack_id);
				  $('.bulk_concatenated_product'+index).val(lineconcatenated_productdata); 
			 }
			 else if(product_group_id =="SEMI FINISHED GOODS" && product_category_id=="FINISHED")
			 {
		     	var lineconcatenated_productdata =(product_type_id+'-'+product_variant_id+'-'+product_packtype_id+'-'+bulk_product_pack_id);
				  $('.bulk_concatenated_product'+index).val(lineconcatenated_productdata); 
			 }
			
			  
	
			 /* else
			 {
			      var concatenated_productdata =(product_variant_id+'-'+product_packtype_id+'-'+product_pack_id);
				   $('.concatenated_product').val(concatenated_productdata);
			 } */
			
         

			 var header= $('.concatenated_product').data('value');
			 var lines= $('.bulk_concatenated_product').data('value');

			if(header=='1')
			{
		       $('.concatenated_product').val(concatenated_productdata);
		       $('.bulk_concatenated_product').val("");
			}
			else
			{   	//alert("2"+lineconcatenated_productdata);
				 $('.concatenated_product').val("");
		         $('.bulk_concatenated_product'+index).val(lineconcatenated_productdata);

			}
		});
		/* end */

		<?php if($group_name == "RAW MATERIALS" || $group_name == "PACKING MATERIALS" ){ ?>

	        $('.product_group_id').trigger('click');
			      $(".fdiv,.stdiv,.rtype").show();
				  $(".rdiv,.product_pack,.pt").hide();
				  $('.linesdiv').css("display", "none");
				  $('.product_variant_id,.product_packtype_id,.bulk_product_pack_id,.bulk_sublocator_id,.product_type_id,.product_pack_id').prop('required',false);
				  $('.concatenated_product').attr('data-value','1');
				    $('.concatenated_product').attr('readonly',false);
				  $('.bulk_concatenated_product').attr('data-value','0');
				  $('#linescheck').val('0');
		          $('.product_type_id').val(0);  
		  <?php } 
		       else 
			   { ?>
		          // $('.product_group_id').trigger('click');
		          // $(".fdiv,.stdiv,.rtype").hide();
		          // $(".rdiv,.product_pack").show();
		<?php } ?>
		
		/*raja code for rawmaterial and product group based div display*/
		  $(document).on('change','.product_group_id',function(){
		     //var product_category_id = $('.product_category_id option:selected').text();
		     var product_group_id = $('.product_group_id option:selected').text();
			 // if(product_category_id=="FINISHED" && product_group_id=="FINISHED GOODS")
			  if(product_group_id=="FINISHED GOODS" || product_group_id=="SEMI FINISHED GOODS")
			  {
				 $('.concatenated_product').attr('data-value','0');
				 $('.bulk_concatenated_product').attr('data-value','1');

				 //$('.sublocator_id').prop('required',false);
				   $('.product_pack_id,.sublocator_id,.product_type_id').removeAttr('required');
			       $(".fdiv,.stdiv,.rtype").hide();
			       $(".linesdiv,.rdiv").show();
			       $('#linescheck').val('1');
                       
			  }
			  else
			  {   //alert("dafga");
				  $('.product_group_id').trigger('click');
			      $(".fdiv,.stdiv,.rtype").show();
				  $(".rdiv,.product_pack,.ftype,.pt").hide();
				  $('.linesdiv').css("display", "none");
				  $('.product_variant_id,.product_packtype_id,.bulk_product_pack_id,.bulk_sublocator_id,.product_pack_id').prop('required',false);
				  $('.concatenated_product').attr('data-value','1');
				    $('.concatenated_product').attr('readonly',false);
				  $('.bulk_concatenated_product').attr('data-value','0');
				  $('#linescheck').val('0');
			  }
		  });
		/* end */

		$(".add_row").relCopy(data);
		 changeclassfields();

		 $('.add_row').click(function(){
             changeclassfields();
         });


	$(document).on('change','.subinventory_id',function(){
			var subinv=$('.subinventory_id').val();
            var condition ='subinventory_id ='+subinv;
$(".sublocator_id").jCombo("{{ URL::to('jcomboform?table=m_sublocators_t:sublocator_id:locator_code') }}&order_by=locator_code asc"+"&parent="+condition,
{selected_value:""});
});

$(document).on('change','.subinventory_id',function(){
    var subinv=$('.subinventory_id').val();
    var condition ='subinventory_id ='+subinv;
    $(".sublocator_id").jCombo("{{ URL::to('jcomboform?table=m_sublocators_t:sublocator_id:locator_code') }}&order_by=locator_code asc"+"&parent="+condition,
    {selected_value:""});

});

$(document).on('click','.salesperson_id',function(){
    $(".salesperson_id").jCombo("{{ URL::to('jcomboform?table=s_salesperson_t:salesperson_id:salesperson_name') }}&order_by=salesperson_name asc",
    {selected_value:""});
});

$(document).on('click','.jcr_hsn_code',function(){
$(".hsn_code").jCombo("{{ URL::to('jcomboform?table=f_gst_code_hdr_t:gst_code_hdr_id:classification_code') }}&order_by=classification_code asc",
{selected_value:""});
});

$(document).on('click','.jcr_account_code_id',function(){
$(".account_code_id").jCombo("{{ URL::to('jcomboform?table=f_account_structure_t:f_account_structure_id:concatenated_segments') }}&order_by=concatenated_segments asc",
{selected_value:""});
});

var organization = '<?php echo Session::get('organization'); ?>' ;
        $('.organization_id').val(organization).change();

		$(document).on('click','.saveform',function(){
		   var btnval		= $(this).val();

			if(btnval == 'APPLYCHANGES')
                var savestatus = 'APPLY CHANGES';
			else(btnval == 'SAVE')
                var savestatus = 'SAVE';



			$('#savestatus').val(savestatus);
			 $('.submit_type').val("save");

			 var url		= "{{ URL::to('productsave') }}";
			 validationrule('productform');
			 var formdata	= $('#productform').serialize();
			 var form = $('#productform');
		     var red_url = "{{ URL::to('product') }}";

			if(btnval != 'APPLYCHANGES')
              {
			    form.parsley().validate();
		          var form = $('#productform');
		          form.parsley().validate();

				   if(form.parsley().isValid())
                   {
					   $.post(url,formdata,function(data)
					   { 																																(url);
					        var status      = data.status;
						    var msg         = data.message;
						    var id          = data.id;
						    var edit_url	= "{{ URL::to('productedit') }}/"+id;

						   if(btnval !='SAVE')
                              {
                                notyMsg(status,msg);
                                setTimeout(function(){
                                window.location.href=edit_url;
                                }, 1500);
                              }
						       else
                               {
									notyMsg(status,msg);
									setTimeout(function(){
									window.location.href=red_url;
									}, 1500);
                               }
					   });
				   }
			  }
			  else
			  {
			     $.post(url,formdata,function(data)
				    {
						var status = data.status;
						var msg    = data.message;
						var id     = data.id;
						var edit_url	="{{ url('productedit') }}/"+id;
							notyMsg(status,msg);
							setTimeout(function(){
							window.location.href=edit_url;
							}, 1500);
					});
			  }

		});

		
		$(document).on('click','#locator_control',function()
   {
		var id=$(this).val();

		if(id=='Yes')
		{
			$('.sublocate').show();
			$('.sublocator_id').attr('required',true);
		}
		else if(id=='No')
		{
			$('.sublocate').hide();
			$('.sublocator_id').attr('required',false);

		}
	});


	$(document).on('click','#serial_control',function()
   {
		var id=$(this).val();

		if(id=='Yes')
		{
			$('.serpre').show();
			$('#serial_prefix').attr('required',true);
		}
		else if(id=='No')
		{
			$('.serpre').hide();
			$('#serial_prefix').attr('required',false);
		}
	});
		



	$(document).on('click','.remove',function()
	 {
	   var index = $(this).closest('tr').index();
	   var rowCount = $('.product_tbl tbody tr').length;
		if(rowCount > 1)
		{
			$($(this).closest("tr")).remove();
			removeclassfields();
		}
		else
		{
			notMsg('notyMsg',"You Can't Delete Atleast One row should be there");
		}
    });

	function changeClassName(className){
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

function removeClass(className)
{
	var rowCount = $('.product_tbl tbody tr').length;
	for(var i=0;i<=rowCount;i++)
	{
	$('.product_tbl tbody tr').find('.'+className).removeClass(className+i);
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

function changeclassfields(){
changeClassName('bulk_product_id');
changeClassName('bulk_line_no');
changeClassName('bulk_product_code');
changeClassName('bulk_product_pack');
changeClassName('bulk_product_pack_id');
changeClassName('bulk_concatenated_product');
changeClassName('bulk_hsn_code');
changeClassName('bulk_primary_uom_id');
changeClassName('bulk_trx_uom_id');
changeClassName('bulk_subinventory_id');
changeClassName('bulk_sublocator_id');
changeClassName('bulk_min_order_qty');
changeClassName('bulk_max_order_qty');
changeClassName('bulk_re_order_level');
changeClassName('bulk_serial_prefix');
changeClassName('addbtn');
}
function removeclassfields()
{
removeClass('bulk_product_id');
removeClass('bulk_line_no');
removeClass('bulk_product_code');
removeClass('bulk_product_pack');
removeClass('bulk_product_pack_id');
removeClass('bulk_concatenated_product');
removeClass('bulk_hsn_code');
removeClass('bulk_primary_uom_id');
removeClass('bulk_trx_uom_id');
removeClass('bulk_subinventory_id');
removeClass('bulk_sublocator_id');
removeClass('bulk_min_order_qty');
removeClass('bulk_max_order_qty');
removeClass('bulk_re_order_level');
removeClass('bulk_serial_prefix');
removeClass('addbtn');

}




	});




	</script>
@include('layouts.php_js_validation')
@endsection
