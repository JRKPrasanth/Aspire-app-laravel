  <style type="text/css">
  .modal {
  text-align: center;
  padding: 0!important;
}

.modal:before {
  content: '';
  
  
  vertical-align: middle;
  margin-right: -4px; /* Adjusts for spacing */
}

.modal-dialog {
  
  text-align: left;
  vertical-align: middle;
}
.card-body{
	padding: 0px;
}
  </style>
<div class="panel panel-visible" id="spy1">
<div class="row">
	
</div>
<div class="row soworkbench">
<div class="sodetails">	
	</div>
	</div>
  <div class="row charts">
	   <div class="col-md-6" >
<div id="container" ></div>
  </div>
	   <div class="col-md-6" >
<div id="container1" ></div>
  </div>
<div class="col-md-12">
<button type='button' href='' class='btn download exportexcel'>Export As Excel </button>
<button type='button' class='btn download exportpdf'> Export As Pdf  </button> 
<button type='button' class='btn vie view'> View So Details </button> 
</div>
    <div class="col-md-12" >
   <table id="sogrid"></table>
  </div>
	  
  </div>
  </div>

<div class="modal fade" id="myModal" role="dialog" style="width:85%;margin:0 auto;overflow-y:hidden;">
<div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body dialogue" style="height:530px;">

        </div>

      </div>

    </div>

<script type="text/javascript">

$( document ).ready(function() {
	var str="{{$chartresult}}";
	var finalData = JSON.parse(str.replace(/&quot;/g,'"'));
	var bar="{{$barchart}}";
	var bar = JSON.parse(bar.replace(/&quot;/g,'"'));
	var drilldown="{{$drilldown}}";
	var drilldown = JSON.parse(drilldown.replace(/&quot;/g,'"'));
	var statusdrilldown="{{$statusdrilldown}}";
	var statusdrilldown = JSON.parse(statusdrilldown.replace(/&quot;/g,'"'));
	console.log(bar);
	
        initDateEdit = function (elem) {
				$(elem).datepicker({
					dateFormat: "yy-mm-dd",
//dateFormat: "yy-mm-dd",
					autoSize: true,
					changeYear: true,
					changeMonth: true,
					showButtonPanel: true,
					showWeek: true
				});
			},
                        initDateSearch = function (elem) {
				setTimeout(function () {
					initDateEdit(elem);
				}, 100);
			};
var rowsToColor = [];
       $("#sogrid").jqGrid({
       datatype: "local",
      mtype: "GET",
	 colNames: ["","","SO Number","SO Date","Customer Name","SO Status","Grand Total"],
        colModel: [
            { name: "sales_hdr_id",align: "center",hidden:true},
			{ name: "sub_id",align: "center",hidden:true},
            { name: "sales_order_no", align: "center",search: true },
            { name: "sales_order_date", align: "center",formatter: 'date',  formatoptions: { srcformat: 'Y-m-d', newformat: 'd-m-Y'},},
           { name: "customer_name",  align: "center",label: "Customer Name"},
			   { name: "order_status_id", align: "center",formatter:fontformst },
            { name: "order_total", align: "center" }
         
              ],
	rowNum:10,
		viewrecords: true,
		footerrow: true,
		sortorder: "desc",
		userDataOnFooter: true, // use the userData parameter of the JSON response to display data on footer
		width: 780,
		height: 300,
		  rownumbers: true ,
		rowList: [10, 20, 50, 100,250,500,1000],
    	pager: "#sogrid"
      
    
});
	var mydata='{{$result}}';
	mydata=JSON.parse(mydata.replace(/&quot;/g,'"'));
	for(var i=0;i<=mydata.length;i++)
	jQuery("#sogrid").jqGrid('addRowData',i+1,mydata[i]);
	
jQuery("#sogrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false,defaultSearch: 'cn'});

 
		$("#sogrid").jqGrid("setLabel", "rn", "S.No");
 $(document).on('click',".exportpdf",function() {
   	$("#sogrid").jqGrid('exportToPdf', {
  title: null,
  orientation: 'portrait',
  pageSize: 'A4',
  description: null,
  onBeforeExport: null,
  download: 'download',
  includeLabels : true,
  includeGroupHeader : true,
  includeFooter: true,
  fileName : "Sales Order Details Report.pdf",
  mimetype : "application/pdf"  
});
});
 $(document).on('click',".exportexcel",function() {			
$("#sogrid").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "Sales Order Details Report.xlsx"
    					
				});		 
	});
/*Karthigaa Purpose for Show Coloumn*/
showcolumn('sogrid');
$("#sogrid").jqGrid('hideCol',["remarks"]);

	$('.view').click(function(){
    
	var index = $("#sogrid").jqGrid('getGridParam','selrow');
	var sohdrid = $("#sogrid").jqGrid ('getCell', index, 'sales_hdr_id');
	var so_status = $("#sogrid").jqGrid ('getCell', index, 'order_status_id');
	if( index )
	{
		var val=$.parseHTML(so_status);
	var so_status=val[0].innerText;
		if(so_status=='APPROVED' || so_status=='COMPLETED' || so_status=='CLOSED'){
			$('.charts').hide();
			$('.soworkbench').show();
		var url="{{URL::to('salesorderrpt')}}/"+sohdrid;
		$.get(url,function(data){
			$(".sodetails").html(data);
		});
		}else{
			var url="{{ URL::to('soorderview')}}/"+sohdrid+"/?report='report'";
		$.get(url,function(data){
			$(".dialogue").html(data);
			$('#myModal').modal('show');
		});
	}
	}
	else
	{
			 notyMsg("info","Please Select Row");
	}	
	
});
$(document).on('click','.sowork_bench',function(){

			$('.soworkbench').hide();
	$('.charts').show();
	return false;

});	
	

  /***** Karthigaa Purpose For CLEAR search ********/
	$("#clearsearch").click(function() {
		var grid = $("#sogrid");
		grid.jqGrid('setGridParam',{search:false});

		var postData = grid.jqGrid('getGridParam','postData');
		$.extend(postData,{filters:""});
		grid.trigger("reloadGrid",[{page:1}]);
                 $('input[id*="gs_"]').val("");
	});
	
/*End*/
Highcharts.chart('container1', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: "Sales Order Details From {{$from}} to {{$to}}"
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.y:.1f}</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.y:.1f}',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
            }
        }
    },
	tooltip: {
    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
    pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
  },
    series: [{
        name: 'Status',
        colorByPoint: true,
        data:finalData
    }],
	 "drilldown": {
        "series":statusdrilldown
    }
	
});	
	
	Highcharts.chart('container', {
    chart: {
        type: 'column'
    },
    title: {
        text:"Sales Order Details From {{$from}} to {{$to}}"
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        type: 'category'
    },
    yAxis: {
        title: {
            text: ''
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y:.1f}'
            }
        }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}</b> of total<br/>'
    },

    "series": [
        {
            "name": "Customer",
            "colorByPoint": true,
            "data":bar
        }
    ],
    "drilldown": {
        "series":drilldown
    }
});
	
	
	
$(".highcharts-credits").html('iFive Technology');	

	
});
function fontformst(cellvalue,options,rowObject)
	{
		var color='Black';
		if(cellvalue=="INITIATED")
		{
			color='Blue ';
		}
		else if(cellvalue=="APPROVED")
		{
			color="DarkGreen";
		}
		else if(cellvalue=="REJECTED")
		{
			color="red";
		}
		else if(cellvalue=="CANCELLED")
		{
			color="OrangeRed";
		}
		else if(cellvalue=="COMPLETED")
		{
			color="ForestGreen ";
		} 
		else if(cellvalue=="DRAFT")
		{
			color="DarkSlateGray";
		}
		else if(cellvalue=="CLOSED")
		{
			color="DarkOrchid ";
		}
		var html='<span class="sostatus"  style="color:'+color+';" originalValue="'+cellvalue+'" >'+cellvalue+'</span>';
		return html;
	}


    </script>
