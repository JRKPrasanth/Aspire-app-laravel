
@extends('layouts.header')
@section('content')

<div id="accordion">
<div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a role="button">
                                       
                                      Subcontract Supplier
                                    </a>
                                </h4>
                            </div>

</div>

<div class="panel panel-visible" id="spy1">
<!--<div class="panel-heading">-->
<div class="panel-title">
  <div class="row">
  <div class="col-md-12">
<?php include('toolbar.php'); ?>
   
</div>
</div>
<div class="row">
    <div class="col-md-12">
         <hr class="xlg">
    </div>
</div>
</div>
<div class="row" >
<div class="col-md-12" style="padding:15px;">
<table id="suppliergrid"></table>
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
 
	var price="{{$price}}";
	var supplier="{{$supplier}}";
$("#suppliergrid").jqGrid({
url: "getsubcontractsupplierData",
datatype: "json",
mtype: "GET",
		 colModel: [
	            { name: "subcontract_supplier_id", label: "id",hidden:true },
                { name: "subcontract_number", label: "Subcontract Supplier Number"},
                { name: "subcontract_name", label: "Subcontract Supplier Name"},
                { name: "suppliertype_name", label: "Supplier Type"},
		{ name: "pricelist_name", label: "PriceList Name"},
                { name: "gst_no", label: "GST No"}
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
		pager: "#suppliergrid"
      });
jQuery("#suppliergrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
$("#suppliergrid").jqGrid("setLabel", "rn", "S.No");
jQuery("#gs_suppliergrid_supplier_type_id").select2();
jQuery("#gs_suppliergrid_default_pricelist_id").select2();


jQuery("#suppliergrid").jqGrid('hideCol',["gst_no"]);

	showcolumn('suppliergrid');
	/*Export To Excel & Pdf*/
	 $(document).on('click',".exportpdf",function() {
   	$("#suppliergrid").jqGrid('exportToPdf', {
  title: null,
  orientation: 'portrait',
  pageSize: 'A4',
  description: null,
  onBeforeExport: null,
  download: 'download',
  includeLabels : true,
  includeGroupHeader : true,
  includeFooter: true,
  fileName : "Create Supplier.pdf",
  mimetype : "application/pdf"  
});
			});
    $(document).on('click',".exportexcel",function() {
$("#suppliergrid").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "Create Supllier.xlsx"
    					
				})		 
	 
	 
	
});
/*End*/
jQuery("#showcolumn_old").click(function() {

  var btn = $(".showcolumn").val();

  if(btn =="1" )
  {
    jQuery("#suppliergrid").jqGrid('showCol',["gst_no"]);
    $(".showcolumn").val('2');
  }else{
    jQuery("#suppliergrid").jqGrid('hideCol',["gst_no"]);
    $(".showcolumn").val('1');
  }

});
/*create Function*/
$(".create").click(function(){ 
	
	var url="{{  URL::to('subcontractcreate/0')}}";
	
	window.location.replace(url);
	
	});
/*End*/

/*Edit Function*/
$("#edit").click(function()
{
	var gr = jQuery("#suppliergrid").jqGrid('getGridParam','selrow');
	var cellValue = jQuery("#suppliergrid").jqGrid ('getCell', gr, 'subcontract_supplier_id');
	if( gr )
	{
              var url ="{{ URL::to('suppliernamechk') }}/" +cellValue;
             $.get(url,function(data)
        { 
          var frieghtcount=$.trim(data);
             if(frieghtcount =='0')
          { 
           var url = "subcontractedit";
          var editUrl = url + '/' + cellValue + '/edit';
		window.location.replace('subcontractedit/' +cellValue);
          }
           else
           {
                var url = "subcontractedit";
                var editUrl = url + '/' + cellValue +"?status=edit";
		window.location.replace(editUrl);
           }
          
        });  
		
	}
	else
	{
	notyMsg("error","Please Select Row");
	}
});
/*End*/
/*View Function*/
$("#view").click(function()
{
	var gr = jQuery("#suppliergrid").jqGrid('getGridParam','selrow');
	var cellValue = jQuery("#suppliergrid").jqGrid ('getCell', gr, 'subcontract_supplier_id');
	if( gr )
	{
        var url = "subcontractview";
        var editUrl = url + '/' + cellValue + '/show';
		window.location.replace('subcontractview/' +cellValue);
	}
	else
	{
	notyMsg("error","Please Select Row");
	}
});
/*End*/
$("#clearsearch").click(function() {
		var grid = $("#suppliergrid");
		grid.jqGrid('setGridParam',{search:false});

		var postData = grid.jqGrid('getGridParam','postData');
		$.extend(postData,{filters:""});
		grid.trigger("reloadGrid",[{page:1}]);
                 $('input[id*="gs_"]').val("");
                
	});

/*Delete Function*/
$("#delete").click(function(){

  var gr = jQuery("#suppliergrid").jqGrid('getGridParam','selrow');
  var cellValue = jQuery("#suppliergrid").jqGrid ('getCell', gr,'subcontract_supplier_id');
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
      closeOnCancel:!1,
    }, function(e) {
    if(e == true)
      {
        var url ="{{ url('supplierdelete') }}/" +cellValue;
        $.get(url,function(data)
        {
          var data = $.trim(data);
          var red_url ="{{ url('supplier') }}";
          if(data =='0')
          {
            notyMsg('success','Deleted Successfully!!!');
             $('.clearsearch').trigger('click');
            setTimeout(function(){
            $("#suppliergrid")[0].triggerToolbar();
            }, 1500);
          }
          if(data=='2')
          {
            notyMsg('error',"You Cant't delete , Supplier Used in SomeWhere!!!");
             $('.clearsearch').trigger('click');
            setTimeout(function(){
              $("#suppliergrid")[0].triggerToolbar();
            }, 1500);
          }
        });
      }
      else
      {
        $('.apply').css('display','none');
         $('.clearsearch').trigger('click');
        swal("Cancelled");
      }
    });
  $('.apply').css('display','none');
  }
  else
  {
  notyMsg("error","Please Select Row");
  }
});

/*End*/

/*********** CLEAR search **************************/

	


});
    </script>
@endsection
