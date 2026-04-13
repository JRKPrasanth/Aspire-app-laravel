@extends('layouts.header')
@section('content')
<h3 class="text-danger">Tax Group</h3>
@include('layouts.breadcrumb')



<form method="post" action="" id="taxgroup" data-parsley-validate>
    <input type="hidden" value="" name="savestatus" id="savestatus" /> {{ csrf_field() }}

    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body card-block headerdiv1">


            <div class="col-md-12">
                <div class="row g-4">

                    <!-- Tax Group Name -->
                    <div class="col-md-4">
                        <label class="form-label">
                            <span class="text-danger">*</span> Tax Group Name
                        </label>
                        <input type="hidden" id="tax_group_id" name="tax_group_id" value="{{ $row->tax_group_id }}"
                            readonly>
                        <input type="text" id="tax_group_name" name="tax_group_name" class="form-control tax_group_name"
                            value="{{ $row->tax_group_name }}" required tabindex="1">
                        <span class="badge bg-danger mt-2 dup_name" style="display:none;"></span>
                    </div>

                    <!-- Tax Percentage -->
                    <div class="col-md-4">
                        <label class="form-label">Tax Percentage (%)</label>
                        <input type="text" id="display_name" name="display_name" class="form-control display_name"
                            value="{{ $row->display_name }}" tabindex="2">
                    </div>

                    <!-- Active -->
                    <div class="col-md-4">
                        <label class="form-label">Active</label>
                        <select name="active" class="form-select active select2" tabindex="3">
                            <option value="Yes" <?php if ($row->active == 'Yes')
                                echo "selected"; ?>>Yes</option>
                            <option value="No" <?php if ($row->active == 'No')
                                echo "selected"; ?>>No</option>
                        </select>
                    </div>

                    <!-- Created By (Disabled) -->
                    <div class="col-md-4 none">
                        <label class="form-label">Created By</label>
                        <select name="created_by" id="created_by" class="form-select select2 created_by" tabindex="4">
                            {!! $created_by !!}
                        </select>
                    </div>

                </div>
            </div>



            <div class="row mt-4">
                <div class="col-md-12">
                    <div id="preview-area" class="table-responsive">
                        <table class="table table-bordered clone_table">
                            <thead class="thead table-light">
                                <th>Line No</th>
                                <th>Tax Category</th>
                                <th>Tax Slab</th>
                                <th>Input Account Code</th>
                                <th>Output Account Code</th>
                                <th>Active</th>
                                <th></th>

                            </thead>
                            <tbody class="clone_lines_body">
                                <?php if (count($linedata) >= 1) { ?>
                                    @foreach($linedata as $key=>$value)
                                    <tr class="rcopy clone">
                                        <td>
                                            <input type="hidden" name="bulk_tax_group_line_id[]"
                                                class="form-control input-sm bulk_tax_group_line_id"
                                                value="{{ $value->tax_group_line_id }}">

                                            <input type="text" name="bulk_line_no[]"
                                                class="form-control input-sm bulk_line_no" value="{{ $value->line_no }}">
                                        </td>

                                        <td>
                                            <select name="bulk_tax_category[]" id="bulk_tax_category"
                                                class="bulk_tax_category select2 parsley-validated" required="required">{!!
                                                $value->tax_category !!}</select>
                                        </td>
                                        <td>
                                            <select name='bulk_tax_code_name[]' id="bulk_tax_code_name" rows='5'
                                                class='bulk_tax_code_name select2' required>{!! $value->tax_code_name
                                                !!}</select>
                                        </td>

                                        <td>
                                            <select name='bulk_input_tax_account_id[]' id="bulk_input_tax_account_id"
                                                rows='5' class='bulk_input_tax_account_id select2' required>{!!
                                                $value->input_tax_account_id !!}</select>
                                        </td>
                                        <td>
                                            <select name='bulk_output_tax_account_id[]' id="bulk_output_tax_account_id"
                                                rows='5' class='bulk_output_tax_account_id select2' required>{!!
                                                $value->output_tax_account_id !!}</select>
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

                                        <td>
                                            <input type="hidden" name="bulk_tax_group_line_id[]"
                                                class="form-control input-sm bulk_tax_group_line_id" value="">

                                            <input type="text" name="bulk_line_no[]"
                                                class="form-control input-sm bulk_line_no" value="">
                                        </td>
                                        <td>
                                            <select name='bulk_tax_category[]' rows='5' id="bulk_tax_category"
                                                class='bulk_tax_category select2' required>{!!$tax_category!!}</select>
                                        </td>
                                        <td>
                                            <select name='bulk_tax_code_name[]' rows='5' id="bulk_tax_code_name"
                                                class='bulk_tax_code_name select2' required>{!!$tax_code_name!!}</select>

                                        </td>

                                        <td>
                                            <select name='bulk_input_tax_account_id[]' id="bulk_input_tax_account_id"
                                                rows='5' class='bulk_input_tax_account_id select2' required>{!!
                                                $input_tax_account_id !!}</select>
                                        </td>
                                        <td>
                                            <select name='bulk_output_tax_account_id[]' id="bulk_output_tax_account_id"
                                                rows='5' class='bulk_output_tax_account_id select2' required>{!!
                                                $output_tax_account_id !!}</select>
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
                        <a href="{{ url('taxgroup') }}" class='btn btn-secondary px-4'>Cancel</a>
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

    function taxcheck(tax_category, index) {
        var taxcount = 0;
        $('.clone').each(function (ind, v) {
            var val = $(".bulk_tax_category" + ind).val();
            if ($.trim(val) != "" && val != null) {
                if (index != ind) {
                    if (val == tax_category) {
                        taxcount++;
                    }
                }
            }
        });
        return taxcount;
    }


    var dup_chk = true;
    function duplicate_validate() {
        var tax_group_name = $(".tax_group_name").val();
        var edit_id = $("#tax_group_id").val();
        $.ajax({
            cache: false,
            url: "{{URL::to('taxgroupcheckname/')}}", //this is your uri
            type: 'GET',
            dataType: 'json',
            async: false,
            data: { tax_group_name: tax_group_name, edit_id: edit_id },
            success: function (response) {
                if (response == 1) {
                    $('.dup_name').html('Tax Group:' + tax_group_name + ' Already Exists');
                    $('.dup_name').show();
                    $(".subinventory_name").val('');
                    dup_chk = false;
                }
                else if (response == 0) {
                    var html = "";
                    $('.dup_name').hide();
                    dup_chk = true;
                    $(".ajaxLoading").show();
                }
            },
            error: function (xhr, resp, text) {
                console.log(xhr, resp, text);
            }
        });
    }

    $(document).ready(function () {

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



        $(document).on('change', '.bulk_tax_category', function () {
            var tax_category = $(this).val();
            var index = ($(this).closest('tr').index());
            var taxcount = taxcheck(tax_category, index);
            if (taxcount <= 0) {

            }
            else {
                var msg = $(".bulk_tax_category" + index + ' option:selected').text();
                var message = '<span style="color:#fdff65">' + msg + '</span>' + ' Tax Category Already Selected';
                showCustomAlert(message, 'info');
                $('.bulk_tax_category' + index).val('').change();

            }


        });


        $(document).on('keypress', '.display_name', function (ev) {
            var regex = new RegExp("^[0-9.]+$");
            var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
            if (regex.test(str)) {
                return true;
            }
            ev.preventDefault();
            return false;
        });


        $('.display_name').bind("cut copy paste", function (e) {
            e.preventDefault();
        });




        $('#savestatus').val('');
        $(document).on('click', '.saveform', function () {
            var btnval = $(this).val();
            $('#savestatus').val(savestatus);

            var url = "{{ url('taxgroupsave') }}";
            var create_url = "{{ url('taxgroupcreate') }}";
            var red_url = "{{ url('taxgroup') }}";

            var formdata = $('#taxgroup').serialize();
            var form = $('#taxgroup');

            form.parsley().validate();
            var form = $('#taxgroup');
            form.parsley().validate();
            duplicate_validate();
            if (form.parsley().isValid()) {
                if (dup_chk == true) {

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
</script>

@endpush