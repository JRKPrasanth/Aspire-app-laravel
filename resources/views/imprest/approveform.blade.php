@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Imprest Approval</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <form action="" id="imprestapprove" data-parsley-validate>
        {{ csrf_field() }}
        <input type="hidden" name="edit_id" value="{{ $edit_id }}" id="edit_id" />
        <input type="hidden" name="status" value="{{ $data->status }}" id="status" />

        <div class="row g-3">
          <!-- Imprest Number -->
          <div class="col-md-4" style="pointer-events:none;">
            <label class="form-label"><span class="text-danger">*</span> Imprest Number</label>
            <input type="text" name="imprest_number" class="form-control" value="{{ $data->imprest_number }}">
          </div>

          <!-- Employee -->
          <div class="col-md-4" style="pointer-events:none;">
            <label class="form-label"><span class="text-danger">*</span> Employee</label>
            <select name="employee_id" id="employee_id" class="form-select select2">
              {!! $employee !!}
            </select>
          </div>

          <!-- Date -->
          <div class="col-md-4" style="pointer-events:none;">
            <label class="form-label"><span class="text-danger">*</span> Date</label>
            <div class="input-group">
              <input type="text" id="imprest_date" name="imprest_date" class="form-control imprest_date"
                value="{{ $data->imprest_date }}" required>
            </div>
          </div>

          <!-- Amount -->
          <div class="col-md-4">
            <label class="form-label"><span class="text-danger">*</span> Amount</label>
            <input type="text" id="amount" name="amount" class="form-control" value="{{ $data->amount }}" required>
          </div>

          <!-- Reason -->
          <div class="col-md-4">
            <label class="form-label"><span class="text-danger">*</span> Reason</label>
            <input type="text" id="reason" name="reason" class="form-control" value="{{ $data->reason }}" required>
          </div>

          <!-- Active -->
          <div class="col-md-4">
            <label class="form-label"><span class="text-danger">*</span> Active</label>
            <select id="active" name="active" class="form-select select2" required>
              <option value="Yes" {{ $data->active == 'Yes' ? 'selected' : '' }}>Yes</option>
              <option value="No" {{ $data->active == 'No' ? 'selected' : '' }}>No</option>
            </select>
          </div>

          <!-- Reporting Manager -->
          <div class="col-md-4 none">
            <label class="form-label"><span class="text-danger">*</span> Reporting Manager</label>
            <div class="input-group">
              <select id="reporting_manager" name="reporting_manager" class="form-select select2" required>
                {!! $reporting !!}
              </select>
            </div>
          </div>
        </div>

        <div class="text-center mt-4">
          <button type="button" class="btn btn-success save_form px-4  me-2" value="APPROVE">
            Approve
          </button>
          <button type="button" class="btn btn-danger save_form px-4  me-2" value="REJECT">
            Reject
          </button>
          <a href="{{url('imprestapproval')}}"><button type="button" class="btn btn-secondary px-4" id="delete">
              Cancel
            </button></a>
        </div>
      </form>
    </div>
  </div>


@endsection
@push('scripts')

  <script>

    //save

    $(document).on('click', '.save_form', function () {
      var status = $(this).val();
      $('#status').val(status);
      var url = "{{URL::to('imprestapprovesave')}}";
      var form = $('#imprestapprove');
      form.parsley().validate();

      if (form.parsley().isValid()) {
        var $btn = $(this);
        $btn.prop('disabled', true);
        var data = $('#imprestapprove').serialize();
        $.post(url, data, function (data1) {

          showCustomAlert('Approved', 'success');
          setTimeout(function () {
            var url = "{{URL::to('imprestapproval')}}";
            window.location.href = url;
          }, 1500);


        });
      }
    });

  </script>

@endpush