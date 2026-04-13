@extends('layouts.header')
@section('content')

<?php error_reporting(0);?>



<style type="text/css">
.form-group {
    padding: 0px 5px;
}

.form-control-label {font-size: 20px;}
.acsetting {
    margin: 10px 0;
    padding: 10px;
    background-color: #fff;
    box-shadow: 0 4px 10px 0 rgba(0,0,0,0.2), 0 4px 20px 0 rgba(0,0,0,0.19);
}
.acsettingtitle {
    font-size: 13px;
    color: #6a85b5;
    letter-spacing: 1px;
    height: 30px;
    line-height: 30px;
    margin: 0;
    font-family: 'Prompt', sans-serif;
    transition: all 0.3s ease 0s;
}
.ui_close_btn {
    border: 1px solid #fff;
    padding: 7px;
    float: right;
    background: #07234e;
    color: #fff;
    cursor: pointer;
}
</style>




<div id="accordion">
<div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a role="button">
                                       Account Settings
                                    </a>
        </h4>
  </div>
</div>



<form method="post" action="{{ URL::to('accountsettingssave') }}" id="accountsettings" novalidate>
{{ csrf_field() }}

<div class="card">

<div class="card-body card-block">
  <div class="row">



    <div class="col-md-4">
    <div class="acsetting">
                    <h3 for="inputIsValid" class="acsettingtitle">PURCHASE INVOICE
                    <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="purchaseinvoice" ></a></span>
                    </h3>
    </div>
    </div>


	  <div class="col-md-4">
            <div class="acsetting">
         <h3 for="inputIsValid" class="acsettingtitle">SALES INVOICE
                     <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="salesinvoice" ></a></span>
                    </h3>
    </div>
  </div>



    <div class="col-md-4">
    <div class="acsetting">

       <h3 for="inputIsValid" class="acsettingtitle">PAYABLES
                    <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="payments" ></a></span>
                    </h3>
    </div>
  </div>
    
    <div class="col-md-4">
            <div class="acsetting">
         <h3 for="inputIsValid" class="acsettingtitle">RECEIVABLES
                    <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="receipts" ></a></span>
                    </h3>
                  
    </div>
  </div>

  </div>
</div>

</div>


        <!--Config Modal -->
<div id="config_modal" class="modal fade" role="dialog">
<div class="modal-dialog">
<!-- Modal content-->
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal">&times;</button>
<h4 class="modal-title">Settings</h4>
</div>
<div class="modal-body ">
<div id="form_body">
     <input type="hidden" name="type" class="type" value="">
  <div class="col-md-12" style="display:none;">
        <label class="col-md-6">Account Type</label>
            <select class="account_type" name="account_type">
				<option value="Gross Account"></option>
				<option value="Taxable Account"></option>
				<option value="Credit Account"></option>
				<option value="Debit Account"></option>
                                <option value="Payment Account"></option>
                                <option value="Receipt Account"></option>
            </select>
        
    </div>
    
<div class="col-md-12 purchaseinvoice commoncls" style="display:none;" >
<div class="row" style="margin-bottom:10px;">
        <label class="col-md-4">Credit Account</label>
        <div class="col-md-8">            
            <select id="credit_acc" name="pocredit_acc" class="credit_acc pocredit_acc select2" required style="width:100%;">
            </select>
        </div>
    </div>

<div class="row" style="margin-bottom:10px;">
        <label class="col-md-4">Gross Account(Debit)</label>
        <div class="col-md-8">
            <select id="debit_acc" name="grossdebit_acc" class="debit_acc grossdebit_acc select2 " required style="width:100%;">
            </select>
        </div>
    </div>
 <div class="row" style="margin-bottom:10px;">
        <label class="col-md-4">Taxable Account(Debit)</label>
        <div class="col-md-8">
            
            <select id="debit_acc" name="taxdebit_acc" class="debit_acc taxdebit_acc select2" required style="width:100%;">
            </select>
        </div>
       
    </div>
</div>
 <div class="col-md-12 salesinvoice commoncls"   style="display:none;">
<div class="row" style="margin-bottom:10px;">
        <label class="col-md-6">Debit Account</label>
        <div class="col-md-6">            
            <select id="debit_acc" name="sodebit_acc" class="debit_acc sodebit_acc select2 " required style="width:100%;">
            </select>
        </div>
    </div>

<div class="row" style="margin-bottom:10px;">
        <label class="col-md-6">Gross Account(credit)</label>
        <div class="col-md-6">
            <select id="credit_acc" name="grosscredit_acc" class="credit_acc grosscredit_acc select2" required style="width:100%;">
            </select>
        </div>
    </div>
 <div class="row" style="margin-bottom:10px;">
        <label class="col-md-6">Taxable Account(Credit)</label>
        <div class="col-md-6">
            
            <select id="credit_acc" name="taxcredit_acc" class="credit_acc taxcredit_acc select2" required style="width:100%;">
            </select>
        </div>
       
    </div>
     </div>

      
     <div class="col-md-12 payments commoncls"  style="display:none;">
<div class="row" style="margin-bottom:10px;">
        <label class="col-md-6">Debit Account</label>
        <div class="col-md-6">            
            <select id="debit_acc" name="paydebit_acc" class="debit_acc paydebit_acc select2" required style="width:100%;">
            </select>
        </div>
    </div>

 <div class="row" style="margin-bottom:10px;">
        <label class="col-md-6">Credit Account</label>
        <div class="col-md-6">
            
            <select id="credit_acc" name="paycredit_acc" class="credit_acc paycredit_acc select2" required style="width:100%;">
            </select>
        </div>
       
    </div>
     </div>

     
     <div class="col-md-12 receipts commoncls"  style="display:none;">
<div class="row" style="margin-bottom:10px;">
        <label class="col-md-6">Debit Account</label>
        <div class="col-md-6">            
            <select id="debit_acc" name="rcptdebit_acc" class="rcptdebit_acc debit_acc select2" required style="width:100%;">
            </select>
        </div>
    </div>

 <div class="row" style="margin-bottom:10px;">
        <label class="col-md-6">Credit Account</label>
        <div class="col-md-6">
            
            <select id="credit_acc" name="rcptcredit_acc" class="credit_acc rcptcredit_acc select2 " required style="width:100%;">
            </select>
        </div>
       
    </div>
     </div>

     
</div>
</div>
    <div align="center">
        <button type="submit" class="btn save">OK</button>
    </div>
</div>
 </div>
</div>
 <!--Config Modal -->




<script type="text/javascript">
$( document ).ready(function() {

     $(".credit_acc").jCombo("{{ URL::to('jcomboform?table=f_account_structure_t:f_account_structure_id:concatenated_segments') }}&order_by=concatenated_segments asc",
     {selected_value:""});
     $(".debit_acc").jCombo("{{ URL::to('jcomboform?table=f_account_structure_t:f_account_structure_id:concatenated_segments') }}&order_by=concatenated_segments asc",
     {selected_value:""});

$('.config').click(function(e) {
        $('#config_modal').modal('show');
        var type=$(this).data("value");
        $('.type').val(type);
         $('.commoncls').css("display","none");
        $('.'+type).css("display","block");
      
        if(type=="purchaseinvoice"){
              $('.purchaseinvoice').prop('disabled', false);
              $('.salesinvoice').prop('disabled', 'disabled');
              $('.payments').prop('disabled', 'disabled');
              $('.receipts').prop('disabled', 'disabled');
        }
        else if(type=="salesinvoice"){
              $('.salesinvoice').prop('disabled', false);
              $('.purchaseinvoice').prop('disabled', 'disabled');
              $('.payments').prop('disabled', 'disabled');
              $('.receipts').prop('disabled', 'disabled');
        }
        else if(type=="purchaseinvoice"){
              $('.payments').prop('disabled', false);
              $('.salesinvoice').prop('disabled', 'disabled');
              $('.purchaseinvoice').prop('disabled', 'disabled');
              $('.receipts').prop('disabled', 'disabled');

        }
        else{
            $('.receipts').prop('disabled', false);
              $('.salesinvoice').prop('disabled', 'disabled');
              $('.payments').prop('disabled', 'disabled');
              $('.purchaseinvoice').prop('disabled', 'disabled'); 
        }
       
          // alert(type);
       
        var url = "{{ URL::to('getaccounts') }}/"+type;
        $.get(url,function(data){
            //console.log(data);
                  $('.grossdebit_acc').val(data.Gross_Account).change();
                  $('.taxdebit_acc').val(data.Taxable_Account).change();
                  $('.pocredit_acc').val(data.Credit_Account).change();
                  
                  $('.grosscredit_acc').val(data.soGross_Account).change();
                  $('.taxcredit_acc').val(data.soTaxable_Account).change();
                  $('.sodebit_acc').val(data.soDebit_Account).change();

                  
                  $('.paydebit_acc').val(data.paydebit_Account).change();
                  $('.paycredit_acc').val(data.paycredit_Account).change();

                  $('.rcptdebit_acc').val(data.rcptDebit_Account).change();
                  $('.rcptcredit_acc').val(data.rcptCredit_Account).change();
                  

    });
    
});



});
    </script>

    <style>
  input[type=checkbox]{
  /* Double-sized Checkboxes */
  -webkit-transform: scale(2); /* Safari and Chrome */
  padding: 10px;
}
.checkboxtext
{
  /* Checkbox text */
  font-size: 60%;
  display: inline;
}


</style>
@endsection
