@extends('layouts.header')
@section('content')
<h3 class="text-danger">

    <?php if ($pageMethod != "salesquoteapproval") { ?>

        <?php include('tools_menu.php');
    }

    if ($row['quote_no'] == '') { ?>

        Sales Quote (NEW)
    <?php } else { ?>
        Sales Quote ({{$row['quote_no']}})
    <?php } ?>

</h3>
@include('layouts.breadcrumb')


<form method="post" action="" id="soquote" data-parsley-validate>
    <input type="hidden" value="" name="savestatus" id="savestatus" />
    <input type="hidden" value="" name="custtype" id="custtype" />
    {{ csrf_field() }}



    <div class="card shadow-lg rounded-4 border-0 mb-4">
        <div class="card-header bg-info bg-gradient text-white rounded-top-4">
        </div>
     <!-- for hidden values purpose -->   
    <div class="form-group row" style="display:none;">
        <label for="inputIsValid" class="form-control-label col-md-4">Quote Status</label>
        <div class="col-md-6">
            <select type="text" name="quote_status" id="quote_status" class="form-control quote_status" value="{{ $row['quote_status'] }}" readonly="readonly" style="pointer-events: none;">
                <option value=""></option>
                <option <?php if($row['quote_status'] =="DRAFT") { echo "selected"; } else { echo ""; } ?> value="DRAFT">DRAFT</option>
                <option <?php if($row['quote_status'] =="INITIATED") { echo "selected"; } else { echo ""; } ?> value="INITIATED">INITIATED</option>
                <option <?php if($row['quote_status'] =="APPROVED") { echo "selected"; } else { echo ""; } ?> value="APPROVED">APPROVED</option>
                <option <?php if($row['quote_status'] =="REJECTED") { echo "selected"; } else { echo ""; } ?> value="REJECTED">REJECTED</option>
            </select>
        </div>
    </div>
 <div class="form-group row" style="display:none;">
        <label for="inputIsValid" class="form-control-label col-md-4">Quote No</label>
        <div class="col-md-6">
            <input class="form-control quote_hdr_id" id="quote_hdr_id" name="quote_hdr_id" size="16" type="hidden" value="{{ $row['quote_hdr_id'] }}" readonly>
            <input class="form-control decimal_point" id="decimal_point"  type="hidden" value="{{ \Session::get('decimal')}}" readonly>
            <input type="text" id="quote_no" name="quote_no" class="form-control quote_no" value="{{ $row['quote_no'] }}" readonly>
        </div>
    </div>
<div class="form-group row" style="display:none;">
        <label for="inputIsValid" class="form-control-label col-md-4">Quote Date</label>
        <div class="col-md-6">
            <div class="input-group date col-md-12">
                <input class="form-control datepicker quote_date" id="quote_date" name="quote_date" size="16" type="text" value="{{ $row['quote_date'] }}">
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
            <input type="hidden" id="quote_date" value="{{ $row['quote_date'] }}" />
        </div>
        <div class="col-md-2 showline">
        </div>
    </div>
    <div class="form-group row" style="display:none;">
        <label for="inputIsValid" class="form-control-label col-md-4">Quote Type</label>
        <div class="col-md-6">
            <select type="text" name="quote_type" id="quote_type" class="form-control quote_type" readonly="readonly" style="pointer-events: none;">
                <option value="">--select--</option>
                <option <?php if($row['quote_type'] =="STANDARD") { echo "selected"; } else { echo ""; } ?> value="STANDARD">STANDARD</option>
                <option <?php if($row['quote_type'] =="LABOUR") { echo "selected"; } else { echo ""; } ?> value="LABOUR">LABOUR</option>
            </select>
        </div>
        <div class="col-md-2">
        </div>
    </div>
    <div class="form-group row" style="display:none;">
        <label for="inputIsValid" class="form-control-label col-md-4">Quote Status</label>
        <div class="col-md-6">
            <select type="text" name="quote_status" id="quote_status" class="form-control quote_status" value="{{ $row['quote_status'] }}" readonly="readonly" style="pointer-events: none;">
                <option value=""></option>
                <option <?php if($row['quote_status'] =="DRAFT") { echo "selected"; } else { echo ""; } ?> value="DRAFT">DRAFT</option>
                <option <?php if($row['quote_status'] =="INITIATED") { echo "selected"; } else { echo ""; } ?> value="INITIATED">INITIATED</option>
                <option <?php if($row['quote_status'] =="APPROVED") { echo "selected"; } else { echo ""; } ?> value="APPROVED">APPROVED</option>
                <option <?php if($row['quote_status'] =="REJECTED") { echo "selected"; } else { echo ""; } ?> value="REJECTED">REJECTED</option>
            </select>
        </div>
        <div class="col-md-2">
        </div>
    </div>
     <div class="form-group row" style="display:none;">
        <label for="inputIsValid" class="form-control-label col-md-4">Quote Tax Total</label>
        <div class="col-md-6">
            <input type="text" name="quote_tax_total" id="quote_tax_total" value="{{ $row['quote_tax_total'] }}" class="form-control quote_tax_total" readonly>
        </div>
        <div class="col-md-2">
        </div>
    </div>
    <div class="form-group row" style="display:none;">
        <label for="inputIsValid" class="form-control-label col-md-4">Quote Grand Total</label>
        <div class="col-md-6">
            <input type="text" name="quote_grand_total" id="quote_grand_total" value="{{ $row['quote_grand_total'] }}" class="form-control quote_grand_total" readonly>
        </div>
        <div class="col-md-2">
        </div>
    </div>

    <div class="form-group row" style="display:none;">
        <label for="inputIsValid" class="form-control-label col-md-4">Source</label>
        <div class="col-md-6 source_div">
            <select name="source" rows="5" class="form-control source" readonly="readonly" style="pointer-events: none;">
                <option value="">--select--</option>
                <option <?php if($row['source'] =="STANDARD") { echo "selected"; } else { echo ""; } ?> value="STANDARD">STANDARD</option>
                <option <?php if($row['source'] =="INQUIRY") { echo "selected"; } else { echo ""; } ?> value="INQUIRY">INQUIRY</option>
            </select>
        </div>
        <div class="col-md-2">
        </div>
    </div>
    <div class="form-group row" style="display:none;">
        <label for="inputIsValid" class="form-control-label col-md-4">Reference No</label>
        <div class="col-md-6">
            <input class="form-control reference_id" id="reference_id" name="reference_id" size="16" type="hidden" value="{!!  $row['reference_id']; !!}" readonly="">
            <input type="text" id="reference_number" name="reference_number" class="form-control reference_number" value="{!!  $row['reference_number']; !!}" readonly="">
        </div>
        <div class="col-md-2">
        </div>
    </div>
     <!-- end -->  
        <div class="card-body">
            <div class="row g-3">

                <!-- Quote Date & Source -->
                <div class="col-md-3">
                    <small class="text-muted d-block">Quote Date</small>
                    <span class="fw-semibold">
                        {{ date(\Session::get('p_date_format'), strtotime($row['quote_date'])) }}
                    </span>
                    <hr class="my-2">
                    <small class="text-muted d-block">Source</small>
                    <span class="fw-semibold text-primary source_span">
                        {{ $row['source'] }}
                    </span>
                </div>

                <!-- Quote Type & Created By -->
                <div class="col-md-3">
                    <small class="text-muted d-block">Quote Type</small>
                    <span class="fw-semibold">{{ $row['quote_type'] }}</span>
                    <hr class="my-2">
                    <small class="text-muted d-block">Created By</small>
                    <span class="fw-semibold text-success create_by"></span>
                </div>

                <!-- Reference No & Grand Total -->
                <div class="col-md-3">
                    <?php
                    if ($row['quote_tax_total'] != "") {
                        $grdtot = number_format($row['quote_grand_total'], \Session::get("decimal"));
                    } else {
                        $grdtot = 0.00;
                    }
                    $grdtot1 = str_replace(',', '', $grdtot);
                    ?>
                    <small class="text-muted d-block">Reference No</small>
                    <span class="fw-semibold">{{ $row['reference_number'] }}</span>
                    <hr class="my-2">
                    <small class="text-muted d-block">Quote Grand Total</small>
                    <span class="fw-semibold text-danger order_total_span">
                        {{ $grdtot1 }}
                    </span>
                </div>

                <!-- Tax Total -->
                <div class="col-md-3">
                    <?php
                    $dec = \Session::get("decimal");
                    if ($row['quote_tax_total'] != "") {
                        $taxtot = number_format($row['quote_tax_total'], $dec);
                    } else {
                        $taxtot = 0.00;
                    }
                    $taxtot1 = str_replace(',', '', $taxtot);
                    ?>
                    <small class="text-muted d-block">Quote Tax Total</small>
                    <span class="fw-semibold text-warning tax_total_span">
                        {{ $taxtot1 }}
                    </span>
                </div>
            </div>


            <h5 class="mb-0">
                <i class="bi bi-ui-checks me-2"></i> Quote Details
            </h5>

            <div class="row g-4">

                <!-- Left Column -->
                <div class="col-md-4">
                    <!-- Quote Name -->
                    <div class="mb-3">
                        <label for="quote_name" class="form-label">
                            <span class="text-danger">*</span> Quote Name
                        </label>
                        <input type="text" id="quote_name" name="quote_name" value="{{ $row['quote_name'] }}"
                            class="form-control" required>
                    </div>

                    <!-- Remarks -->
                    <div class="mb-3">
                        <label for="remarks" class="form-label">
                            <span class="text-danger">*</span> Remarks
                        </label>
                        <input type="text" id="remarks" name="remarks" value="{{ $row['remarks'] }}"
                            class="form-control">
                    </div>

                    <!-- File Upload -->
                    <div class="mb-3">
                        <label for="choosefile" class="form-label">File Upload</label>
                        <input id="" name="choosefile[]" type="file" class="form-control" multiple>
                        <div class="mt-2">
                            <small class="text-danger">Please upload file size below 10MB</small>
                        </div>

                        <!-- Existing files list -->
                        <div class="table-responsive mt-2">
                            <table class="table table-bordered align-middle">
                                <tbody id="fp">
                                    <?php
                                    $dataupload = json_decode($row['attachfile_name']);
                                    if ($dataupload) {
                                        echo '<input type="hidden" value="' . implode(",", $dataupload) . '" name="existing_file" id="existing_file">';
                                        foreach ($dataupload as $v) { ?>
                                            <tr>
                                                <td>
                                                    <a href="{{URL::to('')}}/uploads/soquoteupload/S{{$row['quote_hdr_id']}}/{{$v}}"
                                                        download class="text-decoration-none">
                                                        <i class="bi bi-paperclip me-1"></i> {{ $v }}
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger ms-2 delete_user"
                                                        data-value="{{ $v }}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Middle Column -->
                <div class="col-md-4">
                    <!-- Customer -->
                    <div class="mb-3">
                        <label for="customerid" class="form-label">
                            <span class="text-danger">*</span> Customer
                        </label>
                        <div class="input-group">
                            <select id="customerid" name="customerid" class="form-select select2 customer_id" required>
                                {!! $row['customer_id'] !!}
                            </select>
                            <?php if ($edit_route != "salesquoteapproval") { ?>
                                <button type="button" class="btn btn-outline-secondary customersearch">
                                    <i class="bi bi-search"></i>
                                </button>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- Bill to Address -->
                    <div class="mb-3">
                        <label for="bill_to_address" class="form-label">
                            <span class="text-danger">*</span> Bill To Address
                        </label>
                        <textarea id="bill_to_address" name="bill_to_address" class="form-control" rows="2"
                            readonly>{{ $row['bill_to_address'] }}</textarea>
                        <input type="hidden" id="bill_to_address_id" name="bill_to_address_id"
                            value="{{ $row['bill_to_address_id'] }}">

                        <div class="mt-2">
                            <button type="button" class="btn btn-sm btn-success changeaddress" value="billto">
                                <i class="bi bi-geo-alt"></i> Change Address
                            </button>
                            <button type="button" class="btn btn-sm btn-primary new_billto changeaddress"
                                value="new_billto">
                                <i class="bi bi-plus-circle"></i> New
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-4">
                    <!-- Price List -->
                    <div class="mb-3">
                        <label for="quote_pricelist_id" class="form-label">
                            <span class="text-danger">*</span> Price List
                        </label>
                        <select id="quote_pricelist_id" name="quote_pricelist_id"
                            class="form-select select2 quote_pricelist_id" required>
                            {!! $row['quote_pricelist_id'] !!}
                        </select>
                    </div>

                    <!-- Ship To Address -->
                    <div class="mb-3">
                        <label for="ship_to_address" class="form-label">
                            <span class="text-danger">*</span> Ship To Address
                        </label>
                        <textarea id="ship_to_address" name="ship_to_address" class="form-control" rows="2"
                            readonly>{{ $row['ship_to_address'] }}</textarea>
                        <input type="hidden" id="ship_to_address_id" name="ship_to_address_id"
                            value="{{ $row['ship_to_address_id'] }}">

                        <div class="mt-2">
                            <button type="button" class="btn btn-sm btn-success changeaddress" value="shipto">
                                <i class="bi bi-geo-alt"></i> Change Address
                            </button>
                            <button type="button" class="btn btn-sm btn-primary new_shipto changeaddress"
                                value="new_shipto">
                                <i class="bi bi-plus-circle"></i> New
                            </button>
                        </div>
                    </div>
                </div>
            </div>



            <h5 class="myheaders mb-3">Additional Details</h5>


            <div class="row">
                <?php
                $i = 0;
                $j = 0;
                foreach ($enabled_columns as $index => $val) {
                    $required = ($val->action == '1') ? "required" : '';
                    if ($i != $j) {
                        $j = $i; ?>
                    <?php } ?>

                    <?php if ($val->column_name == 'frieghtterm_id' && $val->active == 1) {
                        $i++; ?>
                        <div class="col-md-4">
                            <label class="form-label"><?php if ($required) { ?><span class="text-danger">*</span><?php } ?>
                                Freight Term</label>
                            <div class="input-group">
                                <select name="frieghtterm_id" <?= $required ?> id="frieghtterm_id" tabindex="7"
                                    class="form-select select2 frieghtterm_id">
                                    {!! $row['frieghtterm_id'] !!}
                                </select>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ($val->column_name == 'payment_term_id' && $val->active == 1) {
                        $i++; ?>
                        <div class="col-md-4">
                            <label class="form-label"><?php if ($required) { ?><span class="text-danger">*</span><?php } ?>
                                Payment Term</label>
                            <div class="input-group">
                                <select name="payment_term_id" <?= $required ?> id="payment_term_id" tabindex="8"
                                    class="form-select select2 payment_term_id">
                                    {!! $row['payment_term_id'] !!}
                                </select>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ($val->column_name == 'delivery_terms_id' && $val->active == 1) {
                        $i++; ?>
                        <div class="col-md-4">
                            <label class="form-label"><?php if ($required) { ?><span class="text-danger">*</span><?php } ?>
                                Delivery Term</label>
                            <div class="input-group">
                                <select name="delivery_terms_id" id="delivery_terms_id" tabindex="9"
                                    class="form-select select2 delivery_terms_id">
                                    {!! $row['delivery_terms_id'] !!}
                                </select>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ($val->column_name == 'emd_details' && $val->active == 1) {
                        $i++; ?>
                        <div class="col-md-4">
                            <label class="form-label"><?php if ($required) { ?><span class="text-danger">*</span><?php } ?> EMD
                                Details</label>
                            <input type="text" name="emd_details" <?= $required ?> id="emd_details"
                                value="{{ $row['emd_details'] }}" class="form-control">
                        </div>
                    <?php } ?>

                    <?php if ($val->column_name == 'currency_id' && $val->active == 1) {
                        $i++; ?>
                        <div class="col-md-4">
                            <label class="form-label"><?php if ($required) { ?><span class="text-danger">*</span><?php } ?>
                                Currency Code</label>
                            <div class="input-group">
                                <select name="currency_id" <?= $required ?> id="currency_id" tabindex="10"
                                    class="form-select select2 currency_id">
                                    {!! $row['currency_id'] !!}
                                </select>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ($val->column_name == 'project_id' && $val->active == 1) {
                        $i++; ?>
                        <div class="col-md-4">
                            <label class="form-label"><?php if ($required) { ?><span class="text-danger">*</span><?php } ?>
                                Project Name</label>
                            <div class="input-group">
                                <select name="project_id" <?= $required ?> class="form-select select2 project_id" tabindex="11"
                                    data-live-search="true">
                                    {!! $row['project_id'] !!}
                                </select>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ($val->column_name == 'salesperson_id' && $val->active == 1) {
                        $i++; ?>
                        <div class="col-md-4">
                            <label class="form-label"><?php if ($required) { ?><span class="text-danger">*</span><?php } ?>
                                Salesperson Name</label>
                            <div class="input-group">
                                <select name="salesperson_id" <?= $required ?> class="form-select select2 salesperson_id"
                                    tabindex="12">
                                    {!! $row['salesperson_id'] !!}
                                </select>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ($val->column_name == 'quote_reference' && $val->active == 1) {
                        $i++; ?>
                        <div class="col-md-4">
                            <label class="form-label"><?php if ($required) { ?><span class="text-danger">*</span><?php } ?>
                                Quote
                                Reference</label>
                            <input type="text" name="quote_reference" <?= $required ?> tabindex="19" id="quote_reference"
                                value="{{ $row['quote_reference'] }}" class="form-control">
                        </div>
                    <?php } ?>

                    <?php if ($val->column_name == 'quote_subject' && $val->active == 1) {
                        $i++; ?>
                        <div class="col-md-4">
                            <label class="form-label"><?php if ($required) { ?><span class="text-danger">*</span><?php } ?>
                                Quote
                                Subject</label>
                            <input type="text" name="quote_subject" <?= $required ?> tabindex="20" id="quote_subject"
                                value="{{ $row['quote_subject'] }}" class="form-control">
                        </div>
                    <?php } ?>

                    <?php if ($val->column_name == 'tittle_of_work' && $val->active == 1) {
                        $i++; ?>
                        <div class="col-md-4">
                            <label class="form-label"><?php if ($required) { ?><span class="text-danger">*</span><?php } ?>
                                Title
                                Of Work</label>
                            <input type="text" name="tittle_of_work" <?= $required ?> id="tittle_of_work" tabindex="18"
                                value="{{ $row['tittle_of_work'] }}" class="form-control">
                        </div>
                    <?php } ?>

                    <?php if ($val->column_name == 'payment_method_id' && $val->active == 1) {
                        $i++; ?>
                        <div class="col-md-4">
                            <label class="form-label"><?php if ($required) { ?><span class="text-danger">*</span><?php } ?>
                                Payment Method</label>
                            <div class="input-group">
                                <select name="payment_method_id" <?= $required ?> class="form-select select2 payment_method_id"
                                    tabindex="21">
                                    {!! $row['payment_method_id'] !!}
                                </select>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ($val->column_name == 'frieghtcarriers_hdr_id' && $val->active == 1) {
                        $i++; ?>
                        <div class="col-md-4">
                            <label class="form-label"><?php if ($required) { ?><span class="text-danger">*</span><?php } ?>
                                Freight Carrier</label>
                            <div class="input-group">
                                <select name="frieghtcarriers_hdr_id" <?= $required ?>
                                    class="form-select select2 frieghtcarriers_hdr_id" tabindex="22">
                                    {!! $row['frieghtcarriers_hdr_id'] !!}
                                </select>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ($val->column_name == 'transport_charges' && $val->active == 1) {
                        $i++; ?>
                        <div class="col-md-4">
                            <label class="form-label"><?php if ($required) { ?><span class="text-danger">*</span><?php } ?>
                                Transport Charges</label>
                            <div class="input-group">
                                <input type="text" name="transport_charges" <?= $required ?> id="transport_charges"
                                    value="{{ $row['transport_charges'] }}" class="form-control charges transport_charges"
                                    readonly>
                                <button class="btn btn-outline-secondary packingtax" type="button"
                                    data-value="Transport Charges" data-at="2"><i class="fa fa-plus"></i></button>
                            </div>
                            <input type="hidden" name="transport_charges_tax" id="transport_charges_tax"
                                value="{{ $row['transport_charges_tax'] }}">
                        </div>
                    <?php } ?>

                    <?php if ($val->column_name == 'other_frieght_amount' && $val->active == 1) {
                        $i++; ?>
                        <div class="col-md-4">
                            <label class="form-label"><?php if ($required) { ?><span class="text-danger">*</span><?php } ?>
                                Other
                                Freight Amount</label>
                            <div class="input-group">
                                <input type="text" name="other_frieght_amount" <?= $required ?> id="other_frieght_amount"
                                    value="{{ $row['other_frieght_amount'] }}" class="form-control charges other_frieght_amount"
                                    readonly>
                                <button class="btn btn-outline-secondary packingtax" type="button"
                                    data-value="Other Freight Amount" data-at="5"><i class="fa fa-plus"></i></button>
                            </div>
                            <input type="hidden" name="other_frieght_amount_tax" id="other_frieght_amount_tax"
                                value="{{ $row['other_frieght_amount_tax'] }}">
                        </div>
                    <?php } ?>

                    <?php if ($val->column_name == 'other_tax_amount' && $val->active == 1) {
                        $i++; ?>
                        <div class="col-md-4">
                            <label class="form-label"><?php if ($required) { ?><span class="text-danger">*</span><?php } ?>
                                Other
                                Tax Amount</label>
                            <div class="input-group">
                                <input type="text" name="other_tax_amount" id="other_tax_amount"
                                    value="{{ $row['other_tax_amount'] }}" class="form-control charges other_tax_amount"
                                    readonly>
                                <button class="btn btn-outline-secondary packingtax" type="button" data-value="Other Tax Amount"
                                    data-at="4"><i class="fa fa-plus"></i></button>
                            </div>
                            <input type="hidden" name="other_tax_amount_tax" id="other_tax_amount_tax"
                                value="{{ $row['other_tax_amount_tax'] }}">
                        </div>
                    <?php } ?>

                    <?php if ($val->column_name == 'packaging_charges' && $val->active == 1) {
                        $i++; ?>
                        <div class="col-md-4">
                            <label class="form-label"><?php if ($required) { ?><span class="text-danger">*</span><?php } ?>
                                Packaging Charges</label>
                            <div class="input-group">
                                <input type="text" name="packaging_charges" <?= $required ?> id="packaging_charges"
                                    value="{{ $row['packaging_charges'] }}" class="form-control charges packaging_charges"
                                    readonly>
                                <button class="btn btn-outline-secondary packingtax" type="button"
                                    data-value="Packaging Charges" data-at="1"><i class="fa fa-plus"></i></button>
                            </div>
                            <input type="hidden" name="packaging_charges_tax" id="packaging_charges_tax"
                                value="{{ $row['packaging_charges_tax'] }}">
                        </div>
                    <?php } ?>

                    <?php if ($val->column_name == 'insurance_charges' && $val->active == 1) {
                        $i++; ?>
                        <div class="col-md-4">
                            <label class="form-label"><?php if ($required) { ?><span class="text-danger">*</span><?php } ?>
                                Insurance Charges</label>
                            <div class="input-group">
                                <input type="text" name="insurance_charges" <?= $required ?> id="insurance_charges"
                                    value="{{ $row['insurance_charges'] }}" class="form-control charges insurance_charges"
                                    readonly>
                                <button class="btn btn-outline-secondary packingtax" type="button"
                                    data-value="Insurance Charges" data-at="3"><i class="fa fa-plus"></i></button>
                            </div>
                            <input type="hidden" name="insurance_charges_tax" id="insurance_charges_tax"
                                value="{{ $row['insurance_charges_tax'] }}">
                        </div>
                    <?php } ?>

                    <?php if ($val->column_name == 'discount_id' && $val->active == 1) {
                        $i++; ?>
                        <div class="col-md-4">
                            <label class="form-label"><?php if ($required) { ?><span class="text-danger">*</span><?php } ?>
                                Discount</label>
                            <div class="input-group">
                                <select name="discount_id" <?= $required ?> id="discount_id" tabindex="23"
                                    class="form-select select2 discount_id">
                                    {!! $row['discount'] !!}
                                </select>
                            </div>
                        </div>
                    <?php } ?>

                <?php } ?>
            </div>


            <div class="row mt-4">
                <div class="col-md-12">


                    <div id="preview-area" class="table-responsive">
                        <table class="table table-bordered clone_table" style="width: 180% !important;">

                            <thead class="table-light">
                                <tr>

                                    <th>Line No</th>
                                    <th class="pdtdiv">Product</th>
                                    <th class="pdtdiv productsearch">&nbsp;</th>
                                    <th class="">Customer Part No</th>
                                    <th>Uom Code</th>
                                    <?php if ($row['quote_type'] == 'LABOUR') { ?>
                                        <th class="pdtdes_div">Product Description</th>
                                    <?php } ?>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Discount(%)</th>
                                    <th>Discount Amount</th>
                                    <th> Tax Exemption</th>
                                    <?php if ($row['quote_type'] == 'STANDARD') { ?>
                                        <th class="hsn"> Hsn Code </th>
                                    <?php } else { ?>
                                        <th>SAC Code</th>
                                    <?php } ?>
                                    <th>Tax Group</th>
                                    <th>Tax Amount</th>
                                    <th>Line Total</th>
                                    <th>Promised Date</th>
                                    <th>Comments</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody class="clone_lines_body">
							    @if(!empty($linedata) && count($linedata) > 0)
								@foreach($linedata as $key => $value)


                                    <tr class="rcopy clone">
                                        <td>
                                            <input type="hidden" name="bulk_quote_line_id[]"
                                                class="form-control  bulk_quote_line_id"
                                                value="{{ $value->quote_line_id }}">
                                            <input type="text" name="bulk_line_no[]" class="form-control  bulk_line_no"
                                                value="{{ $key + 1 }}" readonly="readonly">
                                        </td>

                                        <td class="pdtdiv rdonlydiv">
                                            <select name="bulk_product_id[]"
                                                class="select2 bulk_product_id  parsley-validated" required="required"
                                                readonly>{!! $value->product_id !!}</select>
                                        </td>

                                        <td class="productsearch"><i class="fa fa-search productsearch"></i></td>
                                        <td class="pdtdiv partdiv" style="pointer-events:none;">
                                            <select name="bulk_part_no[]" id="bulk_part_no"
                                                class="bulk_part_no select2 ">{!! $value->part_no !!}</select>
                                        </td>
                                        <td class="uomread">
                                            <select name="bulk_uom_code_id[]" id="bulk_uom_code_id"
                                                class="select2 bulk_uom_code_id">
                                                {!! $value->uomcode_id !!}
                                            </select>
                                        </td>
                                        <?php if ($row['quote_type'] == 'LABOUR') { ?>
                                            <td class="pdtdes_div">
                                                <input type="text" name="bulk_product_description[]"
                                                    class="form-control bulk_product_description input_qty_width"
                                                    required="required" value="{{ $value->product_description }}">
                                            </td>
                                        <?php } ?>

                                        <td>
                                            <input type="text" name="bulk_qty[]"
                                                class="form-control  bulk_qty input_qty_width" value="{{ $value->qty }}"
                                                required="required">
                                        </td>
                                        <td>
                                            <?php $price = number_format((float) $value->unit_price, \Session::get("decimal"), '.', '') ?>
                                            <input type="text" name="bulk_unit_price[]"
                                                class="form-control bulk_unit_price input_qty_width" value="{{ $price }}"
                                                required="required">
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_discount_percentage[]"
                                                class="form-control bulk_discount_percentage input_qty_width"
                                                value="{{ $value->discount_percentage }}">
                                        </td>
                                        <td>
                                            <?php $disamt = number_format((float) $value->discount_amount, \Session::get("decimal"), '.', '') ?>
                                            <input type="text" name="bulk_discount_amount[]"
                                                class="form-control bulk_discount_amount input_qty_width"
                                                value="{{ $disamt }}">
                                        </td>
                                        <td><select name="bulk_tax_excemption[]"
                                                class="form-control select2 bulk_tax_excemption" id="bulk_tax_excemption"
                                                required>
                                                <option value="">--please select--</option>
                                                <option value="Yes" <?php if ($value->tax_excemption == 'Yes') {
                                                    echo 'selected';
                                                } ?>>Yes</option>
                                                <option value="No" <?php if ($value->tax_excemption == 'No') {
                                                    echo 'selected';
                                                } ?>>No</option>
                                            </select>
                                        </td>
                                        <?php if ($row['quote_type'] == 'STANDARD') { ?>
                                            <td class="hsn hsndiv">
                                                <select name="bulk_hsn_code[]" id="bulk_hsn_code"
                                                    class="select2 bulk_hsn_code">{!! $value->hsn_code!!}</select>
                                            </td>
                                        <?php } else { ?>
                                            <td class="hsn hsndiv">
                                                <select name="bulk_hsn_code[]" id="bulk_hsn_code"
                                                    class="select2 bulk_hsn_code">{!! $value->hsn_code!!}</select>
                                            </td>
                                        <?php } ?>
                                        <td class="rdonlydiv taxgrp">
                                            <select name="bulk_tax_group_id[]" id="bulk_tax_group_id"
                                                class="select2 bulk_tax_group_id" required="required">
                                                {!! $value->tax_group_id !!}
                                            </select>
                                        </td>
                                        <td>
                                            <?php $taxamt1 = number_format((float) $value->tax_amount, \Session::get("decimal"), '.', '') ?>
                                            <input type="text" name="bulk_tax_amount[]"
                                                class="form-control bulk_tax_amount input_qty_width" value="{{ $taxamt1 }}"
                                                required="required" readonly>
                                        </td>
                                        <td>
                                            <?php $tot1 = number_format((float) $value->line_total, \Session::get("decimal"), '.', '') ?>
                                            <input type="text" name="bulk_line_total[]"
                                                class="form-control bulk_line_total input_qty_width" value="{{ $tot1 }}"
                                                required="required" readonly>
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_promised_date[]"
                                                class="form-control datepicker bulk_promised_date"
                                                value="{{ $value->promised_date }}">
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_comments[]"
                                                class="form-control bulk_comments input_qty_width"
                                                value="{{ $value->comments }}">
                                        </td>

                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-danger remove-row">
                                                <i class="fas fa-minus-circle"></i>
                                            </button>
                                        </td>

                                    </tr>
										@endforeach
									  @else
                                    <tr class="rcopy clone">
                                        <td>
                                            <input type="hidden" name="bulk_quote_line_id[]"
                                                class="form-control bulk_quote_line_id" value="">
                                            <input type="text" name="bulk_line_no[]" class="form-control bulk_line_no"
                                                value="1" readonly="readonly">
                                        </td>

                                        <td class="pdtdiv">
                                            <select name="bulk_product_id[]"
                                                class="select2 bulk_product_id  parsley-validated" required="required">{!!
                                                $product !!}</select>
                                        </td>
                                        <td class="pdtdiv partdiv"><i class="fa fa-search productsearch"></i></td>
                                        <td class="pdtdiv" style="pointer-events:none;">
                                            <select name="bulk_part_no[]" id="bulk_part_no" class="bulk_part_no select2 ">
                                                {!! $part_no !!}
                                            </select>
                                        </td>
                                        <td class="uomread">
                                            <select name="bulk_uom_code_id[]" id="bulk_uom_code_id"
                                                class="select2 bulk_uom_code_id">
                                                {!! $uom_code_id !!}
                                            </select>
                                        </td>
                                        <?php if ($row['quote_type'] == 'LABOUR') { ?>
                                            <td class="pdtdes_div">
                                                <input type="text" name="bulk_product_description[]"
                                                    class="form-control bulk_product_description input_qty_width"
                                                    required="required" value="">
                                            </td>

                                        <?php } ?>
                                        <td>
                                            <input type="text" name="bulk_qty[]" class="form-control bulk_qty " value=""
                                                required="required">
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_unit_price[]"
                                                class="form-control bulk_unit_price " value="" required="required">
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_discount_percentage[]"
                                                class="form-control bulk_discount_percentage " value="">
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_discount_amount[]"
                                                class="form-control bulk_discount_amount " value="">
                                        </td>
                                        <td><select name="bulk_tax_excemption[]"
                                                class="form-control select2 bulk_tax_excemption" id="bulk_tax_excemption"
                                                required>
                                                <option value="">--please select--</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No" selected>No</option>
                                            </select>
                                        </td>
                                        <?php if ($row['quote_type'] == 'STANDARD') { ?>
                                            <td class="hsn hsndiv">
                                                <select name="bulk_hsn_code[]" id="bulk_hsn_code"
                                                    class="select2 bulk_hsn_code"></select>
                                            </td>
                                        <?PHP } else { ?>
                                            <td class="">
                                                <select name="bulk_hsn_code[]" id="bulk_hsn_code"
                                                    class="select2 bulk_hsn_code">{!! $sac_code !!}</select>
                                            </td>
                                        <?PHP } ?>
                                        <td class="taxgrp">
                                            <select name="bulk_tax_group_id[]" id="bulk_tax_group_id"
                                                class="select2 bulk_tax_group_id" required="required">
                                                {!! $tax_group_id !!}
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_tax_amount[]" class="form-control bulk_tax_amount"
                                                value="" required="required">
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_line_total[]" class="form-control bulk_line_total"
                                                value="" required="required">
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_promised_date[]"
                                                class="form-control datepicker bulk_promised_date" value="">
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_comments[]" class="form-control bulk_comments"
                                                value="">
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
                        <div class="text-end">
                            <button type="button" class="btn btn-success btn-sm add-row">
                                <i class="fas fa-plus-circle"></i> Add Row
                            </button>
                        </div>
                        <input type="hidden" name="enable-masterdetail" value="true">
                        <input type="hidden" class="form-control input-sm bulk_hidden_date datepicker" value="">

                    </div>
                </div>
            </div>



            <div class="row mt-4 mb-3">

                <div class="col-lg-12 col-md-12">
                    <div class="form-group text-center">

                        <?php if ($edit_route == "soquote" || $edit_route == "copysalesquote" || $edit_route == "salesquotefromenquiry") { ?>
                            <input type="hidden" name="submit_type" class="submit_type" id="submit_type">
                            <?php if (($row['quote_status'] == "" && $row['quote_status'] != "REJECTED") || $row['quote_status'] == "DRAFT") { ?>
                                <button name="apply" type="button" class="btn saveform btn-secondary px-4 me-2"
                                    value="APPLYCHANGES">Draft</button>
                            <?php } ?>
                            <button name="button" type="button" class="btn btn-success saveform px-4 me-2"
                                value="SAVE">Save</button>

                        <?php }
                        if ($edit_route == "salesquoteapproval") { ?>

                            <button type="button" name="button" class="btn btn-success saveform px-4 me-2"
                                value="APPROVE">Approve</button>
                            <button type="button" name="button" class="btn btn-danger saveform px-4 me-2"
                                value="REJECT">Reject</button>
                        <?php } ?>
                        <a class='btn btn-danger px-4 me-2' href="{{URL::to($pageMethod)}}">Cancel</a>
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

            <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel"
                aria-hidden="true">
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

        </div>

        <input type="hidden" class="pdtindex" value="" />

    </div>
</form>


<!-- Customer Address Modal -->
<div class="modal fade" id="new_address" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content shadow-lg rounded-4 border-0">

            <!-- Modal Header -->
            <div class="modal-header bg-primary bg-gradient text-white rounded-top-4">
                <h5 class="modal-title">
                    <i class="bi bi-geo-alt-fill me-2"></i> Customer Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <form method="post" id="newaddress" class="needs-validation" novalidate>
                    <div class="row g-4">

                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Customer Site Number</label>
                                <input type="text" name="customer_site_number" id="customer_site_number"
                                    class="form-control" readonly>
                                <input type="hidden" id="custype" name="custype">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Address <span class="text-danger">*</span></label>
                                <input type="text" name="address" id="address" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">State <span class="text-danger">*</span></label>
                                <select name="state" id="state" class="form-select select2" required>
                                    {!! $state_new !!}
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Contact Person</label>
                                <input type="text" name="contact_person" id="contact_person" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Contact Number</label>
                                <input type="text" name="contact_number" id="contact_number" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Primary Address</label>
                                <select name="primary_address" id="primary_address" class="form-select">
                                    <option value="">--Please Select--</option>
                                    <option value="YES">YES</option>
                                    <option value="NO">NO</option>
                                </select>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Customer Site Name <span class="text-danger">*</span></label>
                                <input type="hidden" name="customer_site_id" id="customer_site_id">
                                <input type="text" name="customer_site_name" id="customer_site_name"
                                    class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Country <span class="text-danger">*</span></label>
                                <select name="country" id="country" class="form-select select2" required>
                                    {!! $country_new !!}
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">City <span class="text-danger">*</span></label>
                                <select name="city" id="city" class="form-select select2" required>
                                    {!! $city_new !!}
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Pin Code</label>
                                <input type="text" name="pin_code" id="pin_code" maxlength="6" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Contact Mail ID</label>
                                <input type="email" name="contact_mail" id="contact_mail" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Active</label>
                                <select name="active" id="active" class="form-select" disabled>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-success px-4 newaddress_save">
                    <i class="bi bi-plus-circle me-2"></i> Add Customer Site
                </button>
                <button type="button" class="btn btn-secondary px-4 newaddress_cancel" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i> Cancel
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Tax Details Modal -->
<div class="modal fade" id="taxModal" tabindex="-1" aria-labelledby="taxModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0">

            <!-- Modal Header -->
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title popheader" id="taxModalLabel">Tax Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div class="taxdetail"></div>
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





@endsection
@push('scripts')

<script>

    $('#savestatus').val('');


    $(document).ready(function () {


        /* Purpose For Price not Assign POPup From Enquiry Products*/

        <?php if (isset($quotecount) && $quotecount != 0) {
            ?>
            setTimeout(function () {
                swal({
                    title: "Price not assigned for {{$quotecount}} Products",
                    text: "You want to add price for this product",
                    type: "warning",
                    showCancelButton: !0,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    closeOnConfirm: !1,
                    closeOnCancel: !1
                }, function (e) {
                    if (e == true) {
                        var id = $(".quote_pricelist_id").val();
                        var url = "{{ URL::to('salespricelistedit') }}/" + id;
                        window.open(url);

                    }
                    else {
                        swal("Cancelled");
                        $('.apply').css('display', 'none');
                    }
                }
                );
                $('.apply').css('display', 'none');
            }, 500);
            <?php
        }

        ?>
        /*End*/

        /** Sales Quote Select Start **/
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
        /** Sales Quote Select End **/

        /** Sales Quote Approve Start **/
        var quotetype = $('.quote_type').val();
        if (quotetype == 'LABOUR') {
            $('.bulk_unit_price').attr('readonly', false);
        } else {
            $('.bulk_unit_price').attr('readonly', false);
        }
        <?php if ($edit_route == "salesquoteapproval") { ?>
            $('#choosefile').css("pointer-events", "none");
            $('.changeaddress').attr("hidden", "true");
            $('.add_row').attr("hidden", "true");
            $('.productsearch').attr("hidden", "true");
            $('.remove').attr("hidden", "true");
            $('.delete_user').css("pointer-events", "none");
        <?php } ?>


        /** Sales Quote City Hide Start **/
        $(".cityhide,.statehide").css('pointer-events', 'none');




        /** Sales Quote readonly Start **/
        <?php if ($pageMethod == "salesquoteapproval") { ?>

            $('.additem,.remove,.dishide').css('pointer-events', 'none');
            $('.uomread,.partdiv,.hsndiv').css('pointer-events', 'none');
        <?php } ?>

        $('.uomread').css('pointer-events', 'none');



        /** Sales Quote readonly Start **/
        <?php if ($edit_route == "salesquoteapproval") { ?>
            $('.quote_name,.packaging_charges,.transport_charges,.insurance_charges,.tittle_of_work,.quote_reference,.quote_subject,.bulk_qty,.bulk_unit_price,.bulk_discount_percentage,.bulk_discount_amount,.bulk_promised_date,.rdonlydiv,.bulk_product_description').css('pointer-events', 'none');
            $('.divhide').css('display', 'none');

        <?php } ?>
        /** Sales Quote readonly End **/
        $('.taxgrp').css('pointer-events', 'none');

        var organization = '<?php echo Session::get('organization'); ?>';
        $('.organization_id').val(organization).change();
        var user = '<?php echo Session::get('id'); ?>';
        $('.created_by').val(user).change();
        $(".create_by").html($('.created_by option:selected').text());
        $('.org').html($('.organization_id option:selected').text());



        /** Sales Quote Data hide Start **/
        $(document).on('change', '.bulk_promised_date0', function () {
            var promised_date = $(this).val();
            var hide_date = $('.bulk_hidden_date').val();
            /** Sales Quote Data hide End **/

            /** Sales Quote promised Data Start **/
            $(".bulk_promised_date").each(function (indexs) {

                var dates = $('.bulk_promised_date' + indexs).val();
                console.log(dates);
                if (hide_date == dates || dates == "") {

                    $('.bulk_promised_date' + indexs).val(promised_date);
                }
                $('.bulk_hidden_date').val(promised_date);

            });

        });



        /** Sales Quote Email Validation data Start **/
        function ValidateEmail(email) {
            var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
            return expr.test(email);
        };



        /* read only */

        /**  purpose tax for other charges **/
        $(document).on('click', '.packingtax', function () {
            var type = $(this).attr('data-value');
            var type_id = $(this).attr('data-at');
            $('.popheader').html(type + " Tax Details");
            $('#taxModal').modal('show');
            $('#taxModal')
                .width("50%")
                .css({
                    margin: 'auto',
                    'padding-left': '0px',
                    height: '70%',
                    left: '25%'

                });

            if (type_id == "1") {
                var val_char = $('.packing_charges_tax').val();
            }
            if (type_id == "2") {
                var val_char = $('.transport_charges_tax').val();
            }
            if (type_id == "3") {
                var val_char = $('.insurance_charges_tax').val();
            }
            if (type_id == "4") {
                var val_char = $('.other_tax_amount_tax').val();
            }
            if (type_id == "5") {
                var val_char = $('.other_frieght_amount_tax').val();
            }
            if (type_id == "6") {
                var val_char = $('.unloading_charges_tax').val();
            }
            var text_data = "{!! $tax_group_id_pop!!}";
            var data = '';

            <?php if ($return_url == 'purchaserequisitionapprove' || $return_url == 'purchasequtoetionapprove') { ?>
                data += `
  <div class="row g-3">
    <!-- Extra Charge -->
    <div class="col-md-3">
      <label class="form-label fw-bold">${type}</label>
      <input type="text" class="form-control extracharge${type_id}" value="" readonly>
    </div>

    <!-- Tax Group -->
    <div class="col-md-3">
      <label class="form-label fw-bold">Tax Group</label>
      <select class="form-select select2 tax_details${type_id} tax_detailsse" disabled>
        ${text_data}
      </select>
    </div>

    <!-- Action Button -->
    <div class="col-12 text-right mt-3">
      <button type="button" class="btn btn-primary px-4" value="${type_id}">
        <i class="bi bi-check-circle"></i> Ok
      </button>
    </div>
  </div>
  `;
            <?php } else { ?>
                data += `
  <div class="row g-3">
    <!-- Extra Charge -->
    <div class="col-md-3">
      <label class="form-label fw-bold">${type}</label>
      <input type="text" class="form-control extracharge${type_id}" value="">
    </div>

    <!-- Tax Group -->
    <div class="col-md-3">
      <label class="form-label fw-bold">Tax Group</label>
      <select class="form-select select2 tax_details${type_id} tax_detailsse">
        ${text_data}
      </select>
    </div>

    <!-- Action Button -->
    <div class="col-12 text-right mt-3">
      <button type="button" class="btn btn-success px-4  taxchargesave" value="${type_id}">
        <i class="bi bi-save"></i> Ok
      </button>
    </div>
  </div>
  `;
            <?php } ?>

            $('.taxdetail').html(data);

            // Pre-fill if values exist
            if (val_char != '') {
                var dat = val_char.split(",");
                $('.extracharge' + type_id).val(dat[1]);
                $('.tax_details' + type_id).val(dat[0]).change();
            }


        });

        /** Sales Quote Tax Charge Save Start **/
        $(document).on('click', '.taxchargesave', function () {
            var type = $(this).val();
            var taxgrp = $('.tax_details' + type + ' option:selected').attr('data-display');
            var taxgrp_v = $('.tax_details' + type + ' option:selected').val();
            var charge = parseFloat($('.extracharge' + type).val());
            taxgrp = taxgrp ? taxgrp : 0;
            var amount = parseFloat((charge) * taxgrp / 100).toFixed("{{\Session::get('decimal')}}");

            if (taxgrp_v == 0) {
                notyMsg("info", "Please select Tax Group");
            }
            charge = isNaN(charge) ? "" : charge;
            if (charge == '') {
                notyMsg("info", "Please fill Amount");
            }
            if (charge != '') {
                var a_c = (parseFloat(amount) + parseFloat(charge)).toFixed("{{\Session::get('decimal')}}");;
                var tax_group_value = taxgrp_v + "," + charge;
                if (type == "1") {
                    $('.packaging_charges').val(a_c);
                    $('.packaging_charges_tax').val(tax_group_value);
                }
                if (type == "2") {
                    $('.transport_charges').val(a_c);
                    $('.transport_charges_tax').val(tax_group_value);
                }
                if (type == "3") {
                    $('.insurance_charges').val(a_c);
                    $('.insurance_charges_tax').val(tax_group_value);
                }
                if (type == "4") {

                    $('.other_tax_amount').val(a_c);
                    $('.other_tax_amount_tax').val(tax_group_value);
                }
                if (type == "5") {
                    $('.other_frieght_amount').val(a_c);
                    $('.other_frieght_amount_tax').val(tax_group_value);
                }
                $('#taxModal').modal('hide');
            }
            else {
                if (type == "1") {
                    $('.packaging_charges').val(0);
                    $('.packaging_charges_tax').val('');
                }
                if (type == "2") {
                    $('.transport_charges').val(0);
                    $('.transport_charges_tax').val('');
                }
                if (type == "3") {
                    $('.insurance_charges').val(0);
                    $('.insurance_charges_tax').val('');
                }
                if (type == "4") {
                    $('.other_tax_amount').val(0);
                    $('.other_tax_amount_tax').val('');
                }
                if (type == "5") {
                    $('.other_frieght_amount').val(0);
                    $('.other_frieght_amount_tax').val('');
                }
            }

            remove_cal();
        });
        /** Sales Quote Tax Charge Save End **/

        /** Sales Quote Approval Save Start **/
        <?php if ($edit_route != "salesquoteapproval" && $edit_route != "salesquotefromenquiry") { ?>
            $(document).on('change', '.customer_id', function () {
                var customer_id = $('.customer_id option:selected').val();
                var customer = $('.customer_id').val();

                if (customer != '') {
                    var url = "{{ URL::to('sodispatchaddress') }}/" + customer_id + '?pid=0&condition=soquote';
                    $.get(url, function (data) {
                        if (data[1] != '' && data[0] != '') {
                            var result_data = data[1].split('~');
                            $('.ship_to_address').val(result_data[0]);
                            $('.ship_to_address_id').val(result_data[1]);
                            var result_data = data[0].split('~');
                            $('.bill_to_address').val(result_data[0]);
                            $('.bill_to_address_id').val(result_data[1]);
                        }
                        else if (data[1] != "" || data[0] != "") {
                            if (data[1] != '' && data[0] == "") {
                                var result_data = data[1].split('~');
                                $('.ship_to_address').val(result_data[0]);
                                $('.ship_to_address_id').val(result_data[1]);
                                notyMsgs('info', 'Please Assingn bill To Address !!!');
                                $('.bill_to_address').val('');
                                $('.bill_to_address_id').val('');
                            }
                            else if (data[1] == '' && data[0] != "") {
                                $('.ship_to_address').val('');
                                $('.ship_to_address_id').val('');
                                notyMsgs('info', 'Please Assingn Ship To Address !!!');
                                var result_data = data[0].split('~');
                                $('.bill_to_address').val(result_data[0]);
                                $('.bill_to_address_id').val(result_data[1]);
                            }
                        }
                        else if (data[1] == '' && data[0] == '') {
                            $('.bill_to_address,.ship_to_address,.bill_to_address_id,.ship_to_address_id').val('');
                            notyMsgs('info', 'Please Assingn Ship To and Bill To Address !!!');
                        }

                        if (data.pricelist_id != 0) {
                            $(".quote_pricelist_id").val(data.pricelist_id).change();
                            $('.frieghtcarriers_hdr_id').select2('val', [data.ar_frieghtcarriers_hdr_id]);
                            $('.payment_term_id').select2('val', [data.default_payment_terms_id]);
                            $('.payment_method_id').select2('val', [data.default_payment_method_id]);
                            $('.salesperson_id').select2('val', [data.sales_person]);
                            $('.discount_id').select2('val', [data.ar_discount_hdr_id]);
                        }
                        else {
                            showCustomAlert(' Please Select Price List !!! ', 'warning');
                            $(".quote_pricelist_id").val('').change();
                        }


                        if (data.productid == '<option value="">-- Please Select --</option>') {
                            showCustomAlert("No Product for these Pricelist", "info");
                        }
                        $('.bulk_product_id').html(data.productid);
                    });


                } else {
                    $('.frieghtcarriers_hdr_id').select2('val', ['']);
                    $('.payment_term_id').select2('val', ['']);
                    $('.payment_method_id').select2('val', ['']);
                    $('.salesperson_id').select2('val', ['']);
                    $(".quote_pricelist_id").val('').change();
                    $('.bill_to_address,.ship_to_address,.bill_to_address_id,.ship_to_address_id').val('');
                }

            });


        <?php } ?>
        /** Sales Quote Approval readonly price list and customer Start **/
        <?php if ($edit_route == "salesquoteapproval") { ?>
            $('.quote_pricelist_id,.customer_id').css('pointer-events', 'none');
            $('.quote_pricelist_id,.customer_id').removeAttr('required');
        <?php } ?>
        /** Sales Quote Approval readonly price list and customer End **/

        /** Sales Quote Product Start **/
        function producteach() {

            $('.bulk_product_id').each(function (index) {
                //alert('.bulk_product_id'+index+' option:selected');
                var pid = $('.bulk_product_id' + index + ' option:selected').val();
                var pricelistid = $('.quote_pricelist_id option:selected').val();
                var custid = $('.customer_id option:selected').val();
                var url = "{{ url::to('pricelistdetail_so') }}/" + pid + "/" + pricelistid;
                //alert(pricelistid+'----'+custid+'-----'+index);
                if (custid != '') {
                    if (pricelistid != '') {
                        if (pid != '') {
                            $.get(url, function (data) {
                                $('.bulk_unit_price' + index).val(data);
                                calc_by_index(index);
                            });
                        }
                        else {
                            $('.bulk_unit_price' + index).val(0);
                            calc_by_index(index);
                        }
                    }
                    else {
                        $('.bulk_unit_price' + index).val(0);
                        calc_by_index(index);

                    }
                }
                else {
                    $('.quote_pricelist_id').val('').select2();
                    showCustomAlert(' Please Select Customer !!! ', 'error');
                }
            });
        }
        /** Sales Quote Product End **/

        /** Sales Quote New Address Start **/
        $(document).on('click', '.newaddress_save', function () {
            //if (ValidateEmail($("#contact_mail").val())) {


            var customer_id = $('.customer_id option:selected').val();
            var form = $('#newaddress');
            validationrule('newaddress');
            var primary_address = $('#primary_address').val();
            var formdata = $('#newaddress').serialize();
            form.parsley().validate();
            if (form.parsley().isValid() & (ValidateEmail($("#contact_mail").val()))) {
                var url = "{{ URL::to('newshiptocustomer')  }}/" + customer_id;
                $.post(url, formdata, function (data) {
                    var type = $('.custype').val();
                    data[0] = $.trim(data[0]);
                    if (data[0] != '') {
                        if (type == "SHIP_TO") {
                            $('.ship_to_address_id').val(data[0]);
                            $('.ship_to_address').val(data[1]);
                            notyMsg("success", "Ship to Address Saved Successfully");
                        }
                        else if (type == "BILL_TO") {
                            $('.bill_to_address_id').val(data[0]);
                            $('.bill_to_address').val(data[1]);
                            showCustomAlert("Bill to Address Saved Successfully", "success");
                        }
                    }
                    $('#new_address').modal('hide');
                });
            }
            //}
            else {
                notyMsgs('info', 'Enter Valid Mail Id');
            }

        });
        /** Sales Quote New Address End **/

        /** Sales Quote New Address Cancel Start **/
        $(document).on('click', '.newaddress_cancel', function () {
            $('#new_address').modal('hide');
        });
        /** Sales Quote New Address Cancel End **/

        /** Sales Quote New Ship to  Start **/
        $(document).on('click', '.new_shipto', function () {

            var cid = $('.customer_id option:selected').val();

            $(':input', '#newaddress')
                .not(':button, :submit, :reset, :hidden')
                .val('')
                .prop('checked', false)
                .prop('selected', false);


            $('.city,.country,.state').select2('val', ['']);
            if (cid != "") {
                $('#new_address').modal('show');
                $('#new_address').width("100%");

                var ct = "SHIP_TO";
                $('.custype').val(ct);
            }
            else {
                showCustomAlert("Please select Customer", "info");
            }

        });
        /** Sales Quote New Ship to End **/

        /** Sales Quote New Ship to Start **/
        $('.shipto').click(function () {
            var cid = $('.customer_id').val();
            var c_name = $.trim($('.customer_id option:selected').text()).split("-");
            if (cid == "") {
                notyMsg('info', "Please select Customer first");
            }
            else {

                $('#customerModal').modal('show');
                $('#customerModal').width("100%");

                var ct = $(this).val();
                $('.custype').val(ct);
                var custype = $('.custype').val();

                if (ct == "shipto") {
                    var site_type = "SHIP_TO";
                }

                $(mygrid).jqGrid('setGridParam',
                    {
                        postData: { "site_type": site_type, "cid": cid }
                    }).trigger('reloadGrid');
                $("#gs_customer_name").val($.trim(c_name[1]));
                $("#gs_site_type").val(site_type);
                $("#gs_customer_number").val($.trim(c_name[0]));
                $("#gs_customer_name,#gs_site_type,#gs_customer_number").attr('readonly', true);
            }

        });
        /** Sales Quote New Ship to End **/

        /** Sales Quote New Bill to Start **/
        $(document).on('click', '.new_billto', function () {

            var cid = $('.customer_id option:selected').val();

            $(':input', '#newaddress')
                .not(':button, :submit, :reset, :hidden')
                .val('')
                .prop('checked', false)
                .prop('selected', false);
            $('.city,.country,.state').select2('val', ['']);
            if (cid != "") {
                $('#new_address').modal('show');
                $('#new_address').width("100%");
                var ct = "BILL_TO";
                $('.custype').val(ct);
            }
            else {
                showCustomAlert("Please select Customer", 'warning');
            }

        });
        /** Sales Quote New Bill to End **/

        /** Sales Quote Customer to Start **/
        $('.billto').click(function () {
            var cid = $('.customer_id').val();
            var c_name = $.trim($('.customer_id option:selected').text()).split("-");
            if (cid == "") {
                showCustomAlert("Please select Customer first", "warning");
            }
            else {
                $('#customerModal').modal('show');
                $('#customerModal').width("100%");
                $('#custtype').val('1');
                var ct = $(this).val();
                $('.custype').val(ct);
                var custype = $('.custype').val();
                var custtype = $('#custtype').val();
                if (custtype == "1") {
                    if (ct == "billto") {
                        var site_type = "BILL_TO";
                    }
                }
                else {
                    var site_type = null;
                    var cid = null;

                }
                $(mygrid).jqGrid('setGridParam',
                    {
                        postData: { "site_type": site_type, "cid": cid }
                    }).trigger('reloadGrid');
                $("#gs_customer_name").val($.trim(c_name[1]));
                $("#gs_site_type").val(site_type);
                $("#gs_customer_number").val($.trim(c_name[0]));
                $("#gs_customer_name,#gs_site_type,#gs_customer_number").attr('readonly', true);
            }

        });


        /** Sales Quote Approve Status to Start **/
        $(".approve").on('click', function () {
            $("#quote_status").val("APPROVED").change();
        });
        /** Sales Quote Approve Status to End **/

        /** Sales Quote Approve Status to Start **/
        $(document).on('click', '.approve1', function (e) {
            var approve = $(this).val();
            //$("#quote_status").val("APPROVED").change();
            e.preventDefault();
            var data;
            data = $("#soquoteapproval").serialize();
            var indexurl = "{{URL::to('salesquoteapproval')}}";
            var url = "{{URL::to('soquoteapprovalsave')}}?approve=" + approve;
            $.post(url, data, function (data1) {
                var status = data1.status;
                var msg = data1.message;
                notyMessage(status, msg, indexurl);
            });
        });
        /** Sales Quote Approve Status to End **/

        $('.source_div,.organization_id_div').css('pointer-events', 'none');

        /** Sales Quote Qty Required to Start **/
        function qtyrequiredvalid() {
            $('.bulk_qty').each(function (i) {
                var val = $(this).val();
                if (val == 0) {
                    $('.bulk_qty' + i).val('');
                }
            });
        }
        /** Sales Quote Qty Required to end **/

        /** Sales Quote Product to Start **/
        $('.bulk_product_id').select2();
        var qotype = "{{ $row['quote_type'] }}";
        $('.req').hide();

        // save
        $(document).on('click', '.saveform', function () {
            var qotype = "{{ $row['quote_type'] }}";
            var btnval = $(this).val();
            if (btnval == 'APPLYCHANGES') {
                $('.remarks').attr('required', false);
                $("#quote_status").val('DRAFT').change();
            }

            else if (btnval == 'APPROVE') {
                $('.remarks').attr('required', false);
                $("#quote_status").val('APPROVED').change();
            }
            else if (btnval == 'REJECT') {
                $('.req').show();
                $('.remarks').attr('required', true);
                $("#quote_status").val('REJECTED').change();
            }
            else {
                $('.remarks').attr('required', false);
                $("#quote_status").val('INITIATED').change();
            }

            if (btnval == 'APPLYCHANGES')
                var savestatus = 'DRAFT';

            else
                var savestatus = 'SAVE';

            $('#savestatus').val(savestatus).change();

            var url = "{{ url('soquotesave') }}";
            var red_url = "{{ url($pageMethod) }}";
            var create_url = "{{ url('soquotecreate') }}/0/" + qotype;
            var form = $('#soquote');
            qtyrequiredvalid();
            console.log(btnval);
            if (btnval != 'APPLYCHANGES') {
                form.parsley().validate();

                if (form.parsley().isValid()) {

                    var form_data = new FormData(document.getElementById('soquote'));
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: form_data,
                        enctype: 'multipart/form-data',
                        processData: false,  // tell jQuery not to process the data
                        contentType: false,   // tell jQuery not to set contentType
                        async: true,
                        xhr: function () {
                            var xhr = $.ajaxSettings.xhr();
                            if (xhr.upload) {
                                xhr.upload.addEventListener('progress', function (event) {
                                    var percent = 0;
                                    var position = event.loaded || event.position;
                                    var total = event.total;
                                    if (event.lengthComputable) {
                                        percent = Math.ceil(position / total * 100);
                                    }
                                    //update progressbar

                                }, true);
                            }
                            return xhr;

                        }
                    }).done(function (data) {
                        var status = data.status;
                        var msg = data.message;
                        var id = data.id;
                        var edit_url = "{{ url('soquotecreate') }}/" + id + "/" + qotype;
                        ;

                        if (btnval == 'SAVENEW') {
                            showCustomAlert(status, msg);
                            window.location.href = create_url;

                        }
                        if (btnval != 'SAVE' && btnval != 'DRAFT' && btnval != 'APPROVE' && btnval != 'REJECT' && btnval != 'SAVENEW') {
                            showCustomAlert(msg, status);
                            window.location.href = edit_url;
                        }
                        if (btnval == 'SAVENEW') {
                            showCustomAlert(msg, status);
                            window.location.href = create_url;
                        }else {
                            showCustomAlert(msg, status);
                            window.location.href = red_url;

                        }
                    });
                }
            }else {

                var formdata = $('#soquote').serialize();
                var form_data = new FormData(document.getElementById('soquote'));
                $.ajax({
                    url: url,
                    type: "POST",
                    data: form_data,
                    enctype: 'multipart/form-data',
                    processData: false,  // tell jQuery not to process the data
                    contentType: false,   // tell jQuery not to set contentType
                    async: true,
                    xhr: function () {
                        var xhr = $.ajaxSettings.xhr();
                        if (xhr.upload) {
                            xhr.upload.addEventListener('progress', function (event) {
                                var percent = 0;
                                var position = event.loaded || event.position;
                                var total = event.total;
                                if (event.lengthComputable) {
                                    percent = Math.ceil(position / total * 100);
                                }


                            }, true);
                        }
                        return xhr;

                    }
                }).done(function (data) {


                    var status = data.status;
                    var msg = data.message;
                    var id = data.id;
                    var edit_url = "{{ url('soquotecreate') }}/" + id + "/" + qotype;

                    showCustomAlert("Draft Successfully", status);

                    window.location.href = edit_url;



                });
            }


        });



        /** Sales Quote number Validation  Start **/
        $(document).on('keypress', '.bulk_unit_price,.extracharge, .bulk_qty,.bulk_discount_percentage,.contact_number, .packaging_charges, .transport_charges, .insurance_charges', function (ev) {
            var regex = new RegExp("^[0-9.]+$");
            var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
            if (regex.test(str)) {
                return true;
            }
            ev.preventDefault();
            return false;
        });
        /** Sales Quote number Validation  End **/
        /*copy paste validation*/

        $('.bulk_qty,.bulk_discount_percentage').bind("cut copy paste", function (e) {
            e.preventDefault();
        });
        /*copy paste validation*/

        /** Sales Quote Discount Amount Start **/
        $(document).on('keyup change', '.bulk_discount_amount', function () {
            var index = $(this).closest("tr").index();
            var unitprice = $('.bulk_unit_price' + index).val();
            var requiredqty = $('.bulk_qty' + index).val();
            var taxgrp = $('.bulk_tax_group_id' + index + ' option:selected').attr('data-display');
            taxgrp = taxgrp ? taxgrp : 0;
            var discountsperc = $('.bulk_discount_percentage' + index).val();
            var disamout = $('.bulk_discount_amount' + index).val();
            var subtot = ((requiredqty * unitprice) - disamout);
            var disamt = ((disamout * 100) / (requiredqty * unitprice)).toFixed("{{\Session::get('decimal')}}");
            $('.bulk_discount_percentage' + index).val(disamt);

            var taxamount = parseFloat(subtot * taxgrp / 100).toFixed("{{\Session::get('decimal')}}");

            $('.bulk_tax_amount' + index).val(taxamount);

            var linetot = (parseFloat(subtot) + parseFloat(taxamount)).toFixed("{{\Session::get('decimal')}}");
            $(".bulk_line_total" + index).val(linetot);


            /* Code for set linetotal values into header level field*/
            var sum = 0;
            var sumtax = 0;
            var sumall = 0;
            var charge = 0;

            $('.charges').each(function () {
                var amt = $(this).val();
                if (amt == "")
                    amt = 0;
                charge += parseFloat(amt);
            });
            $('.bulk_line_total').each(function () {
                sum += parseFloat($(this).val());
            });
            $('.bulk_tax_amount').each(function () {
                sumtax += parseFloat($(this).val());
            });

            sumall = Number(charge) + Number(sum);
            $('#order_tax').val(sumtax);
            $('#order_total').val(sumall);
            /* end */
            /* Code for calculating tot tax amount */
            var tax = 0;
            $('.bulk_tax_amount').each(function () {
                tax += parseFloat($(this).val());
            });
            $('.quote_tax').val(tax);
            /* end */
        });
        /** Sales Quote Discount Amount End **/

        /** Sales Quote Change Discount Start **/
        $(document).on('change', '.discount_id', function () {
            $(".bulk_product_id").each(function (index) {
                var dis_customer = $('.discount_id option:selected').attr('data-display');

                var unitprice = $('.bulk_unit_price' + index).val();
                var requiredqty = $('.bulk_qty' + index).val();
                var taxgrp = $('.bulk_tax_group_id' + index + ' option:selected').attr('data-display');
                taxgrp = taxgrp ? taxgrp : 0;
                $('.bulk_discount_percentage' + index).val(dis_customer);
                var discountsperc = $('.bulk_discount_percentage' + index).val();
                var disamout = parseFloat(((requiredqty * unitprice) * discountsperc / 100)).toFixed("{{\Session::get('decimal')}}");
                $('.bulk_discount_amount' + index).val(disamout);
                var taxamount = parseFloat(((requiredqty * unitprice) - disamout) * taxgrp / 100).toFixed("{{\Session::get('decimal')}}");

                $('.bulk_tax_amount' + index).val(taxamount);
                var subtot = parseFloat((requiredqty * unitprice) - disamout).toFixed("{{\Session::get('decimal')}}");
                var linetot = parseFloat(subtot) + parseFloat(taxamount);
                $(".bulk_line_total" + index).val(linetot.toFixed("{{\Session::get('decimal')}}"));
                /* Code for set linetotal values into header level field*/
                remove_cal();
            });
        });
        /** Sales Quote Change Discount End **/


        /** Sales Quote Change Discount Start **/
        $(document).on(' change ', '.bulk_qty,.bulk_unit_price,.bulk_discount_percentage,.bulk_tax_group_id,.bulk_hsn_code,.bulk_product_id,.charges', function () {

            var dis_customer = $('.discount_id option:selected').attr('data-display');
            var index = $(this).closest("tr").index();
            var unitprice = $('.bulk_unit_price' + index).val();
            var requiredqty = $('.bulk_qty' + index).val();
            var taxgrp = $('.bulk_tax_group_id' + index + ' option:selected').attr('data-display');
            taxgrp = taxgrp ? taxgrp : 0;
            if (dis_customer != undefined)
                $('.bulk_discount_percentage' + index).val(dis_customer);
            var discountsperc = $('.bulk_discount_percentage' + index).val();
            var disamout = parseFloat(((requiredqty * unitprice) * discountsperc / 100)).toFixed("{{\Session::get('decimal')}}");
            $('.bulk_discount_amount' + index).val(disamout);
            var taxamount = parseFloat(((requiredqty * unitprice) - disamout) * taxgrp / 100).toFixed("{{\Session::get('decimal')}}");

            $('.bulk_tax_amount' + index).val(taxamount);
            var subtot = parseFloat((requiredqty * unitprice) - disamout).toFixed("{{\Session::get('decimal')}}");
            var linetot = parseFloat(subtot) + parseFloat(taxamount);
            $(".bulk_line_total" + index).val(linetot.toFixed("{{\Session::get('decimal')}}"));
            /* Code for set linetotal values into header level field*/
            remove_cal();
            /* end */
        });
        /** Sales Quote Change Discount End **/

        /** Sales Quote Calculation  Start **/
        function calc_by_index(index) {
            var dis_customer = $('.discount_id option:selected').attr('data-display');
            var unitprice = $('.bulk_unit_price' + index).val();
            var requiredqty = $('.bulk_qty' + index).val();
            var taxgrp = $('.bulk_tax_group_id' + index + ' option:selected').attr('data-display');
            taxgrp = taxgrp ? taxgrp : 0;
            $('.bulk_discount_percentage' + index).val(dis_customer);
            var discountsperc = $('.bulk_discount_percentage' + index).val();
            var disamout = (((requiredqty * unitprice) * discountsperc / 100));
            $('.bulk_discount_amount' + index).val(disamout);
            var taxamount = ((requiredqty * unitprice) - disamout) * taxgrp / 100;

            $('.bulk_tax_amount' + index).val(taxamount);
            var subtot = ((requiredqty * unitprice) - disamout);
            var linetot = parseFloat(subtot + taxamount).toFixed($('#decimal_point').val());
            $(".bulk_line_total" + index).val(linetot);

            /* Code for set linetotal values into header level field*/
            var sum = 0;
            var sumtax = 0;
            var sumall = 0;
            var charge = 0;
            $(".charges").each(function () {
                charge += +$(this).val();
            });
            $('.bulk_line_total').each(function () {
                sum += parseFloat($(this).val());
            });
            sumall = Number(charge) + Number(sum);


            $('.bulk_tax_amount').each(function () {
                sumtax += parseFloat($(this).val());
            });
            $('#quote_tax_total').val(sumtax);
            $('#quote_grand_total').val(sumall);
            /* end */
            /* Code for calculating tot tax amount */
            var tax = 0;
            $('.bulk_tax_amount').each(function () {
                tax += parseFloat($(this).val());
            });
            $('.quote_tax').val(tax);
        }
        /** Sales Quote Calculation  End **/

        /** Sales Quote Remove Calculation  Start **/
        function remove_cal() {
            var sum = 0;
            var sumtax = 0;
            var charge = 0;
            var sumall = 0;
            $('.charges').each(function () {
                charge += +$(this).val();
            });
            $('.bulk_line_total').each(function () {
                sum += parseFloat($(this).val());
            });
            $('.bulk_tax_amount').each(function () {
                sumtax += parseFloat($(this).val());
            });
            sumall = Number(charge) + Number(sum);



            $('#quote_tax_total').val(sumtax.toFixed("{{\Session::get('decimal')}}"));

            $('#quote_grand_total').val(sumall.toFixed("{{\Session::get('decimal')}}"));
            $('.tax_total_span').html(sumtax.toFixed("{{\Session::get('decimal')}}"));
            $('.order_total_span').html(sumall.toFixed("{{\Session::get('decimal')}}"));
            /* end */

            /* Code for calculating tot tax amount */
            var tax = 0;
            $('.bulk_tax_amount').each(function () {
                tax += parseFloat($(this).val());
            });

            $('.quote_tax').val(tax.toFixed("{{\Session::get('decimal')}}"));
            $('.tax_total_span').html(tax.toFixed("{{\Session::get('decimal')}}"));
        }
        /** Sales Quote Remove Calculation End **/

        /** Sales Quote Customer Search Start **/
        $('.customersearch').click(function () {
            $('#customerModal').modal('show');
            $('#customerModal').width("100%");
        });
        /** Sales Quote Customer Search End **/

        /** Sales Quote Customer Search Model Start **/
        $('.customersearch').click(function () {
            $('#customerModal').modal('show');
            $('#customerModal').width("100%");
            $('#custtype').val('1');
            var custtype = $('#custtype').val();

            var ct = $(this).val();
            $('.custype').val(ct);
            if (custtype == "1") {
                if (ct == "billto") {
                    var site_type = "BILL_TO";
                }
            }
            else {
                var site_type = null;
                var cid = null;

            }
            $(mygrid).jqGrid('setGridParam', {
                postData: { "site_type": null, "cid": null }
            }).trigger('reloadGrid');
            $("#gs_customer_name").val('');
            $("#gs_site_type").val('');
            $("#gs_customer_number").val('');
            $("#gs_customer_name,#gs_site_type,#gs_customer_number").attr('readonly', false);
        });
        /** Sales Quote Customer Search Model End **/

        //udhayavarma
        /** Sales Quote Product Search Model Start **/
        $(document).on('click', '.productsearch', function () {

            var customer_id = $('.customer_id option:selected').val();
            var index = ($(this).closest('tr').index());
            if (customer_id != '') {
                var index = ($(this).closest('tr').index());
                $('.pdtindex').val(index);
                $('#productModal').modal('show');
                $('#productModal').width("100%");

                $('.pdtbtn').parent('div').html('');
                var mypdtgrid = $("#productgrid"),
                    pagerSelector = "#pager",
                    myAddButton = function (options) {
                        mypdtgrid.jqGrid('navButtonAdd', pagerSelector, options);
                        mypdtgrid.jqGrid('navButtonAdd', '#' + mypdtgrid[0].id + "_toppager", options);
                    };
                var groupname = "'FINISHED GOODS'";
                var pricelist_id = $('.quote_pricelist_id option:selected').val();
                <?php { ?>
                    var grp = [];
                    grp.push(groupname);
                <?php } ?>

                mypdtgrid.jqGrid({
                    url: "{{ URL::to('getProductgridData') }}?prggrp=" + grp + "&pricelist_id=" + pricelist_id,
                    datatype: "json",
                    mtype: "GET",
                    height: 320,
                    width: 1000,
                    colModel: [
                        { name: "product_code", label: "Product Code", width: 55 },
                        { name: "group_name", label: "Product Group", width: 55 },
                        { name: "category_name", label: "Product Category" },
                        { name: "concatenated_product", label: "Product Name" },
                        { name: "product_id", label: "id", hidden: true, width: 55 }
                    ],

                    iconSet: "fontAwesome",
                    rowNum: 10,
                    rowList: [10, 20, 100, 1000],
                    sortorder: "asc",
                    viewrecords: true,
                    gridview: true,
                    rownumbers: true,
                    pager: pagerSelector,
                    toppager: true,
                    searching: {
                        defaultSearch: "cn"
                    }
                });
                jQuery(mypdtgrid).jqGrid('filterToolbar', { stringResult: true, searchOnEnter: false });
                jQuery("#gs_productgrid_product_category_id").select2();
                mypdtgrid.jqGrid('navGrid', pagerSelector,
                    { cloneToTop: true, edit: false, add: false, del: false, search: true });
                $('.ui-icon-refresh').hide();
                myAddButton({
                    caption: "Select Product",
                    title: "Product",
                    buttonicon: 'fa fa-plus pdtbtn',
                    onClickButton: function () {
                        var index = $('.pdtindex').val();
                        var gr = jQuery(mypdtgrid).jqGrid('getGridParam', 'selrow');
                        var product = jQuery(mypdtgrid).jqGrid('getCell', gr, 'product_id');
                        if (product != false) {
                            $('.bulk_product_id' + index).val(product);

                            $('.bulk_product_id' + index).trigger('change');



                            $('#productModal').modal('hide');
                        }
                        else {
                            notyMsg('info', 'Please Select one row');
                        }
                    }
                });

            }
            else {
                notyMsgs('info', 'Please select Customer');
            }
        });


        var mygrid = $("#customergrid"),
            pagerSelector = "#pager",
            myAddButton = function (options) {
                mygrid.jqGrid('navButtonAdd', pagerSelector, options);
                mygrid.jqGrid('navButtonAdd', '#' + mygrid[0].id + "_toppager", options);
            };
        var cid = $('.customer_id').val();
        var custtype = $('#custtype').val();
        var custype = $('.custype').val();
        var site_type = "SHIP_TO";
        if (custtype == "1") {
            if (custype == "billto") {
                var site_type = "BILL_TO";
            }
        }
        else {
            var site_type = null;
            var cid = null;

        }
        // console.log(site_type+'---'+cid);
        // var myCaption = 'Name: <input id="icname" type="text" value="" />';
        //$('#customergrid').jqGrid('setCaption',myCaption);

        mygrid.jqGrid({
            url: "{{ URL::to('getCustomergridData') }}",
            datatype: "json",
            mtype: "GET",
            postData: { "site_type": site_type, "cid": cid },
            height: 320,
            width: 1000,
            colModel: [
                { name: "customer_id", label: "id", hidden: true, width: 55 },
                { name: "customer_site_id", label: "id", hidden: true, width: 55 },
                { name: "customer_number", label: "Customer Number", width: 55, searchoptions: { clearSearch: false } },
                { name: "customer_name", label: "Customer Name", searchoptions: { clearSearch: false } },
                { name: "customer_type", label: "Customer Type", width: 55 },
                { name: "customer_site_name", label: "Customer Site Name", width: 55 },
                { name: "site_type", label: "Customer Site Type", width: 55, searchoptions: { clearSearch: false } },
                { name: "address", label: "Address", width: 55 },
                { name: "city_name", label: "City", width: 55 },
                { name: "state_name", label: "State", width: 55 },
                { name: "country_name", label: "Country", width: 55 }
            ],

            iconSet: "fontAwesome",
            rowNum: 10,
            rowList: [10, 20, 100, 1000],
            sortorder: "asc",
            viewrecords: true,
            gridview: true,
            rownumbers: true,
            pager: pagerSelector,
            toppager: true,
            searching: {
                defaultSearch: "cn"
            }

        });

        jQuery(mygrid).jqGrid('filterToolbar', { stringResult: true, searchOnEnter: false });
        jQuery("#gs_customergrid_customer_name").select2();
        jQuery("#gs_customergrid_city").select2();
        jQuery("#gs_customergrid_state").select2();
        jQuery("#gs_customergrid_country").select2();
        mygrid.jqGrid('navGrid', pagerSelector,
            { cloneToTop: true, edit: false, add: false, del: false, search: true });

        myAddButton({
            caption: "Select Customer",
            title: "Customer",
            buttonicon: 'ui-icon-plus',
            onClickButton: function () {
                var gr = jQuery(mygrid).jqGrid('getGridParam', 'selrow');
                var customer = jQuery(mygrid).jqGrid('getCell', gr, 'customer_id');
                var site_type = jQuery(mygrid).jqGrid('getCell', gr, 'site_type');
                var customer_site_id = jQuery(mygrid).jqGrid('getCell', gr, 'customer_site_id');

                if (gr) {
                    $('#customerModal').modal('hide');

                    var url = "{{ url::to('custaddress') }}/" + customer + "/" + customer_site_id;
                    $.get(url, function (data) {
                        var check = $('.customer_id').select2('val');
                        if (check != customer) {
                            $('.customer_id').select2('val', [customer]);
                        }
                        if (data != '') {
                            if (site_type == "BILL_TO") {

                                $('.bill_to_address_id').val(customer_site_id);
                                $('.bill_to_address').val(data);
                            }
                            else if (site_type == "SHIP_TO") {

                                $('.ship_to_address_id').val(customer_site_id);
                                $('.ship_to_address').val(data);
                            }
                        }

                    });




                }
                else {
                    notyMsg('info', 'Please Select one row');
                }
            }

        });


        $('#refresh_customergrid_top > div > span').addClass('refreshcust');
        $(".refreshcust").click(function () {
            var cid = $('.customer_id').val();
            if (cid == "") {
                notyMsg('info', "Please select Customer first");
            }
            else {
                var site_type = "SHIP_TO";
                var custype = $('.custype').val();

                if (custype == "billto") {
                    var site_type = "BILL_TO";
                }
                else {
                    var site_type = "SHIP_TO";
                }
                $(mygrid).jqGrid('setGridParam',
                    {
                        postData: { "site_type": site_type, "cid": cid }
                    }).trigger('reloadGrid');
            }

        });
        $(document).on('change', '.GetFileSizeNameAndType', function () {

            var fi = document.getElementById('choosefile'); // GET THE FILE INPUT AS VARIABLE.

            var totalFileSize = 0;

            // VALIDATE OR CHECK IF ANY FILE IS SELECTED.
            if (fi.files.length > 0) {
                // RUN A LOOP TO CHECK EACH SELECTED FILE.
                for (var i = 0; i <= fi.files.length - 1; i++) {
                    //ACCESS THE SIZE PROPERTY OF THE ITEM OBJECT IN FILES COLLECTION. IN THIS WAY ALSO GET OTHER PROPERTIES LIKE FILENAME AND FILETYPE
                    var fsize = fi.files.item(i).size;
                    totalFileSize = totalFileSize + fsize;
                    document.getElementById('fp').innerHTML =
                        document.getElementById('fp').innerHTML
                        +
                        '<tr><td><span class="note" ><br /> File:<span class="files">' + fi.files.item(i).name + '</span>&nbsp;<img src="{{URL::to('')}}/images/cancel.png" data-value="" class="delete_user"></span></td></tr>';
                }
            }
            //document.getElementById('divTotalSize').innerHTML = "Total File(s) Size is <b>" + Math.round(totalFileSize / 1024) + "</b> KB";
        });



        $(document).on('click', '.delete_user', function () {
            var quote_hdr = '{{$row['quote_hdr_id']}}';
            if (quote_hdr != '') {
                var existing_value = $('#existing_file').val();
                var delete_value = $(this).attr('data-value');


                removeValue(existing_value, delete_value);
            }

            $(this).parent().parent().remove();
        });
        function removeValue(existing_value, delete_value) {
            list = existing_value.split(',');
            list.splice(list.indexOf(delete_value), 1);
            var values = list.join(',');
            if (values != '')
                $('#existing_file').val(values);
            else
                $('#existing_file').val('');
        }

        $(document).on('change', '.bulk_tax_excemption', function (event) {
            var index = $(this).closest('tr').index();
            var taxval = $('.bulk_tax_excemption' + index).val();
            if (taxval == "Yes") {
                $('.bulk_tax_group_id' + index).select2('val', ['8']);
            } else {
                $('.bulk_hsn_code' + index).trigger('change');
            }
        });

        $(document).on('change', '.bulk_product_id', function (event) {
            //alert("k");
            var index = $(this).closest('tr').index();
            <?php if ($pageMethod != "salesquoteapproval") { ?>

                var pid = $(".bulk_product_id" + index).val();

                var type = 'so'
                var plid = $('.quote_pricelist_id option:selected').val();
                var cid = $('.customer_id option:selected').val();
                var cusid = $('.bill_to_address_id').val();
                var url = "{{ url::to('productdetails_so') }}/" + pid + "/" + plid + '/' + cusid + '/' + type + '?customer_id=' + cid;
                if (cid != '') {
                    if (plid != '') {
                        if (pid != '') {
                            var pdtcount = 0;
                            var pdtcount = pdtcheck(pid, index);
                            if (pdtcount <= 0) {
                                $.get(url, function (data) {

                                    var hsnid = data['multihsn'];
                                    var qotype = "{{ $row['quote_type'] }}";
                                    if (qotype != "LABOUR") {
                                        var condition = "classification_name='HSN' and gst_code_hdr_id in(" + hsnid + ")";
                                    }
                                    else {
                                        var condition = "classification_name='SAC' and gst_code_hdr_id in(" + hsnid + ")";
                                    }
                                    var hsn = data.hsn_code;
                                    $(".bulk_hsn_code" + index).jCombo("{{ URL::to('jcomboform?table=f_gst_code_hdr_t:gst_code_hdr_id:classification_code') }}&order_by=classification_code asc" + '&parent=' + condition,
                                        { selected_value: hsn.toString() });

                                    $('.bulk_uom_code_id' + index).val(data.uom_code_id).change();

                                    $('.bulk_tax_group_id' + index).val(data.tax_group_id).attr('readonly', true).change();

                                    $(".bulk_part_no" + index).select2('val', [data.manufactpartno]);

                                    if (data.unit_price != '') {
                                        var price = parseFloat(data.unit_price).toFixed("{{\Session::get('decimal')}}");
                                        $('.bulk_unit_price' + index).val(price);
                                        $('.tax').css('pointer-events', 'none');
                                        $('.uom').css('pointer-events', 'none');
                                    }
                                    else {
                                        $('.bulk_unit_price' + index).val('');
                                    }

                                    if ((data.unit_price == 0 || data.unit_price == '') && (data.tax_group_id == '')) {

                                        showCustomAlert('Please Assingn Unit Price And Tax Group for this Product (or) pricelist Date has been Expired for this Product', 'info');

                                    }
                                    if ((data.unit_price != 0 || data.unit_price != '') && (data.tax_group_id == '')) {
                                        showCustomAlert('Please Assingn Tax Group For this Product', 'info');

                                    }
                                    if ((data.unit_price == 0 || data.unit_price == '') && (data.tax_group_id != '')) {

                                        showCustomAlert('Please Assingn unit price For this Product (or) pricelist  Date has been Expired for this Product', 'info');

                                    }

                                    $('.bulk_qoh_qty' + index).val(data.qoh_qty);
                                    calc_by_index(index);
                                });

                            }
                            else {
                                showCustomAlert('Product Already Selected', 'info');
                                $(".bulk_product_id" + index).val('').change();
                                event.preventDefault();
                                rowdataEmpty(index);
                            }
                        }
                        else {

                            rowdataEmpty(index);
                        }
                    }
                    else {

                        showCustomAlert('Please Select Pricelist!!!', 'info');

                        event.preventDefault();
                    }
                }
                else {

                    showCustomAlert('Please Select Customer!!!', 'info');
                    event.preventDefault();
                }
            <?php } ?>
        });

        var quote = '<php echo $row["quote_type"] ?> ';
        if (quote == "STANDARD") {
            $('.bulk_uom_code_id').attr('required', true);
            $('.bulk_product_id').attr('required', true);
            $('.bulk_product_description').removeAttr('required');
        } else {
            $('.bulk_uom_code_id').removeAttr('required');
            $('.bulk_product_id').removeAttr('required');
            $('.bulk_product_description').attr('required', true);
        }


        /*deepika purpose: to load tax based on hsn code*/
        $(document).on('change', '.bulk_hsn_code', function () {

            var hsnid = $(this).val();
            var index = $(this).closest('tr').index();
            var suppsiteid = $('.bill_to_address_id').val();
            var taxval = $('.bulk_tax_excemption' + index).val();
            var m_type = "Sales";
            if (hsnid != "" && hsnid != 0 && suppsiteid != "") {
                var url = "{{ URL::to('taxdetails')}}/" + hsnid + "/" + suppsiteid + "/" + m_type;
                if (taxval == "") {
                    notyMsg('info', 'Please select Tax Excemption');
                    $('.bulk_hsn_code' + index).select2('val', ['']);
                } else
                    if (taxval == "Yes") {
                        $('.bulk_tax_group_id' + index).select2('val', ['8']);

                    } else {
                        $.get(url, function (data) {

                            if (data['tax_group_id'] == 0) {
                                if ($.trim(data['tax_group_id_expiry']) == "expiry") {
                                    $('.bulk_tax_group_id' + index).select2('val', [data['tax_group_id']]);
                                    showCustomAlert('Tax Group expired  for this product', 'info');
                                }
                                else if ($.trim(data['tax_group_id_expiry']) == "location") {
                                    showCustomAlert('Tax not assigned for this Location', 'info');
                                }
                                else {
                                    $('.bulk_tax_group_id' + index).select2('val', [data['tax_group_id']]);
                                    showCustomAlert('Tax Group not assigned for this productef', 'info');
                                }
                            }
                            else {
                                $('.bulk_tax_group_id' + index).select2('val', [data.tax_group_id]);
                                calc_by_index(index);
                            }

                        });
                    }
            }

        });


        /*end*/

        //  purpose quote from inquiry remove product not baseed on pricelist
        <?php if ($pageMethod == 'salesquotefromenquiry') { ?>

            $('.customer_id').trigger('change');

        <?php } ?>



        <?php if ($row['quote_type'] == "STANDARD") { ?>
            $('.pdtdiv').removeClass('hide');
            $('.pdtdes_div').addClass('hide');
            $('.bulk_product_description').removeAttr('required');
            $('.hsn').show();

        <?php } ?>





        $('.quote_date').datepicker({ format: 'yyyy-mm-dd', autoClose: true })
        <?php if ($pageMethod != "soquotecreate" && $pageMethod != "copysalesquote") { ?>
            $(".bulk_product_id").each(function (index) {

                $(this).trigger('change');
            });
        <?php } ?>
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

/** Sales Quote Country Start **/
$(document).on('change', '.country', function () {
    var country_id = $(this).val();

    var url = "{{ URL::to('jcomboformlogin') }}?table=m_states_t:state_id:state_name"
        + "&parent=country_id=" + country_id
        + "&order_by=state_name asc";

    $.ajax({
        url: url,
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

            var $state = $(".state");
            $state.html('<option value="">-- Please Select --</option>');

            $.each(data, function (i, item) {
                $state.append(`<option value="${item.val}">${item.option_name}</option>`);
            });

            if (country_id !== '') {
                $(".statehide").css('pointer-events', 'auto');
            }

            // Clear city when country changes
            $(".city").html('<option value="">-- Please Select --</option>');
        }
    });
});
/** Sales Quote Country End **/

/** Sales Quote State Start **/
$(document).on('change', '.state', function () {
    var state_id = $(this).val();

    if (state_id !== "") {
        var url = "{{ URL::to('jcomboformlogin') }}?table=m_cities_t:city_id:city_name"
            + "&parent=state_id=" + state_id
            + "&order_by=city_name asc";

        $.ajax({
            url: url,
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

                var $city = $(".city");
                $city.html('<option value="">-- Please Select --</option>');

                $.each(data, function (i, item) {
                    $city.append(`<option value="${item.val}">${item.option_name}</option>`);
                });

                $(".cityhide").css('pointer-events', 'auto');
            }
        });
    }
});
/** Sales Quote State End **/
	
</script>

@endpush