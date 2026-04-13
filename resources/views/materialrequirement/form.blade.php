@extends('layouts.header')
@section('content')


<span class="ui_close_btn"></span>
 <div class="ajaxLoading"></div>
<h2 class="heads">Material Requirement
    <span class="ui_close_btn"><a href="{{ URL::to('materialrequirement') }}" class="collapse-close pull-right btn-danger" ></a></span>
</h2>
<div class="card" style="margin-bottom:220px;">
<div class="card-body card-block headerdiv1">
<form method="post" action="" id="materialrequirement" data-parsley-validate >
<input type="hidden" value="" name="save_status" id="save_status" />

{{ csrf_field() }}
<!--***************start*******************-->

<div class="row">


<div>
	<div class="row">
	   <div class="col-md-4">
            <div class="form-group row">
                <label for="inputIsValid" class="form-control-label col-md-6"><span class="req">*</span>Batch No</label>
                <div class="col-md-6">
                    <input type="hidden" name="qc_material_req_id" id="qc_material_req_id" class="qc_material_req_id" value="{{$row->qc_material_req_id}}">
                    <select name="batch_no" id="batch_no" class="select2 batch_no" required="required">
                        {!! $row->batch_no !!}
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputIsValid" class="form-control-label col-md-6"><span class="req">*</span>Employee Name</label>
                <div class="col-md-6">
                    <select name="employee_id" id="employee_id" class="select2 employee_id" required="required">
                        {!! $row->employee_id !!}
                    </select>
                </div>
            </div>
             <div class="form-group row">
            <label for="active" class="form-control-label col-md-6">Created By</label>
            <div class="col-md-6" style="pointer-events:none;">
                <select name='created_by' rows='5' class='select2 created_by' id="created_by" >
              {!! $created_by !!}
                </select>
            </div>
        </div>
            
            
        </div>

        <div class="col-md-4">
			 
            <div class="form-group row">
                <label for="inputIsValid" class="form-control-label col-md-6"> Start Date</label>
                <div class="col-md-6">
                    <div class="input-group date start_date col-md-12" data-date="" data-date-format="yyyy mm dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                        <input type="text" id="start_date" name="start_date" class="form-control start_date datepicker" value="{{ $row->start_date }}" style="width:100%;">
                        
                    </div>
                </div>
            </div>


            <div class="form-group row">
                <label for="inputIsValid" class="form-control-label col-md-6">End Date</label>
                <div class="col-md-6">
                    <div class="input-group date end_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                        <input type="text" id="end_date" size="16" required name="end_date" class="form-control end_date datepicker" value="{{ $row->end_date }}" style="width:100%;" >
                       
                    </div>
                </div>
            </div>
            
           
            
        </div>
        <div class="col-md-4">
             <div class="form-group row">
                <label for="inputIsValid" class="form-control-label col-md-6">Remarks</label>
                <div class="col-md-6">
                    <input type="text" name='remarks' rows='3' class='form-control remarks' id="remarks" value="{{ $row->remarks}}" />
                </div>
            </div>
            <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-6">Active</label>
                    <div class="col-md-6">
                        <select name="active" class="form-control active select2">
                            <option value="Yes" <?php if($row->active=="Yes"){ echo "selected"; }?>>Yes</option>
                            <option value="No" <?php if($row->active=="No"){ echo "selected"; }?>>No</option>
                        </select>
                    </div>
                </div>
        </div>
	</div>
</div>



</div>
    <!--***************END*******************-->

<div class="row">
    <div class="col-md-12">
    <a href="javascript:void(0);" class="add_row additem" rel=".rcopy"><i class="fa fa-plus"></i> ADD</a>
    
    <!--*****************************-Linedata ***************************-->



<div id="preview-area" class="chandru">
    <table class="overflow-y preview materialreq_table">
    <thead>
        <tr>
            <th>Line No</th>
            <th>Product </th>
            <th></th>
            <th>UOM Code </th>
            <th>Issue Qty</th>
            <th>Component Qoh</th>
            <th>Comments</th>
            <th >&nbsp;</th>
        </tr>
    </thead>
    <tbody class="materialreqlines_body">
    <?php if(count($linedata)>=1) { ?>
    @foreach($linedata as $key=>$value)

        <tr class="rcopy clone">
            <td>
                <input type="hidden" name="bulk_qc_materialreq_line_id[]" class="form-control input-sm bulk_qc_materialreq_line_id" value="{{$value->qc_materialreq_line_id}}">
            </td>
            <td>
                <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="{{$value->line_no}}" readonly="readonly">
            </td>
            <td class="pdtdiv">
                <select name="bulk_product_id[]" id="bulk_product_id" class="select2 bulk_product_id" required="required">
                    {!! $value->product_id !!}
                </select>
            </td>
            <td><i class="fa fa-search productsearch"></i></td>
            <td>
                <select name="bulk_uom_code_id[]" id="bulk_uom_code_id" class="select2 bulk_uom_code_id"  >
                    {!! $value->uom_code_id !!} 
                </select>
            </td>
            
            <td>
                <input type="text" name="bulk_issue_qty[]" class="form-control input-sm bulk_issue_qty input_qty_width" value="{{$value->issue_qty}} " minlength="1" maxlength="10" required>
            </td>
           
            <td>
                <input type="text" name="bulk_component_qoh[]" readonly class="form-control input-sm bulk_component_qoh input_qty_width"   minlength="1" maxlength="8" value="{{$value->component_qoh}}">
            </td>

            <td>
                <input type="text" name="bulk_comments[]" class="form-control input-sm bulk_comments" value="{{ $value->comments }}">
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
                <input type="hidden" name="bulk_qc_materialreq_line_id[]" class="form-control input-sm bulk_qc_materialreq_line_id" value="">
            </td>
            <td>
                <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="" readonly="readonly">
            </td>
            <td class="pdtdiv">
                <select name="bulk_product_id[]" id="bulk_product_id" class="select2 bulk_product_id" required="required">
                    {!! $product_id !!}
                </select>
            </td>
            <td><i class="fa fa-search productsearch"></i></td>
            <td class="uomdiv">
                <select name="bulk_uom_code_id[]" id="bulk_uom_code_id" class="select2 bulk_uom_code_id"  >
                    {!! $uom_code_id !!} 
                </select>
            </td>
            
            <td>
                <input type="text" name="bulk_issue_qty[]" class="form-control input-sm bulk_issue_qty input_qty_width" value=" " minlength="1" maxlength="10" required>
            </td>
            
            <td>
                <input type="text" name="bulk_component_qoh[]" readonly class="form-control input-sm bulk_component_qoh input_qty_width"   minlength="1" maxlength="8" value="">
            </td>

            <td>
                <input type="text" name="bulk_comments[]" class="form-control input-sm bulk_comments" value="">
                
            </td>
            <td>
                <a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
                <input type="hidden" name="counter[]">
            </td>
        </tr>
        <?php }?>

    </tbody>
    </table>
    <input type="hidden" name="enable-masterdetail" value="true">
</div>
<!--*******************-Linedata End*******************************-->
</div>
</div>


<div class="row">
	<div class="col-lg-12 col-md-12">
		<div class="form-group text-center">
            <?php if($row->qc_material_req_id == ''){ ?>
                <button type="button" class="btn draft saveform" value="DRAFT">DRAFT</button>
            <?php } ?>
            <button type="button" class="btn save saveform" value="SAVE">Save</button>
		    <a href="{{ url('materialrequirement') }}" class='btn cancel'>Cancel</a>
		</div>
	</div>
</div>
<input type="hidden" value="MATERIAL REQUIREMENT" name="source_type" id="source_type" />
</form>

</div>

</div>

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
<input type="hidden" class="pdtindex" value="" />
<a href="#" id="scroll" style="display: none;"><span></span></a>
<style>

@media only screen and (min-width: 1500px) {
	.bulk_line_no{width:50px !important;}
	.bulk_product_id{width:260px !important;}
	.bulk_uom_code_id{width:110px !important;}
	.bulk_component_qty{width:110px !important;}
	.bulk_issue_qty{width:80px !important;}
	.bulk_subinventory_id{width:120px !important;}
	.bulk_locator_id{width:120px !important;}
	.bulk_component_qoh{width:90px !important;}
	.bulk_comments{width:180px !important;}
}
@media only screen and (min-width: 2000px) {
    .bulk_line_no{width:50px !important;}
    .bulk_product_id{width:320px !important;}
    .bulk_uom_code_id{width:140px !important;}
    .bulk_component_qty{width:140px !important;}
    .bulk_issue_qty{width:120px !important;}
    .bulk_subinventory_id{width:170px !important;}
    .bulk_locator_id{width:170px !important;}
    .bulk_component_qoh{width:140px !important;}
    .bulk_comments{width:250px !important;}
}

	.bulk_line_no{width:50px;}
	.bulk_product_id{width:260px;}
	.bulk_uom_code_id{width:110px;}
	.bulk_component_qty{width:110px;}
	.bulk_issue_qty{width:75px;}
	.bulk_subinventory_id{width:120px;}
	.bulk_locator_id{width:120px;}
	.bulk_component_qoh{width:85px;}
	.bulk_comments{width:180px;}
.modal-body{
    height: 400px;
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
<script>

$(document).ready(function()
{
    
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
        
	$('.uomdiv').css("pointer-events","none");	
	$(".add_row").relCopy(data);
        changeclassfields();

    $('.add_row').click(function() {
        changeclassfields();
    });
	
	$(document).on('click','.remove',function()
    {
    	var index = $(this).closest('tr').index();
    	var rowCount = $('.materialreq_table tbody tr').length;
    	if(rowCount > 1)
    	{
    		$($(this).closest("tr")).remove();
    		removeclassfields();
    	}
    	else
    	{
    		notyMsg("error","You Can't Delete Atleast One row should be there");
    	}
    });

    $(document).on('change','.bulk_product_id',function(){
        var index = ($(this).closest('tr').index());
        var product_id = $(this).val();
        if(product_id !='')
			{
			var index = ($(this).closest('tr').index());
			var pdtcount = pdtcheck(product_id,index);
				if(pdtcount <= 0){
        var url = "{{ URL::to('uomvalue') }}/"+product_id;
        $.get(url , function(data)
        {
            var dt =$.trim(data['uom']);
            var qoh =$.trim(data['available_qty']);
            $('.bulk_uom_code_id'+index).select2('val',dt);
            $('.bulk_component_qoh'+index).val(qoh);
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
 /**** To Empty the Rowdata when product Empty ********/
	function rowdataEmpty(index)
	{
	$(".bulk_product_id" + index).val('').change();
	$(".bulk_uom_code_id" + index).val('').change();
	$(".bulk_comments" + index).val('');
	
	}
    $(document).on('change','.bulk_issue_qty',function(){
        var index = ($(this).closest('tr').index());
        var iss_qty = $(this).val();
        var com_qty = $('.bulk_component_qoh'+index).val();
        if(parseInt(com_qty) < parseInt(iss_qty) ){
            notyMsg('info','Issue qty not more than component qty');
            $('.bulk_issue_qty'+index).val('');
        }

    });

    $(document).on('change','.bulk_subinventory_id',function(){
        var index = ($(this).closest('tr').index());
        var sub_id = $(this).val();
        var condition ='subinventory_id ='+sub_id;
        $(".bulk_locator_id"+index).jCombo("{{ URL::to('jcomboform?table=m_sublocators_t:sublocator_id:locator_code') }}&order_by=locator_code asc"+"&parent="+condition,
        {selected_value:""});

    });

    $('.productsearch').click(function()
    {
        var index = ($(this).closest('tr').index());
        $('.pdtindex').val(index);
        $('#productModal').modal('show');
        $('#productModal').width("100%");

        $(mypdtgrid).trigger("reloadGrid", [{current: true}]);
    });

    var mypdtgrid = $("#productgrid"),
    pagerSelector = "#pager",
    myAddButton = function(options) {
        mypdtgrid.jqGrid('navButtonAdd',pagerSelector,options);
        mypdtgrid.jqGrid('navButtonAdd','#'+mypdtgrid[0].id+"_toppager",options);
    };
    var prdcatopt="{{ $prdcatopt}}";
    var prdnameopt="{{ $prdnameopt }}";
    var prdgrpopt="{{ $prdgrpopt }}";
    mypdtgrid.jqGrid({
        url: "{{ URL::to('getProductgridData') }}",
        datatype: "json",
        mtype: "GET",
        height: 320,
        width: 1000,
         colModel: [
            { name: "product_code", label: "Product Code", width:55},
            { name: "product_group_id", label: "Product Group",stype:'select', editoptions:{value:prdgrpopt}, width:55},
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

	/*deepika purpose:qty validation*/
		$(document).on('keypress','.bulk_issue_qty,.bulk_component_qty,.bulk_component_qoh', function(ev){
			var regex = new RegExp("^[0-9.]+$");
					var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
					if (regex.test(str)) {
						return true;
					}
					ev.preventDefault();
					return false;
		});
	/*end*/
    /*copy paste validation*/
     $('.bulk_issue_qty').bind("cut copy paste", function(e) {
        e.preventDefault();
            });
    /*copy paste validation*/

    $('#save_status').val('');	

    $(document).on('click','.saveform',function()
    {
    	var btnval		= $(this).val();
        if(btnval == 'DRAFT')
            var savestatus = 'DRAFT';
        else if(btnval == 'SAVE' || btnval == 'SAVENEW')
            var savestatus = 'SAVE';

        $('#save_status').val(savestatus);

        var url		= "{{ URL::to('materialrequirementsave') }}";
        var red_url		="{{ URL::to('materialrequirement') }}";
        var create_url	="{{ URL::to('materialrequirementcreate') }}/0";
        validationrule('materialrequirement');
        qtyrequired();
        
        if(btnval != 'DRAFT'){
            var form = $('#materialrequirement');
            form.parsley().validate();
            var form = $('#materialrequirement');
            form.parsley().validate();
            if (form.parsley().isValid())
            {
                 $('.ajaxLoading').show();
                change_date();
                        var formdata    = $('#materialrequirement').serialize();

                $.post(url,formdata,function(data)
                {
                    var status      = data.status;
                    var msg     = '<span style="color:#090065">'+'</span>  '+data.message;
                    var id          = data.id;
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
        }else{
            $('.ajaxLoading').show();
             change_date();
              
                        var formdata    = $('#materialrequirement').serialize();

            $.post(url,formdata,function(data)
            {
                var status      = data.status;
                var msg     = '<span style="color:#090065">'+'</span>  '+data.message;
                var id          = data.id;
                
                notyMsg(status,msg);
                setTimeout(function(){
                    window.location.href=red_url;
                }, 1500);
                
            });
        }
        

    });


});

function qtyrequired()
  {
    $(".bulk_issue_qty").each(function(index){
       var req=$(this).val();
if(req==0)
{
  $('.bulk_issue_qty'+index).val('');
}
    });
  }

/************ Maruthu purpose to remove row action ********************/
function removeClass(className)
{
	var rowCount = $('.materialreq_table tbody tr').length;
	for(var i=0;i<=rowCount;i++)
	{
	$('.materialreq_table tbody tr').find('.'+className).removeClass(className+i);
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
function changeclassfields(){
    changeClassName('bulk_qc_materialreq_line_id');
    changeClassName('bulk_line_no');
    changeClassName('bulk_product_id');
    changeClassName('bulk_uom_code_id');
    changeClassName('bulk_component_qty');
    changeClassName('bulk_issue_qty');
    changeClassName('bulk_component_qoh');
    changeClassName('bulk_subinventory_id');
    changeClassName('bulk_locator_id');
    changeClassName('bulk_comments');
}
function removeclassfields(){
    removeClass('bulk_qc_materialreq_line_id');
    removeClass('bulk_line_no');
    removeClass('bulk_product_id');
    removeClass('bulk_uom_code_id');
    removeClass('bulk_component_qty');
    removeClass('bulk_issue_qty');
    removeClass('bulk_component_qoh');
    removeClass('bulk_subinventory_id');
    removeClass('bulk_locator_id');
    removeClass('bulk_comments');
}
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

$(document).ready(function(){ 
    $(window).scroll(function(){ 
        if ($(this).scrollTop() > 100) { 
            $('#scroll').fadeIn(); 
        } else { 
            $('#scroll').fadeOut(); 
        } 
    }); 
    $('#scroll').click(function(){ 
        $("html, body").animate({ scrollTop: 0 }, 600); 
        return false; 
    }); 
});

	</script>
<style>
#table_scroll tbody {
    display:block;
    max-height:300px;
    overflow:auto;
}
#table_scroll table thead tr {
    display:table;
}
#scroll {
    position:fixed;
    right:7px;
    bottom:2.40em;
    cursor:pointer;
    width:30px;
    height:30px;
    background-color:rgba(0,0,0,0.5);
    text-indent:-9999px;
    display:none;
    /*-webkit-border-radius:60px;
    -moz-border-radius:60px;
    border-radius:60px*/
}
#scroll span {
    position:absolute;
    top:50%;
    left:50%;
    margin-left:-8px;
    margin-top:-12px;
    height:0;
    width:0;
    border:8px solid transparent;
    border-bottom-color:#ffffff;
}
#scroll:hover {
    background-color:rgba(0, 18, 103, 0.59);;
    opacity:1;filter:"alpha(opacity=100)";
    -ms-filter:"alpha(opacity=100)";
}
</style>
@include('layouts.php_js_validation')
@endsection
