@extends('layouts.header')
@section('content')
<h3 class="text-danger">
    <?php if ($pageMethod == "approveconsumable") { ?>
        Consumable Details
    <?php } else { ?>
        Consumable Details
    <?php } ?>
</h3>
@include('layouts.breadcrumb')


<div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white fw-semibold"></div>

    <div class="card-body card-block headerdiv1">

        <form method="post" action="" id="consumable" data-parsley-validate>
            <input type="hidden" value="" name="status" id="savestatus" />

            {{ csrf_field() }}


            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-4">Consumable No</label>
                            <div class="col-md-6">
                                <input type="text" id="consumable_number" name="consumable_number"
                                    class="form-control consumable_number" width="100%"
                                    value="{{ $row->consumable_number }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-4"> Date</label>
                            <div class="col-md-4" style="pointer-events:none;">
                                <div class="input-group date form_date col-md-4" data-date="">
                                    <input class="form-control consumable_date datepicker" id="consumable_date"
                                        name="consumable_date" size="16" type="text" value="{{ $row->consumable_date }}"
                                        readonly>
                                    <input type="hidden" name="consumable_hdr_id" class="consumable_hdr_id"
                                        value="{{$row->consumable_hdr_id}}">
                                </div>

                            </div>
                            <div class="col-md-2 showinline">
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="row mt-4">
                <div class="col-md-12">

                    <div id="preview-area" class="table-responsive">
                        <table class="table table-bordered clone_table">
                            <thead class="table-light">
                                <tr>
                                    <th style="min-width: 70px;"> Line No</th>
                                    <th style="min-width: 200px;"> Product </th>
                                    <th style="min-width: 150px;"> Batch No. </th>
                                    <th style="min-width: 150px;"> Subinventory </th>
                                    <th style="min-width: 120px;"> Locator </th>
                                    <th style="min-width: 100px;">QOH</th>
                                    <th style="min-width: 100px;"> Qty</th>
                                    <th style="min-width: 150px;">Comments</th>
                                    <?php if ($pageMethod == "approveconsumable") { ?>
                                        <th style="min-width: 200px;"> Account</th>
                                    <?php } ?>
                                    <th style="min-width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody class="clone_lines_body">

                                <?php if (count($linedata) >= 1) { ?>
                                    @foreach($linedata as $key=>$value)


                                    <tr class="clone rcopy">
                                        <td>
                                            <input type="hidden" name="bulk_consumable_line_id[]"
                                                class="form-control input-sm bulk_consumable_line_id"
                                                value="{{ $value->consumable_line_id }}">
                                            <input type="text" name="bulk_line_no[]"
                                                class="form-control input-sm bulk_line_no" value="{{$value->line_no}}"
                                                readonly>
                                        </td>

                                        <td class="consumable_lines_body">

                                            <select name='bulk_product_id[]' class='form-control bulk_product_id select2'
                                                required="true" data-selected-product="{{ $value->product_id_raw ?? '' }}">
                                                {!! $product ?? '' !!}

                                            </select>

                                        </td>
                                        <td class="consumable_lines_body">

                                            <select name='bulk_batch_no[]' class='form-control bulk_batch_no select2'
                                                required="true">
                                                {!!$value->batch_no!!}

                                            </select>

                                        </td>
                                        <td class="consumable_lines_body">

                                            <select name='bulk_subinventory[]'
                                                class='form-control bulk_subinventory select2'
                                                required="true">
                                                {!! $value->subinventory_id !!}

                                            </select>

                                        </td>
                                        <td class="consumable_lines_body">

                                            <select name='bulk_sublocator[]' class='form-control bulk_sublocator select2'
                                                required="true">
                                                {!! $value->sublocator_id !!}

                                            </select>

                                        </td>
                                        <td style="pointer-events:none; min-width: 50px;">
                                            <input type="text" name="bulk_qoh[]" class="form-control input-sm bulk_qoh"
                                                value="{{$value->qoh}}" style="min-width: 50px;">


                                        </td>
                                        <td class="consumable_lines_body" style="min-width: 50px;">
                                            <input type="text" name="bulk_qty[]" class="form-control input-sm bulk_qty"
                                                value="{{ $value->qty }}" style="min-width: 30px;">
                                        </td>
                                        <td style="min-width: 170px;">
                                            <input type="text" name="bulk_comments[]"
                                                class="form-control input-sm bulk_comments" value="{{$value->comments}}" style="min-width: 170px;">
                                        </td>
                                        <?php if ($pageMethod == "approveconsumable") { ?>
                                            <td>
                                                <select name='bulk_accounts_structure_id[]'
                                                    class='form-control bulk_accounts_structure_id select2'
                                                    required="true">
                                                    {!!$accounts_structure_id!!}

                                                </select>
                                            </td>
                                        <?php } ?>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-danger remove-row">
                                                <i class="fas fa-minus-circle"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    <!--create mode-->
                                <?php }
                                if (count($linedata) < 1) { ?>
                                    <tr class="clone cloneRow rcopy">
                                        <td>
                                            <input type="hidden" name="bulk_consumable_line_id[]"
                                                class="form-control input-sm bulk_consumable_line_id" value="">

                                            <input type="text" name="bulk_line_no[]"
                                                class="form-control input-sm bulk_line_no" value="1" readonly>
                                        </td>

                                        <td>
                                            <select name='bulk_product_id[]' class='form-control bulk_product_id select2'
                                                required="true">
                                                {!! $product_id !!}

                                            </select>
                                        </td>
                                        <td>

                                            <select name='bulk_batch_no[]' class='form-control bulk_batch_no select2'
                                                required="true">

                                                <option value="">--Please Select--</option>
                                            </select>

                                        </td>
                                        <td>

                                            <select name='bulk_subinventory[]'
                                                class='form-control bulk_subinventory select2'
                                                required="true">
                                                {!! $subinventory_id !!}
                                            </select>

                                        </td>
                                        <td>

                                            <select name='bulk_sublocator[]' class='form-control bulk_sublocator select2'
                                                required="true">
                                                {!! $sublocator_id !!}
                                            </select>

                                        </td>

                                        <td style="pointer-events:none; min-width: 100px;">
                                            <input type="text" name="bulk_qoh[]" class="form-control input-sm bulk_qoh"
                                                value="" style="min-width: 80px;">


                                        </td>
                                        <td style="min-width: 100px;">
                                            <input type="text" name="bulk_qty[]" class="form-control input-sm bulk_qty"
                                                value="" required="true" style="min-width: 80px;">
                                        </td>
                                        <td style="min-width: 150px;">
                                            <input type="text" name="bulk_comments[]"
                                                class="form-control input-sm bulk_comments" value="" required="true" style="min-width: 120px;">
                                        </td>
                                        <?php if ($pageMethod == "approveconsumable") { ?>
                                            <td>
                                                <select name='bulk_accounts_structure_id[]'
                                                    class='form-control bulk_accounts_structure_id select2'
                                                    required="true">
                                                    {!!$accounts_structure_id!!}
                                                </select>
                                            </td>
                                        <?php } ?>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-danger remove-row">
                                                <i class="fas fa-minus-circle"></i>
                                            </button>
                                        </td>
                                    </tr>


                                <?php } ?>
                            </tbody>
                        </table>
                        <div class="text-end <?php if ($pageMethod == 'approveconsumable') {
                                                    echo 'd-none';
                                                } ?>">
                            <button type="button" class="btn btn-success btn-sm add-row">
                                <i class="fas fa-plus-circle"></i> Add Row
                            </button>
                        </div>

                        <input type="hidden" name="enable-masterdetail" value="true">
                    </div>
                </div>
            </div>
            <!------------------------------------------------------------------------------------------>
            <div class="row mt-4 mb-3">
                <div class="col-lg-12 col-md-12">
                    <div class="form-group text-center">
                        <?php if ($pageMethod == "approveconsumable") { ?>
                            <button type="button" class="btn btn-success px-4 me-2 saveform"
                                value="Approve">Approve</button>
                            <a href="{{URL::to('consumableapproval')}}" class='btn btn-danger px-4'>Cancel</a>

                        <?php } else { ?>
                            <button type="submit" class="btn btn-success px-4 me-2 saveform"
                                value="SAVE">Submit</button>
                            <a href="{{ URL::to('consumable') }}" class='btn btn-danger px-4'>Cancel</a>

                        <?php } ?>

                    </div>
                </div>
            </div>
        </form>
    </div>

    @endsection
    @push('scripts')

    <script>
        $(document).ready(function() {

            // Note: Select2 is already initialized globally by header.blade.php
            // Do NOT re-initialize here to avoid double initialization issues

            // Set the previously selected product values after page load
            $('.bulk_product_id').each(function() {
                var selectedProduct = $(this).data('selected-product');
                if (selectedProduct) {
                    $(this).val(selectedProduct).trigger('change.select2');
                }
            });

            function qtyrequiredvalid() {
                $('.bulk_qty').each(function() {
                    const n = Number($(this).val());
                    if (!n) {
                        $(this).val('');
                    }
                });
            }


            // C) When batch changes in a row -> load subinventory, sublocator, QOH for THAT row
            $(document).on('change', '.bulk_batch_no', function() {
                const $row = $(this).closest('tr');
                const batch = $(this).val();
                const product = $row.find('.bulk_product_id').val();

                if (!batch || !product) {
                    // reset dependent fields if needed
                    $row.find('.bulk_subinventory').val(null).trigger('change.select2');
                    $row.find('.bulk_sublocator').val(null).trigger('change.select2');
                    $row.find('.bulk_qoh').val('');
                    return;
                }

                const url = "{{ URL::to('getinventlocator') }}" +
                    "?batch=" + encodeURIComponent(batch) +
                    "&product=" + encodeURIComponent(product);

                $.get(url, function(data) {
                    // expected: { subinventory_id: ..., sublocator_id: ..., qoh: ... }
                    if (data) {
                        $row.find('.bulk_subinventory')
                            .val(data.subinventory_id ?? null)
                            .trigger('change.select2');

                        $row.find('.bulk_sublocator')
                            .val(data.sublocator_id ?? null)
                            .trigger('change.select2');

                        $row.find('.bulk_qoh').val(data.qoh ?? '');
                    } else {
                        // fallback reset
                        $row.find('.bulk_subinventory').val(null).trigger('change.select2');
                        $row.find('.bulk_sublocator').val(null).trigger('change.select2');
                        $row.find('.bulk_qoh').val('');
                    }
                });
            });

            $(document).on('change', '.bulk_product_id', function() {
                const $row = $(this).closest('tr');
                const productId = $(this).val();

                if (!productId) {
                    $row.find('.bulk_batch_no')
                        .html('<option value="">-- Select --</option>')
                        .trigger('change.select2');
                    return;
                }

                $.get('productbatchno?product_id=' + encodeURIComponent(productId), function(optionsHtml) {
                    $row.find('.bulk_batch_no')
                        .html(optionsHtml || '<option value="">-- Select --</option>')
                        .trigger('change.select2');
                });
            });


            // validate qty against qoh for the current row
            $(document).on('change', '.bulk_qty', function() {
                const $row = $(this).closest('tr');
                const enteredVal = parseFloat($(this).val()) || 0;

                // QOH is in the same row
                let qoh = $row.find('.bulk_qoh').val();
                qoh = qoh ? parseFloat(qoh.toString().replace(/,/g, '')) : 0;

                if (enteredVal > qoh) {
                    showCustomAlert("Quantity should not be greater than Qoh", "error");
                    $(this).val('0');
                }
            });




            <?php if ($pageMethod == "approveconsumable") { ?>
                // Only disable the date field, allow editing of line items during approval
                $('.consumable_date').css('pointer-events', 'none');
            <?php } ?>


            $(document).on('keypress', '.bulk_qty', function(ev) {
                var regex = new RegExp("^[0-9-+.]+$");
                var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                if (regex.test(str)) {
                    return true;
                }
                ev.preventDefault();
                return false;
            });


            $('.bulk_qty').bind("cut copy paste", function(e) {
                e.preventDefault();
            });

            $('#savestatus').val('');
            $(document).on('click', '.saveform', function(event) {

                var btnval = $(this).val();
                if (btnval == 'APPLYCHANGES')
                    var savestatus = 'APPLY CHANGES';
                else if (btnval == 'SAVE' || btnval == 'SAVENEW')
                    var savestatus = 'INITIATED';
                else
                    var savestatus = 'APPROVED';

                $('#savestatus').val(savestatus);
                var url = "{{ URL::to('consumablesave') }}";
                if (savestatus == "INITIATED") {
                    var red_url = "{{ URL::to('consumable') }}";
                } else {
                    var red_url = "{{ URL::to('consumableapproval') }}";
                }
                var create_url = "{{ URL::to('consumablecreate') }}";
                qtyrequiredvalid();

                var form = $('#consumable');
                form.parsley().validate();
                var form = $('#consumable');
                form.parsley().validate();
                if (form.parsley().isValid()) {
                    var formdata = $('#consumable').serialize();

                    var $btn = $(this);
                    $btn.prop('disabled', true);

                    $.post(url, formdata, function(data) {
                        var status = data.status;
                        var msg = data.message;
                        showCustomAlert(msg, status);
                        setTimeout(function() {
                            window.location.href = red_url;
                        }, 1500);

                    }).fail(function() {

                    });
                }


            });


            // Add Row
            $(document).on('click', '.add-row', function() {
                const $firstRow = $('.clone_lines_body tr:first');
                const $newRow = $firstRow.clone(false, false); // clone from FIRST row to get all options

                // Clear all input values (except line_no which will be updated)
                $newRow.find('input').not('.bulk_line_no').val('');

                // IMPORTANT: First destroy Select2 on the original row's selects to get clean HTML
                // Don't destroy - we'll work with the cloned DOM

                // Remove Select2 containers from the cloned row (these are the rendered Select2 UI elements)
                $newRow.find('.select2-container').remove();

                // Clean up Select2 data attributes from select elements
                $newRow.find('select').each(function() {
                    // Remove all Select2 related attributes
                    $(this).removeAttr('data-select2-id');
                    $(this).removeAttr('aria-hidden');
                    $(this).removeAttr('tabindex');
                    $(this).removeClass('select2-hidden-accessible');
                    // Remove readonly attribute so dropdowns are clickable
                    $(this).removeAttr('readonly');
                    // Remove any stored data
                    $(this).removeData();
                    // Reset to first option
                    this.selectedIndex = 0;
                });

                // Also clean up any data-select2-id on options
                $newRow.find('option').removeAttr('data-select2-id');

                // Reset batch dropdown to default (since it depends on product selection)
                $newRow.find('.bulk_batch_no').html('<option value="">--Please Select--</option>');

                // Remove duplicate IDs if any exist
                $newRow.find('[id]').each(function() {
                    this.id = this.id + '_' + Date.now();
                });

                // Append the cleaned-up cloned row FIRST
                $('.clone_lines_body').append($newRow);

                // Now initialize Select2 on the new row's selects
                $newRow.find('select.select2').each(function() {
                    $(this).select2({
                        width: '100%',
                        dropdownParent: $(this).closest('td')
                    });
                });

                // Update line numbers
                updateLineNumbers();
            });



            // Remove button
            $(document).on('click', '.remove-row', function() {
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
                $('.clone_lines_body tr').each(function(index) {
                    $(this).find('.bulk_line_no').val(index + 1);
                });
            }



        });
    </script>

    @endpush