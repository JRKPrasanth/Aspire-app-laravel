@extends('layouts.header')
@section('content')
<style>
@media only screen and (min-width: 1500px) {
  .bulk_line_no{width: 50px !important;}
.bulk_product_code{width: 120px !important;}
.bulk_product_pack_id{width: 120px !important;}
.bulk_concatenated_product{width: 120px !important;}
.bulk_hsn_code{width: 120px !important;}
.bulk_tax_credit{width: 120px !important;}
.bulk_defalut_hsn_code{width: 120px !important;}
.bulk_account_code_id{width: 120px !important;}
.bulk_control_account_id{width: 120px !important;}
.bulk_disc_account_code{width: 120px !important;}
.bulk_primary_uom_id{width: 120px !important;}
.bulk_trx_uom_id{width: 120px !important;}
.bulk_subinventory_id{width: 120px !important;}
.bulk_locator_control{width: 120px !important;}
.bulk_sublocator_id{width: 120px !important;}

}




.bulk_line_no{width: 50px;}
.bulk_product_code{width: 120px;}
.bulk_product_pack_id{width: 120px;}
.bulk_concatenated_product{width: 120px;}
.bulk_hsn_code{width: 120px;}
.bulk_tax_credit{width: 120px;}
.bulk_defalut_hsn_code{width: 120px;}
.bulk_account_code_id{width: 120px;}
.bulk_control_account_id{width: 120px;}
.bulk_disc_account_code{width: 120px;}
.bulk_primary_uom_id{width: 120px;}
.bulk_trx_uom_id{width: 120px;}
.bulk_subinventory_id{width: 120px;}
.bulk_locator_control{width: 120px;}
.bulk_sublocator_id{width: 120px;}
.semi{
	display: none;
}




.select2-selection__rendered {
 
  font-size: 11px !important;
}




.select2-container--default .select2-selection--multiple{
  border:none !important ;
}
.select2-container .select2-selection--multiple {
    box-sizing: border-box !important ;
    cursor: pointer !important ;
    display: block !important ;
    min-height: 27px !important ;
    user-select: none !important;
    -webkit-user-select: none !important ;
}
.select2-container--default.select2-container--focus .select2-selection--multiple{
   border: none !important;

}
.select2-selection--multiple{
    overflow-y:auto !important;
    height: 25px !important;
}
input#choosefile {
    background: #9baff1;
    /* border-radius: 12px; */
    font-size: 11px;
    font-weight: 900;
    width: 14.4em;
    color: #fff;
    padding: 6px 1px;
    /* word-wrap: normal; */
}
#file_choosen{
  margin: 4px 0px;
  width: 10em;
  text-align: center;
    border: 0px solid #375a80;
}

#file_choosen>tbody{
    display: block;
    max-height: 120px;
    overflow-y:auto;

}

span.note,span.note>.files{
    font-size: 13px;
    font-family: sans-serif;

}
span.note img{
  width:20px;
  height: 16px;
}

.stdiv.accode{
    pointer-events: visible !important;
}
#sticky-header .row{
    pointer-events: visible !important;
}
.select2.select2-container.select2-container--default{
    width: 271px !important;
}
</style>


	<!--********************************************-->
<div class="ajaxLoading"></div>
<form action="" method="post" id="assetproductform" class="assetproductform" data-parsley-validate>
<input type="hidden" value="" name="savestatus" id="savestatus" />
<input type="hidden" value="" name="linescheck" id="linescheck">
<input type="hidden" name="asset_config_id" value="{{ $asset_config_id }}" id="asset_config_id" />
{{ csrf_field() }}

<h2 class="heads">ASSET PRODUCT
	<span class="ui_close_btn"><a class="collapse-close pull-right btn-danger" onclick="location.href = '{{URL::to('assetproductconfig')}}'"></a></span>
</h2>

<div class="card">
<div class="card-body card-block">
	
	    <div class="col-md-4">
		
		 <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4"><span style="color: red;  ">*</span>Product Name</label>
			<div class="col-md-8">
				<input type="hidden" name="subassemblydata" class="subassemblydata" id="subassemblydata">
                                <select style="width:100%" name='product_id' tabindex="1" rows='5' class='form-control product_id select2' required>
                                    {!! $product_id !!}
                                </select>
			</div>
		</div>
			
		 <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4"><span style="color: red;  ">*</span>Asset Number </label>
			<div class="col-md-8">
                <input type="text" id="asset_number" name="asset_number" class="form-control asset_number" value="{{$assetproductdata['asset_number'] }}" style="width:100%;" required>
			</div>
		</div>

		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Brand Name </label>
			<div class="col-md-8">
				<select style="width:100%" name='brand_name' rows='5' class='form-control brand_name select2' >
				    {!! $brand_name !!}
				</select>
			</div>
		</div>
			
			<div class="form-group row pt">
			<label for="inputIsValid" class="form-control-label col-md-4"><span class="remove" style="color: red;  ">*</span>Quantity </label>
			<div class="col-md-8">
                <input type="text" id="qty" name="qty" class="form-control qty" value="{{$assetproductdata['qty'] }}" style="width:100%;" required>
			</div>
		</div>
	  	
	     <div class="fdivold">
		
		<div class="form-group row raw pc">
			<label for="inputIsValid" class="form-control-label col-md-4"><span class="remove" style="color: red;  ">*</span>UOM </label>
			<div class="col-md-8">
			<select style="width:100%" name='uom' rows='5' class='form-control uom select2' data-show-subtext="true" data-live-search="true" required>
				{!! $uom !!}
			</select>
			</div>
		</div>
		
	  </div>
	  <div class="form-group row pt">
			<label for="inputIsValid" class="form-control-label col-md-4"><span class="remove" style="color: red;  ">*</span>Life Period (in months) </label>
			<div class="col-md-8">
                <input type="text" id="life_period" name="life_period" class="form-control life_period" value="{{$assetproductdata['life_period'] }}" style="width:100%;" required>
			</div>
		</div>
		<div class="form-group row pt">
			<label for="inputIsValid" class="form-control-label col-md-4"><span class="remove" style="color: red;  ">*</span>PO Number </label>
			<div class="col-md-8">
                <input type="text" id="po_number" name="po_number" class="form-control po_number" value="{{$po_number }}" style="width:100%;" required>
			</div>
		</div>
		<div class="form-group row pt">
			<label for="inputIsValid" class="form-control-label col-md-4"><span class="remove" style="color: red;  ">*</span>Capacity/Range </label>
			<div class="col-md-8">
                <input type="text" id="capacity" name="capacity" class="form-control capacity" value="{{$capacity }}" style="width:100%;" required>
			</div>
		</div>
  	</div>
	    
	    <div class="stdivold">
		  <div class="col-md-4" >
			<div class="form-group row stdiv altname">
				<label for="inputIsValid" class="form-control-label col-md-4">Serial Number</label>
				<div class="col-md-8">
                    <input type="text" id="serial_number" tabindex="2" name="serial_number" class="form-control serial_number" value="{{$assetproductdata['serial_number'] }}">
				</div>
			</div>
			<div class="form-group row stdiv altname">
				<label for="inputIsValid" class="form-control-label col-md-4">Remarks</label>
				<div class="col-md-8">
                    <input type="text" id="remarks" tabindex="2" name="remarks" class="form-control remarks" value="{{$remarks }}">
				</div>
			</div>
        
			<div class="form-group row stdiv">
				<label for="inputIsValid" class="form-control-label col-md-4">Warranty From</label>
                                    <div class="col-md-8">
                                        <div class="input-group form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                            <input class="form-control datepicker warrenty_from" id="warrenty_from" name="warrenty_from" size="16" type="text" value="{{ $warrenty_from }}" readonly>
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
			</div>

			<div class="form-group row stdiv">
				<label for="inputIsValid" class="form-control-label col-md-4"><span style="color: red;  ">*</span>Warranty (in months)</label>
				<div class="col-md-8">
				    <input type="text" id="warrenty" tabindex="2" name="warrenty" class="form-control warrenty" value="{{$assetproductdata['warrenty'] }}">
				</div>
			</div>
			  

			<div class="form-group row stdiv semiedit">
				<label for="inputIsValid" class="form-control-label col-md-4"><span class="sfgreq" style="color: red;  ">*</span>Asset Type</label>
				<div class="col-md-8">
					<select style="width:100%" name='asset_type' rows='5' class='form-control asset_type select2' data-show-subtext="true" data-live-search="true" required>
				        {!! $asset_type !!}
			        </select>
				</div>
            </div>
            
            <div class="form-group row stdiv semiedit">
				<label for="inputIsValid" class="form-control-label col-md-4"><span class="sfgreq" style="color: red;  ">*</span>Asset Category</label>
				<div class="col-md-8">
					<select style="width:100%" name='asset_category' rows='5' class='form-control asset_category select2' id="asset_category" data-show-subtext="true" data-live-search="true" required>
				        {!! $asset_category !!}
			        </select>
				</div>
            </div>
			  
			  
			  <div class="form-group row stdiv semiedit">
				<label for="inputIsValid" class="form-control-label col-md-4"><span class="sfgreq" style="color: red;  ">*</span>Asset Status</label>
				<div class="col-md-8">
					<select type="text" name="asset_status" id="asset_status" class="form-control asset_status select2" readonly>
                             <option value="">-- Please Select --</option>
                             <option <?php if($asset_status=="ACTIVE" ) echo "selected"; ?> value="ACTIVE">ACTIVE</option>
                             <option <?php if($asset_status=="INACTIVE" ) echo "selected"; ?> value="INACTIVE">INACTIVE</option>
                    </select>
				</div>
            </div>
            <div class="form-group row stdiv semiedit">
				<label for="inputIsValid" class="form-control-label col-md-4"><span class="sfgreq" style="color: red;  ">*</span>Location</label>
				<div class="col-md-8">
					<select type="text" name="location" id="location" class="form-control location select2" readonly>
                             <option value="">-- Please Select --</option>
                             <option <?php if($location=="HO" ) echo "selected"; ?> value="HO">HO</option>
                             <option <?php if($location=="CO" ) echo "selected"; ?> value="CO">CO</option>
                             <option <?php if($location=="MR" ) echo "selected"; ?> value="MR">MR</option>
                             <option <?php if($location=="HO First Floor" ) echo "selected"; ?> value="HO First Floor">HO First Floor</option>
                             <option <?php if($location=="HO Second Floor" ) echo "selected"; ?> value="HO Second Floor">HO Second Floor</option>
                             <option <?php if($location=="HO Third Floor" ) echo "selected"; ?> value="HO Third Floor">HO Third Floor</option>
                             <option <?php if($location=="Factory OB First Floor" ) echo "selected"; ?> value="Factory OB First Floor">Factory OB First Floor</option>
                             <option <?php if($location=="Factory OB Second Floor" ) echo "selected"; ?> value="Factory OB Second Floor">Factory OB Second Floor</option>
                             <option <?php if($location=="Factory OB Third Floor" ) echo "selected"; ?> value="Factory OB Third Floor">Factory OB Third Floor</option>
                             <option <?php if($location=="Factory RB First Floor" ) echo "selected"; ?> value="Factory RB First Floor">Factory RB First Floor</option>
                             <option <?php if($location=="Factory RB Second Floor" ) echo "selected"; ?> value="Factory RB Second Floor">Factory RB Second Floor</option>
                             <option <?php if($location=="Factory RB Third Floor" ) echo "selected"; ?> value="Factory RB Third Floor">Factory RB Third Floor</option>


                    </select>
				</div>
            </div>
			  
		</div>

		  <div class="col-md-4">

			<div class="form-group row stdiv semiedit">
				<label for="inputIsValid" class="form-control-label col-md-4">Department</label>
				<div class="col-md-8">
				<select style="width:100%" name='department' rows='5' class='form-control department select2' data-show-subtext="true" data-live-search="true" required>
				        {!! $department !!}
			    </select>
				</div>
			</div>
			<div class="form-group row stdiv semiedit">
				<label for="inputIsValid" class="form-control-label col-md-4">Area</label>
				<div class="col-md-8">
				<select style="width:100%" name='area' rows='5' class='form-control area select2' data-show-subtext="true" data-live-search="true" required>
				        {!! $area !!}
			    </select>
				</div>
			</div>
            <div class="form-group row stdiv semiedit">
				<label for="inputIsValid" class="form-control-label col-md-4">Purchase Date</label>
                                    <div class="col-md-8">
                                        <div class="input-group form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                            <input class="form-control datepicker purchase_date" id="purchase_date" name="purchase_date" size="16" type="text" value="{{ $purchase_date }}" readonly>
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
			</div>
			  <div class="form-group row stdiv semiedit">
				<label for="inputIsValid" class="form-control-label col-md-4">Supplier Name</label>
				<div class="col-md-8">
				<select style="width:100%" name='supplier_id' rows='5' class='form-control supplier_id select2' data-show-subtext="true" data-live-search="true" required>
				        {!! $supplier_id !!}
			    </select>
				</div>
			</div>
			<div class="form-group row stdiv semiedit">
				<label for="inputIsValid" class="form-control-label col-md-4">Installed ON</label>
                                    <div class="col-md-8">
                                        <div class="input-group form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                            <input class="form-control datepicker replace_date" id="replace_date" name="replace_date" size="16" type="text" value="{{ $replace_date }}" readonly>
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
			</div>

			<div class="form-group row stdiv semiedit">
				<label for="inputIsValid" class="form-control-label col-md-4">Assigned to</label>
				<div class="col-md-8">
                <select style="width:100%" name='assigned_to' rows='5' class='form-control assigned_to select2' data-show-subtext="true" data-live-search="true" required>
				        {!! $assigned_to !!}
			    </select>
				</div>
			</div>
			
			<div class="form-group row pt">
			<label for="inputIsValid" class="form-control-label col-md-4"><span class="remove" style="color: red;  ">*</span>PO Invoice Number </label>
			<div class="col-md-8">
                <input type="text" id="po_invoice_number" name="po_invoice_number" class="form-control po_invoice_number" value="{{$po_invoice_number }}" style="width:100%;" required>
			</div>
		</div>
		</div>
	</div>	     
<br>
         
<div class="row ast_cat">
    <div class="col-md-4">
        
        <div class="form-group row stdiv semiedit">
				<label for="inputIsValid" class="form-control-label col-md-4">Workgroup</label>
				<div class="col-md-8">
					<select type="text" name="work_group" id="work_group" class="form-control work_group select2" readonly>
                             <option value="">-- Please Select --</option>
                             <option <?php if($work_group=="WORKGROUP" ) echo "selected"; ?> value="WORKGROUP">WORKGROUP</option>
                             <option <?php if($work_group=="DOMAIN" ) echo "selected"; ?> value="DOMAIN">DOMAIN</option>
                    </select>
				</div>
            </div>
            
            <div class="form-group row stdiv">
				<label for="inputIsValid" class="form-control-label col-md-4">Windows Key (OEM Licence)</label>
				<div class="col-md-8">
				    <input type="text" id="windows_key" tabindex="2" name="windows_key" class="form-control windows_key" value="{{$assetproductdata['windows_key'] }}">
				</div>
			</div>
            
            <div class="form-group row stdiv">
				<label for="inputIsValid" class="form-control-label col-md-4">RAM Size</label>
				<div class="col-md-8">
				    <input type="text" id="ram_size" tabindex="2" name="ram_size" class="form-control ram_size" value="{{$assetproductdata['ram_size'] }}">
				</div>
			</div>
			
			<div class="form-group row stdiv">
				<label for="inputIsValid" class="form-control-label col-md-4">Anti-Virus</label>
				<div class="col-md-8">
				    <input type="text" id="anti_virus" tabindex="2" name="anti_virus" class="form-control anti_virus" value="{{$assetproductdata['anti_virus'] }}">
				</div>
			</div>
			
			<div class="form-group row stdiv">
				<label for="inputIsValid" class="form-control-label col-md-4">MS Office Key</label>
				<div class="col-md-8">
				    <input type="text" id="ms_office_key" tabindex="2" name="ms_office_key" class="form-control ms_office_key" value="{{$assetproductdata['ms_office_key'] }}">
				</div>
			</div>
            
            <div class="form-group row stdiv">
				<label for="inputIsValid" class="form-control-label col-md-4">Key Board</label>
				<div class="col-md-8">
				    <input type="text" id="keyboard" tabindex="2" name="keyboard" class="form-control keyboard" value="{{$assetproductdata['keyboard'] }}">
				</div>
			</div>
        
    </div>
    <div class="col-md-4">
        
        <div class="form-group row stdiv">
				<label for="inputIsValid" class="form-control-label col-md-4">System Name</label>
				<div class="col-md-8">
				    <input type="text" id="system_name" tabindex="2" name="system_name" class="form-control system_name" value="{{$assetproductdata['system_name'] }}">
				</div>
			</div>
			
			<div class="form-group row stdiv">
				<label for="inputIsValid" class="form-control-label col-md-4">Processor Name</label>
				<div class="col-md-8">
				    <input type="text" id="processor_name" tabindex="2" name="processor_name" class="form-control processor_name" value="{{$assetproductdata['processor_name'] }}">
				</div>
			</div>
			
			<div class="form-group row stdiv">
				<label for="inputIsValid" class="form-control-label col-md-4">Printer Name</label>
				<div class="col-md-8">
				    <input type="text" id="printer_name" tabindex="2" name="printer_name" class="form-control printer_name" value="{{$assetproductdata['printer_name'] }}">
				</div>
			</div>
			
			<div class="form-group row stdiv">
				<label for="inputIsValid" class="form-control-label col-md-4">IP Address</label>
				<div class="col-md-8">
				    <input type="text" id="ip_address" tabindex="2" name="ip_address" class="form-control ip_address" value="{{$assetproductdata['ip_address'] }}">
				</div>
			</div>
			
			<div class="form-group row stdiv">
				<label for="inputIsValid" class="form-control-label col-md-4">Additional Software</label>
				<div class="col-md-8">
				    <textarea id="add_software" tabindex="2" rows='5' name="add_software" class="form-control add_software" style="height:50px !important;">{{$assetproductdata['add_software'] }}</textarea>
				</div>
			</div>
            
            <div class="form-group row stdiv">
				<label for="inputIsValid" class="form-control-label col-md-4">Network Type</label>
				<div class="col-md-8">
				    <input type="text" id="network_type" tabindex="2" name="network_type" class="form-control network_type" value="{{$assetproductdata['network_type'] }}">
				</div>
			</div>
    </div>
    <div class="col-md-4">
        
        <div class="form-group row stdiv">
				<label for="inputIsValid" class="form-control-label col-md-4">Operating System</label>
				<div class="col-md-8">
				    <input type="text" id="operating_system" tabindex="2" name="operating_system" class="form-control operating_system" value="{{$assetproductdata['operating_system'] }}">
				</div>
			</div>
			
			<div class="form-group row stdiv">
				<label for="inputIsValid" class="form-control-label col-md-4">HDD Size</label>
				<div class="col-md-8">
				    <input type="text" id="hdd_size" tabindex="2" name="hdd_size" class="form-control hdd_size" value="{{$assetproductdata['hdd_size'] }}">
				</div>
			</div>
			
			<div class="form-group row stdiv">
				<label for="inputIsValid" class="form-control-label col-md-4">Monitor</label>
				<div class="col-md-8">
				    <input type="text" id="monitor" tabindex="2" name="monitor" class="form-control monitor" value="{{$assetproductdata['monitor'] }}">
				</div>
			</div>
			
			<div class="form-group row stdiv">
				<label for="inputIsValid" class="form-control-label col-md-4">MS Office</label>
				<div class="col-md-8">
				    <input type="text" id="ms_office" tabindex="2" name="ms_office" class="form-control ms_office" value="{{$assetproductdata['ms_office'] }}">
				</div>
			</div>
			
			<div class="form-group row stdiv">
				<label for="inputIsValid" class="form-control-label col-md-4">Mouse</label>
				<div class="col-md-8">
				    <input type="text" id="mouse" tabindex="2" name="mouse" class="form-control mouse" value="{{$assetproductdata['mouse'] }}">
				</div>
			</div>
            
            <div class="form-group row stdiv semiedit">
				<label for="inputIsValid" class="form-control-label col-md-4">CD/DVD Drive</label>
				<div class="col-md-8">
					<select type="text" name="cd_dvd_drive" id="cd_dvd_drive" class="form-control cd_dvd_drive select2" readonly>
                             <option value="">-- Please Select --</option>
                             <option <?php if($cd_dvd_drive=="YES" ) echo "selected"; ?> value="YES">YES</option>
                             <option <?php if($cd_dvd_drive=="NO" ) echo "selected"; ?> value="NO">NO</option>
                    </select>
				</div>
            </div>
    </div>
</div>	
	
	
	
	
	
	
	    <!--end -->
	
	
			<div class="row butt">
			<div class="col-lg-12 col-md-12">
				<div class="form-group text-center">

					<input type="hidden" name="submit_type" class="submit_type" id="submit_type">
					 <?php  if($return_url=="assetproductconfigcreate") {?>
					<button type="button" id="save" class="btn save saveform" value="SAVE">Save</button>
					 <a class='btn cancel' onclick="location.href = '{{URL::to('assetproductconfig')}}'">Cancel</a>
					 <?php } else {?>
					 
					  <button type="button" name="submit" class="btn save saveform  approve" value="APPROVED">Approve</button>
                        <button type="button" name="submit" class="btn save saveform reject" value="REJECT">Reject</button>
     					 <a class='btn cancel' onclick="location.href = '{{URL::to('assetproductconfig')}}'">Cancel</a>

					 <?php }?>

				</div>
			</div>
		</div>

</div>

	
</div>
	
		
  
	</form>




	<script>

	$(document).ready(function(){

    
   $(document).on('keypress','#qty,#warrenty,#life_period', function(ev){
			var regex = new RegExp("^[0-9.]+$");
					var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
					if (regex.test(str)) {
						return true;
					}
					ev.preventDefault();
					return false;
		});

 <?php  if($return_url!="assetproductconfigcreate") {?>
     $('.row').css('pointer-events','none');     
       $('.butt').css('pointer-events','auto');


 <?php }?>
		      

  	$('.asset_number').on('keyup',function(){
    	this.value= this.value.toUpperCase();
    });

                
		var organization = '<?php echo Session::get('organization'); ?>' ;
                $('.organization_id').val(organization).change();


	
$(document).on('click','.saveform',function() {
   
                        var btnval    = $(this).val();
      if(btnval == 'APPLYCHANGES')
      {
                var savestatus = 'APPLY CHANGES';


      }
     
      else if(btnval == 'REJECT')
      {
        var savestatus = 'REJECTED';


      }   else if(btnval == 'APPROVED')
      {
        var savestatus = 'APPROVED';

      }else 
      {

        var savestatus = 'SAVE';

      }
                           
      $('#savestatus').val(savestatus);
      $('.submit_type').val("save");

      var url   = "{{ URL::to('assetproductconfigsave') }}";
      validationrule('assetproductform');
      var formdata  = $('#assetproductform').serialize();
      var form = $('#assetproductform');
            var red_url = "{{ URL::to('assetproductconfig') }}";

      var create_url= "{{ URL::to('assetproductconfig') }}";
          form.parsley().validate();
            var form = $('#assetproductform');
            form.parsley().validate();
    //  $('.ajaxLoading').show();
            var edit_url ='<?php echo $edit_url; ?>';

        
       var url    = "{{ URL::to('assetproductconfigsave') }}";
         var red_url = "{{ URL::to('assetproductconfig') }}"; 
         	if(btnval != 'APPLYCHANGES')
	{
	    	form.parsley().validate();
		var form = $('#assetproductform');
               
		form.parsley().validate();
		
		if (form.parsley().isValid())
		{
		 $('.ajaxLoading').show();
                 change_date();
                 var formdata	= $('#assetproductform').serialize();
		 var form_data = new FormData(document.getElementById('assetproductform'));   
          $.ajax({
                  url: url,
                  type: "POST",
                  data: form_data,
                  enctype: 'multipart/form-data',
                  processData: false,  // tell jQuery not to process the data
                  contentType: false,   // tell jQuery not to set contentType
                  async:true,
                  xhr: function(){
                      var xhr = $.ajaxSettings.xhr();
                    if (xhr.upload) {
                        xhr.upload.addEventListener('progress', function(event) {
                                var percent = 0;
                                var position = event.loaded || event.position;
                                var total = event.total;
                                if (event.lengthComputable) {
                                        percent = Math.ceil(position / total * 100);
                                }
                                        //update progressbar

                                }, true);
                        }
          return xhr;

                }
                }).done(function(data)
		{
			var status  = data.status;
				var msg     = '<span style="color:#090065"></span>  '+data.message;
				var id      = data.id;
				var auto_no = data.auto_no;
            if(btnval !='SAVE' && btnval !='APPROVED'&& btnval !='REJECT'&& btnval !='Canceled')
           {
                   notyMsg(status,msg);
                   $('.ajaxLoading').hide();
                   window.location.href=create_url;
           } else if(btnval =="APPROVED" || btnval =="REJECT"){
                notyMsg(status,msg);
                   $('.ajaxLoading').hide();
                   			window.location.href="{{URL::to('assetproductconfig')}}";

           }
           else
           {
                   notyMsg(status,msg);
                   $('.ajaxLoading').hide();
                   window.location.href=red_url;
                   
           }
		});  
		}
	}
	else
	{
	  	
        }
});			   	


		
		/**********Up/down/left/right arrow navigation start*******/
		$('input,select').keyup(function (e) {
	        if (e.which == 39) { // right arrow
	          $(this).closest('td').next().find('input,select').focus();
	 
	        } else if (e.which == 37) { // left arrow
	          $(this).closest('td').prev().find('input,select').focus();
	 
	        } else if (e.which == 40) { // down arrow
	          $(this).closest('tr').next().find('td:eq(' + $(this).closest('td').index() + ')').find('input,select').focus();
	 
	        } else if (e.which == 38) { // up arrow
	          $(this).closest('tr').prev().find('td:eq(' + $(this).closest('td').index() + ')').find('input,select').focus();
	        }
            });
		/**********Up/down/left/right arrow navigation end *******/

function changeclassfields(){
changeClassName('bulk_product_id');
changeClassName('bulk_line_no');
changeClassName('bulk_product_code');
changeClassName('bulk_product_pack');
changeClassName('bulk_product_pack_id');
changeClassName('bulk_concatenated_product');
changeClassName('bulk_hsn_code');
changeClassName('bulk_tax_credit');
changeClassName('bulk_account_code_id');
changeClassName('bulk_control_account_id');
changeClassName('bulk_disc_account_code');
changeClassName('bulk_defalut_hsn_code');
changeClassName('bulk_primary_uom_id');
changeClassName('bulk_trx_uom_id');
changeClassName('bulk_subinventory_id');
changeClassName('bulk_sublocator_id');
changeClassName('bulk_locator_control');

	
changeClassName('bulk_min_order_qty');
changeClassName('bulk_min_stock_level2');
changeClassName('bulk_min_stock_level3');
changeClassName('bulk_max_order_qty');
changeClassName('bulk_re_order_level');

changeClassName('bulk_mpq_qty');
changeClassName('bulk_product_expiry_days');
changeClassName('bulk_packing_ctn_box');
changeClassName('addbtn');
changeClassName('bulk_adddetails');


}
function removeclassfields()
{
removeClass('bulk_product_id');
removeClass('bulk_line_no');
removeClass('bulk_product_code');
removeClass('bulk_product_pack');
removeClass('bulk_product_pack_id');
removeClass('bulk_concatenated_product');
removeClass('bulk_hsn_code');
removeClass('bulk_tax_credit');
removeClass('bulk_primary_uom_id');
removeClass('bulk_account_code_id');
removeClass('bulk_control_account_id');
removeClass('bulk_disc_account_code');
removeClass('bulk_trx_uom_id');
removeClass('bulk_subinventory_id');
removeClass('bulk_sublocator_id');
removeClass('bulk_locator_control');

	
removeClass('bulk_min_order_qty');
removeClass('bulk_min_stock_level2');
removeClass('bulk_min_stock_level3');
removeClass('bulk_max_order_qty');
removeClass('bulk_re_order_level');

removeClass('bulk_defalut_hsn_code');
removeClass('bulk_mpq_qty');
removeClass('bulk_product_expiry_days');
removeClass('bulk_packing_ctn_box');
removeClass('addbtn');

}

	});

/*end*/
                    $('.ast_cat').hide();
		/*** based on leave mode date change start **/
                    $(document).on('change','#asset_category',function()
                    {

                            var ast_cat = $('#asset_category').select2('val');
                                if(ast_cat == 16 && ast_cat != '')
                            {
                                         $('.ast_cat').show();
                                     //    $('.od_start_date').attr('required',true);
                                     //    $('.od_end_date').attr('required',true);
                            }else{
                                $('.ast_cat').hide();
                            }        
                    });
    

	</script>
@include('layouts.php_js_validation')
@endsection
