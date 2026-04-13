@extends('layouts.header')
@section('content')
<span class="ui_close_btn"></span>


<div class="row">


<style type="text/css">
@media only screen and (min-width: 1500px) {
.bulk_line_no {width: 42px;}
.bulk_product_id {width: 100px;}
.bulk_uom_code_id {width: 100px;}
.bulk_return_qty {width: 100px;}
.bulk_unit_price {width: 100px;}
.bulk_tax_group_id {width: 100px;}
.bulk_tax_amount {width: 100px;}
.bulk_line_total {width: 100px;}
.bulk_comments {width: 100px;}
}
.bulk_line_no {width: 42px;}
.bulk_product_id {width: 290px;}
.bulk_uom_code_id {width: 75px;}
.bulk_return_qty {width: 100px;}
.bulk_unit_price {width: 100px;}
.bulk_tax_group_id {width: 100px;}
.bulk_tax_amount {width: 100px;}
.bulk_line_total {width: 100px;}
.bulk_comments {width: 100px;}
</style>

<h2 class="heads">CREDIT NOTE <span class="ui_close_btn"><a class="collapse-close pull-right btn-danger" onclick="location.href = '{{url('accountcreditnote')}}'"></a></span></h2>





<form method="post"  id="credit_form" data-parsley-validate>
{{ csrf_field() }}

<div class="card">
<div class="card-header">

</div>
<div class="card-body card-block">
	<div class="col-md-4">
            <input class="form-control " name="invoice_number" size="16" type="hidden" value="{{ $row[0]->invoice_number }}">
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Credit Number</label>
			<div class="col-md-6">
				<input type="text" id="credit_number" name="credit_number"  class="form-control" value="{{$row[0]->credit_number}}"  >
			</div>
			<div class="col-md-2">
			</div>
		</div>
            <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">So RMA Number</label>
			<div class="col-md-6">
                            <select name='so_rma_hdr_id' rows='5' class='form-control so_rma_hdr_id' data-show-subtext="true" data-live-search="true"  required readonly>
				{!! $so_rma_hdr_id  !!}
				</select>
				
			</div>
			<div class="col-md-2">
			</div>
		</div>
            
                 <div class="form-group row">
                  <label for="inputIsValid" class="form-control-label col-md-4"><span style="font-size:20px;color:red;">*</span> Credit Date</label>
			<div class="col-md-6">
			<div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
			<input class="form-control credit_date" id="credit_date" name="credit_date" size="16" type="text" value="{{ $row[0]->credit_date }}" required >
			<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
			</div>
			<input type="hidden" id="credit_date" value="{{ $row[0]->credit_date }}" />
			</div>
			<div class="col-md-1 showline">
			</div>
		</div>

                <div class="form-group row" >
        <label for="inputIsValid" class="form-control-label col-md-4">Credit Status</label>
        <div class="col-md-6">
            <select type="text" name="credit_status" id="credit_status" class="form-control credit_status" readonly>
                <option value="">--Please Select--</option>
                <option <?php if($row[0]->credit_status =="DRAFT") { echo "selected"; } else { echo ""; } ?> value="DRAFT">DRAFT</option>
                <option <?php if($row[0]->credit_status =="INITIATED") { echo "selected"; } else { echo ""; } ?> value="INITIATED">INITIATED</option>
            </select>
        </div>
    </div>
		 
	</div>
	<div class="col-md-4">
            <input class="form-control creditnote_hdr_id" id="creditnote_hdr_id" name="creditnote_hdr_id" size="16" type="hidden" value="{{ $row[0]->creditnote_hdr_id }}" readonly>
	
            <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Invoice Number</label>
			<div class="col-md-6">

				<input type="text" id="invoice_number" name="invoice_number"  class="form-control invoice_number" value="{{ $row[0]->invoice_number   }}" required>
			</div>
			<div class="col-md-2">
			</div>
		</div>
            <div class="form-group row">
                  <label for="inputIsValid" class="form-control-label col-md-4">Invoice Date</label>
			<div class="col-md-6">
			<div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
			<input class="form-control invoice_date" id="invoice_date" name="invoice_date" size="16" type="text" value="{{ $row[0]->invoice_date }}" readonly >
			<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
			</div>
			<input type="hidden" id="invoice_date" value="{{ $row[0]->invoice_date }}" />
			</div>
			<div class="col-md-1 showline">
			</div>
		</div>
 	</div>
		<div class="col-md-4">
     <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Customer Name</label>
			<div class="col-md-6">
				<select name='customerid' rows='5' class='form-control customerid' data-show-subtext="true" data-live-search="true"  required >
				{!! $customerid  !!}
				</select>
			</div>
			<div class="col-md-2 showinline">

			</div>
    </div>
                    <div class="form-group row">
                                <label for="inputIsValid" class="form-control-label col-md-4">Ship To Address</label>
                                <div class="form-group hidethis" style="display:none;">
                                    <input type="text" name="ship_to_address_id" id="ship_to_address_id"  value="{{ $ship_to_address_id }}" class="form-control ship_to_address_id">
                                </div>
                                <div class="col-md-6">
                                    <textarea name='shipping_to_address_txt' rows='2' id='shipping_to_address_txt' class='form-control shipping_to_address_txt' style='height:100px;' readonly>{{$ship_to_address}}</textarea>
                                    <div class="changeaddress_div">
                                        <input type="hidden" id="custype" value="" class="form-control custype">
                                        <br>
<!--                                        <button type="button" class="shipto btn-success btn-xs changeaddress" value="shipto">
                                            <i class="fa fa-address-book" aria-hidden="true"></i> Change Address</button>-->
                                    </div>
                                </div>
                            </div>
    
	</div>
</div>



<!-------------------------Linedata -------------------------------->

<div class="row">
<div class="col-md-12">

<div id="preview-area" class="chandru">
    <table class="overflow-y preview credit_table">

<thead>
<tr>
    <th>
        <th>Line No</th>
        <th>Product </th>
        <th>Uom Code</th>
        <th>Return Qty</th>
        <th>Price</th>
        <th>Tax Group</th>
        <th>Tax Amount</th>
        <th>Line Total</th>
        <th>Comments</th>
        <th>&nbsp;</th>
</tr>
</thead>
<tbody class="po_lines_body">
<?php if(count($linedata)>=1) { ?>
@foreach($linedata as $key=>$value)

	<?php if($value->return_qty==0)
       { $display = "display:none;"; } else {  $display = "display:block;"; }
	?>

<tr class="clone clonedInput">
    <td>
        <input type="hidden" name="bulk_creditnote_line_id[]" class="form-control input-sm bulk_creditnote_line_id" value="{{$value->creditnote_line_id}}">
    </td>
    <td>
        <input type="hidden" name="bulk_creditnote_hdr_id[]" class="form-control input-sm bulk_creditnote_hdr_id" value="">
    </td>
    <td>
        <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="{{ $key + 1 }}" readonly="readonly">
    </td>
    <td class="pdtdiv">
        <select name="bulk_product_id[]" class="form-control bulk_product_id  parsley-validated" required="required">{!! $value->product_id !!}</select>
    </td>
    <td>
        <select name="bulk_uom_code_id[]" id="bulk_uom_code_id" class="form-control bulk_uom_code_id">
            {!! $value->uom_code_id !!}
        </select>
    </td>
    
    <td>
        <input type="text" name="bulk_return_qty[]" class="form-control input-sm bulk_return_qty input_qty_width" value="{{ $value->return_qty }}" required="required" readonly>
    </td>

        <td>
            <input type="text" name="bulk_unit_price[]" class="form-control input-sm bulk_unit_price input_qty_width" value="{{ $value->unit_price }}" required="required">
        </td>
        
        <td>
            <select name="bulk_tax_group_id[]" id="bulk_tax_group_id" class="form-control bulk_tax_group_id">
                {!! $value->tax_group_id !!}
            </select>
        </td>
        <td>
            <input type="text" name="bulk_tax_amount[]" class="form-control input-sm bulk_tax_amount input_qty_width" value="{{ $value->tax_amount }}" required="required">
        </td>
        <td>
            <input type="text" name="bulk_line_total[]" class="form-control input-sm bulk_line_total input_qty_width" value="{{ $value->line_total }}" required="required">
        </td>
        <td>
                    <input type="text" name="bulk_comments[]" class="form-control input-sm bulk_comments" value="{{ $value->comments }}">
                </td>
       
        <td>
            <a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
            <input type="hidden" name="counter[]">
        </td>
</tr>


@endforeach
<?php } ?>

</tbody>
</table>
<input type="hidden" name="enable-masterdetail" value="true">
</div>


</div>
</div>


<!-------------------------Linedata End-------------------------------->




<div class="row">
	<div class="col-lg-12 col-md-12">
		<div class="form-group text-center">
			<button type="button" class="btn applychanges saveform" value="APPLYCHANGES">Apply Changes</button>
			<button type="button" class="btn draft saveform" value="DRAFT">Draft</button>
			<button type="button" class="btn save saveform" value="SAVE">Save</button>
			  <a href="{{ url('accountcreditnote') }}" class='btn cancel'>Cancel</a>
		</div>
	</div>
</div>



</div>
<input type="hidden" class="pdtindex" value="" />
<div id="preloader">
       <img src="https://jrlma.ca/wp-content/plugins/gallery-by-supsystic/src/GridGallery/Galleries/assets/img/loading.gif">
    </div>


</form>

</div>




<script>
$('input').attr('readonly', true);
$('select').attr('readonly', true);
$('#credit_date,.bulk_comments').attr('readonly', false);

$(document).ready(function(){
    $('.bulk_product_id,.bulk_uom_code_id,.bulk_tax_group_id,.customerid,.invoice_date,.credit_status,.ship_to_address_id,.so_rma_hdr_id').css('pointer-events','none');
 $('#savestatus').val('');
    $(document).on('click','.saveform',function(){
            var btnval		= $(this).val();

            if(btnval == 'APPLYCHANGES')
                $("#credit_status").val('DRAFT');
            else if(btnval == 'DRAFT')
                $("#credit_status").val('DRAFT');
            else
                $("#credit_status").val('INITIATED');


            var url		= "{{ url('accountcreditnotesave') }}";
            var red_url		="{{ url('accountcreditnote') }}";
            var create_url	="{{ url('accountcreditnotecreate') }}/0";
            validationrule('credit_form');
            var formdata	= $('#credit_form').serialize();
            var form = $('#credit_form');

            if(btnval != 'APPLYCHANGES')
            {
                form.parsley().validate();
                var form = $('#credit_form');
                form.parsley().validate();

                if (form.parsley().isValid())
                {
                    $.post(url,formdata,function(data)
                    {
                        var status      = data.status;
                        var msg     = '<span style="color:#090065">'+data.auto_no+'</span>  '+data.message;
                        var id          = data.id;
                        var edit_url	= "{{ url('accountcreditnotecreate') }}/"+id;
                        if(btnval !='SAVE' && btnval !='DRAFT')
                        {

                            notyMsg(status,msg);
                            setTimeout(function(){
                            window.location.href=create_url;
                            }, 1500);
                        }
                        else
                        {
                            notyMsg(status,msg);
                            setTimeout(function(){
                            window.location.href=red_url;
                            }, 1500);
                        }
                    });
                }
            }
            else
            {
            $.post(url,formdata,function(data)
            {

                    var status = data.status;
                    var msg    = data.message;
                    var id     = data.id;
                    var edit_url	="{{ url('accountcreditnoteedit') }}/"+id;
                            notyMsg(status,msg);
                            setTimeout(function(){
                            window.location.href=edit_url;
                            }, 1500);

                });
            }
    });

$(document).on('click','.add_row',function()
{
	var cloned = $('.credit_table').find('tr:eq(1)').clone();
	cloned.find("input[type=text],input[type=hidden], textarea,input[type=date],select").val("");
	cloned.appendTo('.po_lines_body');

        changeClassName('bulk_creditnote_line_id');
        changeClassName('bulk_line_no');
        changeClassName('bulk_product_id');
        changeClassName('bulk_uom_code_id');
        changeClassName('bulk_return_qty');
        changeClassName('bulk_unit_price');
        changeClassName('bulk_line_subtotal');
        changeClassName('bulk_tax_group_id');
        changeClassName('bulk_tax_amount');
        changeClassName('bulk_line_total');
        changeClassName('bulk_comments');
});






$(document).on('click','.remove',function()
{
	var index = $(this).closest('tr').index();
	var rowCount = $('.credit_table tbody tr').length;
	if(rowCount > 1)
	{
		$($(this).closest("tr")).remove();
                removeClass('bulk_creditnote_line_id');
                removeClass('bulk_line_no');
                removeClass('bulk_product_id');
                removeClass('bulk_uom_code_id');
                removeClass('bulk_return_qty');
                removeClass('bulk_unit_price');
                removeClass('bulk_line_subtotal');
                removeClass('bulk_tax_group_id');
                removeClass('bulk_tax_amount');
                removeClass('bulk_line_total');
                removeClass('bulk_comments');
    	}
	else
	{
notyMsg("info","You Can't Delete Atleast One row should be there");
	}
});

$(document).on('click','.form',function()
{
var btn_val = $(this).val();
$('.submit_type').val(btn_val);
});


var index = $('.clone').closest('tr').index();
changeClassName('bulk_creditnote_line_id');
changeClassName('bulk_line_no');
changeClassName('bulk_product_id');
changeClassName('bulk_uom_code_id');
changeClassName('bulk_return_qty');
changeClassName('bulk_unit_price');
changeClassName('bulk_line_subtotal');
changeClassName('bulk_tax_group_id');
changeClassName('bulk_tax_amount');
changeClassName('bulk_line_total');
changeClassName('bulk_comments');

function changeClassName(className)
{
$('.' + className).each(function (index)
{
if (className == "bulk_line_no")
{
$(this).val(index + 1).attr("readonly", 1);
}

$(this).removeClass(className + '0');
$(this).addClass(className + index);
});
}

/************ karthigaa purpose to remove row action ********************/
function removeClass(className)
{
	var rowCount = $('.credit_table tbody tr').length;
	for(var i=0;i<=rowCount;i++)
	{
	$('.credit_table tbody tr').find('.'+className).removeClass(className+i);
	}
	$('.' + className).each(function (index)
	{
		if (className == "bulk_line_no")
		{
		$(this).val(index + 1).attr("readonly", 1);
		}
		$(this).addClass(className + index);
	});
}

$('.credit_date').datepicker({format: 'yyyy-mm-dd', autoClose: true})


	});
	</script>
<script>
$(window).on('load',function(){
       //preloader
       var preLoder = $("#preloader");
       preLoder.fadeOut(500);
       var backtoTop = $('.back-to-top')
       backtoTop.fadeOut(100);
   });
</script>

@include('layouts.php_js_validation')
@endsection
