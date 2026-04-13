@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Bank Cheque</h3>
    @include('layouts.breadcrumb')




    <form method="post" action="" id="bankcheque" data-parsley-validate>
        <input type="hidden" value="" name="savestatus" id="savestatus" />
        {{ csrf_field() }}


     <div class="card shadow-lg rounded-4 border-0">

            <div class="card-body card-block">


                <div class="row g-3">
                    <!-- Bank Name -->
                    <div class="col-md-4 none">
                        <label for="bank_id" class="form-label">Bank Name</label>
                        <input type="hidden" id="bank_cheque_hdr_id" name="bank_cheque_hdr_id"
                            value="{{ $bank_cheque_hdr_id }}">
                        <select name="bank_id" id="bank_id" class="form-select select2 bank_id">
                            {!! $bank_id !!}
                        </select>
                    </div>

                    <!-- Bank Branch -->
                    <div class="col-md-4 none">
                        <label for="bank_branch_id" class="form-label">Bank Branch Name</label>
                        <select name="bank_branch_id" id="bank_branch_id" class="form-select select2 bank_branch_id">
                            {!! $bank_branch_id !!}
                        </select>
                    </div>

                    <!-- Account Number -->
                    <div class="col-md-4 none">
                        <label for="account_number" class="form-label">Account Number</label>
                        <select name="account_number" id="account_number" class="form-select select2 account_number">
                            {!! $account_number !!}
                        </select>
                    </div>

                    <!-- Created By -->
                    <div class="col-md-4 none">
                        <label for="created_by" class="form-label">Created By</label>
                        <select name="created_by" id="created_by" class="form-select select2 created_by">
                            {!! $created_by !!}
                        </select>
                    </div>
                </div>



                <!-------------------------Linedata -------------------------------->
                <div class="row mt-4">
                    <div class="col-md-12">

                        <div id="preview-area" class="table-responsive">
                            <table class="table table-bordered clone_table">

                                <thead class="table-light">

                                    <th>Cheque Starting No</th>
                                    <th>Cheque Ending No</th>
                                    <th>Cheque Book No</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Cheque Status</th>
                                    <th></th>

                                </thead>

                                <tbody class="clone_lines_body">
                                    <?php if (count($linedata) >= 1) { ?>
                                    @foreach($linedata as $key => $value)
                                                                <tr class="rcopy clone">
                                                                    <td>
                                                                        <input type="hidden" name="bulk_bank_cheque_line_id[]"
                                                                            class="form-control input-sm bulk_bank_cheque_line_id"
                                                                            value="{{ $value->bank_cheque_line_id }}">

                                                                        <input type="text" name="bulk_cheque_from_no[]"
                                                                            class="form-control input-sm bulk_cheque_from_no"
                                                                            value="{{ $value->cheque_from_no }}" required="required">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="bulk_cheque_to_no[]"
                                                                            class="form-control input-sm bulk_cheque_to_no"
                                                                            value="{{ $value->cheque_to_no }}" required="required">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="bulk_cheque_book_no[]"
                                                                            class="form-control input-sm bulk_cheque_book_no"
                                                                            value="{{ $value->cheque_book_no }}" required="required">
                                                                    </td>

                                                                    <td>
                                                                        <input type="text" name="bulk_start_date[]"
                                                                            class="form-control input-sm bulk_start_date start_date"
                                                                            value="{{ $value->start_date }}">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="bulk_end_date[]"
                                                                            class="form-control input-sm bulk_end_date " value="{{ $value->end_date }}">
                                                                    </td>
                                                                    <td>
                                                                        <select name='cheque_status[]' rows='5'
                                                                            class='form-control cheque_status select2' data-show-subtext="true"
                                                                            data-live-search="true" required>
                                                                            <option value="">--Please Select--</option>
                                                                            <option <?php        if ($value->cheque_status == "ACTIVE") {
                                            echo "selected";
                                        } else {
                                            echo "";
                                        } ?> value="ACTIVE">ACTIVE</option>
                                                                            <option <?php        if ($value->cheque_status == "INACTIVE") {
                                            echo "selected";
                                        } else {
                                            echo "";
                                        } ?> value="INACTIVE">INACTIVE</option>
                                                                            <option <?php        if ($value->cheque_status == "CANCELLED") {
                                            echo "selected";
                                        } else {
                                            echo "";
                                        } ?> value="CANCELLED">CANCELLED</option>
                                                                        </select>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <button type="button" class="btn btn-sm btn-danger remove-row">
                                                                            <i class="fas fa-minus-circle"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                    @endforeach
                                    <?php }
    if (count($linedata) < 1) { ?>
                                    <tr class="rcopy clone">
                                        <td>
                                            <input type="hidden" name="bulk_bank_cheque_line_id[]"
                                                class="form-control input-sm bulk_bank_cheque_line_id" value="">
                                            <input type="text" name="bulk_cheque_from_no[]"
                                                class="form-control input-sm bulk_cheque_from_no" value=""
                                                required="required">
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_cheque_to_no[]"
                                                class="form-control input-sm bulk_cheque_to_no" value=""
                                                required="required">
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_cheque_book_no[]"
                                                class="form-control input-sm bulk_cheque_book_no" value=""
                                                required="required">
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_start_date[]"
                                                class="form-control input-sm bulk_start_date start_date" value="">
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_end_date[]"
                                                class="form-control input-sm bulk_end_date" value="">
                                        </td>
                                        <td>
                                            <select name='cheque_status[]' rows='5'
                                                class='form-control cheque_status select2' data-show-subtext="true"
                                                data-live-search="true" required>
                                                <option value="">--Please Select--</option>
                                                <option value="ACTIVE">ACTIVE</option>
                                                <option value="INACTIVE">INACTIVE</option>
                                                <option value="CANCELLED">CANCELLED</option>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-danger remove-row">
                                                <i class="fas fa-minus-circle"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <div class="text-end">
                                <button type="button" class="btn btn-success btn-sm add-row">
                                    <i class="fas fa-plus-circle"></i> Add Row
                                </button>
                            </div>
                            <input type="hidden" name="enable-masterdetail" value="true">
                        </div>
                    </div>
                </div>

                <div class="row mt-4 mb-3">
                    <div class="col-lg-12 col-md-12">
                        <div class="form-group text-center">
                            <button type="button" class="btn btn-success px-4 me-2 saveform" value="SAVE">Save</button>
                            <a href="{{ url('bankcheque') }}" class='btn btn-secondary px-4 '>Cancel</a>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </form>



@endsection
@push('scripts')


    <script>

        $(document).ready(function () {



            $('#savestatus').val('');
            $(document).on('click', '.saveform', function () {
                var btnval = $(this).val();
                $('#savestatus').val(savestatus);

                var url = "{{ url('bankchequesave') }}";
                var create_url = "{{ url('bankchequecreate') }}";
                var red_url = "{{ url('bankcheque') }}";
                var form = $('#bankcheque');

                form.parsley().validate();
                var form = $('#bankcheque');
                form.parsley().validate();

                if (form.parsley().isValid()) {

                    var $btn = $(this);            
		          	$btn.prop('disabled', true);
            
                    var formdata = $('#bankcheque').serialize();

                    $.post(url, formdata, function (data) {
                        var status = data.status;
                        var msg = data.message;
                        var id = data.id;
                        if (btnval != 'SAVE' && btnval != 'DRAFT') {
                            showCustomAlert(msg, status);
                            setTimeout(function () {
                                window.location.href = create_url;
                            }, 1500);
                        } else {
                            showCustomAlert(msg, status);
                            setTimeout(function () {
                                window.location.href = red_url;
                            }, 1500);
                        }
                    });
                }

            });

            // Add Row
            $(document).on('click', '.add-row', function () {
                const $lastRow = $('.clone_lines_body tr:last');
                const $newRow = $lastRow.clone(false, false); // clone without events or data

                // Clear all input and select values in the cloned row
                $newRow.find('input').val('');
                $newRow.find('select').val('').trigger('change');

                // Remove any Select2 artifacts before reinitializing
                $newRow.find('select.select2').each(function () {
                    if ($.fn.select2 && $(this).hasClass("select2-hidden-accessible")) {
                        $(this).select2('destroy');
                    }
                    $(this).removeAttr('data-select2-id');
                    $(this).next('.select2').remove(); // remove the select2 container
                });

                // Append the cleaned-up cloned row
                $('.clone_lines_body').append($newRow);

                // Reinitialize select2
                $newRow.find('select.select2').select2({ width: '100%' });

                // Update line numbers
                updateLineNumbers();
            });



            // Remove button
            $(document).on('click', '.remove-row', function () {
                const rowCount = $('.clone_lines_body tr').length;
                if (rowCount > 1) {
                    $(this).closest('tr').remove();
                    updateLineNumbers();
                } else {
                    showCustomAlert("You Can't Delete Atleast One row should be There", "warning");
                }
            });

            // Renumber Line Nos
            function updateLineNumbers() {
                $('.clone_lines_body tr').each(function (index) {
                    $(this).find('.bulk_line_no').val(index + 1);
                });
            }

        });

        $(document).on("focus", ".bulk_end_date", function () {

            $(this).datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: "yy-mm-dd",
                minDate: 0,
                maxDate: +1095,
                showAnim: "slideDown",
                yearRange: "-25:+0",

            });
        });

    </script>

@endpush