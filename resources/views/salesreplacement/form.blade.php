@extends('layouts.header')
@section('content')
<h3 class="text-danger">Sales Replacement</h3>
@include('layouts.breadcrumb')
<?php error_reporting(0); ?>
                       

<form method="post" action="" id="salesinvoice" data-parsley-validate class="needs-validation" novalidate>
    <div class="card shadow-lg rounded-4 border-0">
        <input type="hidden" id="decimal_point" value="" />
        <input type="hidden" value="{{ $savestatus }}" name="savestatus" id="savestatus" />
        {{ csrf_field() }}

        <!-- Header Summary -->
        <div class="card-header bg-light border-bottom">
            <div class="row g-3 align-items-center">
                <div class="col-md-3">
                    <div class="small text-muted">Invoice Date</div>
                    <div class="fw-semibold inv_date span_color">
                        <?php echo date(\Session::get('p_date_format'), strtotime($row->invoice_date)); ?>
                    </div>
                    <div class="mt-2 small text-muted">Invoice Type</div>
                    <div class="fw-semibold">
                        {{ $row->invoice_type }}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small text-muted">Reference No</div>
                    <div class="fw-semibold">
                        {{ $row->reference_number }}
                    </div>

                    <div class="mt-2 small text-muted">Created By</div>
                    <div class="fw-semibold create_by span_color"></div>
                </div>
                <div class="col-md-3">
                    <?php  $taxtot = number_format($row->invoice_tax_total,\Session::get("decimal")) ?>
                    <?php $taxtot1 = str_replace(',', '', $taxtot) ?>
                    <?php $grdtot = number_format($row->invoice_grand_total,\Session::get("decimal")) ?>
                    <?php $grdtot1 = str_replace(',', '', $grdtot) ?>

                    <div class="small text-muted">Source</div>
                    <div class="fw-semibold source_span span_color">
                        {{ $row->source }}
                    </div>

                    <div class="mt-2 small text-muted">Invoice Grand Total</div>
                    <div class="fw-bold text-success grand_total_span span_color">
                        {{ $grdtot1 }}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small text-muted">Invoice Tax Total</div>
                    <div class="fw-semibold tax_total_span span_color">
                        {{ $taxtot1 }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="card-body">
            <div class="row g-4">
                <!-- Column 1 -->
                <div class="col-lg-4">
                    <div class="mb-3 row">
                        <label class="col-md-4 col-form-label">Invoice No</label>
                        <div class="col-md-8">
                            <input class="form-control replacement_hdr_id" id="replacement_hdr_id"
                                   name="replacement_hdr_id" type="hidden"
                                   value="{{ $row->replacement_hdr_id }}" readonly>

                            <input type="text" id="replacement_number" name="replacement_number"
                                   class="form-control replacement_number"
                                   value="{{ $row->replacement_number }}" readonly>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-4 col-form-label">Invoice Date</label>
                        <div class="col-md-8">
                            <input class="form-control datepicker invoice_date" id="invoice_date"
                                   name="invoice_date" type="text"
                                   value="{{ $row->invoice_date }}">
                            <input type="hidden" id="invoice_date_hidden" value="{{ $row->invoice_date }}" />
                        </div>
                    </div>

                    <div class="mb-3 row" style="display:none">
                        <label class="col-md-4 col-form-label">Invoice Type</label>
                        <div class="col-md-8">
                            <select name="invoice_type" id="invoice_type"
                                    class="form-select invoice_type" readonly>
                                <option value="">--select--</option>
                                <option value="STANDARD"  @if($row->invoice_type=="STANDARD") selected @endif>STANDARD</option>
                                <option value="LABOUR"    @if($row->invoice_type=="LABOUR") selected @endif>LABOUR</option>
                                <option value="SAMPLE"    @if($row->invoice_type=="SAMPLE") selected @endif>SAMPLE</option>
                                <option value="REPLACEMENT" @if($row->invoice_type=="REPLACEMENT") selected @endif>REPLACEMENT</option>
                                <option value="DIRECT REPLACEMENT" @if($row->invoice_type=="DIRECT REPLACEMENT") selected @endif>DIRECT REPLACEMENT</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-4 col-form-label">
                            <span class="text-danger">*</span> Freight Carrier
                        </label>
                        <div class="col-md-8 stdivhide">
                            <div class="input-group">
                                <select name="ar_frieghtcarriers_hdr_id" class="form-select ar_frieghtcarriers_hdr_id select2"
                                        id="ar_frieghtcarriers_hdr_id" required data-placeholder="please select">
                                    {!! $ar_frieghtcarriers_hdr_id !!}
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-4 col-form-label">LR No</label>
                        <div class="col-md-8 stdivhide">
                            <input type="text" name="lr_no" id="lr_no"
                                   class="form-control lr_no" value="{{ $row->lr_no }}" >
                        </div>
                    </div>

                    <div class="mb-3 row created_by_cfg">
                        <label class="col-md-4 col-form-label">LR Date</label>
                        <div class="col-md-8 stdivhide">
                            <input class="form-control lr_date datepicker" id="lr_date" name="lr_date"
                                   type="text" value="{{ $lr_date }}">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-4 col-form-label">
                            <span class="text-danger">*</span> Due Date
                        </label>
                        <div class="col-md-8">
                            <input class="form-control due_date datepicker1" id="due_date" name="due_date"
                                   type="text" value="{{ $row->due_date }}">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-4 col-form-label">Round Off</label>
                        <div class="col-md-8">
                            <input class="form-control round_off" id="round_off" name="round_off"
                                   type="text" value="{{ $row->round_off }}">
                        </div>
                    </div>
                </div>

                <!-- Column 2 -->
                <div class="col-lg-4">
                    <div class="mb-3 row">
                        <label class="col-md-4 col-form-label">
                            <span class="text-danger">*</span> Customer
                        </label>
                        <div class="col-md-8 stdivhide customer_id">
                            <select name="ship_to_customer_id" class="form-select ship_to_customer_id select2">
                                {!! $ship_to_customer_id !!}
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-4 col-form-label">Bill To Address</label>
                        <div class="col-md-8">
                            <input type="hidden" name="bill_to_address_id" id="bill_to_address_id"
                                   class="bill_to_address_id" value="{{ $bill_to_address_id }}">

                            <textarea name="billing_to_address_txt" rows="2"
                                      id="billing_to_address_txt"
                                      class="form-control billing_to_address_txt" readonly>{{ $bill_to_address }}</textarea>

                            <div class="mt-2 d-flex gap-2 changeaddress_div">
                                <button type="button" class="btn btn-outline-primary btn-sm billto changeaddress" value="billto">
                                    <i class="fa fa-address-book"></i> Change Address
                                </button>
                                <button type="button" class="btn btn-outline-success btn-sm new_billto changeaddress" value="new_billto">
                                    <i class="fa fa-address-book"></i> New
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-4 col-form-label">Invoice Status</label>
                        <div class="col-md-8">
                            <select name="invoice_status" id="invoice_status"
                                    class="form-select invoice_status select2" readonly>
                                <option value="">--please select--</option>
                                <option value="DRAFT"     @if($row->invoice_status=="DRAFT") selected @endif>DRAFT</option>
                                <option value="INITIATED" @if($row->invoice_status=="INITIATED") selected @endif>INITIATED</option>
                                <option value="APPROVED"  @if($row->invoice_status=="APPROVED") selected @endif>APPROVED</option>
                                <option value="REJECTED"  @if($row->invoice_status=="REJECTED") selected @endif>REJECTED</option>
                                <option value="CANCELLED" @if($row->invoice_status=="CANCELLED") selected @endif>CANCELLED</option>
                            </select>

                            <input type="hidden" name="source" class="source" value="{{ $row->source }}">
                            <input type="hidden" name="reference_source_id" class="reference_source_id"
                                   value="{{ $row->reference_source_id }}">
                            <input type="hidden" name="ar_sales_hdr_id" class="ar_sales_hdr_id"
                                   value="{{ $row->ar_sales_hdr_id }}">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-4 col-form-label">
                            <?php if($required != '' ) { ?><span class="text-danger">*</span><?php } ?>
                            <span class="text-danger">*</span> Remarks
                        </label>
                        <div class="col-md-8">
                            <input type="text" name="remarks" id="remarks"
                                   class="form-control remarks" value="{{ $row->remarks }}">
                        </div>
                    </div>
                </div>

                <!-- Column 3 -->
                <div class="col-lg-4">
                    @if($row->invoice_type=="SAMPLE")
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">
                                <span class="text-danger">*</span> Employee
                            </label>
                            <div class="col-md-8 rdonlydiv">
                                <div class="input-group">
                                    <select name="employee_id" class="form-select employee_id select2" id="employee_id">
                                        {!! $row->employee_id !!}
                                    </select>
                                    <button type="button" class="btn btn-outline-secondary jcr_employee_id">
                                        <i class="fa fa-refresh"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mb-3 row pricelist">
                        <label class="col-md-4 col-form-label">
                            <span class="text-danger">*</span> Price List
                        </label>
                        <div class="col-md-8 stdivhide">
                            <select name="invoice_pricelist_id" class="form-select pricelist_id select2"
                                    id="pricelist_id" required>
                                {!! $pricelist !!}
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-4 col-form-label">Ship To Address</label>
                        <div class="col-md-8">
                            <input type="hidden" name="ship_to_address_id" id="ship_to_address_id"
                                   class="ship_to_address_id" value="{{ $ship_to_address_id }}">

                            <textarea name="shipping_to_address_txt" rows="2"
                                      id="shipping_to_address_txt"
                                      class="form-control shipping_to_address_txt" readonly>{{ $ship_to_address }}</textarea>

                            <input type="hidden" id="custype" name="custype" class="custype" value="">

                            <div class="mt-2 d-flex gap-2 changeaddress_div">
                                <button type="button" class="btn btn-outline-primary btn-sm shipto changeaddress" value="shipto">
                                    <i class="fa fa-address-book"></i> Change Address
                                </button>
                                <button type="button" class="btn btn-outline-success btn-sm new_shipto changeaddress" value="new_shipto">
                                    <i class="fa fa-address-book"></i> New
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- File Upload -->
                    <div class="mb-3 row">
                        <label class="col-md-4 col-form-label">File Upload</label>
                        <div class="col-md-8">
                            @if($row->invoice_hdr_id == '')
                                <input id="choosefile" class="form-control GetFileSizeNameAndType"
                                       name="choosefile[]" type="file" multiple />
                                <div class="mt-2">
                                    <table id="file_choosen" class="table table-sm table-bordered mb-1">
                                        <tbody id="fp"></tbody>
                                    </table>
                                    <small class="text-muted">Please upload file size below 10MB</small>
                                </div>
                            @elseif($row->invoice_hdr_id != '' && ($pageMethod == "salesinvoiceapprovalview" || $pageMethod == "salesinvoice"))
                                <input id="choosefile" class="form-control GetFileSizeNameAndType"
                                       name="choosefile[]" type="file" multiple />
                                <div class="mt-2">
                                    <table id="file_choosen" class="table table-sm table-bordered mb-1">
                                        <tbody id="fp">
                                        <?php $dataupload = json_decode($row->attachfile_name); ?>
                                        @if($dataupload != "" && $dataupload != null)
                                            <input type="hidden"
                                                   value="{{ implode(',', $dataupload) }}"
                                                   name="existing_file" id="existing_file" />
                                            @foreach($dataupload as $k => $v)
                                                <tr>
                                                    <td>
                                                        <span class="note">
                                                            File:
                                                            <span class="files">
                                                                <a download
                                                                   href="{{ URL::to('') }}/uploads/soinvoiceupload/SOINV{{ $row->invoice_hdr_id }}/{{ $v }}">
                                                                    {{ $v }}
                                                                </a>
                                                            </span>
                                                            &nbsp;
                                                            <img src="{{ URL::to('') }}/images/cancel.png"
                                                                 data-value="{{ $v }}"
                                                                 class="delete_user"
                                                                 style="cursor:pointer;">
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                    <small class="text-muted">Please upload file size below 10MB</small>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Schemes -->
                    <div class="mb-3 row">
                        <label class="col-md-4 col-form-label">Schemes</label>
                        <div class="col-md-6">
                            <select name="schemes[]" class="form-select schemes select2" id="schemes" multiple>
                                {!! $schemes !!}
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-center">
                            <button type="button" class="btn btn-success btn-sm apply">
                                Apply
                            </button>
                        </div>
                    </div>

                    <div class="mb-3 row" style="display:none">
                        <label class="col-md-4 col-form-label">Reference No</label>
                        <div class="col-md-8">
                            <input class="form-control reference_id" id="reference_id" name="reference_id"
                                   type="hidden" value="{{ $row->reference_id }}" readonly>
                            <input type="text" id="reference_number" name="reference_number"
                                   class="form-control reference_number"
                                   value="{{ $row->reference_number }}" readonly>
                        </div>
                    </div>

                    <div class="mb-3 row" style="display:none">
                        <label class="col-md-4 col-form-label">dis_record_status</label>
                        <div class="col-md-8 stdivhide">
                            <input type="hidden" name="dis_record_status" id="dis_record_status"
                                   class="form-control dis_record_status" value="">
                        </div>
                    </div>

                    <div class="mb-3 row" style="display:none">
                        <label class="col-md-4 col-form-label">Invoice Tax Total</label>
                        <div class="col-md-8">
                            <input type="text" name="invoice_tax_total" id="invoice_tax_total"
                                   value="{{ $row->invoice_tax_total }}"
                                   class="form-control invoice_tax_total" readonly>
                        </div>
                    </div>

                    <div class="mb-3 row" style="display:none">
                        <label class="col-md-4 col-form-label">Invoice Grand Total</label>
                        <div class="col-md-8">
                            <input type="text" name="invoice_grand_total" id="invoice_grand_total"
                                   value="{{ $row->invoice_grand_total }}"
                                   class="form-control invoice_grand_total" readonly>
                            <input type="text" name="balance_amount" id="balance_amount"
                                   value="{{ $row->balance_amount }}"
                                   class="form-control balance_amount mt-2" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Details -->
            <hr class="my-4">
            <h5 class="mb-3">Additional Details</h5>

            <div class="row g-4">
                <div class="col-md-4">
                    <?php
                    $i = 0;
                    $j = 0;
                    $show_div = 0;
                    foreach($enabled_columns as $index => $val) {
                        if($val->action=='1') {
                            $required = "required";
                            $show_div = 1;
                        } else {
                            $required = '';
                        }

                        if($i != $j) { $j = $i; ?>
                            </div>
                            <div class="col-md-4">
                        <?php }

                        // Payment Term
                        if($val->column_name=='payment_term_id' && $val->active==1) { $i++; ?>
                            <div class="mb-3 row payment_term_id_cfg">
                                <label class="col-md-4 col-form-label">
                                    Payment Term
                                    <?php if($required != '' ) { ?><span class="text-danger">*</span><?php } ?>
                                </label>
                                <div class="col-md-8 stdivhide">
                                    <div class="input-group">
                                        <select name="payment_term_id" <?php echo $required; ?>
                                                class="form-select payment_term_id select2" tabindex="8">
                                            {!! $payment_term_id !!}
                                        </select>
                                        <button type="button" class="btn btn-outline-secondary jcr_payment_term_id">
                                            <i class="fa fa-refresh"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php }

                        // Payment Method
                        if($val->column_name=='payment_method_id' && $val->active==1) { $i++; ?>
                            <div class="mb-3 row payment_method_id_cfg">
                                <label class="col-md-4 col-form-label">
                                    Payment Method
                                    <?php if($required != '' ) { ?><span class="text-danger">*</span><?php } ?>
                                </label>
                                <div class="col-md-8 stdivhide">
                                    <div class="input-group">
                                        <select name="payment_method_id" <?php echo $required; ?>
                                                class="form-select payment_method_id select2" tabindex="9">
                                            {!! $payment_method_id !!}
                                        </select>
                                        <button type="button" class="btn btn-outline-secondary jcr_payment_method_id">
                                            <i class="fa fa-refresh"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php }

                        // Created By
                        if($val->column_name=='created_by' && $val->active==1) { $i++; ?>
                            <div class="mb-3 row created_by_cfg created_by" style="display:none;">
                                <label class="col-md-4 col-form-label">
                                    Created By
                                    <?php if($required != '' ) { ?><span class="text-danger">*</span><?php } ?>
                                </label>
                                <div class="col-md-8 stdivhide">
                                    <select name="created_by" <?php echo $required; ?>
                                            class="form-select created_by" readonly>
                                        {!! $created_by !!}
                                    </select>
                                </div>
                            </div>
                        <?php }

                        // Approved Date
                        if($val->column_name=='approved_date' && $val->active==1) { $i++; ?>
                            <div class="mb-3 row created_by_cfg">
                                <label class="col-md-4 col-form-label">
                                    Approved Date
                                    <?php if($required != '' ) { ?><span class="text-danger">*</span><?php } ?>
                                </label>
                                <div class="col-md-8 stdivhide">
                                    <input class="form-control approved_date datepicker" id="approved_date"
                                           name="approved_date" type="text"
                                           value="{{ $approved_date }}" tabindex="10">
                                </div>
                            </div>
                        <?php }

                        // Trade Discount
                        if($val->column_name=='trade_discount' && $val->active==1) { $i++; ?>
                            <div class="mb-3 row created_by_cfg">
                                <label class="col-md-4 col-form-label">
                                    Trade Discount
                                    <?php if($required != '' ) { ?><span class="text-danger">*</span><?php } ?>
                                </label>
                                <div class="col-md-8 stdivhide">
                                    <input class="form-control trade_discount" id="trade_discount"
                                           name="trade_discount" type="text"
                                           value="{{ $row->trade_discount }}" tabindex="10">
                                    <input class="form-control trade_discount_pre" id="trade_discount_pre"
                                           name="trade_discount_pre" type="hidden"
                                           value="{{ $row->trade_discount_pre }}">
                                </div>
                            </div>
                        <?php }

                        // Organization
                        if($val->column_name=='organization' && $val->active==1) { $i++; ?>
                            <div class="mb-3 row created_by_cfg" style="display:none">
                                <label class="col-md-4 col-form-label">
                                    Organization
                                    <?php if($required != '' ) { ?><span class="text-danger">*</span><?php } ?>
                                </label>
                                <div class="col-md-8">
                                    <select name="organization" <?php echo $required; ?>
                                            class="form-select organization" readonly>
                                        {!! $organization_id !!}
                                    </select>
                                </div>
                            </div>
                        <?php }

                        // Approver Comments
                        if($val->column_name=='approver_commnents' && $val->active==1) { $i++; ?>
                            <div class="mb-3 row created_by_cfg">
                                <label class="col-md-4 col-form-label">
                                    Approver Comments
                                    <?php if($required != '' ) { ?><span class="text-danger">*</span><?php } ?>
                                </label>
                                <div class="col-md-8 stdivhide">
                                    <input type="text" name="approver_comments" <?php echo $required; ?>
                                           value="{{ $approver_comments }}"
                                           class="form-control approver_comments" tabindex="11">
                                </div>
                            </div>
                        <?php }

                        // Invoice Currency
                        if($val->column_name=='invoice_currency' && $val->active==1) { $i++; ?>
                            <div class="mb-3 row created_by_cfg">
                                <label class="col-md-4 col-form-label">
                                    Invoice Currency
                                    <?php if($required != '' ) { ?><span class="text-danger">*</span><?php } ?>
                                </label>
                                <div class="col-md-8 stdivhide">
                                    <input type="hidden" id="currency_rate" class="currency_rate" value="">
                                    <div class="input-group">
                                        <select name="invoice_currency"
                                                class="form-select invoice_currency select2"
                                                <?php echo $required; ?> tabindex="15">
                                            {!! $invoice_currency !!}
                                        </select>
                                        <button type="button" class="btn btn-outline-secondary jcr_invoice_currency">
                                            <i class="fa fa-refresh"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php }

                        // Customer Comments
                        if($val->column_name=='customer_comments' && $val->active==1) { $i++; ?>
                            <div class="mb-3 row created_by_cfg">
                                <label class="col-md-4 col-form-label">
                                    Customer Comments
                                    <?php if($required != '' ) { ?><span class="text-danger">*</span><?php } ?>
                                </label>
                                <div class="col-md-8 stdivhide">
                                    <input type="text" name="customer_comments" <?php echo $required; ?>
                                           value="{{ $customer_comments }}"
                                           class="form-control customer_comments" tabindex="12">
                                </div>
                            </div>
                        <?php }

                        // Sales Person
                        if($val->column_name=='salesperson_id' && $val->active==1) { $i++; ?>
                            <div class="mb-3 row created_by_cfg">
                                <label class="col-md-4 col-form-label">
                                    Sales Person
                                    <?php if($required != '' ) { ?><span class="text-danger">*</span><?php } ?>
                                </label>
                                <div class="col-md-8 stdivhide">
                                    <div class="input-group">
                                        <select name="salesperson_id" <?php echo $required; ?>
                                                class="form-select salesperson_id select2"
                                                tabindex="13" data-placeholder="please select">
                                            {!! $salesperson_id !!}
                                        </select>
                                        <button type="button" class="btn btn-outline-secondary jcr_salesperson_id">
                                            <i class="fa fa-refresh"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php }

                        // Delivery Term
                        if($val->column_name=='delivery_term_id' && $val->active==1) { $i++; ?>
                            <div class="mb-3 row created_by_cfg">
                                <label class="col-md-4 col-form-label">
                                    Delivery Term
                                    <?php if($required != '' ) { ?><span class="text-danger">*</span><?php } ?>
                                </label>
                                <div class="col-md-8 stdivhide">
                                    <div class="input-group">
                                        <select name="delivery_term_id" <?php echo $required; ?>
                                                class="form-select delivery_term_id select2"
                                                tabindex="16">
                                            {!! $delivery_term_id !!}
                                        </select>
                                        <button type="button" class="btn btn-outline-secondary jcr_delivery_term_id">
                                            <i class="fa fa-refresh"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php }

                        // Project
                        if($val->column_name=='project_id' && $val->active==1) { $i++; ?>
                            <div class="mb-3 row created_by_cfg">
                                <label class="col-md-4 col-form-label">
                                    Project Name
                                    <?php if($required != '' ) { ?><span class="text-danger">*</span><?php } ?>
                                </label>
                                <div class="col-md-8 stdivhide">
                                    <div class="input-group">
                                        <select name="project_id" <?php echo $required; ?>
                                                class="form-select project_id select2"
                                                tabindex="14">
                                            {!! $project_id !!}
                                        </select>
                                        <button type="button" class="btn btn-outline-secondary jcr_project_id">
                                            <i class="fa fa-refresh"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php }

                        // TDS Applicable
                        if($val->column_name=='tds_applicable' && $val->active==1) { $i++; ?>
                            <div class="mb-3 row tds_amount">
                                <label class="col-md-4 col-form-label">
                                    <span class="text-danger">*</span> TDS Applicable
                                </label>
                                <div class="col-md-8 tds_apply_div stdivhide">
                                    <select name="tds_applicable" <?php echo $required; ?>
                                            class="form-select tds_applicable select2"
                                            tabindex="17">
                                        <option value="">--Please Select--</option>
                                        <option value="YES" @if($row->tds_applicable=='YES') selected @endif>YES</option>
                                        <option value="NO"  @if($row->tds_applicable=='NO')  selected @endif>NO</option>
                                    </select>
                                </div>
                            </div>
                        <?php }

                        // TDS Amount
                        if($val->column_name=='tds_amount' && $val->active==1) { $i++; ?>
                            <div class="mb-3 row tds_amount">
                                <label class="col-md-4 col-form-label">TDS Amount</label>
                                <div class="col-md-8">
                                    <input type="text" name="tds_amount" <?php echo $required; ?>
                                           id="tds_amount" value="{{ $row->tds_amount }}"
                                           class="form-control tds_amount" readonly tabindex="15">
                                </div>
                            </div>
                        <?php }

                        // TDS Percentage
                        if($val->column_name=='tds_prcnt' && $val->active==1) { $i++; ?>
                            <div class="mb-3 row">
                                <label class="col-md-4 col-form-label">TDS Percentage</label>
                                <div class="col-md-8">
                                    <input type="text" name="tds_prcnt" <?php echo $required; ?>
                                           id="tds_prcnt" value="{{ $row->tds_prcnt }}"
                                           class="form-control tds_prcnt" readonly tabindex="14">
                                </div>
                            </div>
                        <?php }

                        // TDS Account
                        if($val->column_name=='tds_account_id' && $val->active==1) { $i++; ?>
                            <div class="mb-3 row tds_div">
                                <label class="col-md-4 col-form-label">TDS Account</label>
                                <div class="col-md-8 stdivhide">
                                    <div class="input-group">
                                        <select name="tds_account_id" <?php echo $required; ?>
                                                class="form-select tds_account_id select2"
                                                readonly tabindex="18">
                                            {!! $tds_account_id !!}
                                        </select>
                                        <button type="button" class="btn btn-outline-secondary jcr_tds_account_id">
                                            <i class="fa fa-refresh"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php }

                        // Packaging Charges
                        if($val->column_name=='packaging_charges' && $val->active==1) { $i++; ?>
                            <div class="mb-3 row">
                                <label class="col-md-4 col-form-label">
                                    <?php if($required != '') { ?><span class="text-danger">*</span><?php } ?>
                                    Packaging Charges
                                </label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="text" name="packaging_charges" id="packaging_charges"
                                               class="form-control charges packaging_charges"
                                               value="{{ $row->packaging_charges }}" readonly>
                                        <button type="button"
                                                class="btn btn-outline-secondary packingtax"
                                                data-value="Packaging Charges" data-at="1">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                    <input type="hidden" name="packaging_charges_tax" id="packaging_charges_tax"
                                           value="{{ $row->packaging_charges_tax }}"
                                           class="packaging_charges_tax">
                                </div>
                            </div>
                        <?php }

                        // Insurance Charges
                        if($val->column_name=='insurance_charges' && $val->active==1) { $i++; ?>
                            <div class="mb-3 row">
                                <label class="col-md-4 col-form-label">
                                    <?php if($required != '') { ?><span class="text-danger">*</span><?php } ?>
                                    Insurance Charges
                                </label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="text" name="insurance_charges" id="insurance_charges"
                                               class="form-control charges insurance_charges"
                                               value="{{ $row->insurance_charges }}" readonly>
                                        <button type="button"
                                                class="btn btn-outline-secondary packingtax"
                                                data-value="Insurance Charges" data-at="3">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                    <input type="hidden" name="insurance_charges_tax" id="insurance_charges_tax"
                                           value="{{ $row->insurance_charges_tax }}"
                                           class="insurance_charges_tax">
                                </div>
                            </div>
                        <?php }

                        // Other Tax Amount
                        if($val->column_name=='other_tax_amount' && $val->active==1) { $i++; ?>
                            <div class="mb-3 row">
                                <label class="col-md-4 col-form-label">
                                    <?php if($required != '') { ?><span class="text-danger">*</span><?php } ?>
                                    Other Tax Amount
                                </label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="text" name="other_tax_amount" id="other_tax_amount"
                                               class="form-control charges other_tax_amount"
                                               value="{{ $row->other_tax_amount }}" readonly>
                                        <button type="button"
                                                class="btn btn-outline-secondary packingtax"
                                                data-value="Other Tax Amount" data-at="4">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                    <input type="hidden" name="other_tax_amount_tax" id="other_tax_amount_tax"
                                           value="{{ $row->other_tax_amount_tax }}"
                                           class="other_tax_amount_tax">
                                </div>
                            </div>
                        <?php }

                        // Other Freight Amount
                        if($val->column_name=='other_frieght_amount' && $val->active==1) { $i++; ?>
                            <div class="mb-3 row">
                                <label class="col-md-4 col-form-label">
                                    <?php if($required != '') { ?><span class="text-danger">*</span><?php } ?>
                                    Other Freight Amount
                                </label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="text" name="other_frieght_amount" id="other_frieght_amount"
                                               class="form-control charges other_frieght_amount"
                                               value="{{ $row->other_frieght_amount }}" readonly>
                                        <button type="button"
                                                class="btn btn-outline-secondary packingtax"
                                                data-value="Other Freight Amount" data-at="5">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                    <input type="hidden" name="other_frieght_amount_tax" id="other_frieght_amount_tax"
                                           value="{{ $row->other_frieght_amount_tax }}"
                                           class="other_frieght_amount_tax">
                                </div>
                            </div>
                        <?php }

                        // Transport Charges
                        if($val->column_name=='transport_charges' && $val->active==1) { $i++; ?>
                            <div class="mb-3 row">
                                <label class="col-md-4 col-form-label">
                                    <?php if($required != '') { ?><span class="text-danger">*</span><?php } ?>
                                    Transport Charges
                                </label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="text" name="transport_charges" id="transport_charges"
                                               class="form-control charges transport_charges"
                                               value="{{ $row->transport_charges }}" readonly>
                                        <button type="button"
                                                class="btn btn-outline-secondary packingtax"
                                                data-value="Transport Charges" data-at="2">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                    <input type="hidden" name="transport_charges_tax" id="transport_charges_tax"
                                           value="{{ $row->transport_charges_tax }}"
                                           class="transport_charges_tax">
                                </div>
                            </div>
                        <?php }

                        // Discount
                        if($val->column_name=='discount_id' && $val->active==1) { $i++; ?>
                            <div class="mb-3 row remarks_cfg dishide">
                                <label class="col-md-4 col-form-label">
                                    <?php if($required != '') { ?><span class="text-danger">*</span><?php } ?>
                                    Discount
                                </label>
                                <div class="col-md-8 disdspnone stdivhide">
                                    <div class="input-group">
                                        <select name="discount_id" <?php echo $required; ?>
                                                id="discount_id"
                                                class="form-select discount_id select2" tabindex="19">
                                            {!! $discount !!}
                                        </select>
                                        <button type="button" class="btn btn-outline-secondary jcr_discount_id">
                                            <i class="fa fa-refresh"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php } } ?>
                </div>
            </div>
        </div>



	

	<!-------------------------Linedata -------------------------------->
            <div class="row mt-4">
        <div class="col-md-12" >
            <div id="preview-area" class="table-responsive">
    <table class="table table-bordered clone_table">

                  <thead class="table-light">
                        <tr>
                            <th id="line" >Line No</th>
                           <?php if($row->source =="STANDARD" || $row->source == "REPLACEMENT" || $row->source =="LABOUR" || $row->source == 'SALES ORDER' ||  $row->source == 'DISPATCH' || $row->source == 'PICK ORDER') { ?>
                            <th id="prod">Product </th>
                            <th>Customer Part No</th>
                            <th id="5">Uom Code </th>
                        <?php } if($row->source =="LABOUR") { ?>
                            <th id="desc">Product Description</th>
                            <?PHP } ?>
                         
                            <?php if($row->source == 'SALES ORDER') { ?>
                               
                           <th>SO Qty</th> 
                       <?php }  else if ($row->source == 'PICK ORDER') { ?>

                         <th>Picked Qty</th> 
                     <?php }else if ($row->source == 'DISPATCH') { ?>

                         <th>Dispatch Qty</th>
                            <?php } ?>
                            <?php  if ($row->source == 'DISPATCH' && $row->ar_sales_hdr_id!='' || ($row->source == 'SALES ORDER')) { ?> 
                              <th>Sales Invoice Qty</th>
                            <?php }  ?>
                            <?php if($row->invoice_type!="DIRECT REPLACEMENT") { ?>
                            <?php if($pageMethod!="directreplacement"){ ?>
                             <th>Invoice Qty</th>
                         <?php } }?>
                            <?php if($row->source == 'SALES ORDER') { ?>
                             <th>Invoiced Qty</th>
                        <?PHP } ?>
                            <th class="replc">Price</th>
                            <th class="replc">Discount(%)</th>
                            <th class="replc">Discount Amount</th>
                            <th class="replc">Tax Exemption</th>
                                <?php if($row->source=="STANDARD" || $row->source == "REPLACEMENT" || $row->source == 'SALES ORDER' ||  $row->source == 'DISPATCH' || $row->source == 'PICK ORDER') { ?>
                           <th class="hsn" id="sample"> HSN Code </th>
                            <?php } else {?>
                            <th  id="sample"> SAC Code </th>
                            <?php } ?>
                         
                            <th id="tax" class="replc">Tax Group</th>
                            <th id="taxamt" class="replc">Tax Amount</th>
                            <th class="replc">Line Total</th>
                            <?php if($row->source!="DISPATCH" && $row->source!="REPLACEMENT"){ ?>
                            <th>QOH</th>
                            <?php } ?>
                            <?php if($pageMethod!="salesinvoicefromdispatch"){ ?>
                            <th>Replacement Qty</th>
                        <?php } ?>
                            <th>Comments</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody class="clone_lines_body">
                        <?php if( !empty($linedata) && count($linedata)>=1) { ?>
                            @foreach($linedata as $key=>$value)

                            <tr class="">

                                <td>
            <input type="hidden" name="bulk_replacement_line_id[]" class="form-control input-sm bulk_replacement_line_id" value="{{ $value->replacement_line_id }}">
            <input type="hidden" name="bulk_reference_hdr_id[]" class="form-control input-sm bulk_reference_hdr_id" value="{{ $value->reference_hdr_id }}">
            <input type="hidden" name="bulk_reference_line_id[]" class="form-control input-sm bulk_reference_line_id" value="{{ $value->reference_line_id }}">
            <input type="hidden" name="ar_sales_line_id[]" class="form-control input-sm ar_sales_line_id" value="{{ $value->ar_sales_line_id }}">

                                    <input type="text" readonly name="bulk_line_no[]" class=" input-sm bulk_line_no " value="{{ $key + 1 }}" readonly="readonly" >
                                </td>
                                    <?php if($row->source =="STANDARD" || $row->source == "REPLACEMENT" || $row->source =="LABOUR" || $row->source == 'SALES ORDER' ||  $row->source == 'DISPATCH' || $row->source == 'PICK ORDER') { ?>
                                <td id="blk" class="stdivhide">
                                    <select name="bulk_product_id[]" class="select2 bulk_product_id  " parsley-validated required="required" >{!! $value->product_id !!}</select>
                                </td>
                                  <td class="pdtdiv" style="pointer-events:none;">
                            <select name="bulk_part_no[]" id="bulk_part_no" class="bulk_part_no select2 "  data-placeholder="please select">{!! $value->part_no !!}</select>
                        </td>
                                 <td class="stdivhide" style="pointer-events:none">
                                    <select name="bulk_uom_code_id[]" id="bulk_uom_code_id" class="select2 bulk_uom_code_id" >
                                        {!! $value->uomcode_id !!}
                                    </select>
                                </td><?php } if($row->source =="LABOUR") {?>
                                <td id="des" class="stdivhide">
                                    <input type="text" name="bulk_description[]" class="form-control bulk_description" value="{{ $value->description }}" required="required">
                                </td>
                               
<?php } ?>
                                 <?php if($row->source !="STANDARD" && $row->source !="LABOUR" && $row->source != "REPLACEMENT") { ?>
                                <td>
                                    <input type="text" name="bulk_salesorder_qty[]" class="form-control input-sm bulk_salesorder_qty input_qty_width" value="{{ $value->salesorder_qty }}" minlength="1" >
                                </td>
                                <?php }?>
                                <?php  if ($row->source == 'DISPATCH' && $row->ar_sales_hdr_id!='' || ($row->source == 'SALES ORDER')) { $qty=""; 
                            if($pageMethod=="salesinvoiceapprovalview") {
                                $qty=$value->qty; 
                            }else{
                                $qty=$value->qty; 
                            }
                                ?> 
                                
                              <td>
                                <a  class="invoicesoqty"> <i class="fa fa-plus invoicesoqtyplus"></i></a>
                          <input type="hidden" name="bulk_sales_order[]" class="bulk_sales_order" value="{{ $value->sales_order }}" style="display: none;">
                    <input type="hidden" name="bulk_sales_order_qty[]" class="bulk_sales_order_qty" value="{{ $value->sales_order_qty }}" style="display: none;">
                    <input type="hidden" name="bulk_sales_order_invoice[]" class="bulk_sales_order_invoice" value="{{ $value->sales_order_invoice }}" style="display: none;">
                    <input type="hidden" name="bulk_sales_order_invoiced[]" class="bulk_sales_order_invoiced" value="{{ $value->sales_order_invoiced }}" style="display: none;">
                                </td>
                            <?php } else{ $qty=$value->qty; }  ?>
                           
                                <?php if($row->invoice_type!="DIRECT REPLACEMENT") { ?>
                                <td class="stdivhide">
                                    <input type="text" name="bulk_qty[]" class="form-control input-sm bulk_qty input_qty_width" value="{{$qty}}" minlength="1" required="required">
                                </td>
                            <?php } ?>
                                <?php if($row->source == 'SALES ORDER') { ?>
                                                                    <td>
                                       <input type="text"  name=bulk_invoice_qty[] class="form-control bulk_invoiced_qty"  readonly value="{{$value->invoiced_qty}}">   
                                    </td>
                                <?PHP } ?>
                                <td class="replc">
                                    <?php $price=number_format($value->unit_price,\Session::get("decimal")) ?>
                                     <?php $price1=str_replace(',', '',$price) ?>
                                    <input type="text" name="bulk_unit_price[]" class="form-control input-sm bulk_unit_price input_qty_width" value="{{ $price1 }}" required="required" readonly="true">
                                </td>
                                <td class="stdivhide replc">
                                    <input type="text" name="bulk_discount_percentage[]" class="form-control input-sm bulk_discount_percentage input_qty_width" value="{{ $value->discount_percentage }}">
                                </td>
                                <td class="stdivhide replc">
                                    <?php $disamt=number_format($value->discount_amount,\Session::get("decimal")) ?>
                                     <?php $disamt1=str_replace(',', '',$disamt) ?>
                                    <input type="text" name="bulk_discount_amount[]" class="form-control input-sm bulk_discount_amount input_qty_width" value="{{ $disamt1 }}" readonly="true">
                                </td>
                                <td class="replc"><select name="bulk_tax_excemption[]" class="form-control select2 bulk_tax_excemption" id="bulk_tax_excemption" required >
    <option value="">--please select--</option>
    <option value="Yes" <?php  if($value->tax_excemption=='Yes') { echo "selected"; }   ?> >Yes</option>
    <option value="No" <?php  if($value->tax_excemption=='No') { echo "selected"; }   ?> >No</option>
  </select>
</td>
                                <?php  if($row->source=="STANDARD" || $row->source == "REPLACEMENT" || $row->source == 'SALES ORDER' ||  $row->source == 'DISPATCH'){ ?>
                                <td class="hsn hsnhide">
                                    <select name="bulk_hsn_code[]" id="bulk_hsn_code" class="select2 bulk_hsn_code" >{!! $value->hsn_code!!}</select>
                                </td>
                                <?php } else {?>
                                  <td class="hsnhide">
                                    <select name="bulk_hsn_code[]" id="bulk_hsn_code" class="select2 bulk_hsn_code" >{!! $value->hsn_code!!}</select>
                                </td>
                                <?php } ?>
                                <td id="tax1" class="stdivhide replc">
                                    <select name="bulk_tax_group_id[]" id="bulk_tax_group_id" class="select2 bulk_tax_group_id" >
                                        {!! $value->taxgroup_id !!}
                                    </select>
                                </td>
                                <td id="taxamount" class="stdivhide replc">
                                    <?php $taxamt=number_format($value->tax_amount,\Session::get("decimal")) ?>
                                     <?php $taxamt1=str_replace(',', '',$taxamt) ?>
                                    <input type="text" name="bulk_tax_amount[]" class="form-control input-sm bulk_tax_amount input_qty_width" value="{{ $taxamt1 }}">
                                </td>
                                <td class="stdivhide replc">
                                    <?php $totamt=number_format($value->line_total,\Session::get("decimal")) ?>
                                     <?php $totamt1=str_replace(',', '',$totamt) ?>
                                    <input type="text" name="bulk_line_total[]" class="form-control input-sm bulk_line_total input_qty_width" value="{{ $totamt1 }}">
                                </td>
<?php if($row->source!="DISPATCH"  && $row->source!="REPLACEMENT"){ ?>
                                <td class="stdivhide">
                                    <input type="text" name="bulk_qoh" id="bulk_qoh" class="form-control bulk_qoh" value="{{ $value->qoh }}">
                                </td>
<?php } ?>
     <?php if($pageMethod!="salesinvoicefromdispatch"){ ?>
                             <td >
                                    <input name="bulk_replacement[]" class="form-control input-sm bulk_replacement input_qty_width" row="5" value="{{$value->replacement}}">
                                </td>
                            <?php } ?>
                                <td >
                                    <input name="bulk_comments[]" class="form-control input-sm bulk_comments input_qty_width" row="5" value="{{ $value->comments }}">
                                </td>

        <td class="text-center">
          <button type="button" class="btn btn-sm btn-danger remove-row">
            <i class="fas fa-minus-circle"></i>
          </button>
        </td>
                            </tr>
                            @endforeach
                            <?php } if(!empty($linedata) && count($linedata) < 1 ) { ?>
                                <tr class="cloneRow clone rcopy clonedInput">

                                    <td>
                                        <input type="hidden" name="bulk_replacement_line_id[]" class="form-control input-sm bulk_replacement_line_id" value="">
                                        <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no " value="1" readonly="readonly" >
                                    </td>
                                       <?php if($row->source =="STANDARD" || $row->source == "REPLACEMENT" || $row->source =="LABOUR" || $row->source == 'SALES ORDER'||  $row->source == 'DISPATCH') { ?>
                                    <td id="blk">
                                        <select name="bulk_product_id[]" class="select2 bulk_product_id" parsley-validated required="required" >
                                            {!! $product_id !!}
                                        </select>
                                    </td>
                                      <td class="pdtdiv" style="pointer-events:none;">
                            <select name="bulk_part_no[]" id="bulk_part_no" class="bulk_part_no select2 " data-placeholder="please select">{!! $part_no !!}</select>
                        </td>
                                      <td style="pointer-events:none">
                                        <select name="bulk_uom_code_id[]" id="bulk_uom_code_id" class="select2 bulk_uom_code_id" >
                                            {!! $uom_code_id !!}
                                        </select>
                                    </td><?php } if($row->source =="LABOUR") { ?>
                                    <td id="des">
                                        <input type="text" name="bulk_description[]" class="form-control bulk_description" value="" required="required">
                                    </td>
                                  <?php } ?>
                                   <?php if($pageMethod!='directreplacement') { ?>
                                     <?php if($row->source !="STANDARD" && $row->source !="LABOUR") { ?>
                                    <td class="dir">     
                                        <input type="text" name="bulk_salesorder_qty[]" class="form-control bulk_salesorder_qty" value="">
                                    </td>
                                    <?php } ?>
                                    <td class="dir">
                                        <input type="hidden" name="bulk_sales_order[]" class="bulk_sales_order">
                    <input type="hidden" name="bulk_sales_order_qty[]" class="bulk_sales_order_qty">
                    <input type="hidden" name="bulk_sales_order_invoice[]" class="bulk_sales_order_invoice">
                    <input type="hidden" name="bulk_sales_order_invoiced[]" class="bulk_sales_order_invoiced">
                                        <input type="text" name="bulk_qty[]" class="form-control bulk_qty " value="" required="required">
                                    </td>
                            
                                    <td class="dir">
                                        <input type="text" name="bulk_unit_price[]" class="form-control input-sm bulk_unit_price " value="" required="required" readonly="true">
                                    </td>
                                    <td class="dir">
                                        <input type="text" name="bulk_discount_percentage[]" class="form-control input-sm bulk_discount_percentage " value="">
                                    </td>
                                    <td class="dir">
                                        <input type="text" name="bulk_discount_amount[]" class="form-control input-sm bulk_discount_amount " value="">
                                    </td>
                                    <td class="dir"><select name="bulk_tax_excemption[]" class="form-control select2 bulk_tax_excemption" id="bulk_tax_excemption" required >
    <option value="">--please select--</option>
    <option value="Yes">Yes</option>
    <option value="No" selected >No</option>
  </select>
</td>
<?php } ?>
                                     <?php if($row->source=="STANDARD" || $row->source == "REPLACEMENT" || $row->source == 'SALES ORDER'||  $row->source == 'DISPATCH') { ?>
                                <td class="hsn hsnhide">
                                        <select name="bulk_hsn_code[]" id="bulk_hsn_code" class="select2 bulk_hsn_code" data-placeholder="please select"></select>
                                    </td>
                                <?php } else {?>
                                  <td class="hsnhide">
                                    <select name="bulk_hsn_code[]" id="bulk_hsn_code" class="select2 bulk_hsn_code" >{!! $sac !!}</select>
                                </td>
                                <?php } ?>
                                   <?php if($pageMethod!='directreplacement') { ?>
                                    <td id="tax1 dir">
                                        <select name="bulk_tax_group_id[]" id="bulk_tax_group_id" class="select2 bulk_tax_group_id" >
                                            {!! $tax_group_id !!}
                                        </select>
                                    </td>
                                    <td id="taxamount dir">
                                        <input type="text" name="bulk_tax_amount[]" class="form-control input-sm bulk_tax_amount" value="">
                                    </td>
                                    <td class="dir">
                                        <input type="text" name="bulk_line_total[]" class="form-control input-sm bulk_line_total" value="">
                                    </td>
                                <?php } ?>
<?php if($row->source!="DISPATCH" && $row->source!="REPLACEMENT"){ ?>
                                    <td>
                                        <input type="text" name="bulk_qoh" id="bulk_qoh" class="form-control bulk_qoh" value="{{ $value->qoh }}">
                                    </td>
<?php } ?>
 <?php if($pageMethod!="salesinvoicefromdispatch"){ ?>
                             <td >
                                    <input name="bulk_replacement[]" class="form-control input-sm bulk_replacement input_qty_width" row="5" value="{{$value->replacement}}">
                                </td>
                            <?php } ?>
                                    <td>
                                        <input name="bulk_comments[]" class="form-control input-sm bulk_comments" value="{{ $value->comments }}" row="5" >
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
            </div>
        </div>
    </div>

    <div class="row mt-4 mb-3">
        <div class="col-lg-12 col-md-12">
            <div class="form-group text-center">

            <?php if($return_url == "salesinvoice" || $return_url == "invoicefrompickorder"  || $return_url == "salesinvoicereplacement" || $return_url == "salesreplacecreate")
            { ?>
                <?PHP if($savestatus !="SAVE" )  { ?>
                <button type="button" class="btn btn-secondary px-4 me-2 saveform" value="APPLYCHANGES">DRAFT</button>
               <?PHP } ?>
              
                <button type="button" class="btn px-4 me-2 btn-success saveform" value="SAVE">SUBMIT</button>
            <?php } else if($return_url == "invoicefromorder" || $pageMethod =="salesinvoicefromdispatch") { ?>
                <button type="button" class="btn px-4 me-2 btn-success saveform" value="SAVE">SUBMIT</button>
            <?php } else { ?>
                <button type="button" name="submit" class="btn px-4 me-2 btn-success saveform" value="APPROVED">Approve</button>
                <button type="button" name="submit" class="btn px-4 me-2  btn-secondary saveform" value="REJECTED">Reject</button>
            <?php } ?>
                <a class='btn btn-outline-danger' onclick='location.href="{{ url($pageModule) }}"'>Cancel</a>
            </div>
        </div>

    </div>


</div>

<input type="hidden" class="pdtindex" value=" " />

</div>

</form>


<!-- karthigaa purpose customer search jqgrid model-->
<div class="modal fade" id="customerModal">
  <div class="modal-dialog" style="width:80%;">
    <div class="modal-content">
        <!--Moda Header-->
      <div class="modal-header">
          <h4 class="modal-title"> Customer Details </h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
        <!-- Modal Body -->
      <div class="modal-body">
          <table id="customergrid"></table>
      </div>
         <!-- Modal footer -->
      <div class="modal-footer">
      </div>

    </div>
  </div>
</div>
<!--end-->





    <!-- karthigaa purpose customer search jqgrid model-->
<div class="modal fade" id="productModal">
  <div class="modal-dialog" style="width:80%;">
    <div class="modal-content">
        <!--Moda Header-->
      <div class="modal-header">
          <h4 class="modal-title"> Product Details </h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
        <!-- Modal Body -->
      <div class="modal-body">
          <table id="productgrid"></table>
      </div>
         <!-- Modal footer -->
      <div class="modal-footer">
      </div>

    </div>
  </div>
</div>
    <!--end-->

<div class="modal fade" id="new_address">
  <div class="modal-dialog" style="width:80%;">
    <div class="modal-content">
    <!--Moda Header-->
      <div class="modal-header">

      <h4 align="center" class="modal-title"> Customer Details<span class="ui_close_btn">  </span></h4><button type="button" class="close btn-danger" data-dismiss="modal"></button> 

    </div>
    <!-- Modal Body -->
    <div class="modal-body">
      <form method="post" action="" id="newaddress" class="newaddress" data-parsley-validate>
      <div class="form-group row">

        <div class="col-md-12">
        <div class="col-md-6">

          <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-6">Customer Site Number</label>
            <div class="col-md-6">
              <input type="text" name="customer_site_number newaddr" id="customer_site_number" value="" class="form-control customer_site_number" readonly>
                    <input type="hidden" id="custype" name="custype" value="" class="form-control custype">
            </div>
          </div>

          <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-6">Address</label>
            <div class="col-md-6">
              <input type="text" name="address" id="address" value="" class="form-control address newaddr"  required="true">
            </div>
          </div>

          <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-6">State</label>
            <div class="col-md-6 statehide">
              <select type="text" name="state" id="state" value="" class="select2 state  newaddr"   style="width:100%" required>
              {!! $state_new !!}
              </select>
            </div>
          </div>

<div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-6">Contact Person</label>
            <div class="col-md-6">
              <input type="text" name="contact_person" id="contact_person" value="" class="form-control contact_person newaddr"  >
            </div>
          </div>
          <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-6">Contact Number</label>
            <div class="col-md-6">
              <input type="text" name="contact_number" id="contact_number" value="" class="form-control contact_number newaddr" required="true">
            </div>
          </div>

          <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-6">Primary Address</label>
            <div class="col-md-6">
             <select name='primary_address' id="primary_address" class='form-control primary_address'>
                           <option value >--Please Select--</option>
                             <option value="YES" >YES</option>
                             <option value="NO" >NO</option>
                          </select>
            </div>
          </div>
        </div>
          <div class="col-md-6">

          <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-6">Customer Site Name</label>
            <div class="col-md-6">
              <input type="hidden" name="customer_site_id" class="customer_site_id" id="customer_site_id" value="" />
              <input type="text" name="customer_site_name" id="customer_site_name" value="" class="form-control customer_site_name newaddr" required >
            </div>
          </div>

          <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-6">Country</label>
            <div class="col-md-6">
              <select type="text" name="country" id="country" value="" class="select2 country newaddr"   style="width:100%" required >
              {!! $country_new !!}
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-6">City</label>
              <div class="col-md-6 cityhide">
              <select type="text" name="city" id="city" value="" class="select2 city newaddr"  style="width:100%" required >
              {!! $city_new !!}
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-6">Pin Code</label>
            <div class="col-md-6">
              <input type="text" name="pin_code" id="pin_code" value="" class="form-control pin_code newaddr"  maxlength="6" >
            </div>
          </div>

<div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-6">Contact MailId</label>
            <div class="col-md-6">
              <input type="text" name="contact_mail" id="contact_mail" value="" class="form-control contact_mail newaddr" required="true" >
            </div>
          </div>
              
          <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-6">Active</label>
            <div class="col-md-6" style="pointer-events:none;">
             <select name='active' rows='5' class='select2 active' id="active" >
            <option  value="Yes" >Yes</option>
            <option  value="No" >No</option>
                </select>
            </div>
          </div>
         </div>
        </div>

       <div class="col-md-12" style="width:100%;margin:auto;text-align:center">
         <button type="button" class="btn save newaddress_save" value="Ok">Add Customer Site</button>
            <button type="button" class="btn cancel" data-dismiss="modal" >Cancel</button>
    </div>
    </div>
        </form>
     <!-- Modal footer -->
      <div class="modal-footer">
      </div>

    </div>
  </div>
</div>
  </div>
<div class="modal fade" id="invoicesoqtyModal"  style="overflow-y:hidden;"> 
  <div class="modal-dialog" style="width:100%;">
    <div class="modal-content">
        <!--Moda Header-->
      <div class="modal-header">
          <h4 class="modal-title"> Sales order  Details </h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <input type="hidden" class="qtyindex" value="">
          <input type="hidden" class="pageMethod" value="{{ $pageMethod }}">
      </div>
        <!-- Modal Body -->
      <div class="modal-body qtydetail">
      </div>
        
         <!-- Modal footer -->
      <div class="modal-footer">
      </div>

    </div>
  </div>
</div>
<div class="modal fade" id="taxModal"  style="overflow-y:hidden;"> 
    <div class="modal-dialog" style="width:100%;">
        <div class="modal-content">
            <!--Moda Header-->
            <div class="modal-header">
                <h4 class="modal-title popheader"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
                <div class="taxdetail">
                </div>
            </div>        
            <!-- Modal footer -->
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')

<script>

$(document).ready(function(){


  var dateToday = new Date();
  var data ="{{\Session::get('j_date_format')}}";

  $( ".datepicker1" ).datepicker({
      changeMonth: true,
      dateFormat: data,
      changeYear: true,   
      maxDate: null,
      onClose: function () {
        $(this).parsley().validate();
        }

    }).attr('readonly', 'readonly');
 <?php if($pageMethod!="salesinvoicefromdispatch"){?>
    $('.pricelist').css('pointer-events','none');
 <?php } ?>
    <?php if($pageModule=="salesinvoiceapproval"){ ?>
     $('.additem,.remove,.disdspnone').css('pointer-events','none');
    $('#choosefile').css("pointer-events","none");
    $('.rdonlydiv').css('pointer-events','none');
    $('.invoicesoqty,.customersearch,.refbtnhide,.stdivhide,.refbtn,.due_date').css('pointer-events','none');
    <?php } ?>
    <?php if($row->invoice_type=="SAMPLE") { ?>
    $('.ship_to_customer_id').attr('required',false);
    $('.bulk_tax_group_id').attr('required',false);
    <?php }else{ ?>
    $('.ship_to_customer_id').attr('required',true);
    $('.bulk_tax_group_id').attr('required',true);
    <?php } ?>
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



$(document).on('change','.invoice_date',function(){
    var inv = $('.invoice_date').val();
    $('.inv_date').html(inv);
});


    $(document).on('change','.ship_to_customer_id',function(event)
    {

        var customer_id = $('.ship_to_customer_id').val();
        if(customer_id !='')
        {  
            var url = "{{ URL::to('sodispatchaddress') }}/"+customer_id+"?pid=0&condition=salesinvoice";
            $.get(url,function(data)
            {
                if(data.pricelist_id != 0){
                    $('.pricelist_id').select2('val',[data.pricelist_id]);
                    $('.ar_frieghtcarriers_hdr_id').select2('val',[data.ar_frieghtcarriers_hdr_id]);
                    $('.payment_term_id').select2('val',[data.default_payment_terms_id]);
                    $('.payment_method_id').select2('val',[data.default_payment_method_id]);
                    $('.salesperson_id').select2('val',[data.sales_person]);
                    $('.discount_id').select2('val',[data.ar_discount_hdr_id]);
                    $('.bulk_product_id').html(data.productid);    
                }else{
                    notyMsg("info","Please select pricelist");
                }
                

                if(data[1] != '' && data[0] !='')
                {
                        var result_data=data[1].split('~');
                        $('.shipping_to_address_txt').val(result_data[0]);
                        $('#ship_to_address_id').val(result_data[1]);

                        var result_data=data[0].split('~');
                        $('.billing_to_address_txt').val(result_data[0]);
                        $('#bill_to_address_id').val(result_data[1]);

                }
                if((data[1] == "" && data[0] != "") || (data[0] == "" && data[1] != ""))
                {

                    if(data[1] != '')
                    {
                        var result_data=data[1].split('~');
                        $('.shipping_to_address_txt').val(result_data[0]);
                        $('#ship_to_address_id').val(result_data[1]);

                    }
                    else
                    {
                        $('.shipping_to_address_txt').val('');
                        notyMsgs('info','Please Assign Ship To Address !!!');

                    
                    }
                    if(data[0]!= '')
                    {
                        var result_data=data[0].split('~');
                        $('.billing_to_address_txt').val(result_data[0]);
                        $('#bill_to_address_id').val(result_data[1]);
                    }
                    else{
                            $('.billing_to_address_txt').val('');
                    notyMsgs('info','Please Assign Bill To  Address !!!');
                    }

                }
                if(data[1] == '' && data[0] =='')
                {
                    $(".billing_to_address_txt,#bill_to_address_id").val('');
                     $(".shipping_to_address_txt,#ship_to_address_id").val('');
                    notyMsgs('info','Please Assign Bill To and Ship To Address !!!');
                }
            });
            
        }
        else
        {
            $(".pricelist_id").val('').change();
            $(".billing_to_address_txt,#bill_to_address_id").val('');
            $(".shipping_to_address_txt,#ship_to_address_id").val('');
             $('.ar_frieghtcarriers_hdr_id').select2('val',['']);
                $('.ar_payment_term_id').select2('val',['']);
                $('.ar_payment_method_id').select2('val',['']);
                $('.salesperson_id').select2('val',['']);
            event.preventDefault();
        }


    });

<?php if($row->invoice_type=="DIRECT REPLACEMENT") {?>
    $('.bulk_qty,.bulk_tax_excemption').attr('required',false);
    $('.bulk_tax_group_id').attr('required',false);
<?php } ?>

       // purpose to other tax calculation
    $(document).on('click','.packingtax',function(){
        var type=$(this).attr('data-value');
        var type_id=$(this).attr('data-at');
        $('.popheader').html(type+" Tax Details");
        $('#taxModal').modal('show');
        $('#taxModal').width("48%").css('margin','auto');
        if(type_id=="1"){
            var val_char=$('.packaging_charges_tax').val();
        }
        if(type_id=="2"){
            var val_char=$('.transport_charges_tax').val();
        } 
        if(type_id=="3"){
            var val_char=$('.insurance_charges_tax').val();
        } 
        if(type_id=="4"){
            var val_char=$('.other_tax_amount_tax').val();
        } 
        if(type_id=="5"){
            var val_char=$('.other_frieght_amount_tax').val();
        } 
        var text_data ="{!! $tax_group_id_pop!!}";
        var data='';
            var return_url="{{$pageMethod}}";   
    var   classname="";
    var   classnames="";
     if(return_url=="salesinvoiceapprovalview"){

var classname="style='pointer-events:none'";         
var classnames="style='display:none'";       
}  
        data+='<div class="col-md-6"><div class="form-group row"> <label for="inputIsValid" class="form-control-label col-md-4">'+type+'</label><div class="col-md-6" '+classname+'><input type="text" class="form-control  extracharge extracharge'+type_id+'" value=""></div><div class="col-md-2"></div></div></div><div class="col-md-6"><div class="form-group row"> <label for="inputIsValid" class="form-control-label col-md-4">Tax Group</label><div class="col-md-6" '+classname+'><select class="select2 form-control tax_details'+type_id+'  tax_detailsse">'+text_data+'</select></div><div class="col-md-2"></div></div></div> <div class="col-md-12" style="width:100%;margin:auto;text-align:center"><button type="button" class="btn ok taxchargesave" '+classnames+' value="'+type_id+'">Ok</button></div>';
        $('.taxdetail').html(data);
        if(val_char!=''){
            var dat=val_char.split(",");
            $('.extracharge'+type_id).val(dat[1]);
            $('.tax_details'+type_id).val(dat[0]).change();
        }
         $(document).on('keypress', '.extracharge', function(ev){
        var regex = new RegExp("^[0-9.]+$");
        var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
        if (regex.test(str))
        {
            return true;
        }
        ev.preventDefault();
        return false;
    });
    });
<!--Purpose:Schemes Apply-Isac Naveen-->
$(".apply").click(function(){
    
     total=0;
     price=0;
     qty=0;
     discount=0;
     
    $(".bulk_qty").each(function(index){
         qty=parseFloat($(this).val());
    
         price=parseFloat($(".bulk_unit_price"+index).val());
         discount=parseFloat($(".bulk_discount_amount"+index).val());
         total=parseFloat(total)+(parseFloat(qty*price))-parseFloat(discount);
        
    });

    
var schemes=$(".schemes").val();
var url="{{URL::to('schemesordercheck')}}";
$.get(url+'?schemes='+schemes+"&total="+total,function(data){
    
    discount=0;
    per=0;
    amount=0;
    if(data.length>0)
    {
        $.each(data,function(index,val){
            if(val.schemes_type=="Discounts")
            {
            per=parseFloat(val.schemes_type_value);
            amount=parseFloat((total*per)/100).toFixed(2);
            discount=parseFloat(discount)+parseFloat(amount);
            total=parseFloat(total)-parseFloat(amount);
                        
            }
            else if(val.schemes_type=="Amount")
            {
            }
            
        });
    }
    
        $(".trade_discount").val(discount.toFixed(2)).change();
    
});
    
});

<!--End-->
<!-- Trade discout Change-->
$(".trade_discount").change(function(){
    var val=parseFloat($(this).val());
     total=0;
     price=0;
     qty=0;
     discount=0;
     
    $(".bulk_qty").each(function(index){
         qty=parseFloat($(this).val());
    
         price=parseFloat($(".bulk_unit_price"+index).val());
         discount=parseFloat($(".bulk_discount_amount"+index).val());
         total=parseFloat(total)+(parseFloat(qty*price))-parseFloat(discount);
        
    });
    
    var pre=parseFloat(val*100)/parseFloat(total);
    pre=pre.toFixed(2);
    $(".trade_discount_pre").val(pre).change();
    
});
$(".trade_discount_pre").change(function(){
    $(".bulk_qty").each(function(index){
        $(this).trigger('change');
    });
});
<!-- End -->

    $(document).on('click','.taxchargesave',function(){
        var type=$(this).val();
        var taxgrp = $('.tax_details'+type+' option:selected').attr('data-display');
        var taxgrp_v = $('.tax_details'+type+' option:selected').val();
        var charge = parseFloat($('.extracharge'+type).val()).toFixed("{{\Session::get('decimal')}}");
        taxgrp = taxgrp?taxgrp:0;
        var amount = parseFloat((charge) * taxgrp/100).toFixed("{{\Session::get('decimal')}}");
        if(taxgrp==0){
            notyMsg("info","Please select Tax Group");
        }
        if(charge==''){
            notyMsg("info","Please fill Amount");
        }
        if(taxgrp!=0 && charge!=''){
            var a_c=(parseFloat(amount)+parseFloat(charge)).toFixed("{{\Session::get('decimal')}}");;
            var tax_group_value=taxgrp_v+","+charge;
            if(type=="1"){
                $('.packaging_charges').val(a_c);
                $('.packaging_charges_tax').val(tax_group_value);
            } 
            if(type=="2"){
                $('.transport_charges').val(a_c);
                $('.transport_charges_tax').val(tax_group_value);
            } 
            if(type=="3"){
                $('.insurance_charges').val(a_c);
                $('.insurance_charges_tax').val(tax_group_value);
            } 
            if(type=="4"){
                $('.other_tax_amount').val(a_c);
                $('.other_tax_amount_tax').val(tax_group_value);
            } 
            if(type=="5"){
                $('.other_frieght_amount').val(a_c);
                $('.other_frieght_amount_tax').val(tax_group_value);
            } 
            $('#taxModal').modal('hide');
        }
        else{
            if(type=="1"){
                $('.packaging_charges').val(0);
                $('.packaging_charges_tax').val('');
            } 
            if(type=="2"){
                $('.transport_charges').val(0);
                $('.transport_charges_tax').val('');
            } 
            if(type=="3"){
                $('.insurance_charges').val(0);
                $('.insurance_charges_tax').val('');
            } 
            if(type=="4"){
                $('.other_tax_amount').val(0);
                $('.other_tax_amount_tax').val('');
            } 
            if(type=="5"){
                $('.other_frieght_amount').val(0);
                $('.other_frieght_amount_tax').val('');
            } 
        }
    });
    /** end **/
      //Purpose for TDS  
    var decimal='<?php echo \Session::get('decimal'); ?>';
          $(".tds_applicable").change(function(){
            var customer_id =$('.ship_to_customer_id').val();
             var tds = $(".tds_applicable option:selected").val();
             if(customer_id){
        if(tds == "YES" ){
             var url = "{{ URL::to('salesloadtds') }}/"+customer_id+"/"+tds;
             $.get(url , function(data){
               
                 if(data.tds_percentage!=""){
        $('.tds_prcnt').val(data.tds_percentage);
                $('.tds_account_id').val(data.tds_account_id).change();
                        var sum = 0;
            var sumtax = 0;
                        var sumall = 0;
                        var subtotal= 0;
                     var sumwithtds=0;
                        var charge=0;
            $('.charges').each(function(){
                 var amt=$(this).val();
              if(amt=="")
                  amt=0;
                charge += parseFloat(amt);
            });

            $('.bulk_line_total').each(function(){
                sum += parseFloat($(this).val());
            });
            $('.bulk_tax_amount').each(function(){
                sumtax += parseFloat($(this).val());
            });
                     
              sumall = parseFloat(sum).toFixed(decimal) + parseFloat(charge).toFixed(decimal);
              sumall = (isNaN(sumall) ? 0 : sumall);
              sumtax = (isNaN(parseFloat(sumtax).toFixed(decimal))) ? 0 : parseFloat(sumtax).toFixed(decimal);
               subtotal = parseFloat((sum - sumtax)).toFixed(decimal);

         
                       $('#invoice_tax_total').val(sumtax);
                      $('#invoice_grand_total').val(sumall);


                
                        $('#balance_amount').val(sumall); 
                     
                      $(".tax_total_span").html(sumtax);
                      $(".grand_total_span").html(sumall);
                     
                        var data1=data.tds_percentage
                         if(data1!=''){
                            var tds_amount=parseFloat(subtotal * (data1/100)).toFixed(decimal);
                            $('.tds_amount').val(tds_amount);
                                sumwithtds = parseFloat((sumall) - (tds_amount)).toFixed(decimal);
                                sumwithtds=(isNaN(sumwithtds) ?0:sumwithtds);
                              $('#invoice_grand_total').val(sumwithtds);
                              $('#balance_amount').val(sumwithtds);
                              $(".tax_total_span").html(sumtax);
                              $(".grand_total_span").html(subtotal);
                         }
             }
                 else{
                     notyMsg("info","TDS Percentage Not Set For This Customer");
                 }
                     
               });
             $('.tds_prcnt,.tds_amount').attr('required',true);
        }
            else{
             $('.tds_prcnt').val('');
             $('.tds_amount').val('');
             $('.tds_prcnt,.tds_amount').attr('required',false);
         }
        }
    });
    

          $('.invoice_currency').change(function(){
                var curr = $(this).val();
                var url = "{{URL::to('salesinvoicecurrency')}}/"+curr;
                if(curr != ""){
                    $.get(url,function(data){
                        data = $.trim(data);
                        $('.currency_rate').val(data);
                        if(data != 0){
                            $('.bulk_unit_price').trigger('change');
                        }
                    });
                }
          });



    <?php if ($row->source == 'DISPATCH') { ?> 
    $('.add_row,.productsearch').css('display','none');
    <?php } ?>
    
    <?php if($pageMethod == "salesinvoiceapprovalview") { ?>
$('#choosefile').css("pointer-events","none");
$('.delete_user,.due_date').css("pointer-events","none");
    <?php } ?>
    $(document).on('change','.GetFileSizeNameAndType',function(){
        
        var fi = document.getElementById('choosefile'); // GET THE FILE INPUT AS VARIABLE.

        var totalFileSize = 0;

        // VALIDATE OR CHECK IF ANY FILE IS SELECTED.
        if (fi.files.length > 0)
        {
            // RUN A LOOP TO CHECK EACH SELECTED FILE.
            for (var i = 0; i <= fi.files.length - 1; i++)
            {
                //ACCESS THE SIZE PROPERTY OF THE ITEM OBJECT IN FILES COLLECTION. IN THIS WAY ALSO GET OTHER PROPERTIES LIKE FILENAME AND FILETYPE
                var fsize = fi.files.item(i).size;
                totalFileSize = totalFileSize + fsize;
                document.getElementById('fp').innerHTML =
                document.getElementById('fp').innerHTML
                +
                '<tr><td><span class="note" ><br /> File:<span class="files">' + fi.files.item(i).name+'</span>&nbsp;<img src="{{URL::to('')}}/images/cancel.png" class="delete_user"></span></td></tr>';
            }
        }
        //document.getElementById('divTotalSize').innerHTML = "Total File(s) Size is <b>" + Math.round(totalFileSize / 1024) + "</b> KB";
    });
    
   
     
     $(document).on('click','.delete_user',function(){
     
        var quote_hdr = '{{$row->invoice_hdr_id}}';
        if(quote_hdr != '')
        {
           
        var existing_value = $('#existing_file').val();
        var delete_value = $(this).attr('data-value');
        
        
            removeValue(existing_value,delete_value);
        }
   


        $(this).parent().parent().remove();
        }); 
    function removeValue(existing_value, delete_value) 
    {
         list = existing_value.split(',');
        list.splice(list.indexOf(delete_value), 1);
        var values = list.join(',');
        if(values != '')
        $('#existing_file').val(values);
    else
         $('#existing_file').val('');
    }
    
    <?php  if ($row->source == 'DISPATCH' && $row->ar_sales_hdr_id!='' || ($row->source == 'SALES ORDER')) { ?> 
    $('.bulk_qty').attr('readonly',true);
    <?php } ?>
    
    $('.invoicesoqty').click(function(){
        $('#invoicesoqtyModal').modal('show');
        
        $('#invoicesoqtyModal').width("55%").css('margin','auto');
        var index=$(this).closest('tr').index();
        $('.qtyindex').val(index);
        var pageMethod =$('.pageMethod').val(); 
            
        if(pageMethod=="salesinvoiceapprovalview") {
        
           $('.qtyok').css('display','none');
           $('.invoice_issue_qty').attr('readonly',true);
        
        }
        var prdid=$('.bulk_product_id'+index).val();
        var soid=$('.ar_sales_hdr_id').val();
        var source=$('.source').val();
        var reference_source_id=$('.reference_source_id').val();
        var soqty=$('.bulk_sales_order_qty'+index).val();
        if(soqty!=''){
        var so_id=$('.bulk_sales_order'+index).val();
        var invoice_qty=$('.bulk_sales_order_invoice'+index).val();
        var invoiced_qty=$('.bulk_sales_order_invoiced'+index).val();
        }
        else{
        var soqty=0;
        var so_id=0;
        var invoice_qty=0;
        var invoiced_qty=0;
        }
        if(source=="DISPATCH" && soid!=''){
            
            var dispatch=reference_source_id;
        }
        else{
            var dispatch=0;
        }
        var pagemode='<?php echo $pagemode; ?>';
        if(pagemode=='create'){
             var invlineid=0;
           var url="{{URL::to('invoicesoqty') }}/"+prdid+"/"+soid+"?so_qty="+soqty+"&so_id="+so_id+"&invoice_qty="+invoice_qty+"&dispatch="+dispatch+"&invoiced_qty="+invoiced_qty+"&invlineid="+invlineid;
           }else if(pagemode=='edit'){
               var invlineid=$('.bulk_replacement_line_id').val();
                var url="{{URL::to('invoicesoqty') }}/"+prdid+"/"+soid+"?so_qty="+soqty+"&so_id="+so_id+"&invoice_qty="+invoice_qty+"&dispatch="+dispatch+"&invoiced_qty="+invoiced_qty+"&invlineid="+invlineid;    
                    }
        $.get(url,function(data){
            $('.qtydetail').html(data);
        }); 
    });
    /*deepika purpose:qty validation*/
        $(document).on('keypress','.invoice_issue_qty', function(ev){
            var regex = new RegExp("^[0-9.]+$");
                    var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                    if (regex.test(str)) {
                        return true;
                    }
                    ev.preventDefault();
                    return false;
        });
    /*end*/
    $(document).on('change','.invoice_issue_qty',function(ev){
         var index=$(this).attr('data-index');
        var qty=parseFloat($('.qty'+index).val()).toFixed(decimal);
        var invoicedqty=parseFloat($('.invoicedqty'+index).val()).toFixed(decimal);
        var qtycheck=qty-invoicedqty;
        var invoice_qty=parseFloat($(this).val()).toFixed(decimal);
        
        if(qtycheck<invoice_qty){
            notyMsg("error","Invoice qty Should Not greater than so qty");
            $('.invoice_issue_qty'+index).val('');
        }
    });
var decimal='<?php echo \Session::get('decimal') ?>';
    $(document).on('click','.qtyok',function(){
        
        var add = 0;
        var invoiceqty=[];
        var sorder_no=[];
        var sorder_qty=[];
        var invoiced_qty=[];
        $('.invoice_issue_qty').each(function(k){           
            var val = parseFloat($(this).val());
            if(isNaN(val))
            {
                val=0;
            }   
            add=add+val;
            if(val==""){
              notyMsgs('error','Please Enter Invoice Qty'); 
                $('#invoicesoqtyModal').modal('show');
               }
            invoiceqty[k]=parseFloat(val).toFixed(decimal); 
        });
        $('.so_id').each(function(k,v){
            if(($(this).val()) !='')
            {
                var so_id = $(this).val();
            }
            else
            {
                var so_id = 0;
            }
            sorder_no[k]=so_id;
        });
        
        $('.qty').each(function(k,v){
            if(($(this).val()) !='')
            {
                var qty = $(this).val();
            }
            else
            {
                var qty = 0;
            }
            sorder_qty[k]=parseFloat(qty).toFixed(decimal);
        });
        $('.invoicedqty').each(function(k,v){
            if(($(this).val()) !='')
            {
                var invoicedqty = $(this).val();
            }
            else
            {
                var invoicedqty = 0;
            }
            invoiced_qty[k]=parseFloat(invoicedqty).toFixed(decimal);
        });
        var index = $('.qtyindex').val();
    
        var salesorder_qty=parseFloat($('.bulk_salesorder_qty'+index).val());
        if(salesorder_qty<add){
        $('.invoice_issue_qty').val('');
            notyMsg("error","Invoice qty Should Not Greater than order qty");
        }
        else{
    if(add==0){
        add="";
    }else{
    add=parseFloat(add).toFixed(decimal);   
    }
        $('.bulk_qty'+index).val(add);
        $('.bulk_sales_order_invoice'+index).val(invoiceqty);
        $('.bulk_sales_order_invoiced'+index).val(invoiced_qty);
        $('.bulk_sales_order'+index).val(sorder_no);
        $('.bulk_sales_order_qty'+index).val(sorder_qty);
        
            var unitprice = $('.bulk_unit_price'+index).val();
  
            var tds_amount = $('.tds_amount').val();
            var amt = parseFloat(add) * parseFloat(unitprice);
            var taxgrp = $('.bulk_tax_group_id'+index+' option:selected').attr('data-display');
            taxgrp = taxgrp?taxgrp:0;
            var discountsperc = $('.bulk_discount_percentage'+index).val();
             var disamout = ((amt * parseFloat(discountsperc) / 100));
    var disamt = (isNaN(disamout)) ? 0 : disamout;
    var d_amt=disamt.toFixed("{{\Session::get('decimal')}}");
    $('.bulk_discount_amount'+index).val(d_amt);
            var taxamount = ((amt - disamt) * parseFloat(taxgrp)) /100;
    taxamount = parseFloat(taxamount).toFixed("{{\Session::get('decimal')}}");
    var eVal = (isNaN(taxamount)) ? 0 : taxamount; 

        $('.bulk_tax_amount'+index).val(eVal);
    var tot =parseFloat((amt - disamt)) + parseFloat(eVal);
    var linetot=tot.toFixed("{{\Session::get('decimal')}}");
    var lintot = (isNaN(linetot)) ? 0 : linetot;
  
    $(".bulk_line_total"+index).val(lintot);
                var sum = 0;
        var sumtax = 0;
      var sumall=0;
    var charge=0;
            $('.charges').each(function(){
                 var amt=$(this).val();
              if(amt=="")
                  amt=0;
                charge += amt;
            });
        $('.bulk_line_total').each(function()
        {
            sum += $(this).val();
        });
        $('.bulk_tax_amount').each(function()
        {
            sumtax += $(this).val();
        });
        
         sumall=parseFloat(charge).toFixed(decimal)+parseFloat(sum).toFixed(decimal);
        sumall=(isNaN(parseFloat(sumall).toFixed(decimal))) ? 0 : parseFloat(sumall).toFixed(decimal);
             sumall = parseFloat(sumall).toFixed(decimal) - parseFloat(tds_amount).toFixed(decimal);
        sumall=(isNaN(parseFloat(sumall).toFixed(decimal))) ? 0 : parseFloat(sumall).toFixed(decimal);
        sumtax=parseFloat(sumtax).toFixed("{{\Session::get('decimal')}}");
        //alert(sumtax);
        $('#invoice_tax_total').val(sumtax);
        $('#invoice_grand_total').val(sumall);
        $('.grand_total_span').html(sumall);
        
        $('.tax_total_span').html(sumtax);
        $('#invoicesoqtyModal').modal('hide');
        }
    });
    <?php if($row->source=="REPLACEMENT"){ ?>
$('.replc').css('display','none');
    <?php } ?>
    
    <?php  if($return_url!="salesinvoice") { ?>

    $('.bulk_unit_price,.bulk_discount_percentage').attr('readonly',true);
    $('.hsnhide').css('pointer-events','none');
    <?php } ?>
    
    $('.bulk_salesorder_qty').attr('readonly',true);
    
    <?php if($pageMethod=="salesinvoiceapprovalview" || $pageMethod  == "createinvoicefromorder") { ?>
    $('.add_row,.rem,.searchhide').css('display','none');
    <?php } ?>
    <?php if(($row->source == 'SALES ORDER') || ($row->source == 'PICK ORDER') || ($row->source == 'DISPATCH')) { ?>
    $('.billto,.new_billto,.shipto,.new_shipto,.jcr_customer_id').css('display','none');
    $('#blk,.customer_id').css('pointer-events','none');
    <?PHP } ?>
                               
                        

    
    <?php if($return_url=="salesinvoiceapprovalview")
     { ?>
         $('.stdivhide').css('pointer-events','none'); 
     
            $('.refbtn').css('display','none');
    
     <?php }?>
        $('#tax1').css('pointer-events','none'); 
    var organization = '<?php echo \session::get('organization'); ?>' ;
$('.organization_id').val(organization).change();
var user = '<?php echo Session::get('id'); ?>' ;
$('.created_by').val(user).change();
$(".create_by").html($('.created_by option:selected').text());

    $('.org').html($('.organization option:selected').text());


 var data ="{{\Session::get('j_date_format')}}";
    
     $('.add_row').click(function()
    {
       var form = $('#salesinvoice');
        form.parsley().destroy();


    });

$(".add_row").relCopy(data);
 $('.add_row').click(function()
    {
        changeclassfields();
        var rowCount = $('.sales_invoice_table tbody tr').length;
        var invoice = $('.invoice_hdr_id').val();
        <?php if($pageMethod == "salesinvoice") { ?>
            if(invoice !=  ""){
                $('.bulk_product_id'+(rowCount-1)).html("<?php echo $product; ?>");
            }
        <?php } else { ?>
                $('.bulk_product_id'+(rowCount-1)).html("<?php echo $product; ?>");
         <?php } ?>
    });



    var show_div = '<?php echo $show_div; ?>';
    if(show_div == "1")
        $('#panel_add').trigger('click');

$(".additional").trigger();



$('.bulk_tax_amount,.bulk_line_total,#bulk_qoh').attr('readonly',true);
    
    
var source='<?php echo $row->invoice_type; ?>';

        if(source=='LABOUR')
        {
                   // $('#prod,#blk').hide();
                    $('#desc,#des').show();
                  ///  $('.bulk_product_id').removeAttr('required');
                  $('.hsn').hide();
                  $('.bulk_unit_price').attr('readonly',false);
        }
        else
                {
                    $('.bulk_unit_price').attr('readonly',true);
                    $('#prod,#blk').show();
                    $('.bulk_product_id').attr('required');
                    $('.bulk_description').removeAttr('required');
                    $('#desc,#des').hide();
                    <?php if($row->source!="Replacement"){ ?>
                    $('.hsn').show();
                  <?php } ?> 
        }

    $(document).on('click','.approve',function()
        {
            $("#invoice_status").val("APPROVED").change();
    });

        $(document).on('click','.reject',function()
        {
            $("#invoice_status").val("REJECTED").change();
    });

        $('.organization').bind('click mousedown', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            return true;
        });

     $(document).on('click','.jcr_discount_id',function(){
var cusurl="{{URL::to('discountjcombo')}}";
      $.get(cusurl,function(data){
        $('.discount_id').html(data); 
      });
});
$('.req').hide();
$(document).on('click','.saveform',function()
    {
//alert();
            var btnval = $(this).val();
            if(btnval == 'APPLYCHANGES')
            {
                $('.remarks').attr('required',false);
                $("#invoice_status").val('DRAFT');
            }
            else if(btnval == 'APPROVED'){
                 $('.remarks').attr('required',false);
                $("#invoice_status").val('APPROVED');
            }
                else if(btnval == 'REJECTED')
                {
                    $('.req').show();
                  $('.remarks').attr('required',true);   
               $("#invoice_status").val('REJECTED')
                }
                    else
                    {
               $('.remarks').attr('required',false);    
               $("#invoice_status").val('INITIATED');
                    }
            qtyrequired();

            if(btnval == 'APPLYCHANGES')
                var savestatus = 'DRAFT';
           
            else if(btnval == 'SAVE' || btnval == 'SAVENEW')
                var savestatus = 'SAVE';
            else if(btnval == 'APPROVED')
                var savestatus ='APPROVED';
            else if(btnval=='REJECTED')
                 var savestatus='REJECTED';

            var show_div = '<?php echo $show_div; ?>';
            if(show_div == "1")
                $('#panel_add').trigger('click');

            $('#savestatus').val(savestatus);

            var url     = "{{URL::to('salesreplacementsave')}}";
            var red_url     ="{{URL::to('salesreplacement')}}";
            var source='<?php echo $row->invoice_type; ?>';
            validationrule('salesinvoice');
            var form = $('#salesinvoice'); 
            if(btnval != 'APPLYCHANGES')
            {
                var form = $('#salesinvoice');
                form.parsley().validate();
                if (form.parsley().isValid())
                {
                    change_date();
                 $('.ajaxLoading').show();

                    var form_data = new FormData(document.getElementById('salesinvoice'));              
                $.ajax({
                  url: "{{URL::to('salesreplacementsave')}}",
                  type: "POST",
                  data: form_data,
                  enctype: 'multipart/form-data',
                  processData: false,  // tell jQuery not to process the data
                  contentType: false,   // tell jQuery not to set contentType
                  async:true,
                  xhr: function(){
                      var xhr = $.ajaxSettings.xhr();
                    if (xhr.upload) {
                        xhr.upload.addEventListener('progress', function(event) {
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
                }).done(function(data)
        {
                        
                        var status      = data.status;
                        var msg     = '<span style="color:#090065">'+data.auto_no+'</span>  '+data.message;
                     //   var msg         = data.message;
                        var id          = data.id;
                        var edit_url    = "{{URL::to('salesinvoicecreate') }}/"+id;
                        if(btnval !='SAVE' && btnval !='DRAFT' && btnval != 'APPROVED' && btnval != 'REJECTED')
                        {

                            notyMsg(status,msg);
                           
                                $('.ajaxLoading').hide();
                            window.location.href=create_url;
                           
                        }
                        else
                        {
                            notyMsg(status,msg);
                           
                                $('.ajaxLoading').hide();
                            window.location.href=red_url;
                            
                        }
                    }); 
                
                }
            }
            else
            {

                          <?php if($row->source!="DISPATCH"){ ?>
                var check=  check_qoh();
                <?php } else{  ?>
                    var check=0;
                <?php  } ?> 
                    if(check== 0){
                         change_date();
                var formdata    = $('#salesinvoice').serialize();
                $('.ajaxLoading').show();
              var form_data = new FormData(document.getElementById('salesinvoice'));              
                $.ajax({
                  url: "{{URL::to('salesreplacementsave')}}",
                  type: "POST",
                  data: form_data,
                  enctype: 'multipart/form-data',
                  processData: false,  // tell jQuery not to process the data
                  contentType: false,   // tell jQuery not to set contentType
                  async:true,
                  xhr: function(){
                      var xhr = $.ajaxSettings.xhr();
                    if (xhr.upload) {
                        xhr.upload.addEventListener('progress', function(event) {
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
                }).done(function(data)
            {
                    var status = data.status;
                    var msg     = '<span style="color:#090065">'+data.auto_no+'</span>  '+data.message;
                    //var msg    = data.message;
                    var id     = data.id;
                    var edit_url    ="{{URL::to('salesinvoicecreate') }}/"+id;
                            notyMsg(status,msg);
                            
                                $('.ajaxLoading').hide();
                            window.location.href=edit_url;
                            
                });
            }
    
                else{
                     notyMsg("info","QOH is less than invoice quantity");
                        $('.ajaxLoading').hide();
                }
            }
    });
  function qtyrequired()
  {
    $(".bulk_qty").each(function(index){
       var req=$(this).val();
if(req==0)
{
  $('.bulk_qty'+index).val('');
}
    });
  }

    function check_qoh(){
        var check=0;
          $('.bulk_qty').each(function (i)
          {
             var qty= $(this).val();
             var qoh=$('.bulk_qoh'+i).val();
              if(parseInt(qty) > parseInt(qoh)){
                  check++;
              }

          });
        
    return check;
        
    }
    var mail = 1;
    /**************** email validation start ***********/
    function ValidateEmail(email) {
        var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        return expr.test(email);
    };   
    /**************** email validation end ***********/
$(document).on('click','.newaddress_save',function()
{
    var customer_id =$('.ship_to_customer_id option:selected').val();
        var form = $('#newaddress');
                 validationrule('newaddress');
                var formdata    = $('#newaddress').serialize();
    form.parsley().validate();
    /**************** email validation start ***********/
        if (!ValidateEmail($("#contact_mail").val())) {
            mail = 1;
        }
        else {
            mail = 0;
        }
        /**************** email validation end ***********/
        if (form.parsley().isValid())
        {
            if(mail != 1 ){
                    var url = "{{ URL::to('newshiptocustomer')  }}/"+customer_id;
                    $.post(url,formdata,function(data)
                    {
                        var type=$('.custype').val();
                        data[0]=$.trim(data[0]);
                        if(data[0]!=''){
                            if(type=="SHIP_TO"){
                                $('.ship_to_address_id').val(data[0]);
                                $('.shipping_to_address_txt').val(data[1]);
                            notyMsg("success","Ship to Address Saved Successfully");
                            }
                            else if(type=="BILL_TO"){
                                    $('.bill_to_address_id').val(data[0]);
                                    $('.billing_to_address_txt').val(data[1]);
                            notyMsg("success","Bill to Address Saved Successfully");
                            }
                        }
                        $('#new_address').modal('hide');
                    });
                }else{
                notyMsg('info','Please enter valid mail (ex:example123@gmail.com)');
            }   
                }
    
    

});

$(document).on('click','.jcr_customer_id',function()
{
    var condition="and savestatus='SAVE' and active='Yes'";
            $(".ship_to_customer_id").jCombo("{{ URL::to('jcomboform1?table=m_customers_t:customer_id:customer_number|customer_name') }}&parent="+condition, {
                selected_value: ""
            });
    $('.pricelist_id')
    $('.bill_to_address_id').val('');
    $('.billing_to_address_txt').val('');
    $('.ship_to_address_id').val('');
    $('.shipping_to_address_txt').val('');
    $('.pricelist_id').val(['']);
    $('.pricelist_id').trigger('change');
});

$(document).on('click','.jcr_ship_to_address_id',function()
{
    $(".ship_to_address_id").jCombo("{{ URL::to('jcomboform?table=m_customer_sites_t:customer_site_id:customer_site_name') }}&order_by=customer_site_name asc",
    {selected_value:""});
});

    $(document).on('click','.jcr_ar_frieghtcarriers_hdr_id',function()
{
    $(".ar_frieghtcarriers_hdr_id").jCombo("{{ URL::to('jcomboform?table=m_frieghtcarriers_hdr_t:ar_frieghtcarriers_hdr_id:carrier_name') }}&order_by=carrier_name asc",
    {selected_value:""});
});    
    
    
$(document).on('click','.jcr_invoice_currency',function()
{
    $(".invoice_currency").jCombo("{{ URL::to('jcomboform?table=f_account_currency_t:account_currency_id:currency_code') }}&order_by=currency_code asc",
    {selected_value:""});
});    
    
$(document).on('click','.jcr_project_id',function(){
$(".project_id").jCombo("{{ URL::to('jcomboform?table=m_projects_t:project_id:project_name') }}&order_by=project_name asc",
{selected_value:""});
});
    $(document).on('click', '.jcr_tds_account_id', function () {
          $(".tds_account_id").jCombo("{{ URL::to('jcomboform?table=f_account_structure_t:f_account_structure_id:concatenated_segments') }}",
          {selected_value: "" });
        });
    $(document).on('click','.jcr_delivery_term_id',function(){
$(".delivery_term_id").jCombo("{{ URL::to('jcomboform?table=m_delivery_terms_t:delivery_terms_id:delivery_term_name') }}&order_by=delivery_term_name asc",
{selected_value:""});
});

$(document).on('click','.jcr_pricelist_id',function(){
    $(".pricelist_id").jCombo("{{ URL::to('jcomboform?table=i_pricelist_hdr_t:pricelist_hdr_id:pricelist_name') }}&order_by=pricelist_name asc",
    {selected_value:""});
});

        $(document).on('click','.jcr_salesperson_id',function(){
        $(".salesperson_id").jCombo("{{ URL::to('jcomboform?table=s_salesperson_t:salesperson_id:salesperson_name') }}&order_by=salesperson_name asc",
        {selected_value:""});
        });


    $(document).on('click','.jcr_invoice_pricelist_id',function(){
var condition =' price_list_type="Sales"';
$(".pricelist_id").jCombo("{{ URL::to('jcomboform?table=i_pricelist_hdr_t:pricelist_hdr_id:pricelist_name') }}&order_by=pricelist_name asc"+'&parent='+condition,
{selected_value:""});
    $('.pricelist_id').trigger('change');
});


    $(document).on('click','.jcr_payment_term_id',function(){
$(".payment_term_id").jCombo("{{ URL::to('jcomboform?table=m_payment_terms_t:payment_term_id:payment_term_name') }}&order_by=payment_term_name asc",
{selected_value:""});
});

$(document).on('click','.jcr_payment_method_id',function(){
$(".payment_method_id").jCombo("{{ URL::to('jcomboform?table=m_payment_methods_t:payment_method_id:payment_method_name') }}&order_by=payment_method_name asc",
{selected_value:""});
});
var x=0;

 $(document).on('change','.bulk_tax_excemption',function(event){
        var index=$(this).closest('tr').index();
        var taxval = $('.bulk_tax_excemption'+index).val();
        if(taxval == "Yes"){
            $('.bulk_tax_group_id'+index).select2('val',['8']);
        }else{
            $('.bulk_hsn_code').trigger('change');
        }
    });

<?php if($pageModule=="salesinvoice"){ ?>

   

$(document).on('change','.bulk_product_id',function(event)
{

    var index=$(this).closest('tr').index();

    var pid=$('.bulk_product_id'+index).val();
    var type = 'so';
    var plid=$('.pricelist_id').select2('val');
    var cid=$('.ship_to_customer_id option:selected').val();
    var cusid=$('.bill_to_address_id').val();
   
    var url="{{ url::to('productdetails_so') }}/"+pid+"/"+plid+"/"+cusid+"/"+type;

if(cid !='')
{

    if(plid !='')
    {
            if(pid !='')
            {
                    var pdtcount = 0;
                    $('.clone').each(function (ind, v)
                    {
                        var val = $(".bulk_product_id" + ind).select2('val');
                        if(index != ind)
                        {
                            if(val == pid)
                            {
                                pdtcount++;
                            }
                        }
                    });
                    if(pdtcount <= 0)
                    {
                    $.get(url,function(data)
                    {
                         var hsnid=data['multihsn'];
                         var hsn=data.hsn_code; 
                  var condition="classification_name='HSN' and gst_code_hdr_id in("+hsnid+")";
                   $(".bulk_hsn_code"+index).jCombo("{{ URL::to('jcomboform?table=f_gst_code_hdr_t:gst_code_hdr_id:classification_code') }}&order_by=classification_code asc"+'&parent='+condition,
                      {selected_value:hsn.toString()});
                    
                    $('.bulk_uom_code_id'+index).val(data.uom_code_id).change();
                 
                        $('.bulk_tax_group_id'+index).val(data.tax_group_id).attr('readonly',true).change();
                    $(".bulk_part_no"+index).select2('val',[data.manufactpartno]);
                        if(data.unit_price==0)
                        {
                            notyMsg("warning",'There is no Pricelist For this Product');
                            $('.bulk_uom_code_id'+index).select2('val','');
                            $('.bulk_unit_price'+index).val(0);

                            $('.bulk_tax_group_id'+index).val(['']);
                            $('.bulk_tax_group_id'+index).trigger('change');
                            $(".bulk_product_id" + index).select2('val','');
                        }

                        $('.bulk_uom_code_id'+index).select2('val',[data.uom_code_id]);
                        
                        var curr = $('.currency_rate').val();

                        if(curr != 0 ){
                            var rate = data.unit_price / curr;
                            $('.bulk_unit_price'+index).val((rate).toFixed(decimal));
                            console.log(rate);
                        }else{
                            $('.bulk_unit_price'+index).val(data.unit_price);
                        }
                        $('.bulk_tax_group_id'+index).select2('val',[data.tax_group_id]);
                        if(data.qoh_qty > 0)
                        {
                           parseFloat( $('.bulk_qoh'+index).val(data.qoh_qty)).toFixed(decimal);
                        }
                        else
                        {
                            $('.bulk_qoh'+index).val(0);
                        }
                        calc_by_index(index);

                        var dis_customer=$('.discount_id option:selected').attr('data-display');
                         $('.bulk_discount_percentage'+index).val(dis_customer);

                    });
                        
                    }
                    else
                    {
                        $('.bulk_product_id'+index).val(['']);
                        $('.bulk_product_id'+index).trigger('change');

                        notyMsg('info','Product Already Selected');

                    }
            }
            else
            {
            $('.bulk_uom_code_id'+index).select2('val','');
            $('.bulk_unit_price'+index).val(0);

            $('.bulk_tax_group_id'+index).val(['']);
            $('.bulk_tax_group_id'+index).trigger('change');

            $('.bulk_qoh'+index).val('');
            calc_by_index(index)
            }
    }
    else
    {
        $('.bulk_uom_code_id'+index).select2('val','');
        $('.bulk_unit_price'+index).val(0);
        $('.bulk_tax_group_id'+index).val(['']);
        $('.bulk_tax_group_id'+index).trigger('change');
        $('.bulk_product_id'+index).select2('val','');
        $('.bulk_qoh'+index).val('');
        calc_by_index(index);

        notyMsg('info','Please Select Pricelist !!!');
                event.preventDefault();

    }
}
else
{

        notyMsg('info','Please Select Customer !!!');
        $('.pricelist_id').val(['']);
        $('.pricelist_id').trigger('change');
        $('.bulk_uom_code_id'+index).select2('val','');
        $('.bulk_unit_price'+index).val(0);
        $('.bulk_tax_group_id'+index).val('');
        $('.bulk_qoh'+index).val('');
        $('.bulk_product_id' + index).val('').select2();    
        event.preventDefault();
}

});
    <?php } ?>
  
   
  /*deepika purpose: to load tax based on hsn code*/
    
    $(document).on('change','.bulk_hsn_code',function(){
        var hsnid=$(this).val();
        var index=$(this).closest('tr').index();
        var suppsiteid=$('.ship_to_address_id').val();
        var m_type="Sales";
        var taxval = $('.bulk_tax_excemption'+index).val();
        if(hsnid!="" && hsnid != 0 && suppsiteid!=""){
          var url="{{ URL::to('taxdetails')}}/"+hsnid+"/"+suppsiteid+"/"+m_type;
        if(taxval == ""){
          notyMsg('info','Please select Tax Excemption');
          $('.bulk_hsn_code'+index).select2('val',['']);
      }else
      if(taxval == "Yes"){
        $('.bulk_tax_group_id'+index).select2('val',['8']);
           
      }else{
           $.get(url,function(data){
               
            if(data['tax_group_id']==0)
            {
                if($.trim(data['tax_group_id_expiry'])=="expiry"){                  
                    $('.bulk_tax_group_id'+index).select2('val',[data['tax_group_id']]);
                    notyMsgs('info','Tax Group expired  for this product');
                }
                else if($.trim(data['tax_group_id_expiry'])=="location")
                {
                   notyMsgs('info','Tax not assigned for this Location');
                }
                else
                {
                    $('.bulk_tax_group_id'+index).select2('val',[data['tax_group_id']]);
                    notyMsgs('info','Tax Group not assigned for this product');
                }   
            }
            else
            {
                $('.bulk_tax_group_id'+index).select2('val',[data.tax_group_id]);
                calc_by_index(index);
            }
               
            
        });
       }
        }
    });

    /*end*/

    $(document).on('change','.pricelist_id',function(event)
{
    var pricelistid=$('.pricelist_id').val();
    var customer_id=$('.ship_to_customer_id option:selected').val();


<?php if($pageMethod !="pickorderfrominvoicecreate" && $pageMethod !="salesinvoiceapprovalview" && $pageMethod!= "salesinvoice" && $row->invoice_type!="SAMPLE") { ?>

    if(customer_id !='')
    {
        $('.bulk_product_id').each(function(index)
        {
            $(this).trigger('change');
        });
    }
    else
    {
        notyMsgs('info','Please Select Customer !!!');
        $('.customer_id').focus();
    }
    <?php } ?>

});



$(document).on('change','.bulk_qty',function()
{
    var index = $(this).closest("tr").index();
            var bulk_salesorder_qty = parseFloat($('.bulk_salesorder_qty'+index).val()).toFixed(decimal);
            var bulk_qty = parseFloat($('.bulk_qty'+index).val()).toFixed(decimal);
    if(bulk_qty>bulk_salesorder_qty){
        notyMsg("warning","Invoice qty not greater than Dispatch");
        $('.bulk_qty'+index).val('');
    }
});
    
        $(document).on('change','.discount_id',function()
{   $(".bulk_product_id").each(function(index){
        var dis_customer=$('.discount_id option:selected').attr('data-display');

    $('.bulk_discount_percentage'+index).val(dis_customer);
        var index = $(this).closest("tr").index();
        var unitprice = $('.bulk_unit_price'+index).val();
        var requiredqty = $('.bulk_qty'+index).val();
        var tds_amount = $('.tds_amount').val();
        var taxgrp = $('.bulk_tax_group_id'+index+' option:selected').attr('data-display');

        taxgrp = taxgrp?taxgrp:0;
        var discountsperc = $('.bulk_discount_percentage'+index).val();
        var disamout = parseFloat(((requiredqty * unitprice) * discountsperc/100)).toFixed(decimal);

        $('.bulk_discount_amount'+index).val(disamout);
        
        var other_total=parseFloat(((requiredqty * unitprice) - disamout));

var trade_dis_pre=$(".trade_discount_pre").val();
trade_dis_pre = trade_dis_pre?trade_dis_pre:0;

var amount=((parseFloat(other_total)*parseFloat(trade_dis_pre))/100).toFixed(decimal);

var other_total=parseFloat(other_total)-parseFloat(amount);

        var taxamount = parseFloat((other_total) * taxgrp /100);
       
        $('.bulk_tax_amount'+index).val(taxamount);
        var subtot = ((requiredqty * unitprice) - disamout - amount);
        var linetot=parseFloat(subtot + taxamount).toFixed($('#decimal_point').val());
            $(".bulk_line_total"+index).val(linetot);
        /* Code for set linetotal values into header level field*/
            var sum = 0;
            var sumtax = 0;
            var sumall=0;
            var charge=0;
            var tax =0;
            $('.charges').each(function(){
                var amt=$(this).val();
                if(amt=="")
                    amt=0;
                charge += parseFloat(amt);
            });

            $('.bulk_line_total').each(function()
            {
                sum += parseFloat($(this).val());
            });
            $('.bulk_tax_amount').each(function()
            {
                tax += parseFloat($(this).val());
            });
            
            sumall=parseFloat(charge).toFixed(decimal)+parseFloat(sum).toFixed(decimal);
            sumall=(isNaN(sumall) ? 0 : sumall);
            sumall = parseFloat(sumall).toFixed(decimal) - parseFloat(tds_amount).toFixed(decimal);
            sumall=(isNaN(sumall) ? 0 : sumall);
            sumtax=parseFloat(tax).toFixed("{{\Session::get('decimal')}}");
            $('#invoice_tax_total').val(parseFloat(sumtax).toFixed(decimal));
            $('#invoice_grand_total').val(parseFloat(sumall).toFixed(decimal));
            $('#balance_amount').val(parseFloat(sumall).toFixed(decimal));

          $(".tax_total_span").html(sumtax);
          
            $(".grand_total_span").html(parseFloat(sumall).toFixed(decimal));
            



        /* end */
        /* Code for calculating tot tax amount */
        
        
        $('.quote_tax').val(tax);
         $(".tax_total_span").html(tax);
        /* end */
});
});

    
    $(document).on('keyup change','.bulk_qty,.bulk_unit_price,.bulk_hsn_code,.bulk_discount_percentage,.bulk_tax_group_id,.bulk_product_id,.charges',function()
{
        var index = $(this).closest("tr").index();
        /*var dis_customer=$('.discount_id option:selected').attr('data-display');
        $('.bulk_discount_percentage'+index).val(dis_customer);*/
        
        var unitprice = parseFloat($('.bulk_unit_price'+index).val()).toFixed(decimal);
        var curr = $('.currency_rate').val();

        $('.bulk_unit_price'+index).val(parseFloat(unitprice).toFixed(decimal));

        var requiredqty = parseFloat($('.bulk_qty'+index).val()).toFixed(decimal);
        var tds_amount = parseFloat($('.tds_amount').val()).toFixed(decimal);
        var taxgrp = $('.bulk_tax_group_id'+index+' option:selected').attr('data-display');
        var discountsperc = $('.bulk_discount_percentage'+index).val();

        taxgrp = taxgrp?taxgrp:0;
        var disamout = parseFloat(((requiredqty * unitprice) * discountsperc/100));
        disamout=(isNaN(parseFloat(disamout))) ? 0 : parseFloat(disamout);
        $('.bulk_discount_amount'+index).val(disamout.toFixed(decimal));
var other_total=parseFloat(((requiredqty * unitprice) - disamout));

var trade_dis_pre=$(".trade_discount_pre").val();
trade_dis_pre = trade_dis_pre?trade_dis_pre:0;

var amount=((parseFloat(other_total)*parseFloat(trade_dis_pre))/100).toFixed(decimal);

var other_total=parseFloat(other_total)-parseFloat(amount);

        var taxamount = parseFloat((other_total) * taxgrp /100);
        taxamount=(isNaN(parseFloat(taxamount))) ? 0 : parseFloat(taxamount);
        $('.bulk_tax_amount'+index).val(taxamount.toFixed(decimal));
        
        var subtot = parseFloat((requiredqty * unitprice) - disamout - amount);
         subtot=(isNaN(parseFloat(subtot))) ? 0 : parseFloat(subtot);
         
        var linetot=parseFloat(subtot + taxamount).toFixed(decimal);
            $(".bulk_line_total"+index).val(linetot);
        /* Code for set linetotal values into header level field*/
            var sum = 0;
            var sumtax = 0;
            $('.bulk_line_total').each(function()
            {
                sum +=(isNaN(parseFloat($(this).val())) ? 0 : parseFloat($(this).val()));
            });
            
        /* end */
        /* Code for calculating tot tax amount */
        var tax =0;
        var sumall=0;
        var charge=0;
        var amt=0;
        $('.charges').each(function(){
            amt=$(this).val();
          if(amt==""){
              amt=0;
          }
            charge+=parseFloat(amt);
        });
        $('.bulk_tax_amount').each(function(){
           tax+= parseFloat($(this).val());
        });
        
        sumall=parseFloat(charge)+parseFloat(sum);
        sumall=(isNaN(sumall)) ? 0 : sumall;
        
        tds_amount = (isNaN(tds_amount) ? 0 : tds_amount)

        sumall = parseFloat(sumall) - parseFloat(tds_amount);
        sumall=(isNaN(sumall) ? 0 : sumall);
        
        sumtax=parseFloat(tax).toFixed("{{\Session::get('decimal')}}");
        $('#invoice_tax_total').val(sumtax);
        $('#balance_amount').val(parseFloat(sumall).toFixed(decimal));
        $('.quote_tax').val(sumtax);
        $(".tax_total_span").html(parseFloat(sumtax).toFixed(decimal));
        $('#invoice_grand_total').val(parseFloat(sumall).toFixed(decimal));
        $('.grand_total_span').html(parseFloat(sumall).toFixed(decimal));
        
        /* end */
        $('.tds_applicable').trigger('change'); 
});


$(document).on('keyup change','.bulk_discount_amount',function()
{
    var index = $(this).closest("tr").index();
    
    var unitprice = parseFloat($('.bulk_unit_price'+index).val()).toFixed(decimal);
    
    var tds_amount = parseFloat($('.tds_amount').val()).toFixed(decimal);
    var requiredqty = parseFloat($('.bulk_qty'+index).val()).toFixed(decimal);
    var taxgrp = $('.bulk_tax_group_id'+index+' option:selected').attr('data-display');
    taxgrp = taxgrp?taxgrp:0;
    var discountsperc = parseFloat($('.bulk_discount_percentage'+index).val()).toFixed(decimal);
    var discountamt = parseFloat($('.bulk_discount_amount'+index).val()).toFixed(decimal);
    var disamout = discountamt;
    var disamt = parseFloat((discountamt * 100)/(requiredqty * unitprice)).toFixed(decimal);
    $('.bulk_discount_percentage'+index).val(disamt);
    $('.bulk_discount_amount'+index).val(disamout);
var other_total=parseFloat(((requiredqty * unitprice) - disamout));

var trade_dis_pre=$(".trade_discount_pre").val();
trade_dis_pre = trade_dis_pre?trade_dis_pre:0;

var amount=((parseFloat(other_total)*parseFloat(trade_dis_pre))/100).toFixed(decimal);

var other_total=parseFloat(other_total)-parseFloat(amount);

        var taxamount = parseFloat((other_total) * taxgrp /100);
    $('.bulk_tax_amount'+index).val(taxamount);
    var subtot = parseFloat((requiredqty * unitprice) - disamout -amount).toFixed(decimal);
    var linetot=parseFloat(subtot + taxamount).toFixed($('#decimal_point').val());
        $(".bulk_line_total"+index).val(linetot);
    /* Code for set linetotal values into header level field*/
        var sum = 0;
        var sumtax = 0;
        var sumall=0;
        var charge=0;

       $('.charges').each(function(){
             var amt=$(this).val();
          if(amt=="")
              amt=0;
            charge +=parseFloat(amt);
        });

        $('.bulk_line_total').each(function()
        {
            sum += parseFloat($(this).val());
        });
        $('.bulk_tax_amount').each(function()
        {
            sumtax += parseFloat($(this).val());
        });
        sumall=parseFloat(charge).toFixed(decimal)+parseFloat(sum).toFixed(decimal);
        sumall = parseFloat(sumall).toFixed(decimal) - parseFloat(tds_amount).toFixed(decimal);
        sumtax=parseFloat(sumtax).toFixed("{{\Session::get('decimal')}}");
        sumall=(isNaN(sumall) ? 0 : sumall);
        $('#invoice_tax_total').val(sumtax);
        $('#invoice_grand_total').val(sumall);
        $('.tax_total_span').html(sumtax);
        $('.grand_total_span').html(sumall);
        
        $('#balance_amount').val(sumall);
    /* end */
    /* Code for calculating tot tax amount */
    var tax =0;
    $('.bulk_tax_amount').each(function(){
       tax+= parseFloat($(this).val()).toFixed(decimal);
    });
    $('.quote_tax').val(tax);
    /* end */
});
    function calc_by_index(index)
{
    
var unit_price = $('.bulk_unit_price'+index).val();
console.log(unit_price);
var qty = $('.bulk_qty'+index).val();
var tax_group = $('.bulk_tax_group_id'+index).val();
var line_sub_total = parseFloat(unit_price * qty).toFixed(decimal);
var tax_amount = parseFloat((line_sub_total*tax_group)/100).toFixed(decimal);

$('.bulk_tax_amount'+index).val(tax_amount);

var linetot=parseFloat(line_sub_total+tax_amount).toFixed($('#decimal_point').val());

/* var discount=parseFloat((discountsperc*linetot)/100).toFixed($('#decimal_point').val());
$('.bulk_discountsamt'+index).val(discount);
var maintotcal = parseFloat(linetot-discount).toFixed($('#decimal_point').val());
*/

$(".bulk_line_sub_total"+index).val(line_sub_total);
$(".bulk_line_total"+index).val(linetot);

/* Code for set linetotal values to header level via keyup*/

var lsbt = 0;
$('.bulk_line_sub_total').each(function()
{
lsbt += parseFloat($(this).val()).toFixed(decimal);
});

$('.order_sub_total').val(lsbt);

var sum = 0;
$('.bulk_line_total').each(function()
{
sum += parseFloat($(this).val()).toFixed(decimal);
});

$('.order_total').val(sum);

/* end */

/* Code for calculating tot tax amount */
var tax =0;
$('.bulk_tax_amount').each(function(){
tax+= parseFloat($(this).val()).toFixed(decimal);
});
$('.order_tax').val(tax);
/* end */

}





    $(document).on('keypress', '.bulk_unit_price,.bulk_qty,.bulk_discount_percentage,.pin_code,.contact_number', function(ev){
        var regex = new RegExp("^[0-9.]+$");
        var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
        if (regex.test(str))
        {
            return true;
        }
        ev.preventDefault();
        return false;
    });
    /*copy paste validation*/

     $('.bulk_qty,.bulk_discount_percentage').bind("cut copy paste", function(e) {
        e.preventDefault();
            });
    /*copy paste validation*/

var index = $('.clone').closest('tr').index();
changeclassfields();


$(document).on('click','.remove',function()
{


    var index = $(this).closest('tr').index();

    var rowCount = $('.sales_invoice_table tbody tr').length;
    if(rowCount > 1)
    {
        $($(this).closest("tr")).remove();
                removeClass('bulk_line_no');
                removeClass('bulk_product_id');
                removeClass('bulk_uom_code_id');
                removeClass('bulk_qty');
                removeClass('bulk_unit_price');
                removeClass('bulk_salesorder_qty');
                removeClass('bulk_discount_percentage');
                removeClass('bulk_discount_amount');
                removeClass('bulk_tax_excemption');
                removeClass('bulk_line_subtotal');
                removeClass('bulk_tax_group_id');
                removeClass('bulk_tax_amount');
                removeClass('bulk_line_total');
                removeClass('bulk_promised_date');
                removeClass('bulk_comments');
                removeClass('bulk_part_no');
                removeClass('bulk_sales_order');
                removeClass('bulk_sales_order_qty');
                removeClass('bulk_sales_order_invoice');
                removeClass('bulk_sales_order_invoiced');
                removeClass('bulk_invoiced_qty');
                removeClass('bulk_replacement');
    }
    else
    {
        notyMsg('info',"You Can't Delete Atleast One row should be there");
    }
    var sum = 0;
    var tax =0;

    $('.bulk_line_total').each(function(){

                sum += (isNaN(parseFloat($(this).val()).toFixed(decimal))) ? 0 : parseFloat($(this).val()).toFixed(decimal);

    });
    $('.bulk_tax_amount').each(function(){

             tax += (isNaN(parseFloat($(this).val()).toFixed(decimal))) ? 0 : parseFloat($(this).val()).toFixed(decimal);
    });
    $('#invoice_grand_total').val(sum);
    $('#balance_amount').val(sum);
    $('#invoice_tax_total').val(tax);
});

        
        $(document).on('click','.productsearch',function()
    {
          var customer_id=$('.ship_to_customer_id option:selected').val();

  if(customer_id!=''){ 
//karthigaa purpose for product search grid
$('.pdtbtn').parent('div').html('');
    var mypdtgrid = $("#productgrid"),
    pagerSelector = "#pager",
    myAddButton = function(options) {
        mypdtgrid.jqGrid('navButtonAdd',pagerSelector,options);
        mypdtgrid.jqGrid('navButtonAdd','#'+mypdtgrid[0].id+"_toppager",options);
    };
    var pricelist_id=$('.pricelist_id option:selected').val();
        var groupname="'FINISHED GOODS'";
                var grp=[];
                grp.push(groupname);

    var prdcatopt="{{ $prdcatopt}}";
    var prdnameopt="{{ $prdnameopt }}";
            mypdtgrid.jqGrid({
            url: "{{ URL::to('getProductgridData') }}?prggrp="+grp,
            datatype: "json",
            mtype: "GET",
            height: 320,
            width: 1000,
             colModel: [
             { name: "product_code", label: "Product Code", width:55},
    { name: "group_name", label: "Product Group", width:55},
    { name: "category_name", label: "Product Category"},
    { name: "concatenated_product", label: "Product Name"},
    { name: "product_id", label: "id",hidden:true, width:55}
        ],

            iconSet: "fontAwesome",
            rowNum: 10,
            rowList: [10,20,100,1000],
            sortorder: "asc",
            viewrecords: true,
            gridview: true,
            rownumbers:true,
            pager: pagerSelector,
            toppager:true,
            searching: {
            defaultSearch: "cn"
            }
           });
            jQuery(mypdtgrid).jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
            $("#gs_productgrid_product_category_id").select2();
mypdtgrid.jqGrid('navGrid',pagerSelector,
{cloneToTop:true,edit:false,add:false,del:false,search:true});
$('.ui-icon-refresh').hide();
myAddButton ({
caption:"Select Product",
title:"Product",
buttonicon :'ui-icon-plus pdtbtn',
        onClickButton:function()
        {
            var index = $('.pdtindex').val();
            var gr = jQuery(mypdtgrid).jqGrid('getGridParam','selrow');
            var product = jQuery(mypdtgrid).jqGrid ('getCell', gr, 'product_id');
            if(product != false )
            {
            $('.bulk_product_id'+index).select2('val',[product]);
            //$('.bulk_product_id'+index).trigger('change');
            $('#productModal').modal('hide');
            }
            else
            {
             notyMsg("info",'Please Select one row');
            }
        }
});
    var index = ($(this).closest('tr').index());
     $('.pdtindex').val(index);
     $('#productModal').modal('show');
     $('#productModal').width("100%");
    }
            else{
                notyMsg("info","Please select Customer");
            }
    });

/*deepika purpose:for customer search*/
      

    var mygrid = $("#customergrid"),
        pagerSelector = "#pager",
        myAddButton = function(options) {
        mygrid.jqGrid('navButtonAdd',pagerSelector,options);
        mygrid.jqGrid('navButtonAdd','#'+mygrid[0].id+"_toppager",options);
        };
        mygrid.jqGrid({
        url: "{{ URL::to('getCustomergridData') }}",
        datatype: "json",
        mtype: "GET",
                    height: 320,
                    width: 1000,
         colModel: [
                            { name: "customer_id", label: "id",hidden:true, width:55},
                        { name: "pincode", label: "pincode",hidden:true, width:55},
                        { name: "customer_site_id", label: "id",hidden:true, width:55},
                        { name: "customer_number", label: "Customer No", width:70},
                        { name: "customer_name", label: "Customer Name"},
                        { name: "customer_type", label: "Customer Type",width:75},
                        { name: "customer_site_name", label: "Site Name", width:75,},
                        { name: "site_type", label: "Site Type", width:55},
                        { name: "address", label: "Address", width:55},
                        { name: "city_name", label: "City", width:55},
                        { name: "state_name", label: "State", width:55},
                        { name: "country_name", label: "Country", width:55}, 
                             ],

                        iconSet: "fontAwesome",
                        rowNum: 10,
                        rowList: [10,20,100,1000],
                        sortorder: "asc",
                        viewrecords: true,
                        gridview: true,
                        rownumbers:true,
                    
                        pager: pagerSelector,
                        toppager:true,
                        searching: {
                        defaultSearch: "cn"
                        }
           });
//  (".ui-search-toolbar").hide();

        jQuery(mygrid).jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});

    $('.customersearch').click(function(){
            $('#customerModal').modal('show');
            $('#customerModal').width("100%");
            var ct=$(this).val();

            $('.custype').val(ct);
            $(mygrid).jqGrid('setGridParam', {
                postData: {"site_type":null,"cid":null }
            }).trigger('reloadGrid');
        
           $("#gs_customer_name").val('');
      $("#gs_site_type").val('');
        $("#gs_customer_number").val('');
       $("#gs_customer_name,#gs_site_type,#gs_customer_number").attr('readonly',false);
    });


       

 $(document).on('click','.new_billto',function(){

        var cid=$('.ship_to_customer_id option:selected').val();
setTimeout(function(){
$(':input','#newaddress')
  .not(':button, :submit, :reset, :hidden')
  .val('')
  .prop('checked', false)
  .prop('selected', false);

     },500);
     $('.city,.country,.state').select2('val',['']);
        if(cid!="")
        {
            $('#new_address').modal('show');
            $('#new_address').width("100%");
              var ct="BILL_TO";
        $('.custype').val(ct);
        }
        else
        {
            notyMsg("info","Please select Customer");
        }

});
$('.billto').click(function(){
  var cid=$('.ship_to_customer_id').val();
     var c_name=$.trim($('.ship_to_customer_id option:selected').text()).split("-");
   if(cid=="")
   {
    notyMsg('info',"Please select Customer first");
   }
   else
   {
       
         $('#customerModal').modal('show');
         $('#customerModal').width("100%");

           var ct=$(this).val();
        $('.custype').val(ct);
          var custype= $('.custype').val();

         if(ct=="billto")
         {
          var site_type="BILL_TO";
          }

        $(mygrid).jqGrid('setGridParam', 
         {
              postData: {"site_type":site_type,"cid":cid }
       }).trigger('reloadGrid');
      $("#gs_customer_name").val($.trim(c_name[1]));
      $("#gs_site_type").val(site_type);
        $("#gs_customer_number").val($.trim(c_name[0]));
       $("#gs_customer_name,#gs_site_type,#gs_customer_number").attr('readonly',true);
  }
 
    });


$(document).on('click','.new_shipto',function(){

        var cid=$('.ship_to_customer_id option:selected').val();
setTimeout(function(){
$(':input','#newaddress')
  .not(':button, :submit, :reset, :hidden')
  .val('')
  .prop('checked', false)
  .prop('selected', false);

     },500);
    $('.city,.country,.state').select2('val',['']);
        if(cid!="")
        {
            $('#new_address').modal('show');
            $('#new_address').width("100%");
            
           var ct="SHIP_TO";
        $('.custype').val(ct);
        }
        else
        {

            notyMsg("info","Please select Customer");
        }

});
     $('.shipto').click(function(){
  
  
  var cid=$('.ship_to_customer_id').val();
             var c_name=$.trim($('.ship_to_customer_id option:selected').text()).split("-");
   if(cid=="")
   {
    notyMsg('info',"Please select Customer first");
   }
   else
   {
       
         $('#customerModal').modal('show');
         $('#customerModal').width("100%");
     
           var ct=$(this).val();
        $('.custype').val(ct);
          var custype= $('.custype').val();

         if(ct=="shipto")
         {
          var site_type="SHIP_TO";
          }

        $(mygrid).jqGrid('setGridParam', 
         {
              postData: {"site_type":site_type,"cid":cid }
       }).trigger('reloadGrid');
           $("#gs_customer_name").val($.trim(c_name[1]));
      $("#gs_site_type").val(site_type);
        $("#gs_customer_number").val($.trim(c_name[0]));
       $("#gs_customer_name,#gs_site_type,#gs_customer_number").attr('readonly',true);
  }
 
    });
     



        mygrid.jqGrid('navGrid',pagerSelector,
        {cloneToTop:true,edit:false,add:false,del:false,search:true});
        myAddButton ({
        caption:"Select Customer",
        title:"Customer",
        buttonicon :'ui-icon-plus',
        onClickButton:function()
        {
            
                var gr = jQuery(mygrid).jqGrid('getGridParam','selrow');
        var customer = jQuery(mygrid).jqGrid ('getCell', gr, 'customer_id');
        var site_type = jQuery(mygrid).jqGrid ('getCell', gr, 'site_type');
        var customer_site_id = jQuery(mygrid).jqGrid ('getCell', gr, 'customer_site_id');
      
        if(gr)
        {
            var url="{{ url::to('custaddress') }}/"+customer+"/"+customer_site_id;
                $.get(url,function(data)
                {
                    var check=$('.ship_to_customer_id').select2('val');
                    if(check!=customer){
    $('.ship_to_customer_id').select2('val',[customer]);
                    }
                    
                if(data!=''){
                    if(site_type=="BILL_TO")
            {
        
            $('.bill_to_address_id').val(customer_site_id);
            $('.billing_to_address_txt').val(data);
            }
            else if(site_type=="SHIP_TO")
            {
            
            $('.ship_to_address_id').val(customer_site_id);
            $('.shipping_to_address_txt').val(data);
            }
                }
                    
                });
            
    
    
        $('#customerModal').modal('hide');
        }
        else
        {
        notyMsg('info','Please Select one row');
        }
        }
            
        
});
       $(".cityhide,.statehide").css('pointer-events','none');
     $(document).on('change', '.country', function ()
     {
    
        var country_id = $('.country').val();
         if(country_id!=""){
        $(".state").jCombo("{{ URL::to('jcomboformlogin?table=m_states_t:state_id:state_name') }}&parent=country_id="+country_id+ '&order_by=state_name asc',
        {selected_value:""});
     }
if(country_id!='')
$(".statehide").css('pointer-events','auto');
            $(".city").find('option').not(':first').remove();
    });
            
    

    $(document).on('change', '.state', function () {

                var state_id = $('.state').val();
if(state_id !="" ){
        $(".city").jCombo("{{ URL::to('jcomboformlogin?table=m_cities_t:city_id:city_name') }}&parent=state_id="+state_id+ '&order_by=city_name asc',
        {selected_value:""});
    $(".cityhide").css('pointer-events','auto');
}
    });
    $('.pricelist_id').trigger('change');
    
    
    /*end*/
    




            function changeClassName(className)
            {
                $('.' + className).each(function (index)
                {
                    if (className == "bulk_line_no")
                    {
                        $(this).val(index + 1).attr("readonly", 1);
                    }
                    $(this).removeClass(className + '0');
                    $(this).addClass(className + index);
                });
            }

/************ karthigaa purpose to remove row action ********************/
function removeClass(className)
{
    var rowCount = $('.sales_invoice_table tbody tr').length;
    for(var i=0;i<=rowCount;i++)
    {
    $('.sales_invoice_table tbody tr').find('.'+className).removeClass(className+i);
    }
    $('.' + className).each(function (index)
    {
        if (className == "bulk_line_no")
        {
        $(this).val(index + 1).attr("readonly", 1);
        }
        $(this).addClass(className + index);
    });

}

function changeclassfields(){
changeClassName('bulk_replacement_line_id');
changeClassName('bulk_line_no');
changeClassName('bulk_product_id');
changeClassName('bulk_uom_code_id');
changeClassName('bulk_qty');
changeClassName('bulk_unit_price');
changeClassName('bulk_salesorder_qty');
changeClassName('bulk_discount_percentage');
changeClassName('bulk_discount_amount');
changeClassName('bulk_tax_excemption');
changeClassName('bulk_line_subtotal');
changeClassName('bulk_tax_group_id');
changeClassName('bulk_tax_amount');
changeClassName('bulk_line_total');
changeClassName('bulk_promised_date');
changeClassName('bulk_comments');
changeClassName('bulk_qoh');
changeClassName('bulk_hsn_code');
changeClassName('bulk_part_no');
changeClassName('bulk_sales_order');
changeClassName('bulk_sales_order_qty');
changeClassName('bulk_sales_order_invoice');
changeClassName('bulk_sales_order_invoiced');
changeClassName('bulk_invoiced_qty');
changeClassName('bulk_replacement');

}
    });

</script>

@endpush
