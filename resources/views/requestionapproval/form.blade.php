@extends('layouts.header')
@section('content')
<?php error_reporting(0);?>
<body>
<span class="ui_close_btn"></span>
<div class="container main_container">

	<!------------------------- breadcrumbs start here --------------------------->
<div class="row">
<div class="col-lg-12 col-md-12">
</div>
</div>
	<!---------------------------------------------------------------------------->

<div class="row">
<div class="col-lg-1">
</div>
<form method="post" action="{{ url('requestionsave') }}" id="requestion" data-parsley-validate>
{{ csrf_field() }}
<div class="col-lg-12">
<div class="card">
<div class="card-header">
<strong>Requestion</strong>
	
<span class="ui_close_btn"><a class="collapse-close pull-right btn-danger" onclick="location.href = '{{url('requestion')}}'"></a></span>
</div>
<?php include('tools_menu.php'); ?>
<div class="card-body card-block">	
	
	<div class="card-body card-block">
	<div class="col-md-4">
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Quote No</label>
			<div class="col-md-6">
			<input class="form-control quotation_hdr_id" id="quotation_hdr_id" name="quotation_hdr_id" size="16" type="hidden" value="{{ $row->quotation_hdr_id }}" readonly>
				<input type="text" id="quotation_no" name="quotation_no" class="form-control quotation_no" value="{{ $row->quotation_no }}" required>
			</div>
			<div class="col-md-2">
			</div>
		</div>
		
		
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Quotation Date</label>
			<div class="col-md-6">
			<div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
			<input class="form-control quotation_date" id="quotation_date" name="quotation_date" size="16" type="text" value="{{ $row->quotation_date }}" readonly>
			<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
			</div>
			<input type="hidden" id="quotation_date" value="{{ $row->quotation_date }}" />
			</div>
			<div class="col-md-2 showline">
			</div>
		</div>
		
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Quotation Type</label>
			<div class="col-md-6">
				<select type="text" name="quotation_type" id="quotation_type" class="form-control quotation_type" >
					<option value="">--select--</option>
					<option <?php if($row->quotation_type =="STANDARD") { echo "selected"; } else { echo ""; } ?> value="STANDARD">STANDARD</option>
					<option <?php if($row->quotation_type =="LABOUR") { echo "selected"; } else { echo ""; } ?> value="LABOUR">LABOUR</option>
				</select>
			</div>
			<div class="col-md-2">
			</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Quotation Status</label>
			<div class="col-md-6">
				<select type="text" name="quote_status" id="quote_status" class="form-control quote_status" value="{{ $row->quote_status }}">
				<option value="">--select--</option>
				<option <?php if($row->quote_status =="INITIATED") { echo "selected"; } else { echo ""; } ?> value="INITIATED">INITIATED</option>
				<option <?php if($row->quote_status =="APPROVED") { echo "selected"; } else { echo ""; } ?> value="APPROVED">APPROVED</option>
				</select>
			</div>
			<div class="col-md-2">
			</div>
		</div>
	</div>



	<div class="col-md-4">
            <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Supplier Name</label>
			<div class="col-md-6">
				<select name='supplier_id' rows='5' class='form-control supplier_id' data-show-subtext="true" data-live-search="true"  required >
				{!! $supplier_id !!}
				</select>
			</div>
			<div class="col-md-2 showinline">
			<span class="showspan"><i class="fa fa-search suppliersearch"></i> </span>
			<span class="showspan"><i class="fa fa-refresh jcr_supplier_id"></i></span>
			</div>
	     </div>
           <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4"> Delivery Date</label>
			<div class="col-md-6">
			<div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
			<input class="form-control delivery_date" id="delivery_date" name="delivery_date" size="16" type="text" value="{{ $row->delivery_date }}" readonly>
			<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
			</div>
			<input type="hidden" id="delivery_date" value="{{ $row->delivery_date }}" />
			</div>
			<div class="col-md-1 showline">
			</div>
		</div>
             <div class="form-group row" >
			<label for="inputIsValid" class="form-control-label col-md-4">Pricelist Name</label>
			<div class="col-md-6">
                            <select name='quote_pricelist_id' rows='5' class='form-control quote_pricelist_id' data-show-subtext="true" data-live-search="true"  required >
				{!! $quote_pricelist_id !!}
			</select>
			</div>
			<div class="col-md-1 showline">
					<span class="showspan"> <i class="fa fa-refresh jcr_quote_pricelist_id"></i></span>
				</div>
		</div>
             <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Freight Carriers </label>
			<div class="col-md-6">
				<select name='freight_carrier_id' rows='5' class='form-control freight_carrier_id' data-show-subtext="true" data-live-search="true"  required >
				{!! $freight_carrier_id !!}
				</select>
			</div>
			<div class="col-md-2 showinline">
			<span class="showspan"><i class="fa fa-refresh jcr_freight_carrier_id"></i></span>
			</div>
	     </div>

	</div>
		<div class="col-md-4">
                 <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Organization</label>
			<div class="col-md-6">
				<select name='organization_id' rows='5' class='form-control organization_id'  data-show-subtext="true" data-live-search="true"  >
					{!! $organization_id !!}
				</select>
			</div>
			<div class="col-md-2 showline">
			</div>
		</div>
                     <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Source</label>
			<div class="col-md-6">
				<select name='source' rows='5' class='form-control source'  data-show-subtext="true" data-live-search="true" readonly  >
					<option value="">--select--</option>
					<option <?php if($row->source =="STANDARD") { echo "selected"; } else { echo ""; } ?> value="STANDARD">STANDARD</option>
					<option <?php if($row->source =="REQUESTION") { echo "selected"; } else { echo ""; } ?> value="REQUESTION">REQUESTION</option>
					<option <?php if($row->source =="ENQUIRY") { echo "selected"; } else { echo ""; } ?> value="ENQUIRY">ENQUIRY</option>
					
					
				</select>
			</div>
			<div class="col-md-2">
			</div>
		</div>	
			
	<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Reference No</label>
			<div class="col-md-6">
			<input class="form-control reference_id" id="reference_id" name="reference_id" size="16" type="hidden" value="{{ $row->reference_id }}" readonly>
				<input type="text" id="reference_number" name="reference_number" class="form-control reference_number" value=" {{ $row->reference_number }}" readonly>
			</div>
			<div class="col-md-2">
			</div>
		</div>
          <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Quote Tax Total</label>
			<div class="col-md-6">
				<input type="text" name="quote_tax_total" id="quote_tax_total" value="{{ $row->quote_tax_total }}" class="form-control quote_tax_total">
			</div>
			<div class="col-md-2">
			</div>
	</div>
              <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Quote Grand Total</label>
			<div class="col-md-6">
				<input type="text" name="quote_grand_total" id="quote_grand_total" value="{{ $row->quote_grand_total }}" class="form-control quote_grand_total">
			</div>
			<div class="col-md-2">
			</div>
		</div>
                     
			
		
		
	</div>
     <!---------------Additional Details------------------------------------>
        <div class="col-lg-12 panel-group " id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

		<div class="panel panel-default">
			<div class="panel-heading" role="tab" id="headingTwo">
				<h4 class="panel-title">
					<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
						<i class="short-full glyphicon glyphicon-plus"></i>
						Additional Details
					</a>
				</h4>
			</div>
                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
<div class="col-md-4 panel-body">
               <div class="form-group row panel-body supprefno_cfg">
			<label for="inputIsValid" class="form-control-label col-md-4">Supplier Ref No</label>
			<div class="col-md-6">
				<input type="text" name="supplier_ref_no" id="supplier_ref_no" class="form-control supplier_ref_no" value="{{ $row->supplier_ref_no }}">
			</div>
			<div class="col-md-1">
			</div>
		</div>
            <div class="form-group row panel-body suppquodate_cfg">
			<label for="inputIsValid" class="form-control-label col-md-4">Supplier Quotation Date</label>
			<div class="col-md-6">
			<div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
			<input class="form-control supplier_quotation_date" id="supplier_quotation_date" name="supplier_quotation_date" size="16" type="text" value="{{ $row->supplier_quotation_date }}" readonly>
			<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
			</div>
			<input type="hidden" id="supplier_quotation_date" value="{{ $row->supplier_quotation_date }}" />
			</div>
			<div class="col-md-1 showline">
			</div>
		</div>
        </div>
<div class="col-md-4 panel-body">
             <div class="form-group row panel-body project_cfg" >
                                <label for="inputIsValid" class="form-control-label col-md-4">Project Name</label>
                                <div class="col-md-6">
                                <select name='project_id' rows='5' class='form-control project_id' data-show-subtext="true" data-live-search="true" >
                                        {!! $project_id !!}
                                        </select>
                                </div>
                                <div class="col-md-2 showline">
                                                <span class="showspan"> <i class="fa fa-refresh jcr_project_id"></i></span>
                                        </div>
                </div>
    </div>
    
    <div class="col-md-4 panel-body">             
                 <div class="form-group row panel-body remarks_cfg">
			<label for="inputIsValid" class="form-control-label col-md-4">Remarks</label>
			<div class="col-md-6">
				<input type="text" name="remarks" id="remarks" value="{{ $row->remarks }}" class="form-control remarks">
			</div>
			<div class="col-md-2">
			</div>
		 </div>
    </div>
        </div>
	</div>
	</div> 
    </div>
     
<div class="row">
	<div class="col-lg-12 col-md-12">
		<input type="hidden" name="submit_type" class="submit_type" value="" />
		<div class="form-group text-center actionbtn">

			<button type="submit" class="btn save saveform approve" value="submit" >Approve</button>
			<button type="submit" class="btn save saveform rejected" >Reject</button>
                        <a class='btn cancel' onclick="location.href = '{{url('poapproval')}}'">Cancel</a>

		</div>
	</div>
</div>
		
		
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	