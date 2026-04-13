@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Miss Punch Approval</h3>
    @include('layouts.breadcrumb')


    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-primary text-white rounded-top">
        </div>
        <div class="card-body">
            <form action="" id="leave" data-parsley-validate>
                <input type="hidden" name="edit_id" value="{{$edit_id}}" id="edit_id" />
                {{ csrf_field()}}

                <div class="row g-4">
                    <!-- Employee -->
                    <div class="col-md-4" style="pointer-events: none;">
                        <label for="employee_id" class="form-label"><span class="text-danger">*</span> Employee</label>
                        <select name="employee_id" id="employee_id" class="form-select select2">{!! $emp_id !!}</select>
                    </div>

                    <!-- Date -->
                    <div class="col-md-4" style="pointer-events: none;">
                        <label for="date" class="form-label"><span class="text-danger">*</span> Date</label>
                        <input class="form-control datepicker" id="date" name="date" type="text" value="{{$date}}" readonly>
                    </div>

                    <!-- punch type -->
                    <div class="col-md-4" style="pointer-events: none;">
                        <label for="in_time" class="form-label"><span class="text-danger">*</span> Request For</label>
                        <select name="misspunch" id="misspunch" class="form-control select2 misspunch" required>
                            {!! $misspunch !!}
                        </select>
                    </div>

                    <!-- Actual In Time -->
                    <div class="col-md-4 contribute" style="pointer-events: none;">
                        <label for="in_time" class="form-label"><span class="text-danger">*</span> Actual In Time</label>
                        <input type="text" id="in_time" name="in_time" class="form-control " value="{{$in_time}}" required
                            readonly>
                    </div>

                    <!-- Actual Out Time -->
                    <div class="col-md-4 contribute1" style="pointer-events: none;">
                        <label for="out_time" class="form-label"><span class="text-danger">*</span> Actual Out Time</label>
                        <input type="text" id="out_time" name="out_time" class="form-control " value="{{$out_time}}"
                            required readonly>
                    </div>

                    <!-- Reason -->
                    <div class="col-md-4">
                        <label for="reason" class="form-label"><span class="text-danger">*</span> Reason</label>
                        <input type="text" id="reason" name="reason" class="form-control" value="{{$reason}}" required
                            readonly>
                    </div>

                    <!-- Misspunch Status -->
                    <div class="col-md-4">
                        <label for="status" class="form-label"><span class="text-danger">*</span> Misspunch Status</label>
                        <select name="status" id="status" class="form-select select2" required>
                            <option value="APPROVED">APPROVED</option>
                            <option value="REJECTED">REJECTED</option>
                        </select>
                    </div>

                    <!-- Forwarded To -->
                    <div class="col-md-4">
                        <label for="forwarded_id" class="form-label"><span class="text-danger">*</span> Forwarded To</label>
                        <div class="d-flex">
                            <select id="forwarded_id" class="form-select select2" multiple required name="forwarded_id">
                                {!! $forwarded_id !!}
                            </select>
                        </div>
                    </div>
                </div>


                <!-- Buttons -->
                <div class="text-center mt-4">
                    <button type="button" class="btn btn-success save_form px-4 me-2" value="APPROVED">
                        <i class="fa fa-check"></i> Approve
                    </button>
                    <button type="button" class="btn btn-danger save_form px-4 me-2" value="REJECTED">
                        <i class="fa fa-times"></i> Reject
                    </button>
                    <a href="{{url('punchrequestapproval') }}"><button type="button" class="btn btn-secondary px-4 me-2"
                            id="delete">
                            <i class="fa fa-ban"></i> Cancel
                        </button></a>
                </div>
            </form>
        </div>
    </div>


@endsection
@push('scripts')


    <script>

        function applyMissPunchUI() {
            const v = +$('#misspunch').val();
            console.log(v);
            if (v === 279) {
                $('.contribute').show();
                $('.contribute1').hide();
            } else if (v === 280) {
                $('.contribute').hide();
                $('.contribute1').show();
            } else {
                // default
                $('.contribute, .contribute1').hide();
            }
        }

        $(function () {
            applyMissPunchUI();
        });


        // save	

        $(document).on('click', '.save_form', function () {
            var status = $(this).val();
            $('#status').val(status).change();
            var url = "{{URL::to('misssave')}}";
            var form = $('#leave');
            form.parsley().validate();
            var form = $('#leave');
            form.parsley().validate();

            var data = $('#leave').serialize();

            $.post(url, data, function (data1) {
                var $btn = $(this);
                $btn.prop('disabled', true);
                if (data1 == 1) {
                    showCustomAlert('Miss Punch  Details  ' + status + ' Successfully', 'success');
                    setTimeout(function () {
                        var url = "{{URL::to('punchrequestapproval')}}";
                        window.location.href = url;
                    }, 1500);
                }
                else {
                    showCustomAlert('Miss Punch  Details  ' + status + ' Successfully', 'success');
                    setTimeout(function () {
                        var url = "{{URL::to('punchrequestapproval')}}";
                        window.location.href = url;
                    }, 1500);
                }
            });
            // }
        });


    </script>

@endpush