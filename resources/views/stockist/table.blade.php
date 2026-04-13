@extends('layouts.header')
@section('content')

@if (session('status'))
        <div class="alert alert-success">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{ session('status') }}
        </div>
    @endif


<h2 class="heads">Stockist </h2>

<div class="panel panel-visible" id="spy1">

<div class="panel-title ">
  <div class="row" style="text-align:left;">
  <div class="col-md-12">


  <?php include 'toolbar.php'; ?>
  </div>
</div>
</div>
<div class="row">

<div class="col-md-12" style="padding:15px;">
<!-- <h2 class="myheaders">Stockist </h2> -->
<table id="grid1"></table>

</div>
</div>
</div>








<script type="text/javascript">
$( document ).ready(function() {

      

      $("#grid1").jqGrid({
      url: "getstockistData",
      datatype: "json",
      mtype: "GET",
    colModel: [
    { name: "stockist_id", label: "id" ,hidden:true},
{ name: "stockist_name", label: "stockist name" ,editable:true, editrules:{date:true}},
{ name: "stockist_address", label: "Stockist address" ,editable:true, editrules:{date:true}},
{ name: "contact_person", label: "contact person" ,editable:true, editrules:{date:true}},
{ name: "stockist_phone", label: "stockist phone" ,editable:true, editrules:{date:true}},
{ name: "username", label: "Created By", editable:true, editrules:{date:true}},
    { name: "created_by", label: "Created By",hidden:true},

 ],
 iconSet: "fontAwesome",
          rowNum: 10,
              rowList: [10,20,50,100],
              sortorder: "desc",
              viewrecords: true,
              gridview: true,
              rownumbers:true,
                pager: "#grid1",
                autowidth: true,
      viewrecords: true,
             searching: {
                 defaultSearch: "cn"
             }
           });

$("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false,edit:true,add:true,del:true,search:true,cloneToTop:true,refresh:false});

$(document).on('click',".exportpdf",function() {
  $("#grid1").jqGrid('exportToPdf', {
        title: null,
        orientation: 'portrait',
        pageSize: 'A4',
        description: null,
        onBeforeExport: null,
        download: 'download',
        includeLabels : true,
        includeGroupHeader : true,
        includeFooter: true,
        fileName : "Stockist.pdf",
        mimetype : "application/pdf"  
      });
   });
   $(document).on('click',".exportexcel",function() {
        $("#grid1").jqGrid("exportToExcel",{
          includeLabels : true,
              includeGroupHeader : true,
              includeFooter: true,
              fileName : "Stockist.xlsx"
              
        })  
    });

   $("#clearsearch").click(function() {
     var grid = $("#grid1");
      grid.jqGrid('setGridParam',{search:false});

      var postData = grid.jqGrid('getGridParam','postData');
      $.extend(postData,{filters:""});
      grid.trigger("reloadGrid",[{page:1}]);
      $('input[id*="gs_"]').val("");
  });

   
showcolumn('grid1');

$(document).ready(function(){


 $("a[href='" + window.location.hash + "']").parent(".reveal").click();
});

$(".create").click(function(){

var url="{{ URL::to('stockistcreate/0')}}";
window.location.replace(url);

});
	


$("#edit").click(function()
{
	var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
	var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'stockist_id');
  var type = "<?php echo $urlname ?>";

	if( cellValue )
	{
    var url = "{{ url('stockistedit') }}/";
		
		  window.location.replace(url +cellValue);
	}
	else
	{
	notyMsg("info","Please Select Row");
	}
});




$("#view").click(function()
{
	var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
	var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'stockist_id');
  var type = "<?php echo $urlname ?>";
	if( cellValue )
	{
    var url = "{{ url('stockistview') }}/";
      window.location.replace(url +cellValue);


	}
	else
	{
	notyMsg("info","Please Select Row");
	}
});


$(".jqgrow td input").each(function () {
    jQuery(this).click(function () {
        $("#grid").jqGrid('setSelection', $(this).parents('tr').attr('id'));
    });
});


$("#delete").click(function(){

  var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
  var cellValue = jQuery("#grid1").jqGrid ('getCell', gr,'stockist_id');
  if( cellValue)
  {
    swal({
      title: "Are you sure?",
      text: "You want to delete!",
      type: "warning",
      showCancelButton: !0,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Yes",
      cancelButtonText: "No",
    }, function(e) {
    if(e == true)
      {
        var url ="{{ URL::to('stockistdelete') }}/" +cellValue;
        $.get(url,function(data)
        {
          var data = $.trim(data);
          var red_url ="{{ URL::to('stockist') }}";

          if(data =='0')
          {
            notyMsg('success','Deleted Successfully!!!');
            setTimeout(function(){
            $("#grid1")[0].triggerToolbar();
            }, 1500);
          }
          if(data=='2')
          {
            notyMsg('error',"You Cant't delete , Stockist DCR Used in SomeWhere!!!");
            setTimeout(function(){
              $("#grid1")[0].triggerToolbar();
            }, 1500);
          }
        });
      }
      else
      {
        $('.apply').css('display','none');
        swal("Cancelled");
      }
    });
  $('.apply').css('display','none');
  }
  else
  {
  notyMsg("info","Please Select Row");
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
