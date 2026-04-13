@extends('layouts.header') @section('content')



<style type="text/css">

@media only screen and (min-width: 1500px) {
  .bulk_line_no {width: 50px !important;}
  .bulk_invoice_hdr_id {width: 160px !important;}
  .bulk_batch_invoice_amount {width: 95px !important;}
  .bulk_receipt_amount {width: 96px !important;}
  .bulk_paid_amount {width: 100px !important;}
  .bulk_balance_amount {width: 110px !important;}
  .bulk_comments {width: 190px !important;}

}
@media only screen and (min-width: 2000px) {
  .bulk_line_no {width: 50px !important;}
  .bulk_invoice_hdr_id {width: 260px !important;}
  .bulk_batch_invoice_amount {width: 195px !important;}
  .bulk_receipt_amount {width: 196px !important;}
  .bulk_paid_amount {width: 200px !important;}
  .bulk_balance_amount {width: 210px !important;}
  .bulk_comments {width: 290px !important;}

}

.bulk_line_no {width: 50px;}
.bulk_invoice_hdr_id {width: 150px;}
.bulk_batch_invoice_amount {width: 85px;}
.bulk_receipt_amount {width: 86px;}
.bulk_paid_amount {width: 90px;}
.bulk_balance_amount {width: 100px;}
.bulk_comments {width: 190px;}


</style>


<h4 class="heads">
  <a role="button">
     Receipts
  </a>
  <span class="ui_close_btn">
    <a class="collapse-close pull-right btn-danger" onclick="location.href = '{{ url('payments') }}'"></a></span>
</h4>

                            





<form method="post" action="" id="receipt_form" data-parsley-validate>
{{ csrf_field() }}

<div class="card">
<div class="card-header">
<div class="row">
        <div class="col-md-3">
            <label class="align_left receipt_number">Receipt Number:{{ $row->receipt_number }}   </label>
            <label class="align_left">Created By:<span class='create_by span_color'></span></label>
        </div>
        <div class="col-md-3">
            <label class="align_left"> Receipt Date:<?php echo date(\Session::get('p_date_format'),strtotime($row->receipt_date));?></label></br>
            <label class="align_left">Organization:<span class='org span_color'></sapn></label></br>
        </div>
        <div class="col-md-3">
            <label class="align_left">Receipt Status:{{ $row->receipt_status }}   </label>
        </div>
        <div class="col-md-3">
            <label class="align_left">  Batch Total Amount:<span class='batch_total_span span_color'>{{ $row->batch_total_amount }}</span></label></br>
        </div>
</div>
</div>
                <div class="card-body card-block">
                <!------------------------------- toggle content start ---------------------------->
                <div class="row">
                <div class="col-md-12">

                        <div class="row">
                         <div class="col-md-4">
                    <div class="form-group row">
                        <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red">*</span>Batch Name</label>
                        <div class="col-md-6 sel2">
                            <input class="form-control receipt_hdr_id" id="receipt_hdr_id" name="receipt_hdr_id" size="16" type="hidden" value="{{ $row->receipt_hdr_id }}" readonly>
                            <input class="form-control receipt_number" id="receipt_number" name="receipt_number" size="16" type="hidden" value="{{ $row->receipt_number }}" readonly>
                            <!--<input class="form-control batch_total_amount" id="batch_total_amount" name="batch_total_amount" size="16" type="hidden" value="{{ $row->batch_total_amount }}" readonly>-->
                            <select name='receipt_batch_hdr_id' rows='5' id='receipt_batch_hdr_id' class='select2 receipt_batch_hdr_id' required>
                                {!! $receipt_batch_hdr_id !!}
                            </select>
                        </div>
                        <div class="col-md-2 showinline">
                            </div>
                    </div>
                               <div class="form-group row" style="display:none">
                                    <label for="inputIsValid" class="form-control-label col-md-4">Receipt Date</label>
                                    <div class="col-md-6">
                                        <div class="input-group form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                            <input class="form-control datepicker receipt_date" id="receipt_date" name="receipt_date" size="16" type="text" value="{{ $row->receipt_date }}" readonly>
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 showline">
                                    </div>
                                </div>
                 <div class="form-group row" style="display:none">
                         <label for="inputIsValid" class="form-control-label col-md-4">Receipt Status</label>
                         <div class="col-md-6">
                             <select type="text" name="receipt_status" id="receipt_status" class="receipt_status" readonly>
                             <option value="">-- Please Select --</option>
                             <option <?php if($row->receipt_status=="DRAFT" ) echo "selected"; ?> value="DRAFT">DRAFT</option>
                             <option <?php if($row->receipt_status=="INITIATED" ) echo "selected"; ?> value="INITIATED">INITIATED</option>
                             </select>
                         </div>
                         <div class="col-md-2">
                         </div>
                     </div>
                    <div class="form-group row">
                                <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red">*</span>Bank Name</label>
                                <div class="col-md-6 sel2">
                                    <select name='bank_id' rows='5' id='bank_id' class='select2 bank_id' required>
                                        {!! $bank_id  !!}
                                    </select>
                                </div>
                                <div class="col-md-2 showinline">
                                    <span class="showspan"> <i class="fa fa-refresh jcr_bank_id"></i></span>
                                </div>

                    </div>
                    <div class="form-group row" >
                      <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red;">*</span>Receipt Type</label>
                      <div class="col-md-6 sel2">
                          <select name='receipt_type_id' rows='5' class='form-control receipt_type_id select2' data-show-subtext="true" data-live-search="true" required>
                              <option value="">--Please Select--</option>
                              <option <?php if($row->receipt_type_id =="CHEQUE") { echo "selected"; } else { echo ""; } ?> value="CHEQUE">CHEQUE</option>
                              <option <?php if($row->receipt_type_id =="CASH") { echo "selected"; } else { echo ""; } ?> value="CASH">CASH</option>
                              <option <?php if($row->receipt_type_id =="NEFT") { echo "selected"; } else { echo ""; } ?> value="NEFT">NEFT</option>
                              <option <?php if($row->receipt_type_id =="MTPS") { echo "selected"; } else { echo ""; } ?> value="MTPS">MTPS</option>

                          </select>
                      </div>
                      <div class="col-md-2">
                      </div>
                  </div>
                             <div class="form-group row" style="display:none">
                                    <label for="inputIsValid" class="form-control-label col-md-4">batch Total Amount</label>
                                    <div class="col-md-6">
                                        <input type="text" id="batch_total_amount" name="batch_total_amount" class="form-control batch_total_amount" value="{{ $row->batch_total_amount }}"  />
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                </div>
                              <div class="form-group row sourcediv">
                                    <label for="inputIsValid" class="form-control-label col-md-4">Advance Amount</label>
                                    <div class="col-md-6">
                                        <input type="text" id="advance_amount" name="advance_amount" class="form-control advance_amount" value="{{ $row->advance_amount }}"  />
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                </div>


			</div>
                        <div class="col-md-4">
                            <div class="form-group row">
                                <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red;">*</span>Customer Name</label>
                                <div class="col-md-6 supplier_div">
                                    <select name='customer_id' rows='5' id='customer_id' class='form-control customer_id ' required>
                                        {!! $customer_id !!}
                                    </select>
                                </div>
                                <div class="col-md-2 showinline">
                                    </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputIsValid" class="form-control-label col-md-4">Account Number</label>
                                <div class="col-md-6 supplier_div">
                                    <select name='account_no' rows='5' id='account_no' class='select2 account_no'>
                                        {!! $account_no !!}
                                    </select>
                                </div>
                                <div class="col-md-2 showinline">
                                    </div>
                            </div>
                            <div class="form-group row chequediv">
                                <label for="inputIsValid" class="form-control-label col-md-4">Cheque No</label>
                                <div class="col-md-6 supplier_div">
                                    <select name='cheque_no' rows='5' id='cheque_no' class='select2 cheque_no'>
                                         {!! $cheque_no !!}
                                    </select>
                                </div>
                                <div class="col-md-2 showinline">
                                    </div>
                            </div>
                              <div class="form-group row">
                                <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red;">*</span>Account Code</label>
                                  <div class="col-md-6 sel2">
                                    <select  name='account_structure_id' rows='5' class='account_structure_id select2' required >
                                        {!!$account_structure_id!!}
                                        </select>
                                  </div>
                                <div class="col-md-2 showinline">
                                        <span class="showspan"> <i class="fa fa-refresh jcr_account_structure_id"></i></span>
                                </div>
                            </div>
                        </div>

                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-4">Customer Site</label>
                                    <div class="col-md-6">
                                        <select name='customer_site_id' rows='5' id='customer_site_id' class='form-control customer_site_id'>
                                            {!! $customer_site_id  !!}
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                </div>
                    <div class="form-group row"  >
                      <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red;">*</span>Receipt Source</label>
                      <div class="col-md-6 sel2">
                          <select name='receipt_source' rows='5' class='form-control receipt_source select2' data-show-subtext="true" data-live-search="true" required>
                              <option value="">--Please Select--</option>
                              <option <?php if($row->receipt_source =="ORDER") { echo "selected"; } else { echo ""; } ?> value="ORDER">ORDER</option>
                              <option <?php if($row->receipt_source =="BATCH") { echo "selected"; } else { echo ""; } ?> value="BATCH">BATCH</option>
                         </select>
                      </div>
                      <div class="col-md-2">
                      </div>
                  </div>
                                 <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red;">*</span>Receipt Reference</label>
                                    <div class="col-md-6">
                                        <input type="text" id="receipt_reference" name="receipt_reference" class="form-control receipt_reference" value="{{ $row->receipt_reference }}" required />
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                </div>
                         <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-4">Remarks</label>
                                    <div class="col-md-6">
                                        <input type="text" name="remarks"  id="remarks"  class="form-control remarks" value="{{ $row->remarks }}" >

                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-4">
                                <div class="form-group row" style="display:none">
                                    <label for="inputIsValid" class="form-control-label col-md-4">Organization </label>
                                    <div class="col-md-6">
                                        <select name='organization_id' rows='5' id='organization_id' class='form-control organization_id'>
                                            {!! $organization_id  !!}
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                </div>
                                <div class="form-group row" style="display:none">
                                    <label for="inputIsValid" class="form-control-label col-md-4">Created By </label>
                                    <div class="col-md-6">
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

<!-- clone row End -->
<div class="row">
    <div class="col-md-12">
 <div id="preview-area" class="chandru">
    <table class="overflow-y preview receipt_table">

                <thead>
                    <tr>

                        <th>Line No</th>
                        <th>Invoice Number</th>
                        <th>Invoice Amount</th>
                        <th>Receipt Amount</th>
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
                            <input type="hidden" name="bulk_receipt_line_id[]" class="form-control  bulk_receipt_line_id" value="{!!  $value->receipt_line_id  !!}">
                        </td>
                        <td>
                            <input type="text"  name="bulk_line_no[]" class="form-control  bulk_line_no" value="{!! ($key+1)  !!}" readonly="readonly">
                        </td>
                        <td>
                            <select name="bulk_invoice_hdr_id[]" id="bulk_invoice_hdr_id" class="bulk_invoice_hdr_id" required="required" >{!! $value->invoice_hdr_id !!}</select>
                        </td>
                        <td>
                            <input type="text" name="bulk_batch_invoice_amount[]" class="form-control  bulk_batch_invoice_amount" value="{!! $value->batch_invoice_amount !!}" readonly="readonly"  >
                        </td>
                        <td>
                            <input type="text" name="bulk_receipt_amount[]" class="form-control  bulk_receipt_amount" required="required" value="{!! $value->receipt_amount !!}"  >
                        </td>
                         <td>
                            <input type="text" name="bulk_paid_amount[]" class="form-control  bulk_paid_amount" required="required" value="{!! $value->paid_amount !!}"  readonly >
                        </td>
                         <td>
                            <input type="text" name="bulk_balance_amount[]" class="form-control  bulk_balance_amount" required="required" value="{!! $value->balance_amount !!}" readonly >
                        </td>
                       <td>
                            <input type="text" name="bulk_comments[]" class="form-control  bulk_comments"  value="{!! $value->comments !!}" ></td>
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
                            <input type="hidden" name="bulk_receipt_line_id[]" class="form-control  bulk_receipt_line_id" value="">
                        </td>
                        <td>
                            <input type="text"  name="bulk_line_no[]" class="form-control  bulk_line_no" value="" readonly="readonly"  >
                        </td>
                        <td>
                            <select name="bulk_invoice_hdr_id[]" id="bulk_invoice_hdr_id" class="form-control bulk_invoice_hdr_id"  >{!! $invoice_hdr_id !!}</select>
                        </td>
                         <td>
                            <input type="text" name="bulk_batch_invoice_amount[]" class="form-control  bulk_batch_invoice_amount" value=""  >
                        </td>
                        <td>
                            <input type="text" name="bulk_receipt_amount[]" class="form-control  bulk_receipt_amount " value=""  >
                        </td>
                         <td>
                            <input type="text" name="bulk_paid_amount[]" class="form-control  bulk_paid_amount " value=""  >
                        </td>
                         <td>
                            <input type="text" name="bulk_balance_amount[]" class="form-control  bulk_balance_amount" value=""  >
                        </td>
                       <td>
                           <input type="text" name="bulk_comments[]" class="form-control  bulk_comments" row="1" value="" ></td>
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
<!--            <button type="button" class="btn applychanges saveform" value="APPLYCHANGES">Apply Changes</button>-->
            <button type="button" class="btn save saveform" value="SAVENEW">Save and New</button>
            <button type="button" class="btn draft saveform" value="DRAFT">Draft</button>
            <button type="button" class="btn save saveform" value="SAVE">Save</button>
            <a href="{{ url('receipts') }}" class='btn cancel'>Cancel</a>
        </div>
    </div>
</div>

<input type="hidden" class="pdtindex" value="" />
</div>

</div>

</form>


<script>
    $(document).ready(function() {
$('.bulk_invoice_hdr_id,.receipt_status,.customer_site_id,.customer_id').css("pointer-events","none");
// $('.sourcediv').css('display','none');
   $('.bulk_receipt_amount').attr("required","true");
   
     $(".jcr_account_structure_id").click(function(){
        $(".account_structure_id").jCombo("{{ URL::to('jcomboform?table=f_account_structure_t:f_account_structure_id:concatenated_segments') }}",
        {selected_value:""});
    });

           /*Validation*/
	$(document).on('keypress','.advance_amount,.bulk_receipt_amount', function(ev){
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


$('.receipt_source').on('change',function(){
    var pmtsrcid=$('.receipt_source option:selected').text();
    var pmtsrc=$.trim(pmtsrcid);
    if(pmtsrc=="ORDER"){
        $('.sourcediv').css('display','block');
    }else{
        $('.sourcediv').css('display','none');
    }
});

    $('.receipt_type_id').on('change',function(){
var pmttypeid=$('.receipt_type_id option:selected').text();
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



/*Karthigaa purpose:For Receipt Amount should not exceeds Invoice&Show Balance*/
 $(document).on('keyup','.bulk_receipt_amount',function(){
    var index = $(this).closest('tr').index();
    var id=$('.receipt_hdr_id').val();
    var inv_id = $('.bulk_invoice_hdr_id'+index+' option:selected').val();
    var invoice_amount=parseInt($('.bulk_batch_invoice_amount'+index).val());
    var receipt_amount=parseInt($('.bulk_receipt_amount'+index).val());
    var paid_amount=parseInt($('.bulk_paid_amount'+index).val());
    //var balance_amount =parseInt($('.bulk_balance_amount'+index).val());
    var bal_amount=parseInt($('.balance_amount'+index).val());

    var rcpt_amt = isNaN(parseInt(receipt_amount)) ? 0 : parseInt(receipt_amount);

    if(rcpt_amt == 0)
    {

       setTimeout(function(){
        $('.bulk_balance_amount'+index).val(bal_amount);
        }, 500);
    }

            if(paid_amount!=0)
            {
                if(receipt_amount>bal_amount)
                {
                    notyMsg('error','Receipt Amount Should not Exceeds than Balance Amount');
                    $('.bulk_receipt_amount'+index).val('');
                    $('.bulk_balance_amount'+index).val(bal_amount);

                }
                else
                {
                    var balamt= bal_amount-receipt_amount;
                    var num1 = isNaN(parseInt(balamt)) ? 0 : parseInt(balamt);
                    $('.bulk_balance_amount'+index).val(num1);
                }
            }
            else
            {

                if(receipt_amount>bal_amount)
                {
                    notyMsg('error','Receipt Amount Should not Exceeds than Balance Amount');
                    $('.bulk_receipt_amount'+index).val('');
                    $('.bulk_balance_amount'+index).val(bal_amount);
                }
                else
                {
                    var balamt= bal_amount-receipt_amount;
                    var num1 = isNaN(parseInt(balamt)) ? 0 : parseInt(balamt);
                    $('.bulk_balance_amount'+index).val(num1);
                }
            }


                var Total=0;
                    $('.bulk_receipt_amount').each(function(){
                             Total +=Number($(this).val());
                    });
                     $(".batch_total_amount").val(Total);
                     $(".batch_total_span").html(Total);

    });
/*Karthigaa purpose:to get supplier,supplier site based on batch name*/
$(document).on('change','.receipt_batch_hdr_id',function(){
    var batchid=$('#receipt_batch_hdr_id').val();
   if(batchid!=""){
      var url = "{{ URL::to('getreceiptbatchdetails') }}/"+batchid;
                $.get(url,function(data){
                     $('.customer_id').val(data[0].customerid);
                     $('.customer_site_id').val(data[0].customer_siteid);

		});
                $.get("{{ URL::to('rcptinvoicedetails') }}/"+batchid,function(data)
			{
				$('.receipt_table tbody').html('');
                                $('.pmt_lines_body').append(data);
				changeclassfields();
			});
    }
   else
   {
	  notyMsg('error','Please Select Receipt Batch');
   }
});


        var index = $('.clone').closest('tr').index();
        changeclassfields();



/****      Karthigaa purpose remove function    ***/
        $(document).on('click', '.remove', function() {
            var index = $(this).closest('tr').index();
            var rowCount = $('.receipt_table tbody tr').length;
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
			$("#receipt_status").val('DRAFT');
		}
            else if(btnval == 'DRAFT'){
			$("#receipt_status").val('DRAFT');
	    }else{
			$("#receipt_status").val('INITIATED');
		}
            $('#savestatus').val(btnval);
            var url = "{{ url('receiptssave') }}";
            var red_url = "{{ url('receipts') }}";
            var create_url = "{{ url('receiptscreate') }}";

            validationrule('receipt_form');
            var form = $('#receipt_form');
            if (btnval != 'APPLYCHANGES') {
                //   form.parsley().validate();
                var form = $('#receipt_form');
                form.parsley().validate();

                if (form.parsley().isValid()) {
                    change_date();
                    var formdata = $('#receipt_form').serialize();
                    $.post(url, formdata, function(data) {
                        var status = data.status;
                        var msg     = '<span style="color:#090065">'+data.auto_no+'</span>  '+data.message;
                        var id = data.id;
                        var edit_url = "{{ url('receiptscreate') }}/" + id;

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
                var formdata = $('#receipt_form').serialize();
                $.post(url, formdata, function(data) {

                    var status = data.status;
		var msg     = '<span style="color:#090065">'+data.auto_no+'</span>  '+data.message;
		var id     = data.id;
                    var edit_url = "{{ url('receiptscreate') }}/" + id;
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
        changeClassName('bulk_invoice_hdr_id');
        changeClassName('bulk_receipt_amount');
        changeClassName('bulk_paid_amount');
        changeClassName('bulk_balance_amount');
        changeClassName('bulk_batch_invoice_amount');
        changeClassName('bulk_account_structure_id');
        changeClassName('bulk_comments');
    }

    function removeclassfields() {
        removeClass('bulk_payment_line_id');
        removeClass('bulk_line_no');
        removeClass('bulk_invoice_hdr_id');
        removeClass('bulk_batch_invoice_amount');
        removeClass('bulk_receipt_amount');
        removeClass('bulk_paid_amount');
        removeClass('bulk_balance_amount');
        removeClass('bulk_account_structure_id');
        removeClass('bulk_comments');
    }
    /************ Maruthu purpose to remove row action ********************/
    function removeClass(className)
	{
        var rowCount = $('.receipt_table tbody tr').length;
        for (var i = 0; i <= rowCount; i++) {
            $('.receipt_table tbody tr').find('.' + className).removeClass(className + i);
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
@include('layouts.php_js_validation')
@endsection
