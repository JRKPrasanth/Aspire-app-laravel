@extends('layouts.header')
@section('content')
<h3 class="text-danger">Production Indent</h3>
@include('layouts.breadcrumb')



<form method="post" action="" id="poreq_form" class="poreq_form" data-parsley-validate>
	
<input type="hidden" value="" name="savestatus" id="savestatus" /> <?php //dd($row->w_requisition_indent_hdr_id); ?>
 <input class="form-control w_requisition_indent_hdr_id" id="w_requisition_indent_hdr_id" name="w_requisition_indent_hdr_id" size="16" type="hidden" value="{{ $row->w_requisition_indent_hdr_id }}" readonly>
{{ csrf_field() }}
	
	
<div class="card shadow-lg rounded-4 border-0">
  <div class="card-header bg-white border-0 py-3">
    <div class="row g-3 align-items-center">
      
           <!-- Column 1 -->
      <div class="col-md-3">
        <div class="mb-2">
          <span class="fw-semibold text-primary">Indent Date:</span>
          <span class="badge bg-primary text-white">
         <?php echo date("Y-m-d", strtotime($row->indent_date ?: date("Y-m-d"))); ?>
          </span>
        </div>
      </div>

      <!-- Column 2 -->
      <div class="col-md-3">
        <div class="mb-2">
          <span class="fw-semibold text-secondary">Created By:</span>
          <span class="badge bg-secondary create_by"></span>
        </div>
      </div>

            <div class="col-md-3">
        <div class="mb-2">
          <span class="fw-semibold text-dark">Source:</span>
          <span class="badge bg-info text-dark">
            {{ $row->indent_source }}
          </span>
        </div>
      </div>

      <!-- Column 3 -->
      <div class="col-md-3">
        <div class="mb-2">
          <span class="fw-semibold text-success">Indent Status:</span>
          <span class="badge 
            {{ $row->indent_status == 'Approved' ? 'bg-success' : 
               ($row->indent_status == 'Pending' ? 'bg-warning text-dark' : 
               'bg-danger') }}">
            {{ $row->indent_status }}
          </span>
        </div>
        <input type="hidden" id="indent_no" name="indent_no"
               class="form-control indent_no" value="{{ $row->indent_no }}" readonly>
      </div>

    </div>
  </div>
	


	
    <div class="card-body mt-2">
      <!-- Body content -->
      <div class="row g-4">
        <div class="col-md-6">
          <!-- Hidden: Indent Date field (kept for compatibility) -->
          <div class="row align-items-center d-none">
            <label class="col-md-4 col-form-label">Indent Date</label>
            <div class="col-md-8">
              <div class="input-group">
                <input class="form-control indent_date datepicker" id="indent_date" name="indent_date" type="text" value="{{ $row->indent_date }}" readonly>
                <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
              </div>
              <input type="hidden" id="indent_date_hidden" value="{{ $row->indent_date }}" />
            </div>
          </div>

          <!-- Requestor Name -->
          <div class="row align-items-center">
            <label class="col-md-4 col-form-label"><span class="text-danger">*</span> Requestor Name</label>
            <div class="col-md-7 requester_div">
              <select name="requestor_id" class="form-select requestor_id select2" data-live-search="true" required>
                {!! $requestor_id !!}
              </select>
            </div>

          </div>
        </div>

        <div class="col-md-6">
          <!-- Reference No -->
          <div class="row align-items-center">
            <label class="col-md-4 col-form-label">Reference No</label>
            <div class="col-md-8">
              <input type="text" id="reference_no" name="reference_no" class="form-control reference_no" value="{{ $row->reference_no }}" readonly>
            </div>
          </div>

          <!-- Hidden keeps (Source/Status/Org/Created By) -->
          <div class="row align-items-center d-none mt-3">
            <label class="col-md-4 col-form-label">Indent Source</label>
            <div class="col-md-8">
              <select name="indent_source" id="indent_source" class="form-select indent_source" readonly>
                <option value="">--select--</option>
                <option value="INTERNAL" <?php if($row->indent_source=="INTERNAL") echo "selected"; ?>>INTERNAL</option>
                <option value="REQUEST FROM PRODUCTION" <?php if($row->indent_source=="REQUEST FROM PRODUCTION") echo "selected"; ?>>REQUEST FROM PRODUCTION</option>
              </select>
            </div>
          </div>

          <div class="row align-items-center d-none mt-3">
            <label class="col-md-4 col-form-label">Indent Status</label>
            <div class="col-md-8">
              <select name="indent_status" id="indent_status" class="form-select indent_status" readonly>
                <option value="">--Please Select--</option>
                <option value="INITIATED" <?php if($row->indent_status=="INITIATED") echo "selected"; ?>>INITIATED</option>
                <option value="CLOSED" <?php if($row->indent_status=="CLOSED") echo "selected"; ?>>CLOSED</option>
              </select>
            </div>
          </div>

          <div class="row align-items-center d-none mt-3">
            <label class="col-md-4 col-form-label">Organization</label>
            <div class="col-md-8">
              <select name="organization_id" class="form-select organization_id select2" data-live-search="true">
                {!! $organization_id !!}
              </select>
            </div>
          </div>

          <div class="row align-items-center d-none mt-3">
            <label class="col-md-4 col-form-label">Created By</label>
            <div class="col-md-8">
              <select name="created_by" class="form-select created_by select2" data-live-search="true">
                {!! $created_by !!}
              </select>
            </div>
          </div>
        </div>
      </div>

	
	



<div class="row mt-4">
  <div class="col-12 linetable">
    <div id="preview-area" class="table-responsive">
      <table class="table table-bordered clone_table" style="width: 115%;">
        <thead class="table-light">
          <tr>
            <th style="width: 80px;">Line No</th>
            <th class="pdtdiv">
              Product
            </th>
            <th class="pdtdes_div">
              Product Description
            </th>
            <th>
              Uom Code
            </th>
            <th>
              Indent Qty
            </th>
            <th>
              Need By Date
            </th>
            <th>
              Comments
            </th>
            <th style="width: 60px;"></th>
          </tr>
        </thead>
        <tbody class="clone_lines_body">

          @if(count($linedata) > 0)
            @foreach($linedata as $key => $value)
              <tr class="line-row">
                <td>
                  <input type="hidden" name="bulk_w_requisition_indent_line_id[]"
                    class="form-control input-sm bulk_w_requisition_indent_line_id"
                    value=>
                  <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="{{ $key + 1 }}"
                    readonly="readonly">
                </td>
                <td class="pdtdiv" id="pdtdiv" style="pointer-events: none;">
                  <select name="bulk_product_id[]" class="select2 bulk_product_id  parsley-validated"
                    required="required">{!! $value->product_id !!}</select>
                </td>
                <td class="pdtdes_div">
                  <input type="text" name="bulk_product_description[]"
                    class="form-control input-sm bulk_product_description input_qty_width"
                    value="">
                </td>
                <td class="uomdiv" id="uomdiv" style="pointer-events: none;">
                  <select name="bulk_uom_code_id[]" id="bulk_uom_code_id"
                    class="select2 bulk_uom_code_id">{!! $value->uom_code_id !!}</select>
                </td>
                <td>
                  <input type="text" name="bulk_qty[]" class="form-control input-sm bulk_qty " value="{{ $value->qty }}"
                    required="required" readonly="readonly">
                </td>
                <td class="nbdate_div">
                  <input type="text" name="bulk_need_by_date[]" class="form-control input-sm datepicker bulk_need_by_date"
                    value="{{ $value->need_by_date }}">
                </td>
                <td class="cmnts_div">
                  <input type="text" name="bulk_comments[]" class="form-control bulk_comments "
                    value="">

                </td>

                <td class="text-center">
                  <button type="button" class="btn btn-sm btn-danger remove-row">
                    <i class="fas fa-minus-circle"></i>
                  </button>
                </td>

              </tr>
            @endforeach
          @else
            <tr class="line-row">

              <td>
                <input type="hidden" name="bulk_w_requisition_indent_line_id[]"
                  class="form-control input-sm bulk_w_requisition_indent_line_id" value="">
                <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="1"
                  readonly="readonly">
              </td>
              <td class="pdtdiv" id="pdtdiv" style="pointer-events: none;">
                <select name="bulk_product_id[]" class="select2 bulk_product_id  parsley-validated"
                  required="required">{!! $product_id !!}</select>
              </td>

              <td class="pdtdes_div">
                <input type="text" name="bulk_product_description[]"
                  class="form-control input-sm bulk_product_description input_qty_width" value="">
              </td>
              <td class="uomdiv" id="uomdiv" style="pointer-events: none;">
                <select name="bulk_uom_code_id[]" id="bulk_uom_code_id"
                  class="select2 bulk_uom_code_id">{!! $uom_code_id !!}</select>
              </td>
              <td>
                <input type="text" name="bulk_qty[]" class="form-control bulk_qty " value="" required="required"
                  readonly="readonly">
              </td>
              <td class="nbdate_div">
                <input type="text" name="bulk_need_by_date[]" class="form-control input-sm datepicker bulk_need_by_date"
                  value="">
              </td>
              <td class="cmnts_div">
                <input type="text" name="bulk_comments[]" class="form-control bulk_comments " value="">
              </td>

              <td class="text-center">
                <button type="button" class="btn btn-sm btn-danger remove-row">
                  <i class="fas fa-minus-circle"></i>
                </button>
              </td>
            </tr>
          @endif
        </tbody>

      </table>

      <div class="text-end">
        <button type="button" class="btn btn-success btn-sm add-row">
          <i class="fas fa-plus-circle"></i> Add Row
        </button>
      </div>
    </div>

  </div>
</div>
<!-- END -->

      <div class="row mt-4">
        <div class="col-12 text-center actionbtn">
          <input type="hidden" name="submit_type" class="submit_type" value="" />

          <?php if($return_url=='productionindent') { ?>
              <button name="submit" type="button" class="btn btn-success px-4 me-2 saveform" value="SAVE"><i class="bi bi-send"></i> Submit</button>
          <?php } else if ($return_url=='purchasecopyrequisition') { ?>
              <button name="apply" type="button" class="btn btn-outline-secondary saveform px-4 me-2" value="APPLYCHANGES"><i class="bi bi-pencil-square"></i> Draft</button>
              <button name="submit" type="button" class="btn btn-success px-4 me-2 saveform" value="SAVE"><i class="bi bi-send"></i> Submit</button>
          <?php } else { ?>
              <button type="button" class="btn btn-success saveform px-4 me-2" value="APPROVE"><i class="bi bi-check2-circle"></i> Approve</button>
              <button type="button" class="btn btn-danger px-4 me-2 saveform" value="REJECT"><i class="bi bi-x-circle"></i> Reject</button>
          <?php } ?>

          <a class="btn btn-outline-danger px-4 me-2 ms-2" href="{{ url($return_url) }}">Cancel</a>
        </div>
      </div>
    </div>
  </div>

</form>

  <!-- Product Details Modal -->
  <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="productModalLabel"><i class="bi bi-box"></i> Product Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table id="productgrid" class="table table-bordered table-hover m-0 align-middle">
              <!-- grid rendered dynamically -->
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>


@endsection
@push('scripts')


<script>
	
$(document).ready(function(){

	 $('#savestatus').val('');

/* Purpose For Default Organization & User*/
	var org=$('.organization_id').val();
	if(org==''){
		var organization = '<?php echo Session::get('organization'); ?>' ;
        $('.organization_id').val(organization).change();
	}
var user = '<?php echo Session::get('id'); ?>' ;
    $('.created_by').val(user).change();
    $('.organization_id,.indent_status,.created_by').attr('readonly','readonly').css('pointer-events','none');
$(".create_by").html($('.created_by option:selected').text());
$('.org').html($('.organization_id option:selected').text());

<?php if($return_url=='productionindentapprove') { ?>
    $('.requester_div,.proj_div,.pdtdiv,.pdtsrchdiv,.uomdiv,.cmnts_div,.nbdate_div').css('pointer-events','none');
    $('.productsearch,.jcr_requestor_id,.jcr_project_id').css('display','none');
	$('input').attr("readonly", true);
	$('select').attr("readonly", true);
	$(".remarks").attr("readonly",false);

	<?php } ?>

$(".approve").on('click',function(){
    $("#indent_status").val("APPROVED").change();
});

$(".reject").on('click',function(){
    $("#indent_status").val("REJECTED").change();
});

});



/*deepika purpose:date load for multiple lines*/
	$(document).on('change','.bulk_need_by_date',function()
        {
            var index = $(this).closest('tr').index();
           
           if(index == 0)
           {
            var need_date=$(this).val();
            $(".bulk_need_by_date").each(function( indexs ) 
            {
                $('.bulk_need_by_date'+indexs).val(need_date);
            });
            }
        });
/*end*/

/*End*/
var index = $('.clone').closest('tr').index();

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
      
        var group="";
            mypdtgrid.jqGrid({
            url: "{{ URL::to('getProductgridData') }}?prggrp="+grp,
			datatype: "json",
			mtype: "GET",
			height: 320,
			width: 1000,
             colModel: [
			{ name: "product_code", label: "Product Code", width:55},
		 	{ name: "group_name", label: "Product Group", width:55},
			{ name: "category_name", label: "Product Category",width:55},
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
		onClickButton:function(){
			var index = $('.pdtindex').val();
			var gr = jQuery(mypdtgrid).jqGrid('getGridParam','selrow');
			var product_id = jQuery(mypdtgrid).jqGrid ('getCell', gr, 'product_id');
                        if(gr )
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
			notyMsg('info','Please Select one row');
			}
		}
});
	
/*Save Function*/
	$(document).on('click','.saveform',function() {
	 $('#panel_add').trigger('click'); //for expanding accordin
	var btnval		= $(this).val();
         if(btnval == 'APPLYCHANGES')
                $("#indent_status").val('DRAFT');
           
            else if(btnval=='APPROVE')
                $("#indent_status").val('APPROVED');
	        else if(btnval=='SAVE' || btnval=='SAVENEW')
				$("#indent_status").val('INITIATED');
            else if(btnval=='REJECT')
                $("#indent_status").val('REJECTED');
            else
                $("#indent_status").val('INITIATED');


	        if(btnval == 'APPLYCHANGES')
                var savestatus = 'DRAFT';
           
            else if(btnval == 'SAVE' || btnval == 'SAVENEW')
                var savestatus = 'SAVE';

			 $('#savestatus').val(savestatus);
			var form = $('#poreq_form');
			validationrule('poreq_form');
			var url			="{{ URL::to('productionindentsave') }}";
			var red_url		="{{ URL::to($return_url) }}";
			var create_url	="{{ URL::to('productionindentcreate') }}/0";



	if(btnval != 'APPLYCHANGES'){
		form.parsley().validate();
		var form = $('#poreq_form');
		form.parsley().validate();
                if (form.parsley().isValid()) {

                   var formdata	= $('#poreq_form').serialize();
		$.post(url,formdata,function(data){
                    var status  = data.status;
                     var msg     = data.message;
                     var id      = data.id;
                     var auto_no = data.auto_no;
                     var edit_url	="{{ url('productionindentcreate') }}/"+id;
		if(btnval == "SAVE" || btnval == "DRAFT" || btnval == "APPROVE" || btnval == "REJECT" ){
			showCustomAlert(msg,status);
			setTimeout(function(){
			window.location.href=red_url;
			}, 1500);
		}
		else{
			showCustomAlert(msg,status);
			setTimeout(function(){
			window.location.href=create_url;
			}, 1500);
		}
		});
		}
	}
	else
	{   
		
	var formdata	= $('#poreq_form').serialize();
	$.post(url,formdata,function(data)
		{
		var status = data.status;
		var msg     = data.message;
		var id     = data.id;
		var edit_url	="{{ URL::to('productionindentcreate') }}/"+id;
			showCustomAlert(msg,status);
			setTimeout(function(){
			window.location.href=edit_url;
			}, 1500);

		});
	}
});



// Add Row
  $(document).on('click', '.add-row', function () {
    const $lastRow = $('.clone_lines_body tr:last');
    const $newRow = $lastRow.clone(false, false); // clone without events or data

    // Clear all input and select values in the cloned row
    $newRow.find('input').val('');
    $newRow.find('select').val('').trigger('change');

    // Remove any Select2 artifacts before reinitializing
    $newRow.find('select.select2').each(function () {
      if ($.fn.select2 && $(this).hasClass("select2-hidden-accessible")) {
        $(this).select2('destroy');
      }
      $(this).removeAttr('data-select2-id');
      $(this).next('.select2').remove(); // remove the select2 container
    });

    // Append the cleaned-up cloned row
    $('.clone_lines_body').append($newRow);

    // Reinitialize select2
    $newRow.find('select.select2').select2({ width: '100%' });

    // Update line numbers
    updateLineNumbers();
  });



  // Remove button
  $(document).on('click', '.remove-row', function () {
    const rowCount = $('.clone_lines_body tr').length;
    if (rowCount > 1) {
      $(this).closest('tr').remove();
      updateLineNumbers();
    } else {
      showCustomAlert("You Can't Delete Atleast One row should be There", "warning");
    }
  });

  // Renumber Line Nos
  function updateLineNumbers() {
    $('.clone_lines_body tr').each(function (index) {
      $(this).find('.bulk_line_no').val(index + 1);
    });
  }
	
	</script>

@endpush
