@extends('layouts.header')
@section('content')
<h3 class="text-danger">
    <?php if ($pageMethod == "quickcustomer") { ?>Quick Customers <?php } else if ($pageMethod == "customersapproval") { ?> Customers Approval <?php } else { ?> Customers <?php } ?>
</h3>
@include('layouts.breadcrumb')
<?php error_reporting(0); ?>



<div class="card shadow-lg rounded-4 border-0">
    <div class="card-body card-block">


        <form method="post" action="{{ URL::to('customerssave') }}" id="customerform" data-parsley-validate>
            <input type="hidden" value="{{ $row->savestatus}}" name="savestatus" id="savestatus" />
            <input type="hidden" value="{{ $row->status}}" name="status" id="status" />
            {{ csrf_field()}}


            <div class="row g-4">
                <div class="col-md-4">
                    <!-- Customer Number -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Customer Number</label>
                        <input type="hidden" name="customer_id" id="customer_id" value="{{ $row->customer_id }}">
                        <input type="text" class="form-control customer_number" id="customer_number"
                            name="customer_number" value="{{ $row->customer_number }}" readonly>
                    </div>

                    <!-- Customer Name -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-danger">* Customer Name</label>
                        <input type="text" class="form-control customer_name" id="customer_name" name="customer_name"
                            value="{{ $row->customer_name }}" required>
                    </div>

                    <!-- Alternate Name -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alternate Name</label>
                        <input type="text" class="form-control alternate_name" id="alternate_name" name="alternate_name"
                            value="{{ $row->alternate_name }}">
                    </div>

                    <!-- Customer Type -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-danger">* Customer Type</label>
                        <div class="input-group">
                            <select class="form-select select2 customer_type_id" name="customer_type_id" required>
                                {!! $customer_type_id !!}
                            </select>
                        </div>
                    </div>

                    <!-- Pricelist -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-danger">* Pricelist Name</label>
                        <div class="input-group">
                            <select class="form-select select2 pricelist_id" name="pricelist_id" required>
                                {!! $pricelist_id !!}
                            </select>
                        </div>
                    </div>

                    <!-- Attachment -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Attachment</label>
                        <input type="file" class="form-control attachment" name="attachment" id="attachment">
                    </div>
                </div>

                <!-- Column 2 -->
                <div class="col-md-4">
                    <!-- Payment Term -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-danger">* Default Payment Term</label>
                        <div class="input-group">
                            <select class="form-select select2 default_payment_terms_id" name="default_payment_terms_id"
                                required>
                                {!! $default_payment_terms_id !!}
                            </select>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-danger">* Default Payment Method</label>
                        <div class="input-group">
                            <select class="form-select select2 default_payment_method_id"
                                name="default_payment_method_id" required>
                                {!! $default_payment_method_id !!}
                            </select>
                        </div>
                    </div>

                    <!-- PAN No -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">PAN No</label>
                        <input type="text" class="form-control pan_no" name="pan_no" id="pan_no"
                            value="{{ $row->pan_no }}" maxlength="10">
                        <small class="text-muted">(Format: 1-5 & 10 alphabet, 6-9 numeric)</small>
                    </div>

                    <!-- Created By -->
                    <div class="mb-3 none">
                        <label class="form-label fw-semibold">Created By</label>
                        <select class="form-select select2 created_by" name="created_by" id="created_by">
                            {!! $created_by !!}
                        </select>
                    </div>
                </div>

                <!-- Column 3 -->
                <div class="col-md-4">
                    <!-- Account Code -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-danger">* Account Code</label>
                        <div class="input-group">
                            <select class="form-select select2 account_structure_id" name="account_structure_id"
                                required>
                                {!! $account_structure_id !!}
                            </select>
                        </div>
                    </div>

                    <!-- Freight Term -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Freight Term</label>
                        <div class="input-group">
                            <select class="form-select select2 frieghtterm_id" name="frieghtterm_id">
                                {!! $frieghtterm_id !!}
                            </select>
                        </div>
                    </div>

                    <!-- Salesperson -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-danger">* Salesperson Name</label>
                        <div class="input-group">
                            <select class="form-select select2 sales_person" name="sales_person" required>
                                {!! $sales_person !!}
                            </select>
                        </div>
                    </div>

                    <!-- Delivery Term -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Delivery Term</label>
                        <div class="input-group">
                            <select class="form-select select2 delivery_terms_id" name="delivery_terms_id">
                                {!! $delivery_terms_id !!}
                            </select>
                        </div>
                    </div>

                    <!-- Active -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Active</label>
                        <select class="form-select select2 active" name="active" id="active">
                            <option value="Yes" {{ $row->active == 'Yes' ? 'selected' : '' }}>Yes</option>
                            <option value="No" {{ $row->active == 'No' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <!-- Schemes -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Schemes</label>
                        <div class="input-group">
                            <select class="form-select select2 schemes" name="schemes">
                                {!! $schemes !!}
                            </select>
                        </div>
                    </div>
                </div>
            </div>




            <h4 class="text-primary fw-semi-bold"> Additional Details </h4>

            <div class="row g-4">
                <?php foreach ($enabled_columns as $index => $val) {
                    $required = ($val->action == '1') ? 'required' : '';
                    ?>

                    <!-- Customer Category -->
                    <?php if ($val->column_name == 'customer_category' && $val->active == 1) { ?>
                        <div class="col-md-4">
                            <label class="form-label">
                                Customer Category <?php if ($required) { ?><span class="text-danger">*</span><?php } ?>
                            </label>
                            <select name="customer_category" class="form-select select2 customer_category" <?= $required; ?>>
                                {!! $customer_category !!}
                            </select>
                        </div>
                    <?php } ?>

                    <!-- Reward Opening Point -->
                    <?php if ($val->column_name == 'reward_opening_point' && $val->active == 1) { ?>
                        <div class="col-md-4">
                            <label class="form-label">
                                Reward Opening Point <?php if ($required) { ?><span class="text-danger">*</span><?php } ?>
                            </label>
                            <input type="text" name="reward_opening_point" class="form-control reward_opening_point"
                                value="{{ $row->reward_opening_point }}" <?= $required; ?>>
                        </div>
                    <?php } ?>

                    <!-- Reward Point -->
                    <?php if ($val->column_name == 'reward_point' && $val->active == 1) { ?>
                        <div class="col-md-4">
                            <label class="form-label">
                                Reward Point <?php if ($required) { ?><span class="text-danger">*</span><?php } ?>
                            </label>
                            <input type="text" name="reward_point" class="form-control reward_point"
                                value="{{ $row->reward_point }}" <?= $required; ?>>
                        </div>
                    <?php } ?>

                    <!-- Discounts -->
                    <?php if ($val->column_name == 'ar_discount_hdr_id' && $val->active == 1) { ?>
                        <div class="col-md-4">
                            <label class="form-label">
                                Discounts <?php if ($required) { ?><span class="text-danger">*</span><?php } ?>
                            </label>
                            <div class="input-group">
                                <select name="ar_discount_hdr_id" class="form-select select2 ar_discount_hdr_id" <?= $required; ?>>
                                    {!! $ar_discount_hdr_id !!}
                                </select>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- Maximum Credit -->
                    <?php if ($val->column_name == 'maximum_credit' && $val->active == 1) { ?>
                        <div class="col-md-4">
                            <label class="form-label">
                                Maximum Credit <?php if ($required) { ?><span class="text-danger">*</span><?php } ?>
                            </label>
                            <input type="text" name="maximum_credit" class="form-control maximum_credit"
                                value="{{ $row->maximum_credit }}" <?= $required; ?>>
                        </div>
                    <?php } ?>

                    <!-- Credit Check -->
                    <?php if ($val->column_name == 'credit_check' && $val->active == 1) { ?>
                        <div class="col-md-4">
                            <label class="form-label">
                                Credit Check <?php if ($required) { ?><span class="text-danger">*</span><?php } ?>
                            </label>
                            <select name="credit_check" class="form-select select2 credit_check" <?= $required; ?>>
                                {!! $credit_check !!}
                            </select>
                        </div>
                    <?php } ?>

                    <!-- Freight Carriers -->
                    <?php if ($val->column_name == 'ar_frieghtcarriers_hdr_id' && $val->active == 1) { ?>
                        <div class="col-md-4">
                            <label class="form-label">
                                Freight Carriers Name <?php if ($required) { ?><span class="text-danger">*</span><?php } ?>
                            </label>
                            <div class="input-group">
                                <select name="ar_frieghtcarriers_hdr_id" class="form-select select2 ar_frieghtcarriers_hdr_id"
                                    <?= $required; ?>>
                                    {!! $ar_frieghtcarriers_hdr_id !!}
                                </select>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- Company Additional Info -->
                    <?php if ($val->column_name == 'company_additional_info' && $val->active == 1) { ?>
                        <div class="col-md-4">
                            <label class="form-label">
                                Company Additional Info <?php if ($required) { ?><span class="text-danger">*</span><?php } ?>
                            </label>
                            <input type="text" name="company_additional_info" class="form-control company_additional_info"
                                value="{{ $row->company_additional_info }}" <?= $required; ?>>
                        </div>
                    <?php } ?>

                    <!-- Line of Business -->
                    <?php if ($val->column_name == 'line_of_business' && $val->active == 1) { ?>
                        <div class="col-md-4">
                            <label class="form-label">
                                Line of Business <?php if ($required) { ?><span class="text-danger">*</span><?php } ?>
                            </label>
                            <input type="text" name="line_of_business" class="form-control line_of_business"
                                value="{{ $row->line_of_business }}" <?= $required; ?>>
                        </div>
                    <?php } ?>

                    <!-- Default Bank -->
                    <?php if ($val->column_name == 'default_bank' && $val->active == 1) { ?>
                        <div class="col-md-4">
                            <label class="form-label">
                                Default Bank <?php if ($required) { ?><span class="text-danger">*</span><?php } ?>
                            </label>
                            <div class="input-group">
                                <select name="default_bank" class="form-select select2 default_bank" <?= $required; ?>>
                                    {!! $default_bank !!}
                                </select>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- TDS Applicable -->
                    <?php if ($val->column_name == 'tds_applicable' && $val->active == 1) { ?>
                        <div class="col-md-4">
                            <label class="form-label">
                                TDS Applicable <?php if ($required) { ?><span class="text-danger">*</span><?php } ?>
                            </label>
                            <select name="tds_applicable" class="form-select select2 tds_applicable" <?= $required; ?>>
                                <option value="">--Please Select--</option>
                                <option value="YES" <?= ($row->tds_applicable == 'YES') ? 'selected' : '' ?>>YES</option>
                                <option value="NO" <?= ($row->tds_applicable == 'NO') ? 'selected' : '' ?>>NO</option>
                            </select>
                        </div>

                        <div class="col-md-4 tds_per">
                            <label class="form-label">
                                TDS Percentage (%) <?php if ($required) { ?><span class="text-danger">*</span><?php } ?>
                            </label>
                            <select name="tds_percentage" class="form-select select2 tds_percentage" <?= $required; ?>>
                                {!! $tds_percentage !!}
                            </select>
                        </div>

                        <div class="col-md-4 tds_per">
                            <label class="form-label">
                                TDS Account Code <?php if ($required) { ?><span class="text-danger">*</span><?php } ?>
                            </label>
                            <div class="input-group">
                                <select name="tds_account_id" class="form-select select2 tds_account_id" <?= $required; ?>>
                                    {!! $tds_account_id !!}
                                </select>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- TCS Applicable -->
                    <?php if ($val->column_name == 'tcs_applicable' && $val->active == 1) { ?>
                        <div class="col-md-4">
                            <label class="form-label">
                                TCS Applicable <?php if ($required) { ?><span class="text-danger">*</span><?php } ?>
                            </label>
                            <select name="tcs_applicable" class="form-select select2 tcs_applicable" <?= $required; ?>>
                                <option value="">--Please Select--</option>
                                <option value="YES" <?= ($row->tcs_applicable == 'YES') ? 'selected' : '' ?>>YES</option>
                                <option value="NO" <?= ($row->tcs_applicable == 'NO') ? 'selected' : '' ?>>NO</option>
                            </select>
                        </div>

                        <div class="col-md-4 tcs_per">
                            <label class="form-label">
                                TCS Percentage (%) <?php if ($required) { ?><span class="text-danger">*</span><?php } ?>
                            </label>
                            <select name="tcs_percentage" class="form-select select2 tcs_percentage" <?= $required; ?>>
                                {!! $tcs_percentage !!}
                            </select>
                        </div>

                        <div class="col-md-4 tcs_per">
                            <label class="form-label">
                                TCS Account Code <?php if ($required) { ?><span class="text-danger">*</span><?php } ?>
                            </label>
                            <div class="input-group">
                                <select name="tcs_account_id" class="form-select select2 tcs_account_id" <?= $required; ?>>
                                    {!! $tcs_account_id !!}
                                </select>
                            </div>
                        </div>
                    <?php } ?>

                <?php } ?>
            </div>

            <!--***************************** Line data form *******************************-->

            <div class="row mt-4">
                <div class="col-md-12">
                    <h4 class="text-primary fw-semi-bold"> Customer Site Details </h4>

                    <div id="preview-area" class="table-responsive mt-2">
                        <table class="table table-bordered clone_table" style="width: 200% !important;">
                            <thead class="table-light">
                                <tr>
                                    <th>Customer Site Number</th>
                                    <th>Customer Site Name</th>
                                    <th><span class="fa fa-copy"></span></th>
                                    <th>Site Type</th>
                                    <th>Address</th>
                                    <th>Country</th>
                                    <th>State</th>
                                    <th>City</th>
                                    <th>Gst No(Should be 15digits)</th>
                                    <th>Tan No</th>
                                    <th>Pincode</th>
                                    <th>Contact</th>
                                    <th>Primary Address</th>
                                    <th>Active</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody class="clone_lines_body">
                                <?php
                                $pointer = "pointer-events:none;";
                                if (count($linedata) >= 1) { ?>
                                    @foreach($linedata as $key=>$value)

                                    <?php if ($value->copy_site_row_h == "1") {
                                        $checked = "checked";
                                        $dis = "disabled";
                                    } else {
                                        $checked = "";
                                        $dis = "";
                                    } ?>
                                    <tr class="rcopy clone">
                                        <td>
                                            <input type="hidden" name="bulk_customer_site_id[]"
                                                class="form-control bulk_customer_site_id "
                                                value="{{$value->customer_site_id }}" readonly="readonly">

                                            <input type="text" name="bulk_customer_site_number[]"
                                                class="form-control bulk_customer_site_number"
                                                data-count="{{ $value->customer_site_number }}"
                                                value="{{ $value->customer_site_number }}" readonly="readonly">
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_customer_site_name[]" tabindex="29"
                                                class="form-control  bulk_customer_site_name"
                                                value="{{ $value->customer_site_name }}" required>
                                        </td>
                                        <td style="width: 33px;">
                                            <div class="l-checkbox copy_check">
                                                <div class="c-checkbox" style="margin-left: 0;">
                                                    <input class="copy_site_row" name="copy_site_row[]" {{$checked}}
                                                        type="checkbox" {{$dis}} style="width: 25px !important;">
                                                    <span class="check_mark"></span>
                                                </div>
                                            </div>
                                            <input class="copy_site_row_h" name="copy_site_row_h[]" type="hidden"
                                                value="{{$value->copy_site_row_h}}">
                                        </td>
                                        <td class="typepoint">
                                            <select name='bulk_site_type[]' tabindex="30" id="bulk_site_type"
                                                class='form-control bulk_site_type select2' required>
                                                <option value="">Please select</option>
                                                <option value="BILL_TO" <?php if ($value->site_type == 'BILL_TO') {
                                                    echo "selected";
                                                } else {
                                                    echo "";
                                                } ?>>BILL_TO</option>
                                                <option value="SHIP_TO" <?php if ($value->site_type == 'SHIP_TO') {
                                                    echo "selected";
                                                } else {
                                                    echo "";
                                                } ?>>SHIP_TO</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" tabindex="31" name="bulk_address[]" size="60"
                                                class="form-control  bulk_address " value="{{ $value->address }}">
                                        </td>
                                        <td class="countrypoint">
                                            <select name="bulk_country[]" tabindex="32"
                                                class="form-control bulk_country select2" value="{{$value->country}}"
                                                required>{!! $country_id !!}</select>
                                        </td>
                                        <td class="statepoint">
                                            <select name="bulk_state[]" tabindex="33" 
                                                class="form-control bulk_state select2" value="{{$value->state}}">{!!
                                                $state_id !!} </select>
                                        </td>
                                        <td class="citypoint">
                                            <select name="bulk_city[]" tabindex="34" class="form-control bulk_city select2"
                                                value="{{$value->city}}">{!! $city_id !!}</select>
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_gst_no[]" tabindex="35"
                                                class="form-control bulk_gst_no " minlength="15" maxlength="15"
                                                value="{{ $value->gst_no }}" maxlength="15">
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_tan_no[]" class="form-control bulk_tan_no "
                                                tabindex="36" value="{{ $value->tan_no }}">
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_pincode[]" tabindex="37"
                                                class="form-control  bulk_pincode input_comments_width "
                                                value="{{ $value->pincode }}">
                                        </td>
                                        <td>
                                            <a href="#" class="contactdetail" title="Add Contact Details"> <i
                                                    class="fa fa-plus"></i></a>
                                            <input type="hidden" name="bulk_contact_person[]"
                                                class="form-control bulk_contact_person "
                                                value="{{$value->contact_person}}">
                                            <input type="hidden" name="bulk_contact_number[]"
                                                class="form-control bulk_contact_number "
                                                value="{{$value->contact_number}}">
                                            <input type="hidden" name="bulk_contact_mail[]"
                                                class="form-control bulk_contact_mail " value="{{$value->contact_mail}}">
                                        </td>
                                        <td class="addresspoint">
                                            <select name='bulk_primary_address[]' tabindex="38" id="bulk_primary_address"
                                                class='form-control bulk_primary_address select2'>
                                                <option value="">Please select</option>
                                                <option value="YES" <?php if ($value->primary_address == 'YES') {
                                                    echo "selected";
                                                } else {
                                                    echo "";
                                                } ?>>YES</option>
                                                <option value="NO" <?php if ($value->primary_address == 'NO') {
                                                    echo "selected";
                                                } else {
                                                    echo "";
                                                } ?>>NO</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name='bulk_active[]' tabindex="39" rows='5' class='select2 bulk_active'
                                                id="bulk_active">
                                                <option <?php if ($value->active == 'Yes') {
                                                    echo "selected";
                                                } ?> value="Yes">
                                                    Yes</option>
                                                <option <?php if ($value->active == 'No') {
                                                    echo "selected";
                                                } ?> value="No">No
                                                </option>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-danger remove-row">
                                                <i class="fas fa-minus-circle"></i>
                                            </button>
                                             <input type="hidden" name="counter[]" />
                                        </td>
                                    </tr>
                                    @endforeach
                                    <!--Create Mode-->
                                <?php }
                                if (count($linedata) < 1) { ?>
                                    <tr class="rcopy clone">
                                        <td>
                                            <input type="hidden" name="bulk_customer_site_id[]"
                                                class="form-control bulk_customer_site_id " value="" readonly="readonly">

                                            <input type="text" name="bulk_customer_site_number[]"
                                                class="form-control bulk_customer_site_number" value="" readonly="readonly"
                                                value="">
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_customer_site_name[]"
                                                class="form-control  bulk_customer_site_name" value="" required>
                                        </td>
                                        <td>
                                            <div class="l-checkbox copy_check">
                                                <div class="c-checkbox" style="margin-left: 0;">
                                                    <input style="width: 25px !important;" class="copy_site_row"
                                                        name="copy_site_row[]" type="checkbox">
                                                    <span class="check_mark"></span>
                                                </div>
                                            </div>
                                            <input class="copy_site_row_h" name="copy_site_row_h[]" type="hidden" value="0">
                                        </td>
                                        <td>
                                            <select name="bulk_site_type[]" id="site_type"
                                                class="form-control bulk_site_type select2" required>
                                                <option value="">Please select</option>
                                                <option value="BILL_TO" <?php if ($value->site_type == 'BILL_TO') {
                                                    echo "selected";
                                                } else {
                                                    echo "";
                                                } ?>>BILL_TO</option>
                                                <option value="SHIP_TO" <?php if ($value->site_type == 'SHIP_TO') {
                                                    echo "selected";
                                                } else {
                                                    echo "";
                                                } ?>>SHIP_TO</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_address[]" class="form-control  bulk_address " size="60"
                                                value="">
                                        </td>
                                        <td>
                                            <select name="bulk_country[]" class="form-control  bulk_country select2"
                                                value="" required>{!! $country_id !!}</select>
                                        </td>
                                        <td class="statepoint">
                                            <select name="bulk_state[]" class="form-control bulk_state select2 "
                                                value="">{!! $state_id !!}</select>
                                        </td>
                                        <td class="citypoint">
                                            <select name="bulk_city[]" class="form-control bulk_city select2" value="">{!!
                                                $city_id !!}</select>
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_gst_no[]" class="form-control bulk_gst_no "
                                                minlength="15" value="" maxlength="15">
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_tan_no[]" class="form-control bulk_tan_no "
                                                value="{{ $value->tan_no }}">
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_pincode[]"
                                                class="form-control  bulk_pincode input_comments_width " value="">
                                        </td>
                                        <td>
                                            <a href="#" class="contactdetail" title="Add Contact Details"> <i
                                                    class="fa fa-plus"></i></a>
                                            <input type="hidden" name="bulk_contact_person[]"
                                                class="form-control bulk_contact_person " value="" style="display:none">
                                            <input type="hidden" name="bulk_contact_number[]"
                                                class="form-control bulk_contact_number " value="" style="display:none">
                                            <input type="hidden" name="bulk_contact_mail[]"
                                                class="form-control bulk_contact_mail " value="" style="display:none">
                                        </td>
                                        <td>
                                            <select name='bulk_primary_address[]' id="bulk_primary_address"
                                                class='form-control bulk_primary_address select2' required>
                                                <option value="">Please select</option>
                                                <option value="YES" <?php if ($value->primary_address == 'YES') {
                                                    echo "selected";
                                                } else {
                                                    echo "";
                                                } ?>>YES</option>
                                                <option value="NO" <?php if ($value->primary_address == 'NO') {
                                                    echo "selected";
                                                } else {
                                                    echo "";
                                                } ?>>NO</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name='bulk_active[]' rows='5' class='select2 bulk_active'
                                                id="bulk_active">
                                                <option <?php if ($value->active == 'Yes') {
                                                    echo "selected";
                                                } ?> value="Yes">
                                                    Yes</option>
                                                <option <?php if ($value->active == 'No') {
                                                    echo "selected";
                                                } ?> value="No">No
                                                </option>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-danger remove-row">
                                                <i class="fas fa-minus-circle"></i>
                                            </button>
                                             <input type="hidden" name="counter[]" />
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
            <!--******* end  **************************************-->

            <input type="hidden" name="site_count" class="site_count" value="{{ $site_count }}" />

            <div class="row mt-4 mb-3">
                <div class="col-lg-12 col-md-12">
                    <div class="form-group text-center">
                        <?php if ($url == "customers" || $url == "customersedit") { ?>
                            <?php if ($row->savestatus != "SAVE") { ?>
                            <?php } ?>
                            <button type="button" class="btn btn-success px-4 me-2 saveform"
                                value="SAVE">Save</button>
                        <?php } else if ($url == 'customersapprovalcreate') { ?>
                                <button type="button" name="submit" class="btn btn-success px-4 me-2 saveform"
                                    value="APPROVED">Approve</button>
                                <button type="button" name="submit" class="btn btn-danger px-4 me-2 saveform"
                                    value="REJECT">Reject</button>
                             
                        <?php } else { ?>
                                <button type="button" class="btn btn-success px-4 me-2 saveform" data-value="quick"
                                    value="APPLYCHANGES">Save</button>
                        <?php } ?>
                        <a class='btn btn-outline-danger px-4 me-2' href="{{ URL::to($pageMethod) }}">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!--popups-->



<!-- Contact Modal -->
<div class="modal fade" id="contactModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header bg-light bg-gradient">
                <h4 class="modal-title">Contact Details</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                <input type="hidden" class="conindex" value="">
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div id="preview-area" class="table-responsive">
                    <table class="table table-bordered clone_table1">
                        <thead class="table-light">
                            <tr>
                                <th>Contact Name</th>
                                <th>Contact Number</th>
                                <th>Mail Id</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="clone_lines_body1">
                            <tr>
                                <td>
                                    <input type="text" name="contact_name[]" class="contact_name form-control" required>
                                </td>
                                <td>
                                    <input type="text" name="contact_number[]" class="contact_number form-control"
                                        pattern="\d{10}" maxlength="10" required>
                                </td>
                                <td>
                                    <input type="email" name="contact_mail[]" class="contact_mail form-control"
                                        required>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-danger remove-row1">
                                        <i class="fas fa-minus-circle"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="text-end">
                        <button type="button" class="btn btn-success btn-sm add-row1">
                            <i class="fas fa-plus-circle"></i> Add Row
                        </button>
                    </div>
                </div>
                <div class="mt-3 text-center">
                    <button type="button" class="btn btn-primary" id="addbox">Add Contact Details</button>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
@push('scripts')

<script>


/* Address start */
function check_primary_address(index) {
    // All rows in the main table body
    var $rows = $('.clone_lines_body tr');
    var $row  = $rows.eq(index);

    var bpmadd         = $row.find('.bulk_primary_address').val();
    var currentSiteType = $row.find('.bulk_site_type').val();

    // If this row is set as primary
    if (bpmadd === 'YES') {
        // Loop through all rows and unset other primaries with same site type
        $rows.each(function (i) {
            if (i !== index) {
                var $r   = $(this);
                var site = $r.find('.bulk_site_type').val();
                var val  = $r.find('.bulk_primary_address').val();

                if (site === currentSiteType && val === 'YES') {
                    $r.find('.bulk_primary_address').val('NO').trigger('change');
                }
            }
        });
    } else {
        // If user unselected primary, ensure there is still at least one YES anywhere
        var hasPrimary = false;

        $rows.each(function () {
            if ($(this).find('.bulk_primary_address').val() === 'YES') {
                hasPrimary = true;
                return false; // break
            }
        });

        if (!hasPrimary) {
            showCustomAlert(
                "Still There's No Primary Address,Please Select Primary Address...",
                "info"
            );
            // revert change on this row
            $row.find('.bulk_primary_address').val('').trigger('change');
            return false;
        }
    }
}

/* Shiping Bill */
function check_ship_bill() {
    var billPrimaryCount = 0;
    var shipPrimaryCount = 0;

    $('.clone_lines_body tr').each(function () {
        var $row     = $(this);
        var site     = $row.find('.bulk_site_type').val();
        var primary  = $row.find('.bulk_primary_address').val();

        if (primary === 'YES') {
            if (site === 'BILL_TO') {
                billPrimaryCount++;
            } else if (site === 'SHIP_TO') {
                shipPrimaryCount++;
            }
        }
    });

    if (billPrimaryCount <= 0) {
        showCustomAlert(
            "Still There's No Primary Address For BILL_TO ,Please Select Primary Address...",
            "info"
        );
        return false;
    }

    if (shipPrimaryCount <= 0) {
        showCustomAlert(
            "Still There's No Primary Address For SHIP_TO,Please Select Primary Address...",
            "info"
        );
        return false;
    }

    return true;
}


    $(document).ready(function () {

        $('#customer_name').keyup(function () {
            $(this).attr('title', $(this).val())
        })


        $('.spanhide').hide();
        <?php if (isset($_GET['edit'])) { ?>
            $('.customer_name').attr('readonly', true);
        <?PHP } ?>
        $('select[name="bulk_primary_address[]').addClass('bulk_primary_address');
        $('select[name="bulk_primary_address[]').change(function (e) {

            var index = $(this).closest('tr').index();
            check_primary_address(index);

        });
        <?php if ($url == "customersedit") { ?>
            $('.rempoint').css('pointer-events', 'none');

        <?php } ?>
        var siteno = "<?php echo $row->customer_number; ?>";
        //alert(siteno);


        $(".tds_applicable").change(function () {
            var tds = $(".tds_applicable option:selected").val();
            if (tds == "YES") {
                $(".tds_per").show();
            } else {
                $(".tds_per").hide();
            }
        });

        $(".tcs_applicable").change(function () {
            var tds = $(".tcs_applicable option:selected").val();
            if (tds == "YES") {
                $(".tcs_per").show();
            } else {
                $(".tcs_per").hide();
            }
        });
        $(".tds_applicable").trigger('change');
        $(".tcs_applicable").trigger('change');



        $(".statepoint,.citypoint").css('pointer-events', 'none');

        // Quick Customer
        <?php if ($row->status != "QUICKCUSTOMER") {
            if ($row->savestatus != "DRAFT") { ?>
                $('.copy_check,.typepoint,.countrypoint,.statepoint,.citypoint,.rempoint,.bulk_address,.bulk_gst_no,.bulk_pincode').css('pointer-events', 'none');
                $('.bulk_customer_site_name,.bulk_tan_no').attr('readonly', true);
            <?php }
        } ?>
        // Customer 
        <?php if ($url == "customers") { ?>
            $('.bulk_address').attr('readonly', false);
            $('.bulk_customer_site_name,.bulk_tan_no').attr('readonly', false);
            $('.copy_check,.typepoint,.rempoint,.bulk_address,.bulk_gst_no,.bulk_pincode').css('pointer-events', 'auto');
        <?php } ?>

        var totcount = $('.suppliersite_table tbody tr').length;

        function decimal_num(str, max) {
            str = str.toString();
            return str.length < max ? decimal_num("0" + str, max) : str;
        }

        /* purpose for PAn no Validation*/
        $('.pan_no').change(function (event) {

            var regExp = /[a-zA-z]{5}\d{4}[a-zA-Z]{1}/;
            var txtpan = $(this).val();
            if (txtpan.length == 10) {
                if (txtpan.match(regExp)) {

                }
                else {
                    showCustomAlert("Not a valid PAN number", "info");
                    $(".pan_no").val('');
                    event.preventDefault();
                }
            }
            else {
                $(".pan_no").val('');
                showCustomAlert('Please enter 10 digits for a valid PAN number', "warning");
                event.preventDefault();
            }

        });

        /* purpose for PAn no Validation End*/

        /* --Start Pan number and state generate gst number --*/
        $(document).on('change', '.pan_no', function () {
            var pan = $('.pan_no').val();
            $(".bulk_state").each(function (i) {
                var state = $(this).select2('val');
                if (state) {
                    var url = "{{ URL::to('getstatecode') }}/" + state;
                    $.get(url, function (data) {
                        var check = $.trim(data);
                        if (check == '') {
                            showCustomAlert("Please assign state code", "warning");
                            var gst_no = pan;
                        }
                        else {
                            var gst_no = check + '' + pan;
                        }
                        $('.bulk_gst_no' + i).val(gst_no);
                    });
                }
            });
        });

        $('.customer_name').on('keyup', function () {
            this.value = this.value.toUpperCase();
        });

        /* --Start Pan number State Code --*/
        $(document).on('change', '.bulk_state', function () {
            var index = $(this).closest('tr').index();
            var state = $(this).select2('val');
            var pan = $('.pan_no').val();
            if (pan != '') {
                var url = "{{ URL::to('getstatecode') }}/" + state;
                $.get(url, function (data) {
                    var check = $.trim(data);
                    if (check == '') {
                        showCustomAlert("Please assign state code", "warning");
                        var gst_no = pan;
                    }
                    else {
                        var gst_no = check + '' + pan;
                    }

                    $('.bulk_gst_no' + index).val(gst_no);
                });
            }
        });
        /* --End Pan number State Code --*/
        $(document).on('change', '.customer_type_id', function () {
            var customer_type = $('.customer_type_id').select2('val');
            var customer_typetext = $('.customer_type_id option:selected').text();

            if (customer_type != '') {
                if (customer_typetext == "EXPORT") {
                    alert(customer_typetext);
                    $('.bulk_state').removeAttr('required');
                    $('.bulk_city').removeAttr('required');
                } else {
                    $('.bulk_state').attr('required', true);
                    $('.bulk_city').attr('required', true);
                }
                var url = "{{ URL::to('gstrequired') }}/" + customer_type;
                $.get(url, function (data) {
                    var check_data = $.trim(data);

                    if (check_data == "Yes") {
                        $('.bulk_gst_no').attr('required', true);
                        $('.pan_no').attr('required', true);
                        $('.spanhide').show();
                    } else {
                        $('.bulk_gst_no').removeAttr('required');
                        $('.pan_no').removeAttr('required');
                        $('.spanhide').hide();
                    }
                });
            }
        });


        var show_div = '<?php echo $show_div; ?>';

        if (show_div == 1)
            $('#panel_add').trigger('click');

        $('#savestatus').val('');

        /* --Start Save Function --*/

        $(document).on('click', '.saveform', function () {
            var btnval = $(this).val();
            var savestatus = 'INITIATED';

            if (btnval == 'APPLYCHANGES') {
                savestatus = 'DRAFT';
            } else if (btnval == 'APPROVED') {
                savestatus = 'SAVE';
            } else if (btnval == 'REJECT') {
                savestatus = 'REJECTED';
            } else if (btnval == 'SAVE') {
                savestatus = 'INITIATED';
            }

            $('#savestatus').val(savestatus);

            var url = "{{ URL::to('customerssave') }}";
            var red_url = "{{ URL::to('customers') }}";
            var approval_url = "{{ URL::to('customersapproval') }}";
            var create_url = "{{ URL::to('customerscreate') }}/0";

            $(".copy_site_row").attr("disabled", false);

            // Prepare form data with FormData for file upload support
            var formdata = new FormData($('#customerform')[0]);
            formdata.append('savestatus', savestatus);

            if (btnval != "APPLYCHANGES") {
                $('#customerform').parsley().validate();

                if ($('#customerform').parsley().isValid()) {
                    var checks = check_ship_bill();
                    if (checks == true) {

                var $btn = $(this);            
			    $btn.prop('disabled', true);

                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: formdata,
                            processData: false,
                            contentType: false,
                            success: function (data) {
                                var status = data.status;
                                var msg = data.message;
                                var id = data.id;
                                var edit_url = "{{ URL::to('customerscreate') }}/" + id;

                                if (btnval != 'SAVE' && btnval != 'DRAFT' && btnval != 'APPROVED' && btnval != 'REJECT') {
                                    showCustomAlert(msg, status);
                                    window.location.href = create_url;
                                } else if (btnval == 'APPROVED' || btnval == 'REJECT') {
                                    showCustomAlert(msg, status);
                                    window.location.href = approval_url;
                                } else {
                                    showCustomAlert(msg, status);
                                    window.location.href = red_url;
                                }
                            }
                        });
                    }
                }
            } else {
                var quick = $(this).data('value');
                var check = 0;
                if (quick == "quick") {
                    savestatus = 'QUICK CUSTOMER';
                    var customer_name = $('.customer_name').val();
                    if (customer_name == "") {
                        check = 1;
                    }
                }

                if (check == 0) {
                 
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: formdata,
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            var status = data.status;
                            var msg = data.message;
                            var id = data.id;
                            var edit_url = "{{ $pageMethod == 'quickcustomer' ? URL::to('quickcustomer') : URL::to('customersedit') }}/" + id;

                            showCustomAlert(msg, status);
                            window.location.href = edit_url;
                        }
                    });
                } else {
                    showCustomAlert("Customer name is required", "error");
                }
            }
        });

        /* -- Start  number Validation -- */
        $(document).on('keypress', '.contact_number,.bulk_pincode,.maximum_credit,.available_credit,.credit_limit,.reward_opening_point,#before_days,#before_dis,#after_int,.reward_point,#after_days', function (ev) {
            var regex = new RegExp("^[0-9.]+$");
            var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
            if (regex.test(str)) {
                return true;
            }
            ev.preventDefault();
            return false;
        });


        /* -- Contact Detail get data function-- */
      // Open modal & remember which row we're editing
$(document).on('click', '.contactdetail', function (e) {
    e.preventDefault();
    var index = $(this).closest('tr').index(); // row index in tbody
    $('.conindex').val(index);
    $('#contactModal').modal('show');
    $('#contactModal').css('margin', 'auto');
});

// When modal is shown, load existing contacts from that row into modal table
$('#contactModal').on('shown.bs.modal', function () {
    var index = $('.conindex').val();
    var $row  = $('.clone_lines_body tr').eq(index);  // row in main table

    // Get hidden values from that row
    var co_name = $row.find('.bulk_contact_person').val()  || '';
    var co_num  = $row.find('.bulk_contact_number').val()  || '';
    var co_mail = $row.find('.bulk_contact_mail').val()    || '';

    // Reset modal table to a single empty row
    var $tbody = $('.clone_table1 .clone_lines_body1');
    $tbody.find('tr:gt(0)').remove(); // keep first row only
    $tbody.find('input').val('');

    // If there are saved contacts, fill them
    if (co_name.trim() !== '') {
        var names  = co_name.split(',');
        var nums   = co_num.split(',');
        var mails  = co_mail.split(',');

        // If more than one contact, add extra rows
        for (var j = 1; j < names.length; j++) {
            $('.add-row1').trigger('click');
        }

        // Now fill each row
        $('.contact_name').each(function (i) {
            $(this).val(names[i] ? names[i].trim() : '');
        });
        $('.contact_number').each(function (i) {
            $(this).val(nums[i] ? nums[i].trim() : '');
        });
        $('.contact_mail').each(function (i) {
            $(this).val(mails[i] ? mails[i].trim() : '');
        });
    }
});

// Add new contact row in modal
$(document).on('click', '.add-row1', function () {
    var $tbody = $('.clone_table1 .clone_lines_body1');
    var $last  = $tbody.find('tr:last');
    var $new   = $last.clone();

    $new.find('input').val(''); // clear inputs in new row
    $tbody.append($new);
});

// Remove contact row in modal
$(document).on('click', '.remove-row1', function () {
    var $tbody = $('.clone_table1 .clone_lines_body1');
    if ($tbody.find('tr').length > 1) { // keep at least one row
        $(this).closest('tr').remove();
    } else {
        // just clear fields if it's the only row
        $(this).closest('tr').find('input').val('');
    }
});

// Save contacts back to main table when user clicks "Add Contact Details"
$(document).on('click', '#addbox', function () {
    var index = $('.conindex').val();
    var $row  = $('.clone_lines_body tr').eq(index);

    var names  = [];
    var nums   = [];
    var mails  = [];

    // Collect values from modal rows
    $('.clone_table1 .clone_lines_body1 tr').each(function () {
        var name = $(this).find('.contact_name').val().trim();
        var num  = $(this).find('.contact_number').val().trim();
        var mail = $(this).find('.contact_mail').val().trim();

        // Only push if at least name or number or mail is filled
        if (name || num || mail) {
            names.push(name);
            nums.push(num);
            mails.push(mail);
        }
    });

    // Save as comma-separated strings into hidden inputs of that row
    $row.find('.bulk_contact_person').val(names.join(','));
    $row.find('.bulk_contact_number').val(nums.join(','));
    $row.find('.bulk_contact_mail').val(mails.join(','));

    // Optional: change tooltip so user sees something happened
    $row.find('.contactdetail').attr('title', names.length + ' contact(s) added');

    // Close modal
    $('#contactModal').modal('hide');
});

        $(document).on('change', '.discount', function () {
            var discount = $('.discount').select2('val');
            if (discount == "Yes") {
                $('.ar_discount_hdr_id').attr('required', true);
                $('.ar_discount_hdr_iddiv').show();
            }
            else {
                $('.ar_discount_hdr_id').removeAttr('required');
                $('.ar_discount_hdr_iddiv').hide();
            }
        });

		// Handle country change for any (even cloned) row
		$(document).on('change', '.bulk_country', function () {
		  const $row = $(this).closest('tr');             // the current row
		  const country_id = $(this).val();               // selected country in this row

		  const $state = $row.find('.bulk_state');        // state select in this row
		  const $city  = $row.find('.bulk_city');         // city select in this row

		  // enable state cell if you need pointer-events
		  $row.find('.statepoint').css('pointer-events', 'auto');

		  const url = "{{ URL::to('jcomboformlogin') }}?table=m_states_t:state_id:state_name"
					+ "&parent=country_id=" + encodeURIComponent(country_id)
					+ "&order_by=state_name";

		  $.ajax({
			url,
			type: 'GET',
			success: function (data) {
			  // normalize to JSON
			  if (typeof data === 'string') {
				try { data = JSON.parse(data); } catch (e) { console.error('Invalid JSON:', data); return; }
			  }

			  // If you want to preserve a preselected value, store it on the element as a data-attr in Blade:
			  // <select class="form-control bulk_state select2" data-selected="{{ $value->state }}">...</select>
			  const selectedState = ($state.data('selected') || '').toString();

			  $state.empty().append('<option value="">-- Please Select --</option>');

			  $.each(data, function (i, item) {
				const val = (item.val ?? '').toString();
				const opt = $('<option>').val(val).text(item.option_name || '');
				if (selectedState && val === selectedState) opt.prop('selected', true);
				$state.append(opt);
			  });

			  // Re-init / refresh select2 on just this element (if you’re using select2)
			  $state.trigger('change.select2');
			},
			error: function (xhr) {
			  console.error('State load failed:', xhr?.status, xhr?.responseText);
			}
		  });

		  // Reset city dropdown for this row
		  $city.find('option').not(':first').remove();
		  $city.trigger('change.select2');
		});

	// When a state's value changes, load that row's cities
$(document).on('change', '.bulk_state', function () {
  const $row   = $(this).closest('tr');
  const stateId = $(this).val();

  const $cityCell = $row.find('.citypoint');
  const $citySel  = $row.find('.bulk_city');

  // enable city cell (if you were disabling it via CSS)
  $cityCell.css('pointer-events', 'auto');

  if (!stateId) {
    // clear city options if no state selected
    $citySel.empty().append('<option value="">-- Please Select --</option>').trigger('change.select2');
    return;
  }

  const url = "{{ URL::to('jcomboformlogin') }}?table=m_cities_t:city_id:city_name"
            + "&parent=state_id=" + encodeURIComponent(stateId)
            + "&order_by=city_name";

  // Optional: show a loading option
  $citySel.html('<option value="">Loading...</option>').trigger('change.select2');

  $.ajax({
    url: url,
    type: 'GET',
    success: function (data) {
      if (typeof data === "string") {
        try { data = JSON.parse(data); } catch (e) { console.error("Invalid JSON response:", data); return; }
      }

      // Prefer a data-selected attribute set server-side:
      // <select class="form-control bulk_city select2" data-selected="{{ $value->city }}">...</select>
      const preselect = ($citySel.data('selected') || '').toString();

      $citySel.empty().append('<option value="">-- Please Select --</option>');

      $.each(data, function (i, item) {
        const val = (item.val ?? '').toString();
        const opt = $('<option>').val(val).text(item.option_name || '');
        if (preselect && val === preselect) opt.prop('selected', true);
        $citySel.append(opt);
      });

      // refresh Select2 for just this select
      $citySel.trigger('change.select2');
    },
    error: function (xhr) {
      console.error('City load failed:', xhr?.status, xhr?.responseText);
      $citySel.empty().append('<option value="">-- Please Select --</option>').trigger('change.select2');
    }
  });
});
	


        <?php if ($row->savestatus == "SAVE") { ?>

            <?php if (isset($_GET['edit'])) { ?>
                var edit = "<?php echo $_GET['edit']; ?>";
            <?PHP } else { ?>
                var edit = ""; <?php } ?>

            if (edit == 'readonly') {

                var index = $(".suppliersite_table tbody tr").closest('tr').index();
                $('.copy_site_row').each(function (i) {
                    var chk = $('.copy_site_row_h' + i).val();
                    $('.copy_check' + i + ',.typepoint' + i + ',.countrypoint' + i + ',.statepoint' + i + ',.citypoint' + i + ',.rempoint' + i + ',.bulk_address' + i + ',.bulk_gst_no' + i + ',.bulk_pincode' + i).css('pointer-events', 'none');
                    $('.bulk_address' + i).attr('readonly', true);
                    $('.bulk_customer_site_name' + i).prop('readonly', true);
                    if (chk == 1) {
                        $('.copy_site_row' + i).attr('checked', 'checked');
                    } else {
                        $('.copy_site_row' + i).attr('checked', false);
                    }

                });
            }
        <?php } ?>


        var save_type = "{{$save_type}}";
        var copy_site = "{{$copy_site}}";
        if (save_type == "apply_changes") {
            var close_ind = $(".copy_site_row").closest('tr').index();
            var copy_id = [];
            var strArr = copy_site.split(',');
            var intArr = [];
            for (i = 0; i < strArr.length; i++) {
                intArr.push(parseInt(strArr[i]));
            }
            var i = 0;
            while (i <= close_ind) {
                var checkarr = $.inArray(i, intArr);
                if (checkarr != "-1") {
                    var cp_i = parseInt(i) + 1;
                    copy_id.push(cp_i);
                    $(".copy_site_row" + i).prop('checked', true);
                    $('.copy_site_row' + i).attr("disabled", true);
                }
                else {
                    $(".copy_site_row" + i).prop('checked', false);
                    $('.copy_site_row' + i).attr("disabled", false);
                }

                i++;
            }
            $.each(copy_id, function (k, value) {
                $(".copy_site_row" + value).prop('checked', false).hide();
            });
        }


var copy_site_id = []; // if you actually need this, otherwise you can remove it

$(document).on('click', '.copy_site_row', function (e) {
    var $sourceRow = $(this).closest('tr');
    var siteType   = $sourceRow.find('.bulk_site_type').val();
    var isChecked  = $(this).prop('checked');

    if (isChecked && siteType !== '') {

        // Add a new row (note: .add-row, not .add_row)
        $('.add-row').trigger('click');

        // Disable the checkbox in the source row & set hidden value
        $(this).prop('disabled', true);
        $sourceRow.find('.copy_site_row_h').val('1');

        // Get values from source row
        var bulk_state           = $sourceRow.find('.bulk_state').val();
        var bulk_country         = $sourceRow.find('.bulk_country').val();
        var bulk_primary_address = $sourceRow.find('.bulk_primary_address').val();
        var bulk_city            = $sourceRow.find('.bulk_city').val();
        var bulk_contact_person  = $sourceRow.find('.bulk_contact_person').val();
        var bulk_contact_number  = $sourceRow.find('.bulk_contact_number').val();
        var bulk_contact_mail    = $sourceRow.find('.bulk_contact_mail').val();
        var bulk_pincode         = $sourceRow.find('.bulk_pincode').val();
        var bulk_address         = $sourceRow.find('.bulk_address').val();
        var bulk_customer_site_name = $sourceRow.find('.bulk_customer_site_name').val();
        var bulk_gst_no          = $sourceRow.find('.bulk_gst_no').val();
        var bulk_active          = $sourceRow.find('.bulk_active').val();

        // Target row = the new last row
        var $targetRow = $('.clone_lines_body').find('tr.rcopy').last();

        // Flip site type BILL_TO ↔ SHIP_TO
        var newSiteType = (siteType === 'BILL_TO') ? 'SHIP_TO' : 'BILL_TO';

        $targetRow.find('.bulk_site_type').val(newSiteType).trigger('change');

        // Copy select fields (assuming select2)
        $targetRow.find('.bulk_country').val(bulk_country).trigger('change');
        $targetRow.find('.bulk_primary_address').val(bulk_primary_address).trigger('change');
        $targetRow.find('.bulk_state').val(bulk_state).trigger('change');
        $targetRow.find('.bulk_city').val(bulk_city).trigger('change');

        // Copy simple inputs
        $targetRow.find('.bulk_pincode').val(bulk_pincode);
        $targetRow.find('.bulk_contact_mail').val(bulk_contact_mail);
        $targetRow.find('.bulk_contact_number').val(bulk_contact_number);
        $targetRow.find('.bulk_contact_person').val(bulk_contact_person);
        $targetRow.find('.bulk_address').val(bulk_address);
        $targetRow.find('.bulk_customer_site_name').val(bulk_customer_site_name);
        $targetRow.find('.bulk_gst_no').val(bulk_gst_no);
        $targetRow.find('.bulk_active').val(bulk_active).trigger('change');

        // Disable copy checkbox in target row too if needed:
        $targetRow.find('.copy_site_row').prop('disabled', true);

    } else if (isChecked && siteType === '') {
        // No site type selected -> show error and uncheck
        showCustomAlert('Please select Site Type', 'info');
        $(this).prop('checked', false);
    }
});




        var form = $("#customerform");
        form.parsley();
        form.submit(function () {
            $('input[name=_token]').val("{{csrf_token()}}");
            var data;
            data = form.serialize();


            var url = "{{ URL::to('customerssave') }}";

            $.post(url, data, function (data1) {
                showCustomAlert(data1['message'], data1['status']);

                window.location.href = "{{ URL::to('customers') }}";

            });

            return false;
        });

        /********************* end ****************************/
    });


    // clone tables - VIGNESH M									 

    // Add Row
$(document).on('click', '.add-row', function () {

    const $lastRow = $('.clone_lines_body tr:last');
    const $newRow = $lastRow.clone(false, false); // clone without events or data

    // Clear all input and select values in the cloned row
    $newRow.find('input').val('').prop('readonly', false); // remove readonly
    $newRow.find('input, select, textarea')
       .prop('readonly', false)
       .prop('disabled', false)
       .css('pointer-events', 'auto');

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

    // Add Row
    $(document).on('click', '.add-row1', function () {
        const $lastRow = $('.clone_lines_body1 tr:last');
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
        $('.clone_lines_body1').append($newRow);

        // Reinitialize select2
        $newRow.find('select.select2').select2({ width: '100%' });

        // Update line numbers
        linesClone();
    });



    // Remove button
    $(document).on('click', '.remove-row1', function () {
        const rowCount = $('.clone_lines_body1 tr').length;
        if (rowCount > 1) {
            $(this).closest('tr').remove();
            linesClone();
        } else {
            showCustomAlert("You Can't Delete Atleast One row should be There", "warning");
        }
    });

    // Renumber Line Nos
    function linesClone() {
        $('.clone_lines_body1 tr').each(function (index) {
            $(this).find('.bulk_line_no').val(index + 1);
        });
    }

</script>



@endpush