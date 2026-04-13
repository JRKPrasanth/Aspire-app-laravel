@extends('layouts.header')
@section('content')
<h3 class="text-danger">Depreciation Details</h3>
@include('layouts.breadcrumb')

<div class="container mt-4">

<div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-building-gear"></i> Depreciation Method Details</h5>
            <a href="../depreciationmethod" class="btn btn-sm btn-danger">
                <i class="bi bi-x-circle"></i> Close
            </a>
        </div>

        <div class="card-body">
            <div id="section-to-print">
           

                <div class="row mb-3">
                    <div class="col-md-4">
                        <p><strong>Depreciation Method Name:</strong> {!! $depreciation_method_name !!}</p>
                        <p><strong>Asset Type Name:</strong> {!! $asset_type_name !!}</p>
                        <p><strong>Asset Category Name:</strong> {!! $asset_category_name !!}</p>
                        <p><strong>PO Number:</strong> {!! $po_number !!}</p>
                    </div>

                    <div class="col-md-4 border-start">
                        <p><strong>Product Name:</strong> {!! $concatenated_product !!}</p>
                        <p><strong>Unit Price:</strong> {!! $unit_price !!}</p>
                        <p><strong>Salvage:</strong> {!! $salvage !!}</p>
                        <p><strong>Salvage Percentage:</strong> {!! $salvage_percentage !!}</p>
                    </div>

                    <div class="col-md-4 border-start">
                        <p><strong>Useful Life:</strong> {!! $useful_life !!}</p>
                        <p><strong>Salvage Value:</strong> {!! $salvage_value !!}</p>
                        <p><strong>Depreciable Base:</strong> {!! $depreciable_base !!}</p>
                        <p><strong>Annual Depreciation Expense:</strong> {!! $depreciation_value !!}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


@endsection
