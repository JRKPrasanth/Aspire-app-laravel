@extends('layouts.header')
@section('content')

<style type="text/css">


.divhide .card{
    border:1px solid #ccc;
}
.card-header {
    
    border-bottom: 1px solid #ccc;
}

.pq-grid-bottom
{
  style:block !important;
}

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
<div class="card">
    <div class="card-body">
<div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a role="button">
                                 Sales Stock Ledger Report
                                    </a>
                                </h4>
                            </div>

    <div class="row">
    <div class="col-md-12">   
        <div class="col-md-6">                  
            <div class="form-group row">
                <label for="inputIsValid" class="form-control-label col-md-4">From Date</label>
                <div class="col-md-6">
                    <div class="input-group form_date " data-date=""   data-link-format="yyyy-mm-dd">
                    <input class="form-control start_date  " id="start_date" name="start_date"  required type="text" value="" style="border-radius: 5px;">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">                  
            <div class="form-group row">
                <label for="inputIsValid" class="form-control-label col-md-4">To Date</label>
                <div class="col-md-6">
                    <div class="input-group form_date " data-date=""   data-link-format="yyyy-mm-dd">
                    <input class="form-control end_date  " id="end_date" name="end_date"  required type="text" value="" style="border-radius: 5px;">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">                  
            <div class="form-group row">
                <label for="inputIsValid" class="form-control-label col-md-4">Product Name</label>
                <div class="col-md-6">
                    <select class="form-control product_id select2  " id="product_id" name="product_id"  required style="border-radius: 5px;">
                        {{!!$product_id!!}}
                    </select>
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
    $('.divhide').hide();


    $(document).on('click','.search',function()
    {
        var start_date = ($('.start_date').val() != '') ? $('.start_date').val() : '';
        var end_date   =  ($('.end_date').val() != '') ? $('.end_date').val()  : ''; 
        var product_id   =  ($('.product_id').val() != '') ? $('.product_id').val()  : ''; 
        var url = "{{URL::to('getsalesstockledgerrpt')}}/?start_date="+start_date+"&end_date="+end_date+"&product_id="+product_id;
        if(start_date!=''&&end_date!='' && product_id !=''){


    var colModel=[
	{ dataIndx: "product_id", align: "center",title: "Id" ,hidden:true},
        { dataIndx: "product_code",align: "center",title: "Product Code" , minWidth:"10%",filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "concatenated_product",align: "center",title: "Product Name" , minWidth:"35%",filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "date",align: "center",title: "Date" , minWidth:"10%",filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "opening_bal",align: "center",title: "Opening Balance" , minWidth:"10%",filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "batch_number", align: "center",title: "Batch Number" ,minWidth:"18%", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "stock_in", align: "center",title: "Stock In" ,minWidth:"18%", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "invoice_no", align: "center",title: "Invoice No" ,minWidth:"18%", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "stock_out", align: "center",title: "Stock Out" ,minWidth:"18%", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "closing_bal", align: "center",title: "Closing Balance" ,minWidth:"18%", filter: { crules: [{condition: "begin" }] }},
	];
  //define dataModel
        var dataModel = {
            location: "remote",            
            dataType: "JSON",
            method: "GET",
            url: url,
		 getData: function (dataJSON) {
                var data = dataJSON.data;
                 /* Purpose For Sum Of Total Values Display*/
                   var stockin = 0;
                   var stockout = 0;
            
        for (var i = 0; i < data.length; i++) {
            var row = data[i];
            
            stockin += parseFloat(row["stock_in"]);
            stockout += parseFloat(row["stock_out"]);
        }
            stockin = $.paramquery.formatCurrency(stockin);
            stockout = $.paramquery.formatCurrency(stockout);
            totalData = {batch_number: "Total",  stock_in: stockin,stock_out:stockout,pq_rowcls: 'backgrdstyle'};
           data.push(totalData);
        /*End Purpose For Sum Of Total Values Display*/
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
            title: "Sales Stock Ledger Report",
            resizable: true,
            hwrap:false,
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
                        saveAs(blob, "Sales Stock Ledger Report.xlsx" );
                    }
                },
                {
                    type: 'button',
                    icon: 'ui-icon-print',
                    label: 'Print',
                    listener: function () {
                        var exportHtml = this.exportData({ title: 'Sales Stock Ledger Report', format: 'htm', render: true }),
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
$("#sopendingqtygrid" ).pqGrid({ scrollModel:{autoFit: false
 }});
    $(document).on('click',".exportexcel",function() {
        $("#sopendingqtygrid").jqGrid("exportToExcel",{
                                                includeLabels : true,
                                                includeGroupHeader : true,
                                                includeFooter: true,
                                                fileName : "Sales Stock Ledger Report.xlsx"

                                        })		 
    });
        }else{
            notyMsg("info","Please Select From & To Period & product name");
        }
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

});

function highchart(data)
{
	
}

    </script>
@endsection
