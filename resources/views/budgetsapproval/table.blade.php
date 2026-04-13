@extends('layouts.header')
@section('content')


<div id="accordion">
<div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a role="button">
                                       Budget Approval
                                    </a>
        </h4>
  </div>
</div>



<div class="panel panel-visible" id="spy1">
<div class="row">
	<div class="col-md-12">
             <?php include('toolbar.php'); ?>
<!--<button type='button' id="approve" data-value="APPROVED" class='btn approve vie'> APPROVE </button>-->
<!--<button type='button' id="reject"  data-value="REJECTED" class='btn reject vie'> REJECT </button>-->

</div>
</div>

<div class="row">
<div class="col-md-12">
<hr class="xlg">
</div>
</div>

<div class="row">
<div class="col-md-12">
<table id="budgetsgrid"></table>
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

$("#budgetsgrid").jqGrid({
url: "getBudgetsapprovalData",
datatype: "json",
mtype: "GET",
	 colModel: [
	{ name: "budget_hdr_id", label: "id",hidden:true },
	{ name: "budget_name", label: "Budget Name" },
        { name: "budget_date", label: "Budget Date"},
        { name: "budget_status", label: "Budget Status"},
        { name: "budget_year", label: "Budget Year"},
        { name: "budget_line_total", label: "Budget Line Total"},
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
		pager: "#budgetsgrid"
      });
jQuery("#budgetsgrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
showcolumn('budgetsgrid');
 $("#budgetsgrid").jqGrid("setLabel", "rn", "S.No");
 $(document).on('click',".exportpdf",function() {
   	$("#budgetsgrid").jqGrid('exportToPdf', {
  title: null,
  orientation: 'portrait',
  pageSize: 'A4',
  description: null,
  onBeforeExport: null,
  download: 'download',
  includeLabels : true,
  includeGroupHeader : true,
  includeFooter: true,
  fileName : "Budget Review.pdf",
  mimetype : "application/pdf"  
});
	});

  $(document).on('click',".exportexcel",function() { 		
$("#budgetsgrid").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "Budget Review.xlsx"
    					
				})		 
	});
$("#approve").click(function(){
	var gr = jQuery("#budgetsgrid").jqGrid('getGridParam','selrow');
	var id = jQuery("#budgetsgrid").jqGrid ('getCell', gr, 'budget_hdr_id');
         var status = "APPROVED";
        //alert(status);
        if(gr){
        var url = "{{ URL::to('getbudgetapproval')}}/"+id+"/"+status;
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
$("#reject").click(function(){
	var gr = jQuery("#budgetsgrid").jqGrid('getGridParam','selrow');
	var id = jQuery("#budgetsgrid").jqGrid ('getCell', gr, 'budget_hdr_id');
         var status = "REJECTED";
        //alert(status);
        if(gr){
        var url = "{{ URL::to('getbudgetapproval')}}/"+id+"/"+status;
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
  var gr=$('#budgetsgrid').jqGrid('getGridParam','selrow');
  var cellValue = $("#budgetsgrid").jqGrid ('getCell', gr, 'budget_hdr_id');
  if(gr){
     var url="budgetsview";
     var viewurl = url+'/'+cellValue+'/view';
     window.location.replace('budgetsview/' +cellValue);
  }
  else
  {
  notyMsg("info","Please Select Row");
  }
});

/*Karthigaa purpose:clear search the jqgrid*/
	$(".clearsearch").click(function(){
            var grid = $("#budgetsgrid");
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
