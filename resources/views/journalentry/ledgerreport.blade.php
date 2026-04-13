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
<h2 class="heads">Ledger Balance report</h2>
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

                    </div>
            </div>
        </div>
    </div>

    <div class="col-md-offset-2 col-md-4">
        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4">Ledger Name</label>
            <div class="col-md-6">
                  <select id="ledger_id" name='ledger_id' rows='5'  class='form-control ledger_id select2' tabindex="1" data-show-subtext="true" data-live-search="true" required>
                    {!! $ledger_id !!}
            </select>
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
   <div id="ledgergrid"></div>
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
       var ledger_id   =  ($('.ledger_id').val() != '') ? $('.ledger_id').val()  : ''; 
 		    
		   if(start_date!=''&&end_date!=''&&ledger_id!=''){
			var url="{{URL::to('getledgerbalance')}}/?start_date="+start_date+"&end_date="+end_date+"&ledger_id="+ledger_id;
            var url = url;
            obj.dataModel.url=url;
     $( "#ledgergrid" ).pqGrid( "option" , "dataModel.url",url );
    $("#ledgergrid").pqGrid("refreshDataAndView");
        }else{
             notyMsg("info","Please Choose Feilds");
         }
        
   });
    var url="";
			var colModel=[
  { dataIndx: "journal_entry_id",minWidth:"15%", align: "center",title: "Id" ,hidden:true},
        { dataIndx: "journal_name",minWidth:"15%",align: "Left",title: "Journal Name" ,filter: { crules: [{condition: "begin" }] }},
       
        { dataIndx: "journal_date",minWidth:"15%",align: "Left",title: "Date" , filter: { crules: [{condition: "begin" }] }},
		{ dataIndx: "reference_source",minWidth:"15%",align: "Left",title: "Reference Source" , filter: { crules: [{condition: "begin" }] }},
		{ dataIndx: "reference_name",minWidth:"15%",align: "Left",title: "Reference Name" , filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "concatenated_segments",minWidth:"15%",align: "Left",title: "Account" , filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "debit_amounts",minWidth:"15%", align: "Right",title: "Debit Amount" , filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "credit_amounts",minWidth:"15%", align: "Right",title: "Credit Amount" , filter: { crules: [{condition: "begin" }] }},
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
				 var balance = 0;
				 for (var i = 0; i < data.length; i++) {
					 
            var row = data[i];
			console.log(row['debit_amounts']);
            if(row["debit_amounts"])
            {
               debit_amounts += parseFloat(row["debit_amounts"]);
            }
            
			if(row["credit_amounts"]>0)
            {
                 credit_amounts += parseFloat(row["credit_amounts"]);
            }
           
              // alert(debit_amount);
        }
	          balance= debit_amounts-credit_amounts;
 			 debit_amounts = $.paramquery.formatCurrency(debit_amounts);
			 credit_amounts = $.paramquery.formatCurrency(credit_amounts);
			 balance = $.paramquery.formatCurrency(balance);
			 console.log(debit_amounts);
			 
            totalData = { debit_amounts: debit_amounts,credit_amounts: credit_amounts,balance:balance,pq_rowcls: 'backgrdstyle'};
           data.push(totalData);	
                return { curPage: dataJSON.curPage, totalRecords: dataJSON.totalRecords, data: data };
            }
        }
         var obj = {
           
            dataModel: dataModel,
            flex:{one: true},
            colModel: colModel,
            pageModel: { type: "remote", rPP: 10000, strRpp: "{0}",strPage:"{0} of {1}" },
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
                        var blob = this.exportData({
                                format: 'xlsx',                                
                                render: true,
                                type: 'blob'
                            });                        
                        saveAs(blob, "Ledger Balance.xlsx" );
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
        $("#ledgergrid").pqGrid(obj);
$("#ledgergrid" ).pqGrid({ scrollModel:{autoFit: false }});
    $(document).on('click',".exportexcel",function() {
        $("#ledgergrid").jqGrid("exportToExcel",{
                                                includeLabels : true,
                                                includeGroupHeader : true,
                                                includeFooter: true,
                                                fileName : "Ledger Balance.xlsx"

                                        })     
    });
		
	});
  /*var data = "<?php echo \Session('j_date_format'); ?>";
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

	
function highchart(data)
{
    
}

var data = "<?php echo \Session('j_date_format'); ?>";
 						$('.start_date').datepicker({
    					changeMonth: true,
     					dateFormat: data,
     					changeYear: true,
      					minDate: "01-04-2024",
      					maxDate: 0,
      					onClose: function( selectedDate ) {
        				jQuery( "#end_date" ).datepicker( "option", "minDate", selectedDate );
        }
      
  });
						$('.end_date').datepicker({
						changeMonth: true,
						dateFormat: data,
						changeYear: true,
						maxDate: 0,
						onClose: function( selectedDate ) {
						jQuery( "#start_date" ).datepicker( "option", "maxDate", selectedDate );
						}
						      
  });          
	</script>
@include('layouts.php_js_validation')
@endsection




