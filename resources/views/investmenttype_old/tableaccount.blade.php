@extends('layouts.header')
@section('content')



<h2 class="heads">HRMS ALLOWANCE ACCOUNT SETTINGS</h2>
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
            url: "{{URL::to('hrmsallowancesettingsgrid')}}",

            datatype: "json",
            mtype: "GET",

      colModel: [
     { name: "account_allowance_setting_id", label: "account_allowance_setting_id", width: 250, hidden: true },
     { name: "department_id", label: "department_id", width: 250, hidden: true },
        { name: "sub_department_name", label: "Department Name", width: 250},
        ],

        iconSet: "fontAwesome",
        rownumbers: true,
        sortname: "account_allowance_setting_id",
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
                      fileName : "Accout Settings.pdf",
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
                        fileName : "Accout Settings.xlsx"                        
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
        
        
      
        
        /** Create Professional Tax  Start **/
        $(document).on('click','.create',function()
        {
           
            var url="{{ URL::to('hrmsallowancesettings') }}/0";
             window.location.href=url;
        });
	/** Create Professional Tax  End **/

    /** Edit Professional Tax  Start **/
		 $(document).on('click','.edit',function()
        {
           
            var index = $("#grid1").jqGrid('getGridParam','selrow');
            var account_allowance_setting_id = $("#grid1").jqGrid ('getCell', index, 'account_allowance_setting_id');
		  	if(index)
            {
				var url="{{ URL::to('hrmsallowancesettings') }}/"+account_allowance_setting_id;
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
                var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'account_allowance_setting_id');
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
                        $.get('hrmsallowancesettingsdelete?del_id='+cellValue, function(data,status)
                        {
                             if(data == 1)
                            {
                                notyMsgs('Info','Accounts settings  Deleted Successfully');
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
