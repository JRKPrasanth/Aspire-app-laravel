@extends('layouts.header')
@section('content')



<div class="row">
<div class="col-md-12">
<div class="panel panel-visible" id="spy1">


    <div class="panel-title ">
            <div class="row">
                <div class="col-md-12">
                    <button class='btn add approve'  value="">Approve</button>
                    <button class="btn sec reject">Reject</button>
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
</div>

</div>
@extends('layouts.footer')
</div>






<script type="text/javascript">
$(document).ready(function()
{
        $("#grid1").jqGrid(
            {
                url: "employeeadvanceapprovedatagrid",
                datatype: "json",
                mtype: "GET",
                colModel: [
                    { name: "advance_id", label: "advance_id", width: 250, hidden: true },
                    { name: "employee_id", label: "employee_id", width: 250, hidden: true },
                    { name: "advance_reason", label: "advance reason", width: 250, hidden: true },
                    { name: "first_name", label: "Name", width: 250 },
                    { name: "advance_date", label: "Advance Date.", width: 250},
                    { name: "mode", label: "Mode", width: 250,hidden: true},
                    { name: "ref_no", label: "Reference No", width: 250,hidden: true},
                    { name: "emi", label: "EMI Month", width: 250},
                    { name: "amount", label: "Amount", width: 250},
                    { name: "paid_amount", label: "Paid Amount", width: 250},
                    { name: "remaining_amount", label: "Remaining Amount", width: 250},
                    { name: "forwarded_id", label: "Reporting Id", width: 250},
                    { name: "approved_status", label: "Approved by Reporting", width: 250},
                    { name: "approved_id", label: "Approved by Reporting", width: 250,hidden: true},
                    { name: "approved_status_hr", label: "Approved by HR", width: 250},
                    { name: "forwarded_id1", label: "Reporting Id", width: 250,hidden: true},
                    { name: "approved_by_hr_id", label: "Reporting Id", width: 250,hidden: true}
                   
                ],

                iconSet: "fontAwesome",
                rowNum: 100,
                rowList: [10,20,100,1000,2000],
                sortorder: "desc",
                viewrecords: true,
                gridview: true,
                rownumbers:true,
                caption: "Advance Details",
                pager: true,
                multiselect:false,
                multipageselection:true,
                searching: {
                defaultSearch: "cn",
                },
            });
        
        



        jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
        
        
        $('#grid1').on('click', function (event) 
        {
            var index = $("#grid1").jqGrid('getGridParam','selrow');
            var advance_id = $("#grid1").jqGrid ('getCell', index, 'advance_id'); 
            $.get("{{ URL::to('getstatus') }}/?advance_id="+advance_id, function(data,status)
            {
                console.log(data);
                if(data[0] == 1)
                {
                    
                    if(data[1] == '0')
                    {
                        $('.approve').prop('disabled',false);
                        $('.reject').prop('disabled',true);
                    }
                    else if(data[1] == '1'){
                        $('.approve').prop('disabled',true);
                        $('.reject').prop('disabled',false);
                    }
                    else if(data[1] == '2'){
                        $('.approve').prop('disabled',false);
                        $('.reject').prop('disabled',true);
                    }
                }
                else if(data[0] == 2)
                {
                    $('.approve').prop('disabled',true);
                    $('.reject').prop('disabled',true);
                }
            });
            
            //var approved_by_hr_id = $("#grid1").jqGrid ('getCell', index, 'approved_by_hr_id');  
        });
        $(".approve").click(function()
        {
                var index = $("#grid1").jqGrid('getGridParam','selrow');
                var advance_id = $("#grid1").jqGrid ('getCell', index, 'advance_id');
                var selRows= $('#grid1 tbody .ui-state-highlight').length;
                var status='approve';

                if(advance_id != false)
                {
                    var url= "{{URL::to('advanceapprove')}}/"+status+"/"+advance_id;
                    window.location.href=url;
//                    $.get("{{ URL::to('advancestatus')}}/?status="+status+'&advance_id='+advance_id, function(data,status)
//                    {   
//                        if(data == 1)
//                        {
//                            notyMsgs('info','Status Approved Successfully');
//                            $("#grid1")[0].triggerToolbar();
//                            $('.approve').prop('disabled',false);
//                            $('.reject').prop('disabled',false);
//                        }
//                   
//                    });
                }
                else
                {
                    notyMsgs('info','Please Select a Row');
                }

        });
        
        $(".reject").click(function()
        {
                var index = $("#grid1").jqGrid('getGridParam','selrow');
                var advance_id = $("#grid1").jqGrid ('getCell', index, 'advance_id');
                var selRows= $('#grid1 tbody .ui-state-highlight').length;
                var status='reject';

                if(advance_id != false)
                {
                    
                    var url= "{{URL::to('advanceapprove')}}/"+status+"/"+advance_id;
                    window.location.href=url;
//                    $.get("{{ URL::to('advancestatus') }}/?status="+status+"&advance_id="+advance_id, function(data,status)
//                    {   
//                        if(data == 1)
//                        {
//                            notyMsgs('info','Status Rejected Successfully');
//                            $("#grid1")[0].triggerToolbar();
//                            $('.approve').prop('disabled',false);
//                            $('.reject').prop('disabled',false);
//                        }
//                    });
                }
                else
                {
                    notyMsgs('info','Please Select a Row');
                }
        });

//        $(".view").click(function()
//        {
//            var index = $("#grid1").jqGrid('getGridParam','selrow');
//            var sales_hdr_id = $("#grid1").jqGrid ('getCell', index, 'sales_hdr_id');
//			var selRows= $('#grid1 tbody .ui-state-highlight').length;
//            if( sales_hdr_id != false )
//            {
//				if(selRows > 1)
//				{
//					notyMsgs('info','Please Select One Row.....');
//				}
//				else
//				{
//                window.location.replace('soorderview/' +sales_hdr_id);
//				}
//            }
//            else
//            {
//                alert("Please Select Row");
//            }
//        });

});
</script>

@endsection
