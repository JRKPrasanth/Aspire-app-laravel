@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Product Access</h3>
  @include('layouts.breadcrumb')


  <form id="productaccess" data-parsley-validate>
    {{ csrf_field() }}
    <div class="card shadow-lg rounded-4 border-0">
      <div class="card-body card-block">

        <div class="row">
          <div class="col-md-offset-3 col-md-6">

            <div class="form-group row">
              <label for="inputIsValid" class="form-control-label col-md-4">User Name</label>
              <div class="col-md-6">
                <select name='user_id' rows='5' class='select2 form-control user_id' data-show-subtext="true"
                  data-live-search="true" required>
                  {!! $user_id  !!}
                </select>
              </div>
              <div class="col-md-2 showinline">
                <input type="hidden" name="a_product_menu_access_id" value="{{$a_product_menu_access_id}}">
              </div>
            </div>
          </div>
          <div class="col-md-offset-3 col-md-6">
            <div class="form-group row">
              <label for="inputIsValid" class="form-control-label col-md-4">Process Name</label>
              <div class="col-md-6">
                <select name='process_id' rows='5' class='select2 form-control process_id' data-show-subtext="true"
                  data-live-search="true" required>
                  {!! $process_id  !!}
                </select>
              </div>
              <div class="col-md-2 showinline">
              </div>
            </div>
          </div>

        </div>
        <div class="row mb-4">
          <?php echo $headhtml; ?>
          {!! $sub_headhtml !!}
          {!! $sub_menuhtml !!}

        </div>


        <div class="row mb-4 justify-content-center">
          <div class="col-md-6 text-center">
            <button type="button" class="btn btn-success saveform me-2 px-4">Submit</button>
            <button type="button" class="btn btn-secondary px-4"
              onclick="location.href = '{{ url('productaccess') }}'">Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </form>

  <!-- Loader Overlay -->
  <div id="formLoader"
    class="position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center bg-white bg-opacity-75"
    style="z-index: 9999; display: none !important;">
    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>
@endsection
@push('scripts')
  <script>
    $(document).ready(function () {


      $('.sub_menu_hide,.child_menu_hide,.button_menu_hides').hide();

      $(document).on('click', '.headmenus', function (e) {
        var id = $(this).attr('data-value');
        $('.sub_menu_hide').hide();
        $('#sub_menu' + id).show();
        return false;
      });
      $(document).on('click', '.subheadmenus', function (e) {
        var id = $(this).attr('data-value');
        $('.child_menu_hide').hide();
        $('#sub_head_menu' + id).show();
        return false;
      });
      $(document).on('click', '.submenus', function (e) {
        var id = $(this).attr('data-value');
        $('.button_menu_hides').hide();
        $('#button_names' + id).show();
        return false;
      });
      $(document).on('click', '.check_head', function (e) {
        var checkvalue = $(this).val();
        if ($(this).is(":checked")) {
          $('.sub_headmenu' + checkvalue).each(function () {
            $(this).prop("checked", "checked");
          });
        }
        else {
          $('.sub_headmenu' + checkvalue).each(function () {
            $(this).removeProp('checked');
          });
        }
      });

      $(document).on('click', '.sub_headmenu0', function (e) {
        var checkvalue = $(this).attr('myval');
        if ($(this).is(":checked")) {
          $('.sub_1headmenu' + checkvalue).each(function () {
            $(this).prop("checked", "checked");
            var checkvalue1 = $(this).attr('myval');
            $('.sub_2headmenu' + checkvalue1).each(function () {
              $(this).prop("checked", "checked");
              var checkvalue2 = $(this).attr('myval');

            });
          });
        }
        else {
          $('.sub_1headmenu' + checkvalue).each(function () {
            $(this).removeProp('checked');
            var checkvalue1 = $(this).attr('myval');
            $('.sub_2headmenu' + checkvalue1).each(function () {
              $(this).removeProp('checked');
              var checkvalue2 = $(this).attr('myval');

            });
          });
        }
      });
      $(document).on('click', '.sub_headmenus', function (e) {
        var checkvalue = $(this).attr('myval');
        if ($(this).is(":checked")) {
          $('.sub_2headmenu' + checkvalue).each(function () {
            $(this).prop("checked", "checked");

          });

        }
        else {
          $('.sub_2headmenu' + checkvalue).each(function () {
            $(this).removeProp('checked');

          });
        }
      });

    });

    // Save Form

    $(document).on('click', '.saveform', function () {
      const form = $("#productaccess");
      let dup_chk = true;
      form.parsley().validate();
      if (form.parsley().isValid() && dup_chk === true) {
        var $btn = $(this);
        $btn.prop('disabled', true);
        const formData = form.serialize();
        var $btn = $(this);
        $btn.prop('disabled', true);
        $("#formLoader").show();
        $.ajax({
          url: "{{ url('productaccesssave') }}",
          type: "POST",
          data: formData,
          success: function (response) {
            $("#formLoader").hide();
            if (response.status === "success") {
              showCustomAlert(response.message || 'Saved successfully!','success');
              setTimeout(() => {
                window.location.href = "{{ url('productaccess') }}";
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