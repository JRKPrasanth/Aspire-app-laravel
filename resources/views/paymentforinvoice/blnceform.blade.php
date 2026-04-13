@extends('layouts.header')
@section('content')

<style type="text/css">
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

<?php include('tools_menu.php');?> 
 <h4 class="heads">Payment For Invoice Balance<span class="ui_close_btn"><a class="collapse-close pull-right btn-danger" onclick="location.href = '{{url('paymentforinvoiceblnce')}}'"></a></span>
 </h4>




    <span class="ui_close_btn"></span>
            <form method="post" action="" id="paymentinv_form" data-parsley-validate>
                {{ csrf_field() }}

                
      <div class="card">

                <div class="card-body card-block">
                    <div class="row">
                            <div class="col-md-4">
                               <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5"><span style="color:red">*</span>Invoice No</label>
                                    <div class="col-md-5 sel2">
      <input type="text" class="form-control bill_number" id="bill_number" size="16" value="{{ $bill_number }}" readonly>
       <input class="form-control payment_id" id="payment_id" name="payment_id" size="16" type="hidden" value="{{ $row->payment_id }}" readonly>
                                <input class="form-control payment_number" id="payment_number" name="payment_number" size="16" type="hidden" value="{{ $row->payment_number }}" readonly>
                               
      <input type="text" class="form-control so_invoice_id" id="so_invoice_id" name="so_invoice_id" size="16" value="{{ $so_invoice_id }}" hidden="true" style="display:none;" readonly>

                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                </div>
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
                                <div class="form-group row">
                            <label for="inputIsValid" class="form-control-label col-md-5">Invoice Amount</label>
                            <div class="col-md-5">
                                <input type="text" id="invoice_amount" name="invoice_amount" class="form-control invoice_amount chckclick" value="{{ $invoice_amt }}" readonly>
                            </div>
                            <div class="col-md-2 showline">
                            </div>
                        </div>
           
                                <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Customer Name</label>
                                    <div class="col-md-5">
                                        <select name='customer_id' rows='5' class='form-control ship_to_customer_id' readonly >
                                            {!! $ship_to_customer_id !!}
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Customer Bank Name</label>
                                    <div class="col-md-5">
                                        <input type="text" id="customer_bank_id" name="customer_bank_id" class="form-control customer_bank_id" value="{{ $row->customer_bank_id}}" required readonly >
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                </div>
                    <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Customer Account Name</label>
                                    <div class="col-md-5">
                                        <input type="text" id="customer_account_name" name="customer_account_name" class="form-control customer_account_name" value="{{ $row->customer_account_name}} " readonly>
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                    </div>
                    <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Customer Account No</label>
                                    <div class="col-md-5">
                                        <input type="text" id="customer_account_no" name="customer_account_no" class="form-control customer_account_no" value="{{ $row->customer_account_no}}" readonly>
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                    </div>
                    <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Customer IFSC Code</label>
                                    <div class="col-md-5">
                                       <input type="text" id="customer_ifsc_code" name="customer_ifsc_code" class="form-control customer_ifsc_code" value="{{ $row->customer_ifsc_code}}" readonly>
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                </div>
                <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Customer Favouring Name</label>
                                    <div class="col-md-5">
                                        <input type="text" id="favouring_name" name="favouring_name" class="form-control favouring_name" value="{{ $row->favouring_name}}" >
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                    </div>
                                
                                
                               
                       
       </div>




                <div class="col-md-4">
                     <div class="form-group row" >
                      <label for="inputIsValid" class="form-control-label col-md-5"><span class="req" style="color:red">*</span>Payment Type</label>
                      <div class="col-md-5 sel2">
                          <select name='payment_type_id' rows='5' class='form-control payment_type_id select2' data-show-subtext="true" data-live-search="true" >
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
                            <label for="inputIsValid" class="form-control-label col-md-5"><span style="color:red">*</span>Payment Amount</label>
                            <div class="col-md-5">
                                <input type="text" id="payment_amount" name="payment_amount" class="form-control payment_amount chckclick" value="{{ $row->payment_amount }}" required readonly >
                            </div>
                            <div class="col-md-2 showinline">
                                <i class="fa fa-plus" aria-hidden="true" data-toggle="modal" data-target="#myModal" style="color: #142e78;
    font-size: 13px;
    padding: 5px;
    border: 1px solid;
    cursor: pointer;"></i>   
                            </div>
                        </div>
						   
			
                         <div class="form-group row bank_date">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Bank Date</label>
                                    <div class="col-md-5">
                                        <div class="input-group form_date col-md-8" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                            <input class="form-control datepicker bank_date" id="bank_date" name="bank_date" size="16" type="text" value="{{ $row->bank_date }}" >
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-2 showline">
                                    </div>
                                </div>
                        <div class="form-group row">
                            <label for="inputIsValid" class="form-control-label col-md-5">Balance Amount</label>
                            <div class="col-md-5">
                                <input type="text" id="balance_amount" name="balance_amount" class="form-control balance_amount chckclick" value="{{ $invoice_amt }}" readonly>
                            </div>
                            <div class="col-md-2 showline">
                            </div>
                        </div>
                       <div class="form-group row chequediv">
                                <label for="inputIsValid" class="form-control-label col-md-5"><span class="bankdiv req" style="color:red">*</span>Bank Name</label>
                                <div class="col-md-5 sel2">
                                    <select name='bank_id' rows='5' id='bank_id' class='select2 bank_id' >
                                        {!! $bank_id  !!}
                                    </select>
                                </div>
                                <div class="col-md-2 showinline">
                                    <span class="showspan"> <i class="fa fa-refresh jcr_bank_id"></i></span>
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
                    </div>
                           
                      
            <div class="col-md-4">
                <div class="form-group row">
                         <label for="inputIsValid" class="form-control-label col-md-5">Payment Source</label>
                         <div class="col-md-5">
                             <select type="text" name="payment_source" id="payment_source" class="form-control  payment_source" readonly >
                             <option value="">-- Please Select --</option>
                             <option <?php if($row->payment_source=="SALESINVOICE" ) echo "selected"; ?> value="SALESINVOICE">SALESINVOICE</option>
                             
                             </select>
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
                          <label for="inputIsValid" class="form-control-label col-md-5"><span class="req" style="color:red;">*</span>Account Code</label>
                          <div class="col-md-5 ">
                              <select name='account_code_id' rows='5' class='select2 account_code_id'>
                                  {!! $account_code_id !!}
                              </select>
                          </div>
                          <div class="col-md-2 showinline">
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
            <div class="form-group row">
                                     <label for="inputIsValid" class="form-control-label col-md-5">Sender Information</label>
                                     <div class="col-md-5">
                                         <input type="text" name="sender_information"  id="sender_information"  class="form-control sender_information" value="{{ $row->sender_information }}" >

                                     </div>
                                     <div class="col-md-2">
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
                                 <div class="form-group row chequediv">
                                <label for="inputIsValid" class="form-control-label col-md-5 chequelabel" >Cheque No</label>
                                <div class="col-md-5 supplier_div">
                                    <input type="text" id="cheque_no" name="cheque_no" class="form-control cheque_no" value="{{ $row->cheque_no }}" readonly />
                                </div>
                                <div class="col-md-2 showinline">
                                    </div>
                            </div>
                <div class="form-group row cheque_date">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Cheque Date</label>
                                    <div class="col-md-5">
                                        <div class="input-group form_date col-md-8" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                            <input class="form-control datepicker cheque_date" id="cheque_date" name="cheque_date" size="16" type="text" value="{{ $row->cheque_date }}" >
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-2 showline">
                                    </div>
                                </div>
                      </div>



                        </div>
                    
                                        <!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Invoice Balance Details</h4>
      </div>
      <div class="modal-body">
         
           <table class="table table-bordered">   

              <tr style="background:#05234e;color:#fff;font-family: fantasy;
    font-size: 14px;">
                <th>Invoice(Bill) Number</th>
                <th>Balance Amount</th>
                <th>Payment Amount</th>
              </tr>
        
               <?php  $i=1;
               foreach($invoice_balamt as $k=>$v) { ?>  
                    <tr>
                  <td>{{$v->invoice_number}}</td>
						<?php $balamt= str_replace("-","",$v->balance_amount); ?>
                  <td class="checkadv balance_amount balance_amount{{$i}}" id="{{$v->balance_amount}}">{{$balamt}}</td>
                  <td><input type="text" name="paymentamt[{{$v->invoice_hdr_id}}]" value ="{{$balamt}}" class="paymentamt"></td>
                 
              </tr>
          <?php $i++; } ?>
          
          </table>
           
      </div>
      <div class="modal-footer">
         <button type="button" class="btn btn-default addpayamt " data-dismiss="modal">Add</button>
        <button type="button" class="btn btn-default close1 " data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<!--end-->
<!--DEEPIKA PURPOSE:advance amount details based on po-->         
<div id="advanceModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Po Balance Details</h4>
      </div>
      <div class="modal-body">
     <?php if($poamt==0){ ?>    
           <table class="table table-bordered">   
 
              <tr style="background:#05234e;color:#fff;font-family: fantasy;
    font-size: 14px;">
                <th>Po Number</th>
                <th>Advance Amount</th>
                <th>Payment Amount</th>
              </tr>
        
               <?php  $i=1;
        
               foreach($po_balamt as $k=>$v) { ?>  
                    <tr>
                  <td>{{$v->po_number}}
         
                  </td>

                  <td class="checkadv balance_amount pobalance_amount{{$i}}" id="{{$v->balance_amount}}">{{$v->balance_amount}}</td>
                  <td><input type="text" name="popaymentamt[{{$v->po_hdr_id}}]" class="popaymentamt" value="{{$v->balance_amount}}"></td>
                 
              </tr>
          <?php $i++; } ?>
       
          </table>
            <?php }else{ ?>
         <tr>
           <td colspan="3">There is no advance amount for this po</td>
         </tr>
          <?php } ?>
      </div>
    <?php if($poamt==0){ ?>
      <div class="modal-footer">
         <button type="button" class="btn btn-default addadvancepayamt" data-dismiss="modal">Add</button>
        <button type="button" class="btn btn-default close1 " data-dismiss="modal">Close</button>
      </div>
    <?php } ?>
    </div>

  </div>
</div>
<!--end-->
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
     <!--DEEPIKA PURPOSE:credit,debit details based on customer-->        
<div id="mycreditModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Invoice Credit Details</h4>
      </div>
      <div class="modal-body">
         
           <table class="table">   
             <tr>
                <th>Invoice Number</th>
                <th>Credit Amount</th>
                <th>Amount</th>
               </tr>
         
               <?php $i=1; if(count($credit_balamt)>0){
               foreach($credit_balamt as $k=>$v) { ?>  
                    <tr>
                  <td>{{$v->bill_number}}</td>
                  <td class="credit_note_balance credit_note_balance{{$i}}" id="{{$v->credit_note_balance}}" >{{$v->credit_note_balance}}</td>
						 <td><input type="text"  name="creditamount[{{$v->po_invoice_id}}]" class="creditamt" value="{{$v->credit_note_balance}}">
						 <input type='hidden' name="debittype[{{$v->po_invoice_id}}]" value="{{$v->type}}">
						 </td>
                </tr>
					<?php $i++;  } }else{?>
			   <tr><td></td><td>There is No Credit Amount Details for this Supplier</td></tr>
          <?php } ?>
          </table>
            
      </div>
      <div class="modal-footer">
         <button type="button" class="btn btn-default add addcreditamt" data-dismiss="modal">Add</button>
        <button type="button" class="btn btn-default close1" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
				<div id="mydebitModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Invoice Debit Details</h4>
      </div>
      <div class="modal-body">
         
           <table class="table">   

              <tr>
                <th>Invoice Number</th>
                <th>Debit Amount</th>
                <th>Amount</th>
              </tr>
         
               <?php $i=1; if(count($credit_balamt)>0){
               foreach($debit_balamt as $k=>$v) { ?>  
                    <tr>
                  <td>{{$v->bill_number}}</td>
                  <td class="debit_note_balance debit_note_balance{{$i}}" id="{{$v->debit_note_balance}}" >{{$v->debit_note_balance}}</td>
						 <td><input type="text" name="debitamount[{{$v->po_invoice_id}}]" class="debitamt" value="{{$v->debit_note_balance}}">
						 <input type='hidden' name="debittype[{{$v->po_invoice_id}}]" value="{{$v->type}}">
						 </td>
                </tr>
					<?php $i++; }  }else{?>
			   <tr><td></td><td>There is No Debit Amount Details for this Supplier</td></tr>
          <?php } ?>
          </table>
            
      </div>
      <div class="modal-footer">
         <button type="button" class="btn btn-default add adddebitamt" data-dismiss="modal">Add</button>
        <button type="button" class="btn btn-default close1" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<!--end-->                     
               
            </form>
            
     

<script>

$(document).ready(function(){
  $('select,.accountcode_div').css('pointer-events', 'none');

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
var decimal = "<?php echo \Session('decimal'); ?>";
 $('.account_code_id,.payment_type_id,.bank_id').attr('required',true);  
 $('.req').show();
$(document).on('click','.addpayamt',function(){
       var tot = 0;
       var index = $(this).closest('tr').index();
       var payment_amount=parseInt($(".payment_amount").val()); 
     var advance_amt=parseInt($(".advance_amount").val()); 
       var balance_amount=parseInt($('.balance_amount'+index).attr('id'));
       var balamount=  $('.invoice_amount').val();
      $(".paymentamt").each(function(){
          tot += parseFloat($(this).val());
      });


         $('.payment_amount').val(tot.toFixed(decimal));
           var balamt=Math.round(balamount-tot).toFixed(2);
         if(balamt==0.00){
         $('.account_code_id').attr('required',false);  
         $('.payment_type_id,.bank_id').attr('required',false);  
         $('.req').hide();
         }
            $('.balance_amount').val(balamt);
        
     
  });
  /*deepika purpose:add credit,debit amount*/ 
	
	$(document).on('click','.addcreditamt',function(){
       var tot = 0;
       var cn = 0;
       var index = $(this).closest('tr').index();console.log(index);
      $(".creditamt").each(function(i,v){
  	  tot += parseFloat($(this).val());
	    });
	 console.log(tot);
         var balance_amount= parseFloat($('.balance_amount').val());
	  
        var adbalamt=parseFloat(balance_amount)+parseFloat(tot);
		
        $('.balance_amount').val(adbalamt);
        $('.credit_amount').val(tot);
     

  }); 
	$(document).on('click','.adddebitamt',function(){
       var tot = 0;
		 var cn = 0;
       var index = $(this).closest('tr').index();
      $(".debitamt").each(function(i,v){
        tot += parseFloat($(this).val());
	   });
	 console.log(tot);
         var balance_amount=  parseFloat($('.balance_amount').val());
	
        var adbalamt=parseFloat(balance_amount)-parseFloat(tot);
	
        $('.balance_amount').val(adbalamt);
        $('.debit_amount').val(tot);
     

  }); 
/*end*/  
  /*deepika purpose:add advance amount*/  
 $(document).on('click','.addadvancepayamt',function(){
       var tot = 0;
       var index = $(this).closest('tr').index();
      $(".popaymentamt").each(function(){
          tot += parseFloat($(this).val());
      });
         var balance_amount=  $('.balance_amount').val();
        var adbalamt=balance_amount-tot;
        $('.balance_amount').val(adbalamt);
        $('.advance_amount').val(tot.toFixed(decimal));

  });   
/*end*/
    $(document).on('keyup','.paymentamt',function(){
              var index = $(this).closest('tr').index();
             var payment=parseInt($(this).val());  
             // alert(payment);
            // var advance_amount=$(".advance_amount").val();
              var balance_amount=parseInt($('.balance_amount'+index).attr('id'));
               var payment_amount=$(".payment_amount").val(); 

if(balance_amount > 0){
 if(payment > balance_amount)
              {
                 notyMsg('info','Payment amount is not more than balance amount');
                  $(this).val('');
              }
} else{
  if(payment < balance_amount)
              {
                 notyMsg('info','Payment amount is not more than balance amount');
                  $(this).val('');
              }
}    
    });
    /* end */
 /*deepika purpose:validation for pobalance amount   */
  $(document).on('keyup','.popaymentamt',function(){
              var index = $(this).closest('tr').index();
             var payment=parseInt($(this).val());  
              var balance_amount=parseInt($('.pobalance_amount'+index).attr('id'));
             
if(balance_amount > 0){
 if(payment > balance_amount)
              {
                 notyMsg('info','Payment amount is not more than balance amount');
                  $(this).val('');
              }
} else{
  if(payment < balance_amount)
              {
                 notyMsg('info','Payment amount is not more than balance amount');
                  $(this).val('');
              }
}    
    });
    /* end */  
  /*deepika purpose:qty validation*/
    $(document).on('keypress','.popaymentamt,.paymentamt', function(ev){
      var regex = new RegExp("^[0-9.]+$");
          var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
          if (regex.test(str)) {
            return true;
          }
          ev.preventDefault();
          return false;
    });
  /*end*/
  
     $(document).on('keyup','.paymentamt',function(){
     
             // var index = $(this).closest('tr').index();
              var payment=parseInt($(this).val());  
              //alert(payment);
       //   var payment_amount=parseInt($('.payment_amount'+index).attr('id'));
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
      var advance_amt=parseInt($(".advance_amount").val()); 
    var paid_amt=parseInt($(".paid_amount").val()); 
    if(payment_amt > invoice_amt){
      notyMsg("error","Payment Amount Exceeds");
      $(".payment_amount").val("");
    }
    else{
      var num1 = isNaN(parseInt(payment_amt)) ? 0 : parseInt(payment_amt);
      $(".payment_amount").val(num1.toFixed(decimal));
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
        $(".bank_id").jCombo("{{ URL::to('jcomboform?table=f_bank_account_hdr_t:bank_account_hdr_id:bank_name') }}&order_by=bank_name asc",
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
          if(pmttype=="CHEQUE" || pmttype=="CASH"|| pmttype=="IMPREST" || pmttype=="ONLINE")
  {
    $(".supplier_bank_id").attr('required',false);
    var form = $('#paymentinv_form');
                form.parsley().destroy();
  }
  else
  {
    $(".supplier_bank_id").attr('required',true);
    var form = $('#paymentinv_form');
                form.parsley().destroy();
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

// /*Karthigaa Purpose for Default Cheque Number*/
// $(document).on('change','.account_no',function(){
//     var accid=$('.account_no').val();
//     var cheque_no=$('.cheque_no').val();
//      var pmttypeid=$('.payment_type_id option:selected').text();
//     var pmttype=$.trim(pmttypeid);
//     $('.mcontent2').html('');
    
//      if(pmttype=="CHEQUE"){
//     if(accid!=""){
//     var url ="{{URL::to('getchequeno')}}/"+accid;
//     $.get(url,function(data){
//     var end=$.trim(data['endcheque']);
//     var chequeno =$.trim(data['chequeno']);
//     if($.trim(data['nocheque'])=="nocheque"){
//       notyMsgs('error','Please Assign Cheque for this Account');
//                     }
//        else if(chequeno > end ){
//                  notyMsg('error','No Cheque Found');
//            }
//            else{
//                $(".cheque_no").val(data['chequeno']);
//            }
//     });
//     }
//     else{
//              $(".cheque_no").val('');
//     }
//     }
//     });
/*End*/
 /*Karthigaa Purpose For Save Function*/      
      
    $(document).on('click', '.saveform', function() {
          var btnval = $(this).val();
      var invoice_amount=parseFloat($('.invoice_amount').val());
      var payment_amount=parseFloat($('.payment_amount').val());
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
            
    var url = "{{ url('paymentforinvoiceblncesave') }}";
    var red_url = "{{url('paymentforinvoiceblnce')}}"
    validationrule('paymentinv_form');
    var formdata = $('#paymentinv_form').serialize();
    var form = $('#paymentinv_form');
            form.parsley().validate();
            var form = $('#paymentinv_form');
            form.parsley().validate();
            if (form.parsley().isValid())
            {
                   change_date();
                    var formdata = $('#paymentinv_form').serialize();
            $.post(url, formdata, function(data)
            {
            var status = data.status;
            var msg = '<span style="color:#090065"></span>' + data.message;
            var id = data.id;
            var edit_url = "{{ url('paymentforinvoiceblncecreate') }}/" + id;
            if (btnval != 'SAVE' && btnval != 'DRAFT')
            {
            notyMsg(status, msg);
            setTimeout(function(){
            window.location.href = red_url;
            }, 1500);
            }
            else
            {
            notyMsg(status, msg);
            setTimeout(function(){
            window.location.href = red_url;
            }, 1500);
            }
            });
            }
    });
    });</script>
<script>
    $(window).on('load', function(){
    //preloader
    var preLoder = $("#preloader");
    preLoder.fadeOut(500);
    var backtoTop = $('.back-to-top')
            backtoTop.fadeOut(100);
    });
</script>

@include('layouts.php_js_validation')
@endsection
