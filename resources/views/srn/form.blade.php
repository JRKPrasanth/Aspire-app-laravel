@extends('layouts.header')
@section('content')
<h3 class="text-danger">Service Receipt Note</h3>
@include('layouts.breadcrumb')


<form method="post" action="{{ url('approvalsave') }}" id="approvalform" data-parsley-validate>
    {{ csrf_field() }}
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0">Approval Form</h5>
        </div>
        <div class="card-body">
            <div class="row g-4">
                
                <!-- GRN Date -->
                <div class="col-md-4">
                    <label class="form-label">GRN Date</label>
                    <div class="input-group">
                        <input type="text" 
                               class="form-control grn_date" 
                               id="grn_date" 
                               name="grn_date" 
                               value="{{ $row->grn_date }}" 
                               readonly 
                               tabindex="1">
                    </div>
                </div>

				<div class="form-group row" style="display:none">
<label for="inputIsValid" class="form-control-label col-md-4">GRN Number</label>
			<div class="col-md-6">
			<input class="form-control grn_id" id="grn_id" name="grn_id" size="16" type="hidden" value="{{ $row->grn_id }}" readonly>
                        <input type="text" id="grn_number" name="grn_number"  class="form-control grn_number" value="{{ $row->grn_number }}" readonly tabindex="1">
			</div>
			<div class="col-md-2">
			</div>
			</div>
				
                <!-- GRN Description -->
                <div class="col-md-4">
                    <label class="form-label">GRN Description</label>
                    <input type="text" 
                           class="form-control grn_description" 
                           id="grn_description" 
                           name="grn_description" 
                           value="{{ $row->grn_description }}" 
                           tabindex="2">
                </div>

                <!-- GRN Status -->
                <div class="col-md-4">
                    <label class="form-label">GRN Status</label>
                    <select class="form-select grn_status select2" id="grn_status" name="grn_status" tabindex="9">
                        <option value="">--Please Select--</option>
                        <option value="DRAFT" {{ $row->grn_status == 'DRAFT' ? 'selected' : '' }}>DRAFT</option>
                        <option value="INITIATED" {{ $row->grn_status == 'INITIATED' ? 'selected' : '' }}>INITIATED</option>
                    </select>
                </div>

                <!-- DC Number -->
                <div class="col-md-4">
                    <label class="form-label text-danger">*</label> DC Number
                    <input type="text" 
                           class="form-control dc_number" 
                           id="dc_number" 
                           name="dc_number" 
                           value="{{ $row->dc_number }}" 
                           required 
                           tabindex="3">
                </div>

                <!-- DC Date -->
                <div class="col-md-4">
                    <label class="form-label">DC Date</label>
                    <div class="input-group">
                        <input type="text" 
                               class="form-control dc_date" 
                               id="dc_date" 
                               name="dc_date" 
                               value="{{ $row->dc_date }}" 
                               tabindex="4">
                    </div>
                </div>

                <!-- Supplier Type -->
                <div class="col-md-4">
                    <label class="form-label text-danger">*</label> Supplier Type
                    <select class="form-select supplier_type select2" id="supplier_type" name="supplier_type" required tabindex="9">
                        <option value="">--Please Select--</option>
                        <option value="SUPPLIER" {{ $row->supplier_type == 'SUPPLIER' ? 'selected' : '' }}>SUPPLIER</option>
                        <option value="SUBCONTRACT" {{ $row->supplier_type == 'SUBCONTRACT' ? 'selected' : '' }}>SUBCONTRACT</option>
                    </select>
                </div>

                <!-- Supplier Name -->
                <div class="col-md-4">
                    <label class="form-label text-danger">*</label> Supplier Name
                    <select class="form-select supplier_id select2" name="supplier_id" required tabindex="8">
                        {!! $supplier_id !!}
                    </select>
                </div>

                <!-- PO Number -->
                <div class="col-md-4">
                    <label class="form-label text-danger">*</label> PO Number
                    <select class="form-select po_number select2" name="po_number" required>
                        {!! $po_number !!}
                    </select>
                </div>

                <!-- PO Date -->
                <div class="col-md-4">
                    <label class="form-label">PO Date</label>
                    <input type="text" 
                           class="form-control po_date" 
                           id="po_date" 
                           name="po_date" 
                           value="{{ $row->po_date }}" 
                           readonly 
                           tabindex="7">
                </div>

                <!-- Reference Number -->
                <div class="col-md-4">
                    <label class="form-label">Reference No</label>
                    <input type="text" 
                           class="form-control reference_number" 
                           id="reference_number" 
                           name="reference_number" 
                           value="{{ $row->reference_number }}" 
                           readonly 
                           tabindex="10">
                </div>

                <!-- Source -->
                <div class="col-md-4">
                    <label class="form-label">Source</label>
                    <select class="form-select source select2" name="source" tabindex="11">
                        <option value="">--Select--</option>
                        <option value="GIN" {{ $row->source == 'GIN' ? 'selected' : '' }}>GIN</option>
                        <option value="GRN" {{ $row->source == 'GRN' ? 'selected' : '' }}>GRN</option>
                        <option value="PO" {{ $row->source == 'PO' ? 'selected' : '' }}>PO</option>
                        <option value="LABOURPO" {{ $row->source == 'LABOURPO' ? 'selected' : '' }}>LABOURPO</option>
                    </select>
                </div>
            </div>
        </div>

<!-------------------------Linedata -------------------------------->

<div class="row mt-4">
  <div class="col-12 linetable">
<div id ="preview-area" class="table-responsive">
  <table class="table table-bordered clone_table">
    <thead class="table-light">
      <tr>
        <th style="width: 80px;">Line No</th>
        <th>Product</th>
        <th>Uom Code</th>
        <th>Description</th>
        <th>Ordered Qty</th>
        <th>Qty</th>
        <th style="width: 60px;"></th>
      </tr>
    </thead>
<tbody class="clone_lines_body">
  @if(count($linedata) > 0)
    @foreach($linedata as $key => $value)
      <tr class="line-row">
<td><input type="hidden" name="bulk_grn_line_id[]" class="form-control input-sm bulk_grn_line_id" value="{{ $value->grn_line_id }}" >
<input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="{{ $key + 1 }}" readonly="readonly" ></td>
<td class="prddiv" style="pointer-events:none;"><select name="bulk_product_id[]" class=" select2 bulk_product_id" required="required" >{!! $value->product_id !!}</select></td>
  <?php if($row->source=="GRN"){?>
<td class="pdtsearch_div"><i class="fa fa-search productsearch"></i></td>
 <?php } ?>
<td class="rdlny">
<select name="bulk_uom_code_id[]" id="bulk_uom_code_id" class="form-control select2 bulk_uom_code_id" >
{!! $value->uom_code_id !!}
</select>
</td>
<td>
<input type="text" name="bulk_packed_discription[]" class="form-control input-sm bulk_packed_discription input_qty_width" value="{{ $value->product_description }}">
</td>
<td>
<input type="text" name="bulk_qty[]" class="form-control input-sm bulk_qty input_qty_width" value="{{ $value->qty }}">
</td>
<td>
<input type="text" name="bulk_receive_qty[]" class="form-control input-sm bulk_receive_qty input_qty_width" value="{{ $value->qty }}">
</td>
        <td class="text-center">
          <button type="button" class="btn btn-sm btn-danger remove-row">
            <i class="fas fa-minus-circle"></i>
          </button>
        </td>
      </tr>
    @endforeach
  @endif
</tbody>
  </table>

  <?php if($row->source!="PO" ){ ?>
  <div class="text-end">
    <button type="button" class="btn btn-success btn-sm add-row">
      <i class="fas fa-plus-circle"></i> Add Row
    </button>
  </div>
 <?PHP } ?> 
</div>

  </div>
</div>
<!-------------------------Linedata End-------------------------------->

<div class="row mt-4 mb-3">
	<div class="col-lg-12 col-md-12">
		<div class="form-group text-center">
			<button type="button" class="btn btn-secondary saveform px-4 me-2"  value="DRAFT">Draft</button>
			<button type="button" class="btn btn-success saveform px-4 me-2" value="SAVE">Save</button>
			  <a class='btn btn-danger px-4 me-2' href="{{ url('srn') }}">Cancel</a>
		</div>
	</div>
</div>
</div>
</form>



@endsection
@push('scripts')

<script>

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
	
	
    /*Karthigaa Purpose For Duplicate Entry Check*/
       var dup_chk = true;
	 function duplicate_validate()
        {

            var dc_number = $(".dc_number").val();
            var edit_id = $("#grn_id").val();
            var supplier_id = $(".supplier_id").val();

            $.ajax({
                cache: false,
                url: "{{URL::to('dccheckname')}}", //this is your uri
                type: 'GET',
                dataType: 'json',
                async : false,
                data: {dc_number : dc_number,edit_id : edit_id,supplier_id:supplier_id},
                success: function(response)
                {
                    console.log(response);
                    if(response == 1)
                    {
                        $('.dup_name').html('DC Number:'+dc_number+' Already Exists');
                        $('.dup_name').show();
                        $(".dc_number").val('');
                        dup_chk = false;

                    }
                    else if(response == 0)
                    {
                        var html ="";
                         $('.dup_name').hide();
                         
                        dup_chk = true;

                    }

                },
                error: function(xhr, resp, text)
                {
                    console.log(xhr, resp, text);
                }


            });
            return dup_chk;
        }
	

	$('.rdlny').css("pointer-events",'none');

	$('.bulk_remaining_qty,.bulk_qty,.bulk_pending_qty').attr("readonly",true);

	<?php if($row->source=='PO'){ ?>
	$('.supplier_id,.prddiv,.bulk_uom_code_id,.gin_id,.grn_status,.source,.po_date,.podiv').css("pointer-events","none");
	$('.star').hide();
	$('.total_packs').removeAttr('required',false);
	<?php } ?>
    $('.gin_id,.grn_status,.source,.po_date').css("pointer-events","none");
    
	
/*End Purpose For Show Supplier & Subcontractor Based on Supplier Type*/	
	$(document).ready(function(){
		 $('#savestatus').val('');
         /*Purpose For Submit Function*/
      $(document).on('click','.saveform',function(){
       var btnval		= $(this).val();
        if(btnval == 'DRAFT'){
                $("#grn_status").val('DRAFT');
            }
            else{
                $("#grn_status").val('INITIATED');
            }
            var url		= "{{ url('genratesrnsave') }}";
            var red_url		="{{ url('srn') }}";
            var create_url	="{{ url('genratesrn') }}/0";
            var form = $('#approvalform');
            if(btnval != 'DRAFT')
            {

              form.parsley().validate();
               var check=  duplicate_validate();
    	    if (form.parsley().isValid() && check==true){

                    var formdata	= $('#approvalform').serialize();
                    var $btn = $(this);            
                    $btn.prop('disabled', true);
                    $.post(url,formdata,function(data)
                    {
                        var status      = data.status;
                        var msg     =    data.message;
                        var id          = data.id;
                        var edit_url	= "{{ url('genratesrn') }}/"+id;
                        if(btnval !='SAVE')
                        {

                            showCustomAlert(msg,status);
                            setTimeout(function(){
                            window.location.href=create_url;
                            }, 1500);
                        }
                        else
                        {
                            showCustomAlert(msg,status);
                            setTimeout(function(){
                            window.location.href=red_url;
                            }, 1500);
                        }
                    });
       }
                }
          
            else
            {


		         var formdata	= $('#approvalform').serialize();
            $.post(url,formdata,function(data)
            {
                    var status = data.status;
                    var msg    = data.message;
                    var id     = data.id;
                    var edit_url	="{{ URL::to('srnedit') }}/"+id;
                        showCustomAlert(msg,status);
                        setTimeout(function(){
                        window.location.href=edit_url;
                        }, 1500);
                });
            }
  return false;
    });
	
    var data ="{{\Session::get('j_date_format')}}";


    /*Validation*/
    $(document).on('keypress','.bulk_box_qty,.product_qty', function(ev){
                    var regex = new RegExp("^[0-9]+$");
                                    var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                                    if (regex.test(str)) {
                                            return true;
                                    }
                                    ev.preventDefault();
                                    return false;
            });

/**  purpose add button create box**/
  $(document).on('click','.boxdetails',function(){
                var index = ($(this).closest('tr').index());
	         $('.pdtindex').val(index);
                var po_id=$('.po_number').val();
                var product_id= $('.bulk_product_id'+index).val();
                var bulk_box_qty=$('.bulk_box_qty'+index).val();
                var bulk_box_product=$('.bulk_box_product'+index).val();
                var bulk_serial_number=$('.bulk_serial_number'+index).val();
                var bulk_batch_number=$('.bulk_batch_number'+index).val();
                if(bulk_box_qty!=""){
                  <?php if($row->source=='PO'||$row->source=='GIN'){?>
                               <?php if($row->supplier_type=='SUPPLIER'){ ?>
                        if(product_id!="" ){
			var url="{{URL::to('boxdetails')}}/"+bulk_box_qty+"/"+bulk_box_product+"/"+bulk_serial_number+"/"+bulk_batch_number+"?po_id="+po_id+"&product_id="+product_id;
                    }
                  <?php } else{ ?>
                      var url="{{URL::to('boxdetails')}}/"+bulk_box_qty+"/"+bulk_box_product+"/"+bulk_serial_number+"/"+bulk_batch_number;
                  <?php } ?>
                  <?php } else {?>
                      var url="{{URL::to('boxdetails')}}/"+bulk_box_qty+"/"+bulk_box_product+"/"+bulk_serial_number+"/"+bulk_batch_number;
                  <?php }?>
                    
			$.get(url , function(data){
				$('.batch_table tbody').html('');
				$('.batch_lines').append(data);
                                $('#boxModal').modal('show');
                                $('#boxModal').width("50%");
			});
                    }
                    else{
                        showCustomAlert("Please enter Box Quantity",'info');
                    }
        });

        $(document).on('click','.addbox',function(){
                var add=0;
		var product_qty=[];
		var batch_number=[];
		var serial_number=[];
		var index=$('.pdtindex').val();
			
	 $('.product_qty').each(function(k,v){
		  var val=parseFloat($(this).val());
			if(($(this).val()) !='')
				{
					var qty = $(this).val();
				}
				else
				{
					var qty = 0;
				}
	 if(isNaN(val))
	 {
		 val=0;
	 }
	
			add=add+val;
				product_qty[k]=qty;
		});
                var close_popup=0;
		$('.batch_number').each(function(k,v){
                    var batchnumber = $(this).val();
			    if(batchnumber == ""){
					notyMsg("info","Please enter Batch Number");
                                        close_popup=1;
				}
		  if(($(this).val()) !='')
				{
					var qty1 = $(this).val();
				}
				else
				{
					var qty1 = 0;
				}
                    batch_number[k]=qty1;
		});
		$('.serial_number').each(function(k,v){
		  
			if(($(this).val()) !='')
				{
					var qty2 = $(this).val();
				}
				else
				{
					var qty2 = 0;
				}
                    serial_number[k]=qty2;
		});
	 	
		$('.bulk_box_product'+index).val(product_qty);
		$('.bulk_serial_number'+index).val(serial_number);
		$('.bulk_batch_number'+index).val(batch_number);
		$('.bulk_receive_qty'+index).val(add);
			if(close_popup==0){
			 $('#boxModal').modal('hide');
                     }
	});

	});


    $(document).on("focus", ".dc_date", function () {
        $(this).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            minDate: -30, 
            maxDate: 0,
            showAnim: "slideDown",
            yearRange: "-25:+0",
        });
    });
	
</script>

@endpush
