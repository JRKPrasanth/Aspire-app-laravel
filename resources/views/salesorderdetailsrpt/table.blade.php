@extends('layouts.header')
@section('content')



<div id="accordion">
<div class="panel-heading" role="tab" id="headingOne">
    <h4 class="panel-title">
    <a role="button">Sales Order Details</a>
    </h4>
</div>
</div>



<div class="panel panel-visible" id="spy1">
	<div class="row">
	<div class="col-md-12">
<?php include('toolbar.php'); ?>
<button type='button' href='' class='btn clearsearch'>Clear Search </button>
<button type='button' id="showcolumn" value="1" class='btn showcolumn showcolumns'> Show column </button>
</div>
</div>


<div class="row">
<div class="col-md-12">
<hr class="xlg">
</div>
</div>

<div class="row">
<div class="col-md-12">
<div id="sodetailsgrid"></div>
</div>
</div>

<div class="row">
<div class="col-md-12">
<hr class="xlg">
</div>
</div>


</div>
<input type="hidden" class="sohdrid" value="">
<input type="hidden" class="sostatusval" value="">
<script src="{{ asset('js/pqselect.min.js')}}"></script>
	<script src="{{ asset('js/pqgrid.min.js')}}"></script>
	<script src="{{ asset('js/pq-localize-en.js')}}"></script>
	 <link rel="stylesheet" href="{{ asset('css/pqselect.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/pqgrid.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/pqgrid.ui.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/pqgrid.css')}}" />
<script src="{{ asset('js/filesaver.js')}}"></script>

<script type="text/javascript">
$( document ).ready(function() {
    
    
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
    
    var colModel=[
	{ dataIndx: "sales_hdr_id", align: "center",title: " Id" ,hidden:true},
	{ dataIndx: "order_status_id", align: "center",title: "Status", filter: { crules: [{condition: "begin" }] }},
	{ dataIndx: "sales_order_date",align: "center",title: "SO Date", filter: { crules: [{condition: "begin" }] } },
        { dataIndx: "sales_order_no", align: "center",title: "Sales Order Number", filter: { crules: [{condition: "begin" }] } },
        { dataIndx: "ship_to_customer_id", align: "center",title: "ustomer Name", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "order_total", align: "center",title: "Amount", filter: { crules: [{condition: "begin" }] } },
        
	];
    
    
          var dataModel = {
            location: "remote",            
            dataType: "JSON",
            method: "GET",
            url: "{{URL::to('getsodetailsData')}}",
			 getData: function (dataJSON) {
                var data = dataJSON.data;
                return { curPage: dataJSON.curPage, totalRecords: dataJSON.totalRecords, data: data };
            }
            //url: "/pro/orders.php",//for PHP
        }
        
         var obj = {
            width:'100%',
            dataModel: dataModel,
            flex:{one: true},
            colModel: colModel,
			 pageModel: { type: "remote", rPP: 10, strRpp: "{0}" },
            //pageModel: { type: 'local', rPP: 20 },            
            wrap: false,
            showBottom: false,            
            editable: false,  
            menuIcon: true,
			 editable: false,
            numberCell: { show: false },
            selectionModel: { type: 'row' },
            menuUI: {
                singleFilter: true
            },
            filterModel: { 
                on: true,             
                header: true, 
                type: 'remote', 
                menuIcon: true 
            },
            title: "Sales Orders details",
            resizable: true,
            hwrap:false,
            freezeCols: 2,
			  toolbar: {
                items: [
                {
                    type: 'button',
                    label: "Export to Excel",
                    icon: 'ui-icon-arrowthickstop-1-s',
                    listener: function () {

                        var blob = this.exportData({
                                //url: "/pro/demos/exportData",
                                format: 'xlsx',                                
                                render: true,
                                type: 'blob'
                            });                        
                        saveAs(blob, "pqGrid.xlsx" );
                    }
                }]
            },
			   formulas: [
                ["[order_status_id", function( rd ){
                    var attr = rd.pq_cellattr = rd.pq_cellattr || {};
                    if(rd.order_status_id =="DRAFT"){                        
                        attr.rank = attr.order_status_id = { style: 'color: #fb0303;font-weight:550;'}                        
                    }
                    else{
                        attr.rank = attr.order_status_id = { style: 'color: #037f03; font-weight:550;'}
                    }
                    return rd.order_status_id;
                }]
            ],
			 rowSelect: function (evt, ui) {
                
                var str = JSON.stringify(ui, function(key, value){                    
                    if( key.indexOf("pq_") !== 0){
                        return value;
                    }
                }, 2)
                var val=$.parseJSON(str);
				var soid=val['addList'][0]['rowData'].sales_hdr_id;
				var sostatus=val['addList'][0]['rowData'].order_status_id;
				 $('.sohdrid').val(soid);
				 $('.sostatusval').val(sostatus);
            }
        };
        
      $("#sodetailsgrid").pqGrid(obj);
$( "#sodetailsgrid" ).pqGrid({ scrollModel:{autoFit: true }});  


	 $(document).on('click',".exportexcel",function() {
		
    $("#sodetailsgrid").jqGrid("exportToExcel",{
                                            includeLabels : true,
                                            includeGroupHeader : true,
                                            includeFooter: true,
                                            fileName : "Sales Order Details.xlsx"

                                    })		 
	 
	 
	
});


$('.view').click(function(){
    
var po_hdrid=$('.pohdrid').val();
var po_status=$('.postatusval').val();
	if( po_hdrid!="" )
	{
		
		if(po_status=='APPROVED' || po_status=='COMPLETED' || po_status=='CLOSED'){
			$('.charts').hide();
			$('.workbench').show();
		var url="{{URL::to('purchasereport')}}/"+po_hdrid;
		$.get(url,function(data){
			$(".podetail").html(data);
		});
		}else{
			var url="{{ URL::to('purchaseorderview')}}/"+po_hdrid+"/?report='report'";
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




function fontColorFormat(cellvalue, options, rowObject) {
 var color = "red";
 var cellHtml = "<span style='color:" + color + "' originalValue='" + cellvalue + "'>" + cellvalue + "</span>";
 return cellHtml;
 }
var customeropt="{{$customeropt}}";
$("#sodetailsgrid").jqGrid({
url: "getsodetailsData",
datatype: "json",
mtype: "GET",
	 colModel: [
	{ name: "sales_hdr_id", label: "id",hidden:true },
        { name: "order_status_id", label: "Status"},
        { name: "sales_order_date", label: "SO Date"},
//        { name: "delivery_date", label: "Delivery Date"},
        { name: "sales_order_no", label: "Sales Order Number"},
	{ name: "ship_to_customer_id",  align: "center",label: "Customer Name",stype:'select', editoptions:{value:customeropt}},
	{ name: "order_total", label: "Amount"},
        ],
        iconSet: "fontAwesome",
        rowNum: 10,
        rowList: [10,20,50,100],
        sortorder: "desc",
        viewrecords: true,
        gridview: true,
        rownumbers:true,
        caption: "",
	autowidth:true,
        pager: true,
//         footerrow: true, 
        searching: {
            defaultSearch: "cn"
        }
      });
jQuery("#sodetailsgrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
//jQuery("#gs_sodetailsgrid_supplier_id").select2();
showcolumn('sodetailsgrid');


/*Karthigaa purpose:clear search the jqgrid*/
	$(".clearsearch").click(function(){
            var grid = $("#sodetailsgrid");
            grid.jqGrid('setGridParam',{search:false});

            var postData = grid.jqGrid('getGridParam','postData');
            $.extend(postData,{filters:""});
            grid.trigger("reloadGrid",[{page:1}]);
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
