@extends('layouts.header')
@section('content')
<style type="text/css">
  /*purpose to align field width based on resolution*/  
     @media  only screen and (min-width: 1500px) { 

       .bulk_line_no {width: 60px !important;}
.bulk_product_id {width: 320px !important;}
.bulk_uom_code_id {width: 100px !important;}
.bulk_qty {width: 60px !important;}
.bulk_due_date{
    width:100px !important;
}
.bulk_need_by_date{width: 122px !important;}
.bulk_comments {width: 300px !important;}

     }

 @media  only screen and (min-width: 2000px) { 

       .bulk_line_no {width: 60px !important;}
.bulk_product_id {width:400px !important;}
.bulk_uom_code_id {width: 150px !important;}
.bulk_qty {width: 110px !important;}
.bulk_due_date{
    width:120px !important;
}
.bulk_need_by_date{width: 142px !important;}
.bulk_comments {width: 400px !important;}
 }


.bulk_line_no {width: 50px}
.bulk_product_id {width: 320px;}
.bulk_uom_code_id {width: 100px;}
.bulk_qty {width: 60px;}
.bulk_due_date{
    width:82px;
}
.bulk_need_by_date{width: 102px;}
.bulk_comments {width: 200px;}
/*end*/
.has-submenu {
    width: 35px;
    height: 35px;
	}
 .uom{
	 pointer-events:none;
	}
.modal-body{
    height: 350px;
    overflow-y:auto 
}

</style>
<span class="ui_close_btn"></span>
 <div class="ajaxLoading"></div>
<h2 class="heads">Job Work Out Order <span class="ui_close_btn"><a href="{{ URL::to('jobworkoutorder') }}" class="collapse-close pull-right btn-danger" ></a>
</span></h2>
	<form method="post" action="" id="jobworkoutorder" data-parsley-validate>
<input type="hidden" value="" name="savestatus" id="savestatus" />

{{ csrf_field() }}
<div class="card">
<div class="card-body card-block headerdiv1">


	<!------------------------------------- Body content start here ---------------------------->
		<div class="row">
			<div class="col-md-12">
			<div>
				<div class="row">
						<div class="col-md-4">
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Jobworkoutorder No</label>
			<div class="col-md-6">
				
			<input class="form-control jobworkoutorder_hdr_id" id="jobworkoutorder_hdr_id" name="jobworkoutorder_hdr_id" width="100%" type="hidden" value="{{ $row->jobworkoutorder_hdr_id }}" readonly >
			<input type="text" id="joboutorder_no" name="joboutorder_no" class="form-control joboutorder_no" width="100%" value="{{ $row->joboutorder_no }}" readonly >
			</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Return Date</label>
			<div class="col-md-6">
			<div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
			<input class="form-control return_date" id="return_date" name="return_date" width="100%" type="text" value="{{ $row->return_date }}" readonly="true">

			</div>
			<input type="hidden" id="return_date" value="{{ $row->return_date }}" />
			</div>
		</div>
			</div>
	<div class="col-md-4">
            <div class="form-group row" >
                <label class="form-control-label col-md-4" for="subcontract_supplier_id"><span style="font-size:15px;color:red;">*</span>Subcontractor</label>
			<div class="col-md-6">
                            <select name='subcontract_supplier_id'  width="100%" class='select2 subcontract_supplier_id' id="subcontract_supplier_id" required>
                                {!! $subcontract_supplier_id !!}
				</select>
			</div>
    		</div>
		<div class="form-group row">
        <label for="inputIsValid" class="form-control-label col-md-4">Remarks</label>
        <div class="col-md-6">
			<input type="text" name="remarks" id="remarks" class="form-control remarks" value="{{ $row->remarks }}" width="100%"/>
        </div>
		
	</div>

	</div>
	<div class="col-md-4">
<div class="form-group row">
		<label for="inputIsValid" class="form-control-label col-md-4">Jobworkoutorder Status</label>
        <div class="col-md-6">
            <select name="joboutorder_status" width="100%" class="form-control joboutorder_status" data-show-subtext="true" data-live-search="true" readonly="" >
                <option value="">--select--</option>
                <option value="INITIATED" <?php if($row->joboutorder_status == "INITIATED") echo "selected"; else ""; ?>>INITIATED</option>
            </select>
        </div>
    </div>

		<div class="form-group row" >
    		<label class="form-control-label col-md-4" for="organization_id">Created By</label>
			<div class="col-md-6">
				<select name='created_by'  width="100%" class='form-control  created_by' id="created_by" readonly>
{!! $created_by !!}
				</select>
			</div>
    		</div>
	</div>
				</div>
			</div>
		</div>
		</div>
	<!------------------------------------------------------------------------------------------>
<div class="row">
<div class="col-md-12">
<!------------------------- clone row start -------------------------------->
	
<a href="javascript:void(0);" class="add_row additem" rel=".rcopy"><i class="fa fa-plus"></i> New Item</a>

<br>
<!------------------------- clone row End-------------------------------->

<!-------------------------Linedata -------------------------------->
 <div id="preview-area" class="linesscroll">
    <table class="overflow-y preview jobworkoutorder_table">
        <thead>
            <tr>

                <th>Line No</th>
                <th>&nbsp;</th>
                <th class="pdtdiv" >Product</th>
                <th>&nbsp;</th>
                <th>Uom Code </th>
                <th>Qty</th>
                <th>Due Date</th>
                <th>Comments</th>
                <th></th>
            </tr>
        </thead>
        <tbody class="workorder_lines_body">
			<!--edit mode-->
            <?php if(count($linedata)>=1) { ?>
                @foreach($linedata as $key=>$value)
                <tr class="clone rcopy">
                    <td >
                        <input type="hidden" name="bulk_jobworkoutorder_line_id[]" class="form-control input-sm bulk_jobworkoutorder_line_id" value="{{ $value->jobworkoutorder_line_id }}">
                    </td>
                    <td>
                        <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="{{ $key + 1 }}" readonly="readonly">
                    </td>
                    <td></td>
                    <td class="pdtdiv">
                        <select name="bulk_product_id[]" id="bulk_product_id" class="bulk_product_id select2" required="required">
                        {!! $value->product_id !!}
                        </select>
                    </td>
                    <td><i class="fa fa-search productsearch"></i></td>
                    <td class="uom">
                        <select name="bulk_uom_code_id[]" id="bulk_uom_code_id" class="select2 bulk_uom_code_id" data-show-subtext="true" data-live-search="true" style="pointer-events:none;">
                        <option>Select</option>
                            <option value=''> {!! $value->uomcode_id !!} </option>

                        </select>
                    </td>
                    <td>
                        <input type="text" name="bulk_qty[]" class="form-control input-sm bulk_qty input_qty_width" value="{{ $value->qty }}" minlength="1" maxlength="4" required="required">
                    </td>
                    <td>
                        <div class="input-group m-b">
                            <input type="text" name="bulk_need_by_date[]" class="form-control bulk_need_by_date datepicker"  value="{{$value->need_by_date}}"/>
                        </div>
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
			<!--create mode-->
                <?php } if(count($linedata) < 1 ) { ?>
                    <tr class="rcopy clone">
                        <td>
                            <input type="hidden" name="bulk_jobworkoutorder_line_id[]" class="form-control input-sm bulk_jobworkoutorder_line_id" value="">
                        </td>
                        <td>
                            <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="1" readonly="readonly">
                        </td>
                         <td></td>
                        <td class="pdtdiv">
                            <select name="bulk_product_id[]" id="bulk_product_id" class="bulk_product_id select2" required="required">{!! $product_id !!}</select>
                        </td>
                        <td><i class="fa fa-search productsearch"></i></td>
                        <td class="uom">
                            <select name="bulk_uom_code_id[]" id="bulk_uom_code_id" class="select2 bulk_uom_code_id" data-show-subtext="true" data-live-search="true">
                                {!! $uomcode_id !!}
                            </select>
                        </td>
                        <td>
                            <input type="text" name="bulk_qty[]" class="form-control input-sm bulk_qty input_qty_width" value="" required="required">
                        </td>
                        <td>
                            <div class="input-group m-b">
                            <input type="text" name="bulk_need_by_date[]" class="form-control bulk_need_by_date datepicker"  value=""/>
                        </div>
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
        <div class="form-group text-center">
			<button type="button" class="btn save saveform" value="SAVE">SUBMIT</button>
            <button type="button" class="btn save saveform" value="SAVENEW">SUBMIT AND NEW</button>
              <a class='btn cancel' onclick='location.href="{{ url($pageModule) }}"'>Cancel</a>
        </div>
    </div>
</div>


</div>

</div>
	</form>
<!--deepika purpose:Product Search Modal-->
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
	<!--end-->
	<script>

/*deepika purpose:qty 0 required validation*/
	 function qtyrequiredvalid(){
            $('.bulk_qty').each(function(i){
                var val = $(this).val();
                if(val == 0){
                    $('.bulk_qty'+i).val('');
                }
            });
        } 
/*end*/
$(document).ready(function()
{
/*Deepika purpose: to disable fields*/
 $('.joboutorder_status,.created_by').css("pointer-events","none");
/*end*/
var data ="{{\Session::get('j_date_format')}}";
changeclassfields();
$(".add_row").relCopy(data);	/*to copy rows*/
/*deepika purpose:to destroy required validation*/
    $('.add_row').click(function()
    {
		 var form = $('#jobworkoutorder');
  form.parsley().destroy();
	changeclassfields();
});
/*end*/
/*deepika purpose:to create new row &add class for all columns*/	
	 $('.add_row').click(function()
    {
	    changeclassfields();
		var ind=$(".clone").last('tr').index();
		  <?php if($pagemode=='edit') { ?>
		$('.bulk_product_id'+ind).html("<?php echo $productid; ?>");
		 <?php } ?>
});
/*end*/
 	/*deepika purpose:qty validation*/
		$(document).on('keypress','.bulk_qty', function(ev){
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
    
     $('.bulk_qty').bind("cut copy paste", function(e) {
        e.preventDefault();
            });
    /*copy paste validation*/
	/*deepika purpose: to call save function & validate form*/
	   $('#savestatus').val('');
    $(document).on('click','.saveform',function()
    {
            var btnval		= $(this).val();
             if(btnval == 'SAVE' || btnval == 'SAVENEW')
                var savestatus = 'SAVE';
            $('#savestatus').val(savestatus);

            var url		= "{{ url('jobworkoutordersave') }}";
            var red_url		="{{ url('jobworkoutorder') }}";
            var create_url	="{{ url('jobworkoutordercreate') }}/0";
		   qtyrequiredvalid();
            validationrule('jobworkoutorder');
            
            var form = $('#jobworkoutorder');
            form.parsley().validate();
                var form = $('#jobworkoutorder');
                form.parsley().validate();

                if (form.parsley().isValid())
                {
                    $('.ajaxLoading').show();
                    change_date();/*to change date format*/
                    var formdata	= $('#jobworkoutorder').serialize();
                    $.post(url,formdata,function(data)
                    {
                        var status      = data.status;
                        var msg     = '<span style="color:#090065">'+data.auto_no+'</span>  '+data.message;
                        var id          = data.id;
                        var edit_url	= "{{ url('jobworkoutordercreate') }}/"+id;
                        if(btnval !='SAVE')
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
            
    });
/*end*/
/*deepika purpose:to remove lines*/		
            $(document).on('click','.remove',function(){
                    var index = $(this).closest('tr').index();
                    var rowCount = $('.jobworkoutorder_table tbody tr').length;
                    if(rowCount > 1)
                    {
                            $($(this).closest("tr")).remove();
                            removeclassfields();
                    }
                    else
                    {
                            notyMsg("info","You Can't Delete Atleast One row should be there");
                    }
            });
/*end*/
/*deepika purpose:to check product already selected*/
        $(document).on('change','.bulk_product_id',function(){
          var product_id = $(this).val();
          var index = ($(this).closest('tr').index());
          var url = "{{ URL::to('jobworkoutorderuom') }}/"+product_id;
			if(product_id !='')
	{
			var pdtcount = 0;
			var pdtcount = pdtcheck(product_id,index);
			if(pdtcount <= 0)
			{
               $.get(url , function(data)
					 {
			     var data = $.trim(data);
                 $('.bulk_uom_code_id'+index).select2('val',[data]);
               });
			}else
			{
				var msg 	 = $(".bulk_product_id" + index + ' option:selected').text();
					var message  = '<span style="color:#fdff65">'+msg+'</span>'+' Product Already Selected';
					notyMsgs('info',message);
				$(".bulk_product_id" + index).val('').change();
				event.preventDefault();
			}
	}
        });
/*end*/
/*deepika purpose:product search modal*/
$(document).on('click','.productsearch',function()
	{
	var index = ($(this).closest('tr').index());
	 $('.pdtindex').val(index);
	 $('#productModal').modal('show');
	 $('#productModal').width("100%");
	});
var mypdtgrid = $("#productgrid"),
    pagerSelector = "#pager",
    myAddButton = function(options) {
        mypdtgrid.jqGrid('navButtonAdd',pagerSelector,options);
        mypdtgrid.jqGrid('navButtonAdd','#'+mypdtgrid[0].id+"_toppager",options);
    };
		var groupname="'SEMI FINISHED GOODS'";
		var groupname1="'FINISHED GOODS'";
 var grp=[];
 grp.push(groupname);
 grp.push(groupname1);
	var prdcatopt="{{ $prdcatopt}}";
            mypdtgrid.jqGrid({
            url: "{{ URL::to('getProductgridData') }}?prggrp="+grp,
			datatype: "json",
			mtype: "GET",
			height: 320,
			width: 1000,
             colModel: [
			{ name: "product_code", label: "Product Code", width:55},
		 	{ name: "group_name", label: "Product Group", width:55},
			{ name: "category_name", label: "Product Category",stype:'select', editoptions:{value:prdcatopt}, width:55},
		 	{ name: "concatenated_product", label: "Product Name", width:55},
			{ name: "product_id", label: "id",hidden:true, width:55}
		],

			iconSet: "fontAwesome",
			rowNum: 10,
			rowList: [10,20,50,100,250,500,1000],
			sortorder: "asc",
			viewrecords: true,
			gridview: true,
			rownumbers:true,
			caption: "Product",
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
			var product = jQuery(mypdtgrid).jqGrid ('getCell', gr, 'product_id');
			if( gr )
			{
			$('.bulk_product_id'+index).val(product);
			$('.bulk_product_id'+index).trigger('change');
			$('#productModal').modal('hide');
			}
			else
			{
			notyMsg('info','Please Select row');
			}
		}
/*end*/
	});
	});
/*deepika purpose: function to access changeclass throught the file*/
function changeclassfields(){
changeClassName('bulk_jobworkoutorder_line_id');
changeClassName('bulk_line_no');
changeClassName('bulk_product_id');
changeClassName('bulk_uom_code_id');
changeClassName('bulk_qty');
changeClassName('bulk_need_by_date');
changeClassName('bulk_comments');
}
/*end*/
/*deepika purpose: function to access removeclass throught the file*/
function removeclassfields(){
removeClass('bulk_jobworkoutorder_line_id');
removeClass('bulk_line_no');
removeClass('bulk_product_id');
removeClass('bulk_uom_code_id');
removeClass('bulk_qty');
removeClass('bulk_need_by_date');
removeClass('bulk_comments');
}
/*end*/
/************ Maruthu purpose to remove row action ********************/
function removeClass(className)
{
	var rowCount = $('.jobworkoutorder_table tbody tr').length;
	for(var i=0;i<=rowCount;i++)
	{
	$('.jobworkoutorder_table tbody tr').find('.'+className).removeClass(className+i);
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
/*end*/
/*deepika purpose: function to add class for lines column*/
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
/*end*/
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
.bulk_uom_code_id,.organization_id,.source{
pointer-events:none;
	}
</style>
@include('layouts.php_js_validation')
@endsection
