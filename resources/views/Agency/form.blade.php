@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Agency</h3>


    <form method="post" action="" id="agency" data-parsley-validate>
        <input type="hidden" name="edit_id" id="edit_id" value="">
        {{ csrf_field() }}

        <div class="card shadow-lg rounded-4 border-0">
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <input type="hidden" name="agency_id" id="agency_id" class="form-control"
                            value="{{ $row->agency_id }}" readonly>

                        <div class="mb-3 row">
                            <label for="agency_name" class="col-md-4 col-form-label"><span
                                    class="text-danger">*</span>Agency Name</label>
                            <div class="col-md-8">
                                <input type="text" id="agency_name" name="agency_name" class="form-control agency_name"
                                    value="{{ $row->agency_name }}" required>
                                <span class="btn btn-danger dup_name d-none mt-2"></span>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="mobile_no" class="col-md-4 col-form-label"><span class="text-danger">*</span>Mobile
                                No</label>
                            <div class="col-md-8">
                                <input type="text" id="mobile_no" name="mobile_no" maxlength="10" class="form-control"
                                    value="{{ $row->mobile_no }}" required>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="country" class="col-md-4 col-form-label">Country</label>
                            <div class="col-md-8">
                                <select name="country" id="country" class="form-select select2 country">
                                    {!! $country_id !!}
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="active" class="col-md-4 col-form-label">Active</label>
                            <div class="col-md-8">
                                <select name="active" id="active" class="form-select select2">
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3 row">
                            <label for="email" class="col-md-4 col-form-label"><span
                                    class="text-danger">*</span>Email</label>
                            <div class="col-md-8">
                                <input type="email" name="email" id="email" class="form-control" value="{{ $row->email }}"
                                    required>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="address" class="col-md-4 col-form-label">Address</label>
                            <div class="col-md-8">
                                <input type="text" name="address" id="address" class="form-control"
                                    value="{{ $row->address }}">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="state" class="col-md-4 col-form-label">State</label>
                            <div class="col-md-8">
                                <select name="state" id="state" class="form-select select2 state">
                                    <option value="">Please select</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="city" class="col-md-4 col-form-label">City</label>
                            <div class="col-md-8">
                                <select name="city" id="city" class="form-select select2 city">
                                    <option value="">Please select</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="row mt-4">
                    <div class="col text-center">
                        <button type="button" class="btn btn-success saveform px-4 me-2" value="SAVE">Save</button>
                        <a href="{{ URL::to('agency') }}" class="btn btn-secondary px-4 me-2">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
@push('scripts')

    <script>

        $(document).on('change', '.country', function () {
            let country_id = $(this).val();
            $('.state').html('<option value="">-- Loading States --</option>');

            if (country_id) {
                $.ajax({
                    url: "{{ url('jcomboformlogin') }}?table=m_states_t:state_id:state_name&parent=country_id=" + country_id + "&order_by=state_name",
                    success: function (data) {
                        $('.state').html('<option value="">-- Select State --</option>');
                        $.each(data, function (i, item) {
                            let selected = item.val == "{{ $row->state ?? '' }}" ? 'selected' : '';
                            $('.state').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
                        });
                    }
                });
            }
        });

        $(document).on('change', '.state', function () {
            let state_id = $(this).val();
            $('.city').html('<option value="">-- Loading Cities --</option>');

            if (state_id) {
                $.ajax({
                    url: "{{ url('jcomboformlogin') }}?table=m_cities_t:city_id:city_name&parent=state_id=" + state_id + "&order_by=city_name",
                    success: function (data) {
                        $('.city').html('<option value="">-- Select City --</option>');
                        $.each(data, function (i, item) {
                            let selected = item.val == "{{ $row->city_id ?? '' }}" ? 'selected' : '';
                            $('.city').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
                        });
                    }
                });
            }
        });

        // Convert agency name to uppercase as user types
        $("#agency_name").on('keyup', function () {
            $(this).val($(this).val().toUpperCase());
        });

        // Allow only numeric input for mobile number
        $(document).on('keypress', '#mobile_no', function (ev) {
            var regex = new RegExp("^[0-9]+$");
            var str = String.fromCharCode(ev.which);
            if (regex.test(str)) {
                return true;
            }
            ev.preventDefault();
            return false;
        });


        function duplicate_validate() {
            let agency_name = $("#agency_name").val();
            let edit_id = $("#agency_id").val();
            let result = true;

            $.ajax({
                url: "{{URL::to('agencynamechk')}}",
                type: 'GET',
                dataType: 'json',
                data: { agency_name: agency_name, edit_id: edit_id },
                async: false, // sync call (not best practice, but OK here)
                success: function (response) {
                    if (parseInt(response) === 1) {
                        $('.dup_name').html('Agency Name: ' + agency_name + ' already exists').removeClass('d-none');
                        $("#agency_name").val('');
                        result = false;
                    } else {
                        $('.dup_name').addClass('d-none');
                    }
                },
                error: function (xhr, status, error) {
                    console.log("Duplicate check error:", error);
                    result = false;
                }
            });

            return result;
        }


        // Save Form

        $(document).on('click', '.saveform', function () {
            const form = $("#agency");
            let dup_chk = true;

            form.parsley().validate();

            if (form.parsley().isValid() && dup_chk === true) {

                var $btn = $(this);
                $btn.prop('disabled', true);

                const formData = form.serialize();

                let isDuplicate = duplicate_validate();

                if (!isDuplicate) {
                    showCustomAlert("Duplicate agency name found. Please enter a different name.", 'warning');
                    return;
                }

                $.ajax({
                    url: "{{ url('agencysave') }}",
                    type: "POST",
                    data: formData,
                    success: function (response) {
                        if (response.status === "success") {
                            showCustomAlert('Saved successfully!', 'success');
                            setTimeout(() => {
                                window.location.href = "{{ url('agency') }}";
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

    </script>
@endpush