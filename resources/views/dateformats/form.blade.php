@extends('layouts.header')
@section('content')
  <h2 class="text-danger">Date Formats</h2>
  @include('layouts.breadcrumb')
  <?php error_reporting(0);?>


  <span class="ui_close_btn"><a class="collapse-close pull-right btn-danger"
      onclick='location.href="{{ url($return_url) }}"'></a></span>
  <span class="ui_close_btn"></span>

  <form action="" id="save" data-parsley-validate>
    {{ csrf_field() }}

    <div class="card shadow-lg rounded-4 border-0">
      <div class="card-header bg-success bg-gradient text-white fw-semibold"></div>
      <div class="card-body mt-2">
        <div class="row mb-3">
          <input type="hidden" class="form-control" id="date_formats_id" name="date_formats_id"
            value="{{ $dateformats->date_formats_id }}" readonly>

          <div class="col-md-6">
            <label for="php_format" class="form-label">
              <span class="text-danger">*</span> PHP Format
            </label>
            <input type="text" id="php_format" name="php_format" required class="form-control"
              value="{{ $dateformats->php_format }}">
          </div>

          <div class="col-md-6">
            <label for="javascript_format" class="form-label">
              <span class="text-danger">*</span> JavaScript Format
            </label>
            <input type="text" id="javascript_format" name="javascript_format" required class="form-control"
              value="{{ $dateformats->javascript_format }}">
          </div>
        </div>

        <div class="text-center mt-4">
          <button type="button" class="btn btn-success saveform px-4 me-2" value="save">Save</button>
          <a href="{{ url('dateformatssettings') }}" class="btn btn-secondary px-4 me-2">Cancel</a>
        </div>
      </div>
    </div>
  </form>

@endsection
@push('scripts')

  <script type="text/javascript">

    var dup_chk = true;
    function duplicate_validate() {
      var php_format = $(".php_format").val();
      var javascript_format = $(".javascript_format").val();
      var edit_id = $(".date_formats_id").val();
      $.ajax({
        cache: false,
        url: "{{URL::to('dateformatcheck') }}",
        type: 'GET',
        dataType: 'json',
        async: false,
        data: { php_format: php_format, javascript_format: javascript_format, edit_id: edit_id },
        success: function (response) {
          console.log(response);
          if (response == 1) {
            notyMsg('info', 'Already this Combination Exists for this Group');

            $(".php_format").val('');
            $(".javascript_format").val('');
            dup_chk = false;

          }
          else if (response == 0) {
            var html = "";

            dup_chk = true;

          }

        },
        error: function (xhr, resp, text) {
          console.log(xhr, resp, text);
        }
      });
    }
    /*end*/


    $(document).ready(function () {

      $(".saveform").click(function () {
        duplicate_validate();
        $('input[name="_token"]').val("{{csrf_token()}}");
        var form = $('#save');
        var data = form.serialize();

        form.parsley().validate();

        var url = "{{ URL::to('dateformatssettingssave')}}";

        if (dup_chk == true) {
          var $btn = $(this);
          $btn.prop('disabled', true);
          $.post(url, data, function (data1) {
            var status = data1.status;
            var msg = data1.message;

            showCustomAlert(msg,'success');
            var url = "{{ url('dateformatssettings') }}";
            setTimeout(function () {
              window.location.href = url;
            }, 1500);

          });
        }
        return false;

      });



    });
  </script>
@endpush