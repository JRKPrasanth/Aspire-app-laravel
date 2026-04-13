@extends('layouts.header')
@section('content')
<?php include('tools_menu.php'); ?>
<h3 class="text-danger">
    <?php if ($return_url == 'supplierapprovalcreate') { ?>
        Supplier Approval
    <?php } else { ?>
        Supplier Create
    <?php } ?>
</h3>
@include('layouts.breadcrumb')



<div class="card shadow-lg rounded-4 border-0">
    <div class="card-body card-block">
        <form method="post" action="{{ URL::to('suppliersave') }}" id="supplierform" enctype="multipart/form-data"
            data-parsley-validate>
            <input type="hidden" value="" name="savestatus" id="savestatus" /> {{ csrf_field()}}

            <!-- Supplier Details Card -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">Supplier Information</div>
                <div class="card-body">
                    <div class="row g-3">
                        <input type="hidden" name="supplier_id" value="{{ $row->supplier_id }}">

                        <div class="col-md-6">
                            <label class="form-label">Supplier Number</label>
                            <input type="text" class="form-control supplier_number" name="supplier_number"
                                value="{{ $row->supplier_number }}" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-danger">* Supplier Name</label>
                            <input type="text" class="form-control supplier_name" name="supplier_name"
                                value="{{ $row->supplier_name }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Alternate Name</label>
                            <input type="text" class="form-control supplier_alternate_name" name="supplier_alternate_name"
                                value="{{ $row->supplier_alternate_name }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-danger">* Supplier Type</label>
                            <div class="d-flex align-items-center">
                                <select class="form-select select2 me-2 supplier_type_id" name="supplier_type_id" required>
                                    {!! $supplier_type_id !!}
                                </select>

                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-danger">* Default Payment Term</label>
                            <div class="d-flex align-items-center">
                                <select class="form-select select2 me-2 default_payment_terms_id" name="default_payment_terms_id" required>
                                    {!! $default_payment_terms_id !!}
                                </select>

                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Freight Term</label>
                            <div class="d-flex align-items-center">
                                <select class="form-select select2 me-2 frieghtterm_id" name="frieghtterm_id">
                                    {!! $frieghtterm_id !!}
                                </select>

                            </div>
                        </div>

                        <div class="col-md-6 attachment_field" style="display: none;">
                            <label class="form-label">Attachment</label>
                            <input type="file" class="form-control attachment" name="attachment">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">Additional Information</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">PAN Number</label>
                            <input type="text" class="form-control pan_number" name="pan_number" maxlength="10"
                                value="{{ $row->pan_number }}">
                            <small class="text-muted">Format: AAAAA9999A</small>
                            <div class="text-danger pan">Please Enter Valid PAN Number</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-danger">* Default Payment Method</label>
                            <div class="d-flex align-items-center">
                                <select class="form-select select2 me-2 default_payment_method_id" name="default_payment_method_id" required>
                                    {!! $default_payment_method_id !!}
                                </select>

                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-danger">* Pricelist Name</label>
                            <div class="d-flex align-items-center">
                                <select class="form-select select2 me-2 default_pricelist_id" name="default_pricelist_id" required>
                                    {!! $default_pricelist_id !!}
                                </select>

                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Delivery Term</label>
                            <div class="d-flex align-items-center">
                                <select class="form-select select2 me-2 delivery_terms_id" name="delivery_terms_id">
                                    {!! $delivery_terms_id !!}
                                </select>

                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Insurance Term</label>
                            <div class="d-flex align-items-center">
                                <select class="form-select select2 me-2 insurance_term_id" name="insurance_term_id">
                                    {!! $insurance_term_id !!}
                                </select>

                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-danger">* Account Structure</label>
                            <div class="d-flex align-items-center">
                                <select class="form-select select2 me-2 account_structure_id" name="account_structure_id" required>
                                    {!! $account_structure_id !!}
                                </select>

                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Active</label>
                            <select class="form-select select2 active" name="active">
                                <option value="Yes" {{ $row->active == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $row->active == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>

                        <div class="col-md-6" style="pointer-events:none;">
                            <label class="form-label">Created By</label>
                            <select class="form-select select2 created_by" name="created_by">
                                {!! $created_by !!}
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Convert Customer to Supplier</label>
                            <div class="d-flex">
                                <div class="form-check me-3">
                                    <input class="form-check-input customer_name" type="radio" name="customer_name" value="Yes" {{
                                        $row->customer_name == 'Yes' ? 'checked' : '' }}>
                                    <label class="form-check-label">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input customer_name" type="radio" name="customer_name" value="No" <?php if( $row->supplier_id== "") echo "checked"; ?> required
                                        <?php if($row->customer_name=="No") echo "checked"; else ''; ?> >

                                    <label class="form-check-label">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 customer mt-3">
                            <label class="form-label">Customer</label>
                            <select name="customer_id" class="form-select select2 customer_id">
                                {!! $customer_id !!}
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-danger">* Freight Carriers</label>
                            <div class="d-flex align-items-center">
                                <select class="form-select select2 me-2 frieghtcarriers_id" name="frieghtcarriers_id" required>
                                    {!! $frieghtcarriers_id !!}
                                </select>

                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-danger">* MSME Enabled Supplier</label>
                            <select name="msme_status" class="form-select select2 msme_status" required>
                                <option value="">--Please Select--</option>
                                <option value="YES" {{ $row->msme_status == 'YES' ? 'selected' : '' }}>YES</option>
                                <option value="NO" {{ $row->msme_status == 'NO' ? 'selected' : '' }}>NO</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>



            <div class="row">
                <div class="col-12 mb-4">
                    <h3 class="my-headers text-primary">Additional Details</h3>
                </div>

                <?php
                $i = 0;
                $j = 0;
                foreach ($enabled_columns as $index => $val) {
                    $required = $val->action == '1' ? 'required' : '';
                    $show_div = $val->active == 1;

                    if (!$show_div)
                        continue;

                    // Default Bank
                    if ($val->column_name == 'default_bank_id') {
                        $i++;
                        ?>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="form-label">
                                    <?php if ($required != '') { ?><span class="text-danger">*</span><?php } ?>
                                    Default Bank
                                </label>
                                <div class="input-group">
                                    <select name="default_bank_id" class="form-select default_bank_id select2" <?= $required ?>>
                                        {!! $default_bank_id !!}
                                    </select>
                                </div>
                            </div>
                        </div>
                    <?php }

                    // TDS Applicable
                    if ($val->column_name == 'tds_applicable') {
                        $i++; ?>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="form-label">
                                    <?php if ($required != '') { ?><span class="text-danger">*</span><?php } ?>
                                    TDS Applicable
                                </label>
                                <select name="tds_applicable" class="form-select tds_applicable select2" <?= $required ?>>
                                    <option value="">--Please Select--</option>
                                    <option value="YES" <?= ($row->tds_applicable == 'YES') ? 'selected' : '' ?>>YES</option>
                                    <option value="NO" <?= ($row->tds_applicable == 'NO') ? 'selected' : '' ?>>NO</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3 tds_per">
                            <label class="form-label">TDS Percentage(%)</label>
                            <div class="input-group">
                                <select name="tds_percentage" class="form-select tds_percentage select2" <?= $required ?>>
                                    {!! $tds_percentage !!}
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3 tds_per">
                            <label class="form-label">TDS Account Code</label>
                            <div class="input-group">
                                <select name="tds_account_id" class="form-select tds_account_id select2" <?= $required ?>>
                                    {!! $tds_account_id !!}
                                </select>
                            </div>
                        </div>
                    <?php }

                    // TCS Applicable
                    if ($val->column_name == 'tcs_applicable') {
                        $i++; ?>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="form-label">
                                    <?php if ($required != '') { ?><span class="text-danger">*</span><?php } ?>
                                    TCS Applicable
                                </label>
                                <select name="tcs_applicable" class="form-select tcs_applicable select2" <?= $required ?>>
                                    <option value="">--Please Select--</option>
                                    <option value="YES" <?= ($row->tcs_applicable == 'YES') ? 'selected' : '' ?>>YES</option>
                                    <option value="NO" <?= ($row->tcs_applicable == 'NO') ? 'selected' : '' ?>>NO</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3 tcs_per">
                            <label class="form-label">TCS Percentage(%)</label>
                            <div class="input-group">
                                <select name="tcs_percentage" class="form-select tcs_percentage select2" <?= $required ?>>
                                    {!! $tcs_percentage !!}
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3 tcs_per">
                            <label class="form-label">TCS Account Code</label>
                            <div class="input-group">
                                <select name="tcs_account_id" class="form-select tcs_account_id select2" <?= $required ?>>
                                    {!! $tcs_account_id !!}
                                </select>

                            </div>
                        </div>
                    <?php }

                    // Supplier Status
                    if ($val->column_name == 'supplier_status') {
                        $i++; ?>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Supplier Status</label>
                            <select name="supplier_status" class="form-select supplier_status select2" <?= $required ?>>
                                <option value="">--Please Select--</option>
                                <option value="ACTIVE" <?= ($row->supplier_status == 'ACTIVE') ? 'selected' : '' ?>>ACTIVE</option>
                                <option value="INACTIVE" <?= ($row->supplier_status == 'INACTIVE') ? 'selected' : '' ?>>INACTIVE
                                </option>
                            </select>
                        </div>
                    <?php }
                } ?>
            </div>



            <div class="row mt-4">
                <div class="col-12 linetable">
                    <div class="table-responsive">
                        <table class="table table-bordered clone_table" style="width: 150% !important;">
                            <thead class="table-light">
                                <tr>
                                    <th>Supplier Site Number</th>
                                    <th>Supplier Site Name </th>
                                    <th>Address </th>
                                    <th>Country</th>
                                    <th>State</th>
                                    <th>City</th>
                                    <th>Pincode</th>
                                    <th>Contact</th>
                                    <th>GST Number</th>
                                    <th>TAN Number</th>
                                    <th>Primary Address</th>
                                    <th>Active</th>
                                    <th style="width: 60px;"></th>
                                </tr>
                            </thead>
                            <tbody class="clone_lines_body">
                                @if(count($linedata) > 0)
                                @foreach($linedata as $key => $value)
                                <tr class="line-row">

                                    <td>
                                        <input type="hidden" name="bulk_supplier_site_id[]"
                                            class="form-control bulk_supplier_site_id "
                                            value="{{$value->supplier_site_id }}" readonly="readonly">

                                        <input type="text" name="bulk_supplier_site_number[]"
                                            class="form-control bulk_supplier_site_number" readonly="readonly"
                                            data-count="{{ $value->supplier_site_number }}"
                                            value="{{ $value->supplier_site_number }}">
                                    </td>
                                    <td class="">
                                        <input type="text" name="bulk_supplier_site_name[]"
                                            class="form-control  bulk_supplier_site_name"
                                            value="{{ $value->supplier_site_name }}" required>
                                    </td>
                                    <td class="">
                                        <input type="text" name="bulk_address[]" class="form-control  bulk_address "
                                            required value="{{ $value->address }}">
                                    </td>
                                    <td class="countrypoint">
                                        <select name="bulk_country[]" class="form-control bulk_country select2"
                                            value="{{$value->country}}">{!! $value->country !!}</select>
                                    </td>
                                    <td class="statepoint">
                                        <select name="bulk_state[]" class="form-control  bulk_state select2"
                                            value="{{$value->state}}">{!! $value->state !!} </select>
                                    </td>
                                    <td class="citypoint">
                                        <select name="bulk_city[]" class="form-control   bulk_city select2"
                                            value="{{$value->city}}">{!! $value->city !!}</select>
                                    </td>
                                    <td class="">
                                        <input type="text" name="bulk_pincode[]"
                                            class="form-control  bulk_pincode input_comments_width "
                                            value="{{ $value->pincode }}" minlength="1" maxlength="6">
                                    </td>
                                    <td class="contactpoint">
                                        <a href="#" class="contactdetail contactpop" title="Add Dispatch Qty"> <i
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
                                    <td class="">
                                        <input type="text" name="bulk_gst_number[]"
                                            class="form-control bulk_gst_number " value="{{ $value->gst_number }}"
                                            minlength="15" maxlength="15">
                                    </td>
                                    <td class="">
                                        <input type="text" name="bulk_tan_no[]" class="form-control bulk_tan_no "
                                            value="{{ $value->tan_no }}">
                                    </td>
                                    <td class="addresspoint">
                                        <select name="bulk_primary_address[]" id="bulk_primary_address"
                                            class="form-control bulk_primary_address select2" required>
                                            <option value="">Please select</option>
                                            <option value="Yes" <?php if ($value->primary_address == 'Yes') {
                                                echo "selected";
                                            } else {
                                                echo "";
                                            } ?>>Yes</option>
                                            <option value="No" <?php if ($value->primary_address == 'No') {
                                                echo "selected";
                                            } else {
                                                echo "";
                                            } ?>>No</option>
                                        </select>
                                    </td>
                                    <td class="activepoint">
                                        <select name="bulk_active[]" class="form-control select2 bulk_active " id="bulk_active">
                                            <option value="Yes" <?php if ($value->active == 'Yes') {
                                                echo "selected";
                                            } ?>>
                                                Yes</option>
                                            <option value="No" <?php if ($value->active == 'No') {
                                                echo "selected";
                                            } ?>>No
                                            </option>
                                        </select>
                                    </td>



                                    <td class="text-center">
										 <input type="hidden" name="counter[]" value="" />
                                        <button type="button" class="btn btn-sm btn-danger remove-row">
                                            <i class="fas fa-minus-circle"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr class="line-row">

                                    <td>
                                        <input type="hidden" name="bulk_supplier_site_id[]"
                                            class="form-control bulk_supplier_site_id " value="" readonly="readonly">

                                        <input type="text" name="bulk_supplier_site_number[]"
                                            class="form-control bulk_supplier_site_number" value="" readonly="readonly"
                                            value="">
                                    </td>
                                    <td>
                                        <input type="text" name="bulk_supplier_site_name[]"
                                            class="form-control  bulk_supplier_site_name " value="" required>
                                    </td>

                                    <td class="">
                                        <input type="text" name="bulk_address[]" class="form-control  bulk_address "
                                            value="" required>
                                    </td>
                                    <td class="sel2">
                                        <select name="bulk_country[]" class="form-control  bulk_country select2"
                                            value="" required>{!! $country_id !!}</select>
                                    </td>
                                    <td class="sel2">
                                        <select name="bulk_state[]" class="form-control  bulk_state select2 " value=""
                                            required>{!! $state_id !!}</select>
                                    </td>
                                    <td class="sel2">
                                        <select name="bulk_city[]" class="form-control   bulk_city select2" value=""
                                            required>{!! $city_id !!}</select>
                                    </td>
                                    <td>
                                        <input type="text" name="bulk_pincode[]"
                                            class="form-control  bulk_pincode input_comments_width " value=""
                                            minlength="1" maxlength="6">
                                    </td>
                                    <td>
                                        <a href="#" class="contactdetail" title="Add Dispatch Qty"> <i
                                                class="fa fa-plus"></i></a>
                                        <input type="hidden" name="bulk_contact_person[]"
                                            class="form-control bulk_contact_person " value="">
                                        <input type="hidden" name="bulk_contact_number[]"
                                            class="form-control bulk_contact_number " value="">
                                        <input type="hidden" name="bulk_contact_mail[]"
                                            class="form-control bulk_contact_mail " value="">
                                    </td>
                                    <td>
                                        <input type="text" name="bulk_gst_number[]" class="form-control bulk_gst_number"
                                            value="" maxlength="15">
                                    </td>
                                    <td>
                                        <input type="text" name="bulk_tan_no[]" class="form-control bulk_tan_no "
                                            value="{{ $value->tan_no }}">
                                    </td>
                                    <td class="sel2">
                                        <select name="bulk_primary_address[]" id="bulk_primary_address"
                                            class="form-control bulk_primary_address select2" required>
                                            <option value="">Please select</option>
                                            <option value="Yes" <?php if ($value->primary_address == 'Yes') {
                                                echo "selected";
                                            } else {
                                                echo "";
                                            } ?>>Yes</option>
                                            <option value="No" <?php if ($value->primary_address == 'No') {
                                                echo "selected";
                                            } else {
                                                echo "";
                                            } ?>>No</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="bulk_active[]" class="form-control select2 bulk_active " id="bulk_active">
                                            <option value="Yes" <?php if ($value->active == 'Yes') {
                                                echo "selected";
                                            } ?>>
                                                Yes</option>
                                            <option value="No" <?php if ($value->active == 'No') {
                                                echo "selected";
                                            } ?>>No
                                            </option>
                                        </select>
                                    </td>

                                    <td class="text-center">
										 <input type="hidden" name="counter[]" value="" />
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




            <input type="hidden" name="site_count" class="site_count" value="{{ $site_count }}" />
            <div class="row">
                <div class="col-lg-12 col-md-12 mt-4">
                    <div class="form-group text-center">

                        @if($row->supplier_id != '' && $return_url != 'supplierapprovalcreate')
                        <button type="button" class="btn btn-success saveform me-2 px-4" value="SAVE">Save</button>
                        <a href="{{ URL::to('supplier') }}" class="btn btn-secondary me-2 px-4">Cancel</a>

                        @elseif($row->supplier_id == '')

                   <!--     <button type="button" class="btn btn-primary applychangesd saveform  px-4 me-2"
                            value="APPLYCHANGES">Draft</button> -->
                        <button type="button" class="btn btn-success saveform  px-4 me-2" value="SAVE">Save</button>
                        <a href="{{ URL::to('supplier') }}" class="btn btn-secondary  px-4 me-2">Cancel</a>

                        @else
                        <button type="button" name="submit" class="btn btn-success saveform approved px-4 me-2"
                            value="APPROVED">Approve</button>
                        <button type="button" name="submit" class="btn btn-danger saveform rejected px-4 me-2"
                            value="REJECT">Reject</button>
                        <a class="btn btn-secondary px-4"
                            onclick="location.href = '{{ URL::to('supplierapproval') }}'">Cancel</a>
                        @endif

                    </div>
                </div>
            </div>

            <!-- purpose for dispatch qty start -->
            <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white" id="contactModalLabel">Contact Details</h5>
                            <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                            <input type="hidden" class="conindex" value="">
                        </div>

                        <!-- Modal Body -->
                        <div class="modal-body">
                            <div id="preview-area" class="chandru">
                                <div class="col-12 linetable">
                                    <div class="table-responsive">
                                        <table class="table table-bordered clone_table1">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Contact Name</th>
                                                    <th>Contact Number</th>
                                                    <th>Email ID</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="clone_lines_body1">
                                                <tr class="line-row">
                                                    <td>
                                                        <input type="text" name="contact_name"
                                                            class="form-control contact_name" placeholder="Name">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="contact_number"
                                                            class="form-control contact_number" minlength="10"
                                                            maxlength="15" placeholder="Phone">
                                                    </td>
                                                    <td>
                                                        <input type="email" name="contact_mail"
                                                            class="form-control contact_mail" placeholder="Email">
                                                    </td>

                                                    <td class="text-center">
														 <input type="hidden" name="counter[]" value="" />
                                                        <button type="button" class="btn btn-sm btn-danger remove-row1">
                                                            <i class="fas fa-minus-circle"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>

                                        </table>
                                    </div>

                                    <div class="text-end">
                                        <button type="button" class="btn btn-primary btn-sm add-row1">
                                            <i class="fas fa-plus-circle"></i> Add
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Footer -->
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary addmasterbox" id="addbox">Add Contact
                                    Details</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!--end-->

        </form>
    </div>
</div>


@endsection
@push('scripts')

<script>

$(document).on('change', '.bulk_country', function () {
    const $row = $(this).closest('tr');
    const bulk_country = $(this).val();

    const $state = $row.find('.bulk_state');
    const $city  = $row.find('.bulk_city');

    // reset state + city for THIS row only
    $state.html('<option value="">-- Loading States --</option>').trigger('change.select2');
    $city.html('<option value="">-- Select City --</option>').trigger('change.select2');

    if (bulk_country) {
        $.ajax({
            url: "{{ url('jcomboformlogin') }}?table=m_states_t:state_id:state_name&parent=country_id=" 
                 + bulk_country + "&order_by=state_name",
            success: function (data) {
                $state.html('<option value="">-- Select State --</option>');
                $.each(data, function (i, item) {
                    $state.append(`<option value="${item.val}">${item.option_name}</option>`);
                });

                // refresh select2 for THIS row only
                $state.trigger('change.select2');
            }
        });
    } else {
        $state.html('<option value="">-- Select State --</option>').trigger('change.select2');
    }
});


$(document).on('change', '.bulk_state', function () {
    const $row = $(this).closest('tr');
    const bulk_state = $(this).val();

    const $city = $row.find('.bulk_city');

    $city.html('<option value="">-- Loading Cities --</option>').trigger('change.select2');

    if (bulk_state) {
        $.ajax({
            url: "{{ url('jcomboformlogin') }}?table=m_cities_t:city_id:city_name&parent=state_id=" 
                 + bulk_state + "&order_by=city_name",
            success: function (data) {
                $city.html('<option value="">-- Select City --</option>');
                $.each(data, function (i, item) {
                    $city.append(`<option value="${item.val}">${item.option_name}</option>`);
                });

                $city.trigger('change.select2');
            }
        });
    } else {
        $city.html('<option value="">-- Select City --</option>').trigger('change.select2');
    }
});


$(function () {
  $('.clone_lines_body select.select2').select2({ width: '100%' });
});

    $(document).on('click', '.add-row', function () {
    const $tbody = $('.clone_lines_body');
    const $lastRow = $tbody.find('tr:last');

    // clone
    const $newRow = $lastRow.clone(false, false);

    // clear inputs in new row (optional)
    $newRow.find('input[type="text"], input[type="hidden"]').val('');
    $newRow.find('select').val('');

    // IMPORTANT: remove select2 container markup if present
    $newRow.find('span.select2').remove();
    $newRow.find('select.select2').removeClass('select2-hidden-accessible').removeAttr('data-select2-id');
    $newRow.find('option').removeAttr('data-select2-id');

    // reset state/city options for new row
    $newRow.find('.bulk_state').html('<option value="">-- Select State --</option>');
    $newRow.find('.bulk_city').html('<option value="">-- Select City --</option>');

    $tbody.append($newRow);

    // init select2 only inside the new row
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
        showCustomAlert("You Can't Delete Atleast One row should be There", "info");
    }
});

// Renumber Line Nos
function updateLineNumbers() {
    $('.clone_lines_body tr').each(function (index) {
        $(this).find('.bulk_line_no').val(index + 1);
    });
}

    // Add Row - for popup

		// Init select2 on page load
$(function () {
  $('.clone_lines_body1').find('select.select2').select2({ width: '100%' });
});

// Add Row
$(document).on('click', '.add-row1', function () {
    const $tbody   = $('.clone_lines_body1');
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
$(document).on('click', '.remove-row1', function () {
    const rowCount = $('.clone_lines_body1 tr').length;
    if (rowCount > 1) {
        $(this).closest('tr').remove();
        updateLineNumbers();
    } else {
        showCustomAlert("You Can't Delete Atleast One row should be There", "warning");
    }
});

// Renumber Line Nos
function updateLineNumbers() {
    $('.clone_lines_body1 tr').each(function (index) {
        $(this).find('.bulk_line_no').val(index + 1);
    });
}



    //  purpose to set primary address validation
    function check_primary_address(index) {

        var bpmadd = $(".bulk_primary_address" + index).val();
        if (bpmadd == "Yes") {
            $(".bulk_primary_address").each(function (i) {
                if (index != i) {
                    if ($(this).val() == "Yes") {
                        $('.bulk_primary_address' + i).val('No').change();
                        $('.bulk_primary_address' + index).val('Yes').change();
                    }
                }
            });
        }
        else {
            var count = 0;
            $(".bulk_primary_address").each(function (i) {
                if (index != i) {
                    if ($(this).val() == "Yes") {
                        count++;
                    }
                }
            });
            if (count <= 0) {
                showCustomAlert("Still There's No Primary Address,Please Select Primary Address...", 'info');
                $('.bulk_primary_address' + index).val('');
                return false;
            }
        }
    }


    $(document).ready(function () {
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

        $('.pan').hide();
        $('select[name="bulk_primary_address[]').addClass('bulk_primary_address');
        $('select[name="bulk_primary_address[]').change(function (e) {
            var index = $(this).closest('tr').index();
            check_primary_address(index);
        });

        $(".bulk_gst_number").keyup(function () {
            this.value = this.value.toUpperCase();
        });

        var siteno = "<?php echo $row->supplier_number; ?>";

        <?php if (isset($used_some)) { ?>
            var rowCount = $('.suppliersite_table tbody tr').length;
            var rowCount1 = $('.suppliersite_table tbody tr').length;


            $(".suppliersite_table>tbody>tr:lt(" + rowCount1 + ") .select2-container").addClass("readonly");
            $("#bulk_primary_address+.select2-container").removeClass("readonly");


            $('.bulk_address,.bulk_supplier_site_name,.bulk_pincode,.bulk_gst_number,.bulk_pincode,.bulk_tan_no').attr('readonly', false);

        <?php } else { ?>
            $('.bulk_address,.bulk_supplier_site_name,.bulk_pincode,.bulk_gst_number,.bulk_pincode,.bulk_tan_no').attr('readonly', false);
            $('.countrypoint,.statepoint,.citypoint,.rempoint,.addresspoint,.activepoint,.contactpoint').css('pointer-events', 'auto');
        <?php } ?>

        var totcount = $('.suppliersite_table tbody tr').length;


        function decimal_num(str, max) {
            str = str.toString();
            return str.length < max ? decimal_num("0" + str, max) : str;
        }

        $(document).on('keypress', '.bulk_pincode,#before_days,#before_dis,#after_days,#after_int', function (ev) {
            var regex = new RegExp("^[0-9.]+$");
            var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
            if (regex.test(str)) {
                return true;
            }
            ev.preventDefault();
            return false;
        });


        /*copy past validation*/

        $('.bulk_contact_person').keypress(function (e) {
            var regex = new RegExp("^[a-zA-Z\s]+$");
            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (regex.test(str)) {
                return true;
            }
            else {
                e.preventDefault();
                return false;
            }
        });

        /*end*/

        $('.contact_number').attr('required', true);

        $(document).on('click', '.contactdetail', function (e) {
          e.preventDefault();
            $('#contactModal').modal('show');
            var index = $(this).closest('tr').index();
            $('.conindex').val(index);
        });
		
$('#contactModal').on('shown.bs.modal', function () {
    var index = $('.conindex').val(); // stored index

    var row = $('.line-row').eq(index); // get the clicked row

    // Get hidden field values and split into arrays
    var co_name = row.find('.bulk_contact_person').val() || '';
    var co_num = row.find('.bulk_contact_number').val() || '';
    var co_mail = row.find('.bulk_contact_mail').val() || '';

    var c_n = co_name.split(',');
    var c_no = co_num.split(',');
    var c_ma = co_mail.split(',');

    // Clear modal body
    $('.clone_lines_body1').html('');

    // Populate modal rows
    for (var i = 0; i < c_n.length; i++) {
        var name = c_n[i] || '';
        var number = c_no[i] || '';
        var email = c_ma[i] || '';

        var html = `
            <tr class="line-row">
                <td><input type="text" name="contact_name" class="form-control contact_name" placeholder="Name" value="${name}"></td>
                <td><input type="text" name="contact_number" class="form-control contact_number" placeholder="Phone" minlength="10" maxlength="15" value="${number}"></td>
                <td><input type="email" name="contact_mail" class="form-control contact_mail" placeholder="Email" value="${email}"></td>
                <td class="text-center"><button type="button" class="btn btn-sm btn-danger remove-row1"><i class="fas fa-minus-circle"></i></button></td>
            </tr>`;
        $('.clone_lines_body1').append(html);
    }
});



		
$('#addbox').click(function () {
    var test = 0;

    // Email validation
    $('.contact_mail').each(function () {
        var currentEmail = $(this).val();
        var expr = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;

        if (!expr.test(currentEmail) && currentEmail !== '') {
            test++;
            showCustomAlert("Enter a valid e-mail address", 'info');
            return false;
        }
    });

    if (test === 0) {
        var con_name = [];
        var con_number = [];
        var con_mail = [];

        $('.contact_name').each(function () {
            con_name.push($(this).val());
        });
        $('.contact_number').each(function () {
            con_number.push($(this).val());
        });
        $('.contact_mail').each(function () {
            con_mail.push($(this).val());
        });

        var index = $('.conindex').val();
        var row = $('.line-row').eq(index);

        // Set the values back as comma-separated strings
        row.find('.bulk_contact_person').val(con_name.join(','));
        row.find('.bulk_contact_number').val(con_number.join(','));
        row.find('.bulk_contact_mail').val(con_mail.join(','));

        $('#contactModal').modal('hide');
    }
});



		
        var show_div = '<?php echo $show_div; ?>';

        if (show_div == 1)
            $('#panel_add').trigger('click');

        $('#savestatus').val('');

	// save function
		
        $(document).on('click', '.saveform', function () {
            var btnval = $(this).val();
            var contact_number = $('.bulk_contact_number').val();

            if (contact_number === "") {
                $('#contactModal').modal('show');
            } else {
                $('#contactModal').modal('hide');
                $('.contact_number').attr('required', false);
            }

            // Determine save status based on button value
            var savestatus = 'INITIATED';
            if (btnval === 'APPLYCHANGES' || btnval === 'DRAFT') {
                savestatus = 'DRAFT';
            } else if (btnval === 'REJECT') {
                savestatus = 'REJECTED';
            } else if (btnval === 'APPROVED') {
                savestatus = 'APPROVED';
            }

            $('#savestatus').val(savestatus);

            var url = "{{ URL::to('suppliersave') }}";
            var red_url = "{{ URL::to('supplier') }}";
            var create_url = "{{ URL::to('suppliercreate') }}/0";

            // Get the form element as a jQuery object
            var form = $('#supplierform').parsley();  // Initialize Parsley on the form

            // Create a new FormData object
            var formData = new FormData($('#supplierform')[0]);
            formData.append('savestatus', savestatus);

            form.validate();
            if (form.isValid()) {
			var $btn = $(this);            
			$btn.prop('disabled', true);
            
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        var status = data.status;
                        var msg = data.message;
                        var id = data.id;
                        var edit_url = "{{ URL::to('suppliercreate') }}/" + id;

                        showCustomAlert(msg, status);

                        if (btnval === 'SAVE') {
                            window.location.href = red_url;
                        } else if (btnval === 'SAVENEW') {
                            window.location.href = create_url;
                        } else if (btnval === "APPROVED" || btnval === "REJECT") {
                            window.location.href = "{{ URL::to('supplierapproval') }}";
                        } else {
                            window.location.href = edit_url;
                        }
                    },
                    error: function (error) {
                        console.error("Error:", error);
                    },
                    complete: function () {
                        $('.ajaxLoading').hide();
                    }
                });
            }
        });



        $(".tds_per").hide();
        $(".tds_applicable").change(function () {
            var tds = $(".tds_applicable option:selected").val();
            if (tds == "YES") {
                $(".tds_per").show();
            } else {
                $(".tds_per").hide();
            }
        });
        $(".tcs_per").hide();

        $(".tcs_applicable").change(function () {
            var tcs = $(".tcs_applicable option:selected").val();
            if (tcs == "YES") {
                $(".tcs_per").show();
            } else {
                $(".tcs_per").hide();
            }
        });

        $(".customer").hide();

        $(".customer_name").click(function () {
            var val = $('.customer_name:checked').val();
            if (val == "Yes") {
                $(".customer").show();

            } else {

                $(".customer").hide();

            }
        });
        <?php if ($row->customer_name == 'Yes') { ?>

            $(".customer").show();
        <?php } else { ?>
            $(".customer").hide();

        <?php } ?>

        var minLength = 10;
        var maxLength = 15;
        $(".contact_number").on("change", function () {
            var value = $(this).val();
            if (value.length < minLength) {
                showCustomAlert("Please Enter Minimium 10 digits ", 'info');
                $(this).val('');
            }
            else if (value.length > maxLength) {
                showCustomAlert("Please Enter 12 to 15 digits Only", 'info');
                $(this).val('');
            }
        });

        /// purpose for PAn no Validation
        $('.pan_number').change(function (event) {

            var regExp = /[a-zA-z]{5}\d{4}[a-zA-Z]{1}/;
            var txtpan = $(this).val();
            if (txtpan.length == 10) {
                if (txtpan.match(regExp)) {
                    $('.pan').hide();
                }
                else {
                    $(".pan_number").val('');
                    $('.pan').show();
                    event.preventDefault();
                }
            }
            else {
                $(".pan_number").val('');
                $('.pan').show();
                event.preventDefault();
            }

        });

        $('.pan_number').on('keyup', function () {
            this.value = this.value.toUpperCase();
        });


        $(document).on('change', '.supplier_type_id', function () {
            var typeid = $(".supplier_type_id option:selected").val();
            console.log(typeid);
            var url = "{{URL::to('suppliertypegst')}}/" + typeid;
            $.get(url, function (data) {
                if (data[0]['gst_required'] == "No") {
                    $('.bulk_gst_number').removeAttr('required');
                    $('.pan_no').removeAttr('required');
                }
                else {
                    $('.bulk_gst_number').attr('required', true);
                    $('.pan_no').attr('required', true);
                }
            });
        });



        $(".supplier_name").keyup(function () {
            this.value = this.value.toUpperCase();
        });

        $(document).on('keyup change', '.bulk_gst_number', function () {

            var gst = $(this).val();
            var index = $(this).closest('tr').index();
            var ids = $('.bulk_supplier_site_id' + index).val();
            var url_print = '{{URL::to("gstduplicate")}}/' + gst;

            $.get(url_print, function (data) {

                if ($.trim(data) == 1) {
                    $('.bulk_gst_number' + index).val("");
                    showCustomAlert("Already Gst Number is Registered ", 'warning');

                }
                else {

                }
            })
        });


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

        $(document).on('click', ".bulk_state", function (e) {
            var state_id = $(this).val();

        });

        //  purpose to customer convert as supplier*/
        function ucwords(str) {
            str = str.toLowerCase().replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g, function (replace_latter) {
                return replace_latter.toUpperCase();
            });  //Can use also /\b[a-z]/g
            return str;  //First letter capital in each word
        }
        $('.customer_id').change(function () {
            var suppliername = $('.customer_id option:selected').text();
            $('.supplier_name').val(suppliername);

            // purpose to get customer details

            var id = $('.customer_id option:selected').val();
            var url_print = '{{URL::to("customerdetailsforsupplier")}}/' + id;
            $.get(url_print, function (data) {
                var gst_no = data['hdr_data'][0].gst_no;
                var billing_address = data['hdr_data'][0].billing_address;
                var pricelist_id = data['hdr_data'][0].pricelist_id;
                var contact_person = data['hdr_data'][0].contact_person;
                var default_payment_terms_id = data['hdr_data'][0].default_payment_terms_id;
                var contact_number = data['hdr_data'][0].contact_number;
                var default_payment_method_id = data['hdr_data'][0].default_payment_method_id;
                var tds_applicable = data['hdr_data'][0].tds_applicable;
                var tds_percentage = data['hdr_data'][0].tds_percentage;
                var default_bank = data['hdr_data'][0].default_bank;
                var ar_frieghtcarriers_hdr_id = data['hdr_data'][0].ar_frieghtcarriers_hdr_id;
                var delivery_terms_id = data['hdr_data'][0].delivery_terms_id;


                $('.gst_no').val(gst_no);
                $('.billing_address').val(billing_address);
                $('.contact_person').val(contact_person);
                $('.contact_number').val(contact_number);
                $('.default_pricelist_id').select2('val', [pricelist_id]);
                $('.default_payment_terms_id').select2('val', [default_payment_terms_id]);
                $('.default_payment_method_id').select2('val', [default_payment_method_id]);
                $('.tds_applicable').select2('val', [tds_applicable]);
                $('.tds_percentage').select2('val', [tds_percentage]);
                $('.default_bank_id').select2('val', [default_bank]);
                $('.frieghtcarriers_id').select2('val', [ar_frieghtcarriers_hdr_id]);
                $('.delivery_terms_id').select2('val', [delivery_terms_id]);

                $('.bulk_supplier_site_name').each(function (index) {
                    if (index != 0) {
                        $($('.bulk_supplier_site_name' + index).closest("tr")).remove();
                    }


                });

                if (data != 0) {
                    $.each(data['line_data'], function (i, v) {
                        if (i != 0) {
                            $('.add_row').trigger('click');
                        }
                        var index = $(".clone").last('tr').index();
                        $('.bulk_supplier_site_name' + index).val([data['line_data'][i].customer_site_name]);
                        $('.bulk_address' + index).val([data['line_data'][i].address]);
                        $('.bulk_country' + index).val([data['line_data'][i].country]).select2();
                        $('.bulk_state' + index).val([data['line_data'][i].state]).select2();
                        $('.bulk_city' + index).val([data['line_data'][i].city]).select2();
                        $('.bulk_pincode' + index).val([data['line_data'][i].pincode]);
                        $('.bulk_contact_person' + index).val([data['line_data'][i].contact_person]);
                        $('.bulk_contact_number' + index).val([data['line_data'][i].contact_number]);
                        $('.bulk_contact_mail' + index).val([data['line_data'][i].contact_mail]);
                        $('.bulk_gst_number' + index).val([data['line_data'][i].gst_no]);
                        $('.bulk_tan_no' + index).val([data['line_data'][i].tan_no]);
                        $('.bulk_primary_address' + index).select2('val', [ucwords(data['line_data'][i].primary_address)]);
                        $('.bulk_active' + index).select2('val', [data['line_data'][i].active]);

                    })
                }

            });

        });


        <?php if ($row->supplier_id != "") { ?>
            $('.tds_applicable').trigger('change');
            $('.tcs_applicable').trigger('change');
            $('.supplier_type_id').trigger('change');
        <?php } ?>

    });


    $(document).ready(function () {
        // Function to toggle the attachment field
        function toggleAttachmentField() {
            if ($('#msme_status').val() === 'YES') {
                $('#attachment_field').show();
            } else {
                $('#attachment_field').hide();
            }
        }

        // Initial call to check the current value on page load
        toggleAttachmentField();

        // Event listener for changes on the msme_status dropdown
        $('#msme_status').on('change', function () {
            toggleAttachmentField();
        });
    });


</script>

@endpush