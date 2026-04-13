@extends('layouts.header') @section('content')


<style type="text/css">

@media  only screen and (min-width: 1500px) { 

.bulk_line_no {width: 100px !important;}
.bulk_po_invoice_id {width: 220px !important;}
.bulk_batch_invoice_amount {width: 220px !important;}
.bulk_payment_amount {width: 220px !important;}
.bulk_paid_amount {width: 220px !important;}
.bulk_balance_amount {width: 220px !important;}
.bulk_comments {width: 250px !important;}


}

.bulk_line_no {width: 80px;}
.bulk_po_invoice_id {width: 143px;}
.bulk_batch_invoice_amount {width: 110px;}
.bulk_payment_amount {width: 100px;}
.bulk_paid_amount {width: 105px;}
.bulk_balance_amount {width: 105px;}
.bulk_comments {width: 190px;}


</style>



   <?php include('tools_menu.php'); ?>  <h4 class="heads">
    <a role="button">Payment</a><span class="ui_close_btn"><a class="collapse-close pull-right btn-danger" onclick='location.href="{{ url('payments') }}"'></a></span>
    </h4>
    





             
<form method="post" action="" id="payment_form" data-parsley-validate>
{{ csrf_field() }}
        
<div class="card">
<div class="card-header">
<div class="row">
        <div class="col-md-3">
            <label class="align_left payment_number">Payment Number:{{ $row->payment_number }}   </label>
            <label class="align_left">Created By:<span class='create_by span_color'></span></label>
        </div>
        <div class="col-md-3">
            <label class="align_left"> Payment Date:<?php echo date(\Session::get('p_date_format'),strtotime($row->payment_date));?></label><br>
            <label class="align_left">Organization:<span class='org span_color'></span></label><br>
        </div>
        <div class="col-md-3">
            <label class="align_left">Payment Status:{{ $row->payment_status }}   </label>
        </div>
        <div class="col-md-3">
            <label class="align_left">  Batch Total Amount:<span class='batch_total_span span_color'>{{ $row->batch_total_amount }}</span></label><br>
        </div>
</div>
</div>
                <div class="card-body card-block">
                <!------------------------------- toggle content start ---------------------------->
                <div class="row">
                <div class="col-md-12">
            <div>
                        <div class="row">
                         <div class="col-md-4">
                    <div class="form-group row">
                        <label for="inputIsValid" class="form-control-label col-md-5"><span style="color:red">*</span>Batch Name</label>
                        <div class="col-md-5 sel2">
                            <input class="form-control payment_hdr_id" id="payment_hdr_id" name="payment_hdr_id" size="16" type="hidden" value="{{ $row->payment_hdr_id }}" readonly>
                            <input class="form-control payment_number" id="payment_number" name="payment_number" size="16" type="hidden" value="{{ $row->payment_number }}" readonly>
                            <input class="form-control batch_total_amount" id="batch_total_amount" name="batch_total_amount" size="16" type="hidden" value="{{ $row->batch_total_amount }}" readonly>
                            <select name='payment_batches_hdr_id' rows='5' id='payment_batches_hdr_id' class='select2 payment_batches_hdr_id ' required>
                                {!! $payment_batches_hdr_id !!}
                            </select>
                        </div>
                        <div class="col-md-3 showinline">
                            </div>
                    </div>
                               <div class="form-group row" style="display:none">
                                    <label for="inputIsValid" class="form-control-label col-md-4">Payment Date</label>
                                    <div class="col-md-6">
                                        <div class="input-group form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                            <input class="form-control datepicker payment_date" id="payment_date" name="payment_date" size="16" type="text" value="{{ $row->payment_date }}" readonly>
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 showinline">
                                    </div>
                                </div>
                 <div class="form-group row" style="display:none">
                         <label for="inputIsValid" class="form-control-label col-md-5">Payment Status</label>
                         <div class="col-md-5">
                             <select type="text" name="payment_status" id="payment_status" class="payment_status" readonly>
                             <option value="">-- Please Select --</option>
                             <option <?php if($row->payment_status=="DRAFT" ) echo "selected"; ?> value="DRAFT">DRAFT</option>
                             <option <?php if($row->payment_status=="INITIATED" ) echo "selected"; ?> value="INITIATED">INITIATED</option>
                             </select>
                         </div>
                         <div class="col-md-2">
                         </div>
                     </div>
                    <div class="form-group row">
                                <label for="inputIsValid" class="form-control-label col-md-5"><span style="color:red">*</span>Bank Name</label>
                                <div class="col-md-5 sel2">
                                    <select name='bank_id' rows='5' id='bank_id' class='select2 bank_id' required>
                                        {!! $bank_id  !!}
                                    </select>
                                </div>
                                <div class="col-md-2 showinline">
                                    <span class="showspan"> <i class="fa fa-refresh jcr_bank_id"></i></span>
                                </div>

                    </div>
                    <div class="form-group row" >
                      <label for="inputIsValid" class="form-control-label col-md-5"><span style="color:red">*</span>Payment Type</label>
                      <div class="col-md-5 sel2">
                          <select name='payment_type_id' rows='5' class='form-control payment_type_id select2' data-show-subtext="true" data-live-search="true" required>
                              <option value="">--Please Select--</option>
                              <option <?php if($row->payment_type_id =="CHEQUE") { echo "selected"; } else { echo ""; } ?> value="CHEQUE">CHEQUE</option>
                              <option <?php if($row->payment_type_id =="CASH") { echo "selected"; } else { echo ""; } ?> value="CASH">CASH</option>
                              <option <?php if($row->payment_type_id =="NEFT") { echo "selected"; } else { echo ""; } ?> value="NEFT">NEFT</option>
                              <option <?php if($row->payment_type_id =="MTPS") { echo "selected"; } else { echo ""; } ?> value="MTPS">MTPS</option>

                          </select>
                      </div>
                      <div class="col-md-2">
                      </div>
                  </div>
                              <div class="form-group row sourcediv">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Advance Amount</label>
                                    <div class="col-md-5">
                                        <input type="text" id="advance_amount" name="advance_amount" class="form-control advance_amount" value="{{ $row->advance_amount }}"  />
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Remarks</label>
                                    <div class="col-md-5">
                                        <input type="text" name="remarks"  id="remarks"  class="form-control remarks" value="{{ $row->remarks }}" >

                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                </div>


			</div>
                        <div class="col-md-4">
                            <div class="form-group row">
                                <label for="inputIsValid" class="form-control-label col-md-5"><span style="color:red">*</span>Supplier Name</label>
                                <div class="col-md-5 supplier_div">
                                    <select name='supplier_id' rows='5' id='supplier_id' class='form-control supplier_id 'required>
                                        {!! $supplier_id !!}
                                    </select>
                                </div>
                                <div class="col-md-2 showinline">
                                    </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputIsValid" class="form-control-label col-md-5">Account Number</label>
                                <div class="col-md-5 supplier_div">
                                    <select name='account_no' rows='5' id='account_no' class='select2 account_no'>
                                        {!! $account_no !!}
                                    </select>
                                </div>
                                <div class="col-md-2 showinline">
                                    </div>
                            </div>
                            
           <div class="form-group row">
                <label for="inputIsValid" class="form-control-label col-md-5"><span style="color:red;">*</span>Account Code</label>
                  <div class="col-md-5 sel2">
                    <select  name='account_structure_id' rows='5' class='account_structure_id select2' required >
                        {!!$account_structure_id!!}
                        </select>
                  </div>
                <div class="col-md-2 showinline">
                        <span class="showspan"> <i class="fa fa-refresh jcr_account_structure_id"></i></span>
                </div>
            </div>
                            <div class="form-group row chequediv">
                                <label for="inputIsValid" class="form-control-label col-md-5">Cheque No</label>
                                <div class="col-md-5 supplier_div">
                                    <select name='cheque_no' rows='5' id='cheque_no' class='select2 cheque_no'>
                                         {!! $cheque_no !!}
                                    </select>
                                </div>
                                <div class="col-md-2 showinline">
                                    </div>
                            </div>
                        </div>

                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Supplier Site</label>
                                    <div class="col-md-5">
                                        <select name='supplier_site_id' rows='5' id='supplier_site_id' class='form-control supplier_site_id'>
                                            {!! $supplier_site_id  !!}
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                </div>
                    <div class="form-group row"  >
                      <label for="inputIsValid" class="form-control-label col-md-5"><span style="color:red">*</span>Payment Source</label>
                      <div class="col-md-5 sel2">
                          <select name='payment_source' rows='5' class='form-control payment_source select2' data-show-subtext="true" data-live-search="true" required>
                              <option value="">--Please Select--</option>
                              <option <?php if($row->payment_source =="ORDER") { echo "selected"; } else { echo ""; } ?> value="ORDER">ORDER</option>
                              <option <?php if($row->payment_source =="BATCH") { echo "selected"; } else { echo ""; } ?> value="BATCH">BATCH</option>
                         </select>
                      </div>
                      <div class="col-md-2">
                      </div>
                  </div>
                                  <div class="form-group row">
                                <label for="inputIsValid" class="form-control-label col-md-5">Purchase Order No</label>
                                <div class="col-md-5 supplier_div">
                                    <select name='po_hdr_id' rows='5' id='po_hdr_id' class='select2 po_hdr_id'>
                                        {!! $po_hdr_id !!}
                                    </select>
                                </div>
                                <div class="col-md-2 showinline">
                                    </div>
                            </div>
							<div class="form-group row">
                                     <label for="inputIsValid" class="form-control-label col-md-5">Sender Information</label>
                                     <div class="col-md-5">
                                         <input type="text" name="sender_information"  id="sender_information"  class="form-control sender_information" value="{{ $row->sender_information }}" >

                                     </div>
                                     <div class="col-md-2">
                                     </div>
                                 </div>
                                 <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5"><span style="color:red">*</span>UTR Number</label>
                                    <div class="col-md-5">
                                        <input type="text" id="payment_reference" name="payment_reference" class="form-control payment_reference" value="{{ $row->payment_reference }}" required />
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                </div>
                              
                         

                            </div>
                            <div class="col-md-4">
                                <div class="form-group row" style="display:none">
                                    <label for="inputIsValid" class="form-control-label col-md-5">batch Total Amount</label>
                                    <div class="col-md-5">
                                        <input type="text" id="batch_total_amount" name="batch_total_amount" class="form-control batch_total_amount" value="{{ $row->batch_total_amount }}"  />
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                </div>
                                <div class="form-group row" style="display:none">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Organization </label>
                                    <div class="col-md-5">
                                        <select name='organization_id' rows='5' id='organization_id' class='form-control organization_id'>
                                            {!! $organization_id  !!}
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                    </div>
                                </div>
                                <div class="form-group row" style="display:none">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Created By </label>
                                    <div class="col-md-5">
                                        <select name='created_by' rows='5' id='created_by' class='form-control created_by'>
                                             {!! $created_by  !!}
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                </div>

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


<!------------------------- clone row End-------------------------------->
<div class="row">
    <div class="col-md-12">
<div id="preview-area" class="chandru">
    <table class="overflow-y preview payment_table">
                <thead>
                    <tr>
                        <th>Line No</th>
                        <th>Invoice Number</th>
                        <th>Invoice Amount</th>
                        <th>Payment Amount</th>
                        <th>Paid Amount</th>
                        <th>Balance Amount</th>
                        <th>Comments</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody class="pmt_lines_body">
                    <?php if(count($linedata)>=1) { ?> @foreach($linedata as $key=>$value)
                    <tr class="rcopy clone">
                        <td>
                            <input type="hidden" name="bulk_payment_line_id[]" class="form-control  bulk_payment_line_id" value="{!!  $value->payment_line_id  !!}">
                        </td>
                        <td>
                            <input type="text"  name="bulk_line_no[]" class="form-control  bulk_line_no" value="{!! ($key+1)  !!}" readonly="readonly" >
                        </td>
                        <td>
                            
                            <select name="bulk_po_invoice_id[]" id="bulk_po_invoice_id" class="bulk_po_invoice_id" required="required" >{!! $value->po_invoice_id !!}</select>
                        </td>
                        <td>
                            <input type="text" name="bulk_batch_invoice_amount[]" class="form-control  bulk_batch_invoice_amount" value="{!! $value->batch_invoice_amount !!}" readonly="readonly"  >
                        </td>
                        <td>
                            <input type="text" name="bulk_payment_amount[]" class="form-control  bulk_payment_amount" required="required" value="{!! $value->payment_amount !!}"  >
                        </td>
                         <td>
                            <input type="text" name="bulk_paid_amount[]" class="form-control  bulk_paid_amount" required="required" value="{!! $value->paid_amount !!}"  readonly >
                        </td>
                         <td>
                            <input type="text" name="bulk_balance_amount[]" class="form-control  bulk_balance_amount" required="required" value="{!! $value->balance_amount !!}" readonly >
                        </td>
                        
                        <td>
                            <textarea name="bulk_comments[]" class="form-control  bulk_comments" row="1" value="" >{!! $value->comments !!}</textarea>
                        </td>
                        <td>
                            <a class="remove remove0">
                                <i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i>
                            </a>
                            <input type="hidden" name="counter[]">
                        </td>
                    </tr>
                    @endforeach
                    <?php } if(count($linedata) < 1 ) { ?>
                    <tr class="rcopy clone">
                        <td>
                            <input type="hidden" name="bulk_payment_line_id[]" class="form-control  bulk_payment_line_id" value="">
                        </td>
                        <td>
                            <input type="text"  name="bulk_line_no[]" class="form-control  bulk_line_no" value="" readonly="readonly" >
                        </td>
                        <td>
                            <select name="bulk_po_invoice_id[]" id="bulk_po_invoice_id" class="form-control bulk_po_invoice_id"  >{!! $po_invoice_id !!}</select>
                        </td>
                         <td>
                            <input type="text" name="bulk_batch_invoice_amount[]" class="form-control  bulk_batch_invoice_amount" value=""  >
                        </td>
                        <td>
                            <input type="text" name="bulk_payment_amount[]" class="form-control  bulk_payment_amount " value=""  >
                        </td>
                         <td>
                            <input type="text" name="bulk_paid_amount[]" class="form-control  bulk_paid_amount " value=""  >
                        </td>
                         <td>
                            <input type="text" name="bulk_balance_amount[]" class="form-control  bulk_balance_amount" value=""  >
                        </td>
                         <td>
                            <textarea name="bulk_comments[]" class="form-control  bulk_comments" row="1" value="" ></textarea>
                        </td>
                        <td>
                            <a class="remove remove0">
                                <i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i>
                            </a>
                            <input type="hidden" name="counter[]">
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
<div class="col-md-12">
<hr class="xlg">
</div>
</div>



<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="form-group text-center">
            <button type="button" class="btn applychanges saveform add" value="APPLYCHANGES">Apply Changes</button>
            <button type="button" class="btn save saveform" value="SAVENEW">Save and New</button>
            <button type="button" class="btn draft saveform" value="DRAFT">Draft</button>
            <button type="button" class="btn save saveform" value="SAVE">Save</button>
            <a href="{{ url('payments') }}" class='btn cancel'>Cancel</a>
        </div>
    </div>
</div>

<input type="hidden" class="pdtindex" value="" />
</div>
</div>
</div>

</form>


<script>
    $(document).ready(function() {
$('.payment_status,.supplier_site_id,.supplier_id,.bulk_po_invoice_id').css("pointer-events","none");
  $(".jcr_account_structure_id").click(function(){
        $(".account_structure_id").jCombo("{{ URL::to('jcomboform?table=f_account_structure_t:f_account_structure_id:concatenated_segments') }}",
        {selected_value:""});
    });
    
 $('.bulk_payment_amount').attr("required","true");
 /* Karthigaa code for lower to uppercase */
         $('.payment_batch_name').keyup(function(){
            this.value = this.value.toUpperCase();
         });
         /* end */
           /*Validation*/
	$(document).on('keypress','.advance_amount,.bulk_payment_amount', function(ev){
			var regex = new RegExp("^[0-9.]+$");
					var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
					if (regex.test(str)) {
						return true;
					}
					ev.preventDefault();
					return false;
		});
	/*End*/
        

      $('#bank_id').on('change',function(){
         var bank = $('#bank_id').val();
         var pdt_condition ='bank_account_hdr_id='+bank;
        $(".account_no").jCombo("{{ URL::to('jcomboform?table=f_bank_account_lines_t:bank_account_line_id:account_number') }}&order_by=account_number asc"+'&parent='+pdt_condition,
           {selected_value:""});
    });

    $(document).on('click','.jcr_bank_id',function(){
        $(".bank_id").jCombo("{{ URL::to('jcomboform?table=f_bank_account_hdr_t:bank_account_hdr_id:bank_name') }}&order_by=bank_name asc",
        {selected_value:""});
    });


$('.payment_source').on('change',function(){
    var pmtsrcid=$('.payment_source option:selected').text();
    var pmtsrc=$.trim(pmtsrcid);
    if(pmtsrc=="ORDER"){
        $('.sourcediv').css('display','block');
    }else{
        $('.sourcediv').css('display','none');
    }
});

    $('.payment_type_id').on('change',function(){
var pmttypeid=$('.payment_type_id option:selected').text();
var pmttype=$.trim(pmttypeid);
if(pmttype=="CHEQUE"){
    $('.chequediv').css('display','block');
}else{
    $('.chequediv').css('display','none');
}
});
      /*Karthigaa Purpose For Default Organization & User*/
var organization = '<?php echo Session::get('organization'); ?>' ;
$('.organization_id').val(organization).change();
var user = '<?php echo Session::get('id'); ?>' ;
$('.created_by').val(user).change();
$(".create_by").html($('.created_by option:selected').text());
$('.org').html($('.organization_id option:selected').text());

        var data = "{{\Session::get('j_date_format')}}";
        $(".add_row").relCopy(data);

        $('.add_row').click(function() {
            changeclassfields();
        });

 changeclassfields();
        $(".select2").select2();
        $(".select2").css('width', '100%');

 /*Karthigaa purpose:For Payment Amount should not exceeds Invoice*/
//$(document).on('keyup','.bulk_payment_amount',function(){
//    var index = $(this).closest('tr').index();
//    var id=$('.payment_hdr_id').val();
//    var inv_id = $('.bulk_po_invoice_id'+index+' option:selected').val();
//    var invoice_amount=$('.bulk_batch_invoice_amount'+index).val();
//    var payment_amount=$(this).val();
//    var url = "{{ URL::to('getPaymentdetails') }}/"+id+'/'+inv_id+'/'+invoice_amount+'/'+payment_amount;
//     $.get(url,function(data){
//    	if(data==0){
//                    $('.bulk_payment_amount'+index).val('');
//                    notyMsg('error','Payment Amount Should not Exceeds than Invoice Amount');
//                    }
//        if(data==1){
//              $('.bulk_payment_amount'+index).val(data);
//                    var Total=0;
//                    $('.bulk_payment_amount').each(function(){
//                             Total +=Number($(this).val());
//                    });
//                     $(".batch_total_span").html(Total);
//                    }
//		});
//    });
 /*Karthigaa purpose:For Payment Amount should not exceeds Invoice&Show Balance*/
 $(document).on('change','.bulk_payment_amount',function(){
    var index = $(this).closest('tr').index();
    var id=$('.payment_hdr_id').val();
    var inv_id = $('.bulk_po_invoice_id'+index+' option:selected').val();
    var invoice_amount=parseInt($('.bulk_batch_invoice_amount'+index).val());
    var payment_amount=parseInt($('.bulk_payment_amount'+index).val());
    var paid_amount=parseInt($('.bulk_paid_amount'+index).val());
    var balance_amount=parseInt($('.bulk_balance_amount'+index).val());
    var bal_amount=parseInt($('.balance_amount'+index).val());
   
    var pay_amt = isNaN(parseInt(payment_amount)) ? 0 : parseInt(payment_amount);

    if(pay_amt == 0)
    {

       setTimeout(function(){
        $('.bulk_balance_amount'+index).val(bal_amount);
        }, 500);
    }

            if(paid_amount!=0)
            {
                if(payment_amount>bal_amount)
                {
                    notyMsg('error','Payment Amount Should not Exceeds than Balance Amount');
                    $('.bulk_payment_amount'+index).val('');
                    $('.bulk_balance_amount'+index).val(bal_amount);

                }
                else
                {
                    var balamt= bal_amount-payment_amount;
                    var num1 = isNaN(parseInt(balamt)) ? 0 : parseInt(balamt);
                    $('.bulk_balance_amount'+index).val(num1);
                }
            }
            else
            {
               
                if(payment_amount>bal_amount)
                {
                    notyMsg('error','Payment Amount Should not Exceeds than Balance Amount');
                    $('.bulk_payment_amount'+index).val('');
                    $('.bulk_balance_amount'+index).val(bal_amount);
                }
                else
                {
                    var balamt= bal_amount-payment_amount;
                    var num1 = isNaN(parseInt(balamt)) ? 0 : parseInt(balamt);
                    $('.bulk_balance_amount'+index).val(num1);
                }
            }


                var Total=0;
                    $('.bulk_payment_amount').each(function(){
                             Total +=Number($(this).val());
                    });
                     $(".batch_total_amount").val(Total); 
                     $(".batch_total_span").html(Total);

    });
/*Karthigaa purpose:to get supplier,supplier site based on batch name*/
$(document).on('change','.payment_batches_hdr_id',function(){

    var batchid=$('#payment_batches_hdr_id').val();
   if(batchid!="")
   {
      var url = "{{ URL::to('getbatchdetails') }}/"+batchid;
                $.get(url,function(data)
                {
                     $('.supplier_id').val(data[0].supplier_id);
                     $('.supplier_site_id').val(data[0].suppliersite_id);

		});
                $.get("{{ URL::to('batchinvoicedetails') }}/"+batchid,function(data)
			{
				$('.payment_table tbody').html('');
                                $('.pmt_lines_body').append(data);
				changeclassfields();
			});
    }
   else
   {
	  notyMsg('error','Please Select a Payment Batch');
   }
});


        var index = $('.clone').closest('tr').index();
        changeclassfields();



/****      Karthigaa purpose remove function    ***/
        $(document).on('click', '.remove', function() {
            var index = $(this).closest('tr').index();
            var rowCount = $('.payment_table tbody tr').length;
            if (rowCount > 1) {
                $($(this).closest("tr")).remove();
                removeclassfields();
            } else {
                notyMsg('error',"You Can't Delete Atleast One row should be there");
            }
        });
   /*End*/


		$(document).on('click', '.saveform', function() {
            var btnval = $(this).val();
            if(btnval == 'APPLYCHANGES'){
			$("#payment_status").val('DRAFT');
		}
            else if(btnval == 'DRAFT'){
			$("#payment_status").val('DRAFT');
	    }else{
			$("#payment_status").val('INITIATED');
		}
            $('#savestatus').val(btnval);
            var url = "{{ url('paymentssave') }}";
            var red_url = "{{ url('payments') }}";
            var create_url = "{{ url('paymentscreate') }}";

            validationrule('payment_form');
            var form = $('#payment_form');
            if (btnval != 'APPLYCHANGES') {
                //   form.parsley().validate();
                var form = $('#payment_form');
                form.parsley().validate();

                if (form.parsley().isValid()) {
                    change_date();
                    var formdata = $('#payment_form').serialize();
                    $.post(url, formdata, function(data) {
                        var status = data.status;
                        var msg     = '<span style="color:#090065">'+data.auto_no+'</span>  '+data.message;
                        var id = data.id;
                        var edit_url = "{{ url('paymentscreate') }}/" + id;

                        if (btnval != 'SAVE' && btnval != 'DRAFT') {
                            notyMsg(status, msg);
                            setTimeout(function() {
                                window.location.href = create_url;
                            }, 1500);
                        } else {
                            notyMsg(status, msg);
                            setTimeout(function() {
                                window.location.href = red_url;
                            }, 1500);
                        }
                    });
                }
            } else {
                change_date();
                var formdata = $('#payment_form').serialize();
                $.post(url, formdata, function(data) {

                    var status = data.status;
		var msg     = '<span style="color:#090065">'+data.auto_no+'</span>  '+data.message;
		var id     = data.id;
                    var edit_url = "{{ url('paymentscreate') }}/" + id;
                    notyMsg(status, msg);
                    setTimeout(function() {
                        window.location.href = edit_url;
                    }, 1500);

                });
            }
        });

    });

    function changeclassfields() {
        changeClassName('bulk_payment_line_id');
        changeClassName('bulk_line_no');
        changeClassName('bulk_po_invoice_id');
        changeClassName('bulk_payment_amount');
        changeClassName('bulk_paid_amount');
        changeClassName('bulk_balance_amount');
        changeClassName('bulk_batch_invoice_amount');
        changeClassName('bulk_account_structure_id');
        changeClassName('bulk_comments');
    }

    function removeclassfields() {
        removeClass('bulk_payment_line_id');
        removeClass('bulk_line_no');
        removeClass('bulk_po_invoice_id');
        removeClass('bulk_batch_invoice_amount');
        removeClass('bulk_payment_amount');
        removeClass('bulk_paid_amount');
        removeClass('bulk_balance_amount');
        removeClass('bulk_account_structure_id');
        removeClass('bulk_comments');
    }
    /************ Maruthu purpose to remove row action ********************/
    function removeClass(className)
	{
        var rowCount = $('.payment_table tbody tr').length;
        for (var i = 0; i <= rowCount; i++) {
            $('.payment_table tbody tr').find('.' + className).removeClass(className + i);
        }
        $('.' + className).each(function(index) {
            if (className == "bulk_line_no") {
                $(this).val(index + 1).attr("readonly", 1);
            }
            $(this).addClass(className + index);
        });
    }

    function changeClassName(className)
	{
        $('.' + className).each(function(index) {
            if (className == "bulk_line_no") {
                $(this).val(index + 1).attr("readonly", 1);
            }

            $(this).removeClass(className + '0');
            $(this).addClass(className + index);
        });
    }
</script>
<style>
    #table_scroll tbody {
        display: block;
        max-height: 300px;
        overflow: auto;
    }

    #table_scroll table thead tr {
        display: table;
    }
</style>
@include('layouts.php_js_validation') @endsection
