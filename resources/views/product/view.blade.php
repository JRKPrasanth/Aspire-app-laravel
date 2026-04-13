@extends('layouts.header')
@section('content')
<h3 class="text-danger">Product Details</h3>
@include('layouts.breadcrumb')

<div class="container my-4">
    <div class="card shadow-lg border-0 rounded-3">
        <!-- Header -->
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Product Details</h5>
            <a href="../product" class="btn btn-sm btn-danger">
                <i class="bi bi-x-lg"></i>
            </a>
        </div>

        <!-- Body -->
        <div class="card-body">
            <div class="row g-4">
                <!-- Column 1 -->
                <div class="col-md-4">
                    <div class="list-group list-group-flush small">
                        <div class="list-group-item"><b>Product Group:</b> {{ $group }}</div>
                        <div class="list-group-item"><b>Product Code:</b> {{ $values['product_code'] }}</div>
                        <div class="list-group-item"><b>Batch No:</b> {{ $values['batch_no'] }}</div>
                        <div class="list-group-item"><b>Product Variant:</b> {{ $product_variant_id }}</div>
                        <div class="list-group-item"><b>Product Alternate Name:</b> {{ $values['product_alternate_name'] }}</div>
                        <div class="list-group-item"><b>Default Hsn Code:</b> {{ $defalut_hsn_code }}</div>
                        <div class="list-group-item"><b>Subinventory:</b> {{ $subinv }}</div>
                        <div class="list-group-item"><b>Min Stock Level1:</b> {{ $values['min_order_qty'] }}</div>
                        <div class="list-group-item"><b>Max Stock Level:</b> {{ $values['max_order_qty'] }}</div>
                        <div class="list-group-item"><b>Tax Credit:</b> {{ $values['tax_credit'] }}</div>
                        <div class="list-group-item"><b>MPQ Qty:</b> {{ $values['mpq_qty'] }}</div>

                        @php $row = json_decode($choosefile); @endphp
                        <div class="list-group-item">
                            <b>Attachments:</b>
                            @if(!empty($row))
                                <?php $v = end($row); ?>
                                <a href="{{ URL::to('') }}/uploads/product_image/{{ $product_id }}/{{ $v }}" 
                                   download 
                                   class="btn btn-link p-0 ms-1">
                                    {{ $v }}
                                    <img src="{{ URL::to('') }}/images/download.png" width="18" alt="download">
                                </a>
                            @else
                                <span class="text-muted">No Files</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Column 2 -->
                <div class="col-md-4">
                    <div class="list-group list-group-flush small">
                        <div class="list-group-item"><b>Product Category:</b> {{ $category }}</div>
                        <div class="list-group-item"><b>Product Type:</b> {{ $product_type_id }}</div>
                        <div class="list-group-item"><b>Product Pack:</b> {{ $product_pack_id }}</div>
                        <div class="list-group-item"><b>Trx Uom:</b> {{ $trxuom }}</div>
                        <div class="list-group-item"><b>Hsn Code:</b> {{ $hsn_code }}</div>
                        <div class="list-group-item"><b>Locator Control:</b> {{ $values['locator_control'] }}</div>
                        <div class="list-group-item"><b>Min Stock Level2:</b> {{ $values['min_stock_level2'] }}</div>
                        <div class="list-group-item"><b>Re Order Qty:</b> {{ $values['re_order_level'] }}</div>
                        <div class="list-group-item"><b>Active:</b> {{ $values['active'] }}</div>
                        <div class="list-group-item"><b>Expiry Days:</b> {{ $values['expiry_days'] }}</div>
                        <div class="list-group-item"><b>QC Check:</b> {{ $values['qc_check'] }}</div>
                    </div>
                </div>

                <!-- Column 3 -->
                <div class="col-md-4">
                    <div class="list-group list-group-flush small">
                        <div class="list-group-item"><b>Product Sub Category:</b> {{ $sub_category }}</div>
                        <div class="list-group-item"><b>Product Pack Type:</b> {{ $product_packtype_id }}</div>
                        <div class="list-group-item"><b>Concatenated Product:</b> {{ $values['concatenated_product'] }}</div>
                        <div class="list-group-item"><b>Primary Uom:</b> {{ $primary_uom }}</div>
                        <div class="list-group-item"><b>Account Code:</b> {{ $account_code }}</div>
                        <div class="list-group-item"><b>Control Account Code:</b> {{ $control_account_code }}</div>
                        <div class="list-group-item"><b>Discount Account Code:</b> {{ $disc_account_code }}</div>
                        <div class="list-group-item"><b>Locator Code:</b> {{ $locator }}</div>
                        <div class="list-group-item"><b>Min Stock Level3:</b> {{ $values['min_stock_level3'] }}</div>
                        <div class="list-group-item"><b>QC Type:</b> {{ $values['qc_type'] }}</div>
                        <div class="list-group-item"><b>Created By:</b> {{ $created_by }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection