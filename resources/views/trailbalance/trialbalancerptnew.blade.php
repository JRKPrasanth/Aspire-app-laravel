@extends('layouts.header')
@section('content')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css">
<style type="text/css">

.divhide .card{
    padding: 5px;
    border: 1px solid #ccc;
}
.divhide .table{
    width: 100%;
}
.card-header{
    border-bottom:1px solid #ccc;
}

.report{
    font-size:12px; 
    border-top: 1px solid #000;
    border-bottom: 1px solid #000;
    text-align:center;
    width:17%;
    position: relative;
    left:42.2%
}
.report-title,.report-title2
{
    font-size: 16px;
    text-align: center;
    line-height: 24px;
    
}
.trail-balance {
    background-color: #fff;
    /*max-width: 1000px;*/
    margin: auto;
    padding: 30px;
    border: 1px solid #ccc;
    box-shadow: 10px 10px 10px #ccc;
    font-size: 16px;
    line-height: 24px;
    color: #555;
}
.trail-balance .table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
.trail-balance table tr.heading td {
    background: #e8e8e8;
    border: 1px solid #dcdcdc !important;
    border-collapse: collapse !important;
    color: #000;
}
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
    
    border: 1px solid #dcdcdc !important;
    border-collapse: collapse !important;
}
.print_logo, .print_name {
    display:none;
}
.tab_disp {
    padding-bottom:30px;
}
.trail-balance {
    padding:0;
    border:none;
}
.table>caption+thead>tr:first-child>td, .table>caption+thead>tr:first-child>th, .table>colgroup+thead>tr:first-child>td, .table>colgroup+thead>tr:first-child>th, .table>thead:first-child>tr:first-child>td, .table>thead:first-child>tr:first-child>th {
    border-top: 0;
    background:rgba(0, 18, 103, 0.89);
    font-family: FiraSans-Book;
    font-weight: bold;
    color: #fff;
    padding:10px;
    font-size:14px;
}
.trail-balance table tr.heading td {
        background-color: #e6e9ed;
        
}
.trail-balance {
   box-shadow:none; 
}
.table tr td b {
    padding-left:5px;
}
.table {
    border: 1px solid #ddd;
    box-shadow:5px 5px 5px rgba(0,0,0,.1);
}
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
    font-size:14px;
    padding:10px;
}
th:nth-child(2) {
    text-align:right !important;
}
th:nth-child(3) {
    text-align:right !important;
}
.prt {
    position: absolute;
    right: 150px;
    top: -7px;
}
.print_logo figure img {
    display:block;
}
.print_name h3 {
    font-size:20px;
}
.print_logo {
    float:left;
    width:15%:;
}
.print_name {
    float:left;
    width:75%;
}
@page {
  size: A4;
}
@media print {
    .print_logo, .print_name {
    display:block;
}
.card-block, .prt, .heads, .footer {
    display:none;
}

.trail-balance, .tab_show, .table {
    max-width:100% !important;
}
.print_logo {
    float:left !important;
    width:15% !important;
}
.print_name {
    float: left !important;
width: 85% !important;
padding: 15px 30px;
font-family: FiraSans-Book;
}
.print_name h3 {
   font-family: FiraSans-Book;
   font-size:16px !important;
}
.report {
    width:100%;
    left:0;
    position:none;
    display:inline-block;
    margin-bottom:15px;
}
.prt_main {
    width:100% !important;
    border-bottom: 1px solid #ccc;
    padding-bottom:15px !important;
}
.container {
    max-width:100% !important;
}
.report-title {
    padding:15px 0 0 0;
}
.table {
    max-width: 100% !important;
}
}
</style>


<link rel="stylesheet" href="{{ asset('css/nlform.css') }}">

<script src="{{ asset('js/modernizr.custom.js') }}"></script>

<script src="{{ asset('js/nlform.js') }}"></script>

<div class="ajaxLoading"></div>
<h2 class="heads">Trial Balance report</h2>
<div class="card">



<div class="row">
    <div class="col-md-12"> 
       <div class="col-md-4">
            <div class="form-group row">
                <label for="inputIsValid" class="form-control-label col-md-4">From Date</label>
                <div class="col-md-6">
                    <input type="text" name="from_date" id="from_date" class="form-control from_date datepicker" >
                </div>
            </div>            
        </div>
        <div class="col-md-4">
            <div class="form-group row">
                <label for="inputIsValid" class="form-control-label col-md-4">To Date</label>
                <div class="col-md-6">
                    <input type="text" name="to_date" id="to_date" class="form-control to_date datepicker" >
                </div>
            </div>            
        </div>     

        
        <div class="col-md-4">
            <div class="form-group row">
                <label for="inputIsValid" class="form-control-label col-md-4">Location</label>
                <div class="col-md-6">
                    <select name='location_id' rows='5' class='form-control location_id select2' id='product' style="border-radius: 5px;">
                        {{!! $location_id !!}}
                    </select>
                </div>
            </div>            
        </div>
    </div>
        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-offset-3 col-md-2"><span style="color:red; " > </span></label>
            <div class="col-md-1">
                <a><button type="button" class="btn add view" id="view" value="">Search</button></a>
            </div>  
        </div>
    
    </div>



<div class="row">
<div class='tab_disp col-md-12' id="outprint">
<div class="prt_main">     
<div class="col-md-3 print_logo">
    <figure>
        <img src="{{ asset('images/logo.jpg') }}" alt=" " />
    </figure>
</div>
<div class="col-md-9 print_name">
    <h3>SKM Siddha and Ayurvedha Company (India) Private Limited </h3>
</div>
<div style="clear:both "></div>
</div>  
<div style="clear:both "></div>
<p class='report-title'>
    <b>Trial Balance Statement</b>
</p>
<p class="report-title2 report">
    <span class="dat"></span>
</p> 
<div class="prt">
    <button type="button" class="btn btn-default btn-sm print_1" >
        <span class="glyphicon glyphicon-print printMe"></span> Print
    </button>
    
     <button type="button" class="btn btn-default btn-sm downloads" >
        <span class="glyphicon glyphicon-download printMe"></span> Download
    </button>
    
</div>
<div class='col-md-12 tab_show'>                            
    <div class="trail-balance" id="table" style="display: flex;">
    </div>
</div>
<div style="clear:both "></div>

</div>
</div> 
 
</div>

 <!-- Ajith  purpose Opening Closeing details jqgrid model-->
<div class="modal fade" id="openModal">
  <div class="modal-dialog" style="width:80%;">
    <div class="modal-content">
        <!--Moda Header-->
      <div class="modal-header">
          <h4 class="modal-title">Stock Details </h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
        <!-- Modal Body -->
      <div class="modal-body">
          <table id="opeinggrid"></table>
      </div>
         <!-- Modal footer -->
      <div class="modal-footer">
      </div>

    </div>
  </div>
</div>

    <!--end-->
 <!-- Ajith  purpose Ledger details jqgrid model-->
<div class="modal fade" id="accountModal">
  <div class="modal-dialog" style="width:80%;">
    <div class="modal-content">
        <!--Moda Header-->
      <div class="modal-header">
          <h4 class="modal-title">Ledger Details </h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
        <!-- Modal Body -->
      <div class="modal-body">
          <table id="accountgrid"></table>
      </div>
         <!-- Modal footer -->
      <div class="modal-footer">
      </div>

    </div>
  </div>
</div>

    <!--end-->
<!--<a href="{{URL::to('trialbalance.xls')}}" class='download_link' download></a>-->



  <script type="text/javascript">

//var nlform = new NLForm(document.getElementById( 'nl-form' )); 

  
//alert('dsfsd');

$("#start_date").datepicker({
    changeMonth: true,
    changeYear: true
});

$("#end_date").datepicker({
    changeMonth: true,
    changeYear: true
});


$('.view').click(function(){
    var from_date=$(".from_date").val();
    var to_date=$(".to_date").val();
    var type=$(".type").val();
    // var branch_id=$(".branch_id").val();
    var location_id=$(".location_id").val();
    if(from_date!="")
    {
        $(".report-title2").html(from_date+' To '+to_date);
        $('.ajaxLoading').show();
        //  var url_val="{{URL::to('gettrialbalance')}}?start_date="+from_date+"&end_date="+to_date+"&type="+type;
        var url_val="{{URL::to('gettrialbalancenew')}}?start_date="+from_date+"&end_date="+to_date+"&type="+type+"&location_id="+location_id;
        // var url_val="{{URL::to('gettrialbalance')}}?start_date="+from_date+"&end_date="+to_date+"&type="+type+"&branch_id="+branch_id+"&location_id="+location_id;
        $.get(url_val,function(data){
            $("#table").html(data);
            //  console.log($(".exp_total").text().replace(',',''));
            var exp=($(".exp_total").text());
            var inc=($(".inc_total").text());
            exp=exp.replace(/,/g,'');
            inc=inc.replace(/,/g,'');
            console.log(exp);
            console.log(inc);
            if(exp<inc)
            {
                var pro=parseFloat(parseFloat(inc) - parseFloat(exp)).toFixed(0);
                var pro1=parseFloat(parseFloat(exp) + parseFloat(pro)).toFixed(0);
                $(".profit_value").text(convertocurrency(pro)); 
                var total=exp+(inc-exp);
                $(".exp_total").text(convertocurrency(pro1))
                // alert(total);
            }
            else
            {
                var pros=parseFloat(parseFloat(exp) - parseFloat(inc)).toFixed(0);
                $(".loss_val").text(convertocurrency(pros)); 
                var total=parseFloat(parseFloat(inc) + parseFloat(pros)).toFixed(0);
                //  var total=inc+(exp-inc);
                // alert(pros);
                $(".inc_total").text(convertocurrency(total))
            }
            $(".child").hide();
            
            $("table").click(function(event) {
                event.stopPropagation();
                var $target = $(event.target);
                var id=$target.attr('data');
                var col=$target.attr('col');
                if (col=='0') {
                   $(".child"+id).show();
                   $target.attr('col','1');
                } else {
                    $(".parent_hide"+id).each(function(){
                        var id1=$(this).attr('data');
                        if(id1!='')
                        {
                            $(".child"+id1).hide();
                            $(this).attr('col','0');
                            $(".parent_hide"+id1).each(function(){
                                var id2=$(this).attr('data');
                                if(id2!='')
                                {
                                    $(".child"+id2).hide();
                                    $(this).attr('col','0');
                                }
                            });
                        }
                    });
                    $(".child"+id).hide();
                    $target.attr('col','0');
                }                    
            });
            $('.ajaxLoading').hide();
        });
    }
    else
    {
        notyMessageError("Please select Month");    
    }
});
 var data ="{{\Session::get('j_date_format')}}";
$("#from_date").datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: data
});

$("#to_date").datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: data
});
$('.downloads').click(function(){
    window.location.href = "trialbalance.xlsx";      
    /*var from_date=$(".from_date").val();
    var to_date=$(".to_date").val();
    var type=$(".type").val();
    var branch_id=$(".branch_id").val();
    var location_id=$(".location_id").val();
    
 if(from_date!="")
 {

$(".report-title2").html(from_date+' To '+to_date);
     $('.ajaxLoading').show();
     
    // var url_val="{{URL::to('gettrialbalance')}}?download=1&start_date="+from_date+"&end_date="+to_date+"&type="+type;;
        var url_val="{{URL::to('gettrialbalance')}}?download=1&start_date="+from_date+"&end_date="+to_date+"&type="+type+"&branch_id="+branch_id+"&location_id="+location_id;
    $.get(url_val,function(data){
        $('.ajaxLoading').hide();
                                    window.location.href = data;
       
    });
}
else
{
    notyMessageError("Please select Month");    
}*/
    }); 

var url='';
$('.pdtbtn').parent('div').html('');
    var mypdtgrid = $("#accountgrid"),
    pagerSelector = "#pager",
    myAddButton = function(options) {
        mypdtgrid.jqGrid('navButtonAdd',pagerSelector,options);
        mypdtgrid.jqGrid('navButtonAdd','#'+mypdtgrid[0].id+"_toppager",options);
    };
  
            mypdtgrid.jqGrid({
            url: url,
            datatype: "json",
            mtype: "GET",
            height: 320,
            width: 1000,
             colModel: [
             { name: "journal_entry_id", label: "Id",hidden:true, width:55},
             { name: "journal_name", label: "Journal Name", width:55},
             { name: "journal_date", label: "Date", width:55},
             { name: "reference_source", label: "Reference Source", width:55},
             { name: "reference_name", label: "Reference Name", width:55},
             { name: "concatenated_segments", label: "Account", width:55},
             { name: "debit_amounts", label: "Debit Amount", width:55},
             { name: "credit_amounts", label: "Credit Amount", width:55},
             { name: "balance", label: "Balance", width:55},
    
    { name: "f_account_structure_id", label: "id",hidden:true, width:55}
        ],

            iconSet: "fontAwesome",
            rowNum: 10,
            rowList: [10,20,100,1000],
            sortorder: "asc",
            viewrecords: true,
            gridview: true,
            rownumbers:true,
            pager: pagerSelector,
            toppager:true,
            searching: {
            defaultSearch: "cn"
            }
           });
            jQuery(mypdtgrid).jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
            $("#gs_productgrid_product_category_id").select2();
mypdtgrid.jqGrid('navGrid',pagerSelector,
{cloneToTop:true,edit:false,add:false,del:false,search:true});
$('.ui-icon-refresh').hide();
myAddButton ({
caption:"Select Account Sructure",
title:"Account",
buttonicon :'ui-icon-plus pdtbtn',
  
            
});


var url1='';
$('.pdtbtn').parent('div').html('');
    var mypdtgrid = $("#opeinggrid"),
    pagerSelector = "#pager",
    myAddButton = function(options) {
        mypdtgrid.jqGrid('navButtonAdd',pagerSelector,options);
        mypdtgrid.jqGrid('navButtonAdd','#'+mypdtgrid[0].id+"_toppager",options);
    };
  
            mypdtgrid.jqGrid({
            url: url1,
            datatype: "json",
            mtype: "GET",
            height: 320,
            width: 1000,
             colModel: [
             { name: "product_id", label: "Id",hidden:true, width:55},
             { name: "group_name", label: "Product Group", width:55},
             { name: "category_name", label: "Product Category", width:55},
             { name: "product_code", label: "Product Code", width:55},
             { name: "concatenated_product", label: "Product Description", width:55},
             { name: "uom_code", label: "Uom Code", width:55},
             { name: "qoh", label: "QOH", width:55},
             { name: "avg", label: "Average Rate", width:55},
             { name: "sum", label: "Stock Value", width:55},
    
    { name: "f_account_structure_id", label: "id",hidden:true, width:55}
        ],

            iconSet: "fontAwesome",
            rowNum: 10,
            rowList: [10,20,100,1000],
            sortorder: "asc",
            viewrecords: true,
            gridview: true,
            rownumbers:true,
            pager: pagerSelector,
            toppager:true,
            searching: {
            defaultSearch: "cn"
            }
           });
            jQuery(mypdtgrid).jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
            $("#gs_productgrid_product_category_id").select2();
mypdtgrid.jqGrid('navGrid',pagerSelector,
{cloneToTop:true,edit:false,add:false,del:false,search:true});
$('.ui-icon-refresh').hide();
myAddButton ({
caption:"Select Account Sructure",
title:"Account",
buttonicon :'ui-icon-plus pdtbtn',
  
            
});

// $('.linkeid').on('click',function(){
//   var accountid=$(this).val();
//   alert(accountid);
  
// });  
function ledgerpop(id){
    var from_date=$('.from_date').val();
     var to_date=$('.to_date').val();
     var accountid=id;
    // alert(accountid);
    //   var url="{{ URL::to('getledgerpandlData')}}?start_date="+from_date+"&end_date="+to_date+"&ledger_id="+accountid
    //         var url = url;
    //         obj.dataModel.url=url;
    //  $( "#ledgergrid" ).pqGrid( "option" , "dataModel.url",url );
    // $("#ledgergrid").pqGrid("refreshDataAndView");
  
        //if(status!='' || employee_type!=''){
           var url="{{ URL::to('getledgerpandlData')}}?start_date="+from_date+"&end_date="+to_date+"&ledger_id="+accountid;
    $("#accountgrid").jqGrid().setGridParam({url : url}).trigger("reloadGrid")
     $('#accountModal').modal('show');
     $('#accountModal').width("100%");    
}

function opening_pop(data){
        
    var from_date=$('.from_date').val();
     var to_date=$('.to_date').val();
     var accountid=data;
 var url="{{ URL::to('getopeningData')}}?start_date="+from_date+"&end_date="+to_date+"&page_name="+accountid;
    $("#opeinggrid").jqGrid().setGridParam({url : url}).trigger("reloadGrid")
     $('#openModal').modal('show');
     $('#openModal').width("100%");    
}
function convertocurrency(data)
{
    var x=data;
x=x.toString();
var lastThree = x.substring(x.length-3);
var otherNumbers = x.substring(0,x.length-3);
if(otherNumbers != '')
    lastThree = ',' + lastThree;
var res = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree;
return res;
}

  </script>
@include('layouts.php_js_validation')
@endsection




