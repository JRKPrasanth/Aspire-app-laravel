@extends('layouts.header')
@section('content')

<?php include('tools_menu.php');?>

<style>

@media only screen and (min-width: 1500px){
.bulk_line_no{
    width: 60px !important;
}
.bulk_product_id{
    width: 300px !important;
}
.bulk_product_group_id{
    width: 140px !important;
}
.bulk_from_loc{
    width: 140px !important;
}
.bulk_qoh{
    width: 80px !important;
}
.bulk_from_transfer_qty{
    width: 80px !important;
}
.bulk_to_transfer_qty{
    width: 80px !important;
}
.bulk_batch_no{
    width: 140px !important;
}
    .bulk_manufacture_date{
    width: 100px;
}
.bulk_product_expire_date{
    width: 100px;
}
}

.bulk_line_no{
    width: 50px;
}
.bulk_product_id{
    width: 260px;
}
.bulk_product_group_id{
    width: 150px;
}
.bulk_from_loc{
    width: 150px;
}
.bulk_qoh{
    width: 80px;
}
.bulk_batch_no{
    width: 150px;
}
    .bulk_manufacture_date{
    width: 100px;
}
.bulk_product_expire_date{
    width: 100px;
}
.bulk_from_transfer_qty{
    width: 80px;
}
.bulk_to_transfer_qty{
    width: 80px;
}
.panel,.card{
    margin-bottom:220px
}
/* modal jqgrid popup styles  */
.modal-open .ui-jqgrid .ui-jqgrid-pager {
    border-left: 0 none !important;
    border-right: 0 none !important;
    border-bottom: 0 none !important;
    border-top: 0 none;
    margin: 0 !important;
    padding: 0 !important;
    position: relative;
    height: auto;
    min-height: 28px;
    white-space: nowrap;
    overflow: hidden;
    /*font-size:11px; */
    z-index: 100
}

.modal-open .ui-jqgrid .ui-jqgrid-toppager .ui-pager-control,.modal-open 
.ui-jqgrid .ui-jqgrid-pager .ui-pager-control {
    position: relative;
    border-left: 0;
    border-bottom: 0;
    border-top: 0;
    height: 28px;
}

.modal-open .ui-jqgrid .ui-pg-table {
    position: relative;
    padding: 1px 0;
    width: auto;
    margin: 0;
}

.modal-open .ui-jqgrid .ui-pg-table td {
  width: auto !important;
    font-weight: normal;
    vertical-align: middle;
    padding: 0px 1px;
}

.modal-open .ui-jqgrid .ui-pg-button {
    height: auto
}

.modal-open .ui-jqgrid .ui-pg-button span {
    display: block;
    margin: 2px;
    float: left;
}

.modal-open .ui-jqgrid .ui-pg-button:hover {
    padding: 0;
}

.modal-open .ui-jqgrid .ui-state-disabled:hover {
    padding: 0px;
}

.modal-open .ui-jqgrid .ui-pg-input,
.modal-open .ui-jqgrid .ui-jqgrid-toppager .ui-pg-input {
    height: 14px;
    width: auto;
    font-size: .9em;
    margin: 0;
    line-height: inherit;
    border: none;
    padding: 3px 2px
}

.modal-open .ui-jqgrid .ui-pg-selbox,
.modal-open .ui-jqgrid .ui-jqgrid-toppager .ui-pg-selbox {
    font-size: .9em;
    line-height: inherit;
    display: block;
    
    margin: 0;
    padding: 3px 0px;
    border: none;
}

.modal-open .ui-jqgrid .ui-separator {
    height: 18px;
    border-left: 2px solid #ccc;
}

.modal-open .ui-separator-li {
    height: 2px;
    border: none;
    border-top: 2px solid #ccc;
    margin: 0;
    padding: 0;
    width: 100%
}

.modal-open .ui-jqgrid .dropdownmenu {
    padding: 3px 0 3px 0;
    margin-left: 4px;
}

.modal-open .ui-jqgrid .ui-jqgrid-pager .ui-pg-div,
.modal-open .ui-jqgrid .ui-jqgrid-toppager .ui-pg-div {
    padding: 1px 0;
    float: left;
    position: relative;
    line-height: 20px;
}

.modal-open .ui-jqgrid .ui-jqgrid-pager .ui-pg-button,
.modal-open .ui-jqgrid .ui-jqgrid-toppager .ui-pg-button {
    cursor: pointer;
}

.modal-open .ui-jqgrid .ui-jqgrid-pager .ui-pg-div span.ui-icon,
.modal-open .ui-jqgrid .ui-jqgrid-toppager .ui-pg-div span.ui-icon {
    float: left;
    margin: 0px;
    width: 18px;
}

.modal-open .ui-jqgrid td input,
.modal-open .ui-jqgrid td select,
.modal-open .ui-jqgrid td textarea {
    margin: 0;
    padding-top: 5px;
    padding-bottom: 5px;
}

.modal-open .ui-jqgrid td textarea {
    width: auto;
    height: auto;
}

.modal-open .ui-jqgrid .ui-jqgrid-toppager {
    width: 100% !important;
    border-left: 0 none !important;
    border-right: 0 none !important;
    border-top: 0 none !important;
    margin: 0 !important;
    padding: 0 !important;
    position: relative;
    white-space: nowrap;
    overflow: hidden;
}

.modal-open .ui-jqgrid .ui-jqgrid-pager .ui-pager-table,
.modal-open .ui-jqgrid .ui-jqgrid-toppager .ui-pager-table {
    width: 100%;
    margin-top: 1px;
    table-layout: fixed;
    /*height: 100%;*/
}

.modal-open .ui-jqgrid .ui-jqgrid-pager .ui-paging-info,
.modal-open .ui-jqgrid .ui-jqgrid-toppager .ui-paging-info {
    font-weight: normal;
    height: auto;
    margin-top: 3px;
    margin-right: 4px;
    display: inline;
}

.modal-open .ui-jqgrid .ui-jqgrid-pager .ui-paging-pager,
.modal-open .ui-jqgrid .ui-jqgrid-toppager .ui-paging-pager {
    table-layout: auto;
    height: 100%;
}

.modal-open .ui-jqgrid .ui-jqgrid-pager .navtable,
.modal-open .ui-jqgrid .ui-jqgrid-toppager .navtable {
    float: left;
    table-layout: auto;
    /*height: 100%;*/
}
@media only screen and (min-width: 1700px) {
.modal-open .ui-jqgrid .ui-jqgrid-pager .ui-paging-info,
.modal-open .ui-jqgrid .ui-jqgrid-toppager .ui-paging-info {
    font-weight: normal;
    height: auto;
    margin-top: 3px;
    margin-right: -25em;
    display: inline;
}
}
</style>






<form  method="post" action="" id="transfer_form" data-parsley-validate>
{{ csrf_field() }}

 <div class="ajaxLoading"></div>
<h2 class="heads"> Subinventory Transfer </h2>


<div class="card">


<div class="card-body card-block ">
<div class="row">
    <div class="col-md-12">


        <!------------------------------------- Body content start here ---------------------------->
        
    
    <div>
        <div class="row">
     <div class="col-md-4">
            <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-5">Subinventory Transfer No</label>
            <div class="col-md-6">
                <input type="text" id="subtransfer_no" name="subtransfer_no" class="form-control subtransfer_no" width="100%" value="{{ $row->subtransfer_no }}" readonly >
            </div>
        </div>
         <div class="form-group row">
             <label for="inputIsValid" class="form-control-label col-md-5">Trx Date</label>
             <div class="col-md-6">
             <div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
             <input class="form-control datepicker trx_date" id="trx_date" name="trx_date" size="16" type="text" value="{{$form_date}}" readonly="true">
             <!-- <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> -->
             </div>
             </div>

         </div>

        
               
             
            
         
     </div>
            
     <div class="col-md-4">
         
             <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-5"><span style="color: red;">&#42;</span> From SubInventory</label>
                    <div class="col-md-6">
                        <select name='frm_subinv_id' rows='5' class='form-control frm_subinv_id select2' id='frm_subinv_id' required style="width:100%;">
                            {!! $subinv_id !!}
                        </select>
                    </div>
                    <div class="col-md-1 showinline">
                        <span class="showspan"> <i class="fa fa-refresh jcr_frm_subinv_id"></i></span>
           </div>
                </div>

     <div class="form-group row">
             <label for="inputIsValid" class="form-control-label col-md-5"><span style="color: red;">*</span>To SubInventory</label>
             <div class="col-md-6">
                 <select name='to_subinv_id' rows='5' class='form-control to_subinv_id select2' id='to_subinv_id' required style="width:100%;">
                                     {!! $subinv_id !!}
                            </select>
             </div>
            <div class="col-md-1 showinline">
                        <span class="showspan"> <i class="fa fa-refresh jcr_to_subinv_id"></i></span>
                    </div>
         </div>

        
         

     </div>
     <div class="col-md-4">
                 <div class="form-group row">
                   <label for="active" class="form-control-label col-md-5">Created By</label>
                   <div class="col-md-6" style="pointer-events:none;">
                       <select name='created_by' rows='5' class='select2 created_by' id="created_by" >
                       {!! $created_by !!}
                       </select>
                   </div>
               </div>
        
        </div>
                 
      <!--   <div class="col-md-4">
            
            <div class="form-group row">
             <label for="inputIsValid" class="form-control-label col-md-5"><span style="color: red;">&#42;</span>To Locator</label>
             <div class="col-md-6 to_loc_iddiv sel2">
                            <select name='to_loc_id' rows='5' class='form-control to_loc_id select2' required style="width:100%;">
                                {!! $loc_id !!}
                            </select>
             </div>
            <div class="col-md-1 showinline">
                            <span class="showspan"> <i class="fa fa-refresh jcr_to_loc_id"></i></span>
                        </div>
         </div>
            
            
             
        </div> -->
     <div class="row text-center">
         <div class="col-md-12">
         <div class="form-group ">
                    
<!--//           <input  type="submit" class="btn save saveform" value="Trading">-->
                                 
                         <!--<input  type="submit" class="btn save saveform" value="APPROVAL">-->
              <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <input type="hidden" name="submit_type" class="submit_type" value="" />
                                <div class="form-group text-center actionbtn">
                                    
                                     <?php if($return_url == 'subinventorytransfers')
                    { ?>
                                    <button name="submit" type="button" class="btn save saveform trading" value="SAVE">Trading</button>
                                  <?php  } else
                        { ?>  
                                    <button name="submit" type="button" class="btn save saveform" value="APPROVAL">APPROVAL</button>
                                      <?php }
                       ?>
                                </div>
                            </div>
                        </div>         
         </div>
     </div>
     </div>
    </div>
    </div>



        <!--***********************************************************-->

    </div>
</div>







<div class="row">
    <div class="col-md-12" style="padding-top: 8px;">
<a href="javascript:void(0);"  class="add_row additem"  rel=".rcopy"><i class="fa fa-plus"></i> New Item</a>

<!-------------------------Linedata -------------------------------->

    <div id="preview-area" class="chandru">
    <table class="overflow-y preview subinvtrns_table">
        <thead>
            <tr>
                <th >Line No</th>
                <th class="pdtdiv" >Product</th>
                
                <th class="pdtsearch_div"></th>
                
                <th>Product Group</th>
                <th >From Locator</th>
                 <th>Batch Number</th>
                 <th>Manufacturer Date</th>
                 <th>Product Expire Date</th>
                <th>Qoh</th>
                <th> Transfer Qty</th>
                <th style="width:33px;"><p style="width:28px;">&nbsp;</p></th>
            </tr>
        </thead>
            <tbody class="subintrnsfer_lines_body">

            <?php if(count($linedata) < 1 ) { ?>
            <tr class="clone rcopy">
            <td></td> 
                    <td>
                            <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="1" readonly="readonly" >
                            <input type="hidden" name="bulk_status[]"  value="INITIATED">
                    </td>
                    <td class="product_sel2"><select name="bulk_product_id[]" class="form-control bulk_product_id  parsley-validated select2" required="required">{!! $product_id !!}</select></td>
                   
                    <td class="pdtsearch_div"><i class="fa fa-search productsearch"></i></td>
                    
                    <td class="grp_div">
                        <select name="bulk_product_group_id[]" id="bulk_product_group_id" class="form-control bulk_product_group_id select2" readonly >
                            {!! $product_group_id !!}
                        </select>
                    </td>
                      <td>
                        <select name="bulk_from_loc[]" id="bulk_from_loc" class="form-control bulk_from_loc parsley-validated select2" required="required">
                
                        </select>
                    </td>
                        <td>
                        <select name="bulk_batch_no[]" id="bulk_batch_no" class="form-control bulk_batch_no parsley-validated select2" required="required">
                
                        </select>
                    </td>
                 <td >
                        <input type="text" name="bulk_manufacture_date[]" class="form-control input-sm bulk_manufacture_date input_qty_width" value="" readonly >
                    </td>
                 <td >
                        <input type="text" name="bulk_product_expire_date[]" class="form-control input-sm bulk_product_expire_date input_qty_width datepicker" value="" readonly >
                    </td>
                    <td >
                        <input type="text" name="bulk_qoh[]" class="form-control input-sm bulk_qoh input_qty_width" value="" readonly >
                    </td>
                    <td >
                    <input type="text" name="bulk_from_transfer_qty[]" class="form-control input-sm bulk_from_transfer_qty" value="" required="required" >
                    </td>
            <!--<td >
                    <input type="text" name="bulk_to_transfer_qty[]" class="form-control input-sm bulk_to_transfer_qty" value="" readonly >
            </td>-->
            <td style="width:33px;">
            <a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
            <input type="hidden" name="counter[]">
            </td>
            </tr>
                    <?php } ?>
            </tbody>
</table>
<input type="hidden" name="enable-masterdetail" value="true">
</div>
</div>
</div>
</div>
<!-------------------------Linedata End-------------------------------->
</div>

    <!-- karthigaa purpose Product search jqgrid model-->
            <div class="modal fade" id="productModal">
                <div class="modal-dialog" style="width:80%;">
                    <div class="modal-content">
                            <!--Moda Header-->
                                <div class="modal-header">
                                    <h4 class="modal-title"> Product Details </h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                            <!-- Modal Body -->
                                <div class="modal-body">
                                    <table id="productgrid"></table>
                                </div>
                            <!-- Modal footer -->
                            <div class="modal-footer">
                            </div>
                    </div>
                </div>
            </div>
    <!--end-->


<input type="hidden" class="pdtindex" value="" />
<input type="hidden" class="frominventory" value="" />
    

    </form>

<!--<script src="<?php //echo asset('js/plugins/parsley/dist/parsley.js')?>"></script>-->
<script>



$(document).ready(function(){
    
        $('.frm_loc_iddiv,.to_loc_iddiv,.grp_div').css('pointer-events','none');
    
            /**********Up/down/left/right arrow navigation start*******/
        $('input,select').keyup(function (e) {
            if (e.which == 39) { // right arrow
              $(this).closest('td').next().find('input,select').focus();
     
            } else if (e.which == 37) { // left arrow
              $(this).closest('td').prev().find('input,select').focus();
     
            } else if (e.which == 40) { // down arrow
              $(this).closest('tr').next().find('td:eq(' + $(this).closest('td').index() + ')').find('input,select').focus();
     
            } else if (e.which == 38) { // up arrow
              $(this).closest('tr').prev().find('td:eq(' + $(this).closest('td').index() + ')').find('input,select').focus();
            }
            });
            /**********Up/down/left/right arrow navigation end *******/
    
    $(document).on('change','.frm_subinv_id',function(){
        var subinv=$('.frm_subinv_id').select2('val'); 
        if(subinv=='0')
        {
            $('.frm_loc_iddiv').css('pointer-events','none');
        }
        else
        { 

            $('.frm_loc_iddiv').css('pointer-events','');
        } 
    });
    
    $(document).on('change','.to_subinv_id',function(){
        var subinv=$('.to_subinv_id').select2('val'); 
        if(subinv=='0')
        {
            $('.to_loc_iddiv').css('pointer-events','none');
        }
        else
        { 

            $('.to_loc_iddiv').css('pointer-events','');
        } 
    });
    
    
    $('.trx_datediv').css('pointer-events','none');
    

    
    $(".jcr_frm_subinv_id").click(function()
        {
        $(".frm_subinv_id").jCombo("{{ URL::to('jcomboform?table=m_subinventory_t:subinventory_id:subinventory_name')}}&order_by=subinventory_name asc",
                {selected_value:""});
        $(".frm_loc_id").jCombo("{{ URL::to('jcomboform?table=m_sublocators_t:sublocator_id:locator_code')}}&order_by=locator_code asc",
                {selected_value:""});
    });


    $(".jcr_frm_loc_id").click(function()
        {
        $(".frm_loc_id").jCombo("{{ URL::to('jcomboform?table=m_sublocators_t:sublocator_id:locator_code')}}&order_by=locator_code asc",
                {selected_value:""});
    });

    
    $(".jcr_to_subinv_id").click(function(){ 
        $(".to_subinv_id").jCombo("{{ URL::to('jcomboform?table=m_subinventory_t:subinventory_id:subinventory_name')}}&order_by=subinventory_name asc",
                {selected_value:""});
        $(".to_loc_id").jCombo("{{ URL::to('jcomboform?table=m_sublocators_t:sublocator_id:locator_code')}}&order_by=locator_code asc",
                {selected_value:""});
    });


    $(".jcr_to_loc_id").click(function(){
        $(".to_loc_id").jCombo("{{ URL::to('jcomboform?table=m_sublocators_t:sublocator_id:locator_code')}}&order_by=locator_code asc",
                {selected_value:""});
    });
    
    $(document).on('change','.frm_subinv_id',function()
    {
        $(".frm_loc_id").css("pointer-events","auto");
        var frm_subinv_id = $(this).val();
        if(frm_subinv_id)
        {
            $(".frm_loc_id").jCombo("{{ URL::to('jcomboform?table=m_sublocators_t:sublocator_id:locator_code') }}&parent=subinventory_id="+frm_subinv_id+"&order_by=locator_code asc",
            {selected_value:""});

            $(".bulk_product_id").jCombo("{{ URL::to('jcomboformcompwithref?table=i_qoh_detail_t:product_id:concatenated_product') }}&parent=subinventory_id="+frm_subinv_id+" and qoh_trx_qty>0&order_by=product_id asc",
            {selected_value:""});
        }
    });

    $(document).on('change','.to_subinv_id',function()
    {
        $(".to_loc_id").css("pointer-events","auto");
        var to_subinv_id=$(this).val();
        if(to_subinv_id){
            $(".to_loc_id").jCombo("{{ URL::to('jcomboform?table=m_sublocators_t:sublocator_id:locator_code') }}&parent=subinventory_id="+to_subinv_id+"&order_by=locator_code asc",
            {selected_value:""});
        }
    });
        
        $('.product_sel2').css('pointer-events','none'); 
        $(document).on('change','.frm_subinv_id',function()
        {
            var frm_loc_id = $('.frm_subinv_id').select2('val');
            // alert(frm_loc_id);
            if(frm_loc_id != '')
            {
                $('.product_sel2').css('pointer-events',''); 
            }
            else
            {
                $('.product_sel2').css('pointer-events','none');   
            } 
        });
        

    // $(".frm_subinv_id").jCombo("{{ URL::to('jcomboform?table=m_subinventory_t:subinventory_id:subinventory_name') }}&order_by=subinventory_name asc",
 //                {selected_value:""});
            
    
    // $(".to_subinv_id").jCombo("{{ URL::to('jcomboform?table=m_subinventory_t:subinventory_id:subinventory_name') }}&order_by=subinventory_name asc",
    // {selected_value:""});


 /*Karthigaa Purpose For Save Function*/      
      
    $(document).on('click', '.saveform', function() {
        var btnval = $(this).val();
        $('#savestatus').val(btnval);
        var url = "{{ URL::to('transfersave') }}";
        validationrule('transfer_form');
        var form = $('#transfer_form');
        form.parsley().validate();
        var form = $('#transfer_form');
        form.parsley().validate();

        if (form.parsley().isValid())
        {   
$('.ajaxLoading').show();
            change_date();
            var formdata = $('#transfer_form').serialize();
            $.post(url, formdata, function(data)
            {
                var status = data.status;
                var msg = '<span style="color:#090065"></span>' + data.message;
                var id = data.id;
                notyMsg(status, msg); 
                if(status == "success")           
                {
                    setTimeout(function(){
                        window.location.reload();
                    },1500);
                }
            });
        }
    });
/*end*/
    /*date function*/
    $('.trx_date').datepicker({format: 'yyyy-mm-dd', autoClose: true});


    var index = $('.clone').closest('tr').index();frm_subinv_id
    Changeclassfields();

/*transfer quantity function*/

    $(document).on('keyup','.bulk_from_transfer_qty',function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        var index = $(this).closest('tr').index();
        var product=$("#bulk_product_id"+index).val();
        if(product!=''){
            var val = Number($(this).val());
            $('.bulk_to_transfer_qty'+index).val(val);
            var qoh=$('.bulk_qoh'+index).val();

            if(val>qoh)
            {
                notyMsg('info','Transfer qty is more than Qoh');
                $(this).val('');
                $('.bulk_to_transfer_qty'+index).val('');
                return true;
            }
        }else{
            notyMsg('info','Please Choose Product...');
        }
    });
    
    /*end*/

    $(document).on('change','.frm_subinv_id',function()
    {
        var frm_subinv_id=$(this).val();
        //var frm_subinv_id=$('.frm_subinv_id').val();
        var url = "{{ URL::to('prddetails') }}?frm_subinv_id="+frm_subinv_id;
        if(frm_subinv_id != "" ){

        //    alert("xfdh")
         /*  $(".bulk_from_loc").jCombo("{{ URL::to('jcomboform?table=m_sublocators_t:sublocator_id:locator_code') }}&parent=subinventory_id="+frm_subinv_id+"&order_by=locator_code asc",
            {selected_value:""});*/
            $.get(url , function(data)
            {
                if(data!=""){
                    // $(".bulk_product_id").css("pointer-events","auto");
                    $(".bulk_product_id").html(data);
                }else{
                    $(".bulk_product_id").html("<option>---Please Select---</option>");
                     notyMsg('info',"There is No Product For This locator... Please choose another Locator");
                }
            });
        }
        var frm_loc_id=$(this).val();
        var to_loc_id=$('.to_loc_id').val();
    /*  if(frm_loc_id!=""){
            if(frm_loc_id==to_loc_id){
               notyMsg('info'," From Locator should not be same as to locator... Please choose another Locator");
                $('.frm_loc_id').val('').change();
            }
        }*/
    });

    $(document).on('change','.to_loc_id',function()
    {
    var to_loc_id=$(this).val();
    var frm_loc_id=$('.frm_loc_id').val();
        if(to_loc_id!=""){
            if(frm_loc_id==to_loc_id){
                notyMsg('info'," To Locator should not be same as from locator... Please choose another Locator");
            $('.to_loc_id').val('').change();
            }
        }
    });

  /*product id function*/

         /*product id function*/

        $(document).on('change','.bulk_batch_no',function()
        {
            var index = $(this).closest('tr').index();console.log(index);
            var product_id=$('.bulk_product_id'+index).val();
            var frm_subinv_id=$('.frm_subinv_id').val();
            var frm_loc_id=$('.bulk_from_loc'+index).val();
            var batch_no=$(this).val();
            var pdtcount = pdtcheck(product_id,index);
            if(product_id!=''){
                var url = "{{ URL::to('productqohdetails') }}?product_id="+product_id+"&frm_subinv_id="+frm_subinv_id+"&frm_loc_id="+frm_loc_id+"&batch_no="+batch_no;
            
                $.get(url , function(data)
                {
                  console.log(data);
                    if(data!=0)
                    {
                       $('.bulk_qoh'+index).val(data[0]);
                       $('.bulk_manufacture_date'+index).val(data[4]);
                        if(data[3]!="00-00-0000"){
                       $('.bulk_product_expire_date'+index).val(data[3]);
                    }
                       // $('.bulk_product_group_id'+index).select2('val',[data[2]]);
                    }
                    else
                       {
                        notyMsg('info',"Qoh not available for this product.. Pls choose another product");
                        $('#bulk_product_id' +index).val('');
                    }
               });
            }
            
        });

/*end*/

/*batch no*/
 $(document).on('change','.bulk_product_id',function()
        {
            var index = $(this).closest('tr').index();console.log(index);
            var product_id=$(this).val();
            var frm_subinv_id=$('.frm_subinv_id').val();
           // var frm_loc_id=$('.frm_loc_id').val();
            var pdtcount = pdtcheck(product_id,index);
            if(pdtcount <= 0){
            if(product_id!=''){

                var url = "{{ URL::to('subinventorybatchno1') }}?product_id="+product_id+"&frm_subinv_id="+frm_subinv_id;
            
                $.get(url , function(data)
                {
                    console.log(data);
                      $('.bulk_product_group_id'+index).select2('val',[data[0]]);
                    $(".bulk_from_loc"+index).html(data[1]);
                 /*   if(data!=0)
                    {
                       $('.bulk_qoh'+index).val(data[0]);

                       $('.bulk_product_group_id'+index).select2('val',[data[2]]);
                    }
                    else
                       {
                        notyMsg('info',"Qoh not available for this product.. Pls choose another product");
                        $('#bulk_product_id' +index).val('');
                    }*/
               });
            }
                }
                else
                {
                     
                    var msg      = $(".bulk_from_loc" + index + ' option:selected').text();
                    var message  = '<span style="color:#fdff65">'+msg+'</span>'+' Product Already Selected';
                    notyMsgs('info',message);
                    rowdataEmpty(index);
                    
                }
        });

 $(document).on('change','.bulk_from_loc',function()
        {
            var index = $(this).closest('tr').index();console.log(index);
            var product_id=$('.bulk_product_id'+index).val();
            var frm_subinv_id=$('.frm_subinv_id').val();
            var frm_loc_id=$(this).val();
            var pdtcount = pdtcheck(product_id,index);
         
            if(product_id!=''){
                var url = "{{ URL::to('subinventorybatchno') }}?product_id="+product_id+"&frm_subinv_id="+frm_subinv_id+"&frm_loc_id="+frm_loc_id;
            
                $.get(url , function(data)
                {
                    console.log(data);
                   //   $('.bulk_product_group_id'+index).select2('val',[data[0]]);
                    $(".bulk_batch_no"+index).html(data[1]);
                 /*   if(data!=0)
                    {
                       $('.bulk_qoh'+index).val(data[0]);

                       $('.bulk_product_group_id'+index).select2('val',[data[2]]);
                    }
                    else
                       {
                        notyMsg('info',"Qoh not available for this product.. Pls choose another product");
                        $('#bulk_product_id' +index).val('');
                    }*/
               });
            }
              
        });
/*End*/

/*end*/
function rowdataEmpty(index)
    {
        $(".bulk_product_id" + index).val('').change();
    }
    
    $(".add_row").on('click',function(){
  var form = $('#transfer_form');
  form.parsley().destroy();
        });
$(".add_row").relCopy();

  $('.add_row').click(function(){

             Changeclassfields();
    });
    
    /*remove function*/
            $(document).on('click','.remove',function()
            {
                var rowCount = $('.subinvtrns_table tbody tr').length;
                if(rowCount > 1)
                {
                    $($(this).closest("tr")).remove();
                    Changeclassfields();
                }
                else
                {
                    notyMsg('info',"You Can't Delete Atleast One row should be there");
                }
            });
    /*end*/
    /*product search function*/
            $('.productsearch').click(function()
            {
                
                var index = ($(this).closest('tr').index());
                $('.pdtindex').val(index);
                var sub_inve = $(".frm_subinv_id").select2('val') != '' ? $(".frm_subinv_id").select2('val') : '';
                $('.frominventory').val(sub_inve);
                $('#productModal').modal('show');
                $('#productModal').width("100%");
            });
              /*end*/          
            /*product search function*/
            $('.productsearch').click(function(){
            
                var sub_inve = $('.frominventory').val();
                $(mypdtgrid).jqGrid('setGridParam', 
                {
                    postData: {"sub_inve":sub_inve}
                }).trigger('reloadGrid');   
            });
    /*end*/
    
/*Karthigaa Purpose For Product Search*/
    var mypdtgrid = $("#productgrid"),

    pagerSelector = "#pager",
    myAddButton = function(options) {
        mypdtgrid.jqGrid('navButtonAdd',pagerSelector,options);
        mypdtgrid.jqGrid('navButtonAdd','#'+mypdtgrid[0].id+"_toppager",options);
    };
        var groupname="'RAW MATERIALS'";
    var gname="'PACKING MATERIALS'";
      
        <?php { ?>
 var grp=[];
 grp.push(groupname);
    grp.push(gname);
<?php } ?>
    var prdcatopt="{{ $prdcatopt}}";
    var prdnameopt="{{ $prdnameopt }}";
        var group="{{$group}}";
        
            mypdtgrid.jqGrid({
            url: "{{URL::to('getProductgridDatasubinventory')}}",
            datatype: "json",
            mtype: "GET",
            height: 320,
            width: 1000,
             colModel:  [
                            { name: "product_code", label: "Product Code", width:55},
                            { name: "group_name", label: "Product Group", editoptions:{value:group}, width:55},
                            { name: "category_name", label: "Product Category", editoptions:{value:prdcatopt}, width:55},
                            { name: "concatenated_product", label: "Product Name",stype:'text', editoptions:{value:prdnameopt}, width:55},
                            { name: "product_id", label: "id",hidden:true, width:55}
                        ],
                        iconSet: "fontAwesome",
            rowNum: 10,
            rowList: [10,20,100,1000],
            sortorder: "asc",
            viewrecords: true,
            gridview: true,
            rownumbers:true,
            caption: "Product",
            pager: pagerSelector,
            toppager:true,
            searching: {
            defaultSearch: "cn"
            }
           });
jQuery(mypdtgrid).jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
jQuery("#gs_productgrid_product_group_id").select2();
jQuery("#gs_productgrid_product_category_id").select2();

mypdtgrid.jqGrid('navGrid',pagerSelector,
{cloneToTop:true,edit:false,add:false,del:false,search:true});

myAddButton ({
caption:"Select Product",
title:"Product",
buttonicon :'ui-icon-plus',
        onClickButton:function()
        {
            var index = $('.pdtindex').val();
            var gr = jQuery(mypdtgrid).jqGrid('getGridParam','selrow');
            var product_id = jQuery(mypdtgrid).jqGrid ('getCell', gr, 'product_id');
            
            if(gr)
            {  
                var pdtcount = pdtcheck(product_id,index); 
                if(pdtcount <= 0)
                { 
                $('.bulk_product_id'+index).val(product_id);
                $('.bulk_product_id'+index).trigger('change');
                $('#productModal').modal('hide');
                }
                else
                { 
                    var msg = jQuery(mypdtgrid).jqGrid ('getCell', gr, 'concatenated_product');
                    var message  = '<span style="color:#fdff65">'+msg+'</span>'+' Product Already Selected';
                    notyMsgs('info',message);
                    rowdataEmpty(index);
                    $('#productModal').modal('hide');
                }
            }
            else
            {
            notyMsg('info','Please Select one row');
            }
        }
});



});


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

    function removeClass(className)
    {
        var rowCount = $('.subinvtrns_table tbody tr').length;
        for(var i=0;i<=rowCount;i++)
        {
        $('.subinvtrns_table tbody tr').find('.'+className).removeClass(className+i);
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
    function Changeclassfields(){
        changeClassName('bulk_line_no');
        changeClassName('bulk_product_id');
        changeClassName('bulk_product_group_id');
        changeClassName('bulk_batch_no');
        changeClassName('bulk_qoh');
        changeClassName('bulk_status');
        changeClassName('bulk_from_transfer_qty');
        changeClassName('bulk_to_transfer_qty');
        changeClassName('bulk_from_loc');
        changeClassName('bulk_manufacture_date');
        changeClassName('bulk_product_expire_date');
    }
    </script>
@include('layouts.php_js_validation');
@endsection
