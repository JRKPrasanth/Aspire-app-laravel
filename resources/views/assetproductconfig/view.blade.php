@extends('layouts.header')
@section('content')

<form>
    {{ csrf_field() }}

   <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Product Details</h6>
            <a href="../assetproductconfig" class="btn btn-sm btn-danger">Close</a>
        </div>

        <div class="card-body">
            <!-- Product Info -->
            <h6 class="fw-bold mb-3">Basic Information</h6>
            <div class="row mb-3">
                <div class="col-md-4"><b>Product Name:</b> {!! $headerdata->concatenated_product !!}</div>
                <div class="col-md-4"><b>Alternate Name:</b> {!! $headerdata->product_alternate_name !!}</div>
                <div class="col-md-4"><b>Product Code:</b> {!! $headerdata->product_code !!}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><b>Group:</b> {!! $headerdata->group_name !!}</div>
                <div class="col-md-4"><b>Category:</b> {!! $headerdata->category_name !!}</div>
                <div class="col-md-4"><b>Sub Category:</b> {!! $headerdata->subcategory_name !!}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><b>HSN Code:</b> {!! $headerdata->classification_code !!}</div>
                <div class="col-md-4">
                    <b>Active:</b> 
                    <span class="badge {{ $headerdata->active=='Yes' ? 'bg-success' : 'bg-danger' }}">
                        {!! $headerdata->active !!}
                    </span>
                </div>
                <div class="col-md-4">
                    <b>Status:</b> 
                    <span class="badge {{ $headerdata->product_status=='Active' ? 'bg-success' : 'bg-secondary' }}">
                        {!! $headerdata->product_status !!}
                    </span>
                </div>
            </div>

            <!-- Asset Config -->
            <h6 class="fw-bold mt-4">Asset Product Config</h6>
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Asset No</th>
                        <th>Brand</th>
                        <th>Quantity</th>
                        <th>UOM</th>
                        <th>Asset Type</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{!! $headerdata->asset_number !!}</td>
                        <td>{!! $headerdata->brand_name !!}</td>
                        <td>{!! $headerdata->qty !!}</td>
                        <td>{!! $headerdata->uom_code !!}</td>
                        <td>{!! $headerdata->asset_category_name !!}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Serial & Warranty -->
            <h6 class="fw-bold mt-4">Serial & Warranty</h6>
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Serial No</th>
                        <th>Warranty From</th>
                        <th>Warranty (Months)</th>
                        <th>Department</th>
                        <th>Asset Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{!! $headerdata->serial_number !!}</td>
                        <td>{!! $headerdata->warrenty_from !!}</td>
                        <td>{!! $headerdata->warrenty !!}</td>
                        <td>{!! $headerdata->sub_department_name !!}</td>
                        <td>
                            <span class="badge {{ $headerdata->asset_status=='ACTIVE' ? 'bg-success' : 'bg-secondary' }}">
                                {!! $headerdata->asset_status !!}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Purchase Info -->
            <h6 class="fw-bold mt-4">Purchase Information</h6>
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Purchase Date</th>
                        <th>Supplier</th>
                        <th>Replace Date</th>
                        <th>Assigned To</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{!! $headerdata->purchase_date !!}</td>
                        <td>{!! $headerdata->supplier_name !!}</td>
                        <td>{!! $headerdata->replace_date !!}</td>
                        <td>{!! $headerdata->first_name !!}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</form>


@endsection