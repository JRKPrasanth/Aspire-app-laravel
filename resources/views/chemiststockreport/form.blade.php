@extends('layouts.header')
@section('content')


<style>

.panel-info {
    border-color: #5b75a6;
}
.custom-file-upload 
{
   color: #fff;
    border: transparent;
    display: inline-block;
    background: black;
    padding: 6px 12px !important;
    cursor: pointer;
}
.btnic
{
    text-align: center;
    background-color: DodgerBlue;
    border: none;
    color: white;
    padding: 12px 30px;
    cursor: pointer;
    font-size: 12px;
    background-color: #33a9ec1c;
}
.ver1
{
  border: 1px solid rgba(55, 48, 73, .3);
}

.ver1 table
{
  width:100%;
}
.column100.column1
{
 width: 265px;
 padding-left: 42px;
}

.row100.head th
{
    padding: 10px;
}

.table100.ver1 td {
 font-size: 12px;
 color: #808080;
 padding: 10px;
 }

.table100.ver1 th {
 font-size: 12px;
 color: #fff;
 text-transform: uppercase;
 padding: 10px;
 background-color:#112f7aad;
}
#myImg {

    width: 60px;
    height: 60px;
}

.add_button {
    margin-top: -23px;
    position: absolute;
    margin-left: -10px;
}

.remove_button {
    margin-top: -23px;
    position: absolute;
    margin-left: -10px;
}

.preview tbody tr > td:first-child {
    display: block;
}
/*.jackfruit {
        width: 100%;
    box-shadow: 2px 2px 5px 0px rgba(0,0,0,0.2);
}
.jackfruit th,td {
    padding: 7px;
    border: 1px solid #d6d6d6;
    font-size: 14px;
    word-wrap: break-word;
    text-transform: capitalize;
}
.jackfruit th {
    background-color: #455986;
    color: #fff;
}*/
table tbody tr:nth-child(1) {
    background: none;
}
table tbody tr:nth-child(even){
  background-color: #d8d9da;
}
.doctordetails .jackfruit th,td {
    padding: 7px;
    border: 1px solid #d6d6d6;
    font-size: 14px;
    word-wrap: break-word;
    text-transform: capitalize;
}
.jackfruit {
    width: 100%;
}
.doctordetails .jackfruit th {
    
    background-color: #455986;
    color: #fff;
}
.ui-datepicker {
    width: auto;
    padding: .2em .2em 0;
    display: none;
}

</style>
<div class="ajaxLoading"></div>
<h3 class="heads" >Stockist Stock Report </h3>

<div class="card">
<div class="card-body">
<div class="tab row" role="tabpanel">
   



    <!-- Tab panes -->
<div class="tab-content col-lg-12 col-md-12">
    
    <div role="tabpanel" class="tab-pane fade in active" id="Section1">
        <form id="official_form" data-parsley-validate>
            <input type="hidden" name="stockist_dcr_id" id="stockist_dcr_id"  value="{{$stockist_dcr_id}}"/>
        
            <div class="row">
                <div class="col-lg-12 col-md-12">
                   
                        
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-lg-4 col-md-4">Stockist Name :<span class="req">*</span></label>
                                    <div class="col-md-6 ">
                                        <select type="text" name="stockist_id" class="stockist_id form-control select2" id="stockist_id">
                                            {!! $stockist_id !!}
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-lg-4 col-md-4">Enter Month :<span class="req">*</span></label>
                                    <div class="col-md-6 ">
                                        <select type="text" name="month_id" class="month_id form-control select2" id="month_id">
                                            {!! $month_id !!}
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 form-group row doctordetails">
                            
                        </div>  

                  
                        <div class="form-group text-center btn_hide">
                            <button type="button"  class="btn save" data-form="0" id="save">Save</button>
                            <button type="button"  class="btn btn-cancel cancel reset" >Cancel</button>
                        </div>
                        
                    
                </div>
            </div>

        
        
        </form>
    </div>

                 
</div>

</div>
</div>
</div>
       <link rel="stylesheet" href="{{asset('css/bootstrap-datetimepicker.css')}}">
<!--************************- End content********************-->


<script>
        
$(document).ready(function()
{
    $('.month_id').css('pointer-events','none');
    $('.btn_hide').hide();
    /******************Product Details Start****************/
    $(document).on('change','.stockist_id',function(){
        $('.month_id').css('pointer-events','');
    });


    $(document).on('click','.cancel',function(){
        window.location.reload();
    });


    $(document).on('change','.month_id',function(){
        var stk_id =$('.stockist_id').val();
        var month_id = $('.month_id').val();
        if(stk_id ==''){
            notyMsg('info','Please select Stockist Name');
        }
        if(month_id == ''){
            notyMsg('info','Please select Month');
        }
        if( month_id != '' && stk_id != '' ){
            var url = "{{ URL::to('stockreport') }}/"+stk_id+"/"+month_id;
            $.get(url, function(data) {
                console.log(data);
                var html = "<table class='jackfruit'><thead><th>Product Name</th><th>Opening Balance</th><th>Purchase Qty</th><th>Sale Qty</th><th>Free Quantity</th><th>Sales Return </th><th>Direct Return /Adjust Qty</th><th>Available Stock </th><th>Direct Sales/Adjust Qty</th><th>Closing Balance</th></thead><tbody>";
                var i=0;
                
                    $.each(data,function(j,v){
                        if(data[i]['stockist_stk_id']){
                        html += "<tr><td><input type='hidden' name='stockist_stk_id[]' value='"+data[i]['stockist_stk_id']+"'><input type='hidden' name='product[]' value='"+data[i]['product_id']+"'> <input type='text' class='form-control product_id product_id"+[i]+"'  name='product_id[]' style='width:480px;' readonly value='"+data[i]['concatenated_product']+"'></td><td> <input type='text' readonly class='form-control open_qty open_qty"+[i]+"' data-index='"+i+"' name='open_qty[]' value='"+data[i]['open_qty']+"'></td><td><input type='text' name='purchase_qty[]' data-index='"+i+"' readonly class='purchase_qty"+[i]+" purchase_qty form-control' value='"+data[i]['purchase_qty']+"'></td><td><input type='text' readonly name='sale_qty[]' data-index='"+i+"' class='sale_qty"+[i]+" sale_qty form-control' value='"+data[i]['sale_qty']+"'></td><td><input type='text' name='free_qty[]' data-index='"+i+"' class='free_qty"+[i]+" free_qty form-control' value='"+data[i]['free_qty']+"'></td><td><input type='text' data-index='"+i+"' name='sales_return[]' value='"+data[i]['sales_return']+"' class='sales_return"+[i]+" sales_return form-control'></td><td><input type='text' data-index='"+i+"' name='dir_return[]' class='dir_return"+[i]+" dir_return form-control' value='"+data[i]['dir_return']+"'></td><td><input type='text' data-index='"+i+"' name='available_qty[]' class='available_qty"+[i]+" available_qty form-control' readonly value='"+data[i]['available_qty']+"'></td><td><input type='text' data-index='"+i+"' name='dir_sales[]' class='dir_sales"+[i]+" dir_sales form-control' value='"+data[i]['dir_sales']+"'></td><td><input type='text' data-index='"+i+"' value='"+data[i]['closing_bal']+"' class='closing_bal"+[i]+" closing_bal form-control' readonly name ='closing_bal[]'></td></tr>";
                                  
                        i++; 
                        }else{
                            html += "<tr><td><input type='hidden' name='stockist_stk_id[]' value='"+data[i]['stockist_stk_id']+"'><input type='hidden' name='product[]' value='"+data[i]['product_id']+"'> <input type='text' class='form-control product_id product_id"+[i]+"'  name='product_id[]' style='width:480px;' readonly value='"+data[i]['concatenated_product']+"'></td><td> <input type='text' readonly class='form-control open_qty open_qty"+[i]+"' data-index='"+i+"' name='open_qty[]' value='"+data[i]['open_qty']+"'></td><td><input type='text' name='purchase_qty[]' data-index='"+i+"' readonly class='purchase_qty"+[i]+" purchase_qty form-control' value='"+data[i]['purchase_qty']+"'></td><td><input type='text' readonly name='sale_qty[]' data-index='"+i+"' class='sale_qty"+[i]+" sale_qty form-control' value='"+data[i]['sale_qty']+"'></td><td><input type='text' name='free_qty[]' data-index='"+i+"' class='free_qty"+[i]+" free_qty form-control' value='"+data[i]['free_qty']+"'></td><td><input type='text' data-index='"+i+"' name='sales_return[]' value='"+data[i]['sales_return']+"' class='sales_return"+[i]+" sales_return form-control'></td><td><input type='text' data-index='"+i+"' name='dir_return[]' class='dir_return"+[i]+" dir_return form-control' value='"+data[i]['dir_return']+"'></td><td><input type='text' data-index='"+i+"' name='available_qty[]' class='available_qty"+[i]+" available_qty form-control' readonly value='"+data[i]['available_qty']+"'></td><td><input type='text' data-index='"+i+"' name='dir_sales[]' class='dir_sales"+[i]+" dir_sales form-control' value='"+data[i]['dir_sales']+"'></td><td><input type='text' data-index='"+i+"' value='"+data[i]['closing_bal']+"' class='closing_bal"+[i]+" closing_bal form-control' readonly name ='closing_bal[]'></td></tr>";
                            i++;
                        }
                    });               
                    
                        
                
                html +="</tbody></table>";

                $('.btn_hide').show();
                $(".doctordetails").html(html);

            });
        }

    });


    $(document).on('keyup','.free_qty,.sales_return,.dir_return,.dir_sales,.sale_qty',function(){

        var free_qty=sales_return=receipt=sale_qty=open_qty=0;
        var index = $(this).attr('data-index');
        
        open_qty = (isNaN($('.open_qty'+index).val()))? 0 : $('.open_qty'+index).val();
        purchase_qty = (isNaN($('.purchase_qty'+index).val()))? 0 : $('.purchase_qty'+index).val();
        sale_qty = (isNaN($('.sale_qty'+index).val()))? 0 : $('.sale_qty'+index).val();
        free_qty = (isNaN($('.free_qty'+index).val()))? 0 : $('.free_qty'+index).val();
        
        sales_return = (isNaN($('.sales_return'+index).val()))? 0 : $('.sales_return'+index).val();
        dir_return = (isNaN($('.dir_return'+index).val()))? 0 : $('.dir_return'+index).val();
        dir_sales = (isNaN($('.dir_sales'+index).val()))? 0 : $('.dir_sales'+index).val();

        // console.log("sale_qty  "+sale_qty+'  purchase_qty'+purchase_qty+"  free_qty"+free_qty+"  sales_return"+sales_return+"  dir_return"+dir_return+"  dir_sales"+dir_sales);
        var closing_balance = (parseInt(open_qty)+parseInt(purchase_qty)+parseInt(free_qty))-(parseInt(sale_qty)+parseInt(sales_return)+parseInt(dir_return)+parseInt(dir_sales));
        var closing_balance1 =   (isNaN(closing_balance))? 0 :closing_balance;
        console.log(closing_balance1);
        $('.closing_bal'+index).val(closing_balance1);
        
    });


    $(document).on('click','.save',function()
    {

        var url            ="{{ url('stockreportsave') }}";
        validationrule('official_form');
        var form = $('#official_form');

            form.parsley().validate();
            var form = $('#official_form');
            form.parsley().validate();
            if (form.parsley().isValid())
            {
                change_date();   
                var formdata    = $('#official_form').serialize();
                $.post(url,formdata,function(data)
                {
                    var status = data.status;
                    var msg    = data.message;
                    var id     = data.id;

                    notyMsg(status,msg);
                    setTimeout(function(){                    
                        window.location.reload();
                    }, 1500);
                });


            }
        

    });
 /***********************Product Details End********************************/
 

});


</script>
@include('layouts.php_js_validation')
@endsection
