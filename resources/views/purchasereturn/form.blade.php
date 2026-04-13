@extends('layouts.header')
@section('content')
<h3 class="text-danger">
<?php if($pageMethod=="purchasereturnapproval") { ?>
Purchase Return Approval
<?php }else { ?>
Purchase Return
<?php } ?>

</h3>
@include('layouts.breadcrumb')
	

<form method="post" id="qcform" data-parsley-validate>
    {{ csrf_field() }}
   <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">QC Return Details</h5>
        </div>
        <div class="card-body">

            <div class="row g-4">
                <!-- Column 1 -->
                <div class="col-md-4">
                    <input type="hidden" name="qc_id" value="{{ $row[0]->qc_header_id }}">
                    
                    <div class="mb-3">
                        <label class="form-label">Return Invoice Number</label>
                        <input type="text" name="return_invoice_number" class="form-control return_invoice_number" value="{{ $row[0]->return_invoice_number }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Return Status</label>
                        <input type="text" id="p_return_status" name="p_return_status" class="form-control p_return_status" value="{{ $row[0]->p_return_status }}" >
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-danger">*</label>
                        <label class="form-label">Invoice Number</label>
                        <input type="hidden" name="invoice_number" value="{{ $row[0]->invoice_number }}">
                        <input type="text" name="bill_number" class="form-control bill_number" value="{{ $row[0]->bill_number }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">PO Number</label>
                        <input type="hidden" name="return_header_id" value="{{ $row[0]->return_header_id }}">
                        <input type="hidden" name="po_number" value="{{ $row[0]->po_hdr_id }}">
                        <input type="text" class="form-control" value="{{ $row[0]->po_number }}">
                    </div>
                </div>

                <!-- Column 2 -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">GRN Number</label>
                        <input type="hidden" name="grn_number" value="{{ $row[0]->grn_id }}">
                        <input type="text" class="form-control" value="{{ $row[0]->grn_number }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">PO Date</label>
                        <input type="text" class="form-control" value="{{ $row[0]->po_date }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Grand Total</label>
                        <input type="text" name="po_grand_total" class="form-control" value="{{ $row[0]->po_grand_total }}">
                        <input type="hidden" name="balance_amount" value="{{ $row[0]->po_grand_total }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">QC Number</label>
                        <input type="text" class="form-control" value="{{ $row[0]->qc_number }}">
                    </div>
                </div>

                <!-- Column 3 -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Supplier Name</label>
                        <select name="supplier_id" class="form-select select2 supplier_id" data-live-search="true">
                            {!! $supplier_id !!}
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">SubContractor Name</label>
                        <select name="subcontract_supplier_id" class="form-select select2 subcontract_supplier_id" data-live-search="true">
                            {!! $subcontract_supplier_id !!}
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tax Total</label>
                        <input type="text" name="po_tax_total" class="form-control po_tax_total" value="{{ $row[0]->po_tax_total }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-danger">*</label>
                        <label class="form-label">Return Date</label>
                        <input type="text" name="return_date" class="form-control datepicker" value="{{ $row[0]->return_date }}" required>
                    </div>
                </div>
            </div>

<!-------------------------Linedata -------------------------------->

<div class="row mt-4">
<div class="col-12 linetable">
<div id ="preview-area" class="table-responsive">
  <table class="table table-bordered clone_table"  style="width:170% !important;">
    <thead class="table-light">
      <tr>
        <th style="width: 80px;">Line No</th>
        <th class="pdtdiv">Product </th>
        <th>Uom Code</th>
        <th style="display:none;">Qty</th>
        <th>Rejected Qty</th>
        <th>Price</th>
        <th>Discount(%)</th>
        <th>Discount Amount</th>
        <th>Tax Group</th>
        <th>Tax Amount</th>
        <th>Line Total</th>
        <th>Reason </th>
        <th style="width: 60px;"></th>
      </tr>
    </thead>
<tbody class="clone_lines_body">
  @if(count($linedata) > 0)
    @foreach($linedata as $key => $value)

<?php if($value->reject_qty==0)
{ $display = "display:none;"; } else {  $display = "display:block;"; }
?>

      <tr class="line-row">

   <td>
        <input type="hidden" name="bulk_return_line_id[]" class="form-control input-sm bulk_return_line_id" value="{{ $value->return_line_id }}">

        <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="{{ $key + 1 }}" readonly="readonly">
    </td>
    <td class="pdtdiv">
        <select name="bulk_product_id[]" class="form-control bulk_product_id select2 parsley-validated" required="required">{!! $value->product_id !!}</select>
    </td>
    <td>
        <select name="bulk_uom_code_id[]" id="bulk_uom_code_id" class="form-control bulk_uom_code_id select2">
            {!! $value->uom_code_id !!}
        </select>
    </td>
    <td style="display:none;">
        <input type="text" name="bulk_qty[]" class="form-control input-sm bulk_qty input_qty_width" value="{{ $value->qty }}" required="required" >
    </td>
    <td>
        <input type="text" name="bulk_reject_qty[]" class="form-control input-sm bulk_reject_qty input_qty_width" value="{{ $value->reject_qty }}" required="required" readonly>
    </td>

        <td>
            <input type="text" name="bulk_unit_price[]" class="form-control input-sm bulk_unit_price input_qty_width" value="{{ $value->unit_price }}" required="required">
        </td>
        <td>
            <input type="text" name="bulk_discount_percentage[]" class="form-control input-sm bulk_discount_percentage input_qty_width" value="{{ $value->discount_percentage }}">
        </td>
        <td>
            <input type="text" name="bulk_discount_amount[]" class="form-control input-sm bulk_discount_amount input_qty_width" value="{{ $value->discount_amount }}">
        </td>
        <td>
            <select name="bulk_tax_group_id[]" id="bulk_tax_group_id" class="form-control bulk_tax_group_id select2">
                {!! $value->tax_group_id !!}
            </select>
        </td>
        <td>
            <input type="text" name="bulk_tax_amount[]" class="form-control input-sm bulk_tax_amount input_qty_width" value="{{ $value->tax_amount }}" required="required">
        </td>
        <td>
            <input type="text" name="bulk_line_total[]" class="form-control input-sm bulk_line_total input_qty_width" value="{{ $value->line_total }}" required="required">
        </td>
        <td>
            <input type="text" name="bulk_reason[]" class="form-control input-sm bulk_reason input_qty_width" value="{{ $value->reason }}">
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

</div>

  </div>
</div>
	
<!-------------------------Linedata End-------------------------------->
<div class="row mt-4 mb-3">
	<div class="col-lg-12 col-md-12">
		<div class="form-group text-center">
                     <?php if($aprvidenty=="") { ?> 
			<button type="button" class="btn btn-secondary px-4 me-2 saveform" value="APPLYCHANGES">Draft</button>
			<button type="button" class="btn btn-success px-4 me-2 saveform" value="SAVE">Save</button>
			  <a href="{{ url('purchasereturn') }}" class='btn btn-outline-danger px-4 me-2'>Cancel</a>
                            <?php } else { ?>
                          <button type="button" class="btn btn-success px-4 me-2 saveform approved" value="APPROVED">Approve</button>
                          <button type="button" class="btn btn-danger px-4 me-2 saveform rejected" value="REJECTED">Reject</button>

			  <a href="{{ url('purchasereturnapproval') }}" class='btn btn-outline-danger'>Cancel</a>
                        <?php } ?>
		</div>
	</div>
</div>

</div>
<input type="hidden" class="pdtindex" value="" />
</form>



@endsection
@push('scripts')

<script>
	
/*Purpose For Readonly*/    
$('input').attr('readonly', true);
$('select').attr('readonly', true);
$('#return_date,.bulk_reason').attr('readonly', false);

$(document).ready(function(){

    $('.bulk_product_id,.bulk_uom_code_id,.bulk_tax_group_id,.supplier_id,.subcontract_supplier_id').css('pointer-events','none');
 $('#savestatus').val('');

    $(document).on('click','.saveform',function(){
            var btnval		= $(this).val();

            if(btnval == 'APPLYCHANGES'){
                $("#p_return_status").val('APPLYCHANGES');
            }
            else if(btnval == 'DRAFT'){
                $("#p_return_status").val('DRAFT');
            }
            else if(btnval == 'APPROVED'){
                $("#p_return_status").val('APPROVED');
            }
            else if(btnval == 'REJECTED'){
                $("#p_return_status").val('REJECTED');
            }
            else{
                $("#p_return_status").val('INITIATED');
            }

            var url		= "{{ url('purchasereturnsave') }}";
            var red_url		="{{ url('purchasereturn') }}";
            var create_url	="{{ url('purchasereturncreate') }}/0";
            var formdata	= $('#qcform').serialize();
            var form = $('#qcform');

            if(btnval != 'APPLYCHANGES')
            {
                form.parsley().validate();
                var form = $('#qcform');
                form.parsley().validate();

                if (form.parsley().isValid())
                {
                    $.post(url,formdata,function(data)
                    {
                        var status      = data.status;
                        var msg     =     data.message;
                        var id          = data.id;
                        var edit_url	= "{{ url('purchasereturncreate') }}/"+id;
                        if(btnval !='SAVE' && btnval !='DRAFT' && btnval !='APPROVED' && btnval !='REJECTED')
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
            $.post(url,formdata,function(data)
            {

                    var status = data.status;
                    var msg    = data.message;
                    var id     = data.id;
                    var edit_url	="{{ url('purchasereturnedit') }}/"+id;
                            showCustomAlert(msg,status);
                            setTimeout(function(){
                            window.location.href=edit_url;
                            }, 1500);

                });
            }
    });
	


      <?php if($aprvidenty!="") { ?> 
$('#return_date,#po_date').css('pointer-events','none');
      <?php } else{ ?>
          $('#po_date').css('pointer-events','none');
      <?php }?>

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


/* pavan validation for qty */
$(".bulk_accept_qty").keyup(function(){
    var index = $(this).closest('tr').index();
     var accept=$(this).val();
    var total=$(".bulk_receive_qty"+index).val();
 if(total < accept){
      notyMsg("info","Exceeds as Recived Qty");
     $(this).val("");
      $(".bulk_reject_qty"+index).val("0");
 }else{
   var sum=parseFloat(total)-parseFloat(accept);
 $(".bulk_reject_qty"+index).val(sum);
    }
})



$('.po_date,.qc_date,.return_date').datepicker({format: 'yyyy-mm-dd', autoClose: true})


});
	

</script>
	
@endpush
