<?php dd('dfsd'); ?>
@extends('layouts.header')
@section('content')

<style type="text/css">

/*.ui-jqgrid .ui-pg-table {
    position: relative;
    padding-bottom: 2px;
    width: 64%;
    margin-left: 14.5em;
}*/
/*.ui-jqgrid .ui-paging-info {
    font-weight: normal;
    margin-top: 3px;
    margin-right: 13.9em !important;
    padding: 20px;
}*/
.panel{
  margin-bottom: 80px;
}
</style>
<div id="accordion">
<div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a role="button">
                                       Sub Ledger
                                    </a>
        </h4>
  </div>
</div>
<table id="productTable"  class="table ">
    <tbody>
        <tr> 
            <td><div>From Date</div></td>
            <td> <div><input id="from_date" class="form-control-label col-md-6 from_date datepicker" placeholder="" name="to_date" type="text" value=""> </div></td> </tr>
        <tr>
            <td><div>To Date </div></td>
            <td><div><input id="to_date date" class="form-control-label col-md-6 to_date datepicker" placeholder="" name="to_date" type="text" value=""> </div></td> </tr>
    </tbody>
   
</table>
 <button  type="button"  class="btn btn-info flt">Filter</button>
<div class="panel panel-visible" id="spy1">
	<div class="row">
	<div class="col-md-12">
             <?php include('toolbar.php'); ?>
<!-- <button type='button' id="ledgerposting"  class='btn ledgerposting vie'> LEDGER POSTING </button>
<button type='button' id="view"  class='btn view vie'> VIEW</button>
<button type='button' href='' class='btn clearsearch'>Clear Search </button>
<button type='button' id="showcolumn" value="1" class='btn showcolumn showcolumns'> Show Column </button> -->
</div>
</div>


<div class="row">
<div class="col-md-12">
<hr class="xlg">
</div>
</div>

<div class="row">
	<div class="col-md-12">
<table id="sublegergrid"></table>
</div>
</div>

<div class="row">
<div class="col-md-12">
<hr class="xlg">
</div>
</div>

</div>


<script type="text/javascript">
$( document ).ready(function() {

$("#sublegergrid").jqGrid({
   
url: "subledgerload",
datatype: "json",
mtype: "GET",
	 colModel: [
	{ name: "f_journal_entry_line_id", label: "id",hidden:true },
        { name: "status", label: "id",hidden:true },
        { name: "journal_entry_id", label: "Journal Name" },
        { name: "journal_date", label: "Journal Date" },
	{ name: "account_id", label: "Account Name" },
        { name: "debit_amount", label: "Debit Amount"},
        { name: "credit_amount", label: "Credit Amount"},
	],
	rowNum:10,
		 
		viewrecords: true,
		footerrow: true,
		sortorder: "desc",
		userDataOnFooter: true, // use the userData parameter of the JSON response to display data on footer
		width: 780,
		height: 300,
		  rownumbers: true ,
		rowList: [10,20,50,100,250,500,1000],
		pager: "#sublegergrid",
multiselect:true,
multiPageSelection:true,
       
//         gridComplete: function() {
//        var recs = parseInt($("#list").getGridParam("records"),10);
//        if (isNaN(recs) || recs == 0) {
//            $("#sublegergrid").hide();
//        }
//        else {
//            $('#gridWrapper').show();
//            alert('records > 0');
//        }
//    },
      });
  
 jQuery("#sublegergrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
	 $("#glbalancegrid").jqGrid("setLabel", "rn", "S.No");
jQuery("#sublegergrid").jqGrid('hideCol','cb');  
showcolumn('sublegergrid');
	 $(document).on('click',".export",function() {
   	$("#sublegergrid").jqGrid('exportToPdf', {
  title: null,
  orientation: 'portrait',
  pageSize: 'A4',
  description: null,
  onBeforeExport: null,
  download: 'download',
  includeLabels : true,
  includeGroupHeader : true,
  includeFooter: true,
  fileName : "Sub Ledger.pdf",
  mimetype : "application/pdf"  
});
			
$("#sublegergrid").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "Sub Ledger.xlsx"
    					
				})		 
	});


$("#ledgerposting").click(function(){
	var gr = jQuery("#sublegergrid").jqGrid('getGridParam','selrow');
      //  var status = jQuery("#sublegergrid").jqGrid ('getCell', gr, 'status');
        var $grid = $("#sublegergrid"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,cellvalues = [];
      for (i = 0, n = selIds.length; i < n; i++) {
	var v=	$grid.jqGrid("getCell", selIds[i], "f_journal_entry_line_id");
		if(v!=false)
		cellvalues.push(v);
	}
      
       if(gr){
        var url = "{{ URL::to('getledgerpost')}}/"+cellvalues;
        $.get(url,function(data){
             var status = data.status;
             var msg     = '<span style="color:#090065"></span>  '+data.message;
             notyMsg(status, msg);
              setTimeout(function() {
                         location.reload();
                            }, 1500);
           
          });
          }
	else
	{
	notyMsg("info","Please Select Row");
	}
});
$('#view').click(function(){
  var gr=$('#sublegergrid').jqGrid('getGridParam','selrow');
  var cellValue = $("#sublegergrid").jqGrid ('getCell', gr, 'f_journal_entry_line_id');
  if(gr){
     window.location.replace('subledgerview/' +cellValue);
  }
  else
  {
  notyMsg("info","Please Select Row");
  }
});
	  /**karthigaa code for search**/
    $('.flt').click(function(){
        var fromdate=$('.from_date').val();
        var todate=$('.to_date').val();
        if( fromdate!='' || todate!=''){
            
             var url="{{URL::to('getsubledgerfilterData')}}/?fromdate="+fromdate+"&todate="+todate;
            $.get(url,function(data){
                console.log(data);
            });
     }
     else{
      notyMsg("error"," Please Choose From Date And To Date");
     }
    
    
     });  
     
 /*Karthigaa purpose:clear search the jqgrid*/
	$(".clearsearch").click(function(){
            var grid = $("#sublegergrid");
            grid.jqGrid('setGridParam',{search:false});

            var postData = grid.jqGrid('getGridParam','postData');
            $.extend(postData,{filters:""});
            grid.trigger("reloadGrid",[{page:1}]);
             $('input[id*="gs_"]').val("");
    });
/*end*/




$(window).scroll(function() {
if ($(this).scrollTop() >150){
    $('.header-sticky').addClass("sticky");
  }
  else{
    $('.header-sticky').removeClass("sticky");
  }
});

});
  </script>
@endsection

