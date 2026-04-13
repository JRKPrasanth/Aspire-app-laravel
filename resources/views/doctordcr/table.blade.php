@extends('layouts.header')
@section('content')

@if (session('status'))
        <div class="alert alert-success">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{ session('status') }}
        </div>
    @endif


<h2 class="heads">Doctor DCR</h2>

<div class="panel panel-visible" id="spy1">

<div class="panel-title ">
  <div class="row tablebutton" >
  <div class="col-md-12">
  <span class=""></span>
  <?php include 'toolbar.php'; ?>
    
  </div>
</div>
</div>
<div class="row">

<div class="col-md-12" style="padding:15px;">

<table id="grid1"></table>

</div>
</div>
</div>








<script type="text/javascript">
$( document ).ready(function() {

    
      $("#grid1").jqGrid({
          url: "getdoctordcrData",
          datatype: "json",
          mtype: "GET",
          shrinkToFit:true,
          colModel: [
              { name: "doctor_dcr_id", label: "id" ,hidden:true},
              { name: "tp_date", label: "Tour Plan Date" ,editable:true, editrules:{date:true}},
              { name: "tp_deviation", label: "Tour Plan Deviation" ,editable:true, editrules:{date:true}},
              { name: "divert_detail", label: "Divert Detail" ,editable:true, editrules:{date:true}},
              { name: "area_name", label: "Area" , editrules:{date:true}},
              { name: "doctor_name", label: "Doctor Name" , editrules:{date:true}},
              { name: "remarks", label: "Remarks",editable:true, editrules:{date:true}},
              { name: "username", label: "Created By", editable:true, editrules:{date:true}},
              { name: "created_by", label: "Created By",hidden:true},
           ],
          
          iconSet: "fontAwesome",
          rowNum: 10,
          rowList: [10,20,50,100,250,500,1000],
          sortorder: "desc",
          viewrecords: true,
          gridview: true,
          rownumbers:true,
          rownumWidth:true,
          pager: "#grid1",
          autowidth: true,
          multiselect:true,
          searching: {
            defaultSearch: "cn"
          },
          onSelectRow: function(){
              var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
              var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'doctor_dcr_id');
              var tpd = jQuery("#grid1").jqGrid ('getCell', gr, 'tp_deviation');
              if(tpd == "Yes"){
                notyMsg('info','You cant edit this data');
                $("#grid1")[0].triggerToolbar();
              }
          }
      });

$("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false,edit:true,add:true,del:true,search:true,cloneToTop:true,refresh:false});
$("#grid1").jqGrid('hideCol','cb');

showcolumn('grid1');
  
$("#exportpdf").on("click", function(){
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
        fileName : "DoctorDCR.pdf",
        mimetype : "application/pdf",
       customSettings:null
    });

});

$("#exportexcel").on("click", function(){
    $("#grid1").jqGrid("exportToExcel",{
      includeLabels : true,
      includeGroupHeader : true,
      includeFooter: true,
      fileName : "DoctorDCR.xlsx",
      maxlength : 40 
    });
                               
});


$(document).ready(function(){
    $("a[href='" + window.location.hash + "']").parent(".reveal").click();
});

$(".create").click(function(){

var url="{{ URL::to('doctordcrcreate/0')}}";
window.location.replace(url);

});
	


$("#edit").click(function()
{
	var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
	var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'doctor_dcr_id');
  var $grid = $("#grid1"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,cellvalues1 = [];
  var j=0;
  for (i = 0, n = selIds.length; i < n; i++) {
    var v=  $grid.jqGrid("getCell", selIds[i], "doctor_dcr_id");
    if(v!=false)
      cellvalues1.push(v);
  }

	if( gr)
	{
      var url = "{{ url('doctordcredit') }}/";
		
		  window.location.replace(url +cellvalues1);
	}
	else
	{
	notyMsg("info","Please Select Row");
	}
});




$("#view").click(function()
{
	var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
	var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'doctor_dcr_id');
  var type = "<?php echo $urlname ?>";
	if( cellValue)
	{
    var url = "{{ url('doctordcrview') }}/";
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
  var cellValue = jQuery("#grid1").jqGrid ('getCell', gr,'doctor_dcr_id');
  if( cellValue )
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
        var url ="{{ URL::to('doctordcrdelete') }}/" +cellValue;
        $.get(url,function(data)
        {
          var data = $.trim(data);
          var red_url ="{{ URL::to('doctordcr') }}";

          if(data =='0')
          {
            notyMsg('success','Deleted Successfully!!!');
            setTimeout(function(){
            $("#grid1")[0].triggerToolbar();
            }, 1500);
            $('#clearsearch').trigger('click');
          }
          if(data=='2')
          {
            notyMsg('error',"You Cant't delete , Doctor DCR Used in SomeWhere!!!");
            setTimeout(function(){
              $("#grid1")[0].triggerToolbar();
            }, 1500);
            $('#clearsearch').trigger('click');
          }
        });
      }
      else
      {
        $('.apply').css('display','none');
        swal("Cancelled");
        $('#clearsearch').trigger('click');
      }
    });
  $('.apply').css('display','none');
  }
  else
  {
  notyMsg("info","Please Select Row");
  }
});

	/*purpose:clear search the jqgrid*/
  $("#clearsearch").click(function()
  {
    
    var grid = $("#grid1");
    grid.jqGrid('setGridParam',{search:false});

    var postData = grid.jqGrid('getGridParam','postData');
    $.extend(postData,{filters:""});
    grid.trigger("reloadGrid",[{page:1}]);
    $('input[id*="gs_"]').val("");
    $('select[id*="gs_"]').select2('val',['']);
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
