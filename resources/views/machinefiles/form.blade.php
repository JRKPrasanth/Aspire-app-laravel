@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Create Machine Files</h3>
    @include('layouts.breadcrumb')

    <div class="card shadow-lg rounded-4 border-0">
        <form method="post" action="" id="filemachine" data-parsley-validate enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" name="savestatus" id="savestatus" />
            <input type="hidden" name="machine_file_id" id="machine_file_id" value="{{ $row->machine_file_id }}" readonly>

            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">
                            <label for="department_id" class="form-label"><span class="text-danger">*</span> Department
                                Name</label>
                            <select name="department_id" id="department_id" class="form-select select2 department_id">
                                {!! $department_id !!}
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="machine_id" class="form-label"><span class="text-danger">*</span> Machine
                                Number</label>
                            <select name="machine_id" id="machine_id" class="form-select select2 machine_id">

                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="machine_name" class="form-label"><span class="text-danger">*</span> Machine
                                Name</label>
                            <select id="machine_name" class="form-select select2 machine_name">

                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="file_name" class="form-label"><span class="text-danger">*</span> File Name</label>
                            <input type="text" id="file_name" name="file_name" class="form-control"
                                value="{{ $row->file_name }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="upload_file" class="form-label"><span class="text-danger">*</span> Choose
                                File</label>
                            <input type="file" id="upload_file" name="upload_file" class="form-control" required>
                            <div id="fp" class="form-text"></div>
                        </div>

                    </div>

                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-success saveform px-4 me-2">Save</button>
                        <a href="{{ URL::to('filemachine') }}" class="btn btn-secondary px-4 me-2">Cancel</a>
                    </div>

                </div>
            </div>
        </form>
    </div>


@endsection
@push('scripts')

    <script>

        $(document).ready(function () {

            $(document).on('change', '.department_id', function () {

                var id = $(this).val();

                if (id != '') {
                    var url = "{{ URL::to('jcomboform1') }}?table=w_machine_hdr_t:machine_hdr_id:machine_name&parent=and department_id=" + id + "&order_by=machine_hdr_id asc";

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

                            $('.machine_name').html('<option value="">-- Select Machine --</option>');

                            $.each(data, function (i, item) {
                                let selected = item.val == "{{ $row->machine_name ?? '' }}" ? 'selected' : '';
                                $('.machine_name').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
                            });

                            $('.machine_name').trigger('change.select2');
                        }


                    });
                }
            });

            $(document).on('change', '.machine_name', function () {
                var id = $(this).val();
                if (id != '') {
                    var url = "{{ URL::to('jcomboforminv') }}?table=w_machine_hdr_t:machine_hdr_id:machine_code&parent= machine_hdr_id=" + id + "&order_by=w_machine_hdr_t.machine_hdr_id asc";

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
                                let selected = item.val == "{{ $row->machine_id ?? '' }}" ? 'selected' : '';
                                $('.machine_id').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
                            });

                            $('.machine_id').trigger('change.select2');
                        }
                    });
                }
            });

        });

        $(document).on('click', '.saveform', function () {
            $('#panel_add').trigger('click');
            var btnval = $(this).val();
            if (btnval == 'APPLYCHANGES')
                var savestatus = 'APPLY CHANGES';
            else if (btnval == 'SAVE' || btnval == 'SAVENEW')
                var savestatus = 'SAVE';
            var url = "{{ url('machinefilessave') }}";
            var create_url = "{{ URL::to('machinefilecreate') }}";
            var red_url = "{{ url('filemachine') }}";
            //  validationrule('Issuestatus');
            var form = $('#filemachine');
            form.parsley().validate();
            form.parsley().validate();
            if (form.parsley().isValid()) {

                var $btn = $(this);
                $btn.prop('disabled', true);
                var form_data = new FormData(document.getElementById('filemachine'));
                $.ajax({
                    url: url,
                    type: "POST",
                    data: form_data,
                    enctype: 'multipart/form-data',
                    processData: false,  // tell jQuery not to process the data
                    contentType: false,   // tell jQuery not to set contentType
                    async: true,
                    xhr: function () {
                        var xhr = $.ajaxSettings.xhr();
                        if (xhr.upload) {
                            xhr.upload.addEventListener('progress', function (event) {
                                var percent = 0;
                                var position = event.loaded || event.position;
                                var total = event.total;
                                if (event.lengthComputable) {
                                    percent = Math.ceil(position / total * 100);
                                }
                                //update progressbar
                            }, true);
                        }
                        return xhr;
                    }
                }).done(function (data, status) {
                    showCustomAlert('Saved successfully!', 'success');
                    setTimeout(function () {
                        window.location.href = red_url;
                    }, 1500);
                }).fail(function (data, status) {
                    var status = data.status;
                    var msg = data.message;
                    var id = data.id;
                    showCustomAlert(message, status);
                    settTimeout(function () {
                        ///    window.location.href=red_url;
                    }, 1000);
                });
            }
        });

    </script>

@endpush