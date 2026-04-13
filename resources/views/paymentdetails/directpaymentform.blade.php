@extends('layouts.header')
@section('content')
<h3 class="text-danger">Direct Payment</h3>
@include('layouts.breadcrumb')
<?php error_reporting(0); ?>



            <form method="post" action="" id="directpayment_form" data-parsley-validate>
                {{ csrf_field() }}
			<div class="card shadow-lg rounded-4 border-0">
                <div class="card-body card-block">
					
   
   
   <div class="row mt-2">

        <input class="form-control payment_id" id="payment_id" name="payment_id" size="16" type="hidden"
            value="{{ $row->payment_id }}" readonly>
        <input class="form-control reference_id" id="reference_id" name="reference_id" size="16" type="hidden"
            value="{{ $row->reference_id }}" readonly>
        <input class="form-control payment_number" id="payment_number" name="payment_number" size="16" type="hidden"
            value="{{ $row->payment_number }}" readonly>




        <div class="col-md-4">
            <div class="row mb-3">
                <label for="inputIsValid" class="col-form-label col-md-6"><span style="color:red">*</span>Payment
                    Type</label>
                <div class="col-md-6 sel2">
                    <select name='payment_type_id' rows='5' class='form-control payment_type_id select2'
                        data-show-subtext="true" data-live-search="true" required>
                        <option value="">--Please Select--</option>
                        <option <?php if ($row->payment_type_id == "CHEQUE") {
                            echo "selected";
                        } else {
                            echo "";
                        } ?>
                            value="CHEQUE">CHEQUE</option>
                        <option <?php if ($row->payment_type_id == "CASH") {
                            echo "selected";
                        } else {
                            echo "";
                        } ?>
                            value="CASH">CASH</option>
                        <option <?php if ($row->payment_type_id == "NEFT") {
                            echo "selected";
                        } else {
                            echo "";
                        } ?>
                            value="NEFT">NEFT</option>
                        <option <?php if ($row->payment_type_id == "MTPS") {
                            echo "selected";
                        } else {
                            echo "";
                        } ?>
                            value="MTPS">MTPS</option>
                        <option <?php if ($row->payment_type_id == "IMPS") {
                            echo "selected";
                        } else {
                            echo "";
                        } ?>
                            value="IMPS">IMPS</option>
                        <option <?php if ($row->payment_type_id == "RTGS") {
                            echo "selected";
                        } else {
                            echo "";
                        } ?>
                            value="RTGS">RTGS</option>
                        <option <?php if ($row->payment_type_id == "ONLINE") {
                            echo "selected";
                        } else {
                            echo "";
                        } ?>
                            value="ONLINE">ONLINE</option>
                        <option <?php if ($row->payment_type_id == "IMPREST") {
                            echo "selected";
                        } else {
                            echo "";
                        } ?>
                            value="IMPREST">IMPREST</option>
                    </select>
                </div>
                <div class="col-md-2">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputIsValid" class="col-form-label col-md-6">Payment Date</label>
                <div class="col-md-6">
                    <div class="input-group form_date col-md-8" data-date="" data-date-format="dd MM yyyy"
                        data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                        <input class="form-control start_date payment_date" id="payment_date" name="payment_date" size="16"
                            type="text" value="{{ $row->payment_date }}">

                    </div>
                </div>
                <div class="col-md-2 showline">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputIsValid" class="col-form-label col-md-6"><span style="color:red">*</span>Payment
                    Amount</label>
                <div class="col-md-6">
                    <input type="text" id="payment_amount" name="payment_amount"
                        class="form-control payment_amount chckclick" value="" required autocomplete="off">
                </div>
                <div class="col-md-2 showinline">
                </div>


            </div>
            <div class="row mb-3">
                <label for="inputIsValid" class="col-form-label col-md-6"><span style="color:red;">*</span>Direct
                    Account Code</label>
                <div class="col-md-6">
                    <select name='direct_accountcodeid' rows='5' class='select2 direct_accountcodeid' required>
                        {!! $direct_accountcodeid !!}
                    </select>
                </div>
                <div class="col-md-2 showinline">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputIsValid" class="col-form-label col-md-6">Favouring Name</label>
                <div class="col-md-6">
                    <input type="text" id="favouring_name" name="favouring_name"
                        class="form-control favouring_name chckclick" value="">
                </div>
                <div class="col-md-2 showinline">
                </div>
            </div>
        </div>
        <div class="col-md-4">

            <div class="row mb-3 chequediv">
                <label for="inputIsValid" class="col-form-label col-md-6"><span class="bankdiv"
                        style="color:red">*</span>Bank Name</label>
                <div class="col-md-6 sel2">
                    <select name='bank_id' rows='5' id='bank_id' class='select2 bank_id' required>
                        {!! $bank_id !!}
                    </select>
                </div>
            </div>
            <div class="row mb-3 chequediv">
                <label for="inputIsValid" class="col-form-label col-md-6">Account Number</label>
                <div class="col-md-6 supplier_div">
                    <select name='account_no' rows='5' id='account_no' class='select2 account_no'>
                    </select>
                </div>
                <div class="col-md-2 showinline">
                </div>
            </div>
            <div class="row mb-3 chequediv">
                <label for="inputIsValid" class="col-form-label col-md-6 chequelabel">Cheque No</label>
                <div class="col-md-6 supplier_div">
                    <input type="number" id="cheque_no" name="cheque_no"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="form-control cheque_no"
                        value="{{ $row->cheque_no }}" readonly />
                </div>
                <div class="col-md-2 showinline">
                </div>
            </div>
            <div class="row mb-3 cheque_date">
                <label for="inputIsValid" class="col-form-label col-md-6">Cheque Date</label>
                <div class="col-md-6">
                    <div class="input-group form_date col-md-8" data-date="" data-date-format="dd MM yyyy"
                        data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                        <input class="form-control datepicker cheque_date" id="cheque_date" name="cheque_date" size="16"
                            type="text" value="{{ $row->cheque_date }}">

                    </div>
                </div>
                <div class="col-md-2 showline">
                </div>
            </div>
        </div>


        <div class="col-md-4">
            <div class="row mb-3">
                <label for="inputIsValid" class="col-form-label col-md-6">Payment Source</label>
                <div class="col-md-6">
                    <select type="text" name="payment_source" id="payment_source" class="form-control  payment_source"
                        readonly>
                        <option value="">-- Please Select --</option>
                        <option <?php if ($row->payment_source == "EXPENSE")
                            echo "selected"; ?> value="EXPENSE">EXPENSE
                        </option>
                        <option <?php if ($row->payment_source == "IMPREST")
                            echo "selected"; ?> value="IMPREST">IMPREST
                        </option>
                        <option <?php if ($row->payment_source == "DIRECTPAYMENT")
                            echo "selected"; ?> value="DIRECTPAYMENT">
                            DIRECTPAYMENT</option>

                    </select>
                </div>
                <div class="col-md-2">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputIsValid" class="col-form-label col-md-6">UTR Number</label>
                <div class="col-md-6">
                    <input type="text" id="payment_reference" name="payment_reference"
                        class="form-control payment_reference" value="{{ $row->payment_reference }}" />
                </div>
                <div class="col-md-2">
                </div>
            </div>
            <div class="row mb-3 imprestemp_div">
                <label for="inputIsValid" class="col-form-label col-md-6">Employee Name</label>
                <div class="col-md-6">
                    <select name='imprest_employee_id' rows='5' class='select2 imprest_employee_id'>
                        {!! $imprest_employee_id !!}
                    </select>
                </div>
                <div class="col-md-2 showinline">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputIsValid" class="col-form-label col-md-6"><span style="color:red;">*</span>Account
                    Code</label>
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
                    <select type="text" name="payment_status" id="payment_status" class="form-control  payment_status"
                        readonly>
                        <option value="">-- Please Select --</option>
                        <option <?php if ($row->payment_status == "PAID")
                            echo "selected"; ?> value="PAID">PAID</option>
                        <option <?php if ($row->payment_status == "UNPAID")
                            echo "selected"; ?> value="UNPAID">UNPAID</option>
                        <option <?php if ($row->payment_status == "OVERDUE")
                            echo "selected"; ?> value="OVERDUE">OVERDUE
                        </option>
                        <option <?php if ($row->payment_status == "PARTIALLY PAID")
                            echo "selected"; ?>
                            value="PARTIALLY PAID">PARTIALLY PAID</option>
                    </select>
                </div>
                <div class="col-md-2">
                </div>
            </div>

            <div class="row mb-3">
                <label for="inputIsValid" class="col-form-label col-md-6">Narration</label>
                <div class="col-md-6">
                    <input type="text" name="remarks" id="remarks" class="form-control remarks" value="{{ $row->remarks }}">

                </div>
            </div>
        </div>
    </div>
					
					
                        <div class="row mt-2 mb-3">
                            <div class="col-lg-12 col-md-12">
                                <input type="hidden" name="submit_type" class="submit_type" value="" />
                                <div class="form-group text-center actionbtn">
                                    <button name="submit" type="button" class="btn btn-success px-4 me-2 saveform" value="SAVE">Save</button>
                                    <a class='btn btn-danger px-4 me-2' onclick='location.href ="{{ url($pageModule) }}"'>Cancel</a>
                                </div>
                            </div>
                        </div>
					
					
                    </div>
                </div>

               
        
				
   
<!-- Expense Balance Modal -->
<div class="modal fade" id="expenseModal" tabindex="-1" aria-labelledby="expenseModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content shadow-lg border-0">
      
      <!-- Header -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="expenseModalLabel">
          <i class="fa fa-file-invoice-dollar me-2"></i> Expense Balance Details
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Body -->
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-bordered align-middle table-striped">
            <thead class="table-dark">
              <tr>
                <th>Expense Number</th>
                <th>Balance Amount</th>
                <th>Payment Amount</th>
              </tr>
            </thead>
            <tbody>
              <?php $i=1; foreach($expense_balamt as $k=>$v) { ?>  
              <tr>
                <td>{{ $v->expense_no }}</td>
                <td class="checkadv balance_amount balance_amount{{ $i }}" id="{{ $v->balance_amount }}">
                  {{ number_format($v->balance_amount, 2) }}
                </td>
                <td>
                  <input type="text" name="paymentamt[{{ $v->expense_id }}]" class="form-control paymentamt text-end" placeholder="Enter amount">
                </td>
              </tr>
              <?php $i++; } ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-success addpayamt" data-bs-dismiss="modal">
          <i class="fa fa-plus-circle me-2"></i> Add
        </button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fa fa-times-circle me-2"></i> Close
        </button>
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
       var payment_amount=parseFloat($(".payment_amount").val()); 
       var balance_amount=parseInt($('.balance_amount'+index).attr('id'));
      $(".paymentamt").each(function(){
          tot += parseFloat($(this).val());
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
             var payment=parseFloat($(this).val());  
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

              var payment=parseFloat($(this).val());  
       		 var payment_amt=$(".payment_amount").val(); 
              if(payment > payment_amount)
              {
                 showCustomAlert('Payment amount is not more than Statement amount','info');
                  $(this).val('');
              }      
    });

  $(document).on('keyup', '.payment_amount', function () {

    var invoice_amt  = parseFloat($(".invoice_amount").val());
    var payment_str  = $(".payment_amount").val();   // keep original string
    var payment_amt  = parseFloat(payment_str);

    var paid_amt     = parseFloat($(".paid_amount").val());
    var balance_amt  = parseFloat($(".balance_amount").val());

    if (payment_amt > invoice_amt) {
        showCustomAlert("Payment Amount Exceeds", 'error');
        $(".payment_amount").val("");
    } 
    else {
        // 🔹 FIX: do NOT overwrite while user is typing decimal
        if (!isNaN(payment_amt) && payment_str !== "" && payment_str !== "." && !payment_str.endsWith('.') && document.activeElement !== this) {
            $(".payment_amount").val(payment_amt);
        }

        $(".paid_amount").val(paid_amt);
        $(".balance_amount").val(balance_amt);
    }
});

$(document).on('blur', '.payment_amount', function () {

    var val = $(this).val();

    if (val === '' || val === '.') {
        $(this).val('');
        return;
    }

    var num = parseFloat(val);

    if (!isNaN(num)) {
        $(this).val(num);
    }

});

  
          /*Validation*/
$(document).on('input', '.payment_amount', function () {

    let val = this.value;

    val = val.replace(/[^0-9.]/g, '');

    let parts = val.split('.');

    if (parts.length > 2) {
        val = parts[0] + '.' + parts[1];
    }

    if (parts.length === 2) {
        val = parts[0] + '.' + parts[1].substring(0, 2);
    }

    this.value = val;

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
           $('.cheque_no').attr('readonly',false);
              $('.cheque_date').show();      
              }else{
                  $('.chequelabel').html('Reference No');
                  $('.cheque_no').attr('readonly',false);
                    $('.cheque_date').hide();      
              }
            if(pmttype=="CHEQUE" || pmttype=="CASH" || pmttype=="IMPREST" || pmttype=="ONLINE")
	{
		$(".supplier_bank_id").attr('required',false);
		var form = $('#directpayment_form');
                form.parsley().destroy();
	}
	else
	{
		$(".supplier_bank_id").attr('required',true);
		var form = $('#directpayment_form');
                form.parsley().destroy();
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
            
			var url = "{{ url('directpaymentsave') }}";
			var red_url = "{{url('paymentsindex')}}"
			validationrule('directpayment_form');
			var formdata = $('#directpayment_form').serialize();
			var form = $('#directpayment_form');
            form.parsley().validate();
            var form = $('#directpayment_form');
            form.parsley().validate();
            if (form.parsley().isValid())
            {

            var formdata = $('#directpayment_form').serialize();
            $.post(url, formdata, function(data)
            {
            var status = data.status;
            var msg = data.message;
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
