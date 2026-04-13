@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Announcement</h3>
    @include('layouts.breadcrumb')

    <form action="" id="announcement_form" class="announcement_form needs-validation" enctype="multipart/form-data"
        method="POST">
        {{ csrf_field() }}

        <div class="card shadow-sm rounded-4 border-0">
            <div class="card-header bg-primary text-white fw-bold">
                Announcement
            </div>
            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="ann_name" class="form-label">Announcement Name</label>
                        <input type="hidden" name="id" value="{{ $row->id }}">
                        <input type="text" class="form-control" id="ann_name" name="ann_name"
                            placeholder="Announcement Name" value="{{ $row->ann_name }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="strat_date" class="form-label">Valid From</label>
                        <input class="form-control ann_date" id="start_date" name="start_date"
                            value="{{ $row->start_date }}">
                    </div>

                    <div class="col-md-6">
                        <label for="end_date" class="form-label">Valid To</label>
                        <input class="form-control ann_date" id="end_date" name="end_date" value="{{ $row->end_date }}">
                    </div>

                    <div class="col-md-6">
                        <label for="photo" class="form-label">Attachment</label>
                        <input type="file" class="form-control attachment" id="photo" name="photo">
                        @if($row->attachment)
                            <small>Current: {{ $row->attachment }}</small>
                        @endif
                    </div>

                    <div class="col-md-6 none">
                        <label for="created_by" class="form-label">Created By</label>
                        <select class="form-select select2" id="created_by" name="created_by" readonly>
                            {!! $created_by !!}
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="active" class="form-label">Active</label>
                        <select class="form-select select2" id="active" name="active" required>
                            <option value="">--Please Select--</option>
                            <option value="Yes" {{ $row->active == 'Yes' ? 'selected' : '' }}>Yes</option>
                            <option value="No" {{ $row->active == 'No' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn btn-success px-4 me-2 saveform">Save</button>
                        <a class="btn btn-secondary px-4" href="{{ url($pageModule) }}">Cancel</a>
                    </div>

                </div>
            </div>
        </div>
    </form>



@endsection
@push('scripts')


    <script>


        <?php if ($pageMethod == "createannouncement") { ?>
        $('.attachment').attr('required', true);
        <?php } ?>

        //  form save

        $(document).ready(function () {
            $(document).on('click', '.saveform', function (e) {
                e.preventDefault();

                var btnval = $(this).val();
                $('#savestatus').val(btnval);

                var form = $('#announcement_form');
                form.parsley().validate();

                if (form.parsley().isValid()) {
                    var saveurl = "{{ url('announcementsave') }}";
                    var red_url = "{{ url('announcement') }}";

                    // Use FormData for file upload
                    var formData = new FormData(form[0]);
                    var $btn = $(this);
                    $btn.prop('disabled', true);
                    $.ajax({
                        url: saveurl,
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            var status = data.status;
                            var msg = data.message;
                            showCustomAlert(msg, status);

                            if (status === 'success') {
                                setTimeout(function () {
                                    window.location.href = red_url;
                                }, 1500);
                            }
                        },
                        error: function (xhr) {
                            showCustomAlert("Upload failed. Try again.", "error");
                        }
                    });
                }
            });
        });



        $(document).ready(function () {

            var dateToday = new Date();

            // Calculate the date 3 months from today
            var maxDate = new Date();
            maxDate.setMonth(maxDate.getMonth() + 3);

            $(".ann_date").datepicker({
                changeMonth: true,
                dateFormat: "yy-mm-dd",
                changeYear: true,
                minDate: dateToday,
                maxDate: maxDate,
                onClose: function () {
                    $(this).parsley().validate();
                }
            }).attr('readonly', 'readonly');

        });

        // ---END---

    </script>
@endpush