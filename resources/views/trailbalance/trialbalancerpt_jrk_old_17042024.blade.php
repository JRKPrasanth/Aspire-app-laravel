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
<h2 class="heads">Trail Balance report</h2>
<div class="card">


<div class="card-body card-block">
  <form method="post" action="" id="job_card_reprot" class="org_form" data-parsley-validate enctype="multipart/form-data">
 
{{ csrf_field() }}
    <div class=" col-md-4">
        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4">From Date</label>
            <div class="col-md-6">
                            <div class="input-group form_date "    data-date=""   data-link-format="yyyy-mm-dd">
                                            <input class="form-control start_date  " id="start_date" name="start_date"  required type="text" value="" style="border-radius: 5px;">
                                             
                            </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4">To Date</label>
            <div class="col-md-6">
                    <div class="input-group form_date "    data-date=""   data-link-format="yyyy-mm-dd">
                                    <input class="form-control end_date  " id="end_date" name="end_date"  required type="text" value="" style="border-radius: 5px;">
                                     <!-- <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> -->
                    </div>
            </div>
        </div>
    </div>


     <div class="col-md-4">
        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4">Type</label>
            <div class="col-md-6">
                    <div class="input-group form_date "    data-date=""   data-link-format="yyyy-mm-dd">
                                  <select class="type select2" id="type" ><option value=''>--Please Select--</option><option value="ALL">ALL</option><option value="POSTED">POSTED</option></select>
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
   <div id="trailbalancegrid"></div>
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
    
      $(document).on('click','.search',function()
    {
    
       var start_date = ($('.start_date').val() != '') ? $('.start_date').val() : '';
       var end_date   =  ($('.end_date').val() != '') ? $('.end_date').val()  : ''; 
       var type   =  ($('.type').val() != '') ? $('.type').val()  : ''; 
        
       if(start_date!=''&&end_date!='' && type!=''){
      var url="{{URL::to('gettrialbalance')}}/?start_date="+start_date+"&end_date="+end_date+"&type="+type;
            var url = url;
            obj.dataModel.url=url;
     $( "#trailbalancegrid" ).pqGrid( "option" , "dataModel.url",url );
    $("#trailbalancegrid").pqGrid("refreshDataAndView");
        }else{
             notyMsg("info","Please Choose Feilds");
         }
        
   });
    var url="";
      var colModel=[
        { dataIndx: "concatenated_segments",minWidth:"23%",align: "Left",title: "Account" , filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "account_id",minWidth:"15%",align: "Left",title: "Code" , filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "FS",minWidth:"15%",align: "Left",title: "FS" , filter: { crules: [{condition: "begin" }] }},
    { dataIndx: "opening_balance",minWidth:"15%",align: "right",title: "Opening Balance" , filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "debit",minWidth:"15%", align: "Right",title: "Debit Amount" , filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "credit",minWidth:"15%", align: "Right",title: "Credit Amount" , filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "balance",minWidth:"15%", align: "Right",title: "Balance" , filter: { crules: [{condition: "begin" }] }},
        
  ];
var dataModel = {
            location: "remote",            
            dataType: "JSON",
            method: "GET",
            url: url,
       getData: function (dataJSON) {
                var data = dataJSON.data;
         var debit_amounts = 0;
         var credit_amounts = 0;
         var opening_balance=0;
         var balance = 0;
         for (var i = 0; i < data.length; i++) {
           
            var row = data[i];
      console.log(row['debit_amounts']);
            if(row["debit"])
            {
               debit_amounts += parseFloat(row["debit"]);
            }
            
      if(row["credit"])
            {
               console.log("c->"+row['credit']);
                 credit_amounts = credit_amounts+parseFloat(row["credit"]);
                 console.log("s->"+credit_amounts);
            }
             if(row["opening_balance"]>0)
            {
                 opening_balance += parseFloat(row["opening_balance"]);
            }
             
           
              // alert(debit_amount);
        }
            balance= debit_amounts-credit_amounts;
       debit_amounts = $.paramquery.formatCurrency(debit_amounts);
       credit_amounts = $.paramquery.formatCurrency(credit_amounts);
       opening_balance = $.paramquery.formatCurrency(opening_balance);
       balance = $.paramquery.formatCurrency(balance);
       console.log(debit_amounts);
       
            totalData = { opening_balance:opening_balance,debit: debit_amounts,credit: credit_amounts,balance:balance,pq_rowcls: 'backgrdstyle'};
           data.push(totalData);  
                return { curPage: dataJSON.curPage, totalRecords: dataJSON.totalRecords, data: data };
            }
        }
         var obj = {
           
            dataModel: dataModel,
            flex:{one: true},
            colModel: colModel,
            pageModel: { type: "remote", rPP: 10, strRpp: "{0}",strPage:"{0} of {1}" },
            wrap: false,
                 width:'100%',        
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
            title: "Ledger Balance Report",
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
                        var url=$.trim($.cookie('trailbalancegrid_pq_export_url')).slice(1,-1)+"&download=1";
                        $.get(url,function(s){
                        
                        var blob = data.exportData({
                                format: 'xlsx',                                
                                render: true,
                                type: 'blob',
                                data:s
                            });                        
                        saveAs(blob, "Trial Balance"+file_date+"-"+time+".xlsx" );
                        });
                    }
                },
                {
                    type: 'button',
                    icon: 'ui-icon-print',
                    label: 'Print',
                    listener: function () {
                        var exportHtml = this.exportData({ title: 'Ledger Balance', format: 'htm', render: true }),
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
        $("#trailbalancegrid").pqGrid(obj);
$("#trailbalancegrid" ).pqGrid({ scrollModel:{autoFit: false }});
    $(document).on('click',".exportexcel",function() {
        $("#trailbalancegrid").jqGrid("exportToExcel",{
                                                includeLabels : true,
                                                includeGroupHeader : true,
                                                includeFooter: true,
                                                fileName : "Trial Balance"+file_date+"-"+time+".xlsx"

                                        })     
    });
    
  });
 /* var data = "<?php echo \Session('j_date_format'); ?>";
  $('.start_date').datepicker({
    changeMonth: true,
      dateFormat: data,
      changeYear: true,   
      
  });
  $('.end_date').datepicker({
    changeMonth: true,
      dateFormat: data,
      changeYear: true,   
      
  });*/
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
@include('layouts.php_js_validation')
@endsection




