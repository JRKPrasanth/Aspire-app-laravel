@extends('layouts.header')
@section('content')

<h2 class="heads">Material Requirement</h2>

<div class="panel panel-visible" id="spy1">
  <div class="panel-title">
	  <div class="row">
  	  <div class="col-md-12">
        <span class=""></span>
        <?php include("toolbar.php");?>
        <!-- <button type='button' href='' class='btn search clearsearch'>Clear Search </button>
        <a id="showcolumn"><button type="button" id="btnviewdetails" class="btn add showcolumn btnviewdetails " data-value="showcolumn">Show Column</button></a> -->
      </div>
    </div>
	</div>
	<div class="row">
	  <div class="col-md-12">
	    <table id="grid1"></table>
	  </div>
  </div>
</div>

<style>
	.mytable {
	background: transparent !important;
	}

</style>

<script type="text/javascript">
	/* set select2*/
  $(".select2").select2();
  $(".select2").css('width','100%');
  /*end*/
$( document ).ready(function() {
  
  var batchopt = "{{$batchopt}}";
  $("#grid1").jqGrid({

			url: "getmaterialreqdata",

      datatype: "json",
      mtype: "GET",

      	 colModel: [

        { name: "qc_material_req_id", label: "id", width: 100,hidden:true},
        { name: "batch_number", label: "Batch No", width: 250 ,editable:true},
        { name: "start_date", label: "Start Date", width: 250 ,editable:true, editrules:{date:true}},
        { name: "end_date", label: "End Date", width: 250 },
        { name: "remarks", label: "Remarks", width: 250 },
        { name: "active", label: "Active", width: 250 },
         { name: "username", label: "Created By", editable:true, editrules:{date:true}},


      ],
    
        iconSet: "fontAwesome",
        rowNum: 10,
        rowList: [10,20,50,100,250,500,1000],
        sortorder: "desc",
        viewrecords: true,
        gridview: true,
        rownumbers:true,
		    autowidth:true,
		    rownumWidth:50,
        caption: "",
        pager: "#grid1",
        searching: {
            defaultSearch: "cn"
        }
      });

  jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
	$('#gs_grid1_batch_no').select2();

  showcolumn('grid1');
// jQuery("#gs_grid1_product_id").select2();

  $(document).on('click','.create',function()
  {
    var url="{{ URL::to('materialreqcreate') }}/0";
    window.location.replace(url);
  });

  jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
		$("#grid1").jqGrid("setLabel", "rn", "S.No");
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
  fileName : "materialrequirement.pdf",
  mimetype : "application/pdf"  
});});
 $(document).on('click',".exportexcel",function() {			
$("#grid1").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "materialrequirement.xlsx"
    					
				})		 
	 
	 
	
});
	
	

  
  
  
  
  $(document).on('click','#edit',function(){
    var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
    var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'qc_material_req_id');
    var url ="{{ url('materialedit') }}/" +cellValue; 
    if( gr )
    { 
      $.get(url,function(data){
        var data = $.trim(data);
        if(data =='0')
        {
          window.location.replace('materialreqcreate/' +cellValue);
        }else{
          notyMsg('error',"You Cant't be Edit this Material, Already used!!!");
        }
      });
      
    }else{
      notyMsg('info',"Please select a row");
    }
  });

  $(document).on('click','#view',function(){
      var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
      var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'qc_material_req_id');
      if( gr)
      { 
        window.location.replace('materialreqview/' +cellValue);
      }else{
        notyMsg('info',"Please select a row");
      }
  });

  $("#delete").click(function(){

      var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
      var cellValue = jQuery("#grid1").jqGrid ('getCell', gr,'qc_material_req_id');
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
          closeOnCancel: !1
        }, function(e) {
        if(e == true)
          {
            var url ="{{ URL::to('materialreqdelete') }}/" +cellValue;
            $.get(url,function(data)
            {
              var data = $.trim(data);
              var red_url ="{{ URL::to('materialrequirement') }}";

              if(data =='0')
              {
                notyMsg('success','Deleted Successfully!!!');
                setTimeout(function(){
                $("#grid1")[0].triggerToolbar();
                }, 1500);
              }
              if(data=='1')
              {
                notyMsg('error',"Delete somewhere error!!!");
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

/*deepika purpose:clear search the jqgrid*/
	$(".clearsearch").click(function()
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
});
    </script>
@endsection
