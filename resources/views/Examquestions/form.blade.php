@extends('layouts.header')
@section('content')
<h3 class="text-danger">Exam Questions</h3>
@include('layouts.breadcrumb')


<div class="card shadow-lg rounded-4 border-0">
<div class="card-body">
<form method="post" action="" id="examquestions" class="examquestions"  enctype="multipart/form-data">
	<input type="hidden" value="" name="save_status" id="save_status" />
	 {{ csrf_field()}}

  <div class="row">
    <div class="col-md-12">


      <!--*******************************- Body content start here ****************************-->
      <div class="row">
        <div class="col-md-12">
          <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-2"><span style="color:red;">*</span>Topic</label>
            <div class="col-md-4 topic">
            <input class="form-control exam_questions_hdr_id" id="exam_questions_hdr_id" name="exam_questions_hdr_id" size="16" type="hidden" value="{{ $row->exam_questions_hdr_id }}" readonly>
            <input class="form-control reference_id" id="reference_id" name="reference_id" size="16" type="hidden" value="{{ $row->reference_id }}" readonly>
			<select name='topic_id' rows='5' class='select2 topic_id' id="topic_id" required>
			   {!! $topic_id !!}
			</select>
           </div>
          </div>
          </div>
        </div>
    </div>
  </div>

      <!--*****************  End  *********** -->

<!--****************Linedata ********************-->
<div class="row mt-4">
  <div class="col-12 linetable">
<div class="table-responsive">
  <table class="table table-bordered company_table">
    <thead class="table-light">
      <tr>
        <th>Line No</th>
        <th >Question Type</th>
        <th >Question</th>
        <th >Option 1</th>
        <th >Option 2</th>
        <th >Option 3</th>
        <th >Option 4</th>
        <th >Answer</th>
        <th >Remarks</th>
        <th style="width: 60px;"></th>
      </tr>
    </thead>
<tbody class="company_lines_body">
  @if(count($linedata) > 0)
    @foreach($linedata as $key => $value)
      <tr class="line-row">

<td><input type="hidden" name="bulk_exam_questions_line_id[]" class="form-control input-sm bulk_exam_questions_line_id" value="{{ $value->exam_questions_line_id }}" ><input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="{{ $key + 1 }}" readonly="readonly"></td>

<td class="emp">
    <select  name="bulk_qtype[]" class="select2 input-sm bulk_qtype" id="bulk_qtype" required >
        <option value=""> -- Select Option -- </option>
        <option value="options" <?php if($value->qtype=='options'){echo 'selected';}?>>Options</option>
        <option value="text" <?php if($value->qtype=='text'){echo 'selected';}?>>Text</option>
    </select>

</td>
<td class="emp">
    <input type="text" name="bulk_question[]" id="bulk_question" value="{{$value->question}}" class="input-sm form-control bulk_question">

</td>
<td class="emp opt">
    <input type="text" name="bulk_option1[]" id="bulk_option1" value="{{$value->option1}}" class="input-sm form-control bulk_option1" <?php if($value->qtype=='text'){echo "style='display:none;'";}?>>

</td>
<td class="emp opt">
    <input type="text" name="bulk_option2[]" id="bulk_option2" value="{{$value->option2}}" class="input-sm form-control bulk_option2" <?php if($value->qtype=='text'){echo "style='display:none;'";}?>>

</td>
<td class="emp opt">
    <input type="text" name="bulk_option3[]" id="bulk_option3" value="{{$value->option3}}" class="input-sm form-control bulk_option3" <?php if($value->qtype=='text'){echo "style='display:none;'";}?>>

</td>
<td class="emp opt">
    <input type="text" name="bulk_option4[]" id="bulk_option4" value="{{$value->option4}}" class="input-sm form-control bulk_option4" <?php if($value->qtype=='text'){echo "style='display:none;'";}?>>

</td>
<td class="emp opt">
    <div class="bulk_answer_div" <?php if($value->qtype=='text'){echo "style='display:none;'";}?>>
        <select  name="bulk_answer[]" class="select2 input-sm bulk_answer" id="bulk_answer" >
            <option value=""> -- Select Option -- </option>
            <option value="option1" <?php if($value->answer=='option1'){echo 'selected';}?>>Option 1</option>
            <option value="option2" <?php if($value->answer=='option2'){echo 'selected';}?>>Option 2</option>
            <option value="option3" <?php if($value->answer=='option3'){echo 'selected';}?>>Option 3</option>
            <option value="option4" <?php if($value->answer=='option4'){echo 'selected';}?>>Option 4</option>
        </select>
    </div>
    <div class="bulk_answer_text_div" <?php if($value->qtype=='options'){echo "style='display:none;'";}?>>
        <textarea  name="bulk_text_answer[]" class="from-control input-sm bulk_text_answer" id="bulk_text_answer">{{$value->text_answer}}</textarea>
    </div>
</td>
<td class="emp">
    <input type="text" name="bulk_remarks[]" id="bulk_remarks" value="{{$value->remarks}}" class="input-sm form-control bulk_remarks">

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

<td><input type="hidden" name="bulk_exam_questions_line_id[]" class="form-control input-sm bulk_exam_questions_line_id" value=""><input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="1" readonly="readonly"></td>

<td class="emp">
    <select  name="bulk_qtype[]" class="select2 input-sm bulk_qtype" id="bulk_qtype" required >
        <option value=""> -- Select Option -- </option>
        <option value="options">Options</option>
        <option value="text">Text</option>
    </select>

</td>
<td class="emp">
  <input type="text" name="bulk_question[]" id="bulk_question" value="" class="input-sm form-control bulk_question" required>
</td>
<td class="emp">
  <input type="text" name="bulk_option1[]" id="bulk_option1" value="" class="input-sm form-control bulk_option1" required>
</td>
<td class="emp">
  <input type="text" name="bulk_option2[]" id="bulk_option2" value="" class="input-sm form-control bulk_option2" required>
</td>
<td class="emp">
  <input type="text" name="bulk_option3[]" id="bulk_option3" value="" class="input-sm form-control bulk_option3" required>
</td>
<td class="emp">
  <input type="text" name="bulk_option4[]" id="bulk_option4" value="" class="input-sm form-control bulk_option4" required>
</td>
<td class="emp">
    <div class="bulk_answer_div">
        <select  name="bulk_answer[]" class="select2 input-sm bulk_answer" id="bulk_answer" required >
            <option value=""> -- Select Option -- </option>
            <option value="option1" >Option 1</option>
            <option value="option2" >Option 2</option>
            <option value="option3" >Option 3</option>
            <option value="option4" >Option 4</option>
        </select>    
    </div>
    <div class="bulk_answer_text_div" style="display:none">
        <textarea  name="bulk_text_answer[]" class="from-control input-sm bulk_text_answer" id="bulk_text_answer"></textarea>
    </div>

</td>
<td class="emp">
  <input type="text" name="bulk_remarks[]" id="bulk_remarks" value="" class="input-sm form-control bulk_remarks">
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
    <button type="button" class="btn btn-success saveform px-4 me-2">Save</button>
    <a href="{{ url($pageModule) }}" class="btn btn-secondary px-4 me-2">Cancel</a>
  </div>
</form>
</div>
</div>


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

	
// Save Form

$(document).on('click', '.saveform', function () {
    const form = $("#examquestions");
    let dup_chk = true; // Declare duplicate check variable

    form.parsley().validate(); // Validate form using Parsley

    if (form.parsley().isValid() && dup_chk === true) {

      var $btn = $(this);            
			$btn.prop('disabled', true);

        const formData = form.serialize(); // Serialize form data

        $.ajax({
            url: "{{ url('examquestionssave') }}",
            type: "POST",
            data: formData,
            success: function (response) {
                if (response.status === "success") {
                    showCustomAlert('Saved successfully!', 'success');
                    setTimeout(() => {
                        window.location.href = "{{ url('examquestions') }}";
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
	
         	$('.trainertext').hide();
	$(".trainer_type").change(function(){
	    var type=$(this).val();
	    if(type=="Internal"){
	        	$('.trainertext').hide();
	        		$('.trainer_name').attr('required',true);
	        			$('.trainer_name1').attr('required',false);
	        				$('.trainer_mail').attr('required',false);
			$(".trainer_name").jCombo("{{ URL::to('jcomboform?table=hr_employee_t:employee_id:first_name|last_name')}}",
		{selected_value:""});
	    }else{
	        	$('.trainersel').hide();
	        		$('.trainertext').show();
	        		$('.trainer_name1').attr('required',true);
	        		$('.trainer_mail').attr('required',true);
	        		
	    }	
	});
	



	$(document).on('change', '.bulk_qtype', function () {
	    
		var index=$(this).closest('tr').index();
		var qtype=$(this).val();
		var count = 0;
// 		alert(qtype);
		if(qtype=='text'){
		    $('.bulk_option1'+index).hide();
		    $('.bulk_option2'+index).hide();
		    $('.bulk_option3'+index).hide();
		    $('.bulk_option4'+index).hide();
		    $('.bulk_answer_div'+index).hide();
		    $('.bulk_answer_text_div'+index).show();
		    
		    $('.bulk_option1'+index).removeAttr("required");
		    $('.bulk_option2'+index).removeAttr("required");
		    $('.bulk_option3'+index).removeAttr("required");
		    $('.bulk_option4'+index).removeAttr("required");
		    $('.bulk_answer'+index).removeAttr("required");
		    $('.bulk_text_answer'+index).attr("required","required");
			
		}else{
		    
		    $('.bulk_option1'+index).show();
		    $('.bulk_option2'+index).show();
		    $('.bulk_option3'+index).show();
		    $('.bulk_option4'+index).show();
		    $('.bulk_answer_div'+index).show();
		    $('.bulk_answer_text_div'+index).hide();
		    
		    $('.bulk_option1'+index).attr("required","required");
		    $('.bulk_option2'+index).attr("required","required");
		    $('.bulk_option3'+index).attr("required","required");
		    $('.bulk_option4'+index).attr("required","required");
		    $('.bulk_answer'+index).attr("required","required");
		    $('.bulk_text_answer'+index).removeAttr("required");
		}
          
        //   $('.bulk_answer_div'+index).html(html);                 
	});

</script>

@endpush
