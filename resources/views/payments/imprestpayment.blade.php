@extends('layouts.header')
@section('content')
<h3 class="text-danger">
<?php if($return=="paymenttravelclaim") { ?>
 Payment For Travel Claim
 <?php } else if($return=="paymentimprest") {  ?>
  Payment For Imprest
 <?php } else if($return=="paymentesi") {  ?>
  Payment For ESI
 <?php } else if($return=="paymentpf") {  ?>
  Payment For PF
 <?php } ?>

</h3>
  @include('layouts.breadcrumb')
<?php error_reporting(0); ?>



            <form method="post" action="" id="paymentinv_form" data-parsley-validate>
                {{ csrf_field() }}
                <div class="card shadow-lg rounded-4 border-0">
                <div class="card-body card-block">
                    <div class="row">
                            <div class="col-md-4">
                                <input class="form-control payment_id" id="payment_id" name="payment_id" size="16" type="hidden" value="{{ $row->payment_id }}" readonly>
                                <input class="form-control reference_id" id="reference_id" name="reference_id" size="16" type="hidden" value="{{ $reference_id }}" readonly>
                                <input class="form-control payment_number" id="payment_number" name="payment_number" size="16" type="hidden" value="{{ $row->payment_number }}" readonly>
                                <input class="form-control employee_id" id="employee_id" name="employee_id" size="16" type="hidden" value="{{ $row->employee_id }}" readonly>
                  
                       <div class="row mb-3">
                                    <label for="inputIsValid" class="col-form-label col-md-6">Payment Date</label>
                                    <div class="col-md-6">
                                        <div class="input-group form_date col-md-8" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                            <input class="form-control start_date payment_date" id="payment_date" name="payment_date" size="16" type="text" value="{{ $row->payment_date }}" >
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-2 showline">
                                    </div>
                                </div>
                                <?php if($row->directpay=="Yes"){?>
                                <div class="row mb-3 chequediv">
                                <label for="inputIsValid" class="col-form-label col-md-6">Employee Name</label>
                                <div class="col-md-6 sel2">
                                    <select name='employee_id' rows='5' id='employee_id' class='select2 employee_id' required>
                                        {!! $employee_id  !!}
                                    </select>
                                </div>
                            </div>
                                <?php } ?>
                           
                           <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-6"><?php if($return=="paymenttravelclaim") { ?>
			 Travel Claim 
			 <?php } else if($return=="paymentimprest") {  ?>
			 Imprest
			 <?php } else if($return=="paymentesi") {  ?>
			 ESI
			 <?php } else if($return=="paymentpf") {  ?>
			 PF
			 <?php }?> Amount</label>
                            <div class="col-md-6">
                                <input type="text" id="invoice_amount" name="invoice_amount" class="form-control invoice_amount chckclick" value="{{ $row->invoice_amount }}" readonly>
                            </div>
                            <div class="col-md-2 showline">
                            </div>
                        </div>  
                                    <?php  if($return=="paymentesi" || $return=="paymentpf" ) {  ?>
                                
                                     <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-6">Employee Amount</label>
                            <div class="col-md-6">
                                <input type="text" id="employee_amount" name="employee_amount" class="form-control employee_amount chckclick" value="{{ $row->employee_amount }}" readonly>
                            </div>
                            <div class="col-md-2 showline">
                            </div>
                        </div> 
                                   <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-6">Company Amount</label>
                            <div class="col-md-6">
                                <input type="text" id="company_amount" name="company_amount" class="form-control company_amount chckclick" value="{{ $row->company_amount }}" readonly>
                            </div>
                            <div class="col-md-2 showline">
                            </div>
                        </div> 
                                    <?php } if($return=="paymentpf" ) {  ?>
                                    <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-6">Company Amount1</label>
                            <div class="col-md-6">
                                <input type="text" id="company_amount1" name="company_amount1" class="form-control company_amount1 chckclick" value="{{ $row->company_amount1 }}" readonly>
                            </div>
                            <div class="col-md-2 showline">
                            </div>
                        </div> 
                               <?php  } ?>
<div class="row mb-3 chequediv">
                                <label for="inputIsValid" class="col-form-label col-md-6"><span class="bankdiv" style="color:red">*</span>Bank Name</label>
                                <div class="col-md-6 sel2">
                                    <select name='bank_id' rows='5' id='bank_id' class='select2 bank_id' required>
                                        {!! $bank_id  !!}
                                    </select>
                                </div>
                        </div>
 <div class="row mb-3 chequediv" >
                                <label for="inputIsValid" class="col-form-label col-md-6">Account Number</label>
                                <div class="col-md-6 supplier_div">
                                    <select name='account_no' rows='5' id='account_no' class='select2 account_no'>
                                       
                                    </select>
                                </div>
                                <div class="col-md-2 showinline">
                                    </div>
                        </div>						
                       
       </div>

                <div class="col-md-4">
                      <div class="row mb-3" >
                      <label for="inputIsValid" class="col-form-label col-md-6"><span style="color:red">*</span>Payment Type</label>
                      <div class="col-md-6 sel2">
                          <select name='payment_type_id' rows='5' class='form-control payment_type_id select2' data-show-subtext="true" data-live-search="true" required>
                              <option value="">--Please Select--</option>
                              <option <?php if($row->payment_type_id =="CHEQUE") { echo "selected"; } else { echo ""; } ?> value="CHEQUE">CHEQUE</option>
                              <option <?php if($row->payment_type_id =="CASH") { echo "selected"; } else { echo ""; } ?> value="CASH">CASH</option>
                              <option <?php if($row->payment_type_id =="NEFT") { echo "selected"; } else { echo ""; } ?> value="NEFT">NEFT</option>
                              <option <?php if($row->payment_type_id =="MTPS") { echo "selected"; } else { echo ""; } ?> value="MTPS">MTPS</option>
                              <option <?php if($row->payment_type_id =="IMPS") { echo "selected"; } else { echo ""; } ?> value="IMPS">IMPS</option>
                              <option <?php if($row->payment_type_id =="RTGS") { echo "selected"; } else { echo ""; } ?> value="RTGS">RTGS</option>
							<option <?php if($row->payment_type_id =="ONLINE") { echo "selected"; } else { echo ""; } ?> value="ONLINE">ONLINE</option>
						     <option <?php if($row->payment_type_id =="IMPREST") { echo "selected"; } else { echo ""; } ?> value="IMPREST">IMPREST</option>
                              </select>
                      </div>
                      <div class="col-md-2">
                      </div>
                </div> 
				 <div class="row mb-3">
                    <label for="inputIsValid" class="col-form-label col-md-6">UTR Number</label>
                    <div class="col-md-6">
                        <input type="text" id="payment_reference" name="payment_reference" class="form-control payment_reference" value="{{ $row->payment_reference }}" />
                    </div>
                    <div class="col-md-2">
                    </div>
                </div>
                      <div class="row mb-3">
                                     <label for="inputIsValid" class="col-form-label col-md-6">Narration</label>
                                     <div class="col-md-6">
                                         <input type="text" name="remarks"  id="remarks"  class="form-control remarks" value="{{ $row->remarks }}" >

                                     </div>
                                     <div class="col-md-2">
                                     </div>
                                 </div> 
                           <div class="row mb-3 chequediv">
                                <label for="inputIsValid" class="col-form-label col-md-6 chequelabel" >Cheque No</label>
                                <div class="col-md-6 supplier_div">

                                        <input type="number" id="cheque_no" name="cheque_no" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="form-control cheque_no" value="{{ $row->cheque_no }}" readonly />
                                </div>
                                <div class="col-md-2 showinline">
                                    </div>
                            </div>
                        
                                <?php  if($return=="paymentpf" ) {  ?>
                                
                                     <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-6">Volunteer Pf</label>
                            <div class="col-md-6">
                                <input type="text" id="v_pf" name="v_pf" class="form-control v_pf chckclick" value="{{ $row->v_pf }}" readonly>
                            </div>
                            <div class="col-md-2 showline">
                            </div>
                        </div> 
                                   <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-6">EDLI Charges</label>
                            <div class="col-md-6">
                                <input type="text" id="edli_charge" name="edli_charge" class="form-control edli_charge chckclick" value="{{ $row->edli_charge }}" readonly>
                            </div>
                            <div class="col-md-2 showline">
                            </div>
                        </div> 
                                    <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-6">Admin Charges</label>
                            <div class="col-md-6">
                                <input type="text" id="admin_charge" name="admin_charge" class="form-control admin_charge chckclick" value="{{ $row->admin_charge }}" readonly>
                            </div>
                            <div class="col-md-2 showline">
                            </div>
                        </div> 
                               <?php  } ?>
                        
                       
                    </div>
                           
                      
            <div class="col-md-4">
                <div class="row mb-3 none">
                         <label for="inputIsValid" class="col-form-label col-md-6">Payment Source</label>
                         <div class="col-md-6">
                             <select type="text" name="payment_source" id="payment_source" class="form-control select2 payment_source" readonly >
                             <option value="">-- Please Select --</option>
                             <option <?php if($row->payment_source=="EXPENSE" ) echo "selected"; ?> value="EXPENSE">EXPENSE</option>
                             <option <?php if($row->payment_source=="IMPREST" ) echo "selected"; ?> value="IMPREST">IMPREST</option>
                             <option <?php if($row->payment_source=="TRAVEL" ) echo "selected"; ?> value="TRAVEL">TRAVEL</option>
                             <option <?php if($row->payment_source=="ESI" ) echo "selected"; ?> value="ESI">ESI</option>
                             <option <?php if($row->payment_source=="PF" ) echo "selected"; ?> value="PF">PF</option>
                             
                             </select>
                         </div>
                         <div class="col-md-2">
                         </div>
                     </div>
               
                <div class="row mb-3">
                          <label for="inputIsValid" class="col-form-label col-md-6"><span style="color:red;">*</span>Account Code</label>
                          <div class="col-md-6 accountcode_div">
                              <select name='account_code_id' rows='5' class='select2 account_code_id' required>
                                  {!! $account_code_id !!}
                              </select>
                          </div>
                          <div class="col-md-2 showinline">
                          </div>
                </div>

                          <div class="row mb-3" style="display:none;">
                         <label for="inputIsValid" class="col-form-label col-md-6">Payment Status</label>
                         <div class="col-md-6">
                             <select type="text" name="payment_status" id="payment_status" class="form-control  payment_status" readonly >
                             <option value="">-- Please Select --</option>
                             <option <?php if($row->payment_status=="PAID" ) echo "selected"; ?> value="PAID">PAID</option>
                             <option <?php if($row->payment_status=="UNPAID" ) echo "selected"; ?> value="UNPAID">UNPAID</option>
                             <option <?php if($row->payment_status=="OVERDUE" ) echo "selected"; ?> value="OVERDUE">OVERDUE</option>
               <option <?php if($row->payment_status=="PARTIALLY PAID" ) echo "selected"; ?> value="PARTIALLY PAID">PARTIALLY PAID</option>
                             </select>
                         </div>
                         <div class="col-md-2">
                         </div>
                     </div>

                          <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-6"><span style="color:red">*</span>Payment Amount</label>
                            <div class="col-md-6">
                                <input type="text" id="payment_amount" name="payment_amount" class="form-control payment_amount chckclick" value="{{ $row->invoice_amount }}" required >
                            </div>
                            <div class="col-md-2 showinline">
                            </div>
                        </div>
                           <?php  if($return=="paymentesi" || $return=="paymentpf" ) {  ?>
                  <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-6">Penality Amount</label>
                            <div class="col-md-6">
                                <input type="text" id="penality_amount" name="penality_amount" class="form-control penality_amount chckclick"   >
                            </div>
                            <div class="col-md-2 showinline">
                            </div>
                        </div>
                           <?php } ?>
                    <?php  if($return=="paymentpf" ) {  ?>
                   <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-6">Discount Amount</label>
                            <div class="col-md-6">
                                <input type="text" id="discount_amount" name="discount_amount" class="form-control discount_amount chckclick"   >
                            </div>
                            <div class="col-md-2 showinline">
                            </div>
                        </div>
                    <?php }?>
                <div class="row mb-3 cheque_date">
                                    <label for="inputIsValid" class="col-form-label col-md-6">Cheque Date</label>
                                    <div class="col-md-6">
                                        <div class="input-group form_date col-md-8" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                            <input class="form-control datepicker cheque_date" id="cheque_date" name="cheque_date" size="16" type="text" value="{{ $row->cheque_date }}" >
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-2 showline">
                                    </div>
                                </div>
                      </div>



                        </div>
					
                        <div class="row mt-4">
                            <div class="col-lg-12 col-md-12">
                                <input type="hidden" name="submit_type" class="submit_type" value="" />
                                <div class="form-group text-center actionbtn">
                                    <button name="submit" type="button" class="btn btn-success px-4 me-2 saveform" value="SAVE">Save</button>
                                    <a class='btn btn-danger px-4 me-2' onclick='location.href ="{{ URL::to($return) }}"'>Cancel</a>
                                </div>
                            </div>
                        </div>
					
                    </div>
                </div>

               
            </form>
            
@endsection
@push('scripts')    

<script>

$(document).ready(function(){
  $('select,.accountcode_div').css('pointer-events', 'none');
  
  <?php if($row->directpay=="Yes"){?>
         $(".invoice_amount").attr("readonly",false); 
  <?php }?>


	$(document).on('keyup','.penality_amount',function(){
		var penality_amt=parseFloat($(".penality_amount").val());
		var invoice_amount=parseFloat($(".invoice_amount").val());
		var discount_amount=isNaN(parseFloat($(".discount_amount").val()))?0:(parseFloat($(".discount_amount").val()));
		var total=isNaN((invoice_amount+penality_amt-discount_amount))?0:(invoice_amount+penality_amt-discount_amount);
		$(".payment_amount").val(total.toFixed(2));
	});


	<?php  if($return=="paymentpf") {  ?>
	$(document).on('keyup','.discount_amount',function(){
		var penality_amt=parseFloat($(".penality_amount").val());
		var invoice_amount=parseFloat($(".invoice_amount").val());
		var discount_amount=parseFloat($(".discount_amount").val());
		
		if(discount_amount!=""){
			var penality_amt=isNaN((penality_amt))?0:(penality_amt);
		var sum=isNaN((invoice_amount+penality_amt-discount_amount))?0:(invoice_amount+penality_amt-discount_amount);
		$(".payment_amount").val(sum.toFixed(2));
		}
	});
<?php } ?>
	
$(document).on('click','.advance_deduction',function(){
    var check=$(this).val();
    var invoiceid=$('.po_invoice_id').val();
    var url="{{URL::to('getadvance')}}/"+invoiceid;
    $.get(url,function(data){
         if(check=='1'){
        $('.balance_amount').val(data.advance_deduction);
        $('.invoice_amount').val(data.advance_deduction);  
        $('.checkadv').html(data.advance_deduction);
    }
    else{
        $('.balance_amount').val(data.balance_amount);  
        $('.invoice_amount').val(data.invoice_grand_total); 
    }
        });
});
	
$(document).on('click','.addpayamt',function(){
       var tot = 0;
       var index = $(this).closest('tr').index();
       var payment_amount=parseInt($(".payment_amount").val()); 
       var balance_amount=parseInt($('.balance_amount'+index).attr('id'));
      $(".paymentamt").each(function(){
          tot += parseInt($(this).val());
      });
     
          <?php if($statement =="0") { ?>
                $('.payment_amount').val(tot);
        <?php } else { ?>
             
    if(tot > payment_amount || tot < payment_amount ){
                 showCustomAlert('Payment amount is not Equal to Statement amount','info');
                  $('.paymentamt').val('');
              } 
     
             
        <?php } ?>
     
  });
    
    
    
    $(document).on('keyup','.paymentamt',function(){
              var index = $(this).closest('tr').index();
             var payment=parseInt($(this).val());  
              var balance_amount=parseInt($('.balance_amount'+index).attr('id'));
               var payment_amount=$(".payment_amount").val(); 

 if(payment > balance_amount)
              {
                 showCustomAlert('Payment amount is not more than balance amount','info');
                  $(this).val('');
              }
         
    });
    /* end */
    
    
     $(document).on('keyup','.paymentamt',function(){

              var payment=parseInt($(this).val());  
       		 var payment_amt=$(".payment_amount").val(); 
              if(payment > payment_amount)
              {
                 showCustomAlert('Payment amount is not more than Statement amount','info');
                  $(this).val('');
              }      
    });

  $(document).on('keyup','.payment_amount',function(){
      var invoice_amt=parseInt($(".invoice_amount").val());
      var payment_amt=parseInt($(".payment_amount").val()); 
    var paid_amt=parseInt($(".paid_amount").val()); 
    var balance_amt=parseInt($(".balance_amount").val()); 
    if(payment_amt > invoice_amt){
      showCustomAlert("Payment Amount Exceeds",'error');
      $(".payment_amount").val("");
    }
    else{
      var num1 = isNaN(parseInt(payment_amt)) ? 0 : parseInt(payment_amt);
      $(".payment_amount").val(num1);
        $(".paid_amount").val(paid_amt);
          $(".balance_amount").val(balance_amt);
    }
  });
  
          /*Validation*/
  $(document).on('keypress','.payment_amount', function(ev){
      var regex = new RegExp("^[0-9.]+$");
          var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
          if (regex.test(str)) {
            return true;
          }
          ev.preventDefault();
          return false;
    });
  /*End*/
	
$('#bank_id').on('change', function () {
    var bank = $(this).val();

    if (bank !== '') {
        var url = "{{ URL::to('jcomboform') }}" +
                  "?table=f_bank_account_lines_t:bank_account_line_id:account_number" +
                  "&order_by=account_number asc" +
                  "&parent=bank_account_hdr_id=" + bank;

        $.ajax({
            url: url,
            type: 'GET',
            success: function (data) {
                // Parse JSON if returned as string
                if (typeof data === "string") {
                    try {
                        data = JSON.parse(data);
                    } catch (e) {
                        console.error("Invalid JSON response:", data);
                        return;
                    }
                }

                var $account = $(".account_no");
                $account.empty().append('<option value="">-- Select Account --</option>');

                $.each(data, function (i, item) {
                    $account.append(`<option value="${item.val}">${item.option_name}</option>`);
                });

                $account.trigger('change.select2'); // refresh Select2 if used
            },
            error: function () {
                console.error("Failed to load account numbers");
            }
        });
    } else {
        $(".account_no").empty().append('<option value="">-- Select Account --</option>');
    }
});


     $(".imprestemp_div").hide();
$('.payment_type_id').on('change',function(){
    var pmttypeid=$('.payment_type_id option:selected').text();
    var pmttype=$.trim(pmttypeid);
     if(pmttype!="CASH" && pmttype!="IMPREST"){
        $('.chequediv').css('display','block');
    }
	else{
		if(pmttype=="IMPREST"){
			$(".imprestemp_div").show();
		 var url="{{URL::to('getimprestaccount')}}";
    $.get(url,function(data){
		$('.account_code_id').val(data[0].imprest_account_id).change();  
        });
		}else{
		 var url="{{URL::to('getcashaccount')}}";
		 $.get(url,function(data){
				$('.account_code_id').val(data[0].cash_account_id).change();  
        });			
		}
        $('#bank_id').prop('required',false);
        $('.chequediv,.bankdiv').css('display','none');
    
	}

 if(pmttype=="CHEQUE"){

          $('.chequelabel').html('Cheque No'); 
           $('.cheque_no').attr('readonly',true);
              $('.cheque_date').show();      
              }else{
                  $('.chequelabel').html('Reference No');
                  $('.cheque_no').attr('readonly',false);
                    $('.cheque_date').hide();      
              }
              


}); 
	
/* Purpose for Bank based Account Code load*/
$(document).on('change','.account_no',function(){
    var account_no=$('.bank_id').val();
    var url="{{URL::to('getaccountdetails')}}/"+account_no;
    $.get(url,function(data){
    $('.account_code_id').val(data[0].account_code_id).change();  
    
        });
});
/*End*/

/*Karthigaa Purpose for Default Cheque Number*/
$(document).on('change','.account_no',function(){
    var accid=$('.account_no').val();
    var cheque_no=$('.cheque_no').val();
    $('.mcontent2').html('');
    if(accid!=""){
    var url ="{{URL::to('getchequeno')}}/"+accid;
    $.get(url,function(data){
		var end=$.trim(data['endcheque']);
		var chequeno =$.trim(data['chequeno']);
		if($.trim(data['nocheque'])=="nocheque"){
			notyMsgs('error','Please Assign Cheque for this Account');
                    }
       else if(chequeno > end ){
                 showCustomAlert('No Cheque Found','error');
           }
           else{
               $(".cheque_no").val(data['chequeno']);
           }
    });
    }
    else{
             $(".cheque_no").val('');
    }
    });

 /* Purpose For Save Function*/      
      
    $(document).on('click', '.saveform', function() {
          var btnval = $(this).val();
      var invoice_amount=$('.invoice_amount').val();
      var payment_amount=$('.payment_amount').val();
            if(invoice_amount==payment_amount){
        $(".payment_status").val("PAID");
      }
      else if(invoice_amount != payment_amount ){
        $(".payment_status").val('PARTIALLY PAID');
      }
      else{
        $(".payment_status").val('UNPAID');
      }
            $('#savestatus').val(btnval);
                    
            var url = "{{ url('accountimprestsave') }}";
            var red_url = "{{URL::to($return)}}"
            validationrule('paymentinv_form');
            var formdata = $('#paymentinv_form').serialize();
            var form = $('#paymentinv_form');
            form.parsley().validate();
            var form = $('#paymentinv_form');
            form.parsley().validate();
            if (form.parsley().isValid())
            {
            var $btn = $(this);            
			$btn.prop('disabled', true);
           var formdata = $('#paymentinv_form').serialize();
            $.post(url, formdata, function(data)
            {
            var status = data.status;
            var msg =  data.message;
            var id = data.id;
            var edit_url = "{{ url('paymentforinvoicecreate') }}/" + id;
            if (btnval != 'SAVE' && btnval != 'DRAFT')
            {
            showCustomAlert(msg,status);
            setTimeout(function(){
            window.location.href = red_url;
            }, 1500);
            }
            else
            {
            showCustomAlert(msg,status);
            setTimeout(function(){
            window.location.href = red_url;
            }, 1500);
            }
            });
            }
    });
    });


	

    $(document).on("focus", ".cheque_date", function () {

        $(this).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            minDate: -90, 
            maxDate: +30,
            showAnim: "slideDown",
            yearRange: "-25:+0",

        });
    });


    
     // cheque number validation
    
        $(document).ready(function(){
        $('#cheque_no').on('input', function() {
            var chequeNo = $(this).val();
            if (chequeNo.length > 6) {
               showCustomAlert('Please Enter valid cheque number','info');
                $(this).val(chequeNo.slice(0, 6));
            }
        });

        $('#cheque_no').on('blur', function() {
            var chequeNo = $(this).val();
            if (chequeNo.length < 6 && chequeNo.length > 0) {
                showCustomAlert('Please Enter valid cheque number','error');
                $(this).focus(); 
            }
        });
    });
	
</script>


@endpush
