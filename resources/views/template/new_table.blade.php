@extends('layouts.header')
@section('content')
<div class="container">
<div class="row">
<div class="col-md-12">
<div class="panel panel-visible" id="spy1">
<div class="panel-heading">
<div class="panel-title hidden-xs">
<span class="glyphicon glyphicon-tasks"></span>
<a href='salesenquirycreate' class='btn btn-sm btn-success'> <i class="fa fa-plus"> Create </i></a>
<a id="editdata"  class="btn btn-sm btn-primary"><i class="fa fa-edit"> Edit </i></a>
<a id="viewdata"  class="btn btn-sm btn-info"><i class="fa fa-eye"> View </i></a>
<button type='button' href='' class='btn btn-sm btn-danger'><i class="fa fa-trash"> Delete </i></button>
</div>
</div>
</div>
</div>
</div>
<div class="row">
<div class="col-md-12">
<table id="grid1"></table>
</div>
</div>
@extends('layouts.footer')
</div>

<script type="text/javascript">
$( document ).ready(function() {
var data="{{ $datas }}";
var result = jQuery.parseJSON(data.replace(/&quot;/g, '"' ));
$("#grid1").jqGrid({
url: "getSalesenquiryData",
datatype: "json",
mtype: "GET",
	height: 300,
	 colModel: [
	{ name: "so_inquiry_hdr_id", label: "id" },
	{ name: "inquiry_no", label: "Inquiry No" ,editable:true, editrules:{date:true}},
	{ name: "inquiry_date", label: "Inquiry Date",editable:true, editrules:{date:true}},
	],
	 /*data:result,*/
	  iconSet: "fontAwesome",
            rowNum: 100,
        rowList: [10,20,50,100],
        sortorder: "asc",
        viewrecords: true,
        gridview: true,
        rownumbers:true,
        caption: "",
          pager: true,
        searching: {
            defaultSearch: "cn"
        }
      });
jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});


$("#editdata").click(function()
{
	var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
	var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'so_inquiry_hdr_id');
	if( cellValue != false )
	{
		var url = "soinquiryedit";
        var editUrl = url + '/' + cellValue + '/edit';
		window.location.replace('salesenquiryedit/' +cellValue);
	}
	else
	{
	alert("Please Select Row");
	}
});

$('#viewdata').click(function(){
  //alert('hhhh');
  var gr=$('#grid1').jqGrid('getGridParam','selrow');
  var cellValue = $("#grid1").jqGrid ('getCell', gr, 'product_group_id');  //alert(cellValue);

  if(cellValue != false)
  {
     var url="productgroupview";
     var viewurl = url+'/'+cellValue+'/view';
     window.location.replace('productgroupview/' +cellValue);
  }
  else
  {
     alert("Please Select Row");
  }
});
	
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
