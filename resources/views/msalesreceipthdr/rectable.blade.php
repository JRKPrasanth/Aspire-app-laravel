@extends("layouts.header")
@section("content")
<div id="accordion">
<div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a role="button">

                                        msalesreceipt
                                    </a>
                                </h4>
                            </div>

</div>




  <div class="panel panel-visible" id="spy1">

<div class="panel-title">
  <div class="row">
    <div class="col-md-12" >
  <?php include("toolbar.php"); ?>         
         

</div>
</div>
<div class="row">
    <div class="col-md-12">
         <hr class="xlg">
    </div>
</div>
</div>
  <div class="row">
    <div class="col-md-12" >
   <table id="msalesreceipthdrgrid"></table>
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
      

    $("#msalesreceipthdrgrid").jqGrid({
    
     
      url: "getmsalesreceipthdrData",
      datatype: "json",
      mtype: "GET",
	    colModel:[
  { name: "receipt_id", label: "RECEIPT ID",hidden:true ,width: 250 },
  { name: "receipt_number", label: "RECEIPT NUMBER",width: 250 },
  { name: "receipt_date", label: "RECEIPT DATE",width: 250 },
  // { name: "invoice_amount", label: "INVOICE AMOUNT",width: 250 },
  // { name: "receipt_amount", label: "RECEIPT AMOUNT",width: 250 },
  { name: "account_code_id", label: "ACCOUNT CODE",width: 250 },
  { name: "receipt_type_id", label: "RECEIPT TYPE",width: 250 },
  { name: "receipt_reference", label: "RECEIPT REFERENCE",width: 250 },
  { name: "remarks", label: "REMARKS",width: 250 },
  { name: "cheque_no", label: "CHEQUE NO",width: 250 },],
	rowNum:10,
		viewrecords: true,
		footerrow: true,
		sortorder: "desc",
		userDataOnFooter: true, // use the userData parameter of the JSON response to display data on footer
		width: 780,
		height: 300,
		  rownumbers: true ,
		rowList: [10, 20, 50, 100,250,500,1000,2500,5000,10000],
    
		pager: "#msalesreceipthdrgrid"
    
});
jQuery("#msalesreceipthdrgrid").jqGrid("filterToolbar",{stringResult: true,searchOnEnter : false});

 
		$("#msalesreceipthdrgrid").jqGrid("setLabel", "rn", "S.No");
	$("#gs_date").attr("placeholder","Eg:1994-10-31");
 $(document).on("click",".exportpdf",function() {
   	$("#msalesreceipthdrgrid").jqGrid("exportToPdf", {
  title: null,
  orientation: "portrait",
  pageSize: "A4",
  description: null,
  onBeforeExport: null,
  download: "download",
  includeLabels : true,
  includeGroupHeader : true,
  includeFooter: true,
  fileName : "msalesreceipthdr.pdf",
  mimetype : "application/pdf"  
});
			
		 
	});
$(document).on("click",".exportexcel",function() {
$("#msalesreceipthdrgrid").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "msalesreceipthdr.xlsx"
    					
				})		 
	});
/*Deepika Purpose for Show Coloumn*/
showcolumn("msalesreceipthdrgrid");


/*Deepika Purpose For CREATE Function*/
$(document).on("click",".create",function(){
var url="{{ URL::to('msalesreceipthdrindex') }}";
window.location.replace(url);
});


/*Deepika Purpose For Edit Function*/
$("#edit").click(function(){
        var index = $("#msalesreceipthdrgrid").jqGrid("getGridParam","selrow");

	var tablehdrid = $("#msalesreceipthdrgrid").jqGrid ("getCell", index, "receipt_id");
       if( index )
       {
         
           window.location.replace("msalesreceipthdrcreate/" +tablehdrid);
         
       }
      else
      {
        notyMsg("info","Please Select Row");
      }

});
    /*Deepika Purpose For View Function*/
       $("#view").click(function(){
	var index = $("#msalesreceipthdrgrid").jqGrid("getGridParam","selrow");
	var tablehdrid = $("#msalesreceipthdrgrid").jqGrid ("getCell", index, "receipt_id");
	if( index )
	{
		window.location.replace("msalesreceipthdrview/" +tablehdrid);
	}
	else
	{
			notyMsg("info","Please Select Row");
	}
   });
       
$("#delete").click(function(){

  var gr = jQuery("#msalesreceipthdrgrid").jqGrid("getGridParam","selrow");
  var cellValue = jQuery("#msalesreceipthdrgrid").jqGrid ("getCell", gr,"table_hdr_id");
  if( gr )
  {
    swal({
      title: "Are you sure?",
      text: "You want to delete!",
      type: "warning",
      showCancelButton: !0,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Yes",
      cancelButtonText: "No",
      closeOnCancel:!1
    }, function(e) {
    if(e == true)
      {
      var url="{{ URL::to('msalesreceipthdrdelete') }}/"+cellValue;
       
        $.get(url,function(data)
        {
          var data = $.trim(data);
          var red_url="{{ URL::to('msalesreceipthdr') }}";

          if(data =="0")
          {
            notyMsg("success","Deleted Successfully!!!");
            $(".clearsearch").trigger("click");
            setTimeout(function(){
            $("#msalesreceipthdrgrid")[0].triggerToolbar();
            }, 1500);
          }
          if(data=="2")
          {
            notyMsg("info","You Cannot delete , Table Used in SomeWhere!!!");
            $(".clearsearch").trigger("click");
            setTimeout(function(){
              $("#msalesreceipthdrgrid")[0].triggerToolbar();
            }, 1500);
          }
        });
      }
      else
      {
        $(".apply").css("display","none");
        $(".clearsearch").trigger("click");
        swal("Cancelled");
      }
    });
  $(".apply").css("display","none");
  }
  else
  {
  notyMsg("info","Please Select Row");
  }
});
/***** Delete Row ********/

	
/***** Deepika Purpose For CLEAR search ********/
	$("#clearsearch").click(function() {
		var grid = $("#msalesreceipthdrgrid");
		grid.jqGrid("setGridParam",{search:false});

		var postData = grid.jqGrid("getGridParam","postData");
		$.extend(postData,{filters:""});
		grid.trigger("reloadGrid",[{page:1}]);
                 $("input[id*='gs_']").val("");
	});
/*End*/
});



    </script>
@include("layouts.php_js_validation")
@endsection
