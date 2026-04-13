@extends('layouts.header')
@section('content')
<h3 class="text-danger">Purchase Order Details</h3>
@include('layouts.breadcrumb')


<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Purchase Order Details</h5>
        <a href="../poinvoiceapproval" class="btn btn-danger btn-sm">
            <i class="bi bi-x-lg"></i>
        </a>
    </div>

    <div class="card-body">
        <!-- Supplier & PO Details -->
        <div class="row g-3">
            <div class="col-md-4">
                <h6 class="text-muted mb-1">Supplier</h6>
                <p class="mb-0"><strong>Name:</strong> {{ $supplier_name }}</p>
                <p class="mb-0"><strong>Site:</strong> {{ $supplier_site_name }}</p>
                <p class="mb-0"><strong>PO No:</strong> <span class="badge bg-info">{{ $po_number }}</span></p>
                <p class="mb-0"><strong>Date:</strong> {{ $po_date }}</p>
                <p class="mb-0"><strong>Type:</strong> {{ $po_type }}</p>
            </div>

            <div class="col-md-4">
                <h6 class="text-muted mb-1">Order Info</h6>
                <p class="mb-0"><strong>Source:</strong> {{ $source }}</p>
                <p class="mb-0"><strong>Reference:</strong> {{ $reference_number }}</p>
                <p class="mb-0"><strong>Status:</strong> <span class="badge bg-success">{{ $po_status }}</span></p>
                <p class="mb-0"><strong>Created By:</strong> {{ $created_by }}</p>
            </div>

            <div class="col-md-4">
                <h6 class="text-muted mb-1">Financials</h6>
                <p class="mb-0"><strong>Delivery Date:</strong> {{ $delivery_date }}</p>
                <p class="mb-0"><strong>Pricelist:</strong> {{ $pricelist_name }}</p>
                <p class="mb-0"><strong>Tax Total:</strong> ₹{{ $po_tax_total }}</p>
                <p class="mb-0"><strong>Grand Total:</strong> ₹{{ $po_grand_total }}</p>
            </div>
        </div>

        <hr>

        <!-- Additional Details -->
        <h6 class="text-muted mt-3">Additional Details</h6>
        <div class="row g-3">
            <div class="col-md-4">
                <p class="mb-0"><strong>Project:</strong> {{ $project_name }}</p>
                <p class="mb-0"><strong>Delivery Term:</strong> {{ $delivery_term_name }}</p>
                <p class="mb-0"><strong>Payment Term:</strong> {{ $payment_term_name }}</p>
                <p class="mb-0"><strong>Payment Method:</strong> {{ $payment_method_name }}</p>
                <p class="mb-0"><strong>Insurance Term:</strong> {{ $insurance_term_name }}</p>
            </div>

            <div class="col-md-4">
                <p class="mb-0"><strong>Freight Carrier:</strong> {{ $carrier_name }}</p>
                <p class="mb-0"><strong>Bill To:</strong> {{ $bill_to_location }}</p>
                <p class="mb-0"><strong>Ship To:</strong> {{ $ship_to_location }}</p>
                <p class="mb-0"><strong>Supplier Ref No:</strong> {{ $supplier_reference_no }}</p>
                <p class="mb-0"><strong>Currency:</strong> {{ $currency }}</p>
            </div>

            <div class="col-md-4">
                <p class="mb-0"><strong>Packing Charges:</strong> ₹{{ $packing_charges }}</p>
                <p class="mb-0"><strong>Insurance Charges:</strong> ₹{{ $insurance_charges }}</p>
                <p class="mb-0"><strong>Unloading Charges:</strong> ₹{{ $unloading_charges }}</p>
                <p class="mb-0"><strong>Transport Charges:</strong> ₹{{ $transport_charges }}</p>
                <p class="mb-0"><strong>Remarks:</strong> {{ $remarks }}</p>
            </div>
        </div>

        <hr>

        <!-- Line Items Table -->
        <h6 class="text-muted mt-3">Order Line Items</h6>
        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Part No</th>
                        <th>Description</th>
                        <th>UOM</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Discount %</th>
                        <th>Discount Amt</th>
                        <th>HSN</th>
                        <th>Tax Group</th>
                        <th>Tax Amt</th>
                        <th>Line Total</th>
                        <th>Promised Date</th>
                        <th>Alternate Date</th>
                        <th>Comments</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vlinesdata as $key=>$value)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $value->concatenated_product }}</td>
                            <td>{{ $value->part_no }}</td>
                            <td>{{ $value->product_description }}</td>
                            <td>{{ $value->uom_code }}</td>
                            <td>{{ $value->qty }}</td>
                            <td>{{ $value->unit_price }}</td>
                            <td>{{ $value->discount_percentage }}</td>
                            <td>{{ $value->discount_amount }}</td>
                            <td>{{ $value->classification_code }}</td>
                            <td>{{ $value->tax_group_name }}</td>
                            <td>{{ $value->tax_amount }}</td>
                            <td>{{ $value->line_total }}</td>
                            <td>{{ \Carbon\Carbon::parse($value->promised_date)->format('d-m-Y') }}</td>
                            <td>{{ $value->promised_alternate_date }}</td>
                            <td>{{ $value->comments }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>




@endsection