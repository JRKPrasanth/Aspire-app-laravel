@extends('layouts.header')
@section('content')
<?php error_reporting(0);?>
<style type="text/css">
  .productsetting{
    margin: 10px 0;
    padding: 10px;
    background-color: #fff;
    box-shadow: 0 4px 10px 0 rgba(0,0,0,0.2), 0 4px 20px 0 rgba(0,0,0,0.19);
  }

 

.productsettingtitle {
    font-size: 13px;
    color: #000;
    letter-spacing: 1px;
    height: 30px;
    line-height: 30px;
    margin: 0;
    font-family: 'Prompt', sans-serif;
    transition: all 0.3s ease 0s;
}
.ui_close_btn {
    border: 1px dashed #455986;
    padding: 10px;
    float: right;
    color: #fff;
    background: #455986;
    cursor: pointer;
}
.select2-container{
  height: auto !important ;
}

.select2-selection__rendered {
 
  font-size: 11px !important;
}




.select2-container--default .select2-selection--multiple{
  border:none !important ;
}
.select2-container .select2-selection--multiple {
    box-sizing: border-box !important ;
    cursor: pointer !important ;
    display: block !important ;
    min-height: 27px !important ;
    user-select: none !important;
    -webkit-user-select: none !important ;
}
.select2-container--default.select2-container--focus .select2-selection--multiple{
   border: none !important;

}
</style>
 
<h2 class="heads">Product Setting</h2>
<form  action=""  id="save" >

    <!--<form method="post" action="{{ URL::to('productsettingsave') }}" id="coloumnpermission" class="productsetting_form" data-parsley-validate>-->
{{ csrf_field() }}
<input type="hidden" value="" name="savestatus" id="savestatus" />
<div class="card">

<div class="card-body card-block">
  <div class="row">
       <!-- <div class="col-md-4">
        <div class="productsetting">
    <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-8">PURCHASE ENQUIRY</label>
                    <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="purchaseenquiry" ></a></span>
    </div>
  </div>
    </div> -->

   <div class="col-md-4">
    <div class="productsetting">
                    <h3 for="inputIsValid" class="productsettingtitle">PURCHASE ENQUIRY
                    <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="purchaseenquiry"></a></span>
                    </h3>
    </div>
    </div>




       <div class="col-md-4">
            <div class="productsetting">
      <h3 for="inputIsValid" class="productsettingtitle">PURCHASE REQUISITION
                         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="purchaserequisition" ></a></span>
                         </h3>
    </div>
  </div>
    <div class="col-md-4">
    <div class="productsetting">
       <h3 for="inputIsValid" class="productsettingtitle">PURCHASE QUOTATION
                         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="purchasequotation" ></a></span>
                       </h3>
    </div>
  </div>
    <div class="col-md-4">
            <div class="productsetting">
      <h3 for="inputIsValid" class="productsettingtitle">PURCHASE COMMITMENT
                         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="purchasecommitment" ></a></span></h3>
    </div>
  </div>
    <div class="col-md-4">
            <div class="productsetting">
     <h3 for="inputIsValid" class="productsettingtitle">PURCHASE ORDER
                         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="purchaseorder" ></a></span></h3>
    </div>
  </div>
      
        <div class="col-md-4">
            <div class="productsetting">
      <h3 for="inputIsValid" class="productsettingtitle">GIN
                         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="goodsinwardnote" ></a></span>
                         </h3>
    </div>
  </div>
        <div class="col-md-4">
            <div class="productsetting">
      <h3 for="inputIsValid" class="productsettingtitle">GRN
                         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="grn" ></a></span></h3>
    </div>
  </div>
   <div class="col-md-4">
            <div class="productsetting">
      <h3 for="inputIsValid" class="productsettingtitle">QUALITY CHECK
                         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="purchaseqc" ></a></span>
                         </h3>
    </div>
  </div>    	
 <div class="col-md-4">
            <div class="productsetting">
      <h3 for="inputIsValid" class="productsettingtitle">PURCHASE INVOICE
                         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="purchaseinvoice" ></a></span>
                         </h3>
    </div>
  </div> 
       <div class="col-md-4">
            <div class="productsetting">
      <h3 for="inputIsValid" class="productsettingtitle">PURCHASE RETURN
                         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="purchasereturn" ></a></span>
                         </h3>
    </div>
  </div>
  <div class="col-md-4">
    <div class="productsetting">
      <h3 for="inputIsValid" class="productsettingtitle">SALES ENQUIRY
         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="salesinquiry" ></a></span>
         </h3>
    </div>
      </div>
      <div class="col-md-4">
      <div class="productsetting">
      <h3 for="inputIsValid" class="productsettingtitle">SALES QUOTE
         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="soquote" ></a></span>
         </h3>
     </div>
     </div>
	  <div class="col-md-4">
      <div class="productsetting">
      <h3 for="inputIsValid" class="productsettingtitle">SALES ORDER
         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="soorder" ></a></span>
         </h3>
     </div>
     </div>
      <div class="col-md-4">
      <div class="productsetting">
      <h3 for="inputIsValid" class="productsettingtitle">SALES INVOICE
         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="salesinvoice" ></a></span>
         </h3>
     </div>
     </div>
       <div class="col-md-4">
      <div class="productsetting">
      <h3 for="inputIsValid" class="productsettingtitle">PICKORDER</label>
         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="pickorder" ></a></span>
     </div>
     </div>
       <div class="col-md-4">
      <div class="productsetting">
      <h3 for="inputIsValid" class="productsettingtitle">DISPATCH
         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="dispatch" ></a></span>
         </h3>
     </div>
     </div>
       <div class="col-md-4">
      <div class="productsetting">
      <h3 for="inputIsValid" class="productsettingtitle">BOM EQUIPMENTS
         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="bomequipments" ></a></span>
         </h3>
     </div>
     </div>
       <div class="col-md-4">
      <div class="productsetting">
      <h3 for="inputIsValid" class="productsettingtitle">MATERIAL BOM
         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="materialbom" ></a></span>
         </h3>
     </div>
     </div>
       <div class="col-md-4">
      <div class="productsetting">
      <h3 for="inputIsValid" class="productsettingtitle">WORKORDER
         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="workorder" ></a></span>
         </h3>
     </div>
     </div>
     
      
     <div class="col-md-4">
      <div class="productsetting">
      <h3 for="inputIsValid" class="productsettingtitle">QA SUBMIT STAGE
         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="qasubmitstage" ></a></span>
         </h3>
     </div>
     </div>

     <div class="col-md-4">
      <div class="productsetting">
      <h3 for="inputIsValid" class="productsettingtitle">PURCHASE PRICELIST
         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="purchasepricelist" ></a></span>
         </h3>
     </div>
     </div>

     <div class="col-md-4">
      <div class="productsetting">
      <h3 for="inputIsValid" class="productsettingtitle">SALES PRICELIST
         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="salespricelist" ></a></span></h3>
     </div>
     </div>
	  <div class="col-md-4">
      <div class="productsetting">
      <h3 for="inputIsValid" class="productsettingtitle">QUALITY INDENT
         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="qualityindent" ></a></span></h3>
     </div>
     </div>
  <div class="col-md-4">
      <div class="productsetting">
      <h3 for="inputIsValid" class="productsettingtitle">JOBWORKOUT ORDER
         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="jobworkoutorder" ></a></span></h3>
     </div>
     </div>
<div class="col-md-4">
      <div class="productsetting">
      <h3 for="inputIsValid" class="productsettingtitle">CLOSE TICKET
         <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="closerequest" ></a></span></h3>
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
<div id="form_body">

    <div class="row">
     <div class="col-md-12"> 
    <label class="form-control-label col-md-5" for="product_group_id"><span style="color:red">*</span>Product Group Name</label>
    <div class="col-md-7">
            <select multiple="multiple" name='product_group_id[]' class='product_group_id select2' id="product_group_id" readonly style="width:100%;">
                {!! $product_group_id !!}
            </select>
    </div>
  </div>
</div>
    <div class="row">
     <div class="col-md-12"> 
    <label class="form-control-label col-md-5" for="select_option"><span style="color:red">*</span>Select Option</label>
    <div class="col-md-7">
        <select multiple="multiple" name="select_option[]" id="select_option" class="select2 select_option" style="width:100%;">
	<option value="">--Please Select--</option>
	<option <?php if($row->select_option =="product_code") { echo "selected"; } else { echo ""; } ?> value="product_code">PRODUCT CODE</option>
        <option <?php if($row->select_option =="concatenated_product") { echo "selected"; } else { echo ""; } ?> value="concatenated_product">PRODUCT NAME</option>
        </select>
            
    </div>
  </div>
</div>
    
      <input type="hidden" name="type" class="type" value="">
</div>
</div>
    <div align="center">
        <!--<button type="submit" class="btn save saveform">OK</button>-->
        <button name="submit" type="button" class="btn save " value="SAVE">OK</button>
    </div>
</div>
 </div>
</div>
 <!--Config Modal -->
</form>









<script type="text/javascript">
$( document ).ready(function() {
//$(".product_group_id").jCombo("{{ URL::to('jcomboform?table=m_product_groups_t:product_group_id:group_name')}}",
//{selected_value:"{{$value->product_group_id}}"});
     $('#savestatus').val('');
    $('.config').click(function(e) {
        var type=$(this).data('value');
         $('.type').val(type);
        $.get('getproductsetting?type='+type,function(data){
            console.log(data);
           if (data != 0) {
                var grouparray=data[0].product_group_id.split(",");
                var grouparray1=data[0].select_option.split(",");
		$('.product_group_id').val(grouparray);
                $('.select_option').val(grouparray1);
		$('.product_group_id').trigger('change.select2');
                $('.select_option').trigger('change.select2');
            }
        });
         $('#config_modal').modal('show');
    });
    
  $(document).on('click','.save',function(e){
                e.preventDefault();
                var data;
                data = $("#save").serialize();
                $.post('productsettingsave', data, function(data)
                {
                    if(data == 1){
                      notyMsg('success',"Inserted Successfully !!");
                        //location.reload();
                    }
                    else if(data == 2)
                    {
                        notyMsg('updated  successfully');
                        //location.reload();
                    }
                });
               $('#config_modal').modal('hide');

            });


});
    </script>
@endsection
