@extends('layouts.header')
@section('content')
<style>
    
  .tooltip {
  position: relative;
  display: inline-block;
  opacity: 1;
  z-index: unset;
}

.tooltip .tooltiptext {
  width: 120px;
  background-color: black;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 5px 0;

  /* Position the tooltip */
  position: absolute;
  z-index: 1;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
}
.tooltip .tooltiptext {
    width: 250px;
    top: -30%;
    left: 50%;
    margin-left: -110px;
}
}
</style>
<div id="accordion">
<div class="panel-heading" role="tab" id="headingOne">
    <h4 class="panel-title">
    <?php if($pageMethod=="elpaymentreqapproval"){ ?>
        <a role="button">EL Encashment - Payment Request Approval</a>
   <?php }   else { ?> 
    <a role="button">EL Encashment - Payment Request</a>
    <?php } ?>
    </h4>
</div>
</div>
  <div class="panel panel-visible" id="spy1">
  <div class="row">
  <div class="col-md-12">
<div class="col-md-4">
 <!--<button type='button' id="paymentrequest"  class='btn paymentrequest vie'> Payment Request </button>-->
  <?php include('toolbar.php'); ?>

<!--<a id="clearsearch"><button type="button" class="btn clearsearch">Clear Search</button></a>
<button type='button' id="view"  class='btn view vie'> View </button>
<button type='button' id="showcolumn" value="1" class='btn showcolumn showcolumns'> Show column </button>-->
</div>
	

</div>
</div>


<div class="row">
<div class="col-md-12">
<hr class="xlg">
</div>
</div>
    <h4 class="panel-title">
        	<div class="row">
    <?php if($pageMethod=="paymentrequestapproveelencash"){ ?>
                          
					  <div class="col-md-3">
									<button type='button'  class='btn paymentrequest' id='paymentrequestapprove'>Payment Request Approve</button>
							</div>
				
   <?php }   else { ?> 
                        				
					<div class="col-md-3">
									<button type='button'  class='btn paymentrequest vie' id='paymentrequest'>Payment Request</button>
							</div>
				
    <?php } ?>
    
<div class="col-md-7">
     <?php if($pageMethod=="paymentrequestelencash"){ ?>
    ALL. TOTAL: <?php if($sum_inv_tot[0]->total > 0){ ?> <a class="btn inv_grand_tot">INR.{{$sum_inv_tot[0]->total}} </a> <?php } else { ?> <a class="btn inv_grand_tot">INR.0.00 </a> <?php } ?>
    Total (based on selection): <div class="tooltip"><a id="inv_search_tot" class="btn inv_search_tot" >INR. 0.00</a><span class="tooltiptext">Click Here to get Selected Total Amount</span></div>
<?php }   else { ?> 
    Total (based on selection): <div class="tooltip"><a id="inv_search_tot" class="btn inv_search_tot" >INR. 0.00</a><span class="tooltiptext">Click Here to get Selected Total Amount</span></div>

   <?php } ?>
</div>
</h4>
<div class="row">
	<div class="col-md-12" >

    <table id="elencashpaymentgrid"></table>
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

var date_format="{{\Session::get('p_date_format')}}";
var pagemethod="{{$pageMethod}}";
$("#elencashpaymentgrid").jqGrid({

      url: "requesteldata?pagemethod="+pagemethod,
      datatype: "json",
      mtype: "GET",
        colModel: [
                { name: "id", label: "id", width: 150, hidden: true },
                { name: "employee_id", label: "employee_id", width: 150, hidden: true},
                { name: "emp_number", label: "Employee Number", width: 150},
                { name: "emp_name", label: "Employee Name", width: 150},
                { name: "sub_department_name", label: "Department", width: 150},
                { name: "active", label: "Active", width: 150},
                { name: "status", label: "Status", width: 150},
                { name: "doj", label: "Date of joining", width: 150},
                { name: "total_el", label: "Total EL", width: 150},
                { name: "extra_el", label: "Eligible for Encashment", width: 150},
                { name: "salary_day", label: "daysalary", width: 150, hidden: true},
                { name: "total_amt", label: "Total Amount", width: 150},
             ],
	rowNum:20,
		 viewrecords: true,
		footerrow: true,
		sortorder: "desc",
		userDataOnFooter: true, // use the userData parameter of the JSON response to display data on footer
		  rownumbers: true ,
		rowList: [10,20,50,100,250,500,1000],
		pager: "#elencashpaymentgrid",
	multiselect:true,
	multiPageSelection:true,
   onSelectRow: function(id){
	var gr = jQuery("#elencashpaymentgrid").jqGrid('getGridParam','selrow');
	var cellValue = jQuery("#elencashpaymentgrid").jqGrid ('getCell', gr, 'empexpensesview');

        var $grid = $("#elencashpaymentgrid"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,
	    sovalue = [];
		var checksup='';
		var j=0;
		
    for (i = 0, n = selIds.length; i < n; i++) {
  var check_supplier= $grid.jqGrid("getCell", selIds[i], "employee_id");
  	console.log(check_supplier);
		if(check_supplier!=false)
		{

		if(j==0)
			checksup=check_supplier;
		if(checksup!=check_supplier)
			checksup='1';
			j=1;

		}
		else
		{
			j=0;
		}
		if(checksup=='1')
{
	var status="Error";
	var msg="Can't Create Payment For Different Employee";
		notyMsg(status,msg);
	$("#elencashpaymentgrid")[0].triggerToolbar();
}


			

      }
	},

});
jQuery("#elencashpaymentgrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
 $("#elencashpaymentgrid").jqGrid("setLabel", "rn", "S.No");
jQuery("#gs_elencashpaymentgrid_supplier_id").select2();
jQuery("#elencashpaymentgrid").jqGrid('hideCol','cb');
 /*Karthigaa Purpose for Show Coloumn*/
showcolumn('elencashpaymentgrid');
$("#gs_expense_date,#gs_due_date,#gs_po_date").attr("placeholder","Eg:yyyy-mm-dd");
		$("#productcatgorygrid").jqGrid("setLabel", "rn", "S.No");
	 $(document).on('click',".exportpdf",function() {
		
   	$("#elencashpaymentgrid").jqGrid('exportToPdf', {
  title: null,
  orientation: 'portrait',
  pageSize: 'A4',
  description: null,
  onBeforeExport: null,
  download: 'download',
  includeLabels : true,
  includeGroupHeader : true,
  includeFooter: true,
  fileName : "Payment Request.pdf",
  mimetype : "application/pdf"  
});
});
	 $(document).on('click',".exportexcel",function() {
$("#elencashpaymentgrid").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "Payment Request.xlsx"
    					
				})		 
});

 /*Karthigaa purpose for Multiple row Payment*/
 $("#paymentrequest").click(function(){
	var gr = jQuery("#elencashpaymentgrid").jqGrid('getGridParam','selrow');
	var val=$(this).val();
        var $grid = $("#elencashpaymentgrid"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,cellvalues = [];
      for (i = 0, n = selIds.length; i < n; i++) {
	var v=	$grid.jqGrid("getCell", selIds[i], "id");
		if(v!=false)
		cellvalues.push(v);
	}
       if(gr){
        var url = "{{ URL::to('getelpaymentreq')}}/"+cellvalues+"?status="+status;
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
	notyMsg("info","Please Select a Row");
	}
});
	
	
	// approve
	 $("#paymentrequestapprove").click(function(){
	var gr = jQuery("#elencashpaymentgrid").jqGrid('getGridParam','selrow');
	var val=$(this).val();
        var $grid = $("#elencashpaymentgrid"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,cellvalues = [];
      for (i = 0, n = selIds.length; i < n; i++) {
	var v=	$grid.jqGrid("getCell", selIds[i], "id");
		if(v!=false)
		cellvalues.push(v);
	}
       if(gr){
        var url = "{{ URL::to('elencashreqapprove')}}/"+cellvalues+"?status="+status;
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
	notyMsg("info","Please Select a Row");
	}
});

	
$("#exp_search_tot").click(function()
            {
                var gr = jQuery("#elencashpaymentgrid").jqGrid('getGridParam','selrow');
                //var invoice_grand_total = jQuery("#elencashpaymentgrid").jqGrid ('getCell', gr, 'invoice_grand_total');
                var $grid = $("#elencashpaymentgrid"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,
	 cellvalues=[];
		  var pid = [];
	     var slid = [];
			var check='';

			//alert(cellvalues);
		var j=0;
		//alert(selIds.length);
    for (i = 0, n = selIds.length; i < n; i++) {
	var v=	$grid.jqGrid("getCell", selIds[i],"balance_amounts");

		if(v!=false){
		cellvalues.push(v);
		var sum_tot = 0;
        for (let f = 0; f < cellvalues.length; f++) {
            sum_tot += parseFloat(cellvalues[f]);
        }
		console.log(sum_tot);
		}
	}
                if(gr)
                {
                    

                    $(".exp_search_tot").html('INR. '+sum_tot);
                
                }else{
                  $(".exp_search_tot").html('INR. 0.00');  
                } 
            });

		$("#inv_search_tot").click(function()
            {
                var gr = jQuery("#elencashpaymentgrid").jqGrid('getGridParam','selrow');
                //var invoice_grand_total = jQuery("#grid1").jqGrid ('getCell', gr, 'invoice_grand_total');
                var $grid = $("#elencashpaymentgrid"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,
	 cellvalues=[];
		  var pid = [];
	     var slid = [];
			var check='';

			//alert(cellvalues);
		var j=0;
		//alert(selIds.length);
    for (i = 0, n = selIds.length; i < n; i++) {
	var v=	$grid.jqGrid("getCell", selIds[i],"total_amt");

		if(v!=false){
		cellvalues.push(v);
		var sum_tot = 0;
        for (let f = 0; f < cellvalues.length; f++) {
            sum_tot += parseFloat(cellvalues[f]);
        }
		console.log(sum_tot);
		}
	}
                if(gr)
                {
                    

                    $(".inv_search_tot").html('INR. '+sum_tot);
                
                }else{
                  $(".inv_search_tot").html('INR. 0.00');  
                } 
            });  
/***** Karthigaa Purpose For CLEAR search ********/
	$("#clearsearch").click(function() {
		var grid = $("#elencashpaymentgrid");
		grid.jqGrid('setGridParam',{search:false});

		var postData = grid.jqGrid('getGridParam','postData');
		$.extend(postData,{filters:""});
		grid.trigger("reloadGrid",[{page:1}]);
               $('input[id*="gs_"]').val("");
               
	});
/*End*/
});
    </script>
@endsection
