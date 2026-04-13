@extends('layouts.header')
@section('content')
<span class="ui_close_btn"></span>


<form action="{{ url('productuploadsave') }}" method="post" id="productupload" data-parsley-validate>
{{ csrf_field() }}



		<h2 class="heads">Product Upload

<span class="ui_close_btn"><a href="../productupload" class="collapse-close pull-right btn-danger" onclick="../productupload"></a></span>
		</h2>
		



<div class="card">
	

<div class="card-body card-block">
<div class="col-md-12">
    <div class="col-md-4">
		
	 	<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Product Group Name</label>
			<div class="col-md-6">
				<input class="form-control product_upload_id" id="product_upload_id" name="product_upload_id" size="16" type="hidden" value="{{ $productuploaddata['product_upload_id'] }}" readonly>
				<select name='product_group_name' rows='5' class='form-control product_group_name select2' >
					{!! $product_group_name !!}
				</select>
			</div>
			<div class="col-md-2 showline"></div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Product Category </label>
			<div class="col-md-6">
				<select name='product_category_name' rows='5' class='form-control product_category_name select2' >
					{!!$product_category_name !!}
				</select>
			</div>
			<div class="col-md-2 showline"></div>
		</div>

		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Product Sub Category </label>
			<div class="col-md-6">
				<select name='product_subcategory_name' rows='5' class='form-control product_subcategory_name select2' >
					{!!$product_subcategory_name !!}
				</select>
			</div>
			<div class="col-md-2 showline"></div>
		</div>
	  	
	  	<div class="form-group row ">
			<label for="inputIsValid" class="form-control-label col-md-4">Product Type </label>
			<div class="col-md-6">
				<select name='product_type_name' rows='5' class='form-control product_type_name select2' id="product_type_name" >
					{!!$product_type_name!!}
				</select>
			</div>
			<div class="col-md-2 showline"></div>
		</div>

		<div class="form-group row fdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Product Code</label>
			<div class="col-md-6">
				<input type="text" id="product_code" name="product_code" class="form-control product_code" value="{{$productuploaddata['product_code'] }}">
			</div>
			<div class="col-md-2"></div>
		</div>
		
		<div class="form-group row ">
			<label for="inputIsValid" class="form-control-label col-md-4">Product Varient </label>
			<div class="col-md-6">
				<select name='product_variant_name' rows='5' class='form-control product_variant_name select2' >
					{!!$product_variant_name!!}
				</select>
			</div>
			<div class="col-md-2 showline"></div>
		</div>
		
		<div class="form-group row ">
			<label for="inputIsValid" class="form-control-label col-md-4">Product Pack Type </label>
			<div class="col-md-6">
				<select name='product_packtype_name' rows='5' class='form-control product_packtype_name select2' id="product_packtype_name" >
					{!! $product_packtype_name !!}
				</select>
			</div>
			<div class="col-md-2 showline"></div>
		</div>
		
		<div class="form-group row fdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Product Pack </label>
			<div class="col-md-6">
				<select name='product_pack_name' rows='5' class='form-control product_pack_name select2' >
					{!!$product_pack_name !!}
				</select>
			</div>
			<div class="col-md-2 showline"></div>
		</div>

	    <div class="form-group row fdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Concatenated Product</label>
			<div class="col-md-6">
				<textarea  id="concatenated_product" name="concatenated_product" data-value="0" class="form-control concatenated_product">{{ $productuploaddata['concatenated_product'] }}</textarea>
			</div>
			<div class="col-md-2"></div>
		</div>
	  	
	  	<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Product Alternate Name</label>
			<div class="col-md-6">
			  	<input type="text" id="product_alternate_name" name="product_alternate_name" class="form-control product_alternate_name" value="{{ $productuploaddata['product_alternate_name'] }}">
			</div>
			<div class="col-md-2"></div>
		</div>

		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Primary Uom</label>
			<div class="col-md-6">
				<select name='primary_uom_name' rows='5' class='form-control primary_uom_name select2' >
					{!!$primary_uom_name !!}
				</select>
			</div>
			<div class="col-md-2 showline"></div>
		</div>

		<div class="form-group row ">
			<label for="inputIsValid" class="form-control-label col-md-4">Active </label>
			<div class="col-md-6">
				<select name="active" name="tax_credit" class=" form-control active select2" id="active" >
                    <option value="Yes" <?php  if($productuploaddata['active']=='Yes'){ echo "selected";  }?> >Yes</option>
                    <option value="No" <?php if($productuploaddata['active']=='No'){ echo "selected";  }?>>No</option>
                </select>
			</div>
			<div class="col-md-2 showline"></div>
		</div>
		
  	</div>
	    
  	<div class="col-md-4" >
		
  		

		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Trx Uom</label>
			<div class="col-md-6">
			<select name='trx_uom_name' rows='5' class='form-control trx_uom_name select2' >
			{!!$trx_uom_name!!}
			</select>
			</div>
			<div class="col-md-2 showline"></div>
		</div>

		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Hsn Code</label>
			<div class="col-md-6">
				<select name='hsn_code' rows='5' class='form-control hsn_code select2' >
					{!!$hsn_code!!}
				</select>
			</div>
			<div class="col-md-2"></div>
		</div>
		
		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Default Hsn Code</label>
			<div class="col-md-6">
				<select name='default_hsn_code' rows='5' class='form-control default_hsn_code select2' id="default_hsn_code" >
					{!!$default_hsn_code!!}
				</select>
			</div>
			<div class="col-md-2"></div>
		</div>

		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Account Code</label>
			<div class="col-md-6">
				<select name='account_code_name' rows='5' class='form-control account_code_name select2' >
				{!!$account_code_name!!}
				</select>
			</div>
			<div class="col-md-2"></div>
		</div>

		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Control Account</label>
			<div class="col-md-6">
				<select name='control_account' rows='5' class='form-control control_account select2' >
				{!!$control_account!!}
				</select>
			</div>
			<div class="col-md-2"></div>
		</div>

		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Discount Account Code</label>
			<div class="col-md-6">
				<select name='disc_account_code_name' rows='5' class='form-control disc_account_code_name select2' >
				{!!$disc_account_code_name!!}
				</select>
			</div>
			<div class="col-md-2"></div>
		</div>

		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Subinventory</label>
			<div class="col-md-6">
				<select name='subinventory_name' rows='5' class='form-control subinventory_name select2' >
					{!!$subinventory_name !!}
				</select>
			</div>
			<div class="col-md-2 showline"></div>
		</div>

		



     <div class="form-group row stdiv">
              <label for="inputIsValid" class="form-control-label col-md-4">Locator Control</label>
              <div class="col-md-6">
                    <div class="l-radio section" style="display: flex;">
                   <div class="c-radio">
                       <input id='locator_control' name='locator_control' type='radio' value="YES" <?php echo ($productuploaddata['locator_control']=='YES')?'checked':'' ?>  />
                       <span class="check_mark"></span>
                       <label for="">YES</label>
                   </div>
                   <div class="c-radio">
                       <input id='locator_control' name='locator_control' type='radio' value="NO" <?php if(empty($productuploaddata['locator_control'])) echo 'checked'; echo ($productuploaddata['locator_control']=='NO')?'checked':'' ?>  />
                       <span class="check_mark"></span>
                       <label for="">NO</label>
                   </div>
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
			<div class="col-md-2 showline"></div>
		</div>
		
		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Min Stock Level1</label>
			<div class="col-md-6">
				<input type="text" id="min_stock_level1" name="min_stock_level1" class="form-control min_stock_level1" value="{{ $productuploaddata['min_stock_level1'] }}">
			</div>
			<div class="col-md-2"></div>
		</div>

		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Min Stock Level2</label>
			<div class="col-md-6">
				<input type="text" id="min_stock_level2" name="min_stock_level2" class="form-control min_stock_level2" value="{{ $productuploaddata['min_stock_level2'] }}">
			</div>
			<div class="col-md-2"></div>
		</div>

		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Min Stock Level3</label>
			<div class="col-md-6">
				<input type="text" id="min_stock_level3" name="min_stock_level3" class="form-control min_stock_level3" value="{{ $productuploaddata['min_stock_level3'] }}">
			</div>
			<div class="col-md-2"></div>
		</div>

		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Max Order Qty</label>
			<div class="col-md-6">
				<input type="text" id="max_order_qty" name="max_order_qty" class="form-control max_order_qty" value="{{ $productuploaddata['max_order_qty'] }}">
			</div>
			<div class="col-md-2"></div>
		</div>

		
	</div>

  	<div class="col-md-4">

  		
		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Re Order Qty</label>
			<div class="col-md-6">
				<input type="text" id="re_order_level" name="re_order_level" class="form-control re_order_level" value="{{ $productuploaddata['re_order_level'] }}">
			</div>
			<div class="col-md-2"></div>
		</div>

		<div class="form-group row stdiv" >
			<label for="inputIsValid" class="form-control-label col-md-4">Tax Credit</label>
			<div class="col-md-6">
				<select name="tax_credit" name="tax_credit" class=" form-control tax_credit select2" id="tax_credit" >
                    <option value="Yes" <?php if($productuploaddata['tax_credit']=='Yes'){ echo "selected";  }?> >Yes</option>
                    <option value="No" <?php if($productuploaddata['tax_credit']=='No'){ echo "selected";  }?>>No</option>
                </select>
			</div>
		</div>

		<div class="form-group row stdiv" >
			<label for="inputIsValid" class="form-control-label col-md-4">QC Type</label>
			<div class="col-md-6">
				<select name="qc_type" name="qc_type" class=" form-control qc_type select2" id="qc_type" >
					<option>--Please Select--</option>
                    <option value="BATCHWISE" <?php if($productuploaddata['qc_type']=='BATCHWISE'){ echo "selected";  }?> >BATCHWISE</option>
                    <option value="SERIALWISE" <?php if($productuploaddata['qc_type']=='SERIALWISE'){ echo "selected";  }?> >SERIALWISE</option>
                </select>
			</div>
		</div>

		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">MPQ Qty</label>
			<div class="col-md-6">
				<input type="text" id="mpq_qty" name="mpq_qty" class="form-control mpq_qty" value="{{ $productuploaddata['mpq_qty'] }}">
			</div>
			<div class="col-md-2"></div>
		</div>

		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Expiry Days</label>
			<div class="col-md-6">
				<input type="text" id="expiry_days" name="expiry_days" class="form-control expiry_days" value="{{ $productuploaddata['expiry_days'] }}">
			</div>
			<div class="col-md-2"></div>
		</div>
		
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Packing Cotton Box</label>
			<div class="col-md-6">
				
				<select name='packing_cotton_box' rows='5' class='form-control packing_cotton_box select2' >
					{!! $packing_cotton_box !!}
				</select>
			</div>
			<div class="col-md-2 showline"></div>
		</div>
			<div class="form-group row ">
			<label for="inputIsValid" class="form-control-label col-md-4">Quality Check </label>
			<div class="col-md-6">
				<select name="active" name="tax_credit" class=" form-control active select2" id="active" >
                    <option value="Yes" <?php  if($productuploaddata['qc_check']=='Yes'){ echo "selected";  }?> >Yes</option>
                    <option value="No" <?php if($productuploaddata['qc_check']=='No'){ echo "selected";  }?>>No</option>
                </select>
			</div>
			<div class="col-md-2 showline"></div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Company</label>
			<div class="col-md-6">
				<select name='company_code' rows='5' class='form-control company_code select2' >
					{!!$company_code!!}
				</select>
			</div>
			<div class="col-md-2 showline"></div>
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
			<div class="col-md-2 showline"></div>
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
				<textarea  id="batch_comments" readonly name="batch_comments" class="form-control batch_comments">{{ $productuploaddata['batch_comments'] }}</textarea>
				
			</div>
			<div class="col-md-2 showline"></div>
		</div>

	</div>
</div>	     
<br>
   
</div>
<div class="row">
	<div class="col-lg-12 col-md-12">
		<div class="form-group text-center">
			<button type="button" class="btn save">Submit</button>
			<a href="../productupload" class='btn cancel'  >Cancel</a>

		</div>
	</div>
</div>
	
</div>

	
	</form>

	

<script>
$(document).ready(function(){

	$(".subinventory_name").change(function(){
		var sub_name =$('.subinventory_name').select2('val');
		console.log(sub_name);
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
