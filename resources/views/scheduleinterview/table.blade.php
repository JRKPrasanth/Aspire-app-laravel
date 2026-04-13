@extends('layouts.header')
@section('content')




<div class="panel panel-visible" id="spy1">


<div class="panel-title ">
	<div class="row">
	<div class="col-md-12">
            <a class='btn add approve'  value="">Approve</a>
            <a class="btn sec reject">Reject</a>
            
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
$("#grid1").jqGrid(
{
/**Jqgrid Interview Schedule  load data Start **/
url: "jobdescriptiongriddata?status=0",
datatype: "json",
mtype: "GET",
colModel: [
{ name: "description_id", label: "description_id", width: 250, hidden: true },
{ name: "description_name", label: "Description Name.", width: 250},
{ name: "department", label: "Department", width: 250},
{ name: "job_title", label: "Job Title", width: 250},
 { name: "reqired_skills", label: "Required Skills",width: 250}
],

iconSet: "fontAwesome",
rowNum: 100,
rowList: [10,20,100,1000,2000],
sortorder: "desc",
viewrecords: true,
gridview: true,
rownumbers:true,
caption: "Job Description Approval",
pager: "#grid1",
multiselect:true,
multipageselection:true,
searching: {
defaultSearch: "cn",
},



});


/**Jqgrid Interview Schedule  load data End **/

        jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});

        /** Job Description Approval data Start **/
        $(".approve").click(function()
        {
                var index = $("#grid1").jqGrid('getGridParam','selrow');
                var description_id = $("#grid1").jqGrid ('getCell', index, 'description_id');
                var selRows= $('#grid1 tbody .ui-state-highlight').length;

                if(description_id != false)
                {
                    var url = "{{ URL::to('approvedescription') }}/approve/"+description_id;
                    $.get(url,function(data)
                    {
                        if(data == 1)
                        {
                            url = "{{ URL::to('jobdescriptionapproval')}}";
                            
                            notyMsgs('info','Job Description Approved Success');
                            setTimeout(function(){
                                window.location.href=url;
                            }, 2000);
                        }
                    });

                }
                else
                {
                    alert("Please Select Row");
                }

        });
        /** Job Description Approval data End **/

        /** Job Description Reject data Start **/
        $(".reject").click(function()
        {
                var index = $("#grid1").jqGrid('getGridParam','selrow');
                var description_id = $("#grid1").jqGrid ('getCell', index, 'description_id');
                var selRows= $('#grid1 tbody .ui-state-highlight').length;

                if(description_id != false)
                {
                    var url = "{{ URL::to('approvedescription') }}/reject/"+description_id;
                    $.get(url,function(data)
                    {
                        if(data == 2)
                        {
                            url = "{{ URL::to('jobdescriptionapproval')}}";
                            
                            notyMsgs('info','Job Description Rejected Success');
                            setTimeout(function(){
                                window.location.href=url;
                            }, 2000);
                        }
                    });

                }
                else
                {
                    alert("Please Select Row");
                }

        });
/** Job Description Reject data End **/

/** Job Description View data Start **/
        $(".view").click(function()
        {
            var index = $("#grid1").jqGrid('getGridParam','selrow');
            var sales_hdr_id = $("#grid1").jqGrid ('getCell', index, 'sales_hdr_id');
			var selRows= $('#grid1 tbody .ui-state-highlight').length;
            if( sales_hdr_id != false )
            {
				if(selRows > 1)
				{
					notyMsgs('info','Please Select One Row.....');
				}
				else
				{
                window.location.replace('soorderview/' +sales_hdr_id);
				}
            }
            else
            {
                alert("Please Select Row");
            }
        });
        /** Job Description View data End **/

});
</script>

@endsection
