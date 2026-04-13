@extends('layouts.header')
@section('content')
<style>
    .readdiv{
        pointer-events: none;
    }
</style>
<span class="ui_close_btn"></span>

<h2 class="text-danger">Primary Data Update
<span class="ui_close_btn"><a href="{{ url('primarydataupload') }}" class="collapse-close pull-right btn-danger" ></a></span>
</h2>

<form method="post" action="" id="primarydataupload" data-parsley-validate>

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
		        <label for="inputIsValid" class="form-control-label col-md-4">Data Type</label>
		        <div class="col-md-8">
					<input type="hidden" name="primarydata_upload_id" id="primarydata_upload_id" class="form-control primarydata_upload_id" value="{{ $row->primarydata_upload_id }}" readonly>
		            <input type="text" name="data_type" id="data_type" row="5" class="form-control data_type" value="{{ $row->data_type }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>

		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Emp ID</label>
		        <div class="col-md-8">
		            <input type="text" name="emp_id" id="emp_id" row="5" class="form-control emp_id" value="{{ $row->emp_id }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>

		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Stockist Dist Name</label>
		        <div class="col-md-8">
		            <input type="text" name="stockist_dist_name" id="stockist_dist_name" row="5" class="form-control stockist_dist_name" value="{{ $row->stockist_dist_name }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>

		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Invoice No.</label>
		        <div class="col-md-8">
		            <input type="text" name="invoice_no" id="invoice_no" row="5" class="form-control invoice_no" value="{{ $row->invoice_no }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>

		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Invoice Date</label>
		            <div class="form_date col-md-8" data-date="" data-date-format="yyyy MM dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <?php if($row->invoice_date=='0000-00-00') { ?>
                                <input class="form-control invoice_date datepicker" id="invoice_date" name="invoice_date" size="16" type="text" value="" tabindex="3">
                            <?php } else { ?>
                                 <input class="form-control invoice_date datepicker" id="invoice_date" name="invoice_date" size="16" type="text" value="{{$row->invoice_date}}" tabindex="3">
                          <?php } ?>
                                <!-- <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> -->
                            
                            
		        </div>
		    </div>

	 
	    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Sales Month</label>
		        <div class="col-md-8">
		            <input type="text" name="sales_month" id="sales_month" row="5" class="form-control sales_month" value="{{ $row->sales_month }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>

		 	<div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Name Type</label>
		        <div class="col-md-8">
		            <input type="text" name="name_type" id="name_type" row="5" class="form-control name_type" value="{{ $row->name_type }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
			
			<div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Active</label>
		        <div class="col-md-8">
		            <input type="text" name="active" id="active" row="5" class="form-control active" value="{{ $row->active }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div> 
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Current Manager</label>
		        <div class="col-md-8">
		            <input type="text" name="current_manager" id="current_manager" row="5" class="form-control current_manager" value="{{ $row->current_manager }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Cost Type</label>
		        <div class="col-md-8">
		            <input type="text" name="cost_type" id="cost_type" row="5" class="form-control cost_type" value="{{ $row->cost_type }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Area</label>
		        <div class="col-md-8">
		            <input type="text" name="area" id="area" row="5" class="form-control area" value="{{ $row->area }}" style="width: 100%;" tabindex="3"/>
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

		</div>

	<div class="col-md-4">
	    
	    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Till Month</label>
		        <div class="col-md-8">
		            <input type="text" name="till_month" id="till_month" row="5" class="form-control till_month" value="{{ $row->till_month }}" style="width: 100%;" tabindex="3"/>
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
		        <label for="inputIsValid" class="form-control-label col-md-4">SFG Product Name</label>
		        <div class="col-md-8">
		            <select type="text" name="sfg_product_name" id="sfg_product_name" class="form-control select2 sfg_product_name" required style="width: 100%;" tabindex="1">
		                {!! $sfg_product_name !!}
		            </select>
		        </div>
		    </div>
	    
	    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Packing Qty</label>
		        <div class="col-md-8">
		            <input type="text" name="packing_qty" id="packing_qty" row="5" class="form-control packing_qty" value="{{ $row->packing_qty }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		 <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Kit</label>
		        <div class="col-md-8">
		            <input type="text" name="kit" id="kit" row="5" class="form-control kit" value="{{ $row->kit }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>   
		    
		 <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Product Form Change</label>
		        <div class="col-md-8">
		            <input type="text" name="product_form_change" id="product_form_change" row="5" class="form-control product_form_change" value="{{ $row->product_form_change }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Product Category</label>
		        <div class="col-md-8">
		            <input type="text" name="product_category" id="product_category" row="5" class="form-control product_category" value="{{ $row->product_category }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Division</label>
		        <div class="col-md-8">
		            <input type="text" name="division" id="division" row="5" class="form-control division" value="{{ $row->division }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Weight</label>
		        <div class="col-md-8">
		            <input type="text" name="weight" id="weight" row="5" class="form-control weight" value="{{ $row->weight }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Rate</label>
		        <div class="col-md-8">
		            <input type="text" name="rate" id="rate" row="5" class="form-control rate" value="{{ $row->rate }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Unit Sold</label>
		        <div class="col-md-8">
		            <input type="text" name="unit_sold" id="unit_sold" row="5" class="form-control unit_sold" value="{{ $row->unit_sold }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Assessable Value</label>
		        <div class="col-md-8">
		            <input type="text" name="asseesable_value" id="asseesable_value" row="5" class="form-control asseesable_value" value="{{ $row->asseesable_value }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Discount</label>
		        <div class="col-md-8">
		            <input type="text" name="discount" id="discount" row="5" class="form-control discount" value="{{ $row->discount }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">IGST</label>
		        <div class="col-md-8">
		            <input type="text" name="igst" id="igst" row="5" class="form-control igst" value="{{ $row->igst }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">CGST</label>
		        <div class="col-md-8">
		            <input type="text" name="cgst" id="cgst" row="5" class="form-control cgst" value="{{ $row->cgst }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">SGST</label>
		        <div class="col-md-8">
		            <input type="text" name="sgst" id="sgst" row="5" class="form-control sgst" value="{{ $row->sgst }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Taxable Value</label>
		        <div class="col-md-8">
		            <input type="text" name="taxable_value" id="taxable_value" row="5" class="form-control taxable_value" value="{{ $row->taxable_value }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Med Cost Bf Bulk Dis</label>
		        <div class="col-md-8">
		            <input type="text" name="med_cost_bf_bulk_dis" id="med_cost_bf_bulk_dis" row="5" class="form-control med_cost_bf_bulk_dis" value="{{ $row->med_cost_bf_bulk_dis }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
	</div>

	<div class="col-md-4">
	  
		<div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Cash Amount</label>
		        <div class="col-md-8">
		            <input type="text" name="cash_amount" id="cash_amount" row="5" class="form-control cash_amount" value="{{ $row->cash_amount }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
	    
	    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Totla Invoice Amount</label>
		        <div class="col-md-8">
		            <input type="text" name="total_invoice_amount" id="total_invoice_amount" row="5" class="form-control total_invoice_amount" value="{{ $row->total_invoice_amount }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>

		<div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">App Man Power</label>
		        <div class="col-md-8">
		            <input type="text" name="app_man_power" id="app_man_power" row="5" class="form-control app_man_power" value="{{ $row->app_man_power }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>

		<div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">MRP</label>
		        <div class="col-md-8">
		            <input type="text" name="mrp" id="mrp" row="5" class="form-control mrp" value="{{ $row->mrp }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>

		<div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">MPQ</label>
		        <div class="col-md-8">
		            <input type="text" name="MPQ" id="MPQ" row="5" class="form-control MPQ" value="{{ $row->MPQ }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Free Unit</label>
		        <div class="col-md-8">
		            <input type="text" name="free_unit" id="free_unit" row="5" class="form-control free_unit" value="{{ $row->free_unit }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Package Weight</label>
		        <div class="col-md-8">
		            <input type="text" name="package_weight" id="package_weight" row="5" class="form-control package_weight" value="{{ $row->package_weight }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Batch Number</label>
		        <div class="col-md-8">
		            <input type="text" name="batch_number" id="batch_number" row="5" class="form-control batch_number" value="{{ $row->batch_number }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Last 6 Month</label>
		        <div class="col-md-8">
		            <input type="text" name="last_6montth" id="last_6montth" row="5" class="form-control last_6montth" value="{{ $row->last_6montth }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Working Days</label>
		            <div class="form_date col-md-8" data-date="" data-date-format="yyyy MM dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <?php if($row->working_days=='0000-00-00') { ?>
                                <input class="form-control working_days datepicker" id="working_days" name="working_days" size="16" type="text" value="" tabindex="3">
                            <?php } else { ?>
                                 <input class="form-control working_days datepicker" id="working_days" name="working_days" size="16" type="text" value="{{$row->working_days}}" tabindex="3">
                          <?php } ?>
                                <!-- <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> -->
                            
                            
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Status</label>
		        <div class="col-md-8">
		            <input type="text" name="status" id="status" row="5" class="form-control status" value="{{ $row->status }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">State Consolidated</label>
		        <div class="col-md-8">
		            <input type="text" name="state_consolidated" id="state_consolidated" row="5" class="form-control state_consolidated" value="{{ $row->state_consolidated }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">HQ</label>
		        <div class="col-md-8">
		            <input type="text" name="hq" id="hq" row="5" class="form-control hq" value="{{ $row->hq }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Sales Free Unit</label>
		        <div class="col-md-8">
		            <input type="text" name="sales_free_unit" id="sales_free_unit" row="5" class="form-control sales_free_unit" value="{{ $row->sales_free_unit }}" style="width: 100%;" tabindex="3"/>
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
        	  <a href="{{ url('primarydataupload') }}" class='btn btn-danger'>Cancel</a>
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
        var url		= "{{ url('primarydatauploadsave') }}";
        var red_url		="{{ url('primarydataupload') }}";
        validationrule('primarydataupload');
        var formdata	= $('#primarydataupload').serialize();
        var form = $('#primarydataupload');
        form.parsley().validate();
        var form = $('#primarydataupload');
        form.parsley().validate();

        if (form.parsley().isValid())
        {
            $.post(url,formdata,function(data)
            {
                var status      = data.status;
                var msg     = '<span style="color:#090065"> Primary Data</span>  '+data.message;
                
                notyMsg(status,msg);
                setTimeout(function(){
                    window.location.href=red_url;
                }, 1500);
                
            });
        }
      
    });

     
});


var dateToday = new Date();
  var data ="{{\Session::get('j_date_format')}}";
    $( "#invoice_date" ).datepicker({
      changeMonth: true,
      dateFormat: data,
      changeYear: true,
      minDate: 0,
			maxDate: 0,
      //maxDate: null,
      onClose: function () {
        $(this).parsley().validate();
        }

    }).attr('readonly', 'readonly');
</script> 

<script>
var dateToday = new Date();
  var data ="{{\Session::get('j_date_format')}}";
    $( "#working_days" ).datepicker({
      changeMonth: true,
      dateFormat: data,
      changeYear: true,
      minDate: 0,
			maxDate: 0,
      //maxDate: null,
      onClose: function () {
        $(this).parsley().validate();
        }

    }).attr('readonly', 'readonly');
</script>

@endpush
