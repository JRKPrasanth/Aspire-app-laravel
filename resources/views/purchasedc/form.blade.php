@extends('layouts.header')
@section('content')
<span class="ui_close_btn"></span>


<div class="row">


<style type="text/css">

@media only screen and (min-width: 1500px) {
.bulk_line_no {width: 50px;}
.bulk_product_id {width: 100px;}
.bulk_uom_code_id {width: 100px;}
.bulk_qty {width: 100px;}
.bulk_comments {width: 450px;}
}
@media only screen and (min-width: 2000px) {
.bulk_line_no {width: 60px;}
.bulk_product_id {width: 200px;}
.bulk_uom_code_id {width: 100px;}
.bulk_qty {width: 100px;}
.bulk_comments {width: 550px;}
}
.bulk_line_no {width: 50px;}
.bulk_product_id {width: 290px;}
.bulk_qty {width: 100px;}
.bulk_uom_code_id {width: 75px;}
.bulk_comments {width: 230px;}
</style>

	<h2 class="heads"> DELIVERY CHALLAN <span class="ui_close_btn"><a class="collapse-close pull-right btn-danger" onclick="location.href = '{{url('purchasedc')}}'"></a></span></h2>



<form method="post"  id="dcform" data-parsley-validate>
	<!--<input type="hidden" value="" name="p_return_status" id="p_return_status" />-->
{{ csrf_field() }}

<div class="card">


<div class="card-body card-block">
	<div class="col-md-4">
		<!--<input class="form-control " name="qc_hdr_id" size="16" type="hidden" value="{{ $row[0]->qc_hdr_id }}" >-->
		 	<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">DC Number</label>
			<div class="col-md-6">
				<input type="text" id="dc_number" name="dc_number"  class="form-control" value="{{$row[0]->dc_number}}" required >
			</div>
			<div class="col-md-2">
			</div>
		</div>
                <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">DC Status</label>
			<div class="col-md-6">

				<input type="text" id="dc_status" name="dc_status"  class="form-control dc_status" value="{{ $row[0]->dc_status   }}" >
			</div>
			<div class="col-md-2">
			</div>
		</div>
            
	<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Remarks</label>
			<div class="col-md-6">

				<input type="text" id="remarks" name="remarks"  class="form-control remarks" value="{{ $row[0]->remarks}}" >
			</div>
			<div class="col-md-2">
			</div>
		</div>
            	
		

	</div>
	<div class="col-md-4">

				<!--<input class="form-control " id="qc_id" name="qc_id" size="16" type="hidden" value="{{ $row[0]->qc_header_id }}" >-->
			 	<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">GRN Number</label>
			<div class="col-md-6">

				<input class="form-control " id="grn_id" name="grn_id" size="16" type="hidden" value="{{ $row[0]->grn_id }}" >
				<input type="text" id="grn_id"   class="form-control grn_id" value="{{$row[0]->grn_number}}"  readonly>
			</div>
			<div class="col-md-2">
			</div>
		</div>
       <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4"> DC Date</label>
			<div class="col-md-6">
			<div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
			<input class="form-control dc_date" id="dc_date" name="dc_date" size="16" type="text" value="{{ $row[0]->dc_date }}" readonly>
			<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
			</div>
			<input type="hidden" id="dc_date" value="{{ $row[0]->dc_date }}" />
			</div>
			<div class="col-md-1 showline">
			</div>
		</div>

	
		 <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Qc Number</label>
			<div class="col-md-6">
				<input class="form-control " id="qc_hdr_id" name="qc_hdr_id" size="16" type="hidden" value="{{ $row[0]->qc_header_id }}" >
				<input type="text" name="qc_number" id="qc_number" value="{{ $row[0]->qc_number }}" class="form-control">
			</div>
			<div class="col-md-2">
			</div>
		</div>
		
		
		
		
		
            	</div>
		<div class="col-md-4">
     <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Supplier Name</label>
			<div class="col-md-6">
				<select name='supplier_id' rows='5' class='form-control supplier_id' data-show-subtext="true" data-live-search="true"   >
				{!! $supplier_id  !!}
				</select>
			</div>
			<div class="col-md-2 showinline">

			</div>
	     </div>
                    <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">SubContractor Name</label>
			<div class="col-md-6">
				<select name='subcontract_supplier_id' rows='5' class='form-control subcontract_supplier_id' data-show-subtext="true" data-live-search="true"  >
				{!! $subcontract_supplier_id  !!}
				</select>
			</div>
			<div class="col-md-2 showinline">

			</div>
	     </div>
	 
	</div>
</div>



<!-------------------------Linedata -------------------------------->

<div class="row">
<div class="col-md-12">

<div id="preview-area" class="chandru">
    <table class="overflow-y preview po_table">

<thead>
<tr>

  	<th>Line No</th>
<th class="pdtdiv">Product </th>
<th>Uom Code</th>
<th>Qty</th>
<th>Reason</th>
<th>&nbsp;</th>
</tr>
</thead>
<tbody class="po_lines_body">
<?php if(count($linedata)>=1) { ?>
@foreach($linedata as $key=>$value)

<tr class="clone clonedInput">
    <td>
        <input type="hidden" name="bulk_dc_line_id[]" class="form-control input-sm bulk_dc_line_id" value="{{ $value->dc_line_id }}">
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
        <input type="text" name="bulk_qty[]" class="form-control input-sm bulk_qty input_qty_width" value="{{ $value->qty }}" required="required" readonly>
    </td>
    <td>
            <input type="text" name="bulk_comments[]" class="form-control input-sm bulk_comments input_qty_width" value="{{ $value->comments }}">
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
                     
			<!--<button type="button" class="btn applychanges saveform" value="APPLYCHANGES">Draft</button>-->
			<button type="button" class="btn save saveform" value="SAVE">Save</button>
			  <a href="{{ url('purchasedc') }}" class='btn cancel'>Cancel</a>
                     </div>
	</div>
</div>



</div>


<input type="hidden" class="pdtindex" value="" />
<div id="preloader">
       <!--<img src="https://jrlma.ca/wp-content/plugins/gallery-by-supsystic/src/GridGallery/Galleries/assets/img/loading.gif">-->
    </div>


</form>

</div>




<script>
$('input').attr('readonly', true);
$('select').attr('readonly', true);
$('#dc_date,.bulk_comments,.remarks').attr('readonly', false);





$(document).ready(function(){
    $('.bulk_product_id,.bulk_uom_code_id,.supplier_id,.subcontract_supplier_id').css('pointer-events','none');
 $('#savestatus').val('');
    $(document).on('click','.saveform',function(){
            var btnval		= $(this).val();

            if(btnval == 'APPLYCHANGES'){
                $("#dc_status").val('APPLYCHANGES');
            }
            else if(btnval == 'DRAFT'){
                $("#dc_status").val('DRAFT');
            }
            else{
                $("#dc_status").val('INITIATED');
            }

            var url		= "{{ url('purchasedcsave') }}";
            var red_url		="{{ url('purchasedc') }}";
            var create_url	="{{ url('purchasedccreate') }}/0";
            validationrule('dcform');
            change_date();
            var formdata	= $('#dcform').serialize();
            var form = $('#dcform');

            if(btnval != 'APPLYCHANGES')
            {
                form.parsley().validate();
                var form = $('#dcform');
                form.parsley().validate();

                if (form.parsley().isValid())
                {
                    $.post(url,formdata,function(data)
                    {
                        var status      = data.status;
                        var msg     = '<span style="color:#090065"></span>'+data.message;
                        var id          = data.id;
                        var edit_url	= "{{ url('purchasedccreate') }}/"+id;
                        if(btnval !='SAVE' && btnval !='DRAFT' && btnval !='APPROVED' && btnval !='REJECTED')
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
                    var edit_url	="{{ url('purchasedcedit') }}/"+id;
                            notyMsg(status,msg);
                            setTimeout(function(){
                            window.location.href=edit_url;
                            }, 1500);

                });
            }
    });
    
     


var data ="{{\Session::get('j_date_format')}}";
	$(".add_row").on('click',function(){
  var form = $('#dcform');
  form.parsley().destroy();
});
$(".add_row").relCopy(data);
   changeclassfields();


  $('.add_row').click(function(){
             changeclassfields();
             var rowCount = $('.po_table tbody tr').length;
             
    });
    


$(document).on('click','.form',function()
{
var btn_val = $(this).val();
$('.submit_type').val(btn_val);
});



$(document).on('click','.remove',function()
{
	var index = $(this).closest('tr').index();
	var rowCount = $('.po_table tbody tr').length;
	if(rowCount > 1)
	{
		$($(this).closest("tr")).remove();
		removeclassfields();
	}
	else
	{
		notyMsg('info',"You Can't Delete Atleast One row should be there");
	}
});



var index = $('.clone').closest('tr').index();



function changeclassfields(){
    changeClassName('bulk_dc_line_id');
    changeClassName('bulk_line_no');
    changeClassName('bulk_product_id');
    changeClassName('bulk_uom_code_id');
    changeClassName('bulk_qty');
    changeClassName('bulk_comments');
}



/*karthigaa purpose for hide product in labour condition*/

/*End*/
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
	var rowCount = $('.po_table tbody tr').length;
	for(var i=0;i<=rowCount;i++)
	{
	$('.po_table tbody tr').find('.'+className).removeClass(className+i);
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
