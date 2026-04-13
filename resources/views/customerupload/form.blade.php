@extends('layouts.header')
@section('content')

<span class="ui_close_btn"></span>


	<!------------------------- breadcrumbs start here --------------------------->

	<!---------------------------------------------------------------------------->



<h2  class="heads">Customer Upload
<span class="ui_close_btn"><a href="../customerupload" class="collapse-close pull-right btn-danger" onclick="../customerupload"></a></span>
</h2>

<div class="card">	


<div class="card-body card-block normalform">
	<form method="post" action="{{ url('customerupload') }}" id="customerupload" data-parsley-validate>
	{{ csrf_field() }}

	<div class="row">
	<div class="col-md-12">
	<div class="col-md-4">
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Customer Name</label>
			<div class="col-md-6">
			<input class="form-control customerupload_id" id="customerupload_id" name="customerupload_id" size="16" type="hidden" value="{{ $customeruploaddata['customerupload_id'] }}" readonly>
				<input type="text" name="customer_name" id="customer_name" value="{{ $customeruploaddata['customer_name'] }}" class="form-control customer_name">
			</div>
			<div class="col-md-2">
			</div>
		</div>

		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Customer Type</label>
			<div class="col-md-6">
				<select name='customer_type' rows='5' class='form-control select2 customer_type' data-show-subtext="true" data-live-search="true"  >
				{!! $customer_type !!}
				</select>
			</div>
			<div class="col-md-2">
			</div>
		</div>

		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Alternate Name</label>
			<div class="col-md-6">
				<input type="text" name="alternate_name" id="alternate_name" value="{{ $customeruploaddata['alternate_name'] }}" class="form-control alternate_name">
			</div>
			<div class="col-md-2 showinline">
			</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Pricelist Name</label>
			<div class="col-md-6">
				<select name='pricelist_name' rows='5' class='form-control select2 pricelist_name' data-show-subtext="true" data-live-search="true"   >
				{!! $pricelist_name !!}
				</select>
			</div>
			<div class="col-md-2">
			</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Default Payment Terms</label>
			<div class="col-md-6">
				<select name='default_payment_terms' rows='5' class='form-control select2 default_payment_terms' data-show-subtext="true" data-live-search="true"   >
				{!! $default_payment_terms !!}
				</select>
			</div>
			<div class="col-md-2">
			</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Default Payment Method</label>
			<div class="col-md-6">
				<select name='default_payment_method' rows='5' class='form-control select2 default_payment_method' data-show-subtext="true" data-live-search="true"   >
				{!! $default_payment_method !!}
				</select>
			</div>
			<div class="col-md-2">
			</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Pan No</label>
			<div class="col-md-6">
				<input type="text" name="pan_no" id="pan_no" value="{{ $customeruploaddata['pan_no'] }}" class="form-control pan_no" 	><span style="font-size: 85%;">(format:(1-5 and 10)-alphabet,(6-9)-numeric)</span>
			</div>
			<div class="col-md-2 showinline"></div>
		</div>
		<div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4">Account Code</label>
              <div class="col-md-6">
                <select  name='account_code' rows='5' class='account_code select2' id="account_code" >
                    {!!$account_code!!}
                </select>
              </div>
            <div class="col-md-2 showinline"></div>
        </div>
        <div class="form-group row">
	        <label for="inputIsValid" class="form-control-label col-md-4">Freight Term </label>
	        <div class="col-md-6">
	            <select name='freight_terms' rows='5' class='form-control freight_terms select2' id="freight_terms" >
	                {!! $freight_terms !!}
	            </select>

	        </div>
	        <div class="col-md-2 showinline"></div>
	    </div>
	    <div class="form-group row">
	        <label for="inputIsValid" class="form-control-label col-md-4">Delivery Term</label>
	        <div class="col-md-6">
	            <select name='delivery_term' rows='5' class='form-control delivery_term select2' id="delivery_term" >
	                {!! $delivery_term !!}
	            </select>
	        </div>
	        <div class="col-md-2 showinline"></div>
	    </div>
	</div>
	<div class="col-md-4">
		
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Sales Person</label>
			<div class="col-md-6">
				<select name='sales_person' rows='5' class='form-control select2 sales_person' data-show-subtext="true" data-live-search="true"   >
				{!! $sales_person !!}
				</select>

			</div>
			<div class="col-md-2">
			</div>
		</div>

		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Customer Category</label>
			<div class="col-md-6">
				<select name='customer_category' rows='5' class='form-control select2 customer_category' data-show-subtext="true" data-live-search="true"   >
				{!! $customer_category !!}
				</select>
			</div>
			<div class="col-md-2">
			</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Reward Opening Point</label>
			<div class="col-md-6">
				<input type="text" name="reward_opening_point" id="reward_opening_point" value="{{ $customeruploaddata['reward_opening_point'] }}" class="form-control reward_opening_point">
			</div>
			<div class="col-md-2">
			</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Reward Point</label>
			<div class="col-md-6">
				<input type="text" name="reward_point" id="reward_point" value="{{ $customeruploaddata['reward_point'] }}" class="form-control reward_point">
			</div>
			<div class="col-md-2">
			</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Discount</label>
			<div class="col-md-6">
				<select name='ar_discount_hdr' rows='5' class='form-control select2 ar_discount_hdr' data-show-subtext="true" data-live-search="true"   >
				{!! $ar_discount_hdr !!}
				</select>
			</div>
			<div class="col-md-2">
			</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Maximum Credit</label>
			<div class="col-md-6">
				<input type="text" name="maximum_credit" id="maximum_credit" value="{{ $customeruploaddata['maximum_credit'] }}" class="form-control maximum_credit">
			</div>
			<div class="col-md-2">
			</div>
		</div>
		
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Credit Check</label>
			<div class="col-md-6">
				<select name='credit_check' rows='5' class='form-control select2 credit_check' data-show-subtext="true" data-live-search="true"   >
				{!! $credit_check !!}
				</select>
			</div>
			<div class="col-md-2">
			</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Frieght Carriers Name</label>
			<div class="col-md-6">
				<select name='ar_frieghtcarriers_hdr' rows='5' class='form-control select2 ar_frieghtcarriers_hdr'   >
				{!! $ar_frieghtcarriers_hdr !!}
				</select>
			</div>
			<div class="col-md-2">
			</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Active</label>
			<div class="col-md-6">
				<select name='active' rows='5' class='form-control select2 active' id="active" >
					<option value="Yes" <?php if($customeruploaddata['active'] == "Yes") echo "selected" ?> >Yes</option>
					<option value="No" <?php if($customeruploaddata['active'] == "No") echo "selected" ?> >No</option>
				</select>
			</div>
			<div class="col-md-2">
			</div>
		</div>
	</div>
	<div class="col-md-4">

		
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Comapany Additional Info</label>
			<div class="col-md-6">
				<input type="text" name="company_additional_info" id="company_additional_info" value="{{ $customeruploaddata['company_additional_info'] }}" class="form-control company_additional_info">
			</div>
			<div class="col-md-2">
			</div>
		</div>

		<div class="form-group row additl-body project_cfg" >
          <label for="inputIsValid" class="form-control-label col-md-4">Line of business</label>
          <div class="col-md-6">
              <input type="text" name="line_of_business" id="line_of_business" value="{{ $customeruploaddata['line_of_business'] }}" class="form-control line_of_business">
          </div>
          <div class="col-md-2 showinline">

          </div>
      	</div>

      	<div class="form-group row additl-body project_cfg" >
            <label for="inputIsValid" class="form-control-label col-md-4">Default Bank</label>
            <div class="col-md-6">
  				<select name='default_bank' class='default_bank select2' id="default_bank" >
            		{!! $default_bank !!}
                </select>				  
			</div>
            <div class="col-md-2 showinline"></div>
        </div>

        <div class="form-group row panel-body remarks_cfg">
            <label for="inputIsValid" class="form-control-label col-md-4">TDS Applicable</label>
            <div class="col-md-6">
                <select name='tds_applicable' class='form-control tds_applicable select2' >
                    
                    
                    <option value="">--Please Select--</option>
                    <option value="YES" <?php if($customeruploaddata['tds_applicable'] =='YES'){ echo "selected"; }?> >YES</option>
                    <option value="NO" <?php if($customeruploaddata['tds_applicable'] =='NO'){ echo "selected"; } ?> >NO</option>
                </select>
            </div>
            <div class="col-md-2">
            </div>
        </div>
        <div class="form-group row panel-body remarks_cfg">
	        <label for="inputIsValid" class="form-control-label col-md-4">Tds Percentage(%)</label>
	        <div class="col-md-6">
	            <select name='tds_percentage' class='tds_percentage select2' >
	            	{!!$tds_percentage!!}
	            </select>
	        </div>
	        <div class="col-md-2">
	        </div>
	    </div>
	     <div class="form-group row panel-body">
            <label for="inputIsValid" class="form-control-label col-md-4">TDS Account Code</label>
            <div class="col-md-6">
                <select name='tds_account_code' class='tds_account_code select2' > 
                	{!!$tds_account_code!!}
                </select>
            </div>
    		<div class="col-md-2 showinline"></div>
        </div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Batch Name</label>
			<div class="col-md-6">
				<input type="text" name="batch_name" id="batch_name" value="{{ $customeruploaddata->batch_name }}" class="form-control batch_name" readonly>
			</div>
			<div class="col-md-2 showinline">

				</div>
		</div>

		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Batch Date</label>
			<div class="col-md-6">
				<input type="text" name="batch_date" id="batch_date" value="{{ $customeruploaddata->batch_date }}" class="form-control batch_date" readonly>
			</div>
			<div class="col-md-2 showinline">

				</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Batch Status</label>
			<div class="col-md-6">
				<input type="text" name="batch_status" id="batch_status" value="{{ $customeruploaddata->batch_status }}" class="form-control batch_status" readonly>
			</div>
			<div class="col-md-2 showinline">

				</div>
		</div>

		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Batch Comments</label>
			<div class="col-md-6">
				<textarea name="batch_comments" id="batch_comments" class="form-control batch_comments" readonly >
					{{ $customeruploaddata->batch_comments }}
				</textarea>
			</div>
			<div class="col-md-2 showinline">

				</div>
		</div>
	</div>

</div>
</div>
<div class="row">
	<div class="col-lg-12 col-md-12">
		<div class="form-group text-center">
			<!--  <a class='btn applychanges'>Apply Changes</a>-->
			<button type="button" class="btn save">SAVE</button>
			<a href="../customerupload" class='btn cancel'  >Cancel</a>

		</div>
	</div>
</div>
</form>
</div>
</div>


<style>
span.lst {
 display: inline-block;
 height: 33px;
 margin: 0 auto;
 padding: 10px;
}

input#choosefile {
    background: #9baff1;
    color: #fff;
    padding: 2px;
    word-wrap: normal;
}


</style>
<script>
$(document).ready(function(){

	$('#customer_name').keyup(function(){
	    this.value=this.value.toUpperCase();
  	})

	/* purpose for PAn no Validation*/
	$('.pan_no').change(function(event)
	{

 		var regExp = /[a-zA-z]{5}\d{4}[a-zA-Z]{1}/; 
 		var txtpan = $(this).val(); 
 		if (txtpan.length == 10 ) { 
  			if( txtpan.match(regExp) ){ 
  			} else {
  				notyMsgs("info","Not a valid PAN number");
  				$(".pan_no").val('');
   				event.preventDefault(); 
  			} 
 		} else { 
     		$(".pan_no").val('');
       	//	notyMsgs("info",'Please enter 10 digits for a valid PAN number');
       		event.preventDefault(); 
		} 
	});
	$('.pan_no').trigger('change');
	/* -- Start Save data -- */
	$(document).on('click','.save',function()
    {
    	var url		= "{{ URL::to('customeruploadsave') }}";
    	var create_url = "{{ URL::to('customerupload') }}";
    	var formdata	= $('#customerupload').serialize();
    	var form = $('#customerupload');
    	$.post(url,formdata,function(data)
        {

        	var status      = data.status;
			var msg			=data.message;
            var id          = data.id;
            notyMsg(status,msg);
       		window.location.href=create_url;
        });
    });
/* -- End Save data -- */
});

</script>
@endsection
