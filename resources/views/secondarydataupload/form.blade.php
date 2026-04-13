@extends('layouts.header')
@section('content')
<style>
    .readdiv{
        pointer-events: none;
    }
</style>
<span class="ui_close_btn"></span>

<h2 class="text-danger">Secondary Data Update
<span class="ui_close_btn"><a href="{{ url('secondarydataupload') }}" class="collapse-close pull-right btn-danger" ></a></span>
</h2>

<form method="post" action="" id="secondarydataupload" data-parsley-validate>

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
					<input type="hidden" name="secondarydate_upload_id" id="secondarydate_upload_id" class="form-control secondarydate_upload_id" value="{{ $row->secondarydate_upload_id }}" readonly>
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
		        <label for="inputIsValid" class="form-control-label col-md-4">Field Force Name</label>
		        <div class="col-md-8">
		            <input type="text" name="field_force_name" id="field_force_name" row="5" class="form-control field_force_name" value="{{ $row->field_force_name }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>

		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Position</label>
		        <div class="col-md-8">
		            <input type="text" name="position" id="position" row="5" class="form-control position" value="{{ $row->position }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>

		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Emp With Level</label>
		        <div class="col-md-8">
		            <input type="text" name="emp_with_level" id="emp_with_level" row="5" class="form-control emp_with_level" value="{{ $row->emp_with_level }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>

	 
	    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">FF Status</label>
		        <div class="col-md-8">
		            <input type="text" name="ff_status" id="ff_status" row="5" class="form-control ff_status" value="{{ $row->ff_status }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>

		 	<div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">HQ Name</label>
		        <div class="col-md-8">
		            <input type="text" name="hq_name" id="hq_name" row="5" class="form-control hq_name" value="{{ $row->hq_name }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
			
			<div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Current Reporting Manager</label>
		        <div class="col-md-8">
		            <input type="text" name="current_reporting_manager" id="current_reporting_manager" row="5" class="form-control current_reporting_manager" value="{{ $row->current_reporting_manager }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div> 
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Stockist Dist Name</label>
		        <div class="col-md-8">
		            <input type="text" name="stockist_dist_name" id="stockist_dist_name" row="5" class="form-control stockist_dist_name" value="{{ $row->stockist_dist_name }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Stockist Code</label>
		        <div class="col-md-8">
		            <input type="text" name="stockist_code" id="stockist_code" row="5" class="form-control stockist_code" value="{{ $row->stockist_code }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Type D S</label>
		        <div class="col-md-8">
		            <input type="text" name="type_d_s" id="type_d_s" row="5" class="form-control type_d_s" value="{{ $row->type_d_s }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Type Sales Target</label>
		        <div class="col-md-8">
		            <input type="text" name="type_sales_target" id="type_sales_target" row="5" class="form-control type_sales_target" value="{{ $row->type_sales_target }}" style="width: 100%;" tabindex="3"/>
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
		        <label for="inputIsValid" class="form-control-label col-md-4">Month Y</label>
		        <div class="col-md-8">
		            <input type="text" name="month_y" id="month_y" row="5" class="form-control month_y" value="{{ $row->month_y }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Till Month</label>
		        <div class="col-md-8">
		            <input type="text" name="till_month" id="till_month" row="5" class="form-control till_month" value="{{ $row->till_month }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Kit</label>
		        <div class="col-md-8">
		            <input type="text" name="kit" id="kit" row="5" class="form-control kit" value="{{ $row->kit }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>

		</div>

	<div class="col-md-4">
	   	
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
		        <label for="inputIsValid" class="form-control-label col-md-4">Product Category</label>
		        <div class="col-md-8">
		            <input type="text" name="product_category" id="product_category" row="5" class="form-control product_category" value="{{ $row->product_category }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		 <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Product Form Change</label>
		        <div class="col-md-8">
		            <input type="text" name="product_form_change" id="product_form_change" row="5" class="form-control product_form_change" value="{{ $row->product_form_change }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Product Division</label>
		        <div class="col-md-8">
		            <input type="text" name="product_division" id="product_division" row="5" class="form-control product_division" value="{{ $row->product_division }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">HQ Division</label>
		        <div class="col-md-8">
		            <input type="text" name="hq_division" id="hq_division" row="5" class="form-control hq_division" value="{{ $row->hq_division }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Division</label>
		        <div class="col-md-8">
		            <input type="text" name="division" id="division" row="5" class="form-control division" value="{{ $row->division }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Branch Name</label>
		        <div class="col-md-8">
		            <input type="text" name="branc_name" id="branc_name" row="5" class="form-control branc_name" value="{{ $row->branc_name }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Product Code</label>
		        <div class="col-md-8">
		            <input type="text" name="product_code" id="product_code" row="5" class="form-control product_code" value="{{ $row->product_code }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Package</label>
		        <div class="col-md-8">
		            <input type="text" name="package" id="package" row="5" class="form-control package" value="{{ $row->package }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Sales KGS LTS</label>
		        <div class="col-md-8">
		            <input type="text" name="sales_kgs_lts" id="sales_kgs_lts" row="5" class="form-control sales_kgs_lts" value="{{ $row->sales_kgs_lts }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Price Per Unit</label>
		        <div class="col-md-8">
		            <input type="text" name="price_per_unit" id="price_per_unit" row="5" class="form-control price_per_unit" value="{{ $row->price_per_unit }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Opening Stock</label>
		        <div class="col-md-8">
		            <input type="text" name="opening_stock" id="opening_stock" row="5" class="form-control opening_stock" value="{{ $row->opening_stock }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Opening Stock Value</label>
		        <div class="col-md-8">
		            <input type="text" name="opening_stock_value" id="opening_stock_value" row="5" class="form-control opening_stock_value" value="{{ $row->opening_stock_value }}" style="width: 100%;" tabindex="3"/>
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
		        <label for="inputIsValid" class="form-control-label col-md-4">Sales Return Stock</label>
		        <div class="col-md-8">
		            <input type="text" name="sales_return_stock" id="sales_return_stock" row="5" class="form-control sales_return_stock" value="{{ $row->sales_return_stock }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Sales Return Stock Value</label>
		        <div class="col-md-8">
		            <input type="text" name="sales_return_stock_value" id="sales_return_stock_value" row="5" class="form-control sales_return_stock_value" value="{{ $row->sales_return_stock_value }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Closing Stock</label>
		        <div class="col-md-8">
		            <input type="text" name="closing_stock" id="closing_stock" row="5" class="form-control closing_stock" value="{{ $row->closing_stock }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Closing Stock Value</label>
		        <div class="col-md-8">
		            <input type="text" name="closing_stock_value" id="closing_stock_value" row="5" class="form-control closing_stock_value" value="{{ $row->closing_stock_value }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
	    
	 	
	</div>

	<div class="col-md-4">
	  
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
		        <label for="inputIsValid" class="form-control-label col-md-4">Purchase Return Stock</label>
		        <div class="col-md-8">
		            <input type="text" name="purchase_return_stock" id="purchase_return_stock" row="5" class="form-control purchase_return_stock" value="{{ $row->purchase_return_stock }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>

		<div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Purchase Return Stock Value</label>
		        <div class="col-md-8">
		            <input type="text" name="purchse_retrun_stock_value" id="purchse_retrun_stock_value" row="5" class="form-control purchse_retrun_stock_value" value="{{ $row->purchse_retrun_stock_value }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>

		<div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Transit Stock</label>
		        <div class="col-md-8">
		            <input type="text" name="transit_stock" id="transit_stock" row="5" class="form-control transit_stock" value="{{ $row->transit_stock }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Transit Stock Value</label>
		        <div class="col-md-8">
		            <input type="text" name="transit_stock_valu" id="transit_stock_valu" row="5" class="form-control transit_stock_valu" value="{{ $row->transit_stock_valu }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Expired Stock</label>
		        <div class="col-md-8">
		            <input type="text" name="expired_stock" id="expired_stock" row="5" class="form-control expired_stock" value="{{ $row->expired_stock }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Expired Stock Value</label>
		        <div class="col-md-8">
		            <input type="text" name="expired_stock_value" id="expired_stock_value" row="5" class="form-control expired_stock_value" value="{{ $row->expired_stock_value }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Damaged Stock</label>
		        <div class="col-md-8">
		            <input type="text" name="damaged_stock" id="damaged_stock" row="5" class="form-control damaged_stock" value="{{ $row->damaged_stock }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Damaged Stock Value</label>
		        <div class="col-md-8">
		            <input type="text" name="damaged_stock_value" id="damaged_stock_value" row="5" class="form-control damaged_stock_value" value="{{ $row->damaged_stock_value }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Status</label>
		        <div class="col-md-8">
		            <input type="text" name="status" id="status" row="5" class="form-control status" value="{{ $row->status }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Stockist Peroid Validity</label>
		        <div class="col-md-8">
		            <input type="text" name="stockist_peroid_validity" id="stockist_peroid_validity" row="5" class="form-control stockist_peroid_validity" value="{{ $row->stockist_peroid_validity }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Approved Date</label>
		            <div class="form_date col-md-8" data-date="" data-date-format="yyyy MM dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <?php if($row->approved_date=='0000-00-00') { ?>
                                <input class="form-control approved_date datepicker" id="approved_date" name="approved_date" size="16" type="text" value="" tabindex="3">
                            <?php } else { ?>
                                 <input class="form-control approved_date datepicker" id="approved_date" name="approved_date" size="16" type="text" value="{{$row->approved_date}}" tabindex="3">
                          <?php } ?>
                                <!-- <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> -->
                            
                            
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">User Name</label>
		        <div class="col-md-8">
		            <input type="text" name="user_name" id="user_name" row="5" class="form-control user_name" value="{{ $row->user_name }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Date of Upon</label>
		            <div class="form_date col-md-8" data-date="" data-date-format="yyyy MM dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <?php if($row->date_of_upon=='0000-00-00') { ?>
                                <input class="form-control date_of_upon datepicker" id="date_of_upon" name="date_of_upon" size="16" type="text" value="" tabindex="3">
                            <?php } else { ?>
                                 <input class="form-control date_of_upon datepicker" id="date_of_upon" name="date_of_upon" size="16" type="text" value="{{$row->date_of_upon}}" tabindex="3">
                          <?php } ?>
                                <!-- <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> -->
                            
                            
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">HQ Old Name</label>
		        <div class="col-md-8">
		            <input type="text" name="hq_old_name" id="hq_old_name" row="5" class="form-control hq_old_name" value="{{ $row->hq_old_name }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Cur Mgr Status</label>
		        <div class="col-md-8">
		            <input type="text" name="cur_mgr_status" id="cur_mgr_status" row="5" class="form-control cur_mgr_status" value="{{ $row->cur_mgr_status }}" style="width: 100%;" tabindex="3"/>
		        </div>
		    </div>
		    
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-4">Local Area City</label>
		        <div class="col-md-8">
		            <input type="text" name="local_area_city" id="local_area_city" row="5" class="form-control local_area_city" value="{{ $row->local_area_city }}" style="width: 100%;" tabindex="3"/>
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
        	  <a href="{{ url('secondarydataupload') }}" class='btn btn-danger'>Cancel</a>
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
        var url		= "{{ url('secondarydatauploadsave') }}";
        var red_url		="{{ url('secondarydataupload') }}";
        validationrule('secondarydataupload');
        var formdata	= $('#secondarydataupload').serialize();
        var form = $('#secondarydataupload');
        form.parsley().validate();
        var form = $('#secondarydataupload');
        form.parsley().validate();

        if (form.parsley().isValid())
        {
            $.post(url,formdata,function(data)
            {
                var status      = data.status;
                var msg     = '<span style="color:#090065"> Secondary Data</span>  '+data.message;
                
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
