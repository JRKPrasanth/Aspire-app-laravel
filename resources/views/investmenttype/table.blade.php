@extends('layouts.header')
@section('content')



<h2 class="heads">Investment Type</h2>
<div class="panel panel-visible" id="spy1">


<div class="panel-title ">
	<div class="row">
            <div class="col-md-12">
             <?php include('toolbar.php'); ?>
            </div>
        </div>
</div>
<div class="row">
<div class="col-md-12" style="padding: 15px;">
<!-- OUR CONTENT STARTS HERE -->

<table id="grid1"></table>

<!-- OUR CONTENT ENDS HERE -->


</div>
</div>
</div>







<script type="text/javascript">
$(document).ready(function()
{
/** Jqgrid  data load Start **/



   $("#grid1").jqGrid({
            url: "{{URL::to('investmenttypegrid')}}",

            datatype: "json",
            mtype: "GET",

      colModel: [
     { name: "inv_id", label: "id", width: 250, hidden: true },
       
        { name: "inv_name", label: "Name", width: 250},
        { name: "inv_limit", label: "Limit", width: 250},
        ],

        iconSet: "fontAwesome",
        rownumbers: true,
        sortname: "inv_id",
        sortorder: "desc",
    rowList: [10, 50, 100,250,500,1000],
        threeStateSort: true,
        sortIconsBeforeText: true,
        headertitles: true,
        pager: "#grid1",
        rowNum: 10,

        viewrecords: true,
        searching: {
            defaultSearch: "cn"
        }
        });
        
        
        jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
/************* ananth grid end ***********/
  $("#grid1").jqGrid("setLabel", "rn", "S.No");         











/** Jqgridata load End **/

/** Jqgro Pdf Start **/
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
                      fileName : "Investmenttype.pdf",
                      mimetype : "application/pdf"  
                });
            });
/** Jqgrid   Export to Pdf End **/


/** Jqgrid   Export to Excel Start **/
            $(document).on('click',".exportexcel",function() {
                $("#grid1").jqGrid("exportToExcel",{
                    includeLabels : true,
                        includeGroupHeader : true,
                        includeFooter: true,
                        fileName : "Investmenttype.xlsx"                        
                })       
                
            });
/** Jqgrid  Professional Tax Export to Excel End **/

showcolumn('grid1');

/*purpose:clear search the jqgrid*/
  $(".clearsearch").click(function()
  {
    var grid = $("#grid1");
    grid.jqGrid('setGridParam',{search:false});
    var postData = grid.jqGrid('getGridParam','postData');
    $.extend(postData,{filters:""});
    grid.trigger("reloadGrid",[{page:1}]);
    $('input[id*="gs_"]').val("");
    
  });
  /*end*/

        jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
        
        
        $('#grid1').on('click', function (event) 
        {
            var index = $("#grid1").jqGrid('getGridParam','selrow');
            var id = $("#grid1").jqGrid ('getCell', index, 'id');
            $('.document_check,.download').attr('id',id);
            $('.document_check,.download').prop('disabled',false);
        });
        
        /** Create Professional Tax  Start **/
        $(document).on('click','.create',function()
        {
           
            var url="{{ URL::to('createinvestmenttype') }}/0";
             window.location.href=url;
        });
	/** Create Professional Tax  End **/

    /** Edit Professional Tax  Start **/
		 $(document).on('click','.edit',function()
        {
           
            var index = $("#grid1").jqGrid('getGridParam','selrow');
            var inv_id = $("#grid1").jqGrid ('getCell', index, 'inv_id');
		  	if(index)
            {
				var url="{{ URL::to('createinvestmenttype') }}/"+inv_id;
             	window.location.href=url;
			}
		    else
		 	{
				 notyMsgs('info','Please Select One Row');
			 }
        });
        /** Edit Professional Tax  End **/

        /** Delete Professional Tax  Start **/
         $(document).on('click','.delete',function(e){
                e.preventDefault();
                var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
                var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'inv_id');
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
            closeOnCancel:!1
          }, function(e) {
          if(e == true)
            {
                        $.get('investmenttypedelete?del_id='+cellValue, function(data,status)
                        {
                             if(data == 1)
                            {
                                notyMsgs('Info','Investment Type  Details Deleted Successfully');
                                $("#grid1")[0].triggerToolbar();
                                
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
                 notyMsgs('Info','Please Select a Row');
                        $("#grid1")[0].triggerToolbar();
                }


            });
            /** Delete Professional Tax  End **/
        
       

});
</script>

@endsection
