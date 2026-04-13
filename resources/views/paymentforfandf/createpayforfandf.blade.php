@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Payment For Employee F&F</h3>
  @include('layouts.breadcrumb')




            <form method="post" action="" id="directexpense_form" data-parsley-validate>
                {{ csrf_field() }}
			   <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body card-block">
					
					
<div class="row">
    <div class="col-md-4">
        <input class="form-control payment_id" id="payment_id" name="payment_id" size="16" type="hidden"
            value="{{ $row->payment_id }}" readonly>
        <input class="form-control reference_id" id="reference_id" name="reference_id" size="16" type="hidden"
            value="{{ $row->reference_id }}" readonly>
        <input class="form-control payment_number" id="payment_number" name="payment_number" size="16" type="hidden"
            value="{{ $row->payment_number }}" readonly>

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
            <label for="inputIsValid" class="col-form-label col-md-6">Sender Information</label>
            <div class="col-md-6">
                <input type="text" name="sender_information" id="sender_information"
                    class="form-control sender_information" value="{{ $row->sender_information }}">

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
            <div class="col-md-2 showinline">
                <!--<span class="showspan"> <i class="fa fa-refresh jcr_bank_id"></i></span>-->
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
                    <input class="form-control enabledatepicker cheque_date" id="cheque_date" name="cheque_date"
                        size="16" type="text" value="{{ $row->cheque_date }}">

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
                    <option <?php if ($row->payment_source == "FANDFPAYMENT")
                        echo "selected"; ?> value="FANDFPAYMENT">
                        FANDFPAYMENT</option>
                </select>
            </div>
            <div class="col-md-2">
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
            <div class="col-md-6 ">
                <select name='account_code_id' rows='5' class='select2 account_code_id' required>
                    {!! $account_code_id !!}
                </select>
            </div>
            <div class="col-md-2 showinline">
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
					
					
					
					
                    <div class="row mt-4">
				  <div class="col-12 linetable">
				<div id="preview-area" class="table-responsive">
				  <table class="table table-bordered clone_table">
					<thead class="table-light">
                    <tr>
                        <th>Employee Name</th>
                        <th>Payment Amount</th>
                        <th>Employee Bank Name</th>
                        <th>Employee Account Name</th>
                        <th>Employee Account No</th>
                        <th>Employee IFSC Code</th>
                        <th>Account Code</th>
                        <th>Narration</th>
                        
                    </tr>
                </thead>
                <tbody class="clone_lines_body">
                    <?php if(count($linedata)>=1) { ?>
                     @foreach($linedata as $key=>$value)
                    <tr>
                        <td>
                            <input type="hidden" name="bulk_payment_id[]" class="form-control  bulk_payment_id" value="">
							<input class="form-control bulk_reference_id" id="bulk_reference_id" name="bulk_reference_id[]" size="16" type="hidden" value="{{ $value->hr_ff_id }}" readonly>
                            <select name="bulk_employee_id[]" id="bulk_employee_id" class="select2 bulk_employee_id"  readonly>{!! $value->employee_id !!}</select>
                        </td>
                        <td>
                            <input type="text" name="bulk_payment_amount[]" class="form-control  bulk_payment_amount " value="{{$value->net_amt}}" readonly required >
                        </td>
                        <td>
                            <input type="text" name="bulk_supplier_bank_id[]" class="form-control  bulk_supplier_bank_id " value="{{$value->bank_name}}" readonly required>
                        </td> 
                         <td>
                            <input type="text" name="bulk_supplier_account_name[]" class="form-control  bulk_supplier_account_name " value="{{$value->account_holder_name}}" readonly required >
                        </td> 
                         <td>
                            <input type="text" name="bulk_supplier_account_no[]" class="form-control  bulk_supplier_account_no " value="{{$value->account_number}}" readonly required >
                        </td> 
                         <td>
                            <input type="text" name="bulk_supplier_ifsc_code[]" class="form-control  bulk_supplier_ifsc_code " value="{{$value->ifsc_code}}" readonly required >
                        </td> 
                        <td>
                            <select name="bulk_account_code_id[]" id="bulk_account_code_id" class="select2 bulk_account_code_id" required >{!! $account_code_id !!}</select>
                        </td>
                        <td>
                            <input type="text" name="bulk_remarks[]" class="form-control bulk_remarks" value="" >
                        </td>
					  <td class="text-center">
						<button type="button" class="btn btn-sm btn-danger remove-row">
						  <i class="fas fa-minus-circle"></i>
						</button>
					  </td>
                    </tr>
                     @endforeach
                  <?php }  if(count($linedata) < 1 ) { ?>
                    <tr>
                      <td class="pdtdiv sel2">
                            <input type="hidden" name="bulk_payment_id[]" class="form-control input-sm bulk_payment_id" value="">
                        
                            <select name="bulk_employee_id[]" id="bulk_employee_id" class="bulk_employee_id select2 parsley-validated" required>{!! $employee_id !!}</select>
                        </td>
                         <td>
                            <input type="text" name="bulk_payment_amount[]" class="form-control  bulk_payment_amount " value=""  >
                        </td>
                        <td>
                            <select name="bulk_account_code_id[]" id="bulk_account_code_id" class="select2 bulk_account_code_id"  >{!! $account_code_id !!}</select>
                        </td>
                        <td>
                            <input type="text" name="bulk_remark[]" class="form-control  bulk_remark " value="">
                        </td>
					  <td class="text-center">
						<button type="button" class="btn btn-sm btn-danger remove-row">
						  <i class="fas fa-minus-circle"></i>
						</button>
					  </td>
                    </tr>
                    <?php } ?>
                    
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
					
					
                        <div class="row mt-4 mb-3">
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


            </form>

@endsection
@push('scripts')


<script>

	$(document).ready(function(){
		
	 $('select,.accountcode_div').css('pointer-events', 'none');
  
  
  var data ="{{\Session::get('j_date_format')}}";


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
      showCustomAlert("Payment Amount Exceeds","error");
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
           $('.cheque_no').attr('readonly',false);
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
            
			var url = "{{ url('fandfpaysave') }}";
			var red_url = "{{url('paymentforfandf')}}"
			validationrule('directexpense_form');
			var formdata = $('#directexpense_form').serialize();
			var form = $('#directexpense_form');
            form.parsley().validate();
            var form = $('#directexpense_form');
            form.parsley().validate();
            if (form.parsley().isValid())
            {

            var formdata = $('#directexpense_form').serialize();
            $.post(url, formdata, function(data)
            {
            var status = data.status;
            var msg = data.message;
            var id = data.id;
            var edit_url = "{{ url('paymentforemployeebonuscreate') }}/" + id;
            if (btnval != 'SAVE' && btnval != 'DRAFT')
            {
            showCustomAlert(msg,status);
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
