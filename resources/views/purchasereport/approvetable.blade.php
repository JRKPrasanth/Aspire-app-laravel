@extends('layouts.header')
@section('content')
<script src="{{ asset('js/jquery.searchFilter.js')}}"></script>
<style type="text/css">
  .datepicker{

    z-index:1052 !important;}


.Menu {
    position: absolute;
    top: 77%;
    left: auto;
    z-index: 1000;
    display: none;
    float: left;
    min-width: 160px;
    padding: 5px 0;
    margin: 2px 0 0;
    font-size: 14px;
    text-align: left;
    list-style: none;
    background-color: #fff;
    -webkit-background-clip: padding-box;
    background-clip: padding-box;
    border: 1px solid #ccc;
    border: 1px solid rgba(0, 0, 0, .15);
    border-radius: 4px;
    -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, .175);
    box-shadow: 0 6px 12px rgba(0, 0, 0, .175);
}

.ui-widget { font-family: Lucida Grande, Lucida Sans, Arial, sans-serif; font-size: 1.1em; }
.ui-widget .ui-widget { font-size: 12px; }
.ui-widget input, .ui-widget select, .ui-widget textarea, .ui-widget button { font-family: Lucida Grande, Lucida Sans, Arial, sans-serif; font-size: 1em; }
.ui-widget-content { border: 1px solid #455986; background: #fcfdfd url(images/ui-bg_inset-hard_100_fcfdfd_1x100.png) 50% bottom repeat-x; color: #222222; }
.ui-widget-content a { color: #222222; }
.ui-widget-header {border: 1px solid #4297d7;background: #455986 url(images/ui-bg_gloss-wave_55_5c9ccc_500x100.png) 50% 50% repeat-x;color: #ffffff;font-weight: bold;}
.ui-widget-header a { color: #ffffff; }
.ui-state-default, .ui-widget-content .ui-state-default{
       background: #f6f6f6;
    border: none;
    font-size: 16px;
    font-family: philoshoper;
    font-weight: normal;
    color: #454545;
}
td, th {
    padding: 1px;
}
.ui-corner-all {
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    border-radius: 5px;
}
input{
    height: 26px;
}



</style>


<div id="accordion">
<div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a role="button">

                                        Purchase Order Pending Qty Report
                                    </a>
                                </h4>
                            </div>

</div>




 <div class="row">
    <div class="col-md-12" >
   <table id="popendingqtygrid"></table>
  </div>
  </div>

<?php //dd($pageMethod); ?>

<script type="text/javascript">

$( document ).ready(function() {
	
	     var grid = $("#popendingqtygrid");
            grid.jqGrid({
                 url: "soorderapprovegriddatareport",
      datatype: "json",
      mtype: "GET",
               colModel: [
            { name: "po_hdr_id",align: "center",hidden:true},
		    { name: "po_number", align: "center",label:"Po Number" },
            { name: "po_date", align: "center",label:"Po Date" },
            { name: "sup_name",  align: "center",label: "Supplier Name"},
            { name: "po_status",  align: "center",label: "Po Status"},
             ],
                postData: {
                    filters:'{"groupOp":"AND","rules":['+
                            '{"field":"po_number","op":"gt","data":""}'+
                            ',{"field":"po_date","op":"lt","data":""}]}'
                },
                search:true,
                pager:'#popendingqtygrid',
                jsonReader: {cell:""},
                rowNum: 10,
                rowList: [5, 10, 20, 50],
                sortname: 'po_hdr_id',
                sortorder: 'asc',
                viewrecords: true,
                height: "100%",
				rownumbers: true 
               
            });
           $('#popendingqtygrid').navGrid("#popendingqtygrid", {                
                search: true, // show search button on the toolbar
                add: false,
                edit: false,
                del: false,
                refresh: true
            },
            {}, // edit options
            {}, // add options
            {}, // delete options
            { 
				multipleSearch: true, 
				multipleGroup : true,
				buttons : [
					{ 
						side : "right",
						text : "Custom",
						position : "first",
						click : function( form, params, event) {
							alert("Custom action in search form");
						}
					}
				]
			}
			);
	
 jQuery("#popendingqtygrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
		$("#popendingqtygrid").jqGrid("setLabel", "rn", "S.No");
});



    </script>
@endsection
