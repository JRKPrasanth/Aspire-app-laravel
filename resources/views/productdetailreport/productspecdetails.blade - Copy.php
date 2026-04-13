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
                                        PRODUCT SPECIFICATION REPORT
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
        { dataIndx: "quality_product_specs_line_id", align: "center",title: "Id" ,hidden:true},
        { dataIndx: "quality_product_specs_hdr_id", align: "center",title: "Id" ,hidden:true},
        { dataIndx: "group_name",align: "center",title: "Product Group" , filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "category_name",align: "center",title: "Product Category Name" , filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "concatenated_product",align: "center",title: "Product Name" , filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "uom",align: "center",title: "Uom Code" , filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "parameter",align: "center",title: "Parameter Number" , filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "lookup_code",align: "center",title: "Criteria" , filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "spec_value_from",align: "center",title: "From Value" , filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "spec_value_to",align: "center",title: "To Value" , filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "comments",align: "center",title: "Comment" , filter: { crules: [{condition: "begin" }] }}
        ];
  //define dataModel
        var dataModel = {
            location: "remote",            
            dataType: "JSON",
            method: "GET",
            url: "{{URL::to('getproductspecdata')}}",
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
            title: "PRODUCT SPECIFICATION REPORT",
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
                        var url=$.trim($.cookie('sopendingqtygrid_pq_export_url')).slice(1,-1)+"&download=1";
                        $.get(url,function(s){
                        
                        var blob = data.exportData({
                                format: 'xlsx',                                
                                render: true,
                                type: 'blob',
                                data:s
                            });
                        saveAs(blob, "productspecificationreport.xlsx" );
                        });
                    }                },
            {
                    type: 'button',
                    icon: 'ui-icon-print',
                    label: 'Print',
                    listener: function () {
                        var exportHtml = this.exportData({ title: 'PRODUCT SPECIFICATION REPORT', format: 'htm', render: true }),
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
                                                fileName : "PRODUCT SPECIFICATION REPORT.xlsx"

                                        })       
    });

});
function highchart(data)
{
    
}

    </script>
@endsection
