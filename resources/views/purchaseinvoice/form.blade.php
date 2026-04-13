@extends('layouts.header')
@section('content')
<?php error_reporting(0); ?>
<h3 class="text-danger">Purchase Invoice</h3>
@include('layouts.breadcrumb')



<div class="container-xxl py-3">
    {{-- HEADER SUMMARY --}}
<div class="card shadow-lg rounded-4 border-0">
        <!-- Sticky, polished PO/Invoice header -->
        <div class="card-header bg-info bg-gradient text-white">
            <div class="row gy-2">

                <!-- PO No & Date -->
                <div class="col-md-3">
                    <strong>PO No:</strong>
                    <span class="badge bg-warning text-dark">{{ $row[0]->ponumber ?? '' }}</span><br>
                    <strong>PO Date:</strong>
                    <span class="badge bg-warning text-dark">{{ $row[0]->po_date ?? '' }}</span><br>
                    <strong>Invoice Status:</strong>
                    <span class="badge bg-warning text-dark">{{ $row[0]->po_invoice_status ?? '' }}</span>
                </div>

                <!-- Supplier / Subcontract -->
                @if($invoice_type!="LABOUR")
                @if(($row[0]->supplier_type ?? '') === "SUPPLIER" || $invoice_type=="LABOUR FROM PO")
                <div class="col-md-3">
                    <strong>Supplier:</strong>
                    <span class="badge bg-success">{{ $suppdata[0]->supplier_name ?? '' }}</span><br>
                    <strong>GSTIN:</strong>
                    <span class="badge bg-success">{{ $suppsitedatagst[0]->gst_number ?? '-' }}</span>
                    <input type="hidden" class="supplierid" name="supplierid" value="{!! $supplier_id !!}">
                </div>
                @else
                <div class="col-md-3">
                    <strong>Subcontract:</strong>
                    <span class="badge bg-success">{{ $suppcondata[0]->subcontract_name ?? '' }}</span><br>
                    <strong>Site:</strong>
                    <span class="badge bg-secondary">{{ $suppconsitedata[0]->subcontract_site_name ?? '' }}</span>
                </div>
                @endif
                @endif

                <!-- Tax Total & Grand Total -->
                <div class="col-md-3">
                    <strong>Invoice Tax Total:</strong>
                    <span class="badge bg-primary tax_total_span">{{ $row->invoice_tax_total ?? '0.00' }}</span><br>
                    <strong>Grand Total:</strong>
                    <span class="badge bg-primary grand_total_span">{{( $row->invoice_grand_total ?? '0.00') }}</span>
                </div>

                <!-- GRN Number & Taxable Amount -->
                <div class="col-md-3">
                    <strong>GRN Number:</strong>
                    <span class="badge bg-secondary grn_number">{{ $row[0]->grn_number ?? '' }}</span><br>
                    <strong>Taxable Amount:</strong>
                    <?php if ($invoice_type == "STANDARD") { ?>
                        <span class="badge bg-secondary taxable_amount">
                            {{ ($row->invoice_grand_total ?? 0) - ($row->invoice_tax_total ?? 0) }}</span>
                        </span>

                    <?php } else { ?>

                        <span class="badge bg-secondary taxable_amount">
                            {{ ($row->invoice_grand_total ?? 0) - ($row->invoice_tax_total ?? 0) + ($row->tds_amount ?? 0) }}</span>
                        </span>

                    <?php } ?>
                    
                </div>

            </div>
        </div>




        {{-- FORM BODY --}}
        <div class="card-body">
            <form method="post" action="" id="invoiceform" class="invoiceform" data-parsley-validate>
                {{ csrf_field() }}

                {{-- Hidden essentials --}}
                <input type="hidden" name="supplier_id" class="supplier_id" value="{!! $supplier_id !!}">
                <input type="hidden" class="suppliersite_id" name="suppliersite_id" value="{!! $suppliersite_id_id !!}">
                <input type="hidden" class="po_invoice_id" id="po_invoice_id" name="po_invoice_id"
                    value="{{ $po_invoice_id }}">
                <input type="hidden" class="po_number" name="po_number[]" value="{{ $row[0]->po_number ?? '' }}"
                    multiple>
                <input type="hidden" name="qc_id" value="{{ $row[0]->qc_header_id ?? '' }}">
                <input type="hidden" id="invoice_number" name="invoice_number"
                    value="{{ $row[0]->invoice_number ?? '' }}">

                {{-- PRIMARY DETAILS --}}
                <div class="row g-4">
                    <div class="col-12 col-xl-4">
                        <div class="mb-3">
                            <label for="bill_number" class="form-label required">Supplier Invoice Number</label>
                            <input type="text" id="bill_number" name="bill_number" class="form-control bill_number"
                                value="{{ $row[0]->bill_number ?? '' }}" required tabindex="1">
                        </div>

                        <div class="mb-3">
                            <label for="invoice_type" class="form-label">Invoice Type</label>
                            <select id="invoice_type" name="invoice_type" class="form-select select2 invoice_type"
                                readonly>
                                <option value="">--select--</option>
                                <option value="STANDARD" @if($invoice_type=="STANDARD" ) selected @endif>STANDARD
                                </option>
                                <option value="LABOUR" @if($invoice_type=="LABOUR" ) selected @endif>LABOUR</option>
                                <option value="LABOUR FROM PO" @if($invoice_type=="LABOUR FROM PO" ) selected @endif>
                                    LABOUR FROM PO</option>
                            </select>
                        </div>

                        <div class="form-group row" style="display:none">
            <label for="inputIsValid" class="form-control-label col-md-4">PO Number</label>
            <div class="col-md-6">
                <input type="hidden" name="payment_status" value="0" />
                <input type="hidden" name="payment_request_status" value="0" />
                <input class="form-control po_number" id="po_number" name="po_number[]" size="16" type="hidden" value="{{ $row[0]->po_number }}" readonly multiple>
                <input class="form-control po_invoice_id" id="po_invoice_id" name="po_invoice_id" size="16" type="hidden" value="{{ $po_invoice_id }}" readonly>
                
            </div>
            <div class="col-md-2">
            </div>
        </div>

    <!-- -->
        <div class="form-group row" style="display:none">
            <label for="inputIsValid" class="form-control-label col-md-4">QC Number</label>
            <div class="col-md-6">
                <input class="form-control " name="qc_id" size="16" type="hidden" value="{{ $row[0]->qc_header_id }}" readonly>
                <input type="text" id="qc_id" class="form-control qc_id" value="{{ $row[0]->qc_number }}" readonly>
            </div>
            <div class="col-md-2">
            </div>
        </div>
        <div class="form-group row" style="display:none">
            <label for="inputIsValid" class="form-control-label col-md-4"> PO Date</label>
            <div class="col-md-6">
                <div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                    <input class="form-control po_date" id="po_date" name="po_date" size="16" type="text" value="{{ $row[0]->po_date }}" readonly>
                    
                </div>
                <input type="hidden" id="po_date" value="{{ $row[0]->po_date }}" />
            </div>
            <div class="col-md-1 showinline">
            </div>
        </div>
                <div class="form-group row " style="display: none;">
            <label for="inputIsValid" class="form-control-label col-md-4">Supplier Site </label>
            <div class="col-md-6 suppliersite_id_div">
                <input type="hidden" value="{{$suppliersite_id}}" name='suppliersite_id' rows='5' class='form-control suppliersite_id' data-show-subtext="true" data-live-search="true">
                <select id="suppliersite_id" name='suppliersite_id'  class='select2 suppliersite_id select2' tabindex="1" data-show-subtext="true" data-live-search="true" >
                    {!! $suppliersite_id !!}
                </select>      
            </div>
        </div>
		
	<div class="form-group row" style="display:none">
            <label for="inputIsValid" class="form-control-label col-md-4">Subcontract Name</label>
            <div class="col-md-6">
                <?php //dd($row);  ?>
                    <input type="hidden" value="{{$subcontract_supplier_id}}" name='subcontract_supplier_id' rows='5' class='form-control subcontract_supplier_id' data-show-subtext="true" data-live-search="true">

            </div>
            <div class="col-md-1 showinline">
            </div>
        </div>
        <div class="form-group row" style="display:none;">
            <label for="inputIsValid" class="form-control-label col-md-4">subcontract Site </label>
            <div class="col-md-6 suppliersite_id_div">
				<input type="hidden" value="{{$subcontract_site_id}}" name='subcontract_site_id' rows='5' class='form-control subcontract_site_id' data-show-subtext="true" data-live-search="true">
            </div>
            
        </div>
        <!-- for save -->
                        @if($invoice_type=="LABOUR")
                        <div class="mb-3">
                            <label for="supplier_id" class="form-label">Supplier Name</label>
                            <select id="supplier_id" name="supplier_id" class="form-select select2 supplier_id"
                                tabindex="1" data-live-search="true">
                                {!! $supplierid !!}
                            </select>
                        </div>
                        @endif
                    </div>

                    <div class="col-12 col-xl-4">
                        <div class="mb-3">
                            <label for="invoice_date" class="form-label required">Supplier Invoice Date</label>
                            <input type="text" id="invoice_date" name="invoice_date" class="form-control previousdates start_date"
                                value="{{ $row->invoice_date ?? '' }}" required tabindex="2" placeholder="YYYY-MM-DD">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Reverse Charge Applicable?</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input reverse_charge" type="checkbox" name="reverse_charge[]"
                                    value="1" @if(($row->reverse_charge ?? '')=="1") checked @endif>
                                <label class="form-check-label">Enable Reverse Charge</label>
                            </div>
                        </div>

				<!-- purpose for save data -->		
				  <div class="form-group row" style="display:none">
            <label for="inputIsValid" class="form-control-label col-md-4">Invoice Status</label>
            <div class="col-md-6">
                <select type="text" name="po_invoice_status" id="po_invoice_status" class="form-control po_invoice_status" readonly>
                    <option value="">--Please Select--</option>
                    <option <?php if($row[0]->po_invoice_status =="DRAFT") { echo "selected"; } else { echo ""; } ?> value="DRAFT">DRAFT</option>
                    <option <?php if($row[0]->po_invoice_status=="INITIATED") { echo "selected"; } else { echo ""; } ?> value="INITIATED">INITIATED</option>
                    <option <?php if($row[0]->po_invoice_status=="APPROVED") { echo "selected"; } else { echo ""; } ?> value="APPROVED">APPROVED</option>
                    <option <?php if($row[0]->po_invoice_status=="REJECTED") { echo "selected"; } else { echo ""; } ?> value="REJECTED">REJECTED</option>
                </select>
            </div>
        </div>

        <div class="form-group row" style="display:none">
            <label for="inputIsValid" class="form-control-label col-md-4">GRN Number</label>
            <div class="col-md-6">
                <input class="form-control " id="grn_number" name="grn_number" size="16" type="hidden" value="{{ $row[0]->grn_id }}" readonly>
                <input type="text" id="grn_number" class="form-control grn_number" value="{{ $row[0]->grn_number }}"  readonly>
                <input class="form-control " id="invoice_number" name="invoice_number" size="16" type="hidden" value="{{ $row[0]->invoice_number }}" readonly>
            </div>
            <div class="col-md-2">
            </div>
        </div>
        <div class="form-group row" style="display:none">
            <label for="inputIsValid" class="form-control-label col-md-4">PO Tax Total</label>
            <div class="col-md-6">
                <input type="text" name="invoice_tax_total" id="invoice_tax_total" value="{{ $row->invoice_tax_total }}" class="form-control invoice_tax_total">
            </div>
            <div class="col-md-2">
            </div>
        </div>
        <div class="form-group row" style="display:none">
            <label for="inputIsValid" class="form-control-label col-md-4">PO Grand Total</label>
            <div class="col-md-6">
                <input type="text" name="invoice_grand_total" id="invoice_grand_total" value="{{ $row->invoice_grand_total }}" class="form-control invoice_grand_total">
                <input type="hidden" id="base_grand_total" value="{{ $row->invoice_grand_total ?? '0.00' }}">
            </div>
            </div>		
		<!--- end -->				
                    </div>

                    <div class="col-12 col-xl-4">
                        @if($invoice_type!="LABOUR" && ($row[0]->grn_source ?? '')!="GRN")
                        <div class="mb-3">
                            <label for="need_to_close" class="form-label required">Need to close PO</label>
                            <select name="need_to_close" id="need_to_close" class="form-select select2 need_to_close" required tabindex="3">
                                <option value="">--Please Select--</option>
                                <option value="YES" @if(($row->need_to_close ?? '')=='1') selected @endif>YES</option>
                                <option value="NO" @if(($row->need_to_close ?? '')=='0') selected @endif>NO</option>
                            </select>
                        </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label" for="choosefile">Attachments</label>
                            @php
                            $existing = [];
                            if(!empty($row->attachfile_name)){
                            $existing = json_decode($row->attachfile_name, true) ?? [];
                            }
                            $downloadable = in_array(($ids ?? null), [1,3]) ? "download" : "";
                            @endphp

                            <input id="" class="form-control GetFileSizeNameAndType" name="choosefile[]"
                                type="file" multiple />
                            <div class="form-text">You can select multiple files.</div>

                            <table class="table table-sm table-bordered mt-3 mb-0 file-table">
                                <tbody id="fp">
                                    @if(!empty($existing))
                                    <input type="hidden" value="{{ implode(',', $existing) }}" name="existing_file"
                                        id="existing_file" />
                                    @foreach($existing as $v)
                                    <tr>
                                        <td class="d-flex align-items-center justify-content-between">
                                            <span>
                                                <i class="bi bi-paperclip me-2"></i>
                                                <a {{ $downloadable }}
                                                    href="{{ url('uploads/purchaseinvoice/PO'.$po_invoice_id.'/'.$v) }}"
                                                    target="_blank">{{ $v }}</a>
                                            </span>
                                            <button type="button" class="btn btn-link text-danger p-0 delete_user"
                                                data-value="{{ $v }}">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- ADDITIONAL DETAILS (driven by $enabled_columns) --}}
                <hr class="my-4">
                <div class="form-section-title text-primary fw-bold text-center">Additional Details</div>

                <div class="row g-4 mt-2">
                    @php $i=0; $j=0; @endphp
                    @foreach($enabled_columns as $index => $val)
                    @php $required = ($val->action=='1') ? 'required' : ''; @endphp

                    @if($val->column_name=='supplier_invoice_no' && $val->active==1)
                    <div class="col-12 col-lg-4">
                        <label class="form-label @if($required) required @endif" for="supplier_invoice_no">Supplier Ref
                            No</label>
                        <input type="text" id="supplier_invoice_no" name="supplier_invoice_no" class="form-control" {{
                            $required }} value="{{ $row->supplier_invoice_no ?? '' }}" tabindex="8">
                    </div>
                    @endif

                    @if($val->column_name=='supplier_invoice_date' && $val->active==1)
                    <div class="col-12 col-lg-4 none">
                        <label class="form-label @if($required) required @endif" for="supplier_invoice_date">Entry
                            Date</label>
                        <input type="text" id="supplier_invoice_date" name="supplier_invoice_date"
                            class="form-control datepicker" {{ $required }}
                            value="{{ $row->supplier_invoice_date ?? '' }}" tabindex="9" placeholder="YYYY-MM-DD">
                    </div>
                    @endif

                    @if($val->column_name=='due_date' && $val->active==1)
                    <div class="col-12 col-lg-4 ">
                        <label class="form-label @if($required) required @endif" for="due_date">Due Date</label>
                        <input type="text" id="due_date" name="due_date" class="form-control due_date " {{ $required }}
                            value="{{ $row->due_date ?? '' }}" tabindex="20" placeholder="YYYY-MM-DD">
                    </div>
                    @endif

                    @if($val->column_name=='dc_date' && $val->active==1)
                    <div class="col-12 col-lg-4">
                        <label class="form-label" for="dc_date">DC Date</label>
                        <input type="text" id="dc_date" name="dc_date" class="form-control dc_date"
                            value="{{ $row->dc_date ?? '' }}" tabindex="11" placeholder="YYYY-MM-DD">
                    </div>
                    @endif

                    @if($val->column_name=='project_id' && $val->active==1)
                    <div class="col-12 col-lg-4">
                        <label class="form-label @if($required) required @endif" for="project_id">Project Name</label>
                        <select name="project_id" id="project_id" class="form-select select2" {{ $required }}
                            tabindex="19">
                            {!! $project_id !!}
                        </select>
                    </div>
                    @endif

                    @if($val->column_name=='delivery_terms_id' && $val->active==1)
                    <div class="col-12 col-lg-4">
                        <label class="form-label @if($required) required @endif" for="delivery_terms_id">Delivery
                            Term</label>
                        <select name="delivery_terms_id" id="delivery_terms_id" class="form-select delivery_terms_id select2" {{ $required
                            }} tabindex="26">
                            {!! $delivery_terms_id !!}
                        </select>
                    </div>
                    @endif

                    @if($val->column_name=='freight_carrier_id' && $val->active==1)
                    <div class="col-12 col-lg-4">
                        <label class="form-label @if($required) required @endif" for="freight_carrier_id">Freight
                            Carriers</label>
                        <select name="freight_carrier_id" id="freight_carrier_id" class="form-select select2" {{
                            $required }} tabindex="27">
                            {!! $freight_carrier_id !!}
                        </select>
                    </div>
                    @endif

                    @if($val->column_name=='payment_method_id' && $val->active==1)
                    <div class="col-12 col-lg-4">
                        <label class="form-label @if($required) required @endif" for="payment_method_id">Payment
                            Method</label>
                        <select name="payment_method_id" id="payment_method_id" class="form-select select2" {{ $required
                            }} tabindex="28">
                            {!! $payment_method_id !!}
                        </select>
                    </div>
                    @endif

                    {{-- Charges (readonly as in your original) --}}
                    @if($val->column_name=='transport_charges' && $val->active==1)
                    <div class="col-12 col-lg-4">
                        <label class="form-label @if($required) required @endif" for="transport_charges">Transport
                            Charges</label>
                        <div class="input-group">
                            <input type="text" id="transport_charges" name="transport_charges"
                                class="form-control charges transport_charges" value="{{ $row->transport_charges ?? '' }}" {{ $required }}
                                tabindex="4" readonly>
                            <button class="btn btn-outline-secondary packingtax" type="button"
                                data-value="Transport Charges" data-at="2">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                        <input type="hidden" name="packing_charges_tax" id="packing_charges_tax" class="packing_charges_tax"
                            value="{{ $row->packing_charges_tax ?? '' }}">
                        <input type="hidden" name="insurance_charges_tax" id="insurance_charges_tax" class="insurance_charges_tax"
                            value="{{ $row->insurance_charges_tax ?? '' }}">
                        <input type="hidden" name="transport_charges_tax" id="transport_charges_tax" class="transport_charges_tax"
                            value="{{ $row->transport_charges_tax ?? '' }}">
                        <input type="hidden" name="unloading_charges_tax" id="unloading_charges_tax" class="unloading_charges_tax"
                            value="{{ $row->unloading_charges_tax ?? '' }}">
                    </div>
                    @endif

                    @if($val->column_name=='unloading_charges' && $val->active==1)
                    <div class="col-12 col-lg-4">
                        <label class="form-label @if($required) required @endif" for="unloading_charges">Unloading
                            Charges</label>
                        <div class="input-group">
                            <input type="text" id="unloading_charges" name="unloading_charges"
                                class="form-control unloading_charges charges" value="{{ $row->unloading_charges ?? '' }}" {{ $required }}
                                tabindex="5" readonly>
                            <button class="btn btn-outline-secondary packingtax" type="button"
                                data-value="Unloading Charges" data-at="6">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                    </div>
                    @endif

                    @if($val->column_name=='insurance_charges' && $val->active==1)
                    <div class="col-12 col-lg-4">
                        <label class="form-label @if($required) required @endif" for="insurance_charges">Insurance
                            Charges</label>
                        <div class="input-group">
                            <input type="text" id="insurance_charges" name="insurance_charges"
                                class="form-control insurance_charges charges" value="{{ $row->insurance_charges ?? '' }}" {{ $required }}
                                tabindex="6" readonly>
                            <button class="btn btn-outline-secondary packingtax" type="button"
                                data-value="Insurance Charges" data-at="3">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                    </div>
                    @endif

                    @if($val->column_name=='packing_charges' && $val->active==1)
                    <div class="col-12 col-lg-4">
                        <label class="form-label @if($required) required @endif" for="packing_charges">Packing
                            Charges</label>
                        <div class="input-group">
                            <input type="text" id="packing_charges" name="packing_charges" class="form-control packing_charges charges"
                                value="{{ $row->packing_charges ?? '' }}" {{ $required }} tabindex="7" readonly>
                            <button class="btn btn-outline-secondary packingtax" type="button"
                                data-value="Packing Charges" data-at="1">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                    </div>
                    @endif

                    @if($val->column_name=='other_tax_amount' && $val->active==1)
                    <div class="col-12 col-lg-4">
                        <label class="form-label @if($required) required @endif" for="other_tax_amount">Other Tax
                            Amount</label>
                        <div class="input-group">
                            <input type="text" id="other_tax_amount" name="other_tax_amount"
                                class="form-control charges" value="{{ $row->other_tax_amount ?? '' }}" {{ $required }}
                                tabindex="12" readonly>
                            <button class="btn btn-outline-secondary packingtax" type="button"
                                data-value="Other Tax Amount" data-at="4">                
                               <input type="hidden" name="other_tax_amount_tax" id="other_tax_amount_tax" class="other_tax_amount_tax"
                            value="{{ $row->other_tax_amount_tax ?? '' }}">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>

                    </div>
                    @endif

                    @if($val->column_name=='other_freight_amount' && $val->active==1)
                    <div class="col-12 col-lg-4">
                        <label class="form-label @if($required) required @endif" for="other_freight_amount">Other
                            Freight Amount</label>
                        <div class="input-group">
                            <input type="text" id="other_freight_amount" name="other_freight_amount"
                                class="form-control other_freight_amount charges" value="{{ $row->other_freight_amount ?? '' }}" {{ $required
                                }} tabindex="21" readonly>
                            <button class="btn btn-outline-secondary packingtax" type="button"
                                data-value="Other Freight Amount" data-at="5">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                        <input type="hidden" name="other_frieght_amount_tax" id="other_frieght_amount_tax" class="other_frieght_amount_tax"
                            value="{{ $row->other_frieght_amount_tax ?? '' }}">
                    </div>
                    @endif

                    @if($val->column_name=='invoice_currency_id' && $val->active==1)
                    <div class="col-12 col-lg-4">
                        <label class="form-label @if($required) required @endif" for="invoice_currency_id">Invoice
                            Currency</label>
                        <select id="invoice_currency_id" name="invoice_currency_id" class="form-select select2" {{
                            $required }} tabindex="23">
                            {!! $invoice_currency_id !!}
                        </select>
                    </div>
                    @endif

                    @if($val->column_name=='invoice_pricelist_id' && $val->active==1)
                    <div class="col-12 col-lg-4">
                        <label class="form-label @if($required) required @endif" for="invoice_pricelist_id">Invoice
                            Pricelist</label>
                        <select id="invoice_pricelist_id" name="invoice_pricelist_id" class="form-select select2" {{
                            $required }} tabindex="22">
                            {!! $invoice_pricelist_id !!}
                        </select>
                    </div>
                    @endif

                    @if($val->column_name=='payment_term_id' && $val->active==1)
                    <div class="col-12 col-lg-4">
                        <label class="form-label @if($required) required @endif" for="payment_term_id">Payment
                            Term</label>
                        <select id="payment_term_id" name="payment_term_id" class="form-select select2" {{ $required }}
                            tabindex="24">
                            {!! $payment_term_id !!}
                        </select>
                    </div>
                    @endif

                    @if($val->column_name=='freight_terms_id' && $val->active==1)
                    <div class="col-12 col-lg-4">
                        <label class="form-label @if($required) required @endif" for="freight_terms_id">Freight
                            Term</label>
                        <select id="freight_terms_id" name="freight_terms_id" class="form-select select2" {{ $required
                            }} tabindex="17">
                            {!! $freight_terms_id !!}
                        </select>
                    </div>
                    @endif

                    @if($val->column_name=='remarks' && $val->active==1)
                    <div class="col-12 col-lg-4">
                        <label class="form-label @if($required) required @endif" for="remarks">Remarks</label>
                        <input type="text" id="remarks" name="remarks" class="form-control remarks" {{ $required }}
                            value="{{ $row->remarks ?? '' }}" tabindex="25" maxlength="250">
                    </div>
                    @endif

                    {{-- TDS --}}
                    @if($val->column_name=='tds_applicable' && $val->active==1)
                    <div class="col-12 col-lg-4 tds_apply_div">
                        <label class="form-label @if($required) required @endif" for="tds_applicable"><span class="text-danger">*</span>TDS
                            Applicable</label>
                        <select id="tds_applicable" name="tds_applicable" class="form-select tds_applicable select2" {{ $required }}
                            tabindex="13">
                            <option value="">--Please Select--</option>
                            <option value="YES" @if(($row[0]->tds_applicable ?? '')=='YES') selected @endif>YES</option>
                            <option value="NO" @if(($row[0]->tds_applicable ?? '')=='NO') selected @endif>NO</option>
                        </select>
                    </div>
                    @endif

                    @if($val->column_name=='tds_amount' && $val->active==1)
                    <div class="col-12 col-lg-4 tds_applyling_div">
                        <label class="form-label" for="tds_amount">TDS Amount</label>
                        <input type="text" id="tds_amount" name="tds_amount" class="form-control tds_amount"
                            value="{{ $row[0]->tds_amount ?? '' }}" readonly tabindex="15">
                    </div>
                    @endif

                    @if($val->column_name=='tds_prcnt' && $val->active==1)
                    <div class="col-12 col-lg-4 tds_applyling_div">
                        <label class="form-label" for="tds_prcnt">TDS Percentage</label>
                        <input type="text" id="tds_prcnt" name="tds_prcnt" class="form-control tds_prcnt"
                            value="{{ $row[0]->tds_prcnt ?? '' }}" readonly tabindex="14">
                    </div>
                    @endif

                    @if($val->column_name=='tds_account_id' && $val->active==1)
                    <div class="col-12 col-lg-4 tds_applyling_div">
                        <label class="form-label" for="tds_account_id">TDS Account</label>
                        <select id="tds_account_id" name="tds_account_id" class="form-select tds_account_id select2" readonly
                            tabindex="16">
                            {!! $tds_account_id !!}
                        </select>
                    </div>
                    @endif

                    {{-- TCS --}}
                    @if($val->column_name=='tcs_applicable' && $val->active==1)
                    <div class="col-12 col-lg-4">
                        <label class="form-label @if($required) required @endif" for="tcs_applicable"><span class="text-danger">*</span>TCS
                            Applicable</label>
                        <select id="tcs_applicable" name="tcs_applicable" class="form-select select2 tcs_applicable" {{ $required }}
                            tabindex="13">
                            <option value="">--Please Select--</option>
                            <option value="YES" @if(($row[0]->tcs_applicable ?? '')=='YES') selected @endif>YES</option>
                            <option value="NO" @if(($row[0]->tcs_applicable ?? '')=='NO') selected @endif>NO</option>
                        </select>
                    </div>
                    @endif

                    @if($val->column_name=='tcs_amount' && $val->active==1)
                    <div class="col-12 col-lg-4 tcs_applyling_div">
                        <label class="form-label" for="tcs_amount">TCS Amount</label>
                        <input type="text" id="tcs_amount" name="tcs_amount" class="form-control tcs_amount"
                            value="{{ $row[0]->tcs_amount ?? '' }}" readonly tabindex="15">
                    </div>
                    @endif

                    @if($val->column_name=='tcs_prcnt' && $val->active==1)
                    <div class="col-12 col-lg-4 tcs_applyling_div">
                        <label class="form-label" for="tcs_prcnt">TCS Percentage</label>
                        <input type="text" id="tcs_prcnt" name="tcs_prcnt" class="form-control tcs_prcnt"
                            value="{{ $row[0]->tcs_prcnt ?? '' }}" readonly tabindex="14">
                    </div>
                    @endif

                    @if($val->column_name=='tcs_account_id' && $val->active==1)
                    <div class="col-12 col-lg-4 tcs_applyling_div">
                        <label class="form-label" for="tcs_account_id">TCS Account</label>
                        <select id="tcs_account_id" name="tcs_account_id" class="form-select select2 tcs_account_id" readonly
                            tabindex="16">
                            {!! $tcs_account_id !!}
                        </select>
                    </div>
                    @endif

                    @if($val->column_name=='round_off' && $val->active==1)
                    <div class="col-12 col-lg-4">
                        <label class="form-label" for="round_off">Round Off</label>
                        <input type="text" id="round_off" name="round_off" class="form-control round_off"
                            value="{{ $row[0]->round_off ?? '' }}" tabindex="14">
                    </div>
                    @endif

                    @if($val->column_name=='dc_number' && $val->active==1)
                    <div class="col-12 col-lg-4">
                        <label class="form-label" for="dc_number">DC Number</label>
                        <input type="text" id="dc_number" name="dc_number" class="form-control dc_number"
                            value="{{ $row->dc_number ?? '' }}" tabindex="10">
                    </div>
                    @endif
                    @endforeach
                </div>

                <!-------------------------Linedata -------------------------------->
                <div class="row mt-4">
                    <div class="col-12 linetable">
                        <div id="preview-area" class="table-responsive">
                            <table class="table table-bordered clone_table" style="width:200% !important">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 80px;">Line No</th>
                                        <th class="pdtdiv freeze">Product </th>
                                        <th>Product Description </th>
                                        <th>Uom Code </th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <?php if ($pageMethod == "poinvoiceapproval") { ?>
                                            <th>Assessable Value</th>
                                            <th>Tax Credit</th>
                                        <?php } ?>
                                        <th>Discount(%)</th>
                                        <th>Discount Amount</th>
                                        <th>Hsn Code</th>
                                        <th>Tax Group</th>
                                        <th>Tax Amount</th>
                                        <th>Line Total</th>
                                        <th>Comments</th>
                                        <th style="width: 60px;"></th>
                                    </tr>
                                </thead>
                                <tbody class="clone_lines_body">
                                    @if(count($linedata) > 0)
                                    @foreach($linedata as $key => $value)


                                    <?php

                                    if (($aprvidenty == '' || $aprvidenty == 'null') && $value->accept_qty == 0) {

                                        $display = "display:none;";
                                    } else {
                                        $display = "display:block;";
                                    }
                                    ?>

                                    <tr class="line-row">

                                        <td>
                                            <input type="hidden" name="bulk_po_invoice_lines_id[]"
                                                class="form-control input-sm bulk_po_invoice_lines_id"
                                                value="{{ $value->po_invoice_lines_id }}">

                                            <input type="text" name="bulk_line_no[]"
                                                class="form-control input-sm bulk_line_no" value="{{ $key + 1 }}"
                                                readonly="readonly">
                                        </td>
                                        <td class="pdtdiv freeze">
                                            <select name="bulk_product_id[]"
                                                class="select2 bulk_product_id parsley-validated"
                                                required="required">{!! $value->product_id !!}</select>
                                        </td>
                                        <td class="pdtdes_div">
                                            <input type="text" name="bulk_product_description[]"
                                                class="form-control input-sm bulk_product_description input_qty_width"
                                                value="{{$value->product_description}}">
                                        </td>
                                        <td>
                                            <select name="bulk_uom_code_id[]" id="bulk_uom_code_id"
                                                class="form-control bulk_uom_code_id">
                                                {!! $value->uom_code_id !!}
                                            </select>
                                        </td>

                                        <td>
                                            <input type="text" name="bulk_qty[]"
                                                class="form-control input-sm bulk_qty input_qty_width"
                                                value="{{ $value->qty }}" required="required">
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_unit_price[]"
                                                class="form-control input-sm bulk_unit_price input_qty_width"
                                                value="{{ $value->unit_price }}" required="required">
                                        </td>
                                        <?php if ($pageMethod == "poinvoiceapproval") { ?>
                                            <td>
                                                <label class="align_left" style="padding:7px !important"> <span
                                                        class=' span_color'> {{ ($value->qty)*($value->unit_price)
                                                        }}</span></label>
                                            </td>
                                            <td>
                                                <?php if ($value->tax_credit == "Yes") { ?>
                                                    <label class="align_left"
                                                        style="padding:7px !important; background:rgb(7 184 4) !important">
                                                        <span class=' span_color'> {{ $value->tax_credit }}</span></label>
                                                <?php } else { ?>
                                                    <label class="align_left"
                                                        style="padding:7px !important; background:rgb(255 18 45) !important">
                                                        <span class=' span_color'> {{ $value->tax_credit }}</span></label>
                                                <?php } ?>
                                            </td>
                                        <?php } ?>
                                        <td>
                                            <input type="text" name="bulk_discount_percentage[]"
                                                class="form-control input-sm bulk_discount_percentage input_qty_width"
                                                value="{{ $value->discount_percentage }}">
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_discount_amount[]"
                                                class="form-control input-sm bulk_discount_amount input_qty_width"
                                                value="{{ $value->discount_amount }}">
                                        </td>
                                        <td class="hsn">
                                            <select name="bulk_hsn_code[]" id="bulk_hsn_code"
                                                class=" select2 bulk_hsn_code"
                                                style="border: 1px solid black; border-radius:5px;"> {!!
                                                $value->hsn_code !!}</select>
                                        </td>
                                        <td class="taxgrp_div">
                                            <select name="bulk_tax_group_id[]" id="bulk_tax_group_id"
                                                class="select2 bulk_tax_group_id" readonly>
                                                {!! $value->tax_group_id !!}
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_tax_amount[]"
                                                class="form-control input-sm bulk_tax_amount input_qty_width"
                                                value="{{ $value->tax_amount }}" required="required">
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_line_total[]"
                                                class="form-control input-sm bulk_line_total input_qty_width"
                                                value="{{ $value->line_total }}" required="required">
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_comments[]"
                                                class="form-control input-sm bulk_comments input_qty_width"
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

                                    <tr class="line-row">
                                        <td>
                                            <input type="hidden" name="bulk_po_invoice_lines_id[]"
                                                class="form-control input-sm bulk_po_invoice_lines_id" value="">

                                            <input type="text" name="bulk_line_no[]"
                                                class="form-control input-sm bulk_line_no" value="1"
                                                readonly="readonly">
                                        </td>
                                        <td class="pdtdiv freeze">
                                            <select name="bulk_product_id[]"
                                                class="select2  bulk_product_id  parsley-validated"
                                                required="required">{!! $product_id !!}</select>
                                        </td>

                                        <td class="pdtdes_div">
                                            <input type="text" name="bulk_product_description[]"
                                                class="form-control input-sm bulk_product_description input_qty_width"
                                                value="">
                                        </td>
                                        <td>
                                            <select name="bulk_uom_code_id[]" id="bulk_uom_code_id"
                                                class="select2 bulk_uom_code_id">
                                                {!! $uom_code_id !!}
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_qty[]" class="form-control bulk_qty " value=""
                                                required="required">
                                        </td>

                                        <td>
                                            <input type="text" name="bulk_unit_price[]"
                                                class="form-control input-sm bulk_unit_price " value=""
                                                required="required">
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
                                                class="select2 bulk_hsn_code">{!! $hsn_code!!}</select>
                                        </td>
                                        <td class="taxgrp_div">
                                            <select name="bulk_tax_group_id[]" id="bulk_tax_group_id"
                                                class="select2 bulk_tax_group_id">
                                                {!! $tax_group_id !!}
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_tax_amount[]"
                                                class="form-control input-sm bulk_tax_amount" value=""
                                                required="required">
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_line_total[]"
                                                class="form-control input-sm bulk_line_total" value=""
                                                required="required">
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
                <!-- END -->
                <!-------------------------Linedata End-------------------------------->

                <div class="row mt-4 mb-3">
                    <div class="col-lg-12 col-md-12">
                        <div class="form-group text-center">
                            <?php if ($aprvidenty == "") { ?>
                                <button type="button" class="btn btn-secondary px-4 me-2 saveform"
                                    value="APPLYCHANGES">Draft</button>
                                <button type="button" class="btn btn-success px-4 me-2 saveform" value="SAVE">Save</button>
                                <a href="{{ url('purchaseinvoice') }}" class='btn btn-outline-danger px-4 me-2'>Cancel</a>
                            <?php } else { ?>
                                <button type="button" class="btn btn-success saveform approved px-4 me-2"
                                    value="APPROVED"><i class="bi bi-check2-circle"></i> Approve</button>
                                <button type="button" class="btn btn-danger saveform rejected px-4 me-2"
                                    value="REJECTED"><i class="bi bi-x"></i> Reject</button>
                                <a href="{{ url($pageMethod) }}" class='btn btn-outline-danger px-4 me-2'>Cancel</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>



            </form>
        </div>
    </div>

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

    <!--end-->


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

    <!--end-->

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

    // 1) Clone the last row (keep events = false)
    const $newRow = $lastRow.clone(false, false);

    // 2) Clean up cloned row's Select2 markup and values
    $newRow.find('select.select2').each(function () {
        // remove Select2 wrapper from cloned DOM, if any
        $(this).next('.select2').remove();

        // remove Select2-specific attributes/classes from the cloned element
        $(this)
            .removeClass('select2-hidden-accessible')
            .removeAttr('data-select2-id')
            .off(); // remove cloned events

        // clear the value
        $(this).val(null);
    });

    // 3) Clear other inputs in the cloned row
    $newRow.find('.bulk_company_line_id').val('');
    $newRow.find('.bulk_line_no').val('');   // will be set by updateLineNumbers()
    $newRow.find('.bulk_description').val('');

    // 4) (Optional but recommended) fix duplicate IDs in cloned row
    $newRow.find('[id]').each(function () {
        const newId = $(this).attr('id') + '_' + Date.now();
        $(this).attr('id', newId);
    });

    // 5) Append cloned row
    $tbody.append($newRow);

    // 6) Initialize Select2 only for the new row's selects
    $newRow.find('select.select2').select2({ width: '100%' });

    // 7) Update line numbers
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

        function example() {
            $("#file_choosen").css({
                "border-color": "rgb(20, 46, 120)",
                "border-width": "1px",
                "border-style": "solid"
            });
        }


        $(document).ready(function () { 

                var tds  =  $('.tds_applicable').val();
                var tcs  =  $('.tcs_applicable').val();
 
                if (tds == "YES") {
                $('.tds_applyling_div').show();
                } else if (tcs == "YES") {
                $('.tcs_applyling_div').show();
                }else{
                     $('.tds_applyling_div').hide();
                      $('.tcs_applyling_div').hide();
                }


            $('.bulk_uom_code_id').css('width', '100px');

            $('.invoice_type').css('pointer-events', 'none');
            <?php if ($aprvidenty == "1") { ?>
                $('.invdate_div').css('pointer-events', 'none');
                $('input').attr('readonly', true);
                $('select').attr('readonly', true);
                $('select').css('pointer-events', 'none');
                $('.delivery_div,.payment_div,.project_div,.taxgrp_div,.bulk_qty,.bulk_discount_percentage,.supplier_div,.pricelist_div,.hsn,.partno,.need_to_close,.tds_apply_div,.freight_div,.payment_div,.paymethod_div,.carrier_div,.currency_div,.due_date').css('pointer-events', 'none');
                $('.add_row,.remove').hide();
                $('.productsearch,.jcomborefresh,.ichide').hide();
                $('.remarks,.bulk_comments,.round_off').attr('readonly', false);
                $('.reverse_charge').css('pointer-events', 'none');
                // $('#fp').css('pointer-events', 'none');
            <?php } ?>


            <?php if ($invoice_type == "LABOUR") { ?>
                $('.suppliersite_id_div,.invoice_date_div,.tds_div,.invoice_pricelist_id,.dc_number,.dc_date').css('pointer-events', 'auto');
                $('.bulk_unit_price,.hsn,.bulk_discount_percentage,.bulk_discount_amount,.bulk_line_total').attr('readonly', false);
                $('.taxgrp_div,.bulk_uom_code_id,.bulk_tax_amount').css("pointer-events", "none");
                $('.need_to_close').attr('required', false);

            <?php } else if ($invoice_type == "LABOUR FROM PO") { ?>
                    $('.tds_div').css("pointer-events", "auto");
                    $('.tds_account_id').attr('readonly', true);

            <?php } else { ?>

                    $('.suppliersite_id_div,.invoice_date_div,.tds_div,.invoice_pricelist_id,.dc_number,.dc_date').css('pointer-events', 'none');
                    $('.bulk_unit_price,.bulk_discount_amount,.bulk_line_total').attr('readonly', true);
                    $('.po_number,.bulk_accept_qty,#po_tax_total,#po_grand_total').attr("readonly", true);
                    $('.supplier_id,.bulk_product_id,.bulk_uom_code_id,.bulk_tax_amount').attr("readonly", true);
                    $('.supplier_id,.po_invoice_status,.bulk_product_id,.pdtdiv,.bulk_uom_code_id').css("pointer-events", "none");
            <?php } ?>


            <?php if ($invoice_type != "LABOUR") { ?>
                <?php if ($row[0]->grn_source != "GRN") { ?>
                    $('.need_to_close', '.supplier_id', '.suppliersite_id').attr("required", false);
                <?php } ?>
            <?php } ?>

		});
            /* Purpose For Supplier Based Price load*/
            $(document).on('change', '.supplier_id', function () {
                var supplier_id = $('.supplier_id option:selected').val();
                if (supplier_id != '') {
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


                    $.get("{{ URL::to('supplierpricelist') }}/" + supplier_id, function (suppdata) {
                        var data = $.trim(suppdata);
                        if (data != 0) {
                            setTimeout(function () {
                                $(".suppliersite_id").val(suppdata['supplier_site_id']).change();
                            }, 500);
                            setTimeout(function () {
                                $(".invoice_pricelist_id").val(suppdata['price_list']).change();
                            }, 500);
                            $('.payment_term_id').val(suppdata['default_payment_terms_id']).change();
                            $('.delivery_terms_id').val(suppdata['delivery_terms_id']).change();
                            $('.payment_method_id').val(suppdata['default_payment_method_id']).change();
                        }
                        else {

                            $(".po_pricelist_id").val('').change();
                        }
                    });


                }


            });

            // purpose for load product based details 

            $(document).on('change', '.bulk_product_id', function (event) {
                var index = $(this).closest('tr').index();
                var product_id = $(this).val();
                var type = $('.invoice_type option:selected').val();

                var plid = $('.invoice_pricelist_id option:selected').val();
                var cid = $('.supplier_id option:selected').val();
                var suppsiteid = $('.suppliersite_id option:selected').val();

                if (plid != "" && product_id != '' && product_id != null) {

                    var url = "{{ url::to('productdetails') }}/" + product_id + "/" + plid + "/" + suppsiteid + "/" + type + "?supplier_id=" + cid + "&source=SUPPLIER";

                    if (product_id != '' && product_id != null) {
                        if (cid != '') {
                            if (plid != '') {
                                if (product_id != '') {
                                    var pdtcount = 0;
                                    var pdtcount = pdtcheck(product_id, index);
                                    if (pdtcount <= 0) {
                                        $.get(url, function (data) {

                                            var hsnid = data['multihsn'];
                                            if (hsnid != '') {
                                                var condition = "classification_name='SAC' and gst_code_hdr_id in(" + hsnid + ")";
                                                var url = "{{ URL::to('jcomboform?table=f_gst_code_hdr_t:gst_code_hdr_id:classification_code') }}" +
                                                    "&order_by=classification_code asc" +
                                                    "&parent=" + encodeURIComponent(condition);

                                                $.ajax({
                                                    url: url,
                                                    type: "GET",
                                                    success: function (data) {
                                                        var $dropdown = $(".bulk_hsn_code" + index);
                                                        $dropdown.empty().append('<option value="">-- Select --</option>');

                                                        // Parse JSON if response is string
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
                                                            $dropdown.append(`<option value="${item.val}">${item.option_name}</option>`);
                                                        });

                                                        $dropdown.trigger('change.select2'); // For select2 dropdowns
                                                    },
                                                    error: function (xhr, status, error) {
                                                        console.error("Error loading HSN codes:", error);
                                                    }
                                                });
                                            }


                                            setTimeout(function () {
                                                $(".bulk_hsn_code" + index).val(data['hsn_code']).change();
                                            }, 500);

                                            $('.bulk_part_no' + index).val(data.part_no).change();
                                            $('.bulk_uom_code_id' + index).val(data.uom_code_id).change();
                                            $('.bulk_unit_price' + index).val(data.unit_price);
                                            calc_by_index(index);
                                            if (data.unit_price == "0" || data.unit_price == "") {
                                                showCustomAlert('Pricelist Not Assigned For this Product ', 'info');
                                            }
                                            if ($.trim(data.tax_group_id) == 0) {
                                                if ($.trim(data.tax_group_id_expiry) == "expiry") {
                                                    showCustomAlert('Tax Group expired  for this product', 'info');
                                                    $('.bulk_tax_group_id' + index).val(data.tax_group_id).change();
                                                }
                                                else if ($.trim(data.tax_group_id_expiry) == "location") {
                                                    showCustomAlert('Tax not assigned for this Location', 'info');
                                                }
                                                else {
                                                    showCustomAlert('Tax Group not assigned for this product', 'info');
                                                    $('.bulk_tax_group_id' + index).val(data.tax_group_id).change();
                                                }
                                            }
                                            else {
                                                $('.bulk_tax_group_id' + index).val('0').change();
                                            }
                                        });
                                    }
                                    else {
                                        showCustomAlert('Product Already Selected', 'info');
                                        rowdataEmpty(index);
                                        $(".bulk_product_id" + index).val('').change();
                                        event.preventDefault();
                                    }

                                }
                                else {
                                    rowdataEmpty(index);
                                    calc_by_index(index)
                                }
                            }
                            else {
                                rowdataEmpty(index);
                                calc_by_index(index);
                                notyMsg('info', 'Please Select a Pricelist');
                                event.preventDefault();
                            }
                        }
                        else {
                            showCustomAlert('Please Select a Supplier', 'info');
                            $(".bulk_product_id" + index).val('').change();
                            event.preventDefault();
                        }
                    }
                }

            });

            $(document).on('change', '.invoice_date', function () {
                var invoice_date = $(this).val();
                var payment_term_id = $('.payment_term_id').val();

                var url = "{{ URL::to('duedatecal')}}/?invoice_date=" + invoice_date + "&payment_term_id=" + payment_term_id;

                $.get(url, function (data) {
                    var due_date = data;
                    $('.due_date').val(due_date);

                });
            });


            $(document).on('change', '.payment_term_id', function () {
                var invoice_date = $(this).val();
                var payment_term_id = $('.payment_term_id').val();

                var url = "{{ URL::to('duedatecal')}}/?invoice_date=" + invoice_date + "&payment_term_id=" + payment_term_id;

                $.get(url, function (data) {
                    var due_date = data;
                    $('.due_date').val(due_date);

                });

            });

            /* purpose: to load tax based on hsn code*/
            $(document).on('change', '.bulk_hsn_code', function () {
                var hsnid = $(this).val();
                if (hsnid) {
                    var index = $(this).closest('tr').index();
                    var suppsiteid = $('.suppliersite_id').val();
                    if (suppsiteid == "") {
                        var suppsiteid = $('.subcontract_site_id').val();//Subcontract Site
                    } else {
                        var suppsiteid = $('.suppliersite_id').val();//Supplier Site
                    }

                    var mtype = "PURCHASE";
                    if (hsnid != "" && suppsiteid != "") {
                        var url = "{{ URL::to('taxdetails')}}/" + hsnid + "/" + suppsiteid + "/" + mtype;
                    }
                    $.get(url, function (data) {
                        if (data['tax_group_id'] == 0) {
                            if ($.trim(data['tax_group_id_expiry']) == "expiry") {
                                showCustomAlert('Tax Group expired  for this product', 'warning');
                            }
                            else if ($.trim(data['tax_group_id_expiry']) == "location") {
                                showCustomAlert('Tax not assigned for this Location', 'warning');
                            }
                            else {
                                showCustomAlert('Tax Group not assigned for this product', 'warning');
                            }
                        }
                        else {
                            $('.bulk_tax_group_id' + index).select2('val', [data.tax_group_id]);
                        }

                    });
                }
            });

            /*end*/

            function calc_by_index(index) {

                var unit_price = $('.bulk_unit_price' + index).val();
                var qty = $('.bulk_qty' + index).val();
                var tax_group = $('.bulk_tax_group_id' + index + ' option:selected').attr('data-display');
                var line_sub_total = parseFloat(unit_price * qty);
                var tax_amount = parseFloat((line_sub_total * tax_group) / 100);
                $('.bulk_tax_amount' + index).val(tax_amount);
                var linetot = parseFloat(line_sub_total + tax_amount).toFixed(decimal);
                $(".bulk_line_sub_total" + index).val(line_sub_total);
                $(".bulk_line_total" + index).val(linetot);

                /* Code for set linetotal values to header level via keyup*/
                var lsbt = 0;
                $('.bulk_line_sub_total').each(function () {
                    lsbt += parseFloat($(this).val());
                });
                $('.order_sub_total').val(lsbt);

                var sum = 0;
                $('.bulk_line_total').each(function () {

                    sum += parseFloat(isNaN($(this).val()) ? 0 : ($(this).val()));
                });
                $('#grand_total_span').html(sum);
                /* end */
                /* Code for calculating tot tax amount */
                var tax = 0;
                $('.bulk_tax_amount').each(function () {
                    tax += parseFloat($(this).val());
                });
                $('.po_tax_total').val(tax);
                /* end */
            }

            $('#savestatus').val('');
            $('.req').hide();

            $(document).on('click', '.saveform', function () {
                var btnval = $(this).val();
                if (btnval == 'APPLYCHANGES') {
                    $('.remarks').attr('required', false);
                    $("#po_invoice_status").val('DRAFT');
                }
                else if (btnval == 'DRAFT') {
                    $('.remarks').attr('required', false);
                    $("#po_invoice_status").val('DRAFT');
                }
                else if (btnval == 'APPROVED') {
                    $('.remarks').attr('required', false);
                    $("#po_invoice_status").val('APPROVED');
                }
                else if (btnval == 'REJECTED') {
                    $('.req').show();
                    $('.remarks').attr('required', true);

                    $("#po_invoice_status").val('REJECTED');
                }
                else {
                    $('.remarks').attr('required', false);
                    $("#po_invoice_status").val('INITIATED');
                }
                $('#savestatus').val(btnval);

                var url = "{{ url('poinvoiceformsave') }}";
                var red_url = "{{ url('purchaseinvoice') }}";
                var redd_url = "{{ url('poinvoiceapproval') }}";
                var create_url = "{{ url('createpoinvoice') }}/0";

                var form = $('#invoiceform');

                if (btnval != 'APPLYCHANGES') {
                    form.parsley().validate();
                    var form = $('#invoiceform');
                    form.parsley().validate();
                    if (form.parsley().isValid()) {

                        var form_data = new FormData(document.getElementById('invoiceform'));
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
                            var edit_url = "{{ url('createpoinvoice') }}/" + id;
                            if (btnval != 'SAVE' && btnval != 'DRAFT' && btnval != 'APPROVED' && btnval != 'REJECTED') {

                                showCustomAlert("SuccessFully", status);
                                setTimeout(function () {
                                    window.location.href = create_url;
                                }, 1500);
                            }
                            else if (btnval == 'APPROVED' || btnval == 'REJECTED') {
                                showCustomAlert("SuccessFully", status);
                                setTimeout(function () {
                                    window.location.href = redd_url;
                                }, 1500);
                            }
                            else {
                                showCustomAlert("SuccessFully", status);
                                setTimeout(function () {
                                    window.location.href = red_url;
                                }, 1500);
                            }
                        });

                    }
                }
                else {

                    var formdata = $('#invoiceform').serialize();
                    var form_data = new FormData(document.getElementById('invoiceform'));
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
                        var edit_url = "{{ url('invoiceDataedit') }}/" + id;
                        showCustomAlert("SuccessFully", status);
                        setTimeout(function () {
                            window.location.href = edit_url;
                        }, 1500);

                    });
                }
            });



            /*Validation*/
            $(document).on('keypress', '.bulk_qty,.extracharge2,.extracharge1,.extracharge4,.extracharge6,.extracharge3,.extracharge5,.bulk_unit_price,.bulk_discount_percentage,.transport_charges,.unloading_charges,.insurance_charges,.packing_charges,.other_freight_amount', function (ev) {
                var regex = new RegExp("^[0-9.]+$");
                var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                if (regex.test(str)) {
                    return true;
                }
                ev.preventDefault();
                return false;
            });
            /*End*/
            /*discount percentage*/
            $('.bulk_discount_percentage').keyup(function () {
                if ($(this).val() > 100) {
                    showCustomAlert("Should not exist more than 100", 'error');
                    $(this).val('');

                }
            });



            $(document).on('click', '.form', function () {
                var btn_val = $(this).val();
                $('.submit_type').val(btn_val);
            });


            $(".suppname_text").html($('.supplier_id option:selected').text());



            $('.suppliersearch').click(function () {
                $('#supplierModal').modal('show');
                $('#supplierModal').width("100%");
            });

            <?php if ($row[0]->supplier_type == 'SUBCONTRACT') { ?>
                $('.bulk_unit_price,.bulk_discount_percentage,.hsn,.taxgrp_div').attr("readonly", false);
                $('.hsn,.taxgrp_div').css('pointer-events', 'auto');
            <?php } ?>
            <?php if ($row[0]->grn_source == 'GRN') { ?>
                $('.bulk_unit_price,.bulk_discount_percentage').attr("readonly", false);
            <?php } ?>
            
            var decimal = "<?php echo \Session('decimal'); ?>";
            $(document).on('keyup change', '.bulk_qty,.bulk_unit_price,.bulk_discount_percentage,.bulk_tax_group_id,.bulk_product_id,.transport_charges, .unloading_charges, .insurance_charges, .packing_charges,.charges,.freight_amount,.other_tax_amount,.other_freight_amount', function () {
                var index = $(this).closest("tr").index();
                var unitprice = $('.bulk_unit_price' + index).val();
                var requiredqty = $('.bulk_qty' + index).val();
                var taxgrp = $('.bulk_tax_group_id' + index + ' option:selected').attr('data-display');
                taxgrp = taxgrp ? taxgrp : 0;
                var discountsperc = $('.bulk_discount_percentage' + index).val();
                var disamout = (((requiredqty * unitprice) * discountsperc / 100));
                $('.bulk_discount_amount' + index).val(disamout);

                var taxamount = ((requiredqty * unitprice) - disamout) * taxgrp / 100;
                $('.bulk_tax_amount' + index).val(taxamount.toFixed(decimal));

                var subtot = ((requiredqty * unitprice) - disamout);
                var linetot = parseFloat(subtot + taxamount).toFixed(decimal);
                $(".bulk_line_total" + index).val(linetot);

                //-----------------------------------------
                /* Code for set linetotal values into header level field*/
                var sum = 0;
                var sumtax = 0;
                var sumall = 0;
                var charge = 0;
                var subtotal = 0;
                var sumwithtds = 0;
                $(".charges").each(function () {
                    charge += +$(this).val();
                });

                $('.bulk_line_total').each(function () {
                    sum += parseFloat($(this).val());
                });
                $('.bulk_tax_amount').each(function () {
                    sumtax += parseFloat($(this).val());
                });
                var tcs_prcnt = $('.tcs_prcnt').val();

                var tcs_amount = (subtotal * (tcs_prcnt / 100));
                var tcs_amount = (isNaN(tcs_amount)) ? 0 : tcs_amount;

                sumall = Number(charge) + Number(sum) + Number(tcs_amount) - Number(sumtax);
                subtotal = Number(sum) + Number(charge) - Number(sumtax);

                $('#invoice_tax_total').val(sumtax);
                $('#invoice_grand_total').val(sumall);

                $(".tax_total_span").html(sumtax);
                $(".grand_total_span").html(sumall.toFixed(2));
                /*TDS*/
                var tds_prcnt = $('.tds_prcnt').val();
                var reverse_charge = $('.reverse_charge:checked').val();
                if (reverse_charge == 1) {
                    if (tds_prcnt != '') {
                        var tds_amount = (subtotal * (tds_prcnt / 100));
                        var rtds_amount = Math.ceil(tds_amount);
                        $('.tds_amount').val(rtds_amount.toFixed(2));
                        sumwithtds = Number(subtotal) - Number(tds_amount);
                        $('#invoice_grand_total').val(sumwithtds.toFixed(2));
                        $(".tax_total_span").html(sumtax.toFixed(2));
                        $(".grand_total_span").html(sumwithtds.toFixed(2));
                    } else {
                        var tds_amount = (subtotal * (tds_prcnt / 100));
                        var rtds_amount = Math.ceil(tds_amount);
                        $('.tds_amount').val(rtds_amount.toFixed(2));
                        sumwithtds = Number(subtotal);
                        $('#invoice_grand_total').val(sumwithtds.toFixed(2));
                        $(".tax_total_span").html(sumtax.toFixed(2));
                        $(".grand_total_span").html(sumwithtds.toFixed(2));
                    }
                }
                else {
                    if (tds_prcnt != '') {
                        var tds_amount = (subtotal * (tds_prcnt / 100));
                        var rtds_amount = Math.ceil(tds_amount);
                        $('.tds_amount').val(rtds_amount.toFixed(2));
                        sumwithtds = Number(sumall) - Number(tds_amount);
                        $('#invoice_grand_total').val(sumwithtds.toFixed(2));
                        $(".tax_total_span").html(sumtax.toFixed(2));
                        $(".grand_total_span").html(sumwithtds.toFixed(2));
                    }
                    else {
                        var tds_amount = (subtotal * (tds_prcnt / 100));
                        var rtds_amount = Math.ceil(tds_amount);
                        $('.tds_amount').val(rtds_amount.toFixed(2));
                        sumwithtds = Number(sumall);
                        $('#invoice_grand_total').val(sumwithtds.toFixed(2));
                        $(".tax_total_span").html(sumtax.toFixed(2));
                        $(".grand_total_span").html(sumwithtds.toFixed(2));
                    }
                }

                /* end */
                /* Code for calculating tot tax amount */
                var tax = 0;
                $('.bulk_tax_amount').each(function () {
                    tax += parseFloat($(this).val());
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
            });
            $(document).on('click', '.delete_user', function () {
                var po_hdr = '{{$po_invoice_id}}';
                if (po_hdr != '') {
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
            /*End File Attachments*/
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



            $(document).on('keyup change', '.round_off', function () {

                var roundoff = parseFloat($(this).val()) || 0;

                // READ ONLY ORIGINAL TOTAL
                var baseTotal = parseFloat($('#base_grand_total').val());
                if (isNaN(baseTotal)) baseTotal = 0;

                var finalTotal = baseTotal + roundoff;

                $('#invoice_grand_total').val(finalTotal.toFixed(2));
                $('.grand_total_span').html(finalTotal.toFixed(2));
            });




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
                    showCustomAlert("Please fill a Amount", 'warning');
                    $('#taxModal').modal('show');
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
                        $('.other_tax_amount_tax').val(tax_group_value);
                    }
                    if (type == "5") {
                        $('.other_freight_amount').val(a_c);
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
                        $('.other_freight_amount').val(0);
                        $('.other_frieght_amount_tax').val('');
                    }
                    if (type == "6") {
                        $('.unloading_charges').val(0);
                        $('.unloading_charges_tax').val('');
                    }
                }
                /* Code for set linetotal values into header level field*/
                var sum = 0;
                var sumtax = 0;
                var sumall = 0;
                var charge = 0;
                var subtotal = 0;
                var sumwithtds = 0;
                $(".charges").each(function () {
                    charge += +$(this).val();
                });

                $('.bulk_line_total').each(function () {
                    sum += parseFloat($(this).val());
                });
                $('.bulk_tax_amount').each(function () {
                    sumtax += parseFloat($(this).val());
                });
                var tcs_prcnt = $('.tcs_prcnt').val();

                var tcs_amount = (subtotal * (tcs_prcnt / 100));
                var tcs_amount = (isNaN(tcs_amount)) ? 0 : tcs_amount;
                sumall = Number(charge) + Number(sum) + Number(tcs_amount);
                subtotal = Number(sum) + Number(charge) - Number(sumtax);
                $('#invoice_tax_total').val(sumtax);
                $('#invoice_grand_total').val(sumall);
                $(".tax_total_span").html(sumtax);
                $(".grand_total_span").html(sumall.toFixed(2));
                /*TDS*/
                var tds_prcnt = $('.tds_prcnt').val();
                var tcs_prcnt = $('.tcs_prcnt').val();

                var tcs_amount = (subtotal * (tcs_prcnt / 100));
                var tcs_amount = (isNaN(tcs_amount)) ? 0 : tcs_amount;

                var reverse_charge = $('.reverse_charge:checked').val();
                if (reverse_charge == 1) {
                    if (tds_prcnt != '') {
                        var tds_amount = (subtotal * (data1 / 100));
                        $('.tds_amount').val(tds_amount.toFixed(2));
                        sumwithtds = Number(subtotal) + Number(tcs_amount) - Number(tds_amount);

                        $('#invoice_grand_total').val(sumwithtds.toFixed(2));

                        $(".tax_total_span").html(sumtax.toFixed(2));
                        $(".grand_total_span").html(sumwithtds.toFixed(2));
                    }
                    else {
                        var tds_amount = (subtotal * (data1 / 100));
                        $('.tds_amount').val(tds_amount.toFixed(2));
                        sumwithtds = Number(subtotal);

                        $('#invoice_grand_total').val(sumwithtds.toFixed(2));

                        $(".tax_total_span").html(sumtax.toFixed(2));
                        $(".grand_total_span").html(sumwithtds.toFixed(2));
                    }
                }
                else {

                    if (tds_prcnt != '') {
                        var tds_amount = (subtotal * (data1 / 100));
                        $('.tds_amount').val(tds_amount.toFixed(2));
                        sumwithtds = Number(sumall) - Number(tds_amount);

                        $('#invoice_grand_total').val(sumwithtds.toFixed(2));

                        $(".tax_total_span").html(sumtax.toFixed(2));
                        $(".grand_total_span").html(sumwithtds.toFixed(2));
                    }
                    else {
                        var tds_amount = (subtotal * (data1 / 100));
                        $('.tds_amount').val(tds_amount.toFixed(2));
                        sumwithtds = Number(sumall);

                        $('#invoice_grand_total').val(sumwithtds.toFixed(2));

                        $(".tax_total_span").html(sumtax.toFixed(2));
                        $(".grand_total_span").html(sumwithtds.toFixed(2));
                    }
                }

            });
            /** end **/
            /* Purpose For TDS Applicable */
            $(".tds_applicable").change(function () {
                var subcontract_id = $(".subcontract_supplier_id").val();
                var supplier_id = $('.supplierid').val();

                var tds = $(".tds_applicable option:selected").val();
                if (tds == "YES") {
                $('.tds_applyling_div').show();
                    if (subcontract_id == "") {
                        var url = "{{ URL::to('loadtds') }}/" + supplier_id + "/" + tds;
                    } else {
                        var url = "{{ URL::to('loadtdssubcontract') }}/" + subcontract_id + "/" + tds;
                    }
                    $.get(url, function (data) {
                        //                 console.log(data);
                        if (data.tds_percentage != "") {
                            $('.tds_prcnt').val(data.tds_percentage);
                            $('.tds_account_id').val(data.tds_account_id).change();
                            var sum = 0;
                            var sumtax = 0;
                            var sumall = 0;
                            var charge = 0;
                            var subtotal = 0;
                            var sumwithtds = 0;
                            $(".charges").each(function () {
                                charge += +$(this).val();
                            });

                            $('.bulk_line_total').each(function () {
                                sum += parseFloat($(this).val());
                            });
                            $('.bulk_tax_amount').each(function () {
                                sumtax += parseFloat($(this).val());
                            });
                            subtotal = Number(sum) - Number(sumtax);
                            var tcs_prcnt = $('.tcs_prcnt').val();

                            var tcs_amount = (subtotal * (tcs_prcnt / 100));
                            var tcs_amount = (isNaN(tcs_amount)) ? 0 : tcs_amount;
                            sumall = Number(charge) + Number(sum) + Number(tcs_amount);



                            $('#invoice_tax_total').val(sumtax);
                            $('#invoice_grand_total').val(sumall);
                            //   $('#balance_amount').val(sumall); 

                            $(".tax_total_span").html(sumtax);
                            $(".grand_total_span").html(sumall.toFixed(2));

                            var data1 = data.tds_percentage
                            var reverse_charge = $('.reverse_charge:checked').val();
                            var tds_prcnt = $('.tds_prcnt').val();

                            if (reverse_charge == 1) {
                                if (tds_prcnt != '') {
                                    var tds_amount = (subtotal * (data1 / 100));
                                    $('.tds_amount').val(tds_amount.toFixed(2));
                                    sumwithtds = Number(subtotal) + Number(tcs_amount) - Number(tds_amount);
                                    // alert(sumwithtds);
                                    $('#invoice_grand_total').val(sumwithtds.toFixed(2));
                                    //  $('#balance_amount').val(sumwithtds.toFixed(2));
                                    $(".tax_total_span").html(sumtax.toFixed(2));
                                    $(".grand_total_span").html(sumwithtds.toFixed(2));
                                }
                                else {
                                    var tds_amount = (subtotal * (data1 / 100));
                                    $('.tds_amount').val(tds_amount.toFixed(2));
                                    sumwithtds = Number(subtotal) + Number(tcs_amount);
                                    // alert(sumwithtds);
                                    $('#invoice_grand_total').val(sumwithtds.toFixed(2));
                                    //  $('#balance_amount').val(sumwithtds.toFixed(2));
                                    $(".tax_total_span").html(sumtax.toFixed(2));
                                    $(".grand_total_span").html(sumwithtds.toFixed(2));
                                }
                            }
                            else {

                                if (tds_prcnt != '') {
                                    var tds_amount = (subtotal * (data1 / 100));
                                    //alert(tds_amount.toFixed(2));
                                    $('.tds_amount').val(tds_amount.toFixed(2));
                                    sumwithtds = Number(sumall) - Number(tds_amount);

                                    $('#invoice_grand_total').val(sumwithtds.toFixed(2));
                                    //  $('#balance_amount').val(sumwithtds.toFixed(2));
                                    $(".tax_total_span").html(sumtax.toFixed(2));
                                    $(".grand_total_span").html(sumwithtds.toFixed(2));
                                }
                                else {
                                    var tds_amount = (subtotal * (data1 / 100));
                                    $('.tds_amount').val(tds_amount.toFixed(2));
                                    sumwithtds = Number(sumall);
                                    $('#invoice_grand_total').val(sumwithtds.toFixed(2));
                                    // $('#balance_amount').val(sumwithtds.toFixed(2));
                                    $(".tax_total_span").html(sumtax.toFixed(2));
                                    $(".grand_total_span").html(sumwithtds.toFixed(2));
                                }
                            }
                        }
                        else {
                            showCustomAlert("TDS Percentage Not Set For This Supplier", 'error');
                        }

                    });
                }
                else {
                    $('.tds_applyling_div').hide();
                    var sum = 0;
                    var sumtax = 0;
                    var sumall = 0;
                    var charge = 0;
                    var subtotal = 0;
                    var sumwithtds = 0;
                    $(".charges").each(function () {
                        charge += +$(this).val();
                    });

                    $('.bulk_line_total').each(function () {
                        sum += parseFloat($(this).val());
                    });
                    $('.bulk_tax_amount').each(function () {
                        sumtax += parseFloat($(this).val());
                    });
                    sumall = Number(charge) + Number(sum);
                    subtotal = Number(sum) - Number(sumtax);

                    var reverse_charge = $('.reverse_charge:checked').val();

                    if (reverse_charge == 1) {
                        $('.tds_prcnt').val('');
                        $('.tds_amount').val('');
                        $('#invoice_tax_total').val(sumtax.toFixed(2));
                        $('#invoice_grand_total').val(subtotal.toFixed(2));
                        //  $('#balance_amount').val(subtotal.toFixed(2)); 
                        $(".tax_total_span").html(sumtax.toFixed(2));
                        $(".grand_total_span").html(subtotal.toFixed(2));
                    }
                    else {
                        $('.tds_prcnt').val('');
                        $('.tds_amount').val('');
                        $('#invoice_tax_total').val(sumtax);
                        $('#invoice_grand_total').val(sumall);
                        //  $('#balance_amount').val(sumall); 
                        $(".tax_total_span").html(sumtax);
                        $(".grand_total_span").html(sumall.toFixed(2));
                    }


                }
            });

            //rohini purpose to create tcs amount
            $(".tcs_applicable").change(function () {
                var subcontract_id = $(".subcontract_supplier_id").val();
                var supplier_id = $('.supplierid').val();

                var tds = $(".tcs_applicable option:selected").val();
                if (tds == "YES") {
                    $('.tcs_applyling_div').show();
                    if (subcontract_id == "") {
                        var url = "{{ URL::to('loadtds') }}/" + supplier_id + "/" + tds;
                    } else {
                        var url = "{{ URL::to('loadtdssubcontract') }}/" + subcontract_id + "/" + tds;
                    }
                    $.get(url, function (data) {
                        //                 console.log(data);
                        if (data.tcs_percentage != "") {
                            $('.tcs_prcnt').val(data.tcs_percentage);
                            $('.tcs_account_id').val(data.tcs_account_id).change();
                            var sum = 0;
                            var sumtax = 0;
                            var sumall = 0;
                            var charge = 0;
                            var subtotal = 0;
                            var sumwithtds = 0;
                            $(".charges").each(function () {
                                charge += +$(this).val();
                            });

                            $('.bulk_line_total').each(function () {
                                sum += parseFloat($(this).val());
                            });
                            $('.bulk_tax_amount').each(function () {
                                sumtax += parseFloat($(this).val());
                            });
                            sumall = Number(charge) + Number(sum);
                            subtotal = Number(sum) - Number(sumtax);
                            // alert(subtotal);
                            $('#invoice_tax_total').val(sumtax);
                            $('#invoice_grand_total').val(sumall);
                            //   $('#balance_amount').val(sumall); 

                            $(".tax_total_span").html(sumtax);
                            $(".grand_total_span").html(sumall.toFixed(2));
                            var tdsamount = $('.tds_amount').val();
                            var tdsamount = (isNaN(tdsamount)) ? 0 : tdsamount;


                            var data1 = data.tcs_percentage
                            var reverse_charge = $('.reverse_charge:checked').val();
                            var tcs_prcnt = $('.tcs_prcnt').val();

                            if (reverse_charge == 1) {
                                if (tcs_prcnt != '') {
                                    var tcs_amount = (sumall * (data1 / 100));
                                    var tcs_amount = (isNaN(tcs_amount)) ? 0 : tcs_amount;

                                    $('.tcs_amount').val(tcs_amount.toFixed(2));
                                    sumwithtds = Number(sumall) + Number(tcs_amount) - Number(tdsamount);
                                    // alert(sumwithtds);
                                    $('#invoice_grand_total').val(sumwithtds.toFixed(2));
                                    //  $('#balance_amount').val(sumwithtds.toFixed(2));
                                    $(".tax_total_span").html(sumtax.toFixed(2));
                                    $(".grand_total_span").html(sumwithtds.toFixed(2));
                                }
                                else {
                                    var tcs_amount = (sumall * (data1 / 100));
                                    var tcs_amount = (isNaN(tcs_amount)) ? 0 : tcs_amount;

                                    $('.tcs_amount').val(tcs_amount.toFixed(2));
                                    sumwithtds = Number(sumall);
                                    // alert(sumwithtds);
                                    $('#invoice_grand_total').val(sumwithtds.toFixed(2));
                                    //  $('#balance_amount').val(sumwithtds.toFixed(2));
                                    $(".tax_total_span").html(sumtax.toFixed(2));
                                    $(".grand_total_span").html(sumwithtds.toFixed(2));
                                }
                            }
                            else {

                                if (tcs_prcnt != '') {

                                    var tcs_amount = (sumall * (data1 / 100));
                                    var tcs_amount = (isNaN(tcs_amount)) ? 0 : tcs_amount;

                                    //alert(tds_amount.toFixed(2));
                                    $('.tcs_amount').val(tcs_amount.toFixed(2));
                                    sumwithtds = Number(sumall) + Number(tcs_amount) - Number(tdsamount);

                                    $('#invoice_grand_total').val(sumwithtds.toFixed(2));
                                    //  $('#balance_amount').val(sumwithtds.toFixed(2));
                                    $(".tax_total_span").html(sumtax.toFixed(2));
                                    $(".grand_total_span").html(sumwithtds.toFixed(2));
                                }
                                else {
                                    var tcs_amount = (sumall * (data1 / 100));
                                    var tcs_amount = (isNaN(tcs_amount)) ? 0 : tcs_amount;

                                    $('.tcs_amount').val(tcs_amount.toFixed(2));
                                    sumwithtds = Number(sumall);
                                    $('#invoice_grand_total').val(sumwithtds.toFixed(2));
                                    // $('#balance_amount').val(sumwithtds.toFixed(2));
                                    $(".tax_total_span").html(sumtax.toFixed(2));
                                    $(".grand_total_span").html(sumwithtds.toFixed(2));
                                }
                            }
                        }
                        else {
                            showCustomAlert("TCS Percentage Not Set For This Supplier", 'error');
                        }

                    });
                }
                else {
                    $('.tcs_applyling_div').hide();
                    var sum = 0;
                    var sumtax = 0;
                    var sumall = 0;
                    var charge = 0;
                    var subtotal = 0;
                    var sumwithtds = 0;
                    $(".charges").each(function () {
                        charge += +$(this).val();
                    });

                    $('.bulk_line_total').each(function () {
                        sum += parseFloat($(this).val());
                    });
                    $('.bulk_tax_amount').each(function () {
                        sumtax += parseFloat($(this).val());
                    });
                    sumall = Number(charge) + Number(sum);
                    subtotal = Number(sum) - Number(sumtax);

                    var reverse_charge = $('.reverse_charge:checked').val();

                    if (reverse_charge == 1) {
                        $('.tcs_prcnt').val('');
                        $('.tcs_amount').val('');
                        $('#invoice_tax_total').val(sumtax.toFixed(2));
                        $('#invoice_grand_total').val(subtotal.toFixed(2));
                        //  $('#balance_amount').val(subtotal.toFixed(2)); 
                        $(".tax_total_span").html(sumtax.toFixed(2));
                        $(".grand_total_span").html(subtotal.toFixed(2));
                    }
                    else {
                        $('.tcs_prcnt').val('');
                        $('.tcs_amount').val('');
                        $('#invoice_tax_total').val(sumtax);
                        $('#invoice_grand_total').val(sumall);
                        //  $('#balance_amount').val(sumall); 
                        $(".tax_total_span").html(sumtax);
                        $(".grand_total_span").html(sumall.toFixed(2));
                    }


                }
            });

            var dateToday = new Date();
            var data = "{{\Session::get('j_date_format')}}";
            var inv_date = $('#invoice_date').val();
            $("#due_date").datepicker({
                changeMonth: true,
                dateFormat: data,
                changeYear: true,
                minDate: inv_date,
                maxDate: +60,
                //maxDate: null,
                onClose: function () {
                    $(this).parsley().validate();
                }

            }).attr('readonly', 'readonly');

            /* Purpose For Reverse Charge Applicable*/
            $(document).on('change', '.reverse_charge', function () {

                var reverse_charge = $(this).is(':checked') ? 1 : 0;

                var sum = 0;
                var sumtax = 0;
                var sumall = 0;
                var charge = 0;
                var subtotal = 0;
                var sumwithtds = 0;

                $(".charges").each(function () {
                    charge += parseFloat($(this).val()) || 0;
                });

                $('.bulk_line_total').each(function () {
                    sum += parseFloat($(this).val()) || 0;
                });

                $('.bulk_tax_amount').each(function () {
                    sumtax += parseFloat($(this).val()) || 0;
                });

                sumall = charge + sum;
                subtotal = sum + charge - sumtax;

                var tcs_prcnt = parseFloat($('.tcs_prcnt').val()) || 0;
                var tcs_amount = (subtotal * tcs_prcnt / 100) || 0;

                var tds_prcnt = parseFloat($('.tds_prcnt').val()) || 0;
                var tds_amount = (subtotal * tds_prcnt / 100) || 0;

                if (reverse_charge === 1) {
                    sumwithtds = subtotal + tcs_amount - tds_amount;
                } else {
                    sumwithtds = sumall + tcs_amount - tds_amount;
                }

                $('.tds_amount').val(tds_amount.toFixed(2));
                $(".tax_total_span").html(sumtax.toFixed(2));
                $(".grand_total_span").html(sumwithtds.toFixed(2));
                $('#invoice_grand_total').val(sumwithtds.toFixed(2));
            });

            /*End*/







    </script>


    @endpush