@extends('layouts.header')
@section('content')

<style type="text/css">

@media  only screen and (min-width: 1500px) { }
.bulk_line_no {width: 100px;}
.bulk_product_id {width: 200px;}
.bulk_uom_code_id {width: 100px;}
.bulk_box_qty {width: 100px;}
.bulk_receive_qty {width: 100px;}
.bulk_accept_qty {width: 200px;}
.bulk_reject_qty {width: 200px;}
.bulk_reason {width: 200px;}
.line_no {width: 70px;}
.bulk_parameter {width: 200px;}
.bulk_standard {width: 100px;}
.bulk_measurement {width: 150px;}
</style>


<span class="ui_close_btn"></span>
<h2 class="heads">Quality Checking</h2>

	<!------------------------- breadcrumbs start here --------------------------->

	<!---------------------------------------------------------------------------->
<form method="post"  id="qcform" data-parsley-validate>
{{ csrf_field() }}
<div class="card">
<div class="card-header">


<span class="ui_close_btn"><a class="collapse-close pull-right btn-danger" onclick="location.href = '{{url('purchaseqc')}}'"></a></span>
</div>

<div class="card-body card-block">
	<div class="col-md-4">

          	<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-6">QC Number</label>
			<div class="col-md-6">
			<input class="form-control grn_id" id="grn_id" name="qc_header_id" size="16" type="hidden" value="{{ $row->qc_header_id }}" readonly>
				<input type="text" id="grn_number" name="qc_number"  class="form-control grn_number" value="{{ $row->qc_number   }}" readonly>
			</div>
			
		</div>

                            <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-6"><span style="color:red;">*</span> QC Date</label>
			<div class="col-md-6">
			<div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
			<input class="form-control qc_date datepicker" id="qc_date" name="qc_date" size="16" type="text" value="{{ $row->qc_date }}" required>
			<!-- <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> -->
			</div>
			<input type="hidden" id="qc_date" value="{{ $row->qc_date }}" />
			</div>
			
		</div>




                          <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-6">Description</label>
			<div class="col-md-6">
                            <textarea name="description" id="remarks" value="{{ $row->description }}" class="form-control description"> </textarea>
			</div>
			
		</div>
		 

	</div>

	<div class="col-md-4">
        <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-6"> PO Date</label>
			<div class="col-md-6">
			<div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
			<input class="form-control po_date" id="po_date" name="po_date" size="16" type="text" value="{{ $row->po_date }}" readonly>
			<!-- <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> -->
			</div>
			<input type="hidden" id="po_date" value="{{ $row->po_date }}" />
			</div>
			
	</div>
        <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-6">Bill Number</label>
			<div class="col-md-6">

				<input type="text" id="bill_number" name="bill_number"  class="form-control bill_number" value="{{ $row->bill_number   }}" required>
			</div>
			
	</div>
        <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-6">Supplier Name</label>
			<div class="col-md-6">
				<select name='supplier_id' rows='5' class='form-control supplier_id' data-show-subtext="true" data-live-search="true"  required >
				{!! $row->supplier_id  !!}
				</select>
			</div>
			
	</div>
      	</div>
	<div class="col-md-4">
             <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-6">QC Status</label>
			<div class="col-md-6">
                            <select type="text" name="qc_status" id="qc_status" class="form-control qc_status" readonly>
					<option value="">--Please Select--</option>
					<option <?php if($row->qc_status =="DRAFT") { echo "selected"; } else { echo ""; } ?> value="DRAFT">DRAFT</option>
					<option <?php if($row->qc_status =="INITIATED") { echo "selected"; } else { echo ""; } ?> value="INITIATED">INITIATED</option>
                            </select>
			</div>
		   </div>

        <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-6">PO Number</label>
			<div class="col-md-6">
                            <?php //dd($row);  ?>
			<select name='po_number' rows='5' class='form-control po_number' data-show-subtext="true" data-live-search="true"  required >
				{!! $row->po_number !!}
				</select>

			</div>
			
		</div>
                	<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-6">GRN Number</label>
			<div class="col-md-6">
                            <?php //dd($row);  ?>
			<select name='grn_number' rows='5' class='form-control grn_number' data-show-subtext="true" data-live-search="true"  readonly >
				{!! $row->grn_number !!}
				</select>

			</div>
			
		</div>






	</div>
</div>

<div class="qcrpdetails">

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
<th>Packed Description</th>
<th>Box Qty</th>
<th>Total Box Qty</th>
<th>Accepted Qty</th>
<th>Rejected Qty</th>
<th>Reason</th>
<th>Quality Check</th>
<th></th>

</tr>
</thead>
<tbody class="po_lines_body">
<?php if(count($linedata)>=1) { ?>
@foreach($linedata as $key=>$value)


<tr class="clone clonedInput">
    <td>
        <input type="hidden" name="bulk_qc_line_id[]" class="form-control input-sm bulk_grn_line_id" value="{{ $value->qc_line_id }}">
    </td>
    <td>
        <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="{{ $key + 1 }}" readonly="readonly" >
    </td>
    <td class="pdtdiv">
        <select name="bulk_product_id[]" class="form-control bulk_product_id  parsley-validated" required="required">{!! $value->product_id !!}</select>
       <input type="hidden" class="form-control input-sm qc_type" value="{{ $value->qc_type }}">
       <input type="hidden" class="form-control input-sm serialno" value="{{ $value->serialno }}">
       <input type="text" class="form-control input-sm pqcdata" name="qcdetails[]" value="0">
	</td>
    <td>
        <select name="bulk_uom_code_id[]" id="bulk_uom_code_id" class="form-control bulk_uom_code_id">
            {!! $value->uom_code_id !!}
        </select>
    </td>
	<td>
            <input type="text" name="bulk_packed_discription[]" class=" input-sm bulk_packed_discription input_qty_width" value="" readonly>
        </td>
    <td>
        <input type="text" name="bulk_box_qty[]" class="form-control input-sm bulk_box_qty input_qty_width" value="{{ $value->qty }}" required="required">
    </td>
    <td>
        <input type="text" name="bulk_receive_qty[]" class="form-control input-sm bulk_receive_qty input_qty_width" value="{{ $value->receive_qty }}" required="required" readonly>
    </td>
				  <td>
        <input type="text" name="bulk_accept_qty[]" class="form-control input-sm bulk_accept_qty input_qty_width" value="" required="required">
    </td>

    <td>
        <input type="text" name="bulk_reject_qty[]" class="form-control input-sm bulk_reject_qty input_qty_width" value="" required="required" readonly>
    </td>
 <td>
        <input type="text" name="bulk_reason[]" class="form-control input-sm bulk_reason input_qty_width" value="{{ $value->reason }}">
    </td>
  
	<td>
		<button type="button" class="btn applychanges qccheck" value="qc">QC</button>
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
			
			<?php if($url == "qcapproval") { ?>
			<button type="button" class="btn save saveform approve" value="APPROVE">Approve</button>
			<button type="button" class="btn save saveform reject" value="REJECT">Reject</button>
			 <a href="{{ url('qcapproval') }}" class='btn cancel'>Cancel</a>
			<?php }else{ ?>
			<button type="button" class="btn applychanges saveform" value="APPLYCHANGES">Apply Changes</button>
			<button type="button" class="btn save saveform" value="DRAFT">Draft</button>
<!--                        <button type="button" class="btn save saveform"   value="SAVENEW">Save and New</button>-->
			<button type="button" class="btn save saveform" value="SAVE">Save</button>
			  <a href="{{ url('purchaseqc') }}" class='btn cancel'>Cancel</a>
			<?php }?>
		</div>
	</div>
</div>


</div>
	
	<!-- deepika purpose  qcmodel-->
  <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog">
                      <!-- Modal content-->
                      <div class="modal-content">
                        <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
							<input type="hidden" class="qctableindex" value="">
							<input type="hidden" class="qcbtnclass" value="">
                                <h4 class="modal-title">Quality Check</h4>
                        </div>
                        <div class="">
                        <div class="col-md-12">
                      <table class="overflow-y preview qc_table">
						<tbody class="qc_lines_body">
						</tbody>
						</table>
                         </div>
                        </div>
                        <div class="modal-footer ">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group text-center foot">
                                          <input type="radio" class="pids" name="qcstatus" data-index="Accepted" value="Accepted" checked/> Accepted
                                          <input type="radio" class="pids" name="qcstatus" data-index="Rejected" value="Rejected"/> Rejected
									</div>  <div class="form-group text-center foot">
										<input type="checkbox" name="applicable" class="applicable" value=""> Same as Applicable
										<button type="button" class="btn add prdqc" data-dismiss="modal">Save</button>
                                        <button type="button" class="btn cancel" data-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                       
                        <!--- --->
                    </div>
                


    </div>
<!--end-->

	<!-- deepika purpose  serialwisemodel-->
  <div class="modal fade" id="serialModal" role="dialog">
                    <div class="modal-dialog modal-sm">
                      <!-- Modal content-->
                      <div class="modal-content">
                        <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Quality Check</h4>
                        </div>
                        <div class="">
                        <div class="col-md-12">
                      <table class="overflow-y preview qcserial_table">
						<tbody class="qcserial_lines_body">
						</tbody>
						</table>
                         </div>
                        </div>
                        <div class="modal-footer">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group text-center">

                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                       
                        <!--- --->
                    </div>
                


    </div>
<!--end-->
	</form>
	
<script>
$('.po_number,.bulk_box_qty,.bill_number').attr("readonly",true);
$('.supplier_id,.bulk_product_id,.bulk_uom_code_id').attr("readonly",true);
$('.supplier_id,.po_number,.grn_number,.bulk_product_id,.bulk_uom_code_id,.qc_status').css("pointer-events","none");


$(document).ready(function(){
   $(document).on('click','.saveform',function()
    {
            var btnval		= $(this).val();

            if(btnval == 'APPLYCHANGES')
                $("#qc_status").val('DRAFT');
            else if(btnval == 'DRAFT')
                $("#qc_status").val('DRAFT');
            else
                $("#qc_status").val('INITIATED');

            var url		= "{{ url('qcsave') }}";
            var red_url		="{{ url('purchaseqc') }}";
            var create_url	="{{ url('qualitychecking') }}/0";
            validationrule('qcform');

            var form = $('#qcform');


            if(btnval != 'APPLYCHANGES')
            {
                form.parsley().validate();
                var form = $('#qcform');
                form.parsley().validate();

                if (form.parsley().isValid())
                {
                    change_date();
                     var formdata	= $('#qcform').serialize();
                    $.post(url,formdata,function(data)
                    {
                        var status      = data.status;
                        var msg     = '<span style="color:#090065">'+data.auto_no+'</span>  '+data.message;
                        var id          = data.id;
                        var edit_url	= "{{ url('qualitychecking') }}/"+id;
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
             change_date();
                     var formdata	= $('#qcform').serialize();
            $.post(url,formdata,function(data)
            {
                   var status = data.status;
                    var msg    = data.message;
                    var id     = data.id;
                    var edit_url	="{{ url('purchaseqcedit') }}/"+id;
                            notyMsg(status,msg);
                            setTimeout(function(){
                            window.location.href=edit_url;
                            }, 1500);

                });
            }
    });
$(document).on('click','.jcr_supplier_id',function()
{
$(".supplier_id").jCombo("{{ URL::to('jcomboform?table=m_supplier_t:supplier_id:supplier_name') }}&order_by=supplier_name asc",
{selected_value:""});
});

$(document).on('click','.jcr_project_id',function(){
$(".project_id").jCombo("{{ URL::to('jcomboform?table=m_projects_t:project_id:project_name') }}&order_by=project_name asc",
{selected_value:""});
});

$(document).on('click','.jcr_po_pricelist_id',function(){
$(".po_pricelist_id").jCombo("{{ URL::to('jcomboform?table=i_pricelist_hdr_t:pricelist_hdr_id:pricelist_name') }}&order_by=pricelist_name asc",
{selected_value:""});
});

$(document).on('click','.add_row',function()
{
	var cloned = $('.po_table').find('tr:eq(1)').clone();
	cloned.find("input[type=text],input[type=hidden], textarea,input[type=date],select").val("");
	cloned.appendTo('.po_lines_body');
    changeclassfields();

});


$(document).on('click','.remove',function()
{
	var index = $(this).closest('tr').index();
	var rowCount = $('.po_table tbody tr').length;
	if(rowCount > 1)
	{
		$($(this).closest("tr")).remove();
                removeClass('bulk_line_no');
                removeClass('bulk_product_id');
                removeClass('bulk_uom_code_id');
                removeClass('bulk_box_qty');
                removeClass('bulk_receive_qty');
                removeClass('bulk_accept_qty');
                removeClass('bulk_reject_qty');

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
changeclassfields();



$('.qccheck').click(function()
	{
	var index = $(this).closest('tr').index();
	var pid=$(".bulk_product_id" + index).val();
	var qctype=$(".qc_type"+index).val();alert(qctype);
	var url="{{URL::to('productqcspecdetails')}}/"+pid;
	if(qctype=="BATCHWISE"){
	 $('#myModal').modal('show');
	 $('#myModal').width("100%");
	$('.qctableindex').val(index);
	$.get(url,function(data){console.log(data);
		if(data!=0){
	 $('.qc_table tbody').html('');
     $('.qc_lines_body').append(data);
			//$('.foot').show();
	}else{
		//  $('.foot').hide();
		$('.qc_table tbody').html("<tr><td></td><td colspan='6' align='center'>There is no Product Specification assigned for this Product.....</td></tr>");
		  }
		
	});}else{
     $('#serialModal').modal('show');
	 $('#serialModal').width("100%");
		var serialnumber=$('.serialno'+ index).val();
		var sno = serialnumber.split(",");
		 $('.qcserial_table tbody').html('');
		$.each(sno,function(i,v){
			 var pqcdata=$('.pqcdata'+index).val();
		    var prdid=$('.bulk_product_id'+index).val();
			 $(".qcserial_lines_body").append("Serial Number:"+v+"<button type='button' class='btn add snoqc' data-index1='"+prdid+v+"'>Quality Check</button>");
		 	if(pqcdata==0){
			$(".qcrpdetails").append("<input type='hidden' name='sncls"+prdid+"[]' class='btn add sncls"+prdid+v+"' value="+v+">");
				
			}
		});
		$('.pqcdata'+index).val('1');
		$('.snoqc').click(function(){
	    $('#myModal').modal('show');
	    $('#myModal').width("100%");
		var dataindex=$(this).data('index1');
			var qcbtnclass =$('.qcbtnclass').val(dataindex);
	    $('#serialModal').modal('hide');
	$.get(url,function(data){
		if(data!=0){
	 $('.qc_table tbody').html('');
     $('.qc_lines_body').append(data);
			//$('.foot').show();
     }else{
		 // $('.foot').hide();
		  }
	});
		});
		$('.prdqc').click(function(){
			var measurement=[];
			var std=[];
			var param=[];
			$('.measure').each(function(i,v){
				//alert(i);
				measurement.push($(this).val());
				var stnd=$('.bulk_standard'+i).val();
				std.push(stnd);
				var prm=$('.bulk_parameter'+i).val();
				param.push(prm);
			});
			var arrydetils=measurement+" "+std+" "+param;
			var qcbtnclass1=$('.qcbtnclass').val();
			$('.sncls'+qcbtnclass1).val(arrydetils);
			//$('.measure').val('');
		//	alert(std+" "+param+""+measure);
			 $('#serialModal').modal('show');
		});
	}
	
	
	
	changeclassfields();
	});
	/*deepika purpose:number validation*/
		$(document).on('keypress','.bulk_measurement', function(ev){
			var regex = new RegExp("^[0-9.]+$");
					var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
					if (regex.test(str)) {
						return true;
					}
					ev.preventDefault();
					return false;
		});
	/*end*/
/*karthigaa purpose for hide product in labour condition*/

/*End*/

/* pavan validation for qty */
$(".bulk_accept_qty").change(function(){

    var index = $(this).closest('tr').index();

 var accept=parseFloat($(this).val());
 var total=parseFloat($(".bulk_receive_qty"+index).val());
 if(total<accept){

     notyMsg("info","Exceeds as Recived Qty");
     $(this).val("");
      $(".bulk_reject_qty"+index).val("0");
 }

else{

   var sum=total-accept;

 $(".bulk_reject_qty"+index).val(sum);
    }

})
$('.po_date,.qc_date').datepicker({format: 'yyyy-mm-dd', autoClose: true})


	});
	
function changeclassfields(){
	
changeClassName('bulk_po_line_id');
changeClassName('bulk_line_no');
changeClassName('bulk_product_id');
changeClassName('bulk_uom_code_id');
changeClassName('bulk_box_qty');
changeClassName('bulk_receive_qty');
changeClassName('bulk_accept_qty');
changeClassName('bulk_reject_qty');
changeClassName('qccheck');	
changeClassName('qc_type');	
changeClassName('pqcdata');	
changeClassName('serialno');	
changeClassName('applicable');	
changeClassName('bulk_measurement');	
changeClassName('bulk_standard');	
changeClassName('bulk_parameter');	
changeClassName('bulk_packed_discription');	
	
}
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
