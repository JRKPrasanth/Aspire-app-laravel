@extends('layouts.header')
@section('content')
    <h3 class="text-danger">

        <?php
    if ($product_url == "") {
        if ($url == "purchasepricelist") { ?>
        Purchase Pricelist
        <?php    } else if ($url == "salespricelist") { ?>
        Sales Pricelist

        <?php        } else if ($url == "salespricelistcopy") { ?>
        Sales Pricelist Copy

        <?php        } else { ?>
        Purchase Pricelist Copy
        <?php        }
    } else { ?>
        Product Pricelist

        <?php } ?>

    </h3>
    @include('layouts.breadcrumb')
    <?php error_reporting(0); ?>


    <form action="" method="post" id="priceform" data-parsley-validate>

        <input type="hidden" value="" name="savestatus" id="savestatus" />
        {{ csrf_field() }}


        <div class="card shadow-lg rounded-4 border-0">
            <div class="card-header bg-primary text-white fw-semibold"></div>
            <div class="card-body card-block">


                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-4">
                        <!-- Pricelist Name -->
                        <div class="mb-3 row">
                            <label class="col-md-5 col-form-label">
                                <span class="text-danger">*</span> Pricelist Name
                            </label>
                            <div class="col-md-7">
                                <input type="hidden" class="form-control pricelist_hdr_id" name="pricelist_hdr_id"
                                    value="{{ $row->pricelist_hdr_id }}">
                                <input type="hidden" class="form-control edit_id" name="edit_id"
                                    value="{{ $row->pricelist_hdr_id }}">
                                <input type="hidden" name="url" value="{{ $url }}">
                                <input type="text" class="form-control pricelist_name" id="pricelist_name"
                                    name="pricelist_name" value="{{ $row->pricelist_name }}" required tabindex="1">
                                <input type="hidden" class="form-control status" id="status" name="status"
                                    value="{{ $row->status }}">
                                <span class="badge bg-danger dup_name d-none"></span>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-3 row">
                            <label class="col-md-5 col-form-label">Description</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control description" id="description" name="description"
                                    value="{{ $row->description }}" tabindex="2">
                            </div>
                        </div>
                    </div>

                    <!-- Middle Column -->
                    <div class="col-md-4">
                        <!-- Pricelist Type -->
                        <div class="mb-3 row none">
                            <label class="col-md-5 col-form-label">Pricelist Type</label>
                            <div class="col-md-7">
                                <select class="form-select select2 price_list_type" id="price_list_type"
                                    name="price_list_type">
                                    <option value="">-- Select --</option>
                                    <option value="Purchase" @if($row->price_list_type == 'Purchase') selected @endif>Purchase
                                    </option>
                                    <option value="Sales" @if($row->price_list_type == 'Sales') selected @endif>Sales</option>
                                </select>
                            </div>
                        </div>

                        <!-- Active -->
                        <div class="mb-3 row">
                            <label class="col-md-5 col-form-label">Active</label>
                            <div class="col-md-7">
                                <select name="active" class="form-select active select2">
                                    <option value="Yes" @if($row->active == "Yes") selected @endif>Yes</option>
                                    <option value="No" @if($row->active == "No") selected @endif>No</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-4">
                        <!-- Created By -->
                        <div class="mb-3 row none">
                            <label class="col-md-5 col-form-label">Created By</label>
                            <div class="col-md-7">
                                <select name="created_by" id="created_by" class="form-select select2 created_by">
                                    {!! $created_by !!}
                                </select>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="row mt-4">
                    <div class="col-md-12">


                        <!--******************-Linedata ************-->
                        <div id="preview-area" class="table-responsive" style="height:300px;">
                            <table class="table table-bordered clone_table" style="width: 135%; !important">

                                <thead class="table-light sticky">
                                    <tr>
                                        <th>Line No</th>
                                        <th>Product</th>
                                        <!-- <th>&nbsp;</th> -->
                                        <?php if ($row->price_list_type == 'Sales') { ?>
                                        <th>Batch Number</th>
                                        <?php } ?>
                                        <th>Unit price</th>
                                        <th>Std price</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Active</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody class="clone_lines_body">
                                    <?php if (count($linedata) >= 1) { ?>
                                    @foreach($linedata as $key => $value)

                                                                <?php        if (($urlname == 'salespricelistapproval' || $urlname == 'purchasepricelistapproval') && ($value->start_date == date('Y-m-d') && $value->end_date >= date('Y-m-d'))) { ?>
                                                                <tr class="clone rcopy" style="background: #ff9e9e !important;">
                                                                    <?php        } else { ?>
                                                                <tr class="clone rcopy">
                                                                    <?php        } ?>
                                                                    <td>
                                                                        <input type="hidden" name="bulk_pricelist_line_id[]"
                                                                            class="form-control input-sm bulk_pricelist_line_id"
                                                                            value="{{$value->pricelist_line_id}}">

                                                                        <input type="text" name="bulk_line_no[]"
                                                                            class="form-control input-sm bulk_line_no" value="{{ $key + 1 }}"
                                                                            readonly="readonly">
                                                                    </td>
                                                                    <td class="sel2">
                                                                        <select name="bulk_product_id[]" class="select2 bulk_product_id" required>{!!
                                            $value->product_id !!}</select>
                                                                    </td>
                                                                    <?php        if ($row->price_list_type == 'Sales') { ?>
                                                                    <td>
                                                                        <input type="text" name="bulk_batch_number[]"
                                                                            class="form-control input-sm bulk_batch_number"
                                                                            value="{{ $value->batch_number }}">
                                                                    </td>
                                                                    <?php        } ?>
                                                                    <td>
                                                                        <input type="text" name="bulk_unit_price[]"
                                                                            class="form-control input-sm bulk_unit_price input_unit_width"
                                                                            value="{{$value->unit_price}}" minlength="1">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="bulk_std_price[]"
                                                                            class="form-control input-sm bulk_std_price input_unit_width"
                                                                            value="{{$value->std_price}}" minlength="1">
                                                                    </td>
                                                                    <td>
                                                                        <input name="bulk_start_date[]" type="text"
                                                                            class="form-control input-sm datepicker bulk_start_date"
                                                                            value="{{$value->start_date}}" readonly>
                                                                    </td>
                                                                    <td>
                                                                        <input name="bulk_end_date[]" type="text"
                                                                            class="form-control input-sm datepicker bulk_end_date"
                                                                            value="{{$value->end_date}}" readonly>
                                                                    </td>
                                                                    <td>
                                                                        <select name="bulk_active[]" class="select2 bulk_active " id="bulk_active"
                                                                            readonly>
                                                                            <option value="Yes" <?php        if ($value->active == 'Yes') {
                                            echo "selected";
                                        } ?>>
                                                                                Yes</option>
                                                                            <option value="No" <?php        if ($value->active == 'No') {
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
    if (count($linedata) < 1) {
                                        ?>
                                    <tr class="clone rcopy">
                                        <td>
                                            <input type="hidden" name="bulk_pricelist_line_id[]"
                                                class="form-control input-sm bulk_pricelist_line_id" value="">

                                            <input type="text" name="bulk_line_no[]"
                                                class="form-control input-sm bulk_line_no" value="1" readonly="readonly">
                                        </td>

                                        <td class="sel2">
                                            <select name="bulk_product_id[]" class="select2 bulk_product_id" required>{!!
            $product_id !!}</select>
                                        </td>

                                        <?php    if ($row->price_list_type == 'Sales') { ?>
                                        <td>
                                            <input type="text" name="bulk_batch_number[]"
                                                class="form-control input-sm bulk_batch_number" value="">
                                        </td>
                                        <?php    } ?>
                                        <td>
                                            <input type="text" name="bulk_unit_price[]"
                                                class="form-control input-sm bulk_unit_price input_unit_width" value=""
                                                minlength="1">
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_std_price[]"
                                                class="form-control input-sm bulk_std_price input_unit_width" value=""
                                                minlength="1">
                                        </td>
                                        <td>
                                            <input name="bulk_start_date[]" type="text"
                                                class="form-control input-sm datepicker bulk_start_date" readonly>
                                        </td>
                                        <td>
                                            <input name="bulk_end_date[]" type="text"
                                                class="form-control input-sm datepicker bulk_end_date" readonly>
                                        </td>
                                        <td>
                                            <select name="bulk_active[]" class="select2 bulk_active" id="bulk_active"
                                                readonly>
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


                <!-------------------------Linedata End-------------------------------->
                <div class="row mt-4 mb-3">
                    <div class="col-lg-12 col-md-12">
                        <div class="form-group text-center">
                            <input type="hidden" name="submit_type" class="submit_type" id="submit_type">

                            <?php if ($urlname == 'salespricelistapproval' || $urlname == 'purchasepricelistapproval') { ?>

                            <button type="button" name="submit" class="btn btn-success px-4 me-2 saveform"
                                value="APPROVED">Approve</button>
                            <button type="button" name="submit" class="btn btn-danger px-4 me-2 saveform"
                                value="REJECT">Reject</button>
                            <a class='btn btn-outline-danger px-4 me-2'
                                href="{{ url('purchasepricelistapproval')}}">Cancel</a>
                            <?php } else { ?>

                            <button type="button" id="save" class="btn btn-success px-4 me-2 saveform"
                                value="SAVE">Save</button>

                            <?php    if ($product_url == "") {
            if ($urlname == "purchasepricelist" || $urlname == "purchasepricelistapproval") { ?>
                            <a class='btn btn-danger px-4 me-2' href="{{ url('purchasepricelist')}}">Cancel</a>
                            <?php        } else if ($urlname == "purchasepricelistcopyedit") { ?>
                            <a class='btn btn-danger px-4 me-2' href="{{ url('purchasepricelistcopy')}}">Cancel</a>
                            <?php            } else if ($urlname == "salespricelistcopyedit") { ?>
                            <a class='btn btn-danger px-4 me-2' href="{{ url('salespricelistcopy')}}">Cancel</a>
                            <?php            } else { ?>
                            <a class='btn btn-danger px-4 me-2' href="{{ url('purchasepricelist')}}">Cancel</a>
                            <?php            }
        } else { ?>
                            <a class='btn btn-danger px-4 me-2' href="{{ url('salespricelist')}}">Cancel</a>
                            <?php    } ?>
                            <?php } ?>

                        </div>
                    </div>
                </div>


                <!--  purpose Product search jqgrid model-->
                <div class="modal fade" id="productModal">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header bg-primary text-white">
                                <h4 class="modal-title">Product Details</h4>
                            </div>

                            <!-- Modal Body -->
                            <div class="modal-body">
                                <table id="productgrid" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Product Code</th>
                                            <th>Product Group</th>
                                            <th>Product Category</th>
                                            <th>Product Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>

                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>


                <input type="hidden" class="pdtindex" value="">

            </div>
        </div>
    </form>



@endsection
@push('scripts')

    <script>

        /* purpose:To check Duplicate entry*/
        var dup_chk = true;
        function duplicate_validate() {
            var pricelist_name = $(".pricelist_name").val();
            var price_list_type = $('.price_list_type').val();

            var edit_id = $(".pricelist_hdr_id").val();

            $.ajax({
                cache: false,
                url: "{{URL::to('purchasepricelist/pricelistcheckname')}}",
                type: 'GET',
                dataType: 'json',
                async: false,
                data: { pricelist_name: pricelist_name, price_list_type: price_list_type, edit_id: edit_id },
                success: function (response) {

                    if (response == 1) {
                        $('.dup_name').html('pricelist_name:' + pricelist_name + ' Already Exists for this price Type');
                        $('.dup_name').show();
                        $(".pricelist_name").val('');
                        dup_chk = false;


                    }
                    else if (response == 0) {
                        var html = "";
                        $('.dup_name').hide();
                        dup_chk = true;

                    }

                },
                error: function (xhr, resp, text) {

                }
            });
        }

        $(document).ready(function () {

            $('.price_list_type').css('pointer-events', 'none');

            $('.pricelist_name').on('keyup', function () {
                this.value = this.value.toUpperCase();
            });


            // Helper: populate batch dropdown for a row from a server-side condition
            function loadBatchNumbers($row, condition, selectedVal = "") {
                const url = "{{ URL::to('jcomboform') }}"
                    + "?table=i_qoh_detail_t:batch_number:batch_number"
                    + "&order_by=qoh_detail_id asc"
                    + "&parent=" + encodeURIComponent(condition);

                $.ajax({
                    url,
                    type: 'GET',
                    success: function (data) {
                        if (typeof data === "string") {
                            try { data = JSON.parse(data); } catch (e) { console.error("Invalid JSON:", data); return; }
                        }

                        const $dropdown = $row.find('.bulk_batch_number');
                        $dropdown.html('<option value="">-- Select Batch Number --</option>');

                        if (Array.isArray(data)) {
                            $.each(data, function (i, item) {
                                const selected = (item.val == selectedVal) ? 'selected' : '';
                                $dropdown.append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
                            });
                        }

                        $dropdown.trigger('change.select2');
                    }
                });
            }

            // When PRODUCT changes
            $(document).on('change', '.bulk_product_id', function () {
                const $row = $(this).closest('tr');
                const bulk_product_id = $(this).val();

                if (!bulk_product_id) {
                    // reset batch and active in this row
                    $row.find('.bulk_batch_number')
                        .html('<option value="">-- Select Batch Number --</option>')
                        .prop('required', false)
                        .trigger('change.select2');
                    return;
                }

                const url = "{{ URL::to('salespricelistbatch') }}/" + encodeURIComponent(bulk_product_id);

                $.get(url, function (data) {
                    // Expect: data is "0" or a CSV list of qoh_detail_ids e.g. "1,2,3"
                    // Build condition either way (empty list produces no results)
                    let condition = 'qoh_detail_id in(' + (data || '') + ')';

                    // Populate batch dropdown for this row
                    loadBatchNumbers($row, condition);

                    // Toggle required attribute
                    const hasBatches = (data && data !== 0 && data !== "0");
                    $row.find('.bulk_batch_number').prop('required', !!hasBatches);

                    // Now enforce "active: No" on earlier duplicate rows
                    const priceType = $('.price_list_type').val(); // page-level control
                    const currentBatch = $row.find('.bulk_batch_number').val(); // may be empty until user picks

                    // Iterate previous rows only
                    $('.clone_lines_body tr').each(function (i) {
                        const $prev = $(this);
                        if ($prev[0] === $row[0]) return false; // stop when reaching current row
                        const prevProduct = $prev.find('.bulk_product_id').val();
                        const prevBatch = $prev.find('.bulk_batch_number').val();

                        if (priceType === 'Sales') {
                            if (prevProduct == bulk_product_id && prevBatch == currentBatch) {
                                $prev.find('.bulk_active').val('No').trigger('change');
                            }
                        } else {
                            if (prevProduct == bulk_product_id) {
                                $prev.find('.bulk_active').val('No').trigger('change');
                            }
                        }
                    });
                });
            });

            // When BATCH changes
            $(document).on('change', '.bulk_batch_number', function () {
                const $row = $(this).closest('tr');
                const bulk_product_id = $row.find('.bulk_product_id').val();
                const batchnumber = $(this).val();
                const priceType = $('.price_list_type').val();

                if (!bulk_product_id) return;

                if (priceType === 'Sales') {
                    // previous rows only
                    $('.clone_lines_body tr').each(function () {
                        const $prev = $(this);
                        if ($prev[0] === $row[0]) return false; // stop when reached current row

                        const prevProduct = $prev.find('.bulk_product_id').val();
                        const prevBatch = $prev.find('.bulk_batch_number').val();

                        if (prevProduct == bulk_product_id && prevBatch == batchnumber) {
                            $prev.find('.bulk_active').val('No').trigger('change');
                        }
                    });
                }
            });




            <?php if ($url == "salespricelistcopy" || $url == "purchasepricelistcopy") { ?>
            $('.pricelist_hdr_id').val('');
            $('.pricelist_name').val('');
            $('.bulk_pricelist_line_id').val('');
            <?php } ?>

            /*Save Function*/

            $(document).on('click', '.saveform', function () {

                var btnval = $(this).val();
                var prd_src = "<?php echo $product_url; ?>";

                if (btnval == 'APPLYCHANGES') {
                    var savestatus = 'DRAFT';
                }
                else if (btnval == 'APPROVED') {
                    var savestatus = 'APPROVED';

                } else if (btnval == 'REJECT') {
                    var savestatus = 'REJECTED';

                }
                else {
                    var savestatus = 'INITIATED';
                }

                $('#savestatus').val(savestatus);
                $('.submit_type').val("save");


                var url = "{{ URL::to('purchasepricelistsave') }}";


                var form = $('#priceform');

                var pricetype = $('.price_list_type').val();
                var reurl = "<?php echo $urlname; ?>";
                if (prd_src == "") {
                    if (reurl == 'purchasepricelistcopyedit') {
                        var red_url = "{{ URL::to('purchasepricelistcopy') }}";
                    } else if (reurl == 'salespricelistcopyedit') {
                        var red_url = "{{ URL::to('salespricelistcopy') }}";
                    }
                    else if (reurl == 'purchasepricelistapproval') {
                        var red_url = "{{ URL::to('purchasepricelistapproval') }}";
                    } else if (reurl == 'salespricelistapproval') {
                        var red_url = "{{ URL::to('salespricelistapproval') }}";
                    }
                    else if (reurl == 'salespricelist' || reurl == 'salespricelistedit') {
                        var red_url = "{{ URL::to('salespricelist') }}";
                    } else {
                        var red_url = "{{ URL::to('purchasepricelist') }}";
                    }
                } else {
                    var red_url = "{{ URL::to('product') }}";
                }



                if (btnval != 'APPLYCHANGES') {
                    form.parsley().validate();
                    var form = $('#priceform');
                    form.parsley().validate();

                    if (form.parsley().isValid()) {
                        var formdata = $('#priceform').serialize();
                        var $btn = $(this);
                        $btn.prop('disabled', true);
                        $.post(url, formdata, function (data) {
                            var status = data.status;
                            var msg = data.message;
                            var id = data.id;
                            var edit_url = "{{ URL::to('purchasepricelistedit') }}/" + id;
                            if (btnval != 'SAVE' && btnval != 'APPROVED' && btnval != 'REJECT') {
                                showCustomAlert(msg, status);
                                setTimeout(function () {
                                    window.location.href = edit_url;
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
                }
                else {

                    var formdata = $('#priceform').serialize();
                    $.post("{{ URL::to('pricelistcheckname') }}", formdata, function (data) {
                        if (data != 1) {
                            $.post(url, formdata, function (data) {
                                var status = data.status;
                                var msg = data.message;
                                var id = data.id;
                                if (prd_src == "") {
                                    if (pricetype == 'Purchase')
                                        var edit_url = "{{ URL::to('purchasepricelistedit') }}/" + id;
                                    else
                                        var edit_url = "{{ URL::to('salespricelistedit') }}/" + id;
                                } else {
                                    var a = 1;
                                    if (pricetype == 'Purchase')
                                        var edit_url = "{{ URL::to('purchasepricelistedit') }}/" + id + '?sr=' + a;
                                    else
                                        var edit_url = "{{ URL::to('salespricelistedit') }}/" + id + '?sr=' + a;
                                }
                                showCustomAlert(msg, status);
                                setTimeout(function () {
                                    window.location.href = edit_url;
                                }, 1500);

                            });

                        }
                        else {
                            showCustomAlert('Pricelist Already Exists', 'info');
                            $('.pricelist_name,.description,.datepicker,.bulk_unit_price,.bulk_std_price,.bulk_start_date,.bulk_end_date').val('');
                            $('.bulk_product_id').val('').select2();
                        }
                    });
                }

            });



            $('.bulk_product_id,.bulk_unit_price').attr('required', true);

            $(document).on('keypress', '.bulk_unit_price,.bulk_std_price', function (ev) {
                var regex = new RegExp("^[0-9.]+$");
                var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                if (regex.test(str)) {
                    return true;
                }
                ev.preventDefault();
                return false;
            });





        });


        function initRowDatepickers($row) {
            const $start = $row.find('.bulk_start_date');
            const $end = $row.find('.bulk_end_date');

            // Remove any cloned datepicker state / duplicate IDs
            $start.removeClass('hasDatepicker').datepicker('destroy').removeAttr('id');
            $end.removeClass('hasDatepicker').datepicker('destroy').removeAttr('id');

            // Init START
            $start.datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: "yy-mm-dd",
                minDate: 0,
                maxDate: 0,
                showAnim: "slideDown",
                yearRange: "-25:+0",
                beforeShow: function () {
                    setTimeout(() => $('#ui-datepicker-div').css('z-index', 99999), 0);
                },
                onSelect: function () {
                    const sd = $start.datepicker('getDate');
                    if (sd) $end.datepicker('option', 'minDate', sd);
                }
            });

            // Init END
            $end.datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: "yy-mm-dd",
                minDate: 0,
                maxDate: +365,
                showAnim: "slideDown",
                yearRange: "-25:+0",
                beforeShow: function () {
                    setTimeout(() => $('#ui-datepicker-div').css('z-index', 99999), 0);
                }
            });
        }

        // Add Row
        $(document).on('click', '.add-row', function () {

    const $lastRow = $('.clone_lines_body tr:last');
    const $newRow = $lastRow.clone(false, false);

    // Clear values
    $newRow.find('input').val('');
    $newRow.find('select').val('');

    // Replace product dropdown with full list
    $newRow.find('.bulk_product_id').html(`{!! $productid !!}`);

    // Remove Select2 footprint
    $newRow.find('select.select2').each(function () {
        if ($(this).hasClass('select2-hidden-accessible')) {
            $(this).select2('destroy');
        }
        $(this).removeAttr('data-select2-id');
        $(this).next('.select2').remove();
    });

    // Append new row
    $('.clone_lines_body').append($newRow);

    // Reinitialize Select2
    $newRow.find('select.select2').select2({ width: '100%' });

    // Reinitialize datepickers
    initRowDatepickers($newRow);

    updateLineNumbers();
});


        // (Optional) init datepickers for existing rows on first load
        $(function () {
            $('.clone_lines_body tr').each(function () { initRowDatepickers($(this)); });
        });

        // Example line-number updater
        function updateLineNumbers() {
            $('.clone_lines_body tr').each(function (i) {
                $(this).find('.bulk_line_no').val(i + 1);
            });
        }


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



    </script>


@endpush