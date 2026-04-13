@extends('layouts.header')
@section('content')
<h3 class="text-danger">Machine Checklist</h3>
@include('layouts.breadcrumb')


<form autocomplete="off" action=" " id="machinechelistform" class="machinechelistform" data-parsley-validate  autocomplete="off" >
    {{ csrf_field() }}
    <input type="hidden" value="" name="savestatus" id="savestatus" />
   

<div class="card shadow-lg rounded-4 border-0">

<div class="card-body card-block">


  <div class="row">

    <div class="col-md-4">
            <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red">*</span>Department</label>
            <div class="col-md-8">        
                 <input type="hidden"  name="checklist_hrd_id" id="checklist_hrd_id" value="<?php echo $row->checklist_hrd_id; ?>" />
                  <select name='department_id' rows='5' class='form-control select2 department_id'  required>{!! $department_id !!}
                </select>
            </div>
             <div class="col-md-2 showinline refbtnhide">
	  </div>
        </div>
    </div>
	  
    <div class="col-md-4">
       <div class="form-group row">
         <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red">*</span>Machine Name</label>
            <div class="col-md-8">
              <select name='machine_id' rows='5' class='form-control select2 machine_id'  required>
              </select>
            </div>           
        </div>
    </div>
	  
    <div class="col-md-4">
     <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red">*</span>Frequency Name</label>
              <div class="col-md-8">
       <select name='frequency_id' rows='5' class='form-control select2 frequency_id'  required>
                </select>
           </div>
            
        </div>
    </div>

   </div>


<!------------------------- clone row start -------------------------------->
<div class="row mt-4">
  <div class="col-12 linetable">
<div class="table-responsive">
  <table class="table table-bordered company_table">
    <thead class="table-light">
      <tr>
        <th style="width: 80px;">Line No</th>
        <th> Checklist Name </th>
        <th style="width: 60px;"></th>
      </tr>
    </thead>
<tbody class="company_lines_body">
  @if(count($linedata) > 0)
    @foreach($linedata as $key => $value)
      <tr class="line-row">
		  <td><input type="text" class="form-control bulk_line_no" name="bulk_line_no[]" value="{{ $key + 1 }}" readonly></td>
      <td>
                        <input type="hidden" name="bulk_checklist_lines_id[]" class="form-control input-sm  bulk_checklist_lines_id" value="{{ $value->checklist_lines_id }}">
              
               
                        <select name="bulk_checklist_id[]" id="bulk_checklist_id" class="bulk_checklist_id  select2 parsley-validated" required >{!! $value->checklist_id !!}</select>
                    </td>
        <td class="text-center">
          <button type="button" class="btn btn-sm btn-danger remove-row">
            <i class="fas fa-minus-circle"></i>
          </button>
        </td>
      </tr>
    @endforeach
  @else
    <tr class="line-row">
		  <td> <input type="text" class="form-control bulk_line_no" name="bulk_line_no[]" value="1" readonly></td>
    <td>
        <input type="hidden" name="bulk_checklist_lines_id[]" class="form-control  input-sm bulk_checklist_lines_id" value="">

                            <select name="bulk_checklist_id[]" id="bulk_checklist_id" class="bulk_checklist_id select2 parsley-validated" required></select>
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
<!-------------------------Linedata End-------------------------------->




</div>

  <div class="text-center mt-4 mb-3">
    <button type="button" class="btn btn-success saveform px-4 me-2">Save</button>
    <a href="{{ url('pmchecksheet') }}" class="btn btn-secondary px-4">Cancel</a>
  </div>
</div>

</form>

 
@endsection
@push('scripts')

<script>
	
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
	
 function taxcheck(checklist,index)
  {
    var taxcount = 0;
    $('.clone').each(function (ind, v)
    {
      var val = $(".bulk_checklist_id" + ind).val();
      if($.trim(val)!="" && val!=null){
      if(index != ind){
      if(val == checklist){
        taxcount++;
      }
      }
      }
    });
    return taxcount;
  }
  

$(document).ready(function()
{
	
       
	$(document).on('change', '.department_id', function () {
		var id = $(this).val();
		if (id != '') {
			var url = "{{ URL::to('jcomboform1') }}?table=w_machine_hdr_t:machine_hdr_id:machine_code|machine_name&parent=and department_id=" + id + "&order_by=machine_hdr_id asc";

			$.ajax({
				url: url,
				type: 'GET',
	success: function (data) {
		// Parse JSON string if needed
		if (typeof data === "string") {
			try {
				data = JSON.parse(data);
			} catch (e) {
				console.error("Invalid JSON response:", data);
				return;
			}
		}

		$('.machine_id').html('<option value="">-- Select Machine --</option>');

		$.each(data, function (i, item) {
			let selected = item.val == "{{ $row->machine_id ?? '' }}" ? 'selected' : '';
			$('.machine_id').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
		});

		$('.machine_id').trigger('change.select2'); 
	}


			});
		}
	});


	$(document).on('change', '.machine_id', function () {
		var id = $(this).val();
		if (id != '') {
			var url = "{{ URL::to('jcomboforminv') }}?table=w_machine_lines_t:frequency_id:frequency_name&parent= machine_hdr_id=" + id + "&order_by=frequency_tbl.frequency_id asc";

			$.ajax({
				url: url,
				type: 'GET',

success: function (data) {
    // Parse JSON string if needed
    if (typeof data === "string") {
        try {
            data = JSON.parse(data);
        } catch (e) {
            console.error("Invalid JSON response:", data);
            return;
        }
    }

    $('.frequency_id').html('<option value="">-- Please Select --</option>');

    $.each(data, function (i, item) {
        let selected = item.val == "{{ $row->frequency_id ?? '' }}" ? 'selected' : '';
        $('.frequency_id').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
    });

    $('.frequency_id').trigger('change.select2'); 
}
			});
		}
	});


	
	
    var page ='<?php echo $pageMethod; ?>';
    if(page=="machinechecklistedit"){
      $('.department_id').trigger('change');
    }
    if(page == "createissue"){
        $('.pagemethod').attr('readonly',false);
        $('.pagemethod').css('pointer-events','auto');
        $('.activecol').show();
    }else{
        $('.pagemethod').attr('readonly',true);
        $('.pagemethod').css('pointer-events','none');
        $('.activecol').hide();

    }

  $('.read').css('pointer-events','none');
 $('.company').css('pointer-events','none');
  
  });
	
  // Save Form

$(document).on('click', '.saveform', function () {
    const form = $("#machinechelistform");
    let dup_chk = true; 

    form.parsley().validate(); 

    if (form.parsley().isValid() && dup_chk === true) {
        const formData = form.serialize(); 
		
        $.ajax({
            url: "{{ url('machinechklistsave') }}",
            type: "POST",
            data: formData,
            success: function (response) {
                if (response.status === "success") {
                    showCustomAlert('Saved successfully!','success');
                    setTimeout(() => {
                        window.location.href = "{{ url('pmchecksheet') }}";
                    }, 1500);
                } else {
                    showCustomAlert(response.message || 'Save failed. Please check your input.', 'error');
                }
            },
            error: function (xhr) {
                let errorMsg = 'Unexpected error occurred.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                showCustomAlert(errorMsg, 'error');
            }
        });
    } else {
        showCustomAlert("Please fill out all required fields correctly.", 'warning');
    }
});
</script>


@endpush
