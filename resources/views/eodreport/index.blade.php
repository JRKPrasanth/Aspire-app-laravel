@extends('layouts.header')
@section('content')


<span class="ui_close_btn"></span>
<h2 class="heads">Document Internal Transfer</h2>

          <div class="row">
                <div class="col-md-12">
              <div class="panel">
                  <div class="panel-body">
                   <?php  include("toolbar.php");?>
  <table id="grid1_doctransfer"></table>
                    
                  </div>
              </div>        
                
                </div>
                </div>
                        </div>                 
             
        </div>
   <!--<a href="{{URL::to('outletmappingmaster.csv')}}" class='download_link' download></a>                   -->

    <script>
     
    $(document).ready(function(){
/************* distributormapping  grid start ***********/
   $("#grid1_doctransfer").jqGrid({
            url: "getdocumentinterntransferdata",
            datatype: "json",
            mtype: "GET",

      colModel: [
{ name: "doc_hdr_id", label: "Id",hidden:true, width: 100},
{ name: "employee_number", label: "Employee Name", width: 250,editable:true, editrules:{date:true}},
{ name: "doc_given_date", label: "Document Given Date", width: 250,editable:true, editrules:{date:true}},
{ name: "status", label: "Status", width: 250,editable:true, editrules:{date:true}},
//{ name: "end_datetime", label: "End DateTime", width: 250,editable:true, editrules:{date:true}},
//{ name: "duration", label: "Duration", width: 250,editable:true, editrules:{date:true}},
{ name: "remarks", label: "Remarks", width: 250,editable:true, editrules:{date:true}},
//{ name: "created_at", label: "Created Date", width: 250,editable:true, editrules:{date:true}},
{ name: "first_name", label: "Created By Name", width: 250,editable:true, editrules:{date:true}},

//{ name: "customer_name", label: "Distributor" ,editable:true, editrules:{date:true}},
// { name: "state_name", label: "State" ,editable:true, editrules:{date:true}},
// { name: "town_name", label: "Town" ,editable:true, editrules:{date:true}},
// { name: "reporting_email_id", label: "Reporting Email" ,editable:true, editrules:{date:true}},
        ],
 iconSet: "fontAwesome",
        rownumbers: true,
        sortname: "doc_hdr_id",
        sortorder: "desc",
    rowList: [10, 50, 100,250,500,1000],
        threeStateSort: true,
        sortIconsBeforeText: true,
        headertitles: true,
        pager: "#grid1_doctransfer",
        rowNum: 10,
        viewrecords: true,
        searching: {
            defaultSearch: "cn"
        }
        });
        
        
        jQuery("#grid1_doctransfer").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
/************* distributormapping grid end ***********/

      $("#grid1_doctransfer").jqGrid("setLabel", "rn", "S.No");    
	$("#exportexcel").on("click", function(){
		$("#grid1_doctransfer").jqGrid("exportToExcel",{
			includeLabels : true,
			includeGroupHeader : true,
			includeFooter: true,
			fileName : "Document Internal Transfer.xlsx",
			maxlength : 40 
		});
                                
                               
    });
   showcolumn('grid1_doctransfer');
                              /************* clear grid search end ***********/
     /*****************create**************/

$('.create').click(function(){
    var url = "{{ URL::to('documentinterntransferedit') }}/0";
      
    window.location.replace(url);
});
/*****************Edit**************/
$("#edit").click(function()
{
    var gr = jQuery("#grid1_doctransfer").jqGrid('getGridParam','selrow');
    var cellValue = jQuery("#grid1_doctransfer").jqGrid ('getCell', gr, 'doc_hdr_id');
    if(gr)
    {

        var url = "{{ URL::to('documentinterntransferedit') }}";
                var editUrl = url + '/' + cellValue;
        window.location.replace(editUrl);
    }
    else
    {
    notyMsg("info","Please Select Row");
    }
});


/******************Delete***********/

$("#delete").click(function()
{
    var gr = jQuery("#grid1_doctransfer").jqGrid('getGridParam','selrow');
    var id = jQuery("#grid1_doctransfer").jqGrid ('getCell', gr, 'doc_hdr_id');
    if(id){
        swal({
                title: "Are you sure?",
                text: "You want to delete!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: !1,
                //timer: 2e3,
                closeOnCancel: !1
            }, function(e) {

            if(e == true)
            {
                var url ="{{ URL::to('documentinterntransferdelete') }}/" +id;
                $.get(url,function(data)
                {
                    var data = $.trim(data);
                    var red_url ="{{ URL::to('documentinterntransfer') }}";
                    var data = $.trim(data);
                    if(data =='0')
                    {
                        notyMsg('success','Deleted Successfully!!!',red_url);
                        setTimeout(function(){
                        window.location.href=red_url;
                        }, 1500);
                    }
                    if(data =='2')
                    {
                        notyMsg('error',"You Cant't delete , Enquiry Used in SomeWhere!!!",red_url);
                    }

                });
            }
            else
            {
                            $('.apply').css('display','none');
                            swal("Cancelled");
            }
            })
        $('.apply').css('display','none');
    }
    else
    {
    notyMsg("info","Please Select Row");
    }
});


/***************VIEW*************************************/
 $("#view").click(function()
        {
            var index = $("#grid1_doctransfer").jqGrid('getGridParam','selrow');
            var doc_hdr_id = $("#grid1_doctransfer").jqGrid ('getCell', index, 'doc_hdr_id');
            if(index)
            {       
                window.location.replace('documentinterntransferview/' +doc_hdr_id);
            }
            else
            {
                notyMsg('info',"Please Select Row");
            }
        });
    /*****  Purpose For CLEAR search ********/
    $(".clearsearch").click(function()
    {   
        var grid = $("#grid1_doctransfer");
        grid.jqGrid('setGridParam',{search:false});
        var postData = grid.jqGrid('getGridParam','postData');
        $.extend(postData,{filters:""});
        grid.trigger("reloadGrid",[{page:1}]);
        $('input[id*="gs_"]').val("");
        $('select[id*="gs_"]').select2('val',['']);
        
    });
    /*End*/

    });
    </script>
@include('layouts.php_js_validation')
@endsection
