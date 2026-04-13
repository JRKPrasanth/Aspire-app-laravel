@extends('layouts.header')
@section('content')
<h3 class="text-danger">Sales Invoice</h3>
@include('layouts.breadcrumb')
<?php error_reporting(0); ?>


<form method="post" action="" id="salesinvoice" data-parsley-validate>

    <div class="card shadow-lg rounded-4 border-0">
        <input type="hidden" id="decimal_point" value="" />
        <input type="hidden" value="{{$savestatus }}" name="savestatus" id="savestatus" />
        {{ csrf_field() }}


        <div class="card-header border-bottom" style="background:#d1d1ff;">
            <div class="row g-3 align-items-start">

                <!-- Column 1 -->
                <div class="col-md-3">
                    <p class="mb-1">
                        <strong>Invoice Date:</strong>
                        <strong class="badge bg-success"> {{ date(\Session::get('p_date_format'), strtotime($row->invoice_date)) }}  </strong>
                    </p>
                    <p class="mb-0">
                        <strong>Invoice Type:</strong>
                        <span class="badge bg-secondary">{{ $row->invoice_type }}</span>
                    </p>
                </div>

                <!-- Column 2 -->
                <div class="col-md-3">
                    <p class="mb-1">
                        <strong>Reference No:</strong> <span class="badge bg-primary fw-semibold">{{ $row->reference_number }}</span>
                    </p>
                    <p class="mb-0">
                        <strong>Created By:</strong>
                        <span class="badge bg-primary fw-semibold create_by">{{ $row->created_by ?? '' }}</span>
                    </p>
                </div>

                <!-- Column 3 -->
                <div class="col-md-3">
					@php
					$taxtot1 = str_replace(',', '', number_format((float)$row->invoice_tax_total ?? 0, \Session::get("decimal")));
					$grdtot1 = str_replace(',', '', number_format((float)$row->invoice_grand_total ?? 0, \Session::get("decimal")));
					@endphp

                    <p class="mb-1">
                        <strong>Source:</strong>
                        <span class="badge bg-secondary source_span">{{ $row->source }}</span>
                    </p>
                    <p class="mb-0">
                        <strong>Grand Total:</strong>
                        <span class="badge bg-success fw-bold grand_total_span">{{ $grdtot1 }}</span>
                    </p>
                </div>

                <!-- Column 4 -->
                <div class="col-md-3">
                    <p class="mb-1">
                        <strong>Tax Total:</strong>
                        <span class="badge bg-primary tax_total_span">{{ $taxtot1 }}</span>
                    </p>
                    <p class="mb-0">
                        <strong>Qty Total:</strong>
     
                        <span class="badge bg-secondary qty_span">
                            {{ str_replace(',', '', number_format((float)$row->qty_total, \Session::get("decimal"))) }}
                        </span>
                    </p>
                </div>

            </div>
        </div>

        <div class="card-body card-block">

            <div class="row g-4">
                <!-- Left Column -->
                <div class="col-lg-4">

                    <!-- Invoice No -->
                    <div class="mb-3 row">
                        <label for="invoice_number" class="col-sm-4 col-form-label">Invoice No</label>
                        <div class="col-sm-8">
                            <input type="hidden" name="invoice_hdr_id" value="{{ $row->invoice_hdr_id }}">
                            <input type="text" id="invoice_number" name="invoice_number" class="form-control invoice_number"
                                value="{{ $row->invoice_number }}" readonly>
                        </div>
                    </div>

                    <!-- Invoice Date -->
                    <div class="mb-3 row">
                        <label for="invoice_date" class="col-sm-4 col-form-label">Invoice Date</label>
                        <div class="col-sm-8">
                            <input type="text" id="invoice_date" name="invoice_date" class="form-control invoice_date"
                                value="{{ $row->invoice_date }}">
                        </div>
                    </div>

                    <!-- Freight Carrier -->
                    <div class="mb-3 row">
                        <label for="ar_frieghtcarriers_hdr_id" class="col-sm-4 col-form-label text-danger fw-bold">*
                            Freight Carrier</label>
                        <div class="col-sm-8 d-flex align-items-center">
                            <select id="ar_frieghtcarriers_hdr_id" name="ar_frieghtcarriers_hdr_id"
                                class="form-select select2 ar_frieghtcarriers_hdr_id" required>
                                {!! $ar_frieghtcarriers_hdr_id !!}
                            </select>
                        </div>
                    </div>

                    <!-- LR No -->
                    <div class="mb-3 row">
                        <label for="lr_no" class="col-sm-4 col-form-label">LR No</label>
                        <div class="col-sm-8">
                            <input type="text" id="lr_no" name="lr_no" class="form-control lr_no" value="{{ $row->lr_no }}">
                            <input name="employee_id" type="hidden" value="0">
                        </div>
                    </div>

                    <!-- LR Date -->
                    <div class="mb-3 row">
                        <label for="lr_date" class="col-sm-4 col-form-label">LR Date</label>
                        <div class="col-sm-8">
                            <input type="text" id="lr_date" name="lr_date" class="form-control lr_date"
                                value="{{ $lr_date }}">
							     <input type="hidden" name="source" class="source" value="{{$row->source}}" >
                                    <input type="hidden" name="reference_source_id" class="reference_source_id" value="{{$row->reference_source_id}}" >
                                    <input type="hidden" name="ar_sales_hdr_id" class="ar_sales_hdr_id" value="{{$row->ar_sales_hdr_id}}" >
                        </div>
                    </div>

													<div class="form-group row" style="display:none">
									<label for="inputIsValid" class="form-control-label col-md-4">Invoice Type</label>
									<div class="col-md-6">
										<select type="invoice_type" name="invoice_type" id="invoice_type" class="form-control invoice_type"  readonly>
											<option value="">--select--</option>
											<option <?php if($row->invoice_type =="STANDARD") { echo "selected"; } else { echo ""; } ?> value="STANDARD">STANDARD</option>
											<option <?php if($row->invoice_type =="LABOUR") { echo "selected"; } else { echo ""; } ?> value="LABOUR">LABOUR</option>
											<option <?php if($row->invoice_type =="SAMPLE") { echo "selected"; } else { echo ""; } ?> value="SAMPLE">SAMPLE</option>
											<option <?php if($row->invoice_type =="REPLACEMENT") { echo "selected"; } else { echo ""; } ?> value="REPLACEMENT">REPLACEMENT</option>
											<option <?php if($row->invoice_type =="EXPORT INVOICE") { echo "selected"; } else { echo ""; } ?> value="EXPORT INVOICE">EXPORT INVOICE</option>
											<option <?php if($row->invoice_type =="EXPORT SAMPLE") { echo "selected"; } else { echo ""; } ?> value="EXPORT SAMPLE">EXPORT SAMPLE</option>
										</select>
									</div>
									<div class="col-md-2">
									</div>
								</div>
					
                    <!-- Due Date -->
                    <div class="mb-3 row">
                        <label for="due_date" class="col-sm-4 col-form-label text-danger fw-bold">* Due Date</label>
                        <div class="col-sm-8">
                            <input type="text" id="due_date" name="due_date" class="form-control due_date"
                                value="{{ $row->due_date }}">
                        </div>
                    </div>

					                            <div class="form-group row" style="display:none">
                                <label for="inputIsValid" class="form-control-label col-md-4">Reference No</label>
                                <div class="col-md-6">
                                    <input class="form-control reference_id" id="reference_id" name="reference_id" size="16" type="hidden" value="{{$row->reference_id }}" readonly="">
                                    <input type="text" id="reference_number" name="reference_number" class="form-control reference_number" value="{{ $row->reference_number }}" readonly="">
                                </div>
                                <div col-md-2>

                                </div>
                            </div>

                            <div class="form-group row" style="display:none">
                                <label for="inputIsValid" class="form-control-label col-md-4">dis_record_status</label>
                                <div class="col-md-6 stdivhide">
                                    <input type="hidden" name="dis_record_status" id="dis_record_status" class="form-control dis_record_status" value="">
                                </div>
                                <div class="col-md-2">
                                </div>
                            </div>

                                <div class="form-group row" style="display:none">
                                <label for="inputIsValid" class="form-control-label col-md-4">Invoice Tax Total</label>
                                <div class="col-md-6">
                                    <input type="text" name="invoice_tax_total" id="invoice_tax_total" value="{{ $row->invoice_tax_total }}" class="form-control invoice_tax_total" readonly>
                                </div>
                                <div class="col-md-2">
                                </div>
                            </div>
                            <div class="form-group row" style="display:none">
                                <label for="inputIsValid" class="form-control-label col-md-4">Invoice Grand Total</label>
                                <div class="col-md-6">
                                <?php  //dd($row);?>
                                    <input type="text" name="invoice_grand_total" id="invoice_grand_total" value="{{ $row->invoice_grand_total }}" class="form-control invoice_grand_total" readonly>
                                    <input type="text" name="balance_amount" id="balance_amount" value="{{ $row->balance_amount }}" class="form-control balance_amount" readonly>
                                </div>
                                <div class="col-md-2">
                                </div>
                            </div>

                            <div class="form-group row" style="display:none;">
<label class="form-control-label col-md-4" for="qty_total"><span style="color:red;">*</span>Qty Total</label>

<div class="col-md-6">
<input type="text" name="qty_total" id="qty_total" class="form-control qty_total"  value="{{ $row->qty_total }}" readonly required data-required="numeric">
</div>
</div>
					
                    <!-- Round Off -->
                    <div class="mb-3 row">
                        <label for="round_off" class="col-sm-4 col-form-label">Round Off</label>
                        <div class="col-sm-8">
                            <input type="text" id="round_off" name="round_off" class="form-control round_off"
                                value="{{ $row->round_off }}">
                        </div>
                    </div>
                </div>

                <!-- Middle Column -->
                <div class="col-lg-4">

                    <!-- Customer -->
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label text-danger fw-bold">* Customer</label>
                        <div class="col-sm-8 d-flex align-items-center">
                            <select name="ship_to_customer_id" class="form-select select2 ship_to_customer_id" required>
                                {!! $ship_to_customer_id !!}
                            </select>
                            <button type="button" class="btn btn-outline-secondary btn-sm ms-2">
                                <i class="fa fa-search customersearch"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Bill To Address -->
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Bill To Address</label>
                        <div class="col-sm-8">
						<input type="hidden" name="bill_to_address_id" id="bill_to_address_id" class="form-control   bill_to_address_id" value="{{ $bill_to_address_id }}" >	
                            <textarea class="form-control billing_to_address_txt" id="billing_to_address_txt" rows="2" readonly>{{ $bill_to_address }}</textarea>
                            <div class="mt-2 d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-success billto">
                                    <i class="fa fa-address-book"></i> Change
                                </button>
                                <button type="button" class="btn btn-sm btn-primary new_billto">
                                    <i class="fa fa-plus"></i> New
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Status -->
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Invoice Status</label>
                        <div class="col-sm-8">
                            <select id="invoice_status" name="invoice_status" class="form-select select2 invoice_status" readonly>
                                <option value="">--select--</option>
                                <option {{ $row->invoice_status=="DRAFT" ? 'selected' : '' }}>DRAFT</option>
                                <option {{ $row->invoice_status=="INITIATED" ? 'selected' : '' }}>INITIATED</option>
                                <option {{ $row->invoice_status=="APPROVED" ? 'selected' : '' }}>APPROVED</option>
                                <option {{ $row->invoice_status=="REJECTED" ? 'selected' : '' }}>REJECTED</option>
                                <option {{ $row->invoice_status=="CANCELLED" ? 'selected' : '' }}>CANCELLED</option>
                            </select>
                        </div>
                    </div>

                    <!-- Remarks -->
                    <div class="mb-3 row">
                        <label for="remarks" class="col-sm-4 col-form-label text-danger fw-bold">* Remarks</label>
                        <div class="col-sm-8">
                            <input type="text" id="remarks" name="remarks" class="form-control remarks"
                                value="{{ $row->remarks }}">
                        </div>
                    </div>

                    <!-- Exchange Rate (conditional) -->
                    @if($row->invoice_type=="EXPORT INVOICE" || $row->invoice_type=="EXPORT SAMPLE")
                    <div class="mb-3 row">
                        <label for="con_exc_rate" class="col-sm-4 col-form-label text-danger fw-bold fst-italic">*
                            Exchange Rate</label>
                        <div class="col-sm-8">
                            <input type="text" id="con_exc_rate" name="con_exc_rate" class="form-control con_exc_rate"
                                value="{{ $row->con_exc_rate }}" readonly>
                        </div>
                    </div>
                    @endif

                    @if($row->invoice_type=="SAMPLE")
                    <div class="form-group row">
                    <label class="form-control-label col-md-4" for="employee_id"><span class="" style="color:red;">*</span>Employee</label>
                    <div class="col-md-6 rdonlydiv">
                    <select name='employee_id'  class='form-control employee_id select2' id="employee_id">
                    {!! $row->employee_id !!}
                    </select>
                    </div>
                        <div class="col-md-2 showinline">
                            <span class="showspan"><i class="fa fa-refresh jcr_employee_id"></i></span>
                        </div>
                    </div>
                    @endif

                </div>

                <!-- Right Column -->
                <div class="col-lg-4">

                    <!-- Pricelist -->
                    <div class="mb-3 row">
                        <label for="pricelist_id" class="col-sm-4 col-form-label text-danger fw-bold text-center">*
                            PriceList</label>
                        <div class="col-sm-8">
                            <select id="pricelist_id" name="invoice_pricelist_id" class="form-select select2 pricelist_id" required>
                                {!! $pricelist !!}
                            </select>
                        </div>
                    </div>

                    <!-- Ship To Address -->
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Ship To Address</label>
                        <div class="col-sm-8">
						<input type="hidden" name="ship_to_address_id" id="ship_to_address_id" class="form-control ship_to_address_id"  value="{{ $ship_to_address_id }}" >	
                            <textarea class="form-control shipping_to_address_txt" id="shipping_to_address_txt" rows="2" readonly>{{ $ship_to_address }}</textarea>
                            <div class="mt-2 d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-success shipto">
                                    <i class="fa fa-address-book"></i> Change
                                </button>
                                <button type="button" class="btn btn-sm btn-primary new_shipto">
                                    <i class="fa fa-plus"></i> New
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- File Upload -->
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">File Upload</label>
                        <div class="col-sm-8">
                            <input type="file"  name="choosefile[]" class="form-control choosefile" multiple>
                            <small class="text-muted">Max file size: 10MB</small>
                            <div class="mt-2">
                                <table class="table table-sm table-bordered" id="file_choosen">
                                    <tbody id="fp">
                                        <!-- Uploaded files loop here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Schemes -->
                    <div class="mb-3 row">
                        <label for="schemes" class="col-sm-4 col-form-label">Schemes</label>
                        <div class="col-sm-8 d-flex align-items-center">
						
                            <select id="schemes" name="schemes[]" class="form-select select2 schemes" multiple>
                                {!! $schemes !!}
                            </select>
                            <button type="button" class="btn btn-success btn-sm ms-2 apply">Apply</button>
                        </div>
                    </div>

                    <!-- Cash Discount -->
                    <div class="mb-3 row">
                        <label for="cash_discount" class="col-sm-4 col-form-label">Cash Discount</label>
                        <div class="col-sm-8 d-flex align-items-center">
                            <input type="hidden" class="cash_discount_amount" value="0">
                            <select id="cash_discount" name="cash_discount" class="form-select cash_discount select2">
                                {!! $cash_schemes !!}
                            </select>
                            <button type="button" class="btn btn-success btn-sm ms-2 cash_discount_type">Apply</button>
                        </div>
                    </div>

                </div>
            </div>


            <div class="row mt-2">
                <div class="col-md-12">
                    <h4 class="text-primary fw-semibold mb-3">Additional Details</h4>
                </div>

		<div class="col-12">


    <div class="card-body pt-0">
        <!-- Responsive grid: 1 → 2 → 3 columns -->
        <div class="row g-3 g-md-4" id="dynamic-form-grid">

            <?php
            // Trackers used in original snippet
            $i = 0;
            $j = 0;
            $show_div = 0;

            foreach ($enabled_columns as $index => $val) {
                $isRequired = ($val->action == '1');
                if ($isRequired) {
                    $show_div = 1;
                }
                $requiredAttr = $isRequired ? 'required' : '';

                // Start each field as a column tile
                echo '<div class="col-12 col-md-6 col-lg-4">';

                /* -----------------------------
                   PAYMENT TERM
                ------------------------------*/
                if ($val->column_name == 'payment_term_id' && $val->active == 1) {
                    $i++;
                    ?>
                    <div class="mb-3">
                        <label class="form-label">Payment Term
                            <?php if ($isRequired) { ?><span class="text-danger ms-1">*</span><?php } ?>
                        </label>
                        <select name="payment_term_id" class="form-select select2 payment_term_id" data-show-subtext="true"
                            data-live-search="true" tabindex="8" <?php echo $requiredAttr; ?>>
                            {!! $payment_term_id !!}
                        </select>
                    </div>
                <?php }

                /* -----------------------------
                   PAYMENT METHOD
                ------------------------------*/
                if ($val->column_name == 'payment_method_id' && $val->active == 1) {
                    $i++;
                    ?>
                    <div class="mb-3">
                        <label class="form-label">Payment Method
                            <?php if ($isRequired) { ?><span class="text-danger ms-1">*</span><?php } ?>
                        </label>
                        <select name="payment_method_id" class="form-select select2 payment_method_id" data-show-subtext="true"
                            data-live-search="true" tabindex="9" <?php echo $requiredAttr; ?>>
                            {!! $payment_method_id !!}
                        </select>
                    </div>
                <?php }

                /* -----------------------------
                   CREATED BY (hidden/read-only)
                ------------------------------*/
                if ($val->column_name == 'created_by' && $val->active == 1) {
                    $i++;
                    ?>
                    <div class="mb-3 none">
                        <label class="form-label">Created By
                            <?php if ($isRequired) { ?><span class="text-danger ms-1">*</span><?php } ?>
                        </label>
                        <select name="created_by" class="form-select created_by" data-show-subtext="true"
                            data-live-search="true" readonly <?php echo $requiredAttr; ?>>
                            {!! $created_by !!}
                        </select>
                    </div>
                <?php }

                /* -----------------------------
                   APPROVED DATE
                ------------------------------*/
                if ($val->column_name == 'approved_date' && $val->active == 1) {
                    $i++;
                    ?>
                    <div class="mb-3">
                        <label class="form-label">Approved Date
                            <?php if ($isRequired) { ?><span class="text-danger ms-1">*</span><?php } ?>
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control datepicker approved_date" id="approved_date"
                                name="approved_date"
                                value="<?php echo ($approved_date == '0000-00-00') ? '' : e($approved_date); ?>" size="16"
                                tabindex="10">
                            <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                        </div>
                    </div>
                <?php }

                /* -----------------------------
                   TRADE DISCOUNT
                ------------------------------*/
                if ($val->column_name == 'trade_discount' && $val->active == 1) {
                    $i++;
                    ?>
                    <div class="mb-3">
                        <label for="trade_discount" class="form-label">Trade Discount
                            <?php if ($isRequired) { ?><span class="text-danger ms-1">*</span><?php } ?>
                        </label>
                        <input type="text" class="form-control trade_discount" id="trade_discount" name="trade_discount"
                            value="{{ $row->trade_discount }}" tabindex="10">
                        <input type="hidden" class="form-control" id="trade_discount_pre" name="trade_discount_pre"
                            value="{{ $row->trade_discount_pre }}">
                    </div>
                <?php }

                /* -----------------------------
                   ORGANIZATION (hidden, readonly)
                ------------------------------*/
                if ($val->column_name == 'organization' && $val->active == 1) {
                    $i++;
                    ?>
                    <div class="mb-3 none">
                        <label class="form-label">Organization
                            <?php if ($isRequired) { ?><span class="text-danger ms-1">*</span><?php } ?>
                        </label>
                        <select name="organization" class="form-select organization" data-show-subtext="true"
                            data-live-search="true" readonly <?php echo $requiredAttr; ?>>
                            {!! $organization_id !!}
                        </select>
                    </div>
                <?php }

                /* -----------------------------
                   APPROVER COMMENTS
                ------------------------------*/
                if ($val->column_name == 'approver_commnents' && $val->active == 1) {
                    $i++;
                    ?>
                    <div class="mb-3">
                        <label class="form-label">Approver Comments
                            <?php if ($isRequired) { ?><span class="text-danger ms-1">*</span><?php } ?>
                        </label>
                        <input type="text" name="approver_comments" class="form-control approver_comments"
                            value="{{ $approver_comments }}" data-show-subtext="true" data-live-search="true" tabindex="11"
                            <?php echo $requiredAttr; ?>>
                    </div>
                <?php }

                /* -----------------------------
                   INVOICE CURRENCY
                ------------------------------*/
                if ($val->column_name == 'invoice_currency' && $val->active == 1) {
                    $i++;
                    ?>
                    <div class="mb-3">
                        <label class="form-label">Invoice Currency
                            <?php if ($isRequired) { ?><span class="text-danger ms-1">*</span><?php } ?>
                        </label>
                        <input type="hidden" id="currency_rate" class="currency_rate" value="">
                        <select name="invoice_currency" class="form-select select2 invoice_currency" data-show-subtext="true"
                            data-live-search="true" tabindex="15" <?php echo $requiredAttr; ?>>
                            {!! $invoice_currency !!}
                        </select>
                    </div>
                <?php }

                /* -----------------------------
                   CUSTOMER COMMENTS
                ------------------------------*/
                if ($val->column_name == 'customer_comments' && $val->active == 1) {
                    $i++;
                    ?>
                    <div class="mb-3">
                        <label class="form-label">Customer Comments
                            <?php if ($isRequired) { ?><span class="text-danger ms-1">*</span><?php } ?>
                        </label>
                        <input type="text" name="customer_comments" class="form-control customer_comments"
                            value="{{ $customer_comments }}" data-show-subtext="true" data-live-search="true" tabindex="12"
                            <?php echo $requiredAttr; ?>>
                    </div>
                <?php }

                /* -----------------------------
                   SALES PERSON
                ------------------------------*/
                if ($val->column_name == 'salesperson_id' && $val->active == 1) {
                    $i++;
                    ?>
                    <div class="mb-3">
                        <label class="form-label">Sales Person
                            <?php if ($isRequired) { ?><span class="text-danger ms-1">*</span><?php } ?>
                        </label>
                        <select name="salesperson_id" class="form-select select2 salesperson_id" data-show-subtext="true"
                            data-live-search="true" tabindex="13" <?php echo $requiredAttr; ?>>
                            {!! $salesperson_id !!}
                        </select>
                    </div>
                <?php }

                /* -----------------------------
                   DELIVERY TERM
                ------------------------------*/
                if ($val->column_name == 'delivery_term_id' && $val->active == 1) {
                    $i++;
                    ?>
                    <div class="mb-3">
                        <label class="form-label">Delivery Term
                            <?php if ($isRequired) { ?><span class="text-danger ms-1">*</span><?php } ?>
                        </label>
                        <select name="delivery_term_id" class="form-select select2 delivery_term_id" data-show-subtext="true"
                            data-live-search="true" tabindex="16" <?php echo $requiredAttr; ?>>
                            {!! $delivery_term_id !!}
                        </select>
                    </div>
                <?php }

                /* -----------------------------
                   PROJECT NAME
                ------------------------------*/
                if ($val->column_name == 'project_id' && $val->active == 1) {
                    $i++;
                    ?>
                    <div class="mb-3">
                        <label class="form-label">Project Name
                            <?php if ($isRequired) { ?><span class="text-danger ms-1">*</span><?php } ?>
                        </label>
                        <select name="project_id" class="form-select select2 project_id" data-show-subtext="true"
                            data-live-search="true" tabindex="14" <?php echo $requiredAttr; ?>>
                            {!! $project_id !!}
                        </select>
                    </div>
                <?php }

                /* -----------------------------
                   TDS (Applicable / Amount / % / Account)
                ------------------------------*/
                if ($val->column_name == 'tds_applicable' && $val->active == 1) {
                    $i++;
                    ?>
                    <div class="mb-3">
                        <label class="form-label"><span class="text-danger">*</span> TDS Applicable</label>
                        <select name="tds_applicable" class="form-select select2 tds_applicable" data-show-subtext="true"
                            data-live-search="true" tabindex="17" <?php echo $requiredAttr; ?>>
                            <option value="">--Please Select--</option>
                            <option value="YES" <?php if ($row->tds_applicable == 'YES')
                                echo 'selected'; ?>>YES</option>
                            <option value="NO" <?php if ($row->tds_applicable == 'NO')
                                echo 'selected'; ?>>NO</option>
                        </select>
                    </div>
                <?php }

                if ($val->column_name == 'tds_amount' && $val->active == 1) {
                    $i++; ?>
                    <div class="mb-3">
                        <label class="form-label">TDS Amount</label>
                        <input type="text" name="tds_amount" id="tds_amount" class="form-control tds_amount"
                            value="{{ $row->tds_amount }}" readonly tabindex="15" <?php echo $requiredAttr; ?>>
                    </div>
                <?php }

                if ($val->column_name == 'tds_prcnt' && $val->active == 1) {
                    $i++; ?>
                    <div class="mb-3">
                        <label class="form-label">TDS Percentage</label>
                        <input type="text" name="tds_prcnt" id="tds_prcnt" class="form-control tds_prcnt"
                            value="{{ $row->tds_prcnt }}" readonly tabindex="14" <?php echo $requiredAttr; ?>>
                    </div>
                <?php }

                if ($val->column_name == 'tds_account_id' && $val->active == 1) {
                    $i++; ?>
                    <div class="mb-3">
                        <label class="form-label">TDS Account</label>
                        <select name="tds_account_id" class="form-select select2 tds_account_id" readonly tabindex="18" <?php echo $requiredAttr; ?>>
                            {!! $tds_account_id !!}
                        </select>
                    </div>
                <?php }

                /* -----------------------------
                   TCS (Applicable / Calc Amount / Amount / % / Account)
                ------------------------------*/
                if ($val->column_name == 'tcs_applicable' && $val->active == 1) {
                    $i++; ?>
                    <div class="mb-3">
                        <label class="form-label"><span class="text-danger">*</span> TCS Applicable</label>
                        <select name="tcs_applicable" class="form-select select2 tcs_applicable" data-show-subtext="true"
                            data-live-search="true" tabindex="17" <?php echo $requiredAttr; ?>>
                            <option value="">--Please Select--</option>
                            <option value="YES" <?php if ($row->tcs_applicable == 'YES')
                                echo 'selected'; ?>>YES</option>
                            <option value="NO" <?php if ($row->tcs_applicable == 'NO')
                                echo 'selected'; ?>>NO</option>
                        </select>
                    </div>
                <?php }

                if ($val->column_name == 'tcs_amount' && $val->active == 1) {
                    $i++; ?>
                    <div class="mb-3">
                        <label class="form-label">TCS Calc Amount</label>
                        <input type="text" name="tcs_calc_amount" id="tcs_calc_amount" class="form-control tcs_calc_amount"
                            value="{{ $row->tcs_calc_amount }}" tabindex="15" <?php echo $requiredAttr; ?>>
                    </div>
                <?php }

                if ($val->column_name == 'tcs_calc_amount' && $val->active == 1) {
                    $i++; ?>
                    <div class="mb-3">
                        <label class="form-label">TCS Amount</label>
                        <input type="text" name="tcs_amount" id="tcs_amount" class="form-control tcs_amount"
                            value="{{ $row->tcs_amount }}" readonly tabindex="15" <?php echo $requiredAttr; ?>>
                    </div>
                <?php }

                if ($val->column_name == 'tcs_prcnt' && $val->active == 1) {
                    $i++; ?>
                    <div class="mb-3">
                        <label class="form-label">TCS Percentage</label>
                        <input type="text" name="tcs_prcnt" id="tcs_prcnt" class="form-control tcs_prcnt"
                            value="{{ $row->tcs_prcnt }}" readonly tabindex="14" <?php echo $requiredAttr; ?>>
                    </div>
                <?php }

                if ($val->column_name == 'tcs_account_id' && $val->active == 1) {
                    $i++; ?>
                    <div class="mb-3">
                        <label class="form-label">TCS Account</label>
                        <select name="tcs_account_id" class="form-select select2 tcs_account_id" readonly tabindex="18" <?php echo $requiredAttr; ?>>
                            {!! $tcs_account_id !!}
                        </select>
                    </div>
                <?php }

                /* -----------------------------
                   PACKAGING / INSURANCE / OTHER TAX / OTHER FREIGHT / TRANSPORT
                ------------------------------*/
                if ($val->column_name == 'packaging_charges' && $val->active == 1) {
                    $i++; ?>
                    <div class="mb-3">
                        <label class="form-label">Packaging Charges <?php if ($isRequired) { ?><span
                                    class="text-danger ms-1">*</span><?php } ?></label>
                        <div class="d-flex gap-2 align-items-center">
                            <input type="text" name="packaging_charges" id="packaging_charges"
                                class="form-control charges packaging_charges" value="{{ $row->packaging_charges }}" readonly>
                            <button type="button" class="btn btn-light border refbtnhide packingtax"
                                data-value="Packaging Charges" data-at="1" title="Add tax line">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                        <input type="hidden" name="packaging_charges_tax" id="packaging_charges_tax"
                            value="{{ $row->packaging_charges_tax }}" class="form-control packaging_charges_tax">
                    </div>
                <?php }

                if ($val->column_name == 'insurance_charges' && $val->active == 1) {
                    $i++; ?>
                    <div class="mb-3">
                        <label class="form-label">Insurance Charges <?php if ($isRequired) { ?><span
                                    class="text-danger ms-1">*</span><?php } ?></label>
                        <div class="d-flex gap-2 align-items-center">
                            <input type="text" name="insurance_charges" id="insurance_charges"
                                class="form-control charges insurance_charges" value="{{ $row->insurance_charges }}" readonly>
                            <button type="button" class="btn btn-light border refbtnhide packingtax"
                                data-value="Insurance Charges" data-at="3" title="Add tax line">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                        <input type="hidden" name="insurance_charges_tax" id="insurance_charges_tax"
                            value="{{ $row->insurance_charges_tax }}" class="form-control insurance_charges_tax">
                    </div>
                <?php }

                if ($val->column_name == 'other_tax_amount' && $val->active == 1) {
                    $i++; ?>
                    <div class="mb-3">
                        <label class="form-label">Other Tax Amount <?php if ($isRequired) { ?><span
                                    class="text-danger ms-1">*</span><?php } ?></label>
                        <div class="d-flex gap-2 align-items-center">
                            <input type="text" name="other_tax_amount" id="other_tax_amount"
                                class="form-control charges other_tax_amount" value="{{ $row->other_tax_amount }}" readonly>
                            <button type="button" class="btn btn-light border refbtnhide packingtax"
                                data-value="Other Tax Amount" data-at="4" title="Add tax line">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                        <input type="hidden" name="other_tax_amount_tax" id="other_tax_amount_tax"
                            value="{{ $row->other_tax_amount_tax }}" class="form-control other_tax_amount_tax">
                    </div>
                <?php }

                if ($val->column_name == 'other_frieght_amount' && $val->active == 1) {
                    $i++; ?>
                    <div class="mb-3">
                        <label class="form-label">Other Freight Amount <?php if ($isRequired) { ?><span
                                    class="text-danger ms-1">*</span><?php } ?></label>
                        <div class="d-flex gap-2 align-items-center">
                            <input type="text" name="other_frieght_amount" id="other_frieght_amount"
                                class="form-control charges other_frieght_amount" value="{{ $row->other_frieght_amount }}"
                                readonly>
                            <button type="button" class="btn btn-light border refbtnhide packingtax"
                                data-value="Other Freight Amount" data-at="5" title="Add tax line">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                        <input type="hidden" name="other_frieght_amount_tax" id="other_frieght_amount_tax"
                            value="{{ $row->other_frieght_amount_tax }}" class="form-control other_frieght_amount_tax">
                    </div>
                <?php }

                if ($val->column_name == 'transport_charges' && $val->active == 1) {
                    $i++; ?>
                    <div class="mb-3">
                        <label class="form-label">Transport Charges <?php if ($isRequired) { ?><span
                                    class="text-danger ms-1">*</span><?php } ?></label>
                        <div class="d-flex gap-2 align-items-center">
                            <input type="text" name="transport_charges" id="transport_charges"
                                class="form-control charges transport_charges" value="{{ $row->transport_charges }}" readonly
                                <?php echo $requiredAttr; ?>>
                            <button type="button" class="btn btn-light border refbtnhide packingtax"
                                data-value="Transport Charges" data-at="2" title="Add tax line">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                        <input type="hidden" name="transport_charges_tax" id="transport_charges_tax"
                            value="{{ $row->transport_charges_tax }}" class="form-control transport_charges_tax">
                    </div>
                <?php }

                /* -----------------------------
                   DISCOUNT
                ------------------------------*/
                if ($val->column_name == 'discount_id' && $val->active == 1) {
                    $i++; ?>
                    <div class="mb-3">
                        <label class="form-label">Discount <?php if ($isRequired) { ?><span
                                    class="text-danger ms-1">*</span><?php } ?></label>
                        <select name="discount_id" id="discount_id" class="form-select select2 discount_id" tabindex="19" <?php echo $requiredAttr; ?>>
                            {!! $discount !!}
                        </select>
                    </div>
                <?php }

                echo '</div>';
            }
            ?>
        </div>
    </div>

</div>
				
 </div>


            <div class="row mt-4">
                <div class="col-md-12">

                    <!-------------------------Linedata -------------------------------->
                    <div id="preview-area" class="table-responsive">
                        <table class="table table-bordered clone_table" style="width: 200% !important;">

                            <thead class="table-light">
                                <tr>
                                    <th id="line">Line No</th>
                                    <?php if ($row->source == "STANDARD" || $row->source == "EXPORT INVOICE" || $row->source == "REPLACEMENT" || $row->source == "LABOUR" || $row->source == 'SALES ORDER' || $row->source == 'DISPATCH' || $row->source == 'PICK ORDER') { ?>
                                        <th id="prod" class="freeze">Product </th>
                                        <th>Customer Part No</th>
                                        <th id="5">Uom Code </th>

                                    <?php }
                                    if ($row->source == "LABOUR") { ?>
                                        <th id="desc">Product Description</th>
                                    <?PHP } ?>
                                    <?php if ($pageMethod == "invoicefromdispatch" || $pageMethod == "salesreplacement" || $pageModule == "salesinvoiceapproval") { ?>
                                        <th>Batch Number</th><?php } ?>
                                    <?php if ($row->source == 'SALES ORDER') { ?>

                                        <th>SO Qty</th> <?php } else if ($row->source == 'PICK ORDER') { ?>

                                            <th>Picked Qty</th>
                                    <?php } else if ($row->source == 'DISPATCH' || $row->source == "REPLACEMENT") { ?>

                                                <th>Dispatch Qty</th>
                                                <th>Free Qty</th>
                                    <?php } ?>
                                    <?php if ($row->source == 'DISPATCH' && $row->ar_sales_hdr_id != '' || ($row->source == 'SALES ORDER')) { ?>
                                        <th>Sales Invoice Qty</th>
                                    <?php } ?>
                                    <th>Invoice Qty</th>
                                    <?php if ($row->source == 'SALES ORDER') { ?>
                                        <th>Invoiced Qty</th>
                                    <?PHP } ?>
                                    <th>MRP</th>
                                    <th>Price</th>
                                    <th>Discount(%)</th>
                                    <th>Discount Amount</th>
                                    <th>Tax Exemption</th>
                                    <?php if ($row->source == "STANDARD" || $row->source == "EXPORT INVOICE" || $row->source == "REPLACEMENT" || $row->source == 'SALES ORDER' || $row->source == 'DISPATCH' || $row->source == 'PICK ORDER') { ?>
                                        <th class="hsn" id="sample"> HSN Code </th>
                                    <?php } else { ?>
                                        <th id="sample"> SAC Code </th>
                                    <?php } ?>

                                    <th id="tax">Tax Group</th>
                                    <th id="taxamt">Tax Amount</th>
                                    <th>Line Total</th>
                                    <?php if ($row->source != "DISPATCH") { ?>
                                        <th>QOH</th>
                                    <?php } ?>
                                    <th>Comments</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody class="clone_lines_body">
                                <?php
                                // dd($linedata);
                                if (count($linedata) >= 1) { ?>
                                    @foreach($linedata as $key=>$value)
                                    <?php //dd($value); ?>
                                    <tr class="clone rcopy clonedInput">

                                        <td>
                                            <input type="hidden" name="bulk_invoice_line_id[]"
                                                class="form-control input-sm bulk_invoice_line_id"
                                                value="{{ $value->invoice_line_id }}">
                                      

                                            <input type="hidden" name="bulk_reference_hdr_id[]"
                                                class="form-control input-sm bulk_reference_hdr_id"
                                                value="{{ $value->reference_hdr_id }}">
                                  

                                            <input type="hidden" name="bulk_reference_line_id[]"
                                                class="form-control input-sm bulk_reference_line_id"
                                                value="{{ $value->reference_line_id }}">
                                   


                                            <input type="hidden" name="ar_sales_line_id[]"
                                                class="form-control input-sm ar_sales_line_id"
                                                value="{{ $value->ar_sales_line_id }}">

                                            <input type="text" readonly name="bulk_line_no[]"
                                                class=" input-sm bulk_line_no " value="{{ $key + 1 }}" readonly="readonly">
                                        </td>
                                        <?php if ($row->source == "STANDARD" || $row->source == "EXPORT INVOICE" || $row->source == "REPLACEMENT" || $row->source == "LABOUR" || $row->source == 'SALES ORDER' || $row->source == 'DISPATCH' || $row->source == 'PICK ORDER') { ?>
                                            <td id="blk" class="stdivhide freeze">
                                                <select name="bulk_product_id[]" class="select2 bulk_product_id  "
                                                    parsley-validated required="required">{!! $value->product_id !!}</select>
                                            </td>
                                            
                                            <td class="pdtdiv" style="pointer-events:none;">
                                                <select name="bulk_part_no[]" id="bulk_part_no"
                                                    class="bulk_part_no select2 ">{!! $value->part_no !!}</select>
                                            </td>
                                            <td class="stdivhide" style="pointer-events:none">
                                                <select name="bulk_uom_code_id[]" id="bulk_uom_code_id"
                                                    class="select2 bulk_uom_code_id">
                                                    {!! $value->uomcode_id !!}
                                                </select>
                                            </td>

                                        <?php }
                                        if ($row->source == "LABOUR") { ?>
                                            <td id="des" class="stdivhide">
                                                <input type="text" name="bulk_description[]"
                                                    class="form-control bulk_description" value="{{ $value->description }}"
                                                    required="required">
                                            </td><?php } ?>
                                        <?php if ($pageMethod == "invoicefromdispatch" || $pageMethod == "salesreplacement" || $pageModule == "salesinvoiceapproval") { ?>
                                            <td> <input type="text" name="bulk_batch_number[]"
                                                    class="form-control bulk_batch_number" value="{{ $value->batch_number }}"
                                                    readonly></td><?php } ?>

                                        <?php if ($row->source != "STANDARD" && $row->source != "LABOUR" && $row->source != "EXPORT INVOICE") { ?>
                                            <td>
                                                <input type="text" name="bulk_salesorder_qty[]"
                                                    class="form-control input-sm bulk_salesorder_qty input_qty_width"
                                                    value="{{ $value->salesorder_qty }}" minlength="1">
                                            </td>
                                            <td>
                                                <input type="text" name="bulk_free_qty[]"
                                                    class="form-control input-sm bulk_free_qty input_qty_width"
                                                    value="{{ $value->free_qty }}" minlength="1">
                                            </td>
                                        <?php } ?>
                                        <?php if ($row->source == 'DISPATCH' && $row->ar_sales_hdr_id != '' || ($row->source == 'SALES ORDER')) {
                                            $qty = "";
                                            if ($pageMethod == "salesinvoiceapprovalview") {
                                                $qty = $value->qty;
                                            } else {
                                                $qty = $value->qty;
                                            }
                                            ?>

                                            <td>
                                                <a class="invoicesoqty"> <i class="fa fa-plus"></i></a>
                                                <input type="hidden" name="bulk_sales_order[]" class="bulk_sales_order"
                                                    value="{{ $value->sales_order }}">
                                                <input type="hidden" name="bulk_sales_order_qty[]" class="bulk_sales_order_qty"
                                                    value="{{ $value->sales_order_qty }}">
                                                <input type="hidden" name="bulk_sales_order_invoice[]"
                                                    class="bulk_sales_order_invoice" value="{{ $value->sales_order_invoice }}">
                                                <input type="hidden" name="bulk_sales_order_invoiced[]"
                                                    class="bulk_sales_order_invoiced"
                                                    value="{{ $value->sales_order_invoiced }}">
                                            </td>
                                        <?php } else {
                                            $qty = $value->qty;
                                        } ?>

                                        <td class="stdivhide">
                                            <input type="text" name="bulk_qty[]"
                                                class="form-control input-sm bulk_qty input_qty_width" value="{{$qty}}"
                                                minlength="0" required="required">
                                        </td>
                                        <?php if ($row->source == 'SALES ORDER') { ?>
                                            <td>
                                                <input type="text" name=bulk_invoice_qty[]
                                                    class="form-control bulk_invoiced_qty" readonly
                                                    value="{{$value->invoiced_qty}}">
                                            </td>
                                        <?PHP } ?>
                                        <td>
                                            <?php $price12 = number_format($value->std_price, \Session::get("decimal")) ?>
                                            <?php $price21 = str_replace(',', '', $price12) ?>
                                            <input type="text" name="bulk_std_price[]"
                                                class="form-control input-sm bulk_std_price input_qty_width"
                                                value="{{ $price21 }}" required="required" readonly>
                                        </td>
                                        <td>
                                            <?php $price = number_format($value->unit_price, \Session::get("decimal")) ?>
                                            <?php $price1 = str_replace(',', '', $price) ?>
                                            <input type="text" name="bulk_unit_price[]"
                                                class="form-control input-sm bulk_unit_price input_qty_width"
                                                value="{{ $price1 }}" required="required" readonly>
                                        </td>
                                        <td class="stdivhide">
                                            <input type="text" name="bulk_discount_percentage[]"
                                                class="form-control input-sm bulk_discount_percentage input_qty_width"
                                                value="{{ $value->discount_percentage }}">
                                        </td>
                                        <td class="stdivhide">
                                            <?php $disamt = number_format($value->discount_amount, \Session::get("decimal")) ?>
                                            <?php $disamt1 = str_replace(',', '', $disamt) ?>
                                            <input type="text" name="bulk_discount_amount[]"
                                                class="form-control input-sm bulk_discount_amount input_qty_width"
                                                value="{{ $disamt1 }}" readonly="true">
                                        </td>
                                        <td><select name="bulk_tax_excemption[]"
                                                class="form-control select2 bulk_tax_excemption" id="bulk_tax_excemption"
                                                required>
                                                <option value="">--please select--</option>
                                                <option value="Yes" <?php if ($value->tax_excemption == 'Yes') {
                                                    echo "selected";
                                                } ?>>Yes</option>
                                                <option value="No" <?php if ($value->tax_excemption == 'No') {
                                                    echo "selected";
                                                } ?>>No</option>
                                            </select>
                                        </td>
                                        <?php if ($row->source == "STANDARD" || $row->source == "EXPORT INVOICE" || $row->source == "REPLACEMENT" || $row->source == 'SALES ORDER' || $row->source == 'DISPATCH') { ?>
                                            <td class="hsn hsnhide">
                                                <select name="bulk_hsn_code[]" id="bulk_hsn_code"
                                                    class="select2 bulk_hsn_code">{!! $value->hsn_code!!}</select>
                                            </td>
                                        <?php } else { ?>
                                            <td class="hsnhide">
                                                <select name="bulk_hsn_code[]" id="bulk_hsn_code"
                                                    class="select2 bulk_hsn_code">{!! $value->hsn_code!!}</select>
                                            </td>
                                        <?php } ?>
                                        <td id="tax1" class="stdivhide">
                                            <select name="bulk_tax_group_id[]" id="bulk_tax_group_id"
                                                class="select2 bulk_tax_group_id">
                                                {!! $value->taxgroup_id !!}
                                            </select>
                                        </td>
                                        <td id="taxamount" class="stdivhide">
                                            <?php $taxamt = number_format($value->tax_amount, \Session::get("decimal")) ?>
                                            <?php $taxamt1 = str_replace(',', '', $taxamt) ?>
                                            <input type="text" name="bulk_tax_amount[]"
                                                class="form-control input-sm bulk_tax_amount input_qty_width"
                                                value="{{ $taxamt1 }}">
                                        </td>
                                        <td class="stdivhide">
                                            <?php $totamt = number_format($value->line_total, \Session::get("decimal")) ?>
                                            <?php $totamt1 = str_replace(',', '', $totamt) ?>
                                            <input type="text" name="bulk_line_total[]"
                                                class="form-control input-sm bulk_line_total input_qty_width"
                                                value="{{ $totamt1 }}">
                                        </td>
                                        <?php if ($row->source != "DISPATCH") { ?>
                                            <td class="stdivhide">
                                                <input type="text" name="bulk_qoh" id="bulk_qoh" class="form-control bulk_qoh"
                                                    value="{{ $value->qoh }}">
                                            </td>
                                        <?php } ?>
                                        <td>
                                            <input name="bulk_comments[]"
                                                class="form-control input-sm bulk_comments input_qty_width" row="5"
                                                value="{{ $value->comments }}">
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
                                    <tr class="cloneRow clone rcopy clonedInput">

                                        <td>
                                            <input type="hidden" name="bulk_invoice_line_id[]"
                                                class="form-control input-sm bulk_invoice_line_id" value="">

                                            <input type="text" name="bulk_line_no[]"
                                                class="form-control input-sm bulk_line_no " value="1" readonly="readonly">
                                        </td>
                                        <?php if ($row->source == "STANDARD" || $row->source == "EXPORT INVOICE" || $row->source == "REPLACEMENT" || $row->source == "LABOUR" || $row->source == 'SALES ORDER' || $row->source == 'DISPATCH') { ?>
                                            <td id="blk" class="freeze">
                                                <select name="bulk_product_id[]" class="select2 bulk_product_id"
                                                    parsley-validated required="required">
                                                    {!! $product_id !!}
                                                </select>
                                            </td>
                                            
                                            <td class="pdtdiv" style="pointer-events:none;">
                                                <select name="bulk_part_no[]" id="bulk_part_no"
                                                    class="bulk_part_no select2 ">{!! $part_no !!}</select>
                                            </td>
                                            <td style="pointer-events:none">
                                                <select name="bulk_uom_code_id[]" id="bulk_uom_code_id"
                                                    class="select2 bulk_uom_code_id">
                                                    {!! $uom_code_id !!}
                                                </select>
                                            </td><?php }
                                        if ($row->source == "LABOUR") { ?>
                                            <td id="des">
                                                <input type="text" name="bulk_description[]"
                                                    class="form-control bulk_description" value="" required="required">
                                            </td>
                                        <?php } ?>
                                        <?php if ($row->source != "STANDARD" && $row->source != "LABOUR" && $row->source != "EXPORT INVOICE") { ?>
                                            <td>

                                                <input type="text" name="bulk_salesorder_qty[]"
                                                    class="form-control bulk_salesorder_qty" value="">

                                            </td>
                                            <td>

                                                <input type="text" name="bulk_free_qty[]" class="form-control bulk_free_qty"
                                                    value="">

                                            </td>
                                        <?php } ?>
                                        <td>
                                            <input type="hidden" name="bulk_sales_order[]" class="bulk_sales_order">
                                            <input type="hidden" name="bulk_sales_order_qty[]" class="bulk_sales_order_qty">
                                            <input type="hidden" name="bulk_sales_order_invoice[]"
                                                class="bulk_sales_order_invoice">
                                            <input type="hidden" name="bulk_sales_order_invoiced[]"
                                                class="bulk_sales_order_invoiced">
                                            <input type="text" name="bulk_qty[]" class="form-control bulk_qty " value=""
                                                required="required">
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_std_price[]"
                                                class="form-control input-sm bulk_std_price " value="" required="required"
                                                readonly>
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_unit_price[]"
                                                class="form-control input-sm bulk_unit_price " value="" required="required"
                                                readonly>
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_discount_percentage[]"
                                                class="form-control input-sm bulk_discount_percentage " value="">
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_discount_amount[]"
                                                class="form-control input-sm bulk_discount_amount " value="">
                                        </td>
                                        <td><select name="bulk_tax_excemption[]"
                                                class="form-control select2 bulk_tax_excemption" id="bulk_tax_excemption"
                                                required>
                                                <option value="">--please select--</option>
                                                <option value="Yes" selected>Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </td>
                                        <?php if ($row->source == "STANDARD" || $row->source == "REPLACEMENT" || $row->source == 'SALES ORDER' || $row->source == 'DISPATCH') { ?>
                                            <td class="hsn hsnhide">
                                                <select name="bulk_hsn_code[]" id="bulk_hsn_code"
                                                    class="select2 bulk_hsn_code"></select>
                                            </td>
                                        <?php } else { ?>
                                            <td class="hsnhide">
                                                <select name="bulk_hsn_code[]" id="bulk_hsn_code"
                                                    class="select2 bulk_hsn_code">{!! $sac !!}</select>
                                            </td>
                                        <?php } ?>

                                        <td id="tax1">
                                            <select name="bulk_tax_group_id[]" id="bulk_tax_group_id"
                                                class="select2 bulk_tax_group_id">
                                                {!! $tax_group_id !!}
                                            </select>
                                        </td>
                                        <td id="taxamount">
                                            <input type="text" name="bulk_tax_amount[]"
                                                class="form-control input-sm bulk_tax_amount" value="">
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_line_total[]"
                                                class="form-control input-sm bulk_line_total" value="">
                                        </td>
                                        <?php if ($row->source != "DISPATCH") { ?>
                                            <td>
                                                <input type="text" name="bulk_qoh" id="bulk_qoh" class="form-control bulk_qoh"
                                                    value="{{ $value->qoh }}">
                                            </td>
                                        <?php } ?>
                                        <td>
                                            <input name="bulk_comments[]" class="form-control input-sm bulk_comments"
                                                value="{{ $value->comments }}" row="5">
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

            <div class="row mt-4 mb-3">
                <div class="col-lg-12 col-md-12">
                    <div class="form-group text-center">

                        <?php if ($return_url == "salesinvoice" || $return_url == "invoicefrompickorder" || $return_url == "salesinvoicereplacement") { ?>
                            <?PHP if ($savestatus != "SAVE") { ?>
                                <button type="button" class="btn btn-secondary px-4 me-2 saveform"
                                    value="APPLYCHANGES">Draft</button>
                            <?PHP } ?>

                            <button type="button" class="btn btn-success px-4 me-2 saveform" value="SAVE">Save</button>
                        <?php } else if (($return_url == "invoicefromorder" && $row->source == "REPLACEMENT") || ($return_url == "salesreplacement") || ($return_url == "invoicefromorder")) { ?>
                                <button type="button" class="btn btn-success px-4 me-2 saveform" value="SAVE">Save</button>
                        <?php } else { ?>
                                <button type="button" name="submit" class="btn btn-success px-4 me-2 saveform"
                                    value="APPROVED">Approve</button>
                                <button type="button" name="submit" class="btn btn-danger px-4 me-2 saveform"
                                    value="REJECTED">Reject</button>
                        <?php } ?>
                        <a class='btn btn-outline-danger px-4 me-2'
                            onclick="location.href = '{{URL::to('salesinvoicefromdispatch')}}'">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</form>


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


    <div class="modal fade" id="taxModal" tabindex="-1" aria-labelledby="taxModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered modal-dialog-scrollable">
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


<!-- Sales Order Details Modal -->
<div class="modal fade" id="invoicesoqtyModal" tabindex="-1" aria-labelledby="invoicesoqtyModalLabel" aria-hidden="true" role="dialog" aria-modal="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

      <!-- Modal Header -->
      <div class="modal-header bg-gradient bg-primary text-white py-3 px-4">
        <h5 class="modal-title fw-semibold" id="invoicesoqtyModalLabel">
          <i class="bi bi-receipt-cutoff me-2"></i> Sales Order Details
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Hidden fields -->
      <input type="hidden" class="qtyindex" value="">
      <input type="hidden" class="pageMethod" value="{{ $pageMethod }}">

      <!-- Modal Body -->
      <div class="modal-body qtydetail p-4 bg-light-subtle">
        <div class="text-center py-5" id="modal-loading">
          <div class="spinner-border text-primary" role="status"></div>
          <p class="mt-3 text-secondary">Loading sales order details...</p>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer bg-light px-4 py-3">
        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
          <i class="bi bi-x-circle me-1"></i> Close
        </button>
      </div>

    </div>
  </div>
</div>


@endsection
@push('scripts')

<script>
	
	
    $(document).ready(function () {

		
        $(".invoice_date").change(function () {

            var val = $(this).val();
            $(".label_invoice_date").html("INVOICE DATE: " + val);
        });
		
        <?php if ($row->source != "REPLACEMENT") { ?>
            $('.pricelist').css('pointer-events', 'none');
        <?php } ?>

        <?php if ($row->invoice_type == "EXPORT INVOICE" || $row->invoice_type == "EXPORT SAMPLE") { ?>
            $(".invoice_currency option[value='37']").attr('disabled', 'disabled');
        <?php } ?>


        <?php if ($pageModule == "salesinvoiceapproval") { ?>
            $('.additem,.remove,.disdspnone').css('pointer-events', 'none');
            $('#choosefile').css("pointer-events", "none");
            $('.rdonlydiv').css('pointer-events', 'none');
            $('.invoicesoqty,.customersearch,.refbtnhide,.stdivhide,.refbtn,.due_date').css('pointer-events', 'none');
        <?php } ?>
        <?php if ($row->invoice_type == "SAMPLE") { ?>
            $('.ship_to_customer_id').attr('required', false);
            $('.bulk_tax_group_id').attr('required', false);
        <?php } else { ?>
            $('.ship_to_customer_id').attr('required', true);
            $('.bulk_tax_group_id').attr('required', true);
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

        $(document).on('change', '.ship_to_customer_id', function (event) {

            var customer_id = $('.ship_to_customer_id').val();
            if (customer_id != '') {
                var url = "{{ URL::to('sodispatchaddress') }}/" + customer_id + "?pid=0&condition=salesinvoice";
                $.get(url, function (data) {
                    if (data.pricelist_id != 0) {
                        $('.pricelist_id').select2('val', [data.pricelist_id]);
                        $('.ar_frieghtcarriers_hdr_id').select2('val', [data.ar_frieghtcarriers_hdr_id]);
                        $('.payment_term_id').select2('val', [data.default_payment_terms_id]);
                        $('.payment_method_id').select2('val', [data.default_payment_method_id]);
                        $('.salesperson_id').select2('val', [data.sales_person]);
                        $('.discount_id').select2('val', [data.ar_discount_hdr_id]);
                        $('.bulk_product_id').html(data.productid);
                    } else {
                        showCustomAlert("Please select pricelist","warning");
                    }


                    if (data[1] != '' && data[0] != '') {
                        var result_data = data[1].split('~');
                        $('.shipping_to_address_txt').val(result_data[0]);
                        $('#ship_to_address_id').val(result_data[1]);

                        var result_data = data[0].split('~');
                        $('.billing_to_address_txt').val(result_data[0]);
                        $('#bill_to_address_id').val(result_data[1]);

                    }
					
                    if ((data[1] == "" && data[0] != "") || (data[0] == "" && data[1] != "")) {

                        if (data[1] != '') {
                            var result_data = data[1].split('~');
                            $('.shipping_to_address_txt').val(result_data[0]);
                            $('#ship_to_address_id').val(result_data[1]);

                        }
                        else {
                            $('.shipping_to_address_txt').val('');
                            showCustomAlert('Please Assign Ship To Address !!!','info');


                        }
                        if (data[0] != '') {
                            var result_data = data[0].split('~');
                            $('.billing_to_address_txt').val(result_data[0]);
                            $('#bill_to_address_id').val(result_data[1]);
                        }
                        else {
                            $('.billing_to_address_txt').val('');
                            showCustomAlert('Please Assign Bill To  Address !!!','info');
                        }

                    }
                    if (data[1] == '' && data[0] == '') {
                        $(".billing_to_address_txt,#bill_to_address_id").val('');
                        $(".shipping_to_address_txt,#ship_to_address_id").val('');
                        showCustomAlert('Please Assign Bill To and Ship To Address !!!','info');
                    }
                });

            } else {
				
                $(".pricelist_id").val('').change();
                $(".billing_to_address_txt,#bill_to_address_id").val('');
                $(".shipping_to_address_txt,#ship_to_address_id").val('');
                $('.ar_frieghtcarriers_hdr_id').select2('val', ['']);
                $('.ar_payment_term_id').select2('val', ['']);
                $('.ar_payment_method_id').select2('val', ['']);
                $('.salesperson_id').select2('val', ['']);
                event.preventDefault();
            }


        });

		
        $(document).on('change', '.invoice_date', function () {
            var invoice_date = $('.invoice_date').val();
            var payment_term_id = $('.payment_term_id').val();
            var url = "{{ URL::to('duedatecal')}}/?invoice_date=" + invoice_date + "&payment_term_id=" + payment_term_id;

            $.get(url, function (data) {
                var due_date = data;
                $('.due_date').val(due_date);

            });

        });
		
		
        $(document).on('change', '.payment_term_id', function () {
            var invoice_date = $('.invoice_date').val();
            var payment_term_id = $('.payment_term_id').val();
            var url = "{{ URL::to('duedatecal')}}/?invoice_date=" + invoice_date + "&payment_term_id=" + payment_term_id;

            $.get(url, function (data) {
                var due_date = data;
                $('.due_date').val(due_date);

            });

        });


        // purpose to other tax calculation
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
    <div class="col-md-6">
      <label class="form-label fw-bold">${type}</label>
      <input type="text" class="form-control extracharge${type_id}" value="" readonly>
    </div>

    <!-- Tax Group -->
    <div class="col-md-6">
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
    <div class="col-md-6">
      <label class="form-label fw-bold">${type}</label>
      <input type="text" class="form-control extracharge${type_id}" value="">
    </div>

    <!-- Tax Group -->
    <div class="col-md-6">
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
		
        // Purpose:Schemes Apply
$(".apply").click(function () {

    var schemes = $('#schemes').val();

    if (!schemes || schemes.length === 0) {
        showCustomAlert("Please select Schemes", "info");
        return;
    }

    var hasError = false;

    $('.clone_table tbody tr').each(function () {

        var $row = $(this);

        var product = $row.find('.bulk_product_id').val();
        var qty = $row.find('.bulk_qty').val();
        var unit_price = $row.find('.bulk_unit_price').val();
        var $discount = $row.find('.bulk_discount_percentage');

        if (!product || !qty) {
            hasError = true;
            return false; // break loop
        }

        var url = "{{URL::to('schemesavailable')}}"
            + "?product=" + product
            + "&qty=" + qty
            + "&unitprice=" + unit_price
            + "&schemes_type=" + schemes.join(',');

        $.get(url, function (data) {
            if (data && data.length > 0) {
                $discount.val(data[0].schemes_type_value);
                $row.find('.bulk_qty').trigger('change');
            }
        });

    });

    if (hasError) {
        showCustomAlert("Product And Qty Is Required", "info");
    }
});




		

		// Purpose:Schemes Apply
		
    $(".cash_discount_type").click(function () {

    let total = 0;
    let discount = 0;

    $('.clone_table tbody tr').each(function () {

        let $row = $(this);

        let qty = parseFloat($row.find('.bulk_qty').val()) || 0;
        let price = parseFloat($row.find('.bulk_unit_price').val()) || 0;
        let lineDiscount = parseFloat($row.find('.bulk_discount_amount').val()) || 0;

        total += (qty * price) - lineDiscount;
    });

    let schemes = $('.cash_discount').val();

    if (!schemes) {
        showCustomAlert("Please select Cash Discount Scheme", "info");
        return;
    }

    var url = "{{URL::to('schemesordercheck')}}";

    $.get(url, { schemes: schemes, total: total }, function (data) {

        let discount = 0;
        let per = 0;
        let amount = 0;

        if (data && data.length > 0) {

            $.each(data, function (i, val) {

                if (val.schemes_type === "Discounts") {

                    per = parseFloat(val.schemes_type_value) || 0;
                    amount = (total * per) / 100;
                    discount += amount;
                    total -= amount;

                } else if (val.schemes_type === "Amount") {

                    amount = parseFloat(val.schemes_type_value) || 0;
                    discount += amount;
                    total -= amount;
                }
            });
        }

        $('.trade_discount')
            .val(discount.toFixed(2))
            .trigger('change');
    });
});



$(document).on('change', '.trade_discount', function () {

    let baseTotal = 0;

    $('.clone_table tbody tr').each(function () {
        const qty   = +$(this).find('.bulk_qty').val() || 0;
        const price = +$(this).find('.bulk_unit_price').val() || 0;
        const disc  = +$(this).find('.bulk_discount_amount').val() || 0;

        baseTotal += (qty * price) - disc;
    });

    const tradeAmt = +$(this).val() || 0;
    const DEC = window.decimal ?? 2;

    const tradePer = baseTotal > 0
        ? (tradeAmt * 100) / baseTotal
        : 0;

    $('#trade_discount_pre')
        .val(tradePer.toFixed(DEC));

    recalcInvoice(); // ✅ MUST
});


$(document).on(
  'keyup change',
  '.bulk_qty, .bulk_unit_price, .bulk_hsn_code, .bulk_discount_percentage, .bulk_tax_group_id, .bulk_product_id, .charges',
  function () {
      recalcInvoice();
  }
);

function recalcInvoice() {

    const DEC = window.decimal ?? 2;
    const num = v => isNaN(parseFloat(v)) ? 0 : parseFloat(v);
    const fmt = v => num(v).toFixed(DEC);

    let sumLine = 0, sumTax = 0, sumQty = 0, charges = 0;

    const tradePer = num($('#trade_discount_pre').val());

    $('.clone_table tbody tr').each(function () {

        const $r = $(this);

        const qty     = num($r.find('.bulk_qty').val());
        const price   = num($r.find('.bulk_unit_price').val());
        const discPer = num($r.find('.bulk_discount_percentage').val());

        const taxPer = num(
            $r.find('.bulk_tax_group_id option:selected').attr('data-display')
            || $r.find('.bulk_tax_group_id').val()
        );

        const gross     = qty * price;
        const lineDisc  = gross * discPer / 100;
        const afterDisc = gross - lineDisc;

        // ✅ HEADER DISCOUNT DISTRIBUTED PER LINE
        const tradeDisc = afterDisc * tradePer / 100;

        const taxable   = afterDisc - tradeDisc;
        const taxAmt    = taxable * taxPer / 100;
        const lineTotal = taxable + taxAmt;

        $r.find('.bulk_discount_amount').val(fmt(lineDisc));
        $r.find('.bulk_tax_amount').val(fmt(taxAmt));
        $r.find('.bulk_line_total').val(fmt(lineTotal));

        sumLine += lineTotal;
        sumTax  += taxAmt;
        sumQty  += qty;
    });

    $('.charges').each(function () {
        charges += num($(this).val());
    });

    const tds = num($('.tds_amount').val());

    const taxTotal   = sumTax + charges;
    const grandTotal = sumLine + charges - tds;

    $('#invoice_tax_total').val(fmt(taxTotal));
    $('.tax_total_span').text(fmt(taxTotal));

    $('#invoice_grand_total').val(fmt(grandTotal));
    $('.grand_total_span').text(fmt(grandTotal));
    $('#balance_amount').val(fmt(grandTotal));

    $('#qty_total').val(fmt(sumQty));
    $('.qty_span').text(fmt(sumQty));
}





            $(document).on('click', '.taxchargesave', function () {
                var type = $(this).val();
                var taxgrp = $('.tax_details' + type + ' option:selected').attr('data-display');
                var taxgrp_v = $('.tax_details' + type + ' option:selected').val();
                var charge = parseFloat($('.extracharge' + type).val()).toFixed("{{\Session::get('decimal')}}");
   
                if (taxgrp == '') {
                    showCustomAlert("Please select Tax Group","warning");
                }
                taxgrp = taxgrp ? taxgrp : 0;
                var amount = parseFloat((charge) * taxgrp / 100).toFixed("{{\Session::get('decimal')}}");
                if (charge == '') {
                    showCustomAlert("Please fill Amount","warning");
                }
                if (taxgrp != '' && charge != '') {
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
				
                /* purpose:calculate other charges with tax and grand total*/
                var trns = $('.transport_charges').val();
                var packchrg = $('.packaging_charges').val();
                var packchrg = $('.insurance_charges').val();
                var othertax = $('.other_tax_amount').val();
                var totalcharge = parseFloat(trns) + parseFloat(packchrg) + parseFloat(packchrg) + parseFloat(othertax);
                var sumtax = parseFloat(totalcharge).toFixed("{{\Session::get('decimal')}}");//alert(sumtax);
                var invtax = $('#invoice_tax_total').val();
                var invtaxtot = parseFloat(invtax) + parseFloat(sumtax);
                $('.tax_total_span').html(parseFloat(invtaxtot).toFixed("{{\Session::get('decimal')}}"));
                $('#invoice_tax_total').val(parseFloat(invtaxtot).toFixed("{{\Session::get('decimal')}}"));
                var tot = $('#invoice_grand_total').val();
                var grndtot = parseFloat(tot) + parseFloat(sumtax);
                $('#invoice_grand_total').val(parseFloat(grndtot).toFixed("{{\Session::get('decimal')}}"));
                $('.grand_total_span').html(parseFloat(grndtot).toFixed("{{\Session::get('decimal')}}"));
                /*end*/
            });

        //Purpose for TDS  
        var decimal = '<?php echo \Session::get('decimal'); ?>';
        $(".tds_applicable").change(function () {
            var customer_id = $('.ship_to_customer_id').val();
            var tds = $(".tds_applicable option:selected").val();
            if (customer_id) {
                if (tds == "YES") {
                    var url = "{{ URL::to('salesloadtds') }}/" + customer_id + "/" + tds;
                    $.get(url, function (data) {

                        if (data.tds_percentage != "") {
                            $('.tds_prcnt').val(data.tds_percentage);
                            $('.tds_account_id').val(data.tds_account_id).change();
                            var sum = 0;
                            var sumtax = 0;
                            var sumall = 0;
                            var sumqty = 0;
                            var subtotal = 0;
                            var sumwithtds = 0;
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
                            $('.bulk_qty').each(function () {
                                sumqty += parseFloat($(this).val());
                            });

                            subtotal = parseFloat((sum - sumtax)).toFixed(decimal);
                            calc_amount = parseFloat($('.tcs_calc_amount').val());
                            var tcs_prcnt = parseFloat($('.tcs_prcnt').val());
                            tcs_prcnt = (isNaN(tcs_prcnt) ? 0 : tcs_prcnt);
                            calc_amount = (isNaN(calc_amount) ? 0 : calc_amount);
                            var tcs_amount = (calc_amount * (tcs_prcnt / 100));
                            var tcs_amount = (isNaN(tcs_amount)) ? 0 : tcs_amount;

                            sumtax = parseFloat(sumtax).toFixed(decimal) + parseFloat(charge).toFixed(decimal);
                            sumall = parseFloat(sum).toFixed(decimal) + parseFloat(charge).toFixed(decimal);
                            sumall = (isNaN(sumall) ? 0 : sumall);
                            sumtax = (isNaN(parseFloat(sumtax).toFixed(decimal))) ? 0 : parseFloat(sumtax).toFixed(decimal);

                            $('#invoice_tax_total').val(sumtax);
                            $('#invoice_grand_total').val(sumall);
                            $('#qty_total').val(sumqty);
                            $(".qty_span").html(sumqty);
                            $('#balance_amount').val(sumall);

                            $(".tax_total_span").html(sumtax);
                            $(".grand_total_span").html(parseFloat(sumall).toFixed(decimal));

                            var data1 = data.tds_percentage
                            if (data1 != '') {
                                var tds_amount = parseFloat(subtotal * (data1 / 100)).toFixed(decimal);
                                $('.tds_amount').val(tds_amount);
                                sumwithtds = parseFloat((sumall) - (tds_amount)).toFixed(decimal);
                                sumwithtds = (isNaN(sumwithtds) ? 0 : sumwithtds);
                                $('#invoice_grand_total').val(sumwithtds);
                                $('#balance_amount').val(sumwithtds);
                                $(".tax_total_span").html(sumtax);
                                $(".grand_total_span").html(parseFloat(sumwithtds).toFixed(decimal));
                            }
                        }
                        else {
                            showCustomAlert("TDS Percentage Not Set For This Customer","info");
                        }

                    });
                    $('.tds_prcnt,.tds_amount').attr('required', true);
                }
                else {
                    $('.tds_prcnt').val('');
                    $('.tds_amount').val('');
                    $('.tds_prcnt,.tds_amount').attr('required', false);
                }
            }
        });


        $(".tcs_calc_amount").change(function () {
            calc_amount = parseFloat($(this).val());
            var data1 = parseFloat($('.tcs_prcnt').val());
            data1 = (isNaN(data1) ? 0 : data1);
            calc_amount = (isNaN(calc_amount) ? 0 : calc_amount);



            var sum = 0;
            var sumtax = 0;
            var sumall = 0;
            var sumqty = 0;
            var subtotal = 0;
            var sumwithtds = 0;
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
            $('.bulk_qty').each(function () {
                sumqty += parseFloat($(this).val());
            });

            subtotal = parseFloat((sum - sumtax)).toFixed(decimal);

            var tds_prcnt = $('.tds_prcnt').val();

            var tds_amount = (subtotal * (tds_prcnt / 100));
            var tds_amount = (isNaN(tds_amount)) ? 0 : tds_amount;


            sumtax = Number(sumtax) + Number(charge);
            sumall = Number(sum) + Number(charge);

            sumall = (isNaN(sumall) ? 0 : sumall);

            sumall = parseFloat(sumall) - parseFloat(tds_amount);

            sumtax = (isNaN(parseFloat(sumtax).toFixed(decimal))) ? 0 : parseFloat(sumtax).toFixed(decimal);



            $('#invoice_tax_total').val(sumtax);
            $('#invoice_grand_total').val(sumall);
            $('#qty_total').val(sumqty);
            $(".qty_span").html(sumqty);
            $('#balance_amount').val(sumall);

            $(".tax_total_span").html(sumtax);
            $(".grand_total_span").html(parseFloat(sumall).toFixed(decimal));

            if (data1 != '') {
                var tcs_amount = parseFloat(calc_amount * (data1 / 100)).toFixed(decimal);
                $('.tcs_amount').val(tcs_amount);
                sumwithtds = Number(sumall) + Number(tcs_amount);
                var sumwithtds = parseFloat(sumwithtds).toFixed(decimal);
                sumwithtds = (isNaN(sumwithtds) ? 0 : sumwithtds);
                $('#invoice_grand_total').val(sumwithtds);
                $('#balance_amount').val(sumwithtds);
                $(".tax_total_span").html(sumtax);
                $(".grand_total_span").html(parseFloat(sumwithtds).toFixed(decimal));
            }


        });

        $('.tcsacc').css('pointer-events', 'none');

        $(".tcs_applicable").change(function () {
            var customer_id = $('.ship_to_customer_id').val();
            var tds = $(".tcs_applicable option:selected").val();
            if (customer_id) {
                if (tds == "YES") {
                    var url = "{{ URL::to('salesloadtds') }}/" + customer_id + "/" + tds;
                    $.get(url, function (data) {
                        if (data.tcs_percentage != "") {
                            $('.tcs_calc_amount').attr('readonly', false);
                            $('.tcs_prcnt').val(data.tcs_percentage);
                            $('.tcs_account_id').val(data.tcs_account_id).change();
                        }
                        else {
                            showCustomAlert("TCS Percentage Not Set For This Customer","warning");
                        }

                    });
                    $('.tcs_prcnt,.tcs_amount').attr('required', true);
                }
                else {

                    var url = "{{ URL::to('salesloadtds') }}/" + customer_id + "/" + tds;
                    $.get(url, function (data) {
                        if (data.tcs_applicable == "YES") {
                            $('.tcs_applicable').val('').select2();
                            $('.tcs_account_id').val('').select2();
                            showCustomAlert("TCS Enabled Customer, Please select YES","warning");
                        }
                    });

                    $('.tcs_calc_amount').trigger('change');
                    $('.tcs_prcnt').val('');
                    $('.tcs_amount').val('');
                    $('.tcs_calc_amount').val('');
                    $('.tcs_prcnt,.tcs_amount').attr('required', false);


                }
            }
        });

        $('.round_off').change(function () {
            var round_off = parseFloat($(this).val());
            var invogrd = $('#invoice_grand_total').val();
            var invtot = parseFloat(invogrd) + parseFloat(round_off);
            $('.grand_total_span').html(invtot);
            $('#invoice_grand_total').val(invtot);

        });

        $('.invoice_currency').change(function () {
            var curr = $(this).val();
            var url = "{{URL::to('salesinvoicecurrency')}}/" + curr;
            if (curr != "") {
                $.get(url, function (data) {
                    data = $.trim(data);
                    $('.currency_rate').val(data);
                    if (data != 0) {
                        $('.bulk_unit_price').trigger('change');
                    }
                });
            }
        });



        <?php if ($row->source == 'DISPATCH') { ?>
            $('.add_row,.productsearch').css('display', 'none');
        <?php } ?>

        <?php if ($pageMethod == "salesinvoiceapprovalview") { ?>
            $('#choosefile').css("pointer-events", "none");
            $('.delete_user,.due_date').css("pointer-events", "none");
        <?php } ?>
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
                        '<tr><td><span class="note" ><br /> File:<span class="files">' + fi.files.item(i).name + '</span>&nbsp;<img src="{{URL::to('')}}/images/cancel.png" class="delete_user"></span></td></tr>';
                }
            }
        });



        $(document).on('click', '.delete_user', function () {

            var quote_hdr = '{{$row->invoice_hdr_id}}';
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

        <?php if ($row->source == 'DISPATCH' && $row->ar_sales_hdr_id != '' || ($row->source == 'SALES ORDER')) { ?>
        <?php } ?>

        $('.invoicesoqty').click(function () {
            $('#invoicesoqtyModal').modal('show');

            $('#invoicesoqtyModal').css('margin', 'auto');
            var index = $(this).closest('tr').index();
            $('.qtyindex').val(index);
        });
		
		
$('#invoicesoqtyModal').on('shown.bs.modal', function () {

  const index      = parseInt($('.qtyindex').val(), 10);
  const pageMethod = $('.pageMethod').val() || '';

  const $row = $('.clone_lines_body tr').eq(index); // ✅ correct row

  /* ---------- PAGE MODE ---------- */
  if (pageMethod === "salesinvoiceapprovalview") {
    $('.qtyok').hide();
    $('.invoice_issue_qty').prop('readonly', true);
  }

  /* ---------- ROW VALUES ---------- */
  const soinvqty = num($row.find('.bulk_salesorder_qty').val() || 0);
  const prdid    = $row.find('.bulk_product_id').val() || 0;
  const soid     = $('.ar_sales_hdr_id').val() || 0;
  const source   = $('.source').val() || '';
  const reference_source_id = $('.reference_source_id').val() || 0;

  const soqty = $row.find('.bulk_sales_order_qty').val() || 0;

  let so_id = 0,
      invoice_qty = 0,
      invoiced_qty = 0,
      batch_numbr = '';

  if (soqty !== '' && soqty !== '0') {
    so_id         = $row.find('.bulk_sales_order').val() || 0;
    invoice_qty   = $row.find('.bulk_sales_order_invoice').val() || 0;
    invoiced_qty  = $row.find('.bulk_sales_order_invoiced').val() || 0;
    batch_numbr = $row.find('.bulk_batch_number').val() || '';
  } else {
    batch_numbr = $row.find('.bulk_batch_number').val() || '';
    
  }

  const dispatch =
    (source === "DISPATCH" && soid) ? reference_source_id : 0;

  /* ---------- PAGE MODE ---------- */
  const pagemode = '{{ $pagemode ?? "" }}';
  let invlineid = 0;

  if (pagemode === 'edit') {
    invlineid = $row.find('.bulk_invoice_line_id').val() || 0;
  }

  /* ---------- BUILD URL ---------- */
  const url =
    "{{ URL::to('invoicesoqty') }}/" + index + "/" + prdid + "/" + soid +
    "?so_qty="       + encodeURIComponent(soqty) +
    "&so_id="        + encodeURIComponent(so_id) +
    "&invoice_qty="  + encodeURIComponent(invoice_qty) +
    "&dispatch="     + encodeURIComponent(dispatch) +
    "&invoiced_qty=" + encodeURIComponent(invoiced_qty) +
    "&invlineid="    + encodeURIComponent(invlineid) +
    "&soinvqty="     + encodeURIComponent(soinvqty) +
    "&batch_numbr="  + encodeURIComponent(batch_numbr);

  /* ---------- LOAD MODAL ---------- */
  $.get(url)
    .done(function (data) {
      $('.qtydetail').html(data);
    })
    .fail(function () {
      showCustomAlert('Unable to load SO details', 'error');
    });
});

		
        /* purpose:qty validation*/
        $(document).on('keypress', '.invoice_issue_qty', function (ev) {
            var regex = new RegExp("^[0-9.]+$");
            var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
            if (regex.test(str)) {
                return true;
            }
            ev.preventDefault();
            return false;
        });
		
        /*end*/
        $(document).on('change', '.invoice_issue_qty', function (ev) {
            var index = $(this).attr('data-index');
            var qty = parseFloat($('.qty' + index).val()).toFixed(decimal);
            var invoicedqty = parseFloat($('.invoicedqty' + index).val()).toFixed(decimal);
            var qtycheck = qty - invoicedqty;
            var invoice_qty = parseFloat($(this).val()).toFixed(decimal);

            if (qtycheck < invoice_qty) {
                showCustomAlert("Invoice qty Should Not greater than so qty","error");
                $('.invoice_issue_qty' + index).val('');
            }
        });
		
        var decimal = '<?php echo \Session::get('decimal') ?>';
		
$(document).on('click', '.qtyok', function () {

  const DEC = (typeof decimal !== 'undefined' && !isNaN(decimal)) ? Number(decimal) : 2;

  const num = (v) => {
    const x = parseFloat(v);
    return isNaN(x) ? 0 : x;
  };

  const fmt = (v) => Number(v).toFixed(DEC);

  const index = parseInt($('.qtyindex').val(), 10);
  const $row  = $('.clone_lines_body tr').eq(index); // ✅ correct row

  /* ---------- COLLECT MODAL VALUES ---------- */
  let add = 0;
  const invoiceqty   = [];
  const sorder_no    = [];
  const sorder_qty   = [];
  const invoiced_qty = [];

  let hasError = false;

  $('.invoice_issue_qty').each(function (k) {
    if ($(this).val() === '') {
      showCustomAlert('Please Enter Invoice Qty', 'error');
      hasError = true;
      return false;
    }
    const v = num($(this).val());
    add += v;
    invoiceqty[k] = fmt(v);
  });

  if (hasError) return;

  $('.so_id').each(function (k) {
    sorder_no[k] = $(this).val() || 0;
  });

  $('.qty').each(function (k) {
    sorder_qty[k] = fmt(num($(this).val()));
  });

  $('.invoicedqty').each(function (k) {
    invoiced_qty[k] = fmt(num($(this).val()));
  });

  /* ---------- VALIDATION ---------- */
  const salesorder_qty = num($row.find('.bulk_salesorder_qty').val());

  if (add > salesorder_qty) {
    showCustomAlert("Invoice qty should not exceed order qty", "error");
    return;
  }

  /* ---------- WRITE BACK TO ROW ---------- */
  $row.find('.bulk_qty').val(fmt(add));
  $row.find('.bulk_sales_order_invoice').val(invoiceqty.join(','));
  $row.find('.bulk_sales_order_invoiced').val(invoiced_qty.join(','));
  $row.find('.bulk_sales_order').val(sorder_no.join(','));
  $row.find('.bulk_sales_order_qty').val(sorder_qty.join(','));

  /* ---------- PRICE / TAX CALC ---------- */
  const unitprice = num($row.find('.bulk_unit_price').val());
  const discountPerc = num($row.find('.bulk_discount_percentage').val());

  let taxgrp = $row.find('.bulk_tax_group_id option:selected').data('display');
  taxgrp = num(taxgrp);

  const amount = add * unitprice;
  const discountAmt = (amount * discountPerc) / 100;
  $row.find('.bulk_discount_amount').val(fmt(discountAmt));

  const taxable = amount - discountAmt;
  const taxamount = (taxable * taxgrp) / 100;
  $row.find('.bulk_tax_amount').val(fmt(taxamount));

  const lineTotal = taxable + taxamount;
  $row.find('.bulk_line_total').val(fmt(lineTotal));

  /* ---------- DOCUMENT TOTALS ---------- */
  const sum = (cls) => {
    let t = 0;
    $(cls).each(function () { t += num($(this).val()); });
    return t;
  };

  const tds_amount = num($('.tds_amount').val());
  const charge     = sum('.charges');
  const sumLine    = sum('.bulk_line_total');
  const sumTax     = sum('.bulk_tax_amount');
  const sumQty     = sum('.bulk_qty');

  let grandTotal = (charge + sumLine) - tds_amount;
  if (isNaN(grandTotal)) grandTotal = 0;

  $('#invoice_tax_total').val(fmt(sumTax + charge));
  $('#invoice_grand_total').val(fmt(grandTotal));
  $('.grand_total_span').html(fmt(grandTotal));

  $('#qty_total').val(fmt(sumQty));
  $('.qty_span').html(fmt(sumQty));
  $('.tax_total_span').html(fmt(sumTax));

  /* ---------- CLOSE MODAL ---------- */
  $('#invoicesoqtyModal').modal('hide');
});



        <?php if ($return_url != "salesinvoice") { ?>

            $('.bulk_discount_percentage').attr('readonly', true);
            $('.hsnhide').css('pointer-events', 'none');
        <?php } ?>

        $('.bulk_salesorder_qty').attr('readonly', true);

        <?php if ($pageMethod == "salesinvoiceapprovalview" || $pageMethod == "createinvoicefromorder") { ?>
            $('.add_row,.rem,.searchhide').css('display', 'none');
        <?php } ?>
        <?php if (($row->source == 'SALES ORDER') || ($row->source == 'PICK ORDER') || ($row->source == 'DISPATCH')) { ?>
            $('.billto,.new_billto,.shipto,.new_shipto,.jcr_customer_id').css('display', 'none');
            $('#blk,.customer_id').css('pointer-events', 'none');
        <?PHP } ?>

        <?php if ($return_url == "salesinvoiceapprovalview") { ?>
            $('.stdivhide').css('pointer-events', 'none');

            $('.refbtn').css('display', 'none');

        <?php } ?>
		
        $('#tax1').css('pointer-events', 'none');
        var organization = '<?php echo \session::get('organization'); ?>';
        $('.organization_id').val(organization).change();
        var user = '<?php echo Session::get('id'); ?>';
        $('.created_by').val(user).change();
        $(".create_by").html($('.created_by option:selected').text());
        $('.org').html($('.organization option:selected').text());


		var show_div = '<?php echo $show_div; ?>';
        if (show_div == "1")
            $('#panel_add').trigger('click');

        $(".additional").trigger();

        $('.bulk_tax_amount,.bulk_line_total,#bulk_qoh').attr('readonly', true);


        var source = '<?php echo $row->invoice_type; ?>';

        if (source == 'LABOUR') {
            $('#desc,#des').show();
            $('.hsn').hide();

        } else {

            $('#prod,#blk').show();
            $('.bulk_product_id').attr('required');
            $('.bulk_description').removeAttr('required');
            $('#desc,#des').hide();
            $('.hsn').show();

        }

        $(document).on('click', '.approve', function () {
            $("#invoice_status").val("APPROVED").change();
        });

        $(document).on('click', '.reject', function () {
            $("#invoice_status").val("REJECTED").change();
        });

        $('.organization').bind('click mousedown', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            return true;
        });
		
        $('.req').hide();
		

			
        $(document).on('click', '.saveform', function () {

            var btnval = $(this).val();
            if (btnval == 'APPLYCHANGES') {
                $('.remarks').attr('required', false);
                $("#invoice_status").val('DRAFT');
            }
            else if (btnval == 'APPROVED') {
                $('.remarks').attr('required', false);
                $("#invoice_status").val('APPROVED');
            }
            else if (btnval == 'REJECTED') {
                $('.req').show();
                $('.remarks').attr('required', true);
                $("#invoice_status").val('REJECTED')
            }
            else {
                $('.remarks').attr('required', false);
                $("#invoice_status").val('INITIATED');
            }
            qtyrequired();

            if (btnval == 'APPLYCHANGES')
                var savestatus = 'DRAFT';

            else if (btnval == 'SAVE' || btnval == 'SAVENEW')
                var savestatus = 'SAVE';
            else if (btnval == 'APPROVED')
                var savestatus = 'APPROVED';
            else if (btnval == 'REJECTED')
                var savestatus = 'REJECTED';

            var show_div = '<?php echo $show_div; ?>';
            if (show_div == "1")
                $('#panel_add').trigger('click');

            $('#savestatus').val(savestatus);

            var red_url = "{{URL::to('salesinvoice')}}";
            var redd_url = "{{URL::to('salesinvoiceapproval')}}";
            var source = '<?php echo $row->invoice_type; ?>';
            if (source == 'LABOUR') {
                var create_url = "{{URL::to('salesinvoicecreate') }}/0/LABOUR";
            } else if (source = 'STANDARD') {
                var create_url = "{{URL::to('salesinvoicecreate') }}/0/STANDARD";
            }

            var form = $('#salesinvoice'); 

            if (btnval != 'APPLYCHANGES') {
                var form = $('#salesinvoice');
                form.parsley().validate();
                if (form.parsley().isValid()) {
                    var $btn = $(this);            
                    $btn.prop('disabled', true);
                    <?php if ($row->source != "DISPATCH" && $row->source != "REPLACEMENT") { ?>
                        var check = check_qoh();
                    <?php } else { ?>
                        var check = 0;
                    <?php } ?>
                    if (check == 0) {

                        var form_data = new FormData(document.getElementById('salesinvoice'));
                        $.ajax({
                            url: "{{URL::to('salesinvoicesave')}}",
                            type: "POST",
                            data: form_data,
                            enctype: 'multipart/form-data',
                            processData: false,  
                            contentType: false,   
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
                            var edit_url = "{{URL::to('salesinvoicecreate') }}/" + id;
                            if (btnval != 'SAVE' && btnval != 'DRAFT' && btnval != 'APPROVED' && btnval != 'REJECTED') {
                                showCustomAlert(msg,status);
                                window.location.href = create_url;

                            }
                            else if (btnval == 'APPROVED' || btnval == 'REJECTED') {
                                showCustomAlert(msg,status);
                                window.location.href = redd_url;
                            }else {
                                showCustomAlert(msg,status);
                                window.location.href = red_url;

                            }
                        });
                    } else {
						
                        showCustomAlert("QOH is less than invoice quantity","error");
                    }
                }
            }else {

                <?php if ($row->source != "DISPATCH") { ?>
                    var check = check_qoh();
                <?php } else { ?>
                    var check = 0;
                <?php } ?>
                if (check == 0) {
                    var formdata = $('#salesinvoice').serialize();
                    var form_data = new FormData(document.getElementById('salesinvoice'));
                    $.ajax({
                        url: "{{URL::to('salesinvoicesave')}}",
                        type: "POST",
                        data: form_data,
                        enctype: 'multipart/form-data',
                        processData: false,  
                        contentType: false, 
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
                        var edit_url = "{{URL::to('salesinvoicecreate') }}/" + id;
                        showCustomAlert(msg,status);
                        window.location.href = edit_url;

                    });
                }

                else {
                    showCustomAlert("QOH is less than invoice quantity","info");
                }
            }
        });		
		
		
        function qtyrequired() {
            $(".bulk_qty").each(function (index) {
                var req = $(this).val();
                if (req < 0) {
                    $('.bulk_qty' + index).val('');
                }
            });
        }

        function check_qoh() {
            var check = 0;
            $('.bulk_qty').each(function (i) {
                var qty = $(this).val();
                var qoh = $('.bulk_qoh' + i).val();
                if (parseInt(qty) > parseInt(qoh)) {
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
        $(document).on('click', '.newaddress_save', function () {
            var customer_id = $('.ship_to_customer_id option:selected').val();
            var form = $('#newaddress');
            validationrule('newaddress');
            var formdata = $('#newaddress').serialize();
            form.parsley().validate();
            /**************** email validation start ***********/
            if (!ValidateEmail($("#contact_mail").val())) {
                mail = 1;
            }
            else {
                mail = 0;
            }
            /**************** email validation end ***********/
            if (form.parsley().isValid()) {
                if (mail != 1) {
                    var url = "{{ URL::to('newshiptocustomer')  }}/" + customer_id;
                    $.post(url, formdata, function (data) {
                        var type = $('.custype').val();
                        data[0] = $.trim(data[0]);
                        if (data[0] != '') {
                            if (type == "SHIP_TO") {
                                $('.ship_to_address_id').val(data[0]);
                                $('.shipping_to_address_txt').val(data[1]);
                                showCustomAlert("Ship to Address Saved Successfully","success");
                            }
                            else if (type == "BILL_TO") {
                                $('.bill_to_address_id').val(data[0]);
                                $('.billing_to_address_txt').val(data[1]);
                                showCustomAlert("Bill to Address Saved Successfully","success");
                            }
                        }
                        $('#new_address').modal('hide');
                    });
                } else {
                    showCustomAlert('Please enter valid mail (ex:example123@gmail.com)','info');
                }
            }
        });

        var x = 0;

// Helper to find the row and its index suffix used in your class names
function getRowIndexSuffix($el) {
  // Prefer an explicit hidden input if you have it:
  const explicit = $el.closest('tr').find('.bulk_line_no').val();
  if (explicit && !isNaN(Number(explicit))) return String(Number(explicit)); // normalize like "3"

  // Fallback: pull the last digits from a known class on the row. Adjust the class name if needed.
  const m = ($el.closest('tr').attr('class') || '').match(/(\d+)(?!.*\d)/);
  return m ? m[1] : ''; // may be empty if not found
}

$(document).on('change', '.bulk_tax_excemption', function () {

    let $row = $(this).closest('tr');
    let taxval = $(this).val();

    if (taxval === "Yes") {
        $row.find('.bulk_tax_group_id').val('8').trigger('change.select2');
    } else {
        $row.find('.bulk_tax_group_id').val('').trigger('change.select2');
    }

});


<?php if ($pageModule == "salesinvoice") { ?>
// ---------- helpers (keep once) ----------
function safeNum(v){ const x=parseFloat(v); return isNaN(x)?0:x; }
function getDEC(){
  const dp = $('#decimal_point').val();
  if (dp !== undefined && dp !== '' && !isNaN(Number(dp))) return Number(dp);
  return (typeof decimal !== 'undefined' && !isNaN(decimal)) ? Number(decimal) : 2;
}
function fmt(v, dec){ return Number(v).toFixed(dec); }

// Ensure an <option> exists, then set value.
// opts = { text: 'Display Text', trigger: 'change' | 'change.select2' | 'none' }
function setSelectValue($sel, value, opts) {
  const val = (value === null || value === undefined) ? '' : String(value);
  const o = Object.assign({ text: val, trigger: 'change' }, opts || {});
  if (!$sel.find('option[value="' + val.replace(/"/g, '\\"') + '"]').length && val !== '') {
    // Append a placeholder option so setting works immediately
    $sel.append(new Option(o.text, val, false, false));
  }
  // Select2 v4 prefers plain change; v3 uses select2('val')
  if ($sel.data('select2') && typeof $sel.select2 === 'function' && $sel.select2.hasOwnProperty('amd')) {
    // v4
    $sel.val(val);
    if (o.trigger === 'change') $sel.trigger('change');
    else if (o.trigger === 'change.select2') $sel.trigger('change.select2');
  } else if ($sel.data('select2') && typeof $sel.select2 === 'function') {
    // v3 fallback
    $sel.select2('val', val);
  } else {
    // plain select
    $sel.val(val);
    if (o.trigger === 'change') $sel.trigger('change');
  }
}

// Prevent re-entry recursion when we touch .bulk_product_id inside its own handler
function withLock($el, key, fn){
  key = key || 'updating';
  if ($el.data(key)) return;
  $el.data(key, true);
  try { fn(); } finally { $el.data(key, false); }
}

// ---------- main handler ----------
$(document).on('change', '.bulk_product_id', function (event) {

  const DEC  = getDEC();
  const $row = $(this).closest('tr');
  const $prod = $row.find('.bulk_product_id');

  withLock($prod, 'updating-product', () => {
    // Prefer a stable line index if you still need it elsewhere
    let idx = $row.find('.bulk_line_no').val();
    if (!idx || isNaN(Number(idx))) idx = String($row.index()); else idx = String(Number(idx));

    const pid      = $prod.val();
    const type     = 'so';
    const plid     = $('.pricelist_id').val();
    const shipToId = $('.ship_to_customer_id').val();
    const billToId = $('.bill_to_address_id').val();

    const $uom     = $row.find('.bulk_uom_code_id');
    const $unitP   = $row.find('.bulk_unit_price');
    const $stdP    = $row.find('.bulk_std_price');
    const $taxGrp  = $row.find('.bulk_tax_group_id');
    const $partNo  = $row.find('.bulk_part_no');
    const $qoh     = $row.find('.bulk_qoh');
    const $hsnDrop = $row.find('.bulk_hsn_code');
    const $discPct = $row.find('.bulk_discount_percentage');

    // ---- validations ----
    if (!shipToId) {
      showCustomAlert('Please Select Customer !!!', 'info');
      $('.pricelist_id').val('').trigger('change');
      setSelectValue($uom, '', {trigger:'change.select2'});
      $unitP.val(0); $stdP.val(0);
      setSelectValue($taxGrp, '', {trigger:'change.select2'});
      $qoh.val('');
      // IMPORTANT: set product silently to avoid recursion
      setSelectValue($prod, '', {trigger:'change.select2'});
      event.preventDefault();
      return;
    }

    if (!plid) {
      setSelectValue($uom, '', {trigger:'change.select2'});
      $unitP.val(0); $stdP.val(0);
      setSelectValue($taxGrp, '', {trigger:'change.select2'});
      setSelectValue($prod, '', {trigger:'change.select2'});
      $qoh.val('');
      if (typeof calc_by_index === 'function') calc_by_index(idx);
      showCustomAlert('Please Select Pricelist !!!', 'info');
      event.preventDefault();
      return;
    }

    if (!pid) {
      // product cleared
      setSelectValue($uom, '', {trigger:'change.select2'});
      $unitP.val(0); $stdP.val(0);
      setSelectValue($taxGrp, '', {trigger:'change.select2'});
      $qoh.val('');
      if (typeof calc_by_index === 'function') calc_by_index(idx);
      return;
    }

    // ---- fetch product details ----
    const prodUrl = "{{ url::to('productdetails_so') }}/"
      + encodeURIComponent(pid) + "/"
      + encodeURIComponent(plid) + "/"
      + encodeURIComponent(billToId || '') + "/"
      + encodeURIComponent(type);

    $.get(prodUrl, function (data) {

      // ---------- HSN population ----------
      const hsnid = data.multihsn; // e.g. "1,2,3"
      const hsn   = data.hsn_code;

      if (hsnid && String(hsnid).trim() !== '') {
        const condition = "classification_name='HSN' and gst_code_hdr_id in(" + hsnid + ")";
        const jcomboUrl = "{{ URL::to('jcomboform') }}"
          + "?table=f_gst_code_hdr_t:gst_code_hdr_id:classification_code"
          + "&order_by=classification_code asc"
          + "&parent=" + encodeURIComponent(condition);

        $.ajax({
          url: jcomboUrl,
          type: "GET",
          success: function (response) {
            let jsonData = response;
            if (typeof response === "string") {
              try { jsonData = JSON.parse(response); } catch(e){ console.error('Invalid JSON:', response); jsonData = []; }
            }
            $hsnDrop.empty().append('<option value="">-- Select HSN Code --</option>');
            $.each(jsonData || [], function(_, item){
              $hsnDrop.append('<option value="'+ item.val +'">'+ item.option_name +'</option>');
            });
            setSelectValue($hsnDrop, String(hsn || ''), {trigger:'change'}); // plain change so your HSN→Tax code runs
          },
          error: function (xhr, status, error) {
            console.error('HSN load error:', error);
            // still clear/select none
            $hsnDrop.empty().append('<option value="">-- Select HSN Code --</option>');
            setSelectValue($hsnDrop, '', {trigger:'change'});
          }
        });
      } else {
        $hsnDrop.empty().append('<option value="">-- Select HSN Code --</option>');
        setSelectValue($hsnDrop, '', {trigger:'change'});
      }

      // ---------- UOM / Tax Group / Part No ----------
      // Ensure an option exists before setting; we don't know the display text, use the code/id as text.
      setSelectValue($uom,    data.uom_code_id,    {text: String(data.uom_code_id || ''),    trigger:'change'});
      setSelectValue($taxGrp, data.tax_group_id,   {text: String(data.tax_group_id || ''),   trigger:'change'});
      setSelectValue($partNo, data.manufactpartno, {text: String(data.manufactpartno || ''), trigger:'change'});

      // ---------- Pricing ----------
      const currRate = safeNum($('.currency_rate').val());
      const unitPrice= safeNum(data.unit_price);
      const stdPrice = safeNum(data.std_price);

      if (currRate !== 0) {
        const rate = unitPrice / currRate;
        $unitP.val(fmt(rate, DEC));
        $stdP.val(fmt(stdPrice, DEC));
      } else {
        $unitP.val(fmt(unitPrice, DEC));
        $stdP.val(fmt(stdPrice, DEC));
      }

      if (unitPrice === 0) {
        showCustomAlert('There is no Pricelist For this Product',"info");
        // Clear controls; note: silent on product to avoid recursion
        setSelectValue($uom, '', {trigger:'change'});
        $unitP.val(0); $stdP.val(0);
        setSelectValue($taxGrp, '', {trigger:'change'});
        setSelectValue($prod, '', {trigger:'change.select2'}); // keep this silent for your own handler
      }

      // ---------- QOH ----------
      const qohQty = safeNum(data.qoh_qty);
      $qoh.val(qohQty > 0 ? fmt(qohQty, DEC) : 0);

      // ---------- Default discount from customer (if any) ----------
      const disCustomer = $('.discount_id option:selected').attr('data-display');
      if (typeof disCustomer !== 'undefined') {
        $discPct.val(disCustomer);
      }

      // ---------- Recalc ----------
      if (typeof calc_by_index === 'function') calc_by_index(idx);

    }).fail(function(){
      showCustomAlert('Unable to fetch product details. Please try again.', 'error');
    });
  });
});


<?php } ?>
		

// Helper: get the row's index suffix you use in classnames (e.g., .bulk_tax_group_id3)
function getRowIdx($row) {
  // Prefer an explicit line number if present
  const ln = $row.find('.bulk_line_no').val();
  if (ln && !isNaN(Number(ln))) return String(Number(ln));
  // Fallback: last digits in the row's class list
  const m = ($row.attr('class') || '').match(/(\d+)(?!.*\d)/);
  return m ? m[1] : '';
}

// purpose: load tax based on HSN code
$(document).on('change', '.bulk_hsn_code', function () {
  const $row = $(this).closest('tr');
  const idx  = getRowIdx($row);

  // Select2 may return a string or an array; normalize to first value
  let hsnid = $(this).val();
  if (Array.isArray(hsnid)) hsnid = hsnid[0];
  if (hsnid === undefined || hsnid === null) hsnid = '';

  const suppsiteid = $('.ship_to_address_id').val(); // ship-to site
  const m_type = 'Sales';

  const $taxEx   = $row.find('.bulk_tax_excemption' + idx);
  const $taxGrp  = $row.find('.bulk_tax_group_id' + idx);
  const $hsnSel  = $row.find('.bulk_hsn_code' + idx);

  const taxval = $taxEx.val() || '';

  // Guards
  if (!hsnid || hsnid === '0') return; // nothing selected
  if (!suppsiteid) {
    //showCustomAlert('Please select Ship-To Location', 'info');
    $taxGrp.val('').trigger('change');
    return;
  }


  // Exempt case: force tax group 8
  if (taxval === 'Yes') {
    $taxGrp.val('8').trigger('change');
    calc_by_index(idx);
    return;
  }

  // Non-exempt: fetch tax by HSN + location
  const url = "{{ URL::to('taxdetails') }}/"
    + encodeURIComponent(hsnid) + "/"
    + encodeURIComponent(suppsiteid) + "/"
    + encodeURIComponent(m_type);

  $.get(url, function (data) {
    const grp   = (data && data.tax_group_id != null) ? String(data.tax_group_id) : '0';
    const flag  = (data && typeof data.tax_group_id_expiry === 'string') ? data.tax_group_id_expiry.trim() : '';

    if (grp === '0') {
      // No valid group: show reason and clear selection
      if (flag === 'expiry') {
        $taxGrp.val(grp).trigger('change');
        showCustomAlert('Tax Group expired for this product', 'warning');
      } else if (flag === 'location') {
        showCustomAlert('Tax not assigned for this Location', 'warning');
      } else {
        $taxGrp.val(grp).trigger('change');
        showCustomAlert('Tax Group not assigned for this product', 'warning');
      }
      return;
    }

    // Success: set group and recalc this row
    $taxGrp.val(grp).trigger('change');
    calc_by_index(idx);
  }).fail(function () {
    showCustomAlert('Unable to load tax details for the selected HSN.', 'error');
  });
});

		
$(document).on('change', '.pricelist_id', function () {
  const pricelistId = $('.pricelist_id').val();          
  const customerId  = $('.ship_to_customer_id').val();            

  <?php if ($pageMethod != "pickorderfrominvoicecreate" && $pageMethod != "salesinvoiceapprovalview" && $pageMethod != "salesinvoice" && $row->invoice_type != "SAMPLE") { ?>
    if (customerId) {

      $('.bulk_product_id').each(function () {

        if ($(this).val()) $(this).trigger('change');
      });
    } else {
      showCustomAlert('Please Select Customer !!!', "warning");
      $('.ship_to_customer_id').focus();
    }
  <?php } ?>
});


  <?php if ($row->invoice_type != "REPLACEMENT") { ?>

function getRowIdxFrom($el){
  const $row = $el.closest('tr');
  const ln = $row.find('.bulk_line_no').val();
  if (ln && !isNaN(Number(ln))) return String(Number(ln));
  // Fallback to tr position (less stable if rows are re-ordered)
  return String($row.index());
}

function num(v){ const x = parseFloat(v); return isNaN(x) ? 0 : x; }


$(document).on('change', '.bulk_qty', function () {
  const idx  = getRowIdxFrom($(this));
  const $row = $(this).closest('tr');

  // Read both fields as numbers; missing values become 0
  const soQty   = num($('.bulk_salesorder_qty' + idx).val()); // "Dispatch/Order" qty field in your UI
  const invQty  = num($('.bulk_qty' + idx).val());

  // If comparison base doesn't exist (NaN→0) and you don't want to block, bail out early
  if (soQty <= 0) {
    // Optional: just recalc line totals if you want
    if (typeof calc_by_index === 'function') calc_by_index(idx);
    return;
  }

  if (invQty > soQty) {
    showCustomAlert("Invoice qty not greater than Dispatch", "info");
    $('.bulk_qty' + idx).val('');       // clear
    if (typeof calc_by_index === 'function') calc_by_index(idx);
    return;
  }

  // Valid qty: normalize formatting and recalc
  $('.bulk_qty' + idx).val(invQty.toFixed(DEC));
  if (typeof calc_by_index === 'function') calc_by_index(idx);
});

<?php } ?>  



		
        $(document).on('change', '.discount_id', function () {
            $(".bulk_product_id").each(function (index) {

                var dis_customer = $('.discount_id option:selected').attr('data-display');

                $('.bulk_discount_percentage' + index).val(dis_customer);
                var index = $(this).closest("tr").index();
                var unitprice = $('.bulk_unit_price' + index).val();
                var requiredqty = $('.bulk_qty' + index).val();
                var tds_amount = $('.tds_amount').val();
                var taxgrp = $('.bulk_tax_group_id' + index + ' option:selected').attr('data-display');

                taxgrp = taxgrp ? taxgrp : 0;
                var discountsperc = $('.bulk_discount_percentage' + index).val();
                var disamout = parseFloat(((requiredqty * unitprice) * discountsperc / 100)).toFixed(decimal);

                $('.bulk_discount_amount' + index).val(disamout);

                var other_total = parseFloat(((requiredqty * unitprice) - disamout));

                var trade_dis_pre = $(".trade_discount_pre").val();
                trade_dis_pre = trade_dis_pre ? trade_dis_pre : 0;

                var amount = ((parseFloat(other_total) * parseFloat(trade_dis_pre)) / 100).toFixed(decimal);

                var other_total = parseFloat(other_total) - parseFloat(amount);

                var taxamount = parseFloat((other_total) * taxgrp / 100);

                $('.bulk_tax_amount' + index).val(taxamount);
                var subtot = ((requiredqty * unitprice) - disamout - amount);
                var linetot = parseFloat(subtot + taxamount).toFixed($('#decimal_point').val());
                $(".bulk_line_total" + index).val(linetot);
                /* Code for set linetotal values into header level field*/
                var sum = 0;
                var sumtax = 0;
                var sumall = 0;
                var charge = 0;
                var tax = 0;
                var sumqty = 0;
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
                    tax += parseFloat($(this).val());
                });
                $('.bulk_qty').each(function () {
                    sumqty += parseFloat($(this).val());
                });
                sumall = parseFloat(charge).toFixed(decimal) + parseFloat(sum).toFixed(decimal);
                sumall = (isNaN(sumall) ? 0 : sumall);
                sumall = parseFloat(sumall).toFixed(decimal) - parseFloat(tds_amount).toFixed(decimal);
                sumqty = (isNaN(sumqty) ? 0 : sumqty);
                sumall = (isNaN(sumall) ? 0 : sumall);
                sumtax = parseFloat(tax).toFixed("{{\Session::get('decimal')}}") + parseFloat(charge).toFixed(decimal);
                $('#invoice_tax_total').val(parseFloat(sumtax).toFixed(decimal));
                $('#invoice_grand_total').val(parseFloat(sumall).toFixed(decimal));
                $('#balance_amount').val(parseFloat(sumall).toFixed(decimal));

                $(".tax_total_span").html(sumtax);

                $(".grand_total_span").html(parseFloat(sumall).toFixed(decimal));
                $('#qty_total').val(parseFloat(sumqty).toFixed(decimal));
                $(".qty_span").html(parseFloat(sumqty).toFixed(decimal));


                $('.quote_tax').val(tax);
                $(".tax_total_span").html(tax);
                /* end */

                $('.trade_discount').val(discount.toFixed(2)).trigger('input');

            });
        });




$(document).on('input change', '.bulk_discount_amount, .bulk_qty, .bulk_tax_group_id', function () {

  const DEC = (window.decimal !== undefined && !isNaN(window.decimal)) ? Number(window.decimal) : 2;

  const num = (v) => {
    if (v === null || v === undefined) return 0;
    v = String(v).replace(/,/g, '').trim();
    const x = parseFloat(v);
    return isNaN(x) ? 0 : x;
  };

  const fmt = (v) => Number(v).toFixed(DEC);

  const $row = $(this).closest('tr');

  const unitprice   = num($row.find('.bulk_unit_price').val());
  const requiredqty = num($row.find('.bulk_qty').val());
  const discountAmt = num($row.find('.bulk_discount_amount').val());

  const tds_amount    = num($('.tds_amount').val());
  const trade_dis_pre = num($('.trade_discount_pre').val()); // %

  // tax %: try data-display first, else option value
  const $opt = $row.find('.bulk_tax_group_id option:selected');
  let taxgrp = num($opt.attr('data-display'));
  if (!taxgrp) taxgrp = num($opt.val());

  const gross = unitprice * requiredqty;

  // % from amount (avoid divide by zero)
  const discountPct = gross > 0 ? (discountAmt * 100) / gross : 0;
  $row.find('.bulk_discount_percentage').val(fmt(discountPct));

  // apply discounts & tax
  const afterLineDisc = Math.max(gross - discountAmt, 0);
  const tradeDiscAmt  = (afterLineDisc * trade_dis_pre) / 100;
  const netBase       = Math.max(afterLineDisc - tradeDiscAmt, 0);

  const taxamount = (netBase * taxgrp) / 100;
  const linetot   = netBase + taxamount;

  $row.find('.bulk_tax_amount').val(fmt(taxamount));
  $row.find('.bulk_line_total').val(fmt(linetot));

  // totals
  let sumLines = 0, sumTax = 0, sumQty = 0, charges = 0;

  $('.bulk_line_total').each(function(){ sumLines += num($(this).val()); });
  $('.bulk_tax_amount').each(function(){ sumTax   += num($(this).val()); });
  $('.bulk_qty').each(function(){        sumQty   += num($(this).val()); });

  $('.charges').each(function(){ charges += num($(this).val()); });

  const taxTotal = sumTax + charges;
  const grand    = (sumLines + charges) - tds_amount;

  $('#invoice_tax_total').val(fmt(taxTotal));
  $('.quote_tax').val(fmt(sumTax));
  $('.tax_total_span').html(fmt(taxTotal));

  $('#invoice_grand_total').val(fmt(grand));
  $('#balance_amount').val(fmt(grand));
  $('.grand_total_span').html(fmt(grand));

  $('#qty_total').val(fmt(sumQty));
  $('.qty_span').html(fmt(sumQty));

});


		
function calc_by_index(index) {
  // ---- helpers ----
  const getDEC = () => {
    const dp = $('#decimal_point').val();
    if (dp !== undefined && dp !== null && dp !== '' && !isNaN(Number(dp))) return Number(dp);
    return (typeof decimal !== 'undefined' && !isNaN(decimal)) ? Number(decimal) : 2;
  };
  const DEC = getDEC();
  const num = (v) => {
    const x = parseFloat(v);
    return isNaN(x) ? 0 : x;
  };

  // ---- read row values (numeric) ----
  const unit_price = num($('.bulk_unit_price' + index).val());
  const qty        = num($('.bulk_qty' + index).val());

  // Tax group % can be in value OR data-display; prefer data-display if present
  let tax_group = $('.bulk_tax_group_id' + index + ' option:selected').attr('data-display');
  tax_group = num(tax_group || $('.bulk_tax_group_id' + index).val());

  // ---- row calculations ----
  const line_sub_total = unit_price * qty;                   // before tax
  const tax_amount     = (line_sub_total * tax_group) / 100; // tax value
  const linetot        = line_sub_total + tax_amount;        // after tax

  // write formatted numbers (strings) into inputs
  $('.bulk_tax_amount'       + index).val(tax_amount.toFixed(DEC));
  $('.bulk_line_sub_total'   + index).val(line_sub_total.toFixed(DEC));
  $('.bulk_line_total'       + index).val(linetot.toFixed(DEC));

  // ---- document-level subtotals/totals ----

  // Order sub total = sum of all line_sub_total
  let orderSub = 0;
  $('.bulk_line_sub_total').each(function () { orderSub += num($(this).val()); });
  $('.order_sub_total').val(orderSub.toFixed(DEC));

  // Order total (lines only, after tax)
  let linesTotal = 0;
  $('.bulk_line_total').each(function () { linesTotal += num($(this).val()); });
  $('.order_total').val(linesTotal.toFixed(DEC));

  // Charges
  const transport = num($('.transport_charges').val());
  const packaging = num($('.packaging_charges').val());
  const insurance = num($('.insurance_charges').val()); // was overwritten before
  const otherTax  = num($('.other_tax_amount').val());  // extra tax/cess etc.
  const tds       = num($('.tds_amount').val());        // if present

  const totalCharges = transport + packaging + insurance + otherTax;

  // Total tax across lines (do NOT apply .toFixed during accumulation)
  let taxSum = 0;
  $('.bulk_tax_amount').each(function () { taxSum += num($(this).val()); });

  // What to show as "invoice_tax_total"?
  // Typically: line taxes + any "other tax" field (NOT freight/packaging/insurance)
  const taxTotal = taxSum + otherTax;

  // Grand total = lines (incl. tax) + all charges - TDS
  const grandTotal = linesTotal + totalCharges - tds;

  // write formatted outputs (note: never chain .toFixed() after .val())
  $('#invoice_tax_total').val(taxTotal.toFixed(DEC));
  $('#invoice_grand_total').val(grandTotal.toFixed(DEC));

  $('.tax_total_span').html(taxTotal.toFixed(DEC));
  $('.grand_total_span').html(grandTotal.toFixed(DEC));
}






        $(document).on('keypress', '.bulk_unit_price,.bulk_qty,.bulk_discount_percentage,.pin_code,.contact_number', function (ev) {
            var regex = new RegExp("^[0-9.]+$");
            var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
            if (regex.test(str)) {
                return true;
            }
            ev.preventDefault();
            return false;
        });
        /*copy paste validation*/

        $('.bulk_qty,.bulk_discount_percentage').bind("cut copy paste", function (e) {
            e.preventDefault();
        });




        $('.customersearch').click(function () {
            $('#customerModal').modal('show');
            $('#customerModal').width("100%");
            var ct = $(this).val();

            $('.custype').val(ct);
            $(mygrid).jqGrid('setGridParam', {
                postData: { "site_type": null, "cid": null }
            }).trigger('reloadGrid');

            $("#gs_customer_name").val('');
            $("#gs_site_type").val('');
            $("#gs_customer_number").val('');
            $("#gs_customer_name,#gs_site_type,#gs_customer_number").attr('readonly', false);
        });




        $(document).on('click', '.new_billto', function () {

            var cid = $('.ship_to_customer_id option:selected').val();
            setTimeout(function () {
                $(':input', '#newaddress')
                    .not(':button, :submit, :reset, :hidden')
                    .val('')
                    .prop('checked', false)
                    .prop('selected', false);

            }, 500);
            $('.city,.country,.state').select2('val', ['']);
            if (cid != "") {
                $('#new_address').modal('show');
                $('#new_address').width("100%");
                var ct = "BILL_TO";
                $('.custype').val(ct);
            }
            else {
                showCustomAlert("Please select Customer","info");
            }

        });
		
		
		
        $('.billto').click(function () {
            var cid = $('.ship_to_customer_id').val();
            var c_name = $.trim($('.ship_to_customer_id option:selected').text()).split("-");
            if (cid == "") {
                showCustomAlert("Please select Customer first","warning");
            }
            else {

                $('#customerModal').modal('show');
                $('#customerModal').width("100%");

                var ct = $(this).val();
                $('.custype').val(ct);
                var custype = $('.custype').val();

                if (ct == "billto") {
                    var site_type = "BILL_TO";
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


        $(document).on('click', '.new_shipto', function () {

            var cid = $('.ship_to_customer_id option:selected').val();
            setTimeout(function () {
                $(':input', '#newaddress')
                    .not(':button, :submit, :reset, :hidden')
                    .val('')
                    .prop('checked', false)
                    .prop('selected', false);

            }, 500);
            $('.city,.country,.state').select2('val', ['']);
            if (cid != "") {
                $('#new_address').modal('show');
                $('#new_address').width("100%");

                var ct = "SHIP_TO";
                $('.custype').val(ct);
            }
            else {

                showCustomAlert("Please select Customer","warning");
            }

        });
		
		
		
        $('.shipto').click(function () {


            var cid = $('.ship_to_customer_id').val();
            var c_name = $.trim($('.ship_to_customer_id option:selected').text()).split("-");
            if (cid == "") {
                showCustomAlert("Please select Customer first","info");
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



        $('.invoice_currency').change(function () {
            var curr = $(this).val();
            var url = "{{URL::to('conversionexchangecurrency')}}/" + curr;
            if (curr != "") {
                $.get(url, function (data) {
                    data = $.trim(data);
                    console.log(data);
                    $('.con_exc_rate').val(data);

                });
            }
        });




        $(".cityhide,.statehide").css('pointer-events', 'none');


        $('.pricelist_id').trigger('change');


        /*end*/
        var invoicestatus = $('.invoice_status').val();
        if (invoicestatus != "INITIATED") {
			
// ------- helpers (keep once in your page) -------
function num(v){ const x = parseFloat(v); return isNaN(x) ? 0 : x; }
function getDEC(){
  const dp = $('#decimal_point').val();
  if (dp !== undefined && dp !== '' && !isNaN(Number(dp))) return Number(dp);
  return (typeof decimal !== 'undefined' && !isNaN(decimal)) ? Number(decimal) : 2;
}
function fmt(v, dec){ return Number(v).toFixed(dec); }
function getRowIndex($row){
  const ln = $row.find('.bulk_line_no').val();
  if (ln && !isNaN(Number(ln))) return String(Number(ln));
  return String($row.index());
}

// ------- main handler: open SO qty modal / compute / write back -------
$(document).on('click', '.invoicesoqty', function () {

  const DEC = getDEC();

  const $row = $(this).closest('tr');
  const idx  = $row.closest('tbody').find('tr').index($row); // ✅ correct index

  $('.qtyindex').val(idx);

  /* ---------- PAGE LEVEL VALUES ---------- */
  const source              = $('.source').val() || '';
  const reference_source_id = $('.reference_source_id').val() || 0;
  const soid                = $('.ar_sales_hdr_id').val() || 0;
  const pagemode            = '{{ $pagemode ?? "" }}';

  /* ---------- ROW LEVEL VALUES ---------- */
  const prdid = $row.find('.bulk_product_id').val() || 0;

  const salesorder_qty = num(
    $row.find('.bulk_salesorder_qty').first().val() || 0
  );

  const soqty_csv        = $row.find('.bulk_sales_order_qty').val() || '0';
  const so_id_csv        = $row.find('.bulk_sales_order').val() || '0';
  const invoice_qty_csv  = $row.find('.bulk_sales_order_invoice').val() || '0';
  const invoiced_qty_csv = $row.find('.bulk_sales_order_invoiced').val() || '0';

  const dispatch =
    (source === "DISPATCH" && soid) ? (reference_source_id || 0) : 0;

  let invlineid = 0;
  if (pagemode === 'edit') {
    invlineid = $row.find('.bulk_invoice_line_id').val() || 0;
  }

  /* ---------- OPEN MODAL FIRST ---------- */
  $('#invoicesoqtyModal').modal('show');
  $('.qtydetail').html($('#modal-loading').clone());

  /* ---------- BUILD URL ---------- */
  const url =
    "{{ URL::to('invoicesoqty') }}/" + idx + "/" + prdid + "/" + soid +
    "?so_qty="       + encodeURIComponent(soqty_csv) +
    "&so_id="        + encodeURIComponent(so_id_csv) +
    "&invoice_qty="  + encodeURIComponent(invoice_qty_csv) +
    "&dispatch="     + encodeURIComponent(dispatch) +
    "&invoiced_qty=" + encodeURIComponent(invoiced_qty_csv) +
    "&invlineid="    + encodeURIComponent(invlineid) +
    "&soinvqty="     + encodeURIComponent(salesorder_qty);

  /* ---------- LOAD MODAL DATA ---------- */
  $.get(url)
    .done(function (data) {

      $('.qtydetail').html(data);

      let total_invoice_qty = 0;
      const invoiceqty_arr   = [];
      const sorder_no_arr    = [];
      const sorder_qty_arr   = [];
      const invoiced_qty_arr = [];

      $('.invoice_issue_qty').each(function (k) {
        const v = num($(this).val());

        if (!v && $(this).val() === '') {
          showCustomAlert('Please enter Invoice Qty', 'error');
          return false;
        }

        total_invoice_qty += v;
        invoiceqty_arr[k] = fmt(v, DEC);
      });

      $('.so_id').each(function (k) {
        sorder_no_arr[k] = $(this).val() || 0;
      });

      $('.qty').each(function (k) {
        sorder_qty_arr[k] = fmt(num($(this).val()), DEC);
      });

      $('.invoicedqty').each(function (k) {
        invoiced_qty_arr[k] = fmt(num($(this).val()), DEC);
      });

      if (total_invoice_qty > salesorder_qty) {
        showCustomAlert("Invoice qty should not exceed order qty", "error");
        return;
      }

      /* ---------- WRITE BACK TO ROW ---------- */
      $row.find('.bulk_qty').val(fmt(total_invoice_qty, DEC));
      $row.find('.bulk_sales_order_invoice').val(invoiceqty_arr.join(','));
      $row.find('.bulk_sales_order_invoiced').val(invoiced_qty_arr.join(','));
      $row.find('.bulk_sales_order').val(sorder_no_arr.join(','));
      $row.find('.bulk_sales_order_qty').val(sorder_qty_arr.join(','));

      $('#invoicesoqtyModal').modal('hide');
    })
    .fail(function () {
      showCustomAlert('Unable to load SO quantities for this line.', 'error');
      $('#invoicesoqtyModal').modal('hide');
    });
});



        }


        $(".payment_term_id").trigger('change');
    });

	
    var dateToday = new Date();
    var data = "{{ \Session('j_date_format') ?? 'yy-mm-dd' }}";
    var monfree_status = "{{ $monfrez_active }}";
    console.log(monfree_status);
    if (monfree_status == "Yes") {
        var min = "{{ $mindate1 }}";
        console.log(min);
        var max = "{{ $maxdate1 }}";
    } else {
        var min = "{{ $mindate2 }}";
        var max = "{{ $maxdate2 }}";
    }
    $("#invoice_date").datepicker({
        changeMonth: true,
        dateFormat: data,
        changeYear: true,
        minDate: min,
        maxDate: max,
        onClose: function () {
            $(this).parsley().validate();
        }

    }).attr('readonly', 'readonly');
	

    var dateToday = new Date();
    var data = "{{\Session::get('j_date_format')}}";
    $("#approved_date").datepicker({
        changeMonth: true,
        dateFormat: data,
        changeYear: true,
        minDate: 0,
        maxDate: 0,
        onClose: function () {
            $(this).parsley().validate();
        }

    }).attr('readonly', 'readonly');

	
    var dateToday = new Date();
    var data = "{{\Session::get('j_date_format')}}";
    $("#lr_date").datepicker({
        changeMonth: true,
        dateFormat: data,
        changeYear: true,
        minDate: 0,
        maxDate: 0,
        onClose: function () {
            $(this).parsley().validate();
        }

    }).attr('readonly', 'readonly');

	
    var dateToday = new Date();
    var data = "{{\Session::get('j_date_format')}}";
    $("#due_date").datepicker({
        changeMonth: true,
        dateFormat: data,
        changeYear: true,
        minDate: 0,
        maxDate: 0,
        //maxDate: null,
        onClose: function () {
            $(this).parsley().validate();
        }

    }).attr('readonly', 'readonly');
	
	// Add Row
$(document).on('click', '.add-row', function () {

    const $lastRow = $('.clone_lines_body tr:last');
    const $newRow = $lastRow.clone(false, false);

    // Clear inputs EXCEPT line number
    $newRow.find('input').each(function () {
        if (!$(this).hasClass('bulk_line_no')) {
            $(this).val('');
        }
    });

    // Clear selects
    $newRow.find('select').val('');

    // ✅ Set bulk_tax_excemption to Yes
    $newRow.find('.bulk_tax_excemption').val('Yes');

    // Destroy select2 before append
    $newRow.find('select.select2').each(function () {
        if ($(this).hasClass("select2-hidden-accessible")) {
            $(this).select2('destroy');
        }
        $(this).removeAttr('data-select2-id');
        $(this).next('.select2').remove();
    });

    // Append row
    $('.clone_lines_body').append($newRow);

    // Reinit select2
    $newRow.find('select.select2').select2({ width: '100%' });

    // ✅ Trigger change for select2 UI update
    $newRow.find('.bulk_tax_excemption').trigger('change');

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
        const $lineInput = $(this).find('input.bulk_line_no');

        if ($lineInput.length) {
            $lineInput.val(index + 1);
        }
    });
}

</script>


@endpush