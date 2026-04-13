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
<!--  -->

<div class="divhide">
    
    
    </div>
<div class="row">
    <div class="col-md-12" >
   <table id="sopendingqtygrid"></table>
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
		
	 //    $(document).on('click','.search',function()
		// {
		
		// 	 // var start_date = ($('.start_date').val() != '') ? $('.start_date').val() : '';
		// 	 // var end_date   =  ($('.end_date').val() != '') ? $('.end_date').val()  : ''; 
  //   //    var employee_id   =  ($('.employee_id').val() != '') ? $('.employee_id').val()  : ''; 
 		    
		//    // if(start_date!=''&&end_date!=''&&employee_id!=''){
		// 	// var url="{{URL::to('getemp')}}";
  //  //          var url = url;
  //  //          obj.dataModel.url=url;
  //  //   $( "#sopendingqtygrid" ).pqGrid( "option" , "dataModel.url",url );
  //  //  $("#sopendingqtygrid").pqGrid("refreshDataAndView");
  //       // }else{
  //       //      notyMsg("info","Please Choose Feilds");
  //       //  }
        
  //  });
  var url="{{URL::to('getemp')}}";
			var colModel=[
  { dataIndx: "journal_entry_id", align: "center",title: "Id" ,hidden:true},
        { dataIndx: "journal_name",align: "Left",title: "Journal Name" , width:"15%",filter: { crules: [{condition: "begin" }] }},
       
        { dataIndx: "journal_date",align: "Left",title: "Date" , width:"15%",filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "concatenated_segments",align: "Left",title: "Account" , width:"20%",filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "debit_amounts", align: "Right",title: "Debit Amount" ,width:"15%", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "credit_amounts", align: "Right",title: "Credit Amount" ,width:"15%", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "balance", align: "Right",title: "Balance" ,width:"15%", filter: { crules: [{condition: "begin" }] }},
        
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
                        var url=$.trim($.cookie('sopendingqtygrid_pq_export_url')).slice(1,-1)+"&download=1";
                        $.get(url,function(s){
                        
                        var blob = data.exportData({
                                format: 'xlsx',                                
                                render: true,
                                type: 'blob',
                                data:s
                            });                        
                        saveAs(blob, "Employee Balaces.xlsx" );
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
$("#sopendingqtygrid" ).pqGrid({ scrollModel:{autoFit: true }});
    $(document).on('click',".exportexcel",function() {
        $("#sopendingqtygrid").jqGrid("exportToExcel",{
                                                includeLabels : true,
                                                includeGroupHeader : true,
                                                includeFooter: true,
                                                fileName : "Employee Balance.xlsx"

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




