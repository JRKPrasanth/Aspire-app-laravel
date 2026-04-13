@extends('layouts.header')
@section('content')
<h3 class="text-danger">Goods Receipt Note</h3>
@include('layouts.breadcrumb')


<form method="post" action="{{ url('approvalsave') }}" id="approvalform" data-parsley-validate>
    {{ csrf_field() }}
    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">GRN Approval</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">

                <!-- GRN Date -->
                <div class="col-md-4">
                    <label class="form-label">GRN Date</label>
                    <div class="input-group">
                        <input type="text" class="form-control grn_date" id="grn_date" name="grn_date"
                            value="{{ $row->grn_date }}" readonly tabindex="1">
                    </div>
                </div>

				<div class="form-group row" style="display:none">
<label for="inputIsValid" class="form-control-label col-md-4">GRN Number</label>
			<div class="col-md-7">
			<input class="form-control grn_id" id="grn_id" name="grn_id" size="16" type="hidden" value="{{ $row->grn_id }}" readonly>
								<input type="text" id="grn_number" name="grn_number"  class="form-control grn_number" value="{{ $row->grn_number }}" readonly tabindex="1">
					</div>
		</div>
				
                <!-- GRN Description -->
                <div class="col-md-4">
                    <label class="form-label">GRN Description</label>
                    <input type="text" name="grn_description" id="grn_description"
                        value="{{ $row->grn_description }}" class="form-control grn_description" tabindex="2">
                </div>

                <!-- GRN Status -->
                <div class="col-md-4" style="pointer-events:none;">
                    <label class="form-label">GRN Status</label>
                    <select name="grn_status" id="grn_status" class="form-select select2 grn_status" tabindex="9">
                        <option value="">--Please Select--</option>
                        <option {{ $row->grn_status == "DRAFT" ? 'selected' : '' }}>DRAFT</option>
                        <option {{ $row->grn_status == "INITIATED" ? 'selected' : '' }}>INITIATED</option>
                    </select>
                </div>

                <!-- DC Number -->
                <div class="col-md-4">
                    <label class="form-label text-danger">* DC Number</label>
                    <input type="hidden" name="master_grn_id" value="0">
                    <input type="hidden" name="qcstatus" value="0">
                    <input type="text" id="dc_number" name="dc_number"
                        value="{{ $row->dc_number }}" required tabindex="3" class="form-control dc_number">
                        <span class="badge bg-danger dup_name d-none"></span>
                </div>

                <!-- DC Date -->
                <div class="col-md-4">
                    <label class="form-label">DC Date</label>
                    <div class="input-group">
                        <input type="text" class="form-control dc_date" id="dc_date" name="dc_date"
                            value="{{ $row->dc_date }}" tabindex="4">
                    </div>
                </div>

             <div class="form-group row d-none">
			<label for="inputIsValid" class="form-control-label col-md-4">GIN Number</label>
			<div class="col-md-7">
				<select name='gin_number' rows='5' class='form-control gin_id' data-show-subtext="true" data-live-search="true" readonly tabindex="5">
				{!! $row->gin_id  !!}
				</select>
			</div>
			<div class="col-md-2 showinline">

			</div>
	     </div>

                <!-- PO Number -->
                @if($row->source=='PO' || $row->source=='GIN')
                    @if($row->supplier_type=='SUPPLIER')
                        <div class="col-md-4">
                            <label class="form-label text-danger">* PO Number</label>
                            <select name="po_number[]" class="form-select select2 po_number" multiple>
                                {!! $po_number !!}
                            </select>
                        </div>
                    @endif
                @endif

                <!-- PO Date -->
                @if($row->source=='PO' || $row->source=='GIN')
                    <div class="col-md-4">
                        <label class="form-label">PO Date</label>
                        <input type="text" name="po_date" class="form-control po_date" value="{{ $row->po_date }}" readonly>
                         
                    </div>
                @endif

                <!-- Supplier Type -->
                <div class="col-md-4">
                    <label class="form-label text-danger">* Supplier Type</label>
                    <select name="supplier_type" id="supplier_type" class="form-select select2 supplier_type" required tabindex="9">
                        <option value="">--Please Select--</option>
                        <option {{ $row->supplier_type=="SUPPLIER" ? 'selected' : '' }}>SUPPLIER</option>
                        <option {{ $row->supplier_type=="SUBCONTRACT" ? 'selected' : '' }}>SUBCONTRACT</option>
                    </select>
                </div>

                <!-- Subcontract Name -->
                <div class="col-md-4 showcontract">
                    <label class="form-label text-danger">* Subcontract Name</label>
                    <select name="subcontract_supplier_id" class="form-select subcontract_supplier_id select2"  tabindex="8">
                        {!! $subcontract_supplier_id !!}
                    </select>
                </div>

                <!-- Supplier Name -->
                <div class="col-md-4 showsupplier">
                    <label class="form-label text-danger">* Supplier Name</label>
                    <select name="supplier_id" class="form-select supplier_id select2" tabindex="8">
                        {!! $supplier_id !!}
                    </select>
                    <input type="hidden" name="invoice_created" class="form-control invoice_created" value="No">
                </div>

                <!-- Reference No -->
                <div class="col-md-4" style="pointer-events:none;">
                    <label class="form-label">Reference No</label>
                    	<input class="form-control reference_id" id="reference_id" name="reference_id" size="16" type="hidden" value="{{$row->reference_id}}" readonly >
                    <input type="text" id="reference_number" name="reference_number"
                        value="{{ $row->reference_number }}" class="form-control reference_number">
                </div>

                <!-- Source -->
                <div class="col-md-4">
                    <label class="form-label">Source</label>
                    <select name="source" class="form-select select2 source" readonly tabindex="11">
                        <option value="">--select--</option>
                        <option {{ $row->source=="GIN" ? 'selected' : '' }}>GIN</option>
                        <option {{ $row->source=="GRN" ? 'selected' : '' }}>GRN</option>
                        <option {{ $row->source=="PO" ? 'selected' : '' }}>PO</option>
                    </select>
                </div>

                <!-- Total Product Packs -->
                <div class="col-md-4">
                    <label class="form-label text-danger">* Total Product Packs</label>
                    <input type="text" name="total_packs" class="form-control total_packs"
                        value="{{ $row->total_packs }}" required tabindex="12">
                </div>

                <!-- Checked By -->
                <div class="col-md-4">
                    <label class="form-label">Checked By</label>
                    <select name="checked_by" id="checked_by" class="form-select select2 checked_by" >
                        {!! $checked_by !!}
                    </select>
                </div>

                <!-- Approved By -->
                <div class="col-md-4"> 
                    <label class="form-label">Approved By</label>
                    <select name="approved_by" id="approved_by" class="form-select select2 approved_by" >
                        {!! $approved_by !!}
                    </select>
                </div>

            </div>
        </div>


<!-------------------------Linedata -------------------------------->
<div class="row mt-4">
  <div class="col-12 linetable">
<div id="preview-area" class="table-responsive">
  <table class="table table-bordered clone_table">
    <thead class="table-light">
      <tr>
        <th style="width: 80px;">Line No</th>
        <th>Product</th>
        <th>Po Number</th>
        <th>Uom Code</th>
        <th>Ordered Qty</th>
        <th>Pending Qty</th>
        <th>No.of Batch Number</th>
        <th>Add Qty</th>
        <th>Total Product Qty</th>
        <th style="width: 60px;"></th>
      </tr>
    </thead>
<tbody class="clone_lines_body">
  @if(count($linedata) > 0)
    @foreach($linedata as $key => $value)
      <tr class="line-row">
<td><input type="hidden" name="bulk_grn_line_id[]" class="form-control input-sm bulk_grn_line_id" value="{{ $value->grn_line_id }}" >
<?php if($row->source=='PO'){?>	
<input type="hidden" name="bulk_po_hdr_id[]" class="form-control input-sm bulk_po_hdr_id" value="{{ $value->po_hdr_id }}" >
<?php }?>
<input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="{{ $key + 1 }}" readonly="readonly" ></td>
<td class="prddiv" ><select name="bulk_product_id[]" class=" select2 bulk_product_id" required="required" >{!! $value->product_id !!}</select></td>

  <td class="rdlny">
  <select name="bulk_po_no[]" id="bulk_po_no" class="form-control select2 bulk_po_no" >
{!! $value->po_no !!}
</select>
</td>
  
<td class="rdlny">
<select name="bulk_uom_code_id[]" id="bulk_uom_code_id" class="form-control select2 bulk_uom_code_id" >
{!! $value->uom_code_id !!}
</select>
</td>
<td>
<input type="text" name="bulk_qty[]" class="form-control input-sm bulk_qty input_qty_width" value="{{ $value->qty }}">
</td>
<td>
<input type="text" name="bulk_pending_qty[]" class="form-control input-sm bulk_pending_qty input_qty_width" value="{{ $value->pending_qty }}">
</td>

           <td>
             
               <input type="hidden" name="bulk_box_product[]" class="form-control input-sm bulk_box_product input_qty_width" value="{{ $value->box_product }}">
               <input type="hidden" name="bulk_batch_number[]" class="form-control input-sm bulk_batch_number input_qty_width" value="{{ $value->batch_number }}">
               <input type="hidden" name="bulk_serial_number[]" class="form-control input-sm bulk_serial_number input_qty_width" value="{{ $value->serial_number }}">
           
               
               <input type="text" name="bulk_box_qty[]" class="form-control input-sm bulk_box_qty input_qty_width" value="{{ $value->box_qty }}" required>
        </td>
        
       <td><i class="fa fa-plus boxdetails" style="color: #142e78;
    font-size: 13px;
    font-weight: bolder;
    padding: 5px;
    border: 1px solid;
    cursor: pointer;"></i></td>

     
                <td>
                    <input type="text" name="bulk_receive_qty[]" class="form-control input-sm bulk_receive_qty input_qty_width" value="{{ $value->receive_qty }}" required="required" readonly>
                  
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

  <?php if($row->source!="PO" || $row->source!="GIN"){ ?>
  <div class="text-end">
    <button type="button" class="btn btn-success btn-sm add-row">
      <i class="fas fa-plus-circle"></i> Add Row
    </button>
  </div>
 <?php } ?> 
</div>
  </div>
</div>
<!-- END -->

<input type="hidden" name="enable-masterdetail" value="true">
<!-------------------------Linedata End-------------------------------->

<div class="row mt-4 mb-3">
	<div class="col-lg-12 col-md-12">
		<div class="form-group text-center">
			<button type="button" class="btn btn-secondary saveform px-4 me-2" value="DRAFT">Draft</button>
			<button type="button" class="btn btn-success saveform px-4 me-2" value="SAVE">Save</button>
			  <a class='btn btn-danger px-4 me-2' href="{{ url('grn') }}">Cancel</a>
		</div>
	</div>
</div>

<!-- popups -->		
		
<!-- Product Details Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content shadow-lg border-0">
      
      <!-- Modal Header -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="productModalLabel">
          <i class="bi bi-box-seam"></i> Product Details
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <!-- Modal Body -->
      <div class="modal-body">
        <div class="table-responsive">
          <table id="productgrid" class="table table-bordered table-striped table-hover align-middle">
            <!-- Data will be injected dynamically -->
          </table>
        </div>
      </div>
      
      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="bi bi-x-lg"></i> Close
        </button>
      </div>
      
    </div>
  </div>
</div>

<input type="hidden" class="pdtindex" value="" />

<!-- Box Details Modal -->
<div class="modal fade" id="boxModal" tabindex="-1" aria-labelledby="boxModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-l">
    <div class="modal-content shadow-lg border-0">
      
      <!-- Modal Header -->
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="boxModalLabel">
          <i class="bi bi-inboxes"></i> Box Details
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <!-- Modal Body -->
      <div class="modal-body">
        <div class="table-responsive">
          <table id="batch" class="table table-bordered table-striped table-hover align-middle w-75 mx-auto">
            <tbody class="batch_lines">
              <!-- Rows will be injected dynamically -->
            </tbody>
          </table>
        </div>
      </div>
      
      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="bi bi-x-lg"></i> Close
        </button>
      </div>
      
    </div>
  </div>
</div>
		

</div>

</form>


@endsection
@push('scripts')

<script>
	

var groupName = "{{ $groupname }}";

if (groupName == '7') {
    
    $('.checked_by, .approved_by').prop('required', true);
    
} else {
    
    $('.checked_by, .approved_by').removeAttr('required');
}

// Add Row
$(document).on('click', '.add-row', function () {

    const $tbody   = $('.clone_lines_body');
    const $lastRow = $tbody.find('tr:last');

    // 🔴 DESTROY Select2 before cloning
    $lastRow.find('select.select2').select2('destroy');

    const $newRow = $lastRow.clone(false);

    // Reset inputs
    $newRow.find('input').each(function () {
        if (!$(this).hasClass('bulk_line_no')) {
            $(this).val('').prop('readonly', false);
        }
    });

    // Reset selects (keep options)
    $newRow.find('select').each(function () {
        $(this).prop('selectedIndex', 0);
    });

    // Append new row
    $tbody.append($newRow);

    // 🔥 RE-INIT Select2 for BOTH rows
    $tbody.find('select.select2').select2({
        width: '100%'
    });

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
	
	
$(document).on('change', '.bulk_product_id', function() {
    var product_id = $(this).val();
    var $row = $(this).closest('tr'); // get the current row

    var url = "{{ URL::to('productuom') }}/" + product_id;
    $.get(url, function(data) {
        var data = $.trim(data);
        $row.find('.bulk_uom_code_id').val(data).trigger('change');
        $row.find('.bulk_batch_number').val(0);
        $row.find('.bulk_serial_number').val(0);
        $row.find('.bulk_box_product').val(0);
    });
});

$(document).on('change', '.po_number', function() {
    // clear existing rows except first
    $('.bulk_product_id').each(function(index) {
        if (index != 0) {
            $(this).closest("tr").remove();
        }
    });

    var poid = $('.po_number').val();
    var url = "{{ URL::to('getpodetailss') }}/" + poid;

    $.get(url, function(data) {
        $.each(data, function(key, value) {
            if (key != 0) {
                $('.add-row').trigger('click'); 
            }
            var $row = $('.clone_lines_body tr').eq(key); // target correct row
            $row.find('.bulk_po_no').val(value.po_hdr_id).trigger('change');
            $row.find('.bulk_product_id').val(value.product_id).trigger('change');
            $row.find('.bulk_uom_code_id').val(value.uom_code_id).trigger('change');
            $row.find('.bulk_qty').val(value.qty).css("pointer-events", "none");
            $row.find('.bulk_pending_qty').val(value.pendingqty).css("pointer-events", "none");
            $row.find('.bulk_batch_number').val(0);
            $row.find('.bulk_serial_number').val(0);
            $row.find('.bulk_box_product').val(0);
        });
    });
});
	
$(document).on('click', '.boxdetails', function() {
    var $row = $(this).closest('tr');
    var index = $row.index();

    $('.pdtindex').val(index);

    var source = $('.source').val();
    var po_id = $('.po_number').val();
    if (source == "GIN") {
        po_id = $row.find('.bulk_po_no').val();
    }

    var product_id        = $row.find('.bulk_product_id').val();
    var bulk_box_qty      = $row.find('.bulk_box_qty').val();
    var bulk_box_product  = $row.find('.bulk_box_product').val();
    var bulk_serial_number= $row.find('.bulk_serial_number').val();
    var bulk_batch_number = $row.find('.bulk_batch_number').val();

    if (bulk_box_qty != "") {
        var url = "{{URL::to('boxdetails')}}/" + bulk_box_qty + "/" + bulk_box_product + "/" + bulk_serial_number + "/" + bulk_batch_number;
        <?php if($row->source=='PO'||$row->source=='GIN'){?>
            <?php if($row->supplier_type=='SUPPLIER'){ ?>
                if (product_id != "") {
                    url += "?po_id=" + po_id + "&product_id=" + product_id;
                }
            <?php } ?>
        <?php } ?>

        $.get(url, function(data) {
            $('.batch_table tbody').html('');
            $('.batch_lines').html(''); 
            $('.batch_lines').append(data);
            $('#boxModal').modal('show');
        });
    } else {
        showCustomAlert("Please enter Box Quantity",'info');
    }
});
	

$(document).on('click', '.addbox', function() {
    var index = $('.pdtindex').val();
    var $row = $('.clone_lines_body tr').eq(index);

    var add = 0;
    var product_qty = [];
    var batch_number = [];
    var serial_number = [];
    var close_popup = 0;

    $('.product_qty').each(function(k) {
        var val = parseFloat($(this).val()) || 0;
        add += val;
        product_qty[k] = val;
    });

    $('.batch_number').each(function(k) {
        var val = $(this).val() || 0;
        if (val == "") {
            showCustomAlert("Please enter Batch Number",'info');
            close_popup = 1;
        }
        batch_number[k] = val;
    });

    $('.serial_number').each(function(k) {
        serial_number[k] = $(this).val() || 0;
    });

    $row.find('.bulk_box_product').val(product_qty);
    $row.find('.bulk_serial_number').val(serial_number);
    $row.find('.bulk_batch_number').val(batch_number);
    $row.find('.bulk_receive_qty').val(add);

    if (close_popup == 0) {
        $('#boxModal').modal('hide');
    }
});
	
	
	
    /* Purpose For Duplicate Entry Check*/
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
                
                if (response == 1)
                {
                    $('.dup_name')
                        .html('DC Number: ' + dc_number + ' already exists')
                        .removeClass('d-none')
                        .addClass('d-block');

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
    
    /* Purpose For Show Supplier & Subcontractor Based on Supplier Type*/
    <?php if($row->source=='GIN'){ ?>
        <?php if($row->supplier_type=='SUPPLIER'){ ?>
        $('.add_row').css("pointer-events","none");
        $('.prddiv').css("pointer-events","none");
         $(".showsupplier").show();
            $(".showcontract").hide();
            $('.subcontract_supplier_id').attr("required",false);
        <?php } ELSE{ ?>
              $(".showcontract").show();
            $(".showsupplier").hide();
           $('.supplier_id').attr("required",false); 
            <?php } ?>
        <?php }?>
            
             <?php if($row->source=='PO'){ ?>
             $(".showcontract").hide();
             $('.subcontract_supplier_id').attr("required",false);
        <?php }?>


	$(document).on('change','.supplier_type',function(){
        var supplier_type = $(".supplier_type option:selected").val();
        
        if(supplier_type == "SUPPLIER" ){
            $(".showsupplier").show();
            $(".showcontract").hide();
            $('.subcontract_supplier_id').attr("required",false); 
            
        }else{
             $(".showcontract").show();
            $(".showsupplier").hide();
           $('.supplier_id').attr("required",false); 
        }
    });		
	
/*End Purpose For Show Supplier & Subcontractor Based on Supplier Type*/	
$(document).ready(function(){
	 $('#savestatus').val('');
         /*Purpose For Submit Function*/
      $(document).on('click','.saveform',function()
    {
         var rcvqty=$('.bulk_receive_qty').val();
        if(rcvqty=="0"){
            $(".bulk_receive_qty").val("");
        }
       var btnval		= $(this).val();
        if(btnval == 'DRAFT'){
                $("#grn_status").val('DRAFT');
            }
            else{
                $("#grn_status").val('INITIATED');
            }
              $('#savestatus').val(btnval);
            var url		= "{{ url('genratesave') }}";
            var red_url		="{{ url('grn') }}";
            var create_url	="{{ url('genrategrn') }}/0";
            var form = $('#approvalform');
            
            if(btnval != 'DRAFT')
            {

              form.parsley().validate();
               var check=  duplicate_validate();
    	    if (form.parsley().isValid() && check==true){
                var bulk_pending_qty=0;
                var receive_qty=0;
     $(".bulk_pending_qty").each(function(index){
      bulk_pending_qty=bulk_pending_qty+parseFloat($(this).val());
      receive_qty=receive_qty+parseFloat($(".bulk_receive_qty"+index).val());
       });

              if(receive_qty > bulk_pending_qty){
                  setTimeout(function () {
                     swal({
      title: "GRN Qty is not equal to PO Qty",
      text: "You want to Continue this GRN ?",
      type: "warning",
      showCancelButton: !0,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Yes",
      cancelButtonText: "No",
      closeOnCancel:!1
    }, function(e) {
    if(e == true)
      {

                     var formdata	= $('#approvalform').serialize();

                    $.post(url,formdata,function(data)
                    {
                        var status      = data.status;
                        var msg     =   data.message;
                        var id          = data.id;
                        var edit_url	= "{{ url('genrategrn') }}/"+id;
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
      else
      {
         swal("Cancelled");
      }
    });
     }, 500);
       }
       else
       {

                     var formdata	= $('#approvalform').serialize();

                    $.post(url,formdata,function(data)
                    {
                        var status      = data.status;
                        var msg     = data.message;
                        var id          = data.id;
                        var edit_url	= "{{ url('genrategrn') }}/"+id;
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
            }
            else
            {

		         var formdata	= $('#approvalform').serialize();
            $.post(url,formdata,function(data)
            {
                    var status = data.status;
                    var msg    = data.message;
                    var id     = data.id;
                    var edit_url	="{{ URL::to('grnedit') }}/"+id;
                        showCustomAlert(msg,status);
                        setTimeout(function(){
                        window.location.href=edit_url;
                        }, 1500);
                });
            }
  return false;
    });
	 
 });

    $(document).on('keypress','.bulk_box_qty,.product_qty', function(ev){
		var regex = new RegExp("^[0-9.]+$");
					var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
					if (regex.test(str)) {
						return true;
					}
					ev.preventDefault();
					return false;
            });

      $('.bulk_box_qty').bind("cut copy paste", function(e) {
        e.preventDefault();
            });   

	

	
$(document).on('input', '.product_qty', function() {
    var referenceNumber = $('.reference_number').val().trim(); // Get reference number
    var orderQty = parseFloat($('.orderqty').val()) || 0; // Get order qty
    var productQty = parseFloat($(this).val()) || 0; // Get entered qty

    // Skip validation if reference number is "DIRECT GRN"
    if (referenceNumber === "DIRECT GRN") {
        return; // Exit function without validation
    }

    if (productQty > orderQty) {
        alert('Product quantity cannot be greater than Order Quantity!');
        $(this).val(orderQty); // Reset to max allowed
    }
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
