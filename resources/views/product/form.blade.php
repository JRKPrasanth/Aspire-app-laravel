@extends('layouts.header')
@section('content')
<h3 class="text-danger">Product</h3>
@include('layouts.breadcrumb')

<style>
    .accode .select2-container .select2-selection--single {
        height: auto !important;
        min-height: 38px;
    }

    .accode .select2-container .select2-selection__rendered {
        white-space: normal !important;
        word-break: break-word;
        line-height: 1.4;
    }

    .accode .select2-container--disabled .select2-selection__rendered {
        white-space: normal !important;
    }
</style>


<form action="" method="post" id="productform" class="productform" data-parsley-validate>
    <input type="hidden" value="" name="savestatus" id="savestatus" />
    <input type="hidden" value="" name="linescheck" id="linescheck">
    {{ csrf_field() }}


    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-primary text-white fw-semibold"></div>
        <div class="card-body card-block">


            <div class="row">
                <!-- Column 1 -->
                <div class="col-md-4">
                    <!-- Product Group -->
                    <div class="row mb-3 divread">
                        <label class="col-md-4 col-form-label">
                            <span class="required-star">*</span> Product Group Name
                        </label>
                        <div class="col-md-8">
                            <input type="hidden" name="subassemblydata" class="subassemblydata" id="subassemblydata">
                            <select name="product_group_id" tabindex="1" rows="5"
                                class="form-control product_group_id select2" required>
                                {!! $product_group_id !!}
                            </select>
                        </div>
                    </div>

                    <!-- Product Category -->
                    <div class="row mb-3 divread">
                        <label class="col-md-4 col-form-label">
                            <span class="required-star">*</span> Product Category
                        </label>
                        <div class="col-md-8">
                            <select id="select1" name="product_category_id" rows="5"
                                class="form-control product_category_id select2" required>
                                {!! $product_category_id !!}
                            </select>
                        </div>
                    </div>

                    <!-- Product Sub Category -->
                    <div class="row mb-3 divread">
                        <label class="col-md-4 col-form-label">Product Sub Category</label>
                        <div class="col-md-8">
                            <select name="product_subcategory_id" rows="5"
                                class="form-control product_subcategory_id select2">
                                {!! $product_subcategory_id !!}
                            </select>
                        </div>
                    </div>

                    <!-- Barcode -->
                    <div class="row mb-3 bar divread">
                        <label class="col-md-4 col-form-label">
                            <span class="required-star">*</span> Barcode Number
                        </label>
                        <div class="col-md-8">
                            <input type="text" id="barcode_no" name="barcode_no" class="form-control barcode_no"
                                value="{{ $productdata['barcode_number'] }}">
                        </div>
                    </div>

                    <!-- Product Classification -->
                    <div class="row mb-3 alg divread">
                        <label class="col-md-4 col-form-label">
                            <span class="required-star">*</span> Product Classification
                        </label>
                        <div class="col-md-8">
                            <select name="po_class" id="po_class" class="form-control po_class select2">
                                {!! $product_classification !!}
                            </select>
                        </div>

                    </div>

                    <!-- Product Type -->
                    <div class="row mb-3 pt divread">
                        <label class="col-md-4 col-form-label">
                            <span class="required-star remove">*</span> Product Type
                        </label>
                        <div class="col-md-8">
                            <select name="product_type_id" rows="5" class="form-control product_type_id select2"
                                required>
                                {!! $product_type_id !!}
                            </select>
                        </div>
                    </div>

                    <!-- Product Code -->
                    <div class="fdivold">
                        <div class="row mb-3 fdiv">
                            <label class="col-md-4 col-form-label">
                                <span class="required-star remove">*</span> Product Code
                            </label>
                            <div class="col-md-8">
                                <input type="hidden" class="form-control product_id" id="product_id" name="product_id"
                                    value="{{ $product_id }}" readonly>
                                <input type="text" id="product_code" name="product_code"
                                    class="form-control product_code" value="{{ $productdata['product_code'] }}"
                                    required>
                            </div>
                            <div class="col-md-2"></div>
                        </div>

                        <!-- Product Variant -->
                        <div class="row mb-3 raw pc divread">
                            <label class="col-md-4 col-form-label">
                                <span class="required-star remove">*</span> Product Variant
                            </label>
                            <div class="col-md-8">
                                <select name="product_variant_id" rows="5"
                                    class="form-control product_variant_id select2" data-live-search="true" required>
                                    {!! $product_variant_id !!}
                                </select>
                            </div>
                        </div>

                        <!-- Product Pack Type -->
                        <div class="row mb-3 raw pp divread">
                            <label class="col-md-4 col-form-label">Product Pack Type</label>
                            <div class="col-md-8">
                                <select name="product_packtype_id" rows="5"
                                    class="form-control product_packtype_id select2" data-live-search="true">
                                    {!! $product_packtype_id !!}
                                </select>
                            </div>
                        </div>

                        <!-- Product Pack -->
                        <div class="row mb-3 fdiv raw prdpk semiedit divread">
                            <label class="col-md-4 col-form-label">
                                <span class="required-star">*</span> Product Pack
                            </label>
                            <div class="col-md-8">
                                <select name="product_pack_id" rows="5" class="form-control product_pack_id select2"
                                    data-live-search="true" required>
                                    {!! $product_pack_id !!}
                                </select>
                            </div>
                        </div>

                        <!-- Concatenated Product -->
                        <div class="row mb-3 concatenated_product_app fdiv">
                            <label class="col-md-4 col-form-label">
                                <span class="required-star">*</span> Concatenated Product
                            </label>
                            <div class="col-md-8">
                                <textarea id="concatenated_product" name="concatenated_product"
                                    class="form-control concatenated_product"
                                    required>{{ $productdata['concatenated_product'] }}</textarea>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                    </div>

                    <!-- File Upload -->
                    <div class="row mb-3">
                        <label class="col-md-4 col-form-label" for="customer_id">File Upload</label>
                        <div class="col-md-8">
                            @if($product_id == '')
                            <input id="" class="GetFileSizeNameAndType choosefile" name="choosefile[]" type="file"
                                multiple>
                            <table id="file_choosen" class="table table-bordered table-sm mt-2 mb-0">
                                <tbody id="fp"></tbody>
                            </table>
                            @else
                            <input id="" class="GetFileSizeNameAndType choosefile" name="choosefile[]"
                                type="file" multiple>
                            @if($choosefile != "" && $choosefile != NULL)
                            <?php $dataupload = json_decode($choosefile); ?>
                            <input type="hidden" value="{{ implode(',', $dataupload) }}" name="existing_file"
                                id="existing_file">
                            <table class="table table-bordered table-sm mt-2">
                                <tbody>
                                    @foreach($dataupload as $k => $v)
                                    <tr>
                                        <td class="small">
                                            File {{ $k+1 }}:
                                            <a download
                                                href="{{ URL::to('') }}/uploads/product_image/{{ $product_id }}/{{ $v }}">{{
                                                $v }}</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Column 2 -->

                <div class="col-md-4 stdivold divread">
                    <div class="form-section">
                        <div class="row mb-3 stdiv altname">
                            <label class="col-form-label col-md-4">Product Alternate Name</label>
                            <div class="col-md-8">
                                <input type="text" id="product_alternate_name" tabindex="2"
                                    name="product_alternate_name" class="form-control product_alternate_name"
                                    value="{{ $productdata['product_alternate_name'] }}">
                            </div>
                            <div class="col-md-2"></div>
                        </div>

                        <div class="row mb-3 stdiv">
                            <label class="col-form-label col-md-4"><span class="required-star">*</span> Primary
                                Uom</label>
                            <div class="col-md-8">
                                <select style="width:100%" name="primary_uom_id" rows="5"
                                    class="form-control primary_uom_id select2" data-show-subtext="true"
                                    data-live-search="true" required>
                                    {!! $primary_uom_id !!}
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3 stdiv">
                            <label class="col-form-label col-md-4"><span class="required-star">*</span> Trx Uom</label>
                            <div class="col-md-8">
                                <select style="width:100%" name="trx_uom_id" rows="5"
                                    class="form-control trx_uom_id select2" data-show-subtext="true"
                                    data-live-search="true" required>
                                    {!! $trx_uom_id !!}
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3 stdiv semiedit">
                            <label class="col-form-label col-md-4"><span class="required-star sfgreq">*</span> Hsn
                                Code</label>
                            <div class="col-md-8">
                                <select multiple="multiple" style="width:100%" name="hsn_code[]" rows="5"
                                    class="form-control hsn_code select2" required>
                                    {!! $hsn_code !!}
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3 stdiv semiedit">
                            <label class="col-form-label col-md-4"><span class="required-star sfgreq">*</span> Default
                                Hsn Code</label>
                            <div class="col-md-8">
                                <select style="width:100%" name="defalut_hsn_code" rows="5"
                                    class="form-control defalut_hsn_code select2" required>
                                    {!! $defalut_hsn_code !!}
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3 stdiv accode">
                            <label class="col-form-label col-md-4"><span class="required-star sfgreq">*</span> Account
                                Code</label>
                            <div class="col-md-8">
                                <select style="width:100%" name="account_code_id" rows="5"
                                    class="account_code_id select2">
                                    {!! $account_code_id !!}
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3 stdiv accode">
                            <label class="col-form-label col-md-4"><span class="required-star sfgreq">*</span> Control
                                Account</label>
                            <div class="col-md-8">
                                <select style="width:100%" name="control_account_id" class="control_account_id select2">
                                    {!! $control_account_id !!}
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3 stdiv accode">
                            <label class="col-form-label col-md-4"><span class="required-star sfgreq">*</span> Discount
                                Account Code</label>
                            <div class="col-md-8">
                                <select style="width:100%" name="disc_account_code" rows="5"
                                    class="disc_account_code select2">
                                    {!! $disc_account_code !!}
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3 stdiv">
                            <label class="col-form-label col-md-4"><span class="required-star">*</span>
                                Subinventory</label>
                            <div class="col-md-8">
                                <select style="width:100%" name="subinventory_id" rows="5"
                                    class="form-control subinventory_id select2" required>
                                    {!! $subinventory_id !!}
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3 stdiv locctrl">
                            <label class="col-form-label col-md-4">Locator Control</label>
                            <div class="col-md-8 l-radio d-flex gap-3">
                                <div class="c-radio d-flex align-items-center gap-2">
                                    <input class="form-check-input" id="locator_control_yes" name="locator_control"
                                        type="radio" value="Yes" <?php echo ($productdata['locator_control'] == 'Yes') ? 'checked' : '' ?> />
                                    <label class="form-check-label" for="locator_control_yes">Yes</label>
                                </div>
                                <div class="c-radio d-flex align-items-center gap-2">
                                    <input class="form-check-input" id="locator_control_no" name="locator_control"
                                        type="radio" value="No" <?php echo ($productdata['locator_control'] != 'Yes') ? 'checked' : '' ?> />
                                    <label class="form-check-label" for="locator_control_no">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3 sublocate stdiv">
                            <label class="col-form-label col-md-4"><span class="required-star">*</span> Locator
                                Code</label>
                            <div class="col-md-8">
                                <select style="width:100%" name="sublocator_id" rows="5"
                                    class="form-control sublocator_id select2" data-show-subtext="true"
                                    data-live-search="true" required>
                                    {!! $sublocator_id !!}
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Column 3 -->
                <div class="col-md-4 divread">
                    <div class="form-section">
                        <div class="row mb-3 stdiv semiedit">
                            <label class="col-form-label col-md-4">Min Stock Level1</label>
                            <div class="col-md-8">
                                <input type="text" id="min_order_qty" name="min_order_qty"
                                    class="form-control min_order_qty" value="{{ $productdata['min_order_qty'] }}">
                            </div>
                            <div class="col-md-2"></div>
                        </div>

                        <div class="row mb-3 stdiv semiedit">
                            <label class="col-form-label col-md-4">Min Stock Level2</label>
                            <div class="col-md-8">
                                <input type="text" id="min_stock_level2" name="min_stock_level2"
                                    class="form-control min_stock_level2"
                                    value="{{ $productdata['min_stock_level2'] }}">
                            </div>
                            <div class="col-md-2"></div>
                        </div>

                        <div class="row mb-3 stdiv semiedit">
                            <label class="col-form-label col-md-4">Min Stock Level3</label>
                            <div class="col-md-8">
                                <input type="text" id="min_stock_level3" name="min_stock_level3"
                                    class="form-control min_stock_level3"
                                    value="{{ $productdata['min_stock_level3'] }}">
                            </div>
                            <div class="col-md-2"></div>
                        </div>

                        <div class="row mb-3 stdiv semiedit">
                            <label class="col-form-label col-md-4">Max Stock Level</label>
                            <div class="col-md-8">
                                <input type="text" id="max_order_qty" name="max_order_qty"
                                    class="form-control max_order_qty" value="{{ $productdata['max_order_qty'] }}">
                            </div>
                            <div class="col-md-2"></div>
                        </div>

                        <div class="row mb-3 exp_semi">
                            <label class="col-form-label col-md-4">
                                <span class="required-star remove">*</span> Expiry Days
                            </label>
                            <div class="col-md-8">
                                <input type="text" id="expiry_days_sfg" name="expiry_days_sfg"
                                    class="form-control expiry_days_sfg"
                                    value="{{ $productdata['product_expiry_days'] }}">
                            </div>
                            <div class="col-md-2"></div>
                        </div>

                        <div class="row mb-3 stdiv semiedit">
                            <label class="col-form-label col-md-4">Re Order Qty</label>
                            <div class="col-md-8">
                                <input type="text" id="re_order_level" name="re_order_level"
                                    class="form-control re_order_level" value="{{ $productdata['re_order_level'] }}">
                            </div>
                            <div class="col-md-2"></div>
                        </div>

                        <div class="row mb-3 stdiv semiedit">
                            <label class="col-form-label col-md-4"><span class="required-star">*</span> Tax
                                Credit</label>
                            <div class="col-md-8">
                                <select name="tax_credit" class="form-control tax_credit select2" id="tax_credit"
                                    required>
                                    <option value="">--Please Select--</option>
                                    <option value="Yes" <?php if ($productdata['tax_credit'] == "Yes")
                                        echo "selected"; ?>>Yes
                                    </option>
                                    <option value="No" <?php if ($productdata['tax_credit'] == "No")
                                        echo "selected"; ?>>
                                        No
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2"></div>
                        </div>

                        <div class="row mb-3" style="display:none;">
                            <label class="col-form-label col-md-4"><span class="required-star">*</span> Status</label>
                            <div class="col-md-8">
                                <select name="product_status" class="form-control product_status select2"
                                    id="product_status" required>
                                    <option value="">--Please Select--</option>
                                    <option value="DRAFT" <?php if ($productdata['product_status'] == "DRAFT")
                                        echo "selected"; ?>>DRAFT</option>
                                    <option value="INITIATED" <?php if ($productdata['product_status'] == "INITIATED")
                                        echo "selected"; ?>>INITIATED</option>
                                    <option value="APPROVED" <?php if ($productdata['product_status'] == "APPROVED")
                                        echo "selected"; ?>>APPROVED</option>
                                    <option value="REJECTED" <?php if ($productdata['product_status'] == "REJECTED")
                                        echo "selected"; ?>>REJECTED</option>
                                </select>
                            </div>
                            <div class="col-md-2"></div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-form-label col-md-4"><span class="required-star">*</span> QC Type</label>
                            <div class="col-md-8">
                                <select name="qc_type" class="form-control qc_type select2" id="qc_type" required>
                                    <option value="">--Please Select--</option>
                                    <option value="BATCHWISE" <?php if ($productdata['qc_type'] == "BATCHWISE")
                                        echo "selected"; ?>>BATCHWISE</option>
                                    <option value="SERIALWISE" <?php if ($productdata['qc_type'] == "SERIALWISE")
                                        echo "selected"; ?>>SERIALWISE</option>
                                </select>
                            </div>

                        </div>

                        <div class="row mb-3">
                            <label class="col-form-label col-md-4"><span class="required-star">*</span> QC Check</label>
                            <div class="col-md-8">
                                <select name="qc_check" class="form-control qc_check select2" id="qc_check" required>
                                    <option value="Yes" <?php if ($productdata['qc_check'] == "Yes")
                                        echo "selected"; ?>>
                                        Yes
                                    </option>
                                    <option value="No" <?php if ($productdata['qc_check'] == "No")
                                        echo "selected"; ?>>No
                                    </option>
                                </select>
                            </div>

                        </div>

                        <div class="row mb-3">
                            <label class="col-form-label col-md-4"><span class="required-star">*</span> QC No Of
                                Days</label>
                            <div class="col-md-8">
                                <input type="text" id="qc_no_of_days" name="qc_no_of_days"
                                    class="form-control qc_no_of_days" value="{{ $productdata['qc_no_of_days'] }}">
                            </div>

                        </div>

                        <div class="row mb-3 alg">
                            <label class="col-form-label col-md-4"><span class="required-star">*</span> Group
                                Classification</label>
                            <div class="col-md-8">
                                <select name="group_classification" class="form-control group_classification select2"
                                    id="group_classification" style="width:100% !important;">
                                    {!! $group_classification !!}
                                </select>
                            </div>

                        </div>

                        <div class="row mb-3 bar">
                            <label class="col-form-label col-md-4"><span class="required-star">*</span> Gross
                                Weight</label>
                            <div class="col-md-8">
                                <input type="text" id="gross_weight" name="gross_weight"
                                    class="form-control gross_weight" value="{{ $productdata['gross_weight'] }}">
                            </div>

                        </div>

                        <div class="row mb-3 bar">
                            <label class="col-form-label col-md-4"><span class="required-star">*</span> Net
                                Weight</label>
                            <div class="col-md-8">
                                <input type="text" id="net_weight" name="net_weight" class="form-control net_weight"
                                    value="{{ $productdata['net_weight'] }}">
                            </div>

                        </div>

                        <div class="row mb-3">
                            <label class="col-form-label col-md-4">Active</label>
                            <div class="col-md-8">
                                <select name="active" id="active" rows="5" class="select2">
                                    <option <?php if ($productdata['active'] == "Yes") {
                                        echo "selected";
                                    } ?>
                                        value="Yes">Yes
                                    </option>
                                    <option <?php if ($productdata['active'] == "No") {
                                        echo "selected";
                                    } ?> value="No">
                                        No
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2"></div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-form-label col-md-4">Created By</label>
                            <div class="col-md-8" style="pointer-events:none;">
                                <select name="created_by" rows="5" class="select2 created_by" id="created_by">
                                    {!! $created_by !!}
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3 varg">
                            <label class="col-form-label col-md-4"><span class="required-star">*</span> Product Variant
                                Group</label>
                            <div class="col-md-8">
                                <select style="width:100%" name="product_var_grp" rows="5"
                                    class="form-control product_var_grp select2" data-show-subtext="true"
                                    data-live-search="true">
                                    {!! $pro_varient_grp !!}
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3 finishedit">
                            <label class="col-form-label col-md-4">Exipry Days</label>
                            <div class="col-md-8">
                                <input type="text" id="product_expiry_days" name="product_expiry_days"
                                    class="form-control product_expiry_days" value="{{ $productdata['expiry_days'] }}">
                            </div>
                        </div>

                        <div class="row mb-3 finishedit">
                            <label class="col-form-label col-md-4">MPQ Qty</label>
                            <div class="col-md-8">
                                <input type="text" id="mpq_qty" name="mpq_qty" class="form-control mpq_qty"
                                    value="{{ $productdata['mpq_qty'] }}">
                            </div>
                        </div>

                        <div class="row mb-3 finishedit">
                            <label class="col-form-label col-md-4">Packing Cotton Box</label>
                            <div class="col-md-8">
                                <select name="packing_ctn_box" rows="5" class="select2 packing_ctn_box"
                                    id="packing_ctn_box">
                                    {!! $packing_ctn_box !!}
                                </select>
                            </div>
                        </div>

                    </div>

                </div>
            </div>


            <!--  Code for lines level data-->
            <div class="row linesdiv mt-4">
                <div class="col-md-12">
                    <div id="preview-area" class="table-responsive">
                        <table class="table table-bordered clone_table" style="width: 240% !important;">

                            <thead class="table-light">
                                <tr>
                                    <th>Line No</th>
                                    <th>Product Code </th>
                                    <th class="wip">Product Pack </th>
                                    <th>Concat Product </th>
                                    <th class="semi" style="width: 7%;">HSN Code</th>
                                    <th class="semi">Tax Credit</th>
                                    <th class="semi">Default Hsn Code</th>
                                    <th>Account Code</th>
                                    <th>Control Account</th>
                                    <th>Disc Account code</th>
                                    <th>Primary Uom</th>
                                    <th>Trx Uom</th>
                                    <th>Subinventory</th>
                                    <th>Locator Control</th>
                                    <th>Locator</th>
                                    <th>&nbsp;</th>
                                    <th class="semi">&nbsp;</th>
                                </tr>
                            </thead> <?php //dd($productdata); ?>
                            <tbody class="clone_lines_body">
                                <?php if ($parent_id >= 1) { ?>
                                    @foreach($productdata as $key=>$value)
                                    <tr>

                                    </tr>
                                    @endforeach
                                <?php }
                                if ($parent_id < 1) {
                                    ?>

                                    <tr class="rcopy clone">

                                        <td>
                                            <input type="hidden" name="bulk_product_id[]"
                                                class="form-control input-sm bulk_product_id" value="">
                                            <input type="text" name="bulk_line_no[]"
                                                class="form-control input-sm bulk_line_no" value="1" readonly="readonly">
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_product_code[]"
                                                class="form-control input-sm bulk_product_code">
                                        </td>
                                        <td class="wip wipshow">
                                            <select name='bulk_product_pack_id[]' rows='5'
                                                class='form-control bulk_product_pack_id select2' data-show-subtext="true"
                                                data-live-search="true">
                                                {!!$product_pack_id !!}
                                            </select>

                                        </td>
                                        <td><input type="text" name="bulk_concatenated_product[]"
                                                class="form-control input-sm bulk_concatenated_product" data-value="0"
                                                readonly="readonly">
                                            <input type="hidden" name="bulk_adddetails[]"
                                                class="form-control input-sm bulk_adddetails" data-value=""
                                                readonly="readonly">
                                        </td>
                                        <td class="semi">
                                            <select multiple="multiple" name='bulk_hsn_code[]' rows='5'
                                                class='form-control bulk_hsn_code select2'>
                                                {!!$hsn_code!!}
                                            </select>
                                        </td>
                                        <td class="semi">
                                            <select name='bulk_tax_credit[]' rows='5'
                                                class='form-control bulk_tax_credit select2'>
                                                <option value="">--Please Select--</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </td>


                                        <td class="semi">
                                            <select name='bulk_defalut_hsn_code[]' rows='5'
                                                class='form-control bulk_defalut_hsn_code select2'>
                                                {!!$defalut_hsn_code!!}
                                            </select>
                                        </td>

                                        <td>
                                            <select name='bulk_account_code_id[]' rows='5'
                                                class='form-control bulk_account_code_id select2' id='bulk_account_code_id'>
                                                {!!$account_code_id!!}
                                            </select>
                                        </td>
                                        <td>
                                            <select name='bulk_control_account_id[]' rows='5'
                                                class='form-control bulk_control_account_id select2'
                                                id='bulk_control_account_id'>
                                                {!!$control_account_id!!}
                                            </select>
                                        </td>

                                        <td>
                                            <select name='bulk_disc_account_code[]' rows='5'
                                                class='form-control bulk_disc_account_code select2'
                                                id='bulk_disc_account_code'>
                                                {!!$disc_account_code!!}
                                            </select>
                                        </td>


                                        <td>
                                            <select name='bulk_primary_uom_id[]' rows='5'
                                                class='form-control bulk_primary_uom_id select2' data-show-subtext="true"
                                                data-live-search="true">
                                                {!!$primary_uom_id !!}
                                            </select>
                                        </td>
                                        <td>
                                            <select name='bulk_trx_uom_id[]' rows='5'
                                                class='form-control bulk_trx_uom_id select2' data-show-subtext="true"
                                                data-live-search="true">
                                                {!! $trx_uom_id !!}
                                            </select>
                                        </td>
                                        <td>
                                            <select name='bulk_subinventory_id[]' rows='5'
                                                class='form-control bulk_subinventory_id select2' data-show-subtext="true"
                                                data-live-search="true">
                                                {!!$subinventory_id !!}
                                            </select>
                                        </td>
                                        <td><select name='bulk_locator_control[]' rows='5'
                                                class='form-control bulk_locator_control select2'>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select></td>
                                        <td>
                                            <select name='bulk_sublocator_id[]' rows='5'
                                                class='form-control bulk_sublocator_id select2' data-show-subtext="true"
                                                data-live-search="true">
                                                {!!$sublocator_id!!}
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-danger remove-row">
                                                <i class="fas fa-minus-circle"></i>
                                            </button>
                                        </td>
                                        <td class="semi">
                                            <button type="button" class="btn btn-sm btn-primary addbtn" data-index=""
                                                data-toggle="modal" aria-hidden="true">
                                                <i class="fas fa-plus-circle"></i>
                                            </button>
                                            <input type="hidden" name="counter[]">
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

            <!--pop up -->


            <div class="modal fade" id="prdaddinfo" tabindex="-1" role="dialog" aria-labelledby="prdaddinfoLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document"> <!-- made large for better spacing -->
                    <div class="modal-content">

                        <!-- Header -->
                        <div class="modal-header bg-info text-white">
                            <h5 class="modal-title" id="prdaddinfoLabel">Additional Details</h5>
                            <button type="button" class="close btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <!-- Body -->
                        <div class="modal-body">
                            <input type="hidden" class="popupindex" value="">

                            <div class="row g-3">
                                <!-- Min Stock Level 1 -->
                                <div class="col-md-6">
                                    <label class="form-label">Min Stock Level 1</label>
                                    <input type="text" id="bulk_min_order_qty" name="bulk_min_order_qty[]"
                                        class="form-control">
                                    <input type="hidden" name="additionaldetails[]" class="popupindex">
                                </div>

                                <!-- Max Stock Level -->
                                <div class="col-md-6">
                                    <label class="form-label">Max Stock Level</label>
                                    <input type="text" id="bulk_max_order_qty" name="bulk_max_order_qty[]"
                                        class="form-control">
                                </div>

                                <!-- Min Stock Level 2 -->
                                <div class="col-md-6">
                                    <label class="form-label">Min Stock Level 2</label>
                                    <input type="text" id="bulk_min_stock_level2" name="bulk_min_stock_level2[]"
                                        class="form-control">
                                </div>

                                <!-- MPQ qty -->
                                <div class="col-md-6">
                                    <label class="form-label">MPQ Qty</label>
                                    <input type="text" id="bulk_mpq_qty" name="bulk_mpq_qty[]"
                                        class="form-control bulk_mpq_qty">
                                </div>

                                <!-- Min Stock Level 3 -->
                                <div class="col-md-6">
                                    <label class="form-label">Min Stock Level 3</label>
                                    <input type="text" id="bulk_min_stock_level3" name="bulk_min_stock_level3[]"
                                        class="form-control">
                                </div>

                                <!-- Expiry Days -->
                                <div class="col-md-6">
                                    <label class="form-label">Expiry Days</label>
                                    <input type="text" id="bulk_product_expiry_days" name="bulk_product_expiry_days[]"
                                        class="form-control bulk_product_expiry_days">
                                </div>

                                <!-- Packing Cotton Box -->
                                <div class="col-md-6">
                                    <label class="form-label">Packing Cotton Box</label>
                                    <select id="bulk_packing_ctn_box" name="bulk_packing_ctn_box[]"
                                        class="form-control bulk_packing_ctn_box select2">
                                        {!! $packing_ctn_box !!}
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success px-4 me-1 closeed" data-close="">Save</button>
                            <button type="reset" class="btn btn-secondary px-4">Reset</button>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row butt mt-4 mb-3">
                <div class="col-lg-12 col-md-12">
                    <div class="form-group text-center">

                        <input type="hidden" name="submit_type" class="submit_type" id="submit_type">
                        <?php if ($return_url == "productcreate" || $return_url =='productedit') { ?>
                            <button type="button" id="save" class="btn btn-success px-4 me-2 saveform"
                                value="SAVE">Save</button>
                            <a class='btn btn-danger px-4 me-2'
                                onclick="location.href = '{{URL::to('product')}}'">Cancel</a>
                        <?php } else { ?>
                            <button type="button" name="submit" class="btn btn-success px-4 me-2 saveform"
                                value="APPROVED">Approve</button>
                            <button type="button" name="submit" class="btn btn-danger px-4 me-2 saveform"
                                value="REJECT">Reject</button>
                            <a class='btn btn-outline-danger px-4 me-2'
                                onclick="location.href = '{{URL::to('productapproval')}}'">Cancel</a>

                        <?php } ?>

                    </div>
                </div>
            </div>

        </div>


    </div>

</form>


@endsection
@push('scripts')

<script>

    // barcode and po classification

    $(document).on('change', '.product_group_id', function () {
        var product_group_id = $('.product_group_id option:selected').text();

        if (product_group_id === "FINISHED GOODS") {
            $('.bar, .alg,.varg').show();
            $('.exp_semi').hide();
            $('.po_class,.barcode_no,.group_classification,.gross_weight,.net_weight').prop('required', true);
        } else if (product_group_id === "SEMI FINISHED GOODS") {
            $('.bar').hide();
            $('.barcode_no,.gross_weight,.net_weight').prop('required', false);
            $('.alg, .exp_semi,.varg').show();
            $('.po_class,.group_classification, .expiry_days_sfg').prop('required', true);
        } else {
            $('.bar, .alg, .exp_semi, .varg').hide();
            $('.po_class,.barcode_no,.group_classification,.gross_weight,.net_weight,.expiry_days_sfg').prop('required', false);
        }
    });

    $(document).ready(function () {
        var returnurl = "productedit";
        var product_group_id = $('.product_group_id option:selected').text();

        if (returnurl === "productedit" && product_group_id === "FINISHED GOODS") {
            $('.bar, .alg, .varg').show();
        } else if (returnurl === "productedit" && product_group_id === "SEMI FINISHED GOODS") {
            $('.alg, .varg').show();
            $('.bar').hide();
        } else {
            $('.bar, .alg, .varg').hide();
        }
    });
    // end

    $(document).ready(function () {

        $('.organization_id').css('pointer-events', 'none');
        $('.linesdiv').hide();


        $(document).on('keypress', '#bulk_product_expiry_days', function (ev) {
            var regex = new RegExp("^[0-9.]+$");
            var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
            if (regex.test(str)) {
                return true;
            }
            ev.preventDefault();
            return false;
        });



        $(document).on('keypress', '#bulk_product_expiry_days,#re_order_level,#max_order_qty,#min_order_qty,#bulk_re_order_level,#bulk_mpq_qty,#bulk_min_order_qty,#bulk_max_order_qty,#bulk_min_stock_level3,#bulk_min_stock_level2,#min_stock_level2,#min_stock_level3', function (ev) {
            var regex = new RegExp("^[0-9.]+$");
            var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
            if (regex.test(str)) {
                return true;
            }
            ev.preventDefault();
            return false;
        });

        $("#bulk_product_expiry_days,#re_order_level,#max_order_qty,#min_order_qty,#min_stock_level3,#min_stock_level2").bind("cut copy paste", function (e) {
            e.preventDefault();
        });



        /*Change Function for jcombo Product pack change*/
        $(document).on('change', '.bulk_product_pack_id', function () {

            var dt = $(this).val();
            if (dt != '') {
                var index = ($(this).closest('tr').index());
                var pdtcount = spccheck(dt, index);

                if (pdtcount <= 0) {

                }
                else {
                    var msg = $(".bulk_product_pack_id" + index + ' option:selected').text();
                    var message = '<span style="color:#fdff65">' + msg + '</span>' + ' Product Pack Already Selected';
                    showCustomAlert(message, 'info');
                    $(".bulk_product_pack_id" + index).val('').change();
                }
            }

        });

    

        <?php if ($return_url != "productcreate" && $return_url =='productapproved') { ?>
            $('.divread').css('pointer-events', 'none');
            $('.concatenated_product_app').css('pointer-events', 'auto');
            $('.butt').css('pointer-events', 'auto');
            $('.accode').css('pointer-events', 'auto');

        <?php } ?>



        // Product Group → Category
        $(document).on('change', '.product_group_id', function () {
            var prdgroup = $(this).val();
            if (prdgroup != '') {
                var url = "{{ URL::to('jcomboform') }}?table=m_product_category_t:product_category_id:category_name&order_by=category_name asc&parent=product_group_id=" + prdgroup;

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

                        $('.product_category_id').html('<option value="">-- Select Category --</option>');

                        $.each(data, function (i, item) {
                            $('.product_category_id').append(`<option value="${item.val}">${item.option_name}</option>`);
                        });

                        $('.product_category_id').trigger('change.select2');
                    }
                });
            } else {
                $('.product_category_id').html('<option value="">-- Select Category --</option>').trigger('change.select2');
                $('.product_subcategory_id').html('<option value="">-- Select Subcategory --</option>').trigger('change.select2');
            }
        });


        // Category → Subcategory
        $(document).on('change', '.product_category_id', function () {
            var prdgroup = $('.product_group_id').val();
            var category = $(this).val();

            if (prdgroup == "") {
                notyMsg("info", "Please select product group name");
                $('.product_category_id').val('').trigger('change.select2');
                return;
            }

            if (category != "") {
                var url = "{{ URL::to('jcomboform') }}?table=m_product_subcategory_t:product_subcategory_id:subcategory_name&order_by=subcategory_name asc&parent=product_category_id=" + category;

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

                        $('.product_subcategory_id').html('<option value="">-- Select Subcategory --</option>');

                        $.each(data, function (i, item) {
                            $('.product_subcategory_id').append(`<option value="${item.val}">${item.option_name}</option>`);
                        });

                        $('.product_subcategory_id').trigger('change.select2');
                    }
                });
            } else {
                $('.product_subcategory_id').html('<option value="">-- Select Subcategory --</option>').trigger('change.select2');
            }
        });


        // Subcategory → Accounts
        $(document).on('change', '.product_subcategory_id', function () {
            var group = $('.product_group_id').val();
            var category = $('.product_category_id').val();
            var sub = $(this).val();

            if (sub != null && sub != '') {
                if (category == "") {
                    showCustomAlert("Please select Product Category name", "info");
                    $('.product_subcategory_id').val('').trigger('change.select2');
                    return;
                }

                var url = "{{ URL::to('accountassign') }}?group=" + group + "&category=" + category + "&sub=" + sub;

                $.get(url, function (data) {
                    $('.account_code_id').val(data['acccode_id']).trigger('change.select2');
                    $('.control_account_id').val(data['control_acccode']).trigger('change.select2');
                    $('.disc_account_code').val(data['disc_acccode']).trigger('change.select2');

                    $('.bulk_account_code_id').val(data['acccode_id']).trigger('change.select2');
                    $('.bulk_control_account_id').val(data['control_acccode']).trigger('change.select2');
                    $('.bulk_disc_account_code').val(data['disc_acccode']).trigger('change.select2');
                });
            }
        });

        $(document).on('change', '.subinventory_id', function () {
            var subinv = $(this).val();

            if (subinv != "") {
                var url = "{{ URL::to('jcomboform') }}?table=m_sublocators_t:sublocator_id:locator_code&order_by=locator_code asc&parent=subinventory_id=" + subinv;

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

                        $('.sublocator_id').html('<option value="">-- Select Sublocator --</option>');

                        $.each(data, function (i, item) {
                            $('.sublocator_id').append(`<option value="${item.val}">${item.option_name}</option>`);
                        });

                        $('.sublocator_id').trigger('change.select2');
                    }
                });
            } else {
                $('.sublocator_id').html('<option value="">-- Select Sublocator --</option>').trigger('change.select2');
            }
        });

        /*purpose : load acc,dis account,control acc from account setting */
        $(document).on('change', '.product_subcategory_id', function () {
            var group = $('.product_group_id').select2('val');
            var category = $('.product_category_id').select2('val');
            var sub = $('.product_subcategory_id').select2('val');
            var url = '';
            if (sub != null && sub != '') {
                if (category == "") {
                    showCustomAlert("Please select Product Category name", "info");
                    $('.product_subcategory_id').val('').select2();
                } else {
                    url = "{{URL::to('accountassign')}}?group=" + group + "&category=" + category + "&sub=" + sub;

                    $.get(url, function (data) {
                        $('.account_code_id').select2('val', [data['acccode_id']]);
                        $('.control_account_id').select2('val', [data['control_acccode']]);
                        $('.disc_account_code').select2('val', [data['disc_acccode']]);

                        $('.bulk_account_code_id').select2('val', [data['acccode_id']]);
                        $('.bulk_control_account_id').select2('val', [data['control_acccode']]);
                        $('.bulk_disc_account_code').select2('val', [data['disc_acccode']]);
                    });
                }
            }
        });

        /*  code for concat product create*/
function concatnatproduct($rowOrAll) {
  var product_group_id    = $('.product_group_id option:selected').text();
  var product_category_id = $('.product_category_id option:selected').text();
  var product_type_id     = $('.product_type_id option:selected').text();
  var product_variant_id  = $('.product_variant_id option:selected').text();

  if (!(product_group_id == "FINISHED GOODS" ||
        product_group_id == "SEMI FINISHED GOODS" ||
        product_group_id == "SAMPLE PRODUCTS")) {
    return;
  }

  // normalize type/variant
  if (product_type_id == "-- Please Select --") product_type_id = "";
  if (product_variant_id == "-- Please Select --") product_variant_id = "";

  // spacing rules (keep it simple and safe)
  var typePart    = product_type_id ? product_type_id.trim() : "";
  var variantPart = product_variant_id ? product_variant_id.trim() : "";

  function buildConcat(packText) {
    packText = (packText && packText !== "-- Please Select --") ? packText.trim() : "";
    return [typePart, variantPart, packText].filter(Boolean).join(" ");
  }

  // If passed 'ERP' update all rows, else update one row
  if ($rowOrAll === 'ERP') {
    $('.clone_lines_body tr').each(function () {
      var $row = $(this);
      var packText = $row.find('.bulk_product_pack_id option:selected').text();
      $row.find('.bulk_concatenated_product').val(buildConcat(packText));
    });
  } else {
    var $row = $rowOrAll;
    var packText = $row.find('.bulk_product_pack_id option:selected').text();
    $row.find('.bulk_concatenated_product').val(buildConcat(packText));
  }

  // if you still need this for other places
  $('.subassemblydata').val([typePart, variantPart].filter(Boolean).join(" "));

  // keep your update logic if needed
  var edit_url = '<?php echo $edit_url; ?>';
  if (edit_url == "update") {
    var product_pack_id = $('.product_pack_id option:selected').text();
    var concat = [$('.product_type_id option:selected').text(),
                  $('.product_variant_id option:selected').text(),
                  product_pack_id].filter(Boolean).join(" ");
    $('.concatenated_product').val(concat);
  }
}

$(document).on(
  'change',
  '.product_type_id,.product_variant_id,.product_pack_id,.bulk_product_pack_id',
  function () {
    if ($(this).is('.bulk_product_pack_id')) {
      concatnatproduct($(this).closest('tr')); // update only that row
    } else {
      concatnatproduct('ERP'); // update all rows
    }
  }
);

        /* end */
        <?php if ($edit_url == "create") { ?>
            $('.finishedit').hide();
        <?php } else { ?>
            $('.finishedit').show();
        <?php } ?>
        <?php if (($parent_id) == "0") { ?>

            $('.concatenated_product').attr('data-value', '1');
            $('.bulk_concatenated_product').attr('data-value', '0');

        <?php } else { ?>

            $('.concatenated_product').attr('data-value', '0');
            $('.bulk_concatenated_product').attr('data-value', '1');
        <?php }

        if ($group_name == "RAW MATERIALS") { ?>

            $(".raw,.pt,.pc,.pp,.prdpk").hide();
            $('.linesdiv').css("display", "none");
            $('.concatenated_product').removeAttr("readonly");
            $('.product_code').prop('required', true);
            $('.product_variant_id,.product_packtype_id,.product_pack_id,.bulk_product_pack_id,.bulk_sublocator_id,.product_type_id,.product_variant_id,.product_packtype_id,.product_pack_id').prop('required', false);
            $('#linescheck').val('0');
        <?php }
        if ($group_name == "PACKING MATERIALS") { ?>
            $(".pt,.pc").hide();
            $('.linesdiv').css("display", "none");
            $('.concatenated_product').removeAttr("readonly");
            $('.product_variant_id,.bulk_product_pack_id,.bulk_sublocator_id,.product_type_id,.product_variant_id').prop('required', false);
            $('#linescheck').val('0');

        <?php }
        if ($group_name == "SEMI FINISHED GOODS") { ?>
            $('.semiedit').hide();
            $('.hsn_code,.defalut_hsn_code,.tax_credit,.product_pack_id').attr('required', false);
            $(document).on('change', '.product_category_id', function () {
                var category = $('.product_category_id').select2('val');
                if (category == "SEMI FINISHED") {
                    $('.wip').hide();
                    $('.bulk_product_pack_id').prop('required', false);
                }
                else if (category == "WIP GOODS") {
                    $('.wipshow').show();
                    $('.bulk_product_pack_id').prop('required', true);
                }

            });
        <?php }
        if ($group_name == "SEMI FINISHED GOODS") { ?>
            $('.finishedit').show();
        <?php } else { ?>
            $(".pt,.pc").hide();
            $('.linesdiv').css("display", "none");
            $('.concatenated_product').removeAttr("readonly");
            $('.product_variant_id,.bulk_product_pack_id,.bulk_sublocator_id,.product_type_id,.product_variant_id').prop('required', false);
            $('#linescheck').val('0');
        <?php } ?>
        /*  code for lines level additional details*/

        $(document).on('click', '.addbtn', function () {
            var index = $(this).closest('tr').index();

            $('#prdaddinfo').modal('show');
            $('.popupindex').val(index);

            $('.close').attr('data-close', index);
            $('.closeed').attr('data-close', index);

            if ($('.bulk_adddetails' + index).val() == '') {
                $('#bulk_min_order_qty').val('');
                $('#bulk_min_stock_level3').val('');
                $('#bulk_min_stock_level2').val('');
                $('#bulk_product_expiry_days').val('');

                $('#bulk_max_order_qty').val('');
                $('#bulk_mpq_qty').val('');
                $('#bulk_packing_ctn_box').select2('val', ['']);
            }
            else {
                var explode = $('.bulk_adddetails' + index).val().split(',');

                $('#bulk_min_order_qty').val(explode[0]);
                $('#bulk_min_stock_level2').val(explode[1]);
                $('#bulk_min_stock_level3').val(explode[2]);
                $('#bulk_product_expiry_days').val(explode[3]);

                $('#bulk_max_order_qty').val(explode[4]);
                $('#bulk_mpq_qty').val(explode[5]);
                $('#bulk_packing_ctn_box').val(explode[6]);



            }
        });

        $(document).on('click', '.close,.closeed', function () {

            var index = $(this).attr('data-close');

            var min_order = $('#bulk_min_order_qty').val();
            var min_stocklevel2 = $('#bulk_min_stock_level2').val();
            var min_stocklevel3 = $('#bulk_min_stock_level3').val();
            var expiry_days = $('#bulk_product_expiry_days').val();

            var max_order = $('#bulk_max_order_qty').val();
            var mpq_qty = $('#bulk_mpq_qty').val();

            var packing_box = $('#bulk_packing_ctn_box option:selected').val();

            if (!expiry_days) {
                alert('Expiry Days is mandatory');
                return;
            }

            var con_cat = min_order + ',' + min_stocklevel2 + ',' + min_stocklevel3 + ',' + expiry_days + ',' + max_order + ',' + mpq_qty + ',' + packing_box;

            $('.bulk_adddetails' + index).val(con_cat);

            $('#prdaddinfo').modal('hide');
        });
        /* end */


        $('.concatenated_product').on('keyup', function () {
            this.value = this.value.toUpperCase();
        });



        /* code for rawmaterial and product group based div display*/
        $(document).on('change', '.product_group_id,.product_category_id', function () {
            var product_category_id = $('.product_category_id option:selected').text();
            var product_group_id = $('.product_group_id option:selected').text();
            var product_type_id = $('.product_type_id option:selected').text();
            var product_variant_id = $('.product_variant_id option:selected').text();
            var category = $('.product_category_id option:selected').text();
            var form = $('#productform');
            form.parsley().destroy();
            var product_id = $("#product_id").val();

            if ((product_group_id == "FINISHED GOODS" || product_group_id == "SAMPLE PRODUCTS") && product_id == '') {
                $('.concatenated_product').attr('data-value', '0');
                $('.bulk_concatenated_product').attr('data-value', '1');

                $('.product_pack_id,.sublocator_id,.product_code,.concatenated_product').removeAttr('required');
                $(".fdiv,.stdiv,.accode,.altname").hide();

                $('.pc,.pp,.pt').show();
                $('.linesdiv').show();
                $('#linescheck').val('1');
                $('.product_code,.product_pack_id,.concatenated_product,.tax_credit,.primary_uom_id,.trx_uom_id,.hsn_code,.defalut_hsn_code,.disc_account_code,.account_code_id,.control_account_id,.subinventory_id').prop('required', false);
                $('.bulk_product_code,.bulk_product_pack_id,.bulk_concatenated_product,.bulk_hsn_code,.bulk_defalut_hsn_code,.bulk_account_code_id,.bulk_control_account_id,.bulk_disc_account_code,.bulk_primary_uom_id,.bulk_trx_uom_id,.bulk_tax_credit,.bulk_subinventory_id').prop('required', true);
                $('.semi,.sfgreq').show();
            } else if (product_group_id == "SEMI FINISHED GOODS" && category == "WIP GOODS" && product_id == '') {

                $('.concatenated_product').attr('data-value', '0');
                $('.bulk_concatenated_product').attr('data-value', '1');

                $('.product_pack_id,.sublocator_id,.product_code,.concatenated_product').removeAttr('required');
                $(".fdiv,.stdiv,.accode,.altname").hide();

                $('.pc,.pp,.pt').show();
                $('.linesdiv').show();
                $('#linescheck').val('1');
                $('.product_code,.product_pack_id,.concatenated_product,.tax_credit,.primary_uom_id,.trx_uom_id,.hsn_code,.defalut_hsn_code,.account_code_id,.control_account_id,.disc_account_code,.subinventory_id').prop('required', false);
                $('.bulk_product_code,.bulk_concatenated_product,.bulk_account_code_id,.bulk_control_account_id,.bulk_disc_account_code,.bulk_subinventory_id,.bulk_primary_uom_id,.bulk_trx_uom_id,.product_type_id,.product_variant_id,.product_packtype_id').prop('required', true);
                $('.semi,.sfgreq').hide();

                $('.hsn_code,.defalut_hsn_code,.tax_credit').attr('required', false);
            } else if (product_group_id == "SEMI FINISHED GOODS" && category != "WIP GOODS" && product_id == '') {
                $('.concatenated_product').attr('data-value', '0');
                $('.bulk_concatenated_product').attr('data-value', '1');

                $('.product_pack_id,.sublocator_id,.product_code,.concatenated_product').removeAttr('required');
                $(".fdiv,.stdiv,.accode,.altname").show();

                $('.pc,.pp,.pt').show();
                $('.linesdiv').hide();
                $('#linescheck').val('0');
                $('.product_code,.product_pack_id,.concatenated_product,.tax_credit,.primary_uom_id,.trx_uom_id,.hsn_code,.defalut_hsn_code,.subinventory_id').prop('required', true);
                $('.bulk_product_code,.bulk_concatenated_product,.bulk_account_code_id,.bulk_control_account_id,.bulk_disc_account_code,.bulk_subinventory_id,.bulk_primary_uom_id,.bulk_trx_uom_id,.product_type_id,.product_variant_id,.product_packtype_id,.account_code_id,.control_account_id,.disc_account_code,.hsn_code,.defalut_hsn_code').prop('required', false);
                $('.semi,.sfgreq').hide();
            }
            else if (product_group_id == "RAW MATERIALS" && product_id == '') {
                $(".fdiv,.stdiv,.accode,.altname").show();

                $(".raw,.pt,.pc,.pp,.prdpk").hide();
                $('.linesdiv').hide();
                $('#linescheck').val('0');
                $('.concatenated_product').removeAttr("readonly");

                $('.product_code,.primary_uom_id,.trx_uom_id,.hsn_code,.defalut_hsn_code,.account_code_id,.control_account_id,.disc_account_code,.subinventory_id,.sublocator_id,.tax_credit').attr('required', true);
                $('.bulk_concatenated_product,.bulk_product_pack_id,.bulk_sublocator_id,.bulk_account_code_id,.bulk_control_account_id,.bulk_disc_account_code,.bulk_primary_uom_id,.bulk_trx_uom_id, .bulk_subinventory_id,.product_packtype_id,.product_variant_id,.product_pack_id,.product_type_id,.bulk_hsn_code').attr('required', false);

            }
            else if (product_group_id == "PACKING MATERIALS" && product_id == '') {

                $(".fdiv,.stdiv,.accode,.altname,.pp,.prdpk").show();
                $(".pt,.pc").hide();
                $('.linesdiv').css("display", "none");
                $('.concatenated_product').removeAttr("readonly");
                $('.bulk_concatenated_product,.bulk_product_pack_id,.bulk_sublocator_id,.bulk_account_code_id,.bulk_control_account_id,.bulk_disc_account_code,.bulk_primary_uom_id,.bulk_trx_uom_id, .bulk_subinventory_id,.product_packtype_id,.product_variant_id,.product_pack_id,.product_type_id,.bulk_hsn_code').attr('required', false);
                $('.product_pack_id,.concatenated_product,.primary_uom_id,.trx_uom_id,.hsn_code,.defalut_hsn_code,.account_code_id,.control_account_id,.disc_account_code,.subinventory_id,.sublocator_id,.tax_credit').attr('required', true);
                $('#linescheck').val('0');
            }
            else if (product_id == '') {
                $(".fdiv,.stdiv,.accode,.altname,.pp,.prdpk").show();
                $(".pt,.pc").hide();
                $('.linesdiv').css("display", "none");
                $('.concatenated_product').removeAttr("readonly");
                $('.bulk_concatenated_product,.bulk_product_pack_id,.bulk_sublocator_id,.bulk_account_code_id,.bulk_control_account_id,.bulk_disc_account_code,.bulk_primary_uom_id,.bulk_trx_uom_id, .bulk_subinventory_id,.product_packtype_id,.product_variant_id,.product_pack_id,.product_type_id,.bulk_hsn_code').attr('required', false);
                $('.product_pack_id,.concatenated_product,.primary_uom_id,.trx_uom_id,.hsn_code,.defalut_hsn_code,.account_code_id,.control_account_id,.disc_account_code,.subinventory_id,.sublocator_id,.tax_credit').attr('required', true);
                $('#linescheck').val('0');
            }
        });
        /* end */






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
        $(document).on('click', '.delete_user', function (e) {
            var whichtr = $(this).closest("tr");
            whichtr.remove();
        });

        $(document).on('click', '.delete_user', function () {
            var po_hdr = '{{$product_id}}';
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
            var values = list.join(','); alert(values);
            if (values != '') {
                $('#existing_file').val('');

                $('#existing_file').val(values);
            }
            else {
                $('#existing_file').val('');
            }
        }
        $(document).on('click', '.salesperson_id', function () {
            $(".salesperson_id").jCombo("{{ URL::to('jcomboform?table=s_salesperson_t:salesperson_id:salesperson_name') }}&order_by=salesperson_name asc",
                { selected_value: "" });
        });



        function hsn_validate() {
            var edit_url = '<?php echo $edit_url; ?>';
            var group = $('.product_group_id option:selected').text();

            if (group != "-- Please Select --") {
                var valid = 1;

                if ((group == "FINISHED GOODS" || group == "SAMPLE PRODUCTS") && edit_url == "create") {
                    var valid_arr = [];
                    var index = $('.product_tbl tbody tr').length;
                    for (var i = 0; i < index; i++) {
                        var de_hsn = $('.bulk_defalut_hsn_code' + i).val();
                        var hsn_cd = $('.bulk_hsn_code' + i).val();
                        if ((jQuery.inArray(de_hsn, hsn_cd)) != '-1') {
                            valid_arr.push(0);
                        } else {
                            valid_arr.push(1);
                        }
                    }
                    if ((jQuery.inArray(1, valid_arr)) != '-1') {
                        var valid = 1;
                    }
                    else {
                        var valid = 0;
                    }
                } else {
                    var defa_hsn = $('.defalut_hsn_code').val();
                    var hsn = $('.hsn_code').val();
                    if ((jQuery.inArray(defa_hsn, hsn)) != '-1') {
                        var valid = 0;
                    } else {
                        var valid = 1;
                    }

                }
                return valid;
            }
        }


        function duplicate_check_concatenate(value) {


            var res;
            if (value == 2) {
                $(".product_tbl > tbody >tr").each(function (index) {
                    $tr_id = (index);
                    var value = $('.bulk_concatenated_product' + index).val();
                    if (value != '') {

                        res = 0;
                    }
                    else {

                        res = 1;
                        $('.bulk_concatenated_product' + index).css("border-color", "red");
                    }


                });
                return res;
            }

        }
        /*** other finished **/
        var product_id = "{{$product_id}}";
        function duplicate_check(value) {
            if (value == 1 && product_id == '') {
                var retval;
                var result = {};
                $(".product_tbl > tbody >tr").each(function (index) {
                    $tr_id = (index);
                    var value = $('.bulk_concatenated_product' + index).val();
                    result[index] = value;
                });

                var edit_id = $('.product_id').val();
                var url = "{{URL::to('concatenateproductcheck')}}";
                $.ajax({
                    cache: false,
                    url: url, //this is your uri
                    type: 'GET',
                    dataType: 'json',
                    async: false,
                    data: { result: result, edit_id: edit_id },
                    success: function (response) {
                        if (response['exist'].length > 0) {
                            $.each(response['exist'], function (index, value) {

                                if (jQuery.isEmptyObject(response['inarray']) == false) {

                                    if (jQuery.inArray(value, response['inarray']) != -1) {

                                        $('.bulk_concatenated_product' + index).css("border-color", "red");
                                        retval = 1;
                                    }
                                    else {

                                        $('.bulk_concatenated_product' + index).css("border-color", "white");
                                    }
                                }
                                else {

                                    $.each(response['exist'], function (index, value) {

                                        $('.bulk_concatenated_product' + value).css("border-color", "white");
                                    });
                                    retval = 0;
                                    return true;
                                }

                            });

                        }


                    },
                    error: function (xhr, resp, text) {
                        console.log(xhr, resp, text);
                    }
                });
                return retval;
            }
            else {

                var edit_id = $('.product_id').val();
                var concatenated_product = $('#concatenated_product').val();
                var url = "{{URL::to('concatenateproductchecksingle')}}";

                $.ajax({
                    cache: false,
                    url: url, //this is your uri
                    type: 'GET',
                    dataType: 'json',
                    async: false,
                    data: { concatenated_product: concatenated_product, edit_id: edit_id },
                    success: function (response) {
                        if (response == 1) {

                            retval = 1;


                        }
                        else if (response == 0) {

                            retval = 0;

                        }
                    },
                    error: function (xhr, resp, text) {
                        console.log(xhr, resp, text);
                    }


                });


                return retval;
            }
        }

        function dup_check_product_code(value) {
            if (value == 1 && product_id == '') {
                var retval;
                var result = {};
                $(".product_tbl > tbody >tr").each(function (index) {
                    $tr_id = (index);
                    var value = $('.bulk_product_code' + index).val();
                    result[index] = value;
                });

                var edit_id = $('.product_id').val();
                var url = "{{URL::to('productcodechecklines')}}";
                $.ajax({
                    cache: false,
                    url: url, //this is your uri
                    type: 'GET',
                    dataType: 'json',
                    async: false,
                    data: { result: result, edit_id: edit_id },
                    success: function (response) {
                        if (response['exist'].length > 0) {
                            $.each(response['exist'], function (index, value) {

                                if (jQuery.isEmptyObject(response['inarray']) == false) {

                                    if (jQuery.inArray(value, response['inarray']) != -1) {

                                        $('.bulk_product_code' + index).css("border-color", "red");
                                        retval = 1;
                                    }
                                    else {

                                        $('.bulk_product_code' + index).css("border-color", "white");
                                    }
                                }
                                else {

                                    $.each(response['exist'], function (index, value) {

                                        $('.bulk_product_code' + value).css("border-color", "white");
                                    });
                                    retval = 0;
                                    return true;
                                }

                            });

                        }


                    },
                    error: function (xhr, resp, text) {
                        console.log(xhr, resp, text);
                    }
                });
                return retval;
            }
            else {

                var edit_id = $('.product_id').val();
                var product_code = $('#product_code').val();
                var url = "{{URL::to('productcodecheck')}}";

                $.ajax({
                    cache: false,
                    url: url, //this is your uri
                    type: 'GET',
                    dataType: 'json',
                    async: false,
                    data: { product_code: product_code, edit_id: edit_id },
                    success: function (response) {
                        if (response == 1) {

                            retval = 1;


                        }
                        else if (response == 0) {

                            retval = 0;

                        }
                    },
                    error: function (xhr, resp, text) {
                        console.log(xhr, resp, text);
                    }


                });


                return retval;
            }
        }

        var organization = '<?php echo Session::get('organization'); ?>';
        $('.organization_id').val(organization).change();



        $(document).on('click', '.saveform', function () {

            var product_group_id = $('.product_group_id option:selected').text();
            if (product_group_id == "FINISHED GOODS" || product_group_id == "SEMI FINISHED GOODS" || product_group_id == "SAMPLE PRODUCTS") {
                var dup_chk = duplicate_check(1);
                var check_product_code = 0;
                var check_product_code = dup_check_product_code(1);
            }
            else {

                $(".product_tbl > tbody >tr").each(function (index) {
                    $tr_id = (index);
                    $('.bulk_product_code' + index + ',.bulk_hsn_code0' + index + ',.bulk_tax_credit' + index + ',.bulk_defalut_hsn_code' + index + ',.bulk_account_code_id' + index + ',.bulk_control_account_id' + index + '.bulk_primary_uom_id' + index + '.bulk_trx_uom_id' + index + '.bulk_subinventory_id' + index).removeAttr('required');
                });
                var dup_chk = duplicate_check(2);
                if (dup_chk == 1) {
                    $('#concatenated_product').css("border-color", "red");
                    showCustomAlert("Duplicate entry for concatenated product", "warning");
                }
                else {
                    $('#concatenated_product').css("border-color", "white");
                }

                var check_product_code = dup_check_product_code(2);


            }

            // var dup_chk1 = duplicate_check_concatenate();


            var btnval = $(this).val();
            if (btnval == 'APPLYCHANGES') {
                var savestatus = 'APPLY CHANGES';
                $("#product_status").val("DRAFT").change();


            }

            else if (btnval == 'REJECT') {
                var savestatus = 'REJECTED';
                $("#product_status").val("REJECTED").change();


            } else if (btnval == 'APPROVED') {
                var savestatus = 'APPROVED';

                $("#product_status").val("APPROVED").change();


            } else {
                $("#product_status").val("INITIATED").change();

                var savestatus = 'SAVE';
                $("#product_status").val('INITIATED');

            }

            $('#savestatus').val(savestatus);
            $('.submit_type').val("save");

            var url = "{{ URL::to('productsave') }}";
            var formdata = $('#productform').serialize();
            var form = $('#productform');
            var red_url = "{{ URL::to('product') }}";
            var create_url = "{{ URL::to('product') }}";
            form.parsley().validate();
            var form = $('#productform');
            form.parsley().validate();

            var edit_url = '<?php echo $edit_url; ?>';
            var hsn_valid = hsn_validate();

            var minmax = 0;
            if (edit_url == "create") {

                var group = $('.product_group_id option:selected').text();
                if (group == "SEMI FINISHED GOODS") {
                    hsn_valid = 0;
                }
                if (group == "FINISHED GOODS" || group == "SAMPLE PRODUCTS") {
                    $('.bulk_adddetails').each(function () {
                        var ch = $(this).val();
                        console.log(ch)
                        if (ch == "") {
                            minmax++;
                        } else {

                            var ttt = [];
                            ttt = ch.split(',');
                            $.each(ttt, function (i, v) {

                            });
                        }
                    });

                } else if (group == "SEMI FINISHED GOODS") {
                    minmax = 0;
                }

                if (minmax > 0) {
                    showCustomAlert('Please fill Min and Max product Qty For all Line Level Product!!!', 'warning');
                }

            } else if (edit_url == "update") {
                var group = $('.product_group_id option:selected').text();
                if (group == "SEMI FINISHED GOODS") {
                    hsn_valid = 0;
                }
            }
            if (hsn_valid == 1) {
                showCustomAlert("Please choose default hsn code value in hsn code", "info");
            }

            check_product_code = 0;

            var url = "{{ URL::to('productsave') }}";
            var red_url = "{{ URL::to('product') }}";
            if (btnval != 'APPLYCHANGES' && hsn_valid == 0 && minmax == 0 && dup_chk == 0 && check_product_code == 0) {
                form.parsley().validate();
                var form = $('#productform');

                form.parsley().validate();

                if (form.parsley().isValid()) {

                    var $btn = $(this);            
                    $btn.prop('disabled', true);
                    var formdata = $('#productform').serialize();
                    var form_data = new FormData(document.getElementById('productform'));
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
                        if (btnval != 'SAVE' && btnval != 'APPROVED' && btnval != 'REJECT' && btnval != 'Canceled') {
                            showCustomAlert(msg, status);

                            window.location.href = create_url;
                        } else if (btnval == "APPROVED" || btnval == "REJECT") {
                            showCustomAlert(msg, status);

                            window.location.href = "{{URL::to('productapproval')}}";

                        }
                        else {
                            if (status !== 'success') {
                                showCustomAlert(msg, status);

                            } else {

                                showCustomAlert(msg, status);
                                window.location.href = red_url;
                            }

                        }
                    });
                }
            }
            else {
                if (check_product_code == 1) {
                    showCustomAlert("Product Code aldready exsist", "info");
                }

            }
        });

        /*deepika purpose:set default yes for locator & product control*/
        <?php if ($pagemode == "create") { ?>
            $("#locator_control").attr('checked', true);
        <?php } else { ?>
            var id = $("input[name='locator_control']:checked").val();

            if (id == 'Yes') {
                $('.sublocate').show();
                $('.sublocator_id').attr('required', true);
            }
            else if (id == 'No') {
                $('.sublocate').hide();
                $('.sublocator_id').attr('required', false);
            } else {
                $('.sublocate').hide();
                $('.sublocator_id').attr('required', false);
            }
        <?php } ?>
        /*end*/

        $(document).on('click', '#locator_control', function () {
            var id = $("input[name='locator_control']:checked").val();

            if (id == 'Yes') {
                $('.sublocate').show();
                $('.sublocator_id').attr('required', true);
            }
            else if (id == 'No') {
                $('.sublocate').hide();
                $('.sublocator_id').attr('required', false);

            }
        });




        $(document).on('click', '.addbtn', function () {
            var index = $(this).closest('tr').index();

        });





        /**********Up/down/left/right arrow navigation start*******/
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
        /**********Up/down/left/right arrow navigation end *******/


    });


    function spccheck(sepc_id, index) {

        var pdtcount = 0;
        $(".product_tbl > tbody > tr").each(function (ind) {
            var val = $(".bulk_product_pack_id" + ind).val();
            if (index != ind) {
                if (val == sepc_id) {
                    pdtcount++;
                }
            }
        });
        return pdtcount;
    }

    /*puropose load account code from account settings */
    function accountassign() {
        var acc_code = $('.bulk_account_code_id0').val();
        var cont_code = $('.bulk_control_account_id0').val();
        var dis_code = $('.bulk_disc_account_code0').val();
        $(".product_tbl > tbody > tr").each(function (ind) {
            var acc = $(".bulk_account_code_id" + ind).val();
            var cont = $(".bulk_control_account_id" + ind).val();
            var dis = $(".bulk_disc_account_code" + ind).val();

            if (acc == '') {
                $(".bulk_account_code_id" + ind).select2('val', [acc_code]);
            }
            if (cont == '') {
                $(".bulk_control_account_id" + ind).select2('val', [cont_code]);
            }
            if (cont == '') {
                $(".bulk_disc_account_code" + ind).select2('val', [dis_code]);
            }
        });
    }
    /*end*/
    function changeClassName1(className) {
        var i = 0;
        $('.' + className).each(function (index) {
            $('.bulk_locator_control' + index).select2('val', ['Yes']);
            $(this).removeAttr('name');
            $(this).attr('name', 'bulk_hsn_code' + index + '[]');
            i++;
        });
    }

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


</script>


@endpush