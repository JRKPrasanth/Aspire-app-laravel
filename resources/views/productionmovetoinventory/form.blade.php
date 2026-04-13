@extends('layouts.header')
@section('content')
<h3 class="text-danger">Move To Inventory</h3>
@include('layouts.breadcrumb')


<div class="card shadow-lg rounded-4 border-0">
<div class="card-body card-block">
<form method="post" action="{{url('movetosave')}}" id="promovetoinventory" class="promovetoinventory"  enctype="multipart/form-data">

   {{ csrf_field()}}
	   <div class="row" >
    <div class="col-md-4">
   
          <input type="hidden" id="status" name="status" class="form-control status" value="{{ $status}}" readonly>
         
           <input type="hidden" id="quality_spec_trx_hdr_id" name="quality_spec_trx_hdr_id" class="form-control quality_spec_trx_hdr_id" value="{{ $quality_spec_trx_hdr_id}}" readonly>
          
		<?php if($status=="movetoinventoryqa"){ ?>
      <div class="form-group row">
        <label for="inputIsValid" class="form-control-label col-md-5">Job No</label>
        <div class="col-md-7">

          <input type="hidden" id="job_id" name="job_id" class="form-control job_no" value="{{ $row[0]->w_jobs_hdr_id}}" readonly>
         
          <input type="text" id="job_no" name="job_no" class="form-control job_no" value="{{ $row[0]->job_no}}" readonly>
        </div>
      </div>
		
          <div class="form-group row mt-4">
        <label for="inputIsValid" class="form-control-label col-md-5">Job Quantity</label>
        <div class="col-md-7">

          
          <input type="text" id="accept_qty" name="accept_qty" class="form-control accept_qty" value="{{ $accept_qty}}" readonly>
        </div>
      </div>
<?php }  ?>


    </div>

      <div class="col-md-4">


          <?php if($status=="movetoinventoryqa"){ ?>
               <div class="form-group row">
        <label for="start_date" class="form-control-label col-md-5">Job Date</label>
        <div class="col-md-7">
          <div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
            <input class="form-control job_date datepicker" id="job_date" name="job_date" size="16" type="text" value="{{ $row[0]->job_date }}"  >
            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
          </div>

        </div>
      </div>
		  	<?php } ?>
		    <div class="form-group row mt-4">
        <label class="form-control-label col-md-5">Manufacturer Date</label>
        <div class="col-md-7">
          <div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
            <input class="form-control manufacturer_date" id="manufacturer_date" name="manufacturer_date" size="16" type="text" value="{{ $manufacturer_date }}"  >
        </div>

        </div>
      </div>
	</div>

      <div class="col-md-4">



        <?php if($status=="movetoinventoryqa"){ ?>
    <div class="form-group row">
        <label for="inputIsValid" class="form-control-label col-md-5">QA Reference No</label>
        <div class="col-md-7">
           <input type="text" id="reference_no" name="reference_no" class="form-control reference_no" value="{{ $reference_no}}" readonly>

		  </div>
      </div>
      <?php } ?>




		<div class="form-group row mt-4 none">
        <label class="form-control-label col-md-5">Product Expiry Date</label>
        <div class="col-md-7">
          <div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
            <input class="form-control product_expire_date" id="product_expire_date" name="product_expire_date" size="16" type="text" value="{{ $expiry_date }}"  >
        </div>

        </div>
      </div>
      </div>
</div>
	
   
<div class="row mt-4">
  <div id="preview-area" class="col-12 linetable">
<div class="table-responsive">
  <table class="table table-bordered company_table">
    <thead class="table-light">
      <tr>
      <th>Line No</th>
        <th>Product Name</th>
        <th>UOM</th>
        <th>Batch Number</th>
        <th>Production Qty</th>
        <th>Subinventory</th>
        <th>Sublocator</th>
        <th>Moved Qty</th>
      </tr>
    </thead>
<tbody class="company_lines_body">
  @if(count($lines_data)>=1)
    @foreach($lines_data as $key => $value)
      <tr class="line-row">
      <td>
        <input type="text"  name="bulk_line_no[]" class="form-control bulk_line_no" value="{{ $key + 1 }}" readonly="readonly">
        <input type="hidden"  name="bulk_qa_submitstage_trx_hdr_id[]" class="form-control bulk_qa_submitstage_trx_hdr_id" value="{{ $value->qa_submitstage_trx_hdr_id }}" readonly="readonly">
        <input type="hidden"  name="bulk_rejection_type[]" class="form-control bulk_rejection_type" value="{{ $value->rejection_type }}" readonly="readonly">
        <input type="hidden"  name="bulk_qa_submitstage_trx_line_id[]" class="form-control bulk_qa_submitstage_trx_line_id" value="{{ $value->qa_submitstage_trx_line_id }}" readonly="readonly">
    </td>


    <td>
      
        <select name="bulk_product_id[]" id="bulk_product_id" class="bulk_product_id form-control select2" required="required" style="pointer-events:none;" readonly >{!! $value->product_id !!}</select>
    </td>
    <td>
        <select name="bulk_uom_code_id[]" id="bulk_uom_code_id" class="form-control bulk_uom_code_id select2" data-show-subtext="true" data-live-search="true" style="pointer-events:none;" readonly>
            {!! $value->uom_code_id !!} 

        </select>
    </td>
        <td>
        <input type="text" class="form-control input-sm bulk_plan_no input_qty_width" name="plan_no[]"  value="{{$value->plan_no}}">
    </td>
    <td>
        <input type="text" name="bulk_qty[]" class="form-control input-sm bulk_qty input_qty_width " value="{{ $value->qty }}"  required="required" readonly>
    </td>
	<td>
        <select name="bulk_subinventory_id[]" id="bulk_subinventory_id" class="form-control bulk_subinventory_id select2" data-show-subtext="true" data-live-search="true" required="required">
         {!! $value->subinventory_id !!} 

        </select>
    </td>
	<td>
        <select name="bulk_sublocator_id[]" id="bulk_sublocator_id" class="form-control bulk_sublocator_id select2" data-show-subtext="true" data-live-search="true" required="required">
            <option value=''> {!! $value->sublocator_id !!} </option>

        </select>
    </td>
    <td>
        <input type="text" class="form-control input-sm bulk_qoh input_qty_width" name="bulk_qoh[]"  value="{{ $value->qty }}" readonly required="required">
    </td>
      </tr>
    @endforeach
  @endif
</tbody>
  </table>

</div>

</div>
</div>
       <div class="row mt-4 mt-2 mb-3">
      <div class="col-lg-12 col-md-12">
        <div class="form-group text-center">
           <button type="button" class="btn btn-success saveform px-4 me-2" value = "save" > Move to Store</button>
           <a onclick='location.href="{{ url("prodmovetoinventory") }}"' class='btn btn-secondary px-4 me-2'>Cancel</a>

        </div>
      </div>
    </div>
</form>

</div>
</div>

@endsection
@push('scripts')
<script>

$(document).ready(function(){

    $(document).on('keyup','.bulk_qoh',function(){
      var index = $(this).closest('tr').index();
       var production_qty=parseInt($('.bulk_qty'+index).val());
       var moved_qty= parseInt($(this).val());
       var accept_qty= parseInt($('.accept_qty').val());
       var total=0;
       if(moved_qty > production_qty)
       {
           showCustomAlert("Moved Qty should Not Exceed Production Qty","warning");
           $(".bulk_qoh"+index).val("");
       }
       else{
           $('.bulk_qoh').each(function (index)
  {
     var bulk_qoh= parseFloat($('.bulk_qoh'+index).val());
     
     if(isNaN(bulk_qoh))
     {
         bulk_qoh=0;
     }
     
      total=parseFloat(total)+parseFloat(bulk_qoh);
  });
  if(total>accept_qty)
  {
       showCustomAlert("Moved Qty should Not Exceed Job Qty",'warning');
           $(".bulk_qoh").val("");
  }
       }
    });
	
  /* purpose:number validation*/
		$(document).on('keypress','.bulk_plan_no', function(ev){
			var regex = new RegExp("^[0-9\/]+$");
					var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
					if (regex.test(str)) {
						return true;
					}
					ev.preventDefault();
					return false;
		});

	/* purpose:locator load based on subinventory*/
	$(".bulk_subinventory_id").change(function(){
	var index = $(this).closest('tr').index();
	var subinventory= $(".bulk_subinventory_id"+index).val();
	$('.bulk_sublocator_id').attr('disabled',true);
	if(subinventory!='')
	{var condition ="subinventory_id="+subinventory;
		$(".bulk_sublocator_id"+index).jCombo("{{ URL::to('jcomboform?table=m_sublocators_t:sublocator_id:locator_code') }}&order_by=locator_code asc"+'&parent='+condition,
		{selected_value:""});
	$('.bulk_sublocator_id').prop('disabled', false);
	}
	});

// save function

   $(document).on('click','.saveform',function()
    {

            var form = $('#promovetoinventory');
            form.parsley().validate();
           var return_url="{{ url('prodmovetoinventory') }}";
            var url="{{ url('pmovetoinventorysave') }}";
           var formdata  = $('#promovetoinventory').serialize();

                if (form.parsley().isValid())
                {
                  var $btn = $(this);            
                  $btn.prop('disabled', true);
                  $.post(url,formdata,function(data)
                    {
                        var status      = data.status;
                        var msg     = data.message;
                            showCustomAlert(msg,status);
                            setTimeout(function(){
                            window.location.href=return_url;
                            }, 1500);
                          
                        
                    });
                }
    });
    });


</script>

@endpush