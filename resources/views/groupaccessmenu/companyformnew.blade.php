@extends('layouts.header')
@section('content')

<h2 class="text-danger">Company Access</h2>
@include('layouts.breadcrumb')

<form id="companyaccess_form" data-parsley-validate>
  {{ csrf_field() }}

<div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">

      <div class="row mb-4 justify-content-center">
        <div class="col-md-6">
          <label class="form-label fw-bold"><span class="text-danger fw-bold">*</span> Company Name</label>
          <select name="company_name" class="form-select select2 company_name" required>
            {!! $company_name !!}
          </select>
          <input type="hidden" name="a_company_menu_access_id" value="{{ $a_company_menu_access_id }}">
        </div>
      </div>

      <div class="row mb-4">

          {!! $headhtml !!}
          {!! $sub_headhtml !!}
          {!! $sub_menuhtml !!}
          {!! $buttonhtml !!}
   
      </div>


      <div class="row mb-4 justify-content-center">
        <div class="col-md-6 text-center">
          <button type="button" class="btn btn-success saveform me-2">Submit</button>
          <button type="button" class="btn btn-secondary" onclick="location.href = '{{ url('companyaccess') }}'">Cancel</button>
        </div>
      </div>

    </div>
  </div>
</form>

<!-- Loader Overlay -->
<div id="formLoader" class="position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center bg-white bg-opacity-75" style="z-index: 9999; display: none !important;">
  <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
    <span class="visually-hidden">Loading...</span>
  </div>
</div>

@endsection
@push('scripts')

<script>
	
$(document).ready(function(){
$('.sub_menu_hide,.child_menu_hide,.button_menu_hides').hide();

        $(document).on('click','.headmenus',function(e){
            var id=$(this).attr('data-value');
            $('.sub_menu_hide').hide();
            $('#sub_menu'+id).show();
            return false;
        });
          $(document).on('click','.subheadmenus',function(e){
            var id=$(this).attr('data-value');
            $('.child_menu_hide').hide();
            $('#sub_head_menu'+id).show();
            return false;
        });
         $(document).on('click','.submenus',function(e){
            var id=$(this).attr('data-value');
            $('.button_menu_hides').hide();
            $('#button_names'+id).show();
            return false;
        });
        $(document).on('click','.check_head',function(e){
             var checkvalue= $(this).val();
            if($(this).is(":checked")){ 
              $('.sub_headmenu'+checkvalue).each(function(){
                   $(this).prop("checked", true);
              });
            }
            else{
                $('.sub_headmenu'+checkvalue).each(function(){
                  $(this).prop("checked", false);
              });
            }
    });
    
      $(document).on('click','.sub_headmenu0,.sub_headmenus,.sub_child_menus',function(e){
             var checkvalue= $(this).attr('myval');
            if($(this).is(":checked")){ 
              $('.sub_headmenu'+checkvalue).each(function(){
                   $(this).prop("checked", true);
                     var checkvalue1= $(this).attr('myval');
                     $('.sub_headmenu'+checkvalue1).each(function(){
                       $(this).prop("checked", true);
                        var checkvalue2= $(this).attr('myval');
                     $('.sub_headmenu'+checkvalue2).each(function(){
                       $(this).prop("checked", true);
                     });
                     });
              });
            }
            else{
                $('.sub_headmenu'+checkvalue).each(function(){
                  $(this).prop("checked", false);
                          var checkvalue1= $(this).attr('myval');
                   $('.sub_headmenu'+checkvalue1).each(function(){
                        $(this).prop("checked", false);
                           var checkvalue2= $(this).attr('myval');
                     $('.sub_headmenu'+checkvalue2).each(function(){
                        $(this).prop("checked", false);
                     });
                     });
              });
            }
    });
	});

    // Save Form

    $(document).on('click', '.saveform', function () {

         var $btn = $(this);            
			   $btn.prop('disabled', true);

        const form = $("#companyaccess_form");
        let dup_chk = true; 
        form.parsley().validate(); 
        if (form.parsley().isValid() && dup_chk === true) {
            const formData = form.serialize();
			    $("#formLoader").show();
            $.ajax({
                url: "{{ url('companyaccesssave') }}",
                type: "POST",
                data: formData,
                success: function (response) {
					$("#formLoader").hide(); 
                    if (response.status === "success") {
                        showCustomAlert(response.message || 'Saved successfully!', 'success');
                        setTimeout(() => {
                            window.location.href = "{{ url('companyaccess') }}";
                        }, 1500);
                    } else {
                        showCustomAlert(response.message || 'Save failed. Please check your input.', 'error');
                    }
                },
                error: function (xhr) {
					$("#formLoader").hide(); 
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
