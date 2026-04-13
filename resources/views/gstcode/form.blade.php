@extends('layouts.header')
@section('content')
<h3 class="text-danger">GST Code</h3>
@include('layouts.breadcrumb')


<form method="post" action="" id="gstcode" data-parsley-validate>
    <input type="hidden" value="" name="savestatus" id="savestatus" />

    {{ csrf_field() }}


    <div class="card shadow-lg rounded-4 border-0">

        <div class="card-body card-block headerdiv1">

            <div class="col-md-12">
                <div class="row g-3">
                    <!-- Classification Code -->
                    <div class="col-md-4">
                        <label for="classification_code" class="form-label">
                            <span class="text-danger">*</span> Classification Code
                        </label>
                        <input type="hidden" class="form-control gst_code_hdr_id" id="gst_code_hdr_id"
                            name="gst_code_hdr_id" value="{{ $row->gst_code_hdr_id }}" readonly>
                        <input type="text" id="classification_code" name="classification_code"
                            class="form-control classification_code" value="{{ $row->classification_code }}" required
                            tabindex="1">
                        <span class="btn btn-danger dup_name mt-1" style="display:none;"></span>
                    </div>

                    <!-- Classification Name -->
                    <div class="col-md-4">
                        <label for="classification_name" class="form-label">
                            <span class="text-danger">*</span> Classification Name
                        </label>
                        <select name="classification_name" id="classification_name"
                            class="form-select select2 classification_name" required tabindex="2">
                            <option value="">--Select--</option>
                            <option value="HSN" {{ $row->classification_name == "HSN" ? "selected" : "" }}>HSN</option>
                            <option value="SAC" {{ $row->classification_name == "SAC" ? "selected" : "" }}>SAC</option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div class="col-md-4">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" id="description" name="description" class="form-control description"
                            value="{{ $row->description }}" tabindex="3">
                    </div>

                    <!-- Active -->
                    <div class="col-md-4">
                        <label for="active" class="form-label">Active</label>
                        <select name="active" id="active" class="form-select select2" tabindex="4">
                            <option value="Yes" {{ $row->active == "Yes" ? "selected" : "" }}>Yes</option>
                            <option value="No" {{ $row->active == "No" ? "selected" : "" }}>No</option>
                        </select>
                    </div>

                    <!-- Created By -->
                    <div class="col-md-4 none">
                        <label for="created_by" class="form-label">Created By</label>
                        <select name="created_by" id="created_by" class="form-select select2 created_by" tabindex="5">
                            {!! $created_by !!}
                        </select>
                    </div>
                </div>
            </div>




            <!------------------------- clone row start -------------------------------->
            <div class="row mt-4">
                <div class="col-md-12">

                    <div id="preview-area" class="table-responsive">
                        <table class="table table-bordered clone_table">
                            <thead class="thead table-light">

                                <th>
                                    Tax Group
                                </th>
                                <th>
                                    Tax Location Type
                                </th>
                                <th>
                                    Start Date
                                </th>
                                <th>
                                    End Date
                                </th>
                                <th>
                                    Active
                                </th>
                                <th></th>

                            </thead>
                            <tbody class="clone_lines_body">

                                <?php if (count($linedata) >= 1) { ?>
                                    @foreach($linedata as $key=>$value)

                                    <tr class="rcopy clone">

                                        <td class="pdtdiv sel2">
                                            <input type="hidden" name="bulk_gst_code_line_id[]"
                                                class="form-control input-sm bulk_gst_code_line_id"
                                                value="{{ $value->gst_code_line_id }}">
                                            <select name="bulk_tax_group_id[]" id="bulk_tax_group_id"
                                                class="bulk_tax_group_id  select2 parsley-validated" required>{!!
                                                $value->tax_group_id !!}</select>
                                        </td>
                                        <td class="sel2">
                                            <select name='bulk_tax_location_type[]' rows='5' id="bulk_tax_location_type"
                                                class='bulk_tax_location_type select2' required>{!!
                                                $value->tax_location_type !!}</select>
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_start_date[]"
                                                class="form-control bulkstart_date" value="{{$value->start_date}}"
                                                required>
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_end_date[]" class="form-control bulkstart_date"
                                                value="{{$value->end_date}}" required>
                                        </td>
                                        <td>
                                            <select name="bulk_active[]" class="select2 bulk_active " id="bulk_active">
                                                <option value="Yes" <?php if ($value->active == 'Yes') {
                                                    echo "selected";
                                                } ?>>
                                                    Yes</option>
                                                <option value="No" <?php if ($value->active == 'No') {
                                                    echo "selected";
                                                } ?>>No
                                                </option>
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



                                        <td class="pdtdiv sel2">
                                            <input type="hidden" name="bulk_gst_code_line_id[]"
                                                class="form-control input-sm bulk_gst_code_line_id" value="">
                                            <select name="bulk_tax_group_id[]" id="bulk_tax_group_id"
                                                class="bulk_tax_group_id select2 parsley-validated" required>{!!
                                                $tax_group_id !!}</select>
                                        </td>
                                        <td class="sel2">
                                            <select name='bulk_tax_location_type[]' rows='5' id="bulk_tax_location_type"
                                                class='bulk_tax_location_type select2'
                                                required>{!!$tax_location_type!!}</select>
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_start_date[]"
                                                class="form-control bulkstart_date " value="" required>
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_end_date[]" class="form-control bulkstart_date "
                                                value="" required>
                                        </td>
                                        <td>
                                            <select name="bulk_active[]" class="select2 bulk_active " id="bulk_active">
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
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
                        <a href="{{ url('gstcode') }}" class="btn btn-secondary px-4 me-2">Cancel</a>
                    </div>
                </div>
            </div>

        </div>

        <input type="hidden" class="pdtindex" value="" />

    </div>
</form>



@endsection
@push('scripts')



<script>
    /***************To check product Already Selected ***********/
    function taxcheck(tax_group, index) {
        var taxcount = 0;
        $('.clone').each(function (ind, v) {
            var val = $(".bulk_tax_group_id" + ind).val();
            if ($.trim(val) != "" && val != null) {
                if (index != ind) {
                    if (val == tax_group) {
                        taxcount++;
                    }
                }
            }
        });
        return taxcount;
    }

    var dup_chk = true;
    function duplicate_validate() {
        var classification_code = $(".classification_code").val();
        var edit_id = $("#gst_code_hdr_id").val();

        $.ajax({
            cache: false,
            url: "{{URL::to('gstcodecheckname/')}}", //this is your uri
            type: 'GET',
            dataType: 'json',
            async: false,
            data: { classification_code: classification_code, edit_id: edit_id },
            success: function (response) {
                console.log(response);
                if (response == 1) {
                    $('.dup_name').html('Classification Code:' + classification_code + ' Already Exists');
                    $('.dup_name').show();
                    $(".classification_code").val('');
                    dup_chk = false;

                }
                else if (response == 0) {
                    var html = "";
                    $('.dup_name').hide();
                    dup_chk = true;

                }

            },
            error: function (xhr, resp, text) {
                console.log(xhr, resp, text);
            }
        });
    }

    $(document).ready(function () {
        $(document).on('keypress', '.classification_code', function (ev) {
            var regex = new RegExp("^[0-9.]+$");
            var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
            if (regex.test(str)) {
                return true;
            }
            ev.preventDefault();
            return false;
        });

        $('.classification_code').bind("cut copy paste", function (e) {
            e.preventDefault();
        });

        /**********Up/down/left/right arrow navigation start*******/
        $('input').keyup(function (e) {
            if (e.which == 39) { // right arrow
                $(this).closest('td').next().find('input').focus();

            } else if (e.which == 37) { // left arrow
                $(this).closest('td').prev().find('input').focus();

            } else if (e.which == 40) { // down arrow
                $(this).closest('tr').next().find('td:eq(' + $(this).closest('td').index() + ')').find('input').focus();

            } else if (e.which == 38) { // up arrow
                $(this).closest('tr').prev().find('td:eq(' + $(this).closest('td').index() + ')').find('input').focus();
            }
        });

        $(document).on('change', '.bulk_tax_group_id', function () {
            var tax_group = $(this).val();
            var index = ($(this).closest('tr').index());
            var taxcount = taxcheck(tax_group, index);

            if (taxcount <= 0) {

            }
            else {
                var msg = $(".bulk_tax_group_id" + index + ' option:selected').text();
                var message = '<span style="color:#fdff65">' + msg + '</span>' + ' Tax Group Already Selected';
                showCustomAlert(message, 'info');
                $('.bulk_tax_group_id' + index).val('').change();
            }
        });

        var data = "{{\Session::get('j_date_format')}}";


        $('#savestatus').val('');
        $(document).on('click', '.saveform', function () {
            var btnval = $(this).val();
            $('#savestatus').val(savestatus);

            var url = "{{ url('gstcodesave') }}";
            var create_url = "{{ url('gstcodecreate') }}";
            var red_url = "{{ url('gstcode') }}";
            var form = $('#gstcode');
            form.parsley().validate();
            var form = $('#gstcode');
            form.parsley().validate();
            duplicate_validate();
            if (form.parsley().isValid()) {

                var formdata = $('#gstcode').serialize();
                $.post(url, formdata, function (data) {
                    var status = data.status;
                    var msg = data.message;
                    var id = data.id;
                    if (btnval != 'SAVE' && btnval != 'DRAFT') {
                        showCustomAlert(msg, status);
                        setTimeout(function () {
                            window.location.href = create_url;
                        }, 1500);
                    }
                    else {
                        showCustomAlert(msg, status);
                        setTimeout(function () {
                            window.location.href = red_url;
                        }, 1500);
                    }
                });
            }


        });
        /**********Up/down/left/right arrow navigation start*******/
        $('input').keyup(function (e) {
            if (e.which == 39) { // right arrow
                $(this).closest('td').next().find('input').focus();

            } else if (e.which == 37) { // left arrow
                $(this).closest('td').prev().find('input').focus();

            } else if (e.which == 40) { // down arrow
                $(this).closest('tr').next().find('td:eq(' + $(this).closest('td').index() + ')').find('input').focus();

            } else if (e.which == 38) { // up arrow
                $(this).closest('tr').prev().find('td:eq(' + $(this).closest('td').index() + ')').find('input').focus();
            }
        });



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

    $(document).on("focus", ".bulkstart_date", function () {

        $(this).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            minDate: 0, 
            showAnim: "slideDown",
            yearRange: "-25:+0",

        });
    });
</script>

@endpush