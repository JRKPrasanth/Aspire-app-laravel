@extends('layouts.header')
@section('content')
<style type="text/css">


.divhide .card{
    padding: 5px;
    border: 1px solid #ccc;
}
.divhide .table{
    width: 100%;
}
.card-header{
    border-bottom:1px solid #ccc;
}
</style>
<h2 class="heads"> Operation report</h2>
            <div class="row">

             
         
                <div class="col-md-4">
                       <div class="form-group  row" > 
                    <label for="Description" class=" control-label col-md-4 text-left"> 
                     From Date
                    </label>
                    <div class="col-md-6">
                        <input type="text" name="from_date" class="form-control from_date datepicker" id="from_date">
                   </div> 
                    <div class="col-md-2">

                    </div>
                </div>  
                </div> 
                 <div class="col-md-4">
                      <div class="form-group row " > 
                    <label for="year" class=" control-label col-md-4 text-left"> 
                      To Date
                    </label>
                    <div class="col-md-6">
                         <input type="text" name="to_date" class="form-control to_date datepicker" id="to_date">
                        </select>
                    </div> 
                    <div class="col-md-2">

                    </div>
                    </div>
                    </div>
               
                <div class="col-md-4">
                      <div class="form-group row " > 
                    <label for="year" class=" control-label col-md-4 text-left"> 
                     
                    </label>
                    <div class="col-md-6">
                    
             <button name="submit" type="button" style="
    margin-top: 0;" class="btn search report_search" >Search</button>
             
                    </div>
                    <div class="col-md-2">

                    </div>
                </div>

                </div>
                
              </div>
           
<div class="card-body card-block">
<div class="divhide">
    </div>
<div class="row">
    <div class="col-md-12" >
  <div id="grid1" style="margin:5px auto;"></div>   
  </div>
  </div>

</div>
</div>


<script src="{{ asset('js/pqgrid.min.js')}}"></script>
<script src="{{ asset('js/pqselect.min.js')}}"></script>
<script src="{{ asset('js/pq-localize-en.js')}}"></script>
<link rel="stylesheet" href="{{ asset('css/pqselect.min.css')}}" />
<link rel="stylesheet" href="{{ asset('css/pqgrid.min.css')}}" />
<link rel="stylesheet" href="{{ asset('css/pqgrid.ui.min.css')}}" />
<link rel="stylesheet" href="{{ asset('css/pqgrid.css')}}" />
<script src="{{ asset('js/filesaver.js')}}"></script>

  <script type="text/javascript">


  
$(document).ready(function()
{
  function pqDatePicker(ui) {
            var $this = ui.$editor;
            $this.datepicker({
                    yearRange: "-25:+0",
                    changeYear: true,
                    changeMonth: true,
                    dateFormat:"yy-mm-dd"
                    
                });
        }
// search
$(document).on('click', '.report_search', function () {
	

        var from_date = $('#from_date').val();
       var to_date = $('#to_date').val();
          if(from_date=="")
             from_date=0;
          if(to_date=="")
             to_date=0;
         if(from_date!=0){
            var url = "{{URL::to('operationreportdetails')}}?from_date="+from_date+"&to_date="+to_date;
      
 var url = url;
 console.log(url);
     obj.dataModel.url=url;
     $( "#grid1" ).pqGrid( "option" , "dataModel.url",url );
    $("#grid1").pqGrid("refreshDataAndView"); 
           
           
                     
      
       }else{
            notyMsg("info","Please Choose Fields");
        }
        
    });





    var colM = [{ dataIndx: "plan_no",align: "begin",minWidth:"20%",title: "Plan No" , filter: { crules: [{condition: "begin" }] }},
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
   
   { dataIndx: "starttime", align: "right",minWidth:"20%",title: "Start Time", filter: { crules: [{condition: "begin" }] }},
   { dataIndx: "endtime", align: "right",minWidth:"20%",title: "End Time", filter: { crules: [{condition: "begin" }] }},
   
    { dataIndx: "working_hours", align: "right",minWidth:"20%",title: "Emp Hrs", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "empqty", align: "right",minWidth:"20%",title: "Emp Qty", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "devhrs", align: "right",minWidth:"20%",title: "Dev Hrs", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "machine_time", align: "right",minWidth:"20%",title: "M/C Hrs", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "machine_name", align: "right",minWidth:"20%",title: "M/c Name", filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "machine_code", align: "right",minWidth:"20%",title: "M/c Code", filter: { crules: [{condition: "begin" }] }},
    
    ];
  

 var url="";
 var dataModel = {
                location: "remote",            
                dataType: "JSON",
                method: "GET",
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
                colModel: colM,
                pageModel: { type: "remote", rPP: 10, strRpp: "{0}",strPage:"{0} of {1}" },
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
                        var file_date   =  ($('.to_date').val() != '') ? $('.to_date').val()  : '';
                        console.log(file_date);
                        var url=$.trim($.cookie('grid1_pq_export_url')).slice(1,-1)+"&download=1";
                        $.get(url,function(s){
                            var blob = data.exportData({
                                format: 'xlsx',                                
                                render: true,
                                type: 'blob',
                                data:s
                            });                        
                        saveAs(blob, "Operation Report"+file_date+"-"+time+".xlsx" );
                        });
                        
                    }
                    },
                    {
                        type: 'button',
                        icon: 'ui-icon-print',
                        label: 'Print',
                        listener: function () {
                            var exportHtml = this.exportData({ title: 'OPERATION REPORT', format: 'htm', render: true }),
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
  
            }
            };

        $("#grid1").pqGrid(obj);
        $("#grid1").pqGrid({ scrollModel:{autoFit: true }});

   var data = "<?php echo \Session('j_date_format'); ?>";
  $('.start_date').datepicker({
    changeMonth: true,
      dateFormat: data,
      changeYear: true,   
      
  });
  $('.end_date').datepicker({
    changeMonth: true,
      dateFormat: data,
      changeYear: true,   
      
  });

});
function highchart(data)
{
    
}
     
  </script>
@include('layouts.php_js_validation')
@endsection