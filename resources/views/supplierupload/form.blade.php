@extends('layouts.header')
@section('content')


    <span class="ui_close_btn"></span>

       
      <h2 class="heads"> Supplier <span class="ui_close_btn"><a href="{{ URL::to('supplierupload') }}" class="collapse-close pull-right btn-danger"></a></span></h2>
                        

                <div class="card">
                    
                    <?php error_reporting(0); ?>
                    <div class="card-body card-block">
                        <form  method="post" action="{{ URL::to('supplieruploadsave') }}" id="supplieruploadform" data-parsley-validate>
                          <input type="hidden" value="" name="savestatus" id="savestatus" />
                            {{ csrf_field()}}

                           <div class="col-md-4">
                                <div class="form-group row" style="display:none;">
                                    <label for="inputIsValid" class="form-control-label col-md-4">Supplier Number</label>
                                    <div class="col-md-6">
                                        <input class="form-control supplier_upload_id" id="supplier_upload_id" name="supplier_upload_id" size="16" type="hidden" value="{{ $supplieruploaddata->supplier_upload_id }}" >
                                        <input type="text" id="supplier_number" name="supplier_number" class="form-control supplier_number" value="{{ $supplieruploaddata->supplier_number }}" readonly>
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="customer Name" class="form-control-label col-md-4">Supplier Name</label>
                                    <div class="col-md-6">
                                        <input type='text' name="supplier_name" id="supplier_name" rows='5' class='form-control supplier_name'  value="{{ $supplieruploaddata->supplier_name }}" required>
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for=" Alternate Name" class="form-control-label col-md-4">Alternate Name</label>
                                    <div class="col-md-6">
                                        <input name="supplier_alternate_name"  id="supplier_alternate_name" class="form-control supplier_alternate_name" value="{{ $supplieruploaddata->supplier_alternate_name }}">
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                                <div class=" form-group row">
                                    <label for="Supplier Name" class="form-control-label col-md-4">Supplier Type</label>
                                    <div class="col-md-6">
                                        <select name="supplier_type_id"  id="supplier_type_id" class="supplier_type_id select2">
                                            {!!$supplier_type_id!!}
                                        </select>
                                    </div>
				                 <div class="col-md-2">
                                    </div>

                                </div>
                               <div class=" form-group row">
                                    <label for="PAN Number" class="form-control-label col-md-4">PAN Number</label>
                                    <div class="col-md-6">
                                       <input type="text" name="pan_number" id="pan_number" class="form-control pan_number" maxlength="10"  value="{{ $supplieruploaddata->pan_number }}" tabindex="4" >
                                    </div>

                                    <div class="col-md-1 showinline">

                                    </div>

                                </div>
<!--							   <div class="form-group row">
                                    <label class="form-control-label col-md-4">Default Bank</label>
                                    <div class="col-md-6">
                                      <input type="text" name="default_bank" id="default_bank" class="form-control default_bank" value="{{ $supplieruploaddata->bank_name }}">
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>-->
							   <div class="form-group row">
                                    <label  class="form-control-label col-md-4">Batch Name</label>
                                    <div class="col-md-6">
                                      <input type="text" name="batch_name" id="batch_name" class="form-control batch_name" value="{{ $supplieruploaddata->batch_name }}" readonly>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                               	 <div class="form-group row">
                                    <label  class="form-control-label col-md-4">Batch Date</label>
                                    <div class="col-md-6">
                                      <input type="text" name="batch_date" id="batch_date" class="form-control batch_date" value="{{ $supplieruploaddata->batch_date }}" readonly>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                               <div class="form-group row">
                                    <label  class="form-control-label col-md-4">Batch Status</label>
                                    <div class="col-md-6">
                                      <input type="text" name="batch_status" id="batch_status" class="form-control batch_status" value="{{ $supplieruploaddata->batch_status }}" readonly>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
							   <div class="form-group row">
                                    <label  class="form-control-label col-md-4">Batch Comments</label>
                                    <div class="col-md-6">
                                      <input type="text" name="batch_comments" id="batch_comments" class="form-control batch_comments" value="{{ $supplieruploaddata->batch_comments }}" readonly>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                
                                <div class="form-group row" >
                                    <label for="inputIsValid" class="form-control-label col-md-5">Pricelist Name</label>

                                    <div class="col-md-6">
                                        <select name='default_pricelist_id' class='default_pricelist_id select2'>
                                             {!! $default_pricelist_id !!}
                                        </select>
                                    </div>
                                 </div>

                                <div class="form-group row" >
                                    <label for="inputIsValid" class="form-control-label col-md-5">Default Payment Term</label>
                                    <div class="col-md-6">
                                         <select name='default_payment_terms_id' rows='5' class='form-control default_payment_terms_id select2' data-show-subtext="true" data-live-search="true"  required="true">
                                                {!! $default_payment_terms_id !!}
                                          </select>
                                    </div>
                                  </div>

                                <div class="form-group row" >
                                    <label for="inputIsValid" class="form-control-label col-md-5">Default Payment Method</label>
                                    <div class="col-md-6">
                                        <select name='default_payment_method_id' class='default_payment_method_id select2'>
                                            {!! $default_payment_method_id !!}
                                        </select>
                                    </div>
                                </div>
                                 
                                 <div class="form-group row" >
                                    <label for="inputIsValid" class="form-control-label col-md-5">Insurance Term</label>
                                    <div class="col-md-6">
                                       <select name='insurance_term_id' class='insurance_term_id select2' required="true">
                {!! $insurance_term_id !!}
            </select>
                                    </div>
                                </div>
                                 <div class="form-group row" >
                                    <label for="inputIsValid" class="form-control-label col-md-5">Delivery term</label>
                                    <div class="col-md-6">
                                       <select name='delivery_terms_id' class='delivery_terms_id select2' required="true">
                                            {!! $delivery_terms_id !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" >
                                    <label for="inputIsValid" class="form-control-label col-md-5">Freight Term</label>
                                    <div class="col-md-6">
                                       <select name='frieghtterm_id' rows='5' class='form-control frieghtterm_id select2' data-show-subtext="true" data-live-search="true" required="true">
                                            {!! $frieghtterm_id !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" >
                                    <label for="inputIsValid" class="form-control-label col-md-5">Freight Carrier</label>
                                    <div class="col-md-6">
                                     <select name='frieghtcarriers_id' rows='5' class='form-control frieghtcarriers_id select2' data-show-subtext="true" data-live-search="true" required="true">
                                          {!! $frieghtcarriers_id !!}
                                    </select>
                                    </div>
                                </div>
                                
							
                            </div>

                            <div class="col-md-4">
                                <div class="form-group row">
                                <label for="inputIsValid" class="form-control-label col-md-4">Account Structure</label>
                                <div class="col-md-6 sel2">
                                    <select style="width:100%" name='account_structure_id' rows='5' class='account_structure_id select2' >
                                        {!!$account_structure_id!!}
                                    </select>
                                </div>
                                <div class="col-md-2 showinline">
                                    
                                </div>
                            </div>
                                <div class="form-group row">
                                <label for="inputIsValid" class="form-control-label col-md-4">Default Bank</label>
                                <div class="col-md-6 sel2">
                                    <select style="width:100%" name='default_bank_id' rows='5' class='default_bank_id select2' >
                                        {!!$default_bank_id!!}
                                    </select>
                                </div>
                                <div class="col-md-2 showinline">
                                    
                                </div>
                            </div>


                            <div class="form-group row">
                                       <label for="active" class="form-control-label col-md-4">Active</label>
                                       <div class="col-md-6 sel2">
                                           <select name='active' id="active" rows='5' class='active select2'  >
                                               <option <?php if($row->active=="Yes"){ echo "selected" ; }?>  value="Yes" >Yes</option>
                                               <option <?php if($row->active=="No"){ echo "selected" ; }?> value="No" >No</option>
                                           </select>
                                       </div>

                                  </div>
                                
                               <div class="form-group row">
                            <label for="inputIsValid" class="form-control-label col-md-4">Convert Customer to Supplier</label>
                            <div class="col-md-6">
                                <div class="l-radio" style="display: flex;">
                                    <div class="c-radio">
                                        <input type="radio" name="customer_name" class="customer_name" id='customer_name' value="YES"  <?php echo ($customer_name=='YES')?'checked':'' ?>>
                                        <span class="check_mark"></span>
                                        <label for="">Yes</label>
                                    </div>
                                    <div class="c-radio">

                                        <input type="radio" name="customer_name" class="customer_name" id='customer_name' value="NO" <?php if( $customer_name!= "YES") echo "checked"; ?> >

                                            <span class="check_mark"></span>
                                            <label for="">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 customer_id" style="margin-top: 10px;padding: 5px;">
                                <select name='customer_id'  class='form-control customer_id select2' data-show-subtext="true" data-live-search="true" > {!! $customer_id !!}
                                </select>
                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
<!--<div class="col-md-4">-->

                <div class="form-group row panel-body remarks_cfg">
                    <label for="inputIsValid" class="form-control-label col-md-4">TDS Applicable</label>
                    <div class="col-md-6">
                        <select name='tds_applicable' rows='5'  class='form-control tds_applicable select2' data-show-subtext="true" data-live-search="true" >
                            
                            
                            <option value="">--Please Select--</option>
                            <option value="YES" <?php if($tds_applicable =='YES'){ echo "selected"; }?> >YES</option>
                            <option value="NO" <?php if($tds_applicable !='YES'){ echo "selected"; } ?> >NO</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                    </div>
                </div>
                <!--</div>-->
                                <div class="form-group row panel-body remarks_cfg">
                        <label for="inputIsValid" class="form-control-label col-md-4">Tds Percentage(%)</label>
                        <div class="col-md-6">
                            <select style="width:100%" name='tds_percentage' rows='5' class='tds_percentage select2' >
                                        {!!$tds_percentage!!}
                                    </select>
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                          <div class="form-group row">
                                <label for="inputIsValid" class="form-control-label col-md-4">TDS Account</label>
                                <div class="col-md-6 sel2">
                                    <select style="width:100%" name='tds_account_id' rows='5' class='tds_account_id select2' >
                                        {!!$tds_account_id!!}
                                    </select>
                                </div>
                                <div class="col-md-2 showinline">
                                   
                                </div>
                            </div>      
								
                            </div>


                            <!--******************************-->
                    
                    

<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="form-group text-center">
            <!--  <a class='btn applychanges'>Apply Changes</a>-->
            <button type="button" class="btn save">Save</button>
            <a href="../supplierupload" class='btn cancel'  >Cancel</a>

        </div>
    </div>
</div>

                        </form>

                    </div>
                </div>

            

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.js"></script>

<script>
    $(document).ready(function () {




    $(document).on('click','.save',function()
    {

        var url		= "{{ URL::to('supplieruploadsave') }}";
        var red_url		="{{ URL::to('supplierupload') }}";

        var formdata	= $('#supplieruploadform').serialize();
        var form = $('#supplieruploadform');

        $.post(url,formdata,function(data)
        {
            var status      = data.status;
			var msg			=data.message;
            var id          = data.id;
            notyMsg(status,msg);
            setTimeout(function(){
                window.location.href=red_url;
            }, 1500);

        });



    });



		 $(".customer_id").hide();
        
    $(".customer_name").click(function(){
        var val = $('.customer_name:checked').val();
        if(val == "YES" ){
            $(".customer_id").show();
            
        } else {
            
            $(".customer_id").hide();
            
        }
    });
<?php if($customer_name=='YES'){ ?>
       
            $(".customer_id").show();
      <?php  } else{ ?>
            $(".customer_id").hide();
        
        <?php } ?>
            
            
               $(".tds_applicable").change(function(){
        var tds = $(".tds_applicable option:selected").val();
        if(tds == "YES" ){
            $(".tds_per").show();
        }else{
            $(".tds_per").hide();
        }
    });
    
 $(".overdue").prop( "checked", true );

 $('.over_due_payment').hide();
 $('.overdue_cal').hide();
  if($('.overdue:checked').val()=="Yes"){
    $('.over_due_payment').show();
    $('.overdue_cal').show();
  }

  $(document).on('click', '.overdue', function (){
            var val = $('.overdue:checked').val();
	if(val=="Yes"){
            $('.over_due_payment').show();
            $('.overdue_cal').show();
	} else if(val=="No"){
	$('.over_due_payment').hide();
	$('.overdue_cal').hide();
	}
    });


        $(".supplier_name").keyup(function () {
            this.value = this.value.toUpperCase();
        });



    });
</script>

<style>
    .btn-xs {
        display: inline-block;
        min-width: 10px;
        margin: 2px 5px;
        /*padding: 10px 15px 12px;*/
        padding:5px;
        /*font: 700 12px/1 'Open Sans', sans-serif;*/
        border-radius: 3px;
        /*box-shadow: inset 0 -1px 0 1px rgba(0, 0, 0, 0.1), inset 0 -10px 20px rgba(0, 0, 0, 0.1);*/
        cursor: pointer;
    }
u {
    text-decoration: underline;
   width: 265px;
   margin-top: -66px;
   margin-left: 0px;
   font-weight:700;
}

</style>
@include('layouts.php_js_validation')
@endsection
