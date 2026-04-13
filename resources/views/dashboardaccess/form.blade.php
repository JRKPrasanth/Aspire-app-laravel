@extends('layouts.header')
@section('content')
  <h2 class="text-danger">Dashboard Access</h2>
  @include('layouts.breadcrumb')

  <form id="dashboardaccess" data-parsley-validate>
    {{ csrf_field() }}

    <div class="card shadow-lg rounded-4 border-0">
      <div class="card-body card-block">

        <div class="row">
          <div class="col-md-offset-3 col-md-6">
            <div class="form-group row">
              <label for="inputIsValid" class="form-control-label col-md-4"><span class="req">*</span>User Name</label>
              <div class="col-md-6">
                <select name='user_id' rows='5' class='select2 form-control user_id' data-show-subtext="true"
                  data-live-search="true" required>
                  {!! $user_id  !!}
                </select>
              </div>
              <div class="col-md-2 showinline">
                <input type="hidden" name="a_dashboard_access_id" value="{{$a_dashboard_access_id}}">
              </div>
            </div>


          </div>


        </div>
        <div class="row mb-4">

          <?php echo $headhtml; ?>
          {!! $invhtml !!}
          {!! $saleshtml !!}
          {!! $ophtml !!}
          {!! $prdhtml !!}
          {!! $acchtml !!}
          {!! $levhtml !!}
          {!! $dashtml !!}

        </div>

      </div>

      <div class="row mb-4 justify-content-center">
        <div class="col-md-6 text-center">
          <button type="button" class="btn btn-success saveform me-2 px-4">Submit</button>
          <button type="button" class="btn btn-secondary px-4"
            onclick="location.href = '{{ url('dashboardaccess') }}'">Cancel</button>
        </div>
      </div>
    </div>

  </form>

@endsection
@push('scripts')

  <script>
    $(document).ready(function () {

      $(document).on('click', '.check_head', function (e) {
        var checkvalue = $(this).val();
        if ($(this).is(":checked")) {
          $('.head' + checkvalue).each(function () {
            $(this).prop("checked", true);
          });
        }
        else {
          $('.head' + checkvalue).each(function () {
            $(this).prop("checked", false);
          });
        }
      });
    });
    // save function	
    $(document).on('click', '.saveform', function () {
      const form = $("#dashboardaccess");
      let dup_chk = true;
      form.parsley().validate();
      if (form.parsley().isValid() && dup_chk === true) {
        const formData = form.serialize();
        var $btn = $(this);
        $btn.prop('disabled', true);
        $.ajax({
          url: "{{ url('dashboardaccesssave') }}",
          type: "POST",
          data: formData,
          success: function (response) {
            if (response.status === "success") {
              showCustomAlert(response.message || 'Saved successfully!','success');
              setTimeout(() => {
                window.location.href = "{{ url('dashboardaccess') }}";
              }, 1500);
            } else {
              showCustomAlert(response.message || 'Save failed. Please check your input.','error');
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