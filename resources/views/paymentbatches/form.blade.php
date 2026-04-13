@extends('layouts.header') @section('content')
<span class="ui_close_btn"></span>


<style type="text/css">

@media  only screen and (max-width: 1499px) {

.showspan i {
    color: #6c87b8;
    font-size: 9px;
    padding: 3px;
    float: left;
    border: 1px solid;
    cursor: pointer;
    margin: 6px 1px;
    line-height: 11px;
}

}

@media  only screen and (min-width: 1500px) { 
.bulk_line_no {width: 55px !important;}
.bulk_po_invoice_id {width: 140px !important;}
.bulk_invoice_amount {width: 140px !important;}
.bulk_invoice_due_date {width: 100px !important;}
.bulk_comments {width: 200px !important;}
}

.bulk_line_no {width: 50px;}
.bulk_po_invoice_id {width: 120px;}
.bulk_invoice_amount {width: 110px;}
.bulk_invoice_due_date {width: 103px;}
.bulk_comments {width: 200px;}
</style>




     <?php include('tools_menu.php'); ?>
    <h4 class="heads">
    <a role="button">Payment Batches </a><span class="ui_close_btn"><a href="{{ url('paymentbatches') }}" class="collapse-close pull-right btn-danger"></a></span>
    </h4>
     





                   
<form method="post" action="" id="paymentbatch_form" data-parsley-validate>
{{ csrf_field() }}
        
            <div class="card">
               
                <div class="card-body card-block">
                <!------------------------------- toggle content start ---------------------------->
               
               
                   
                        <div class="row">
                         <div class="col-md-4">
                             <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Payment Batch Number</label>
                                    <div class="col-md-6">
                                        <input type="text" id="payment_batch_number" name="payment_batch_number" class="form-control payment_batch_number" value="{{ $row->payment_batch_number }}" readonly/>
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5"><span style="color:red">*</span>Payment Batch Name</label>
                                    <div class="col-md-6">
                                        <input class="form-control payment_batches_hdr_id" id="payment_batches_hdr_id" name="payment_batches_hdr_id" size="16" type="hidden" value="{{ $row->payment_batches_hdr_id }}" readonly>
                                        <input type="text" id="payment_batch_name" name="payment_batch_name" class="form-control payment_batch_name" value="{{ $row->payment_batch_name }}" required />
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                </div>
                    <div class="form-group row">
                         <label for="inputIsValid" class="form-control-label col-md-5">Payment Batch Status</label>
                         <div class="col-md-6">
                             <select type="text" name="payment_batch_status" id="payment_batch_status" class="payment_batch_status" readonly>
                             <option value="">-- Please Select --</option>
                             <option <?php if($row->payment_batch_status=="DRAFT" ) echo "selected"; ?> value="DRAFT">DRAFT</option>
                             <option <?php if($row->payment_batch_status=="INITIATED" ) echo "selected"; ?> value="INITIATED">INITIATED</option>
                             </select>
                         </div>
                         <div class="col-md-2">
                         </div>
                     </div>
			</div>

<div class="col-md-4">
    <div class="form-group row">
        <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red">*</span>Supplier Name</label>
        <div class="col-md-6 supplier_div sel2">
            <select name='supplier_id' rows='5' id='supplier_id' class='form-control supplier_id select2' required>
                {!! $supplier_id !!}
            </select>
        </div>
        <div class="col-md-2 showinline">
            <span class="showspan"><i class="fa fa-search suppliersearch"></i> </span>
            <span class="showspan"><i class="fa fa-refresh jcr_supplier_id"></i></span>
        </div>
    </div>
     <div class="form-group row">
        <label for="inputIsValid" class="form-control-label col-md-4">Organization Name</label>
        <div class="col-md-6">
            <select name='organization_id' rows='5' class='form-control organization_id'>
                {!! $organization_id !!}
            </select>
        </div>
        <div class="col-md-2 showinline">
        </div>
    </div>
</div>

                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Supplier Site</label>
                                    <div class="col-md-6 sel2">
                                        <select name='suppliersite_id' rows='5' id='suppliersite_id' class='select2 suppliersite_id' required>
                                            {!! $suppliersite_id  !!}
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                    </div>
                                </div>

                                 <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Batch Date</label>
                                    <div class="col-md-6">
                                        <div class="input-group form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                            <input class="form-control datepicker batch_date" id="batch_date" name="batch_date" size="16" type="text" value="{{ $row->batch_date }}" readonly>
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-2 showline">
                                    </div>
                                </div>
                            </div>
                       </div>
                 

     <div class="row">
<div class="col-md-12">
<hr class="xlg">
</div>
</div>          
               
<!------------------------- clone row End-------------------------------->
<div class="row">
    <div class="col-md-12">

<a href="javascript:void(0);" class="add_row additem newitem" rel=".rcopy">
    <i class="fa fa-plus"></i> New Item</a>
<div id="preview-area" class="chandru">
    <table class="overflow-y preview payment_batch_table">
                <thead>
                    <tr>
                        <th>Line No</th>
                        <th>Invoice Number</th>
                        <th>Invoice Amount</th>
                        <th>Invoice Due Date</th>
                        <th >Comments</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="so_inq_lines_body1">
                    <?php if(count($linedata)>=1) { ?> @foreach($linedata as $key=>$value)
                    <tr class="rcopy clone">
                        <td>
                            <input type="hidden" name="bulk_payment_batches_line_id[]" class="form-control  bulk_payment_batches_line_id" value="{!!  $value->payment_batches_line_id  !!}">
                        </td>
                        <td>
                            <input type="text"  name="bulk_line_no[]" class="form-control  bulk_line_no" value="{!! ($key+1)  !!}" readonly="readonly" >
                        </td>
                        <td class="sel2">
                            <select name="bulk_po_invoice_id[]" id="bulk_po_invoice_id" class="bulk_po_invoice_id select2 parsley-validated" required="required" >{!! $value->po_invoice_id !!}</select>
                        </td>
                        <td>
                            <input type="text" name="bulk_invoice_amount[]" class="form-control  bulk_invoice_amount input_qty_width" value="{!! $value->invoice_amount !!}" required="required" readonly >
                        </td>
                        <td>
                            <div class="input-group m-b">
                                <input type="text" name="bulk_invoice_due_date[]" class="form-control datepicker bulk_invoice_due_date" value="{!! $value->invoice_due_date !!}" >
                            </div>
                        </td>
                        <td>
                            <textarea name="bulk_comments[]" class="form-control  bulk_comments" row="1" value="" >{!! $value->comments !!}</textarea>
                        </td>
                        <td>
                            <a class="remove remove0">
                                <i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i>
                            </a>
                            <input type="hidden" name="counter[]">
                        </td>
                    </tr>
                    @endforeach
                    <?php } if(count($linedata) < 1 ) { ?>
                    <tr class="rcopy clone">
                        <td>
                            <input type="hidden" name="bulk_payment_batches_line_id[]" class="form-control  bulk_payment_batches_line_id" value="">
                        </td>
                        <td>
                            <input type="text"  name="bulk_line_no[]" class="form-control  bulk_line_no" value="1" readonly="readonly" >
                        </td>
                        <td class="sel2">
                            <select name="bulk_po_invoice_id[]" id="bulk_po_invoice_id" class="bulk_po_invoice_id select2 parsley-validated" required="required" >{!! $po_invoice_id !!}</select>
                        </td>
                        <td>
                            <input type="text" name="bulk_invoice_amount[]" class="form-control  bulk_invoice_amount input_qty_width" value="" required="required" readonly >
                        </td>
                        <td>
                            <div class="input-group m-b">
                                <input type="text" name="bulk_invoice_due_date[]" class="form-control datepicker bulk_invoice_due_date" >
                            </div>
                        </td>
                        <td>
                            <textarea name="bulk_comments[]" class="form-control  bulk_comments" row="1" value="" ></textarea>
                        </td>
                        <td>
                            <a class="remove remove0">
                                <i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i>
                            </a>
                            <input type="hidden" name="counter[]">
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
<div class="col-md-12">
<hr class="xlg">
</div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="form-group text-center">
            <button type="button" class="btn applychanges saveform add" value="APPLYCHANGES">Apply Changes</button>
            <button type="button" class="btn draft saveform" value="DRAFT">Draft</button>
            <button type="button" class="btn save saveform" value="SAVENEW">Save and New</button>
            <button type="button" class="btn save saveform" value="SAVE">Save</button>
            <a href="{{ url('paymentbatches') }}" class='btn cancel'>Cancel</a>
        </div>
    </div>
</div>

<!-- karthigaa purpose supplier search jqgrid model-->
<div class="modal fade" id="supplierModal">
  <div class="modal-dialog" style="width:80%;">
    <div class="modal-content">
		<!--Moda Header-->
      <div class="modal-header">
		  <h4 class="modal-title"> Supplier Details </h4>
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
	  </div>
		<!-- Modal Body -->
	  <div class="modal-body">
	      <table id="suppliergrid"></table>
	  </div>
		 <!-- Modal footer -->
      <div class="modal-footer">
      </div>

    </div>
  </div>
</div>
<!--end-->



<input type="hidden" class="pdtindex" value="" />
</div>
</div>


</form>


<script>
    $(document).ready(function() {
$('.payment_batch_status').css("pointer-events","none");
 /* Karthigaa code for lower to uppercase */
         $('.payment_batch_name').keyup(function(){
            this.value = this.value.toUpperCase();
         });
         /* end */

        var data = "{{\Session::get('j_date_format')}}";
        $(".add_row").relCopy(data);

        $('.add_row').click(function() {
            changeclassfields();
        });

 changeclassfields();
       


       $(document).on('click','.jcr_supplier_id',function()
            {
            $(".supplier_id").jCombo("{{ URL::to('jcomboform?table=m_supplier_t:supplier_id:supplier_name') }}&order_by=supplier_name asc",
            {selected_value:""});
            });

//            var supplier = $('#supplier_id').val();
//            var pdt_condition ='supplier_id='+supplier;
//	$(".bulk_po_invoice_id").jCombo("{{ URL::to('jcomboforminv?table=p_po_invoice_hdr_t:po_invoice_id:bill_number') }}&order_by=bill_number asc"+'&parent='+pdt_condition,
//	{selected_value:""});

        var index = $('.clone').closest('tr').index();
        changeclassfields();

//Karthigaa purpose to get invoice number based on Supplier
$('#supplier_id').on('change',function()
{
	var supplier = $('#supplier_id').val();
	if(supplier!='')
	{
	var condition =' supplier_id='+supplier;
	$(".suppliersite_id").jCombo("{{ URL::to('jcomboform?table=m_supplier_sites_t:supplier_site_id:supplier_site_name') }}&order_by=supplier_site_name asc"+'&parent='+condition,
	{selected_value:""});

	var pdt_condition ='supplier_id='+supplier;
	$(".bulk_po_invoice_id").jCombo("{{ URL::to('jcomboform?table=p_po_invoice_hdr_t:po_invoice_id:bill_number') }}&order_by=bill_number asc"+'&parent='+pdt_condition,
	{selected_value:""});

	}
});
/*****************************Karthigaa purpose to get Invoice Amount **********************/
$(document).on('change','.bulk_po_invoice_id',function(){
var supplier = $('#supplier_id').val();
var index = $(this).closest('tr').index();
if(supplier!=''){
var inv_id = $('.bulk_po_invoice_id'+index+' option:selected').val();
var pdtcount = poinvcheck(inv_id,index);
        if(pdtcount <= 0){
		var url = "{{ URL::to('getpoinvamount') }}/"+inv_id;
               $.get(url,function(data){
                    console.log(data);
                      $('.bulk_invoice_amount'+index).val(data);

		});
	}
        else{
            var msg 	 = $(".bulk_po_invoice_id" + index + ' option:selected').text();
            var message  = '<span style="color:#fdff65">'+msg+'</span>'+' Invoice Already Selected';
            notyMsgs('info',message);
            rowdataEmpty(index);
            }
}
else
{
notyMsgs('info','Please Select a Supplier ');
$(".bulk_po_invoice_id" + index).val('').select2();
}
});
/****      Karthigaa purpose remove function    ***/
        $(document).on('click', '.remove', function() {
            var index = $(this).closest('tr').index();
            var rowCount = $('.payment_batch_table tbody tr').length;
            if (rowCount > 1) {
                $($(this).closest("tr")).remove();
                removeclassfields();
            } else {
                notyMsg('error',"You Can't Delete Atleast One row should be there");
            }
        });
   /*End*/


/*Karthigaa Purpose for Supplier Search*/
 $('.suppliersearch').click(function(){
	 $('#supplierModal').modal('show');
	 $('#supplierModal').width("100%");
	});

var supnameopt="{{ $supnameopt }}";
var suptypeopt="{{ $suptypeopt }}";
var country="{{ $country }}";
var state="{{ $state }}";
var city="{{ $city }}";

	var mygrid = $("#suppliergrid"),
    pagerSelector = "#pager",
    myAddButton = function(options) {
        mygrid.jqGrid('navButtonAdd',pagerSelector,options);
        mygrid.jqGrid('navButtonAdd','#'+mygrid[0].id+"_toppager",options);
    };
          mygrid.jqGrid({
          url: "{{ URL::to('getSuppliergridData') }}",
            datatype: "json",
            mtype: "GET",
			height: 320,
			width: 1000,
             colModel: [
			{ name: "supplierid", label: "id",hidden:true, width:55},
			{ name: "supplier_site_id", label: "id",hidden:true, width:55},
			{ name: "supplier_number", label: "Supplier Number", width:55},
		 	{ name: "supplier_name", label: "Supplier Name",stype:'select', editoptions:{value:supnameopt}, width:55},
                        { name: "supplier_type_id", label: "Supplier Type",stype:'select', editoptions:{value:suptypeopt}, width:55},
		 	{ name: "supplier_site_name", label: "Supplier Site Name", width:55},
                        { name: "address", label: "Address", width:55},
                        { name: "country", label: "Country", width:55,stype:'select', editoptions:{value:country}},
                        { name: "state", label: "State", width:55,stype:'select', editoptions:{value:state}},
                        { name: "city", label: "City",stype:'select', editoptions:{value:city}, width:55},
                        { name: "site_type", label: "Supplier Site Type", width:55},
                    ],

                        iconSet: "fontAwesome",
                        rowNum: 10,
                        rowList: [10,20,100,1000],
                        sortorder: "asc",
                        viewrecords: true,
                        gridview: true,
                        rownumbers:true,
                        caption: "Supplier",
                        pager: pagerSelector,
                        toppager:true,
                        searching: {
                        defaultSearch: "cn"
                        }
		   });
jQuery(mygrid).jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
jQuery('#gs_suppliergrid_supplier_name,#gs_suppliergrid_supplier_type_id,#gs_suppliergrid_country,#gs_suppliergrid_state,#gs_suppliergrid_city').select2();
mygrid.jqGrid('navGrid',pagerSelector,
{cloneToTop:true,edit:false,add:false,del:false,search:true});
myAddButton ({
caption:"Select Supplier",
title:"Supplier",
buttonicon :'ui-icon-plus',
		onClickButton:function()
		{
		var gr = $(mygrid).jqGrid('getGridParam','selrow');
		var supplier = $(mygrid).jqGrid ('getCell', gr, 'supplierid');
		var address = $(mygrid).jqGrid ('getCell', gr, 'address');
		if( supplier != false )
		{
		$('.supplier_id').val(supplier).change();
		$('#supplierModal').modal('hide');
		}
		else
		{
		notyMsg('error','Please Select a row');
		}
		}
});

		$(document).on('click', '.saveform', function() {
            var btnval = $(this).val();
            if(btnval == 'APPLYCHANGES'){
			$("#payment_batch_status").val('DRAFT');
		}
            else if(btnval == 'DRAFT'){
			$("#payment_batch_status").val('DRAFT');
	    }else{
			$("#payment_batch_status").val('INITIATED');
		}
            $('#savestatus').val(btnval);
            var url = "{{ url('paymentbatchessave') }}";
            var red_url = "{{ url('paymentbatches') }}";
            var create_url = "{{ url('paymentbatchescreate') }}";

            validationrule('paymentbatch_form');
            var form = $('#paymentbatch_form');
            if (btnval != 'APPLYCHANGES') {
                //   form.parsley().validate();
                var form = $('#paymentbatch_form');
                form.parsley().validate();

                if (form.parsley().isValid()) {
                    change_date();
                    var formdata = $('#paymentbatch_form').serialize();
                    $.post(url, formdata, function(data) {
                        var status = data.status;
                        var msg = '<span style="color:#090065"></span>  ' + data.message;
                        var id = data.id;
                        var edit_url = "{{ url('paymentbatchescreate') }}/" + id;
                        if (btnval != 'SAVE' && btnval != 'DRAFT') {
                            notyMsg(status, msg);
                            setTimeout(function() {
                                window.location.href = create_url;
                            }, 1500);
                        } else {
                            notyMsg(status, msg);
                            setTimeout(function() {
                                window.location.href = red_url;
                            }, 1500);
                        }
                    });
                }
            } else {
                change_date();
                var formdata = $('#paymentbatch_form').serialize();
                $.post(url, formdata, function(data) {

                    var status = data.status;
                    var msg = data.message;
                    var id = data.id;
                    var edit_url = "{{ url('paymentbatchescreate') }}/" + id;
                    notyMsg(status, msg);
                    setTimeout(function() {
                        window.location.href = edit_url;
                    }, 1500);

                });
            }
        });



    });

    function changeclassfields() {
        changeClassName('bulk_payment_batches_line_id');
        changeClassName('bulk_line_no');
        changeClassName('bulk_po_invoice_id');
        changeClassName('bulk_invoice_amount');
        changeClassName('bulk_invoice_due_date');
        changeClassName('bulk_comments');
    }

    function removeclassfields() {
        removeClass('bulk_payment_batches_line_id');
        removeClass('bulk_line_no');
        removeClass('bulk_po_invoice_id');
        removeClass('bulk_invoice_amount');
        removeClass('bulk_invoice_due_date');
        removeClass('bulk_comments');
    }
    /************ Maruthu purpose to remove row action ********************/
    function removeClass(className)
	{
        var rowCount = $('.payment_batch_table tbody tr').length;
        for (var i = 0; i <= rowCount; i++) {
            $('.payment_batch_table tbody tr').find('.' + className).removeClass(className + i);
        }
        $('.' + className).each(function(index) {
            if (className == "bulk_line_no") {
                $(this).val(index + 1).attr("readonly", 1);
            }
            $(this).addClass(className + index);
        });
    }

    function changeClassName(className)
	{
        $('.' + className).each(function(index) {
            if (className == "bulk_line_no") {
                $(this).val(index + 1).attr("readonly", 1);
            }

            $(this).removeClass(className + '0');
            $(this).addClass(className + index);
        });
    }
</script>
<style>
    #table_scroll tbody {
        display: block;
        max-height: 300px;
        overflow: auto;
    }

    #table_scroll table thead tr {
        display: table;
    }
</style>
@include('layouts.php_js_validation') @endsection
