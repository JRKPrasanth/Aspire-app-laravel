@extends('layouts.header')
@section('content')
<style>
    .readdiv{
        pointer-events: none;
    }
</style>
<span class="ui_close_btn"></span>

<h2 class="text-danger">Primary and Secondary Data Update
<span class="ui_close_btn"><a href="{{ url('prmyscdyupload') }}" class="collapse-close pull-right btn-danger" ></a></span>
</h2>

<form method="post" action="" id="prmyscdyupload" data-parsley-validate>

{{ csrf_field() }}
<div class="card">
<div class="card-body card-block headerdiv1">



<!--****************- Body content start here **************-->
	<div class="row">
	<div class="col-md-12">
	<div>
	<div class="row">

		<div class="col-md-4">
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Type of Data</label>
		        <div class="col-md-8">
					<input type="hidden" name="prmyscdy_upload_id" id="prmyscdy_upload_id" class="form-control prmyscdy_upload_id" value="{{ $row->prmyscdy_upload_id }}" readonly>
		            <input type="text" name="type_of_data" id="type_of_data" row="5" class="form-control type_of_data" value="{{ $row->type_of_data }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>

		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Current Reporting MGR</label>
		        <div class="col-md-8">
		            <input type="text" name="currrent_reporting_MGR" id="currrent_reporting_MGR" row="5" class="form-control currrent_reporting_MGR" value="{{ $row->currrent_reporting_MGR }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>

		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">State</label>
		        <div class="col-md-8">
		            <input type="text" name="state" id="state" row="5" class="form-control state" value="{{ $row->state }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>

		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Region</label>
		        <div class="col-md-8">
		            <input type="text" name="region" id="region" row="5" class="form-control region" value="{{ $row->region }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>

		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Zone</label>
		        <div class="col-md-8">
		            <input type="text" name="zone" id="zone" row="5" class="form-control zone" value="{{ $row->zone }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>

	 
	    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">C Year</label>
		        <div class="col-md-8">
		            <input type="text" name="c_year" id="c_year" row="5" class="form-control c_year" value="{{ $row->c_year }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>

		 	<div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">F Year</label>
		        <div class="col-md-8">
		            <input type="text" name="f_year" id="f_year" row="5" class="form-control f_year" value="{{ $row->f_year }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
			
			<div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Month</label>
		        <div class="col-md-8">
		            <input type="text" name="month" id="month" row="5" class="form-control month" value="{{ $row->month }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div> 
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Month Year</label>
		        <div class="col-md-8">
		            <input type="text" name="month_y" id="month_y" row="5" class="form-control month_y" value="{{ $row->month_y }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Product Pack Name</label>
		        <div class="col-md-8">
		            <select type="text" name="product_pack_name" id="product_pack_name" class="form-control select2 product_pack_name" required style="width: 100%;" tabindex="1">
		                {!! $product_pack_name !!}
		            </select>
		        </div>
		    </div>

	
	    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Product Name</label>
		        <div class="col-md-8">
		            <select type="text" name="product_name" id="product_name" class="form-control select2 product_name" required style="width: 100%;" tabindex="1">
		                {!! $product_name !!}
		            </select>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Pack Size</label>
		        <div class="col-md-8">
		            <input type="text" name="pack_size" id="pack_size" row="5" class="form-control pack_size" value="{{ $row->pack_size }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Product Kit</label>
		        <div class="col-md-8">
		            <input type="text" name="product_kit" id="product_kit" row="5" class="form-control product_kit" value="{{ $row->product_kit }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Division</label>
		        <div class="col-md-8">
		            <input type="text" name="division" id="division" row="5" class="form-control division" value="{{ $row->division }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    

		</div>

	<div class="col-md-4">
	   	
	    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">HQ Name</label>
		        <div class="col-md-8">
		            <input type="text" name="HQ_name" id="HQ_name" row="5" class="form-control HQ_name" value="{{ $row->HQ_name }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Area</label>
		        <div class="col-md-8">
		            <input type="text" name="area" id="area" row="5" class="form-control area" value="{{ $row->area }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
	    
	    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Type D S</label>
		        <div class="col-md-8">
		            <input type="text" name="type_d_s" id="type_d_s" row="5" class="form-control type_d_s" value="{{ $row->type_d_s }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		 <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Stockist Dist Name</label>
		        <div class="col-md-8">
		            <input type="text" name="stockist_dist_name" id="stockist_dist_name" row="5" class="form-control stockist_dist_name" value="{{ $row->stockist_dist_name }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Sales KGS LTS</label>
		        <div class="col-md-8">
		            <input type="text" name="sales_kgs_lts" id="sales_kgs_lts" row="5" class="form-control sales_kgs_lts" value="{{ $row->sales_kgs_lts }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Active/Inactive</label>
		        <div class="col-md-8">
		            <input type="text" name="active_inactive" id="active_inactive" row="5" class="form-control active_inactive" value="{{ $row->active_inactive }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Employee Name</label>
		        <div class="col-md-8">
		            <input type="text" name="employee_name" id="employee_name" row="5" class="form-control employee_name" value="{{ $row->employee_name }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Product Category</label>
		        <div class="col-md-8">
		            <input type="text" name="product_category" id="product_category" row="5" class="form-control product_category" value="{{ $row->product_category }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Type</label>
		        <div class="col-md-8">
		            <input type="text" name="type" id="type" row="5" class="form-control type" value="{{ $row->type }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Purchase Stock</label>
		        <div class="col-md-8">
		            <input type="text" name="purchase_stock" id="purchase_stock" row="5" class="form-control purchase_stock" value="{{ $row->purchase_stock }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Purchase Stock Value</label>
		        <div class="col-md-8">
		            <input type="text" name="purchase_stock_value" id="purchase_stock_value" row="5" class="form-control purchase_stock_value" value="{{ $row->purchase_stock_value }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Sales Unit</label>
		        <div class="col-md-8">
		            <input type="text" name="sales_unit" id="sales_unit" row="5" class="form-control sales_unit" value="{{ $row->sales_unit }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Sales Value</label>
		        <div class="col-md-8">
		            <input type="text" name="sales_value" id="sales_value" row="5" class="form-control sales_value" value="{{ $row->sales_value }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Closing Stock</label>
		        <div class="col-md-8">
		            <input type="text" name="closing_stock" id="closing_stock" row="5" class="form-control closing_stock" value="{{ $row->closing_stock }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    
	    
	 	
	</div>

	<div class="col-md-4">
	  
		<div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Closing Stock Value</label>
		        <div class="col-md-8">
		            <input type="text" name="closing_stock_value" id="closing_stock_value" row="5" class="form-control closing_stock_value" value="{{ $row->closing_stock_value }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
	    
	    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Free Stock</label>
		        <div class="col-md-8">
		            <input type="text" name="free_stock" id="free_stock" row="5" class="form-control free_stock" value="{{ $row->free_stock }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>

		<div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Free Stock Value</label>
		        <div class="col-md-8">
		            <input type="text" name="free_stock_value" id="free_stock_value" row="5" class="form-control free_stock_value" value="{{ $row->free_stock_value }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>

		<div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Sales Free Units</label>
		        <div class="col-md-8">
		            <input type="text" name="sales_free_units" id="sales_free_units" row="5" class="form-control sales_free_units" value="{{ $row->sales_free_units }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>

		<div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Rate</label>
		        <div class="col-md-8">
		            <input type="text" name="rate" id="rate" row="5" class="form-control rate" value="{{ $row->rate }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Concatenate</label>
		        <div class="col-md-8">
		            <input type="text" name="concatenate" id="concatenate" row="5" class="form-control concatenate" value="{{ $row->concatenate }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Extra Offer</label>
		        <div class="col-md-8">
		            <input type="text" name="extra_offer" id="extra_offer" row="5" class="form-control extra_offer" value="{{ $row->extra_offer }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Camp Product</label>
		        <div class="col-md-8">
		            <input type="text" name="camp_product" id="camp_product" row="5" class="form-control camp_product" value="{{ $row->camp_product }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Note</label>
		        <div class="col-md-8">
		            <input type="text" name="note" id="note" row="5" class="form-control note" value="{{ $row->note }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Free Value Classical Sale Value</label>
		        <div class="col-md-8">
		            <input type="text" name="free_value_classical_sale_value" id="free_value_classical_sale_value" row="5" class="form-control free_value_classical_sale_value" value="{{ $row->free_value_classical_sale_value }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row readdiv">
		        <label for="inputIsValid" class="form-control-label col-md-4">Batch Name</label>
		        <div class="col-md-8">
		            <input type="text" name="batch_name" id="batch_name" row="5" class="form-control batch_name" value="{{ $row->batch_name }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row readdiv">
		        <label for="inputIsValid" class="form-control-label col-md-4">Batch Date</label>
		        <div class="col-md-8">
		            <input type="text" name="batch_date" id="batch_date" row="5" class="form-control batch_date" value="{{ $row->batch_date }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row readdiv">
		        <label for="inputIsValid" class="form-control-label col-md-4">Batch Status</label>
		        <div class="col-md-8">
		            <input type="text" name="batch_status" id="batch_status" row="5" class="form-control batch_status" value="{{ $row->batch_status }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    

	</div>
					
	</div>
	</div>
	</div>
	</div>
	<!--**************************-->

<div class="row">
	<div class="col-lg-12 col-md-12">
		<div class="form-group text-center">
			    
			<button type="button" class="btn btn-success saveform" value="SAVE">Update</button>
        	  <a href="{{ url('prmyscdyupload') }}" class='btn btn-danger'>Cancel</a>
		</div>
	</div>
</div>




</div>

</div>
	
</form>

@endsection
@push('scripts')

<script>

$(document).ready(function()
{
	
	/*deepika purpose:qty validation*/
		$(document).on('keypress','.per_based,.qty_based', function(ev){
			var regex = new RegExp("^[0-9.]+$");
			var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
			if (regex.test(str)) {
				return true;
			}
			ev.preventDefault();
			return false;
		});
	/*end*/
	$(document).on('change','.bom_product',function(){
		var prd = $('.bom_product').val();
		var url1 = "{{ URL::to('getprdtypeid') }}/"+prd;
		$.get(url1 , function(data){
			var data = $.trim(data);
			$('.bom_uom_code').val(data).trigger('change');;
		});
	});
	/*deepika purpose:to get component product*/
	$(document).on('change','.component_product',function(){
		var prd = $('.component_product').val();
		var url1 = "{{ URL::to('getprdtypeid') }}/"+prd;
		$.get(url1 , function(data){
			var data = $.trim(data);
			$('.component_uom_code').val(data).trigger('change');;
		});
	});
	/*deepika purpose:to get component qty*/
  $('.component_qty').change(function(){
	var qtybas=$(this).val();
	if(qtybas=='Percentage'){
	   $('.qty_based').attr('readonly',true);
	   $('.per_based').attr('required',true);
	   $('.per_based').attr('readonly',false);
		$('.per').show();
		$('.val').hide();
	   }else{
		 $('.qty_based').attr('readonly',false);
	   $('.per_based').attr('readonly',true);   
	   $('.qty_based').attr('required',true);   
		   $('.val').show();
		   $('.per').hide();
	   }
  });
$('.component_qty').trigger('change');
    $(document).on('click','.saveform',function()
    {
        var url		= "{{ url('prmyscdyuploadsave') }}";
        var red_url		="{{ url('prmyscdyupload') }}";
        validationrule('prmyscdyupload');
        var formdata	= $('#prmyscdyupload').serialize();
        var form = $('#prmyscdyupload');
        form.parsley().validate();
        var form = $('#prmyscdyupload');
        form.parsley().validate();

        if (form.parsley().isValid())
        {
            $.post(url,formdata,function(data)
            {
                var status      = data.status;
                var msg     = '<span style="color:#090065"> Primary and Secondary Data</span>  '+data.message;
                
                notyMsg(status,msg);
                setTimeout(function(){
                    window.location.href=red_url;
                }, 1500);
                
            });
        }
      
    });

     
});



</script>

@endpush
