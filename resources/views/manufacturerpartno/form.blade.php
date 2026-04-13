@extends('layouts.header')
@section('content')
<h3 class="text-danger">Manufacture Part No Details</h3>
@include('layouts.breadcrumb')



	<form action="" method="post" class="manufacturer" id="manufacturer" data-parsley-validate>
	<input type="hidden" value="" name="savestatus" id="savestatus" />
		{{ csrf_field()}}


	<div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white py-3">
    </div>

    <div class="card-body">
        <div class="row g-4">

            <!-- Product Group -->
            <div class="col-md-4 none">
                <label class="form-label fw-semibold">Product Group Name</label>
                <select name="product_group_id" class="form-select select2 product_group_id">
                    {!! $product_group_id !!}
                </select>
            </div>

            <!-- Created By -->
            <div class="col-md-4 none">
                <label class="form-label fw-semibold">Created By</label>
                <div style="pointer-events:none;">
                    <select name="created_by" id="created_by" class="form-select select2 created_by">
                        {!! $created_by !!}
                    </select>
                </div>
            </div>

            <!-- Product -->
            <div class="col-md-4 none">
                <label class="form-label fw-semibold">Product Name</label>
                <select name="product_id" class="form-select select2 product_id">
                    {!! $product_id !!}
                </select>
            </div>

            <!-- Remarks -->
            <div class="col-md-4 mb-4">
                <label class="form-label fw-semibold">Remarks</label>
                <input type="text" 
                       name="remarks" 
                       id="remarks" 
                       class="form-control remarks" 
                       value="{{ $mfgdatas['remarks'] }}"
                       placeholder="Enter remarks">

                <input type="hidden" name="edit_id" class="edit_id" value="{{ $edit }}">
                <input type="hidden" name="status" id="status" class="status" value="{{ $mfgdatas['status'] }}">
            </div>

        </div>

<!-------------------------Linedata -------------------------------->


<div id="preview-area" class="table-responsive" >
  <table class="table table-bordered clone_table">
    <thead class="table-light">
        <tr>

            <th >Line No</th>
            <th class="pdtdiv">Manufacturer Source </th>
            <th>Manufacturer Source Value </th>
            <th>Part No</th>
            <th>Part No Description</th>
            <th >&nbsp;</th>

        </tr>

    </thead>
    <tbody class="clone_lines_body">
        <?php if(count($linedata)>=1) { ?>
            @foreach($linedata as $key=>$value)
            <?php //dd($value);?>
                <tr class="cloneRow clone rcopy">
                    <td>
                        <input type="hidden" name="bulk_manufacturer_partno_id[]" class="form-control input-sm bulk_manufacturer_partno_id" value="{{ $value->manufacturer_partno_id }}">
           
                        <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="{{ $key + 1 }}" readonly="readonly" >
                    </td>
                    <td>
                        <select name="bulk_manufacturer_source[]" id="bulk_manufacturer_source" class="select2 bulk_manufacturer_source" required>
                            <option value="">Please select</option>
                            <option value="SUPPLIER" <?php if($value->manufacturer_source=='SUPPLIER'){ echo "selected"; }?> >SUPPLIER</option>
                            <option value="CUSTOMER" <?php if($value->manufacturer_source=='CUSTOMER'){ echo "selected"; }?>>CUSTOMER</option>
                        </select>
                    </td>
                    <td>
                        <select name="bulk_manufacturer_source_value_id[]" class="select2 bulk_manufacturer_source_value_id  " required="required">
                            {!! $value->manufacturer_source_value_id !!}</select>
                    </td>
                    <td>
                        <input type="text" name="bulk_part_no[]" class="form-control input-sm bulk_part_no input_qty_width" value="{{ $value->part_no }}" required>
                    </td>
                    <td>
                        <input type="text" name="bulk_part_no_description[]" class="form-control input-sm bulk_part_no_description" value="{{ $value->part_no_description }}">
                    </td>

					<td class="text-center">
					<button type="button" class="btn btn-sm btn-danger remove-row">
						<i class="fas fa-minus-circle"></i>
					</button>
					</td>
                </tr>
                @endforeach
                <?php } if(count($linedata) < 1 ) {  ?>
                    <tr class="cloneRow clone rcopy">
                        <td>
                            <input type="hidden" name="bulk_manufacturer_partno_id[]" class="form-control input-sm bulk_manufacturer_partno_id" value="">
                
                            <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="1" readonly="readonly" >
                        </td>
                        <td>
                            <select name="bulk_manufacturer_source[]" id="bulk_manufacturer_source" class="select2 bulk_manufacturer_source" required>
                                <option value=""> -- Select -- </option>
                                <option value="SUPPLIER"> SUPPLIER </option>
                                <option value="CUSTOMER"> CUSTOMER</option>
                            </select>
                        </td>
                        <td>
                            <select name="bulk_manufacturer_source_value_id[]" class="select2 bulk_manufacturer_source_value_id " required="required">
                                <option value="">-- please select --</option>
                            </select>
                        </td>

                        <td>
                            <input type="text" name="bulk_part_no[]" class="form-control input-sm bulk_part_no input_qty_width" value="" required>
                        </td>
                        <td>
                            <input type="text" name="bulk_part_no_description[]" class="form-control input-sm bulk_part_no_description" value="">
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
<?php if($edit==0){?>
  <div class="text-end">
    <button type="button" class="btn btn-success btn-sm add-row">
      <i class="fas fa-plus-circle"></i> Add Row
    </button>
  </div>
<?php } ?>

	</div>

<div class="row mt-4 mb-3">
	<div class="col-lg-12 col-md-12">
			<div class="form-group text-center">
				<input type="hidden" name="submit_type" class="submit_type" id="submit_type">
				
				<button type="button" id="save" class="btn btn-success px-4 me-2 saveform" value="SAVE">Save</button>

				  <a class='btn btn-danger px-4' onclick="location.href ='{{url('manufacturerpartno')}}'">Cancel</a>
			</div>
		</div>
	</div>

</div>

</div>

</form>




@endsection
@push('scripts')

	<script>

	$(document).ready(function(){
	/*Group Name Keyup Function For Uppercase*/
	$('.group_name').on('keyup',function(){
	this.value= this.value.toUpperCase();
	});
	/*End*/

$('.prdgroupdiv,.productdiv').css("pointer-events","none");

/*Save Function*/
		$(document).on('click','.saveform',function(){

		   var btnval		= $(this).val();

			if(btnval == 'APPLYCHANGES')
                var savestatus = 'APPLY CHANGES';
			else(btnval == 'SAVE')
                var savestatus = 'SAVE';

			 $('#savestatus').val(savestatus);
			 $('.submit_type').val("save");

			 var url		= "{{ URL::to('manufacturerpartnosave') }}";
			 validationrule('manufacturer');
			var red_url = "{{ URL::to('manufacturerpartno') }}";
			 var formdata	= $('#manufacturer').serialize();
			 var form = $('#manufacturer');


			 if(btnval != 'APPLYCHANGES')
              {
			     form.parsley().validate();
		          var form = $('#manufacturer');
		          form.parsley().validate();

				  if(form.parsley().isValid())
                 {
					var $btn = $(this);            
					$btn.prop('disabled', true);
				    $.post(url,formdata,function(data)
					  {
					      var status      = data.status;
						  var msg         = data.message;
						    var id          = data.id;
						var edit_url	= "{{ URL::to('manufacturerpartnoedit') }}/"+id;

						if(btnval =='SAVE')
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
						var edit_url	="{{ URL::to('manufacturerpartnoedit') }}/"+id;
								showCustomAlert(msg,status);
								setTimeout(function(){
								window.location.href=edit_url;
								}, 1500);

					});
				}

		});


$(document).on('change', '.bulk_manufacturer_source', function () {

    var $row = $(this).closest('tr');
    var source = $(this).val();
    var $target = $row.find('.bulk_manufacturer_source_value_id');

    var url = "{{ URL::to('jcomboform') }}";
    var params = {};

    if (source === "CUSTOMER") {
        params = {
            table: "m_customers_t:customer_id:customer_number|customer_name",
            order_by: "customer_name asc"
        };

    } else if (source === "SUPPLIER") {
        params = {
            table: "m_supplier_t:supplier_id:supplier_number|supplier_name",
            order_by: "supplier_name asc"
        };

    } else {
        $target.html('<option value="">-- please select --</option>');
        return;
    }

    $.ajax({
        url: url,
        method: "GET",
        data: params,
        success: function (response) {
            $target.html(response);
            $target.trigger("change"); // refresh select2
        }
    });

});


/* Manufacturer Source Value Function */
$(document).on('change', '.bulk_manufacturer_source_value_id', function () {

    var $row  = $(this).closest('tr');                       // current row
    var srcid = $(this).val();                               // selected source value
    var manufacturersource = $row.find('.bulk_manufacturer_source').val(); // SUPPLIER / CUSTOMER
    var productid = $('.product_id').val();                  // assuming global hidden input

    // ----- 1. Check duplicates in previous rows -----
    var isDuplicate = false;

    // loop rows in order, stop when we reach current row
    $('.clone_lines_body').find('tr.clone').each(function () {
        var $thisRow = $(this);

        // when we reach current row, break the loop
        if ($thisRow.is($row)) {
            return false; // break .each()
        }

        var prevSource      = $thisRow.find('.bulk_manufacturer_source').val();
        var prevSourceValue = $thisRow.find('.bulk_manufacturer_source_value_id').val();

        if (prevSource === manufacturersource && prevSourceValue === srcid && srcid !== '') {
            isDuplicate = true;
            return false; // break
        }
    });

    if (isDuplicate) {
        // reset current select
        $(this).val('').trigger('change.select2'); // or just .trigger('change') if you init select2 differently
        showCustomAlert('Product Name Already Selected', 'info');
        return;
    }

    // ----- 2. Server-side validation -----
    if (srcid !== '') {

        var url = "{{ URL::to('mfgsourcevalidate') }}";

        $.get(url, {
            manufacturersource: manufacturersource,
            srcid: srcid,
            productid: productid
        }, function (data) {

            // expecting something like [1, "message"] as response
            if (data[0] == 1) {
                showCustomAlert(data[1] + " already saved... Choose another " + data[1], "info");
                $row.find('.bulk_manufacturer_source_value_id')
                    .val('')
                    .trigger('change.select2');
            }

        }, 'json'); // assuming JSON; if not, remove this
    }

});

// Init select2 on page load
$(function () {
    $('.clone_lines_body').find('select.select2').select2({ width: '100%' });
});

// Add Row
$(document).on('click', '.add-row', function () {
    const $tbody   = $('.clone_lines_body');
    const $lastRow = $tbody.find('tr:last');

    // 1) Clone the last row (keep events = false)
    const $newRow = $lastRow.clone(false, false);

    // 2) Clean up cloned row's Select2 markup and values
    $newRow.find('select.select2').each(function () {
        // remove Select2 wrapper from cloned DOM, if any
        $(this).next('.select2').remove();

        // remove Select2-specific attributes/classes from the cloned element
        $(this)
            .removeClass('select2-hidden-accessible')
            .removeAttr('data-select2-id')
            .off(); // remove cloned events

        // clear the value
        $(this).val(null);
    });

    // 3) Clear other inputs in the cloned row
    $newRow.find('.bulk_company_line_id').val('');
    $newRow.find('.bulk_line_no').val('');   // will be set by updateLineNumbers()
    $newRow.find('.bulk_description').val('');

    // 4) (Optional but recommended) fix duplicate IDs in cloned row
    $newRow.find('[id]').each(function () {
        const newId = $(this).attr('id') + '_' + Date.now();
        $(this).attr('id', newId);
    });

    // 5) Append cloned row
    $tbody.append($newRow);

    // 6) Initialize Select2 only for the new row's selects
    $newRow.find('select.select2').select2({ width: '100%' });

    // 7) Update line numbers
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
	});



</script>
@endpush
