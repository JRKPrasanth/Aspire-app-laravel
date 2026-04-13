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
<h2 class="heads">Employee Balance report</h2>
<div class="card">


<div class="card-body card-block">
  <form method="post" action="" id="job_card_reprot" class="org_form" data-parsley-validate enctype="multipart/form-data">
 
{{ csrf_field() }}
    <div class="col-md-offset-2 col-md-4">
        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4">From Date</label>
            <div class="col-md-6">
                            <div class="input-group form_date " 	 data-date=""   data-link-format="yyyy-mm-dd">
                                            <input class="form-control start_date  " id="start_date" name="start_date"  required type="text" value="" style="border-radius: 5px;">
                                             
                            </div>
            </div>
        </div>
    </div>
	  
    <div class="col-md-4">
        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4">To Date</label>
            <div class="col-md-6">
                    <div class="input-group form_date " 	 data-date=""   data-link-format="yyyy-mm-dd">
                                    <input class="form-control end_date  " id="end_date" name="end_date"  required type="text" value="" style="border-radius: 5px;">
                                     <!-- <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> -->
                    </div>
            </div>
        </div>
    </div>

  
  

            <div class="col-lg-12 col-md-12">
                <div class="form-group text-center">
                    <button type="button" class="btn save search" value="SAVE">Search</button>
               </div>
            </div>
    </form>

<div class="divhide">
    
    
    </div>
<div class="row">
    <div class="col-md-12" >
   <div id="sopendingqtygrid"></div>
  </div>
  </div>

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
       var employee_id   =  ($('.employee_id').val() != '') ? $('.employee_id').val()  : ''; 
 		    
		   if(start_date!=''&&end_date!=''){
			var url="{{URL::to('getemployeebalanceall')}}/?start_date="+start_date+"&end_date="+end_date;
            var url = url;
            obj.dataModel.url=url;
     $( "#sopendingqtygrid" ).pqGrid( "option" , "dataModel.url",url );
    $("#sopendingqtygrid").pqGrid("refreshDataAndView");
        }else{
             notyMsg("info","Please Choose Feilds");
         }
        
   });
    var url="";
			var colModel=[
        { dataIndx: "employee_name",align: "Left",title: "Employee Name" ,minWidth:"18%", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "employee_number",align: "Left",title: "Employee Number" ,minWidth:"18%", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "employee_type",align: "Left",title: "Employee Type" ,minWidth:"18%", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "employee_status",align: "Left",title: "Employee Status" ,minWidth:"18%", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "op_balance", align: "Right",title: "OpeningBalance" ,minWidth:"18%", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "debit_amount", align: "Right",title: "Debit Amount" ,minWidth:"18%", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "credit_amount", align: "Right",title: "Credit Amount" ,minWidth:"18%", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "balance", align: "Right",title: "Balance" ,minWidth:"18%", filter: { crules: [{condition: "begin" }] }},
        
  ];
var dataModel = {
            location: "remote",            
            dataType: "JSON",
            method: "GET",
            url: url,
    
        }
         var obj = {
            width:'100%',
            dataModel: dataModel,
            flex:{one: true},
            colModel: colModel,
            pageModel: { type: "remote", rPP: 10, strRpp: "{0}",strPage:"{0} of {1}" },
            wrap: false,
                     
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
            title: "Employee Balance Report",
            resizable: true,
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
                        var url=$.trim($.cookie('sopendingqtygrid_pq_export_url')).slice(1,-1)+"&download=1";
                        $.get(url,function(s){
                        
                        var blob = data.exportData({
                                format: 'xlsx',                                
                                render: true,
                                type: 'blob',
                                data:s
                            });                        
                        saveAs(blob, "Employee Balance All"+file_date+"-"+time+".xlsx" );
                        });
                    }
                },
                {
                    type: 'button',
                    icon: 'ui-icon-print',
                    label: 'Print',
                    listener: function () {
                        var exportHtml = this.exportData({ title: 'Employee Balance', format: 'htm', render: true }),
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
        $("#sopendingqtygrid").pqGrid(obj);
$("#sopendingqtygrid" ).pqGrid({ scrollModel:{autoFit: false }});
    $(document).on('click',".exportexcel",function() {
        $("#sopendingqtygrid").jqGrid("exportToExcel",{
                                                includeLabels : true,
                                                includeGroupHeader : true,
                                                includeFooter: true,
                                                fileName : "Employee Balance All"+file_date+"-"+time+".xlsx"

                                        })     
    });
		
	});
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

	
function highchart(data)
{
    
}
     
	</script>
@include('layouts.php_js_validation')
@endsection




