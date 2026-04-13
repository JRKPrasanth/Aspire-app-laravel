@extends('layouts.header')
@section('content')
<style type="text/css">
	.select2-container{
		height: 30px;
	}
	.organization{
		pointer-events: none;
	}
</style>
<div id="accordion">
	<div class="panel-heading" role="tab" id="headingOne">
		<h4 class="panel-title"><a role="button">Product Upload</a></h4>
		<span class="ui_close_btn">
			<a class="collapse-close pull-right btn-danger" onclick='location.href="{{URL::to('productupload')}}"'></a>
		</span>
	</div>
</div>
<div class="row">
	
<form action="{{ url('productuploadsave') }}" method="post" id="productupload" data-parsley-validate>
{{ csrf_field() }}
<div class="col-lg-12">
<div class="card">
	
<div class="card-body card-block">
<div class="col-md-12">
    <div class="col-md-4">
		
	 	<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Product group Name</label>
			<div class="col-md-6">
				<input class="form-control product_upload_id" id="product_upload_id" name="product_upload_id" size="16" type="hidden" value="{{ $productuploaddata['product_upload_id'] }}" readonly>
				<select name='product_group_name' rows='5' class='form-control product_group_name select2' >
					{!! $product_group_name !!}
				</select>
			</div>
			<div class="col-md-2 showline">
				<!-- <span class="showspan"> <i class="fa fa-refresh jcr_product_group_name"></i></span> -->
			</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Product category </label>
			<div class="col-md-6">
				<select name='product_category_name' rows='5' class='form-control product_category_name select2' >
					{!! $product_category_name !!}
				</select>
			</div>
			<div class="col-md-2 showline">
				<!-- <span class="showspan"> <i class="fa fa-refresh jcr_product_category_name"></i></span> -->
			</div>
		</div>
	  	<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Product Sub Category </label>
				<div class="col-md-6 sel2">
				<select name='product_subcategory_name' rows='5' class='form-control product_subcategory_name select2' >
					{!! $product_subcategory_name !!}
				</select>
			</div>
			<div class="col-md-2 showinline">
			</div>
		</div>

		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">QC Type </label>
				<div class="col-md-6 sel2">
				<select name='qctype'  class='form-control qctype select2' id="qctype"  >
					<option value="">--Please Select--</option>
					<option value="BATCHWISE" <?php if($productuploaddata['qctype'] == "BATCHWISE") echo "selected"; ?> >BATCHWISE</option>
					<option value="SERIALWISE"  <?php if($productuploaddata['qctype'] == "SERIALWISE") echo "selected"; ?>>SERIALWISE</option>
				</select>
			</div>
			<div class="col-md-2 showinline"></div>
		</div>
		<div class="form-group row ">
			<label for="inputIsValid" class="form-control-label col-md-4"><span style="color: red;">*</span>Product Name</label>
			<div class="col-md-6">
			  	<input type="text" id="product_name" name="product_name" class="form-control product_name" required value="{{ $productuploaddata['product_name'] }}">
			</div>
			<div class="col-md-2 chbox"></div>
		</div>
		<div class="form-group row fdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Product Pack </label>
				<div class="col-md-6 sel2">
				<select name='product_pack' rows='5' class='form-control product_pack select2' >
					{!! $product_pack !!}
				</select>
			</div>
			<div class="col-md-2 fdiv">
			</div>
		</div>	
		<!-- <div class="form-group row ">
			<label for="inputIsValid" class="form-control-label col-md-4">Product Family Name </label>
			<div class="col-md-6">
				<select name='product_family_name' rows='5' class='form-control product_family_name select2' >
					
				</select>
			</div>
			<div class="col-md-2 showline"></div>
		</div>
		
		<div class="form-group row fdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Product Base Name </label>
			<div class="col-md-6">
				<select name='product_base_name' rows='5' class='form-control product_base_name select2' >
					
				</select>
			</div>
			<div class="col-md-2 showline"></div>
		</div> -->

	    <div class="form-group row fdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Concatenated product</label>
			<div class="col-md-6">
				<textarea style=" width: 140px; height: 78px;" readonly id="concatenated_product" name="concatenated_product" data-value="0" class="form-control concatenated_product">{{ $productuploaddata['concatenated_product'] }}</textarea>
			</div>
			<div class="col-md-2"></div>
		</div>
		<div class="form-group row fdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Product code</label>
			<div class="col-md-6">
				<input type="text" id="product_code" name="product_code" class="form-control product_code" value="{{$productuploaddata['product_code'] }}">
			</div>
			<div class="col-md-2"></div>
		</div>
  	</div>
	    
  	<div class="col-md-4" >
		
	  	<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Product alternate name</label>
			<div class="col-md-6">
			  	<input type="text" id="product_alternate_name" name="product_alternate_name" class="form-control product_alternate_name" value="{{ $productuploaddata['product_alternate_name'] }}">
			</div>
			<div class="col-md-2"></div>
		</div>
		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Primary uom</label>
			<div class="col-md-6">
			<select name='primary_uom_name' rows='5' class='form-control primary_uom_name select2' >
				{!!$primary_uom_name !!}
			</select>
			</div>
			<div class="col-md-2 showline">
				<!-- <span class="showspan"> <i class="fa fa-refresh jcr_primary_uom_name"></i></span> -->
			</div>
		</div>

		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Trx uom</label>
			<div class="col-md-6">
			<select name='trx_uom_name' rows='5' class='form-control trx_uom_name select2' >
			{!!$trx_uom_name!!}
			</select>
			</div>
			<div class="col-md-2 showline">
				<!-- <span class="showspan"> <i class="fa fa-refresh jcr_trx_uom_name"></i></span> -->
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
				<!-- <span class="showspan"> <i class="fa fa-refresh jcr_hsn_code"></i></span> -->
			</div>
		</div>

		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Subinventory</label>
			<div class="col-md-6">
				<select name='subinventory_name' rows='5' class='form-control subinventory_name select2' >
					{!!$subinventory_name !!}
				</select>
			</div>
			<div class="col-md-2 showline">
				<!-- <span class="showspan"> <i class="fa fa-refresh jcr_subinventory_name"></i></span> -->
			</div>
		</div>

		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Locator control</label>
			<div class="col-md-6">

				<div class="section">
					<input id='locator_control' name='locator_control' type='radio' value="YES" <?php echo ($productuploaddata['locator_control']=='YES')?'checked':'' ?>  /> YES
					<input id='locator_control' name='locator_control' type='radio' value="NO" <?php if(empty($productuploaddata['locator_control'])) echo 'checked'; echo ($productuploaddata['locator_control']=='NO')?'checked':'' ?>  /> NO
				</div>
			</div>
		</div>

		<div class="form-group row sublocate stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Locator Code</label>
			<div class="col-md-6">
			<select name='sublocator_name' rows='5' class='form-control sublocator_name select2' >
				{!!$sublocator_name!!}
			</select>
			</div>
			<div class="col-md-2 showline">
				<!-- <span class="showspan"> <i class="fa fa-refresh jcr_sublocator_name"></i></span> -->
			</div>
		</div>
		<div class="form-group row ">
			<label for="inputIsValid" class="form-control-label col-md-4"><span style="color: red;">*</span>SAC code</label>
			<div class="col-md-6 sel2">
				<select name='sac_code' rows='5' class='form-control sac_code select2' required >
					{!!$sac_code!!}
				</select>
			</div>
			<div class="col-md-2 showinline">
				<!-- <span class="showspan"> <i class="fa fa-refresh jcr_sac_code"></i></span> -->
			</div>
		</div>
		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Account code</label>
			<div class="col-md-6">
				<select name='account_code_name' rows='5' class='form-control account_code_name select2' >
				{!!$account_code_name!!}
				</select>
			</div>
			<div class="col-md-2">
				<!-- <span class="showspan"> <i class="fa fa-refresh jcr_account_code_name"></i></span> -->
			</div>
		</div>

	</div>

  	<div class="col-md-4">

		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Min order qty</label>
			<div class="col-md-6">
				<input type="text" id="min_order_qty" name="min_order_qty" class="form-control min_order_qty" value="{{ $productuploaddata['min_order_qty'] }}">
			</div>
			<div class="col-md-2"></div>
		</div>

		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Max order qty</label>
			<div class="col-md-6">
				<input type="text" id="max_order" name="max_order_qty" class="form-control max_order_qty" value="{{ $productuploaddata['max_order_qty'] }}">
			</div>
			<div class="col-md-2"></div>
		</div>
		
		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Re order qty</label>
			<div class="col-md-6">
				<input type="text" id="re_order_level" name="re_order_level" class="form-control re_order_level" value="{{ $productuploaddata['re_order_level'] }}">
			</div>
			<div class="col-md-2"></div>
		</div>

		<!-- <div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">serial control</label>
			<div class="col-md-6">
				<div class="section">
					<input id='serial_control' name='serial_control' type='radio' value="YES" <?php echo ($productuploaddata['serial_control']=='YES')?'checked':'' ?>  /> YES
					<input id='serial_control' name='serial_control' type='radio' value="NO" <?php if(empty($productuploaddata['serial_control'])) echo 'checked'; echo ($productuploaddata['serial_control']=='NO')?'checked':'' ?> /> NO
				</div>
			</div>
			<div class="col-md-2"></div>
		</div>

		<div class="form-group row serpre stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Serial prefix</label>
			<div class="col-md-6">

			<input type="text" id="serial_prefix" name="serial_prefix" class="form-control serial_prefix" value="{{ $productuploaddata['serial_prefix'] }}">
			</div>
			<div class="col-md-2"></div>
		</div> -->

		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Company</label>
			<div class="col-md-6">
				<select name='company_code' rows='5' class='form-control company_code select2' >
					{!!$company_code!!}
				</select>
			</div>
			<div class="col-md-2 showline">
				<!-- <span class="showspan"> <i class="fa fa-refresh jcr_company_code"></i></span> -->
			</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Batch Name</label>
			<div class="col-md-6">
				<input type="text" name="batch_name" id="batch_name" value="{{ $productuploaddata['batch_name'] }}" class="form-control batch_name" readonly>
			</div>
			<div class="col-md-2 showline">

				</div>
		</div>

		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Batch Date</label>
			<div class="col-md-6">
				<input type="text" name="batch_date" id="batch_date" value="{{ $productuploaddata['batch_date'] }}" class="form-control batch_date" readonly>
			</div>
			<div class="col-md-2 showline">

				</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Batch Status</label>
			<div class="col-md-6">
				<input type="text" name="batch_status" id="batch_status" value="{{ $productuploaddata['batch_status'] }}" class="form-control batch_status" readonly>
			</div>
			<div class="col-md-2 showline">

				</div>
		</div>

		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Batch Comments</label>
			<div class="col-md-6">
				<textarea style=" width: 237px; height: 140px;" id="batch_comments" readonly name="batch_comments" class="form-control batch_comments">{{ $productuploaddata['batch_comments'] }}</textarea>
				
			</div>
			<div class="col-md-2 showline">

				</div>
		</div>

	</div>
</div>	     
<br>
   
</div>
<div class="row">
	<div class="col-lg-12 col-md-12">
		<div class="form-group text-center">
			<!--  <a class='btn applychanges'>Apply Changes</a>-->
			<button type="button" class="btn save">Save</button>
			<a href="../productupload" class='btn cancel'  >Cancel</a>

		</div>
	</div>
</div>
	
</div>
</div>
	
	</form>

	<div class="col-lg-2"></div>
</div>
@extends('layouts.footer')

<script>
$(document).ready(function(){

	$(".product_group_name").change(function(){
		var group_name =$('.product_group_name').select2('val');
		var condition = ' product_group_id='+group_name; 	 
		$(".product_category_name").jCombo("{{ URL::to('jcomboform?table=m_product_category_t:product_category_id:category_name')}}&order_by=product_group_id asc"+'&parent='+condition, {selected_value:""});
	});

	$(".product_category_name").change(function(){
		var category_name =$('.product_category_name').select2('val');
		var condition = ' product_category_id='+category_name; 	 
		$(".product_subcategory_name").jCombo("{{ URL::to('jcomboform?table=m_product_subcategory_t:subcategory_name:subcategory_name')}}&order_by=product_category_id asc"+'&parent='+condition, {selected_value:""});
	});

	$(".subinventory_name").change(function(){
		var sub_name =$('.subinventory_name').select2('val');
		var condition = ' subinventory_id='+sub_name; 	 
		$(".sublocator_name").jCombo("{{ URL::to('jcomboform?table=m_sublocators_t:sublocator_id:locator_code')}}&order_by=locator_code asc"+'&parent='+condition, {selected_value:""});
	});

	$(document).on('click','.save',function()
    {
    	var url		= "{{ URL::to('productuploadsave') }}";
    	var create_url = "{{ URL::to('productupload') }}";
    	var formdata	= $('#productupload').serialize();
    	var form = $('#productupload');
    	$.post(url,formdata,function(data)
        {

        	var status      = data.status;
			var msg			=data.message;
            notyMsg(status,msg);
            setTimeout(function(){
           		window.location.href=create_url;
            }, 1500);
        });
    });

});

</script>

@endsection
