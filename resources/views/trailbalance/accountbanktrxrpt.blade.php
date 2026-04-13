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


<div class="card">
    <div class="card-body">
<div class="row">
<div class="col-md-12">   
    <div class="col-md-4">					
        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4">From Date</label>
            <div class="col-md-6">
                <div class="input-group form_date " 	 data-date=""   data-link-format="yyyy-mm-dd">
                    <input class="form-control start_date  " id="start_date" name="start_date"  required type="text" value="" style="border-radius: 5px;" autocomplete="off">
                </div>
            </div>
        </div>
    </div>
	  
    <div class="col-md-4">
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
    <!--<div class="col-md-4">-->
    <!--<div class="form-group row " >-->
    <!--                            <label for="inputIsValid" class="form-control-label col-md-4">Account Number</label>-->
    <!--                            <div class="col-md-6 supplier_div">-->
    <!--                                <select name='account_no' rows='5' id='account_no' class='select2 account_no'>-->
    <!--                                    {!! $account_no !!}-->
    <!--                                </select>-->
    <!--                            </div>-->
                                
    <!--                    </div>-->
    
    <!--</div>-->
    
    <!--<div class="col-md-2">-->
    <!--    <div class="form-group row">-->
    <!--        <label for="inputIsValid" class="form-control-label col-md-4">Cash</label>-->
    <!--        <div class="col-md-6">-->
    <!--            <div class="input-group form_date " 	 data-date=""   data-link-format="yyyy-mm-dd">-->
    <!--                <input class="form-control cash " id="cash" name="cash" type="checkbox" value="" style="border-radius: 5px;" autocomplete="off" checked>-->
                    
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->
    <!--<div class="col-md-2">-->
    
      
    <div class="col-md-4">
        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4">Bank</label>
            <div class="col-md-6">
                <div class="input-group form_date " 	 data-date=""   data-link-format="yyyy-mm-dd">
                    <input class="form-control bank " id="bank" name="bank" type="checkbox" value="" style="border-radius: 5px;" autocomplete="off" checked>
                    
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
								
<div class="divhide">
    
  	
    </div>
    
 
</div>
</div>
</div>

<div class="row">
    <div class="col-md-12" >
   <div id="accounttrxgrid"></div>
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
		$('.divhide').hide();
		
		function pqDatePicker(ui) {
            var $this = ui.$editor;
            $this.datepicker({
                    yearRange: "-25:+0",
                    changeYear: true,
                    changeMonth: true,
                    dateFormat:"yy-mm-dd"
                    
                });
        }
        
	    $(document).on('click','.search',function(){
			 var start_date = ($('.start_date').val() != '') ? $('.start_date').val() : '';
			 var end_date   =  ($('.end_date').val() != '') ? $('.end_date').val()  : ''; 
			  
			 //var cash =  ($('.cash').prop('checked') == true) ? '1'  : '0'; 
			 var bank =  ($('.bank').prop('checked') == true) ? '1'  : '0'; 
		    
		    if(start_date!=''&&end_date!=''){
			    var url="{{URL::to('getaccountbanktransaction')}}/?start_date="+start_date+"&end_date="+end_date+"&bank="+bank;
	            var url = url;
                obj.dataModel.url=url;
                $( "#accounttrxgrid" ).pqGrid( "option" , "dataModel.url",url );
                $("#accounttrxgrid").pqGrid("refreshDataAndView");  
			}else{
                notyMsg("info","Please Select From & To Period");
            }
        });


	    
    var url="";
			var colModel=[
  { dataIndx: "journal_entry_id",minWidth:"12%", align: "center",title: "Id" ,hidden:true},
        { dataIndx: "journal_name",minWidth:"15%",align: "Left",title: "Journal Name" ,filter: { crules: [{condition: "begin" }] }},
       
        { dataIndx: "journal_date",align: "Left",title: "Date" ,dataType: "date",minWidth:"19%", 
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
		{ dataIndx: "reference_source",minWidth:"12%",align: "Left",title: "Reference Source" , filter: { crules: [{condition: "begin" }] }},
		{ dataIndx: "reference_name",minWidth:"15%",align: "Left",title: "Reference Name" , filter: { crules: [{condition: "begin" }] }},
// 		{ dataIndx: "account_number",minWidth:"12%",align: "Left",title: "Account Number" , filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "narration",minWidth:"15%",align: "Left",title: "Narration" , filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "concatenated_segments",minWidth:"15%",align: "Left",title: "Account" , filter: { crules: [{condition: "begin" }] }},
         { dataIndx: "debit_amounts",minWidth:"12%", align: "Right",title: "Debit Amount" , filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "credit_amounts",minWidth:"12%", align: "Right",title: "Credit Amount" , filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "balance",minWidth:"12%", align: "Right",title: "Balance" , filter: { crules: [{condition: "begin" }] }},
        
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
          // data.push(totalData);	
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
            title: "Day Book",
            resizable: true,
            
            freezeCols: 2,
            toolbar: {
                items: [{
                    type: 'button',
                    label: "Export to Excel",
                    icon: 'ui-icon-arrowthickstop-1-s',
                   

     listener: function () {
                        var data=this;
                        var url=$.trim($.cookie('accounttrxgrid_pq_export_url')).slice(1,-1)+"&download=1";
                        $.get(url,function(s){
                        
                        var blob = data.exportData({
                                format: 'xlsx',                                
                                render: true,
                                type: 'blob',
                                data:s
                            });                        
                        saveAs(blob, "accounttrxreport.xlsx" );
                        });
                    }
                },
                {
                    type: 'button',
                    icon: 'ui-icon-print',
                    label: 'Print',
                    listener: function () {
                        var url=$.trim($.cookie('accounttrxgrid_pq_export_url')).slice(1,-1)+"&print=1";
                             window.open(url);
                        
                        // var exportHtml = this.exportData({ title: 'Day Book', format: 'htm', render: true }),
                        //     newWin = window.open('', '', 'width=1200, height=700'),
                        //     doc = newWin.document.open();
                        // doc.write(exportHtml);
                        // doc.close();
                        // newWin.print();
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
        $("#accounttrxgrid").pqGrid(obj);
$("#accounttrxgrid" ).pqGrid({ scrollModel:{autoFit: false }});
    $(document).on('click',".exportexcel",function() {
        $("#accounttrxgrid").jqGrid("exportToExcel",{
                                                includeLabels : true,
                                                includeGroupHeader : true,
                                                includeFooter: true,
                                                fileName : "Day Book.xlsx"

                                        })     
    });
		
		});
     
	</script>
@endsection




