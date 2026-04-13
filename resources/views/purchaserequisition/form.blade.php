

@extends('layouts.header')
@section('content')
<style>
/*styles for  line table to different screens*/
 @media only screen and (min-width: 1500px) {
.bulk_line_no{width: 50px !important;}
.bulk_product_id{width: 240px !important;}
.bulk_part_no{width: 110px !important;}
.bulk_product_description{width: 110px !important;}
.bulk_uom_code_id{width: 110px !important;}
.bulk_qty{width: 100px !important;}
.bulk_qoh{width: 100px !important;}
.bulk_need_by_date{width: 100px !important;}
.bulk_comments{width: 150px !important;}
}
@media only screen and (min-width: 2000px) {
.bulk_line_no{width: 50px !important;}
.bulk_product_id{width: 340px !important;}
.bulk_part_no{width: 210px !important;}
.bulk_product_description{width: 210px !important;}
.bulk_uom_code_id{width: 210px !important;}
.bulk_qty{width: 200px !important;}
.bulk_qoh{width: 200px !important;}
.bulk_need_by_date{width: 200px !important;}
.bulk_comments{width: 250px !important;}
}


@media only screen and (max-width: 1499px) {
.bulk_line_no{width: 50px;}
.bulk_product_id{width: 245px;}
.bulk_part_no{width: 110px ;}
.bulk_product_description{width: 110px;}
.bulk_uom_code_id{width: 110px;}
.bulk_qty{width: 70px;}
.bulk_qoh{width: 70px;}
.bulk_indent_qty{width: 100px;}
.bulk_need_by_date{width: 100px;}
.bulk_comments{width: 140px;}
}

.po_req_table::-webkit-scrollbar {
    width: 1em;
}


.po_req_table::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
}
 
.po_req_table::-webkit-scrollbar-thumb {
  background-color: darkgrey;
  outline: 1px solid slategrey;
}

.modal-body{
    height: 300px;
    overflow-y:auto; 
}


/* modal jqgrid popup styles  */
.modal-open .ui-jqgrid .ui-jqgrid-pager {
    border-left: 0 none !important;
    border-right: 0 none !important;
    border-bottom: 0 none !important;
    border-top: 0 none;
    margin: 0 !important;
    padding: 0 !important;
    position: relative;
    height: auto;
    min-height: 28px;
    white-space: nowrap;
    overflow: hidden;
    /*font-size:11px; */
    z-index: 100
}

.modal-open .ui-jqgrid .ui-jqgrid-toppager .ui-pager-control,.modal-open 
.ui-jqgrid .ui-jqgrid-pager .ui-pager-control {
    position: relative;
    border-left: 0;
    border-bottom: 0;
    border-top: 0;
    height: 28px;
}

.modal-open .ui-jqgrid .ui-pg-table {
    position: relative;
    padding: 1px 0;
    width: auto;
    margin: 0;
}

.modal-open .ui-jqgrid .ui-pg-table td {
  width: auto !important;
    font-weight: normal;
    vertical-align: middle;
    padding: 0px 1px;
}

.modal-open .ui-jqgrid .ui-pg-button {
    height: auto
}

.modal-open .ui-jqgrid .ui-pg-button span {
    display: block;
    margin: 2px;
    float: left;
}

.modal-open .ui-jqgrid .ui-pg-button:hover {
    padding: 0;
}

.modal-open .ui-jqgrid .ui-state-disabled:hover {
    padding: 0px;
}

.modal-open .ui-jqgrid .ui-pg-input,
.modal-open .ui-jqgrid .ui-jqgrid-toppager .ui-pg-input {
    height: 14px;
    width: auto;
    font-size: .9em;
    margin: 0;
    line-height: inherit;
    border: none;
    padding: 3px 2px
}

.modal-open .ui-jqgrid .ui-pg-selbox,
.modal-open .ui-jqgrid .ui-jqgrid-toppager .ui-pg-selbox {
    font-size: .9em;
    line-height: inherit;
    display: block;
    
    margin: 0;
    padding: 3px 0px;
    border: none;
}

.modal-open .ui-jqgrid .ui-separator {
    height: 18px;
    border-left: 2px solid #ccc;
}

.modal-open .ui-separator-li {
    height: 2px;
    border: none;
    border-top: 2px solid #ccc;
    margin: 0;
    padding: 0;
    width: 100%
}

.modal-open .ui-jqgrid .dropdownmenu {
    padding: 3px 0 3px 0;
    margin-left: 4px;
}

.modal-open .ui-jqgrid .ui-jqgrid-pager .ui-pg-div,
.modal-open .ui-jqgrid .ui-jqgrid-toppager .ui-pg-div {
    padding: 1px 0;
    float: left;
    position: relative;
    line-height: 20px;
}

.modal-open .ui-jqgrid .ui-jqgrid-pager .ui-pg-button,
.modal-open .ui-jqgrid .ui-jqgrid-toppager .ui-pg-button {
    cursor: pointer;
}

.modal-open .ui-jqgrid .ui-jqgrid-pager .ui-pg-div span.ui-icon,
.modal-open .ui-jqgrid .ui-jqgrid-toppager .ui-pg-div span.ui-icon {
    float: left;
    margin: 0px;
    width: 18px;
}

.modal-open .ui-jqgrid td input,
.modal-open .ui-jqgrid td select,
.modal-open .ui-jqgrid td textarea {
    margin: 0;
    padding-top: 5px;
    padding-bottom: 5px;
}

.modal-open .ui-jqgrid td textarea {
    width: auto;
    height: auto;
}

.modal-open .ui-jqgrid .ui-jqgrid-toppager {
    width: 100% !important;
    border-left: 0 none !important;
    border-right: 0 none !important;
    border-top: 0 none !important;
    margin: 0 !important;
    padding: 0 !important;
    position: relative;
    white-space: nowrap;
    overflow: hidden;
}

.modal-open .ui-jqgrid .ui-jqgrid-pager .ui-pager-table,
.modal-open .ui-jqgrid .ui-jqgrid-toppager .ui-pager-table {
    width: 100%;
    margin-top: 1px;
    table-layout: fixed;
    /*height: 100%;*/
}

.modal-open .ui-jqgrid .ui-jqgrid-pager .ui-paging-info,
.modal-open .ui-jqgrid .ui-jqgrid-toppager .ui-paging-info {
    font-weight: normal;
    height: auto;
    margin-top: 3px;
    margin-right: 4px;
    display: inline;
}

.modal-open .ui-jqgrid .ui-jqgrid-pager .ui-paging-pager,
.modal-open .ui-jqgrid .ui-jqgrid-toppager .ui-paging-pager {
    table-layout: auto;
    height: 100%;
}

.modal-open .ui-jqgrid .ui-jqgrid-pager .navtable,
.modal-open .ui-jqgrid .ui-jqgrid-toppager .navtable {
    float: left;
    table-layout: auto;
    /*height: 100%;*/
}
@media only screen and (min-width: 1700px) {
.modal-open .ui-jqgrid .ui-jqgrid-pager .ui-paging-info,
.modal-open .ui-jqgrid .ui-jqgrid-toppager .ui-paging-info {
    font-weight: normal;
    height: auto;
    margin-top: 3px;
    margin-right: -25em;
    display: inline;
}
}


</style>
<div class="ajaxLoading"></div>
<?php error_reporting(0);

if($row->requisition_source=='INTERNAL' && $row->requisition_hdr_id=='' && !isset($copy_requisition_no))
{
    $head=" ( New )";
}
else if($row->requisition_hdr_id !='')
{
    $head=" ( ".$row->requisition_no." )";
}else if($row->requisition_status=='Indent'){
	
	$head=" ( Copy  From ".$copy_requisition_no." )";
}
else if($row->requisition_status=='INITIATED'){
	
	$head=" ( Copy  From ".$copy_requisition_no." )";
}
else if($row->requisition_status=='APPROVED'){
	
	$head=" ( Copy  From ".$copy_requisition_no." )";
}
else
{
    $head=" ( from Indent ".$row->reference_no." )";
}

 ?>

<?php include('tools_menu.php'); ?> <h3 class="heads">Purchase Requisition {{$head}}
<span class="ui_close_btn"><a class="collapse-close pull-right btn-danger" onclick='location.href="{{ URL::to($return_url) }}"'></a></span>
</h3>

<form method="post" action="" id="poreq_form" class="poreq_form" data-parsley-validate>
   <input class="form-control requisition_hdr_id" id="requisition_hdr_id" name="requisition_hdr_id" size="16" type="hidden" value="{{ $row->requisition_hdr_id }}" readonly>
{{ csrf_field() }}
<div class="card">
<div class="card-header">
<div class="col-md-12">
        <div class="col-md-4">
            <label class="align_left"> Requisition Date:<?php echo date(\Session::get('p_date_format'),strtotime($row->requisition_date));?></label></br>
            <label class="align_left">  Requisition Source:<span class='requisition_source span_color'>{{ $row->requisition_source }}</span></label>
        </div>
    <div class="col-md-4">
        <label class="align_left">  Requisition Status:<span class='requisition_status span_color'>{{ $row->requisition_status }}</span></label>
            <input type="hidden" id="requisition_no" name="requisition_no" class="form-control requisition_no" value="{{ $row->requisition_no }}" readonly>

        </div>
        <div class="col-md-4">
            <label class="align_left">Created By:<span class='create_by span_color'></span></label>
        </div>
       </div>
</div>

<div class="card-body card-block ">

		<!------------------------------------- Body content start here ---------------------------->
		<div class="row">
			<div class="col-md-12">



				<div class="row">
					<div class="col-md-6">
                <div class="form-group row" style="display:none">
			<label for="inputIsValid" class="form-control-label col-md-4">Requisition Date</label>
			<div class="col-md-6">
			<div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
			<input class="form-control requisition_date datepicker" id="requisition_date" name="requisition_date" size="16" type="text" value="{{ $row->requisition_date }}" readonly >
			<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
			</div>
			<input type="hidden" id="requisition_date" value="{{ $row->requisition_date }}" />
			</div>
			<div class="col-md-2 showline">
			</div>
		</div>
		
                <div class="form-group row " >
                                  <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red;" >*</span>Requestor Name</label>
                                  <div class="col-md-6 requester_div">
                                      <select name='requestor_id' rows='5' class='form-control requestor_id select2' data-show-subtext="true" data-live-search="true" required tabindex="1">
                                                  {!! $requestor_id !!}
                                      </select>
                                  </div>
                                  <div class="col-md-2 showinline">
                                  <span class="showspan"><i class="fa fa-refresh jcr_requestor_id"></i></span>
                                  </div>
                </div>
<?php if($return_url=="productionindent"){ ?>
  <div class="form-group row ">
			<label for="inputIsValid" class="form-control-label col-md-4">Consider Po</label>

			<div class="col-md-6 requester_div">
                            <input type="checkbox" name="considerpo[]" value="1" <?php if($row->considerpo =="1") { echo "checked"; } else { echo ""; } ?> class="considerpo">  
				
			</div>
			
    </div>
	<?php } ?>
	</div>
<div class="col-md-6">
<?php if($return_url=="productionindent"){ ?>
	 <div class="form-group row " >
			<label for="inputIsValid" class="form-control-label col-md-4">Reference No</label>

			<div class="col-md-6">
				<input type="text" id="reference_no" name="reference_no" class="form-control reference_no" value="{{ $row->reference_no }}" readonly>
                   
			</div>
			
	     </div>
	<?php } ?>
    <div class="form-group row" style="display:none">
			<label for="inputIsValid" class="form-control-label col-md-4">Requisition Source</label>
			<div class="col-md-6">
				<select type="text" name="requisition_source" id="requisition_source" class="form-control requisition_source" value="{{ $row->requisition_source }}" readonly>
				<option value="">--select--</option>
				<option <?php if($row->requisition_source =="INTERNAL") { echo "selected"; } else { echo ""; } ?> value="INTERNAL">INTERNAL</option>
				<option <?php if($row->requisition_source =="INDENT") { echo "selected"; } else { echo ""; } ?> value="INDENT">INDENT</option>
				</select>
			</div>
			<div class="col-md-2">
			</div>
		</div>

      <div class="form-group row" style="display:none">
			<label for="inputIsValid" class="form-control-label col-md-4">Requisition Status</label>
			<div class="col-md-6">
                            <select type="text" name="requisition_status" id="requisition_status" class="form-control requisition_status" readonly>
					<option value="">--Please Select--</option>
					<option <?php if($row->requisition_status =="DRAFT") { echo "selected"; } else { echo ""; } ?> value="DRAFT">DRAFT</option>
					<option <?php if($row->requisition_status =="INITIATED") { echo "selected"; } else { echo ""; } ?> value="INITIATED">INITIATED</option>
                                        <option <?php if($row->requisition_status =="APPROVED") { echo "selected"; } else { echo ""; } ?> value="APPROVED">APPROVED</option>
                                        <option <?php if($row->requisition_status =="REJECTED") { echo "selected"; } else { echo ""; } ?> value="REJECTED">REJECTED</option>
				</select>
			</div>
		</div>
     <div class="form-group row" style="display:none">
			<label for="inputIsValid" class="form-control-label col-md-4">Organization</label>
			<div class="col-md-6">
				<select name='organization_id' rows='5' class='form-control organization_id'  data-show-subtext="true" data-live-search="true"  >
					{!! $organization_id !!}
				</select>
			</div>
			<div class="col-md-2 showline">
			</div>
    </div>
               <div class="form-group row" style="display:none">
			<label for="inputIsValid" class="form-control-label col-md-4">Created By</label>
			<div class="col-md-6">
				<select name='created_by' rows='5' class='form-control created_by'  data-show-subtext="true" data-live-search="true"  >
					{!! $created_by !!}
				</select>
			</div>
			<div class="col-md-2 showline">
			</div>
		</div>

</div>
				</div>
<!--Karthigaa Purpose For Display Dynamic Columns from Column Permission Setting-->
<h5 class="myheaders">Additional Details</h5>
<div>
<div class="row">
<div class="col-md-6">
                <?php $i=0; $j=0; foreach($enabled_columns as $index=>$val) {  if($val->action=='1') $required="required"; else $required='';?>
               <?php if($i!=$j) { $j=$i;?>
                 </div>
                       <div class="col-md-6">
               <?php } ?>

                     <?php if($val->column_name=='project_id' && $val->active==1) { $i++; ?>
             <div class="form-group row" >
                                <label for="inputIsValid" class="form-control-label col-md-4"><?php if($required != '') { ?><span style="color:red;">*</span><?php } ?>Project Name</label>
                                <div class="col-md-6 proj_div">
                                <select name='project_id' rows='5' <?php echo $required;?> class='form-control project_id select2' data-show-subtext="true" data-live-search="true" tabindex="2" >
                                        {!! $project_id !!}
                                        </select>
                                </div>
                                <div class="col-md-2 showinline">
                                                <span class="showspan"> <i class="fa fa-refresh jcr_project_id"></i></span>
                                        </div>
                </div>
                      <?php } ?>
                     <?php if($val->column_name=='remarks' && $val->active==1) { $i++; ?>
                    <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4"><?php if($required != '') { ?><span class="req" style="color:red;">*</span><?php }   ?>  <span class="req" style="color:red;">*</span>  Remarks</label>
			<div class="col-md-6">
                            <input type="text" id="remarks" <?php echo $required;?> name="remarks" class="form-control remarks" value="{{ $row->remarks }}" tabindex="3" >
			</div>
			<div class="col-md-2">
			</div>
		 </div>
                <?php } }?>

    </div>
				</div>


		</div>
                        
</div>
</div>

	<!------------------------------------------------------------------------------------------>

</div>
<div class="row">
<div class="col-md-12">
    <?php if($return_url!="purchaserequisitionapprove"){?>
<a href="javascript:void(0);"  class="add_row additem" rel=".rcopy"><i class="fa fa-plus"></i> New Item</a>
<?php } ?>
 <div id="preview-area" class="chandru">
    <table class="overflow-y preview po_req_table">


<thead >
<tr>
    <th >
        Line No
    </th>
    <th class="pdtdiv">
        Product
    </th>
    <th >
        &nbsp;
    </th>
    <th class="pdtdes_div hide">
       Supplier Part No
    </th>
    <th class="pdtdes_div">
        Product Description
    </th>
    <th>
        Uom Code
    </th>
	<?php if($return_url=="productionindent"){ ?>
    <th>Indent Qty</th>
	<th>Qoh</th>
	<?php } ?>
	<th>
        Qty
    </th>
    <th>
      Need By Date
    </th>
    <th>
        Comments
    </th>
    <th>&nbsp;</th>

</tr>
</thead>
<tbody class="po_req_lines_body">
    <?php if(count($linedata)>=1) { ?>
        @foreach($linedata as $key=>$value)
        <tr class="rcopy clone">
            <td >
                <input type="hidden" name="bulk_requisition_line_id[]" class="form-control input-sm bulk_requisition_line_id" value="{{ $value->requisition_line_id }}" >
            </td>
            <td>
                <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="{{ $key + 1 }}" readonly="readonly">
            </td>
            <td class="pdtdiv">
                <select name="bulk_product_id[]" class="select2 bulk_product_id  parsley-validated" required="required">{!! $value->product_id !!}</select>
            </td>
            <td><i class="fa fa-search productsearch"></i></td>
            <td class="pdtdes_div hide">
                <select name="bulk_part_no[]" class="select2 bulk_part_no  parsley-validated">{!! $value->part_no !!}</select>
            </td>
            <td class="pdtdes_div">
                <input type="text" name="bulk_product_description[]" class="form-control input-sm bulk_product_description input_qty_width" value="{{ $value->product_description }}">
            </td>
            <td class="uomdiv">
                <select name="bulk_uom_code_id[]" id="bulk_uom_code_id" class="select2 bulk_uom_code_id">{!! $value->uom_code_id !!}</select>
            </td>
				<?php if($return_url=="productionindent"){ ?>
			 <td>
                <input type="text" name="bulk_indent_qty[]" class="form-control input-sm bulk_indent_qty " value="{{ $value->indent_qty }}" readonly>
            </td>
           <td>
                <input type="text"  class="form-control input-sm bulk_qoh " value="{{ $value->qoh }}" readonly>
            </td>
			
			<?php } ?>
            <td>
                <input type="text" name="bulk_qty[]" class="form-control input-sm bulk_qty " value="{{ $value->qty }}" required="required">
            </td>
            <td class="nbdate_div">
                <input type="text" name="bulk_need_by_date[]" class="form-control input-sm datepicker bulk_need_by_date" value="{{ $value->need_by_date }}" required readonly="true">
            </td>
            <td class="cmnts_div">
                <input type="text" name="bulk_comments[]" class="form-control bulk_comments " value="{{ $value->comments }}" required="required">
                <!--<textarea name="bulk_comments[]" class="form-control input-sm bulk_comments" rows="1">{{ $value->comments }}</textarea>-->
            </td>
             <?php if($return_url!="purchaserequisitionapprove"){?>
            <td ><a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
                <input type="hidden" name="counter[]">
            </td>
             <?php } ?>
        </tr>

        @endforeach
        <?php } if(count($linedata) < 1 ) { ?>
            <tr class="rcopy clone">
                <td >
                    <input type="hidden" name="bulk_requisition_line_id[]" class="form-control input-sm bulk_requisition_line_id" value="" >
                </td>
                <td>
                    <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="1" readonly="readonly">
                </td>
                <td class="pdtdiv">
                    <select name="bulk_product_id[]" class="select2 bulk_product_id  parsley-validated" required="required">{!! $product_id !!}</select>
                </td>
                <td class="pdtsrchdiv"> <i class="fa fa-search productsearch"></i></td>
                <td class="pdtdes_div hide">
                    <select name="bulk_part_no[]" class="select2 bulk_part_no  parsley-validated">{!! $part_no !!}</select>
                
            </td>
                <td class="pdtdes_div">
                    <input type="text" name="bulk_product_description[]" class="form-control input-sm bulk_product_description input_qty_width" value="">
                </td>
                <td class="uomdiv">
                    <select name="bulk_uom_code_id[]" id="bulk_uom_code_id" class="select2 bulk_uom_code_id">{!! $uom_code_id !!}</select>
                </td>
                <td>
                    <input type="text" name="bulk_qty[]" class="form-control bulk_qty " value="" required="required">
                </td>
                <td class="nbdate_div">
                    <input type="text" name="bulk_need_by_date[]" class="form-control input-sm datepicker bulk_need_by_date" value="" required>
                </td>
                <td class="cmnts_div">
                    <input type="text" name="bulk_comments[]" class="form-control bulk_comments " value="" required="required">
                </td>
                <?php if($return_url!="purchaserequisitionapprove"){?>
                <td ><a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
                    <input type="hidden" name="counter[]">
                </td>
                <?php } ?>
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

                      <?php if($return_url=='purchaserequisition')
                    {
				         if($row->requisition_status=='DRAFT')
						 { ?>
							<button name="apply" type="button" class="btn saveform applychanges draft" value="APPLYCHANGES">Draft</button>
			                             <button name="submit" type="button" class="btn save saveform" value="SAVE">submit</button>
							 <button name="submit" type="button" class="btn save saveform" value="SAVENEW">submit and New</button>

						<?php  } else
						{ ?>
							
							<!-- <button type="button" class="btn save saveform" value="DRAFT">Draft</button> -->
			<button name="submit" type="button" class="btn save saveform" value="SAVE">submit</button>
							<button name="submit" type="button" class="btn save saveform" value="SAVENEW">submit and New</button>
							
						<?php }
				       ?>


              <?php }
			    else if ($return_url=='purchasecopyrequisition') { ?>
            <button name="apply" type="button" class="btn saveform applychanges draft" value="APPLYCHANGES">Draft</button>
          <!--  <button type="button" class="btn save saveform" value="DRAFT">Draft</button> -->
            <button name="submit" type="button" class="btn save saveform" value="SAVENEW">Save and New</button>
            <button name="submit" type="button" class="btn save saveform" value="SAVE">Save</button>
<?php } else if($return_url=="productionindent"){ ?>
			 <button name="submit" type="button" class="btn save saveform" value="SAVE">Save</button>
			<?php } else { ?>
			<button type="button" class="btn save saveform approve" value="APPROVE">Approve</button>
			<button type="button" class="btn save saveform reject" value="REJECT">Reject</button>
			<?php } ?>
                        <a class='btn cancel' onclick='location.href="{{ url($return_url) }}"'>Cancel</a>
	<!--<a class='btn cancel' onclick='location.href="{{ url($pageModule) }}"'>Cancel</a>-->


		</div>
	</div>
</div>
</div>


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
<input  type="hidden" class="form-control input-sm bulk_hidden_date datepicker" value="">
<!--<div id="preloader">
       <img src="https://jrlma.ca/wp-content/plugins/gallery-by-supsystic/src/GridGallery/Galleries/assets/img/loading.gif">
    </div>-->
</div>
	</form>




<script>
$(document).ready(function(){

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
            
	 


/*Karthigaa Purpose For Default Organization & User*/
	var org=$('.organization_id').val();
	if(org==''){
		var organization = '<?php echo Session::get('organization'); ?>' ;
                $('.organization_id').val(organization).change();
	}
var user = '<?php echo Session::get('id'); ?>' ;
    $('.created_by').val(user).change();
    $('.organization_id,.requisition_status,.created_by,.uomdiv').attr('readonly','readonly').css('pointer-events','none');
$(".create_by").html($('.created_by option:selected').text());
$('.org').html($('.organization_id option:selected').text());

<?php if($return_url=='purchaserequisitionapprove') { ?>
    //$('.requester_div,.proj_div,.pdtdiv,.pdtsrchdiv,.uomdiv,.cmnts_div,.nbdate_div').css('pointer-events','none');
    $('.requester_div,.proj_div,.pdtdiv,.pdtsrchdiv,.uomdiv,.nbdate_div,.bulk_product_description,.bulk_qty').css('pointer-events','none');
    $('.productsearch,.jcr_requestor_id,.jcr_project_id').css('display','none');
//	$('input').attr("readonly", true);
	$('select').attr("readonly", true);
	$(".remarks").attr("readonly",false);
        $('.cmnts_div').attr("readonly",false);

	<?php } ?>
	
	
	
$(".approve").on('click',function(){
    $("#requisition_status").val("APPROVED").change();
});

$(".reject").on('click',function(){
    $("#requisition_status").val("REJECTED").change();
});

/*deepika purpose:consider po based on indent */
	$(document).on('click','.considerpo',function(){
if ($('input[type=checkbox]').prop('checked')==true) {
 $('.bulk_product_id').each(function(i,v){
var prdid=$(this).val();
	 var indqty= $('.bulk_indent_qty'+i).val();
var url="{{ URL::to('considerpoqty')}}/"+prdid;
	 $.get(url,function(data){
		 var data=$.trim(data);
		 if(data!=""){
		 var indreqqty=parseFloat(indqty)-parseFloat(data);
		$('.bulk_qty'+i).val(indreqqty); 
		 }else{
			$('.bulk_qty'+i).val(indqty);  
		 }
	 });
	});
}else{
	$('.bulk_product_id').each(function(i,v){
		 var indqty= $('.bulk_indent_qty'+i).val();
			$('.bulk_qty'+i).val(indqty);  
	});
	
}
	});
	/*end*/

  /*Karthigaa purpose for Jcombo refresh*/
$(document).on('click','.jcr_project_id',function(){
$(".project_id").jCombo("{{ URL::to('jcomboform?table=m_projects_t:project_id:project_name') }}&order_by=project_name asc",
{selected_value:""});
});

$(document).on('click','.jcr_requestor_id',function(){
$(".requestor_id").jCombo("{{ URL::to('jcomboform?table=hr_employee_t:employee_id:first_name') }}&order_by=first_name asc",
{selected_value:""});
});
var data ="{{\Session::get('j_date_format')}}";
$(".add_row").on('click',function(){
  var form = $('#poreq_form');
  form.parsley().destroy();
});

$(".add_row").relCopy(data);
$('.add_row').click(function(){
    changeClassfields();
	
	var rowCount = $('.po_req_table tbody tr').length;
	var index  = Number(rowCount) - 1;
	var dateval = $('.bulk_need_by_date0').val();
	$('.bulk_need_by_date'+index).val(dateval);
        var ind=$(".clone").last('tr').index();
            $('.bulk_product_id'+ind).html('');
            $('.bulk_product_id'+ind).html("<?php echo $product_id ?>");
            $('.bulk_uom_code_id'+ind).html("<?php echo $uom_code_id ?>");
});


/*Karthigaa Purpose For Display Same Date for Multiple Rows **/
$(document).on('change','.bulk_need_by_date0',function(){
		var needby_date=$(this).val();
                var hide_date=$('.bulk_hidden_date').val();
        
    $( ".bulk_need_by_date" ).each(function( indexs ){
            var dates=$('.bulk_need_by_date'+indexs).val();    
            if(hide_date ==dates || dates=="" ){
                $('.bulk_need_by_date'+indexs).val(needby_date);
                }
                $('.bulk_hidden_date').val(needby_date);
            });
      });
$(document).on('click','.remove',function(){
	var index = $(this).closest('tr').index();
	var rowCount = $('.po_req_table tbody tr').length;
	if(rowCount > 1)
	{
		$($(this).closest("tr")).remove();
                removeclassfields();
	}
	else
	{
		notyMsg('info',"You Can't Delete Atleast One row should be there");
	}
});
var index = $('.clone').closest('tr').index();
changeClassfields();

 /*karthigaa purpose for load uom code based on product */
   $(document).on('change','.bulk_product_id',function(){
          var product_id = $(this).val();
			if(product_id !='')
			{
			var index = ($(this).closest('tr').index());
			var pdtcount = pdtcheck(product_id,index);
				if(pdtcount <= 0){
				  var url = "{{ URL::to('porequom') }}/"+product_id;
				   $.get(url , function(data)
				   {
					 var data = $.trim(data);
					 $('.bulk_uom_code_id'+index).val(data).trigger('change');
				   });
				}
				else
				{
					var msg 	 = $(".bulk_product_id" + index + ' option:selected').text();
					var message  = '<span style="color:#fdff65">'+msg+'</span>'+' Product Already Selected';
					notyMsgs('info',message);
					rowdataEmpty(index);
				}

			}
        });
/****Qty Validation*******/
$(".bulk_qty").keyup(function() {
    var $this = $(this);
    $this.val($this.val().replace(/[^\d.]/g, ''));
});
 /**** To Empty the Rowdata when product Empty ********/
	function rowdataEmpty(index)
	{
	$(".bulk_product_id" + index).val('').change();
	$(".bulk_uom_code_id" + index).val('').change();
	$(".bulk_qty" + index).val('');
	$(".bulk_need_by_date" + index).val('');
	$(".bulk_comments" + index).val('');
	$(".bulk_qty" + index).trigger('change');
	}
/**** To Empty the Rowdata when product Empty End********/

$(document).on('click','.productsearch',function(){
	var index = ($(this).closest('tr').index());
	 $('.pdtindex').val(index);
	 $('#productModal').modal('show');
	 $('#productModal').width("100%");
	});
//karthigaa purpose for product search grid
	var mypdtgrid = $("#productgrid"),
    pagerSelector = "#pager",
    myAddButton = function(options) {
        mypdtgrid.jqGrid('navButtonAdd',pagerSelector,options);
        mypdtgrid.jqGrid('navButtonAdd','#'+mypdtgrid[0].id+"_toppager",options);
    }
	var groupname="'RAW MATERIALS'";
	var gname="'PACKING MATERIALS'";
        var grp=[];
        grp.push(groupname);
	grp.push(gname);
        var prdcatopt="{{ $prdcatopt}}";
	var prdnameopt="{{ $prdnameopt }}";
        var group="{{$group}}";
            mypdtgrid.jqGrid({
            url: "{{ URL::to('getProductgridData') }}?prggrp="+grp,
			datatype: "json",
			mtype: "GET",
			height: 320,
			width: 1000,
             colModel: [
			{ name: "product_code", label: "Product Code", width:55},
		 	{ name: "group_name", label: "Product Group", editoptions:{value:group}, width:55},
			{ name: "category_name", label: "Product Category", editoptions:{value:prdcatopt}, width:55},
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
			pager: pagerSelector,
			toppager:true,
			searching: {
			defaultSearch: "cn"
			}
		   });
            jQuery(mypdtgrid).jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
            jQuery("#gs_productgrid_product_group_id").select2();
jQuery("#gs_productgrid_product_category_id").select2();
             mypdtgrid.jqGrid('navGrid',pagerSelector,
            {cloneToTop:true,edit:false,add:false,del:false,search:true});
           //  $('#refresh_productgrid_top').find('.fa-refresh').addClass('product_refresh');
	$('#refresh_productgrid_top > div > span').addClass('product_refresh');
  $('.product_refresh').click(function(){
           $('input[id*="gs_"]').val("");
           $('select[id*="gs_"]').select2('val',['']);
            var url = "{{URL::to('productgroupid')}}?module_name=purchaserequisition";
            $.get(url, function(data) 
            {
                var condition = 'product_group_id in('+data+')';
            $(".bulk_product_id").jCombo("{{ URL::to('jcomboform?table=m_products_t:product_id:concatenated_product') }}&order_by=product_id asc"+'&parent='+condition,
            {selected_value:""});
                
            });
           
        });
myAddButton ({
caption:"Select Product",
title:"Product",
buttonicon :'ui-icon-plus',
		onClickButton:function(){
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
			notyMsg('error','Please Select a row');
			}
		}
});

     $('.req').hide();
$(document).on('click','.saveform',function() {
 $('#panel_add').trigger('click'); //for expanding accordin
	var btnval		= $(this).val();
         if(btnval == 'APPLYCHANGES')
         {
             $('.remarks').attr('required',false);
                $("#requisition_status").val('DRAFT');
         }
            else if(btnval == 'DRAFT')
            {
                $('.remarks').attr('required',false);
                $("#requisition_status").val('DRAFT');
            }
            else if(btnval=='APPROVE')
            {
                
                $('.remarks').attr('required',false);
                $("#requisition_status").val('APPROVED');
            }
	        else if(btnval=='SAVE' || btnval=='SAVENEW')
            {
                 $('.remarks').attr('required',false);
				$("#requisition_status").val('INITIATED');
            }
            else if(btnval=='REJECT')
            {
                $('.req').show();
                    
                
                $('.remarks').attr('required',true);
                $("#requisition_status").val('REJECTED');
            }
            else
            {
                
                 $('.remarks').attr('required',false);
                $("#requisition_status").val('INITIATED');
            }

	       
	var form = $('#poreq_form');
	validationrule('poreq_form');
	var url			="{{ URL::to('purchaserequisitionsave') }}";
	var red_url		="{{ URL::to($return_url) }}";
	var create_url	="{{ URL::to('purchaserequisitioncreate') }}/0";



	if(btnval != 'APPLYCHANGES'){
         
		form.parsley().validate();
		var form = $('#poreq_form');
        qtyrequired();
		form.parsley().validate();
       
                if (form.parsley().isValid()) {
                     $('.ajaxLoading').show();
                       change_date();

                   var formdata	= $('#poreq_form').serialize();
		$.post(url,formdata,function(data){
                    var status  = data.status;
                     var msg     = '<span style="color:#090065">'+data.auto_no+'</span>  '+data.message;
                     var id      = data.id;
                     var auto_no = data.auto_no;
                     var edit_url	="{{ url('purchaserequisitioncreate') }}/"+id;
		if(btnval == "SAVE" || btnval == "DRAFT" || btnval == "APPROVE" || btnval == "REJECT" ){
			notyMsg(status,msg);
//			setTimeout(function(){
			window.location.href=red_url;
//			}, 1500);
		}
		else{
			notyMsg(status,msg);
//			setTimeout(function(){
			window.location.href=create_url;
//			}, 1500);
		}
		});
		}
	}
	else
	{
			$('.ajaxLoading').show();
             change_date();
	var formdata	= $('#poreq_form').serialize();
	$.post(url,formdata,function(data)
		{
		var status = data.status;
		var msg     = '<span style="color:#090065">'+data.auto_no+'</span>  '+data.message;
		var id     = data.id;
		var edit_url	="{{ URL::to('purchaserequisitioncreate') }}/"+id;
			notyMsg(status,msg);
//			setTimeout(function(){
			window.location.href=edit_url;
//			}, 1500);

		});
	}
});

  
});
    
  function qtyrequired()
    {
$(".bulk_qty").each(function(index)
 {
    var qty=$(this).val();
    if(qty==0)
    {
        $(".bulk_qty"+index).val('');
    }
        
    });
}   
function changeClassName(className){
$('.' + className).each(function (index)
{
if (className == "bulk_line_no")
{
$(this).val(index + 1).attr("readonly", 1);
}
else if( className == "bulk_need_by_date" && index >0 ){
	var dates0=$('.bulk_need_by_date0').val();
        $('.bulk_hidden_date').val(dates0);
	var dates=$('.bulk_need_by_date'+index).val();
        if(dates0 == dates || dates =="undefined" || dates=="" ) {
           $(this).val(dates0);
        }
	$('.dates'+index).css("pointer-events","none");
    }

$(this).removeClass(className + '0');
$(this).addClass(className + index);
});
}

function changeClassfields(){
    changeClassName('bulk_requisition_line_id');
    changeClassName('bulk_line_no');
    changeClassName('bulk_product_id');
    changeClassName('bulk_product_description');
    changeClassName('bulk_uom_code_id');
    changeClassName('bulk_qty');
    changeClassName('bulk_need_by_date');
    changeClassName('bulk_comments');
    changeClassName('bulk_indent_qty');
    changeClassName('bulk_qoh');
    }
     function removeclassfields(){
            removeClass('bulk_requisition_line_id');
            removeClass('bulk_line_no');
            removeClass('bulk_product_id');
            removeClass('bulk_product_description');
            removeClass('bulk_uom_code_id');
            removeClass('bulk_qty');
            removeClass('bulk_need_by_date');
            removeClass('bulk_comments');
            removeClass('bulk_indent_qty');
            removeClass('bulk_qoh');
    }
/************ karthigaa purpose to remove row action ********************/
function removeClass(className){
	var rowCount = $('.po_req_table tbody tr').length;
	for(var i=0;i<=rowCount;i++)
	{
	$('.po_req_table tbody tr').find('.'+className).removeClass(className+i);
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
                
            $('#moved6').css('pointer-events','none');
           /* $(document).on('change','.bulk_product_id',function()
            {
                var product_id = $(this).select2('val');
                var supplier_id = $('.supplier_id').select2('val');
                var index = ($(this).closest('tr').index());
                if(product_id !='' && supplier_id != '')
                {
                    var url = "{{URL::to('getpartno')}}?supplier_id="+supplier_id+"&product_id="+product_id;
                    $.get(url,function(response)
                    {
                        var data = $.trim(response);
                        
                        if($.trim(response) != '')
                        {
                            $('.bulk_part_no'+index).select2('val',[data]);
                        }
                        else{
                             $('.bulk_part_no'+index).select2('val',['']);
                        }
                       
                        
                    });
                } 
                else
                {
                    notyMsg('error','Please Choose Supplier');
                }
            });  */    
            
            
	</script>
<script>
$(window).on('load',function(){
       //preloader
       var preLoder = $("#preloader");
       preLoder.fadeOut(500);
       var backtoTop = $('.back-to-top')
       backtoTop.fadeOut(100);
   });
</script>
@include('layouts.php_js_validation')
@endsection

