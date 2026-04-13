@extends('layouts.header')
@section('content')
<h3 class="text-danger">Subinventory Transfer</h3>
@include('layouts.breadcrumb')
<?php include('tools_menu.php'); ?>

<form method="post" action="" id="transfer_form" data-parsley-validate>
    {{ csrf_field() }}


    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-success text-white fw-semibold"></div>
        <div class="card-body card-block ">


            <div class="row">
                <div class="col-md-12">

                    <div class="row g-4">

                        <!-- Left Column -->
                        <div class="col-md-4">
                            <!-- Subinventory Transfer No -->
                            <div class="mb-3 row">
                                <label class="col-sm-5 col-form-label">Subinventory Transfer No</label>
                                <div class="col-sm-7">
                                    <input type="text" id="subtransfer_no" name="subtransfer_no"
                                        class="form-control subtransfer_no" value="{{ $row->subtransfer_no }}" readonly>
                                </div>
                            </div>

                            <!-- Transaction Date -->
                            <div class="mb-3 row">
                                <label class="col-sm-5 col-form-label">Trx Date</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input type="text" id="trx_date" name="trx_date"
                                            class="form-control datepicker trx_date" value="{{$form_date}}" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Work In Progress Only -->
                            <?php if ($group_name == "10") { ?>
                                <div class="mb-3 row">
                                    <label class="col-sm-5 col-form-label text-danger">* Start Time</label>
                                    <div class="col-sm-7">
                                        <input type="text" id="process_start_date" name="process_start_date"
                                            class="form-control start_date_time">
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-5 col-form-label text-danger">* Duration</label>
                                    <div class="col-sm-7">
                                        <input type="text" id="machine_time" name="machine_time"
                                            class="form-control machine_time" readonly>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <!-- Middle Column -->
                        <div class="col-md-4">
                            <!-- From SubInventory -->
                            <div class="mb-3 row">
                                <label class="col-sm-5 col-form-label text-danger">* From SubInventory</label>
                                <div class="col-sm-7">
                                    <select id="frm_subinv_id" name="frm_subinv_id"
                                        class="form-select select2 frm_subinv_id" required>
                                        {!! $subinv_id !!}
                                    </select>
                                </div>
                            </div>

                            <!-- To SubInventory -->
                            <div class="mb-3 row">
                                <label class="col-sm-5 col-form-label text-danger">* To SubInventory</label>
                                <div class="col-sm-7">
                                    <select id="to_subinv_id" name="to_subinv_id"
                                        class="form-select select2 to_subinv_id" required>
                                        {!! $subinv_id !!}
                                    </select>
                                </div>
                            </div>

                            <!-- End Time (WIP Only) -->
                            <?php if ($group_name == "10") { ?>
                                <div class="mb-3 row">
                                    <label class="col-sm-5 col-form-label text-danger">* End Time</label>
                                    <div class="col-sm-7">
                                        <input type="text" id="process_end_date" name="process_end_date"
                                            class="form-control end_date_time" readonly>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-4">
                            <!-- Created By -->
                            <div class="mb-3 row none">
                                <label class="col-sm-5 col-form-label">Created By</label>
                                <div class="col-sm-7">
                                    <select id="created_by" name="created_by" class="form-select select2">
                                        {!! $created_by !!}
                                    </select>
                                </div>
                            </div>

                            <!-- Job Assigned To (WIP Only) -->
                            <?php if ($group_name == "10") { ?>
                                <div class="mb-3 row">
                                    <label class="col-sm-5 col-form-label text-danger">* Job Assigned To</label>
                                    <div class="col-sm-7">
                                        <select id="employee_id" name="employee_id[]"
                                            class="form-select select2 employee_id" multiple>
                                            {!! $employee !!}
                                        </select>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row mt-4">
                <div class="col-md-12">
                    <!-------------------------Linedata -------------------------------->

                    <div id="preview-area" class="table-responsive">
                         <table class="table table-bordered clone_table" style="width: 150% !important;">
                            <thead class="table-light">
                                <tr>
                                    <th>Line No</th>
                                    <th class="pdtdiv">Product</th>
                                    <th class="pdtsearch_div"></th>
                                    <th>Product Group</th>
                                    <th>From Locator</th>
                                    <th>Batch Number</th>
                                    <th>Manufacturer Date</th>
                                    <th>Product Expire Date</th>
                                    <th>Qoh</th>
                                    <th> Transfer Qty</th>
                                    <th> Remarks</th>
                                    <th></th>
                                </tr>

                            </thead>

                            <tbody class="clone_lines_body">

                                <?php if (count($linedata) < 1) { ?>
                                    <tr class="clone rcopy">
                                        <td>
                                            <input type="text" name="bulk_line_no[]"
                                                class="form-control input-sm bulk_line_no" value="1" readonly="readonly">
                                            <input type="hidden" name="bulk_status[]" class="bulk_status"  value="INITIATED">
                                        </td>
                                        <td class="product_sel2"><select name="bulk_product_id[]"
                                                class="form-control bulk_product_id  parsley-validated select2"
                                                required="required">{!! $product_id !!}</select></td>

                                        <td class="pdtsearch_div"><i class="fa fa-search productsearch"></i></td>

                                        <td class="grp_div">
                                            <select name="bulk_product_group_id[]" id="bulk_product_group_id"
                                                class="form-control bulk_product_group_id select2" readonly>
                                                {!! $product_group_id !!}
                                            </select>
                                        </td>
                                        <td>
                                            <select name="bulk_from_loc[]" id="bulk_from_loc"
                                                class="form-control bulk_from_loc parsley-validated select2"
                                                required="required">

                                            </select>
                                        </td>
                                        <td>
                                            <select name="bulk_batch_no[]" id="bulk_batch_no"
                                                class="form-control bulk_batch_no parsley-validated select2"
                                                required="required">

                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_manufacture_date[]"
                                                class="form-control input-sm bulk_manufacture_date input_qty_width"
                                                value="">
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_product_expire_date[]"
                                                class="form-control input-sm bulk_product_expire_date input_qty_width"
                                                value="" readonly>
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_qoh[]"
                                                class="form-control input-sm bulk_qoh input_qty_width" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_from_transfer_qty[]"
                                                class="form-control input-sm bulk_from_transfer_qty" value=""
                                                required="required">
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_remarks[]"
                                                class="form-control input-sm bulk_remarks" value="{{ $value->remarks }}">
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
			
			
			                    <div class="row text-center mt-4">
                        <div class="col-md-12">
                            <div class="form-group ">

                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <input type="hidden" name="submit_type" class="submit_type" value="" />
                                        <div class="form-group text-center actionbtn">

                                            <?php if ($return_url == 'subinventorytransfers') { ?>
                                                <button name="submit" type="button" class="btn btn-primary saveform px-4"
                                                    value="SAVE"><i class="bi bi-bar-chart-fill"></i> Trading</button>
                                            <?php } else { ?>
                                                <button name="submit" type="button" class="btn btn-success px-4 saveform px-4"
                                                    value="APPROVAL">Approve</button>
                                            <?php }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
     <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
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

    <!--end-->


    <input type="hidden" class="pdtindex" value="" />
    <input type="hidden" class="frominventory" value="" />


</form>

@endsection
@push('scripts')

<script>



    $(document).ready(function () {
		$('.pdtsearch_div').css('display', 'none');
        $('.frm_loc_iddiv,.to_loc_iddiv,.grp_div').css('pointer-events', 'none');

        /**********Up/down/left/right arrow navigation start*******/
        $('input,select').keyup(function (e) {
            if (e.which == 39) { // right arrow
                $(this).closest('td').next().find('input,select').focus();

            } else if (e.which == 37) { // left arrow
                $(this).closest('td').prev().find('input,select').focus();

            } else if (e.which == 40) { // down arrow
                $(this).closest('tr').next().find('td:eq(' + $(this).closest('td').index() + ')').find('input,select').focus();

            } else if (e.which == 38) { // up arrow
                $(this).closest('tr').prev().find('td:eq(' + $(this).closest('td').index() + ')').find('input,select').focus();
            }
        });
        /**********Up/down/left/right arrow navigation end *******/


        $(document).off('change', '.frm_subinv_id').on('change', '.frm_subinv_id', function () {

            var subinv = $('.frm_subinv_id').select2('val');
            var hasSubinv = subinv && subinv !== '0';

            $('.frm_loc_iddiv').css('pointer-events', hasSubinv ? '' : 'none');

            $('.frm_loc_id').css('pointer-events', 'auto');

            $('.product_sel2').css('pointer-events', hasSubinv ? '' : 'none');

            if (hasSubinv) {
                var urlLoc = "{{ URL::to('jcomboform1') }}?table=m_sublocators_t:sublocator_id:locator_code"
                    + "&parent=and subinventory_id=" + subinv
                    + "&order_by=locator_code asc";

                $.ajax({
                    url: urlLoc,
                    type: 'GET',
                    success: function (data) {
                        if (typeof data === "string") {
                            try {
                                data = JSON.parse(data);
                            } catch (e) {
                                console.error("Invalid JSON response:", data);
                                return;
                            }
                        }

                        $(".frm_loc_id").html('<option value="">-- Select Locator --</option>');
                        $.each(data, function (i, item) {
                            $(".frm_loc_id").append(
                                `<option value="${item.val}">${item.option_name}</option>`
                            );
                        });

                        $(".frm_loc_id").trigger('change.select2');
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error (frm_loc_id):", error);
                    }
                });


                var urlProd = "{{ URL::to('jcomboformcompwithref') }}?table=i_qoh_detail_t:product_id:concatenated_product"
                    + "&parent=subinventory_id=" + subinv + " and qoh_trx_qty>0"
                    + "&order_by=product_id asc";

                $.ajax({
                    url: urlProd,
                    type: 'GET',
                    success: function (data) {
                        if (typeof data === "string") {
                            try {
                                data = JSON.parse(data);
                            } catch (e) {
                                console.error("Invalid JSON response:", data);
                                return;
                            }
                        }

                        $(".bulk_product_id").html('<option value="">-- Select Product --</option>');
                        $.each(data, function (i, item) {
                            $(".bulk_product_id").append(
                                `<option value="${item.val}">${item.option_name}</option>`
                            );
                        });

                        $(".bulk_product_id").trigger('change.select2');
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error (bulk_product_id):", error);
                    }
                });

                // Populate products with HTML from prddetails
                var url = "{{ URL::to('prddetails') }}?frm_subinv_id=" + encodeURIComponent(subinv);
                $.get(url, function (data) {
                    if (data) {
                        $('.bulk_product_id').html(data);
                    } else {
                        $('.bulk_product_id').html("<option>---Please Select---</option>");
                        showCustomAlert("There is No Product For This locator... Please choose another Locator", "info");
                    }
                });
            } else {

            }
        });



        $(document).on('change', '.to_subinv_id', function () {

            var subinv = $('.to_subinv_id').select2('val');
            $(".to_loc_id").css("pointer-events", "auto");
            if (subinv == '0') {
                $('.to_loc_iddiv').css('pointer-events', 'none');

                var urlToLoc = "{{ URL::to('jcomboform1') }}?table=m_sublocators_t:sublocator_id:locator_code"
                    + "&parent=subinventory_id=" + to_subinv_id
                    + "&order_by=locator_code asc";

                $.ajax({
                    url: urlToLoc,
                    type: 'GET',
                    success: function (data) {
                        // Parse JSON string if backend returns string
                        if (typeof data === "string") {
                            try {
                                data = JSON.parse(data);
                            } catch (e) {
                                console.error("Invalid JSON response:", data);
                                return;
                            }
                        }

                        // Reset dropdown
                        $(".to_loc_id").html('<option value="">-- Select Locator --</option>');

                        // Populate options
                        $.each(data, function (i, item) {
                            $(".to_loc_id").append(
                                `<option value="${item.val}">${item.option_name}</option>`
                            );
                        });

                        // Refresh select2 if applied
                        $(".to_loc_id").trigger('change.select2');
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error (to_loc_id):", error);
                    }
                });


            }
            else {

                $('.to_loc_iddiv').css('pointer-events', '');
            }
        });

        $('.trx_datediv').css('pointer-events', 'none');


			// Prevent selecting the same product in another row
		$(document).on('change', '.bulk_product_id', function () {
		  const $row = $(this).closest('tr');
		  const currentVal = $(this).val();

		  if (!currentVal) return;

		  // check other rows for same value
		  let isDuplicate = false;
		  $('.bulk_product_id').not(this).each(function () {
			if ($(this).val() === currentVal) {
			  isDuplicate = true;
			  return false; // break
			}
		  });

		  if (isDuplicate) {
			const label = $row.find('.bulk_product_id option:selected').text() || 'This product';
			// use your notifier (either one works—uncomment the one you use)
			showCustomAlert(`${label} Already Selected`, 'error');
			// notyMsgs('info', `${label} already selected in another row`);

			// reset current select and any dependent fields in this row
			$(this).val(null).trigger('change.select2');

			$row.find('.bulk_product_group_id').val(null).trigger('change.select2');
			$row.find('.bulk_from_loc').html('<option value="">-- Select --</option>').trigger('change.select2');
			$row.find('.bulk_batch_no').html('<option value="">-- Select --</option>').trigger('change.select2');
			$row.find('.bulk_manufacture_date').val('');
			$row.find('.bulk_product_expire_date').val('');
			$row.find('.bulk_qoh').val('');
			$row.find('.bulk_from_transfer_qty').val('');
			$row.find('.bulk_remarks').val('');
			return;
		  }

		  // (optional) load per-product data for THIS row after passing duplicate check
		  const frm_subinv_id = $('.frm_subinv_id').val();
		  const url = "{{ URL::to('subinventorybatchno1') }}"
					+ "?product_id=" + encodeURIComponent(currentVal)
					+ "&frm_subinv_id=" + encodeURIComponent(frm_subinv_id || '');

		  $.get(url, function (data) {
			if (!Array.isArray(data)) return;

			// product group
			$row.find('.bulk_product_group_id')
				.val(data[0] ?? '')
				.trigger('change.select2');

			// from locator options
			$row.find('.bulk_from_loc')
				.html(data[1] || '<option value="">-- Select --</option>')
				.trigger('change.select2');

			// reset dependent fields
			$row.find('.bulk_batch_no')
				.html('<option value="">-- Select --</option>')
				.trigger('change.select2');
			$row.find('.bulk_manufacture_date').val('');
			$row.find('.bulk_product_expire_date').val('');
			$row.find('.bulk_qoh').val('');
			$row.find('.bulk_from_transfer_qty').val('');
		  });
		});


        // 1) Transfer qty mirrors to "to" qty and validates against QOH
        $(document).on('keyup', '.bulk_from_transfer_qty', function () {
            const $row = $(this).closest('tr');
            const productId = $row.find('.bulk_product_id').val();

            if (!productId) {
                showCustomAlert('Please Choose Product...', 'info');
                $(this).val('');
                $row.find('.bulk_to_transfer_qty').val('');
                return;
            }

            const val = Number($(this).val()) || 0;
            $row.find('.bulk_to_transfer_qty').val(val); // mirror

            const qoh = Number($row.find('.bulk_qoh').val()) || 0;

            if (val > qoh) {
                showCustomAlert('Transfer qty is more than Qoh', 'info');
                $(this).val('');
                $row.find('.bulk_to_transfer_qty').val('');
            }
        });

        // 2) To locator must differ from From locator (page-level "from" locator)
        $(document).on('change', '.to_loc_id', function () {
            const to_loc_id = $(this).val();
            const frm_loc_id = $('.frm_loc_id').val(); // header/global control

            if (to_loc_id && frm_loc_id && frm_loc_id === to_loc_id) {
                showCustomAlert(
                    'To Locator should not be same as from locator... Please choose another Locator',
                    'warning'
                );
                $(this).val('').trigger('change'); // reset this select (keep select2 in sync if used)
            }
        });



        // 3) On batch change: load QOH + dates for THIS row
        $(document).on('change', '.bulk_batch_no', function () {
            const $row = $(this).closest('tr');

            const product_id = $row.find('.bulk_product_id').val();
            const productgroup = ($row.find('.bulk_product_group_id option:selected').text() || '').trim();
            const frm_subinv_id = $('.frm_subinv_id').val();     // page-level field
            const frm_loc_id = $row.find('.bulk_from_loc').val(); // row-level field
            const batch_no = $(this).val();

            if (!product_id) {
                showCustomAlert('Please Choose Product...', 'info');
                // reset dependent fields
                $row.find('.bulk_qoh').val('');
                $row.find('.bulk_manufacture_date').val('');
                $row.find('.bulk_product_expire_date').val('');
                return;
            }

            const url = "{{ URL::to('productqohdetails') }}"
                + "?product_id=" + encodeURIComponent(product_id)
                + "&frm_subinv_id=" + encodeURIComponent(frm_subinv_id || '')
                + "&frm_loc_id=" + encodeURIComponent(frm_loc_id || '')
                + "&batch_no=" + encodeURIComponent(batch_no || '');

            $.get(url, function (data) {
                // Expecting array-like: [qoh, ?, ?, expDate, mfgDate] based on your original usage
                if (data && data !== 0) {
                    const qoh = Number(data[0]) || 0;
                    const exp = data[3]; // string like "dd-mm-yyyy"
                    const mfg = data[4];

                    $row.find('.bulk_qoh').val(qoh);
                    $row.find('.bulk_manufacture_date').val(mfg || '');

                    if (productgroup === 'PACKING MATERIALS') {
                        $row.find('.bulk_product_expire_date').val('');
                    } else {
                        if (exp && exp !== '00-00-0000' && exp !== '30-11--0001') {
                            $row.find('.bulk_product_expire_date').val(exp);
                        } else {
                            $row.find('.bulk_product_expire_date').val('');
                        }
                    }
                } else {
                    showCustomAlert('Qoh not available for this product.. Pls choose another product', 'warning');
                    // Clear product + dependent fields in this row
                    $row.find('.bulk_product_id').val(null).trigger('change.select2');
                    $row.find('.bulk_qoh').val('');
                    $row.find('.bulk_manufacture_date').val('');
                    $row.find('.bulk_product_expire_date').val('');
                }
            });
        });

        // 4) From locator change: load batch list for THIS row
        $(document).on('change', '.bulk_from_loc', function () {
            const $row = $(this).closest('tr');

            const product_id = $row.find('.bulk_product_id').val();
            const frm_subinv_id = $('.frm_subinv_id').val(); // page-level
            const frm_loc_id = $(this).val();

            if (!product_id) {
                showCustomAlert('Please Choose Product...', 'info');
                // reset batch on this row
                $row.find('.bulk_batch_no')
                    .html('<option value="">-- Select --</option>')
                    .trigger('change.select2');
                return;
            }

            const url = "{{ URL::to('subinventorybatchno') }}"
                + "?product_id=" + encodeURIComponent(product_id)
                + "&frm_subinv_id=" + encodeURIComponent(frm_subinv_id || '')
                + "&frm_loc_id=" + encodeURIComponent(frm_loc_id || '');

            $.get(url, function (data) {
                // Your original code uses data[1] as <option> HTML
                const optionsHtml = (Array.isArray(data) ? data[1] : '') || '<option value="">-- Select --</option>';

                $row.find('.bulk_batch_no')
                    .html(optionsHtml)
                    .trigger('change.select2'); // keep select2 synced
            });
        });


        /*product search function*/
        $(document).on('click', '.productsearch', function () {
            var index = $(this).closest('tr').index();
            $('.pdtindex').val(index);

            var sub_inve = $(".frm_subinv_id").val() || '';
            $('.frominventory').val(sub_inve);

            // Open Modal
            $('#productModal').modal('show');

            // Load DataTable
            if (!$.fn.DataTable.isDataTable('#productgrid')) {
                $('#productgrid').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('getProductgridDatasubinventory') }}",
                        data: function (d) {
                            d.sub_inve = $('.frominventory').val();
                        }
                    },
                    columns: [
                        { data: 'product_code', name: 'product_code' },
                        { data: 'group_name', name: 'group_name' },
                        { data: 'category_name', name: 'category_name' },
                        { data: 'concatenated_product', name: 'concatenated_product' },
                        {
                            data: 'product_id',
                            render: function (data, type, row) {
                                return `<button type="button" class="btn btn-sm btn-primary selectProduct" 
                                    data-id="${row.product_id}" 
                                    data-name="${row.concatenated_product}">
                                    Select
                                </button>`;
                            },
                            orderable: false,
                            searchable: false
                        }
                    ],
                    pageLength: 10
                });
            } else {
                $('#productgrid').DataTable().ajax.reload();
            }
        });


        $(document).on('click', '.selectProduct', function () {
            var product_id = $(this).data('id');
            var product_name = $(this).data('name');
            var index = $('.pdtindex').val();

            var pdtcount = pdtcheck(product_id, index);

            if (pdtcount <= 0) {
                $('.bulk_product_id' + index).val(product_id).trigger('change');
                $('#productModal').modal('hide');
            } else {
                var message = `<span style="color:#fdff65">${product_name}</span> Product Already Selected`;
                showCustomAlert(message, 'warning');
                rowdataEmpty(index);
                $('#productModal').modal('hide');
            }
        });




        var dateToday = new Date();


        $(document).on("focus", ".bulk_product_expire_date", function () {

            $(this).datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: "yy-mm-dd",
                minDate: 0,
                maxDate: '+4Y',
                showAnim: "slideDown",
                yearRange: "c:+4",

            });
        });



        $(document).on("focus", ".trx_date", function () {

            $(this).datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: "yy-mm-dd",
                minDate: 0,
                maxDate: 0,
                showAnim: "slideDown",
                yearRange: "-25:+0",

            });
        });

        
        $(document).on("change", "#process_end_date", function () {

            var start = new Date($('#process_start_date').val());
            var end   = new Date($('#process_end_date').val());

            if (end < start) {
                alert('End datetime cannot be before start datetime');
                $(this).val('');
                $('.machine_time').val('');
                return;
            }

            // Total difference in minutes
            var diffMinutes = Math.floor((end - start) / (1000 * 60));

            // Calculate hours and minutes
            var hours = Math.floor(diffMinutes / 60);
            var minutes = diffMinutes % 60;

            // Convert to HH.MM format
            var formattedTime = hours + '.' + (minutes < 10 ? '0' + minutes : minutes);

            $('.machine_time').val(formattedTime);
        });


        function pdtcheck(product_id, index) {
            var count = 0;

            // Loop through all bulk_product_id inputs
            $("input[name^='bulk_product_id']").each(function (i, el) {
                if ($(el).val() == product_id && i != index) {
                    count++;
                }
            });

            return count;
        }

        // Add Row
        $(document).on('click', '.add-row', function () {
            const $lastRow = $('.clone_lines_body tr:last');
            const $newRow = $lastRow.clone(false, false); // clone without events or data

            // Clear all input and select values in the cloned row
            $newRow.find('input').val('');
			$newRow.find('input[type="text"]').val('');
            $newRow.find('select').val('').trigger('change');
			$newRow.find('.bulk_status').val('INITIATED');
            // Remove any Select2 artifacts before reinitializing
            $newRow.find('select.select2').each(function () {
                if ($.fn.select2 && $(this).hasClass("select2-hidden-accessible")) {
                    $(this).select2('destroy');
                }
                $(this).removeAttr('data-select2-id');
                $(this).next('.select2').remove(); // remove the select2 container
            });

            
                $newRow.find('.bulk_product_expire_date').each(function () {
                    $(this).removeClass('hasDatepicker').removeAttr('id').val('');
                    // reinitialize datepicker
                    $(this).datepicker({
                        changeMonth: true,
                        changeYear: true,
                        dateFormat: "yy-mm-dd",
                        minDate: 0,
                        maxDate: '+4Y',
                        showAnim: "slideDown",
                        yearRange: "c:+4",

                    });
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

		// save form
	 $(document).on('click', '.saveform', function() {
        var btnval = $(this).val();
        $('#savestatus').val(btnval);
        var url = "{{ URL::to('transfersave') }}";
        var form = $('#transfer_form');
        form.parsley().validate();
        var form = $('#transfer_form');
        form.parsley().validate();

        if (form.parsley().isValid())
        {   
			var $btn = $(this);            
			$btn.prop('disabled', true);
            var formdata = $('#transfer_form').serialize();
            $.post(url, formdata, function(data)
            {
                var status = data.status;
                var msg = data.message;
                var id = data.id;
                showCustomAlert(msg,"success"); 
                if(status == "success")           
                {
                    setTimeout(function(){
                        window.location.reload();
                    },1500);
                }
            });
        }
    });
/*end*/
		
    });
	
</script>

@endpush