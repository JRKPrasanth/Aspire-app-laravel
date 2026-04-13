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


<div id="accordion">
<div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a role="button">

                                        Production Report
                                    </a>
                                </h4>
                            </div>

</div>




 <div class="row">
    <div class="col-md-12" >
   <table id="sopendingqtygrid"></table>
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
     var colModel=[
        { dataIndx: "sales_hdr_id", align: "center",title: "Id" ,hidden:true},
        { dataIndx: "sales_order_date", align: "center",title: "Rqmt #" , filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "sales_order_no",align: "center",title: "Date" , filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "sales_order_no",align: "center",title: "Month" , filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "sales_order_no",align: "center",title: "Year" , filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "sales_order_no",align: "center",title: "Product" , filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "sales_order_no",align: "center",title: "Unit Pack" , filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "sales_order_no",align: "center",title: "Batch No" , filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "sales_order_no",align: "center",title: "Work Type" , filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "customer_name", align: "center",title: "Order Type", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "delivery_date", align: "center",title: "Batch Size", filter: { crules: [{condition: "begin" }] } },
        { dataIndx: "delivery_date", align: "center",title: "Uom", filter: { crules: [{condition: "begin" }] } },
        { dataIndx: "delivery_date", align: "center",title: "Category", filter: { crules: [{condition: "begin" }] } },
        { dataIndx: "delivery_date", align: "center",title: "Planned Date", filter: { crules: [{condition: "begin" }] } },
        { dataIndx: "delivery_date", align: "center",title: "Actual Date", filter: { crules: [{condition: "begin" }] } },
        { dataIndx: "delivery_date", align: "center",title: "Schedule type", filter: { crules: [{condition: "begin" }] } },
        { dataIndx: "concatenated_product",align: "center",title: "Emp Name", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "concatenated_product",align: "center",title: "Work Activity", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "concatenated_product",align: "center",title: "Type", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "concatenated_product",align: "center",title: "MC Name", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "concatenated_product",align: "center",title: "MC ID No.", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "concatenated_product",align: "center",title: "Man Power", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "concatenated_product",align: "center",title: "Qty / Min", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "concatenated_product",align: "center",title: "MC Stngs_Hrs:Mins", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "concatenated_product",align: "center",title: "Trgt Qty", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "concatenated_product",align: "center",title: "Expctd_Hrs:Mins", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "concatenated_product",align: "center",title: "Ind_Exp: Hrs", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "concatenated_product",align: "center",title: "Act_Hrs:Mins", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "concatenated_product",align: "center",title: "Act Qty", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "concatenated_product",align: "center",title: "Ind_Act", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "concatenated_product",align: "center",title: "Ind_Act Hrs:Mins", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "concatenated_product",align: "center",title: "Ind_Dev Hrs", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "concatenated_product",align: "center",title: "Ind_Dev Mins", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "concatenated_product",align: "center",title: "Dev Hrs:Mins", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "concatenated_product",align: "center",title: "Dev in Minutes", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "concatenated_product",align: "center",title:  "MC Totidle Hrs-24:00:00", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "concatenated_product",align: "center",title: "In Days", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "concatenated_product",align: "center",title: "Remarks", filter: { crules: [{condition: "begin" }] }}
        ];
  //define dataModel
        var dataModel = {
            location: "remote",            
            dataType: "JSON",
            method: "GET",
            url: "{{URL::to('getsopendingqty')}}",
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
            title: "SALES ORDER PENDING QTY",
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
                        saveAs(blob, "pendingsoqtyreport.xlsx" );
                    }
                },
                 {
                    type: 'button',
                    icon: 'ui-icon-print',
                    label: 'Print',
                    listener: function () {
                        var exportHtml = this.exportData({ title: 'SALES ORDER PENDING QTY', format: 'htm', render: true }),
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
                                                fileName : "Purchase Order Details.xlsx"

                                        })       
    });

});

function highchart(data)
{
    
}

    </script>
@endsection
