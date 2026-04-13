@extends('layouts.header')
@section('content')

<style type="text/css">
@media  only screen and (min-width: 1500px) {

.bulk_subcontract_site_number {width: 150px !important;}
.bulk_subcontract_site_name {width: 150px !important;}
.bulk_address {width: 150px !important;}
.bulk_country {width: 150px !important;}
.bulk_state {width: 150px !important;}
.bulk_city {width: 150px !important;}
.bulk_pincode {width: 130px !important;}
.bulk_contact_person {width: 150px !important;}
.bulk_contact_number {width: 150px !important;}
.bulk_gst_number {width: 140px !important;}
.bulk_primary_address { width: 150px;}
.bulk_active { width: 150px;}

 }
@media  only screen and (min-width: 2000px) {

.bulk_subcontract_site_number {width: 200px !important;}
.bulk_subcontract_site_name {width: 200px !important;}
.bulk_address {width: 200px !important;}
.bulk_country {width: 200px !important;}
.bulk_state {width: 200px !important;}
.bulk_city {width: 200px !important;}
.bulk_pincode {width: 180px !important;}
.bulk_contact_person {width: 200px !important;}
.bulk_contact_number {width: 200px !important;}
.bulk_gst_number {width: 190px !important;}
.bulk_primary_address { width: 200px !important;}
.bulk_active { width: 200px !important;}

 }


.bulk_subcontract_site_number {width: 130px;}
.bulk_subcontract_site_name {width: 120px;}
.bulk_address {width: 100px;}
.bulk_country {width: 120px;}
.bulk_state {width: 120px;}
.bulk_city {width: 120px;}
.bulk_pincode {width: 80px;}
.bulk_contact_person {width: 100px;}
.bulk_contact_number {width: 100px;}
.bulk_gst_number {width: 140px;}
.bulk_primary_address {  width: 150px;}
.bulk_active { width: 150px;}
.modal-body{
    height: 290px;
    overflow-y:auto;
}
</style>


    <span class="ui_close_btn"></span>

        


<?php include('tools_menu.php'); ?>
 <div class="ajaxLoading"></div>
<h3 class="heads"><a role="button">Subcontract Supplier</a> <span class="ui_close_btn"><a href="{{ URL::to('subcontractsupplier') }}" class="collapse-close pull-right btn-danger" onclick="supplier"></a></span></h3>




<div class="card">

    <?php error_reporting(0); ?>
        <div class="card-body card-block">
        
        <form method="post" action="{{ URL::to('subcontractsave') }}" id="subcontractform" data-parsley-validate>
        <input type="hidden" value="" name="savestatus" id="savestatus" /> {{ csrf_field()}}

     
    
    <div class="row">
        <div class="col-md-12">

    <div class="col-md-4">
    <div class="form-group row">
        <label for="inputIsValid" class="form-control-label col-md-4">Subcontract Number</label>
        <div class="col-md-6">
            <input class="form-control subcontract_supplier_id" id="subcontract_supplier_id" name="subcontract_supplier_id" size="16" type="hidden" value="{{ $row->subcontract_supplier_id}}">
            <input type="text" id="subcontract_number" name="subcontract_number" class="form-control subcontract_number" value="{{ $row->subcontract_number}}" readonly tabindex="1">
        </div>
        <div class="col-md-2">
        </div>
    </div>

    <div class="form-group row">
        <label for="Supplier Name" class="form-control-label col-md-4"><span style="font-style:20px;color:red;">*</span>Subcontract Supplier Name</label>
        <div class="col-md-6">
            <input type='text' name="subcontract_name" id="subcontract_name" rows='5' class='form-control subcontract_name' required="true" value="{{ $row->subcontract_name }}" tabindex="2">
        </div>
        <div class="col-md-2">
        </div>
    </div>

    <div class="form-group row">
        <label for=" Alternate Name" class="form-control-label col-md-4">Subcontract Alternate Name</label>
        <div class="col-md-6">
            <input name="subcontract_alternate_name" id="subcontract_alternate_name" class="form-control subcontract_alternate_name" value="{{ $row->subcontract_alternate_name }}" tabindex="3">
        </div>
        <div class="col-md-2"></div>
    </div>
   <div class=" form-group row">
        <label for="Supplier Name" class="form-control-label col-md-4"><span style="font-style:20px;color:red;">*</span>Supplier Type </label> 
        <div class="col-md-6">
            <select name="supplier_type_id" id="supplier_type_id" class="supplier_type_id select2" required="true">
                {!!$supplier_type_id!!}
            </select>
        </div>
        <div class="col-md-1 showinline">
            <span class="showspan"> <i class="fa fa-refresh jcr_supplier_type_id"></i></span>
        </div>
        <div class="col-md-2">
        </div>
    </div>
<div class="form-group row">
        <label for="inputIsValid" class="form-control-label col-md-4"><span style="font-style:20px;color:red;">*</span>Default Payment Term</label>
        <div class="col-md-6 sel2   ">
            <select name='default_payment_terms_id' rows='5' class='form-control default_payment_terms_id select2' data-show-subtext="true" data-live-search="true" required="true">
                {!! $default_payment_terms_id !!}
            </select>
        </div>

        <div class="col-md-1 showinline">
            <span class="showspan"> <i class="fa fa-refresh jcr_default_payment_terms_id"></i></span>
        </div>
    </div>

    
    

</div>

 <div class="col-md-4">
<div class=" form-group row">
        <label for="PAN Number" class="form-control-label col-md-4">PAN Number</label>
        <div class="col-md-6">
            <input type="text" name="pan_number" id="pan_number" class="form-control pan_number" maxlength="10"  value="{{ $row->pan_number }}" tabindex="4" >
			<span class="pan " style="font-size:11px;color:red;">Please Enter Valid Pan Number</span>		
        </div>
	
        <div class="col-md-1 showinline">

        </div>
        
    </div>
<div class="form-group row">
        <label for="inputIsValid" class="form-control-label col-md-4"><span style="font-style:20px;color:red;">*</span>Default Payment Method</label>
        <div class="col-md-6 sel2">
            <select name='default_payment_method_id' class='default_payment_method_id select2' required="true">
                {!! $default_payment_method_id !!}
            </select>
        </div>
        <div class="col-md-1 showinline">
            <span class="showspan"> <i class="fa fa-refresh jcr_default_payment_method_id"></i></span>
        </div>
    </div>

    <div class="form-group row">
        <label for="inputIsValid" class="form-control-label col-md-4"><span style="font-style:20px;color:red;">*</span>Pricelist Name</label>
        <div class="col-md-6 sel2">
            <select name='default_pricelist_id' class='default_pricelist_id select2' required="true">
                {!! $default_pricelist_id !!}
            </select>
        </div>

        <div class="col-md-1 showinline">
            <span class="showspan"> <i class="fa fa-refresh jcr_default_pricelist_id"></i></span>
        </div>
    </div>
     <div class="form-group row">
        <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red;">*</span>Delivery Term</label>
        <div class="col-md-6 sel2">
            <select name='delivery_terms_id' class='delivery_terms_id select2' required="true">
                {!! $delivery_terms_id !!}
            </select>
        </div>
        <div class="col-md-1 showinline">
            <span class="showspan"> <i class="fa fa-refresh jcr_delivery_terms_id"></i></span>
        </div>
    </div>
 
<div class="form-group row">
        <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red;">*</span>Insurance Term</label>
        <div class="col-md-6 sel2">
            <select name='insurance_term_id' class='insurance_term_id select2' required="true">
                {!! $insurance_term_id !!}
            </select>
        </div>
	 
        <div class="col-md-1 showinline">
            <span class="showspan"> <i class="fa fa-refresh jcr_insurance_term_id"></i></span>
        </div>
    </div>

</div>

    


<div class="col-md-4">
    <div class="form-group row">
        <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red;">*</span>Account Structure</label>
        <div class="col-md-6 sel2">
            <select style="width:100%" name='account_structure_id' rows='5' class='account_structure_id select2' required>
                {!!$account_structure_id!!}
            </select>
        </div>
        <div class="col-md-2 showinline">
            <span class="showspan"> <i class="fa fa-refresh jcr_account_structure_id"></i></span>
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
            <label for="active" class="form-control-label col-md-4">Created By</label>
            <div class="col-md-6 sel2" style="pointer-events:none;">
				<select name='created_by' rows='5' class='select2 created_by' id="created_by" >
			{!! $created_by !!}
				</select>
			</div>
            
       </div>
<div class="form-group row">
                            <label for="inputIsValid" class="form-control-label col-md-4">Convert Customer to Supplier</label>
                            <div class="col-md-6">
                                <div class="l-radio" style="display: flex;">
                                    <div class="c-radio">
                                        <input type="radio" name="customer_name" class="customer_name" id='customer_name' value="Yes" required <?php echo ($row->customer_name=='Yes')?'checked':'' ?>>
                                        <span class="check_mark"></span>
                                        <label for="">Yes</label>
                                    </div>
                                    <div class="c-radio">

                                        <input type="radio" name="customer_name" class="customer_name" id='customer_name' value="No" <?php if( $row->subcontract_supplier_id== "") echo "checked"; ?> required
                                        <?php if($row->customer_name=="No") echo "checked"; else ''; ?> >

                                            <span class="check_mark"></span>
                                            <label for="">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 customer" style="margin-top: 10px;padding: 5px;">
                                <select name='customer_id' <?php echo $required; ?> class='form-control customer_id select2' data-show-subtext="true" data-live-search="true" > {!! $customer_id !!}
                                </select>
                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
    </div>


</div>



</div>
			







<div class="row">
<div class="col-md-12" style="display:none;">
<hr class="xlg">
</div>
</div>

<div class="row">
<!--    <div class="col-md-12">
		<h4> <u>Payment Terms</u></h4>
    </div>-->
    <div class="row" style="display:none;">
    <div class="col-md-4"></div>
    <div class="col-md-4">
    <div class="form-group row">
        <label for="inputIsValid" class="form-control-label col-md-6">if overdue payment is applicable :</label>
        <div class="col-md-6">
            <div class="l-radio" style="display: flex;">
                <div class="c-radio">
                    <input type="radio" name="overdue" class="overdue" id='watch-me' value="Yes" required <?php if($row->overdue=="Yes") echo "checked"; else ''; ?> >
                    <span class="check_mark"></span>
                    <label for="">Yes</label>
                </div>
                <div class="c-radio">
                    <input type="radio" name="overdue" class="overdue" id='watch-me' value="No" <?php if( $row->subcontract_supplier_id== "") echo "checked"; ?> required
                    <?php if($row->overdue=="No") echo "checked"; else ""; ?> >
                        <span class="check_mark"></span>
                        <label for="">No</label>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="col-md-4"></div>
    </div>







<div id='show-me' class="col-md-12">
    <div class="">
        <div class="row">
        <div class="col-md-4">
        <div class="form-group row overdue_cal">
            <label class="field col-md-3">Calculation</label>
            <select name="calculation_id" id="calculation_id" class="form-control calculation_id select2" value="" style="width:200px;">
                <option value="">--Please Select</option>
                <option value="Taxable Amount" <?php if($lineoverdue[0]->calculation_id=='Taxable Amount'){ echo "selected"; }?> >Taxable Amount</option>
                <option value="Net Amount" <?php if($lineoverdue[0]->calculation_id=='Net Amount'){ echo "selected"; }?>>Net Amount</option>
            </select>
        </div>
        </div>
        </div>
        <!--***************** Discount payment ***********************************-->
        <div class="row over_due_payment">
            <br>
            <br>
            <div class="col-md-6">
                <fieldset><u>Discount for Before Payment  Due Date:</u>
                    <br>
                    <br>
                    <a href="javascript:void(0);" class="add_row1 additem " rel=".clone1"><i class="fa fa-plus"></i> ADD</a>

                    <div id="preview-area" class="chandru">
                        <table class="overflow-y preview discount_table">
                            <thead>
                                <tr>
                                    <th> Days </th>
                                    <th>% Discount</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody class="discount_body ">
                                <?php if (count($lineoverdue) >= 1) { ?>
                                    @foreach($lineoverdue as $key=>$value)
                                    <tr class="clone clonedInput">
                                        <td></td>
                                        <td>
                                            <input type="text" name="before_days[]" class="form-control" id="before_days" value="{{ $value->before_day }}">
											<span id="spnError" style="color: Red; display: none">Valid characters: Numbers and (.) </span>
                                        </td>
                                        <td>
                                            <input type="text" name="before_dis[]" class="form-control" id="before_dis" value="{{ $value->before_dis }}">
                                        </td>
                                        <td><a class=" btn btn btn-xs btn-danger remove0 remove1">-</a></td>
                                        <td style="display: none;">
                                            <input type="hidden" name="dis_counter[]">
                                        </td>
                                    </tr>
                                    @endforeach
                                    <?php } if (count($lineoverdue) < 1) { ?>
                                        <tr class="clone clonedInput">
                                            <td></td>
                                            <td>
                                                <input type="text" name="before_days[]" class="form-control" id="before_days">
                                            </td>
                                            <td>
                                                <input type="text" name="before_dis[]" class="form-control" id="before_dis">
                                            </td>
                                            <td><a style="width: 16px;" class=" btn btn btn-xs btn-danger remove0 remove1">-</a></td>
                                            <td style="display: none;">
                                                <input type="hidden" name="dis_counter[]">
                                            </td>
                                        </tr>
                                        <?php } ?>
                            </tbody>
                        </table>

                    </div>
                </fieldset>
            </div>

            <!--******Interest payment **********-->
            <div class="col-md-6">
                <fieldset><u>Interest for After Payment  Due Date:</u>
                    <br>
                    <br>
                    <a href="javascript:void(0);" class="add_row2 additem" rel=".clone2"><i class="fa fa-plus"></i> ADD</a>

                    <div id="preview-area" class="chandru">
                        <table class="overflow-y preview interest_table">
                            <thead>
                                <tr>

                                    <th>Days </th>
                                    <th>% Interest</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody class="interest_body">
                                <?php if (count($lineoverdue) >= 1) { ?>
                                    @foreach($lineoverdue as $key=>$value)
                                    <tr class="clone1 clonedInput ">
                                        <td></td>
                                        <td>
                                            <input type="text" name="after_days[]" class="form-control" id="after_days" value="{{ $value->after_day }}">
                                        </td>
                                        <td>
                                            <input type="text" name="after_int[]" class="form-control" id="after_int" value="{{ $value->after_int }}">
                                        </td>
                                        <td><a style="width:16px;" class=" btn btn-xs btn-danger remove0 remove2">-</a></td>
                                        <td style="display: none;">
                                            <input type="hidden" name="int_counter[]">
                                        </td>
                                    </tr>
                                    @endforeach
                                    <?php } if (count($lineoverdue) < 1) { ?>
                                        <tr class="clone1 clonedInput ">
                                            <td></td>
                                            <td>
                                                <input type="text" name="after_days[]" class="form-control" id="after_days">
                                            </td>
                                            <td>
                                                <input type="text" name="after_int[]" class="form-control" id="after_int">
                                            </td>
                                            <td><a style="width:16px;" class=" btn btn-xs btn-danger remove0 remove2">-</a></td>
                                            <td style="display:none;">
                                                <input type="hidden" name="int_counter[]">
                                            </td>
                                        </tr>
                                        <?php } ?>
                            </tbody>
                        </table>

                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</div>

</div>

<div class="row">
<div class="col-md-12">
<hr class="xlg">
</div>
</div>


<div class="row">
    <div class="col-md-12">
    <h3 class="myheaders">Additional Details</h3>
    </div>
   

        <?php $i=0; $j=0; 
                       //dd($enabled_columns);
                       foreach($enabled_columns as $index=>$val) 
                       {  

                        if($val->action=='1')
                        {
                            $required="required"; 
                        }
                        else
                        { 
                             $required='';
                        }

                        if($val->active==1)
                        {   
                            $show_div=1; 
                        }
                        else
                        { 
                            $show_div=0; 
                        }

                        if($i!=$j) 
                         { 
                             $j=$i;    ?>
   
   
        <?php } 
            if($val->column_name=='default_bank_id' && $val->active==1) 
              { 
                  $i++; 
           ?>
            <div class="col-md-4">
            <div class="form-group row panel-body remarks_cfg">
                <label for="inputIsValid" class="form-control-label col-md-4">Default Bank</label>
                <div class="col-md-6">
                    <select name='default_bank_id' rows='5' <?php echo $required; ?> class='form-control default_bank_id select2' data-show-subtext="true" data-live-search="true" > {!!$default_bank_id!!}
                    </select>
                </div>
                <div class="col-md-2 showinline">
                    <span class="showspan"> <i class="fa fa-refresh jcr_default_bank_id"></i></span>
                </div>
            </div>
            </div>
            <?php } 
            if($val->column_name=='tds_applicable' && $val->active==1) { $i++; ?>
                 <div class="col-md-4">
                <div class="form-group row panel-body remarks_cfg">
                    <label for="inputIsValid" class="form-control-label col-md-4">TDS Applicable</label>
                    <div class="col-md-6">
                        <select name='tds_applicable' rows='5' <?php echo $required; ?> class='form-control tds_applicable select2' data-show-subtext="true" data-live-search="true" >
                            
                            
                            <option value="">--Please Select--</option>
                            <option value="YES" <?php if($row->tds_applicable =='YES'){ echo "selected"; }?> >YES</option>
                            <option value="NO" <?php if($row->tds_applicable =='NO'){ echo "selected"; } ?> >NO</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                    </div>
                </div>
                </div>

                 
                <div class="tds_per">
                    <div class="col-md-4">
                    <div class="form-group row panel-body remarks_cfg">
                        <label for="inputIsValid" class="form-control-label col-md-4">Tds Percentage(%)</label>
                        <div class="col-md-6">
                            <select name='tds_percentage' rows='5' <?php echo $required; ?> class='form-control tds_percentage select2' data-show-subtext="true" data-live-search="true" >
                                <option value="">--Please Select--</option>
                                <option value="1" <?php if($row->tds_percentage =='1'){ echo "selected"; }?> >1</option>
                                <option value="2" <?php if($row->tds_percentage =='2'){ echo "selected"; }?> >2</option>
                                <option value="10" <?php if($row->tds_percentage =='10'){ echo "selected"; }?> >10</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group row panel-body">
                        <label for="inputIsValid" class="form-control-label col-md-4">TDS Account Code</label>
                        <div class="col-md-6">
                            <select name='tds_account_id' rows='5' <?php echo $required; ?> class='tds_account_id select2' data-show-subtext="true" data-live-search="true" > {!!$tds_account_id!!}
                            </select>
                        </div>
                <div class="col-md-2 showinline">
					  <span class="showspan"> <i class="fa fa-refresh jcr_tds_account_id"></i></span>
                        </div>
                    </div>
                </div>
                </div>
                <?php }    if($val->column_name=='tcs_applicable' && $val->active==1) { $i++; ?>
                 <div class="col-md-4">
                <div class="form-group row panel-body remarks_cfg">
                    <label for="inputIsValid" class="form-control-label col-md-4"><?php if($required != '') { ?><span style="color:red;">*</span><?php } ?>TCS Applicable</label>
                    <div class="col-md-6">
                        <select name='tcs_applicable' rows='5' <?php echo $required; ?> class='form-control tcs_applicable select2' data-show-subtext="true" data-live-search="true" >
                            
                            
                            <option value="">--Please Select--</option>
                            <option value="YES" <?php if($row->tcs_applicable =='YES'){ echo "selected"; }?> >YES</option>
                            <option value="NO" <?php if($row->tcs_applicable =='NO'){ echo "selected"; } ?> >NO</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                    </div>
                </div>
                </div>

                 
                <div class="tcs_per">
                    <div class="col-md-4">
                    <div class="form-group row panel-body remarks_cfg">
                        <label for="inputIsValid" class="form-control-label col-md-4">TCS Percentage(%)</label>
                        <div class="col-md-6">
                            <select name='tcs_percentage' rows='5' <?php echo $required; ?> class='form-control tcs_percentage select2' data-show-subtext="true" data-live-search="true" >
							{!!$tcs_percentage!!}
                            </select>
                        </div>
                        <div class="col-md-2 showinline">
            <span class="showspan"> <i class="fa fa-refresh tcs_percentage"></i></span>
        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group row panel-body">
                        <label for="inputIsValid" class="form-control-label col-md-4">TCS Account Code</label>
                        <div class="col-md-6">
                            <select name='tcs_account_id' rows='5' <?php echo $required; ?> class='tcs_account_id select2' data-show-subtext="true" data-live-search="true" > {!!$tcs_account_id!!}
                            </select>
                        </div>
                <div class="col-md-2 showinline">
					  <span class="showspan"> <i class="fa fa-refresh jcr_tcs_account_id"></i></span>
                        </div>
                    </div>
                </div>
                </div>
                <?php }  
            if($val->column_name=='supplier_status' && $val->active==1) { $i++; ?>
                 <div class="col-md-4">
                    <div class="form-group row panel-body remarks_cfg">
                        <label for="inputIsValid" class="form-control-label col-md-4">Supplier Status</label>
                        <div class="col-md-6">
                            <select name='supplier_status' rows='5' <?php echo $required; ?> class='form-control supplier_status select2' data-show-subtext="true" data-live-search="true" >
                                <option value="">--Please Select--</option>
                                <option value="ACTIVE" <?php if($row->supplier_status =='ACTIVE'){ echo "selected"; }?> >ACTIVE</option>
                                <option value="INACTIVE" <?php if($row->supplier_status =='INACTIVE'){ echo "selected"; }?> >INACTIVE</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                </div>
                    <?php }  } ?>

    

</div>



<div class="row">
<div class="col-md-12">
<hr class="xlg">
</div>
</div>

<div class="row">
    <div class="col-md-12">
       <a href="javascript:void(0);" class="add_row additem newitem" rel=".rcopy">
            <i class="fa fa-plus"></i> New Item</a>

        <div id="preview-area" class="chandru">
            <table class="overflow-y preview suppliersite_table">

                <thead>
                    <tr>

                        <th>Subcontract Site Number</th>
                        <th>Subcontract Site Name </th>
                        <th>Address </th>
                        <th>Country</th>
                        <th>State</th>
                        <th>City</th>
                        <th>Pincode</th>
                        <th>Contact</th>
                        <th>GST Number</th>
                        <th>Primary Address</th>
                        <th>Active</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="suppsite_body">
                    <!--Edit Mode-->
                    <?php  if (count($linedata) >= 1) {
                                        //dd($linedata);    ?>
                        @foreach($linedata as $key=>$value)
                        <tr class="rcopy clone">

                            <td>
                                <input type="hidden" name="bulk_subcontract_site_id[]" class="form-control bulk_subcontract_site_id " value="{{$value->subcontract_site_id }}" readonly="readonly">
                            </td>
                            <td>
                                <input type="text" name="bulk_subcontract_site_number[]" class="form-control bulk_subcontract_site_number" data-count="{{ $value->subcontract_site_number }}" value="{{ $value->supplier_site_number }}" readonly="readonly">
                            </td>
                            <td>
                                <input type="text" name="bulk_subcontract_site_name[]" class="form-control  bulk_subcontract_site_name" value="{{ $value->subcontract_site_name }}" required>
                            </td>



                            <td>
                                <input type="text" name="bulk_address[]" class="form-control  bulk_address " required value="{{ $value->address }}">
                            </td>
                            <td class="sel2">
                                <select name="bulk_country[]" class="form-control bulk_country select2" value="{{$value->country}}">{!! $country_id !!}</select>
                            </td>
                            <td class="sel2">
                                <select name="bulk_state[]" class="form-control  bulk_state select2" value="{{$value->state}}">{!! $state_id !!} </select>
                            </td>
                            <td class="sel2">
                                <select name="bulk_city[]" class="form-control   bulk_city select2" value="{{$value->city}}">{!! $city_id !!}</select>
                            </td>
                            <td>
                                <input type="text" name="bulk_pincode[]" class="form-control  bulk_pincode input_comments_width " value="{{ $value->pincode }}" minlength="1" maxlength="6">
                            </td>
<!--                            <td>
                                <input type="text" name="bulk_contact_person[]" class="form-control bulk_contact_person " value="{{ $value->contact_person }}">
                            </td>
                            <td>
                                <input type="text" name="bulk_contact_number[]" class="form-control bulk_contact_number " value="{{ $value->contact_number }}">
                            </td>-->
<td>
                                <a href="#" class="contactdetail" title="Add Dispatch Qty"> <i class="fa fa-plus"></i></a>
                                <input type="hidden" name="bulk_contact_person[]" class="form-control bulk_contact_person " value="{{$value->contact_person}}">
                                <input type="hidden" name="bulk_contact_number[]" class="form-control bulk_contact_number " value="{{$value->contact_number}}">
                                <input type="hidden" name="bulk_contact_mail[]" class="form-control bulk_contact_mail " value="{{$value->contact_mail}}">
                            </td>                            <td>
                                <input type="text" name="bulk_gst_number[]" class="form-control bulk_gst_number " value="{{ $value->gst_number }}"  minlength="15" maxlength="15">
                            </td>
                            <td class="sel2">
                                <select name="bulk_primary_address[]" id="bulk_primary_address" class="form-control bulk_primary_address select2" required>
                                    <option value="">Please select</option>
                                    <option value="Yes" <?php if ($value->primary_address == 'Yes') { echo "selected" ; } else { echo ""; } ?>>Yes</option>
                                    <option value="No" <?php if ($value->primary_address == 'No') { echo "selected" ; } else { echo ""; } ?>>No</option>
                                </select>
                            </td>
                            <td>
                                <select name="bulk_active[]" class="select2 bulk_active " id="bulk_active" readonly>
                                                             <option value="Yes" <?php if($value->active=='Yes'){ echo "selected"; }?>>Yes</option>
                                                             <option value="No" <?php if($value->active=='No'){ echo "selected"; }?>>No</option>
                                </select>
                             </td>
                            <td><a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
                                <input type="hidden" name="counter[]" value="" />
                            </td>
                        </tr>
                        @endforeach
                        <!--Create Mode-->
                        <?php } if (count($linedata) < 1) { ?>
                            <tr class="rcopy clone">
                                <td>
                                    <input type="hidden" name="bulk_subcontract_site_id[]" class="form-control bulk_subcontract_site_id " value="" readonly="readonly">
                                </td>
                                <td>
                                    <input type="text" name="bulk_subcontract_site_number[]" class="form-control bulk_subcontract_site_number" value="" readonly="readonly" value="">
                                </td>
                                <td>
                                    <input type="text" name="bulk_subcontract_site_name[]" class="form-control  bulk_subcontract_site_name " value="" required>
                                </td>
                                 <td>
                                    <input type="text" name="bulk_address[]" class="form-control  bulk_address " value="" required>
                                </td>
                                <td class="sel2">
                                    <select name="bulk_country[]" class="form-control  bulk_country select2" value="" required>{!! $country_id !!}</select>
                                </td>
                                <td class="sel2">
                                    <select name="bulk_state[]" class="form-control  bulk_state select2 " value="" required>{!! $state_id !!}</select>
                                </td>
                                <td class="sel2">
                                    <select name="bulk_city[]" class="form-control   bulk_city select2" value="" required>{!! $city_id !!}</select>
                                </td>
                                <td>
                                    <input type="text" name="bulk_pincode[]" class="form-control  bulk_pincode input_comments_width " value="" minlength="1" maxlength="6">
                                </td>
                                <td>
                                    <a href="#" class="contactdetail" title="Add Dispatch Qty"> <i class="fa fa-plus"></i></a>
                                    <input type="hidden" name="bulk_contact_person[]" class="form-control bulk_contact_person " value="">
                                    <input type="hidden" name="bulk_contact_number[]" class="form-control bulk_contact_number " value="">
                                    <input type="hidden" name="bulk_contact_mail[]" class="form-control bulk_contact_mail " value="">
                                </td>
                                <td>
                                    <input type="text" name="bulk_gst_number[]" class="form-control bulk_gst_number" value="" maxlength="15">
                                </td>
                                <td class="sel2">
                                    <select name="bulk_primary_address[]" id="bulk_primary_address" class="form-control bulk_primary_address select2" required>
                                        <option value="">Please select</option>
                                        <option value="Yes" <?php if ($value->primary_address == 'Yes') { echo "selected" ; } else { echo ""; } ?>>Yes</option>
                                        <option value="No" <?php if ($value->primary_address == 'No') { echo "selected" ; } else { echo ""; } ?>>No</option>
                                    </select>
                                </td>
                               <td>
                                <select name="bulk_active[]" class="select2 bulk_active " id="bulk_active" readonly>
                                                             <option value="Yes" <?php if($value->active=='Yes'){ echo "selected"; }?>>Yes</option>
                                                             <option value="No" <?php if($value->active=='No'){ echo "selected"; }?>>No</option>
                                </select>
                                </td>
                                <td><a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
                                    <input type="hidden" name="counter[]" value="" />
                                    <input type="hidden" class="copy_site_id" />
                                </td>
                            </tr>

                            <?php } ?>
                </tbody>
            </table>
            <input type="hidden" name="enable-masterdetail" value="true">
        </div>
        <br>
    </div>
</div>


<div class="row">
<div class="col-md-12">
<hr class="xlg">
</div>
</div>


<input type="hidden" name="site_count" class="site_count" value="{{ $site_count }}" />
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="form-group text-center">
            <?php  if($row->subcontract_supplier_id!='')
                                        { ?>
                <button type="button" class="btn save saveform" value="SAVENEW">Save AND NEW</button>
                <button type="button" class="btn save saveform" value="SAVE">Save</button>
                <a href="{{ URL::to('subcontractsupplier') }}" class='btn cancel'>Cancel</a>
                <?php   } else
                          { ?>
                    <button type="button" class="btn applychanges saveform draft" value="APPLYCHANGES">DRAFT</button>
                    <button type="button" class="btn save saveform" value="SAVE">Save</button>
                    <button type="button" class="btn save saveform" value="SAVENEW">Save AND NEW</button>
                    <a href="{{ URL::to('subcontractsupplier') }}" class='btn cancel'>Cancel</a>
                    <?php  } ?>

        </div>
    </div>
</div>
                 


           

     <!-- purpose for dispatch qty start -->
<div class="modal fade" id="contactModal">
  <div class="modal-dialog modal-lg" >
    <div class="modal-content">
        <!--Moda Header-->
      <div class="modal-header">
          <h4 class="modal-title"> Contact Details </h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <input type="hidden" class="conindex" value="">
      </div>
        <!-- Modal Body -->
        <button type="button" class="btn add addmasterbox" id="addbox"> Add Contact Details</button> 
        
      <div class="modal-body ">
        <div id="preview-area" class="chandru">
            <table class="overflow-y preview contact_table">
                <thead>
                    <tr>
                        <th>Contact Name</th>
                        <th>Contact Number</th>
                        <th>Mail Id</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="contact_body">
                    <tr class="rcopy4 clone4">
                        <td></td>
                        <td>
                            <input type="text" name="contact_name" class="contact_name form-control">
                        </td>
                        <td>
                            <input type="text" name="contact_number" class="contact_number form-control">
                        </td>
                        <td>
                            <input type="text" name="contact_mail" class="contact_mail form-control">
                        </td>
                        <td><a class="removecon removecon0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
                            <input type="hidden" name="con_counter[]" value="" />
                        </td>
                    </tr>
                </tbody>
                
            </table>
             <a href="javascript:void(0);" class="add_row3 additem newitem" rel=".rcopy4">
            <i class="fa fa-plus"></i> New Item</a>
        </div>
        
      </div>
        
         <!-- Modal footer -->
      <div class="modal-footer">
      </div>

    </div>
  </div>
</div>
<!--end-->             










        </form>
        </div>
        </div>
        </div>

</div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.js"></script>

<script>
	/*Raji purpose to set primary address validation*/
function check_primary_address(index)
{
	
	
	
	
	
	var bpmadd = $(".bulk_primary_address" + index).val();
	if (bpmadd == "Yes")
	{
	 $(".bulk_primary_address").each(function(i)
	 {
		if(index != i)
		{
			if($(this).val() == "Yes")
			{
				$('.bulk_primary_address'+i).val('No').change();
				$('.bulk_primary_address' + index).val('Yes').change();
			}
		}
	 });
	}
	else
	{
     var count =0;
	$(".bulk_primary_address").each(function(i)
	 {
		if(index != i)
		{
			if($(this).val() == "Yes")
			{
				count++;
			}
		}
	 });
		if(count <= 0)
		{
		notyMsgs('info',"Still There's No Primary Address,Please Select Primary Address...");	
		$('.bulk_primary_address' + index).val('');
		return false;
		}
	}
}


    $(document).ready(function () {


/**********Up/down/left/right arrow navigation start*******/
		$('input').keyup(function (e) {
	        if (e.which == 39) { // right arrow
	          $(this).closest('td').next().find('input').focus();
	 
	        } else if (e.which == 37) { // left arrow
	          $(this).closest('td').prev().find('input').focus();
	 
	        } else if (e.which == 40) { // down arrow
	          $(this).closest('tr').next().find('td:eq(' + $(this).closest('td').index() + ')').find('input').focus();
	 
	        } else if (e.which == 38) { // up arrow
	          $(this).closest('tr').prev().find('td:eq(' + $(this).closest('td').index() + ')').find('input').focus();
	        }
      	});
        
		$('.pan').hide();
		 $('select[name="bulk_primary_address[]').addClass('bulk_primary_address');
$('select[name="bulk_primary_address[]').change(function(e)
{

var index = $(this).closest('tr').index();
check_primary_address(index);
//alert(sel);

});
        $(".bulk_gst_number").keyup(function(){
		this.value=this.value.toUpperCase();
		
		});
                
                 $(".add_row3").relCopy();
    $('.add_row3').click(function() {
        changeclassfields1();
    });

	var siteno="<?php echo $row->subcontract_number; ?>";
$('.add_row').click(function() {
	var rowCount = $('.suppliersite_table tbody tr').length;
	var site_count = $('.site_count').val();
	var concat ='/SS';
	var site_number =siteno + concat + decimal_num((Number(site_count) + Number(rowCount)),3);
	var index =Number(rowCount) -1;
			<?php	if($row->subcontract_supplier_id!='') { ?>
	$('.bulk_subcontract_site_number'+index).val(site_number);
				<?php  } else { ?>
				$('.bulk_subcontract_site_number'+index).val();
				<?php } ?>
                    var data ="{{\Session::get('j_date_format')}}";
                    var form = $('#subcontractform');
                    form.parsley().destroy();
                 changeClassfields();   
                    
        });

$(".add_row").relCopy();		


		  function decimal_num(str, max) {
		  str = str.toString();
		  return str.length < max ? decimal_num("0" + str, max) : str;
		}
$('.add_row').click(function() {
			// changeclassfields();
               changeClassfields();

});
		$(document).on('keypress','.contact_number,.bulk_pincode,#before_days,#before_dis,#after_days,#after_int', function(ev){
			var regex = new RegExp("^[0-9.]+$");
					var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
					if (regex.test(str)) {
						return true;
					}
					ev.preventDefault();
					return false;
		});

/* validation for letters only*/
/*copy paste validation*/
$('.contact_number,.bulk_pincode').bind("cut copy paste", function(e) {
        e.preventDefault();
            });
/*copy paste validation*/


$('.bulk_contact_person').keypress(function (e) {
        var regex = new RegExp("^[a-zA-Z\s]+$");
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (regex.test(str)) {
            return true;
        }
        else {
            e.preventDefault();
            return false;
        }
    });


/*end*/
	
	<?php  if(isset($used_some)) { ?>
	
	$('.subcontract_name').css('pointer-events','none');
       $('.bulk_subcontract_site_name,.bulk_address,.sel2,.bulk_country,.bulk_state,.bulk_city,.bulk_pincode,.bulk_gst_number,.bulk_primary_address,.bulk_active').css('pointer-events','none');
	<?php } ?>	
		

    $('.contactdetail').click(function(){
        $('#contactModal').modal('show');
        $('#contactModal').width("70%").css('margin','auto');
        var index=$(this).closest('tr').index();
        $('.conindex').val(index);
    });
 $('#contactModal').on('shown.bs.modal', function () {   
        var index = $(this).closest('tr').index();
        var index=$('.conindex').val();
        var co_name = $('.bulk_contact_person'+index).val();
        var co_num = $('.bulk_contact_number'+index).val();
        var co_mail = $('.bulk_contact_mail'+index).val();
        
        if(co_name !=""){
            var c_n = co_name.split(',');
            var cnam_l = c_n.length;
            var index = $(this).closest('tr').index();
            var rowCount = $('.contact_table tbody tr').length;
            if (rowCount > 1)
            {
                $(".contact_table tbody").find("tr:gt(0)").remove();
            }
            for(var j=1; j<cnam_l;j++ ){
                $('.add_row3').trigger('click');
            }
            $('.contact_name').each(function(a){
                if(c_n[a] != 0 ){
                    $(this).val(c_n[a]);
                }else{
                    $(this).val('');
                }
            });             
        }
        if(co_num !=""){
            var c_no = co_num.split(',');
            var cnum_l = c_no.length;
            $('.contact_number').each(function(d){
                if(c_no[d] != 0 ){
                    $(this).val(c_no[d]);
                }else{
                    $(this).val('');
                }
            });
        }
        
        if(co_mail !=""){
            var c_ma = co_mail.split(',');
            
            var cm_l = c_ma.length;
            $('.contact_mail').each(function(h){
                if(c_ma[h] != 0 ){
                    $(this).val(c_ma[h]);
                }else{
                    $(this).val('');
                }
            });
        }
    });

    $('#addbox').click(function(){
        var add=0;
        var con_name=[];
        var con_number=[];
        var con_mail=[];
        var index=$('.conindex').val();
        $('.contact_name').each(function(k,v){
            var val=$(this).val();
            if(($(this).val()) !=''){
                var name = $(this).val();
            }else{
               var name = 0;
            }
            add=add+val;
            con_name[k]=name;
        });
        $('.contact_number').each(function(k,v){
            var val=$(this).val();
            if(($(this).val()) !=''){
                var c_number = $(this).val();
            }else{
               var c_number = 0;
            }
            add=add+val;
            con_number[k]=c_number;
        });
        $('.contact_mail').each(function(k,v){
            var val=$(this).val();
            if(($(this).val()) !=''){
                var c_mail = $(this).val();
            }else{
               var c_mail = 0;
            }
            add=add+val;
            con_mail[k]=c_mail;
        });
        $('.bulk_contact_person'+index).val(con_name);
        $('.bulk_contact_number'+index).val(con_number);
        $('.bulk_contact_mail'+index).val(con_mail);
        $('.contact_name').val('');
        $('.contact_number').val('');
        $('.contact_mail').val('');
        $('#contactModal').modal('hide');
    
    });


		var show_div = '<?php echo $show_div; ?>';

		if(show_div == 1)
			$('#panel_add').trigger('click');

		 $('#savestatus').val('');
/*Save Function*/
    $(document).on('click','.saveform',function()
    {
            var btnval		= $(this).val();


            if(btnval == 'APPLYCHANGES')
                var savestatus = 'APPLY CHANGES';
            else if(btnval == 'DRAFT')
                var savestatus = 'DRAFT';
            else if(btnval == 'SAVE' || btnval == 'SAVENEW')
                var savestatus = 'SAVE';

            $('#savestatus').val(savestatus);

            var url		= "{{ URL::to('subcontractsave') }}";
            var red_url		="{{ URL::to('subcontractsupplier') }}";
            var create_url	="{{ URL::to('subcontractcreate') }}/0";
            validationrule('subcontractform');
            var formdata	= $('#subcontractform').serialize();
            


            if(btnval != "APPLYCHANGES")
            {

               var form = $('#subcontractform');
                form.parsley().validate();

                if (form.parsley().isValid())
                {
                    $('.ajaxLoading').show();
                    $.post(url,formdata,function(data)
                    {
                        var status      = data.status;
						var msg			=data.message;
                        var id          = data.id;
						
                        var edit_url	= "{{ URL::to('subcontractcreate') }}/"+id;

                        if(btnval !='SAVE' && btnval !='DRAFT')
                        {

                            notyMsg(status,msg);
                            setTimeout(function(){
                            window.location.href=create_url;
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
                  $('.ajaxLoading').show();

                   // alert(url)
                    var status = data.status;
                    var msg    = data.message;
                    var id     = data.id;
                    var edit_url	="{{ URL::to('subcontractedit') }}/"+id;
                            notyMsg(status,msg);
                            setTimeout(function(){
                           window.location.href=edit_url;
                            }, 1500);

                });
            }

    });

/*End*/
 
 $('.over_due_payment').hide();
 $('.overdue_cal').hide();
  if($('.overdue:checked').val()=="Yes"){
    $('.over_due_payment').show();
    $('.overdue_cal').show();
  }



		  //$(".tds_per").hide();

    $(".tds_applicable").change(function(){
        var tds = $(".tds_applicable option:selected").val();
        if(tds == "YES" ){
            $(".tds_per").show();
        }else{
            $(".tds_per").hide();
        }
    });
  $(".tcs_per").hide();

    $(".tcs_applicable").change(function(){
        var tcs = $(".tcs_applicable option:selected").val();
        if(tcs == "YES" ){
            $(".tcs_per").show();
        }else{
            $(".tcs_per").hide();
        }
    });
		 $(".customer").hide();
        
    $(".customer_name").click(function(){
        var val = $('.customer_name:checked').val();
        if(val == "Yes" ){
            $(".customer").show();
            
        } else {
            
            $(".customer").hide();
            
        }
    });
<?php if($row->customer_name=='Yes'){ ?>
       
            $(".customer").show();
      <?php  } else{ ?>
            $(".customer").hide();
        
        <?php } ?>


var company = '<?php echo Session::get('companyid'); ?>' ;
var company1="company_id="+company;

$(".jcr_discount_hdr").click(function(){
        $(".ar_discount_hdr_id").jCombo("{{ URL::to('jcomboform?table=m_discounts_hdr_t:ar_discount_hdr_id:default_discount_amount') }}&order_by=default_discount_amount asc",
        {selected_value:""});


    });
$(".jcr_account_structure_id").click(function(){
        $(".account_structure_id").jCombo("{{ URL::to('jcomboform?table=f_account_structure_t:f_account_structure_id:concatenated_segments') }}&order_by=concatenated_segments asc",
        {selected_value:""});
    });    
    

$(".jcr_tds_account_id").click(function(){
        $(".tds_account_id").jCombo("{{ URL::to('jcomboform?table=f_account_structure_t:f_account_structure_id:concatenated_segments') }}&order_by=concatenated_segments asc",
        {selected_value:""});
    });    
      $(".jcr_frieghtcarriers").click(function(){
        $(".ar_frieghtcarriers_hdr_id").jCombo("{{ URL::to('jcomboform?table=m_frieghtcarriers_hdr_t:ar_frieghtcarriers_hdr_id:carrier_name') }}&order_by=carrier_name asc",
        {selected_value:""});

    });


	$(document).on('click', '.jcr_default_pricelist_id', function () {
            $(".default_pricelist_id").jCombo("{{ URL::to('jcomboform?table=i_pricelist_hdr_t:pricelist_hdr_id:pricelist_name') }}&order_by=pricelist_name asc",
            {selected_value: ""});
        });


        $(document).on('click','.jcr_supplier_type_id',function(){
            $(".supplier_type_id").jCombo("{{ URL::to('jcomboform?table=m_suppliertypes_t:suppliertype_id:suppliertype_name') }}&order_by=suppliertype_name asc",
            {selected_value:""});
        });

        $(document).on('click','.jcr_default_payment_terms_id',function(){
            $(".default_payment_terms_id").jCombo("{{ URL::to('jcomboform?table=m_payment_terms_t:payment_term_id:payment_term_name') }}&order_by=payment_term_name asc &parent="+company1,
            {selected_value:""});
        });
        $(document).on('click','.jcr_insurance_term_id',function(){
            $(".insurance_term_id").jCombo("{{ URL::to('jcomboform?table=m_insurance_terms_t:insurance_term_id:insurance_term_name') }}&order_by=insurance_term_name asc &parent="+company1,
            {selected_value:""});
        });

         $(document).on('click','.jcr_default_payment_method_id',function(){
            $(".default_payment_method_id").jCombo("{{ URL::to('jcomboform?table=m_payment_methods_t:payment_method_id:payment_method_name') }}&order_by=payment_method_name asc &parent="+company1,
            {selected_value:""});
        });
		
		
$(document).on('click','.jcr_delivery_terms_id',function(){
            $(".delivery_terms_id").jCombo("{{ URL::to('jcomboform?table=m_delivery_terms_t:delivery_terms_id:delivery_term_name') }}&order_by=delivery_term_name asc &parent="+company1,
            {selected_value:""});
        });

  $(document).on('click','.jcr_default_bank_id',function(){
      $(".default_bank_id").jCombo("{{ URL::to('jcomboform?table=f_bank_account_hdr_t:bank_account_hdr_id:bank_name') }}&order_by=bank_name asc",
      {selected_value: ""});
    });


	$(document).on('change', '.bulk_country', function ()
     {
		var index=$(this).closest('tr').index();
		var country_id = $('.bulk_country'+index).val();
		
		$(".bulk_state"+index).jCombo("{{ URL::to('jcomboformlogin?table=m_states_t:state_id:state_name') }}&parent=country_id="+country_id+ '&order_by=state_name asc',
		{selected_value:"$value->bulk_state"});

			$(".bulk_city"+index).find('option').not(':first').remove();
	});
		
		
        /*KArthigaa purpose for PAn no Validation*/
$('.pan_number').change(function(event){

 var regExp = /[a-zA-z]{5}\d{4}[a-zA-Z]{1}/; 
 var txtpan = $(this).val(); 
 if (txtpan.length == 10 ) { 
  if( txtpan.match(regExp) ){ 
   $('.pan').hide();
  }
  else {
 // notyMsg("error","Not a valid PAN number");
  $(".pan_number").val('');
	  $('.pan').show();
   event.preventDefault(); 
  } 
 } 
 else { 
     $(".pan_number").val('');
      $('.pan').show();
       event.preventDefault(); 
 } 

});

$('.pan_number').on('keyup',function(){
	this.value= this.value.toUpperCase();
	});   
        /*End*/
$(document).on('change','.supplier_type_id',function(){
    var typeid=$(".supplier_type_id option:selected").val();
    var url="{{URL::to('suppliertypegst')}}/"+typeid;
    $.get(url,function(data){
        if(data[0]['gst_required']=="No"){
            $('.bulk_gst_number').removeAttr('required');
            $('.pan_no').removeAttr('required');
         }
        else{
               $('.bulk_gst_number').attr('required',true);
               $('.pan_no').attr('required',true);
        }
    });
});
/*KArthigaa purpose for PAn no Validation*/
	$(document).on('change', '.bulk_state', function () {
          var state_id=$(this).val();
		//alert(state_id);
		var index=$(this).closest('tr').index();
                 var panno=$(".pan_number").val();
		var url_print='{{URL::to("gst_value")}}/'+state_id; 
			$.get(url_print,function(data)
		  {
			  $('.bulk_gst_number'+index).val($.trim(data)+panno);
			  
		  });
    
		var stateid = $('.bulk_state'+index).val();  
		if(stateid!= ""){
		$(".bulk_city"+index).jCombo("{{ URL::to('jcomboformlogin?table=m_cities_t:city_id:city_name') }}&parent=state_id="+stateid+ '&order_by=city_name asc',
		{selected_value:"$value->bulk_city"});
		}
	});
/*End*/

        $(".subcontract_name").keyup(function () {
            this.value = this.value.toUpperCase();
        });

$(document).on('keyup change','.bulk_gst_number',function(){
 
 var gst=$(this).val();
 var index=$(this).closest('tr').index();
 var ids=$('.bulk_subcontract_supplier_id'+index).val();
 var url_print='{{URL::to("gstduplicate")}}/'+gst; 

$.get(url_print,function(data){

if($.trim(data)==1){
  $('.bulk_gst_number'+index).val("");
notyMsgs("info","Already Gst Number is Registered ");  
        
        }
        else{
            
        }
    })
  });


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




		var save_type="{{$save_type}}";
        var copy_site="{{$copy_site}}";
        if(save_type=="apply_changes")
        {
           var close_ind=$(".copy_site_row").closest('tr').index();
           var copy_id=[];
           var strArr = copy_site.split(',');
           var intArr = [];
        for(i=0; i < strArr.length; i++)
        {
            intArr.push(parseInt(strArr[i]));
        }
        var i=0;
        while(i <= close_ind)
        {
        	var checkarr=$.inArray(i,intArr);
        	 if(checkarr != "-1")
              {
                var cp_i=parseInt(i)+1;
                copy_id.push(cp_i);
                $(".copy_site_row"+i).prop('checked',true);
                $('.copy_site_row'+i).attr("disabled", true);
              }
            else
            {
             	$(".copy_site_row"+i).prop('checked',false);
       	        $('.copy_site_row'+i).attr("disabled", false);
            }

        	i++;
        }
       $.each(copy_id,function(k,value){
        	$(".copy_site_row"+value).prop('checked',false).hide();
         });
}

$(document).on('click',".bulk_state",function (e)
{
	var state_id=$(this).val();
	
	
	
});


  $(document).on('click', '.removecon', function ()
    {
        var index = $(this).closest('tr').index();
        var rowCount = $('.contact_table tbody tr').length;
        if (rowCount > 1)
        {
            $(this).closest("tr").remove();
            removeClass1('contact_name');
        }else
        {
            notyMsg('info',"You Can't Delete Atleast One row should be there");
        }
    });
        $(document).on('click', '.remove', function ()
        {
            var index = $(this).closest('tr').index();
            var rowCount = $('.suppliersite_table tbody tr').length;
            if (rowCount > 1)
            {
                $(this).closest("tr").remove();
                removeClass('bulk_subcontract_supplier_id');
                removeClass('bulk_subcontract_supplier_id');
                removeClass('bulk_subcontract_site_number');
                removeClass('bulk_subcontract_site_name');
		removeClass('copy_site_row');
                removeClass('bulk_address');
                removeClass('bulk_country');
                removeClass('bulk_state');
                removeClass('bulk_city');
                removeClass('bulk_pincode');
                removeClass('bulk_contact_name');
                removeClass('bulk_contact_number');
                removeClass('bulk_contact_mail');
                removeClass('bulk_contact_person');
                removeClass('bulk_gst_number');
                removeClass('bulk_primary_address');


				var site_count = $('.site_count').val();
				$('.bulk_customer_site_number').each(function (index)
				{
					var concat ='/SS';
	//var site_number =siteno + concat + decimal_num((Number(site_count) + Number(rowCount)),3);
						var site_number = siteno + concat +(Number(site_count) + Number(index) + 1);
					var index =Number(rowCount) -1;
					$('.bulk_customer_site_number'+index).val(site_number);

				 });




            } else
            {
                notyMsg('info',"You Can't Delete Atleast One row should be there");
            }
        });


				changeClassfields();

        $(document).on('click','.add_row1', function() {
            var cloned = $('.discount_table').find('tr:eq(1)').clone();
            cloned.find("input[type=text],input[type=hidden], textarea,input[type=date],select").val("");
            cloned.appendTo('.discount_body');
            changeClassName('before_days');
            changeClassName('before_dis');
        });



        $(document).on('click', '.add_row2', function() {
            var cloned = $('.interest_table').find('tr:eq(1)').clone();
            cloned.find("input[type=text],input[type=hidden], textarea,input[type=date],select").val("");
            cloned.appendTo('.interest_body');
            changeClassName('after_days');
            changeClassName('after_int');
        });

        /****************Remove for Discount & interest ********/
        $(document).on('click', '.remove1', function ()
        {
            var index = $(this).closest('tr').index();
            var rowCount = $('.discount_table tbody tr').length;
            if (rowCount > 1)
            {
                $($(this).closest("tr")).remove();
                removeClass('before_days');
                removeClass('before_dis');
            } else
            {
                notyMsg('info',"You Can't Delete Atleast One row should be there");
            }
        });
        $(document).on('click', '.remove2', function () {
            var index = $(this).closest('tr').index();
            var rowCount = $('.interest_table tbody tr').length;
            if (rowCount > 1) {
                $($(this).closest("tr")).remove();
                removeClass('after_days');
                removeClass('after_int');
            } else {
                notyMsg('info',"You Can't Delete Atleast One row should be there");
            }
        });


        /********************* end ****************************/
		/* Raji purpose to customer convert as supplier*/
		
		$('.customer_id').change(function()
{
suppliername = $('.customer_id option:selected').text();
//alert(suppliername);
/*Raji purpose to get customer details*/
$('.subcontract_name').val(suppliername);

 var id= $('.customer_id option:selected').val();
	
	var url_print='{{URL::to("customerdetails")}}/'+id;
	$.get(url_print,function(data)
		  {
            var customer=data['hdr_data'][0].customer_name;
            var gst_no=data['hdr_data'][0].gst_no;
            var billing_address=data['hdr_data'][0].billing_address;
            var pricelist_id=data['hdr_data'][0].pricelist_id;
            var contact_person=data['hdr_data'][0].contact_person;
            var default_payment_terms_id=data['hdr_data'][0].default_payment_terms_id;
            var contact_number=data['hdr_data'][0].contact_number;
            var default_payment_method_id=data['hdr_data'][0].default_payment_method_id;
            var overdue=data['hdr_data'][0].overdue;
           
        
              $('.gst_no').val(gst_no);
              $('.billing_address').val(billing_address); 
              $('.contact_person').val(contact_person); 
              $('.contact_number').val(contact_number); 
              $('.overdue:checked').val(overdue); 
              $('.default_pricelist_id').select2('val',[pricelist_id]); 
              $('.default_payment_terms_id').select2('val',[default_payment_terms_id]); 
              $('.default_payment_method_id').select2('val',[default_payment_method_id]); 
             
        
        
	//	$("input[name=mygroup][value=" + value + "]").prop('checked', true);
$("input[name=overdue][value=" + overdue + "]").attr('checked', 'checked');
		
		
        
            $supplier_site_name=data['line_data'][0].customer_site_name;
            $supplier_site_no=data['line_data'][0].customer_site_number;
            $address=data['line_data'][0].address;
            $country_name=data['line_data'][0].country;
            $state_name=data['line_data'][0].state;
            $city_name=data['line_data'][0].city;
            $contact_name=data['line_data'][0].contact_name;
            $pincode=data['line_data'][0].pincode;
            $gstno=data['line_data'][0].gstno; 
            $contact_number=data['line_data'][0].contact_number;
		
       $before_days=data['over_line_data'][0].before_days;
       $before_dis=data['over_line_data'][0].before_dis;
       $after_days=data['over_line_data'][0].after_days;
       $after_int=data['over_line_data'][0].after_int;
       $calculation_id=data['over_line_data'][0].calculation_id;
          $('.bulk_subcontract_site_name').val($supplier_site_name);
              
          $('.bulk_subcontract_site_number').val('');    
            $('.bulk_address').val($address);   
            $('.bulk_country').val($country_name).select2();  
            $('.bulk_state').val($state_name).select2();
            $('.bulk_city').val($city_name).select2();  
            $('.bulk_pincode').val($pincode); 
            $('.bulk_contact_person').val($contact_name); 
            $('.bulk_contact_number').val($contact_number); 
            $('.bulk_gst_number').val($gstno); 
		
		$('#before_days').val($before_days);
		$('#before_dis').val($before_dis);
		$('#after_days').val($after_days);
		$('#after_int').val($after_int);
		$('#calculation_id').val($calculation_id).select2();
		

          })
            

});
		
		
	/*end*/	
		
$('.tds_applicable').trigger('change');	
$('.tcs_applicable').trigger('change');		

$('.supplier_type_id').trigger('change');		
		
		
    });


function changeClassfields()
{
        changeClassName('bulk_subcontract_supplier_id');
        changeClassName('bulk_subcontract_supplier_id');
        changeClassName('bulk_subcontract_site_number');
        changeClassName('bulk_subcontract_site_name');
        changeClassName('copy_site_row');
        changeClassName('bulk_address');
        changeClassName('bulk_country');
        changeClassName('bulk_state');
        changeClassName('bulk_city');
        changeClassName('bulk_pincode');
        changeClassName('bulk_contact_name');
        changeClassName('bulk_contact_number');
        changeClassName('bulk_contact_mail');
        changeClassName('bulk_contact_person');
        changeClassName('bulk_gst_number');
        changeClassName('bulk_primary_address');
    

}

    function changeClassName(className) {
            $('.' + className).each(function(index) {
                if (className == "bulk_line_no") {
                    $(this).val(index + 1).attr("readonly", 1);
                }

                $(this).removeClass(className + '0');
                $(this).addClass(className + index);
            });
        }
   function removeClass1(className)
    {
        var rowCount = $('.contact_table tbody tr').length;
        for (var i = 0; i <= rowCount; i++)
        {
            $('.contact_table tbody tr').find('.' + className).removeClass(className + i);
        }
        $('.' + className).each(function (index)
        {
            $(this).addClass(className + index);
        });

    }
        function changeClassName1(className)
    {
        $('.' + className).each(function (index) {
            $(this).removeClass(className + '0');
            $(this).addClass(className + index);
        });
    }
    function changeclassfields1()
{
    changeClassName1('contact_name');
}
    function removeClass(className)
    {
        var rowCount = $('.suppliersite_table tbody tr').length;
        for (var i = 0; i <= rowCount; i++)
        {
            $('.suppliersite_table tbody tr').find('.' + className).removeClass(className + i);
        }
        $('.' + className).each(function (index)
        {
            $(this).addClass(className + index);
        });

    }
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
