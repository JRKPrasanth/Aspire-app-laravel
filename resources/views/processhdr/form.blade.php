@extends('layouts.header')
@section('content')

<?php  include('tools_menu.php');
?>


<span class="ui_close_btn"><a class="collapse-close pull-right btn-danger" onclick='location.href="{{ URL::to($pageModule) }}"'></a></span>
</h3>
<div class="row">


<!---------------------------------------------------------------------------->



<form method="post" action="" id="process" data-parsley-validate>

{{ csrf_field() }}
<div class="col-lg-12">
<div class="card">



<div class="card-body card-block">

	<!---------------------------- Body element ------------------------------->
	<div class="row">
	<div class="col-md-12">

			<div>
				<div class="row">

    <div class="form-group row" >
        <label for="inputIsValid" class="form-control-label col-md-4">Job card number</label>
        <div class="col-md-6">
            <input class="form-control process_hdr_id" id="process_hdr_id" name="process_hdr_id" size="16" type="hidden" value="" readonly>
            <input type="text" id="job_card_number" name="job_card_number" class="form-control job_card_number" value="" 	>
        </div>
        <div class="col-md-2">
        </div>
    </div>





						 <div class="form-group row">
        <label for="inputIsValid" class="form-control-label col-md-4">Process Name</label>
        <div class="col-md-6">
			<select name="process" class="form-control select2 process">
						{!! $process_name !!}
			</select>

        </div>
        <div class="col-md-2">
        </div>
    </div>





				</div>
			</div>

			<div>

				</div>
			</div>

		</div>
	</div>

	<!---------------------------- End body element --------------------------->



<!-------------------------Linedata -------------------------------->
<div class="row wire">
<div class="col-md-12">

<a href="javascript:void(0);" class="add_row additem newitem wire_draw"   rel=".clone1"><i class="fa fa-plus"></i> New Item</a>

<div class="table-responsive subgrid_div">
<table class="table table-striped wire_draw table_scroll">
<thead>
<tr >
<th ><p>Line No</p></th>
<th ><p>Machine Name</p></th>
<th><p>Input Size(mm/SWG)</p> </th>
<th><p>OUTPUT Size(mm/SWG)</p></th>
<th><p>SEQ NO</p></th>
<th><p>PLANED QTY</p> </th>
<th ><p>PLANED TIME</p></th>
<th ><p>PACKING</p></th>
<th ><p>CUSTOMER</p></th>
<th ><p>ACHIVED QTY</p></th>
<th ><p>ACTUAL TIME</p></th>
<th ><p>REMARKS</p></th>

<th></th>
</tr>






</thead>
<tbody class="so_inq_lines_body">


<tr class="rcopy clone1 process1">
	<td></td>
<?php    ?>
<td ><input type="text" name="bulk_line_no[]" class="form-control  bulk_line_no" value="1" readonly="readonly"></td>
<td  ><select name="bulk_machine_id[]" class="select2 bulk_machine_id  parsley-validated" >

		{!! $machine_name !!}
	</select></td>

<td  >
<input type="text" name="bulk_input_size[]" class="form-control bulk_input_size input_qty_width"  value="">
</td>

<td >
<input type="text" name="bulk_output_size[]" class="form-control  bulk_output_size input_qty_width" value=""  >
</td>
<td >
<input type="text" name="bulk_seq_no[]" class="form-control bulk_seq_no input_qty_width" value="" >
</td>
<td >
<input type="text" name="bulk_planed_qty[]" class="form-control bulk_planed_qty input_qty_width" value="" >
</td>
<td >
<input type="text" name="bulk_planed_time[]" class="form-control bulk_planed_time input_qty_width" value="" >
</td>

<td >
<input type="text" name="bulk_packing[]" class="form-control bulk_packing input_qty_width" value="" >
</td>
<td >
<input type="text" name="bulk_customer[]" class="form-control bulk_customer input_qty_width" value="">
</td>
<td >
<input type="text" name="bulk_achived_qty[]" class="form-control bulk_achived_qty" value="">
</td>
<td >
<input type="text" name="bulk_achived_time[]" class="form-control bulk_achived_time input_qty_width" value="">
</td>
<td >
<input type="text" name="bulk_remarks[]" class="form-control bulk_remarks input_qty_width" value="">
</td>



<td>

<a class="remove_wire remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>

<input type="hidden" name="counter[]">
</td>
</tr>












</tbody>
</table>
<input type="hidden" name="enable-masterdetail" value="true">
</div>
</div>
</div>
<div class="row strip_cutting">
<div class="col-md-12">


<a href="javascript:void(0);" class="add_rows additem newitem "   rel=".clone2"><i class="fa fa-plus"></i> New Item</a>
<div class="table-responsive subgrid_div">
<table class="table table-striped strip_cutting table_scroll">
<thead>


<tr class="process2">

<th ><p>Priority</p></th>
<th><p>Profile</p> </th>
<th><p>R or F</p></th>
<th><p>Cut Length(MM)</p></th>
<th><p>Planned QTY (kg)</p> </th>
	<th><p>PLANED TIME <b>From</b></p></th>
	<th><p>PLANED TIME <b>TO</b></p></th>
<th ><p>OPR</p></th>
<th ><p>Actual Time <b>From</b></p></th>
<th ><p>Actual Time<b>TO</b></p></th>
<th ><p>ACHIVED QTY</p></th>
<th ><p>REMARKS</p></th>
<th></th>
</tr >




</thead>
<tbody class="so_inq_lines_body">






<tr class="rcopy clone2 ">
	<td></td>

<td ><input type="text" name="bulk_priority[]" class="form-control  bulk_lines_no" value="1" readonly="readonly"></td>
<td  ><input type="text" name="bulk_profile[]" class="form-control  bulk_profile" value="" ></td>

<td  >
<input type="text" name="bulk_rf[]" class="form-control bulk_rf input_qty_width"  value="">
</td>

<td >
<input type="text" name="bulk_cutlength[]" class="form-control  bulk_cutlength input_qty_width" value=""  >
</td>

<td >
<input type="text" name="bulk_planned_qty[]" class="form-control bulk_planned_qty input_qty_width" value="" >
</td>
<td >
<input type="text" name="bulk_planed_from[]" class="form-control bulk_planed_from input_qty_width" value="" >
</td>
<td >
<input type="text" name="bulk_planed_to[]" class="form-control bulk_planed_to input_qty_width" value="" >
</td>
<td >
<input type="text" name="bulk_opr[]" class="form-control bulk_opr input_qty_width" value="" >
</td>
<td >
<input type="text" name="bulk_actual_from[]" class="form-control bulk_actual_from input_qty_width" value="">
</td>
<td >
<input type="text" name="bulk_actual_to[]" class="form-control bulk_achived_qty" value="">
</td>
<td >
<input type="text" name="bulk_achiveed_qty[]" class="form-control bulk_achiveed_qty input_qty_width" value="">
</td>
<td >
<input type="text" name="bulk_remarkss[]" class="form-control bulk_remarkss input_qty_width" value="">
</td>



<td>

<a class="remove_cutting remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>

<input type="hidden" name="counter[]">
</td>
</tr>








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



<button name="button" type="button" class="btn save saveform" value="SAVE">Save</button>

<a class='btn cancel' onclick='location.href="{{ url($pageMethod) }}"'>Cancel</a>
</div>
</div>
</div>
</div>
</div>
</div>



	<!-- karthigaa purpose customer search jqgrid model-->


	<!--end-->
<input type="hidden" class="pdtindex" value="" />


</div>
	</form>

	<script>

    $('.wire').hide();
    $('.strip_cutting').hide();
 $(document).ready(function(){



$(".add_row").relCopy(data);
    $('.add_row').click(function()
    {
	changeclassfields();
});
$(".add_rows").relCopy(data);
    $('.add_rows').click(function()
    {
	changeclassfield();
});



$(document).on('click','.jcr_customer_id',function(){
$(".customer_id").jCombo("{{ URL::to('jcomboform?table=m_customers_t:customer_id:customer_name') }}&order_by=customer_name asc",
{selected_value:''});
});

	 $(document).on('change','.process',function(){
var id=$(this).val();
		if(id=="35"){

    $('.wire').show();
    $('.strip_cutting').hide();

		} else {

    $('.wire').hide();
    $('.strip_cutting').show();
		}
});


/* read only */




	function changeclassfields()
{

    changeClassName('bulk_line_no');
    changeClassName('bulk_machine_id');

    changeClassName('bulk_input_size');
    changeClassName('bulk_output_size');
    changeClassName('bulk_seq_no');
    changeClassName('bulk_planed_qty');
    changeClassName('bulk_planed_time');
    changeClassName('bulk_packing');
    changeClassName('bulk_customer');
    changeClassName('bulk_achived_qty');
    changeClassName('bulk_achived_time');
    changeClassName('bulk_remarks');

 }
	 	function changeclassfield()
{

    changeClassName('bulk_lines_no');
    changeClassName('bulk_profile');
    changeClassName('bulk_rf');
    changeClassName('bulk_cutlength');
    changeClassName('bulk_planned_qty');
    changeClassName('bulk_planed_from');
    changeClassName('bulk_planed_to');
    changeClassName('bulk_opr');
    changeClassName('bulk_actual_from');
    changeClassName('bulk_actual_to');
    changeClassName('bulk_achived_qty');
    changeClassName('bulk_achiveed_time');
    changeClassName('bulk_remarkss');

 }










$('.bulk_product_id').select2({width:'100%'});

$(document).on('click','.saveform',function()
{
	var url			="{{ url('processhdrsave') }}";
	var red_url		="{{ url($pageMethod) }}";
	var create_url	="{{ url('process') }}/0";
	validationrule('process');

	var form = $('#process');


		change_date();
		var formdata	= $('#process').serialize();
		$.post(url,formdata,function(data)
		{
		var status = data.status;
		var msg     = '<span style="color:#090065">'+data.auto_no+'</span>  '+data.message;
		var id     = data.id;
		var edit_url	="{{ url('process') }}/"+id;
		notyMsg(status,msg);
		setTimeout(function(){
		window.location.href=edit_url;
		}, 1500);
		});

});



$(document).on('click','.remove_wire',function()
{
	var index = $(this).closest('tr').index();
	var rowCount = $('.wire_draw tbody tr').length;
	if(rowCount > 1)
	{
		$($(this).closest("tr")).remove();

	}
	else
	{
		alert("You Can't Delete Atleast One row should be there");
	}
});
$(document).on('click','.remove_cutting',function()
{
	var index = $(this).closest('tr').index();
	var rowCount = $('.strip_cutting tbody tr').length;
	if(rowCount > 1)
	{
		$($(this).closest("tr")).remove();

	}
	else
	{
		alert("You Can't Delete Atleast One row should be there");
	}
});






$('.customersearch').click(function()
	{
	 $('#customerModal').modal('show');
	 $('#customerModal').width("100%");
	});
$(document).on('click','.productsearch',function()
	{
	var index = ($(this).closest('tr').index());
	 $('.pdtindex').val(index);
	 $('#productModal').modal('show');
	 $('#productModal').width("100%");
	});





/**** To Empty the Rowdata when product Empty ********/
	function rowdataEmpty(index)
	{
	$(".bulk_product_id" + index).val('').change;
	$(".bulk_uom_code_id" + index).val('').change();
	$(".bulk_qty" + index).val('');
	$(".bulk_unit_price" + index).val('');
	$(".bulk_discount_percentage" + index).val('');
	$(".bulk_discount_amount" + index).val('');
	$(".bulk_tax_group_id" + index).change();
	$(".bulk_tax_amount" + index).val('');
	$(".bulk_line_total" + index).val('');
	$(".bulk_promised_date" + index).val('');
	$(".bulk_comments" + index).val('');
	$(".bulk_qty" + index).trigger('change');
	}
/**** To Empty the Rowdata when product Empty End********/
var index = $('.clone').closest('tr').index();
changeClassName('bulk_quote_line_id');
changeClassName('bulk_line_no');
changeClassName('bulk_product_id');
changeClassName('bulk_uom_code_id');
changeClassName('bulk_qty');
changeClassName('bulk_unit_price');
changeClassName('bulk_discount_percentage');
changeClassName('bulk_discount_amount');
changeClassName('bulk_line_subtotal');
changeClassName('bulk_tax_group_id');
changeClassName('bulk_tax_amount');
changeClassName('bulk_line_total');
changeClassName('bulk_promised_date');
changeClassName('bulk_comments');






function changeClassName(className)
{
$('.' + className).each(function (index)
{
if (className == "bulk_line_no")

{
$(this).val(index + 1).attr("readonly", 1);
}
	if (className == "bulk_lines_no")

{
$(this).val(index + 1).attr("readonly", 1);
}


$(this).removeClass(className + '0');
$(this).addClass(className + index);
});
}

/************ Maruthu purpose to remove row action ********************/
function removeClass(className)
{
	var rowCount = $('.so_quote_table tbody tr').length;
	for(var i=0;i<=rowCount;i++)
	{
	$('.so_quote_table tbody tr').find('.'+className).removeClass(className+i);
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

$('.quote_date').datepicker({format: 'yyyy-mm-dd', autoClose: true})


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
<style>
#preloader {
position: fixed;
top: 0;
left: 0;
width: 100%;
height: 100%;
text-align: center;
z-index: 999999999;
background: rgba(0, 0, 0, .2);
}

#preloader img {
display: inline-block;
position: relative;
width: 64px;
height: 64px;
margin:22% auto;
}
.table_scroll tbody {
display:block;
max-height:300px;
overflow:auto;
}
.table_scroll thead tr {
display:table;
}
	div.navtable {
	margin-top:-14px !important;
	}
</style>
@include('layouts.php_js_validation')
@endsection
