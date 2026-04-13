@extends('layouts.header')
@section('content')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css">
<style type="text/css">
.modal-open .modal {
     overflow-x: auto !important; 
     overflow-y: auto !important; 
}
.parent:hover
{
    cursor:pointer;
}
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
    max-width: 1000px;
    margin: auto;
    padding: 30px;
    border: 1px solid #ccc;
    box-shadow: 10px 10px 10px #ccc;
    font-size: 16px;
    line-height: 24px;
    color: #555;
}
.trail-balance table tr.heading td {
    background: #e8e8e8;
    border-bottom: 1px solid #dcdcdc;
    color: #000;
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


<!--<h2 class="heads">Profit And Loss Standard report</h2>-->
<h2 class="heads">Balance Sheet Report</h2>
<div class="card">


<div class="card-body card-block">
<div class="main clearfix">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-8">
                <form id="nl-form" class="nl-form">
					I like to see 
				
					Balance Sheet from
                    <input type="text" value="" name='daterange' placeholder="any Date" class='datepicker from_date' value="{{date('Y-m-01')}}"/> to
                    <input type="text" value="" name='daterange' placeholder="any Date" class='datepicker to_date'  value="{{date('Y-m-d')}}"/> 
					<div class="nl-submit-wrap">
						<button class="nl-submit view" type="button">View Balance Sheet</button>
					</div>
					<div class="nl-overlay"></div>
				</form>
            </div>
            <div class="col-md-4">
                    SHIP Journal till Date: 
                    <a type="button" class="btn btn-primary " style="cursor: none;width: 35%;">{{$ship_date[0]->journal_date}}</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-7">
                &nbsp;
            </div>
            <div class="col-md-5">
                    Last SHIP Journal has been executed on: 
                    <a type="button" class="btn btn-primary " style="cursor: none;">{{$ship_date[0]->created_at}}</a>
            </div>
        </div>
    </div>
				
			</div>
    
    
    </div>
<div class="row">
<div class='tab_disp col-md-12' id="outprint">
<div class="prt_main">     
<div class="col-md-3 print_logo">
    <figure>
        <img src="{{ asset('images/jrks.png') }}" alt=" " />
    </figure>
</div>
<div class="col-md-9 print_name">
    <h3>DR. JRK'S RESEARCH AND PHARMACEUTICALS PVT.LTD</h3>
</div>
<div style="clear:both "></div>
</div>  
<div style="clear:both "></div>
<p class='report-title'>
    <b>Balance Sheet</b>
</p>
<p class="report-title2 report">
    <span class="dat"></span>
</p> 
<div class="prt">
    <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
        <span class="glyphicon glyphicon-print printMe"></span> Print
    </button>
    
     <button type="button" class="btn btn-default btn-sm downloads" >
        <span class="glyphicon glyphicon-download printMe"></span> Download
    </button>
    
</div>
<div class='col-md-12 tab_show'>                            
    <div class="trail-balance" id="table">
    </div>
</div>
<div style="clear:both "></div>
</div>
</div> 
</div>


<a href="{{URL::to('balancesheet.xls')}}" class='download_link' download></a>

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

  <script type="text/javascript">

var nlform = new NLForm(document.getElementById( 'nl-form' )); 


 

$('.view').click(function(){
          
    
 var from_date=$(".from_date").val();
 var to_date=$(".to_date").val();
 if(from_date!="")
 {

$(".report-title2").html(from_date+' To '+to_date);
     
     
    var url_val="{{URL::to('getbalancesheetdata1')}}?start_date="+from_date+"&end_date="+to_date;
    $.get(url_val,function(data){
        
            $("#table").html(data);
            
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
            $(".child"+id).hide();
            $target.attr('col','0');
        }                    
    });
  
            
       
    });
}
else
{
    notyMessageError("Please select Month");	
}
    });
    
    
$('.downloads').click(function(){
          
    
 var from_date=$(".from_date").val();
 var to_date=$(".to_date").val();
 if(from_date!="")
 {

$(".report-title2").html(from_date+' To '+to_date);
     
     
    var url_val="{{URL::to('getbalancesheetdata1')}}?download=1&start_date="+from_date+"&end_date="+to_date;
    $.get(url_val,function(data){
        
            $('.download_link')[0].click();
       
    });
}
else
{
    notyMessageError("Please select Month");	
}
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

  </script>
@include('layouts.php_js_validation')
@endsection




