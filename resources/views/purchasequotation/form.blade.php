@extends('layouts.header')
@section('content')

    <?php error_reporting(0);
    if ($row->source == 'STANDARD' && $row->quotation_hdr_id == '' && !isset($copy_quotation_no)) {
        $head = " (New)";
    } else if ($row->quotation_hdr_id != '') {
        $head = " (" . $row->quotation_no . " )";
    } else if (isset($copy_quotation_no)) {
        $head = " (Copy From " . $copy_quotation_no . " )";

    } else {
        $head = " (Convert From " . $row->source . " )";
    }


                ?>
    <?php include('tools_menu.php'); ?>
    <h3 class="text-danger">Purchase Quotation {{$head}}</h3>
    @include('layouts.breadcrumb')



    <form method="post" action="" id="poquote_form" class="poquote_form needs-validation" novalidate>
        <input type="hidden" id="savestatus" value="">
        @csrf

        <!-- Card -->
        <div class="card shadow-lg rounded-4 border-0">
            <!-- Card Header -->
            <div class="card-header bg-primary text-white">
                <div class="row g-3">
                    <div class="col-md-3">
                        <strong>Quotation Date:</strong> <span class="bg-secondary badge">{{ $row->quotation_date
                                        }}</span><br>
                        <strong>Quotation Type:</strong> <span class="bg-secondary badge">{{ $row->quotation_type }}</span>
                    </div>
                    <div class="col-md-3">
                        <strong>Source:</strong> <span class="bg-secondary badge">{{ $row->source }}</span><br>
                        <strong>Reference No.:</strong> <span class="bg-secondary badge">{{ $row->reference_number }}</span>
                    </div>
                    <div class="col-md-3">
                        <strong>Quote Tax Total:</strong> <span class="bg-success badge tax_total_span">{{ $row->quote_tax_total
                                        }}</span><br>
                        <strong>Quote Grand Total:</strong> <span class="bg-success badge grand_total_span">{{ $row->quote_grand_total
                                        }}</span>
                    </div>
                    <div class="col-md-3">
                        <strong>Created By:</strong> <span class="bg-secondary badge create_by"></span>
                    </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="card-body">
                <div class="row g-4">
                    <!-- Supplier Name -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-danger">* Supplier Name</label>
                        <div class="input-group">
                            <select name="supplier_id" class="form-select select2 supplier_id" required>
                                {!! $supplier_id !!}
                            </select>

                        </div>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-outline-secondary suppliersearch mt-3" type="button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>


                    <!-- purpose for on change function -->

                    <div class="form-group row" style="display:none;">
                        <label for="inputIsValid" class="form-control-label col-md-4">Quote No</label>
                        <div class="col-md-6">
                            <input class="form-control quotation_hdr_id" id="quotation_hdr_id" name="quotation_hdr_id"
                                size="16" type="hidden" value="{{ $row->quotation_hdr_id }}" readonly>
                            <input type="text" id="quotation_no" name="quotation_no" class="form-control quotation_no"
                                value="{{ $row->quotation_no }}" readonly>
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>

                    <div class="form-group row" style="display:none">
                        <label for="inputIsValid" class="form-control-label col-md-4">Quotation Date</label>
                        <div class="col-md-6">
                            <div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy"
                                data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <input class="form-control quotation_date datepicker" id="quotation_date"
                                    name="quotation_date" size="16" type="text" value="{{ $row->quotation_date }}" readonly>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="quotation_date" value="{{ $row->quotation_date }}" />
                        </div>
                        <div class="col-md-2 showinline">
                        </div>
                    </div>
                    <div class="form-group row" style="display:none">
                        <label for="inputIsValid" class="form-control-label col-md-4">Quotation Type</label>
                        <div class="col-md-6">
                            <select type="text" name="quotation_type" id="quotation_type"
                                class="form-control quotation_type" readonly>
                                <option value="">--select--</option>
                                <option <?php if ($row->quotation_type == "STANDARD") {
        echo "selected";
    } else {
        echo "";
    } ?> value="STANDARD">STANDARD</option>
                                <option <?php if ($row->quotation_type == "LABOUR") {
        echo "selected";
    } else {
        echo "";
    } ?>
                                    value="LABOUR">LABOUR</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                    <div class="form-group row" style="display:none">
                        <label for="inputIsValid" class="form-control-label col-md-4">Quotation Status</label>
                        <div class="col-md-6">
                            <select type="text" name="quote_status" id="quote_status" class="form-control quote_status"
                                readonly>
                                <option value="">--Please Select--</option>
                                <option <?php if ($row->quote_status == "DRAFT") {
        echo "selected";
    } else {
        echo "";
    } ?>
                                    value="DRAFT">DRAFT</option>
                                <option <?php if ($row->quote_status == "INITIATED") {
        echo "selected";
    } else {
        echo "";
    } ?>
                                    value="INITIATED">INITIATED</option>
                                <option <?php if ($row->quote_status == "APPROVED") {
        echo "selected";
    } else {
        echo "";
    } ?>
                                    value="APPROVED">APPROVED</option>
                                <option <?php if ($row->quote_status == "REJECTED") {
        echo "selected";
    } else {
        echo "";
    } ?>
                                    value="REJECTED">REJECTED</option>
                            </select>
                        </div>
                    </div>

                    <!-- end -->

                    <!-- Supplier Site -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Supplier Site</label>
                        <div class="input-group">
                            <select name="supplier_site_id" id="supplier_site_id"
                                class="form-select select2 supplier_site_id">
                                {!! $supplier_site_id !!}
                            </select>
                        </div>
                    </div>

                    <!-- Freight Carrier -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-danger">* Freight Carriers</label>
                        <div class="input-group">
                            <select name="freight_carrier_id" class="form-select select2 freight_carrier_id" required>
                                {!! $freight_carrier_id !!}
                            </select>
                        </div>
                    </div>
                </div>

                <!-- 2nd -->
                <div class="row g-3 mt-2">
                    <!-- Pricelist Name (Hidden) -->
                    <div class="col-md-4 d-none">
                        <label class="form-label">
                            <span class="text-danger">*</span> Pricelist Name
                        </label>
                        <select name="quote_pricelist_id" class="form-select quote_pricelist_id select2" readonly required>
                            {!! $quote_pricelist_id !!}
                        </select>
                    </div>

                    <!-- Active (Hidden) -->
                    <div class="col-md-4 d-none">
                        <label class="form-label">Active</label>
                        <select name="active" class="form-select active select2" id="active">
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>

                    <!-- Delivery Date -->
                    <div class="col-md-4">
                        <label class="form-label">Delivery Date</label>
                        <input type="text" name="delivery_date" id="delivery_date"
                            class="form-control delivery_date datepicker" data-link-format="yyyy-mm-dd"
                            value="{{ $row->delivery_date }}">
                    </div>

                    <!-- File Upload -->
<div class="col-md-4">
    <label class="form-label">File Upload</label>

    <div class="border p-2 rounded bg-light">

        @php
            $dataupload = json_decode($row->attachfile_name, true) ?? [];

            if ($return_url == "purchasequtoetionapprove") {
                $link = "download";
            } else {
                $link = '';
            }
        @endphp

        {{-- ALWAYS keep hidden input --}}
        <input type="hidden"
               name="existing_file"
               id="existing_file"
               value="{{ implode(',', $dataupload) }}">

        {{-- Show upload only if not approve screen --}}
        @if($return_url != "purchasequtoetionapprove")
            <input id="choosefile"
                   class="form-control GetFileSizeNameAndType"
                   name="choosefile[]"
                   type="file"
                   multiple>
        @endif

        <table class="table table-sm table-bordered mt-2" id="file_choosen">
            <tbody id="fp">

            @if(count($dataupload) > 0)
                @foreach($dataupload as $file)
                    <tr>
                        <td>
                            File:
                            <a {{ $link }}
                               href="{{ URL::to('') }}/Uploads/poquoteattachment/PO{{ $row->quotation_hdr_id }}/{{ $file }}"
                               target="_blank">
                                {{ $file }}
                            </a>

                            @if($return_url != "purchasequtoetionapprove")
                                <img src="{{ URL::to('') }}/images/cancel.png"
                                     class="delete_user ms-2"
                                     data-value="{{ $file }}"
                                     style="cursor:pointer;">
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endif

            </tbody>
        </table>

    </div>
</div>


                    <!-- Hidden Fields -->
                    <div class="col-md-4 d-none">
                        <label class="form-label">Created By</label>
                        <select name="created_by" class="form-select created_by select2">
                            {!! $created_by !!}
                        </select>
                    </div>

                    <div class="col-md-4 d-none">
                        <label class="form-label">Organization</label>
                        <select name="organization_id" class="form-select organization_id">
                            {!! $organization_id !!}
                        </select>
                    </div>

                    <div class="col-md-4 d-none">
                        <label class="form-label">Source</label>
                        <select name="source" class="form-select source" readonly>
                            <option value="">--select--</option>
                            <option {{ $row->source == "STANDARD" ? 'selected' : '' }} value="STANDARD">STANDARD</option>
                            <option {{ $row->source == "REQUISITION" ? 'selected' : '' }} value="REQUISITION">REQUISITION
                            </option>
                            <option {{ $row->source == "ENQUIRY" ? 'selected' : '' }} value="ENQUIRY">ENQUIRY</option>
                        </select>
                    </div>

                    <div class="col-md-4 d-none">
                        <label class="form-label">Reference No</label>
                        <input type="hidden" class="form-control reference_id" id="reference_id" name="reference_id"
                            value="{{ $row->reference_id }}">
                        <input type="text" id="reference_number" name="reference_number"
                            class="form-control reference_number" value="{{ $row->reference_number }}" readonly>
                    </div>

                    <div class="col-md-4 d-none">
                        <label class="form-label">Quote Tax Total</label>
                        <input type="text" name="quote_tax_total" id="quote_tax_total" value="{{ $row->quote_tax_total }}"
                            class="form-control quote_tax_total" readonly>
                    </div>

                    <div class="col-md-4 d-none">
                        <label class="form-label">Quote Grand Total</label>
                        <input type="text" name="quote_grand_total" id="quote_grand_total"
                            value="{{ $row->quote_grand_total }}" class="form-control quote_grand_total" readonly>
                    </div>

                </div>



                <!--3rd Purpose: Display Dynamic Columns from Column Permission Setting-->

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Additional Details</h5>
                    </div>

                    <div class="card-body">
                        <div class="row g-4">
                            <?php
    $i = 0;
    $j = 0;
    foreach ($enabled_columns as $index => $val) {
        $required = ($val->action == '1') ? 'required' : '';
        if ($i != $j) {
            $j = $i;
        }
                                            ?>

                            <?php    if ($val->column_name == 'supplier_ref_no' && $val->active == 1) {
            $i++; ?>
                            <div class="col-lg-4 col-md-6 supprefno_cfg">
                                <label for="supplier_ref_no" class="form-label">
                                    <?php        if ($required) { ?><span class="text-danger">*</span>
                                    <?php        } ?>Supplier Ref
                                    No
                                </label>
                                <input type="text" name="supplier_ref_no" id="supplier_ref_no" tabindex="7"
                                    class="form-control supplier_ref_no" <?= $required; ?>
                                    value="{{ $row->supplier_ref_no }}">
                            </div>
                            <?php    } ?>

                            <?php    if ($val->column_name == 'supplier_quotation_date' && $val->active == 1) {
            $i++; ?>
                            <div class="col-lg-4 col-md-6 suppquodate_cfg">
                                <label for="supplier_quotation_date" class="form-label">
                                    <?php        if ($required) { ?><span class="text-danger">*</span>
                                    <?php        } ?>Supplier
                                    Quote Date
                                </label>
                                <input type="date" class="form-control supplier_quotation_date" tabindex="8" <?= $required; ?> id="supplier_quotation_date" name="supplier_quotation_date"
                                    value="{{ $row->supplier_quotation_date }}">
                            </div>
                            <?php    } ?>

                            <?php    if ($val->column_name == 'project_id' && $val->active == 1) {
            $i++; ?>
                            <div class="col-lg-4 col-md-6 project_cfg">
                                <label for="project_id" class="form-label">
                                    <?php        if ($required) { ?><span class="text-danger">*</span>
                                    <?php        } ?>Project Name
                                </label>
                                <div class="d-flex align-items-center gap-2">
                                    <select name="project_id" id="project_id" rows="5" tabindex="15" <?= $required; ?>
                                        class="form-select project_id select2" data-live-search="true">{!! $project_id
                                                            !!}</select>

                                </div>
                            </div>
                            <?php    } ?>

                            <?php    if ($val->column_name == 'packing_charges' && $val->active == 1) {
            $i++; ?>
                            <div class="col-lg-4 col-md-6">
                                <input type="hidden" name="packing_charges_tax" id="packing_charges_tax"
                                    value="{{ $row->packing_charges_tax }}" class="packing_charges_tax">
                                <input type="hidden" name="insurance_charges_tax" id="insurance_charges_tax"
                                    value="{{ $row->insurance_charges_tax }}" class="insurance_charges_tax">
                                <input type="hidden" name="transport_charges_tax" id="transport_charges_tax"
                                    value="{{ $row->transport_charges_tax }}" class="transport_charges_tax">

                                <label for="packing_charges" class="form-label">
                                    <?php        if ($required) { ?><span class="text-danger">*</span>
                                    <?php        } ?>Packing
                                    Charges
                                </label>
                                <div class="input-group">
                                    <input type="text" name="packing_charges" id="packing_charges" <?= $required; ?>
                                        value="{{ $row->packing_charges }}" class="form-control charges packing_charges"
                                        readonly>
                                    <button type="button" class="btn btn-outline-secondary packingtax"
                                        data-value="Packing Charges" data-at="1" title="Add Tax">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <?php    } ?>

                            <?php    if ($val->column_name == 'transport_charges' && $val->active == 1) {
            $i++; ?>
                            <div class="col-lg-4 col-md-6">
                                <label for="transport_charges" class="form-label">
                                    <?php        if ($required) { ?><span class="text-danger">*</span>
                                    <?php        } ?>Transport
                                    Charges
                                </label>
                                <div class="input-group">
                                    <input type="text" name="transport_charges" id="transport_charges" <?= $required; ?>
                                        value="{{ $row->transport_charges }}" class="form-control charges transport_charges"
                                        readonly>
                                    <button type="button" class="btn btn-outline-secondary packingtax"
                                        data-value="Transport Charges" data-at="2" title="Add Tax">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <?php    } ?>

                            <?php    if ($val->column_name == 'insurance_charges' && $val->active == 1) {
            $i++; ?>
                            <div class="col-lg-4 col-md-6">
                                <label for="insurance_charges" class="form-label">
                                    <?php        if ($required) { ?><span class="text-danger">*</span>
                                    <?php        } ?>Insurance
                                    Charges
                                </label>
                                <div class="input-group">
                                    <input type="text" name="insurance_charges" id="insurance_charges" <?= $required; ?>
                                        value="{{ $row->insurance_charges }}" class="form-control charges insurance_charges"
                                        readonly>
                                    <button type="button" class="btn btn-outline-secondary packingtax"
                                        data-value="Insurance Charges" data-at="3" title="Add Tax">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <?php    } ?>

                            <?php    if ($val->column_name == 'other_tax_amount' && $val->active == 1) {
            $i++; ?>
                            <div class="col-lg-4 col-md-6">
                                <label for="other_tax_amount" class="form-label">
                                    <?php        if ($required) { ?><span class="text-danger">*</span>
                                    <?php        } ?>Other Tax
                                    Amount
                                </label>
                                <div class="input-group">
                                    <input type="text" name="other_tax_amount" id="other_tax_amount"
                                        class="form-control charges other_tax_amount" value="{{ $row->other_tax_amount }}"
                                        readonly>
                                    <button type="button" class="btn btn-outline-secondary packingtax"
                                        data-value="Other Tax Amount" data-at="4" title="Add Tax">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                                <input type="hidden" name="other_tax_amount_tax" id="other_tax_amount_tax"
                                    value="{{ $row->other_tax_amount_tax }}" class="other_tax_amount_tax">
                            </div>
                            <?php    } ?>

                            <?php    if ($val->column_name == 'other_freight_amount' && $val->active == 1) {
            $i++; ?>
                            <div class="col-lg-4 col-md-6">
                                <label for="other_frieght_amount" class="form-label">
                                    <?php        if ($required) { ?><span class="text-danger">*</span>
                                    <?php        } ?>Other
                                    Freight Amount
                                </label>
                                <div class="input-group">
                                    <input type="text" name="other_frieght_amount" id="other_frieght_amount"
                                        class="form-control charges other_frieght_amount"
                                        value="{{ $row->other_frieght_amount }}" readonly>
                                    <button type="button" class="btn btn-outline-secondary packingtax"
                                        data-value="Other Freight Amount" data-at="5" title="Add Tax">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                                <input type="hidden" name="other_frieght_amount_tax" id="other_frieght_amount_tax"
                                    value="{{ $row->other_frieght_amount_tax }}" class="other_frieght_amount_tax">
                            </div>
                            <?php    } ?>

                            <?php    if ($val->column_name == 'unloading_charges' && $val->active == 1) {
            $i++; ?>
                            <div class="col-lg-4 col-md-6">
                                <label for="unloading_charges" class="form-label">
                                    <?php        if ($required) { ?><span class="text-danger">*</span>
                                    <?php        } ?>Unloading
                                    Charges
                                </label>
                                <div class="input-group">
                                    <input type="text" name="unloading_charges" id="unloading_charges" <?= $required; ?>
                                        value="{{ $row->unloading_charges }}" class="form-control charges unloading_charges"
                                        readonly>
                                    <button type="button" class="btn btn-outline-secondary packingtax"
                                        data-value="Unloading Charges" data-at="6" title="Add Tax">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                                <input type="hidden" name="unloading_charges_tax" id="unloading_charges_tax"
                                    value="{{ $row->unloading_charges_tax }}" class="unloading_charges_tax">
                            </div>
                            <?php    } ?>

                            <?php    if ($val->column_name == 'bill_to_address_id' && $val->active == 1) {
            $i++; ?>
                            <div class="col-lg-4 col-md-6">
                                <label for="bill_to_address" class="form-label">
                                    <?php        if ($required) { ?><span class="text-danger">*</span> <?php        } ?>Bill
                                    to
                                    Address
                                </label>
                                <textarea id="bill_to_address" rows="3" tabindex="12" class="form-control bill_to_address"
                                    readonly>{{$bill_to_address}}</textarea>
                            </div>
                            <?php    } ?>

                            <?php    if ($val->column_name == 'ship_to_address_id' && $val->active == 1) {
            $i++; ?>
                            <div class="col-lg-4 col-md-6">
                                <label for="ship_to_address" class="form-label">
                                    <?php        if ($required) { ?><span class="text-danger">*</span> <?php        } ?>Ship
                                    to
                                    Address
                                </label>
                                <textarea id="ship_to_address" rows="3" tabindex="14" class="form-control ship_to_address"
                                    readonly>{{$ship_to_address}}</textarea>
                            </div>
                            <?php    } ?>

                            <?php    if ($val->column_name == 'ship_to_location_id' && $val->active == 1) {
            $i++; ?>
                            <div class="col-lg-4 col-md-6">
                                <label for="ship_to_location_id" class="form-label">
                                    <?php        if ($required) { ?><span class="text-danger">*</span> <?php        } ?>Ship
                                    To
                                    Location
                                </label>
                                <div class="d-flex align-items-center gap-2">
                                    <select name="ship_to_location_id" id="ship_to_location_id" data-location="2" rows="5"
                                        tabindex="13" class="form-select ship_to_location_id select2"
                                        data-live-search="true">{!! $ship_to_location_id !!}</select>
                                </div>
                            </div>
                            <?php    } ?>

                            <?php    if ($val->column_name == 'bill_to_location_id' && $val->active == 1) {
            $i++; ?>
                            <div class="col-lg-4 col-md-6">
                                <label for="bill_to_location_id" class="form-label">
                                    <?php        if ($required) { ?><span class="text-danger">*</span> <?php        } ?>Bill
                                    To
                                    Location
                                </label>
                                <div class="d-flex align-items-center gap-2">
                                    <select name="bill_to_location_id" id="bill_to_location_id" data-location="1" rows="5"
                                        tabindex="11" class="form-select bill_to_location_id select2"
                                        data-live-search="true">{!! $bill_to_location_id !!}</select>
                                </div>
                            </div>
                            <?php    } ?>

                            <?php    if ($val->column_name == 'payment_term_id' && $val->active == 1) {
            $i++; ?>
                            <div class="col-lg-4 col-md-6">
                                <label for="payment_term_id" class="form-label">
                                    <?php        if ($required) { ?><span class="text-danger">*</span>
                                    <?php        } ?>Payment Term
                                </label>
                                <div class="d-flex align-items-center gap-2">
                                    <select name="payment_term_id" id="payment_term_id" rows="5" tabindex="5"
                                        class="form-select payment_term_id select2" data-live-search="true">{!!
                $payment_term_id !!}</select>
                                </div>
                            </div>
                            <?php    } ?>

                            <?php    if ($val->column_name == 'insurance_term_id' && $val->active == 1) {
            $i++; ?>
                            <div class="col-lg-4 col-md-6">
                                <label for="insurance_term_id" class="form-label">
                                    <?php        if ($required) { ?><span class="text-danger">*</span>
                                    <?php        } ?>Insurance
                                    Term
                                </label>
                                <div class="d-flex align-items-center gap-2">
                                    <select name="insurance_term_id" id="insurance_term_id" rows="5" tabindex="10"
                                        class="form-select insurance_term_id select2" data-live-search="true">{!!
                $insurance_term_id !!}</select>
                                </div>
                            </div>
                            <?php    } ?>

                            <?php    if ($val->column_name == 'default_payment_method_id' && $val->active == 1) {
            $i++; ?>
                            <div class="col-lg-4 col-md-6">
                                <label for="default_payment_method_id" class="form-label">
                                    <?php        if ($required) { ?><span class="text-danger">*</span>
                                    <?php        } ?>Payment
                                    Method
                                </label>
                                <div class="d-flex align-items-center gap-2">
                                    <select name="default_payment_method_id" id="default_payment_method_id" rows="5"
                                        tabindex="6" class="form-select default_payment_method_id select2"
                                        data-live-search="true">{!! $default_payment_method_id !!}</select>

                                </div>
                            </div>
                            <?php    } ?>

                            <?php    if ($val->column_name == 'delivery_terms_id' && $val->active == 1) {
            $i++; ?>
                            <div class="col-lg-4 col-md-6">
                                <label for="delivery_terms_id" class="form-label">
                                    <?php        if ($required) { ?><span class="text-danger">*</span>
                                    <?php        } ?>Delivery
                                    Term
                                </label>
                                <div class="d-flex align-items-center gap-2">
                                    <select name="delivery_terms_id" id="delivery_terms_id" rows="5" tabindex="9"
                                        class="form-select delivery_terms_id select2" data-live-search="true">{!!
                $delivery_terms_id !!}</select>

                                </div>
                            </div>
                            <?php    } ?>

                            <?php    if ($val->column_name == 'remarks' && $val->active == 1) {
            $i++; ?>
                            <div class="col-lg-4 col-md-6 remarks_cfg">
                                <label for="remarks" class="form-label">
                                    <?php        if ($required) { ?><span class="text-danger">*</span>
                                    <?php        } ?>Remarks
                                </label>
                                <input type="text" name="remarks" id="remarks" tabindex="16" <?= $required; ?>
                                    value="{{ $row->remarks }}" class="form-control remarks">
                            </div>
                            <?php    } ?>

                            <?php } // foreach ?>
                        </div>
                    </div>
                </div>




                <!-------------------------Linedata -------------------------------->
                <div class="row mt-2">
                    <div class="col-12 linetable">

                        <div id="preview-area" class="table-responsive">
                            <table class="table table-bordered clone_table" style="width: 200%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Line No</th>
                                        <th class="pdtdiv freeze">Product</th>
                                        <th class="hidepart">Supplier part no</th>
                                        <th class="pdtdes_div">Product Description</th>
                                        <th>Uom Code</th>
                                        <th>Qty</th>
                                        <th> Price </th>
                                        <th> Previous Cost </th>
                                        <th> Discount(%) </th>
                                        <th> Discount Amount </th>
                                        <?php if ($row->quotation_type == "STANDARD") { ?>
                                        <th> Hsn Code </th>
                                        <?php } else { ?>
                                        <th> SAC Code </th>
                                        <?php } ?>
                                        <th> Tax Group </th>
                                        <th> Tax Amount </th>
                                        <th> Line Total </th>
                                        <th> Promised Date </th>
                                        <th> Comments </th>
                                        <th style="width: 60px;"></th>
                                    </tr>
                                </thead>
                                <tbody class="clone_lines_body">
                                    @if(count($linedata) > 0)
                                        @foreach($linedata as $key => $value)
                                                                <tr class="line-row">
                                                                    <td>
                                                                        <input type="hidden" name="bulk_quotation_line_id[]"
                                                                            class="form-control input-sm bulk_quotation_line_id"
                                                                            value="{{ $value->quotation_line_id }}">

                                                                        <input type="text" name="bulk_line_no[]"
                                                                            class="form-control input-sm bulk_line_no" value="{{ $key + 1 }}"
                                                                            readonly="readonly">
                                                                    </td>
                                                                    <td class="pdtdiv freeze">
                                                                        <select name="bulk_product_id[]"
                                                                            class="select2 bulk_product_id  parsley-validated" required="required">{!!
                                            $value->product_id !!}</select>
                                                                    </td>

                                                                    <td class="manufact_partno_id rdonly hidepart">
                                                                        <select name="bulk_manufacturer_partno_id[]" id="bulk_manufacturer_partno_id"
                                                                            class="select2 bulk_manufacturer_partno_id">{!!$value->part_no!!}</select>
                                                                    </td>
                                                                    <td class="pdtdes_div">
                                                                        <input type="text" name="bulk_product_description[]"
                                                                            class="form-control input-sm bulk_product_description "
                                                                            value="{{ $value->product_description }}">
                                                                    </td>
                                                                    <td class="uomdiv">
                                                                        <select name="bulk_uom_code_id[]" id="bulk_uom_code_id"
                                                                            class="select2 bulk_uom_code_id">{!! $value->uom_code_id !!}</select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="bulk_qty[]" class="form-control input-sm bulk_qty "
                                                                            value="{{ $value->qty }}" required="required">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="bulk_unit_price[]"
                                                                            class="form-control input-sm bulk_unit_price "
                                                                            value="{{ $value->unit_price }}" required="required">
                                                                    </td>
                                                                    <td class="showspan  previouscost" data-value="Previous Cost" data-at="1"> <i
                                                                            class="fa fa-plus"></i>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="bulk_discount_percentage[]"
                                                                            class="form-control input-sm bulk_discount_percentage "
                                                                            value="{{ $value->discount_percentage }}">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="bulk_discount_amount[]"
                                                                            class="form-control input-sm bulk_discount_amount "
                                                                            value="{{ $value->discount_amount }}">
                                                                    </td>
                                                                    <td class="hsn">
                                                                        <select name="bulk_hsn_code[]" id="bulk_hsn_code"
                                                                            class="select2 bulk_hsn_code">{!! $value->hsn_code!!}</select>
                                                                    </td>
                                                                    <td class="">
                                                                        <select name="bulk_tax_group_id[]" id="bulk_tax_group_id"
                                                                            class="select2 bulk_tax_group_id" required="required">{!!
                                            $value->tax_group_id !!}</select>
                                                                    </td>

                                                                    <td>
                                                                        <input type="text" name="bulk_tax_amount[]"
                                                                            class="form-control input-sm bulk_tax_amount "
                                                                            value="{{ $value->tax_amount }}" required="required">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="bulk_line_total[]"
                                                                            class="form-control input-sm bulk_line_total "
                                                                            value="{{ $value->line_total }}" required="required">
                                                                    </td>
                                                                    <td class="promise_date">
                                                                        <input name="bulk_promised_date[]" type="text"
                                                                            class="form-control input-sm bulk_promised_date"
                                                                            value="{{ $value->promised_date }}">

                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="bulk_comments[]"
                                                                            class="form-control input-sm bulk_comments " value="{{ $value->comments }}">
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
                                                                        <input type="hidden" name="bulk_quotation_line_id[]"
                                                                            class="form-control input-sm bulk_quotation_line_id" value="">
                                                                        <input type="text" name="bulk_line_no[]"
                                                                            class="form-control input-sm bulk_line_no" value="1" readonly="readonly">
                                                                    </td>
                                                                    <td class="pdtdiv freeze">
                                                                        <select name="bulk_product_id[]"
                                                                            class="select2 bulk_product_id  parsley-validated" required="required">{!!
                                        $product_id !!}</select>
                                                                    </td>

                                                                    <td class="manufact_partno_id rdonly hidepart">
                                                                        <select name="bulk_manufacturer_partno_id[]" id="bulk_manufacturer_partno_id"
                                                                            class="select2 bulk_manufacturer_partno_id">{!!$part_no!!}</select>
                                                                    </td>
                                                                    <td class="pdtdes_div">
                                                                        <input type="text" name="bulk_product_description[]"
                                                                            class="form-control input-sm bulk_product_description " value="">
                                                                    </td>
                                                                    <td class="uomdiv">
                                                                        <select name="bulk_uom_code_id[]" id="bulk_uom_code_id"
                                                                            class="select2 bulk_uom_code_id">{!! $uom_code_id !!}</select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="bulk_qty[]" class="form-control bulk_qty " value=""
                                                                            required="required">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="bulk_unit_price[]"
                                                                            class="form-control input-sm bulk_unit_price " value="" required="required">
                                                                    </td>
                                                                    <td class="showspan  previouscost" data-value="Previous Cost" data-at="1"> <i
                                                                            class="fa fa-plus"></i>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="bulk_discount_percentage[]"
                                                                            class="form-control input-sm bulk_discount_percentage " value="">
                                                                    </td>

                                                                    <td>
                                                                        <input type="text" name="bulk_discount_amount[]"
                                                                            class="form-control input-sm bulk_discount_amount " value="">
                                                                    </td>

                                                                    <td class="hsn">
                                                                        <select name="bulk_hsn_code[]" id="bulk_hsn_code"
                                                                            class="select2 bulk_hsn_code">{!! $hsn_code !!}</select>
                                                                    </td>

                                                                    <td class="">
                                                                        <select name="bulk_tax_group_id[]" id="bulk_tax_group_id"
                                                                            class="select2 bulk_tax_group_id" required="required">{!! $tax_group_id !!}
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="bulk_tax_amount[]"
                                                                            class="form-control input-sm bulk_tax_amount" value="" required="required">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="bulk_line_total[]"
                                                                            class="form-control input-sm bulk_line_total" value="" required="required">
                                                                    </td>
                                                                    <td class="promise_date">
                                                                        <input name="bulk_promised_date[]" type="text"
                                                                            class="form-control input-sm bulk_promised_date" value="">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="bulk_comments[]"
                                                                            class="form-control input-sm bulk_comments" value="">
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
                        </div>

                    </div>
                </div>

                <input type="hidden" value="true">
                <input type="hidden" class="form-control input-sm bulk_hidden_date datepicker" value="">
                <!-------------------------Linedata End-------------------------------->
                <div class="row mt-4 mb-3">
                    <div class="col-lg-12 col-md-12">
                        <input type="hidden" class="submit_type" value="" />
                        <div class="form-group text-center actionbtn">
                            <?php if ($return_url == 'purchasequotation') { ?>
                            <button name="apply" type="button" class="btn btn-secondary saveform applychangesd px-4 me-2"
                                value="APPLYCHANGES">Draft</button>
                            <button name="submit" type="button" class="btn btn-success saveform px-4 me-2"
                                value="SAVE">Save</button>

                            <?php } else if ($return_url == 'purchasecopyquotation') { ?>
                            <button name="apply" type="button" class="btn btn-secondary saveform applychangesd px-4 me-2"
                                value="APPLYCHANGES">Draft</button>
                            <button name="submit" type="button" class="btn btn-success saveform px-4 me-2"
                                value="SAVE">Save</button>

                            <?php    } else if ($return_url == 'purchasequtoetionapprove') { ?>
                            <button type="button" class="btn btn-success saveform px-4 me-2" value="APPROVE"><i
                                    class="bi bi-check2-circle"></i> Approve</button>
                            <button type="button" class="btn btn-danger saveform px-4 me-2" value="REJECT"><i
                                    class="bi bi-x-lg"></i> Reject</button>
                            <?php    } else { ?>
                            <button name="apply" type="button" class="btn btn-secondary saveform applychangesd px-4 me-2"
                                value="APPLYCHANGES">Draft</button>
                            <button name="submit" type="button" class="btn btn-success saveform px-4 me-2"
                                value="SAVE">Save</button>

                            <?php    } ?>

                            <a class='btn btn-outline-danger' href="{{ $pageModule }}">Cancel</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <input type="hidden" class="pdtindex" value="" />
    </form>



    <!--popup-->

    <!-- Supplier Modal -->
    <div class="modal fade" id="supplierModal" tabindex="-1" aria-labelledby="supplierModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content shadow-lg border-0">

                <!-- Modal Header -->
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="supplierModalLabel">
                        <i class="bi bi-truck"></i> Supplier Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="table-responsive">
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
                                    <th>City</th>
                                    <th>Action</th>
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


    <!-- Tax Modal -->
    <div class="modal fade" id="taxModal" tabindex="-1" aria-labelledby="taxModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content shadow-lg border-0">

                <!-- Modal Header -->
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="taxModalLabel"></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="taxdetail popheader"></div>
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

    <!-- Product Modal -->
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

@endsection
@push('scripts')

    <script>

        // Init select2 on page load
        $(function () {
            $('.clone_lines_body').find('select.select2').select2({ width: '100%' });
        });

        // Add Row
        // Function to update line numbers
        function updateLineNumbers() {
            $('.clone_lines_body tr').each(function (index) {
                $(this).find('.bulk_line_no').val(index + 1);
            });
        }

        // Function to initialize select2
        function initSelect2($row) {
            $row.find('select.select2').each(function () {
                $(this).select2({
                    width: '100%' // adjust as needed
                });
            });
        }

                // Add row
            $(document).on('click', '.add-row', function () {
            const $tbody = $('.clone_lines_body');
            const $lastRow = $tbody.find('tr:last');
            const $newRow = $lastRow.clone(false);
            // 🔹 Get promised date from last row
            const promisedDate = $lastRow.find('.bulk_promised_date').val();

            const $productSelect = $newRow.find('.bulk_product_id');
            const $productid = `{!! $product_id !!}`;
            if ($productSelect.hasClass('select2-hidden-accessible')) {
                $productSelect.select2('destroy');
            }

                $productSelect.html($productid).val('');

            // Clear inputs except hidden IDs
            $newRow.find('input').not('.bulk_quotation_line_id').val('');

            // 🔹 Set promised date to new row
            $newRow.find('.bulk_promised_date').val(promisedDate);

            // Destroy old select2
            $newRow.find('select.select2').each(function () {
                if ($(this).data('select2')) {
                    $(this).select2('destroy');
                }
                $(this).val('');
            });

            // Remove duplicated select2 containers
            $newRow.find('span.select2').remove();

            // Fix duplicate IDs
            $newRow.find('[id]').each(function () {
                this.id = this.id + '_' + Date.now();
            });

            // Append row
            $tbody.append($newRow);

            // Re-init select2
            initSelect2($newRow);

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




        function example() {
            $("#file_choosen").css({
                "border-color": "rgb(20, 46, 120)",
                "border-width": "1px",
            });
        }


        $(document).ready(function () {
            /*** Purpose For:Up/down/left/right arrow navigation start*******/
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

            /* Purpose For Price not Assign POPup From Enquiry Products*/
            <?php if (isset($quotecount) && $quotecount != 0) { ?>
            setTimeout(function () {
                swal({
                    title: "Price not assigned for {{$quotecount}} Products",
                    text: "You want to add price for this product",
                    type: "warning",
                    showCancelButton: !0,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    closeOnCancel: !1
                }, function (e) {
                    if (e == true) {
                        var id = $(".quote_pricelist_id").val();
                        var url = "{{ URL::to('purchasepricelistedit') }}/" + id;
                        console.log(url);
                        window.open(url);

                    }
                    else {

                        swal("Cancelled");
                    }
                });
            }, 500);
            <?php
    }
                        ?>

            $(document).on('keypress', '.bulk_qty,.bulk_unit_price,.bulk_discount_percentage,.transport_charges,.unloading_charges,.insurance_charges,.packing_charges,.extracharge1,.extracharge3,.extracharge6,.extracharge2,.extracharge5,.extracharge4', function (ev) {
                var regex = new RegExp("^[0-9.]+$");
                var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                if (regex.test(str)) {
                    return true;
                }
                ev.preventDefault();
                return false;
            });

            /*copy past validation*/
            $('.bulk_qty,.bulk_discount_percentage').bind("cut copy paste", function (e) {
                e.preventDefault();
            });
            /*copy past validation*/

            /*discount percentage*/
            $('.bulk_discount_percentage').keyup(function () {
                if ($(this).val() > 100) {
                    notyMsg("info", "Discount(%) Should not exist more than 100");
                    $(this).val('');

                }
            });

            /*Purpose For Readonly*/
            $(".pricelist_div").css('pointer-events', 'none');
            $('.manufact_partno_id').css('pointer-events', 'none');
            <?php if ($return_url == 'purchasequtoetionapprove') { ?>
            $('.grpdiv').css('pointer-events', 'none');
            $('.delete_user').css('pointer-events', 'none');
            <?php } else { ?>
            $('.alink').attr('href', '#');
            $('.grpdiv,.uomdiv').css('pointer-events', 'none');
            $('.delete_user').css('pointer-events', '');

            <?php } ?>

            <?php if ($return_url == 'purchaserequisitionapprove' || $return_url == 'purchasequtoetionapprove') { ?>
            $('input').attr("readonly", true);
            $('select').attr("readonly", true);
            $('.freight_div,.supplier_div,.pricelist_div,.pdtdiv,.taxdiv,.pro_date,.uomdiv,.hsn,.promise_date').css('pointer-events', 'none');
            $('.deldate_div,.supp_div,.pro_div,.suppqd_div,.dterm_div,.billloc_div,.paytm_div,.sploc_div,.project_div,.readonly_div').css('pointer-events', 'none');
            $('#extracharge1').attr("readonly", 'readonly');
            $(".remarks").attr("readonly", false);
            $('.ichide').hide();
            $('.add_row,.remove').hide();
            $('.productsearch').hide();
            $(".bulk_comments").attr("readonly", false);
            <?php } ?>

            $('.organization_id,.source,.created_by,.quotation_type,.quote_status').attr('readonly', 'readonly').css('pointer-events', 'none');
            $('.bulk_tax_amount,.bulk_line_total').attr('readonly', true);

            /*End Purpose For Readonly*/

            /* Purpose For Default Organization & User*/
            var organization = '<?php echo Session::get('organization'); ?>';
            $('.organization_id').val(organization).change();
            var user = '<?php echo session::get('id'); ?>';
            $('.created_by').val(user).change();
            $(".create_by").html($('.created_by option:selected').text());
            $('.org').html($('.organization_id option:selected').text());

            /* purpose for hide product in labour condition*/
            <?php if ($row->quotation_type == "STANDARD") { ?>
            $('.pdtdes_div').hide();
            <?php } else { ?>
            $('.bulk_product_description,.bulk_hsn_code').attr('required', true);
            $('.productsearch').hide();
            $('.bulk_product_id').removeAttr('required');
            <?php } ?>
            <?php if ($row->quotation_type == "LABOUR") { ?>
            <?php    if ($return_url != 'purchasequtoetionapprove') { ?>
            $(".bulk_unit_price").attr("readonly", false);
            <?php    } ?>
            $(".quote_pricelist_id").removeAttr('required');
            $('.hidepart,.reqstar').hide();
            <?php } else { ?>
            $('.grpdiv,.uomdiv').css('pointer-events', 'none');
            <?php } ?>


            /* Purpose For Supplier Based Details load*/
            $(document).on('change', '.supplier_id', function () {
                var supplier_id = $('.supplier_id option:selected').val();

                if (supplier_id !== '') {
                    $.get("{{ URL::to('supplierpricelist') }}/" + supplier_id, function (suppdata) {
                        console.log(suppdata);

                        // Ensure it's parsed JSON if the backend returns a JSON string
                        if (typeof suppdata === 'string') {
                            try {
                                suppdata = JSON.parse(suppdata);
                            } catch (e) {
                                console.error("Invalid JSON format from supplierpricelist:", suppdata);
                                return;
                            }
                        }

                        if ($.trim(suppdata) != 0) {
                            // Fetch Supplier Sites
                            $.ajax({
                                url: "{{ URL::to('jcomboform') }}",
                                type: "GET",
                                data: {
                                    table: "m_supplier_sites_t:supplier_site_id:supplier_site_number|supplier_site_name",
                                    order_by: "supplier_site_name asc",
                                    parent: "supplier_id=" + supplier_id
                                },
                                success: function (data) {
                                    var $dropdown = $(".supplier_site_id");
                                    $dropdown.empty().append('<option value="">-- Select Supplier Site --</option>');

                                    // Parse JSON if necessary
                                    if (typeof data === 'string') {
                                        try {
                                            data = JSON.parse(data);
                                        } catch (e) {
                                            console.error("Invalid JSON from jcomboform:", data);
                                            return;
                                        }
                                    }

                                    $.each(data, function (i, item) {
                                        let selected = (item.val == suppdata['supplier_site_id']) ? 'selected' : '';
                                        $dropdown.append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
                                    });

                                    $dropdown.trigger('change.select2');
                                },
                                error: function (xhr, status, error) {
                                    console.error("Error loading supplier sites:", error);
                                }
                            });

                            // Populate other fields
                            $(".quote_pricelist_id").val(suppdata['price_list']).trigger('change');
                            $(".payment_term_id").val(suppdata['default_payment_terms_id']).trigger('change');
                            $(".delivery_terms_id").val(suppdata['delivery_terms_id']).trigger('change');
                            $(".default_payment_method_id").val(suppdata['default_payment_method_id']).trigger('change');
                            $(".insurance_term_id").val(suppdata['insurance_term_id']).trigger('change');
                            $(".freight_carrier_id").val(suppdata['frieghtcarriers_id']).trigger('change');

                            if (suppdata['price_list'] == 0) {
                                showCustomAlert('No Pricelist Assigned For this Supplier', 'info');
                            }
                        } else {
                            // Reset fields
                            $(".quote_pricelist_id, .supplier_site_id, .payment_term_id, .delivery_terms_id, .default_payment_method_id")
                                .val('').trigger('change');
                        }
                    });
                }
            });


            /** purpose tax for other charges **/
            $(document).on('click', '.packingtax', function () {
                var type = $(this).attr('data-value');
                var type_id = $(this).attr('data-at');
                $('.popheader').html(type + " Tax Details");
                $('#taxModal').modal('show');


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

            $(document).on('click', '.previouscost', function () {

                var type = $(this).data('value');

                // get current row
                var $row = $(this).closest('tr');

                // get product id from same row
                var id = $row.find('.bulk_product_id').val();

                if (!id) {
                    alert('Please select product first');
                    return;
                }

                $('.popheader').html(type);
                $('#taxModal').modal('show');

                var url = "{{ URL::to('getproductpvscost') }}/" + id;

                $.get(url, function (data) {
                    $('.popheader').html(data);
                });
            });


            /**  purpose tax for other charges Save Function **/
            $(document).on('click', '.taxchargesave', function () {
                var type = $(this).val();
                var taxgrp = $('.tax_details' + type + ' option:selected').attr('data-display');
                var taxgrp_v = $('.tax_details' + type + ' option:selected').val();
                var charge = parseFloat($('.extracharge' + type).val());
                taxgrp = taxgrp ? taxgrp : 0;
                var amount = parseFloat((charge) * taxgrp / 100).toFixed("{{\Session::get('decimal')}}");
                if (taxgrp_v == 0) {
                    showCustomAlert("Please select a Tax Group", 'info');
                }
                charge = isNaN(charge) ? '' : charge;
                if (charge == '') {
                    $('#taxModal').modal('show');
                    showCustomAlert("Please fill a Amount", 'info');
                }

                if (taxgrp_v != 0 && charge != '') {
                    var a_c = (parseFloat(amount) + parseFloat(charge)).toFixed("{{\Session::get('decimal')}}");;
                    var tax_group_value = taxgrp_v + "," + charge;
                    if (type == "1") {
                        $('.packing_charges').val(a_c);
                        $('.packing_charges_tax').val(tax_group_value);
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
                        $('.other_tax_amount_tax').val(a_c);
                    }
                    if (type == "5") {
                        $('.other_frieght_amount').val(a_c);
                        $('.other_frieght_amount_tax').val(tax_group_value);
                    }
                    if (type == "6") {
                        $('.unloading_charges').val(a_c);
                        $('.unloading_charges_tax').val(tax_group_value);
                    }
                    $('#taxModal').modal('hide');
                }
                else {
                    if (type == "1") {
                        $('.packing_charges').val(0);
                        $('.packing_charges_tax').val('');
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
                    if (type == "6") {
                        $('.unloading_charges').val(0);
                        $('.unloading_charges_tax').val('');
                    }
                }
            recalc_totals();
            });
            /** end **/
            $(".approve").on('click', function () {
                $("#quote_status").val("APPROVED").change();
            });

            $(".reject").on('click', function () {
                $("#quote_status").val("REJECTED").change();
            });




            // ---- config / helpers ----
            const DECIMAL_PLACES = Number('{{ session("decimal", 2) }}'); // set once
            const num = v => {
                const n = parseFloat((v ?? '').toString().replace(/,/g, ''));
                return isNaN(n) ? 0 : n;
            };
            const showCustomAlerts = (msg, type) => { if (typeof showCustomAlert === 'function') showCustomAlert(msg, type); };

            // ---- Product change -> fetch product details ----
            $(document).on('change', '.bulk_product_id', function (event) {
                const $row = $(this).closest('tr');
                const product_id = $(this).val();                 // Select2 v4
                const type = $('.quotation_type').val();
                const plid = '0';
                const supplierid = $('.supplier_id').val();
                const suppsiteid = $('.supplier_site_id').val();

                $row.find('.hsn').css('pointer-events', 'auto');

                if (!product_id) return;

                if (!supplierid) {
                    showCustomAlerts('Please Select a Supplier', 'info');
                    $(this).val(null).trigger('change');
                    event.preventDefault();
                    return;
                }

                // duplicate check (if you keep your function)
                const rowIndex = $row.index();
                if (typeof pdtcheck === 'function' && pdtcheck(product_id, rowIndex) > 0) {
                    showCustomAlerts('Product Already Selected', 'info');
                    if (typeof rowdataEmpty === 'function') rowdataEmpty($row);
                    $(this).val(null).trigger('change');
                    event.preventDefault();
                    return;
                }

                const url = "{{ url('productdetails') }}/"
                    + encodeURIComponent(product_id) + "/"
                    + encodeURIComponent(plid) + "/"
                    + encodeURIComponent(suppsiteid) + "/"
                    + encodeURIComponent(type)
                    + "?supplier_id=" + encodeURIComponent(supplierid)
                    + "&source=SUPPLIER";

                $.getJSON(url).done(data => {
                    // Manufacturer part no
                    $row.find('.bulk_manufacturer_partno_id')
                        .val(data.part_no ?? null)
                        .trigger('change');

                    // UOM
                    $row.find('.bulk_uom_code_id')
                        .val(data.uom_code_id ?? null)
                        .trigger('change');

                    // HSN/SAC population via jCombo (only this row)
                    const hsnIds = data.multihsn;
                    if (hsnIds) {
                        const classification = (type === 'STANDARD') ? 'HSN' : 'SAC';
                        const condition = `and classification_name='${classification}' and gst_code_hdr_id in(${hsnIds})`;
                        const selectedHsn = (data.hsn_code ?? '').toString();

                        const url2 = "{{ url('jcomboform1') }}"
                            + "?table=f_gst_code_hdr_t:gst_code_hdr_id:classification_code"
                            + "&order_by=classification_code asc"
                            + "&parent=" + encodeURIComponent(condition);

                        $.ajax({
                            url: url2,
                            type: 'GET',
                            success: function (resp) {
                                if (typeof resp === 'string') {
                                    try { resp = JSON.parse(resp); } catch (e) { console.error('Invalid JSON:', resp); return; }
                                }
                                const $sel = $row.find('.bulk_hsn_code');
                                $sel.empty().append('<option value="">-- Select HSN Code --</option>');
                                $.each(resp, function (i, item) {
                                    const val = (item.val ?? '').toString();
                                    const text = item.option_name ?? '';
                                    $sel.append(`<option value="${val}">${text}</option>`);
                                });
                                if (!$sel.hasClass('select2-hidden-accessible')) $sel.select2();
                                $sel.val(selectedHsn).trigger('change'); // <-- this fires your HSN change handler
                            },
                            error: function (xhr, status, error) {
                                console.error('AJAX Error (HSN list):', error);
                            }
                        });
                    }

                    if (typeof calc_by_row === 'function') {
                        calc_by_row($row);        // optional: row-based calculator (see below)
                    } else if (typeof calc_by_index === 'function') {
                        calc_by_index(rowIndex);  // keep your original if you like
                    }
                }).fail(() => {
                    showCustomAlert('Failed to fetch product details', 'error');
                });
            });

            // ---- HSN/SAC change -> fetch tax group ----
            $(document).on('change', '.bulk_hsn_code', function () {
                console.log('[HSN change] fired');   // should print every time now
                const $row = $(this).closest('tr');
                const hsnid = $(this).val();
                const suppsiteid = $('.supplier_site_id').val();
                const mtype = 'PURCHASE';
                if (!hsnid || !suppsiteid) return;

                const url = "{{ url('taxdetails') }}/"
                    + encodeURIComponent(hsnid) + "/"
                    + encodeURIComponent(suppsiteid) + "/"
                    + encodeURIComponent(mtype);

                $.getJSON(url).done(data => {
                    const $taxSel = $row.find('.bulk_tax_group_id');
                    if (Number(data.tax_group_id) === 0) {
                        $taxSel.val(data.tax_group_id).trigger('change');  // programmatic change
                        const reason = $.trim(data.tax_group_id_expiry || '');
                        if (reason === 'expiry') showCustomAlerts('Tax Group expired for this product', 'info');
                        else if (reason === 'location') showCustomAlerts('Tax not assigned for this Location', 'info');
                        else showCustomAlerts('Tax Group not assigned for this product', 'info');
                    } else {
                        $taxSel.val(data.tax_group_id).trigger('change');  // programmatic change
                    }
                }).fail((xhr, s, e) => console.error('Tax fetch failed:', e));
            });


            // ---- Promised date copy (from first row control) ----
            // Give ONLY the "master" date input the class .bulk_promised_date0 in HTML
            $(document).on('change', '.bulk_promised_date0', function () {
                const promised = $(this).val();
                if (!promised) return;

                const hidden = $('.bulk_hidden_date').val();
                $('.bulk_promised_date').each(function () {
                    const $inp = $(this);
                    if ($inp.val() === '' || $inp.val() === hidden) {
                        $inp.val(promised);
                    }
                });
                $('.bulk_hidden_date').val(promised);
            });

            // ---- Clear a row (row-scoped version) ----
            function rowdataEmpty($rowOrIndex) {
                const $row = ($rowOrIndex instanceof jQuery) ? $rowOrIndex
                    : $('.clone_lines_body tr').eq($rowOrIndex);

                $row.find('.bulk_uom_code_id').val(null).trigger('change');
                $row.find('.bulk_unit_price').val('');
                $row.find('.bulk_discount_percentage').val('');
                $row.find('.bulk_discount_amount').val('');
                $row.find('.bulk_hsn_code').val(null).trigger('change');
                $row.find('.bulk_tax_group_id').val(null).trigger('change');
                $row.find('.bulk_tax_amount').val('');
                $row.find('.bulk_line_total').val('');
                $row.find('.bulk_promised_date').val('');
                $row.find('.bulk_comments').val('');
                $row.find('.bulk_manufacturer_partno_id').val(null).trigger('change');
                $row.find('.bulk_qty').trigger('change');
            }

            // ---- Unified calc triggers (row-scoped) ----
            $(document).on(
                'keyup change',
                '.bulk_qty,.bulk_unit_price,.bulk_tax_group_id,.bulk_discount_percentage,.bulk_product_id,.transport_charges,.unloading_charges,.insurance_charges,.packing_charges',
                function () {
                    const $row = $(this).closest('tr');
                    calc_by_row($row);
                    recalc_totals();
                    
                }
            );

            // ---- Discount amount -> back-calc percentage (row-scoped) ----
            $(document).on('keyup change', '.bulk_discount_amount', function () {
                const $row = $(this).closest('tr');

                const unitprice = num($row.find('.bulk_unit_price').val());
                const requiredqty = num($row.find('.bulk_qty').val());
                const taxgrp = num($row.find('.bulk_tax_group_id option:selected').attr('data-display'));
                const discountamt = num($row.find('.bulk_discount_amount').val());

                const base = unitprice * requiredqty;
                const discPerc = base ? ((discountamt * 100) / base) : 0;

                $row.find('.bulk_discount_percentage').val(discPerc.toFixed(DECIMAL_PLACES));

                const subtotal = base - discountamt;
                const taxamount = subtotal * taxgrp / 100;
                $row.find('.bulk_tax_amount').val(taxamount.toFixed(DECIMAL_PLACES));

                const linetot = subtotal + taxamount;
                $row.find('.bulk_line_total').val(linetot.toFixed(DECIMAL_PLACES));

                recalc_totals();
            });

            // ---- Row calculator (replaces calc_by_index) ----
            function calc_by_row($row) {
                const unitprice = num($row.find('.bulk_unit_price').val());
                const requiredqty = num($row.find('.bulk_qty').val());
                const discPerc = num($row.find('.bulk_discount_percentage').val());
                const taxgrp = num($row.find('.bulk_tax_group_id option:selected').attr('data-display'));

                const base = unitprice * requiredqty;
                const disAmt = base * discPerc / 100;
                const subTot = base - disAmt;
                const taxAmt = subTot * taxgrp / 100;
                const lineTot = subTot + taxAmt;

                $row.find('.bulk_discount_amount').val(disAmt.toFixed(DECIMAL_PLACES));
                $row.find('.bulk_tax_amount').val(taxAmt.toFixed(DECIMAL_PLACES));
                $row.find('.bulk_line_total').val(lineTot.toFixed(DECIMAL_PLACES));

                recalc_totals();
            }

            // ---- Header totals (charges + line totals) ----
function recalc_totals() {
  let charges = 0;
  let sum = 0;
  let sumtax = 0;

  // ✅ Include all charge fields (works even without .charges class)
  $('.packing_charges,.transport_charges,.unloading_charges,.insurance_charges,.other_tax_amount,.other_frieght_amount')
    .each(function () {
      charges += num($(this).val());
    });

  // Line totals
  $('.bulk_line_total').each(function () {
    sum += num($(this).val());
  });

  // Tax totals (product line tax)
  $('.bulk_tax_amount').each(function () {
    sumtax += num($(this).val());
  });

  let grand = charges + sum;

console.log('Charges:', charges, 'Line Total Sum:', sum, 'Tax Total:', sumtax, 'Grand Total:', grand);

  let roundOff = (Math.round(grand) - grand);
  roundOff = roundOff.toFixed(DECIMAL_PLACES);

  // Quote totals
  $('#quote_tax_total').val(sumtax.toFixed(DECIMAL_PLACES));
  $('#quote_grand_total').val(grand.toFixed(DECIMAL_PLACES));
  $('.quote_tax_total').val(sumtax.toFixed(DECIMAL_PLACES));
  $('.quote_grand_total').val(grand.toFixed(DECIMAL_PLACES));

  // PO totals
  $('#po_tax_total').val(sumtax.toFixed(DECIMAL_PLACES));
  $('#po_grand_total').val(grand.toFixed(DECIMAL_PLACES));
  $('.po_tax_total').val(sumtax.toFixed(DECIMAL_PLACES));
  $('.po_grand_total').val(grand.toFixed(DECIMAL_PLACES));

  // Display spans
  $('.tax_total_span').html(sumtax.toFixed(DECIMAL_PLACES));
  $('.grand_total_span').html(grand.toFixed(DECIMAL_PLACES));
  $('.round_off_span').html(roundOff);

  $('#round_off').val(roundOff);
}

            // ---- Keep your existing total_amount() but you can route to recalc_totals() ----
            function total_amount() { recalc_totals(); }

            /* Purpose for Supplier Search Modal*/
            $('.suppliersearch').click(function () {
                $('#supplierModal').modal('show');
                $('#supplierModal').width("100%");
            });
            /* Purpose for Load Bill TO,Ship TO Location*/
            $(document).on('change', '.bill_to_location_id,.ship_to_location_id', function () {
                var status = $(this).attr('data-location');
                if (status == 1)
                    var location_id = $('.bill_to_location_id').select2('val');
                else
                    var location_id = $('.ship_to_location_id').select2('val');
                var url = "{{URL::to('getaddress')}}?location_id=" + location_id;
                $.get(url, function (data) {
                    var data = $.trim(data);
                    if (data != '') {

                        if (status == 1)
                            $('.bill_to_address').html(data);
                        else
                            $('.ship_to_address').html(data);
                    }
                    else {

                        if (status == 1)
                            $('.bill_to_address').html('');
                        else
                            $('.ship_to_address').html('');
                    }
                });

            });
            /*End Purpose for Load Bill TO,Ship TO Location*/

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
                        { data: 'location_id', name: 'location_id', visible: false },
                        {
                            data: null,
                            orderable: false,
                            searchable: false,
                            render: function (data) {
                                return `<button class="btn btn-primary btn-sm select-supplier" 
                                  data-id="${data.supplierid}" 
                                  data-address="${data.address}"
                                  data-loc="${data.location_id}">
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
                    var loc = $(this).data('loc');

                    $('.supplier_id').val(supplierId).change();
                    $('.bill_to_address').val(address).change();
                    $('.ship_to_address').val(address).change();
                    $('.bill_to_location_id').val(loc).change();
                    $('.ship_to_location_id').val(loc).change();
                    $('#supplierModal').modal('hide');
                });
            });
            /*End*/
            /*Purpose For File Attachment*/
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
            });


            /*file upload validation*/
            $('#choosefile').change(function () {

                var fp = $("#choosefile");
                var lg = fp[0].files.length; // get length
                var items = fp[0].files;
                var fileSize = 0;

                if (lg > 0) {
                    for (var i = 0; i < lg; i++) {
                        fileSize = fileSize + items[i].size; // get file size
                    }

                    if (fileSize > 10485760) {
                        showCustomAlert('File size must not be more than 10MB', 'error');
                        $('#choosefile').val('');

                    }

                }

            });

            /*file upload validation*/

        $(document).on('click', '.delete_user', function () {

            var existingVal = $('#existing_file').val() || '';
            var deleteVal   = $(this).data('value');

            if (existingVal !== '') {
                var list = existingVal.split(',');
                var index = list.indexOf(deleteVal);

                if (index !== -1) {
                    list.splice(index, 1);
                }

                $('#existing_file').val(list.join(','));
            }

            $(this).closest('tr').remove();
        });

            /* Purpose For Product Search*/
            $(document).on('click', '.productsearch', function () {
                var supplier_id = $('.supplier_id').val();

                if (!supplier_id) {
                    showCustomAlert('Please select a Supplier', 'info');
                    return;
                }

                $('.pdtbtn').parent('div').html('');
                var index = $(this).closest('tr').index();
                $('.pdtindex').val(index);

                $('#productModal').modal('show').css('width', '100%');

                var groupname = 'RAW MATERIALS';
                var gname = 'PACKING MATERIALS';
                var grp = [groupname, gname];
                var pricelist_id = $('.quote_pricelist_id').val();

                if ($.fn.DataTable.isDataTable('#productgrid')) {
                    $('#productgrid').DataTable().destroy();
                }

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

                // Refresh button
                $(".refreshprd").off('click').on('click', function () {
                    var quote_pricelist_id = $('.quote_pricelist_id').val();
                    $(".bulk_product_id").each(function (i) {
                        if (i !== 0) $(this).closest('tr').remove();
                    });

                    if (quote_pricelist_id) {
                        $.get("{{URL::to('getpriceproduct')}}/" + quote_pricelist_id + '/0', function (data) {
                            if ($.trim(data) === '<option value="">-- Please Select --</option>') {
                                showCustomAlert("No Product for these Pricelist", 'info');
                            }
                            $('.bulk_product_id').html(data);
                        });
                    } else {
                        showCustomAlert("Please select a Pricelist", 'info');
                    }
                });

                // Row click to select product
                $('#productgrid tbody').off('click').on('click', 'tr', function () {
                    var data = table.row(this).data();
                    var index = $('.pdtindex').val();
                    var product_id = data.product_id;

                    if (product_id) {
                        var pdtcount = pdtcheck(product_id, index);
                        if (pdtcount <= 0) {
                            $('.bulk_product_id' + index).val(product_id).trigger('change');
                            $('#productModal').modal('hide');
                        } else {
                            var msg = data.concatenated_product;
                            var message = '<span style="color:#fdff65">' + msg + '</span>' + ' Product Already Selected';
                            showCustomAlert(message, 'warning');
                            rowdataEmpty(index);
                            $('#productModal').modal('hide');
                        }
                    } else {
                        showCustomAlert('Please Select a row');
                    }
                });
            });

            /*End*/
            $('.req').hide();

            /* Purpose for Submit Function*/
            $(document).on('click', '.saveform', function () {
                var quotationtype = "{{ $row->quotation_type }}";
                var btnval = $(this).val();
                if (btnval == 'APPLYCHANGES') {
                    $('.remarks').attr('required', false);
                    $("#quote_status").val('DRAFT');
                }
                else if (btnval == 'DRAFT') {
                    $('.remarks').attr('required', false);
                    $("#quote_status").val('DRAFT');
                }
                else if (btnval == "APPROVE") {
                    $('.remarks').attr('required', false);
                    $("#quote_status").val('APPROVED');
                }
                else if (btnval == "REJECT") {
                    $('.req').show();
                    $('.remarks').attr('required', true);
                    $("#quote_status").val('REJECTED');
                }
                else {
                    $('.remarks').attr('required', false);
                    $("#quote_status").val('INITIATED');
                }
                $('#savestatus').val(btnval);

                var url = "{{ url('purchasequotationsave') }}";
                var red_url = "{{ URL::to($return_url) }}";
                if (quotationtype == 'LABOUR') {
                    var create_url = "{{URL::to('purchasequotationcreate') }}/0/LABOUR";
                } else if (quotationtype === 'STANDARD') {
                    var create_url = "{{URL::to('purchasequotationcreate') }}/0/STANDARD";
                }

                qtyrequired();
                var form = $('#poquote_form');
                if (btnval != 'APPLYCHANGES') {
                    form.parsley().validate();
                    var form = $('#poquote_form');
                    form.parsley().validate();
                    if (form.parsley().isValid()) {
                        var $btn = $(this);
                        $btn.prop('disabled', true);
                        var formdata = $('#poquote_form').serialize();
                        var form_data = new FormData(document.getElementById('poquote_form'));
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
                            var auto_no = data.auto_no;
                            var edit_url = "{{ url('purchasequotationcreate') }}/" + id;

                            if (btnval != 'SAVE' && btnval != 'DRAFT' && btnval != 'APPROVE' && btnval != 'REJECT') {
                                showCustomAlert(msg, status);
                                window.location.href = create_url;
                            }
                            else {
                                showCustomAlert(msg, status);
                                window.location.href = red_url;
                            }
                        });
                    }

                } else {

                    var formdata = $('#poquote_form').serialize();
                    var form_data = new FormData(document.getElementById('poquote_form'));
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
                        var edit_url = "{{ URL::to('purchasequotationcreate') }}/" + id;
                        showCustomAlert(msg, status);
                        window.location.href = edit_url;
                    });



                }
            });


            /*Purpose For Qty 0 Submit Validation*/
            function qtyrequired() {
                $(".bulk_qty").each(function (index) {
                    var qty = $(this).val();
                    if (qty == 0) {
                        $('.bulk_qty' + index).val('');
                    }
                });
            }
            //$('.quote_pricelist_id').trigger('change');
        });

        <?php
    $rptmasterdate1 = \DB::table('m_rpt_date_tbl')->select('date')->where('comments', 'For Form')->orderBy('id', 'DESC')->limit(1)->get();
    //dd($rptmasterdate1[0]->date);
    if (COUNT($rptmasterdate1) > 0 && $rptmasterdate1[0]->date != '0000-00-00') {
        $rptmasterdate = date('d-m-Y', strtotime($rptmasterdate1[0]->date));
    } else {
        $rptmasterdate = "0";
    }
                    ?>

        var strmaxdate = "{{$rptmasterdate}}";
        var dateToday = new Date();


        $(document).on("focus", ".supplier_quotation_date", function () {

            $(this).datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: "yy-mm-dd",
                minDate: new Date(2024, 3, 1),
                maxDate: strmaxdate,
                showAnim: "slideDown",
                yearRange: "-25:+0",

            });
        });

        $(document).on("focus", ".bulk_promised_date", function () {

            $(this).datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: "yy-mm-dd",
                minDate: 0,
                maxDate: +60,
                showAnim: "slideDown",
                yearRange: "-25:+0",

            });
        });


    </script>

@endpush