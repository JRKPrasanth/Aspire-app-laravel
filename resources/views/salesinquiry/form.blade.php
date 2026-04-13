@extends('layouts.header')
@section('content')
    <h3 class="text-danger">

        <?php if (($row->inquiry_no) != '') { ?>
        Sales Enquiry ({{ $row->inquiry_no }} )
        <?php } else { ?>
        Sales Enquiry (NEW)
        <?php } ?>


    </h3>
    @include('layouts.breadcrumb')



    <form method="post" action="" id="salesinquiry" data-parsley-validate>
        <input type="hidden" value="" name="savestatus" id="savestatus" /> {{ csrf_field() }}

        <div class="card shadow-lg rounded-4 border-0" id="spy1">
            <div class="card-header bg-primary bg-gradient text-white rounded-top-4">
                <div class="row text-center text-md-start">


                    <!--  for data save purpose  -->
                    <div class="form-group row" style="display:none;">
                        <label for="inputIsValid" class="form-control-label col-md-4">Inquiry Type</label>
                        <div class="col-md-6">
                            <select type="text" name="inquiry_type" id="inquiry_type" class="form-control inquiry_type">
                                <option value="">--select--</option>
                                <option <?php if ($row->inquiry_type == "STANDARD") {
        echo "selected";
    } else {
        echo "";
    } ?>
                                    value="STANDARD">
                                    STANDARD</option>
                                <option <?php if ($row->inquiry_type == "LABOUR") {
        echo "selected";
    } else {
        echo "";
    } ?>
                                    value="LABOUR">
                                    LABOUR</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                    <div class="form-group row" style="display:none;">
                        <label for="inputIsValid" class="form-control-label col-md-4">Inquiry Date</label>
                        <div class="col-md-6">
                            <div class="input-group form_date col-md-12" data-date="" data-date-format="dd MM yyyy"
                                data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <input class="form-control datepicker inquiry_date" id="inquiry_date" name="inquiry_date"
                                    size="16" type="text" value="{{ $row->inquiry_date }}" readonly>
                            </div>
                            <input type="hidden" id="inquiry_date" value="{{ $row->inquiry_date }}" />
                        </div>
                        <div class="col-md-2 showinline">
                        </div>
                    </div>

                    <div class="form-group row" style="display:none;">
                        <label for="inputIsValid" class="form-control-label col-md-4">Inquiry No</label>
                        <div class="col-md-6">
                            <input class="form-control so_inquiry_hdr_id" id="so_inquiry_hdr_id" name="so_inquiry_hdr_id"
                                size="16" type="hidden" value="{{ $row->so_inquiry_hdr_id }}" readonly>
                            <input type="text" id="inquiry_no" name="inquiry_no" class="form-control inquiry_no"
                                value="{{ $row->inquiry_no }}" readonly>
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>

                    <div class="form-group row" style="display:none;">
                        <label for="inputIsValid" class="form-control-label col-md-4">Created By</label>
                        <div class="col-md-6">
                            <select name='created_by' rows='5' class='form-control created_by' data-show-subtext="true"
                                data-live-search="true">
                                {!! $created_by !!}
                            </select>
                        </div>
                        <div class="col-md-2 showinline">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body card-block">
                <div class="row g-4">
                    <!-- Customer Section -->
                    <div class="col-md-4">
                        <div class="row mb-3">
                            <label class="col-form-label col-md-4">
                                <span class="text-danger">*</span> Customer
                            </label>
                            <div class="col-md-7" style="pointer-events:none;">
                                <select name="customerid" class="select2 customer_id w-100" required>
                                    {!! $customer_id !!}
                                </select>
                            </div>
                            <div class="col-md-1 d-flex align-items-center">
                                <i class="fa fa-search customersearch me-2"></i>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-form-label col-md-4">Enquiry Source</label>
                            <div class="col-md-8">
                                <select name="source_type_id" class="form-control source_type_id select2">
                                    {!! $source_type_id !!}
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Project Section -->
                    <div class="col-md-4">
                        <div class="row mb-3">
                            <label class="col-form-label col-md-4">Project Name</label>
                            <div class="col-md-8">
                                <select name="project_id" class="select2 project_id w-100">
                                    {!! $project_id !!}
                                </select>
                            </div>
                        </div>

                        <!-- Hidden: Created By -->
                        <div class="row mb-3 d-none">
                            <label class="col-form-label col-md-4">Created By</label>
                            <div class="col-md-8">
                                <select name="created_by" class="form-control created_by">
                                    {!! $created_by !!}
                                </select>
                            </div>
                        </div>

                        <!-- Hidden: Inquiry Status -->
                        <div class="row mb-3 d-none">
                            <label class="col-form-label col-md-4">Inquiry Status</label>
                            <div class="col-md-8">
                                <select name="inquiry_status" class="form-control inquiry_status" id="inquiry_status">
                                    <option value="">-- Please Select --</option>
                                    <option value="DRAFT" {{ $inquiry_status == "DRAFT" ? 'selected' : '' }}>DRAFT</option>
                                    <option value="INITIATED" {{ $inquiry_status == "INITIATED" ? 'selected' : '' }}>INITIATED
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Remarks Section -->
                    <div class="col-md-4">
                        <div class="row mb-3">
                            <label class="col-form-label col-md-4">Remarks</label>
                            <div class="col-md-8">
                                <input type="text" name="remarks" class="form-control remarks" value="{{ $row->remarks }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Details -->
                <h5 class="myheaders mt-4 text-primary">Additional Details</h5>
                <div class="row g-4 mt-4">
                    @php $i = 0;
                    $j = 0; @endphp
                    @foreach($enabled_columns as $val)
                        @php $required = $val->action == '1' ? 'required' : ''; @endphp
                        @if($val->active == 1)
                            <div class="col-md-4">
                                @if($val->column_name == 'tender_id')
                                        <div class="row mb-3">
                                            <label class="col-form-label col-md-4">
                                                @if($required)<span class="text-danger">*</span>@endif Tender Id
                                            </label>
                                            <div class="col-md-8">
                                                <input type="text" name="tender_id" class="form-control" value="{{ $row->tender_id }}" {{
                                    $required }}>
                                            </div>
                                        </div>
                                @endif

                                @if($val->column_name == 'tender_ref_no')
                                    <div class="row mb-3">
                                        <label class="col-form-label col-md-4">
                                            @if($required)<span class="text-danger">*</span>@endif Tender Ref No
                                        </label>
                                        <div class="col-md-8">
                                            <input type="text" name="tender_ref_no" class="form-control"
                                                value="{{ $row->tender_ref_no }}" {{ $required }}>
                                        </div>
                                    </div>
                                @endif

                                @if($val->column_name == 'submission_duedate')
                                    <div class="row mb-3">
                                        <label class="col-form-label col-md-4">
                                            @if($required)<span class="text-danger">*</span>@endif Submission Due Date
                                        </label>
                                        <div class="col-md-8">
                                            <input type="text" name="submission_duedate" class="form-control start_date"
                                                value="{{ $row->submission_duedate }}" {{ $required }}>
                                        </div>
                                    </div>
                                @endif

                                @if($val->column_name == 'emd_details')
                                    <div class="row mb-3">
                                        <label class="col-form-label col-md-4">
                                            @if($required)<span class="text-danger">*</span>@endif EMD Details
                                        </label>
                                        <div class="col-md-8">
                                            <input type="text" name="emd_details" class="form-control" value="{{ $row->emd_details }}"
                                                {{ $required }}>
                                        </div>
                                    </div>
                                @endif

                                @if($val->column_name == 'tittle_of_work')
                                    <div class="row mb-3">
                                        <label class="col-form-label col-md-4">
                                            @if($required)<span class="text-danger">*</span>@endif Title Of Work
                                        </label>
                                        <div class="col-md-8">
                                            <input type="text" name="tittle_of_work" class="form-control"
                                                value="{{ $row->tittle_of_work }}" {{ $required }}>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>



            <!------------------------- clone row End-------------------------------->
            <div class="row mt-4">
                <div class="col-md-12">

                    <div id="preview-area" class="table-responsive">
                        <table class="table table-bordered clone_table">

                            <thead class="table-light">
                                <tr>

                                    <th>Line No</th>
                                    <th class="pdtdiv">Product</th>
                                    <th class="">Customer Part No</th>
                                    <th>Uom Code </th>
                                    <?php if ($row->inquiry_type == "LABOUR") { ?>
                                    <th class="pdtdes_div">Product Description</th>
                                    <?php } ?>

                                    <th>Required Qty</th>
                                    <th>Need By Date</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="clone_lines_body">
                                <?php if (count($linedata) >= 1) { ?>
                                @foreach($linedata as $key => $value)
                                                    <tr class="rcopy clone">
                                                        <td>
                                                            <input type="hidden" name="bulk_so_inquiry_lines_id[]"
                                                                class="form-control  bulk_so_inquiry_lines_id"
                                                                value="{{ $value->so_inquiry_lines_id }}">

                                                            <input type="text" name="bulk_line_no[]" class="form-control  bulk_line_no"
                                                                value="{{ $key + 1 }}" readonly="readonly">
                                                        </td>

                                                        <td class="pdtdiv">
                                                            <select name="bulk_product_id[]" id="bulk_product_id"
                                                                class="bulk_product_id   select2 parsley-validated" required>{!!
                                    $value->product_id !!}</select>
                                                        </td>
                                                        <td class="pdtdiv" style="pointer-events:none;">
                                                            <select name="bulk_part_no[]" id="bulk_part_no"
                                                                class="bulk_part_no select2 parsley-validated">{!! $value->part_no !!}</select>
                                                        </td>
                                                        <td class="uom">
                                                            <select name="bulk_uom_code_id[]" id="bulk_uom_code_id"
                                                                class="select2 bulk_uom_code_id" data-show-subtext="true"
                                                                data-live-search="true">
                                                                <option value=''> {!! $value->uomcode_id !!} </option>

                                                            </select>
                                                        </td>
                                                        <?php        if ($row->inquiry_type == "LABOUR") { ?>
                                                        <td class="pdtdes_div">
                                                            <input type="text" name="bulk_product_description[]"
                                                                class="form-control  bulk_product_description input_qty_width"
                                                                value="{{ $value->product_description }}" required="required">
                                                        </td>
                                                        <?php        } ?>


                                                        <td>
                                                            <input type="text" name="bulk_required_qty[]"
                                                                class="form-control  bulk_required_qty input_qty_width"
                                                                value="{{ $value->required_qty }}" required="required">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="bulk_need_by_date[]"
                                                                class="form-control  bulk_need_by_date" value="{{ $value->need_by_date }}">
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
                                        <input type="hidden" name="bulk_so_inquiry_lines_id[]"
                                            class="form-control  bulk_so_inquiry_lines_id" value="">

                                        <input type="text" name="bulk_line_no[]" class="form-control  bulk_line_no"
                                            value="1" readonly="readonly">
                                    </td>

                                    <td class="pdtdiv">
                                        <select name="bulk_product_id[]" id="bulk_product_id"
                                            class="bulk_product_id select2 parsley-validated">{!! $product !!}</select>
                                    </td>
                                    <td class="pdtdiv" style="pointer-events:none;">
                                        <select name="bulk_part_no[]" id="bulk_part_no" class="bulk_part_no select2 ">{!!
            $part_no !!}</select>
                                    </td>


                                    <td class="uom">
                                        <select name="bulk_uom_code_id[]" id="bulk_uom_code_id"
                                            class="select2 bulk_uom_code_id" data-show-subtext="true"
                                            data-live-search="true">
                                            {!! $uomcode_id !!}
                                        </select>
                                    </td>
                                    <?php    if ($row->inquiry_type == "LABOUR") { ?>
                                    <td class="pdtdes_div">
                                        <input type="text" name="bulk_product_description[]"
                                            class="form-control  bulk_product_description input_qty_width" value=""
                                            required="required">
                                    </td>
                                    <?php    } ?>

                                    <td>
                                        <input type="text" name="bulk_required_qty[]"
                                            class="form-control  bulk_required_qty input_qty_width" value=""
                                            required="required">
                                    </td>
                                    <td>
                                        <div class="input-group m-b">
                                            <input type="text" name="bulk_need_by_date[]"
                                                class="form-control bulk_need_by_date" />
                                        </div>
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
                        <input type="hidden" class="form-control input-sm bulk_hidden_date datepicker" value="">
                    </div>
                </div>
            </div>


            <div class="row mt-4 mb-3">
                <div class="col-lg-12 col-md-12">
                    <div class="form-group text-center">

                        <button type="button" class="btn btn-secondary saveform px-4 me-2"
                            value="APPLYCHANGES">Draft</button>
                        <button type="button" class="btn btn-success saveform px-4 me-2" value="SAVE">Save</button>
                        <a type="button" class="btn btn-danger px-4 me-2" href="{{url('salesinquiry')}}">Cancel</a>
                    </div>
                </div>
            </div>


            <!--  Popups  --->
            <!-- Customer Search Modal -->
            <div class="modal fade" id="customerModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content shadow-lg rounded-4 border-0">

                        <!-- Modal Header -->
                        <div class="modal-header bg-primary bg-gradient text-white rounded-top-4">
                            <h5 class="modal-title">
                                <i class="bi bi-people-fill me-2"></i> Customer Details
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <!-- Modal Body -->
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table id="customerTable" class="table table-striped table-hover w-100">
                                    <thead>
                                        <tr>
                                            <th>Customer Number</th>
                                            <th>Customer Name</th>
                                            <th>Customer Type</th>
                                            <th>Customer Site Name</th>
                                            <th>Site Type</th>
                                            <th>Address</th>
                                            <th>City</th>
                                            <th>State</th>
                                            <th>Country</th>
                                            <th>Select</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>

                        </div>

                        <!-- Modal Footer -->
                        <div class="modal-footer bg-light rounded-bottom-4">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-1"></i> Close
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
                                <i class="bi bi-box-seam"></i> Product Details
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
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle"></i> Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!--end-->


            <input type="hidden" class="pdtindex" value="" />
        </div>
    </form>

@endsection
@push('scripts')


    <script>

        $(document).ready(function () {

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


            var user = '<?php echo Session::get('id'); ?>';
            $('.created_by').val(user).change();

            $(".create_by").html($('.created_by option:selected').text());

            var data = "{{\Session::get('j_date_format')}}";


            /* Sales Inquiry Validation check Start  */
            $(document).on('keypress', '.bulk_required_qty', function (ev) {
                var regex = new RegExp("^[0-9.]+$");
                var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                if (regex.test(str)) {
                    return true;
                }
                ev.preventDefault();
                return false;
            });

            /*copy paste validation*/

            $('.bulk_required_qty').bind("cut copy paste", function (e) {
                e.preventDefault();
            });
            /*copy paste validation*/

            function qtyrequiredvalid() {
                $('.bulk_required_qty').each(function (i) {
                    var val = $(this).val();
                    if (val == 0) {
                        $('.bulk_required_qty' + i).val('');
                    }
                });
            }


            /* Sales Inquiry Validation Start  */
            $(document).on('keypress', '.bulk_required_qty', function (ev) {

                var regex = new RegExp("^[0-9.]+$");
                var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                if (regex.test(str)) {
                    return true;
                }
                ev.preventDefault();
                return false;
            });


            /* Sales Inquiry Save Start  */
            $('#savestatus').val('');
            $(document).on('click', '.saveform', function () {

                var btnval = $(this).val();
                if (btnval == 'APPLYCHANGES')
                    $("#inquiry_status").val('DRAFT');
                else
                    $("#inquiry_status").val('INITIATED');


                if (btnval == 'APPLYCHANGES')
                    var savestatus = 'DRAFT';
                else if (btnval == 'SAVE')
                    var savestatus = 'SAVE';
                var type = "{{$row->inquiry_type}}";
                $('#savestatus').val(savestatus);
                var url = "{{ url('salesinquirysave') }}";
                var red_url = "{{ url($pageMethod) }}";
                var create_url = "{{ url('salesinquirycreate') }}/0/" + type;
                qtyrequiredvalid();

                var form = $('#salesinquiry');
                if (btnval != 'APPLYCHANGES') {

                    var form = $('#salesinquiry');
                    form.parsley().validate();

                    if (form.parsley().isValid()) {

                        var formdata = $('#salesinquiry').serialize();
                        $.post(url, formdata, function (data) {
                            var status = data.status;
                            var msg = data.message;
                            var id = data.id;
                            var edit_url = "{{ url('salesinquirycreate') }}/" + id;
                            if (btnval != 'SAVE' && btnval != 'DRAFT') {
                                showCustomAlert(msg, 'success');
                                window.location.href = create_url;

                            } else {
                                showCustomAlert(msg, 'success');
                                var url = "{{ url('salesinquiry') }}";
                                window.location.href = url;
                            }
                        });
                    }
                } else {

                    var formdata = $('#salesinquiry').serialize();
                    $.post(url, formdata, function (data) {

                        var status = data.status;
                        var msg = data.message;
                        var id = data.id;
                        var edit_url = "{{ url('salesinquirycreate') }}/" + id;
                        showCustomAlert(msg, 'success');
                        window.location.href = edit_url;

                    });
                }
            });



            /* Sales Inquiry Product Detail Start  */
            $(document).on('change', '.bulk_product_id', function () {

                var $row = $(this).closest('tr');
                var product_id = $(this).val();
                var customer_id = $('.customer_id').val();
                var plid = 0;
                var type = 'so';

                if (!customer_id) {
                    showCustomAlert('Please Select Customer', "warning");
                    $(this).val('').trigger('change');
                    $row.find('.bulk_required_qty').val('');
                    return;
                }

                if (!product_id) {
                    $row.find('.bulk_uom_code_id').val('').trigger('change');
                    $row.find('.bulk_required_qty').val('');
                    return;
                }

                // Duplicate check
                var exists = false;
                $('.bulk_product_id').not(this).each(function () {
                    if ($(this).val() == product_id) {
                        exists = true;
                    }
                });

                if (exists) {
                    showCustomAlert('Product Already Selected', "info");
                    $(this).val('').trigger('change');
                    return;
                }

                var url = "{{ url('productdetails_so') }}/" + product_id + "/" + plid + '/' + customer_id + '/' + type;

                $.get(url, function (data) {

                    $row.find('.bulk_uom_code_id')
                        .val(data.uom_code_id)
                        .trigger('change');

                    $row.find('.bulk_required_qty').val('');

                    $row.find('.bulk_part_no')
                        .val(data.manufactpartno)
                        .trigger('change');
                });

            });




            function pdtcheck(product_id, index) {
                var pdtcount = 0;
                $('.clone').each(function (ind, v) {
                    var val = $(".bulk_product_id" + ind).val();
                    if (index != ind) {
                        if (val == product_id) {
                            pdtcount++;
                        }
                    }
                });
                return pdtcount;
            }


            /* Sales Inquiry Show Pop Model Start  */
            $('.customersearch').click(function () {
                $('#customerModal').modal('show');
                $('#customerModal').width("100%");
            });


            /* Sales Inquiry date Start  */
            $(document).on('change', '.bulk_need_by_date', function () {

                var selectedDate = $(this).val();

                if (!selectedDate) return;

                $('.bulk_need_by_date').each(function () {

                    if ($(this).val() === '' || $(this).val() === $('.bulk_hidden_date').val()) {
                        $(this).val(selectedDate);
                    }

                });

                $('.bulk_hidden_date').val(selectedDate);
            });




            /*Jqgrid  Sales Inquiry Load date Start  */
            $(document).ready(function () {
                var table = $('#customerTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ url('getCustomergridData') }}?status=enquiry",
                    columns: [
                        { data: 'customer_number', name: 'customer_number' },
                        { data: 'customer_name', name: 'customer_name' },
                        { data: 'customer_type', name: 'customer_type' },
                        { data: 'customer_site_name', name: 'customer_site_name' },
                        { data: 'site_type', name: 'site_type' },
                        { data: 'address', name: 'address' },
                        { data: 'city_name', name: 'city_name' },
                        { data: 'state_name', name: 'state_name' },
                        { data: 'country_name', name: 'country_name' },
                        {
                            data: 'customer_id',
                            name: 'customer_id',
                            orderable: false,
                            searchable: false,
                            render: function (data, type, row) {
                                return `<button class="btn btn-sm btn-success select-customer"
                                        data-id="${row.customer_id}"
                                        data-address="${row.address}">
                                    <i class="bi bi-check2-circle"></i> Select
                                </button>`;
                            }
                        }
                    ],
                    pageLength: 10,
                    lengthMenu: [10, 20, 50, 100, 1000],
                });

                // Handle row selection
                $('#customerTable').on('click', '.select-customer', function () {
                    var customer_id = $(this).data('id');
                    var address = $(this).data('address');

                    if (customer_id) {
                        $('.customer_id').val(customer_id).trigger('change'); // for select2
                        $('#customerModal').modal('hide');
                    } else {
                        showCustomAlert('info', 'Please Select one row');
                    }
                });

                // Refresh button if you want to reload
                $('.customer_refresh').click(function () {
                    table.ajax.reload();
                });
            });

            /*Jqgrid  Sales Inquiry Load date End  */


            $(document).ready(function () {

                var groupname = "'FINISHED GOODS'";
                var grp = [];
                grp.push(groupname);


                var table = $('#productTable').DataTable({
                    processing: true,
                    serverSide: true,

                    ajax: "{{ URL::to('getProductgridData')}}?prggrp=" + grp,
                    columns: [
                        { data: 'product_code', name: 'product_code' },
                        { data: 'group_name', name: 'group_name' },
                        { data: 'category_name', name: 'category_name' },
                        { data: 'concatenated_product', name: 'concatenated_product' },
                        {
                            data: 'product_id',
                            name: 'product_id',
                            orderable: false,
                            searchable: false,
                            render: function (data, type, row) {
                                return `<button class="btn btn-sm btn-success select-product" data-id="${data}" data-name="${row.concatenated_product}">
                                    <i class="bi bi-check2-circle"></i> Select
                                </button>`;
                            }
                        }
                    ],
                    pageLength: 10,
                    lengthMenu: [10, 20, 50, 100, 250, 500, 1000],
                });

                // Handle Product Select
                $('#productTable').on('click', '.select-product', function () {
                    var product_id = $(this).data('id');
                    var product_name = $(this).data('name');
                    var index = $('.pdtindex').val();

                    var pdtcount = pdtcheck(product_id, index);
                    if (pdtcount <= 0) {
                        $('.bulk_product_id' + index).val(product_id).trigger('change');
                        $('#productModal').modal('hide');
                    } else {
                        var message = `<span style="color:#fdff65">${product_name}</span> Product Already Selected`;
                        showCustomAlert(message, 'info');
                        rowdataEmpty(index);
                        $('#productModal').modal('hide');
                    }
                });

                // Refresh Button (like jqGrid refresh)
                $('.product_refresh').click(function () {
                    table.ajax.reload();
                });
            });



            /**** To Empty the Rowdata when product Empty ********/
            function rowdataEmpty(index) {
                $(".bulk_product_id" + index).val('').change();
                $(".bulk_uom_code_id" + index).val('').change();
                $(".bulk_required_qty" + index).val('');
                $(".bulk_need_by_date" + index).val('');
                $(".bulk_comments" + index).val('');
                $(".bulk_qty" + index).trigger('change');
            }


            /**** To Empty the Rowdata when product Empty End********/
            <?php if ($row->inquiry_type == "STANDARD") { ?>
            $('.pdtdiv').removeClass('hide');
            $('.pdtdes_div').addClass('hide');
            $('.bulk_product_id').attr('required', 'required');
            $('.bulk_product_description').removeAttr('required');
            <?php } else { ?>

            <?php } ?>

        });


        // Add Row
      $(document).on('click', '.add-row', function () {

    const $lastRow = $('.clone_lines_body tr:last');

    // Destroy datepicker in last row before cloning
    $lastRow.find('.bulk_need_by_date').datepicker('destroy');

    const $newRow = $lastRow.clone(false);

    // Clear inputs
    $newRow.find('input[type="text"]').val('');
    $newRow.find('input[type="hidden"]').val('');
    $newRow.find('select').val('');

    // Fix Datepicker duplication issue
    $newRow.find('.bulk_need_by_date')
        .removeClass('hasDatepicker')
        .removeAttr('id');

    // Fix Select2 duplication
    $newRow.find('select.select2').each(function () {
        if ($(this).hasClass("select2-hidden-accessible")) {
            $(this).select2('destroy');
        }
        $(this).next('.select2').remove();
    });

    $('.clone_lines_body').append($newRow);

    // Reinitialize Select2
    $newRow.find('select.select2').select2({
        width: '100%'
    });

    // Reinitialize Datepicker ONLY for new row
    $newRow.find('.bulk_need_by_date').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        minDate: 0,
        showAnim: "slideDown",
        yearRange: "-25:+0"
    });

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


$(document).on("focus", ".bulk_need_by_date", function () {

    if (!$(this).hasClass("hasDatepicker")) {
        $(this).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            minDate: 0,
            showAnim: "slideDown",
            yearRange: "-25:+0"
        });
    }

});


    </script>

@endpush