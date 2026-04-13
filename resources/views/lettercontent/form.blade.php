@extends('layouts.header')
@section('content')
<h3 class="text-danger">Letter Content</h3>
@include('layouts.breadcrumb')

<style>
	.tox .tox-statusbar{
	display:none;
	}
	.tox .tox-tbtn svg {
		fill: #0071f3;
	}
</style>	

<div class="card shadow-lg rounded-4 border-0">
  <div class="card-body">
    <form action="" id="lettercontentForm" data-parsley-validate>
      <input type="hidden" name="edit_id" value="{{ $edit_id }}" id="edit_id" />
      {{ csrf_field() }}

      <div class="row g-3">
        <!-- Letter Type -->
        <div class="col-md-4">
          <div class="mb-3">
            <label class="form-label"><span class="text-danger">*</span> Letter Type</label>
            <select name="letter_type" id="letter_type" class="form-select select2" required data-show-subtext="true" data-live-search="true" required >
              {!! $letter_type !!}
            </select>
          </div>
        </div>

        <!-- Employee Type -->
        <div class="col-md-4">
          <div class="mb-3">
            <label class="form-label"><span class="text-danger">*</span> Employee Type</label>
            <select name="employee_type" id="employee_type" class="form-select select2" required data-show-subtext="true" data-live-search="true" required >
              {!! $employee_type !!}
            </select>
          </div>
        </div>

        <!-- Active -->
        <div class="col-md-4">
          <div class="mb-3">
            <label class="form-label">Active</label>
            <select name="active" id="active" class="form-select select2" required>
              <option value="Yes" {{ $active == 'Yes' ? 'selected' : '' }}>Yes</option>
              <option value="No" {{ $active == 'No' ? 'selected' : '' }}>No</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Body Content -->
      <div class="mb-4">
        <label class="form-label"><span class="text-danger">*</span> Body Content</label>
        <textarea class="form-control content" name="lettercontent" id="lettercontent" rows="8" required>{{ $body_content }}</textarea>
      </div>

      <!-- Tag Sections -->
      <div class="mb-4">
        <div class="row g-3">
          <!-- Offer Tags -->
          <div class="col-md-4 offer">
            <div class="card p-3 h-100">
              <h6>Offer Tags</h6>
              <div class="d-flex flex-wrap gap-1">
                <small>[company_name]</small>
                <small>[company_address]</small>
                <small>[logo]</small>
                <small>[position]</small>
                <small>[current_date]</small>
                <small>[employee_name]</small>
                <small>[gross_salary]</small>
                <small>[gross_text]</small>
                <small>[at date]</small>
                <small>[valid date]</small>
                <small>[HR name]</small>
                <small>[grade]</small>
                <small>[location]</small>
                <small>[id]</small>
                <small>[prefix]</small>
                <small>[da]</small>
              </div>
            </div>
          </div>

          <!-- Appointment Tags -->
          <div class="col-md-4 appointment">
            <div class="card p-3 h-100">
              <h6>Appointment Tags</h6>
              <div class="d-flex flex-wrap gap-1">
                <small>[current_date]</small>
                <small>[logo]</small>
                <small>[company_name]</small>
                <small>[location]</small>
                <small>[Director]</small>
                <small>[employee_name]</small>
                <small>[employee_address]</small>
                <small>[Date_of_joining]</small>
                <small>[gross_salary]</small>
                <small>[basic]</small>
                <small>[hra]</small>
                <small>[da]</small>
                <small>[gross_text]</small>
                <small>[prefix]</small>
                <small>[randid]</small>
                <small>[position]</small>
                <small>[grade]</small>
                <small>[loc]</small>
                <small>[offer]</small>
                <small>[state]</small>
              </div>
            </div>
          </div>

          <!-- Permission Tags -->
          <div class="col-md-4 permission">
            <div class="card p-3 h-100">
              <h6>Permission Tags</h6>
              <div class="d-flex flex-wrap gap-1">
                <small>[current_date]</small>
                <small>[logo]</small>
                <small>[company_name]</small>
                <small>[Director]</small>
                <small>[employee_name]</small>
                <small>[start_date_time]</small>
                <small>[end_date_time]</small>
                <small>[no_of_hrs]</small>
                <small>[forwarded_id]</small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Buttons -->
      <div class="text-center">
        <button type="button" class="btn btn-success save_form px-4 me-2">Save</button>
        <button type="button" class="btn btn-danger clear px-4 me-2" id="delete">Clear</button>
        <button type="button" class="btn btn-secondary px-4 me-2" onclick="location.href='{{ URL::to('lettercontent') }}'">Cancel</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<!-- TinyMCE -->
<script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/parsleyjs"></script>
<script>
	
$(document).ready(function () {
  // Hide sections initially
  $('.offer, .appointment, .permission').hide();

  // Initialize TinyMCE
tinymce.init({
  selector: 'textarea.content',
  height: 400,
  menubar: false,
  plugins: 'lists link image code fullscreen',
  toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | code fullscreen',
  content_style: "body { font-family:Helvetica,Arial,sans-serif; font-size:14px }",
  license_key: 'gpl'
});


  // Insert tags into editor
  $(document).on('click', 'small', function () {
    var text = $(this).html();
    tinymce.activeEditor.execCommand('mceInsertContent', false, text);
  });

  // Clear form
  $(document).on('click', '.clear', function () {
    $('#edit_id').val('');
    $('#letter_type').val('').trigger('change');
    $('#employee_type').val('').trigger('change');
    $('#active').val('Yes').trigger('change');
    tinymce.get("lettercontent").setContent('');
  });

  // Show tags based on letter type
  $('#letter_type').on('change', function () {
    var optionText = $("#letter_type option:selected").text();
    $('.offer, .appointment, .permission').hide();
    if (optionText == "Offer Letter") $('.offer').show();
    else if (optionText == "Appointment Letter") $('.appointment').show();
    else if (optionText == "Permission Slip") $('.permission').show();
  }).trigger('change');

  
  // Save form
$(document).on('click', '.save_form', function () {
  var form = $('#lettercontentForm');

  tinymce.triggerSave(); // sync content

  form.parsley().validate();

  if (!form.parsley().isValid()) {
    console.warn("Parsley failed");

    form.find(':input').each(function () {
      const instance = $(this).parsley();
      if (!instance.isValid()) {
        console.warn('Invalid:', this.name, instance.getErrorsMessages());
      }
    });
    return;
  }

  let editorContent = tinymce.get("lettercontent").getContent();

  if (editorContent.trim() === '' || editorContent.trim() === '<p><br></p>') {
    showCustomAlert('Please fill Body Content', 'warning');
    return;
  }

  let form_data = new FormData(form[0]);
  form_data.set("lettercontent", editorContent);

  $.ajax({
    url: "{{ url('lettercontentsave') }}",
    type: "POST",
    data: form_data,
    processData: false,
    contentType: false,
    success: function (data) {
      if (data == 1 || data == 2) {
        showCustomAlert(
          data == 1 ? 'Content Details Saved Successfully' : 'Content Details Updated Successfully',
          'success'
        );
        setTimeout(() => {
          window.location.href = "{{ URL::to('lettercontent') }}";
        }, 2000);
      } else {
        $(".alert-success").hide();
        $(".alert-danger").fadeIn(800);
      }
    },
    error: function () {
      $(".alert-success").hide();
      $(".alert-danger").fadeIn(800);
    }
  });
});



});
</script>
@endpush
