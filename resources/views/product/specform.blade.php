@extends('layouts.header')
@section('content')
<h3 class="text-danger">Product Specification</h3>
@include('layouts.breadcrumb')

<form method="post" action="" id="productspecform" data-parsley-validate>
    {{ csrf_field() }}


<div class="card shadow-lg rounded-4 border-0">
<div class="card-header bg-primary text-white fw-semibold"></div>
<div class="card-body card-block headerdiv1">

<!--******************** Body content start here ****************-->
	<div class="row">
		<div class="col-md-12">
            <div class="row">

                    <div class="row mb-3 none">
                        <label for="inputIsValid" class="col-form-label col-md-4"><span style="color:red;">*</span> Product Name</label>
                        <div class="col-md-6">
    			            <input type="hidden" name="quality_product_specs_hdr_id" id="quality_product_specs_hdr_id" class="form-control quality_product_specs_hdr_id" value="{{ $quality_product_specs_hdr_id }}" readonly>
                            <select type="text" name="product_id" id="product_id" class="form-control product_id select2" required style="width: 100%;">
                                {!! $product_id !!}    
                            </select>
                        </div>

                </div>		
			</div>			
		</div>
	<!--*************************************-->

		
<div class="row mt-2">
<div class="col-md-12">

<!--*******************-Linedata ****************-->

    <div id="preview-area" class="table-responsive">
    <table class="table table-bordered clone_table">
        <thead class="table-light">
            <tr>
                <th>Line No</th>
                <th>Parameter </th>
                <th>Specification Criteria</th>
                <th>Spec Value From</th>
                <th>Spec Value To</th>
                <th>Uom</th>
                <th>Quality Type</th>
                <th>Comments</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody class="clone_lines_body">
            <?php if(count($linedata)>=1) { ?>
                @foreach($linedata as $key=>$value)
                <tr class="clone rcopy">
                    <td>
                        <input type="hidden" name="bulk_quality_product_specs_line_id[]" class="form-control input-sm bulk_quality_product_specs_line_id" value="{{ $value->quality_product_specs_line_id }}">
                        <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="{{ $key + 1 }}" readonly="readonly">
                    </td>
                    <td class="pdtdiv">
                      <input type="text" name="bulk_parameter[]" class="form-control input-sm bulk_parameter input_qty_width" value="{{ $value->parameter }}" required="required">
                    </td>
                    <td>
                        <select name="bulk_spec_criteria[]" class="form-control input-sm bulk_spec_criteria select2"   required="required">
						{!! $value->spec_criteria !!}
						</select>
                    </td>
					<td>
                        <input type="text" name="bulk_spec_value_from[]" class="form-control input-sm bulk_spec_value_from input_qty_width" value="{{ $value->spec_value_from }}" readonly >

                    </td>
					<td>
                        <input type="text" name="bulk_spec_value_to[]" class="form-control input-sm bulk_spec_value_to input_qty_width" value="{{ $value->spec_value_to }}"  required="required">

                    </td>
						<td>
                        <input type="text" name="bulk_uom[]" class="form-control input-sm bulk_uom input_qty_width" value="{{ $value->uom }}"  required="required">

                    </td>
                     <td>
                       
                        <select name='bulk_quality_type[]' tabindex="39" rows='5' class='select2 bulk_quality_type' id="bulk_quality_type" >
                            <option  value=" ">Please Select</option>
                            <option  value="Analytical" <?php if($value->quality_type=="Analytical"){ echo "selected"; }?>> Analytical</option>
                            <option  value="Microbial" <?php if($value->quality_type=="Microbial"){ echo "selected"; }?>>Microbial</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" name="bulk_comments[]" class="form-control input-sm bulk_comments input_qty_width" required value="{{ $value->comments }}">
                    </td>
                   
      <td class="text-center">
        <button type="button" class="btn btn-sm btn-danger remove-row">
          <i class="fas fa-minus-circle"></i>
        </button>
      </td>
                </tr>
                @endforeach
                <?php } if(count($linedata) < 1 ) { ?>
                    <tr class="clone rcopy">
                        <td>
                            <input type="hidden" name="bulk_quality_product_specs_line_id[]" class="form-control input-sm bulk_quality_product_specs_line_id" value="">

                            <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="1" readonly="readonly">
                        </td>
                        <td class="pdtdiv">
                          <input type="text" name="bulk_parameter[]" class="form-control input-sm bulk_parameter input_qty_width" value="" required="required">
                        </td>
                       
                        <td>
                        <select name="bulk_spec_criteria[]" class="form-control input-sm bulk_spec_criteria select2"   required="required">
						{!! $spec_criteria !!}
							</select>
                        </td>
					    <td>
                            <input type="text" name="bulk_spec_value_from[]" class="form-control input-sm bulk_spec_value_from input_qty_width">
                        </td>
					    <td>
                            <input type="text" name="bulk_spec_value_to[]" class="form-control input-sm bulk_spec_value_to input_qty_width"  required="required">

                        </td>
							<td>
                        <input type="text" name="bulk_uom[]" class="form-control input-sm bulk_uom input_qty_width" value=""  required="required">

                    </td>
                   <td>
                       
                        <select name='bulk_quality_type[]' tabindex="39" rows='5' class='select2 bulk_quality_type' id="bulk_quality_type" >
                             <option  value="" >Please Select</option>
                            <option  value="Analytical" >Analytical</option>
                            <option   value="Microbial" >Microbial</option>
                        </select>
                    </td>
                       
                        <td>
                            <input type="text" name="bulk_comments[]" class="form-control input-sm bulk_comments required input_qty_width">
                        </td>
                       
					  <td class="text-center">
						<button type="button" class="btn btn-sm btn-danger remove-row">
						  <i class="fas fa-minus-circle"></i>
						</button>
					  </td>
                    </tr>
                <?php } ?>
        </tbody>
    </table>
	
		  <div class="text-end">
    <button type="button" class="btn btn-success btn-sm add-row">
      <i class="fas fa-plus-circle"></i> Add Row
    </button>
  </div>
		
    <input type="hidden" name="enable-masterdetail" value="true">
</div>
<!--**********************-Linedata End****************-->
</div>
</div>




<div class="row mt-4 mb-3">
	<div class="col-lg-12 col-md-12">
		<div class="form-group text-center">
      		<button type="button" class="btn btn-success px-4 me-2 saveform" value="SAVE">Submit</button>
			  <a href="{{ url('product') }}" class='btn btn-danger px-4'>Cancel</a>
		</div>
	</div>
</div>

</div>

</div>
</div>

</form>

@endsection
@push('scripts')

<script>

// Add Row
$(document).on('click', '.add-row', function () {
    const $lastRow = $('.clone_lines_body tr:last');
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
    $('.clone_lines_body').append($newRow);

    // Reinitialize select2
    $newRow.find('select.select2').select2({ width: '100%' });

		// Update line numbers
		updateLineNumbers();
	});



  // Remove button
  $(document).on('click', '.remove-row', function () {
    const rowCount = $('.clone_lines_body tr').length;
    if (rowCount > 1) {
      $(this).closest('tr').remove();
      updateLineNumbers();
    } else {
      showCustomAlert("You Can't Delete Atleast One row should be There", "warning");
    }
  });

  // Renumber Line Nos
  function updateLineNumbers() {
    $('.clone_lines_body tr').each(function (index) {
      $(this).find('.bulk_line_no').val(index + 1);
    });
  }

	
$(document).ready(function()
{


	$('.bulk_parameter').keyup(function() {
        this.value = this.value.toUpperCase();
    });
	
	
	/* purpose:spec criteria validation*/
	
		$(document).on('change','.bulk_spec_criteria', function(){
			var index = $(this).closest('tr').index();
			var specval = $(".bulk_spec_criteria"+index+" option:selected").text();
			
			if(specval!='BETWEEN' && specval!='PASS/FAIL'){
			   $('.bulk_spec_value_from'+index).attr('readonly',true);
			   $('.bulk_spec_value_from'+index).attr('required',false);
				$('.bulk_spec_value_to'+index).attr('readonly',false);  
                $('.bulk_spec_value_to'+index).attr('required',true);     
			}else if(specval=='PASS/FAIL'){
				  $('.bulk_spec_value_from'+index).attr('readonly',true);		
				  $('.bulk_spec_value_to'+index).attr('readonly',true);		
				  $('.bulk_spec_value_from'+index).attr('required',false);
				   $('.bulk_spec_value_to'+index).attr('required',false);
			}
			  else 
			   {
				 $('.bulk_spec_value_from'+index).attr('readonly',false);
			     $('.bulk_spec_value_from'+index).prop('required',true);   
                 $('.bulk_spec_value_to'+index).attr('readonly',false);  
                 $('.bulk_spec_value_to'+index).attr('required',true);     
			   }
			
		});

   
	
    $(document).on('click','.saveform',function()
    {
        var btnval		= $(this).val(); 
      
        var url		= "{{ url('productspecsave') }}";
        var red_url		="{{ url('product') }}";
        var formdata	= $('#productspecform').serialize();
        var form = $('#productspecform');
       
       
        form.parsley().validate();
        var form = $('#productspecform');
        form.parsley().validate();

        if (form.parsley().isValid())
        {
          			var $btn = $(this);            
			          $btn.prop('disabled', true);
            $.post(url,formdata,function(data)
            {
                var status      = data.status;
                var msg     =    data.message;
                var id          = data.id;
                    showCustomAlert(msg,status);
                    setTimeout(function(){
                    window.location.href=red_url;
                    }, 1500);
                
            });
        }
           
            
    });
       

});



</script>


@endpush
