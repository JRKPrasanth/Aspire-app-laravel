@extends('layouts.header') @section('content')
<span class="ui_close_btn"></span>
<!---------------------------------------------------------------------------->

<style type="text/css">


@media  only screen and (min-width: 1500px) {

.bulk_line_no {width: 50px !important;}
.bulk_invoice_hdr_id {width: 200px !important;}
.bulk_invoice_amount {width: 200px !important;}
.bulk_invoice_due_date {width: 100px !important;}
.bulk_comments {width: 200px !important;}

 }
 @media  only screen and (min-width: 2000px) {
.bulk_line_no {width: 50px !important;}
.bulk_invoice_hdr_id {width: 300px !important;}
.bulk_invoice_amount {width: 300px !important;}
.bulk_invoice_due_date {width: 200px !important;}
.bulk_comments {width: 300px !important;}
 }

.bulk_line_no {width: 50px;}
.bulk_invoice_hdr_id {width: 110px;}
.bulk_invoice_amount {width: 100px;}
.bulk_invoice_due_date {width: 100px;}
.bulk_comments {width: 200px;}

.modal-body{
    height: 300px;
    overflow-y:auto;
}

</style>


                           <?php include('tools_menu.php'); ?>     <h4 class="heads">
                                    <a role="button">
                                       
                                        Receipt Batches
                                    </a><span class="ui_close_btn">
   <a href="{{ url('receiptbatches') }}" class="collapse-close pull-right btn-danger"></a>
   </span>
                                </h4>
                            



<form method="post" action="" id="receiptbatch" data-parsley-validate>
<input type="hidden" value="" name="savestatus" id="savestatus" /> {{ csrf_field() }}

            <div class="card">
             <div class="card-header">

                </div>
                <div class="card-body card-block">
                <!------------------------------- toggle content start ---------------------------->
                <div class="row">
                <div class="col-md-12">
                
                
                   
                        <div class="row">
                            <div class="col-md-4">

                            <div class="form-group row">
                                <label for="inputIsValid" class="form-control-label col-md-4">Receipt Batch Name</label>
                                <div class="col-md-6">
                                    <input class="form-control s_receipt_batch_hdr_id" id="s_receipt_batch_hdr_id" name="s_receipt_batch_hdr_id" size="16" type="hidden" value="{{ $row->s_receipt_batch_hdr_id }}" readonly>
                                    <input type="text" id="receipt_batch_name" name="receipt_batch_name" class="form-control receipt_batch_name" value="{{ $row->receipt_batch_name }}" required />
                                </div>
                                <div class="col-md-2">
                                </div>
                            </div>

                            <div class="form-group row">
                            <label for="inputIsValid" class="form-control-label col-md-4">Batch Status</label>
                            <div class="col-md-6">
                              <select type="text" name="receipt_batch_status" id="receipt_batch_status" class="receipt_batch_status readonly">
                              <option value="">-- Please Select --</option>
                              <option <?php if($row->receipt_batch_status=="DRAFT" ) echo "selected"; ?> value="DRAFT">DRAFT</option>
                              <option <?php if($row->receipt_batch_status=="INITIATED" ) echo "selected"; ?> value="INITIATED">INITIATED</option>
                              </select>
                            </div>
                            <div class="col-md-2">
                            </div>
                            </div>

                    </div>


                            <div class="col-md-4">
                                 <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red">*</span>Customer</label>
                                    <div class="col-md-6">
                                        <select type="text" name="customerid" id="customerid" class="select2 customerid" required>
                                            {!! $customerid !!}
                                        </select>
                                    </div>
                                    <div class="col-md-2 showinline">
                                        <span class="showspan"><i class="fa fa-search customersearch"></i> </span>
                                        <span class="showspan"><i class="fa fa-refresh jcr_customer_id"></i></span>
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
                                    <label for="inputIsValid" class="form-control-label col-md-4">Customer site</label>
                                    <div class="col-md-6">
                                        <select name='customer_siteid' rows='5' class='select2 customer_siteid' id="customer_siteid" data-show-subtext="true" data-live-search="true">
                                            {!! $customer_siteid !!}
                                        </select>
                                    </div>
                                    <div class="col-md-2 showline">
                                    </div>
          </div>
        <div class="form-group row">
           <label for="inputIsValid" class="form-control-label col-md-4">Batch Date</label>
           <div class="col-md-6">
               <div class="input-group form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                   <input class="form-control datepicker batch_date" id="batch_date" name="batch_date" size="16" type="text" value="{{ $row->batch_date }}" readonly>
                   <!-- <span class="input-group-addon">
                       <span class="glyphicon glyphicon-calendar"></span>
                   </span> -->
               </div>
           </div>
           <div class="col-md-2 showline">
           </div>
       </div>
    </div>
                     </div>
                    

                    <div>

                    </div>
                
                </div>
            </div>

<!------------------------- clone row End-------------------------------->
<div class="row">
    <div class="col-md-12">

<a href="javascript:void(0);" class="add_row additem newitem" rel=".rcopy">
    <i class="fa fa-plus"></i> New Item</a>
    <div id="preview-area" class="chandru">
    <table class="overflow-y preview receipt_batch_table">
                <thead class="thead">
                    <tr>
                        <th>Line No</th>
                        <th>Invoice Number</th>
                        <th>Invoice Amount</th>
                        <th>Invoice Due Date</th>
                        <th>Comments</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="so_inq_lines_body1">
                    <?php if(count($linedata)>=1) { ?> @foreach($linedata as $key=>$value)
                    <tr class="rcopy clone">
                        <td>
                            <input type="hidden" name="bulk_s_receipt_batch_lines_id[]" class="form-control  bulk_s_receipt_batch_lines_id" value="{!!  $value->s_receipt_batch_lines_id  !!}">
                        </td>
                        <td>
                            <input type="text"  name="bulk_line_no[]" class="form-control  bulk_line_no" value="{!! ($key+1)  !!}" readonly="readonly" >
                        </td>
                        <td class="pdtdiv">
                            <select name="bulk_invoice_hdr_id[]" id="bulk_invoice_hdr_id" class="bulk_invoice_hdr_id select2 parsley-validated" required="required">{!! $value->invoice_hdr_id !!}</select>
                        </td>
                        <td class="pdtdes_div">
                            <input type="text" name="bulk_invoice_amount[]" class="form-control  bulk_invoice_amount input_qty_width" value="{!! $value->invoice_amount !!}" required="required" readonly>
                        </td>
                        <td>
                            <div class="input-group m-b">
                                <input type="text" name="bulk_invoice_due_date[]" class="form-control datepicker bulk_invoice_due_date" value="{!! $value->invoice_due_date !!}" />
                            </div>
                        </td>
                        <td>
                            <textarea name="bulk_comments[]" class="form-control  bulk_comments" row="1" value="">{!! $value->comments !!}</textarea>
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
                            <input type="hidden" name="bulk_s_receipt_batch_lines_id[]" class="form-control  bulk_s_receipt_batch_lines_id" value="">
                        </td>
                        <td>
                            <input type="text"  name="bulk_line_no[]" class="form-control  bulk_line_no" value="1" readonly="readonly" >
                        </td>
                        <td class="pdtdiv">
                            <select name="bulk_invoice_hdr_id[]" id="bulk_invoice_hdr_id" class="bulk_invoice_hdr_id select2 parsley-validated" required="required">{!! $invoice_hdr_id !!}</select>
                        </td>
                        <td class="pdtdes_div">
                            <input type="text" name="bulk_invoice_amount[]" class="form-control  bulk_invoice_amount input_qty_width" value="" required="required" readonly>
                        </td>
                        <td>
                            <div class="input-group m-b">
                                <input type="text" name="bulk_invoice_due_date[]" class="form-control datepicker bulk_invoice_due_date" />
                            </div>
                        </td>
                        <td>
                            <textarea name="bulk_comments[]" class="form-control  bulk_comments" row="1" value=""></textarea>
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
    <div class="col-lg-12 col-md-12">
        <div class="form-group text-center">
            <button type="button" class="btn applychanges saveform" value="APPLYCHANGES">Apply Changes</button>
            <button type="button" class="btn draft saveform" value="DRAFT">Draft</button>
            <button type="button" class="btn save saveform" value="SAVENEW">Save and New</button>
            <button type="button" class="btn save saveform" value="SAVE">Save</button>
            <a href="{{ url('receiptbatches') }}" class='btn cancel'>Cancel</a>
        </div>
    </div>
</div>


<!-- Maruthu purpose customer search jqgrid model-->
<div class="modal fade" id="customerModal">
    <div class="modal-dialog" style="width:80%;">
        <div class="modal-content">
            <!--Moda Header-->
            <div class="modal-header">
                <h4 class="modal-title"> Customer Details </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
                <table id="customergrid"></table>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
            </div>

        </div>
    </div>
</div>
<!--end-->

<!-- Maruthu purpose customer search jqgrid model-->
<div class="modal fade" id="productModal">
    <div class="modal-dialog" style="width:80%;">
        <div class="modal-content">
            <!--Moda Header-->
            <div class="modal-header">
                <h4 class="modal-title"> Product Details </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
                <table id="productgrid"></table>
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
         /* Karthigaa code for lower to uppercase */
         $('.receipt_batch_name').keyup(function(){
            this.value = this.value.toUpperCase();
         });
         /* end */

        $('.receipt_batch_status').css("pointer-events","none");
        var data = "{{\Session::get('j_date_format')}}";
        $(".add_row").relCopy(data);

        $('.add_row').click(function() {
            changeclassfields();
        });
        changeclassfields();


        $(document).on('click', '.jcr_customer_id', function() {
            $(".customerid").jCombo("{{ URL::to('jcomboform?table=m_customers_t:customer_id:customer_name') }}&order_by=customer_name asc", {
                selected_value: ""
            });
        });


        var index = $('.clone').closest('tr').index();
        changeclassfields();

//Maruthu purpose to get invoice number based on Customer
$('#customerid').on('change',function()
{
	var customer = $('#customerid').val();
	if(customer!='')
	{
	var condition =' customer_id='+customer;
	$(".customer_siteid").jCombo("{{ URL::to('jcomboform?table=m_customer_sites_t:customer_site_id:customer_site_name') }}&order_by=customer_site_name asc"+'&parent='+condition,
	{selected_value:""});

	var pdt_condition =' ship_to_customer_id='+customer;
	$(".bulk_invoice_hdr_id").jCombo("{{ URL::to('jcomboform?table=s_invoice_hdr_t:invoice_hdr_id:invoice_number') }}&order_by=invoice_number asc"+'&parent='+pdt_condition,
	{selected_value:""});

	}
});
/*****************************Maruthu purpose to get Invoice Amount **********************/
$(document).on('change','.bulk_invoice_hdr_id_old',function()
{
var customer = $('#customerid').val();
var index = $(this).closest('tr').index();
if(customer!='')
{
var inv_id = $('.bulk_invoice_hdr_id'+index+' option:selected').val();
	if(inv_id !='')
	{
		//alert();
		var url = "{{ URL::to('getinvamount') }}/"+inv_id;
		$.get(url,function(data)
		{
         $('.bulk_invoice_amount'+index).val(data);
		});
	}


}
else
{
notyMsgs('info','Please Select Customer !!!');
}
});



		 $(document).on('change','.bulk_invoice_hdr_id',function(){
          var customer = $('#customerid').val();
			var index = ($(this).closest('tr').index());
			if(customer !='')
			{
				var inv_id = $('.bulk_invoice_hdr_id'+index+' option:selected').val();
			var pdtcount = invcheck(inv_id,index);
				if(pdtcount <= 0){

					//alert(pdtcount);
				  var url = "{{ URL::to('getinvamount') }}/"+inv_id;
				   $.get(url , function(data)
				   {

					 $('.bulk_invoice_amount'+index).val(data);
				   });
				}
				else
				{
					var msg 	 = $(".bulk_invoice_hdr_id" + index + ' option:selected').text();
					var message  = '<span style="color:#fdff65">'+msg+'</span>'+' Invoice Already Selected';
					notyMsgs('info',message);
					rowdataEmpty(index);
				}

			}
			 else
{
notyMsgs('info','Please Select Customer !!!');
}

        });


		function rowdataEmpty(index)
	{
	$(".bulk_invoice_hdr_id" + index).val('').change();
	$(".bulk_invoice_amount" + index).val('').change();

	}











/*****************************Maruthu purpose to get Invoice Amount End**********************/
        $(document).on('click', '.remove', function() {
            var index = $(this).closest('tr').index();
            var rowCount = $('.receipt_batch_table tbody tr').length;
            if (rowCount > 1) {
                $($(this).closest("tr")).remove();
                removeclassfields();
            } else {
                notyMsg('error',"You Can't Delete Atleast One row should be there");
            }
        });


        $('.customersearch').click(function() {
            $('#customerModal').modal('show');
            $('#customerModal').width("100%");
        });

          var cusnameopt = "{{ $cusnameopt }}";
        var custypeopt = "{{ $custypeopt }}";
        var stateopt = "{{ $stateopt }}";
        var cityopt = "{{ $cityopt }}";
        var countryopt = "{{ $countryopt }}";
        var mygrid = $("#customergrid"),
            pagerSelector = "#pager",
            myAddButton = function(options) {
                mygrid.jqGrid('navButtonAdd', pagerSelector, options);
                mygrid.jqGrid('navButtonAdd', '#' + mygrid[0].id + "_toppager", options);
            };
        mygrid.jqGrid({
            url: "{{ URL::to('getCustomergridData') }}",
            datatype: "json",
            mtype: "GET",
            height: 320,
            width: 1000,
            colModel: [{
                    name: "customer_id",
                    label: "id",
                    hidden: true,
                    width: 55
                },
                {
                    name: "customer_site_id",
                    label: "id",
                    hidden: true,
                    width: 55
                },
                {
                    name: "customer_number",
                    label: "Customer Number",
                    width: 55
                },
                {
                    name: "customer_name",
                    label: "Customer Name",
                    stype: 'select',
                    editoptions: {
                        value: cusnameopt
                    },
                    width: 55
                },
                {
                    name: "customer_type_id",
                    label: "Customer Type",
                    width: 55,
                    stype: 'select',
                    editoptions: {
                        value: custypeopt
                    }
                },
                {
                    name: "customer_site_name",
                    label: "Customer Site Name",
                    width: 55
                },
                {
                    name: "site_type",
                    label: "Customer Site Type",
                    width: 55
                },
                {
                    name: "address",
                    label: "Address",
                    width: 55
                },
                {
                    name: "city",
                    label: "City",
                    width: 55,
                    stype: 'select',
                    editoptions: {
                        value: cityopt
                    }
                },
                {
                    name: "state",
                    label: "State",
                    width: 55,
                    stype: 'select',
                    editoptions: {
                        value: stateopt
                    }
                },
                {
                    name: "country",
                    label: "Country",
                    width: 55,
                    stype: 'select',
                    editoptions: {
                        value: countryopt
                    }
                }
            ],

            iconSet: "fontAwesome",
            rowNum: 10,
            rowList: [10, 20,50,100,1000],
            sortorder: "asc",
            viewrecords: true,
            gridview: true,
            rownumbers: true,
            caption: "Customer",
            pager: pagerSelector,
            toppager: true,
            searching: {
                defaultSearch: "cn"
            }
        });
        jQuery(mygrid).jqGrid('filterToolbar', {
            stringResult: true,
            searchOnEnter: false
        });
        jQuery('#gs_customergrid_customer_name').select2();
        jQuery('#gs_customergrid_city').select2();
        jQuery('#gs_customergrid_state').select2();
        jQuery('#gs_customergrid_country').select2();
        mygrid.jqGrid('navGrid', pagerSelector, {
            cloneToTop: true,
            edit: false,
            add: false,
            del: false,
            search: true
        });
        myAddButton({
            caption: "Select Customer",
            title: "Customer",
            buttonicon: 'ui-icon-plus',
            onClickButton: function() {
                var gr = jQuery(mygrid).jqGrid('getGridParam', 'selrow');
                var customer = jQuery(mygrid).jqGrid('getCell', gr, 'customer_id');
                var address = jQuery(mygrid).jqGrid('getCell', gr, 'address');
                if (customer != false) {
                    $('.customer_id').select2('val', customer);
                    $('#customerModal').modal('hide');
                } else {
                    alert('Please Select one row');
                }
            }
        });


$(document).on('click', '.saveform', function() {
            var btnval = $(this).val();
            if (btnval == 'APPLYCHANGES')
                var savestatus = 'APPLY CHANGES';
            else if (btnval == 'DRAFT')
                var savestatus = 'DRAFT';
            else if (btnval == 'SAVE' || btnval == 'SAVENEW')
                var savestatus = 'SAVE';

			if (btnval == 'APPLYCHANGES')
                $("#receipt_batch_status").val('DRAFT');
            else if (btnval == 'DRAFT')
                $("#receipt_batch_status").val('DRAFT');
            else
                $("#receipt_batch_status").val('INITIATED');

            $('#savestatus').val(savestatus);
            var url = "{{ url('batchsave') }}";
            var red_url = "{{ url('receiptbatches') }}";
            var create_url = "{{ url('receiptbatchcreate') }}/0";

            validationrule('receiptbatch');
            var form = $('#receiptbatch');
            if (btnval != 'APPLYCHANGES') {
                //   form.parsley().validate();
                var form = $('#receiptbatch');
                form.parsley().validate();

                if (form.parsley().isValid()) {
                    change_date();
                    var formdata = $('#receiptbatch').serialize();
                    $.post(url, formdata, function(data) {
                        var status = data.status;
                        var msg = '<span style="color:#090065"> </span>  ' + data.message;
                        var id = data.id;
                        var edit_url = "{{ url('receiptbatchcreate') }}/" + id;
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
                var formdata = $('#receiptbatch').serialize();
                $.post(url, formdata, function(data) {

                    var status = data.status;
                    var msg = data.message;
                    var id = data.id;
                    var edit_url = "{{ url('receiptbatchcreate') }}/" + id;
                    notyMsg(status, msg);
                    setTimeout(function() {
                        window.location.href = edit_url;
                    }, 1500);

                });
            }
        });

    });

    function changeclassfields() {
        changeClassName('bulk_s_receipt_batch_lines_id');
        changeClassName('bulk_line_no');
        changeClassName('bulk_invoice_hdr_id');
        changeClassName('bulk_invoice_amount');
        changeClassName('bulk_invoice_due_date');
        changeClassName('bulk_comments');
    }

    function removeclassfields() {
        removeClass('bulk_s_receipt_batch_lines_id');
        removeClass('bulk_line_no');
        removeClass('bulk_invoice_hdr_id');
        removeClass('bulk_invoice_amount');
        removeClass('bulk_invoice_due_date');
        removeClass('bulk_comments');
    }
    /************ Maruthu purpose to remove row action ********************/
    function removeClass(className)
	{
        var rowCount = $('.receipt_batch_table tbody tr').length;
        for (var i = 0; i <= rowCount; i++) {
            $('.receipt_batch_table tbody tr').find('.' + className).removeClass(className + i);
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

@include('layouts.php_js_validation') @endsection
