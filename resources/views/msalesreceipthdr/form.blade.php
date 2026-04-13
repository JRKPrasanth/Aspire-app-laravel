@extends("layouts.header")
@section("content")

<style type="text/css">
/*styles for removing edit button in sweet alert*/
    .sweet-alert p .apply.btn-lg{
       display: none;
    }
</style>

<span class="ui_close_btn"></span><h3 class="heads"><a role="button">msalesreceipt</a> <span class="ui_close_btn"><a href="{{ URL::to('msalesreceipthdr') }}" class="collapse-close pull-right btn-danger" ></a></span></h3><style type="text/css">
    /*Resoultion For Screen Width  */@media only screen and (min-width: 1500px) {
    .bulk_line_no {width: 50px !important;}.bulk_receipt_line_id{width: 100px !important;}.bulk_receipt_id{width: 100px !important;}.bulk_line_no{width: 100px !important;}.bulk_invoice_hdr_id{width: 100px !important;}.bulk_receipt_amount{width: 100px !important;}.bulk_balance_amount{width: 100px !important;}.bulk_invoice_amount{width: 100px !important;}.bulk_expense_amount{width: 100px !important;}}
    @media only screen and (min-width: 2000px) {
    .bulk_line_no {width: 50px !important;}.bulk_receipt_line_id{width: 100px !important;}.bulk_receipt_id{width: 100px !important;}.bulk_line_no{width: 100px !important;}.bulk_invoice_hdr_id{width: 100px !important;}.bulk_receipt_amount{width: 100px !important;}.bulk_balance_amount{width: 100px !important;}.bulk_invoice_amount{width: 100px !important;}.bulk_expense_amount{width: 100px !important;}  }
    .bulk_line_no{width: 50px;}.bulk_receipt_line_id{width: 100px !important;}.bulk_receipt_id{width: 100px !important;}.bulk_line_no{width: 100px !important;}.bulk_invoice_hdr_id{width: 100px !important;}.bulk_receipt_amount{width: 100px !important;}.bulk_balance_amount{width: 100px !important;}.bulk_invoice_amount{width: 100px !important;}.bulk_expense_amount{width: 100px !important;}}
</style><div class="ajaxLoading"></div>
<form method="post" action="" id="msalesreceipthdr" class="msalesreceipthdr" data-parsley-validate>
{{ csrf_field() }}
<div class="card">

<div class="card-body card-block">
  <!------------------------------------- Body content start here ---------------------------->
<div class="row">
<div class="row">
<div class="col-md-12">
    <input type="hidden" name="receipt_id" value="{{ $row->receipt_id}}" >
    <input type="hidden" name="receipt_number" value="{{ $row->receipt_number}}" >
    <div class="col-md-4 form-group row" >
                                    <label for="inputIsValid" class="form-control-label col-md-5">Receipt Date</label>
                                    <div class="col-md-5">
                                        <div class="input-group form_date col-md-8" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                            <input class="form-control datepicker receipt_date" id="receipt_date" name="receipt_date" size="16" type="text" value="{{ $row->receipt_date }}" >
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-2 showline">
                                    </div>
                                </div>
 
    <!--<div class="col-md-4 form-group row" >-->
    <!--    <label for="inputIsValid" class="form-control-label col-md-4">INVOICE AMOUNT</label>-->
    <!--    <div class="col-md-6">-->
    <!--         <input type="text" id="invoice_amount" name="invoice_amount" class="form-control invoice_amount" value="{{$row->invoice_amount}}" >-->
    <!--    </div>-->
    <!--    <div class="col-md-2">-->
    <!--    </div>-->
    <!--</div>-->
    
    <!--<div class="col-md-4 form-group row" >-->
    <!--    <label for="inputIsValid" class="form-control-label col-md-4">RECEIPT AMOUNT</label>-->
    <!--    <div class="col-md-6">-->
    <!--         <input type="text" id="receipt_amount" name="receipt_amount" class="form-control receipt_amount" value="{{$row->receipt_amount}}" >-->
    <!--    </div>-->
    <!--    <div class="col-md-2">-->
    <!--    </div>-->
    <!--</div>  -->
    <div class="col-md-4 form-group row" >
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
                     <div class="col-md-4 form-group chequediv row" >
        
                                <label for="inputIsValid" class="form-control-label col-md-5"><span class="bankdiv" style="color:red">*</span>Bank Name</label>
                                <div class="col-md-5 sel2">
                                    <select name='bank_id' rows='5' id='bank_id' class='select2 bank_id' required>
                                        {!! $row->bank_id !!}
                                    </select>
                                </div>
                                <div class="col-md-2 showinline">
                                    <span class="showspan"> <i class="fa fa-refresh jcr_bank_id"></i></span>
                                </div>
                        </div>
    <div class="col-md-4 form-group row" >
             <label for="inputIsValid" class="form-control-label col-md-5">Account Number</label>
            <div class="col-md-5 supplier_div">
            <select name='account_no' rows='5' id='account_no' class='select2 account_no'>
                 {!! $row->account_no !!}
                                    </select>
                                </div>
                                <div class="col-md-2 showinline">
                                    </div>
                        </div>  
    <div class="col-md-4 form-group row" >
                                    <label for="inputIsValid" class="form-control-label col-md-5"><span style="color:red;">*</span>Account Code</label>
                                    <div class="col-md-5">
                                        <select name='account_code_id' rows='5' class='select2 account_code_id' required>
                                            {!! $row->account_code_id !!}
                                        </select>
                                    </div>
                                    <div class="col-md-2 showinline">
                                       <span class="showspan"><i class="fa fa-refresh jcr_account_code_id"></i></span>
                                    </div>
                                </div>

<div class="col-md-4 form-group row" >
                                    <label for="inputIsValid" class="form-control-label col-md-5">Expense Account Code</label>
                                    <div class="col-md-5">
                                        <select name='expense_account_code_id' rows='5' class='select2 expense_account_code_id' >
                                            {!! $row->expense_account_code_id !!}
                                        </select>
                                    </div>
                                    <div class="col-md-2 showinline">
                                       
                                    </div>
                                </div>

    <div class="col-md-4 form-group row" >
        <label for="inputIsValid" class="form-control-label col-md-5">Bank Date</label>
        <div class="col-md-5">
             <input type="text" id="bank_date" name="bank_date" class="form-control bank_date datepicker" value="{{$row->bank_date}}" >
        </div>
        <div class="col-md-2">
        </div>
    </div>
    
    <!--<div class="col-md-4 form-group row" >-->
    <!--    <label for="inputIsValid" class="form-control-label col-md-4">RECEIPT REFERENCE</label>-->
    <!--    <div class="col-md-6">-->
    <!--         <input type="text" id="receipt_reference" name="receipt_reference" class="form-control receipt_reference" value="{{$row->receipt_reference}}" >-->
    <!--    </div>-->
    <!--    <div class="col-md-2">-->
    <!--    </div>-->
    <!--</div> -->
    
        <div class="col-md-4 form-group row chequedate">
                                     <label for="inputIsValid" class="form-control-label col-md-5">Cheque Date</label>
                                     <div class="col-md-5">
                                         <input type="text" name="cheque_date"  id="cheque_date"  class="form-control cheque_date datepicker" >

                                     </div>
                                     <div class="col-md-2">
                                     </div>
                                 </div> 
                                 <div class="col-md-4 form-group row chequediv"  style="display:none;">
                                <label for="inputIsValid" class="form-control-label col-md-5"><span style="color:red">*</span>Cheque No</label>
                                <div class="col-md-5 supplier_div">
                                    <input type="text" id="cheque_no" name="cheque_no" class="form-control cheque_no" value="{{ $row->cheque_no }}"  />
                                </div>
                                <div class="col-md-2 showinline">
                                    </div>
                            </div>
    
    <div class="col-md-4 form-group row" >
        <label for="inputIsValid" class="form-control-label col-md-5">Reference No</label>
        <div class="col-md-5">
             <input type="text" id="reference_no" name="reference_no" class="form-control reference_no" value="{{$row->reference_no}}" >
        </div>
        <div class="col-md-2">
        </div>
    </div>      
<div class="col-md-4 form-group row" >
                                     <label for="inputIsValid" class="form-control-label col-md-5">Remarks</label>
                                     <div class="col-md-5">
                                         <input type="text" name="remarks"  id="remarks"  class="form-control remarks" value="{{ $row->remarks }}" >

                                     </div>
                                     <div class="col-md-2">
                                     </div>
                                 </div>                     
  <div class="col-md-2">

  </div>
</div>



  
  
                                </div>
<div class="row"  style="margin-top: 22px;">
<div class="col-md-12">

<a href="javascript:void(0);"  class="add_row additem" rel=".rcopy"><i class="fa fa-plus"></i> New Item</a>


 <div id="preview-area" class="chandru">
    <table class="overflow-y preview table_hdr">

<thead>
<tr>
    <th>LINE NO</th>
    <th>CUSTOMER NAME</th>
    <th>INVOICE NO</th>
    <th>INVOICE AMOUNT</th>
    <th>EXPENSE AMOUNT</th>
    <th>RECEIPT AMOUNT</th>
    <th>BALANCE AMOUNT</th>
    <th ></th>

</tr>
</thead>
<tbody class="table_lines_body">
<?php if(count($linedata)>=1) { ?>
@foreach($linedata as $key=>$value)

<tr class="rcopy clone" >
  <td></td>
    <td>
        <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no " value="{{ $key + 1 }}" readonly="readonly">
        <input type="hidden" class="receipt_line_id" name="bulk_receipt_line_id" value="{{ $value->receipt_line_id }}">
        <input type="hidden" class="receipt_id" name="bulk_receipt_id" value="{{ $value->receipt_id }}"></td>
        <td><select name="bulk_customer_id[]" id="bulk_customer_id" class="select2 bulk_customer_id ">{!! $value->customer_id !!}</select>
  </td>
        <td style='pointer-events: none;'>
            <select name="bulk_invoice_hdr_id[]" id="bulk_invoice_hdr_id" class="select2 bulk_invoice_hdr_id " required="required" >{!! $value->invoice_hdr_id !!}</select>
  </td>
    <td><input type="text" name="bulk_invoice_amount[]" id="bulk_invoice_amount" class=" form-control input-sm bulk_invoice_amount"  value="{{ $value->invoice_amount }}" readonly>
    </td>
        <td><input type="text" name="bulk_expense_amount[]" id="bulk_expense_amount" class=" form-control input-sm bulk_expense_amount"  value="{{ $value->expense_amount }}" >
        </td> 
        <td>
            <input type="text" name="bulk_receipt_amount[]" id="bulk_receipt_amount" class=" form-control input-sm bulk_receipt_amount"  value="{{ $value->receipt_amount }}" >
            </td>
        <td><input type="text" name="bulk_balance_amount[]" id="bulk_balance_amount" class=" form-control input-sm bulk_balance_amount"  value="{{ $value->balance_amount }}" >
        </td>
      
    

            <td>
                <a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
                <input type="hidden" name="counter[]">
            </td>
</tr>
@endforeach
<?php } if(count($linedata) < 1 ) {  ?>
    <tr class="rcopy clone" >
    <td></td>
    <td>
        <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no " value="1" readonly="readonly"><input type="hidden" class="receipt_line_id" name="bulk_receipt_line_id"><input type="hidden" class="receipt_id" name="bulk_receipt_id"></td>
        <td><input type="text" name="bulk_line_no[]" id="bulk_line_no" class=" form-control input-sm bulk_line_no"  value="" ></td>
        <td><select name="bulk_invoice_hdr_id[]" id="bulk_invoice_hdr_id" class="select2 bulk_invoice_hdr_id " required="required">{!! $invoice_hdr_id !!}</select>
  </td>
        <td><input type="text" name="bulk_receipt_amount[]" id="bulk_receipt_amount" class=" form-control input-sm bulk_receipt_amount"  value="" ></td>
        <td><input type="text" name="bulk_balance_amount[]" id="bulk_balance_amount" class=" form-control input-sm bulk_balance_amount"  value="" ></td>
        <td><input type="text" name="bulk_invoice_amount[]" id="bulk_invoice_amount" class=" form-control input-sm bulk_invoice_amount"  value="" ></td>
        <td><input type="text" name="bulk_expense_amount[]" id="bulk_expense_amount" class=" form-control input-sm bulk_expense_amount"  value="" ></td><td>
                <a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
                <input type="hidden" name="counter[]">
            </td>
</tr>


<?php } ?>
</tbody>
</table>
<input type="hidden" name="enable-masterdetail" value="true">
<input  type="hidden" class="form-control input-sm bulk_hidden_date datepicker" value="">
</div>

</div>
</div>
<!-------------------------Linedata End--------------------------------><div class="row">
  <div class="col-lg-12 col-md-12">
    <div class="form-group text-center actionbtn">
                      <button name="submit" type="button" class="btn save saveform" value="SAVE">Submit</button>
              <a class="btn cancel" href="{{ URL::to('msalesreceipthdr') }}">Cancel</a>
    </div>
  </div>
</div>


</div>

</div>
</div>


  </div>
  </div>
    </form>


  <script>
/*Purpose For Required Validation*/
$(".select2").change(function() {
            $(this).parsley().validate();
 });


function example(){
       $("#file_choosen").css({"border-color": "rgb(20, 46, 120)", 
             "border-width":"1px", 
             "border-style":"solid"});        
       }
  
    
$(document).ready(function(){

/*Deepika Purpose For Add New Row*/    
var data ="{{\Session::get('j_date_format')}}"; $(".add_row").on("click",function(){
  var form = $("#msalesreceipthdr");
  form.parsley().destroy();
});
changeclassfields();
$(".add_row").relCopy(data);
$(".add_row").click(function(){
     changeclassfields();
}); /*End*/

/*Deepika Purpose For Remove Row*/   
$(document).on("click",".remove",function(){
  var index = $(this).closest("tr").index();
  var rowCount = $(".msalesreceipthdr tbody tr").length;
  if(rowCount > 1){
    $($(this).closest("tr")).remove();
                removeclassfields();
  }
  else{
    notyMsg("info","You Cannot Delete Atleast One row should be there");
  }
});
/*End*/

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

$(document).on('change','.account_no',function(){
//    var account_no=$('.account_no').val();
    var account_no=$('.bank_id').val();
    var url="{{URL::to('getaccountdetails')}}/"+account_no;
    $.get(url,function(data){
//        console.log(data);
    $('.account_code_id').val(data[0].account_code_id).change();  
    
        });
});
     $('#bank_id').on('change',function(){
         var bank = $('#bank_id').val();
         var pdt_condition ='bank_account_hdr_id='+bank;
        $(".account_no").jCombo("{{ URL::to('jcomboform?table=f_bank_account_lines_t:bank_account_line_id:account_number') }}&order_by=account_number asc"+'&parent='+pdt_condition,
           {selected_value:""});
    });
$(document).on('change','.bulk_receipt_amount,.bulk_expense_amount',function(){
    var index=$(this).closest('tr').index();
    
      var invoice_amt=parseFloat($(".bulk_invoice_amount"+index).val());
      var receipt_amt=parseFloat($(".bulk_receipt_amount"+index).val()); 
    var expense=parseFloat($(".bulk_expense_amount"+index).val()); 
    var balance_amt=parseFloat($(".bulk_balance_amount"+index).val()); 
    
      //var num1 = isNaN(parseInt(receipt_amt)) ? 0 : parseInt(receipt_amt);
      var bal=invoice_amt-(receipt_amt+expense);
      //re+ex-invo
//      $(".receipt_amount").val(num1);
//        $(".paid_amount").val(paid_amt);
          $(".bulk_balance_amount"+index).val(bal);
    
  });
  
var index = $(".clone").closest("tr").index();
changeclassfields();/*Deepika Purpose For Submit Function*/                 
$(document).on("click",".saveform",function() {
    
        var btnval = $(this).val();
var red_url="{{ URL::to('msalesreceipthdr') }}";
var url="{{ URL::to('msalesreceipthdrsave') }}";

  validationrule("msalesreceipthdr");
  var form = $("#msalesreceipthdr");
  
    form.parsley().validate();
    var form = $("#msalesreceipthdr");
                    form.parsley().validate();

    if (form.parsley().isValid())
    {
     $(".ajaxLoading").show();
     //alert(round_off);
                 change_date();
                 var formdata = $("#msalesreceipthdr").serialize();
     var form_data = new FormData(document.getElementById("msalesreceipthdr"));   
          $.ajax({
                  url: url,
                  type: "POST",
                  data: form_data,
                  enctype: "multipart/form-data",
                  processData: false,  // tell jQuery not to process the data
                  contentType: false,   // tell jQuery not to set contentType
                  async:true,
                  xhr: function(){
                      var xhr = $.ajaxSettings.xhr();
                    if (xhr.upload) {
                        xhr.upload.addEventListener("progress", function(event) {
                                var percent = 0;
                                var position = event.loaded || event.position;
                                var total = event.total;
                                if (event.lengthComputable) {
                                        percent = Math.ceil(position / total * 100);
                                }
                                        //update progressbar

                                }, true);
                        }
          return xhr;

                }
                }).done(function(data)
    {
      var status  = data.status;
        var msg     = "<span style='color:#090065'>"+data.auto_no+"</span>  "+data.message;
        var id      = data.id;
        var auto_no = data.auto_no;
            if(btnval !="SAVE" && btnval !="Approved" && btnval !="Rejected" && btnval !="Canceled")
           {
                   notyMsg(status,msg);
                   $(".ajaxLoading").hide();
                   window.location.href=create_url;
           }
           else
           {
                   notyMsg(status,msg);
                   $(".ajaxLoading").hide();
                   window.location.href=red_url;
                   
           }
    });  
    }
  

});
});function changeClassName(className)
{
$("." + className).each(function (index)
{
if (className == "bulk_line_no")
{
$(this).val(index + 1).attr("readonly", 1);
}

$(this).removeClass(className + "0");
$(this).addClass(className + index);
});
}
/************ Deepika purpose to remove row action ********************/
function removeClass(className)
{
  var rowCount = $(".$header tbody tr").length;
  for(var i=0;i<=rowCount;i++)
  {
  $(".$header tbody tr").find("."+className).removeClass(className+i);
  }
  $("." + className).each(function (index)
  {
    if (className == "bulk_line_no")
    {
    $(this).val(index + 1).attr("readonly", 1);
    }
    $(this).addClass(className + index);
  });
}function changeclassfields(){
    changeClassName("bulk_line_no");
    changeClassName('bulk_receipt_line_id');
    changeClassName('bulk_customer_id');
    changeClassName('bulk_receipt_id');changeClassName('bulk_line_no');changeClassName('bulk_invoice_hdr_id');changeClassName('bulk_receipt_amount');changeClassName('bulk_balance_amount');changeClassName('bulk_invoice_amount');changeClassName('bulk_expense_amount');}
function removeclassfields(){
        removeClass("bulk_line_no");
        removeClass('bulk_customer_id');
        removeClass('bulk_receipt_line_id');removeClass('bulk_receipt_id');removeClass('bulk_line_no');removeClass('bulk_invoice_hdr_id');removeClass('bulk_receipt_amount');removeClass('bulk_balance_amount');removeClass('bulk_invoice_amount');removeClass('bulk_expense_amount');        }</script>


@include("layouts.php_js_validation")
@endsection
