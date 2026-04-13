<span class="ui_close_btn"></span>
	
<style type="text/css">
@media  only screen and (min-width: 1500px) {
.bulk_line_no {width: 150px !important;}
.bulk_product_id {width: 200px !important;}
.bulk_uom_code_id {width: 200px !important;}
.bulk_subinventory_id {width: 200px !important;}
.bulk_sublocator_id {width: 250px !important;}
.bulk_accept_qty {width: 250px !important;}
.bulk_allocated_qty {width: 200px !important;}
.bulk_qoh_qty {width: 250px !important;}
 }

.bulk_line_no {width: 100px;}
.bulk_product_id {width: 200px;}
.bulk_uom_code_id {width: 100px;}
.bulk_subinventory_id {width: 100px;}
.bulk_sublocator_id {width: 150px;}
.bulk_accept_qty {width: 100px;}
.bulk_allocated_qty {width: 100px;}
.bulk_qoh_qty {width: 100px;}

.addbox{
    align:right;
}

</style>
<!-- <h4 class="heads">Move To Inventory -->
    <!--<span class="ui_close_btn"><a class="collapse-close pull-right btn-danger" onclick="location.href = '{{url('movetoinventory')}}'"></a></span>-->
<!-- </h4>
 --><div class="card" style="margin-bottom: 0px !important">
<div class="card-header"></div>
<div class="card-body card-block">
    <form id="popupsave">
    <!--<form method="post" action="" id="mvinv_form" class="mvinv_form" data-parsley-validate>-->
      {{ csrf_field() }}
<div class="col-md-6">
        <div class="form-group row">
                <label for="qc_number" class="form-control-label col-md-4">GRN Number</label>
                <div class="col-md-6">
                    <!--/*Showing GRN Header and GRN line value in the name of QC hdr &line*/-->
                <input class="form-control qc_line_id" id="qc_line_id" name="qc_line_id" size="16" type="hidden" value="{{$qc_line_id}}" >
                <input class="form-control qc_header_id" id="qc_header_id" name="qc_header_id" size="16" type="hidden" value="{{$qc_header_id}}" >
                <!--/**/-->
                      <input class="form-control grn_id" id="grn_id" name="grn_id" size="16" type="hidden" value="{{$invdata[0]->grn_number}}" >
                      <input type='text' name="grn_number" id="grn_number" rows='5' class='form-control grn_number'  value="{{$invdata[0]->grn_name}}" readonly>
                                                                    <input type='hidden' name="quality_check" id="quality_check" rows='5' class='form-control quality_check'  value="{{$invdata[0]->quality_check}}" readonly>
                </div>
                <div class="col-md-2">
                </div>
        </div>
        <?php if($invdata[0]->quality_check=="Yes"){?>
	<div class="form-group row">
                    <label for="qc_number" class="form-control-label col-md-4">QC Number</label>
                    <div class="col-md-6">
                        <input class="form-control qc_line_id" id="qc_line_id" name="qc_line_id" size="16" type="hidden" value="{{$qc_line_id}}" >
                        <input class="form-control qc_header_id" id="qc_header_id" name="qc_header_id" size="16" type="hidden" value="{{$qc_header_id}}" >
                         <input type='text' name="qc_number" id="qc_number" rows='5' class='form-control qc_number'  value="{{$invdata[0]->qc_number}}" readonly>
                    </div>
                       <div class="col-md-2">
                       </div>
              </div>
			  <?php }?>
</div>
<div class="col-md-6">
						
        <div class="form-group row">
		<label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red;">*</span>Move to Inventory Date</label>
		<div class="col-md-6">
		<div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
		<input class="form-control move_date datepicker" id="move_date" name="move_date" size="16" type="text" value='{{date("Y-m-d")}}' required>
		</div>
		<input type="hidden" id="qc_date" value='' />
		</div>
		<div class="col-md-1 showline">
		</div>
	</div>
</div>
<input class="form-control po_number" id="po_number" name="po_number" size="16" type="hidden" value="{{$invdata[0]->po_number}}" >

<!--<div class="col-lg-12 col-md-12">
<div class="form-group text-center">
        <button type="button" class="btn btn-sm add move_inv" value="" >Move to Inventory</button>
  </div>
                                        </form>
</div>-->
<div class="row">
<div class="col-md-12 batch_lines">

	

<input type="hidden" name="enable-masterdetail" value="true">


</div>
</div>

 
		
	
<!--                              
<div class="modal fade" id="boxModal">
  <div class="modal-dialog vertical-align" style="width:960px;">
    <div class="modal-content">
		Moda Header
      <div class="modal-header">
		  <h4 class="modal-title"> Move to Inventory </h4>
                  <input class="form-control po_number" id="po_number" name="po_number" size="16" type="hidden" value="{{$invdata[0]->po_number}}" >
                  <input type='hidden' name="quality_check" id="quality_check" rows='5' class='form-control quality_check'  value="{{$invdata[0]->quality_check}}" readonly>
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
	  </div>
		 Modal Body 
	  <div class="modal-body">
	      <table id="batch" class="batch_table">
			  
		  <tbody class="batch_lines">
		
			  </tbody>
		  </table>
	  </div>
		  Modal footer 
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>-->
                              </form>
    </div>
<script>



	 $(document).ready(function(){

	 $(document).on('click','.cancel',function(){
          var red_url		="{{ URL::to('movetoinventory') }}";
          window.location.href=red_url;
         });

//setTimeout(function(){ $('table.preview').stickyTable({style : 'stripped'}); }, 500);
		 
		 $('.uomdiv,.prddiv').css('pointer-events','none');
		  

		 $('.bulk_accept_qty,.bulk_uom_code_id,.bulk_qoh_qty').attr('readonly',true);

		changeClassName('bulk_qc_line_id');
		changeClassName('bulk_line_no');
		changeClassName('bulk_product_id');
		changeClassName('bulk_uom_code_id');
		changeClassName('bulk_accept_qty');
		changeClassName('bulk_qoh_qty');
		changeClassName('bulk_allocated_qty');
		changeClassName('bulk_subinventory_id');
		changeClassName('bulk_sublocator_id');

		 $(document).on('change keyup','.bulk_product_id,.bulk_allocated_qty',function(){

		    var index=$(this).closest('tr').index();

			 var prd = $('.bulk_product_id'+index).val();

			 var acptqty = $('.bulk_allocated_qty'+index).val();


			 var total = $('.accept'+prd).val();
			 var qoh_qty = $('.qoh_qty'+prd).val();
			 var uom=$('.uom'+prd).val();
			 $('.bulk_uom_code_id'+index).val(uom).change();
			 var acceptqty=0;


			$('.bulk_product_id').each(function(k){
 				var product_id=$('.bulk_product_id'+k).val();
 				var uom_code_id=$('.bulk_uom_code_id').val();
					if(prd==product_id)
					{

	                  acceptqty = parseFloat(acceptqty) + parseFloat($('.bulk_allocated_qty'+k).val());
                    }

					if(acceptqty > total)
					{
						 notyMsg("info","Exceeds Accept Qty");
					    $('.bulk_allocated_qty'+k).val("0");
				    }

					});
			 $('.bulk_accept_qty'+index).val(total);
			 $('.bulk_qoh_qty'+index).val(qoh_qty);

		 });
$('select').attr("required",true);
$('.sublocator_id').prop("required",true);
$(document).on('click','.addbox',function() {
    /*karthigaa Purpose for Serial wise Qty Checking*/
    var qty=0;
    var accept_qty=parseInt($('.accept_qty').val()); 
        $(".box_product_qty").each(function(){
                              qty += +$(this).val();
                           });
     if(qty>accept_qty || qty<accept_qty ){
        notyMsgs("error","Box Qty Should Not Exceeds QC Accept Qty");
        $('.box_product_qty').val("");
    }
 /*End*/
    var url="{{ URL::to('inventorysave') }}";
	var red_url		="{{ URL::to('movetoinventory') }}";
        validationrule('popupsave');
	var formdata	= $('#popupsave').serialize();
	var form = $('#popupsave');
		form.parsley().validate();
		var form = $('#popupsave');
		form.parsley().validate();
		if (form.parsley().isValid()) {
		  $.post(url,formdata,function(data){
				var status  = data.status;
				var msg     = '<span style="color:#090065"></span>  '+data.message;
				var id      = data.id;
				var auto_no = data.auto_no;

				notyMsg(status,msg);
				setTimeout(function(){
				window.location.href=red_url;
				}, 1500);
			

		});
		}
});
//$(document).on('click','.move_inv',function() {
    var qc_line_id=$('.qc_line_id').val();
    var grn_id=$('.grn_id').val();
    
	var quality_check=$('.quality_check').val();
    var url ="{{ URL::to('movetoinventoryupdate') }}/"+qc_line_id+"/"+grn_id+"/"+quality_check;
      $.get(url,function(data){
      		$('.batch_table tbody').html('');
                if(data!=""){
	        $('.batch_lines').append(data);
                 $('#boxModal').modal('show');
                 $('#boxModal').width("100%");
//				$('.subinventory_id,.sublocator_id').select2();
                            }
             else{
                 var red_url		="{{ URL::to('movetoinventory') }}";
                 notyMsg("info","There is no accepted quantity");
                setTimeout(function(){
				window.location.href=red_url;
				}, 1500);
             }
      });

setTimeout(function(){ $('table.preview').stickyTable({style : 'stripped'}); }, 300);
//});

//$(document).on('keyup','.box_product_qty',function(){
//    var box_qty=parseInt($(this).val());
//    alert(box_qty);
//     
//// var accept_qty=parseInt($('.accept_qty').val());   
// alert(accept_qty);
// if(box_qty>accept_qty){
//     notyMsgs("error","Box Quantity Should Not Exceeds QC Quantity");
//     $('.box_product_qty').val("");
// }
//});

$(document).on('click','.saveform',function() {
        var btnval = $(this).val();
        $('#savestatus').val(btnval);
       var url			="{{ URL::to('moveinvsave') }}";
	var red_url		="{{ URL::to('movetoinventory') }}";
        validationrule('mvinv_form');
	var formdata	= $('#mvinv_form').serialize();
	var form = $('#mvinv_form');
		form.parsley().validate();
		var form = $('#mvinv_form');
		form.parsley().validate();
		if (form.parsley().isValid()) {
		  $.post(url,formdata,function(data){
				var status  = data.status;
				var msg     = '<span style="color:#090065"></span>  '+data.message;
				var id      = data.id;
				var auto_no = data.auto_no;

			if(btnval =='SAVE')
			{
				notyMsg(status,msg);
				setTimeout(function(){
				window.location.href=red_url;
				}, 1500);
			}

		});
		}

	});


	$(document).on('change', '.subinventory_id', function () {
//		var subinventory_id = $('.subinventory_id').select2('val');
		var subinventory_id = $('.subinventory_id').val();
		$(".sublocator_id").jCombo("{{ URL::to('jcomboform?table=m_sublocators_t:sublocator_id:locator_name') }}&parent=subinventory_id="+subinventory_id+ '&order_by=locator_name asc',
		{selected_value:"$value->sublocator_id"});

	});
		 $(document).on('click','.add_row',function(){
			cloneRow('gin_tbl','mvi_lines_body');

			changeClassName('bulk_qc_line_id');
			changeClassName('bulk_line_no');
			changeClassName('bulk_product_id');
			changeClassName('bulk_uom_code_id');
			changeClassName('bulk_accept_qty');
			changeClassName('bulk_qoh_qty');
			changeClassName('bulk_allocated_qty');
			changeClassName('bulk_subinventory_id');
			changeClassName('bulk_sublocator_id');
          });


	   $(document).on('click','.remove',function(){
		    var index = $(this).closest('tr').index();
		    var rowCount = $('.gin_tbl tbody tr').length;
		     if(rowCount > 1)
			 {
			    $($(this).closest("tr")).remove();
				 removeClass('bulk_qc_line_id');
				 removeClass('bulk_line_no');
				 removeClass('bulk_product_id');
				 removeClass('bulk_uom_code_id');
				 removeClass('bulk_accept_qty');
				 removeClass('bulk_qoh_qty');
				 removeClass('bulk_allocated_qty');
				 removeClass('bulk_subinventory_id');
				 removeClass('bulk_sublocator_id');
			 }
		   else
			{
				 notyMsg("info","You Can't Delete Atleast One row should be there");
			}

	     });

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

		 function removeClass(className)
		 {
			var rowCount = $('.gin_tbl tbody tr').length;
			for(var i=0;i<=rowCount;i++)
			{
			$('.gin_tbl tbody tr').find('.'+className).removeClass(className+i);
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

		 
	$('.bulk_product_id > option').each(function(){

		var j;
		var values = $(this).val();

		if(jQuery.inArray(values,myarray)!='-1')
		{

		}
		else
		{
		 	j=$(this).val();
			$(".bulk_product_id  option[value='"+ j + "']").remove();
		}
	});

 /* end */

	 });


</script>
@include('layouts.php_js_validation')

