<style>
.payable_amount{
    display: inline-block;
    min-width: 110px;
    margin: 15px 5px;
    height: 35px;
    line-height: 23px;
    box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
    cursor: default;
    border-radius: 4px;
    border: 1px solid #81cdee;
    text-transform: uppercase !important;
}
</style>



<div class="row">
    <div class="col-md-12"style=" background-color: white;border:1" > 
  <div class="col-md-12" >
 <a id="buttonclick"><button type="button" class="btn add paymentinv buttonclick">New  Payment</button></a>
TOTAL PAYABLE: <a class="btn payable_amount">INR. {{$balance_amount_sum[0]->balance_amount_sum}}</a> 
</div>
</div>	 
</div>

<div id="paymentsgridid"></div>




<input type="hidden" class="poinvoiceid" value="">

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
 var colModel=[
	{ dataIndx: "po_invoice_id", align: "center",title: "Id" ,hidden:true},
	{ dataIndx: "bill_number",align: "center",title: "Invoice Number" , filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "invoice_date", align: "center",title: "Invoice Date" ,dataType: "date",minWidth:"19%", 
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
        { dataIndx: "po_number",align: "center",title: "PO Number" ,minWidth:"14%",filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "supplier_id", align: "center",title: "Supplier Name",minWidth:"14%", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "invoice_grand_total", align: "center",title: "Invoice Amount",minWidth:"14%", filter: { crules: [{condition: "begin" }] } },
        { dataIndx: "paid_amount",align: "center",title: "Paid Amount",minWidth:"14%", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "balance_amount", title: "Balance Amount",minWidth:"14%", filter: { crules: [{condition: "begin" }] }},
        { dataIndx: "paid_status", title: "Payment Status",minWidth:"14%", filter: { crules: [{condition: "begin" }] } }
	];
       
	  //define dataModel
        var dataModel = {
            location: "remote",            
            dataType: "JSON",
            method: "GET",
            url: "{{URL::to('getpaymentpendingreportData')}}",
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
            title: "Pending Purchase Invoice",
            resizable: true,
            hwrap:false,
            freezeCols: 2,
			  toolbar: {
                items: [
                {
                    type: 'button',
                    label: "Export to Excel",
                    icon: 'ui-icon-arrowthickstop-1-s',
                    listener: function () {
                        var data=this;
                        var url=$.trim($.cookie('paymentsgridid_pq_export_url')).slice(1,-1)+"&download=1";
                        $.get(url,function(s){
                        
                        var blob = data.exportData({
                                format: 'xlsx',                                
                                render: true,
                                type: 'blob',
                                data:s
                            });                        
                        saveAs(blob, "pendingpurchase_aging.xlsx" );
                        });
                    }
                },
                 {
                    type: 'button',
                    icon: 'ui-icon-print',
                    label: 'Print',
                    listener: function () {
                        var exportHtml = this.exportData({ title: 'Pending Purchase Invoice', format: 'htm', render: true }),
                            newWin = window.open('', '', 'width=1200, height=700'),
                            doc = newWin.document.open();
                        doc.write(exportHtml);
                        doc.close();
                        newWin.print();
                    }
                }]
            },
			   formulas: [
                ["[paid_status", function( rd ){
                    var attr = rd.pq_cellattr = rd.pq_cellattr || {};
                    if(rd.paid_status =="UnPaid"){                        
                        attr.rank = attr.paid_status = { style: 'color: #fb0303;font-weight:550;'}                        
                    }
                    else{
                        attr.rank = attr.paid_status = { style: 'color: #037f03; font-weight:550;'}
                    }
                    return rd.paid_status;
                }]
            ],
			 rowSelect: function (evt, ui) {
                
                var str = JSON.stringify(ui, function(key, value){                    
                    if( key.indexOf("pq_") !== 0){
                        return value;
                    }
                }, 2)
                var val=$.parseJSON(str);
				var poinvid=val['addList'][0]['rowData'].po_invoice_id;
				//var postatus=val['addList'][0]['rowData'].po_status;
				 $('.poinvoiceid').val(poinvid);
				// $('.postatusval').val(postatus);
            }
        };
        $("#paymentsgridid").pqGrid(obj);
$( "#paymentsgridid" ).pqGrid({ scrollModel:{autoFit: false }});
	$(".buttonclick").click(function(){
		
		var poinvoiceid=$('.poinvoiceid').val();

	if( poinvoiceid!="" )
	{
		 window.location.replace('paymentforinvoicecreate/'+poinvoiceid+'/'+'0'+'/'+'0');
		
	}
	else
	{
			 notyMsg("info","Please Select Row");
	}
	});
	
	

/*Karthigaa Purpose For Report*/
$("#payablereport").click(function(){
    var gr=$('#paymentsgridid').jqGrid('getGridParam','selrow');
  var cellValue = $("#paymentsgridid").jqGrid ('getCell', gr, 'payment_id');
  if(cellValue != false){
     window.location.replace('payablereport/' +cellValue);
  }
  else
  {
  notyMsg("info","Please Select Row");
  }  
});


 
$('#view').click(function(){
  var gr=$('#paymentsgridid').jqGrid('getGridParam','selrow');
  var cellValue = $("#paymentsgridid").jqGrid ('getCell', gr, 'payment_id');
  if(cellValue != false){
     var url="paymentsview";
     var viewurl = url+'/'+cellValue+'/view';
     window.location.replace('paymentsview/' +cellValue);
  }
  else
  {
  notyMsg("info","Please Select Row");
  }
});

/*Karthigaa purpose:clear search the jqgrid*/
	$(".clearsearch").click(function(){
            var grid = $("#paymentsgridid");
            grid.jqGrid('setGridParam',{search:false});

            var postData = grid.jqGrid('getGridParam','postData');
            $.extend(postData,{filters:""});
            grid.trigger("reloadGrid",[{page:1}]);
    });
/*end*/


});
  </script>


