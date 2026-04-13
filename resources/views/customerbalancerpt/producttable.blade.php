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
<h2 class="heads">Product report</h2>
<div class="card">


<div class="card-body card-block">
  <form method="post" action="" id="job_card_reprot" class="org_form" data-parsley-validate enctype="multipart/form-data">
 
{{ csrf_field() }}
    <div class="col-md-offset-2 col-md-4">
        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4">From Date</label>
            <div class="col-md-6">
                            <div class="input-group form_date "      data-date=""   data-link-format="yyyy-mm-dd">
                                            <input class="form-control start_date  " id="start_date" name="start_date"  required type="text" value="" style="border-radius: 5px;" autocomplete="off">
                                             
                            </div>
            </div>
        </div>
    </div>
      
    <div class="col-md-4">
        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4">To Date</label>
            <div class="col-md-6">
                    <div class="input-group form_date "      data-date=""   data-link-format="yyyy-mm-dd">
                                    <input class="form-control end_date  " id="end_date" name="end_date"  required type="text" value="" style="border-radius: 5px;" autocomplete="off">
                                     <!-- <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> -->
                    </div>
            </div>
        </div>
    </div>

    <div class="col-md-offset-2 col-md-4">
        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4">Product Name</label>
            <div class="col-md-6">
                  <select id="product_id" name='product_id' rows='5'  class='form-control product_id select2' tabindex="1" data-show-subtext="true" data-live-search="true" required>
                    {!! $product_id !!}
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
   <div id="cusbalancegrid"></div>
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
            var product_id   =  ($('.product_id').val() != '') ? $('.product_id').val()  : ''; 
            
           if(start_date!=''&&end_date!=''&product_id!=''){
            var url="{{URL::to('getproductrmrpt1Data')}}/?start_date="+start_date+"&end_date="+end_date+"&product_id="+product_id;
             var url = url;
     obj.dataModel.url=url;
     $( "#cusbalancegrid" ).pqGrid( "option" , "dataModel.url",url );
    $("#cusbalancegrid").pqGrid("refreshDataAndView");
        }else{
             notyMsg("info","Please Choose Feilds");
         }
        
   });
    
       // var url="{{URL::to('getcustomerbalance')}}/?start_date="+start_date+"&end_date="+end_date+"&customer_id="+customer_id;
var url="";
              var colModel=[
        { dataIndx: "lot_no",align: "left",title: "Lot No" , minWidth:"15%",filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "product_rm",align: "left",title: "Product RM" ,minWidth:"15%",  filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "qty_rm", align: "right",title: "Qty" ,minWidth:"15%", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "batch_no", align: "right",title: "Batch No" ,minWidth:"15%", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "balance", align: "right",title: "FG Product" ,minWidth:"15%", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "qty_fg", align: "right",title: "QTY" ,minWidth:"15%", filter: { crules: [{condition: "begin" }] }},
        
  ];
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
            title: "Customer Balance Report",
            resizable: true,
            
            freezeCols: 2,
            toolbar: {
                items: [{
                    type: 'button',
                    label: "Export to Excel",
                    icon: 'ui-icon-arrowthickstop-1-s',
                     listener: function () {
                        var data=this;
                        var url=$.trim($.cookie('cusbalancegrid_pq_export_url')).slice(1,-1)+"&download=1";
                        $.get(url,function(s){
                        
                        var blob = data.exportData({
                                format: 'xlsx',                                
                                render: true,
                                type: 'blob',
                                data:s
                            });                        
                        saveAs(blob, "Customer Balance Report.xlsx" );
                        });
                    }
                },
                {
                    type: 'button',
                    icon: 'ui-icon-print',
                    label: 'Print',
                    listener: function () {
                        var exportHtml = this.exportData({ title: 'Customer Balance', format: 'htm', render: true }),
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
        $("#cusbalancegrid").pqGrid(obj);
$("#cusbalancegrid" ).pqGrid({ scrollModel:{autoFit: false }});
    $(document).on('click',".exportexcel",function() {
        $("#cusbalancegrid").jqGrid("exportToExcel",{
                                                includeLabels : true,
                                                includeGroupHeader : true,
                                                includeFooter: true,
                                                fileName : "Customer Balance.xlsx"

                                        })     
    });

       
    
      var data = "<?php echo \Session('j_date_format'); ?>";


}); 
     
function highchart(data)
{
    
}
    </script>
@include('layouts.php_js_validation')
@endsection




