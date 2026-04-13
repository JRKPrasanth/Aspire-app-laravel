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
                                  Consumable Entry Stock Report
                                    </a>
                                </h4>
                            </div>

</div>




 <div class="row">
    <div class="col-md-12" >
   <div id="consumablerptgrid"></div>
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
    var group = "{{$pageMethod}}";
    
     var colModel=[
        { dataIndx: "product_id", align: "left",title: "Id" ,hidden:true},
        { dataIndx: "product_code",align: "left",title: "Product Code" ,min-width:"13%", filter: { crules: [{condition: "begin" }] }}, 
		   { dataIndx: "concatenated_product",align: "left",title: "Product Name" ,minWidth:"26%", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "batch_no",align: "left",title: "Batch No" , minWidth:"13%",filter: { crules: [{condition: "begin" }] }}, 
        { dataIndx: "subinventory_name",align: "left",title: "Subinventory" ,minWidth:"13%", filter: { crules: [{condition: "begin" }] }}, 
        { dataIndx: "locator_code",align: "left",title: "Locator Code" ,minWidth:"13%", filter: { crules: [{condition: "begin" }] }},
      { dataIndx: "qoh",align: "right",title: "Qoh" , minWidth:"13%",filter: { crules: [{condition: "begin" }] }},
         { dataIndx: "qty",align: "right",title: "Consumable Qty" ,minWidth:"13%", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "concatenated_segments",align: "right",title: "Account Structure" , minWidth:"13%",filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "comments",align: "right",title: "Comments" , minWidth:"13%",filter: { crules: [{condition: "begin" }] }},
        

        ];
  //define dataModel
        var dataModel = {
            location: "remote",            
            dataType: "JSON",
            method: "GET",
            url: "{{URL::to('getconsumabledata')}}?group="+group+"&locator="+loc+"&subcategory="+ctgy+"&loccode="+cod,
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
            pageModel: { type: "remote", rPP: 10,rPPOptions: [10,50,100,500,1000,1500,2000,2500] },
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
                mode:"AND",
                menuIcon: true 
            },
            title: group+" STOCK REPORT",
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
                        var url=$.trim($.cookie('consumablerptgrid_pq_export_url')).slice(1,-1)+"&download=1";
                        $.get(url,function(s){
                        
                        var blob = data.exportData({
                                format: 'xlsx',                                
                                render: true,
                                type: 'blob',
                                data:s
                            });                        
                        saveAs(blob, "consumablereport.xlsx" );
                        });
                    }
                },
                 {
                    type: 'button',
                    icon: 'ui-icon-print',
                    label: 'Print',
                    listener: function () {
                        var exportHtml = this.exportData({ title: group+" CONSUMABLE STOCK ENTRY REPORT", format: 'htm', render: true }),
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

        $("#consumablerptgrid").pqGrid(obj);
$("#consumablerptgrid" ).pqGrid({ scrollModel:{autoFit: true }});
    $(document).on('click',".exportexcel",function() {
        $("#consumablerptgrid").jqGrid("exportToExcel",{
                                                includeLabels : true,
                                                includeGroupHeader : true,
                                                includeFooter: true,
                                                fileName : "CONSUMABLE STOCK REPORT Details.xlsx"

                                        })       
    });

});

function highchart(data)
{
    
}

    </script>
@endsection
