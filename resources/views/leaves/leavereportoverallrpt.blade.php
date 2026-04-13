@extends('layouts.header')
@section('content')

<style type="text/css">
  .datepicker{

    z-index:1052 !important;}
.Menu {
    position: absolute;
    top: 77%;
    left: auto;
    z-index: 1000;
    display: none;
    float: left;
    min-width: 160px;
    padding: 5px 0;
    margin: 2px 0 0;
    font-size: 14px;
    text-align: left;
    list-style: none;
    background-color: #fff;
    -webkit-background-clip: padding-box;
    background-clip: padding-box;
    border: 1px solid #ccc;
    border: 1px solid rgba(0, 0, 0, .15);
    border-radius: 4px;
    -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, .175);
    box-shadow: 0 6px 12px rgba(0, 0, 0, .175);
}
</style>
<!--<div id="accordion">
<div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a role="button">
                                 Dispatch Details Report
                                    </a>
                                </h4>
                            </div>

</div>
 <div class="row">
    <div class="col-md-12" >
   <div id="dispatchdetails"></div>
  </div>
  </div>-->
  
<div id="accordion">
    <div class="panel-heading" role="tab" id="headingOne">
        <h4 class="panel-title">
        <a role="button">
        Leave Report For Overall Leave Request
        </a>
        </h4>
</div>
    <div class="card">
    <div class="card-body">

<div class="row">
    <div class="col-md-12">   
        <div class="col-md-6">                  
            <div class="form-group row">
                <label for="inputIsValid" class="form-control-label col-md-4">From Date</label>
                <div class="col-md-6">
                    <div class="input-group form_date " data-date=""   data-link-format="yyyy-mm-dd">
                    <input class="form-control start_date  " id="start_date" name="start_date"  required type="text" value="" style="border-radius: 5px;" autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">                  
            <div class="form-group row">
                <label for="inputIsValid" class="form-control-label col-md-4">To Date</label>
                <div class="col-md-6">
                    <div class="input-group form_date " data-date=""   data-link-format="yyyy-mm-dd">
                    <input class="form-control end_date  " id="end_date" name="end_date"  required type="text" value="" style="border-radius: 5px;" autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6"></div>
    </div>
        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-offset-3 col-md-2"><span style="color:red; " > </span></label>
            <div class="col-md-1">
                <a><button type="button" class="btn add search" id="search" value="">Search</button></a>
            </div>  
        </div>
    
    </div>
</div>

 <div class="row">
    <div class="col-md-12" >
   <div id="leaveoverallgrid"></div>
  </div>
  </div>




<script src="{{ asset('js/pqselect.min.js')}}"></script>
<script src="{{ asset('js/pqgrid.min.js')}}"></script>
<script src="{{ asset('js/pq-localize-en.js')}}"></script>
<link rel="stylesheet" href="{{ asset('css/pqselect.min.css')}}" />
<link rel="stylesheet" href="{{ asset('css/pqgrid.min.css')}}" />
<link rel="stylesheet" href="{{ asset('css/pqgrid.ui.min.css')}}" />
<link rel="stylesheet" href="{{ asset('css/pqgrid.css')}}" />
<script src="{{ asset('js/filesaver.js')}}"></script>

<script type="text/javascript">

$( document ).ready(function() {
    
    function pqDatePicker(ui) {
            var $this = ui.$editor;
            $this.datepicker({
                    yearRange: "-25:+0",
                    changeYear: true,
                    changeMonth: true,
                    dateFormat:"yy-mm-dd"
                    
                });
        }
  $(document).on('click','.search',function()
    {
        var start_date = ($('.start_date').val() != '') ? $('.start_date').val() : '';
        var end_date   =  ($('.end_date').val() != '') ? $('.end_date').val()  : ''; 
       
       
        if(start_date!=''&&end_date!=''){
            var url = "{{URL::to('getleaveoverallgridrpt')}}/?start_date="+start_date+"&end_date="+end_date;
            var url = url;
     obj.dataModel.url=url;
     $( "#leaveoverallgrid" ).pqGrid( "option" , "dataModel.url",url );
    $("#leaveoverallgrid").pqGrid("refreshDataAndView");
        }else{
             notyMsg("info","Please Choose Feilds");
         }
 });
  var url;  
    
    var colModel=[
	{ dataIndx: "leave_id", align: "center",title: "Leaves ID" ,hidden:true},
	{ dataIndx: "employee_id", align: "begin",title: "Employee Id",hidden:true ,minWidth:"18%", filter: { crules: [{condition: "begin" }] }},
	{ dataIndx: "forwarded_id", align: "begin",title: "Forwarded Id",hidden:true ,minWidth:"18%", filter: { crules: [{condition: "begin" }] }},
	{ dataIndx: "employee_name",align: "begin",title: "Employee Name" , minWidth:"18%",filter: { crules: [{condition: "begin" }] }},
	{ dataIndx: "reporting_name",align: "begin",title: "Reporting Name" , minWidth:"18%",filter: { crules: [{condition: "begin" }] }},
	{ dataIndx: "leave_type",align: "begin",title: "Leave Type" , minWidth:"18%",filter: { crules: [{condition: "begin" }] }},
	{ dataIndx: "start_date",align: "right",title: "Start Date" , minWidth:"18%",filter: { crules: [{condition: "begin" }] }},
	{ dataIndx: "end_date",align: "right",title: "End Date" , minWidth:"18%",filter: { crules: [{condition: "begin" }] }},
	{ dataIndx: "created_at",align: "right",title: "Leave Submmision date" , minWidth:"18%",filter: { crules: [{condition: "begin" }] }},
	{ dataIndx: "leave_reason",align: "right",title: "Leave Reason" , minWidth:"18%",filter: { crules: [{condition: "begin" }] }},
	{ dataIndx: "no_of_days",align: "right",title: "No Of Days" , minWidth:"18%",filter: { crules: [{condition: "begin" }] }},
	{ dataIndx: "alloted_days",align: "right",title: "Alloted Days" , minWidth:"18%",filter: { crules: [{condition: "begin" }] }},
	{ dataIndx: "approval_reason",align: "right",title: "Approve Reason" , minWidth:"18%",filter: { crules: [{condition: "begin" }] }},
	{ dataIndx: "approvel_comments",align: "right",title: "Approve Comments" , minWidth:"18%",filter: { crules: [{condition: "begin" }] }},
	{ dataIndx: "leave_status",align: "right",title: "Approve Status" , minWidth:"18%",filter: { crules: [{condition: "begin" }] }},
	{ dataIndx: "organization_id",align: "right",title: "Organization",hidden:true , minWidth:"18%",filter: { crules: [{condition: "begin" }] }},
	{ dataIndx: "od_start_date",align: "begin",title: "OD start date",hidden:true , Width:"50%",filter: { crules: [{condition: "begin" }] }},
	{ dataIndx: "od_end_date",align: "begin",title: "OD end date",hidden:true , Width:"50%",filter: { crules: [{condition: "begin" }] }},
	{ dataIndx: "od_no_of_days",align: "begin",title: "Od No of days",hidden:true , Width:"50%",filter: { crules: [{condition: "begin" }] }},
	{ dataIndx: "od_alloted_days",align: "begin",title: "od alloted days",hidden:true , Width:"50%",filter: { crules: [{condition: "begin" }] }},
	{ dataIndx: "leave_mode",align: "begin",title: "Leave Mode",hidden:true , Width:"50%",filter: { crules: [{condition: "begin" }] }},
    
	];
  //define dataModel
        var dataModel = {
            location: "remote",            
            dataType: "JSON",
            method: "GET",
            //url: "{{URL::to('getdispatchdetails')}}",
            url: url,
			 getData: function (dataJSON) {
                var data = dataJSON.data;
                return { curPage: dataJSON.curPage, totalRecords: dataJSON.totalRecords, data: data };
            }
        }
         var obj = {
            width:'100%',
            dataModel: dataModel,
            flex:{one: true},
            colModel: colModel,
            pageModel: { type: "remote", rPP: 10, strRpp: "{0}",strPage:"{0} of {1}" },
            wrap: false,
            showBottom: false,            
            editable: false,  
            menuIcon: true,
			 editable: false,
            numberCell: { show: false },
            selectionModel: { type: 'row' },
            menuUI: {
                singleFilter: true
            },
            filterModel: { 
                on: true,             
                header: true, 
                type: 'remote', 
                menuIcon: true 
            },
            title: "Dispatch Details Report",
            resizable: true,
            hwrap:false,
            freezeCols: 2,
            toolbar: {
                items: [{
                    type: 'button',
                    label: "Export to Excel",
                    icon: 'ui-icon-arrowthickstop-1-s',
                    

     listener: function () {
                        var data=this;
                        var dt = new Date();
	                        var time = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
	                        var file_date   =  ($('.end_date').val() != '') ? $('.end_date').val()  : '';
	                        console.log(file_date);
                        var url=$.trim($.cookie('leaveoverallgrid_pq_export_url')).slice(1,-1)+"&download=1";
                        $.get(url,function(s){
                        
                        var blob = data.exportData({
                                format: 'xlsx',                                
                                render: true,
                                type: 'blob',
                                data:s
                            });                        
                        saveAs(blob, "Dispatch Details Report"+file_date+"-"+time+".xlsx" );
                        });
                    }
                },
                {
                    type: 'button',
                    icon: 'ui-icon-print',
                    label: 'Print',
                    listener: function () {
                        var exportHtml = this.exportData({ title: 'Dispatch Details', format: 'htm', render: true }),
                            newWin = window.open('', '', 'width=1200, height=700'),
                            doc = newWin.document.open();
                        doc.write(exportHtml);
                        doc.close();
                        newWin.print();
                    }
                }]
            },
rowSelect: function (evt, ui) {
                var str = JSON.stringify(ui, function(key, value){                    
                    if( key.indexOf("pq_") !== 0){
                        return value;
                    }
                }, 2)
                var val=$.parseJSON(str);
				var poinvid=val['addList'][0]['rowData'].po_invoice_id;
				 $('.poinvoiceid').val(poinvid);
            }
        };
        $("#leaveoverallgrid").pqGrid(obj);
$("#leaveoverallgrid" ).pqGrid({ scrollModel:{autoFit: false }});
    $(document).on('click',".exportexcel",function() {
        $("#leaveoverallgrid").jqGrid("exportToExcel",{
                                                includeLabels : true,
                                                includeGroupHeader : true,
                                                includeFooter: true,
                                                fileName : "Dispatch Details Report"+file_date+"-"+time+".xlsx"

                                        })		 
    });

});

var data = "<?php echo \Session('j_date_format'); ?>";
var grid_min_date="{{\Session::get('js_griddate')}}";
  var grid_max_date="{{\Session::get('js_gridenddate')}}";
 						$('.start_date').datepicker({
    					changeMonth: true,
     					dateFormat: data,
     					changeYear: true,
      					minDate: grid_min_date,
      					maxDate: grid_max_date,
      					onClose: function( selectedDate ) {
        				jQuery( "#end_date" ).datepicker( "option", "minDate", selectedDate );
        }
      
  });
						$('.end_date').datepicker({
						changeMonth: true,
						dateFormat: data,
						changeYear: true,
						minDate: grid_min_date,
      					maxDate: grid_max_date,
						onClose: function( selectedDate ) {
						jQuery( "#start_date" ).datepicker( "option", "maxDate", selectedDate );
						}
						      
  });   

function highchart(data)
{
	
}

    </script>
@endsection
