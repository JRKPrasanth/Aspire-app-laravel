@extends('layouts.header')
@section('content')
<script src="{{ asset('js/pqselect.min.js')}}"></script>
<script src="{{ asset('js/pqgrid.min.js')}}"></script>
<script src="{{ asset('js/pq-localize-en.js')}}"></script>
<link rel="stylesheet" href="{{ asset('css/pqselect.min.css')}}" />
<link rel="stylesheet" href="{{ asset('css/pqgrid.min.css')}}" />
<link rel="stylesheet" href="{{ asset('css/pqgrid.ui.min.css')}}" />
<link rel="stylesheet" href="{{ asset('css/pqgrid.css')}}" />
<script src="{{ asset('js/filesaver.js')}}"></script>
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

<div id="accordion">
<div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a role="button">
                                       Operation Report </a>
                                </h4>
</div>
</div>
 <div class="row">
    <div class="col-md-12" >
   <div id="operationreport"></div>
  </div>
  </div>

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



	
   var colModel=[
	{ dataIndx: "workorder_hdr_id", align: "center",title: "Id" ,hidden:true},
	{ dataIndx: "plan_no",align: "begin",minWidth:"20%",title: "Plan No" , filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "plan_date", align: "begin",minWidth:"20%",title: "Plan Date" ,dataType: "date",minWidth:"19%", 
                 render: function (ui) {
                    return ui.cellData;
                },
                 filter: {
                    crules: [{condition: "between" }], init: pqDatePicker,
                    listeners: [{ 'change': function (evt, ui) {

                       

                        this.filter({
                            oper: "add",
                            rule: ui
                        })
                    }
                    }] }},

    { dataIndx: "product_code", align: "begin",minWidth:"20%",title: "Product Code" , filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "product_name", align: "begin",minWidth:"20%",title: "Product", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "production_qty", align: "right",minWidth:"20%",title: "Plan Qty", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "shift", align: "right",minWidth:"20%",title: "Shift", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "job_no", align: "right",minWidth:"20%",title: "Job No", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "job_date", align: "right",minWidth:"20%",title: "Job Created Date", filter: { crules: [{condition: "begin" }] }},
    
    { dataIndx: "pack_name", align: "right",minWidth:"20%",title: "Unit Pack", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "batch_no", align: "right",minWidth:"20%",title: "Batch No", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "job_adjusted_qty", align: "right",minWidth:"20%",title: "Job Adjusted Qty", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "job_completed_qty", align: "right",minWidth:"20%",title: "Job Completed Qty", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "job_completion_date", align: "right",minWidth:"20%",title: "Job Completion Date", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "bom_process", align: "right",minWidth:"20%",title: "Process Level", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "job_process", align: "right",minWidth:"20%",title: "Process Name", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "job_assigned_name", align: "right",minWidth:"20%",title: "JC Open Emp Name", filter: { crules: [{condition: "begin" }] }},
   
    { dataIndx: "machour", align: "right",minWidth:"20%",title: "M/c Hours based JC Qty", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "qa_assigned_name", align: "right",minWidth:"20%",title: "JC Comp Emp Name", filter: { crules: [{condition: "begin" }] }},
   
    { dataIndx: "working_hours", align: "right",minWidth:"20%",title: "Emp Hrs", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "empqty", align: "right",minWidth:"20%",title: "Emp Qty", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "devhrs", align: "right",minWidth:"20%",title: "Dev Hrs", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "machine_name", align: "right",minWidth:"20%",title: "M/c Name", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "machine_code", align: "right",minWidth:"20%",title: "M/c Code", filter: { crules: [{condition: "begin" }] }},
    
   	];
  //define dataModel
        var dataModel = {
            location: "remote",            
            dataType: "JSON",
            method: "GET",
            url: "{{URL::to('operationreportdata')}}",
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
          	 pageModel: { type: "remote", rPP: 10, rPPOptions:[10,20,50,100,500,1000,2000,3000], strRpp: "{0}",strPage:"{0} of {1}" },
		   wrap: false,
            showBottom: true,            
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
            title: "Operation Report",
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
                        var url=$.trim($.cookie('operationreport_pq_export_url')).slice(1,-1)+"&download=1";
                        $.get(url,function(s){
                        
                        var blob = data.exportData({
                                format: 'xlsx',                                
                                render: true,
                                type: 'blob',
                                data:s
                            });                        
                        saveAs(blob, "Operation Report.xlsx" );
                        });
                    }
                },
            {
                    type: 'button',
                    icon: 'ui-icon-print',
                    label: 'Print',
                    listener: function () {
                        var exportHtml = this.exportData({ title: 'Operation Report', format: 'htm', render: true }),
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
        $("#operationreport").pqGrid(obj);
$("#operationreport" ).pqGrid({ scrollModel:{autoFit: false }});
    $(document).on('click',".exportexcel",function() {
        $("#operationreport").jqGrid("exportToExcel",{
                                                includeLabels : true,
                                                includeGroupHeader : true,
                                                includeFooter: true,
                                                fileName : "Operation Report.xlsx"

                                        })		 
    });

});
function highchart(data)
{
	
}


    </script>
@endsection
