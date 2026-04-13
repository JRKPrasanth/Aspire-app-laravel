@extends('layouts.header')
@section('content')
<?php error_reporting(0); if($row->source=='STANDARD' && $row->po_hdr_id=='' && !isset($copy_po_number))
{
    $head=" ( New )";
}
else if($row->po_hdr_id !='')
{
    $head=" ( ".$row->po_number." )";
}
else if(isset($copy_po_number))
{
    $head=" ( Copy From ".$copy_po_number." )";
}
else
{
    $head=" ( Convert From ".$row->source." )";
}
?>
 <?php include('tools_menu.php'); ?> <h3 class="heads">Purchase Order {{$head}}
<span class="ui_close_btn"><a class="collapse-close pull-right btn-danger" onclick='location.href="{{ url($return_url) }}"'></a></span>
</h3>


<?php //dd($this->data['pageMethod']); ?>


<style type="text/css">

    @media only screen and (min-width: 1500px) {

 .bulk_line_no {width: 100px !important;}
.bulk_product_id {width: 250px !important;}
.bulk_product_description {width: 150px !important;}
.bulk_uom_code_id {width: 100px !important;}
.bulk_qty {width: 100px !important;}
.bulk_received_qty {width: 100px !important;}
.bulk_unit_price {width: 100px !important;}
.bulk_discount_percentage {width: 100px !important;}
.bulk_discount_amount {width: 100px !important;}
.bulk_tax_group_id {width: 100px !important;}
.bulk_tax_amount {width: 100px !important;}
.bulk_hsn_code{width:100px !important;}
.bulk_part_no {width: 150px !important;}
.bulk_line_total {width: 100px !important;}
.bulk_promised_date {width: 100px !important;}
.bulk_comments {width: 180px !important;}
.bulk_promised_alternate_date  {width: 300px !important;}

    }



    .bulk_line_no{width: 100px;}
    .bulk_product_id{width: 150px;}
    .bulk_part_no {width: 150px ;}
    .bulk_product_description{width: 150px;}
    .bulk_uom_code_id{width: 120px;}
    .bulk_qty{width: 100px;}
    .bulk_unit_price {width: 100px;}
    .bulk_discount_percentage{width: 100px;}
    .bulk_discount_amount{width: 100px;}
    .bulk_tax_group_id{width: 120px;}
    .bulk_tax_amount{width: 100px;}
    .bulk_hsn_code{width:100px;}
    .bulk_line_total{width: 100px;}
    .bulk_promised_date{width: 100px;}
    .bulk_comments{width: 180px;}
    .bulk_promised_alternate_date{width:110px;}


</style>







<form method="post" action="" id="poamendment_form" class="poamendment_form" data-parsley-validate>
{{ csrf_field() }}
<div class="card">
<div class="card-header">
    <div class="col-md-12">
        
        <div class="col-md-3">
            <label class="align_left"> PO Date:<?php echo date(\Session::get('p_date_format'),strtotime($row->po_date));?></label></br><label class="align_left">PO Type:{{ $row->po_type }}   </label>
        </div>
        <div class="col-md-3">
            <!--<label class="align_left">Organization:<span class='org span_color'></span></label></br>-->
            <label class="align_left">Created By:<span class='create_by span_color'></span></label></br>
            <label class="align_left">  Source:<span class='source_span span_color'>{{$row->source}}</span></label>
        </div>
        <div class="col-md-3">
<label class="align_left">  PO Tax Total:<span class='tax_total_span span_color' id="tax_total_span">{{ $row->po_tax_total }}</span></label></br><label class="align_left">PO Grand Total:<span class='grand_total_span span_color' id="grand_total_span">{{ $row->po_grand_total }}</span>   </label>        
	
        </div>

        <div class="col-md-3">
        <label class="align_left">Reference No.:{{$row->reference_number}} <?php if($ids=='1'){?> <i class="fa fa-plus viewquote"></i><?php }?></label>
        </div>
       </div>

</div>



<div class="card-body card-block">


	<!------------------------------------- Body content start here ---------------------------->

				<div class="row">
                    <div class="row">



				<div class="col-md-12">
         <div class="col-md-4 form-group row" style="display:none;">
            <label for="inputIsValid" class="form-control-label col-md-4">PO Number</label>
            <div class="col-md-6">
                <input class="form-control po_hdr_id" id="po_hdr_id" name="po_hdr_id" size="16" type="hidden" value="{{ $row->po_hdr_id }}" readonly>
                <input type="text" id="po_number" name="po_number" class="form-control po_number" value="{{ $row->po_number }}" readonly>
            </div>
            <div class="col-md-2">

            </div>
    </div>

      <div class="col-md-4 form-group row">
        <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red;" >&#42;</span>Supplier Name</label>
        <div class="col-md-6 supplier_div">
            <select name='supplier_id' rows='5' class='form-control supplier_id select2' data-show-subtext="true" data-live-search="true" required>
                {!! $supplier_id !!}
            </select>
        </div>
        <div class="col-md-2 showinline ichide">
            <span class="showspan"><i class="fa fa-search suppliersearch"></i> </span>
            <span class="showspan"><i class="fa fa-refresh jcr_supplier_id"></i></span>
        </div>
    </div>
<div class="form-group col-md-4 ">
                        <label for="inputIsValid" class="form-control-label col-md-4">Supplier Site </label>
                        <div class="col-md-6 ssite_div">
                            <select name='suppliersite_id' rows='5' class='suppliersite_id select2'>
{!! $suppliersite_id !!}
                            </select>
                        </div>
                    <div class="col-md-2 showinline ichide">
                    <span class="showspan"><i class="fa fa-refresh jcr_suppliersite_id"></i></span>
                </div>
                    </div>
<div class="form-group col-md-4 row">
                                    <label for="inputIsValid" class="form-control-label col-md-6">Is this transaction applicable for reverse charge?</label>
                                   <div class="col-md-6">
                                        <div class="c-checkbox">
                                            <input type="checkbox" name="reverse_charge[]" value="1" <?php if($row->reverse_charge =="1") { echo "checked"; } else { echo ""; } ?> class="reverse_charge">
                                            <span class="check_mark"></span>
                                        </div>
                                  </div>
                              </div>
   <div class="col-md-4 form-group row">
        <label for="delivery_date" class="form-control-label col-md-4">Delivery Date</label>
        <div class="col-md-6 delivery_date_div">
            <input type='text' name="delivery_date" id="delivery_date" rows='5' class='form-control delivery_date datepicker' data-link-format="yyyy-mm-dd" value="{{$row->delivery_date}}">
        </div>
        <div class="col-md-2">
        </div>
    </div>
       <div class="col-md-4 form-group">  
        <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red;" >&#42;</span>Pricelist Term</label>
        <div class="col-md-6 pricelist_div">
            <select name='po_pricelist_id' rows='5' class='form-control po_pricelist_id select2' required>
                {!! $po_pricelist_id !!}
            </select>
        </div>
        <div class="col-md-2 showinline ichide">
            <span class="showspan"><i class="fa fa-refresh jcr_po_pricelist_id"></i>

			</span>
        </div>
    </div>                             
    <div class="form-group row" style="display:none;">
        <label for="inputIsValid" class="form-control-label col-md-4"> PO Date</label>
        <div class="col-md-6">
            <div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                <input class="form-control po_date datepicker" id="po_date" name="po_date" size="16" type="text" value="{{ $row->po_date }}" readonly>
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
            <input type="hidden" id="po_date" value="{{ $row->po_date }}" />
        </div>
        <div class="col-md-1 showline">
        </div>
    </div>

    <div class="form-group row" style="display:none;">
        <label for="inputIsValid" class="form-control-label col-md-4">PO Type</label>
        <div class="col-md-6">
            <select type="text" name="po_type" id="po_type" class="form-control po_type" readonly>
                <option value="">--select--</option>
                <option <?php if($row->po_type =="STANDARD") { echo "selected"; } else { echo ""; } ?> value="STANDARD">STANDARD</option>
                <option <?php if($row->po_type =="LABOUR") { echo "selected"; } else { echo ""; } ?> value="LABOUR">LABOUR</option>
            </select>
        </div>
        <div class="col-md-2">
        </div>
    </div>

    <div class="form-group row" style="display:none;">
        <label for="inputIsValid" class="form-control-label col-md-4">PO Status</label>
        <div class="col-md-6">
            <select type="text" name="po_status" id="po_status" class="form-control po_status" readonly>
                <option value="">--Please Select--</option>
                <option <?php if($row->po_status =="DRAFT") { echo "selected"; } else { echo ""; } ?> value="DRAFT">DRAFT</option>
                <option <?php if($row->po_status =="INITIATED") { echo "selected"; } else { echo ""; } ?> value="INITIATED">INITIATED</option>
                <option <?php if($row->po_status =="APPROVED") { echo "selected"; } else { echo ""; } ?> value="APPROVED">APPROVED</option>
                <option <?php if($row->po_status =="REJECTED") { echo "selected"; } else { echo ""; } ?> value="REJECTED">REJECTED</option>
                <option <?php if($row->po_status =="CANCELLED") { echo "selected"; } else { echo ""; } ?> value="CANCELLED">CANCELLED</option>
            </select>
        </div>
    </div>





    

    <div class="form-group row" style="display:none;">
        <label for="inputIsValid" class="form-control-label col-md-4">Organization Name</label>
        <div class="col-md-6">
            <select name='organization_id' rows='5' class='form-control organization_id'>
                {!! $organization_id !!}
            </select>
        </div>
        <div class="col-md-2 showinline">
        </div>
    </div>
    <div class="form-group row" style="display:none;">
        <label for="inputIsValid" class="form-control-label col-md-4">Created By</label>
        <div class="col-md-6">
            <select name='created_by' rows='5' class='form-control created_by' data-show-subtext="true" data-live-search="true">
                {!! $created_by !!}
            </select>
        </div>
        <div class="col-md-2 showline">
        </div>
    </div>


    <div class="form-group row" style="display:none;">
        <label for="inputIsValid" class="form-control-label col-md-4">Reference No</label>
        <div class="col-md-6">
            <input class="form-control reference_id" id="reference_id" name="reference_id" size="16" type="hidden" value="{{$row->reference_id}}" readonly>
            <input type="text" id="reference_number" name="reference_number" class="form-control reference_number" value="{{$row->reference_number}}" readonly>
        </div>
        <div class="col-md-2">
        </div>
    </div>
    <div class="form-group row" style="display:none;">
        <label for="inputIsValid" class="form-control-label col-md-4">Source</label>
        <div class="col-md-6">
            <select name='source' rows='5' class='form-control source' data-show-subtext="true" data-live-search="true" readonly>
                <option value="">--select--</option>
                <option <?php if($row->source =="STANDARD") { echo "selected"; } else { echo ""; } ?> value="STANDARD">STANDARD</option>
                <option <?php if($row->source =="REQUISITION") { echo "selected"; } else { echo ""; } ?> value="REQUISITION">REQUISITION</option>
                <option <?php if($row->source =="ENQUIRY") { echo "selected"; } else { echo ""; } ?> value="ENQUIRY">ENQUIRY</option>
                <option <?php if($row->source =="QUOTATION") { echo "selected"; } else { echo ""; } ?> value="QUOTATION">QUOTATION</option>

            </select>
        </div>
        <div class="col-md-2">
        </div>
    </div>
    <div class="form-group row" style="display:none;">
        <label for="inputIsValid" class="form-control-label col-md-4">PO Tax Total</label>
        <div class="col-md-6">
            <input type="text" name="po_tax_total" id="po_tax_total" value="{{ $row->po_tax_total }}" class="form-control po_tax_total" readonly>
        </div>
        <div class="col-md-2">
        </div>
    </div>
    <div class="form-group row" style="display:none;">
        <label for="inputIsValid" class="form-control-label col-md-4">PO Grand Total</label>
        <div class="col-md-6">
            <input type="text" name="po_grand_total" id="po_grand_total" value="{{ $row->po_grand_total }}" class="form-control po_grand_total" readonly>
        </div>
        <div class="col-md-2">
        </div>
    </div>

                                </div>
</div>

</div>
<div class="row">

				<div class="col-md-12">
                    <h5 class="myheaders">Additional Details</h5>



    <?php $i=0; $j=0; foreach($enabled_columns as $index=>$val) {   if($val->action=='1') $required="required"; else $required='';?>
        <?php if($i!=$j) { $j=$i;?>

    <?php } ?>
        <?php if($val->column_name=='payment_term_id' && $val->active==1) { $i++; ?>
            <div class="form-group col-md-4">
                <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red;" >*</span>Payment Term</label>
                <div class="col-md-6 payment_div">
                    <select name='payment_term_id' rows='5' <?php echo $required;?> class='form-control payment_term_id select2' data-show-subtext="true" data-live-search="true" required > {!! $payment_term_id !!}
                    </select>
                </div>
                <div class="col-md-2 showinline ichide">
                    <span class="showspan"><i class="fa fa-refresh jcr_payment_term_id"></i></span>
                </div>
            </div>
            <?php } ?>

 

           <?php if($val->column_name=='freight_terms_id' && $val->active==1) { $i++; ?>
            <div class="form-group col-md-4">
                <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red;" >*</span>Freight Term</label>
                <div class="col-md-6 payment_div">
                    <select name='freight_terms_id' rows='5' <?php echo $required;?> class='form-control freight_terms_id select2' data-show-subtext="true" data-live-search="true" required >{!! $freight_terms_id !!}
                    </select>
                </div>
                <div class="col-md-2 showinline ichide">
                    <span class="showspan"><i class="fa fa-refresh jcr_freight_terms_id"></i></span>
                </div>
            </div>
            <?php } ?>
          <?php if($val->column_name=='freight_carrier_id' && $val->active==1) { $i++; ?>
            <div class="form-group col-md-4">
                <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red;" >*</span>Freight Carriers</label>
                <div class="col-md-6 payment_div">
                    <select name='freight_carrier_id' rows='5' <?php echo $required;?> class='form-control freight_carrier_id select2' data-show-subtext="true" data-live-search="true" required >{!! $freight_carrier_id !!}
                    </select>
                </div>
                <div class="col-md-2 showinline ichide">
                    <span class="showspan"><i class="fa fa-refresh jcr_freight_carrier_id"></i></span>
                </div>
            </div>
            <?php } ?>
                <?php if($val->column_name=='delivery_terms_id' && $val->active==1) { $i++; ?>
                    <div class="form-group col-md-4">
                        <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red;" >*</span>Delivery Term</label>
                        <div class="col-md-6 delivery_div">
                            <select name='delivery_terms_id' rows='5' <?php echo $required;?> class='form-control delivery_terms_id select2' data-show-subtext="true" data-live-search="true" required > {!! $delivery_terms_id !!}
                            </select>
                        </div>
                        <div class="col-md-2 showinline ichide">
                            <span class="showspan"><i class="fa fa-refresh jcr_delivery_terms_id"></i></span>
                        </div>
                    </div>
                    <?php } ?>

                        <?php if($val->column_name=='project_id' && $val->active==1) { $i++; ?>
                            <div class="form-group col-md-4">
                                <label for="inputIsValid" class="form-control-label col-md-4">Project Name</label>
                                <div class="col-md-6 project_div">
                                    <select name='project_id' rows='5' <?php echo $required;?> class='form-control project_id select2' data-show-subtext="true" data-live-search="true" > {!! $project_id !!}
                                    </select>
                                </div>
                                <div class="col-md-2 showinline ichide">
                                    <span class="showspan"> <i class="fa fa-refresh jcr_project_id"></i></span>
                                </div>
                            </div>
                                     <?php } ?>
     <?php if($val->column_name=='supplier_reference_no' && $val->active==1) { $i++; ?>
                            <div class="form-group col-md-4">
                                <label for="inputIsValid" class="form-control-label col-md-4">Supplier Reference Number</label>
                                <div class="col-md-6 project_div">
                                    <input  name='supplier_reference_no' rows='5' type="text" id="supplier_reference_no" class="form-control supplier_reference_no" value="{{$row->supplier_reference_no}}">
                                </div>
                                <div class="col-md-2 showinline">
                                   
                                </div>
                            </div>
                                     <?php } ?>

   <?php if($val->column_name=='currency' && $val->active==1) { $i++; ?>
                            <div class="form-group col-md-4">
                                <label for="inputIsValid" class="form-control-label col-md-4">Currency</label>
                                <div class="col-md-6 project_div">
                                    <select name='currency' rows='5' <?php echo $required;?> class='form-control currency select2' data-show-subtext="true" data-live-search="true" > {!! $currency !!}
                                    </select>
                                </div>
                                <div class="col-md-2 showinline">
                                 
                                </div>
                            </div>
                                     <?php } ?>




                        <?php if($val->column_name=='transport_charges' && $val->active==1) { $i++; ?>
                            <div class="form-group col-md-4">
                                <label for="inputIsValid" class="form-control-label col-md-4">Transport Charges</label>
                                <div class="col-md-6 ">
                                    <input type="text" name="transport_charges" id="transport_charges" <?php echo $required;?> value="{{ $row->transport_charges }}" class="form-control transport_charges charges">
                                </div>
                                <div class="col-md-2 showinline">
                                </div>
                            </div>

                                     <?php } ?>
                        <?php if($val->column_name=='unloading_charges' && $val->active==1) { $i++; ?>
                            <div class="form-group col-md-4">
                                <label for="inputIsValid" class="form-control-label col-md-4">Unloading Charges</label>
                                <div class="col-md-6">
                                    <input type="text" name="unloading_charges" id="unloading_charges" <?php echo $required;?> value="{{ $row->unloading_charges }}" class="form-control unloading_charges charges">
                                </div>
                                <div class="col-md-2 showinline">
                                </div>
                            </div>

                             <?php } ?>
                        <?php if($val->column_name=='insurance_charges' && $val->active==1) { $i++; ?>
                            <div class="form-group col-md-4">
                                <label for="inputIsValid" class="form-control-label col-md-4">Insurance Charges</label>
                                <div class="col-md-6">
                                    <input type="text" name="insurance_charges" id="insurance_charges" <?php echo $required;?> value="{{ $row->insurance_charges }}" class="form-control insurance_charges charges">
                                </div>
                                <div class="col-md-2 showinline">
                                </div>
                            </div>

                      <?php } ?>
                       
                        <?php if($val->column_name=='packing_charges' && $val->active==1) { $i++; ?>
                            <div class="form-group col-md-4">
                                <label for="inputIsValid" class="form-control-label col-md-4">Packing Charges</label>
                                <div class="col-md-6">
                                    <input type="text" name="packing_charges" id="packing_charges" <?php echo $required;?> value="{{ $row->packing_charges }}" class="form-control packing_charges charges"
                                </div>
                                <div class="col-md-2 showinline">
                                </div>
                            </div>
                        </div>
         <?php } ?>
                        <?php if($val->column_name=='ship_to_location_id' && $val->active==1) { $i++; ?>
                            <div class="form-group col-md-4">
                                <label for="inputIsValid" class="form-control-label col-md-4">Ship To Location</label>
                                <div class="col-md-6 shiftoloc_div">
                                    <select name='ship_to_location_id' rows='5' <?php echo $required;?> class='form-control ship_to_location_id select2' data-show-subtext="true" data-live-search="true" > {!! $ship_to_location_id !!}
                                    </select>
                                </div>
                                 <div class="col-md-2 showinline ichide">
                            <span class="showspan"><i class="fa fa-refresh jcr_ship_to_location_id"></i></span>
                        </div>
                            </div>

     <?php } ?>
					<?php if($val->column_name=='default_payment_method_id' && $val->active==1) { $i++; ?>
                    <div class="form-group col-md-4">
                        <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red;" >*</span>Payment Method</label>
                        <div class="col-md-6 delivery_div">
                            <select name='default_payment_method_id' rows='5' <?php echo $required;?> class='form-control default_payment_method_id select2' data-show-subtext="true" data-live-search="true" required >

                            <option>Please Select</option>
                            {!! $default_payment_method_id !!}
                            </select>
                        </div>
                        <div class="col-md-2 showinline ichide">
                            <span class="showspan"><i class="fa fa-refresh jcr_default_payment_method_id"></i></span>
                        </div>
                    </div>
                    <?php } ?>
 <?php if($val->column_name=='insurance_term_id' && $val->active==1) { $i++; ?>
                            <div class="form-group col-md-4">
                                <label for="inputIsValid" class="form-control-label col-md-4">Insurance Term</label>
                                <div class="col-md-6">
                                 <select name='insurance_term_id' rows='5' <?php echo $required;?> class='form-control insurance_term_id select2' data-show-subtext="true" data-live-search="true" > {!! $insurance_term_id !!}
                                    </select>
                                </div>
                               <div class="col-md-2 showinline ichide">
                                    <span class="showspan"> <i class="fa fa-refresh jcr_insurance_term_id"></i></span>
                                </div>
                            </div>
     <?php } ?>


                        <?php if($val->column_name=='bill_to_location_id' && $val->active==1) { $i++; ?>
                            <div class="form-group col-md-4">
                                <label for="inputIsValid" class="form-control-label col-md-4">Bill To Location</label>
                                <div class="col-md-6 billtoloc_div">
                                 <select name='bill_to_location_id' rows='5' <?php echo $required;?> class='form-control bill_to_location_id select2' data-show-subtext="true" data-live-search="true" > {!! $bill_to_location_id !!}
                                    </select>
                                </div>
                                <div class="col-md-2 showinline ichide">
                            <span class="showspan"><i class="fa fa-refresh jcr_bill_to_location_id"></i></span>
                        </div>
                            </div>

         <?php } ?>
                        <?php if($val->column_name=='freight_amount' && $val->active==1) { $i++; ?>
                            <div class="form-group col-md-4">
                                <label for="inputIsValid" class="form-control-label col-md-4">Freight Amount</label>
                                <div class="col-md-6">
                                    <input type="text" name="freight_amount" id="freight_amount" <?php echo $required;?> value="{{ $row->freight_amount }}" class="form-control freight_amount charges"
                                </div>
                                <div class="col-md-2 showinline">
                                </div>
                            </div>
                            </div>
                            <?php } ?>

                             <?php if($val->column_name=='remarks' && $val->active==1) { $i++; ?>
                                    <div class="form-group col-md-4">
                                        <label for="inputIsValid" class="form-control-label col-md-4">Remarks</label>
                                        <div class="col-md-6"> <?php //dd($row->remarks); ?>
                                            <input type="text" name="remarks"  id="remarks" value="{{ $row->remarks }}" class="form-control remarks">
                                        </div>
                                        <div class="col-md-2">
                                        </div>
                                    </div>
    <?php }  }?>


</div>
</div>



<div class="row"  style="margin-top: 22px;">
<div class="col-md-12">

<a href="javascript:void(0);"  class="add_row additem" rel=".rcopy"><i class="fa fa-plus"></i> New Item</a>



 <div id="preview-area" class="chandru">
    <table class="overflow-y preview po_table">

<thead>
<tr>
    <th>Line No</th>
    <th class="pdtdiv" >Product</th>
      <th >&nbsp;</th>
      <th class="hidepart">Part Number</th>
    <th class="pdtdes_div" >Product Description</th>
    <th>Uom Code </th>
<th>Qty</th>
<?php if($ids=='3') { ?>
<th>Received Qty</th>
<?php } ?>
<th>Price</th>
<th>Discount(%)</th>
<th>Discount Amount</th>
<?php if($row->po_type=="STANDARD"){?>
<th> HSN Code </th>
<?php } else{?>
<th> SAC Code </th>
<?php }?>
<th>Tax Group</th>
<th>Tax Amount</th>
<th>Line Total</th>
<th>Promised Date</th>
<th>Promised Alternate Date</th>
<th>Comments</th>
<th></th>

</tr>
</thead>
<tbody class="po_lines_body">
<?php if(count($linedata)>=1) { ?>
@foreach($linedata as $key=>$value)

<tr class="rcopy clone" class="approve">
    <td>
        <input type="hidden" name="bulk_po_line_id[]" class="form-control input-sm bulk_po_line_id" value="{{ $value->po_line_id }}">
        <input type="hidden" class="form-control input-sm bulk_min_order" value="">
        <input type="hidden" class="form-control input-sm bulk_max_order" value="">
    </td>
    <td>
        <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="{{ $key + 1 }}" readonly="readonly">
    </td>
    <td class="pdtdiv">
        <select name="bulk_product_id[]" id="bulk_product_id" class="select2 bulk_product_id  parsley-validated" required="required">{!! $value->product_id !!}</select>
    </td>


  <td><i class="fa fa-search productsearch"></i></td>
  <td class="partno hidepart">
        <select name="bulk_part_no[]" class="select2 bulk_part_no ">{!! $value->part_no !!}</select>
    </td>
    <td class="pdtdes_div">
        <input type="text" name="bulk_product_description[]" class="form-control input-sm bulk_product_description" value="{{ $value->product_description }}">
    </td>
    <td class="uomdiv">
        <select name="bulk_uom_code_id[]" id="bulk_uom_code_id" class="select2 bulk_uom_code_id">
            {!! $value->uom_code_id !!}
        </select>
    </td>
    <td>
        <input type="text" name="bulk_qty[]" class="form-control input-sm bulk_qty " value="{{ $value->qty }}" required="required">
    </td>
    <?php if(ids=="3")  { ?>
        <td>
            <input type="text" class="form-control input-sm bulk_received_qty " value="{{$value->received_qty}}" required="required">
        </td>
        <?php }  ?>
            <td>
                <input type="text" name="bulk_unit_price[]" class="form-control input-sm bulk_unit_price " value="{{ $value->unit_price }}" required="required">
            </td>
            <td>
                <input type="text" name="bulk_discount_percentage[]" class="form-control input-sm bulk_discount_percentage " value="{{ $value->discount_percentage }}">
            </td>
            <td>
                <input type="text" name="bulk_discount_amount[]" class="form-control input-sm bulk_discount_amount " value="{{ $value->discount_amount }}">
            </td>
	 <td class="hsn">
            <select name="bulk_hsn_code[]" id="bulk_hsn_code" class="select2 bulk_hsn_code"  > {!! $value->hsn_code !!}</select>
        </td>
            <td class="taxgroup">
                <select name="bulk_tax_group_id[]" id="bulk_tax_group_id" class="select2 bulk_tax_group_id" required="required">
                    {!! $value->tax_group_id !!}
                </select>
            </td>
            <td>
                <input type="text" name="bulk_tax_amount[]" class="form-control input-sm bulk_tax_amount " value="{{ $value->tax_amount }}" required="required">
            </td>
            <td>
                <input type="text" name="bulk_line_total[]" class="form-control input-sm bulk_line_total " value="{{ $value->line_total }}" required="required">
            </td>
            <td>
                <input type="text" name="bulk_promised_date[]" class="form-control datepicker input-sm bulk_promised_date" value="{{ $value->promised_date }}">
            </td>
			<td>
                <input type="text" name="bulk_promised_alternate_date[]" class="form-control datepicker input-sm bulk_promised_alternate_date" value="{{ $value->promised_alternate_date }}">
            </td>
            <td>
                <input type="text" name="bulk_comments[]" class="form-control input-sm bulk_comments " value="{{ $value->comments }}">
            </td>

            <td>
                <a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
                <input type="hidden" name="counter[]">
            </td>
</tr>
@endforeach
<?php } if(count($linedata) < 1 ) { ?>
    <tr class="rcopy clone">
        <td>
            <input type="hidden" name="bulk_po_line_id[]" class="form-control input-sm bulk_po_line_id" value="">
			 <input type="hidden" class="form-control input-sm bulk_min_order" value="">
        <input type="hidden" class="form-control input-sm bulk_max_order" value="">
        </td>
        <td>
            <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="1" readonly="readonly">
        </td>
        <td class="pdtdiv">
            <select name="bulk_product_id[]" id="bulk_product_id" class="select2 bulk_product_id  parsley-validated" required="required"></select>
        </td>
        <td><i class="fa fa-search productsearch"></i></td>
       <td class="partno hidepart">
        <select name="bulk_part_no[]" class="select2 bulk_part_no ">{!! $part_no !!}</select>
    </td>
        <td class="pdtdes_div">
            <input type="text" name="bulk_product_description[]" class="form-control input-sm bulk_product_description " value="">
        </td>
        <td class="uomdiv">
            <select name="bulk_uom_code_id[]" id="bulk_uom_code_id" class="select2 bulk_uom_code_id">
                {!! $uom_code_id !!}
            </select>
        </td>
        <td>
            <input type="text" name="bulk_qty[]" class="form-control bulk_qty " value="" required="required">
        </td>
        <?php if(ids=="3")  { ?>
            <td>
                <input type="text" name="bulk_received_qty[]" class="form-control input-sm bulk_received_qty " value="" required="required">
            </td>
            <?php }  ?>
                <td>
                    <input type="text" name="bulk_unit_price[]" class="form-control input-sm bulk_unit_price " value="" required="required">
                </td>
                <td>
                    <input type="text" name="bulk_discount_percentage[]" class="form-control input-sm bulk_discount_percentage " value="">
                </td>
                <td>
                    <input type="text" name="bulk_discount_amount[]" class="form-control input-sm bulk_discount_amount " value="">
                </td>
 <td class="hsn">
            <select name="bulk_hsn_code[]" id="bulk_hsn_code" class="select2 bulk_hsn_code" ></select>
        </td>
                <td class="taxgroup">
                    <select name="bulk_tax_group_id[]" id="bulk_tax_group_id" class="select2 bulk_tax_group_id" required="required">
                        {!! $tax_group_id !!}
                    </select>
                </td>
                <td>
                    <input type="text" name="bulk_tax_amount[]" class="form-control input-sm bulk_tax_amount" value="">
                </td>
                <td>
                    <input type="text" name="bulk_line_total[]" class="form-control input-sm bulk_line_total" value="" required="required">
                </td>
                <td class="prodate">
                    <input type="text" name="bulk_promised_date[]" class="form-control datepicker input-sm bulk_promised_date" value="">
                </td>
				<td>
                <input type="text" name="bulk_promised_alternate_date[]" class="form-control datepicker input-sm bulk_promised_alternate_date" value="{{ $value->po_alternate_date }}">
            </td>
                <td>
                    <input type="text" name="bulk_comments[]" class="form-control input-sm bulk_comments" value="">
                </td>

                <td>
                    <a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
                    <input type="hidden" name="counter[]">
                </td>
    </tr>


<?php } ?>
</tbody>
</table>
<input type="hidden" name="enable-masterdetail" value="true">
</div>

</div>
</div>

<!-------------------------Linedata End-------------------------------->
<div class="row">
	<div class="col-lg-12 col-md-12">
		<input type="hidden" name="submit_type" class="submit_type" value="" />
		<div class="form-group text-center actionbtn">
                   <?php if($ids=="2")
                    { ?>
            <button name="apply" type="button" class="btn saveform applychanges" value="APPLYCHANGES">Apply Changes</button>
            <button type="button" class="btn save saveform" value="DRAFT">Draft</button>
            <button name="submit" type="button" class="btn save saveform" value="SAVENEW">Save and New</button>
            <button name="submit" type="button" class="btn save saveform" value="SAVE">Save</button>
             <?php }
              else if($ids=="4")
                    { ?>
            <button name="apply" type="button" class="btn saveform applychanges" value="APPLYCHANGES">Apply Changes</button>
            <button type="button" class="btn save saveform" value="DRAFT">Draft</button>
            <button name="submit" type="button" class="btn save saveform" value="SAVENEW">Save and New</button>
            <button name="submit" type="button" class="btn save saveform" value="SAVE">Save</button>
             <?php } else if ($ids=="3") { ?>
			          <button   type="button" class="btn save saveform" value="Canceled">Po Cancel</button>
            <?php } else if($ids=="1"){  ?>
                        <button   type="button" class="btn save saveform" value="Approved">Approve</button>
			<button   type="button" class="btn save saveform" value="Rejected">Rejected</button>
             <?php  } else {  ?>
                       <button name="apply" type="button" class="btn saveform applychanges" value="APPLYCHANGES">Apply Changes</button>
            <button type="button" class="btn save saveform" value="DRAFT">Draft</button>
            <button name="submit" type="button" class="btn save saveform" value="SAVENEW">Save and New</button>
            <button name="submit" type="button" class="btn save saveform" value="SAVE">Save</button>
                   <?php  } ?>
            <a class='btn cancel' onclick='location.href="{{ url($return_url) }}"'>Cancel</a>
		</div>
	</div>
</div>


</div>

</div>
</div>


<!-- karthigaa purpose supplier search jqgrid model-->
<div class="modal fade" id="supplierModal">
  <div class="modal-dialog" style="width:80%;">
    <div class="modal-content">
		<!--Moda Header-->
      <div class="modal-header">
		  <h4 class="modal-title"> Supplier Details </h4>
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
	  </div>
		<!-- Modal Body -->
	  <div class="modal-body">
	      <table id="suppliergrid"></table>
	  </div>
		 <!-- Modal footer -->
      <div class="modal-footer">
      </div>

    </div>
  </div>
</div>
<!--end-->

	<!-- karthigaa purpose Product search jqgrid model-->
<div class="modal fade" id="productModal">
  <div class="modal-dialog" style="width:80%;">
    <div class="modal-content">
		<!--Moda Header-->
      <div class="modal-header">
		  <h4 class="modal-title"> Product Details </h4>
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
	  </div>
		<!-- Modal Body -->
	  <div class="modal-body">
	      <table id="productgrid"></table>
	  </div>
		 <!-- Modal footer -->
      <div class="modal-footer">
      </div>

    </div>
  </div>
</div>

	<!--end-->
<input type="hidden" class="pdtindex" value="" />
<div id="preloader">
       <img src="https://jrlma.ca/wp-content/plugins/gallery-by-supsystic/src/GridGallery/Galleries/assets/img/loading.gif">
    </div>
  </div>
  </div>
  	</form>
	<script>


   <?php   if($ids == "1" || $ids == "3"){   ?>
$('input').attr('readonly', true);
$('select').attr('readonly', true);
$('select').css('pointer-events', 'none');
$('.delivery_div,.payment_div,.project_div,.supplier_div,.pricelist_div,.hsn,.partno').css('pointer-events','none');
//$('.fa').hide();
//$('.fa').hide();
 $('.ichide').hide();
$('.add_row,.remove').hide();
$('.productsearch').hide();
		$('.remarks').attr('readonly',false);
<?php } ?>
<?php if ($ids=="4"){ ?>

  $('.po_status').val('');
  <?php }?>
      
  <?php if ($ids=="5"){ ?>
      $('input').attr('readonly', true);
$('select').attr('readonly', true);
$('select').css('pointer-events', 'none');
      $('.delivery_div,.payment_div,.project_div,.supplier_div,.pricelist_div').css('pointer-events','none');
      $('.remarks,.bulk_qty,.bulk_unit_price,.bulk_discount_percentage,.partno').attr('readonly',false);
        <?php }?>
<?php if($row->po_type=="LABOUR") {?>	
    
$('.hidepart').hide();
<?php } else{ ?>
 $('.taxgroup,.uomdiv').css('pointer-events','none');
<?php }?>
$(document).ready(function(){
    <?php
      if($return_url=="purchasequtoetopo")
      { ?>
          $('.pricelist_div').css('pointer-events','none');

     <?php  }

         if($return_url=="poapproval")
         { ?>
           $('.ssite_div,.shiftoloc_div,.pdtdiv,.taxgroup,.prodate,.billtoloc_div,.delivery_date_div').css('pointer-events','none');

      <?php   }

         ?>

$('.viewquote').click(function(){
	var quoteid=$('.reference_id').val();
	if(quoteid!=""){
		var url="{{ URL::to('purchasequotationview')}}/"+quoteid;
	  window.open(url);
}
});



    /*Karthigaa Purpose For Default Organization & User*/
var organization = '<?php echo Session::get('organization'); ?>' ;
$('.organization_id').val(organization).change();
var user = '<?php echo Session::get('id'); ?>' ;
$('.created_by').val(user).change();
$(".create_by").html($('.created_by option:selected').text());
$('.org').html($('.organization_id option:selected').text());

	$('.bulk_tax_amount,.bulk_line_total').attr('readonly',true);
  /*Validation*/
	$(document).on('keypress','.bulk_qty,.bulk_unit_price,.bulk_discount_percentage,.transport_charges,.unloading_charges,.insurance_charges,.packing_charges,.freight_amount', function(ev){
			var regex = new RegExp("^[0-9.]+$");
					var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
					if (regex.test(str)) {
						return true;
					}
					ev.preventDefault();
					return false;
		});
	/*End*/
/*Karthigaa Purpose for Jcombo Refresh*/
$(document).on('click','.jcr_supplier_id',function()
{
$(".supplier_id").jCombo("{{ URL::to('jcomboform?table=m_supplier_t:supplier_id:supplier_name') }}&order_by=supplier_name asc",
{selected_value:""});
});

$(document).on('click','.jcr_project_id',function(){
$(".project_id").jCombo("{{ URL::to('jcomboform?table=m_projects_t:project_id:project_name') }}&order_by=project_name asc",
{selected_value:""});
});

	$(document).on('click','.jcr_default_payment_method_id',function(){
$(".default_payment_method_id").jCombo("{{ URL::to('jcomboform?table=m_payment_methods_t:payment_method_id:payment_method_name') }}&order_by=payment_method_name asc",
{selected_value:""});
});

	$(document).on('click','.jcr_freight_carrier_id',function(){
$(".freight_carrier_id").jCombo("{{ URL::to('jcomboform?table=m_frieghtcarriers_hdr_t:ar_frieghtcarriers_hdr_id:carrier_name') }}&order_by=carrier_name asc",
{selected_value:""});
});
$(document).on('click','.jcr_insurance_term_id',function(){
    $(".insurance_term_id").jCombo("{{ URL::to('jcomboform?table=m_insurance_terms_t:insurance_term_id:insurance_term_name') }}&order_by=insurance_term_name asc",
    {selected_value:""});
    });

	$(document).on('click','.jcr_freight_terms_id',function(){
$(".freight_terms_id").jCombo("{{ URL::to('jcomboform?table=m_frieghtterms_t:frieghtterm_id:fob_point_name') }}&order_by=fob_point_name asc",
{selected_value:""});
});


	$(document).on('click','.jcr_suppliersite_id',function(){
$(".suppliersite_id").jCombo("{{ URL::to('jcomboform?table=m_supplier_sites_t:supplier_site_id:supplier_site_name') }}&order_by=supplier_site_name asc",
{selected_value:""});
});

	$(document).on('click','.jcr_bill_to_location_id',function(){
$(".bill_to_location_id").jCombo("{{ URL::to('jcomboform?table=m_location_t:location_id:location_name') }}&order_by=location_name asc",
{selected_value:""});
});

	$(document).on('click','.jcr_ship_to_location_id',function(){
$(".ship_to_location_id").jCombo("{{ URL::to('jcomboform?table=m_location_t:location_id:location_name') }}&order_by=location_name asc",
{selected_value:""});
});


$(document).on('click','.jcr_payment_term_id',function(){
$(".payment_term_id").jCombo("{{ URL::to('jcomboform?table=m_payment_terms_t:payment_term_id:payment_term_name') }}&order_by=payment_term_name asc",
{selected_value:""});
});

$(document).on('click','.jcr_delivery_terms_id',function(){
$(".delivery_terms_id").jCombo("{{ URL::to('jcomboform?table=m_delivery_terms_t:delivery_terms_id:delivery_term_name') }}&order_by=delivery_term_name asc",
{selected_value:""});
});
$(document).on('click','.jcr_po_pricelist_id',function(){

	var condition =' price_list_type="Purchase"';
	$(".po_pricelist_id").jCombo("{{ URL::to('jcomboform?table=i_pricelist_hdr_t:pricelist_hdr_id:pricelist_name') }}&order_by=pricelist_name asc"+'&parent='+condition,
	{selected_value:""});
});
/*End*/


$('.source,.po_type,.organization_id,.created_by,.po_status').attr('readonly','readonly').css('pointer-events','none');

/*Karthigaa Purpose For Supplier Based Price load*/

    $(document).on('change','.supplier_id',function(){
        
      
    var supplier_id=$('.supplier_id option:selected').val();
    if(supplier_id!='')
    {
     
     var condition ="supplier_id="+supplier_id;
      
    $(".suppliersite_id").jCombo("{{ URL::to('jcomboform?table=m_supplier_sites_t:supplier_site_id:supplier_site_name') }}&order_by=supplier_site_name asc"+'&parent='+condition,
    {selected_value:""});



    $.get("{{ URL::to('supplierpricelist') }}/"+supplier_id,function(suppdata)
    {
        var data = $.trim(suppdata);
        if(data !=0)
        {
            $(".po_pricelist_id").val(suppdata['price_list']).change();
            $(".suppliersite_id").val(suppdata['supplier_site_id']).change();
            $('.payment_term_id').val(suppdata['default_payment_terms_id']).change();
            $('.delivery_terms_id').val(suppdata['delivery_terms_id']).change();
            $('.default_payment_method_id').val(suppdata['default_payment_method_id']).change();
            $('.insurance_term_id').val(suppdata['insurance_term_id']).change();

        }
        else
        {

            $(".po_pricelist_id").val('').change();
        }
    });


}

});
   /*End*/


    /* rajacode for pricelist based prodcut load */
 $(document).on('change','.po_pricelist_id',function(){
    var po_pricelist_id=$('.po_pricelist_id option:selected').val();
		$('.bulk_product_id').each(function(index) {
     var product_id=$('.bulk_product_id'+index).val()?$('.bulk_product_id'+index).val():0;
       $.get("{{URL::to('getpriceproduct')}}/"+po_pricelist_id+'/'+product_id,function(data)
        { 
            var data = $.trim(data);
           if(data!=0)
           {
              $('.bulk_product_id'+index).html(data);
           }
        });
       });
    });
//    $(document).on('change','.po_pricelist_id',function(){
//    var po_pricelist_id=$('.po_pricelist_id option:selected').val();
//$(".bulk_product_id").each(function(index){
//  if(index!=0)
//  $(this).closest('tr').remove();
//});
//        $.get("{{URL::to('getpriceproduct')}}/"+po_pricelist_id+'/0',function(data)
//        {
//               $('#bulk_product_id').html(data);
//        });
//    });
       /* rajacode end for pricelist based prodcut load */

	/*karthigaa purpose for load product based details */

	/*karthigaa purpose for load product based details */
    $(document).on('change','.bulk_product_id',function(event){

        var index=$(this).closest('tr').index();
        var product_id=$(this).val();
        var type=$('.po_type option:selected').val();
        
        var plid=$('.po_pricelist_id option:selected').val(); 
        var cid=$('.supplier_id option:selected').val();
        var suppsiteid=$('.suppliersite_id option:selected').val();
        
        var url="{{ url::to('productdetails') }}/"+product_id+"/"+plid+"/"+suppsiteid+"/"+type+"?supplier_id="+cid+"&source=SUPPLIER";
        if(product_id !='')
        {
        if(cid !='')
        { 
        	if(plid !='')
        	{
            	if(product_id !='')
        		{
        			var pdtcount = 0;
        			var pdtcount = pdtcheck(product_id,index);
        			if(pdtcount <= 0)
        			{
        			$.get(url,function(data)
        			{   
                                 console.log(data);
                         var hsnid=data['multihsn'];
				var condition="classification_name='HSN' and gst_code_hdr_id in("+hsnid+")";
				 $(".bulk_hsn_code"+index).jCombo("{{ URL::to('jcomboform?table=f_gst_code_hdr_t:gst_code_hdr_id:classification_code') }}&order_by=classification_code asc"+'&parent='+condition,
    {selected_value:''});
			 setTimeout(function(){ 
			 $(".bulk_hsn_code"+index).val(data['hsn_code']).change();
								   }, 500);
                        $('.bulk_part_no'+index).select2('val',[data['part_no']]);
        				$('.bulk_uom_code_id'+index).val(data.uom_code_id).change();
        				$('.bulk_unit_price'+index).val(data.unit_price);
        				var min_order=$('.bulk_min_order'+index).val(data.min_order_qty);
        				var max_order=$('.bulk_max_order'+index).val(data.max_order_qty);
                                       
        				$('.bulk_tax_group_id'+index).val(data.tax_group_id).change();
        				calc_by_index(index);
        				if(data.unit_price =="0")
        				{
        				notyMsgs('info','Pricelist Not Assigned For this Product !!!');
        				}
        		if($.trim(data.tax_group_id)==0){
					
					if($.trim(data.tax_group_id_expiry)=="expiry"){
						      notyMsgs('info','Tax Group expired  for this product');
						 $('.bulk_tax_group_id'+index).val(data.tax_group_id).change();
					}
                                           else if($.trim(data.tax_group_id_expiry)=="location"){
						      notyMsgs('info','Tax not assigned for this Location');
					}
					else{
                                                 notyMsgs('info','Tax Group not assigned for this product');
						 $('.bulk_tax_group_id'+index).val(data.tax_group_id).change();
					}	
				   }
                else
                {
                  $('.bulk_tax_group_id'+index).val(data.tax_group_id).change();
                }
        			});
        			}
        			else
        			{
        				notyMsgs('info','Product Already Selected');
        				rowdataEmpty(index);
        				$(".bulk_product_id" + index).val('').change();
        				event.preventDefault();
        			}

        		}
        		else
        		{
        		rowdataEmpty(index);
        		calc_by_index(index)
        		}
        	}
        	else
        	{
        		rowdataEmpty(index);
        		calc_by_index(index);
        		notyMsg('info','Please Select Pricelist !!!');
        	 /// $(".bulk_product_id" + index).val('').change();
        	 event.preventDefault();
        	}
        }
        else
        {
        notyMsg('info','Please Select Supplier !!!');
        $(".bulk_product_id" + index).val('').change();
        	 event.preventDefault();
        //rowdataEmpty(index);
        }
        }
    });
/*deepika purpose: to load tax based on hsn code*/
	$(document).on('change','.bulk_hsn_code',function(){
		var hsnid=$(this).val();
		var index=$(this).closest('tr').index();
		var suppsiteid=$('.suppliersite_id option:selected').val();
		var mtype="PURCHASE";
		if(hsnid!="" && suppsiteid!=""){
		var url="{{ URL::to('taxdetails')}}/"+hsnid+"/"+suppsiteid+"/"+mtype;
		   }
		$.get(url,function(data){
			//console.log(data['tax_group_id']);
			if(data['tax_group_id']==0)
				   {
					
					if($.trim(data['tax_group_id_expiry'])=="expiry"){
					
						      $('.bulk_tax_group_id'+index).select2('val',[data['tax_group_id']]);
                              notyMsgs('info','Tax Group expired  for this product');
					}
                    else if($.trim(data['tax_group_id_expiry'])=="location")
					{
					   notyMsgs('info','Tax not assigned for this Location');
					}
					else
					{
						
                        $('.bulk_tax_group_id'+index).select2('val',[data['tax_group_id']]);
                          notyMsgs('info','Tax Group not assigned for this productef');
					}	
				   }
                else
				{
                  $('.bulk_tax_group_id'+index).select2('val',[data.tax_group_id]);
                   calc_by_index(index);
				}
			
		});
	});

	/*end*/
      
  $(document).on('change','.po_pricelist_id',function(event){


		var plid=$('.po_pricelist_id').val();
		var supplierid=$('.supplier_id option:selected').val();
        
        if(plid != null){
    		$('.bulk_product_id').each(function(index){
    			var product_id=$(this).val();
                        var type=$('.po_type option:selected').val();
                        var suppsiteid=$('.supplier_site_id option:selected').val();
    			var url="{{ url::to('productdetails') }}/"+product_id+"/"+plid+"/"+suppsiteid+"/"+type;
                if(product_id != ""){
        			$.get(url,function(data)
        			{
        				$('.bulk_uom_code_id'+index).val(data.uom_code_id).change();
        				if(data.unit_price =="0")
        				{
        				notyMsgs('info','Pricelist Not Assigned For this Product !!!');
        				}
                        else
                        {
                           $('.bulk_unit_price'+index).val(data.unit_price);
                        }
        			if($.trim(data.tax_group_id)==0){
					
					if($.trim(data.tax_group_id_expiry)=="expiry"){
						      notyMsgs('info','Tax Group expired  for this product');
						 $('.bulk_tax_group_id'+index).val(data.tax_group_id).change();
					}
                                           else if($.trim(data.tax_group_id_expiry)=="location"){
						      notyMsgs('info','Tax not assigned for this Location');
					}
					else{
                                                 notyMsgs('info','Tax Group not assigned for this product');
						 $('.bulk_tax_group_id'+index).val(data.tax_group_id).change();
					}	
				   }
                else
                {
                  $('.bulk_tax_group_id'+index).val(data.tax_group_id).change();
                }
                        calc_by_index(index);
        			});
                }
    		});
        }

	});





    /*End*/
    /*Karthigaa Purpose for Add Sum of other charges into total*/
function total_amount(){
			var sum = 0;
			var sumtax = 0;
                        var sumall = 0;
                        var charge= 0;
                        $(".charges").each(function(){
                               charge += +$(this).val();
                           });
			$('.bulk_line_total').each(function(){
				sum += parseFloat($(this).val());
			});
			$('.bulk_tax_amount').each(function(){
				sumtax += parseFloat($(this).val());
			});
            sumall = Number(charge) + Number(sum);
			$('#quote_tax_total').val(sumtax);
			$('#quote_grand_total').val(sumall);
			$(".tax_total_span").html(sumtax);
			$(".grand_total_span").html(sumall);
		/* end */
    }

	/**** To Empty the Rowdata when product Empty ********/
	function rowdataEmpty(index)
	{
	$(".bulk_product_id" + index).val('').change;
	$(".bulk_uom_code_id" + index).val('').change();
	$(".bulk_qty" + index).val('');
	$(".bulk_unit_price" + index).val('');
	$(".bulk_discount_percentage" + index).val('');
	$(".bulk_discount_amount" + index).val('');
	$(".bulk_tax_group_id" + index).change();
	$(".bulk_tax_amount" + index).val('');
    $(".bulk_line_sub_total" + index).val('');
	$(".bulk_line_total" + index).val('');
        $(".bulk_part_no" + index).val('');
	$(".bulk_promised_date" + index).val('');
	$(".bulk_comments" + index).val('');
	$(".bulk_qty" + index).trigger('change');
	}
/**** To Empty the Rowdata when product Empty End********/
	/*Karthigaa Code for lines level calulaton process*/
	$(document).on('keyup change','.bulk_qty,.bulk_unit_price,.bulk_discount_percentage,.bulk_tax_group_id,.bulk_product_id,.transport_charges, .unloading_charges, .insurance_charges, .packing_charges,.freight_amount',function(){
	var index = $(this).closest("tr").index();
	var unitprice = $('.bulk_unit_price'+index).val();
	var requiredqty = $('.bulk_qty'+index).val();
	var taxgrp = $('.bulk_tax_group_id'+index+' option:selected').attr('data-display');
	taxgrp = taxgrp?taxgrp:0;
	var discountsperc = $('.bulk_discount_percentage'+index).val();
	var disamout = (((requiredqty * unitprice) * discountsperc/100));
	$('.bulk_discount_amount'+index).val(disamout);
	var taxamount = ((requiredqty * unitprice) - disamout) * taxgrp /100;
	$('.bulk_tax_amount'+index).val(taxamount);
	var subtot = ((requiredqty * unitprice) - disamout);
        var linetot=parseFloat(subtot + taxamount).toFixed($('#decimal_point').val());
	$(".bulk_line_total"+index).val(linetot);
		/* Code for set linetotal values into header level field*/
			var sum = 0;
			var sumtax = 0;
                        var sumall = 0;
                        var charge= 0;
                        $(".charges").each(function(){
                               charge += +$(this).val();
                           });

			$('.bulk_line_total').each(function(){
				sum += parseFloat($(this).val());
			});
			$('.bulk_tax_amount').each(function(){
				sumtax += parseFloat($(this).val());
			});
                          sumall = Number(charge) + Number(sum);
			$('#po_tax_total').val(sumtax);
			$('#po_grand_total').val(sumall);
      $(".tax_total_span").html(sumtax);
    //alert("jj");
      $(".grand_total_span").html(sumall);
		/* end */
		/* Code for calculating tot tax amount */
		var tax =0;
		$('.bulk_tax_amount').each(function(){
		   tax+= parseFloat($(this).val());
		});
		$('.po_tax_total').val(tax);
                $(".tax_total_span").html(tax);
		/* end */
});

$(document).on('keyup change','.bulk_discount_amount',function()
{
	var index = $(this).closest("tr").index();
	var unitprice = $('.bulk_unit_price'+index).val();
	var requiredqty = $('.bulk_qty'+index).val();

	var taxgrp = $('.bulk_tax_group_id'+index+'option:selected').attr('data-display');
	taxgrp = taxgrp?taxgrp:0;
	var discountsperc = $('.bulk_discount_percentage'+index).val();
	var discountamt = $('.bulk_discount_amount'+index).val();
	var disamout = discountamt;
	var disamt = ((discountamt * 100)/(requiredqty * unitprice));


	$('.bulk_discount_percentage'+index).val(disamt);
	$('.bulk_discount_amount'+index).val(disamout);
	var taxamount = ((requiredqty * unitprice) - disamout) * taxgrp /100;

	$('.bulk_tax_amount'+index).val(taxamount);
	var subtot = ((requiredqty * unitprice) - disamout);
	var linetot=parseFloat(subtot + taxamount).toFixed($('#decimal_point').val());
		$(".bulk_line_total"+index).val(linetot);
	/* Code for set linetotal values into header level field*/
		var sum = 0;
		var sumtax = 0;
		$('.bulk_line_total').each(function()
		{
			sum += parseFloat($(this).val());

		});
		$('.bulk_tax_amount').each(function()
		{
			sumtax += parseFloat($(this).val());
		});
		$('#po_tax_total').val(sumtax);
		$('#po_grand_total').val(sum);
        $(".tax_total_span").html(sumtax);
        $(".grand_total_span").html(sum);
	/* end */
	/* Code for calculating tot tax amount */
	var tax =0;
	$('.bulk_tax_amount').each(function(){
	   tax+= parseFloat($(this).val());
	});
	$('.po_tax_total').val(tax);
         $(".tax_total_span").html(tax);
	/* end */
});

function calc_by_index(index){

        var unit_price = $('.bulk_unit_price'+index).val();
        var qty = $('.bulk_qty'+index).val();
        var tax_group = $('.bulk_tax_group_id'+index+' option:selected').attr('data-display');
   //lert(tax_group);
        var line_sub_total = parseFloat(unit_price * qty);
        var tax_amount = parseFloat((line_sub_total*tax_group)/100);
        $('.bulk_tax_amount'+index).val(tax_amount);
        var linetot=parseFloat(line_sub_total+tax_amount).toFixed($('#decimal_point').val());
        $(".bulk_line_sub_total"+index).val(line_sub_total);
        $(".bulk_line_total"+index).val(linetot);

/* Code for set linetotal values to header level via keyup*/
        var lsbt = 0;
        $('.bulk_line_sub_total').each(function()
        {
        lsbt += parseFloat($(this).val());
        });
        $('.order_sub_total').val(lsbt);

        var sum = 0;
        $('.bulk_line_total').each(function()
        {

        sum += parseFloat(isNaN($(this).val())?0:($(this).val()));
        // alert(sum);
        });
//alert(sum);
        $('#grand_total_span').html(sum);
/* end */
/* Code for calculating tot tax amount */
        var tax =0;
        $('.bulk_tax_amount').each(function(){
        tax+= parseFloat($(this).val());
        });
        $('.po_tax_total').val(tax);
/* end */
}
    /*End*/

var data ="{{\Session::get('j_date_format')}}";
$(".add_row").relCopy(data);
$('.add_row').click(function(){

                changeclassfields();
});

$(document).on('click','.remove',function(){
	var index = $(this).closest('tr').index();
	var rowCount = $('.po_table tbody tr').length;
	if(rowCount > 1){
		$($(this).closest("tr")).remove();
                removeclassfields();
	}
	else{
		notyMsg('error',"You Can't Delete Atleast One row should be there");
	}
         var sum = 0;
        var sumtax = 0;
        $('.bulk_line_total').each(function()
        {
            sum += parseFloat($(this).val());
        });
        $('.bulk_tax_amount').each(function()
        {
            sumtax += parseFloat($(this).val());
        });
        $('#po_tax_total').val(sumtax);
        $('#po_grand_total').val(sum);
});

var index = $('.clone').closest('tr').index();
changeclassfields();

$('.suppliersearch').click(function()
	{
	 $('#supplierModal').modal('show');
	 $('#supplierModal').width("100%");
	$(mygrid).trigger("reloadGrid", [{current: true}]);
	});
$('.productsearch').click(function()
	{
	 var index = ($(this).closest('tr').index());
	 $('.pdtindex').val(index);
	 $('#productModal').modal('show');
	 $('#productModal').width("100%");

$(mypdtgrid).trigger("reloadGrid", [{current: true}]);
	});
/*Karthigaa Purpose for Supplier Search*/
var supnameopt="{{ $supnameopt }}";
	var mygrid = $("#suppliergrid"),
    pagerSelector = "#pager",
    myAddButton = function(options) {
        mygrid.jqGrid('navButtonAdd',pagerSelector,options);
        mygrid.jqGrid('navButtonAdd','#'+mygrid[0].id+"_toppager",options);
    };
          mygrid.jqGrid({
          url: "{{ URL::to('getSuppliergridData') }}",
            datatype: "json",
            mtype: "GET",
			height: 320,
			width: 1000,
             colModel: [
			{ name: "supplierid", label: "id",hidden:true, width:55},
			{ name: "supplier_site_id", label: "id",hidden:true, width:55},
			{ name: "supplier_number", label: "Supplier Number", width:55},
		 	{ name: "supplier_name", label: "Supplier Name",stype:'select', editoptions:{value:supnameopt}, width:55},
			{ name: "supplier_type_id", label: "Supplier Type", width:55},
		 	{ name: "supplier_site_name", label: "Supplier Site Name", width:55},
		 	{ name: "site_type", label: "Supplier Site Type", width:55},
		 	{ name: "address", label: "Address", width:55},
		 	{ name: "city", label: "City", width:55},
		 	{ name: "state", label: "State", width:55},
		 	{ name: "country", label: "Country", width:55}
                    ],

                        iconSet: "fontAwesome",
                        rowNum: 10,
                        rowList: [10,20,100,1000],
                        sortorder: "asc",
                        viewrecords: true,
                        gridview: true,
                        rownumbers:true,
                        caption: "Supplier",
                        pager: pagerSelector,
                        toppager:true,
                        searching: {
                        defaultSearch: "cn"
                        }
		   });
                        jQuery(mygrid).jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
                        mygrid.jqGrid('navGrid',pagerSelector,
                        {cloneToTop:true,edit:false,add:false,del:false,search:true});
                        myAddButton ({
                        caption:"Select Supplier",
                        title:"Supplier",
                        buttonicon :'ui-icon-plus',
		onClickButton:function()
		{
		var gr = $(mygrid).jqGrid('getGridParam','selrow');
		var supplier = $(mygrid).jqGrid ('getCell', gr, 'supplierid');
		var address = $(mygrid).jqGrid ('getCell', gr, 'address');
		if( supplier != false )
		{
		$('.supplier_id').val(supplier).change();
		$('#supplierModal').modal('hide');
		}
		else
		{
		notyMsg('info','Please Select one row');
		}
		}
});
/*Karthigaa Purpose For Product Search*/
    var mypdtgrid = $("#productgrid"),
    pagerSelector = "#pager",
    myAddButton = function(options) {
        mypdtgrid.jqGrid('navButtonAdd',pagerSelector,options);
        mypdtgrid.jqGrid('navButtonAdd','#'+mypdtgrid[0].id+"_toppager",options);
    };
		var groupname="'RAW MATERIALS'";
	var gname="'PACKING MATERIALS'";
 		var grp=[];
 			grp.push(groupname);
	grp.push(gname);
	var prdcatopt="{{ $prdcatopt}}";
	var prdnameopt="{{ $prdnameopt }}";
            mypdtgrid.jqGrid({
            url: "{{ URL::to('getProductgridData') }}?prggrp="+grp,
			datatype: "json",
			mtype: "GET",
			height: 320,
			width: 1000,
             colModel: [
			{ name: "product_code", label: "Product Code", width:55},
		 	{ name: "product_group_id", label: "Product Group", width:55},
			{ name: "product_category_id", label: "Product Category",stype:'select', editoptions:{value:prdcatopt}, width:55},
		 	{ name: "concatenated_product", label: "Product Name",stype:'text', editoptions:{value:prdnameopt}, width:55},
			{ name: "product_id", label: "id",hidden:true, width:55}
		  ],
                        iconSet: "fontAwesome",
			rowNum: 10,
			rowList: [10,20,100,1000],
			sortorder: "asc",
			viewrecords: true,
			gridview: true,
			rownumbers:true,
			caption: "Supplier",
			pager: pagerSelector,
			toppager:true,
			searching: {


			defaultSearch: "cn"
			}
		   });

			jQuery(mypdtgrid).jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});

			mypdtgrid.jqGrid('navGrid',pagerSelector,
			{cloneToTop:true,edit:false,add:false,del:false,search:true});
			myAddButton ({

			caption:"Select Product",
			title:"Product",
			buttonicon :'ui-icon-plus',
		onClickButton:function()
		{

			var index = $('.pdtindex').val();
			var gr = jQuery(mypdtgrid).jqGrid('getGridParam','selrow');
			var product_id = jQuery(mypdtgrid).jqGrid ('getCell', gr, 'product_id');
			 if(product_id != false )
			{
				var pdtcount = pdtcheck(product_id,index);
				if(pdtcount <= 0)
				{
				$('.bulk_product_id'+index).val(product_id);
				$('.bulk_product_id'+index).trigger('change');
				$('#productModal').modal('hide');
				}
				else
				{
                                        var msg = jQuery(mypdtgrid).jqGrid ('getCell', gr, 'concatenated_product');
					var message  = '<span style="color:#fdff65">'+msg+'</span>'+' Product Already Selected';
					notyMsgs('info',message);
					rowdataEmpty(index);
					$('#productModal').modal('hide');
				}
			}
			else
			{
			notyMsg('error','Please Select one row');
			}
		}
});


/*karthigaa purpose for hide product in labour condition*/
<?php if($row->po_type =="STANDARD")
{ ?>
$('.pdtdes_div').addClass('hide');

    
$(document).on('keyup','.bulk_qty',function(){
	var index=$(this).closest('tr').index();
var minorder=$('.bulk_min_order'+index).val();
var maxorder=$('.bulk_max_order'+index).val();
var qty=$('.bulk_qty'+index).val();
	if(parseInt(qty) > parseInt(maxorder)){
	   notyMsg('info','Qty should not exceed max order qty ' +maxorder);
		$('.bulk_qty'+index).val('');
	}else if(qty<minorder){
		notyMsg('info','Qty should not below min order qty ' +minorder);
		$('.bulk_qty'+index).val('');
	}
});

<?php }
     else { ?>
$('.pdtdiv,.productsearch').addClass('hide');
$('.bulk_product_id').removeAttr('required');
<?php } ?>
/*End*/


$(document).on('click','.saveform',function() {

      $('#panel_add').trigger('click'); //for expanding accordin
        var btnval = $(this).val();
                if(btnval == 'APPLYCHANGES')
                $("#po_status").val('DRAFT');
            else if(btnval == 'DRAFT')
                $("#po_status").val('DRAFT');
             else if(btnval=='Approved')
                $("#po_status").val('APPROVED');
                 else if(btnval=='Rejected')
                $("#po_status").val('REJECTED');
             else if(btnval=='Canceled')
                $("#po_status").val('CANCELLED');
            else
                $("#po_status").val('APPROVED');

        $('#savestatus').val(btnval);

	var url			="{{ URL::to('poamendmentsave') }}";
//	var red_url		="{{ URL::to('purchaseorder') }}";
        var red_url		="{{ URL::to($return_url) }}";
	var create_url	="{{ URL::to('poamendmentcreate') }}/0/0/2";
	validationrule('poamendment_form');

	var form = $('#poamendment_form');

	if(btnval != 'APPLYCHANGES')
	{
		form.parsley().validate();
		var form = $('#poamendment_form');
		form.parsley().validate();

		if (form.parsley().isValid())
		{
                change_date();
          	var formdata	= $('#poamendment_form').serialize();
		  $.post(url,formdata,function(data)
		   {
				var status  = data.status;
				var msg     = '<span style="color:#090065">'+data.auto_no+'</span>  '+data.message;
				var id      = data.id;
				var auto_no = data.auto_no;
		//		var edit_url	="{{ url('purchaseordercreate') }}/"+id+"/0/2";

			if(btnval !='SAVE' && btnval !='DRAFT' && btnval !='Approved'&& btnval !='Rejected'&& btnval !='Canceled')
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
     change_date();
          	var formdata	= $('#poamendment_form').serialize();
	$.post(url,formdata,function(data)
		{
		var status = data.status;
		var msg     = '<span style="color:#090065">'+data.auto_no+'</span>  '+data.message;
		var id     = data.id;
			var edit_url	="{{ URL::to('poamendmentcreate') }}/"+id;
			notyMsg(status,msg);
			setTimeout(function(){
			window.location.href=edit_url;
			}, 1500);
		});
	}
});

	});
function changeClassName(className)
{
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
function removeclassfields(){
        removeClass('bulk_line_no');
        removeClass('bulk_product_id');
        removeClass('bulk_uom_code_id');
        removeClass('bulk_qty');
        removeClass('bulk_unit_price');
        removeClass('bulk_discount_percentage');
        removeClass('bulk_discount_amount');
        removeClass('bulk_line_subtotal');
        removeClass('bulk_tax_group_id');
        removeClass('bulk_tax_amount');
        removeClass('bulk_line_total');
        removeClass('bulk_promised_date');
        removeClass('bulk_comments');
        removeClass('bulk_received_qty');
        removeClass('bulk_part_number');
        removeClass('bulk_min_order');
        removeClass('bulk_max_order');
        removeClass('bulk_hsn_code');
    }
function changeclassfields(){
    changeClassName('bulk_po_line_id');
    changeClassName('bulk_line_no');
    changeClassName('bulk_product_id');
    changeClassName('bulk_uom_code_id');
    changeClassName('bulk_qty');
    changeClassName('bulk_unit_price');
    changeClassName('bulk_discount_percentage');
    changeClassName('bulk_discount_amount');
    changeClassName('bulk_line_subtotal');
    changeClassName('bulk_tax_group_id');
    changeClassName('bulk_tax_amount');
    changeClassName('bulk_line_total');
    changeClassName('bulk_promised_date');
    changeClassName('bulk_comments');
    changeClassName('bulk_received_qty');
    changeClassName('bulk_part_number');
    changeClassName('bulk_min_order');
    changeClassName('bulk_max_order');
    changeClassName('bulk_hsn_code');
    }
/************ karthigaa purpose to remove row action ********************/
function removeClass(className)
{
	var rowCount = $('.po_table tbody tr').length;
	for(var i=0;i<=rowCount;i++)
	{
	$('.po_table tbody tr').find('.'+className).removeClass(className+i);
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

	</script>
<script>
$(window).on('load',function(){
       //preloader
       var preLoder = $("#preloader");
       preLoder.fadeOut(500);
       var backtoTop = $('.back-to-top')
       backtoTop.fadeOut(100);
   });

    function preventBack() {
    window.history.forward();
}
 window.onunload = function() {
    null;
};
setTimeout("preventBack()", 0);
</script>

@include('layouts.php_js_validation')
@endsection
