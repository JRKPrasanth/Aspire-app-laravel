@extends('layouts.header')
@section('content')
<style type="text/css">


.divhide .card{
    border:1px solid #ccc;
}
.card-header {
    
    border-bottom: 1px solid #ccc;
}


</style>

<div id="accordion">
<div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a role="button">
                                      Consolidated Employee Activity Report
                                    </a>
        </h4>
  </div>
</div>

<div class="card">
    <div class="card-body">
<div class="row">
<div class="col-md-12">   
 
			<div class="col-md-6">					
        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4">From Date</label>
            <div class="col-md-6">
                            <div class="input-group form_date " 	 data-date=""   data-link-format="yyyy-mm-dd">
                                            <input class="form-control start_date  " id="start_date" name="start_date"  required type="text" value="" style="border-radius: 5px;" autocomplete="off">
                                             
                            </div>
            </div>
        </div>
    </div>
	  
    <div class="col-md-6">
        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4">To Date</label>
            <div class="col-md-6">
                    <div class="input-group form_date " 	 data-date=""   data-link-format="yyyy-mm-dd">
                                    <input class="form-control end_date  " id="end_date" name="end_date"  required type="text" value="" style="border-radius: 5px;" autocomplete="off">
                                     <!-- <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> -->
                    </div>
            </div>
        </div>
    </div>
<div class="form-group row">
                                   <label for="inputIsValid" class="form-control-label col-md-offset-3 col-md-2"><span style="color:red; " > </span></label>
                                    <div class="col-md-1">
										<a><button type="button" class="btn add search" id="search" value="">Search</button></a>
                                    </div>
                                    <div class="col-md-2 showline">
                                    </div>
                                </div>
								
<div class="row">
    <div class="col-md-12" >
   <div id="consolempactreportdetailsid" style="margin:5px auto;"></div>
  </div>
  </div>
</div>
</div>
</div>

</div>
<?php  $date=date("Y-m-d");?>

<!--<a href="{{URL::to('journalreport.csv')}}" class='download_link' download></a>-->
<a href="<?php echo URL::to('consolempactreport.csv') ?>" class='download_link' download></a>
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
		$('.divhide').hide();
	    $(document).on('click','.search',function()
{
			 var start_date = ($('.start_date').val() != '') ? $('.start_date').val() : '';
			 var end_date   =  ($('.end_date').val() != '') ? $('.end_date').val()  : ''; 
		 if(start_date!=''&&end_date!=''){
			var url="{{URL::to('consolempactreportdetails')}}/?start_date="+start_date+"&end_date="+end_date;
	 
   var colModel=[
    { dataIndx: "plan_no",align: "begin",minWidth:"20%",title: "Plan No" , filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "type", align: "right",minWidth:"20%",title: "Type", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "yr_month", align: "right",minWidth:"20%",title: "Month Year", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "plan_date", align: "begin",minWidth:"20%",title: "Plan Date" ,dataType: "date",minWidth:"19%", filter: {
                    crules: [{condition: "begin" }] }},
    { dataIndx: "job_no", align: "right",minWidth:"20%",title: "Job No", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "job_date", align: "right",minWidth:"20%",title: "Job Created Date", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "product_name", align: "begin",minWidth:"20%",title: "Product", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "production_qty", align: "right",minWidth:"20%",title: "Plan Qty", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "shift", align: "right",minWidth:"20%",title: "Shift", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "pack_name", align: "right",minWidth:"20%",title: "Unit Pack", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "batch_no", align: "right",minWidth:"20%",title: "Batch No", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "job_qty", align: "right",minWidth:"20%",title: "Job Qty", filter: { crules: [{condition: "begin" }] }},
    //{ dataIndx: "job_completed_qty", align: "right",minWidth:"20%",title: "Job Completed Qty", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "job_completion_date", align: "right",minWidth:"20%",title: "Job Completion Date", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "process_level", align: "right",minWidth:"20%",title: "Process Level", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "process_name", align: "right",minWidth:"20%",title: "Process Name", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "process_date", align: "right",minWidth:"20%",title: "Process Date", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "job_assigned_name", align: "right",minWidth:"20%",title: "JC Open Emp Name", filter: { crules: [{condition: "begin" }] }},
   
    { dataIndx: "machour", align: "right",minWidth:"20%",title: "M/c Hours based JC Qty", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "qa_assigned_name", align: "right",minWidth:"20%",title: "JC Comp Emp Name", filter: { crules: [{condition: "begin" }] }},
   
    { dataIndx: "startdt", align: "right",minWidth:"20%",title: "Start Date", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "enddt", align: "right",minWidth:"20%",title: "End Date", filter: { crules: [{condition: "begin" }] }},

    { dataIndx: "starttime", align: "right",minWidth:"20%",title: "Start Time", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "endtime", align: "right",minWidth:"20%",title: "End Time", filter: { crules: [{condition: "begin" }] }},
   
    { dataIndx: "working_hours", align: "right",minWidth:"20%",title: "Emp Hrs", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "empqty", align: "right",minWidth:"20%",title: "Emp Qty", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "devhrs", align: "right",minWidth:"20%",title: "Dev Hrs", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "machine_time", align: "right",minWidth:"20%",title: "M/C Hrs", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "machine_name", align: "right",minWidth:"20%",title: "M/c Name", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "machine_code", align: "right",minWidth:"20%",title: "M/c Code", filter: { crules: [{condition: "begin" }] }},
        
  ];



//var url="";

  //define dataModel
        var dataModel = {
            location: "remote",            
            dataType: "JSON",
            method: "GET",
            url:url,
       getData: function (dataJSON) {
                var data = dataJSON.data;
                 console.log(data);
                return { curPage: dataJSON.curPage, totalRecords: dataJSON.totalRecords, data: data };
            }
        }
         var obj = {
            width:'100%',
            dataModel: dataModel,
            flex:{one: true},
            colModel: colModel,
            pageModel: { type: "remote",rPP:10,  strRpp: "{0}"},
            wrap: false,           
            editable: false,  
            menuIcon: true,
       editable: false,
            numberCell: { show: false },
            selectionModel: { type: 'row' },
            title: "Consolidated Employee Activity Report",
            resizable: true,
           menuUI: {
                singleFilter: true
            },
            filterModel: { 
                on: true,             
                header: true, 
                type: 'remote', 
                menuIcon: true 
            },
            freezeCols: 2,
            toolbar: {
                items: [{
                    type: 'button',
                    label: "Export to Excel",
                    icon: 'ui-icon-arrowthickstop-1-s',
                   

     listener: function () {
                        var data=this;
                        var url=$.trim($.cookie('consolempactreportdetailsid_pq_export_url')).slice(1,-1)+"&download=1";
                        $.get(url,function(s){
                        $('.download_link')[0].click();
                        });
                    }
                },
                 {
                    type: 'button',
                    icon: 'ui-icon-print',
                    label: 'Print',
                    listener: function () {
                        var exportHtml = this.exportData({ title: 'Consolidated Employee Activity Report', format: 'htm', render: true }),
                            newWin = window.open('', '', 'width=1200, height=700'),
                            doc = newWin.document.open();
                        doc.write(exportHtml);
                        doc.close();
                        newWin.print();
                    }
                }]
            },

        };

    

               $("#consolempactreportdetailsid").pqGrid(obj);
$("#consolempactreportdetailsid" ).pqGrid({ scrollModel:{autoFit: false }});
    $(document).on('click',".exportexcel",function() {
        $("#consolempactreportdetailsid").jqGrid("exportToExcel",{
                                                includeLabels : true,
                                                includeGroupHeader : true,
                                                includeFooter: true,
                                                fileName : "Consolidated Employee Activity Report.xlsx"

                                        })     
    });


 
			 }
                      else{
                          notyMsg("info","Please Select From & To Period");
                      }
});

		});
$(function()
{
  
  
//   $('.start_date').datepicker({
//     changeMonth: true,
//       dateFormat: data,
//       changeYear: true,   
      
//   });
//   $('.end_date').datepicker({
//     changeMonth: true,
//       dateFormat: data,
//       changeYear: true,   
      
//   });
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




