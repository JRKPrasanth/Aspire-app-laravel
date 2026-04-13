@extends('layouts.header')
@section('content')

<?php error_reporting(0);

/*Purpose For Display Label In Top of Form Page */
if ($row->source == 'ENQUIRY' && $row->enquiry_hdr_id == '' && !isset($copy_enquiry_number)) {
    $head = " ( New )";
} else if ($row->enquiry_hdr_id != '') {
    $head = " ( " . $row->enquiry_number . " )";
} else if (isset($copy_enquiry_number)) {
    $head = " ( Copy From " . $copy_enquiry_number . " )";
} else {
    $head = " ( Convert From " . $row->source . " )";
}

?>
<?php include('tools_menu.php'); ?>
<h3 class="text-danger">Purchase Enquiry {{$head}}</h3>
@include('layouts.breadcrumb')




<!-- Card -->
<div class="card shadow-lg rounded-4 border-0">
    <form method="post" action="" id="purchaseenquiry_form" class="purchaseenquiry_form" data-parsley-validate>
        @csrf
        <div class="card-header bg-primary text-white">
            <div class="row g-3">
                <div class="col-md-3">
                    <strong>Enquiry Date:</strong> <span class="badge bg-secondary">{{ $row->enquiry_date }}</span><br>
                    <strong>Enquiry Type:</strong> <span class="badge bg-secondary">{{ $row->enquiry_type_id }}</span>
                </div>
                <div class="col-md-3">
                    <strong>Enquiry Status:</strong>
                    <span class="enquiry_status badge bg-secondary">{{ $row->enquiry_status }}</span>
                </div>
                <div class="col-md-3">
                    <strong>Source:</strong>
                    <span class="source_span badge bg-secondary">{{ $row->source }}</span>
                </div>
                <div class="col-md-3">
                    <strong>Created By:</strong>
                    <span class="create_by badge text-secondary"></span>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- Hidden Fields -->
            <input type="hidden" name="enquiry_hdr_id" value="{{ $row->enquiry_hdr_id }}">
            <input type="hidden" name="enquiry_number" value="{{ $row->enquiry_number }}">
            <input type="hidden" name="enquiry_date" value="{{ $row->enquiry_date }}">

            <!-- Row 1 -->
            <div class="row g-3 mb-3">
                <div class="col-md-5">
                    <label class="form-label">
                        <span class="text-danger">*</span> Supplier Name
                    </label>
                    <div class="input-group">
                        <select name="supplier_id" class="form-select supplier_id select2" required>
                            {!! $supplier_id !!}
                        </select>
                    </div>
                </div>
                <div class="col-md-1 mt-4">
                    <button type="button" class="btn btn-outline-secondary suppliersearch mt-2" title="Search">
                        <i class="fa fa-search"></i>
                    </button>
                </div>


                <div class="col-md-6">
                    <label class="form-label">
                        <span class="text-danger">*</span> Supplier Site Name
                    </label>
                    <div class="input-group">
                        <select name="suppliersite_id" class="form-select suppliersite_id select2" required>
                            {!! $suppliersite_id !!}
                        </select>
                    </div>
                </div>
            </div>

            <!-- Additional Details -->
            <h5 class="mt-4 mb-3 border-bottom pb-2 text-primary">Additional Details</h5>
            <div class="row g-3">
                @php $i=0; $j=0; @endphp
                @foreach($enabled_columns as $index => $val)
                @php $required = ($val->action == '1') ? 'required' : ''; @endphp

                {{-- Project --}}
                @if($val->column_name=='project_id' && $val->active==1)
                <div class="col-md-6">
                    <label class="form-label">
                        @if($required) <span class="text-danger">*</span> @endif Project Name
                    </label>
                    <div class="input-group">
                        <select name="project_id" class="form-select project_id select2" {{ $required }}>
                            {!! $project_id !!}
                        </select>
                    </div>
                </div>
                @endif

                {{-- Enquiry Type --}}
                @if($val->column_name=='enquiry_type' && $val->active==1)
                <div class="col-md-6">
                    <label class="form-label">
                        @if($required) <span class="text-danger">*</span> @endif Enquiry Type
                    </label>
                    <select name="enquiry_type" id="enquiry_type" class="form-select select2 enquiry_type" {{ $required
                        }}>
                        <option value="">--Please Select--</option>
                        <option {{ $row->enquiry_type == "PHONE" ? 'selected' : '' }} value="PHONE">PHONE</option>
                        <option {{ $row->enquiry_type == "EMAIL" ? 'selected' : '' }} value="EMAIL">EMAIL</option>
                        <option {{ $row->enquiry_type == "PERSON" ? 'selected' : '' }} value="PERSON">PERSON</option>
                        <option {{ $row->enquiry_type == "FAX" ? 'selected' : '' }} value="FAX">FAX</option>
                    </select>
                </div>
                @endif

                {{-- Other Info --}}
                @if($val->column_name=='other_info' && $val->active==1)
                <div class="col-md-6">
                    <label class="form-label">
                        @if($required) <span class="text-danger">*</span> @endif Other Info
                    </label>
                    <input type="text" name="other_info" class="form-control" value="{{ $row->other_info }}" {{
                        $required }}>
                </div>
                @endif

                {{-- Remarks --}}
                @if($val->column_name=='remarks' && $val->active==1)
                <div class="col-md-6">
                    <label class="form-label">
                        @if($required) <span class="text-danger">*</span> @endif Remarks
                    </label>
                    <input type="text" name="remarks" class="form-control" value="{{ $row->remarks }}" {{ $required }}>
                </div>
                @endif
                @endforeach
            </div>
        </div>

        <!--Lines Start-->
        <div class="row mt-4">
            <div class="col-12 linetable">
                <div id="preview-area" class="table-responsive">
                    <table class="table table-bordered clone_table">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 80px;">Line No</th>
                                <th class="pdtdiv">Product </th>
                                <th class="pdtsearch_div"></th>
                                <th class="pdtdes_div">Product Description</th>
                                <?php if ($row->enquiry_type_id != "LABOUR") { ?>
                                    <th>Supplier Part No</th>
                                <?php } ?>
                                <th>Uom Code </th>
                                <th>Qty</th>
                                <th>Promised Date</th>
                                <th>
                                    <p>Comments</p>
                                </th>
                                <th style="width: 60px;"></th>
                            </tr>
                        </thead>
                        <tbody class="clone_lines_body">
                            @if(count($linedata) > 0)
                            @foreach($linedata as $key => $value)
                            <tr class="line-row">
                                <td>
                                    <input type="hidden" name="bulk_enquiry_line_id[]"
                                        class="form-control input-sm bulk_enquiry_line_id"
                                        value="{{ $value->enquiry_line_id }}">
                                    <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no"
                                        value="{{ $key + 1 }}" readonly="readonly">
                                </td>
                                <td class="pdtdiv">
                                    <select name="bulk_product_id[]" class="select2 bulk_product_id  parsley-validated"
                                        tabindex="12" required="required">{!! $value->product_id !!}</select>
                                </td>
                                <td class="pdtsearch_div"><i class="fa fa-search productsearch"></i></td>

                                <td class="pdtdes_div">
                                    <input type="text" name="bulk_product_description[]" tabindex="13"
                                        class="form-control input-sm bulk_product_description  input_qty_width"
                                        value="{{ $value->product_description }}">
                                </td>
                                <?php if ($row->enquiry_type_id != "LABOUR") { ?>
                                    <td class="partdiv">
                                        <select name="bulk_part_no[]" class="select2 bulk_part_no" tabindex="14">{!!
                                            $value->part_no !!}</select>
                                    </td>
                                <?php } ?>
                                <td class="uomdiv">
                                    <select name="bulk_uom_code_id[]" tabindex="15" id="bulk_uom_code_id"
                                        class="select2 bulk_uom_code_id">{!! $value->uom_code_id !!}</select>
                                </td>

                                <td>
                                    <input type="text" name="bulk_qty[]" tabindex="16"
                                        class="form-control input-sm bulk_qty input_qty_width" value="{{ $value->qty }}"
                                        minlength="1" required="required">
                                </td>

                                <td>
                                    <input type="text" name="bulk_promised_date[]" tabindex="17"
                                        class="form-control datepicker input-sm bulk_promised_date"
                                        value="{{ $value->promised_date }}">
                                </td>
                                <td>
                                    <input type="text" name="bulk_comments[]" tabindex="18"
                                        class="form-control bulk_comments " value="{{ $value->comments }}"
                                        required="required">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-danger remove-row">
                                        <i class="fas fa-minus-circle"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr class="line-row">
                                <td>
                                    <input type="hidden" name="bulk_enquiry_line_id[]"
                                        class="form-control input-sm bulk_enquiry_line_id" value="">
                                    <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no "
                                        value="1" readonly="readonly">
                                </td>
                                <td class="pdtdiv">
                                    <select name="bulk_product_id[]" tabindex="12"
                                        class="select2 bulk_product_id  parsley-validated" required="required">{!!
                                        $product_id !!}</select>
                                </td>
                                <td class="pdtsearch_div"><i class="fa fa-search productsearch"></i></td>
                                <td class="pdtdes_div">
                                    <input type="text" name="bulk_product_description[]" tabindex="13"
                                        class="form-control input-sm bulk_product_description input_qty_width" value="">
                                </td>
                                <?php if ($row->enquiry_type_id != "LABOUR") { ?>
                                    <td class="partdiv">
                                        <select name="bulk_part_no[]" class="select2 bulk_part_no" tabindex="14">{!!
                                            $part_no !!}</select>
                                    </td>
                                <?php } ?>
                                <td class="uomdiv">
                                    <select name="bulk_uom_code_id[]" id="bulk_uom_code_id" tabindex="15"
                                        class="select2 bulk_uom_code_id">{!! $uom_code_id !!}</select>
                                </td>

                                <td>
                                    <input type="text" name="bulk_qty[]" tabindex="16"
                                        class="form-control input-sm bulk_qty input_qty_width" value="" minlength="1"
                                        required="required">
                                </td>
                                <div class="dates">
                                    <td>
                                        <input type="text" name="bulk_promised_date[]" tabindex="17"
                                            class="form-control datepicker input-sm bulk_promised_date" value="">
                                    </td>
                                </div>
                                <td>
                                    <input type="text" name="bulk_comments[]" tabindex="18"
                                        class="form-control bulk_comments " value="" required="required">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-danger remove-row">
                                        <i class="fas fa-minus-circle"></i>
                                    </button>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                    <input type="hidden" name="enable-masterdetail" value="true">
                    <input type="hidden" class="form-control input-sm bulk_hidden_date datepicker" value="">

                    <div class="text-end">
                        <button type="button" class="btn btn-success btn-sm add-row">
                            <i class="fas fa-plus-circle"></i> Add Row
                        </button>
                    </div>
                </div>

            </div>
        </div>
        <!-- END -->

        <!-----Submit Function-------->
        <div class="row mt-4 mb-3">
            <div class="col-lg-12 col-md-12">
                <div class="form-group text-center">
                    <input type="hidden" name="submit_type" class="submit_type" id="submit_type">
                    <?php if ($row->enquiry_status == 'DRAFT') { ?>
                        <button name="apply" type="button" class="btn btn-secondary saveform applychangesd px-4 me-2"
                            value="APPLYCHANGES">Draft</button>
                        <button name="submit" type="button" class="btn btn-success px-4 me-2  saveform"
                            value="SAVE">Save</button>
                        <a class='btn btn-danger px-4 me-2' href="{{ url('purchaseenquiry') }}">Cancel</a>
                    <?php } else { ?>
                        <button name="submit" type="button" class="btn btn-success px-4 me-2  saveform"
                            value="SAVE">Save</button>
                        <a class='btn btn-secondary px-4 me-2'
                            href="{{ url('purchaseenquiry') }}">Cancel</a>
                    <?php } ?>
                </div>
            </div>
        </div>
        <input type="hidden" class="pdtindex" value="" />

    </form>
</div>


<!-- Popups -->

<div class="modal fade" id="supplierModal" tabindex="-1" aria-labelledby="supplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0">

            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="supplierModalLabel">
                    <i class="fa fa-truck me-2"></i> Supplier Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div class="table-responsive">

                    <!-- Table -->
                    <table id="supplierTable" class="table table-bordered table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>Supplier Number</th>
                                <th>Supplier Name</th>
                                <th>Supplier Type</th>
                                <th>Supplier Site Name</th>
                                <th>Address</th>
                                <th>Country</th>
                                <th>State</th>
                                <th>City</th>
                                <th>Select</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times me-1"></i> Close
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Product Search Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0">

            <!-- Modal Header -->
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="productModalLabel">
                    <i class="fa fa-cubes me-2"></i> Product Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="productTable" class="table table-bordered table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>Product Code</th>
                                <th>Product Group</th>
                                <th>Product Category</th>
                                <th>Product Name</th>
                                <th>Select</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times me-1"></i> Close
                </button>
            </div>

        </div>
    </div>
</div>


<!-- END -->




@endsection
@push('scripts')

<script>

		// Init select2 on page load
$(function () {
  $('.clone_lines_body').find('select.select2').select2({ width: '100%' });
});

// Add Row
$(document).on('click', '.add-row', function () {
    const $tbody   = $('.clone_lines_body');
    const $lastRow = $tbody.find('tr:last');

    // 1) Destroy select2 on the last row BEFORE cloning
    $lastRow.find('select.select2').each(function () {
        if ($.fn.select2 && $(this).hasClass("select2-hidden-accessible")) {
            $(this).select2('destroy');
        }
    });

    // 2) Clone the cleaned row
    const $newRow = $lastRow.clone(false, false);


    // 4) Append cloned row
    $tbody.append($newRow);

    // 5) Re-init select2 on ALL location selects inside the tbody
    $tbody.find('select.select2').select2({ width: '100%' });

    // 6) Update line numbers
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


    $(document).ready(function () {
        /*  Purpose For:Up/down/left/right arrow navigation start*******/
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

        /* Purpose for Description Mantatory In Labour Condition*/
        <?php if ($row->enquiry_type_id == "LABOUR") { ?>
            $('.bulk_product_description').attr('required', true);
        <?php } ?>


        /*Numeric Validation*/
        $(document).on('keypress', '.bulk_qty', function (ev) {
            var regex = new RegExp("^[0-9.]+$");
            var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
            if (regex.test(str)) {
                return true;
            }
            ev.preventDefault();
            return false;
        });
        /*End*/

        /*copy past validation*/
        $('.bulk_qty').bind("cut copy paste", function (e) {
            e.preventDefault();
        });

        /*Purpose For Default Organization & User*/
        var organization = '<?php echo Session::get('organization'); ?>';
        $('.organization_id').val(organization).change();
        var user = '<?php echo Session::get('id'); ?>';
        $('.created_by').val(user).change();
        $(".create_by").html($('.created_by option:selected').text());
        $('.org').html($('.organization_id option:selected').text());

        /* Purpose For Readonly*/
        $('.uomdiv').css("pointer-events", "none");
        $('.partdiv').css("pointer-events", "none");






        /* Purpose For Display Same Date for Multiple Rows **/
        $(document).on('change', '.bulk_promised_date0', function () {
            var promised_date = $(this).val();
            var hide_date = $('.bulk_hidden_date').val();
            $(".bulk_promised_date").each(function (indexs) {
                var dates = $('.bulk_promised_date' + indexs).val();
                if (hide_date == dates || dates == "") {
                    $('.bulk_promised_date' + indexs).val(promised_date);
                }
                $('.bulk_hidden_date').val(promised_date);
            });
        });


        /* Purpose for Add row Function*/
        var data = "{{\Session::get('j_date_format')}}";
        $(".add_row").on('click', function () {
            var form = $('#purchaseenquiry_form');
            form.parsley().destroy();
        });


        /* Purpose for Supplier & Product search*/
        $('.suppliersearch').click(function () {
            $('#supplierModal').modal('show');
            $('#supplierModal').width("100%");
        });
        $('.productsearch').click(function () {
            var index = ($(this).closest('tr').index());
            $('.pdtindex').val(index);
            $('#productModal').modal('show');
            $('#productModal').width("100%");
        });



        /* Purpose for Load Uom Code Based On Product*/
        $(document).on('change', '.bulk_product_id', function () {
            var product_id = $(this).val();
            var supplier_id = $('.supplier_id').select2('val');
            var index = ($(this).closest('tr').index());
            if (supplier_id != ' ') {
                if (product_id != '') {

                    var pdtcount = pdtcheck(product_id, index);
                    if (pdtcount <= 0) {
                        var url = "{{ URL::to('poenquiryuom') }}/" + product_id;
                        $.get(url, function (data) {
                            var data = $.trim(data);
                            $('.bulk_uom_code_id' + index).val(data).trigger('change');
                        });
                    }
                    else {
                        var msg = $(".bulk_product_id" + index + ' option:selected').text();
                        var message = '<span style="color:#fdff65">' + msg + '</span>' + ' Product Already Selected';
                        showCustomAlert(message, 'info');
                        rowdataEmpty(index);
                    }
                }
                else {

                    $('.bulk_uom_code_id' + index).val('').change();
                }
            }
            else {
                showCustomAlert("Please Select a Supplier", 'info');
                $(".bulk_product_id" + index).val('').change();

            }
        });


        $(document).on('change', '.supplier_id', function () {
            $('.bulk_product_id').trigger('change');
        });
        /*Karthigaa Purpose for Load Product& Supplier Based Part No*/
        $(document).on('change', '.bulk_product_id', function () {
            var product_id = $(this).select2('val');
            var supplier_id = $('.supplier_id').select2('val');
            var index = ($(this).closest('tr').index());
            if (product_id != '' && supplier_id != '') {
                var index = ($(this).closest('tr').index());
                var pdtcount = pdtcheck(product_id, index);
                if (pdtcount <= 0) {
                    var url = "{{URL::to('getpartno')}}?supplier_id=" + supplier_id + "&product_id=" + product_id;
                    $.get(url, function (response) {
                        console.log(response);
                        var data = $.trim(response);

                        if (data != '') {
                            $('.bulk_part_no' + index).select2('val', [data]);
                        }
                        else {
                            $('.bulk_part_no' + index).select2('val', ['']);
                        }
                    });
                }
                else {
                    var msg = $(".bulk_product_id" + index + ' option:selected').text();
                    var message = '<span style="color:#fdff65">' + msg + '</span>' + ' Product Already Selected';
                    showCustomAlert(message, 'info');
                    rowdataEmpty(index);
                }
            }
        });
        /*End*/


        /**** To Empty the Rowdata when product Empty ********/
        function rowdataEmpty(index) {
            $(".bulk_product_id" + index).val('').change();
            $(".bulk_uom_code_id" + index).val('').change();
            $(".bulk_qty" + index).val('');
            $(".bulk_promised_date" + index).val('');
            $(".bulk_comments" + index).val('');
            $(".bulk_qty" + index).trigger('change');
        }
        // purpose to get Supplier Site based on Supplier
        $(document).on('change', '.supplier_id', function () {
            var supplier = $('.supplier_id').val();
            $.get("{{ URL::to('supplierpricelist') }}/" + supplier, function (suppdata) {
                var data = $.trim(suppdata);
                if (data != 0) {

                    var condition = 'supplier_id=' + supplier;
                    $.ajax({
                        url: "{{ URL::to('jcomboform') }}",
                        type: "GET",
                        data: {
                            table: "m_supplier_sites_t:supplier_site_id:supplier_site_number|supplier_site_name",
                            order_by: "supplier_site_name asc",
                            parent: condition
                        },
                        success: function (data) {
                            var $dropdown = $(".suppliersite_id");
                            $dropdown.empty(); // Clear previous options

                            // Add default placeholder
                            $dropdown.append('<option value="">-- Select Supplier Site --</option>');

                            // Parse JSON if needed
                            if (typeof data === "string") {
                                try {
                                    data = JSON.parse(data);
                                } catch (e) {
                                    console.error("Invalid JSON response:", data);
                                    return;
                                }
                            }

                            // Populate dropdown
                            $.each(data, function (i, item) {
                                let selected = item.val == suppdata['supplier_site_id'].toString() ? 'selected' : '';
                                $dropdown.append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
                            });

                            // Trigger change for any dependent logic
                            $dropdown.trigger('change.select2');
                        },
                        error: function (xhr, status, error) {
                            console.error("Error fetching supplier sites:", error);
                        }
                    });


                }
                else {
                    $(".suppliersite_id").val('').change();
                }
            });
        });


        /* Purpose for Supplier Search*/
        $(document).ready(function () {
            var table = $('#supplierTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('getSupplierData') }}",
                    data: function (d) {
                        d.supplier_name = $('#filter_supplier_name').val();
                        d.suppliertype_name = $('#filter_suppliertype').val();
                        d.country_name = $('#filter_country').val();
                        d.state_name = $('#filter_state').val();
                        d.city_name = $('#filter_city').val();
                    }
                },
                columns: [
                    { data: 'supplier_number', name: 'supplier_number' },
                    { data: 'supplier_name', name: 'supplier_name' },
                    { data: 'suppliertype_name', name: 'suppliertype_name' },
                    { data: 'supplier_site_name', name: 'supplier_site_name' },
                    { data: 'address', name: 'address' },
                    { data: 'country_name', name: 'country_name' },
                    { data: 'state_name', name: 'state_name' },
                    { data: 'city_name', name: 'city_name' },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function (data) {
                            return `<button class="btn btn-primary btn-sm select-supplier" 
                                data-id="${data.supplierid}" 
                                data-address="${data.address}">
                                Select
                            </button>`;
                        }
                    }
                ],
                pageLength: 10
            });

            // Filter event
            $('#filter_supplier_name, #filter_suppliertype, #filter_country, #filter_state, #filter_city').on('keyup change', function () {
                table.draw();
            });

            // Refresh filters
            $('#resetFilters').click(function () {
                $('#filter_supplier_name, #filter_suppliertype, #filter_country, #filter_state, #filter_city').val('');
                table.draw();
            });

            // Select Supplier button
            $('#supplierTable').on('click', '.select-supplier', function () {
                var supplierId = $(this).data('id');
                var address = $(this).data('address');

                $('.supplier_id').val(supplierId).change();
                $('#supplierModal').modal('hide');
            });
        });



        /* Purpose For Product Search*/

        $(document).ready(function () {
            var productTable = $('#productTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('getProductgridData') }}",
                    data: function (d) {
                        d.group_name = $('#filter_group').val();
                        d.category_name = $('#filter_category').val();
                        d.product_name = $('#filter_name').val();
                    }
                },
                columns: [
                    { data: 'product_code', name: 'product_code' },
                    { data: 'group_name', name: 'group_name' },
                    { data: 'category_name', name: 'category_name' },
                    { data: 'concatenated_product', name: 'concatenated_product' },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function (data) {
                            return `<button class="btn btn-success btn-sm select-product"
                                data-id="${data.product_id}"
                                data-name="${data.concatenated_product}">
                                Select
                            </button>`;
                        }
                    }
                ],
                pageLength: 10
            });

            // Filter events
            $('#filter_group, #filter_category, #filter_name').on('keyup change', function () {
                productTable.draw();
            });

            // Refresh filters & reload dropdown options
            $('#resetProductFilters').click(function () {
                $('#filter_group, #filter_category, #filter_name').val('');
                productTable.draw();

                // Reload bulk_product_id dropdown like jqGrid refresh
                $.get("{{ URL::to('productgroupid') }}?module_name=purchaseenquiry", function (data) {
                    var condition = 'product_group_id in(' + data + ')';
                    $(".bulk_product_id").jCombo(
                        "{{ URL::to('jcomboform?table=m_products_t:product_id:concatenated_product') }}&order_by=product_id asc" + '&parent=' + condition,
                        { selected_value: "" }
                    );
                });
            });

            // Select Product
            $('#productTable').on('click', '.select-product', function () {
                var index = $('.pdtindex').val();
                var productId = $(this).data('id');
                var productName = $(this).data('name');

                if (pdtcheck(productId, index) <= 0) {
                    $('.bulk_product_id' + index).val(productId).trigger('change');
                    $('#productModal').modal('hide');
                } else {
                    var message = `<span style="color:#fdff65">${productName}</span> Product Already Selected`;
                    showCustomAlert(message,'info');
                    rowdataEmpty(index);
                    $('#productModal').modal('hide');
                }
            });
        });




        /* Purpose For Product Require Based on Enquiry Type*/
        <?php if ($row->enquiry_type_id == "STANDARD") { ?>
            $('.pdtdes_div').addClass('hide');
        <?php } else { ?>
            $('.bulk_product_id').removeAttr('required');
        <?php } ?>



        //   save function
        $(document).on('click', '.saveform', function () {
            var btnval = $(this).val();
            if (btnval == 'APPLYCHANGES') {
                $("#enquiry_status").val('DRAFT');
            }
            else if (btnval == 'DRAFT') {
                $("#enquiry_status").val('DRAFT');
            }
            else {
                $("#enquiry_status").val('INITIATED');
            }
            var type = $('.enquiry_type_id').val();
            var url = "{{ url('purchaseenquirysave') }}";
            var red_url = "{{ url($return_url) }}";
            var create_url = "{{ url('purchaseenquirycreate') }}/0" + '/' + type;

            var form = $('#purchaseenquiry_form');
            if (btnval != 'APPLYCHANGES') {
                form.parsley().validate();
                var form = $('#purchaseenquiry_form');
                qtyrequired();
                form.parsley().validate();

                if (form.parsley().isValid()) {

                    var formdata = $('#purchaseenquiry_form').serialize();
                    $.post(url, formdata, function (data) {

                        var status = data.status;
                        var msg = data.message;
                        var id = data.id;
                        var auto_no = data.auto_no;

                        if (btnval != 'SAVE' && btnval != 'DRAFT') {
                            showCustomAlert(msg, status);

                            window.location.href = create_url;

                        }
                        else {
                            showCustomAlert(status, msg);

                            window.location.href = red_url;

                        }
                    });
                }
            }
            else {

                var formdata = $('#purchaseenquiry_form').serialize();
                $.post(url, formdata, function (data) {
                    var status = data.status;
                    var msg = data.message;
                    var id = data.id;

                    var edit_url = "{{ url('purchaseenquirycreate') }}/" + id;

                    var edit_url = "{{ url('purchaseenquirycreate') }}/" + id + '/' + type;

                    showCustomAlert(msg, status);

                    window.location.href = edit_url;


                });
            }
        });

        function qtyrequired() {
            $(".bulk_qty").each(function (index) {
                var qty = $(this).val();
                if (qty == 0) {
                    $(".bulk_qty" + index).val('');
                }

            });
        }

    });


</script>


@endpush