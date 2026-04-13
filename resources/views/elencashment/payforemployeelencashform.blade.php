@extends('layouts.header')
@section('content')
<style type="text/css">
@media only screen and (min-width: 1500px) {
.bulk_employee_id{width:250px !important;}
.bulk_payment_amount{width:150px !important;}
.bulk_supplier_bank_id{width:150px !important;}
.bulk_supplier_account_name{width:150px !important;}
.bulk_supplier_account_no{width:150px !important;}
.bulk_supplier_ifsc_code{width:150px !important;}
.bulk_account_code_id{width:350px !important;}
.bulk_remarks{width:120px;}
}

.bulk_employee_id{width:150px;}
.bulk_payment_amount{width:190px;}
.bulk_supplier_bank_id{width:150px !important;}
.bulk_supplier_account_name{width:150px !important;}
.bulk_supplier_account_no{width:150px !important;}
.bulk_supplier_ifsc_code{width:150px !important;}
.bulk_account_code_id{width:120px;}
.bulk_remarks{width:120px;}

 #myModal table tbody>tr{
  font-family: unset !important;
 }
   #myModal input{
    border: 1px solid #07224f;
   }
  @media screen and (min-width: 1350px) {
    .table-bordered>tbody>tr>td,.table-bordered{
    border-radius: 0;
    border: 2px solid #05234e !important;
   }
  }
   .table-bordered>tbody>tr>td,.table-bordered{
    border-radius: 0;
    border: 1px solid #05234e;
   }
   .table-bordered>tbody>tr>th{
    border: none;
   }
</style>

<?php // include('tools_menu.php');?> 
 <h4 class="heads">Payment For EL ENCASHMENT<span class="ui_close_btn"><a class="collapse-close pull-right btn-danger" onclick="location.href = '{{url('paymentforelencashment')}}'"></a></span>
 </h4>

    <span class="ui_close_btn"></span>
            <form method="post" action="" id="directexpense_form" data-parsley-validate>
                {{ csrf_field() }}
      <div class="card">
                <div class="card-body card-block">
                    <div class="row">
                            <div class="col-md-4">
                                <input class="form-control payment_id" id="payment_id" name="payment_id" size="16" type="hidden" value="{{ $row->payment_id }}" readonly>
                               <input class="form-control reference_id" id="reference_id" name="reference_id" size="16" type="hidden" value="{{ $row->reference_id }}" readonly>
                                <input class="form-control payment_number" id="payment_number" name="payment_number" size="16" type="hidden" value="{{ $row->payment_number }}" readonly>
                     
                                <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Payment Date</label>
                                    <div class="col-md-5">
                                        <div class="input-group form_date col-md-8" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                            <input class="form-control datepicker payment_date" id="payment_date" name="payment_date" size="16" type="text" value="{{ $row->payment_date }}" >
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-2 showline">
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
                              <option <?php if($row->payment_type_id =="IMPS") { echo "selected"; } else { echo ""; } ?> value="IMPS">IMPS</option>
                              <option <?php if($row->payment_type_id =="RTGS") { echo "selected"; } else { echo ""; } ?> value="RTGS">RTGS</option>
							<option <?php if($row->payment_type_id =="ONLINE") { echo "selected"; } else { echo ""; } ?> value="ONLINE">ONLINE</option>
						     <option <?php if($row->payment_type_id =="IMPREST") { echo "selected"; } else { echo ""; } ?> value="IMPREST">IMPREST</option>
                              </select>
                      </div>
                      <div class="col-md-2">
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
                    <label for="inputIsValid" class="form-control-label col-md-5">UTR Number</label>
                    <div class="col-md-5">
                        <input type="text" id="payment_reference" name="payment_reference" class="form-control payment_reference" value="{{ $row->payment_reference }}" />
                    </div>
                    <div class="col-md-2">
                    </div>
                </div> 
                       
       </div>

                <div class="col-md-4">
                                    
                      
                        <div class="form-group row chequediv">
                                <label for="inputIsValid" class="form-control-label col-md-5"><span class="bankdiv" style="color:red">*</span>Bank Name</label>
                                <div class="col-md-5 sel2">
                                    <select name='bank_id' rows='5' id='bank_id' class='select2 bank_id' required>
                                        {!! $bank_id  !!}
                                    </select>
                                </div>
                                <div class="col-md-2 showinline">
                                    <!--<span class="showspan"> <i class="fa fa-refresh jcr_bank_id"></i></span>-->
                                </div>
                        </div>
                        <div class="form-group row chequediv" >
                                <label for="inputIsValid" class="form-control-label col-md-5">Account Number</label>
                                <div class="col-md-5 supplier_div">
                                    <select name='account_no' rows='5' id='account_no' class='select2 account_no'>
                                       
                                    </select>
                                </div>
                                <div class="col-md-2 showinline">
                                    </div>
                        </div>
                     <div class="form-group row chequediv">
                                <label for="inputIsValid" class="form-control-label col-md-5 chequelabel" >Cheque No</label>
                                <div class="col-md-5 supplier_div">
                                <input type="number" id="cheque_no" name="cheque_no" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="form-control cheque_no" value="{{ $row->cheque_no }}" readonly />
                                </div>
                                <div class="col-md-2 showinline">
                                    </div>
                            </div>
                            <div class="form-group row cheque_date">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Cheque Date</label>
                                    <div class="col-md-5">
                                        <div class="input-group form_date col-md-8" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                            <input class="form-control enabledatepicker cheque_date" id="cheque_date" name="cheque_date" size="16" type="text" value="{{ $row->cheque_date }}" >
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-2 showline">
                                    </div>
                                </div>
                    </div>
                           
                      
            <div class="col-md-4">
                <div class="form-group row">
                         <label for="inputIsValid" class="form-control-label col-md-5">Payment Source</label>
                         <div class="col-md-5">
                             <select type="text" name="payment_source" id="payment_source" class="form-control  payment_source" readonly >
                             <option <?php if($row->payment_source=="ELENCASHMENT" ) echo "selected"; ?> value="ELENCASHMENT">ELENCASHMENT</option>
                             </select>
                         </div>
                         <div class="col-md-2">
                         </div>
                     </div>
               
                          <div class="form-group row" style="display:none;">
                         <label for="inputIsValid" class="form-control-label col-md-5">Payment Status</label>
                         <div class="col-md-5">
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
					 <div class="form-group row imprestemp_div">
                          <label for="inputIsValid" class="form-control-label col-md-5">Employee Name</label>
                          <div class="col-md-5">
                              <select name='imprest_employee_id' rows='5' class='select2 imprest_employee_id' >
                                  {!! $imprest_employee_id !!}
                              </select>
                          </div>
                          <div class="col-md-2 showinline">
                          </div>
                        </div>
                            <div class="form-group row">
                          <label for="inputIsValid" class="form-control-label col-md-5"><span style="color:red;">*</span>Account Code</label>
                          <div class="col-md-5 ">
                              <select name='account_code_id' rows='5' class='select2 account_code_id' required>
                                  {!! $account_code_id !!}
                              </select>
                          </div>
                          <div class="col-md-2 showinline">
                          </div>
                </div>
                           <div class="form-group row">
                                     <label for="inputIsValid" class="form-control-label col-md-5">Narration</label>
                                     <div class="col-md-5">
                                         <input type="text" name="remarks"  id="remarks"  class="form-control remarks" value="{{ $row->remarks }}" >

                                     </div>
                                     <div class="col-md-2">
                                     </div>
                                 </div>  
                                
                      </div>



                        </div>
                    <div class="row">
    <div class="col-md-12">
        <a href="javascript:void(0);"  class="add_row additem"  rel=".rcopy"><i class="fa fa-plus"></i> New Item</a>
<div id="preview-area" class="chandru">
    <table class="overflow-y preview payment_table">
                <thead>
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
                <tbody class="pmt_lines_body">
                    <?php if(count($linedata)>=1) { ?>
                     @foreach($linedata as $key=>$value)
                    <tr class="rcopy clone">
                        <td>
                            <input type="hidden" name="bulk_payment_id[]" class="form-control  bulk_payment_id" value="">
							<input class="form-control bulk_reference_id" id="bulk_reference_id" name="bulk_reference_id[]" size="16" type="hidden" value="{{ $value->id }}" readonly>
                        </td>
                        <td>
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
                        <td>
                            <a class="remove remove0">
                                <i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i>
                            </a>
                            <input type="hidden" name="counter[]">
                        </td>
                    </tr>
                     @endforeach
                  <?php }  if(count($linedata) < 1 ) { ?>
                    <tr class="rcopy clone">
                        <td>
                            <input type="hidden" name="bulk_payment_id[]" class="form-control input-sm bulk_payment_id" value="">
                        </td>
                        <td class="pdtdiv sel2">
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
                        <td>
                            <a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
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
                            <div class="col-lg-12 col-md-12">
                                <input type="hidden" name="submit_type" class="submit_type" value="" />
                                <div class="form-group text-center actionbtn">
                                    <!--<button type="button" class="btn draft saveform" value="DRAFT">Draft</button>-->
                                    <button name="submit" type="button" class="btn save saveform" value="SAVE">Save</button>
                                    <a class='btn cancel' onclick='location.href ="{{ url($pageModule) }}"'>Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                    <input type="hidden" class="pdtindex" value="" />
                    <div id="preloader">
                        <img src="https://jrlma.ca/wp-content/plugins/gallery-by-supsystic/src/GridGallery/Galleries/assets/img/loading.gif">
                    </div>
            </form>
<script>

$(document).ready(function(){
  $('select,.accountcode_div').css('pointer-events', 'none');
  
  
  var data ="{{\Session::get('j_date_format')}}";

$(".add_row").on('click',function(){
        var form = $('#directexpense_form');
        form.parsley().destroy();
          });
	$(".add_row").relCopy(data);
        changeclassfields();
   
   $('.add_row').click(function(){
            var rowCount = $('.payment_table tbody tr').length;
       	    var index = rowCount - 1;
            changeclassfields();
    });

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
                 notyMsg('info','Payment amount is not Equal to Statement amount');
                  $('.paymentamt').val('');
              } 
     
             
        <?php } ?>
     
  });
    
    
    
    $(document).on('keyup','.paymentamt',function(){
              var index = $(this).closest('tr').index();
             var payment=parseInt($(this).val());  
             // alert(payment);
              var balance_amount=parseInt($('.balance_amount'+index).attr('id'));
               var payment_amount=$(".payment_amount").val(); 

 if(payment > balance_amount)
              {
                 notyMsg('info','Payment amount is not more than balance amount');
                  $(this).val('');
              }
         
    });
    /* end */
    
    
     $(document).on('keyup','.paymentamt',function(){
     
             // var index = $(this).closest('tr').index();
              var payment=parseInt($(this).val());  
              //alert(payment);
              //var payment_amount=parseInt($('.payment_amount'+index).attr('id'));
        var payment_amt=$(".payment_amount").val(); 
       // alert(payment_amt);
              if(payment > payment_amount)
              {
                 notyMsg('info','Payment amount is not more than Statement amount');
                  $(this).val('');
              }      
    });

  $(document).on('keyup','.payment_amount',function(){
      var invoice_amt=parseInt($(".invoice_amount").val());
      var payment_amt=parseInt($(".payment_amount").val()); 
    var paid_amt=parseInt($(".paid_amount").val()); 
    var balance_amt=parseInt($(".balance_amount").val()); 
    if(payment_amt > invoice_amt){
      notyMsg("error","Payment Amount Exceeds");
      $(".payment_amount").val("");
    }
    else{
      var num1 = isNaN(parseInt(payment_amt)) ? 0 : parseInt(payment_amt);
      $(".payment_amount").val(num1);
        $(".paid_amount").val(paid_amt);
          $(".balance_amount").val(balance_amt);
    }
  });
 
  
        $(".jcr_account_code_id").click(function(){
        $(".account_code_id").jCombo("{{ URL::to('jcomboform?table=f_account_structure_t:f_account_structure_id:concatenated_segments') }}",
        {selected_value:""});
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
     $('#bank_id').on('change',function(){
         var bank = $('#bank_id').val();
         var pdt_condition ='bank_account_hdr_id='+bank;
        $(".account_no").jCombo("{{ URL::to('jcomboform?table=f_bank_account_lines_t:bank_account_line_id:account_number') }}&order_by=account_number asc"+'&parent='+pdt_condition,
           {selected_value:""});
           
    });

    $(document).on('click','.jcr_bank_id',function(){
        $(".bank_id").jCombo("{{ URL::to(' ?table=f_bank_account_hdr_t:bank_account_hdr_id:bank_name') }}&order_by=bank_name asc &parent=bank_source='Company Account'",
        {selected_value:""});
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
/*Karthigaa Purpose for Bank based Account Code load*/
$(document).on('change','.account_no',function(){
//    var account_no=$('.account_no').val();
    var account_no=$('.bank_id').val();
    var url="{{URL::to('getaccountdetails')}}/"+account_no;
    $.get(url,function(data){
//        console.log(data);
    $('.account_code_id').val(data[0].account_code_id).change();  
    
        });
});
/*End*/

/*Karthigaa Purpose for Default Cheque Number*/
// $(document).on('change','.account_no',function(){
//      var pmttypeid=$('.payment_type_id option:selected').text();
//     var pmttype=$.trim(pmttypeid);
//     if(pmttype=="CHEQUE"){
//     var accid=$('.account_no').val();
//     var cheque_no=$('.cheque_no').val();
//     $('.mcontent2').html('');
//     if(accid!=""){
//     var url ="{{URL::to('getchequeno')}}/"+accid;
//     $.get(url,function(data){
// 		var end=$.trim(data['endcheque']);
// 		var chequeno =$.trim(data['chequeno']);
// 		if($.trim(data['nocheque'])=="nocheque"){
// 			notyMsgs('error','Please Assign Cheque for this Account');
//                     }
//       else if(chequeno > end ){
//                  notyMsg('error','No Cheque Found');
//           }
//           else{
//               $(".cheque_no").val(data['chequeno']);
//           }
//     });
//     }
//     else{
//              $(".cheque_no").val('');
//     }
//     }
//     });
/*End*/
$(document).on('click','.remove',function()
{
	var index = $(this).closest('tr').index();
	var rowCount = $('.payment_table tbody tr').length;
	if(rowCount > 1)
	{
		$($(this).closest("tr")).remove();
		removeclassfields();
	}
	else
	{
		notyMsg('info',"You Can't Delete Atleast One row should be there");
	}
});

	$( ".enabledatepicker" ).datepicker({
      changeMonth: true,
      dateFormat: data,
      changeYear: true,	 
      maxDate: null,
      onClose: function () {
        $(this).parsley().validate();
        }

    }).attr('readonly', 'readonly');

 /*Karthigaa Purpose For Save Function*/      
      
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
            
    var url = "{{ url('elpaysave')}}";
    var red_url = "{{url('paymentforelencashment')}}"
    validationrule('directexpense_form');
    var formdata = $('#directexpense_form').serialize();
    var form = $('#directexpense_form');
            form.parsley().validate();
            var form = $('#directexpense_form');
            form.parsley().validate();
            if (form.parsley().isValid())
            {
                   change_date();
                    var formdata = $('#directexpense_form').serialize();
            $.post(url, formdata, function(data)
            {
            var status = data.status;
            var msg = '<span style="color:#090065"></span>' + data.message;
            var id = data.id;
            var edit_url = "{{ url('paymentforelencashcreate') }}/" + id;
            if (btnval != 'SAVE' && btnval != 'DRAFT')
            {
            notyMsg(status, msg);

            }
            else
            {
            notyMsg(status, msg);
    setTimeout(function(){
        $('.ajaxLoading').hide();
                    window.location.href=red_url;
    }, 1500);
            }
            });
            }
    });
    });
    function changeclassfields(){
changeClassName('bulk_payment_id');
changeClassName('bulk_employee_id');
changeClassName('bulk_payment_amount');
changeClassName('bulk_supplier_bank_id');
changeClassName('bulk_supplier_account_name');
changeClassName('bulk_supplier_account_no');
changeClassName('bulk_supplier_ifsc_code');
changeClassName('bulk_account_code_id');
changeClassName('bulk_remarks');
changeClassName('bulk_reference_id');
}
function removeclassfields(){
removeClass('bulk_payment_id');
removeClass('bulk_employee_id');
removeClass('bulk_payment_amount');
removeClass('bulk_supplier_bank_id');
removeClass('bulk_supplier_account_name');
removeClass('bulk_supplier_account_no');
removeClass('bulk_supplier_ifsc_code');
removeClass('bulk_account_code_id');
removeClass('bulk_remarks');
removeClass('bulk_reference_id');
}
/************ Karthigaa purpose to remove row action ********************/
function removeClass(className)
{
	var rowCount = $('.payment_table tbody tr').length;
	for(var i=0;i<=rowCount;i++)
	{
	$('.payment_table tbody tr').find('.'+className).removeClass(className+i);
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

    </script>
    
     <script>
var dateToday = new Date();
  var data ="{{\Session::get('j_date_format')}}";
    $( ".payment_date" ).datepicker({
      changeMonth: true,
      dateFormat: data,
      changeYear: true,
      minDate: "01-04-2024",
			maxDate: 0,
      //maxDate: null,
      onClose: function () {
        $(this).parsley().validate();
        }

    }).attr('readonly', 'readonly');
</script>      

<script>
var dateToday = new Date();
  var data ="{{\Session::get('j_date_format')}}";
    $( "#cheque_date" ).datepicker({
      changeMonth: true,
      dateFormat: data,
      changeYear: true,
      minDate: -90,
			maxDate: +30,
      //maxDate: null,
      onClose: function () {
        $(this).parsley().validate();
        }

    }).attr('readonly', 'readonly');
</script>  
    
<script>
    $(window).on('load', function(){
    //preloader
    var preLoder = $("#preloader");
    preLoder.fadeOut(500);
    var backtoTop = $('.back-to-top')
            backtoTop.fadeOut(100);
    });
    
         // cheque number validation
    
     $(document).ready(function(){
        $('#cheque_no').on('input', function() {
            var chequeNo = $(this).val();
            if (chequeNo.length > 6) {
               notyMsg('info','Please Enter valid cheque number');
                $(this).val(chequeNo.slice(0, 6));
            }
        });

        $('#cheque_no').on('blur', function() {
            var chequeNo = $(this).val();
            if (chequeNo.length < 6 && chequeNo.length > 0) {
                notyMsg('info','Please Enter valid cheque number');
                $(this).focus(); 
            }
        });
    });
</script>

@include('layouts.php_js_validation')
@endsection
