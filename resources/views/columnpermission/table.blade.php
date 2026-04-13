@extends('layouts.header')
@section('content')

<style type="text/css">
  
  .form-group {
    padding: 0px 4px;
}
label {    padding: 10px 0 !important;}
.modal-body{
    height: 250px;
    overflow-y: auto;
}
#config_modal .form-group{
	border:none;
}
#config_modal select{
	border-radius: 5px;
	border:1px solid #445985;
}
</style>


<?php error_reporting(0);?>


<h2 class="heads">Column Permission</h2>

<form method="post" action="{{ URL::to('coloumnsave') }}" id="coloumnpermission" data-parsley-validate>
{{ csrf_field() }}
<div class="card">

<div class="card-body card-block">
  <div class="row">
        
    <div class="col-md-4">
    <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-8">PURCHASE ENQUIRY</label>
                    <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="purchaseenquiry" ></a></span>
    </div>
    </div>
    <div class="col-md-4">
    <div class="form-group row">
      <label for="inputIsValid" class="form-control-label col-md-8">PURCHASE QUOTATION</label>
                         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="purchasequotation" ></a></span>
    </div>
  </div>
    <div class="col-md-4">
            <div class="form-group row">
      <label for="inputIsValid" class="form-control-label col-md-8">PURCHASE REQUISITION</label>
                         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="purchaserequisition" ></a></span>
    </div>
  </div>
    <div class="col-md-4">
            <div class="form-group row">
      <label for="inputIsValid" class="form-control-label col-md-8">PURCHASE ORDER</label>
                         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="purchaseorder" ></a></span>
    </div>
  </div>
      <div class="col-md-4">
            <div class="form-group row">
      <label for="inputIsValid" class="form-control-label col-md-8">PURCHASE INVOICE</label>
                         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="purchaseinvoice" ></a></span>
    </div>
  </div>
    <div class="col-md-4">
    <div class="form-group row">
      <label for="inputIsValid" class="form-control-label col-md-8">FREIGHT CARRIER</label>
                         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="freightcarriershdr" ></a></span>
    </div>
  </div>
    <div class="col-md-4">
    <div class="form-group row">
      <label for="inputIsValid" class="form-control-label col-md-8">DISCOUNTS</label>
         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="ardiscountshdr" ></a></span>
    </div>
  </div>
    <div class="col-md-4">
    <div class="form-group row">
      <label for="inputIsValid" class="form-control-label col-md-8">Supplier</label>
         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="supplier" ></a></span>
    </div>
  </div>
    <div class="col-md-4">
    <div class="form-group row">
      <label for="inputIsValid" class="form-control-label col-md-8">Customer</label>
         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="customers" ></a></span>
    </div>
  </div>

    <div class="col-md-4">
    <div class="form-group row">
      <label for="inputIsValid" class="form-control-label col-md-8">Sales Order</label>
         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="salesorder" ></a></span>
    </div>
  </div>
    <div class="col-md-4">
    <div class="form-group row">
      <label for="inputIsValid" class="form-control-label col-md-8">Create Sales Enquiry</label>
         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="salesinquiry" ></a></span>
    </div>
      </div>
      <div class="col-md-4">
      <div class="form-group row">
      <label for="inputIsValid" class="form-control-label col-md-8">Create Sales Invoice</label>
         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="salesinvoice" ></a></span>
    </div>
  </div>
    <div class="col-md-4">
      <div class="form-group row">
      <label for="inputIsValid" class="form-control-label col-md-8">Create Sales Quote</label>
         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="soquote" ></a></span>
    </div>
  </div>
    <div class="col-md-4">
      <div class="form-group row">
      <label for="inputIsValid" class="form-control-label col-md-8">Create Material Bom</label>
         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="materialbom" ></a></span>
    </div>
  </div>
  <div class="col-md-4">
      <div class="form-group row">
      <label for="inputIsValid" class="form-control-label col-md-8">Pick Order</label>
         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="pickorder" ></a></span>
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
<h4 class="modal-title">Configuration</h4>
</div>
<div class="modal-body ">
	<div class="row">
		<div class="col-md-12">
<div id="form_body">
   
   
        
</div>
</div>
</div>
</div>
    <div align="center">        
        <button type="submit" class="btn save ok">OK</button>        
    </div>
</div>
 </div>
</div>
 <!--Config Modal -->







<script type="text/javascript">
$( document ).ready(function() {

$('.config').click(function(e) {
    var type=$(this).data('value')
	//alert(type);
    $.get('getcolumns?type='+type,function(data){
        var html='';
      $.each(data,function(index,val){
        var check='';  
        if(val.active=="1")
          {
              check='checked';
          }
          else
          {
              check='';
          }
       //Required selected    
          var actionselect='';
          var actionselectn='';
          if(val.action =="1")
          {
              var actionselect ='selected';
          }
          else
          {
              var actionselect ='';
          }
          if(val.action =="2")
          {
              var actionselectn ='selected';
          }
          else
          {
              var actionselectn ='';
          }
          //End
         html+="<div class='col-md-6'><div class='form-group col-md-12'><input   type='checkbox' class='checkboxtext' name='columns[]' value='"+val.column_name+"' "+check +">&nbsp&nbsp&nbsp"+val.label_name+"&nbsp&nbsp&nbsp </div></div>"+"<div class='col-md-6'><div class='form-group col-md-12'><select name='required["+val.column_name+"]'><option value=''>--Please Select--</option><option value=1 "+actionselect +" >Required</option><option value=2 "+actionselectn +" >Not Required</option></select></div></div>"; 
      });
      html+="<input type='hidden' name='type' value='"+type+"'>";
      $("#form_body").html(html);
    });
     $('#config_modal').modal('show');
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
