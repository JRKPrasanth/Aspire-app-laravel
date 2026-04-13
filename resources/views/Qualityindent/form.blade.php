@extends('layouts.header')
@section('content')
<h3 class="text-danger">Quality Indent </h3>
@include('layouts.breadcrumb')
		
      
		<form method="post" action="" id="indentform" data-parsley-validate>
		{{ csrf_field() }}
		
<div class="card shadow-lg rounded-4 border-0">
  <div class="card-body headerdiv1">
    <!-- Body content start here -->
    <div class="row">
      <div class="col-md-12">

        <div class="row g-4">
          <!-- Indent Name -->
          <div class="col-md-4">
            <div class="row mb-3 align-items-center">
              <label class="col-md-4 col-form-label">
                <span class="text-danger">*</span>Indent Name
              </label>
              <div class="col-md-8">
                <input type="hidden" class="form-control quality_indent_hdr_id" id="quality_indent_hdr_id" name="quality_indent_hdr_id" value="{{ $row->quality_indent_hdr_id }}" readonly>
                <input type="hidden" name="qc_id" value="{{ $qc_id }}">
                <input type="text" id="indent_name" name="indent_name" class="form-control indent_name" value="{{ $row->indent_name }}" required>
              </div>
            </div>

            <div class="row mb-3 align-items-center none">
              <label class="col-md-4 col-form-label" for="created_by">Created By</label>
              <div class="col-md-8">
                <select name="created_by" class="form-control created_by select2" id="created_by" readonly>
                  {!! $created_by !!}
                </select>
              </div>
            </div>
          </div>

          <!-- Indent Date -->
          <div class="col-md-4">
            <div class="row mb-3 align-items-center">
              <label class="col-md-4 col-form-label">
                <span class="text-danger">*</span>Indent Date
              </label>
              <div class="col-md-8">
                <input type="text" id="indent_date" name="indent_date" class="form-control start_date indent_date" value="{{ $row->indent_date }}" required>
              </div>
            </div>
          </div>

          <!-- Remarks -->
          <div class="col-md-4">
            <div class="row mb-3 align-items-center">
              <label class="col-md-4 col-form-label">Remarks</label>
              <div class="col-md-8">
                <input type="text" name="remarks" id="remarks" class="form-control remarks" value="{!! $row->remarks !!}">
              </div>
            </div>
          </div>

        </div>

      </div>
    </div>



<div class="row">
  <div class="col-12 linetable">
<div class="table-responsive">
  <table class="table table-bordered company_table">
    <thead class="table-light">
      <tr>
      <th> Line No</th>
      <th> Product </th>
      <th> Uom Code</th>
      <th> Quantity</th>
        <?php if($pagemethod =="qualitycheck" ) { ?>
        <th>Used Quantity</th>
        <th>Remaining Quantity</th>
        <?php } ?>
          <?php if($pagemethod =="indentmaterialissue" ) { ?>
        <th>QOH</th>
        <th>Issue Quantity</th>
        <?php } ?>
    <th> Comments</th>
<?php if($pagemethod !="indentmaterialissue" ) { ?>
        <th style="width: 60px;"></th>
        <?php } ?>
      </tr>
    </thead>
<tbody class="company_lines_body">
  @if(count($linedata) > 0)
    @foreach($linedata as $key => $value)
      <tr class="line-row">
      <td>
        <input type="hidden" name="bulk_quality_indent_lines_id[]" class="form-control input-sm bulk_quality_indent_lines_id" value="{{ $value->quality_indent_lines_id }}">
        <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="{{$value->line_no}}" readonly>
    </td>
 
    <td class="productrdly">
       
     <select  name='bulk_product_id[]' class='form-control bulk_product_id select2' id="bulk_product_id"   required="true">
		{!! $product_id[$key] !!}
		
		</select>
	
	</td>
    <td class="uomrdly">
		 <select  name='bulk_uom_code_id[]' class='form-control bulk_uom_code_id select2' id="bulk_uom_code_id"  required="true">
		{!!$uom_code_id[$key]!!}
		
		</select>
        
    </td>
 <td>
        <input type="text" name="bulk_qty[]" class="form-control input-sm bulk_qty" value="{{$value->qty}}" required="true">
    </td>
		<?php if($pagemethod =="qualitycheck" ) { ?>
 <td>
        <input type="text" name="bulk_used_qty[]" class="form-control input-sm bulk_used_qty" value="" >
    </td>
	 <td>
        <input type="text" name="bulk_remaining_qty[]" class="form-control input-sm bulk_remaining_qty" value="{{ $remaining_qty[$key] }}" readonly >
    </td>
	
	<?php } ?>
			<?php if($pagemethod =="indentmaterialissue" ) { ?>
 <td>
        <input type="text" name="bulk_qoh_qty[]" class="form-control input-sm bulk_qoh_qty" value="{{ $qoh_qty[$key] }}"  readonly>
    </td>
	 <td>
        <input type="text" name="bulk_issue_qty[]" class="form-control input-sm bulk_issue_qty" value=""  >
    </td>
	
	<?php } ?>
		 <td>
        <input type="text" name="bulk_comments[]" class="form-control input-sm bulk_comments" value="{{$value->comments}}" >
    </td>
<?php if($pagemethod !="indentmaterialissue" ) { ?>
        <td class="text-center">
          <button type="button" class="btn btn-sm btn-danger remove-row">
            <i class="fas fa-minus-circle"></i>
          </button>
        </td>
        <?php } ?> 
      </tr>
    @endforeach


  @else

    <tr class="line-row">
    <td>
        <input type="hidden" name="bulk_quality_indent_lines_id[]" class="form-control input-sm bulk_quality_indent_lines_id" value="">

        <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="1" readonly>
    </td>
    <td>
            <select  name='bulk_product_id[]' class='form-control bulk_product_id select2' id="bulk_product_id"   required="true">
		{!!$product_id!!}
		
		</select>
    </td>
    <td class="uomrdly">
		 <select  name='bulk_uom_code_id[]' class='form-control bulk_uom_code_id select2' id="bulk_uom_code_id"  required="true">
		{!!$uom_code_id!!}
		
		</select>
        
    </td>
 <td>
        <input type="text" name="bulk_qty[]" class="form-control input-sm bulk_qty" value="" required="true">
    </td>
		 <td>
        <input type="text" name="bulk_comments[]" class="form-control input-sm bulk_comments" value="" >
    </td>
      <td class="text-center">
        <button type="button" class="btn btn-sm btn-danger remove-row">
          <i class="fas fa-minus-circle"></i>
        </button>
      </td>
    </tr>
  @endif
</tbody>

  </table>

  <div class="text-end">
    <button type="button" class="btn btn-success btn-sm add-row">
      <i class="fas fa-plus-circle"></i> Add Row
    </button>
  </div>
</div>

  </div>
</div>
<!-- END -->

  <div class="text-center mt-4">
  <?php if($pagemethod =="indentmaterialissue" ) { ?> 
    <button type="button" class="btn btn-success saveform px-4 me-2">Issue</button>
    <?php } else {?> 
    <button type="button" class="btn btn-success saveform px-4 me-2">Save</button>
    <?php } ?>
    <a href="{{ url($pageModule) }}" class="btn btn-secondary px-4 me-2">Cancel</a>
  </div>
</div>
</div>   
</form>

@endsection
@push('scripts')			
		
<script>
	
		$(document).ready(function()
		{
			<?php if($pagemethod =="qualitycheck" ) { ?>
$('.bulk_used_qty').attr("required",true);
$('.productrdly,.bulk_qty,.bulk_comments,.indent_name,.indent_date,.remarks').css("pointer-events","none");   
	<?php } ?>
			<?php if($pagemethod =="indentmaterialissue" ) { ?>
$('.bulk_issue_qty').attr("required",true);
$('.productrdly,.bulk_qty,.bulk_comments,.indent_name,.indent_date,.remarks').css("pointer-events","none");   
	<?php } ?>
//$('.uomrdly,.created_by').css("pointer-events","none");               
       
		
            /**kaviya purpose not greater than qoh qty **/        
         	$(document).on('change','.bulk_issue_qty',function()
	     	{      
				var index=$(this).closest('tr').index();
	            var issue_qty = parseFloat($(this).val());
	            var qoh_qty = parseFloat($('.bulk_qoh_qty'+index).val());
				if(issue_qty>qoh_qty)
				{
					$('.bulk_issue_qty'+index).val('');
					showCustomAlert("Not greater than QOH Quantity",'warning',);
					
				}
	 		});
			
        	$(document).on('change','.bulk_product_id',function()
		{            

	 var index=$(this).closest('tr').index();
	 var product_type = $(this).val();
	if(product_type !='')
			{

			var pdtcount = 0;
			$('.clone').each(function (ind, v)
			{
			var val = $(".bulk_product_id" + ind).val();
			if(index != ind)
			{
			if(val == product_type)
			{
				pdtcount++;
			}
			}
				});
				if(pdtcount >0)
				{

				    var msg = $(".bulk_product_id" + index + ' option:selected').text();
					var message  = '<span style="color:#fdff65">'+msg+'</span>'+' Product  Already Selected';
					showCustomAlert(message,'warning');
					$(".bulk_product_id" + index).select2('val',['']);
				}
				else{
					 var url = "{{ URL::to('poenquiryuom') }}/"+product_type;
				   $.get(url , function(data)
				   {
					 var data = $.trim(data);
					  
					   
					 $('.bulk_uom_code_id'+index).val(data).trigger('change');
				   });}

			}

});
			$(document).on('keyup','.indent_name',function()
		{
	
		$(this).val($(this).val().toUpperCase());
		});
		/*deepika purpose:qty validation*/
			$(document).on('keypress','.bulk_qty', function(ev){
				var regex = new RegExp("^[0-9.]+$");
						var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
						if (regex.test(str)) {
							return true;
						}
						ev.preventDefault();
						return false;
			});
		/*end*/
			/** kaviya purpose issue save **/
		$(document).on('click','.materialissue',function()
		{
			var save=$(this).val();
				var url		= "{{ url('materialissueindentsave') }}";
				var red_url		="{{ url('indentmaterialissue') }}";
				var formdata	= $('#indentform').serialize();
				var form = $('#indentform');

					form.parsley().validate();
					var form = $('#indentform');
					form.parsley().validate();

					if (form.parsley().isValid())
					{
						$.post(url,formdata,function(data)
						{
							var status      = data.status;
							var msg     = '<span style="color:#090065">'+data.message+'</span>  ';
							var id          = data.id;
							if(save ='SAVE')
							{

								showCustomAlert(msg,status);
								setTimeout(function(){
								window.location.href=red_url;
								}, 1500);
							}
							else
							{
								showCustomAlert(msg,status);
								setTimeout(function(){
								window.location.href=create_url;
								}, 1500);
							}
						});
					}
				

		});
		$(document).on('click','.saveform',function()
		{
			var save=$(this).val();
				var url		= "{{ url('qualityindentsave') }}";
			<?php if($pagemethod =="qualitycheck" ) { ?>
				var red_url		="{{ url('qualitycheck') }}";
			<?php } else{ ?>
			var red_url		="{{ url('qualityindent') }}";
			<?php } ?>
				var create_url	="{{ url('qualityindentcreate') }}/0";
				var formdata	= $('#indentform').serialize();
				var form = $('#indentform');

					form.parsley().validate();
					var form = $('#indentform');
					form.parsley().validate();
					if (form.parsley().isValid())
					{
            			var $btn = $(this);            
            $btn.prop('disabled', true);
						$.post(url,formdata,function(data)
						{
							var status      = data.status;
							var msg     = data.message;
							var id          = data.id;
							if(save ='SAVE')
							{

								showCustomAlert(msg,status);
								setTimeout(function(){
								window.location.href=red_url;
								}, 1500);
							}
							else
							{
								showCustomAlert(msg,status);
								setTimeout(function(){
								window.location.href=create_url;
								}, 1500);
							}
						});
					}
				

		});       
		});
// Add Row
$(document).on('click', '.add-row', function () {
    const $lastRow = $('.company_lines_body tr:last');
    const $newRow = $lastRow.clone(false, false); // clone without events or data

    // Clear all input and select values in the cloned row
    $newRow.find('input').val('');
    $newRow.find('select').val('').trigger('change');

    // Remove any Select2 artifacts before reinitializing
    $newRow.find('select.select2').each(function () {
        if ($.fn.select2 && $(this).hasClass("select2-hidden-accessible")) {
            $(this).select2('destroy');
        }
        $(this).removeAttr('data-select2-id'); 
       $(this).next('.select2').remove(); // remove the select2 container
    });

    // Append the cleaned-up cloned row
    $('.company_lines_body').append($newRow);

    // Reinitialize select2
    $newRow.find('select.select2').select2({ width: '100%' });

		// Update line numbers
		updateLineNumbers();
	});



  // Remove button
  $(document).on('click', '.remove-row', function () {
    const rowCount = $('.company_lines_body tr').length;
    if (rowCount > 1) {
      $(this).closest('tr').remove();
      updateLineNumbers();
    } else {
      showCustomAlert("You Can't Delete Atleast One row should be There", "warning");
    }
  });

  // Renumber Line Nos
  function updateLineNumbers() {
    $('.company_lines_body tr').each(function (index) {
      $(this).find('.bulk_line_no').val(index + 1);
    });
  }	
	
</script>

@endpush