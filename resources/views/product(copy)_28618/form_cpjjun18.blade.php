@extends('layouts.header')
@section('content')


<div class="container">

	<!------------------------- breadcrumbs start here --------------------------->
<div class="row">
<div class="col-lg-12 col-md-12">
<!--div class='steps'>
<div class='step complete'>Dashboard</div>
<div class='step active'>Sales</div>
<div class='step'>Sales-Create</div>
<div class='step'>Step 4</div>
</div-->
</div>
</div>
	<!---------------------------------------------------------------------------->

<div class="row">
<div class="col-lg-1">
</div>
	<form action="" method="post" id="productform" data-parsley-validate>
	<input type="hidden" value="" name="savestatus" id="savestatus" />
{{ csrf_field() }}
<div class="col-lg-12">
<div class="card">
<div class="card-header">
<strong>Product</strong>
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
	  	
	     <div class="fdivold">
		<div class="form-group row fdiv">
			<label for="inputIsValid" class="form-control-label col-md-5">Product code</label>
			<div class="col-md-6">
			<input class="form-control product_id" id="product_id" name="product_id" size="16" type="hidden" value="{{ $product_id }}" readonly>
			<input type="text" id="product_code" name="product_code" class="form-control product_code" value="{{$productdata['product_code'] }}">
			</div>
			<div class="col-md-1">
			</div>
		</div>
		
		<div class="form-group row ">
			<label for="inputIsValid" class="form-control-label col-md-4"><span style="color: red;  ">*</span>Product Color </label>
			<div class="col-md-6">
			<select name='product_color_id' rows='5' class='form-control product_color_id select2' data-show-subtext="true" data-live-search="true" required>
				{!!$product_color_id !!}
			</select>
			</div>
			<div class="col-md-2 showline">
			<span class="showspan"> <i class="fa fa-refresh jcr_product_color_id"></i></span>
			</div>
		</div>
		
		<div class="form-group row ">
			<label for="inputIsValid" class="form-control-label col-md-4"><span style="color: red;  ">*</span>Product Pack Type </label>
			<div class="col-md-6">
			<select name='product_packtype_id' rows='5' class='form-control product_packtype_id select2' data-show-subtext="true" data-live-search="true" required>
			{!!$product_packtype_id !!}
			</select>
			</div>
			<div class="col-md-2 showline">
			<span class="showspan"> <i class="fa fa-refresh jcr_product_packtype_id"></i></span>
			</div>
		</div>
		
		<div class="form-group row fdiv">
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
			<label for="inputIsValid" class="form-control-label col-md-5">Concatenated product</label>
			<div class="col-md-6">

			<textarea style=" width: 140px; height: 78px;" id="concatenated_product" name="concatenated_product" data-value="0" class="form-control concatenated_product">{{ $productdata['concatenated_product'] }}</textarea>
			</div>
			<div class="col-md-1">
			</div>
		</div>
	  </div>
  	</div>
	    
	    <div class="stdivold">
		  <div class="col-md-4" >
			<div class="form-group row stdiv">
				<label for="inputIsValid" class="form-control-label col-md-5">Product alternate name</label>
				<div class="col-md-6">
				  <input type="text" id="product_alternate_name" name="product_alternate_name" class="form-control product_alternate_name" value="{{ $productdata['product_alternate_name'] }}">
				</div>
				<div class="col-md-1">
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

			<div class="form-group row stdiv">
				<label for="inputIsValid" class="form-control-label col-md-4">Locator control</label>
				<div class="col-md-6">

				<div class="section">
				<input id='watch_me' name='overdue' type='radio' value="1" checked="checked" /> Yes
					<input id='watch_me' name='overdue' type='radio' value="2" /> No
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

			<div class="form-group row stdiv">
				<label for="inputIsValid" class="form-control-label col-md-4">serial control</label>
				<div class="col-md-6">
				<div class="section">
				<input id='watch_now' name='overdue' type='radio' value="Yes" checked="checked" /> Yes
				<input id='watch_now' name='overdue' type='radio' value="No" /> No
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
					   <div class="table-responsive subgrid_div">
						   <table class="table table-striped product_tbl table_scroll">
							   <thead style="display: block;overflow: auto;">
								<tr>
								
								<th><p>Line No</p></th>
								<th><p>Product Code</p> </th>
								<th><p>Product Pack </p></th>
								<th><p>Concat Product</p> </th>
								<th><p>HSN Code</p> </th>
								<th><p>Primary Uom</p></th>
								<th><p>Trx Uom</p></th>
								<th><p>Subinventory</p></th>
								<th><p>Locator</p></th>
								<th style="width:30px"><p style="width:30px">&nbsp;</p></th>
								</tr>
								</thead>
							   <tbody class="product_tbl_lines_body">
								   
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
								   <td><a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
                                       <input type="hidden" name="counter[]">
                                   </td>
								 </tr>   
							   </tbody>	   
						   </table>
					   </div>
				   </div>
	           </div>
	    <!--end -->
	
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

	<div class="col-lg-1">
	</div>
</div>
@extends('layouts.footer')
</div>

	<script>
	$(document).ready(function(){
  
		  $('.linesdiv').hide();
		
		  $(".select2").select2({width:"100%"});
		$('.concatenated_product').attr('readonly',true);
		

	$(".jcr_product_group_id").click(function(){
		$(".product_group_id").jCombo("{{ URL::to('jcomboform?table=m_product_groups_t:product_group_id:group_name')}}",
			{selected_value:""});
	});

	$(".jcr_product_category_id").click(function(){
		$(".product_category_id").jCombo("{{ URL::to('jcomboform?table=m_product_category_t:product_category_id:category_name')}}",
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


	// $(".jcr_organization_id").click(function(){
	// 	$(".organization_id").jCombo("{{ URL::to('jcomboform?table=m_organizations_t:organization_id:organization_name')}}",
	// 		{selected_value:""});
	// });

     /* raja code for concat product create*/
		$(document).on('change','.product_color_id,.product_pack_id,.product_packtype_id',function(){
			 var index = $('.product_pack_id').closest('tr').index();
			
			var product_color_id =$('.product_color_id option:selected').text();
			var product_pack_id =$('.product_pack_id option:selected').text();
			var product_packtype_id =$('.product_packtype_id option:selected').text();			
			var concatenated_productdata =(product_color_id+'-'+product_packtype_id+'-'+product_pack_id);
			
			 var header= $('.concatenated_product').data('value');
			 var lines= $('.bulk_concatenated_product').data('value');
			
			if(header=='1')
			{
		     $('.concatenated_product').val(concatenated_productdata);
		     $('.bulk_concatenated_product').val("");
			}
			else
			{
				 $('.concatenated_product').val("");
		         $('.bulk_concatenated_product').val(concatenated_productdata);
			}
		});
		
		
		
		
		/* end */
		
		/*raja code for rawmaterial and product group based div display*/
		  $(document).on('change','.product_category_id,.product_group_id',function(){
		     var product_category_id = $('.product_category_id option:selected').text();
		     var product_group_id = $('.product_group_id option:selected').text();
			  if(product_category_id=="FINISHED" && product_group_id=="FINISHED GOODS")
			  {
				 $('.concatenated_product').attr('data-value','0');
				 $('.bulk_concatenated_product').attr('data-value','1');
				  
				 //$('.sublocator_id').prop('required',false);
				  
			   $(".fdiv,.stdiv").hide();
			   $('.linesdiv').show();
		      
			  }  
			  else
			  {
			    $(".fdiv,.stdiv").show();
				   $('.linesdiv').css("display", "none");
				  $('.product_color_id,.product_packtype_id,.bulk_product_pack_id,.bulk_sublocator_id').prop('required',false);
				  $('.concatenated_product').attr('data-value','1');
				 $('.bulk_concatenated_product').attr('data-value','0');
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

			alert(btnval);
			
			$('#savestatus').val(savestatus);
			 $('.submit_type').val("save");

			 var url		= "{{ URL::to('productsave') }}";
			 validationrule('productform');
			 var formdata	= $('#productform').serialize();
			 var form = $('#productform');
		     var red_url = "{{ URL::to('product') }}";

			if(btnval != 'APPLYCHANGES')
              { alert("in");
			    form.parsley().validate();
		          var form = $('#productform');
		          form.parsley().validate();

				   if(form.parsley().isValid())
                   { alert("ff");
					   $.post(url,formdata,function(data)
					   { alert(url);
					        var status      = data.status;
						    var msg         = data.message;
						    var id          = data.id;
						    var edit_url	= "{{ URL::to('productedit') }}/"+id;
                             alert(btnval+"1");
						   if(btnval !='SAVE')
                              { 
                                notyMsg(status,msg);
                                setTimeout(function(){
                                window.location.href=edit_url;
                                }, 1500);
                              }
						       else
                               { alert("2");
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



	$(document).on('click','#watch_me',function()
   {
		var id=$(this).val();

		if(id==1)
		{
			$('.sublocate').show();
			$('.sublocator_id').attr('required',true);
		}
		else if(id==2)
		{
			$('.sublocate').hide();
			$('.sublocator_id').attr('required',false);

		}
	});


	$(document).on('click','#watch_now',function()
   {
		var id=$(this).val();

		if(id=='Yes')
		{
			$('.serpre').show();
			$('#watch_now').attr('required',true);
		}
		else if(id=='No')
		{
			$('.serpre').hide();
			$('#watch_now').attr('required',false);
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
changeClassName('bulk_concatenated_product');
changeClassName('bulk_hsncode');
changeClassName('bulk_primaryuom');
changeClassName('bulk_trxuom');
changeClassName('bulk_subinventory');
changeClassName('bulk_locator');
}
function removeclassfields()
{
removeClass('bulk_product_id');
removeClass('bulk_line_no');
removeClass('bulk_product_code');
removeClass('bulk_product_pack');
removeClass('bulk_concatenated_product');
removeClass('bulk_hsncode');
removeClass('bulk_primaryuom');
removeClass('bulk_trxuom');
removeClass('bulk_subinventory');
removeClass('bulk_locator');
}




	});




	</script>
@include('layouts.php_js_validation')
@endsection
