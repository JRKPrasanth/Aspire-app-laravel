@extends('layouts.header')
@section('content')

<style type="text/css">
 
   #myModal input{
    border: 1px solid rgba(0, 18, 103, 0.89);
   }
   #myModal .table{
     border: 1px solid rgba(0, 18, 103, 0.89);
    margin: 0px auto;
    float: none;
   }
@media screen and (max-width: 1280px) and (min-width: 1400px) {
  #myModal .table{
     border: 2px solid rgba(0, 18, 103, 0.89);
    margin: 0px auto;
    float: none;
   }
}

   .table>tbody>tr>th{
    color: #fff;
    background: rgba(0, 18, 103, 0.89);
   }
   .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
    border: 1px solid rgba(0, 18, 103, 0.89);
   }



</style>


<?php include('tools_menu.php');?>
<div class="ajaxLoading"></div> 
   <h4 class="heads">
    Receipt For Invoice<span class="ui_close_btn"><a class="collapse-close pull-right btn-danger" onclick="location.href = '{{url('receiptforinvoice')}}'"></a></span>
    </h4>
    
<span class="ui_close_btn"></span>
            <form method="post" action="" id="receiptinv_form" data-parsley-validate>
                {{ csrf_field() }}

      <div class="card">

                <div class="card-body card-block">
                    <div class="row">
                            <div class="col-md-4">
                                <input class="form-control receipt_id" id="receipt_id" name="receipt_id" size="16" type="hidden" value="{{ $row->receipt_id }}" readonly>
                                <input class="form-control receipt_number" id="receipt_number" name="receipt_number" size="16" type="hidden" value="{{ $row->receipt_number }}" readonly>
                                <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5"><span style="color:red">*</span>Invoice No</label>
                                    <div class="col-md-5 sel2">
                                        <input type="text" class="form-control invoice_number" id="invoice_number" size="16" value="{{ $invoice_number }}" readonly>
                                        <input type="text" class="form-control invoice_hdr_id" id="invoice_hdr_id" name="invoice_hdr_id" size="16" value="{{ $row->invoice_hdr_id }}" hidden="true" style="display:none;" readonly>
<input type="hidden" class="form-control sales_hdr_id" id="sales_hdr_id" name="sales_hdr_id" size="16" value="{{ $row->sales_hdr_id }}" hidden="true" style="display:none;" readonly>
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Customer Name</label>
                                    <div class="col-md-5">
                                        <select name='customer_id' rows='5' class='form-control customer_id' readonly >
                                            {!! $customer_id !!}
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                </div>
                        <div class="form-group row">
                            <label for="inputIsValid" class="form-control-label col-md-5">Invoice Amount</label>
                            <div class="col-md-5">
                                <input type="text" id="invoice_amount" name="invoice_amount" class="form-control invoice_amount chckclick" value="{{ $row->invoice_amount }}" readonly>
                            </div>
                            <div class="col-md-2 showline">
                                
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputIsValid" class="form-control-label col-md-5">Invoice Currency</label>
                            <div class="col-md-5">
                                <input type="text" id="invoice_currency" name="invoice_currency" class="form-control invoice_currency chckclick" value="{{ $row->invoice_currency }}" readonly>
                            </div>
                            <div class="col-md-2 showline">
                                
                            </div>
                        </div>
                       <?php if($row->invoice_currency!="INR") { ?>
                        <div class="form-group row exportval">
                            <label for="inputIsValid" class="form-control-label col-md-5">Invoice Exchage Rate</label>
                            <div class="col-md-5">
                                <input type="text" id="conversion_rate" name="conversion_rate" class="form-control conversion_rate chckclick" value="{{ $row->conversion_rate }}" readonly>
                            </div>
                            <div class="col-md-2 showline">
                            </div>
                        </div>
                        <div class="form-group row exportval">
                            <label for="inputIsValid" class="form-control-label col-md-5">Invoice Exchage Amount</label>
                            <div class="col-md-5">
                                <input type="text" id="conversion_amt" name="conversion_amt" class="form-control conversion_amt chckclick" value="{{ $row->conversion_amt }}" readonly>
                            </div>
                            <div class="col-md-2 showline">
                                
                            </div>
                        </div> <?php } ?>
                                <div class="form-group row">
                                     <label for="inputIsValid" class="form-control-label col-md-5">Customer Account Name</label>
                                     <div class="col-md-5">
                                         <input type="text" name="customer_account_name"  id="customer_account_name"  class="form-control customer_account_name" value="{{$row->customer_account_name}}" readonly>
                                     </div>
                                     <div class="col-md-2">
                                     </div>
                                 </div> 
                                <div class="form-group row">
                                     <label for="inputIsValid" class="form-control-label col-md-5">Customer Bank Name</label>
                                     <div class="col-md-5">
                                         <input type="text" name="bank_name"  id="bank_name"  class="form-control bank_name" value="{{$row->bank_name}}" readonly>

                                     </div>
                                     <div class="col-md-2">
                                     </div>
                                 </div> 
                                 <div class="form-group row">
                                     <label for="inputIsValid" class="form-control-label col-md-5">Customer Account Number</label>
                                     <div class="col-md-5">
                                         <input type="text" name="account_number"  id="account_number"  class="form-control account_number" value="{{$row->account_number}}" readonly >

                                     </div>
                                     <div class="col-md-2">
                                     </div>
                                </div> 
                                <div class="form-group row">
                                     <label for="inputIsValid" class="form-control-label col-md-5">Customer IFSC Code</label>
                                     <div class="col-md-5">
                                         <input type="text" name="ifsc_code"  id="ifsc_code"  class="form-control ifsc_code" value="{{$row->ifsc_code}}" readonly >

                                     </div>
                                     <div class="col-md-2">
                                     </div>
                                 </div>
                                </div>
                         <div class="col-md-4">
                              <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Receipt Date</label>
                                    <div class="col-md-5">
                                        <div class="input-group form_date col-md-8" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                            <input class="form-control datepicker receipt_date" id="receipt_date" name="receipt_date" size="16" type="text" value="{{ $row->receipt_date }}" >
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-2 showline">
                                    </div>
                                </div>
              
                                <div class="form-group row" style="display:none;">
                         <label for="inputIsValid" class="form-control-label col-md-5">Receipt Status</label>
                         <div class="col-md-5">
                             <select type="text" name="receipt_status" id="receipt_status" class="form-control  receipt_status"  >
                             <option value="">-- Please Select --</option>
                             <option <?php if($row->receipt_status=="PAID" ) echo "selected"; ?> value="PAID">PAID</option>
                             <option <?php if($row->receipt_status=="UNPAID" ) echo "selected"; ?> value="UNPAID">UNPAID</option>
                             <option <?php if($row->receipt_status=="OVERDUE" ) echo "selected"; ?> value="OVERDUE">OVERDUE</option>
			     <option <?php if($row->receipt_status=="PARTIALLY PAID" ) echo "selected"; ?> value="PARTIALLY PAID">PARTIALLY PAID</option>
                             </select>
                         </div>
                         <div class="col-md-2">
                         </div>
                     </div>
                     <div class="form-group row" >
                      <label for="inputIsValid" class="form-control-label col-md-5"><span style="color:red">*</span>Receipt Type</label>
                      <div class="col-md-5 sel2">
                          <select name='receipt_type_id' rows='5' class='form-control receipt_type_id select2' data-show-subtext="true" data-live-search="true" required>
                              <option value="">--Please Select--</option>
                              <option <?php if($row->receipt_type_id =="CHEQUE") { echo "selected"; } else { echo ""; } ?> value="CHEQUE">CHEQUE</option>
                              <option <?php if($row->receipt_type_id =="CASH") { echo "selected"; } else { echo ""; } ?> value="CASH">CASH</option>
                              <option <?php if($row->receipt_type_id =="NEFT") { echo "selected"; } else { echo ""; } ?> value="NEFT">NEFT</option>
                              <option <?php if($row->receipt_type_id =="MTPS") { echo "selected"; } else { echo ""; } ?> value="MTPS">MTPS</option>
                              <option <?php if($row->receipt_type_id =="DEBIT") { echo "selected"; } else { echo ""; } ?> value="DEBIT">DEBIT</option>
                              <option <?php if($row->receipt_type_id =="CREDIT") { echo "selected"; } else { echo ""; } ?> value="CREDIT">CREDIT</option>
                              <option <?php if($row->receipt_type_id =="NET BANKING") { echo "selected"; } else { echo ""; } ?> value="NET BANKING">NET BANKING</option>
                              <option <?php if($row->receipt_type_id =="OTHER ATMS") { echo "selected"; } else { echo ""; } ?> value="OTHER ATMS">OTHER ATMS</option>
                              <option <?php if($row->receipt_type_id =="ICICI ATM") { echo "selected"; } else { echo ""; } ?> value="ICICI ATM">ICICI ATM</option>
                              <option <?php if($row->receipt_type_id =="MOBILE BANKING") { echo "selected"; } else { echo ""; } ?> value="ICICI ATM">MOBILE BANKING</option>
                              <option <?php if($row->receipt_type_id =="CASH DEPOSIT") { echo "selected"; } else { echo ""; } ?> value="CASH DEPOSIT">CASH DEPOSIT</option>
                          </select>
                      </div>
                     </div>
                       <div class="form-group row chequediv">
                                <label for="inputIsValid" class="form-control-label col-md-5"><span class="bankdiv" style="color:red">*</span>Bank Name</label>
                                <div class="col-md-5 sel2">
                                    <select name='bank_id' rows='5' id='bank_id' class='select2 bank_id' required>
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
                                        {!! $account_no !!}
                                    </select>
                                </div>
                                <div class="col-md-2 showinline">
                                    </div>
                        </div>
                      
                      <div class="col-md-2">
                      </div>
               
                    <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5"><span style="color:red;">*</span>Account Code</label>
                                    <div class="col-md-5">
                                        <select name='account_code_id' rows='5' class='select2 account_code_id' required>
                                            {!! $account_code_id !!}
                                        </select>
                                    </div>
                                    <div class="col-md-2 showinline">
                                       <span class="showspan"><i class="fa fa-refresh jcr_account_code_id"></i></span>
                                    </div>
                                </div>
                                  <div class="form-group row chequedate">
                                     <label for="inputIsValid" class="form-control-label col-md-5">Cheque Date</label>
                                     <div class="col-md-5">
                                         <input type="text" name="cheque_date"  id="cheque_date"  class="form-control cheque_date datepicker" required="true">

                                     </div>
                                     <div class="col-md-2">
                                     </div>
                                 </div> 
                                    <div class="form-group row">
                                                     <label for="inputIsValid" class="form-control-label col-md-5">Bank Date</label>
                                                     <div class="col-md-5">
                                                         <input type="text" name="bank_date"  id="bank_date"  class="form-control bank_date datepicker" value="" >

                                                     </div>
                                                     <div class="col-md-2">
                                                     </div>
                                    </div>
                                    <div class="form-group row">
                                     <label for="inputIsValid" class="form-control-label col-md-5">Reference No</label>
                                     <div class="col-md-5">
                                         <input type="text" name="reference_no"  id="reference_no"  class="form-control reference_no" value="" >

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
                    <label for="inputIsValid" class="form-control-label col-md-5"><span style="color:red">*</span>Receipt Reference</label>
                    <div class="col-md-5">
                        <input type="text" id="receipt_reference" name="receipt_reference" class="form-control receipt_reference" value="{{ $row->receipt_reference }}" required />
                    </div>
                    <div class="col-md-2">
                    </div>
                </div>
                  <div class="form-group row">
                       <label for="inputIsValid" class="form-control-label col-md-5">Advance Amount</label>
                        <div class="col-md-5">
                            <input type="text" id="advance_amount" name="advance_amount" class="form-control advance_amount" value="{{ $row->advance_amount }}" required readonly >
                         </div>
                          <div class="col-md-2 showinline">
                                <i class="fa fa-plus" aria-hidden="true" data-toggle="modal" data-target="#advanceModal" style="color: #142e78;
                font-size: 13px;
                padding: 5px;
                border: 1px solid;
                cursor: pointer;"></i>   
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputIsValid" class="form-control-label col-md-5"><span style="color:red">*</span>Receipt Amount</label>
                            <div class="col-md-5">
                                <input type="text" id="receipt_amount" name="receipt_amount" class="form-control receipt_amount chckclick" value="{{ $row->receipt_amount }}"  readonly>
                            </div>
                            <div class="col-md-2 showinline">
                             <span class="showspan"><i class="fa fa-plus" aria-hidden="true" data-toggle="modal" data-target="#myModal" style="margin::4px 0;"></i></span>   
                              
                            </div>
                        </div>
							   <div class="form-group row">
                            <label for="inputIsValid" class="form-control-label col-md-5"><span style="color:red">*</span>Credit Amount</label>
                            <div class="col-md-5">
                                <input type="text" id="credit_amount" name="credit_amount" class="form-control credit_amount creditchckclick" value="{{ $row->credit_amount }}"  readonly>
                            </div>
                            <div class="col-md-2 showinline">
                             <span class="showspan"><i class="fa fa-plus" aria-hidden="true" data-toggle="modal" data-target="#mycreditModal" style="margin::4px 0;"></i></span>   
                              
                            </div>
                        </div>
							   <div class="form-group row">
                            <label for="inputIsValid" class="form-control-label col-md-5"><span style="color:red">*</span>Debit Amount</label>
                            <div class="col-md-5">
                                <input type="text" id="debit_amount" name="debit_amount" class="form-control debit_amount debitchckclick" value="{{ $row->debit_amount }}"  readonly>
                            </div>
                            <div class="col-md-2 showinline">
                             <span class="showspan"><i class="fa fa-plus" aria-hidden="true" data-toggle="modal" data-target="#mydebitModal" style="margin::4px 0;"></i></span>   
                              
                            </div>
                        </div>
							 <div class="form-group row">
                            <label for="inputIsValid" class="form-control-label col-md-5">Paid Amount</label>
                            <div class="col-md-5">
                                <input type="text" id="paid_amount" name="paid_amount" class="form-control paid_amount chckclick" value="{{ $row->paid_amount }}" readonly>
                            </div>
                            <div class="col-md-2 showline">
                            </div>
                        </div> 
			         <div class="form-group row">
                            <label for="inputIsValid" class="form-control-label col-md-5">Balance Amount</label>
                            <div class="col-md-5">
                                <input type="text" id="balance_amount" name="balance_amount" class="form-control balance_amount chckclick" value="{{ $row->balance_amount }}" readonly>
                            </div>
                            <div class="col-md-2 showline">
                            </div>
                        </div>
                        <?php if($row->invoice_currency!="INR") { ?>
                        <div class="form-group row exportval">
                            <label for="inputIsValid" class="form-control-label col-md-5"><span style="color:red">*</span>Exchange Rate</label>
                            <div class="col-md-5">
                              
                                <input type="hidden" id="exchangerate" name="exchangerate" class="form-control exchangerate " value="{{$row->exchangerate}}">
                                <input type="text" id="exchangerateshow"  class="form-control exchangerateshow " value="{{$row->exchangerate}}">
                            </div>
                            <div class="col-md-2 showline">
                            </div>
                        </div>
                        
                       <div class="form-group row exportval">
                            <label for="inputIsValid" class="form-control-label col-md-5">Exchange Amount</label>
                            <div class="col-md-5">
                                <input type="text" id="exchangeamount" name="exchangeamount" class="form-control exchangeamount " value="" >
                            </div>
                            <div class="col-md-2 showline">
                            </div>
                        </div>
                        <div class="form-group row exportval">
                            <label for="inputIsValid" class="form-control-label col-md-5">Gain/Loss</label>
                            <div class="col-md-5">
                                <input type="text" id="gainloss" name="gainloss" class="form-control gainloss " value="{{ $row->gainloss }}" readonly="true">
                            </div>
                            <div class="col-md-2 showline">
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group row chequediv" style="display:none;">
                                <label for="inputIsValid" class="form-control-label col-md-5"><span style="color:red">*</span>Cheque No</label>
                                <div class="col-md-5 supplier_div">
                                    <input type="text" id="cheque_no" name="cheque_no" class="form-control cheque_no" value="{{ $row->cheque_no }}"  required/>
                                </div>
                                <div class="col-md-2 showinline">
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
         
           <table class="table">   

              <tr>
                <th>Invoice Number</th>
                <th>Balance Amount</th>
                <th>Receipt Amount</th>
              </tr>
         
               <?php $i=1;
               foreach($invoice_balamt as $k=>$v) { ?>  
                    <tr>
                  <td>{{$v->invoice_number}}</td>
						<?php $balamt=$v->balance_amount; ?>
                  <td class="balance_amount balance_amount{{$i}}" id="{{$balamt}}" >{{$balamt}}</td>
                  <td><input type="text" name="rcptamt[]" class="rcptamt" value="{{$balamt}}"></td>
              </tr>
					<?php $i++;  } ?>
          
          </table>
            
      </div>
      <div class="modal-footer">
         <button type="button" class="btn btn-default add addrcptamt" data-dismiss="modal">Add</button>
        <button type="button" class="btn btn-default close1" data-dismiss="modal">Close</button>
      </div>
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
               
                
    <!--DEEPIKA PURPOSE:advance amount details based on po-->         
<div id="advanceModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">So Balance Details</h4>
      </div>
      <div class="modal-body">
     <?php if($soamt==0){ ?>    
           <table class="table table-bordered">   
 
              <tr style="background:#05234e;color:#fff;font-family: fantasy;
    font-size: 14px;">
                <th>So Number</th>
                <th>Advance Amount</th>
                <th>Payment Amount</th>
              </tr>
        
               <?php  $i=1;
        
               foreach($so_balamt as $k=>$v) { ?>  
                    <tr>
                  <td>{{$v->sales_order_no}}
         
                  </td>

                  <td class="checkadv balance_amount sobalance_amount{{$i}}" id="{{$v->balance_amount}}">{{$v->balance_amount}}</td>
                  <td><input type="text" name="sopaymentamt[{{$v->sales_hdr_id}}]" class="sopaymentamt" value="{{$v->balance_amount}}"></td>
                 
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
         
               <?php $i=1; if(count($invoice_credit)>0){
               foreach($invoice_credit as $k=>$v) { ?>  
                    <tr>
                  <td>{{$v->invoice_number}}</td>
                  <td class="credit_note_balance credit_note_balance{{$i}}" id="{{$v->credit_note_balance}}" >{{$v->credit_note_balance}}</td>
						 <td><input type="text"  name="creditamount[{{$v->invoice_hdr_id}}]" class="creditamt" value="{{$v->credit_note_balance}}"></td>
                </tr>
					<?php $i++;  } }else{?>
			   <tr><td></td><td>There is No Credit Amount Details for this Customer</td></tr>
          <?php } ?>
          </table>
            
      </div>
      <div class="modal-footer">
         <?php if(count($invoice_credit)>0){ ?>
         <button type="button" class="btn btn-default add addcreditamt" data-dismiss="modal">Add</button>
     <?php } ?>
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
         
               <?php $i=1; if(count($invoice_debit)>0){
               foreach($invoice_debit as $k=>$v) { ?>  
                    <tr>
                  <td>{{$v->invoice_number}}</td>
                  <td class="debit_note_balance debit_note_balance{{$i}}" id="{{$v->debit_note_balance}}" >{{$v->debit_note_balance}}</td>
						 <td><input type="text" name="debitamount[{{$v->invoice_hdr_id}}]" class="debitamt" value="{{$v->debit_note_balance}}"></td>
                </tr>
					<?php $i++;  } }else{?>
			   <tr><td></td><td>There is No Debit Amount Details for this Customer</td></tr>
          <?php } ?>
          </table>
            
      </div>
      <div class="modal-footer">
        <?php if(count($invoice_debit)>0){ ?>
         <button type="button" class="btn btn-default add adddebitamt" data-dismiss="modal">Add</button>
     <?php } ?>
        <button type="button" class="btn btn-default close1" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<!--end-->                     
            </form>
<script>

$(document).ready(function(){
  $('select').css('pointer-events', 'none');

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
<?php if($row->invoice_currency!="INR") { ?>
$(".exchangerate").attr('required',true);  

    <?php  } ?>
	/*deepika purpose:qty validation*/
		$(document).on('keypress','.sopaymentamt,.rcptamt,.creditamt,.debitamt', function(ev){
			var regex = new RegExp("^[0-9.]+$");
					var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
					if (regex.test(str)) {
						return true;
					}
					ev.preventDefault();
					return false;
		});
	/*end*/
  /*deepika purpose:add advance amount*/  
	var decimal="{{Session::get('decimal')}}";
 $(document).on('click','.addadvancepayamt',function(){
       var tot = 0;
       var index = $(this).closest('tr').index();
      $(".sopaymentamt").each(function(){
          tot += parseFloat($(this).val());
      });
	 console.log(tot);
         var balance_amount=  $('.balance_amount').val();
	  var adbalamt=tot;
	 if(balance_amount!=""){
        var adbalamt=balance_amount-tot;
		}
        $('.balance_amount').val(adbalamt);
        $('.advance_amount').val(tot.toFixed(decimal)).change();
        //$('.exchangerate').keyup();

  });   
	/*end*/
   /* code for balance amount calaculation*/
  $(document).on('click','.addrcptamt',function(){
       var tot = 0;
       var index = $(this).closest('tr').index();
       var receipt_amount=parseFloat($(".receipt_amount").val()); 
       var balance_amount=parseFloat($('.balance_amount').val());
      
      $(".rcptamt").each(function(){         
          tot += parseFloat($(this).val());
      });
       console.log(tot);
       console.log(balance_amount);
                 <?php if($statement =="0") { ?>
                $('.receipt_amount').val(tot);
        <?php } else { ?>
             
//    if(tot > receipt_amount || tot < receipt_amount){
if(tot > receipt_amount || tot < receipt_amount){  
  $('#myModal').show();
                 notyMsg('info','Receipt amount is not Equal to Statement amount');
                  $('.rcptamt').val('0');
               
              } 
        <?php } ?>
	  var adbalamt=balance_amount-tot;
	$('.balance_amount').val(adbalamt);  
  });

    $(document).on('keyup','.rcptamt',function(){
              var index = $(this).closest('tr').index();
              var rpt=parseInt($(this).val());  
              var balance_amount=parseInt($('.balance_amount'+index).attr('id'));
              var bal= (balance_amount/100);
              var bal1 = bal+balance_amount;
              if(rpt > bal1)
             {
                notyMsg('info','Receipt amount is not more then balance amount');
                 $(this).val('0');
              }      
    });
    /* end */
    $(document).on('change','.advance_amount',function(){  
    var invoice_amt=parseFloat($(".invoice_amount").val());
  var exchangerate=parseFloat($(".exchangerate").val());
   var conversion_rate=parseFloat($(".conversion_rate").val());
  var advance_amount=parseFloat($(".advance_amount").val()); 
var examount=exchangerate*advance_amount;
var examount1 = isNaN(parseFloat(examount)) ? 0 : parseFloat(examount);
var examount12= parseFloat(examount1).toFixed(2);
$(".exchangeamount").val(examount12);
var gainlossval=(exchangerate-conversion_rate)*advance_amount;
var gainlossval1 = isNaN(parseFloat(gainlossval)) ? 0 : parseFloat(gainlossval);
var gainlossval12= parseFloat(gainlossval1).toFixed(2);
$(".gainloss").val(gainlossval12);      
    });   

  $(document).on('keyup','.exchangerate',function(){    
  var invoice_amt=parseFloat($(".invoice_amount").val());
  var exchangerate=parseFloat($(".exchangerate").val());
   var conversion_rate=parseFloat($(".conversion_rate").val());
  var receipt_amt=parseFloat($(".receipt_amount").val()); 
var examount=exchangerate*receipt_amt;
var examount1 = isNaN(parseFloat(examount)) ? 0 : parseFloat(examount);
var examount12= parseFloat(examount1).toFixed(2);
$(".exchangeamount").val(examount12);
var gainlossval=(exchangerate-conversion_rate)*receipt_amt;
var gainlossval1 = isNaN(parseFloat(gainlossval)) ? 0 : parseFloat(gainlossval);
var gainlossval12= parseFloat(gainlossval1).toFixed(2);
$(".gainloss").val(gainlossval12);
  });        
    
$(document).on('keyup','.exchangeamount',function(){    
  var invoice_amt=parseFloat($(".invoice_amount").val());
  var exchangeamount=parseFloat($(".exchangeamount").val());
  var conversion_amt =parseFloat($(".conversion_amt").val());
   var conversion_rate=parseFloat($(".conversion_rate").val());
  var receipt_amt=parseFloat($(".receipt_amount").val()); 

 var aaa= exchangeamount/receipt_amt;
 var aaa2 = isNaN(parseFloat(aaa)) ? 0 : parseFloat(aaa);
 var aaa1=parseFloat(aaa2).toFixed(4);
 var aaa1show=parseFloat(aaa2).toFixed(2);
 $(".exchangerate").val(aaa1);
 $(".exchangerateshow").val(aaa1show);
//var examount=exchangerate*receipt_amt;
 //var aaa1 = isNaN(parseFloat(examount)) ? 0 : parseFloat(examount);
 //var examount12= parseFloat(aaa1).toFixed(2);
// $(".exchangeamount").val(examount12);
    var gainlossval=(aaa1-conversion_rate)*receipt_amt;
//rerate-inrate*reamt
// var gainlossval=receipt_amt-conversion_amt;
 var gainlossval1 = isNaN(parseFloat(gainlossval)) ? 0 : parseFloat(gainlossval);
 var gainlossval12= parseFloat(gainlossval1).toFixed(2);
 $(".gainloss").val(gainlossval12);
  });

  $(document).on('change','.receipt_amount',function(){
	    var invoice_amt=parseInt($(".invoice_amount").val());
	    var receipt_amt=parseInt($(".receipt_amount").val()); 
		var paid_amt=parseInt($(".paid_amount").val()); 
		var balance_amt=parseInt($(".balance_amount").val()); 
		if(receipt_amt > invoice_amt){
			notyMsg("error","Receipt Amount Exceeds");
			$(".receipt_amount").val("");
		}
		else{
			var num1 = isNaN(parseInt(receipt_amt)) ? 0 : parseInt(receipt_amt);
			$(".receipt_amount").val(num1);
				$(".paid_amount").val(paid_amt);
					$(".balance_amount").val(balance_amt);
		}
  });
		/*deepika purpose:add credit,debit amount*/ 
	$(document).on('change','.creditamt',function(){
		 var index = $(this).closest('tr').index();console.log(index);
	var	cn= parseFloat($('.credit_note_balance'+index).val());console.log(cn);
    var	cnamt= $(this).val();console.log(cnamt);
		  if(cn < cnamt){
		notyMsg('info','Amount should not be greater than Credit Amount');  
			$('.creditamt'+i).val(''); 
		  }
	});
	$(document).on('click','.addcreditamt',function(){
       var tot = 0;
       var cn = 0;
       var index = $(this).closest('tr').index();console.log(index);
      $(".creditamt").each(function(i,v){
  	  tot += parseFloat($(this).val());
	    });
	 console.log(tot);
         var balance_amount= parseFloat($('.balance_amount').val());
	  
        var adbalamt=parseFloat(balance_amount)-parseFloat(tot);
		
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
	
        var adbalamt=parseFloat(balance_amount)+parseFloat(tot);
	
        $('.balance_amount').val(adbalamt);
        $('.debit_amount').val(tot);
     

  }); 
/*end*/
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
          /*Validation*/
	$(document).on('keypress','.receipt_amount', function(ev){
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
  
$('.receipt_type_id').on('change',function(){
    var pmttypeid=$('.receipt_type_id option:selected').text();
    var pmttype=$.trim(pmttypeid);
     if(pmttype=="CHEQUE"){
        $(".cheque_no").attr('required',true);  
        $(".cheque_date").attr('required',true);  
     }
     else{
         $(".cheque_no").attr('required',false);
         $(".cheque_date").attr('required',false);     
     }
    if(pmttype!="CASH"){
        $('.chequediv').css('display','block');
    }else{
         var url="{{URL::to('getcashaccount')}}";
    $.get(url,function(data){
        console.log(data);
    $('.account_code_id').val(data[0].cash_account_id).change();  
    
        });
        $('#bank_id').prop('required',false);
        $('.chequediv,.bankdiv').css('display','none');
    }

 if(pmttype=="CHEQUE"){

          $('.chequelabel').html('Cheque No'); 
           $('.cheque_no').attr('required',true);
           $('.cheque_date').attr('required',true);
              $('.chequedate').show(); 
             // $(".cheque_no").attr('required',true);     
              }else{
                  $('.chequelabel').html('Reference No');
                  $('.cheque_no').attr('required',false);
                  $('.chequedate').hide();
                   $('.cheque_date').attr('required',false); 
                 //   $(".cheque_no").attr('required',false);         
              }
        if(pmttype=="CHEQUE" || pmttype=="CASH")
	{
		$(".supplier_bank_id").attr('required',false);
		var form = $('#receiptinv_form');
         form.parsley().destroy();
        }
	else
	{
		
		$(".supplier_bank_id").attr('required',true);
		var form = $('#receiptinv_form');
                form.parsley().destroy();
		
	}    


}); 

/*Karthigaa Purpose for Default Cheque Number*/
/*$(document).on('change','.account_no',function(){
    var accid=$('.account_no').val();
    var cheque_no=$('.cheque_no').val();
    $('.mcontent2').html('');
    if(accid!=""){
    var url ="{{URL::to('getchequeno')}}/"+accid;
    $.get(url,function(data){
        var end=data['endcheque'];
        if(data['chequeno']> end ){
                 notyMsg('error','No Cheque Found');
           }
           else{
               $(".cheque_no").val(data['chequeno']);
           }
    });
    }
    else{
             $(".cheque_no").val('');
    }
    });*/
/*End*/
 /*Karthigaa Purpose For Save Function*/      
      
    $(document).on('click', '.saveform', function() {
          var btnval = $(this).val();
           $('#savestatus').val(btnval);
            
    var url = "{{ url('receiptforinvoicesave') }}";
    var red_url = "{{url('receiptforinvoice')}}"
    validationrule('receiptinv_form');
    var formdata = $('#receiptinv_form').serialize();
    var form = $('#receiptinv_form');
            form.parsley().validate();
            var form = $('#receiptinv_form');
            form.parsley().validate();
            if (form.parsley().isValid())
            {
                $(".ajaxLoading").show();
                   change_date();
                    var formdata = $('#receiptinv_form').serialize();
            $.post(url, formdata, function(data)
            {
            var status = data.status;
            var msg = '<span style="color:#090065"></span>' + data.message;
            var id = data.id;
            var edit_url = "{{ url('receiptforinvoicecreate') }}/" + id;
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
