@extends('layouts.header')
@section('content')
@include('layouts.breadcrumb')

<?php
error_reporting(0);
if ($row->source == 'STANDARD' && $row->po_hdr_id == '' && !isset($copy_po_number)) {
    $head = " ( New )";
} else if ($row->po_hdr_id != '') {
    $head = " ( " . $row->po_number . " )";
} else if (isset($copy_po_number)) {
    $head = " ( Copy From " . $copy_po_number . " )";
} else {
    $head = " ( Convert From " . $row->source . " )";
}
?>
<?php include('tools_menu.php'); ?>
<h3 class="text-danger">Purchase Order {{$head}}
</h3>


<form method="post" action="" id="po_form" class="po_form needs-validation" novalidate>
    {{ csrf_field() }}

    <!-- Card Header -->
    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-primary text-white">
            <div class="row gy-2">
                <div class="col-md-3">
                    <strong>PO Date:</strong> <span class="badge bg-success">{{ $row->po_date }}</span> <br>
                    <strong>PO Type:</strong> <span class="badge bg-success">{{ $row->po_type }}</span>
                </div>
                <div class="col-md-3">
                    <strong>Reference No.:</strong> <span class="badge bg-success">{{$row->reference_number}} </span>
                    @if($ids == '1')
                    <i class="fa fa-plus text-warning ms-1 viewquote"></i>
                    @endif
                    <br>
                    <strong>Source:</strong> <span class="badge bg-success">{{$row->source}}</span>
                </div>
                <div class="col-md-3">
                    <strong>PO Tax Total:</strong>
                    <span id="tax_total_span" class="badge bg-success tax_total_span">{{ $row->po_tax_total }}</span><br>
                    <strong>PO Grand Total:</strong>
                    <span id="grand_total_span" class="badge bg-success grand_total_span">{{ $row->po_grand_total }}</span>
                </div>
                <div class="col-md-3">
                    <strong>Created By:</strong> <span class="badge bg-success create_by"></span><br>
                    <strong>Round Off:</strong>
                    <span id="round_off_span" class="badge bg-success round_off_span">{{ $row->round_off }}</span>
                </div>
            </div>
        </div>

        <!-- Card Body -->
        <div class="card-body">
            <div class="row g-4">

                <!-- Supplier Name -->
                <div class="col-md-3">
                    <label class="form-label">
                        <span class="text-danger">*</span> Supplier Name
                    </label>
                    <div class="input-group">
                        <select id="suppliername" name="supplier_id" class="form-select supplier_id select2" required>
                            {!! $supplier_id !!}
                        </select>

                    </div>
                </div>

                <!-- purpose for on change function -->

                <div class="form-group row" style="display:none;">
                    <label for="inputIsValid" class="form-control-label col-md-4"> PO Date</label>
                    <div class="col-md-6">
                        <div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy"
                            data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <input class="form-control po_date datepicker" id="po_date" name="po_date" size="16"
                                type="text" value="{{ $row->po_date }}" readonly>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                        <input type="hidden" id="po_date" value="{{ $row->po_date }}" />
                    </div>
                    <div class="col-md-1 showline">
                    </div>
                </div>

                <div class="form-group row" style="display:none;">
                    <label for="inputIsValid" class="form-control-label col-md-4">PO Type</label>
                    <div class="col-md-6">
                        <select type="text" name="po_type" id="po_type" class="form-control po_type" readonly>
                            <option value="">--select--</option>
                            <option <?php if ($row->po_type == "STANDARD") {
                                echo "selected";
                            } else {
                                echo "";
                            } ?>
                                value="STANDARD">STANDARD</option>
                            <option <?php if ($row->po_type == "LABOUR") {
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

                <div class="form-group row" style="display:none;">
                    <label for="inputIsValid" class="form-control-label col-md-4">PO Status</label>
                    <div class="col-md-6">
                        <select type="text" name="po_status" id="po_status" class="form-control po_status" readonly>
                            <option value="">--Please Select--</option>
                            <option <?php if ($row->po_status == "DRAFT") {
                                echo "selected";
                            } else {
                                echo "";
                            } ?>
                                value="DRAFT">DRAFT</option>
                            <option <?php if ($row->po_status == "INITIATED") {
                                echo "selected";
                            } else {
                                echo "";
                            } ?>
                                value="INITIATED">INITIATED</option>
                            <option <?php if ($row->po_status == "APPROVED") {
                                echo "selected";
                            } else {
                                echo "";
                            } ?>
                                value="APPROVED">APPROVED</option>
                            <option <?php if ($row->po_status == "REJECTED") {
                                echo "selected";
                            } else {
                                echo "";
                            } ?>
                                value="REJECTED">REJECTED</option>
                            <option <?php if ($row->po_status == "CANCELLED") {
                                echo "selected";
                            } else {
                                echo "";
                            } ?>
                                value="CANCELLED">CANCELLED</option>
                            <option <?php if ($row->po_status == "CLOSED") {
                                echo "selected";
                            } else {
                                echo "";
                            } ?>
                                value="CLOSED">CLOSED</option>
                            <option <?php if ($row->po_status == "COMPLETED") {
                                echo "selected";
                            } else {
                                echo "";
                            } ?>
                                value="COMPLETED">COMPLETED</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row" style="display:none;">
                    <label for="inputIsValid" class="form-control-label col-md-4">Amendment Status</label>
                    <div class="col-md-6">
                        <select type="text" name="amendment_status" id="amendment_status"
                            class="form-control amendment_status" readonly>
                            <option value="">--Please Select--</option>
                            <option <?php if ($row->amendment_status == "1") {
                                echo "selected";
                            } else {
                                echo "";
                            } ?>
                                value="1">INITIATED</option>

                        </select>
                    </div>
                </div>
                <div class="form-group row" style="display:none;">
                    <label for="inputIsValid" class="form-control-label col-md-4">Organization Name</label>
                    <div class="col-md-6">
                        <select name='organization_id' rows='5' tabindex="6" class='form-control organization_id'>
                            {!! $organization_id !!}
                        </select>
                    </div>
                    <div class="col-md-2 showinline">
                    </div>
                </div>
                <div class="form-group row" style="display:none;">
                    <label for="inputIsValid" class="form-control-label col-md-4">Created By</label>
                    <div class="col-md-6">
                        <select name='created_by' rows='5' class='form-control created_by' tabindex="7"
                            data-show-subtext="true" data-live-search="true">
                            {!! $created_by !!}
                        </select>
                    </div>
                    <div class="col-md-2 showline">
                    </div>
                </div>


                <div class="form-group row" style="display:none;">
                    <label for="inputIsValid" class="form-control-label col-md-4">Reference No</label>
                    <div class="col-md-6">
                        <input class="form-control reference_id" id="reference_id" name="reference_id" size="16"
                            type="hidden" value="{{$row->reference_id}}" readonly>
                        <input type="text" id="reference_number" name="reference_number"
                            class="form-control reference_number" value="{{$row->reference_number}}" readonly>
                    </div>
                    <div class="col-md-2">
                    </div>
                </div>
                <div class="form-group row" style="display:none;">
                    <label for="inputIsValid" class="form-control-label col-md-4">Source</label>
                    <div class="col-md-6">
                        <select name='source' rows='5' class='form-control source' data-show-subtext="true"
                            data-live-search="true" readonly>
                            <option value="">--select--</option>
                            <option <?php if ($row->source == "STANDARD") {
                                echo "selected";
                            } else {
                                echo "";
                            } ?>
                                value="STANDARD">STANDARD</option>
                            <option <?php if ($row->source == "REQUISITION") {
                                echo "selected";
                            } else {
                                echo "";
                            } ?>
                                value="REQUISITION">REQUISITION</option>
                            <option <?php if ($row->source == "ENQUIRY") {
                                echo "selected";
                            } else {
                                echo "";
                            } ?>
                                value="ENQUIRY">ENQUIRY</option>
                            <option <?php if ($row->source == "QUOTATION") {
                                echo "selected";
                            } else {
                                echo "";
                            } ?>
                                value="QUOTATION">QUOTATION</option>
                            <option <?php if ($row->source == "PO") {
                                echo "selected";
                            } else {
                                echo "";
                            } ?> value="PO">
                                PO
                            </option>

                        </select>
                    </div>
                    <div class="col-md-2">
                    </div>
                </div>
                <div class="form-group row" style="display:none;">
                    <label for="inputIsValid" class="form-control-label col-md-4">PO Tax Total</label>
                    <div class="col-md-6">
                        <input type="text" name="po_tax_total" id="po_tax_total" value="{{ $row->po_tax_total }}"
                            class="form-control po_tax_total" readonly>
                    </div>
                    <div class="col-md-2">
                    </div>
                </div>
                <div class="form-group row" style="display:none;">
                    <label for="inputIsValid" class="form-control-label col-md-4">PO Grand Total</label>
                    <div class="col-md-6">
                        <input type="text" name="po_grand_total" id="po_grand_total" value="{{ $row->po_grand_total }}"
                            class="form-control po_grand_total" readonly>
                    </div>
                    <div class="col-md-2">
                    </div>
                </div>
                <div class="col-md-4 form-group row" style="display:none;">
                    <label for="inputIsValid" class="form-control-label col-md-4">PO Number</label>
                    <div class="col-md-6">
                        <input class="form-control po_hdr_id" id="po_hdr_id" name="po_hdr_id" size="16" type="hidden"
                            value="{{ $row->po_hdr_id }}" readonly>
                        <input type="text" id="po_number" name="po_number" class="form-control po_number"
                            value="{{ $row->po_number }}" readonly>
                    </div>
                    <div class="col-md-2">

                    </div>
                </div>

                <!-- end -->


                <div class="col-md-1">
                    <button type="button" class="btn btn-outline-secondary suppliersearch mt-3"><i
                            class="fa fa-search"></i></button>
                </div>
                <!-- Supplier Site -->
                <div class="col-md-4">
                    <label class="form-label">Supplier Site</label>
                    <div class="input-group">
                        <select name="suppliersite_id" class="form-select suppliersite_id select2">
                            {!! $suppliersite_id !!}
                        </select>
                    </div>
                </div>

                <!-- Reverse Charge -->
                <div class="col-md-4">
                    <label class="form-label">Reverse Charge Applicable?</label>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input reverse_charge" type="checkbox" name="reverse_charge[]" value="1"
                            @if($row->reverse_charge == "1") checked @endif>
                        <label class="form-check-label">Yes</label>
                    </div>
                </div>

                <!-- Delivery Date -->
                <div class="col-md-4">
                    <label class="form-label">Delivery Date</label>
                    <input type="text" name="delivery_date" id="delivery_date" class="form-control delivery_date"
                        value="{{$row->delivery_date}}">
                </div>

                <!-- Pricelist Term -->
                <div class="col-md-4">
                    <label class="form-label">
                        <span class="text-danger">*</span> Pricelist Term
                    </label>
                    <select name="po_pricelist_id" class="form-select po_pricelist_id select2" required>
                        {!! $po_pricelist_id !!}
                    </select>
                </div>

                <!-- File Upload -->
                <div class="col-md-4">
                    <label class="form-label">File Upload</label>
                    <input type="file" id="choosefile" name="choosefile[]" class="form-control" multiple>
                    @php
                    $dataupload = json_decode($row->attachfile_name, true);
                    @endphp
                    
            <input type="hidden"
                id="existing_file"
                name="existing_file"
                value="{{ !empty($dataupload) ? implode(',', $dataupload) : '' }}">

               <?php     if($row->source == "QUOTATION"){ ?>
                    <div class="mt-2">
                        @php $dataupload = json_decode($row->attachfile_name); @endphp
                        @if(!empty($dataupload))
                        <ul class="list-group">
                            @foreach($dataupload as $file)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="{{ URL::to('') }}/Uploads/poquoteattachment/PO{{ $row->quo_id }}/{{ $file }}"
                                    target="_blank">{{ $file }}</a>
                                <img src="{{ URL::to('') }}/images/cancel.png" class="delete_user"
                                    data-value="{{ $file }}" style="cursor:pointer;">
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                <?php  }else{ ?>
                    <div class="mt-2">
                        @php $dataupload = json_decode($row->attachfile_name); @endphp
                        @if(!empty($dataupload))
                        <ul class="list-group">
                            @foreach($dataupload as $file)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="{{ URL::to('') }}/Uploads/purchaseorder/PO{{ $row->po_hdr_id }}/{{ $file }}"
                                    target="_blank">{{ $file }}</a>
                                <img src="{{ URL::to('') }}/images/cancel.png" class="delete_user"
                                    data-value="{{ $file }}" style="cursor:pointer;">
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                <?php  } ?>
                </div>

                <!-- PO for VERDURA -->
                <div class="col-md-4">
                    <label class="form-label text-danger fw-bold">Is this PO for VERDURA?</label>
                    <select name="po_for_verdura" class="form-select po_for_verdura select2" required>
                        <option value="">--Please Select--</option>
                        <option value="YES" @if($row->po_for_verdura == "YES") selected @endif>YES</option>
                        <option value="NO" @if($row->po_for_verdura == "NO") selected @endif>NO</option>
                    </select>
                </div>
                </div>

                <!-- Amendment Status -->
                <div class="row mb-3 d-none">
                    <label class="col-md-4 col-form-label">Amendment Status</label>
                    <div class="col-md-6">
                        <select name="amendment_status" id="amendment_status" class="form-select amendment_status"
                            disabled>
                            <option value="">--Please Select--</option>
                            <option value="1" {{ $row->amendment_status == "1" ? "selected" : "" }}>INITIATED</option>
                        </select>
                    </div>
                </div>

                <!-- Source -->
                <div class="row mb-3 d-none">
                    <label class="col-md-4 col-form-label">Source</label>
                    <div class="col-md-6">
                        <select name="source" class="form-select" disabled>
                            <option value="">--select--</option>
                            @foreach(["STANDARD","REQUISITION","ENQUIRY","QUOTATION","PO"] as $src)
                            <option value="{{ $src }}" {{ $row->source == $src ? "selected" : "" }}>{{ $src }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Round Off -->
                <div class="row mb-3 d-none">
                    <label class="col-md-4 col-form-label">Round Off</label>
                    <div class="col-md-6">
                        <input type="hidden" name="advance_status" id="advance_status" value="0">
                        <input type="hidden" name="advance_amount" id="advance_amount" value="0">
                        <!-- <input type="hidden" name="balance_amount" class="grand_total_span" value="{{ $row->po_grand_total }}"> -->
                        <input type="text" name="round_off" id="round_off" value="{{ $row->round_off }}"
                            class="form-control round_off" readonly>
                    </div>
                </div>


                <div class="row g-3">
                    <div class="col-12">
                        <h5 class="myheaders mb-3 text-primary">Additional Details</h5>
                    </div>

                    <?php
                    $i = 0;
                    $j = 0;
                    foreach ($enabled_columns as $index => $val) {
                        $required = ($val->action == '1') ? "required" : '';

                        // Payment Term
                        if ($val->column_name == 'payment_term_id' && $val->active == 1) {
                            $i++; ?>
                            <div class="col-md-4">
                                <label class="form-label"><?= $required ? '<span class="text-danger">*</span>' : '' ?> Payment
                                    Term</label>
                                <div class="input-group">
                                    <select name="payment_term_id" class="form-select select2 payment_term_id" <?= $required; ?>>
                                        {!! $payment_term_id !!}
                                    </select>
                                </div>
                            </div>
                        <?php } ?>

                        <!-- Freight Term -->
                        <?php if ($val->column_name == 'freight_terms_id' && $val->active == 1) {
                            $i++; ?>
                            <div class="col-md-4">
                                <label class="form-label"><?= $required ? '<span class="text-danger">*</span>' : '' ?> Freight
                                    Term</label>
                                <div class="input-group">
                                    <select name="freight_terms_id" class="form-select select2 freight_terms_id" <?= $required; ?>>
                                        {!! $freight_terms_id !!}
                                    </select>
                                </div>
                            </div>
                        <?php } ?>

                        <!-- Freight Carrier -->
                        <?php if ($val->column_name == 'freight_carrier_id' && $val->active == 1) {
                            $i++; ?>
                            <div class="col-md-4">
                                <label class="form-label"><?= $required ? '<span class="text-danger">*</span>' : '' ?> Freight
                                    Carrier</label>
                                <div class="input-group">
                                    <select name="freight_carrier_id" class="form-select select2 freight_carrier_id"
                                        <?= $required; ?>>
                                        {!! $freight_carrier_id !!}
                                    </select>
                                </div>
                            </div>
                        <?php } ?>

                        <!-- Delivery Term -->
                        <?php if ($val->column_name == 'delivery_terms_id' && $val->active == 1) {
                            $i++; ?>
                            <div class="col-md-4">
                                <label class="form-label"><?= $required ? '<span class="text-danger">*</span>' : '' ?> Delivery
                                    Term</label>
                                <div class="input-group">
                                    <select name="delivery_terms_id" class="form-select select2 delivery_terms_id" <?= $required; ?>>
                                        {!! $delivery_terms_id !!}
                                    </select>
                                </div>
                            </div>
                        <?php } ?>

                        <!-- Project Name -->
                        <?php if ($val->column_name == 'project_id' && $val->active == 1) {
                            $i++; ?>
                            <div class="col-md-4">
                                <label class="form-label"><?= $required ? '<span class="text-danger">*</span>' : '' ?> Project
                                    Name</label>
                                <div class="input-group">
                                    <select name="project_id" class="form-select select2 project_id" <?= $required; ?>>
                                        {!! $project_id !!}
                                    </select>
                                </div>
                            </div>
                        <?php } ?>

                        <!-- Currency -->
                        <?php if ($val->column_name == 'currency' && $val->active == 1) {
                            $i++; ?>
                            <div class="col-md-4">
                                <label class="form-label"><?= $required ? '<span class="text-danger">*</span>' : '' ?>
                                    Currency</label>
                                <div class="input-group">
                                    <select name="currency" class="form-select select2 currency_jcombo" <?= $required; ?>>
                                        {!! $currency !!}
                                    </select>
                                </div>
                            </div>
                        <?php } ?>

                        <!-- Supplier Ref No -->
                        <?php if ($val->column_name == 'supplier_reference_no' && $val->active == 1) {
                            $i++; ?>
                            <div class="col-md-4">
                                <label class="form-label"><?= $required ? '<span class="text-danger">*</span>' : '' ?> Supplier
                                    Ref No</label>
                                <input type="text" name="supplier_reference_no" id="supplier_reference_no"
                                    value="{{ $row->supplier_reference_no }}" class="form-control" <?= $required; ?>>
                            </div>
                        <?php } ?>

                        <!-- Bill To Address -->
                        <?php if ($val->column_name == 'bill_to_address_id' && $val->active == 1) {
                            $i++; ?>
                            <div class="col-md-4">
                                <label class="form-label"><?= $required ? '<span class="text-danger">*</span>' : '' ?> Bill To
                                    Address</label>
                                <textarea name="bill_to_address" id="bill_to_address" class="form-control bill_to_address"
                                    readonly <?= $required; ?>>{{ $bill_to_address }}</textarea>
                            </div>
                        <?php } ?>

                        <!-- Ship To Address -->
                        <?php if ($val->column_name == 'ship_to_address_id' && $val->active == 1) {
                            $i++; ?>
                            <div class="col-md-4">
                                <label class="form-label"><?= $required ? '<span class="text-danger">*</span>' : '' ?> Ship To
                                    Address</label>
                                <textarea name="ship_to_address" id="ship_to_address" class="form-control ship_to_address"
                                    readonly <?= $required; ?>>{{ $ship_to_address }}</textarea>
                            </div>
                        <?php } ?>

                        <!-- Packing Charges -->
                        <?php if ($val->column_name == 'packing_charges' && $val->active == 1) {
                            $i++; ?>
                            <div class="col-md-4">
                                <label class="form-label">Packing Charges</label>
                                <div class="input-group">
                                    <input type="text" name="packing_charges" id="packing_charges"
                                        value="{{ $row->packing_charges }}" class="form-control packing_charges" readonly
                                        <?= $required; ?>>
                                    <button type="button" class="btn btn-outline-secondary packingtax"
                                        data-value="Packing Charges" data-at="1"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        <?php } ?>

                        <!-- Transport Charges -->
                        <?php if ($val->column_name == 'transport_charges' && $val->active == 1) {
                            $i++; ?>
                            <div class="col-md-4">
                                <label class="form-label">Transport Charges</label>
                                <div class="input-group">
                                    <input type="text" name="transport_charges" id="transport_charges"
                                        value="{{ $row->transport_charges }}" class="form-control transport_charges" readonly
                                        <?= $required; ?>>
                                                                        <input type="hidden" name="packing_charges_tax" id="packing_charges_tax" value="{{ $row->packing_charges_tax}}" class="form-control  packing_charges_tax">
                                <input type="hidden" name="insurance_charges_tax" id="insurance_charges_tax" value="{{ $row->insurance_charges_tax}}" class="form-control  insurance_charges_tax"  >       
                                <input type="hidden" name="transport_charges_tax" id="transport_charges_tax" value="{{ $row->transport_charges_tax}}" class="form-control  transport_charges_tax"   >      
                                <input type="hidden" name="unloading_charges_tax" id="unloading_charges_tax" value="{{ $row->unloading_charges_tax}}" class="form-control  unloading_charges_tax">    
                                    <button type="button" class="btn btn-outline-secondary packingtax"
                                        data-value="Transport Charges" data-at="2"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        <?php } ?>

                        <!-- Unloading Charges -->
                        <?php if ($val->column_name == 'unloading_charges' && $val->active == 1) {
                            $i++; ?>
                            <div class="col-md-4">
                                <label class="form-label">Unloading Charges</label>
                                <div class="input-group">
                                    <input type="text" name="unloading_charges" id="unloading_charges"
                                        value="{{ $row->unloading_charges }}" class="form-control unloading_charges" readonly
                                        <?= $required; ?>>
                                    <button type="button" class="btn btn-outline-secondary packingtax"
                                        data-value="Unloading Charges" data-at="3"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        <?php } ?>

                        <!-- Insurance Charges -->
                        <?php if ($val->column_name == 'insurance_charges' && $val->active == 1) {
                            $i++; ?>
                            <div class="col-md-4">
                                <label class="form-label">Insurance Charges</label>
                                <div class="input-group">
                                    <input type="text" name="insurance_charges" id="insurance_charges"
                                        value="{{ $row->insurance_charges }}" class="form-control insurance_charges" readonly
                                        <?= $required; ?>>
                                    <button type="button" class="btn btn-outline-secondary packingtax"
                                        data-value="Insuance Charges" data-at="4"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        <?php } ?>

                        <!-- Ship To Location -->
                        <?php if ($val->column_name == 'ship_to_location_id' && $val->active == 1) {
                            $i++; ?>
                            <div class="col-md-4">
                                <label class="form-label">Ship To Location</label>
                                <select name="ship_to_location_id" id="ship_to_location_id"
                                    class="form-select select2 ship_to_location_id" <?= $required; ?>>
                                    {!! $ship_to_location_id !!}
                                </select>
                            </div>
                        <?php } ?>

                        <!-- Default Payment Method -->
                        <?php if ($val->column_name == 'default_payment_method_id' && $val->active == 1) {
                            $i++; ?>
                            <div class="col-md-4">
                                <label class="form-label">Default Payment Method</label>
                                <select name="default_payment_method_id" id="default_payment_method_id"
                                    class="form-select select2 default_payment_method_id" <?= $required; ?>>
                                    {!! $default_payment_method_id !!}
                                </select>
                            </div>
                        <?php } ?>

                        <!-- Insurance Term -->
                        <?php if ($val->column_name == 'insurance_term_id' && $val->active == 1) {
                            $i++; ?>
                            <div class="col-md-4">
                                <label class="form-label">Insurance Term</label>
                                <select name="insurance_term_id" id="insurance_term_id"
                                    class="form-select select2 insurance_term_id" <?= $required; ?>>
                                    {!! $insurance_term_id !!}
                                </select>
                            </div>
                        <?php } ?>

                        <!-- Bill To Location -->
                        <?php if ($val->column_name == 'bill_to_location_id' && $val->active == 1) {
                            $i++; ?>
                            <div class="col-md-4">
                                <label class="form-label">Bill To Location</label>
                                <select name="bill_to_location_id" id="bill_to_location_id"
                                    class="form-select select2 bill_to_location_id" <?= $required; ?>>
                                    {!! $bill_to_location_id !!}
                                </select>
                            </div>
                        <?php } ?>

                        <!-- Other Freight Amount -->
                        <?php if ($val->column_name == 'other_frieght_amount' && $val->active == 1) {
                            $i++; ?>
                            <div class="col-md-4">
                                <label class="form-label">Other Freight Amount</label>
                                <input type="text" name="other_frieght_amount" id="other_frieght_amount"
                                    value="{{ $row->other_frieght_amount }}" class="form-control other_frieght_amount"
                                    <?= $required; ?>>
                            </div>
                        <?php } ?>

                        <!-- Other Tax Amount -->
                        <?php if ($val->column_name == 'other_tax_amount' && $val->active == 1) {
                            $i++; ?>
                            <div class="col-md-4">
                                <label class="form-label">Other Tax Amount</label>
                                <input type="text" name="other_tax_amount" id="other_tax_amount"
                                    value="{{ $row->other_tax_amount }}" class="form-control other_tax_amount" <?= $required; ?>>
                            </div>
                        <?php } ?>

                        <!-- Remarks -->
                        <?php if ($val->column_name == 'remarks' && $val->active == 1) {
                            $i++; ?>
                            <div class="col-md-4">
                                <label class="form-label">Remarks</label>
                                <textarea name="remarks" id="remarks" class="form-control remarks" <?= $required; ?>>{{ $row->remarks }}</textarea>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>




                <div class="row mt-4">
                    <div class="col-12 linetable">
                        <div id="preview-area" class="table-responsive">
                            <table class="table table-bordered clone_table" style="width:200%;">
                                <thead class="table-light">
                                    <tr>
                                        <th class="column1">Line No</th>
                                        <th class="pdtdiv freeze">Product</th>
                                        <th class="hidepart">Supplier Part No</th>
                                        <th class="pdtdes_div">Product Description</th>
                                        <th class="column4">Uom Code </th>
                                        <th class="column5" style="width: 6%;">Qty</th>
                                        <?php if ($ids == '3') { ?>
                                            <th>Received Qty</th>
                                        <?php } ?>
                                        <th class="column6"></th>
                                        <th class="column7">Price</th>
                                        <th class="column8">Discount(%)</th>
                                        <th class="column9">Discount Amount</th>
                                        <?php if ($row->po_type == "STANDARD") { ?>
                                            <th class="column10"> HSN Code </th>
                                        <?php } else { ?>
                                            <th> SAC Code </th>
                                        <?php } ?>
                                        <th class="column11">Tax Group</th>
                                        <th class="column12">Tax Amount</th>
                                        <th class="column13">Sub Total</th>
                                        <th class="column14">Line Total</th>
                                        <th class="column15">Qoh Qty</th>
                                        <th class="column16">Promised Date</th>
                                        <th class="column17">Promised Alternate Date</th>
                                        <th class="column18">Comments</th>
                                        <th style="width: 60px;"></th>
                                    </tr>
                                </thead>
                                <tbody class="clone_lines_body">
                                    @if(count($linedata) > 0)
                                    @foreach($linedata as $key => $value)
                                    <tr class="line-row">
                                        <td class="column1">
                                            <input type="hidden" name="bulk_po_line_id[]"
                                                class="form-control input-sm bulk_po_line_id"
                                                value="{{ $value->po_line_id }}">
                                            <input type="text" name="bulk_line_no[]"
                                                class="form-control input-sm bulk_line_no " value="{{ $key + 1 }}"
                                                readonly="readonly">
                                        </td>
                                        <td class="pdtdiv freeze">
                                            <select name="bulk_product_id[]" id="bulk_product_id"
                                                class="select2 bulk_product_id  parsley-validated"
                                                required="required">{!! $value->product_id !!}</select>
                                        </td>


                                        
                                        <td class="partno hidepart" style="pointer-events:none">
                                            <select name="bulk_part_no[]" class="select2 bulk_part_no ">{!!
                                                $value->part_no !!}</select>
                                        </td>
                                        <td class="pdtdes_div">
                                            <input type="text" name="bulk_product_description[]"
                                                class="form-control input-sm bulk_product_description"
                                                value="{{ $value->product_description }}">
                                        </td>

                                        <td class="uomdiv column4">
                                            <select name="bulk_uom_code_id[]" id="bulk_uom_code_id"
                                                class="select2 bulk_uom_code_id">
                                                {!! $value->uom_code_id !!}
                                            </select>
                                        </td>
                                        <td class="column5">
                                            <input type="text" name="bulk_qty[]" class="form-control input-sm bulk_qty "
                                                value="{{ $value->qty }}" required="required">
                                        </td>
                                        <?php if ($ids == "3") { ?>
                                            <td>
                                                <input type="text" class="form-control input-sm bulk_received_qty "
                                                    value="{{$value->received_qty}}" required="required">
                                            </td>
                                        <?php } ?>
                                        <td class="column6"><i class="fa fa-rupee productprice"></i></td>
                                        <td class="column7">
                                            <input type="text" name="bulk_unit_price[]"
                                                class="form-control input-sm bulk_unit_price "
                                                value="{{ $value->unit_price }}" readonly required="required">
                                        </td>
                                        <td class="column8">
                                            <input type="text" name="bulk_discount_percentage[]"
                                                class="form-control input-sm bulk_discount_percentage "
                                                value="{{ $value->discount_percentage }}">
                                        </td>
                                        <td class="column9">
                                            <input type="text" name="bulk_discount_amount[]"
                                                class="form-control input-sm bulk_discount_amount "
                                                value="{{ $value->discount_amount }}">
                                        </td>
                                        <td class="hsn column10">
                                            <select name="bulk_hsn_code[]" id="bulk_hsn_code"
                                                class="select2 bulk_hsn_code" required> {!! $value->hsn_code
                                                !!}</select>
                                        </td>
                                        <td class="column11">
                                            <select name="bulk_tax_group_id[]" id="bulk_tax_group_id"
                                                class="select2 bulk_tax_group_id" required="required">
                                                {!! $value->tax_group_id !!}
                                            </select>
                                        </td>
                                        <td class="column12">
                                            <input type="text" name="bulk_tax_amount[]"
                                                class="form-control input-sm bulk_tax_amount "
                                                value="{{ $value->tax_amount }}" required="required">
                                        </td>
                                        <td class="column13">
                                            <input type="text" name="bulk_line_sub_total[]"
                                                class="form-control input-sm bulk_line_sub_total "
                                                value="{{ $value->line_sub_total }}" required="required">
                                        </td>
                                        <td class="column14">
                                            <input type="text" name="bulk_line_total[]"
                                                class="form-control input-sm bulk_line_total "
                                                value="{{ $value->line_total }}" required="required">
                                        </td>
                                        <td class="column15"><input type="text" name="bulk_qoh_qty[]"
                                                class="form-control  bulk_qoh_qty" value="{{ $value->qoh_qty }}"
                                                readonly></td>
                                        <td class="prodate column16">
                                            <input type="text" name="bulk_promised_date[]"
                                                class="form-control input-sm bulk_promised_date" required
                                                value="{{ $value->promised_date }}">
                                        </td>
                                        <td class="prodate column17">
                                            <input type="text" name="bulk_promised_alternate_date[]"
                                                class="form-control input-sm bulk_promised_alternate_date"
                                                value="{{ $value->promised_alternate_date }}">
                                        </td>
                                        <td class="column18">
                                            <textarea name="bulk_comments[]"
                                                class="form-control input-sm bulk_comments ">{{ $value->comments }}</textarea>
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
                                        <td class="column1">
                                            <input type="hidden" name="bulk_po_line_id[]"
                                                class="form-control input-sm bulk_po_line_id" value="">
                                            <input type="text" name="bulk_line_no[]"
                                                class="form-control input-sm bulk_line_no column1" value="1"
                                                readonly="readonly">
                                        </td>
                                        <td class="pdtdiv freeze">
                                            <select name="bulk_product_id[]" id="bulk_product_id"
                                                class="select2 bulk_product_id  parsley-validated"
                                                required="required">{!! $product_id!!}</select>
                                        </td>
                                        
                                        <td class="partno hidepart" style="pointer-events:none">
                                            <select name="bulk_part_no[]" class="select2 bulk_part_no ">{!! $part_no
                                                !!}</select>
                                        </td>
                                        <td class="pdtdes_div">
                                            <input type="text" name="bulk_product_description[]"
                                                class="form-control input-sm bulk_product_description " value="">
                                        </td>
                                        <td class="uomdiv column4">
                                            <select name="bulk_uom_code_id[]" id="bulk_uom_code_id"
                                                class="select2 bulk_uom_code_id">
                                                {!! $uom_code_id !!}
                                            </select>
                                        </td>
                                        <td class="column5">
                                            <input type="text" name="bulk_qty[]" class="form-control bulk_qty " value=""
                                                required="required">
                                        </td>
                                        <?php if ($ids == "3") { ?>
                                            <td>
                                                <input type="text" name="bulk_received_qty[]"
                                                    class="form-control input-sm bulk_received_qty " value=""
                                                    required="required">
                                            </td>
                                        <?php } ?>
                                        <td class="column6"><i class="fa fa-rupee productprice"></i></td>
                                        <td class="column7">
                                            <input type="text" name="bulk_unit_price[]"
                                                class="form-control input-sm bulk_unit_price " readonly value=""
                                                required="required">
                                        </td>
                                        <td class="column8">
                                            <input type="text" name="bulk_discount_percentage[]"
                                                class="form-control input-sm bulk_discount_percentage " value="">
                                        </td>
                                        <td class="column9">
                                            <input type="text" name="bulk_discount_amount[]"
                                                class="form-control input-sm bulk_discount_amount " value="">
                                        </td>
                                        <td class="hsn column10">
                                            <select name="bulk_hsn_code[]" id="bulk_hsn_code"
                                                class="select2 bulk_hsn_code" required>{!! $hsn_code !!}</select>
                                        </td>
                                        <td class="column11">
                                            <select name="bulk_tax_group_id[]" id="bulk_tax_group_id"
                                                class="select2 bulk_tax_group_id" required="required">
                                                {!! $tax_group_id !!}
                                            </select>
                                        </td>
                                        <td class="column12">
                                            <input type="text" name="bulk_tax_amount[]"
                                                class="form-control input-sm bulk_tax_amount" value="">
                                        </td>
                                        <td class="column13">
                                            <input type="text" name="bulk_line_sub_total[]"
                                                class="form-control input-sm bulk_line_sub_total" value=""
                                                required="required">
                                        </td>
                                        <td class="column14">
                                            <input type="text" name="bulk_line_total[]"
                                                class="form-control input-sm bulk_line_total" value=""
                                                required="required">
                                        </td>
                                        <td class="column15"><input type="text" name="bulk_qoh_qty[]"
                                                class="form-control  bulk_qoh_qty" value="" readonly></td>
                                        <td class="prodate column16">
                                            <input type="text" name="bulk_promised_date[]"
                                                class="form-control  input-sm bulk_promised_date" required value="">
                                        </td>
                                        <td class="prodate column17">
                                            <input type="text" name="bulk_promised_alternate_date[]"
                                                class="form-control  input-sm bulk_promised_alternate_date"
                                                value="{{ $value->po_alternate_date }}">
                                        </td>
                                        <td class="column18">
                                            <textarea name="bulk_comments[]" class="form-control input-sm bulk_comments"
                                                value=""></textarea>
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
                <!-- END -->

                <!-------------------------Linedata End-------------------------------->
                <div class="row mt-4">
                    <div class="col-lg-12 col-md-12">
                        <input type="hidden" name="submit_type" class="submit_type" value="" />
                        <div class="form-group text-center actionbtn">
                            <?php if ($ids == "2") { ?>
                                <button name="apply" type="button"
                                    class="btn btn-secondary saveform applychangesd px-4 me-2"
                                    value="APPLYCHANGES">Draft</button>
                                <button name="submit" type="button" class="btn btn-success saveform px-4 me-2"
                                    value="SAVE">Save</button>


                            <?php } else if ($ids == "4") { ?>
                                    <button name="apply" type="button"
                                        class="btn btn-secondary saveform applychangesd  px-4 me-2"
                                        value="APPLYCHANGES">Draft</button>
                                    <button name="submit" type="button" class="btn btn-success saveform px-4 me-2"
                                        value="SAVE">Save</button>

                            <?php } else if ($ids == "3") { ?>
                                        <button type="button" class="btn btn-danger saveform px-4 me-2" value="Canceled">Po
                                            Cancel</button>
                            <?php } else if ($ids == "1") { ?>
                                            <button type="button" class="btn btn-success px-4 me-2 saveform" value="Approved"><i
                                                    class="bi bi-check-all"></i> Approve</button>
                                            <button type="button" class="btn btn-danger px-4 me-2 saveform" value="Rejected"><i
                                                    class="bi bi-x"></i> Reject</button>
                            <?php } else { ?>
                                            <button name="apply" type="button"
                                                class="btn btn-secondary saveform applychangesd px-4 me-2"
                                                value="APPLYCHANGES">Draft</button>
                                            <button name="submit" type="button" class="btn btn-success saveform px-4 me-2"
                                                value="SAVE">Submit</button>
                            <?php } ?>
                            <a class='btn btn-outline-danger px-4 me-2'
                                onclick='location.href="{{ url($return_url) }}"'>Cancel</a>
                        </div>
                    </div>
                </div>


            </div>

        </div>
    </div>


    <!-- purpose Product search jqgrid model-->
    <!-- Product Details Modal -->
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

    <!-- Product Price Modal -->
    <div class="modal fade" id="productprice" tabindex="-1" aria-labelledby="productPriceLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="productPriceLabel">
                        <i class="bi bi-currency-rupee me-2"></i> Product Price
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Supplier Name</th>
                                    <th>Date</th>
                                    <th>PO Number</th>
                                    <th>Unit Price</th>
                                    <th>Product</th>
                                </tr>
                            </thead>
                            <tbody class="mcontent5">
                                <!-- Dynamic rows -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>

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

    <input type="hidden" class="pdtindex" value="" />
</form>


<!-- Popups -->
<!-- Supplier Modal (Bootstrap 5) -->
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
                                <th>Action</th>
                                <th>Supplier Number</th>
                                <th>Supplier Name</th>
                                <th>Supplier Type</th>
                                <th>Supplier Site Name</th>
                                <th>Address</th>
                                <th>Country</th>
                                <th>State</th>
                                <th>City</th>
                                <th>City</th>
                                
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

<!-- Bootstrap 5 Column Visibility Modal -->
<div class="modal fade" id="myShowColumn" tabindex="-1" aria-labelledby="myShowColumnLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="myShowColumnLabel">
                    <i class="bi bi-eye me-2"></i> Show or Hide Columns
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">
                    <!-- Column 1 -->
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="column1" name="column1">
                            <label class="form-check-label" for="column1">Line No</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="pdtdiv" name="pdtdiv">
                            <label class="form-check-label" for="pdtdiv">Product</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="column3" name="column3">
                            <label class="form-check-label" for="column3">Product Search</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="column6" name="column6">
                            <label class="form-check-label" for="column6">₹</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="column8" name="column8">
                            <label class="form-check-label" for="column8">Discount (%)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="column10" name="column10">
                            <label class="form-check-label" for="column10">HSN Code</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="column12" name="column12">
                            <label class="form-check-label" for="column12">Tax Amount</label>
                        </div>
                    </div>

                    <!-- Column 2 -->
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="hidepart" name="hidepart">
                            <label class="form-check-label" for="hidepart">Supplier Part No</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="column4" name="column4">
                            <label class="form-check-label" for="column4">UOM Code</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="column5" name="column5">
                            <label class="form-check-label" for="column5">Qty</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="column7" name="column7">
                            <label class="form-check-label" for="column7">Price</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="column9" name="column9">
                            <label class="form-check-label" for="column9">Discount Amount</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="column11" name="column11">
                            <label class="form-check-label" for="column11">Tax Group</label>
                        </div>
                    </div>

                    <!-- Column 3 -->
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="column14" name="column14">
                            <label class="form-check-label" for="column14">Line Total</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="column16" name="column16">
                            <label class="form-check-label" for="column16">Promised Date</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="column18" name="column18">
                            <label class="form-check-label" for="column18">Comments</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="column13" name="column13">
                            <label class="form-check-label" for="column13">Sub Total</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="column15" name="column15">
                            <label class="form-check-label" for="column15">QOH Qty</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="column17" name="column17">
                            <label class="form-check-label" for="column17">Promised Alternative Date</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- End -->

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

    const $tbody   = $('.clone_lines_body');
    const $lastRow = $tbody.find('tr:last');
    const $newRow  = $lastRow.clone(false);

    /* ---------- CLEAR INPUTS ---------- */
    $newRow.find('input').not('.bulk_quotation_line_id').val('');
    const promisedDate = $lastRow.find('.bulk_promised_date').val();

    /* ---------- PRODUCT DROPDOWN ---------- */
    const $productSelect = $newRow.find('.bulk_product_id');

    if ($productSelect.hasClass('select2-hidden-accessible')) {
        $productSelect.select2('destroy');
    }

    // Apply cached products if available
    if (typeof productOptionsHtml !== 'undefined' && productOptionsHtml !== '') {
        $productSelect.html(productOptionsHtml);
    } else {
        // Fallback: fetch from server
        let pid = $('.po_pricelist_id').val() || 0;
        $.get("{{ URL::to('getpriceproduct') }}/" + pid + "/0?condition=purchaseorder", function (data) {
            productOptionsHtml = data;
            $productSelect.html(data);
            $productSelect.val(null).trigger('change');
        });
    }

    // 🔥 Clear any cloned selection
    $productSelect.val(null).trigger('change');

    $newRow.find('.bulk_promised_date').val(promisedDate);

    /* ---------- OTHER SELECT2 ---------- */
    $newRow.find('select.select2').not('.bulk_product_id').each(function () {
        if ($(this).hasClass('select2-hidden-accessible')) {
            $(this).select2('destroy');
        }
        $(this).val('');
    });

    $newRow.find('span.select2').remove();

    /* ---------- FIX DUPLICATE IDs ---------- */
    $newRow.find('[id]').each(function () {
        this.id = this.id + '_' + Date.now();
    });

    /* ---------- APPEND ---------- */
    $tbody.append($newRow);

    /* ---------- REINIT SELECT2 ---------- */
    $newRow.find('select.select2').select2({ width: '100%' });

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
    // select
    $(".select2").change(function () {
        $(this).parsley().validate();
    });


    function example() {
        $("#file_choosen").css({
            "border-color": "rgb(20, 46, 120)",
            "border-width": "1px",
            "border-style": "solid"
        });
    }

    /* Purpose For Readonly Fields*/
    <?php if ($ids == "1" || $ids == "3") { ?>
        $('.bulk_unit_price').css('readonly', true);
        $("#choosefile").attr("disabled", true);
        $('.currency').hide();
        var ids = "{{$ids}}";
        $('input').attr('readonly', true);
        $('select').attr('readonly', true);
        $('select').css('pointer-events', 'none');
        $('#fp').css('pointer-events', 'none');
        if (ids == 1) {
            $('.reverse_charge').attr('disabled', true);
        }
        if (ids == 3) {

            $('.currency').hide();
            $('.bulk_promised_date,.bulk_promised_alternate_date').css('pointer-events', 'none');
        }
        $('.delivery_div,.payment_div,.project_div,.supplier_div,.hsn,.partno,.insurance_div,.uomdiv,.shiftoloc_div,.billtoloc_div,.delivery_date_div,.ssite_div,.pdtdiv,.taxgroup').css('pointer-events', 'none');
        $('.ichide').hide();
        $('.add_row,.remove').hide();
        $('.productsearch').hide();
        $('.remarks,.bulk_comments').attr('readonly', false);
    <?php } else { ?>
        $('#fp').css('pointer-events', '');
    <?php } ?>

    <?php if ($ids == "5") { ?>
        $('.partno').css('pointer-events', 'none');
    <?php } ?>
    /*End Purpose For Readonly Fields*/

    /** Purpose For Labour Condition**/
    <?php if ($ids == "4") { ?>
        <?php if ($row->po_type == "LABOUR") { ?>
            $('.productprice').css('display', 'none');
        <?php } ?>
        $('.po_status').val('');
    <?php } ?>

    <?php if ($row->po_type == "LABOUR") { ?>
        <?php if ($ids != 3) { ?>
            $('.bulk_unit_price').attr("readonly", false);
        <?php } ?>
        $(".po_pricelist_id").removeAttr('required');
        $('.hidepart,.reqstar').hide();
    <?php } else { ?>
        $('.taxgroup,.uomdiv').css('pointer-events', 'none');
    <?php } ?>
    /*End Purpose For Labour Condition*/


    $(document).ready(function () {
        var decimal = "<?php echo \Session('decimal'); ?>";
        <?php
        if ($return_url == "purchasequtoetopo") { ?>
            $('.pricelist_div').css('pointer-events', 'none');
        <?php }

        if ($return_url == "poapproval") { ?>
            $('.pricelist_div,.ssite_div,.shiftoloc_div,.pdtdiv,.taxgroup,.prodate,.billtoloc_div,.delivery_date_div').css('pointer-events', 'none');

        <?php }

        ?>
        $('.pricelist_div').css('pointer-events', 'none');
        $('.viewquote').click(function () {
            var quoteid = $('.reference_id').val();
            if (quoteid != "") {
                var url = "{{ URL::to('purchasequotationview')}}/" + quoteid;
                window.open(url);
            }
        });


        /**********Up/down/left/right arrow navigation start*******/
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


        /* Purpose For Default Organization & User*/
        var organization = '<?php echo Session::get('organization'); ?>';
        $('.organization_id').val(organization).change();
        var user = '<?php echo Session::get('id'); ?>';
        $('.created_by').val(user).change();
        $(".create_by").html($('.created_by option:selected').text());
        $('.org').html($('.organization_id option:selected').text());

        $('.bulk_tax_amount,.bulk_line_total,.bulk_line_sub_total').attr('readonly', true);

        $(document).on('keypress', '.bulk_qty,.extracharge2,.extracharge1,.extracharge4,.extracharge6,.extracharge3,.extracharge5,.bulk_unit_price,.bulk_discount_percentage,.transport_charges,.unloading_charges,.insurance_charges,.packing_charges,.other_freight_amount', function (ev) {
            var regex = new RegExp("^[0-9.]+$");
            var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
            if (regex.test(str)) {
                return true;
            }
            ev.preventDefault();
            return false;
        });


        $('.bulk_qty,.bulk_discount_percentage').bind("cut copy paste", function (e) {
            e.preventDefault();
        });


        $('.bulk_discount_percentage').keyup(function () {
            if ($(this).val() > 100) {
                showCustomAlert("Should not exist more than 100",'info');
                $(this).val('');

            }
        });


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

        $(document).on('change', '.bulk_promised_alternate_date0', function () {
            const promised = $(this).val();
            if (!promised) return;

            const hidden = $('.bulk_hidden_date').val();
            $('.bulk_promised_alternate_date').each(function () {
                const $inp = $(this);
                if ($inp.val() === '' || $inp.val() === hidden) {
                    $inp.val(promised);
                }
            });
            $('.bulk_hidden_date').val(promised);
        });




        $('.source,.po_type,.organization_id,.created_by,.po_status').attr('readonly', 'readonly').css('pointer-events', 'none');

       <?php if ($row->po_type != "LABOUR") { ?>

let productOptionsHtml = '';   // cache for product options

function loadProductsByPricelist() {
    let po_pricelist_id = $('.po_pricelist_id').val();
    if (!po_pricelist_id) return;

    $.get("{{ URL::to('getpriceproduct') }}/" + po_pricelist_id + "/0?condition=purchaseorder", function (data) {
        productOptionsHtml = data;

        $('.bulk_product_id').each(function () {
            const $sel = $(this);
            const currentVal = $sel.val(); // keep old selection for existing rows

            $sel.html(data);

            if (currentVal) {
                $sel.val(currentVal).trigger('change');
            }
        });
    });

    pricelistchange();
}

// When user changes pricelist
$(document).on('change', '.po_pricelist_id', function () {
    loadProductsByPricelist();
});

// When page loads (EDIT mode)
$(window).on('load', function () {
    if ($('.po_pricelist_id').val()) {
        loadProductsByPricelist();
    }
});

<?php } ?>

        
        /* Purpose For Supplier Based Price load*/
        $(document).on('change', '.supplier_id', function () {
            var supplier_id = $('.supplier_id option:selected').val();
            if (supplier_id != '') {
                $.get("{{ URL::to('supplierpricelist') }}/" + supplier_id, function (suppdata) {
                    var data = $.trim(suppdata);
                    if (data != 0) {
                        var condition = "supplier_id=" + supplier_id;
                        var url = "{{ URL::to('jcomboform?table=m_supplier_sites_t:supplier_site_id:supplier_site_number|supplier_site_name') }}" +
                            "&order_by=supplier_site_name asc" +
                            "&parent=" + encodeURIComponent(condition);

                        $.ajax({
                            url: url,
                            type: "GET",
                            success: function (data) {
                                var $dropdown = $(".suppliersite_id");
                                $dropdown.empty().append('<option value="">-- Select --</option>');

                                // Parse JSON if needed
                                if (typeof data === "string") {
                                    try {
                                        data = JSON.parse(data);
                                    } catch (e) {
                                        console.error("Invalid JSON:", data);
                                        return;
                                    }
                                }

                                // Populate dropdown
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


                        setTimeout(function () {
                            $(".po_pricelist_id").val(suppdata['price_list']).change();
                        }, 500);
                        $('.payment_term_id').val(suppdata['default_payment_terms_id']).change();
                        $('.delivery_terms_id').val(suppdata['delivery_terms_id']).change();
                        $('.default_payment_method_id').val(suppdata['default_payment_method_id']).change();
                        $('.insurance_term_id').val(suppdata['insurance_term_id']).change();
                        $('.freight_terms_id').val(suppdata['frieghtterm_id']).change();
                        $('.freight_carrier_id').val(suppdata['frieghtcarriers_id']).change();
                        pricelistchange();
                    }
                    else {

                        $(".po_pricelist_id").val('').change();
                    }
                });
            }


        });
        /*End*/


        // ---- config / helpers ----
        const DECIMAL_PLACES = Number('{{ session("decimal", 2) }}'); // set once
        const num = v => {
            const n = parseFloat((v ?? '').toString().replace(/,/g, ''));
            return isNaN(n) ? 0 : n;
        };
        const showCustomAlerts = (msg, type) => { if (typeof showCustomAlert === 'function') showCustomAlert(msg, type); };


        /* purpose for load product based details */

        $(document).on('change', '.bulk_product_id', function (event) {
            const $row = $(this).closest('tr');
            const product_id = $(this).val(); // works with Select2 v4
            const type = $('.po_type').val();
            const plid = $('.po_pricelist_id').val();
            const supplierid = $('.supplier_id').val();
            const suppsiteid = $('.suppliersite_id').val();

            $row.find('.hsn').css("pointer-events", "auto");

            if (!product_id) return;

            if (!supplierid) {
                showCustomAlert('Please Select Supplier !!!','info');
                $(this).val(null).trigger('change');
                event.preventDefault();
                return;
            }

            if (!plid) {
                showCustomAlert('Please Select Pricelist !!!','info');
                rowdataEmpty($row);
                $(this).val(null).trigger('change');
                event.preventDefault();
                return;
            }

            if (product_id === '' || product_id === '-- Please Select --') {
                rowdataEmpty($row);
                calc_by_row($row);
                return;
            }

            // duplicate check
            const rowIndex = $row.index();
            if (typeof pdtcheck === 'function' && pdtcheck(product_id, rowIndex) > 0) {
                showCustomAlert('Product Already Selected','info');
                rowdataEmpty($row);
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
                // UOM
                $row.find('.bulk_uom_code_id').val(data.uom_code_id ?? null).trigger('change');
                $row.find('.bulk_tax_group_id').val(data.tax_group_id ?? null).trigger('change');

                // Part No
                $row.find('.bulk_part_no').val(data.part_no ?? null).trigger('change');

                // HSN/SAC via jCombo
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

                // Unit price
                if (!data.unit_price || data.unit_price === "0") {
                    $row.find('.bulk_unit_price').val('');
                  showCustomAlert('Pricelist Not Assigned for this Product (or) Date has Expired','info');
                } else {
                    $row.find('.bulk_unit_price').val(
                        parseFloat(data.unit_price).toFixed({{ session('decimal',2) }})
      );
    }

// QOH
$row.find('.bulk_qoh_qty').val(data.qoh_qty > 0 ? data.qoh_qty : 0);

    // Recalculate
    if (typeof calc_by_row === 'function') {
        calc_by_row($row);
    } else if (typeof calc_by_index === 'function') {
        calc_by_index(rowIndex);
    }
  }).fail(() => {
        showCustomAlert('Failed to fetch product details', 'error');
    });
});



    /*deepika purpose: to load tax based on hsn code*/
    <?php if ($return_url != "purchaseorder") { ?>
        $(document).on('change', '.bulk_hsn_code', function () {

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
                    if (reason === 'expiry') showCustomAlerts('Tax Group expired for this product','info');
                    else if (reason === 'location') showCustomAlerts('Tax not assigned for this Location','info');
                    else showCustomAlerts('Tax Group not assigned for this product','info');
                } else {
                    $taxSel.val(data.tax_group_id).trigger('change');  // programmatic change
                }
            }).fail((xhr, s, e) => console.error('Tax fetch failed:', e));
        });
    <?php } ?>

    /* purpose for load product based All Details */
    function pricelistchange() {
        const plid = $('.po_pricelist_id').val();
        const supplierid = $('.supplier_id').val();
        const type = $('.po_type').val();
        const suppsiteid = $('.supplier_site_id').val();

        if (type !== "STANDARD" || !plid) return;

        $('.bulk_product_id').each(function (index) {
            const $row = $(this).closest('tr');
            const product_id = $(this).val();
            if (!product_id) {
                // clear fields if product empty
                $row.find('.bulk_hsn_code, .bulk_tax_group_id, .bulk_uom_code_id').val(null).trigger('change');
                $row.find('.bulk_unit_price, .bulk_line_sub_total, .bulk_line_total, .bulk_tax_amount').val('');
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
                // set product back (redundant but safe)
                $row.find('.bulk_product_id').val(data.product_id ?? null).trigger('change');

                // UOM
                $row.find('.bulk_uom_code_id').val(data.uom_code_id ?? null).trigger('change');

                // Price
                if (!data.unit_price || data.unit_price === "0") {
                    showCustomAlert('Pricelist Not Assigned For this Product !!!', 'warning');
                    $row.find('.bulk_unit_price, .bulk_line_sub_total, .bulk_line_total, .bulk_tax_amount').val('');
                    $row.find('.bulk_hsn_code, .bulk_tax_group_id, .bulk_uom_code_id').val(null).trigger('change');
                } else {
                    $row.find('.bulk_unit_price').val(data.unit_price);
                }

                // QOH
                $row.find('.bulk_qoh_qty').val(data.qoh_qty > 0 ? data.qoh_qty : 0);

                // Tax
                if (!data.tax_group_id || data.tax_group_id == 0) {
                    if (data.tax_group_id_expiry === "expiry") {
                        showCustomAlert('Tax Group expired for this product','info');
                    } else if (data.tax_group_id_expiry === "location") {
                        showCustomAlert('Tax not assigned for this Location','info');
                    } else {
                        showCustomAlert('Tax Group not assigned for this product','info');
                    }
                }
                $row.find('.bulk_tax_group_id').val(data.tax_group_id ?? null).trigger('change');

                // HSN
                if (data.hsn_code) {
                    $row.find('.bulk_hsn_code').val(data.hsn_code).trigger('change');
                }

                // finally recalc row
                if (typeof calc_by_row === 'function') {
                    calc_by_row($row);
                } else if (typeof calc_by_index === 'function') {
                    calc_by_index(index);
                }
            });
        });
    }



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
    /*End*/

    /**** To Empty the Rowdata when product Empty ********/
    function rowdataEmpty($rowOrIndex) {


        const $row = ($rowOrIndex instanceof jQuery) ? $rowOrIndex : $('.clone_lines_body tr').eq($rowOrIndex);

        $row.find('.bulk_uom_code_id').val(null).trigger('change');
        $row.find('.bulk_unit_price').val('');
        $row.find('.bulk_discount_percentage').val('');
        $row.find('.bulk_discount_amount').val('');
        $row.find('.bulk_hsn_code').val(null).trigger('change');
        $row.find('.bulk_tax_group_id').val(null).trigger('change');
        $row.find('.bulk_tax_amount').val('');
        $row.find('.bulk_line_total').val('');
        $row.find('.bulk_line_sub_total').val('');
        $row.find('.bulk_part_no').val('');
        $row.find('.bulk_promised_date').val('');
        $row.find('.bulk_promised_alternate_date').val('');
        $row.find('.bulk_comments').val('');
        $row.find('.bulk_manufacturer_partno_id').val(null).trigger('change');
        $row.find('.bulk_qty').trigger('change');
    }



// ================================
// TAX MODAL OPEN
// ================================
$(document).on('click', '.packingtax', function () {
  var type    = $(this).attr('data-value');
  var type_id = $(this).attr('data-at'); // "1","2","3","4"

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

  // ✅ Correct mapping (based on your HTML: 1 packing, 2 transport, 3 unloading, 4 insurance)
  let val_char = '';
  if (type_id == "1") val_char = $('.packing_charges_tax').val() || '';
  if (type_id == "2") val_char = $('.transport_charges_tax').val() || '';
  if (type_id == "3") val_char = $('.unloading_charges_tax').val() || '';
  if (type_id == "4") val_char = $('.insurance_charges_tax').val() || '';

  var text_data = "{!! $tax_group_id_pop!!}";
  var data = '';

  <?php if ($return_url == 'purchaserequisitionapprove' || $return_url == 'purchasequtoetionapprove') { ?>
    data += `
      <div class="row g-3">
        <div class="col-md-3">
          <label class="form-label fw-bold">${type}</label>
          <input type="text" class="form-control extracharge${type_id}" value="" readonly>
        </div>

        <div class="col-md-3">
          <label class="form-label fw-bold">Tax Group</label>
          <select class="form-select select2 tax_details${type_id} tax_detailsse" disabled>
            ${text_data}
          </select>
        </div>

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
        <div class="col-md-3">
          <label class="form-label fw-bold">${type}</label>
          <input type="text" class="form-control extracharge${type_id}" value="">
        </div>

        <div class="col-md-3">
          <label class="form-label fw-bold">Tax Group</label>
          <select class="form-select select2 tax_details${type_id} tax_detailsse">
            ${text_data}
          </select>
        </div>

        <div class="col-12 text-right mt-3">
          <button type="button" class="btn btn-success px-4 taxchargesave" value="${type_id}">
            <i class="bi bi-save"></i> Ok
          </button>
        </div>
      </div>
    `;
  <?php } ?>

  $('.taxdetail').html(data);

  // ✅ Safe prefill (no split crash)
  if (typeof val_char === 'string' && val_char.includes(',')) {
    var dat = val_char.split(",");
    $('.extracharge' + type_id).val(dat[1] || '');
    $('.tax_details' + type_id).val(dat[0] || '').change();
  } else {
    $('.extracharge' + type_id).val('');
    $('.tax_details' + type_id).val('').change();
  }
});


// ================================
// TAX SAVE
// ================================
$(document).on('click', '.taxchargesave', function () {

  var type = $(this).val(); // "1","2","3","4"
  var taxgrp = $('.tax_details' + type + ' option:selected').attr('data-display');
  var taxgrp_v = $('.tax_details' + type + ' option:selected').val();

  let charge = num($('.extracharge' + type).val());

  taxgrp = taxgrp ? num(taxgrp) : 0;

  // Validations
  if (taxgrp_v == 0 || taxgrp_v === "0" || !taxgrp_v) {
    showCustomAlert("Please select Tax Group", 'warning');
    return;
  }
  if (!charge) {
    showCustomAlert("Please fill Amount", 'warning');
    $('#taxModal').modal('show');
    return;
  }

  // Tax amount and total charge-with-tax
  let amount = (charge * taxgrp / 100);
  let a_c = (amount + charge);

  amount = amount.toFixed(DECIMAL_PLACES);
  a_c = a_c.toFixed(DECIMAL_PLACES);

  var tax_group_value = taxgrp_v + "," + charge; // store raw charge

  // ✅ Update field + trigger change so totals update
  if (type == "1") {
    $('.packing_charges').val(a_c).trigger('change');
    $('.packing_charges_tax').val(tax_group_value);
  }
  if (type == "2") {
    $('.transport_charges').val(a_c).trigger('change');
    $('.transport_charges_tax').val(tax_group_value);
  }
  if (type == "3") {
    $('.unloading_charges').val(a_c).trigger('change');
    $('.unloading_charges_tax').val(tax_group_value);
  }
  if (type == "4") {
    $('.insurance_charges').val(a_c).trigger('change');
    $('.insurance_charges_tax').val(tax_group_value);
  }

  // Force totals update (extra safety)
  recalc_totals();

  $('#taxModal').modal('hide');
});


// ================================
// Unified calc triggers (row-scoped)
// ================================
$(document).on(
  'keyup change',
  '.bulk_qty,.bulk_unit_price,.bulk_tax_group_id,.bulk_discount_percentage,.bulk_product_id,.bulk_discount_amount',
  function () {
    const $row = $(this).closest('tr');
    calc_by_row($row);
  }
);

// Charges (often readonly, so trigger change from code)
$(document).on(
  'keyup change',
  '.packing_charges,.transport_charges,.unloading_charges,.insurance_charges,.other_tax_amount,.other_frieght_amount',
  function () {
    recalc_totals();
  }
);


// ================================
// Row calculator
// ================================
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
  $row.find('.bulk_line_sub_total').val(subTot.toFixed(DECIMAL_PLACES));

  recalc_totals();
}


// ================================
// Header totals (charges + line totals)
// ================================
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

  // Reverse charge handling
  if ($('input.reverse_charge').is(':checked')) {
    grand -= sumtax;
  }

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

function total_amount() { recalc_totals(); }

    /** end **/



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

        const subot = ((requiredqty * unitprice) - discountamt);
        $row.find('.bulk_line_sub_total').val(subot.toFixed(DECIMAL_PLACES));

        recalc_totals();
    });




    $('.suppliersearch').click(function () {
        $('#supplierModal').modal('show');
        $('#supplierModal').width("100%");
    });

    /*Karthigaa Purpose for Supplier Search*/
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
                },
                { data: 'supplier_number', name: 'supplier_number' },
                { data: 'supplier_name', name: 'supplier_name' },
                { data: 'suppliertype_name', name: 'suppliertype_name' },
                { data: 'supplier_site_name', name: 'supplier_site_name' },
                { data: 'address', name: 'address' },
                { data: 'country_name', name: 'country_name' },
                { data: 'state_name', name: 'state_name' },
                { data: 'city_name', name: 'city_name' },
                { data: 'location_id', name: 'location_id', visible: false },

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

    /*Karthigaa Purpose For Product Search*/
    $(document).on('click', '.productsearch', function () {
        var supplier_id = $('.supplier_id').val();

        if (!supplier_id) {
            showCustomAlert('Please select a Supplier','info');
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
                    productOptionsHtml = data;
                    if ($.trim(data) === '<option value="">-- Please Select --</option>') {
                        showCustomAlert("No Product for these Pricelist",'info');
                    }
                    $('.bulk_product_id').html(data);
                });
            } else {
                showCustomAlert("Please select a Pricelist",'info');
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
    /*Karthigaa Purpose For File Attachments*/
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
        //document.getElementById('divTotalSize').innerHTML = "Total File(s) Size is <b>" + Math.round(totalFileSize / 1024) + "</b> KB";
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

                    showCustomAlert('File size must not be more than 10MB', 'warning');

                    $('#choosefile').val('');

                }

            }

        });
        /*file upload validation*/
    });

    $(document).on('click', '.delete_user', function () {

    var po_hdr = '{{$row->po_hdr_id}}';

    if (po_hdr != '') {

        var existing_value = $('#existing_file').val() || '';
        var delete_value = $(this).data('value');

        if (existing_value !== '') {
            removeValue(existing_value, delete_value);
        }
    }

    // remove list item
    $(this).closest('li').remove();
});

function removeValue(existing_value, delete_value) {

    if (!existing_value) {
        $('#existing_file').val('');
        return;
    }

    var list = existing_value.split(',');

    var index = list.indexOf(delete_value);
    if (index !== -1) {
        list.splice(index, 1);
    }

    $('#existing_file').val(list.join(','));
}



    /*End File Attachments*/
    /*Karthigaa Purpose For To See Previous Product Po History Popup*/
    $(document).on('click', '.productprice', function () {
        var index = $(this).closest('tr').index();
        var product_id = $('.bulk_product_id' + index).val();
        if (product_id != '') {
            $.get("{{URL::to('productprice')}}/" + product_id, function (data) {
                data = jQuery.parseJSON(data);
                if (data != 0) {
                    $('#productprice').modal('show');
                    $('.modal-dialog').width('80%');
                    $('.mcontent5').html('');
                    $.each(data, function (key) {
                        $('.mcontent5').append('<tr>\n\
        <td width="220px"><input type="text" name="supplier" class="form-control ' + key + '" value="' + data[key].supplier_name + '" readonly/></td>\n\
        <td width="150px"><input type="text" name="po_date" class="form-control ' + key + '" value="' + data[key].po_date + '" readonly/></td>\n\
        <td width="150px"><input type="text" name="po_number" class="form-control ' + key + '" value="' + data[key].po_number + '" readonly/></td>\n\
        <td width="120px"><input type="text" name="unit_price" class="form-control ' + key + '" value="' + data[key].unit_price + '" readonly/></td>\n\
        <td width="360px"><input type="text" name="product" class="form-control ' + key + '" value="' + data[key].concatenated_product + '" readonly/></td></tr>');
                    });
                    $("#productprice").modal({ backdrop: "static" });
                }
                else {
                    showCustomAlert('There is no previous PO in this Product', 'error');
                }
            });

        }
        else {
            showCustomAlert("Please Select Product", 'error');
        }
    });
    /*End*/
    /*karthigaa purpose for hide product in labour condition*/
    <?php if ($row->po_type == "STANDARD") { ?>
        $('.pdtdes_div').hide();
    <?php } else { ?>
        $('.bulk_product_id').removeAttr('required');
        $('.bulk_product_description').attr('required', true);
    <?php } ?>
    /*End*/
    function gst_qty() {
        $(".bulk_qty").each(function () {
            $(this).trigger('change');
        });
    }
    $('.seq').hide();
    /*Karthigaa Purpose For Submit Function*/
    $(document).on('click', '.saveform', function () {
        $('#panel_add').trigger('click'); //for expanding according
        gst_qty();
        var potype = "{{ $row->po_type }}";

        var btnval = $(this).val();
        if (btnval == 'APPLYCHANGES') {
            $('.remarks').attr('required', false);
            $("#po_status").val('DRAFT');
        }
        else if (btnval == 'Approved') {
            $('.remarks').attr('required', false);
            $("#po_status").val('APPROVED');
        }
        else if (btnval == 'Rejected') {
            $('.seq').show();
            $('.remarks').attr('required', true);
            $("#po_status").val('REJECTED');
        }
        else if (btnval == 'Canceled') {
            $('.remarks').attr('required', false);
            $("#po_status").val('CANCELLED');
        }
        else {
            $("#po_status").val('INITIATED');
        }
        <?php if ($ids == "3") { ?>
            var totalqty = 0;
            var recqty = 0;
            $('.bulk_qty').each(function (data) {
                totalqty += parseFloat($(this).val());
                recqty += parseFloat($('.bulk_received_qty' + index).val());
            });
            if (recqty < totalqty) {
                $("#po_status").val('CLOSED');
            }
            if (recqty == totalqty) {
                $("#po_status").val('COMPLETED');
            }
            if (recqty == 0) {
                $("#po_status").val('CANCELLED');
            }
        <?php } ?>
        $('#savestatus').val(btnval);

        var url = "{{ URL::to('purchaseordersave') }}";
        var red_url = "{{ URL::to($return_url) }}";
        if (potype == 'LABOUR') {
            var create_url = "{{ url('purchaseordercreate') }}/0/LABOUR/2";
        } else if (potype = 'STANDARD') {
            var create_url = "{{ url('purchaseordercreate') }}/0/STANDARD/2";
        }

        var form = $('#po_form');
        if (btnval != 'APPLYCHANGES') {
            form.parsley().validate();
            var form = $('#po_form');
            qtyrequired();
            form.parsley().validate();

            if (form.parsley().isValid()) {
                var formdata = $('#po_form').serialize();
                var form_data = new FormData(document.getElementById('po_form'));
                var $btn = $(this);            
			    $btn.prop('disabled', true);
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
                    if (btnval != 'SAVE' && btnval != 'Approved' && btnval != 'Rejected' && btnval != 'Canceled') {
                        showCustomAlert(msg,status);
                        window.location.href = create_url;
                    }else {
                        showCustomAlert(msg,status);
                        window.location.href = red_url;

                    }
                });
            }
        }
        else {

            var formdata = $('#po_form').serialize();

            var form_data = new FormData(document.getElementById('po_form'));
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
                var edit_url = "{{ URL::to('purchaseordercreate') }}/" + id;
                showCustomAlert(msg,status);
                window.location.href = edit_url;

            });
        }
    });
    /*Karthigaa Purpose For Price not Assign POPup From Enquiry Products*/
    <?php if (isset($pocount) && $pocount != 0) { ?>
        setTimeout(function () {
            swal({
                title: "Price not assigned for {{$pocount}} Products",
                text: "You want to add price for this product",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnCancel: !1
            }, function (e) {
                if (e == true) {
                    var id = $(".po_pricelist_id").val();
                    var url = "{{ URL::to('purchasepricelistedit') }}/" + id;
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
        /*End*/
    });

    /*Purpose For Qty 0 Submit Validation*/
    function qtyrequired() {
        $(".bulk_qty").each(function (index) {
            var req = $(this).val();
            if (req == 0) {
                $(".bulk_qty").val('');
            }
        });
    }


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

    $(document).on("focus", ".bulk_promised_alternate_date", function () {

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

    $(document).on("focus", ".delivery_date", function () {

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

    function preventBack() {
        window.history.forward();
    }
    window.onunload = function () {
        null;
    };
    setTimeout("preventBack()", 0);



    $(function () {
        $('input[type="checkbox"]').click(function () {
            if ($(this).prop("checked") == true) {
                var column = "table ." + $(this).attr("name");
                var columnWidth = "table td." + $(this).attr("name");

                var tableWidth = $('.stickytable').width() - $(columnWidth).width() - 24;
                $(".stickytable").css("width", tableWidth);
                $(column).css("display", "none");



            }
            else if ($(this).prop("checked") == false) {
                var column = "table ." + $(this).attr("name");
                var columnWidth = "table td." + $(this).attr("name");
                $(column).css("display", "");

            }
        });

    });

</script>

@endpush