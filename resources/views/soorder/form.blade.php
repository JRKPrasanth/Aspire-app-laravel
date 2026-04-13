@extends('layouts.header')
@section('content')
<h3 class="text-danger">

    Sales Order {{ $row['sales_order_no'] }}
    <?php if ($pageMethod == "soorderfromqo") {
        $pageModule = "soorderfromqo"; ?>

    <?php } else if ($pageMethod == "soorderapproved") {
        $pageModule = "salesorderapproval" ?>
        <span class="ui_close_btn"><a class="collapse-close pull-right btn-danger"
                onclick='location.href="{{ URL::to($pageModule) }}"'></a></span>
    <?php } else { ?>
        <span class="ui_close_btn"><a class="collapse-close pull-right btn-danger"
                onclick='location.href="{{ URL::to($pageModule) }}"'></a></span>
    <?php } ?>

</h3>
@include('layouts.breadcrumb')
<?php error_reporting(0); ?>


<div class="card shadow-lg rounded-4 border-0">


    <div class="card shadow-sm rounded-4 border-0 mb-4">
        <div class="card-header bg-light border-bottom fw-bold rounded-top-4">
            <div class="row g-3 align-items-center">

                <!-- Column 1 -->
                <div class="col-md-3">
                    <p class="mb-1 text-muted small">Order Date</p>
                    <h6 class="mb-2 text-primary fw-bold">{{ date(\Session::get('p_date_format'),
                        strtotime($row['sales_order_date'])) }}
                    </h6>

                    <p class="mb-1 text-muted small">Source</p>
                    <h6 class="mb-0 text-primary fw-bold">{{ $row['source'] }}</h6>
                </div>

                <!-- Column 2 -->
                <div class="col-md-3">
                    <p class="mb-1 text-muted small">Order Type</p>
                    <h6 class="mb-2 text-primary fw-bold">{{ $row['order_type_id'] }}</h6>

                    <p class="mb-1 text-muted small">Created By</p>
                    <h6 class="mb-0 text-primary"><span class="create_by text-primary fw-bold"></span></h6>
                </div>

                <!-- Column 3 -->
                <div class="col-md-3">
                    <p class="mb-1 text-success small d-flex align-items-center fw-bold">
                        Reference No
                        @if($pageMethod=="soorderapproved")
                        <i class="fa fa-plus ms-2 text-primary refview" role="button"></i>
                        @endif
                    </p>
                    <h6 class="mb-2">{{ $row['reference_number'] }}</h6>

                    <p class="mb-1 text-muted small">Order Total</p>
                    <h6 class="mb-0 text-success fw-bold">
                        {{ number_format((float)$row['order_total'], \Session::get("decimal")) }}
                    </h6>
                </div>

                <!-- Column 4 -->
                <div class="col-md-3">
                    <p class="mb-1 text-muted small">Order Tax</p>
                    <h6 class="mb-2 text-danger fw-bold tax_span">
                        {{ number_format((float)$row['order_tax'], \Session::get("decimal")) }}
                    </h6>

                    <p class="mb-1 text-muted small">Qty Total</p>
                    <h6 class="mb-0 fw-bold qty_span text-success">
                        {{ number_format((float)$row['qty_total'], \Session::get("decimal")) }}
                    </h6>
                </div>

            </div>
        </div>
    </div>

    <div class="card-body card-block">
        <form method="POST" enctype="multipart/form-data" action="" class="soorder_form" id="soorder_form"
            data-parsley-validate>
            <input type="hidden" value="" name="savestatus" id="savestatus" />
            {{ csrf_field()}}


            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-primary text-white fw-semibold rounded-top-4">
                    Sales Order Details
                </div>
                <div class="card-body">
                    <div class="row g-4">

                        <!-- Column 1 -->
                        <div class="col-12 col-lg-4">
                            <div class="card h-100">
                                <div class="card-header py-3">
                                    <strong class="text-uppercase small text-muted">Order Details</strong>
                                </div>
                                <div class="card-body">

                                    <!-- Order No (read only, hidden input + display) -->
                                    <div class="mb-3 d-none">
                                        <label for="sales_order_no" class="form-label">Order No</label>
                                        <input type="text" id="sales_order_no" name="sales_order_no"
                                            value="{{ $row['sales_order_no'] }}" class="form-control sales_order_no"
                                            readonly>
                                        <input type="hidden" name="sales_hdr_id" id="sales_hdr_id"
                                            class="form-control sales_hdr_id" value="{{ $row['sales_hdr_id'] }}">
                                    </div>

                                    <!-- Order Date -->
                                    <div class="mb-3">
                                        <label for="sales_order_date" class="form-label">Order Date</label>
                                        <div class="input-group input-icon">
                                            <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                            <input type="text" class="form-control sales_order_date"
                                                id="sales_order_date" name="sales_order_date"
                                                value="{{ $row['sales_order_date'] }}" readonly>
                                            <input id="source" type="hidden" name="source" value="{{ $row['source'] }}">
                                            <input id="order_status_id" type="hidden" name="order_status_id"
                                                value="{{ $row['order_status_id'] }}">
                                        </div>
                                    </div>

                                    <!-- Order Type (hidden) -->
                                    <div class="mb-3 d-none">
                                        <label for="order_type_id" class="form-label">Order Type</label>
                                        <input type="text" id="order_type_id" name="order_type_id"
                                            class="form-control order_type_id" value="{{ $row['order_type_id'] }}"
                                            readonly>
                                    </div>


                                    <!-- Organization (disabled) -->
                                    <div class="mb-3 d-none">
                                        <label for="organization_id" class="form-label">Organization</label>
                                        <select id="organization_id" name="organization_id"
                                            class="form-select select2 organization_id">
                                            {!!
                                            app(config('global.CONT'))->jCombo('m_organizations_t','organization_id','organization_name',Session::get('organization'))!!}
                                        </select>
                                    </div>

                                    <!-- Created By (hidden) -->
                                    <div class="mb-3 d-none">
                                        <label for="created_by" class="form-label">Created By</label>
                                        <select id="created_by" name="created_by"
                                            class="form-select select2 created_by">{!!
                                            $created_by !!}</select>
                                    </div>

                                    <!-- Customer -->
                                    <div class="mb-3 cusreademp">
                                        <label for="ship_to_customer_id" class="form-label required">Customer</label>
                                        <div class="d-flex gap-2">
                                            <select id="ship_to_customer_id" name="ship_to_customer_id"
                                                class="form-select select2 customer_id" required>
                                                {!! $customer !!}
                                            </select>
                                            <?php $groupname = \Session::get('groupname');
                                            if ($groupname != '14') { ?>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        title="Search Customer"><i
                                                            class="bi bi-search customersearch"></i></button>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <!-- Bill to Address -->
                                    <div class="mb-3">
                                        <label for="bill_to_address" class="form-label required ">Bill to
                                            Address</label>
                                        <textarea id="bill_to_address" class="bill_to_address form-control" rows="3"
                                            readonly required>{{$row['bill_to_address']}}</textarea>
                                        <input type="hidden" id="bill_to_address_id" name="bill_to_address_id"
                                            value="{{$row['bill_to_address_id']}}" class="bill_to_address_id">
                                        <div class="mt-2 d-flex gap-2">
                                            <button type="button"
                                                class="btn btn-outline-primary btn-sm changeaddress billto"
                                                value="billto"><i class="bi bi-journal-text me-1"></i>Change</button>
                                            <button type="button"
                                                class="btn btn-outline-success btn-sm changeaddress new_billto"
                                                value="new_billto"><i class="bi bi-plus-square me-1"></i>New</button>
                                        </div>
                                    </div>

                                    <!-- Delivery Date -->
                                    <div class="mb-0">
                                        <label for="delivery_date" class="form-label required">Delivery Date</label>
                                        <div class="input-group input-icon">
                                            <span class="input-group-text"><i class="bi bi-calendar2-event"></i></span>
                                            <input type="text" class="form-control delivery_date" id="delivery_date"
                                                name="delivery_date" value="{{ $row['delivery_date'] }}" required>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Column 2 -->
                        <div class="col-12 col-lg-4">
                            <div class="card h-100">
                                <div class="card-header py-3">
                                    <strong class="text-uppercase small text-muted">Pricing & Shipping</strong>
                                </div>
                                <div class="card-body">

                                    <!-- Price List -->
                                    <div class="mb-3">
                                        <label for="pricelist_id" class="form-label required ">Price List</label>
                                        <select id="pricelist_id" name="pricelist_id"
                                            class="form-select select2 pricelist_id rdonlydiv priceid" required>
                                            {!! $pricelist !!}
                                        </select>
                                    </div>

                                    <!-- Ship to Address -->
                                    <div class="mb-3">
                                        <label for="ship_to_address" class="form-label">Ship to Address</label>
                                        <textarea id="ship_to_address" class="form-control ship_to_address" rows="3"
                                            readonly required>{{$row['ship_to_address']}}</textarea>
                                        <input type="hidden" id="ship_to_address_id" name="ship_to_address_id"
                                            class="ship_to_address_id" value="{{$row['ship_to_address_id']}}">
                                        <input type="hidden" id="custype" name="custype" value="" />
                                        <div class="mt-2 d-flex gap-2">
                                            <button type="button"
                                                class="btn btn-outline-primary btn-sm changeaddress shipto"
                                                value="shipto"><i class="bi bi-journal-text me-1"></i>Change</button>
                                            <button type="button"
                                                class="btn btn-outline-success btn-sm changeaddress new_shipto"
                                                value="new_shipto"><i class="bi bi-plus-square me-1"></i>New</button>
                                        </div>
                                    </div>

                                    <!-- Proforma Invoice -->
                                    <div class="mb-3">
                                        <label class="form-label required text-danger fw-bold fst-italic">NEED PROFORMA
                                            INVOICE</label>
                                        <select name='proforma_invoice' class='form-select select2 proforma_invoice'
                                            required>
                                            <option value="">--Please Select--</option>
                                            <option value="YES" <?php if ($row['proforma_invoice'] == "YES")
                                                                    echo "selected"; ?>>YES</option>
                                            <option value="NO" <?php if ($row['proforma_invoice'] == "NO")
                                                                    echo "selected"; ?>>NO</option>
                                        </select>
                                    </div>

                                    <!-- Exchange Rate (only when EXPORT / EXPORT SAMPLE) -->
                                    <?php if ($row['order_type_id'] == "EXPORT" || $row['order_type_id'] == "EXPORT SAMPLE") { ?>
                                        <div class="mb-0 readonly">
                                            <label for="con_exc_rate"
                                                class="form-label required text-danger fw-bold fst-italic">Exchange
                                                Rate</label>
                                            <input type="text" id="con_exc_rate" name="con_exc_rate"
                                                class="form-control con_exc_rate" value="{{ $row['con_exc_rate'] }}"
                                                readonly>
                                        </div>
                                    <?php } ?>

                                </div>
                            </div>
                        </div>

                        <!-- Column 3 -->
                        <div class="col-12 col-lg-4">
                            <div class="card h-100">
                                <div class="card-header py-3">
                                    <strong class="text-uppercase small text-muted">References & Attachments</strong>
                                </div>
                                <div class="card-body">

                                    <!-- Reference No (hidden/plaintext) -->
                                    <div class="mb-3 d-none">
                                        <label for="reference_number" class="form-label">Reference No</label>
                                        <input type="hidden" id="reference_id" name="reference_id"
                                            value="{{$row['reference_id']}}" />
                                        <input type="text" id="reference_number" name="reference_number"
                                            class="form-control reference_number" value="{{$row['reference_number']}}"
                                            readonly>
                                    </div>

                                    <!-- Customer PO # -->
                                    <div class="mb-3">
                                        <label for="customer_po_number" class="form-label">Customer PO Number</label>
                                        <input type="text" id="customer_po_number" name="customer_po_number"
                                            value="{{ $row['customer_po_number'] }}"
                                            class="form-control customer_po_number" placeholder="e.g. PO-2025-0001"
                                            autocomplete="off">
                                    </div>

                                    <!-- Schemes -->
                                    <div class="mb-3">
                                        <label class="form-label">Schemes</label>
                                        <div class="d-flex gap-2">
                                            <select name='schemes_hdr_id' class='form-select select2 schemes_hdr_id'
                                                multiple>
                                                {!! $row['schemes'] !!}
                                            </select>
                                            <button type="button"
                                                class="btn btn-outline-success btn-sm schemes_type">Apply</button>
                                        </div>
                                        <div class="form-text form-text-muted">Hold Ctrl/Cmd to select multiple.</div>
                                    </div>

                                    <!-- Cash Discount -->
                                    <div class="mb-3">
                                        <label class="form-label">Cash Discount</label>
                                        <div class="d-flex gap-2">
                                            <select name='cash_discount' class='form-select select2 cash_discount'
                                                multiple>
                                                {!! $row['cash_discount'] !!}
                                            </select>
                                            <button type="button"
                                                class="btn btn-outline-success btn-sm cash_discount_type">Apply</button>
                                        </div>
                                    </div>

                                    <!-- Employee (only for SAMPLE order type) -->
                                    <?php if ($row['order_type_id'] == "SAMPLE") { ?>
                                        <div class="mb-3">
                                            <label for="employee_id" class="form-label required">Employee</label>
                                            <div class="d-flex gap-2">
                                                <select id="employee_id" name="employee_id"
                                                    class="form-select select2 employee_id" required>
                                                    {!! $row['employee_id'] !!}
                                                </select>
                                                <button type="button" class="btn btn-outline-secondary btn-sm"><i
                                                        class="bi bi-arrow-repeat jcr_employee_id"></i></button>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <!-- Hidden totals (kept for backend) -->
                                    <div class="row g-3 d-none">
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Order Tax</label>
                                            <input type="text" id="order_tax" name="order_tax"
                                                class="form-control order_tax" value="{{ $row['order_tax'] }}" readonly>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Order Total</label>
                                            <input type="text" id="order_total" name="order_total"
                                                class="form-control order_total" value="{{ $row['order_total'] }}"
                                                readonly>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Qty Total</label>
                                            <input type="text" id="qty_total" name="qty_total"
                                                class="form-control qty_total" value="{{ $row['qty_total'] }}" readonly>
                                        </div>
                                    </div>

                                    <!-- File Uploads -->
                                    <div class="mb-0">
                                        <label class="form-label">File Upload</label>

                                        <?php if ($pageModule == "soorder" && $row['sales_hdr_id'] == '' || $pageMethod == "soorderfromqo") { ?>
                                            <input id="" name="choosefile[]" type="file" class="form-control" multiple />
                                            <div class="mt-3">
                                                <table id="file_choosen"
                                                    class="table table-sm table-bordered align-middle mb-2">
                                                    <tbody id="fp"></tbody>
                                                </table>
                                                <div class="form-text text-success">Please upload files below 10MB each.
                                                </div>
                                            </div>
                                        <?php } else if ($row['sales_hdr_id'] != '' && ($return_url == "soordercreate" || $return_url == "socancellation" || $return_url == "soorderapproved")) { ?>
                                            <input id="choosefile" name="choosefile[]" type="file"
                                                class="form-control choosefile" multiple />
                                            <div class="mt-3">
                                                <table id="file_choosen"
                                                    class="table table-sm table-bordered align-middle mb-2">
                                                    <tbody id="fp">
                                                        <?php $dataupload = json_decode($row['attachfile_name']);
                                                        if ($dataupload != "" && $dataupload != NULL) { ?>
                                                            <input type="hidden" value="{{implode(" ,",$dataupload)}}"
                                                                name="existing_file" id="existing_file" />
                                                            <?php foreach ($dataupload as $k => $v) { ?>
                                                                <tr>
                                                                    <td>
                                                                        <span class="small">File: <span class="files"><a download
                                                                                    href="{{URL::to('')}}/Uploads/salesorderupload/SO{{$row['sales_hdr_id']}}/{{$v}}">{{$v}}</a></span></span>
                                                                        <button
                                                                            class="btn btn-link text-danger p-0 float-end delete_user"
                                                                            data-value="{{$v}}" type="button" title="Remove"><i
                                                                                class="bi bi-x-circle"></i></button>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                                <div class="form-text text-danger">Please upload files below 10MB each.
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <div class="mt-2">
                                                <table id="file_choosen"
                                                    class="table table-sm table-bordered align-middle mb-2">
                                                    <tbody id="fp">
                                                        <?php $dataupload = json_decode($row->attachfile_name);
                                                        if ($dataupload != "" && $dataupload != NULL) { ?>
                                                            <input type="hidden" value="{{implode(" ,",$dataupload)}}"
                                                                name="existing_file" id="existing_file" />
                                                            <?php foreach ($dataupload as $k => $v) { ?>
                                                                <tr>
                                                                    <td>
                                                                        <span class="small">File: <span class="files"><a download
                                                                                    href="{{URL::to('')}}/uploads/salesorderupload/SO{{$row['sales_hdr_id']}}/{{$v}}">{{$v}}</a></span></span>
                                                                        <button
                                                                            class="btn btn-link text-danger p-0 float-end delete_user"
                                                                            data-value="{{$v}}" type="button" title="Remove"><i
                                                                                class="bi bi-x-circle"></i></button>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php } ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Additional Details (Bootstrap 5) -->
                    <div class="mt-5">
                        <div class="card">
                            <div class="card-header py-3">
                                <strong class="text-primary">Additional Details</strong>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <?php
                                    $i = 0;
                                    $j = 0;
                                    $show_div = 0;
                                    foreach ($enabled_columns as $index => $val) {
                                        $required = ($val->action == '1') ? 'required' : '';
                                    ?>
                                        <?php if ($val->column_name == 'so_ref_no' && $val->active == 1) {
                                            $i++; ?>
                                            <div class="col-12 col-lg-4">
                                                <label for="so_ref_no" class="form-label">SO Reference Number</label>
                                                <input type="text" name="so_ref_no" <?= $required ?> id="so_ref_no"
                                                    class="form-control so_ref_no" value="{{ $row['so_ref_no'] }}">
                                            </div>
                                        <?php } ?>

                                        <?php if ($val->column_name == 'ar_quote_hdr_id' && $val->active == 1) {
                                            $i++; ?>
                                            <div class="col-12 col-lg-4">
                                                <label for="ar_quote_hdr_id" class="form-label">Quote Number</label>
                                                <input class="form-control ar_quote_hdr_id" name="ar_quote_hdr_id" <?= $required ?> id="ar_quote_hdr_id" type="text" value="{{ $row['ar_quote_hdr_id'] }}">
                                            </div>
                                        <?php } ?>

                                        <?php if ($val->column_name == 'currency_code_id' && $val->active == 1) {
                                            $i++; ?>
                                            <div class="col-12 col-lg-4">
                                                <label for="currency_code_id" class="form-label">Currency Code</label>
                                                <select name='currency_code_id' <?= $required ?>
                                                    class='form-select currency_code_id' id="currency_code_id">
                                                    {!!
                                                    app(config('global.CONT'))->jCombo('m_currency_t','currency_id','currency_code',$row['currency_code_id'])!!}
                                                </select>
                                            </div>
                                        <?php } ?>

                                        <?php if ($val->column_name == 'remarks' && $val->active == 1) {
                                            $i++; ?>
                                            <div class="col-12 col-lg-4">
                                                <label for="remarks"
                                                    class="form-label<?= ($required ? ' required' : '') ?>">Remarks</label>
                                                <input type="text" name="remarks" id="remarks" class="form-control remarks"
                                                    value="{{ $row['remarks'] }}" tabindex="2">
                                            </div>
                                        <?php } ?>

                                        <?php if ($val->column_name == 'contact_number' && $val->active == 1) {
                                            $i++; ?>
                                            <div class="col-12 col-lg-4">
                                                <label for="contact_number"
                                                    class="form-label<?= ($required ? ' required' : '') ?>">Contact
                                                    Number</label>
                                                <input type="text" name="contact_number" id="contact_number"
                                                    class="form-control contact_number" maxlength="13"
                                                    value="{{ $row['contact_number'] }}" tabindex="4">
                                            </div>
                                        <?php } ?>

                                        <?php if ($val->column_name == 'contact_person' && $val->active == 1) {
                                            $i++; ?>
                                            <div class="col-12 col-lg-4">
                                                <label for="contact_person"
                                                    class="form-label<?= ($required ? ' required' : '') ?>">Contact
                                                    Person</label>
                                                <input type="text" name="contact_person" id="contact_person" <?= $required; ?>
                                                    class="form-control contact_person" value="{{ $row['contact_person'] }}"
                                                    tabindex="3">
                                            </div>
                                        <?php } ?>

                                        <?php if ($val->column_name == 'project_id' && $val->active == 1) {
                                            $i++; ?>
                                            <div class="col-12 col-lg-4">
                                                <label for="project_id"
                                                    class="form-label<?= ($required ? ' required' : '') ?>">Project</label>
                                                <div class="d-flex gap-2 align-items-start">
                                                    <select name='project_id' class='form-select project_id select2'
                                                        id="project_id" data-show-subtext="true" data-live-search="true">{!!
                                                        $row['project_id'] !!}</select>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <?php if ($val->column_name == 'salesperson_id' && $val->active == 1) {
                                            $i++; ?>
                                            <div class="col-12 col-lg-4">
                                                <label class="form-label<?= ($required ? ' required' : '') ?>">Sales Person
                                                    Name</label>
                                                <div class="d-flex gap-2 align-items-start">
                                                    <select name='salesperson_id' class='form-select salesperson_id select2'
                                                        data-show-subtext="true" data-live-search="true">{!!
                                                        $row['salesperson_id'] !!}</select>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <?php if ($val->column_name == 'ar_frieghtcarriers_hdr_id' && $val->active == 1) {
                                            $i++; ?>
                                            <div class="col-12 col-lg-4">
                                                <label class="form-label<?= ($required ? ' required' : '') ?>">Carrier
                                                    Name</label>
                                                <div class="d-flex gap-2 align-items-start">
                                                    <select name='freight_carrier_id'
                                                        class='form-select ar_frieghtcarriers_hdr_id select2'
                                                        data-show-subtext="true" data-live-search="true">{!!
                                                        $row['ar_frieghtcarriers_hdr_id'] !!}</select>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <?php if ($val->column_name == 'ar_delivery_terms_id' && $val->active == 1) {
                                            $i++; ?>
                                            <div class="col-12 col-lg-4">
                                                <label class="form-label<?= ($required ? ' required' : '') ?>">Delivery
                                                    Name</label>
                                                <div class="d-flex gap-2 align-items-start">
                                                    <select name='ar_delivery_terms_id'
                                                        class='form-select ar_delivery_terms_id select2'
                                                        data-show-subtext="true" data-live-search="true">{!!
                                                        $row['ar_delivery_terms_id'] !!}</select>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <?php if ($val->column_name == 'ar_payment_method_id' && $val->active == 1) {
                                            $i++; ?>
                                            <div class="col-12 col-lg-4">
                                                <label class="form-label<?= ($required ? ' required' : '') ?>">Payment Method
                                                    Name</label>
                                                <div class="d-flex gap-2 align-items-start">
                                                    <select name='ar_payment_method_id'
                                                        class='form-select ar_payment_method_id select2'
                                                        data-show-subtext="true" data-live-search="true">{!!
                                                        $row['ar_payment_method_id'] !!}</select>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <?php if ($val->column_name == 'other_tax_amount' && $val->active == 1) {
                                            $i++; ?>
                                            <div class="col-12 col-lg-4">
                                                <label for="other_tax_amount"
                                                    class="form-label<?= ($required ? ' required' : '') ?>">Other Tax
                                                    Amount</label>
                                                <div class="input-group">
                                                    <input type="text" name="other_tax_amount" id="other_tax_amount"
                                                        class="form-control charges other_tax_amount"
                                                        value="{{ $row['other_tax_amount'] }}" readonly>
                                                    <button class="btn btn-outline-primary packingtax"
                                                        data-value="Other Tax Amount" data-at="4" type="button" title="Add"><i
                                                            class="bi bi-plus-circle"></i></button>
                                                </div>
                                                <input type="hidden" name="other_tax_amount_tax" id="other_tax_amount_tax"
                                                    value="{{ $row['other_tax_amount_tax']}}" class="other_tax_amount_tax">
                                            </div>
                                        <?php } ?>

                                        <?php if ($val->column_name == 'other_frieght_amount' && $val->active == 1) {
                                            $i++; ?>
                                            <div class="col-12 col-lg-4">
                                                <label for="other_frieght_amount"
                                                    class="form-label<?= ($required ? ' required' : '') ?>">Other Freight
                                                    Amount</label>
                                                <div class="input-group">
                                                    <input type="text" name="other_frieght_amount" id="other_frieght_amount"
                                                        class="form-control charges other_frieght_amount"
                                                        value="{{ $row['other_frieght_amount'] }}" readonly>
                                                    <button class="btn btn-outline-primary packingtax"
                                                        data-value="Other Freight Amount" data-at="5" type="button"
                                                        title="Add"><i class="bi bi-plus-circle"></i></button>
                                                </div>
                                                <input type="hidden" name="other_frieght_amount_tax"
                                                    id="other_frieght_amount_tax" value="{{ $row['other_frieght_amount_tax']}}"
                                                    class="other_frieght_amount_tax">
                                            </div>
                                        <?php } ?>

                                        <?php if ($val->column_name == 'transport_charges' && $val->active == 1) {
                                            $i++; ?>
                                            <div class="col-12 col-lg-4">
                                                <label for="transport_charges"
                                                    class="form-label<?= ($required ? ' required' : '') ?>">Transport
                                                    Charges</label>
                                                <div class="input-group">
                                                    <input type="text" name="transport_charges" id="transport_charges"
                                                        class="form-control charges transport_charges"
                                                        value="{{ $row['transport_charges'] }}" readonly>
                                                    <button class="btn btn-outline-primary packingtax"
                                                        data-value="Transport Charges" data-at="2" type="button" title="Add"><i
                                                            class="bi bi-plus-circle"></i></button>
                                                </div>
                                                <input type="hidden" name="transport_charges_tax" id="transport_charges_tax"
                                                    value="{{ $row['transport_charges_tax']}}" class="transport_charges_tax">
                                            </div>
                                        <?php } ?>

                                        <?php if ($val->column_name == 'insurance_charges' && $val->active == 1) {
                                            $i++; ?>
                                            <div class="col-12 col-lg-4">
                                                <label for="insurance_charges"
                                                    class="form-label<?= ($required ? ' required' : '') ?>">Insurance
                                                    Charges</label>
                                                <div class="input-group">
                                                    <input type="text" name="insurance_charges" id="insurance_charges"
                                                        class="form-control charges insurance_charges"
                                                        value="{{ $row['insurance_charges'] }}" readonly>
                                                    <button class="btn btn-outline-primary packingtax"
                                                        data-value="Insurance Charges" data-at="3" type="button" title="Add"><i
                                                            class="bi bi-plus-circle"></i></button>
                                                </div>
                                                <input type="hidden" name="insurance_charges_tax" id="insurance_charges_tax"
                                                    value="{{ $row['insurance_charges_tax']}}" class="insurance_charges_tax">
                                            </div>
                                        <?php } ?>

                                        <?php if ($val->column_name == 'packaging_charges' && $val->active == 1) {
                                            $i++; ?>
                                            <div class="col-12 col-lg-4">
                                                <label for="packaging_charges"
                                                    class="form-label<?= ($required ? ' required' : '') ?>">Packaging
                                                    Charges</label>
                                                <div class="input-group">
                                                    <input type="text" name="packaging_charges" id="packaging_charges"
                                                        class="form-control charges packaging_charges"
                                                        value="{{ $row['packaging_charges'] }}" readonly>
                                                    <button class="btn btn-outline-primary packingtax"
                                                        data-value="Packaging Charges" data-at="1" type="button" title="Add"><i
                                                            class="bi bi-plus-circle"></i></button>
                                                </div>
                                                <input type="hidden" name="packaging_charges_tax" id="packaging_charges_tax"
                                                    value="{{$row['packaging_charges_tax']}}" class="packaging_charges_tax">
                                            </div>
                                        <?php } ?>

                                        <?php if ($val->column_name == 'ar_payment_term_id' && $val->active == 1) {
                                            $i++; ?>
                                            <div class="col-12 col-lg-4">
                                                <label class="form-label<?= ($required ? ' required' : '') ?>">Payment
                                                    Term</label>
                                                <div class="d-flex gap-2 align-items-start">
                                                    <select name='ar_payment_term_id'
                                                        class='form-select ar_payment_term_id select2' data-show-subtext="true"
                                                        data-live-search="true">{!! $row['ar_payment_term_id'] !!}</select>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <?php if ($val->column_name == 'ar_frieghtterm_id' && $val->active == 1) {
                                            $i++; ?>
                                            <div class="col-12 col-lg-4">
                                                <label class="form-label<?= ($required ? ' required' : '') ?>">Freight
                                                    Term</label>
                                                <div class="d-flex gap-2 align-items-start">
                                                    <select name='ar_frieghtterm_id'
                                                        class='form-select ar_frieghtterm_id select2' data-show-subtext="true"
                                                        data-live-search="true">{!! $row['ar_frieghtterm_id'] !!}</select>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <?php if ($val->column_name == 'invoice_currency' && $val->active == 1) {
                                            $i++; ?>
                                            <div class="col-12 col-lg-4">
                                                <label class="form-label<?= ($required ? ' required' : '') ?>">Invoice
                                                    Currency</label>
                                                <input type="hidden" name="" id="currency_rate" class="currency_rate" value="">
                                                <div class="d-flex gap-2 align-items-start">
                                                    <select name="invoice_currency" id="invoice_currency" <?= $required; ?>
                                                        tabindex="23" class="form-select select2 invoice_currency">{!!
                                                        $row['invoice_currency'] !!}</select>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <?php if ($val->column_name == 'trade_discount' && $val->active == 1) {
                                            $i++; ?>
                                            <div class="col-12 col-lg-4">
                                                <label class="form-label<?= ($required ? ' required' : '') ?>">Trade
                                                    Discount</label>
                                                <input class="form-control trade_discount" id="trade_discount"
                                                    name="trade_discount" type="text" value="{{$row['trade_discount']}}"
                                                    tabindex="10">
                                                <input class="form-control trade_discount_pre" id="trade_discount_pre"
                                                    name="trade_discount_pre" type="hidden"
                                                    value="{{$row['trade_discount_pre']}}">
                                            </div>
                                        <?php } ?>

                                        <?php if ($val->column_name == 'discount_id' && $val->active == 1) {
                                            $i++; ?>
                                            <div class="col-12 col-lg-4">
                                                <label class="form-label<?= ($required ? ' required' : '') ?>">Discount</label>
                                                <div class="d-flex gap-2 align-items-start">
                                                    <select name="discount_id" id="discount_id" <?= $required; ?>
                                                        class="form-select select2 discount_id">{!! $discount !!}</select>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <?php if ($val->column_name == 'tcs_applicable' && $val->active == 1) {
                                            $i++; ?>
                                            <div class="col-12 col-lg-4">
                                                <label class="form-label required">TCS Applicable</label>
                                                <select name='tcs_applicable' <?= $required; ?>
                                                    class='form-select tcs_applicable select2' tabindex="17">
                                                    <option value="">--Please Select--</option>
                                                    <option value="YES" <?php if ($row['tcs_applicable'] == 'YES')
                                                                            echo 'selected'; ?>>YES</option>
                                                    <option value="NO" <?php if ($row['tcs_applicable'] == 'NO')
                                                                            echo 'selected'; ?>>NO</option>
                                                </select>
                                            </div>
                                        <?php } ?>

                                        <?php if ($val->column_name == 'tcs_amount' && $val->active == 1) {
                                            $i++; ?>
                                            <div class="col-12 col-lg-4">
                                                <label class="form-label">TCS Calc Amount</label>
                                                <input type="text" name="tcs_calc_amount" <?= $required; ?> id="tcs_calc_amount"
                                                    value="{{$row['tcs_calc_amount']}}" class="form-control tcs_calc_amount"
                                                    tabindex="15">
                                            </div>
                                        <?php } ?>

                                        <?php if ($val->column_name == 'tcs_calc_amount' && $val->active == 1) {
                                            $i++; ?>
                                            <div class="col-12 col-lg-4">
                                                <label class="form-label">TCS Amount</label>
                                                <input type="text" name="tcs_amount" <?= $required; ?> id="tcs_amount"
                                                    value="{{$row['tcs_amount']}}" class="form-control tcs_amount" readonly
                                                    tabindex="15">
                                            </div>
                                        <?php } ?>

                                        <?php if ($val->column_name == 'tcs_prcnt' && $val->active == 1) {
                                            $i++; ?>
                                            <div class="col-12 col-lg-4">
                                                <label class="form-label">TCS Percentage</label>
                                                <input type="text" name="tcs_prcnt" <?= $required; ?> id="tcs_prcnt"
                                                    value="{{$row['tcs_prcnt']}}" class="form-control tcs_prcnt" readonly
                                                    tabindex="14">
                                            </div>
                                        <?php } ?>

                                        <?php if ($val->column_name == 'tcs_account_id' && $val->active == 1) {
                                            $i++; ?>
                                            <div class="col-12 col-lg-4">
                                                <label class="form-label">TCS Account</label>
                                                <div class="d-flex gap-2 align-items-start">
                                                    <select name='tcs_account_id' <?= $required; ?>
                                                        class='form-select tcs_account_id select2' readonly tabindex="18">{!!
                                                        $row['tcs_account_id'] !!}</select>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?php } // end foreach 
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>




                    <?php if ($row['order_type_id'] == "EXPORT" || $row['order_type_id'] == "EXPORT SAMPLE") { ?>

                        <div class="row g-3 mb-3">

                            <!-- Mode of Transport -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="transport_mode" class="form-label fw-semibold">
                                        Mode of Transport <span class="text-danger">*</span>
                                    </label>
                                    <select name="transport_mode" id="transport_mode" class="form-select select2" required>
                                        {!! $row['transport_mode'] !!}
                                    </select>
                                </div>
                            </div>

                            <!-- Port of Discharge -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="port_of_discharge" class="form-label fw-semibold">
                                        Port of Discharge <span class="text-danger">*</span>
                                    </label>
                                    <select name="port_of_discharge" id="port_of_discharge" class="form-select select2"
                                        required>
                                        {!! $row['port_of_discharge'] !!}
                                    </select>
                                </div>
                            </div>

                            <!-- Empty Column for Alignment -->
                            <div class="col-md-4"></div>
                        </div>

                    <?php } ?>


                    <!-- Hidden template for all products (used by add-row to populate product dropdown) -->
                    <select id="all_products_template" style="display:none;">
                        {!! app(config('global.CONT'))->jcustomselect('m_products_t','product_id','concatenated_product','','and product_group_id=1') !!}
                    </select>

                    <!-- Hidden template for all UOM codes -->
                    <select id="all_uom_template" style="display:none;">
                        {!! app(config('global.CONT'))->jCombo('m_uom_codes_t','uom_code_id','uom_code','') !!}
                    </select>

                    <!-- Hidden template for all tax groups -->
                    <select id="all_tax_groups_template" style="display:none;">
                        {!! app(config('global.CONT'))->jCombotax('m_tax_group_t','tax_group_id','tax_group_name','') !!}
                    </select>

                    <input type="hidden" id="decimal_point" class="decimal_point" value="" />

                    <!-------------------------Linedata  form-------------------------------->

                    <div class="row mt-4">
                        <div class=" col-md-12">
                            <div id="preview-area" class="table-responsive">
                                <table class="table table-bordered clone_table" style="width:200% !important;">


                                    <thead class="table-light">
                                        <tr>
                                            <th>Line No</th>
                                            <th>Product</th>
                                            <th>Customer Part No</th>
                                            <th>Uom Code</th>
                                            <?php if ($row['order_type_id'] == "LABOUR") { ?>
                                                <th>Product Description</th>
                                            <?PHP } ?>

                                            <th>Qty</th>
                                            <th class="sample">Unit Price</th>
                                            <th>Free Qty</th>
                                            <th class="sample">Discount Percentage</th>
                                            <th class="sample">Discount Amount</th>
                                            <th class="sample">Tax Exemption</th>
                                            <?php if ($row['order_type_id'] == "STANDARD") { ?>
                                                <th class="hsn" id="sample"> HSN Code </th>
                                            <?php } else { ?>
                                                <th id="sample"> SAC Code </th>
                                            <?php } ?>
                                            <th class="sample">Tax Group</th>
                                            <th class="sample">Tax Amount</th>
                                            <th class="sample">Line Total</th>
                                            <th>Qoh Qty</th>
                                            <th>Delivery Date</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>

                                    <tbody class="clone_lines_body">

                                        <?php if (count($linedata) <= 0) {
                                        ?>
                                            <tr class="rcopy clone clonedInput">

                                                <td><input type="hidden" name="bulk_sales_line_id[]"
                                                        class="form-control  bulk_sales_line_id" value="">
                                                    <input type="hidden" name="bulk_workorder_status[]" value="0">
                                                    <input type="hidden" name="bulk_dispatch_status[]" value="0">
                                                    <input type="hidden" name="bulk_invoiced_qty[]" value="0">
                                                    <input type="hidden" name="bulk_dispatched_qty[]" value="0">
                                                    <input type="hidden" name="bulk_remaining_qty[]" value="0">
                                                    <input type="text" name="bulk_line_no[]"
                                                        class="form-control bulk_line_no" value="1" readonly="readonly">
                                                </td>

                                                <td class="divquote">
                                                    <select name="bulk_product_id[]"
                                                        class="form-control select2 bulk_product_id">

                                                        {!!
                                                        app(config('global.CONT'))->jcustomselect('m_products_t','product_id','concatenated_product','','and
                                                        product_group_id=1') !!}

                                                    </select>
                                                </td>
                                                <td class="" style="pointer-events:none;">
                                                    <select name="bulk_part_no[]" id="bulk_part_no"
                                                        class="bulk_part_no select2 ">{!! $part_no !!}</select>
                                                </td>
                                                <td class="uom " style="pointer-events:none;">

                                                    <select name="bulk_uom_code_id[]" id="bulk_uom_code_id"
                                                        class=" bulk_uom_code_id select2" required="required">
                                                        {!!
                                                        app(config('global.CONT'))->jCombo('m_uom_codes_t','uom_code_id','uom_code',$rows['uom_code_id']);
                                                        !!}

                                                    </select>
                                                </td>
                                                <?php if ($row['order_type_id'] == "LABOUR") { ?>
                                                    <td class="pdtdes_div">
                                                        <input type="text" name="bulk_product_description[]"
                                                            class="form-control  bulk_product_description input_qty_width">
                                                    </td>
                                                <?php } ?>



                                                <td>
                                                    <input type="text" name="bulk_qty[]"
                                                        class="form-control bulk_qty input_qty_width" required>
                                                </td>

                                                <td class="sample"><input type="text" name="bulk_unit_price[]"
                                                        id="bulk_unit_price" class="form-control bulk_unit_price"
                                                        required="required" readonly></td>
                                                <td>
                                                    <input type="text" name="bulk_free_qty[]"
                                                        class="form-control bulk_free_qty input_qty_width">
                                                </td>
                                                <td class=""><input type="text" name="bulk_discount_percentage[]"
                                                        class="form-control  bulk_discount_percentage"></td>

                                                <td class="sample"><input type="text" name="bulk_discount_amount[]"
                                                        class="form-control  bulk_discount_amount"></td>
                                                <td><select name="bulk_tax_excemption[]"
                                                        class="form-control select2 bulk_tax_excemption"
                                                        id="bulk_tax_excemption" required>
                                                        <option value="">--Please Select--</option>
                                                        <option value="Yes" <?php if ($row['order_type_id'] == "EXPORT" && $row['order_type_id'] == "EXPORT SAMPLE") { ?> selected
                                                            <?php } ?>>Yes
                                                        </option>
                                                        <option value="No" <?php if ($row['order_type_id'] != "EXPORT" && $row['order_type_id'] != "EXPORT SAMPLE") { ?> selected
                                                            <?php } ?>>No
                                                        </option>
                                                    </select>
                                                </td>
                                                <?php if ($row['order_type_id'] == "STANDARD") { ?>
                                                    <td class="hsn" id="hsncode">
                                                        <select name="bulk_hsn_code[]" id="bulk_hsn_code"
                                                            class="select2 bulk_hsn_code">{!! $hsn_code !!}</select>
                                                    </td>
                                                <?PHP } else { ?>
                                                    <td>
                                                        <select name="bulk_hsn_code[]" id="bulk_hsn_code"
                                                            class="select2 bulk_hsn_code">{!! $hsn_code !!}</select>
                                                    </td>
                                                <?php } ?>
                                                <td class="tax">
                                                    <select name="bulk_tax_group_id[]" id="bulk_tax_group_id"
                                                        class="bulk_tax_group_id select2" required="required">
                                                        {!!
                                                        app(config('global.CONT'))->jCombotax('m_tax_group_t','tax_group_id','tax_group_name',$rows['tax_group_id']);
                                                        !!}

                                                    </select>
                                                </td>

                                                <td class="sample"><input type="text" name="bulk_tax_amount[]"
                                                        class="form-control  bulk_tax_amount" readonly></td>

                                                <td class="sample"><input type="text" name="bulk_line_total[]"
                                                        class="form-control  bulk_line_total" readonly></td>
                                                <td><input type="text" name="bulk_qoh_qty[]"
                                                        class="form-control  bulk_qoh_qty"></td>
                                                <td><input type="text" name="bulk_delivery_date[]"
                                                        class="form-control  bulk_delivery_date" required></td>

                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-danger remove-row">
                                                        <i class="fas fa-minus-circle"></i>
                                                    </button>
                                                </td>

                                            </tr>

                                        <?php } else {

                                        ?>
                                            @foreach ($linedata as $row_key=>$rows)

                                            <tr class="rcopy clone clonedInput">

                                                <td><input type="hidden" name="bulk_sales_line_id[]"
                                                        class="form-control  bulk_sales_line_id"
                                                        value="{{ $rows->sales_line_id }}">
                                                    <input type="hidden" name="bulk_workorder_status[]" value="0">
                                                    <input type="hidden" name="bulk_dispatch_status[]" value="0">
                                                    <input type="hidden" name="bulk_invoiced_qty[]" value="0">
                                                    <input type="hidden" name="bulk_dispatched_qty[]" value="0">
                                                    <input type="hidden" name="bulk_remaining_qty[]" value="0">
                                                    <input type="text" name="bulk_line_no[]"
                                                        class="form-control bulk_line_no" value="{{ $key + 1 }}"
                                                        readonly="readonly">
                                                </td>

                                                <td class="pdtdiv rdonlydiv  divquote">

                                                    <select name="bulk_product_id[]"
                                                        class="form-control select2 bulk_product_id " id="bulk_product_id">
                                                        {!! $rows->product_id !!}

                                                    </select>
                                                </td>
                                                <td class="pdtdiv ">

                                                    <select name="bulk_part_no[]" id="bulk_part_no"
                                                        class="bulk_part_no select2">
                                                        {!! $rows->manufacturer_partno_id !!}
                                                    </select>
                                                </td>
                                                <td class="uom rdonlydiv ">
                                                    <select name="bulk_uom_code_id[]" id="bulk_uom_code_id"
                                                        class="form-control bulk_uom_code_id select2"
                                                        required="required">
                                                        {!! $rows->uom_code_id !!}
                                                    </select>
                                                </td>
                                                <?php if ($row['order_type_id'] == "LABOUR") { ?>
                                                    <td class="pdtdes_div  ">
                                                        <input type="text" name="bulk_product_description[]"
                                                            class="form-control  bulk_product_description input_qty_width"
                                                            value="{{ $rows->product_description }}">
                                                    </td>
                                                <?php } ?>



                                                <td>
                                                    <input type="text" name="bulk_qty[]"
                                                        class="form-control  bulk_qty input_qty_width"
                                                        value="{{ $rows->qty }}" required>
                                                </td>

                                                <td class="sample">
                                                    <?php $price = number_format($rows->unit_price, \Session::get("decimal"), '.', '') ?>

                                                    <input type="text" name="bulk_unit_price[]"
                                                        class="form-control  bulk_unit_price" value="{{ $price }}"
                                                        required="required" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" name="bulk_free_qty[]"
                                                        class="form-control bulk_free_qty input_qty_width"
                                                        value="{{$rows->free_qty}}">
                                                </td>
                                                <td class=""><input type="text" name="bulk_discount_percentage[]"
                                                        class="form-control  bulk_discount_percentage"
                                                        value="{{ $rows->discount_percentage }}" ></td>

                                                <td>
                                                    <?php $disamt = number_format($rows->discount_amount, \Session::get("decimal"), '.', '') ?>

                                                    <input type="text" name="bulk_discount_amount[]"
                                                        class="form-control  bulk_discount_amount" value="{{ $disamt }}">
                                                </td>
                                                <td><select name="bulk_tax_excemption[]"
                                                        class="form-control select2 bulk_tax_excemption"
                                                        id="bulk_tax_excemption" required>
                                                        <option value="">--please select--</option>
                                                        <option value="Yes" <?php if ($rows->tax_excemption == 'Yes') {
                                                                                echo 'selected';
                                                                            } ?>>Yes</option>
                                                        <option value="No" <?php if ($rows->tax_excemption == 'No') {
                                                                                echo 'selected';
                                                                            } ?>>No</option>
                                                    </select>
                                                </td>
                                                <?php if ($row['order_type_id'] == "STANDARD") { ?>
                                                    <td class="hsn" id="hsncode">
                                                        <select name="bulk_hsn_code[]" id="bulk_hsn_code"
                                                            class="select2 bulk_hsn_code">{!! $rows->hsn_code !!}</select>
                                                    </td>
                                                <?php } else { ?>
                                                    <td>
                                                        <select name="bulk_hsn_code[]" id="bulk_hsn_code"
                                                            class="select2 bulk_hsn_code">

                                                            {!! $rows->hsn_code !!}
                                                        </select>
                                                    </td>
                                                <?php } ?>

                                                <td class="tax rdonlydiv">
                                                    <select name="bulk_tax_group_id[]" id="bulk_tax_group_id"
                                                        class="form-control bulk_tax_group_id select2" required="required">
                                                        {!! $rows->tax_group_id !!}
                                                    </select>
                                                </td>

                                                <td class="sample">
                                                    <?php $taxamt = number_format($rows->tax_amount, \Session::get("decimal"), '.', '') ?>

                                                    <input type="text" name="bulk_tax_amount[]"
                                                        class="form-control  bulk_tax_amount" readonly
                                                        value="{{ $taxamt }}">
                                                </td>


                                                <td class="sample">
                                                    <?php $linetot = number_format($rows->line_total, \Session::get("decimal"), '.', '') ?>
                                                    <input type="text" name="bulk_line_total[]"
                                                        class="form-control  bulk_line_total" readonly
                                                        value="{{ $linetot }}">
                                                </td>
                                                <td><input type="text" name="bulk_qoh_qty[]"
                                                        class="form-control  bulk_qoh_qty" value="{{ $rows->qoh_qty }}">
                                                </td>
                                                <td><input type="text" name="bulk_delivery_date[]"
                                                        class="form-control  bulk_delivery_date datepicker"
                                                        value="{{ $rows->delivery_date }}" required></td>

                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-danger remove-row">
                                                        <i class="fas fa-minus-circle"></i>
                                                    </button>
                                                </td>

                                            </tr>

                                            @endforeach
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
                                <?php $groupname = \Session::get('groupname'); ?>
                                <?php $employe_id = \Session::get('emp_id'); ?>
                                <?php if ($return_url == "soordercreate") { ?>
                                    <input type="hidden" name="submit_type" class="submit_type" id="submit_type">
                                    <?php if ($groupname == '14') { ?>

                                    <?php } else if ($groupname == '14' && $employe_id == '24') { ?>
                                        <button name="apply" type="button" class="btn btn-secondary px-4 me-2 saveform"
                                            value="APPLYCHANGES">Draft</button>
                                    <?php } else { ?>
                                        <button name="apply" type="button" class="btn btn-secondary saveform px-4 me-2"
                                            value="APPLYCHANGES">Draft</button>
                                    <?php } ?>
                                    <button name="submit" type="button" class="btn px-4 me-2 btn-success saveform"
                                        value="SAVE">Save</button>

                                <?php } else if ($return_url == "socancellation") { ?>
                                    <button type="button" name="cancelled" class="btn cancelled btn-danger px-4 me-2"
                                        value="CANCELLED">Cancel Sales Order</button>
                                <?php } else { ?>
                                    <button type="button" name="submit" class="btn btn-success px-4 me-2 saveform"
                                        value="APPROVED">Approve</button>
                                    <button type="button" name="submit" class="btn btn-danger px-4 me-2 saveform"
                                        value="REJECT">Reject</button>
                                <?php } ?>
                                <a class='btn btn-outline-danger px-4'
                                    onclick='location.href="{{ url($pageModule) }}"'>Cancel</a>
                            </div>
                        </div>
                    </div>


                    <!-- Product Details Modal -->
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
                                        <table id="productTable" class="table table-bordered table-striped"
                                            style="width:100%">
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


                    <input type="hidden" class="pdtindex" value="" />

                </div>
            </div>

        </form>




        <!-- Tax Details Modal -->
        <div class="modal fade" id="taxModal" tabindex="-1" aria-labelledby="taxModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
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



        <div class="modal fade" id="new_address" tabindex="-1" aria-labelledby="newAddressLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl"> <!-- 80% width replaced with modal-xl -->
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h5 class="modal-title w-100 text-center fw-bold" id="newAddressLabel">Customer Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body">
                        <form method="post" action="" id="newaddress" class="needs-validation" novalidate>
                            <div class="row g-4">

                                <!-- Left Column -->
                                <div class="col-md-6">

                                    <div class="mb-3 row">
                                        <label for="customer_site_number" class="col-sm-5 col-form-label">Customer Site
                                            Number</label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="customer_site_number"
                                                name="customer_site_number" readonly>
                                            <input type="hidden" id="custype" name="custype">
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <label for="address" class="col-sm-5 col-form-label">
                                            Address <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="address" name="address"
                                                required>
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <label for="state" class="col-sm-5 col-form-label">
                                            State <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-sm-7">
                                            <select class="form-select select2" id="state" name="state" required>
                                                {!! $state_new !!}
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <label for="contact_person" class="col-sm-5 col-form-label">Contact
                                            Person</label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="contact_person"
                                                name="contact_person">
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <label for="contact_number" class="col-sm-5 col-form-label">
                                            Contact Number <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="contact_number"
                                                name="contact_number" required>
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <label for="primary_address" class="col-sm-5 col-form-label">Primary
                                            Address</label>
                                        <div class="col-sm-7">
                                            <select class="form-select" id="primary_address" name="primary_address">
                                                <option value="">-- Please Select --</option>
                                                <option value="YES">YES</option>
                                                <option value="NO">NO</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="col-md-6">

                                    <div class="mb-3 row">
                                        <label for="customer_site_name" class="col-sm-5 col-form-label">
                                            Customer Site Name <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-sm-7">
                                            <input type="hidden" id="customer_site_id" name="customer_site_id">
                                            <input type="text" class="form-control" id="customer_site_name"
                                                name="customer_site_name" required>
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <label for="country" class="col-sm-5 col-form-label">
                                            Country <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-sm-7">
                                            <select class="form-select select2" id="country" name="country" required>
                                                {!! $country_new !!}
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <label for="city" class="col-sm-5 col-form-label">
                                            City <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-sm-7">
                                            <select class="form-select select2" id="city" name="city" required>
                                                {!! $city_new !!}
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <label for="pin_code" class="col-sm-5 col-form-label">Pin Code</label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="pin_code" name="pin_code"
                                                maxlength="6">
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <label for="contact_mail" class="col-sm-5 col-form-label">
                                            Contact Mail ID <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-sm-7">
                                            <input type="email" class="form-control" id="contact_mail"
                                                name="contact_mail" required>
                                            <div class="invalid-feedback">Email format must be example123@gmail.com
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <label for="active" class="col-sm-5 col-form-label">Active</label>
                                        <div class="col-sm-7">
                                            <select class="form-select" id="active" name="active" disabled>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="text-center mt-4">
                                <button type="button" class="btn btn-success me-2 newaddress_save">Add Customer
                                    Site</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <input type='hidden' class='sche_check' value='0'>


        @endsection
        @push('scripts')


        <script>
            function example() {
                $("#file_choosen").css({
                    "border-color": "rgb(20, 46, 120)",
                    "border-width": "1px",
                    "border-style": "solid"
                });
            }


            $('#savestatus').val('');
            $(document).ready(function() {

                /* Purpose For Price not Assign POPup From Enquiry Products*/

                <?php
                if (isset($quotecount) && $quotecount != 0) {
                ?>

                    setTimeout(function() {
                        swal({
                            title: "Price not assigned for {{$quotecount}} Products",
                            text: "You want to add price for this product",
                            type: "warning",
                            showCancelButton: !0,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Yes",
                            cancelButtonText: "No",
                            closeOnCancel: !1
                        }, function(e) {
                            if (e == true) {
                                var id = $(".pricelist_id").val();
                                var url = "{{ URL::to('salespricelistedit') }}/" + id;
                                //console.log(url);
                                window.open(url);

                            } else {
                                $('.apply').css('display', 'none');
                                swal("Cancelled");
                            }
                        });
                        $('.apply').css('display', 'none');
                    }, 500);
                <?php
                }

                ?>
            });
            /*End*/

            var ordertype = $('.order_type_id').val();
            if (ordertype == 'LABOUR' || ordertype == 'SAMPLE') {
                $('.bulk_unit_price').attr('readonly', false);
                $('.bulk_unit_price').attr('required', false);
                $('.sample').css("pointer-events", "auto");
            } else {
                $('.bulk_unit_price').attr('readonly', true);
                $('.bulk_unit_price').attr('required', true);
                $('.sample').css("pointer-events", "none");
            }

            <?php if ($row['order_type_id'] == "SAMPLE") { ?>
                $('.sample').css("pointer-events", "auto");
            <?php } ?>

            <?php if ($row['order_type_id'] == "EXPORT" || $row['order_type_id'] == "EXPORT SAMPLE") { ?>
                $('.proforma_invoice').css("pointer-events", "none");
            <?php } ?>



            <?php if ($row['source'] == "SAMPLE") { ?>
                $('.priceid').css('pointer-events', 'auto');
            <?php } else { ?>
                $('.priceid').css('pointer-events', 'none');
            <?php } ?>
            /*end*/


            <?php if ($row['order_type_id'] != "SAMPLE") { ?>
                $('.hsn,.tax').css("pointer-events", "auto");
                $('.pricelist_id,.customer_id').attr('required', true);

            <?php } else { ?>
                $('.hsn,.tax').css("pointer-events", "none");
                $('.bulk_discount_amount').attr('readonly', true);
                $('.bulk_discount_percentage,.bulk_discount_amount,.bulk_tax_amount,.bulk_line_total').val(0);
                $('.pricelist_id,.customer_id').attr('required', false);
                $('.cusread').css("pointer-events", "none");
            <?php } ?>

            /*Maruthuu purpose for hide product in labour condition*/
            <?php if ($row['order_type_id'] == "STANDARD") { ?>
                $('.pdtdes_div').addClass('hide');
            <?php } else if ($row['order_type_id'] == "") { ?>

                $('.pdtdes_div').addClass('hide');
                $('.bulk_unit_price,.bulk_tax_group_id,.bill_to_address,.ship_to_address,.pricelist_id').removeAttr('required');
                $('.hsn').show();
            <?php }
            if ($row['order_type_id'] == "LABOUR") { ?>
                $('.hsn').hide();
            <?php } else { ?>

            <?php } ?>
            /*End*/


            <?php if ($row['order_type_id'] == "STANDARD" || $row['order_type_id'] == "SAMPLE") { ?>

                $('.bulk_product_id').attr("required", true);
                $('.bulk_uom_code_id').attr("required", true);

            <?PHP } else { ?>
                $('.bulk_product_id').removeAttr("required", false);
                $('.bulk_uom_code_id').removeAttr("required", false);
            <?php } ?>

            $('.bulk_uom_code_id0').select2({
                readonly: true
            });


            $(document).on('change', '.GetFileSizeNameAndType', function() {

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
                            document.getElementById('fp').innerHTML +
                            '<tr><td><span class="note" ><br /> File:<span class="files">' + fi.files.item(i).name + '</span>&nbsp;<img src="{{URL::to("")}}/images/cancel.png" class="delete_user"></span></td></tr>';
                    }
                }

            });


            /**  purpose tax for other charges **/
            $(document).on('click', '.packingtax', function() {
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
                var text_data = `{!! $tax_group_id_pop!!}`;
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
    <div class="col-md-4">
      <label class="form-label fw-bold">${type}</label>
      <input type="text" class="form-control extracharge${type_id}" value="">
    </div>

    <!-- Tax Group -->
    <div class="col-md-4">
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



            /**********Up/down/left/right arrow navigation start*******/
            $('input').keyup(function(e) {
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
            /**********Up/down/left/right arrow navigation end *******/

            <?php if ($return_url == "soorderapproved") { ?>
                $('.datehide,.cash_discount_type,.schemes_type').css('pointer-events', 'none');
            <?php } else { ?>
                $('.datehide').css('pointer-events', '');
            <?php } ?>

            $(document).on('click', '.taxchargesave', function() {
                var type = $(this).val();
                var taxgrp = $('.tax_details' + type + ' option:selected').attr('data-display');
                var taxgrp_v = $('.tax_details' + type + ' option:selected').val();
                var charge = parseFloat($('.extracharge' + type).val());
                taxgrp = taxgrp ? taxgrp : 0;
                var amount = parseFloat((charge) * taxgrp / 100).toFixed("{{\Session::get('decimal')}}");

                charge = isNaN(charge) ? '' : charge;
                if (charge == '') {
                    showCustomAlert("Please fill Amount", "info");
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
                    }
                    if (type == "5") {
                        $('.other_frieght_amount').val(a_c);
                        $('.other_frieght_amount_tax').val(tax_group_value);
                    }
                    $('#taxModal').modal('hide');
                } else {
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

            });


            // Use the PHP-provided decimal places
            var decimal = '<?php echo \Session::get('decimal'); ?>';

            // ------ Row calculator (call this whenever qty/price/discount/tax changes) ------
            function recalcRow($row) {
                // Grab values from this row
                var unitPrice = parseFloat($row.find('.bulk_unit_price').val());
                var qty = parseFloat($row.find('.bulk_qty').val());
                var discPerc = parseFloat($row.find('.bulk_discount_percentage').val());
                var discAmtIn = parseFloat($row.find('.bulk_discount_amount').val());
                var taxOpt = $row.find('.bulk_tax_group_id option:selected');
                var taxPct = parseFloat(taxOpt.attr('data-display')); // your template uses data-display
                var taxEx = ($row.find('.bulk_tax_excemption').val() || '').toUpperCase(); // Yes/No

                // Normalize NaNs
                unitPrice = isNaN(unitPrice) ? 0 : unitPrice;
                qty = isNaN(qty) ? 0 : qty;
                discPerc = isNaN(discPerc) ? 0 : discPerc;
                discAmtIn = isNaN(discAmtIn) ? 0 : discAmtIn;
                taxPct = isNaN(taxPct) ? 0 : taxPct;

                // Extended amount
                var amount = unitPrice * qty;

                // Discount: if user typed Discount Amount, prefer it; otherwise compute from percentage
                var discAmt = discAmtIn > 0 ? discAmtIn : (amount * discPerc / 100);

                // Subtotal before tax
                var net = amount - discAmt;
                net = net < 0 ? 0 : net;

                // Tax
                var tax = (taxEx === 'YES') ? 0 : (net * taxPct / 100);

                // Line total
                var lineTotal = net + tax;

                // Write back (respecting decimals)
                $row.find('.bulk_discount_amount').val(discAmt.toFixed(decimal));
                $row.find('.bulk_tax_amount').val(tax.toFixed(decimal));
                $row.find('.bulk_line_total').val(lineTotal.toFixed(decimal));
            }

            // ------ Header totals recompute ------
            function recalcHeaderTotals() {
                var sumLineTotals = 0;
                var sumTax = 0;
                var sumQty = 0;
                var charges = 0;

                // Charges total (your other handler uses ".charges")
                $('.charges').each(function() {
                    var v = parseFloat($(this).val());
                    charges += isNaN(v) ? 0 : v;
                });

                $('.bulk_line_total').each(function() {
                    var v = parseFloat($(this).val());
                    sumLineTotals += isNaN(v) ? 0 : v;
                });

                $('.bulk_tax_amount').each(function() {
                    var v = parseFloat($(this).val());
                    sumTax += isNaN(v) ? 0 : v;
                });

                $('.bulk_qty').each(function() {
                    var v = parseFloat($(this).val());
                    sumQty += isNaN(v) ? 0 : v;
                });

                // Subtotal (sum of lines without tax) if needed:
                // var subtotal = (sumLineTotals - sumTax);

                // Your previous logic adds charges to tax and to overall total
                var orderTax = (sumTax + charges);
                var orderTotal = (sumLineTotals + charges);

                // Guard / format
                orderTax = isNaN(orderTax) ? 0 : parseFloat(orderTax).toFixed(decimal);
                orderTotal = isNaN(orderTotal) ? 0 : parseFloat(orderTotal).toFixed(decimal);

                // Write header fields/spans
                $('#order_tax').val(orderTax);
                $('#order_total').val(orderTotal);
                $('#qty_total').val(sumQty);
                $('#balance_amount').val(orderTotal);

                $('.tax_span').html(orderTax);
                $('.order_span').html(orderTotal);
                $('.qty_span').html(sumQty);
            }

            // ------ Public API you can call: calc_by_index replaced with calc_by_row ------
            function calc_by_row(triggerEl) {
                var $row = $(triggerEl).closest('tr');
                recalcRow($row);
                recalcHeaderTotals();
            }

            // If you prefer to keep the same function name, you can keep this thin shim:
            function calc_by_index(index) {
                // Find the row by index (0-based) within tbody and recalc
                var $row = $('.clone_lines_body tr').eq(index);
                if ($row.length) {
                    recalcRow($row);
                    recalcHeaderTotals();
                }
            }

            // ------ Suggested bindings (examples) ------
            // Recalculate when these fields change within any row
            $(document).on('input change', '.bulk_qty, .bulk_unit_price, .bulk_discount_percentage, .bulk_discount_amount, .bulk_tax_group_id, .bulk_tax_excemption', function() {
                calc_by_row(this);
            });

            // Recalculate totals when charges change
            $(document).on('input change', '.charges', function() {
                recalcHeaderTotals();
            });

            // Keep your existing delete handler OUTSIDE the calculator
            $(document).on('click', '.delete_user', function() {
                var quote_hdr = '{{$row["sales_hdr_id"]}}';
                if (quote_hdr != '') {
                    var existing_value = $('#existing_file').val();
                    var delete_value = $(this).attr('data-value');
                    removeValue(existing_value, delete_value);
                }
                $(this).closest('tr').remove();
                recalcHeaderTotals(); // keep header in sync after removal
            });

            // Function to update line numbers
            function updateLineNumbers() {
                $('.clone_lines_body tr').each(function(index) {
                    $(this).find('.bulk_line_no').val(index + 1);
                });
            }

            // Function to initialize select2 with proper dropdown positioning
            function initSelect2($row) {
                $row.find('select.select2').each(function() {
                    $(this).select2({
                        width: '100%',
                        dropdownParent: $(this).closest('td')
                    });
                });
            }

            function reinitSelect2($context) {
    // Destroy existing Select2 if already applied
    $context.find('select').each(function () {
        if ($(this).hasClass('select2-hidden-accessible')) {
            $(this).select2('destroy');
        }
    });

    // Re-initialize Select2
    $context.find('select').select2({
        width: '100%'
    });
}


// Add row - Clone from FIRST row to get all dropdown options
$(document).on('click', '.add-row', function() {

    const $tbody   = $('.clone_lines_body');
    const $firstRow = $tbody.find('tr:first');
    const $lastRow  = $tbody.find('tr:last');

    const promisedDate = $lastRow.find('.bulk_delivery_date').val();
    const altDate      = $lastRow.find('.bulk_promised_alternate_date').val();

    var isSample = "{{ $row['source'] }}" === "SAMPLE";

    // IMPORTANT: destroy select2 on source rows before cloning (SAMPLE fix)
    if (isSample) {
        reinitSelect2($lastRow); // ensure clean state
    }

    const $newRow = $firstRow.clone(false, false);

    /* ---------- CLEAR INPUTS ---------- */
    $newRow.find('input').not('.bulk_line_no').val('');
    $newRow.find('.bulk_sales_line_id').val('');

    /* ---------- RESTORE DATE VALUES ---------- */
    $newRow.find('.bulk_delivery_date').val(promisedDate);
    $newRow.find('.bulk_promised_alternate_date').val(altDate);

    /* ---------- CLEAN SELECT2 ---------- */
    $newRow.find('.select2-container').remove();
    $newRow.find('span.select2').remove();

    $newRow.find('select').each(function() {
        $(this)
            .removeAttr('data-select2-id')
            .removeClass('select2-hidden-accessible')
            .removeAttr('aria-hidden')
            .removeAttr('tabindex')
            .removeData()
            .off();
    });

    $newRow.find('option').removeAttr('data-select2-id');

    /* ---------- PRODUCT DROPDOWN ---------- */
if (isSample) {
    // Get selected price list
    var priceid = $('.pricelist_id').val();

    // Reset first
    $newRow.find('.bulk_product_id').html('<option value="">-- Please Select --</option>');

    if (priceid) {
        var url = "{{ URL::to('getpriceproduct') }}/" + priceid + "/0?condition=soorder";

        $.get(url, function(htmlOptions) {
            $newRow.find('.bulk_product_id').html(htmlOptions);

            // Re-init select2 after loading options
            initSelect2($newRow);
        });
    }
} else {
    // Normal flow: load from hidden template
    var allProductsHtml = $('#all_products_template').html();
    if (allProductsHtml) {
        $newRow.find('.bulk_product_id').html(allProductsHtml);
    }
}


    /* ---------- OTHER DROPDOWNS ---------- */
    var allUomHtml = $('#all_uom_template').html();
    if (allUomHtml) {
        $newRow.find('.bulk_uom_code_id').html(allUomHtml);
    }

    var allTaxHtml = $('#all_tax_groups_template').html();
    if (allTaxHtml) {
        $newRow.find('.bulk_tax_group_id').html(allTaxHtml);
    }

    $newRow.find('.bulk_part_no').html('<option value="">--Please Select--</option>');
    $newRow.find('.bulk_hsn_code').html('<option value="">--Please Select--</option>');
    $newRow.find('.bulk_tax_excemption').val('No');

    /* ---------- FIX DUPLICATE IDs ---------- */
    $newRow.find('[id]').each(function() {
        this.id = this.id + '_' + Date.now();
    });

    /* ---------- APPEND ---------- */
    $tbody.append($newRow);

    /* ---------- REINIT SELECT2 CLEANLY ---------- */
    reinitSelect2($newRow);

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

            $(document).ready(function() {



                <?php if ($pageMethod == "soorderapproved" || $pageMethod == "socancellation") { ?>
                    $('.additem, .remove, .bulk_delivery_date, .disdspnone').css('pointer-events', 'none');
                    $('#choosefile, .delete_user').css('pointer-events', 'none');
                <?php } ?>

                // Your own initializer
                if (typeof changeClassfields === 'function') {
                    changeClassfields();
                }

                <?php if ($row['order_type_id'] == "LABOUR") { ?>
                    $('.hide_div').addClass('hide');
                <?php } ?>

                // Session-driven decimal places
                var decimalpoint = '<?php echo \Session::get('decimal'); ?>';
                $('.decimal_point').val(decimalpoint);

                <?php if ($return_url == "soorderapproved" || $return_url == "socancellation") { ?>
                    $('.rdonlydiv').css('pointer-events', 'none');
                    $('.refbtnhide').hide();
                <?php } ?>

                // Readonly fields
                $('.bulk_qoh_qty').prop('readonly', true);

                // Pre-fill org/user
                var organization = '<?php echo \Session::get('organization'); ?>';
                $('.organization_id').val(organization).trigger('change');

                var user = '<?php echo \Session::get('id'); ?>';
                $('.created_by').val(user).trigger('change');

                // Mirror selected labels into spans
                $(".create_by").text($('.created_by option:selected').text());
                $(".source_span").text($('.source option:selected').text());
                $(".ordertype_span").text($('.order_type_id option:selected').text());
                $('.org').text($('.organization_id option:selected').text());


                // SAMPLE source: load employee address
                <?php if ($row['source'] == "SAMPLE") { ?>
                    $(document).on('change', '.employee_id', function() {
                        $('.cusreademp').css('pointer-events', 'none');
                        var emp = $(this).val();
                        if (!emp) {
                            $('.ship_to_address, .bill_to_address').val('');
                            $('.ship_to_address_id, .bill_to_address_id').val('');
                            return;
                        }
                        var url = "{{ URL::to('employeeaddress') }}/" + emp;
                        $.get(url, function(data) {
                            if (data) {
                                $('.ship_to_address, .bill_to_address').val(data);
                                $('.ship_to_address_id, .bill_to_address_id').val(emp);
                            } else {
                                $('.ship_to_address, .bill_to_address').val('');
                                $('.ship_to_address_id, .bill_to_address_id').val('');
                            }
                        });
                    });
                <?php } ?>

                // Currency & conversion: merge into one handler
                $(document).on('change', '.invoice_currency', function() {
                    // reset rate to 1 immediately
                    $('.currency_rate').val('1');

                    var curr = $(this).val();
                    if (!curr) {
                        $('.con_exc_rate').val('');
                        return;
                    }
                    var url = "{{ URL::to('conversionexchangecurrency') }}/" + curr;
                    $.get(url, function(data) {
                        data = $.trim(data);
                        console.log('conversion rate:', data);
                        $('.con_exc_rate').val(data);
                    });
                });

                // Tax group select looks readonly in UI
                $('.tax').css('pointer-events', 'none');


                $(document).on('keypress', '.bulk_unit_price,.bulk_qty,.bulk_discount_percentage,.packaging_charges,.pin_code,.insurance_charges,.transport_charges,.other_frieght_amount,.contact_number,.other_tax_amount,.bulk_discount_amount,.extracharge', function(ev) {
                    var regex = new RegExp("^[0-9.]+$");
                    var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                    if (regex.test(str)) {
                        return true;
                    }
                    ev.preventDefault();
                    return false;
                });

                $('.bulk_unit_price,.bulk_qty,.bulk_discount_percentage,.bulk_discount_amount').bind("cut copy paste", function(e) {
                    e.preventDefault();
                });






                <?php if ($pageMethod != 'soorderapproved' && $pageMethod != "socancellation") { ?>
                    // Customer change - load addresses, defaults, products
                    $(document).on('change', '.customer_id', function() {
                        var customer_id = $('.customer_id').val();
                        if (!customer_id) {
                            // clear dependent fields
                            $('.ar_frieghtcarriers_hdr_id, .ar_payment_term_id, .ar_payment_method_id, .salesperson_id, .discount_id')
                                .val(null).trigger('change');
                            $(".pricelist_id").val(null).trigger('change');
                            $('.bill_to_address, .ship_to_address, .bill_to_address_id, .ship_to_address_id').val('');
                            return;
                        }

                        var url = "{{ URL::to('sodispatchaddress') }}/" + customer_id + '?pid=0&condition=soorder';
                        $.get(url, function(data) {
                            // Guard against missing/empty values
                            var bill = (data && data[0]) ? $.trim(data[0]) : '';
                            var ship = (data && data[1]) ? $.trim(data[1]) : '';

                            if (ship && bill) {
                                var s = ship.split('~');
                                $('.ship_to_address').val(s[0]);
                                $('.ship_to_address_id').val(s[1]);

                                var b = bill.split('~');
                                $('.bill_to_address').val(b[0]);
                                $('.bill_to_address_id').val(b[1]);
                            } else if (ship && !bill) {
                                var s2 = ship.split('~');
                                $('.ship_to_address').val(s2[0]);
                                $('.ship_to_address_id').val(s2[1]);
                                showCustomAlert('Please Assign Bill To Address !!!', 'info');
                                $('.bill_to_address, .bill_to_address_id').val('');
                            } else if (!ship && bill) {
                                showCustomAlert('Please Assign Ship To Address !!!', 'info');
                                var b2 = bill.split('~');
                                $('.bill_to_address').val(b2[0]);
                                $('.bill_to_address_id').val(b2[1]);
                                $('.ship_to_address, .ship_to_address_id').val('');
                            } else {
                                $('.bill_to_address, .ship_to_address, .bill_to_address_id, .ship_to_address_id').val('');
                                showCustomAlert('Please Assign Ship To and Bill To Address !!!', 'info');
                            }

                            // Defaults (price list, freight, payment terms/method, salesperson, discount)
                            if (data && Number(data.pricelist_id) !== 0) {
                                $(".pricelist_id").val(data.pricelist_id).trigger('change');
                                $('.ar_frieghtcarriers_hdr_id').val(data.ar_frieghtcarriers_hdr_id).trigger('change');
                                $('.ar_payment_term_id').val(data.default_payment_terms_id).trigger('change');
                                $('.ar_payment_method_id').val(data.default_payment_method_id).trigger('change');
                                $('.salesperson_id').val(data.sales_person).trigger('change');
                                $('.discount_id').val(data.ar_discount_hdr_id).trigger('change');
                            } else {
                                showCustomAlert('Please Select Price List !!!', 'info');
                                $(".pricelist_id").val(null).trigger('change');
                            }

                            if (data && data.contact_person) {
                                $('.contact_person').val(data.contact_person);
                            }
                            if (data && data.contact_number) {
                                $('.contact_number').val(data.contact_number);
                            }

                            // Populate empty product dropdowns with customer-price-list-specific options (if provided)
                            if (data && data.productid) {
                                $('.bulk_product_id').each(function() {
                                    if (!$(this).val()) {
                                        $(this).html(data.productid).trigger('change');
                                    }
                                });
                            }
                        });
                    });
                <?php } ?>

                <?php if ($row['source'] == "SAMPLE") { ?>
                    // On price list change, reload product options for each row (preserve current selection per row)
                    $(document).on('change', '.pricelist_id', function() {
                        var priceid = $(this).val();
                        if (!priceid) return;

                        var url = "{{ URL::to('getpriceproduct') }}/" + priceid + "/0?condition=soorder";
                        $.get(url, function(htmlOptions) {
                            $('.bulk_product_id').each(function() {
                                var prev = $(this).val();
                                $(this).html(htmlOptions);
                                if (prev) {
                                    $(this).val(prev).trigger('change');
                                }
                            });
                        });
                    });
                <?php } ?>

                // New BILL_TO
                $(document).on('click', '.new_billto', function() {
                    var cid = $('.customer_id').val();
                    if (!cid) return showCustomAlert("Please select Customer", "info");

                    // Reset form
                    setTimeout(function() {
                        $('#newaddress').find(':input')
                            .not(':button, :submit, :reset, :hidden')
                            .val('')
                            .prop('checked', false)
                            .prop('selected', false);
                        $('.city, .country, .state').val(null).trigger('change');
                    }, 0);

                    $('#new_address').modal('show').width("100%");
                    $('.custype').val('BILL_TO');
                });

                // New SHIP_TO
                $(document).on('click', '.new_shipto', function() {
                    var cid = $('.customer_id').val();
                    if (!cid) return showCustomAlert("Please select Customer", "info");

                    $('#newaddress').find(':input')
                        .not(':button, :submit, :reset, :hidden')
                        .val('')
                        .prop('checked', false)
                        .prop('selected', false);
                    $('.city, .country, .state').val(null).trigger('change');

                    $('#new_address').modal('show').width("100%");
                    $('.custype').val('SHIP_TO');
                });

                // Bill-to/Ship-to lookup grid
                $(document).on('click', '.billto', function() {
                    var cid = $('.customer_id').val();
                    var cParts = $.trim($('.customer_id option:selected').text()).split('-');
                    if (!cid) return showCustomAlert("Please select Customer first", "info");

                    $('#customerModal').modal('show').width("100%");
                    var ct = $(this).val(); // "billto" or "shipto"
                    $('.custype').val(ct);

                    var site_type = (ct === "billto") ? "BILL_TO" : "SHIP_TO";

                    // (Optional) fill any header search inputs you still show
                    $("#gs_customer_name").val($.trim(cParts[1] || ''));
                    $("#gs_site_type").val(site_type);
                    $("#gs_customer_number").val($.trim(cParts[0] || ''));
                    $("#gs_customer_name,#gs_site_type,#gs_customer_number").prop('readonly', true);

                    // Filter the clone table instead of jqGrid
                    var $tbl = $('#customerTable');
                    $tbl.find('tbody tr').each(function() {
                        var $tr = $(this);
                        var rowNo = $tr.children('td').eq(0).text().trim();
                        var rowName = $tr.children('td').eq(1).text().trim(); // not used here, but handy
                        var rowSite = $tr.children('td').eq(2).text().trim();
                        var show = (rowSite === site_type && rowNo === $.trim(cParts[0] || ''));
                        $tr.toggle(show);
                    });

                    // Clicking a visible row picks the address and closes the modal
                    $('#customerTable')
                        .off('click.rowpick')
                        .on('click.rowpick', 'tbody tr:visible', function() {
                            var $tr = $(this);
                            var addrId = $tr.children('td').eq(3).text().trim();
                            var addrText = $tr.children('td').eq(4).text().trim();

                            if (site_type === 'BILL_TO') {
                                $('.bill_to_address_id').val(addrId);
                                $('.bill_to_address').val(addrText);
                            } else {
                                $('.ship_to_address_id').val(addrId);
                                $('.ship_to_address').val(addrText);
                            }
                            $('#customerModal').modal('hide');
                        });
                });


                // Email validation
                function ValidateEmail(email) {
                    var expr = /^([\w.-]+)@((\[[0-9]{1,3}(\.[0-9]{1,3}){2}\.)|(([\w-]+\.)+))([a-zA-Z]{2,}|[0-9]{1,3})(\]?)$/;
                    return expr.test(email);
                }

                // Save new address (modal)
                $(document).on('click', '.newaddress_save', function() {
                    var customer_id = $('.customer_id').val();
                    var form = $('#newaddress');
                    validationrule('newaddress');
                    form.parsley().validate();

                    var emailOk = ValidateEmail($("#contact_mail").val());
                    if (!form.parsley().isValid()) return;

                    if (!emailOk) {
                        return showCustomAlert('Please enter valid mail (ex: example123@gmail.com)', 'info');
                    }

                    var url = "{{ URL::to('newshiptocustomer') }}/" + customer_id;
                    var formdata = form.serialize();
                    $.post(url, formdata, function(data) {
                        // Expect data like [id, address]
                        var type = $('.custype').val();
                        if (data && $.trim(data[0]) !== '') {
                            if (type === "SHIP_TO") {
                                $('.ship_to_address_id').val(data[0]);
                                $('.ship_to_address').val(data[1]);
                                showCustomAlert("Ship to Address Saved Successfully", "success");
                            } else if (type === "BILL_TO") {
                                $('.bill_to_address_id').val(data[0]);
                                $('.bill_to_address').val(data[1]);
                                showCustomAlert("Bill to Address Saved Successfully", "success");
                            }
                        }
                        $('#new_address').modal('hide');
                    });
                });

                <?php if ($pageMethod == 'soorderapproved' || $pageMethod == 'salesorderfromqo') { ?>
                    $('.hsn').css('pointer-events', 'none');
                <?php } ?>

                // Arrow key navigation across inputs
                $('input').on('keyup', function(e) {
                    var $td = $(this).closest('td'),
                        idx = $td.index();
                    if (e.which === 39) { // right
                        $td.next().find('input').first().focus();
                    } else if (e.which === 37) { // left
                        $td.prev().find('input').first().focus();
                    } else if (e.which === 40) { // down
                        $(this).closest('tr').next().find('td:eq(' + idx + ') input').first().focus();
                    } else if (e.which === 38) { // up
                        $(this).closest('tr').prev().find('td:eq(' + idx + ') input').first().focus();
                    }
                });

                <?php if ($return_url != 'soordercreate') { ?>
                    // Lock down fields on view-only pages
                    $('input').prop('readonly', true);
                    $('select').prop('disabled', true); // selects don't support readonly
                    $(".remarks").prop('readonly', false);
                <?php } ?>

                // Reference viewer
                $(document).on('click', '.refview', function() {
                    var quoteid = $('.reference_id').val();
                    if (!quoteid) return;

                    <?php if ($row['source'] == "ENQUIRY") { ?>
                        window.open("{{ URL::to('salesinquiryview') }}/" + quoteid);
                    <?php } ?>
                    <?php if ($row['source'] == "QUOTE") { ?>
                        window.open("{{ URL::to('soquoteview') }}/" + quoteid + "/quote");
                    <?php } ?>
                });

                // Approve / Reject buttons
                $(document).on('click', '.approve', function() {
                    $("#order_status_id").val("APPROVED").trigger('change');
                });
                $(document).on('click', '.reject', function() {
                    $("#order_status_id").val("REJECT").trigger('change');
                });

                // Prevent accidental edits on some dropdowns (if not already disabled)
                $('.bulk_uom_code_id').on('amodaldestroy', function(e) {
                    e.stopPropagation();
                });


                <?php if ($pageMethod != "soorderapproved") {
                    if ($row['order_type_id'] != "SAMPLE") { ?>

                        $(document).on('change', '.bulk_tax_excemption', function(event) {
                            var $row = $(this).closest('tr');
                            var taxval = $(this).val(); // "Yes" / "No"
                            if (taxval === "Yes") {
                                // Force Tax Group = 8 when exempted
                                $row.find('.bulk_tax_group_id').val('8').trigger('change');
                            } else {
                                // Re-evaluate HSN to fetch tax for non-exempt
                                $row.find('.bulk_hsn_code').trigger('change');
                            }
                        });

                        // re-entry guard to avoid recursive change triggers
                        let _suppressProductChange = false;

                        $(document).on('change', '.bulk_product_id', function(event) {
                            if (_suppressProductChange) return;

                            const $row = $(this).closest('.clone'); // current line
                            const pid = $.trim($(this).val());
                            const type = 'so';
                            const plid = $('.pricelist_id').val();
                            const cid = $('.customer_id').val();
                            const cusid = $('.bill_to_address_id').val();

                            // PREREQS - do NOT trigger 'change' again here
                            if (!cid) {
                                showCustomAlert('Please Select Customer', 'info');
                                _suppressProductChange = true;
                                $(this).val(null).trigger('change.select2'); // update Select2 UI only
                                _suppressProductChange = false;
                                return; // no recursion
                            }
                            if (!cusid) {
                                showCustomAlert('Please Select Bill to Address', 'info');
                                _suppressProductChange = true;
                                $(this).val(null).trigger('change.select2');
                                _suppressProductChange = false;
                                return;
                            }
                            if (!plid || !pid) {
                                rowdataEmpty($row);
                                return;
                            }

                            const url = "{{ url::to('productdetails_so') }}/" + pid + "/" + plid + "/" + cusid + "/" + type;

                            $.get(url, function(data) {
                                // Build HSN/SAC condition from response
                                const hsnid = data['multihsn'];
                                let condition;
                                <?php if ($row['order_type_id'] == "STANDARD") { ?>
                                    condition = "classification_name='HSN' and gst_code_hdr_id in(" + hsnid + ")";
                                <?php } else { ?>
                                    condition = "classification_name='SAC' and gst_code_hdr_id in(" + hsnid + ")";
                                <?php } ?>

                                // Populate HSN/SAC for THIS row
                                const hsnSelected = (data.hsn_code || '').toString();
                                const $hsnSelect = $row.find('.bulk_hsn_code'); // get select element in current row

                                if (condition !== "") {
                                    var url = "{{ URL::to('jcomboform') }}" +
                                        "?table=f_gst_code_hdr_t:gst_code_hdr_id:classification_code" +
                                        "&order_by=classification_code asc" +
                                        "&parent=" + encodeURIComponent(condition);

                                    $.ajax({
                                        url: url,
                                        type: "GET",
                                        success: function(data) {
                                            if (typeof data === "string") {
                                                try {
                                                    data = JSON.parse(data);
                                                } catch (e) {
                                                    console.error("Invalid JSON:", data);
                                                    return;
                                                }
                                            }

                                            // Clear and reset dropdown
                                            $hsnSelect.empty().append('<option value="">-- Select HSN Code --</option>');

                                            $.each(data, function(i, item) {
                                                var selected = item.val == hsnSelected ? "selected" : "";
                                                $hsnSelect.append(
                                                    `<option value="${item.val}" ${selected}>${item.option_name}</option>`
                                                );
                                            });

                                            // Reinitialize Select2 if used
                                            $hsnSelect.trigger('change.select2');
                                        },
                                        error: function(xhr, status, error) {
                                            console.error("AJAX Error:", error);
                                        }
                                    });
                                } else {
                                    $hsnSelect.empty().append('<option value="">-- Select HSN Code --</option>');
                                }

                                // UOM
                                $row.find('.bulk_uom_code_id').val(data.uom_code_id).trigger('change');


                                // Manufacturer part no
                                if (data.manufactpartno && data.manufactpartno != 0) {
                                    $row.find('.bulk_part_no').val(data.manufactpartno).trigger('change');
                                } else {
                                    $row.find('.bulk_part_no').val(null).trigger('change');
                                }

                                // Unit price (currency rate + schedule check)
                                if (data.unit_price !== '') {
                                    let price = parseFloat(data.unit_price);
                                    if (isNaN(price)) price = 0;
                                    const schedOff = parseInt($('.sche_check').val(), 10) === 0;

                                    const curr = parseFloat($('.currency_rate').val());
                                    if (!isNaN(curr) && curr !== 0) price = price / curr;

                                    if (schedOff) {
                                        $row.find('.bulk_unit_price').val(price.toFixed(<?php echo (int) \Session::get('decimal'); ?>));
                                    }

                                    // Tax group
                                    $row.find('.bulk_tax_group_id').val(data.tax_group_id).trigger('change');
                                    $row.find('.tax, .uom').css('pointer-events', 'none');
                                } else {
                                    $row.find('.bulk_unit_price').val('');
                                }

                                // Warnings (no triggers here)
                                if ((data.unit_price == 0 || data.unit_price === '') && (data.tax_group_id === "")) {
                                    showCustomAlert('Please Assign Unit Price and tax group for this Product', 'info');
                                } else if (data.unit_price == 0 || data.unit_price === '') {
                                    if (plid != '176') {
                                        showCustomAlert('Please Assign Unit Price for this Product', 'info');
                                        showCustomAlert('Pricelist Date has been Expired for this Product', 'info');
                                    }
                                } else if (data.tax_group_id === "") {
                                    showCustomAlert('Please assign tax group for this product', 'info');
                                }

                                // QOH
                                const qoh = parseFloat(data.qoh_qty);
                                $row.find('.bulk_qoh_qty').val(isNaN(qoh) ? 0 : qoh);

                                // Recalculate this row + header totals
                                if (typeof calc_by_row === 'function') {
                                    calc_by_row($row.find('.bulk_qty'));
                                } else if (typeof calc_by_index === 'function') {
                                    calc_by_index($row.index());
                                }
                            });
                        });


                    <?php } else { ?>

                        $(document).on('change', '.bulk_product_id', function(event) {
                            var $row = $(this).closest('.clone');
                            var pid = $(this).val();
                            var priceid = $('.pricelist_id').val();

                            if (!priceid) {
                                showCustomAlert('Please Select Pricelist', 'info');
                                $(this).val(null).trigger('change');
                                return;
                            }
                            if (!pid) return;

                            var url = "{{ URL::to('poenquiryuom') }}/" + pid;
                            $.get(url, function(data) {
                                data = $.trim(data);
                                $row.find('.bulk_uom_code_id').val(data).trigger('change');
                            });
                        });

                <?php }
                } ?>

                $(document).on('change', '.bulk_hsn_code', function() {
                    var $row = $(this).closest('tr');
                    var hsnid = $(this).val();
                    var suppsite = $('.bill_to_address_id').val();
                    var m_type = "Sales";
                    var taxval = ($row.find('.bulk_tax_excemption').val() || '');

                    if (!hsnid || hsnid == 0 || !suppsite) return;

                    if (!taxval) {
                        showCustomAlert('Please select Tax Excemption', 'info');
                        return;
                    }

                    if (taxval === "Yes") {
                        $row.find('.bulk_tax_group_id').val('8').trigger('change'); // exempt group
                        return;
                    }

                    var url = "{{ URL::to('taxdetails') }}/" + hsnid + "/" + suppsite + "/" + m_type;
                    $.get(url, function(data) {
                        if (!data) return;

                        if (data['tax_group_id'] == 0) {
                            var tag = $.trim(data['tax_group_id_expiry']);
                            if (tag === "expiry") {
                                showCustomAlert('Tax Group expired for this product', 'info');
                            } else if (tag === "location") {
                                showCustomAlert('Tax not assigned for this Location', 'info');
                            } else {
                                showCustomAlert('Tax Group not assigned for this product', 'info');
                            }
                        } else {
                            $row.find('.bulk_tax_group_id').val(data.tax_group_id).trigger('change');
                        }
                    });
                });

                // --- Row reset helper ---
                function rowdataEmpty($row) {
                    $row.find('.bulk_uom_code_id').val(null).trigger('change');
                    $row.find('.bulk_unit_price').val('');
                    $row.find('.bulk_tax_group_id').val(null).trigger('change');
                    $row.find('.bulk_qoh_qty').val('');
                    $row.find('.bulk_qty').val('');
                    $row.find('.bulk_discount_percentage').val('');
                    $row.find('.bulk_discount_amount').val('');
                    $row.find('.bulk_hsn_code').val(null).trigger('change');
                    $row.find('.bulk_qty').trigger('change');
                }

                $('.source_div,.organization_id_div').css('pointer-events', 'none');

                $(document).on('change', '.pricelist_id', function() {
                    var pricelistid = $('.pricelist_id').val();
                    var customer_id = $('.customer_id option:selected').val();

                });


                $(".cancelled").click(function(e) {
                    $("#order_status_id").val("CANCELLED").change();
                    var cancelled = $(this).val();
                    var id = $(".sales_hdr_id").val();

                    e.preventDefault();
                    var indexurl = "{{URL::to('socancellation')}}";
                    var url = "{{URL::to('socancellationcancel')}}/" + id + "/" + cancelled;
                    $.get(url, function(data) {
                        var status = data.status;
                        var msg = data.message;
                        showCustomAlert(msg, status);
                        window.location.href = indexurl;
                    });

                });

                $('.req').hide();

                $(document).on('click', '.saveform', function() {
                    var btnval = $(this).val();
                    console.log(btnval);
                    if (btnval == 'APPLYCHANGES') {
                        $('.remarks').attr('required', false);
                        $("#order_status_id").val('DRAFT');
                    } else if (btnval == 'REJECT') {
                        $('.req').show();
                        $('.remarks').attr('required', true);
                        $("#order_status_id").val('REJECTED');
                    } else if (btnval == 'APPROVED') {
                        $('.remarks').attr('required', false);
                        $("#order_status_id").val('APPROVED');
                    } else {
                        $('.remarks').attr('required', false);
                        var emp_group = "{{\Session::get('groupname')}}";
                        if (emp_group == "Marketing Personnel") {
                            $("#order_status_id").val('DRAFT');
                        } else {
                            $("#order_status_id").val('INITIATED');
                        }
                    }
                    var order_type = "{{$row['source']}}";

                    if (btnval == 'APPLYCHANGES')
                        var savestatus = 'DRAFT';
                    else if (btnval == 'REJECT')
                        var savestatus = 'REJECT';
                    else if (btnval == 'APPROVED')
                        var savestatus = 'APPROVED';

                    else if (btnval == 'SAVE' || btnval == 'SAVENEW')
                        var savestatus = 'SAVE';

                    var show_div = '<?php echo $show_div; ?>';
                    if (show_div == "1")
                        $('#panel_add').trigger('click');

                    $('#savestatus').val(savestatus);

                    <?php if ($row['source'] == "SAMPLE") { ?>
                        $('.bulk_tax_group_id').attr('required', false);
                        var cus = $('.customer_id').val();
                        if (cus == "") {
                            $('.bill_to_address,.ship_to_address').attr('required', false);
                        } else {
                            $('.bill_to_address,.ship_to_address').attr('required', true);
                        }
                    <?php } else { ?>
                        qtyrequired();
                    <?php } ?>

                    var url = "{{URL::to('saveorder')}}";
                    var red_url = "{{URL::to('soorder')}}";
                    var create_url = "{{URL::to('soordercreate')}}/0/" + order_type;
                    validationrule('soorder_form');

                    var ordertype = $('.order_type_id').val();
                    if (btnval != 'APPLYCHANGES') {
                        var form = $('#soorder_form');
                        form.parsley().validate();
                        if (form.parsley().isValid()) {
                            var $btn = $(this);
                            $btn.prop('disabled', true);

                            var customer = $('.customer_id').val();
                            var employee = $('.employee_id').val();
                            if (customer != "" || employee != "") {
                                var form_data = new FormData(document.getElementById('soorder_form'));

                                $.ajax({
                                    url: "{{URL::to('saveorder')}}",
                                    type: "POST",
                                    data: form_data,
                                    enctype: 'multipart/form-data',
                                    processData: false, // tell jQuery not to process the data
                                    contentType: false, // tell jQuery not to set contentType
                                    async: true,
                                    xhr: function() {
                                        var xhr = $.ajaxSettings.xhr();
                                        if (xhr.upload) {
                                            xhr.upload.addEventListener('progress', function(event) {
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
                                }).done(function(data) {
                                    var status = data.status;
                                    var msg = data.message;
                                    var id = data.id;
                                    var auto_no = data.auto_no;
                                    var edit_url = "{{URL::to('soordercreate')}}/" + id;
                                    if (btnval != 'SAVE' && btnval != 'DRAFT' && btnval != "APPROVED" && btnval != "REJECT") {
                                        showCustomAlert(msg, status);
                                        setTimeout(function() {

                                            $('.ajaxLoading').hide();
                                            window.location.href = create_url;
                                        }, 1500);
                                    } else if (btnval == "APPROVED" || btnval == "REJECT") {
                                        showCustomAlert(msg, status);
                                        setTimeout(function() {
                                            window.location.href = "{{URL::to('salesorderapproval')}}";
                                        }, 1500);

                                    } else {
                                        showCustomAlert(msg, status);
                                        setTimeout(function() {
                                            window.location.href = red_url;
                                        }, 1500);
                                    }
                                });
                            } else {

                                showCustomAlert("Please choose customer or employee..", "info");

                            }
                        }
                    } else {

                        var form_data = new FormData(document.getElementById('soorder_form'));
                        $.ajax({
                            url: url,
                            type: "POST",
                            data: form_data,
                            enctype: 'multipart/form-data',
                            processData: false, // tell jQuery not to process the data
                            contentType: false, // tell jQuery not to set contentType
                            async: true,
                            xhr: function() {
                                var xhr = $.ajaxSettings.xhr();
                                if (xhr.upload) {
                                    xhr.upload.addEventListener('progress', function(event) {
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
                        }).done(function(data) {
                            var status = data.status;
                            var msg = data.message;
                            var id = data.id;
                            var edit_url = "{{URL::to('soordercreate')}}/" + id;

                            showCustomAlert(msg, status);
                            setTimeout(function() {
                                location.reload();
                                window.location.href = edit_url;
                            }, 1500);
                        });
                    }
                });


                // how many decimals to show
                var DEC = parseInt('<?php echo \Session::get('decimal'); ?>', 10) || 2;

                // helpers
                function toNum(v) {
                    v = parseFloat(v);
                    return isNaN(v) ? 0 : v;
                }

                function f2(v) {
                    return (toNum(v)).toFixed(DEC);
                }

                // -------- row calculator --------
                function recalcRow($row) {
                    var qty = toNum($row.find('.bulk_qty').val());
                    var unitPrice = toNum($row.find('.bulk_unit_price').val());
                    var discPerc = toNum($row.find('.bulk_discount_percentage').val());
                    var tradePerc = toNum($('.trade_discount_pre').val());
                    var taxPerc = toNum($row.find('.bulk_tax_group_id option:selected').attr('data-display')); // %
                    // base amount
                    var amt = qty * unitPrice;

                    // discount from discount %
                    var discAmt = (amt * discPerc / 100);

                    // amount after discount
                    var otherTotal = amt - discAmt;

                    // trade discount % (header level)
                    var tradeAmt = (otherTotal * tradePerc / 100);
                    otherTotal = otherTotal - tradeAmt;

                    // tax on net
                    var taxAmt = (otherTotal * taxPerc / 100);

                    // line total = net + tax
                    var lineTotal = otherTotal + taxAmt;

                    // write back
                    $row.find('.bulk_discount_amount').val(f2(discAmt));
                    $row.find('.bulk_tax_amount').val(f2(taxAmt));
                    $row.find('.bulk_line_total').val(f2(lineTotal));
                }

                // -------- header totals --------
                function recalcHeaderTotals() {
                    var sum = 0,
                        sumtax = 0,
                        sumqty = 0,
                        charge = 0;

                    $('.charges').each(function() {
                        charge += toNum($(this).val());
                    });
                    $('.bulk_line_total').each(function() {
                        sum += toNum($(this).val());
                    });
                    $('.bulk_tax_amount').each(function() {
                        sumtax += toNum($(this).val());
                    });
                    $('.bulk_qty').each(function() {
                        sumqty += toNum($(this).val());
                    });

                    var orderTotal = sum + charge;
                    var orderTax = sumtax; // keep tax = sum of line taxes
                    // if charges are taxable in your header view, add them here instead:
                    // orderTax = sumtax + charge;

                    $('#order_total').val(f2(orderTotal));
                    $('#order_tax').val(f2(orderTax));
                    $('#qty_total').val(f2(sumqty));
                    $('.order_span').html(f2(orderTotal));
                    $('.tax_span').html(f2(orderTax));
                    $('.qty_span').html(f2(sumqty));

                    // keep any other mirrored fields you use
                    $('.quote_tax').val(f2(sumtax));
                }

                // -------- your original helpers, fixed to row-scope --------
                function qtyrequired() {
                    $(".bulk_qty").each(function() {
                        var v = toNum($(this).val());
                        if (v === 0) $(this).val('');
                    });
                }

                // When DISCOUNT set changes: apply % to each row and recompute
                $(document).on('change', '.discount_id', function() {
                    var dis_customer = toNum($('.discount_id option:selected').attr('data-display')); // %
                    $(".bulk_product_id").each(function() {
                        var $row = $(this).closest('tr');
                        // set row discount %
                        $row.find('.bulk_discount_percentage').val(dis_customer);
                        // recalc this row
                        recalcRow($row);
                    });
                    // one header recompute at the end
                    recalcHeaderTotals();
                });

                // Recompute on user edits
                $(document).on('keyup change',
                    '.bulk_qty, .bulk_unit_price, .bulk_hsn_code, .bulk_discount_percentage, .bulk_tax_group_id, .bulk_product_id, .charges',
                    function() {
                        var $row = $(this).closest('tr');
                        recalcRow($row);
                        recalcHeaderTotals();
                    }
                );

                // decimals from session
                var DEC = parseInt('<?php echo \Session::get('decimal'); ?>', 10) || 2;

                function num(v) {
                    v = parseFloat(v);
                    return isNaN(v) ? 0 : v;
                }

                function fmt(v) {
                    return num(v).toFixed(DEC);
                }

                // Recalc header totals (same fields you update now)
                function recalcHeaderTotals() {
                    var sum = 0,
                        sumtax = 0,
                        sumqty = 0,
                        charge = 0;

                    $('.charges').each(function() {
                        charge += num($(this).val());
                    });
                    $('.bulk_line_total').each(function() {
                        sum += num($(this).val());
                    });
                    $('.bulk_tax_amount').each(function() {
                        sumtax += num($(this).val());
                    });
                    $('.bulk_qty').each(function() {
                        sumqty += num($(this).val());
                    });

                    var sumall = num(charge) + num(sum);

                    $('#order_tax').val(fmt(sumtax));
                    $('#order_total').val(fmt(sumall));
                    $('#qty_total').val(fmt(sumqty));
                    $('.order_span').html(fmt(sumall));
                    $('.tax_span').html(fmt(sumtax));
                    $('.qty_span').html(fmt(sumqty));

                    // total tax mirror
                    var tax = 0;
                    $('.bulk_tax_amount').each(function() {
                        tax += num($(this).val());
                    });
                    $('.quote_tax').val(fmt(tax));
                }

                // Discount AMOUNT edited - back-calc % and totals for THIS ROW, then header
                $(document).on('keyup change', '.bulk_discount_amount', function() {
                    var $row = $(this).closest('tr');

                    var unitprice = num($row.find('.bulk_unit_price').val());
                    var requiredqty = num($row.find('.bulk_qty').val());
                    var taxgrp = num($row.find('.bulk_tax_group_id option:selected').attr('data-display')); // %
                    var discAmt = num($row.find('.bulk_discount_amount').val());

                    // base amount & percent back-calc (guard against /0)
                    var baseAmt = unitprice * requiredqty;
                    var discPerc = (baseAmt > 0) ? ((discAmt * 100) / baseAmt) : 0;
                    $row.find('.bulk_discount_percentage').val(fmt(discPerc));

                    // After discount
                    var other_total = baseAmt - discAmt;

                    // Trade discount %
                    var trade_dis_pre = num($('.trade_discount_pre').val());
                    var tradeAmt = (other_total * trade_dis_pre) / 100;

                    // After trade discount
                    other_total = other_total - tradeAmt;

                    // Tax on net (after trade)
                    var taxamount = (other_total * taxgrp) / 100;
                    $row.find('.bulk_tax_amount').val(fmt(taxamount));

                    // Line total = net (after trade) + tax
                    var linetot = other_total + taxamount;
                    $row.find('.bulk_line_total').val(fmt(linetot));

                    // Update header totals once
                    recalcHeaderTotals();
                });

                /* deepika: set index for lines (delivery date propagation) */
                var jfmt = "{{ \Session::get('j_date_format') }}"; // kept for compatibility if used elsewhere

                // initialize all line delivery dates from header field (if present)
                var dtInit = $('.delivery_date').val();
                if (dtInit) {
                    $('.bulk_delivery_date').val(dtInit);
                }

                // keep lines in sync when header delivery date changes
                $(document).on('change', '.delivery_date', function() {
                    var dt = $(this).val();
                    $('.bulk_delivery_date').val(dt);
                });

                // Initialize line numbers on page load
                updateLineNumbers();

                // === CASH DISCOUNT TYPE CLICK ===
                $(document).on('click', '.cash_discount_type', function() {
                    let total = 0;

                    $('.bulk_qty').each(function() {
                        const $tr = $(this).closest('tr');
                        const qty = num($(this).val());
                        const price = num($tr.find('.bulk_unit_price').val());
                        const discount = num($tr.find('.bulk_discount_amount').val());
                        total += (qty * price) - discount;
                    });

                    const schemes = $('.cash_discount').val();
                    const url = "{{ URL::to('schemesordercheck') }}";

                    $.get(`${url}?schemes=${schemes}&total=${total}`, function(data) {
                        let discount = 0;

                        if (data.length > 0) {
                            $.each(data, function(_, val) {
                                if (val.schemes_type === 'Discounts') {
                                    const per = num(val.schemes_type_value);
                                    const amount = ((total * per) / 100);
                                    discount += amount;
                                    total -= amount;
                                }
                                // If you plan to handle Amount type later, keep this else-if stub:
                                else if (val.schemes_type === 'Amount') {
                                    // TODO: handle flat amount scheme
                                }
                            });
                        }

                        $('.trade_discount').val(fmt(discount)).trigger('change');
                    });
                });

                // === TRADE DISCOUNT CHANGE ===
                $(document).on('change', '.trade_discount', function() {
                    const val = num($(this).val());
                    let total = 0;

                    $('.bulk_qty').each(function() {
                        const $tr = $(this).closest('tr');
                        const qty = num($(this).val());
                        const price = num($tr.find('.bulk_unit_price').val());
                        const discount = num($tr.find('.bulk_discount_amount').val());
                        total += (qty * price) - discount;
                    });

                    const pre = total > 0 ? (val * 100) / total : 0;
                    $('.trade_discount_pre').val(fmt(pre)).trigger('change');
                });

                // === TRADE DISCOUNT PERCENT CHANGE ===
                $(document).on('change', '.trade_discount_pre', function() {
                    $('.bulk_qty').each(function() {
                        $(this).trigger('change');
                    });
                });

                // === SCHEMES TYPE CLICK ===
                $(document).on('click', '.schemes_type', function() {
                    const schemes_type = $('.schemes_type').val();
                    const product_id = $('.bulk_product_id').val();
                    const qty = $('.bulk_qty').val();

                    // remove rows with zero price
                    $('.bulk_unit_price').each(function() {
                        const $tr = $(this).closest('tr');
                        if (num($(this).val()) === 0) {
                            $tr.remove();
                        }
                    });

                    $('.sche_check').val(1);

                    if (product_id && qty) {
                        const schemes = $('.schemes').val();

                        if (schemes != null) {
                            $('.bulk_product_id').each(function() {
                                const $tr = $(this).closest('tr');
                                const product = $tr.find('.bulk_product_id').val();
                                const qty = num($tr.find('.bulk_qty').val());
                                const unit_price = num($tr.find('.bulk_unit_price').val());

                                if (unit_price !== 0) {
                                    const url = `{{ URL::to('schemesavailable') }}?product=${product}&qty=${qty}&unitprice=${unit_price}&schemes_type=${schemes}`;

                                    $.get(url, function(data) {
                                        if (data && data !== 0) {
                                            const scheme = data['sch_data'][0];
                                            if (scheme.schemes_type !== 'Gift') {
                                                $tr.find('.bulk_discount_percentage').val(scheme.schemes_type_value);
                                                $tr.find('.bulk_qty').trigger('change');
                                            } else {
                                                // Gift scheme logic (free qty)
                                                const from_qty = parseInt(scheme.scheme_base_value_from);
                                                const scheme_qty = parseInt(scheme.schemes_type_value);
                                                const free_qty_cal = Math.abs(qty / from_qty);
                                                const free_qty = Math.floor(free_qty_cal * scheme_qty);
                                                $tr.find('.bulk_free_qty').val(free_qty);
                                            }
                                        }
                                    });
                                }
                            });
                        } else {
                            $('.bulk_free_qty').val('');
                            showCustomAlert('Please select Schemes', 'info');
                        }
                    } else {
                        showCustomAlert('Product and Qty are required', 'info');
                    }
                });



            });


            $(document).on("focus", "#sales_order_date", function() {

                $(this).datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: "yy-mm-dd",
                    minDate: -3,
                    maxDate: 0,
                    showAnim: "slideDown",
                    yearRange: "-25:+0",

                });
            });



            $(document).on("focus", "#delivery_date", function() {

                $(this).datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: "yy-mm-dd",
                    minDate: 0,
                    maxDate: +15,
                    showAnim: "slideDown",
                    yearRange: "-25:+0",

                });
            });

            $(document).on("focus", ".bulk_delivery_date", function() {

                $(this).datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: "yy-mm-dd",
                    showAnim: "slideDown",
                    yearRange: "-25:+0",

                });
            });
        </script>

        @endpush