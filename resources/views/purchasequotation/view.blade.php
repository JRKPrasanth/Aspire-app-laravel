@extends('layouts.header')
@section('content')
<h3 class="text-danger">Purchase Quotation Details</h3>
@include('layouts.breadcrumb')

<form>
<div class="card shadow-lg border-0">
    
    <!-- Header -->
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Quotation Details</h5>
        <a class="btn btn-danger btn-sm closeurl" href="{{ $pageModule }}"><i class="bi bi-x-lg"></i> Close</a>
    </div>

    <!-- Body -->
    <div class="card-body">

        <div class="invoice-box" id="section-to-print">
            <!-- Main Information Row -->
            <div class="row mb-4">
                <!-- Column 1 -->
                <div class="col-md-4">
                    <h6 class="fw-bold text-primary mb-3">Supplier Info</h6>
                    <p><b>Supplier Name:</b> {!! $supplier_name !!}</p>
                    <p><b>Supplier Site Name:</b> {!! $supplier_name !!}</p>
                    <p><b>Delivery Date:</b> {!! $delivery_date !!}</p>
                    <p><b>Freight Carriers:</b> {!! $carrier_name !!}</p>
                    <p><b>Price List Name:</b> {!! $pricelist_name !!}</p>

                    @php $row = json_decode($attachements); @endphp
                    @if(!empty($row))
                        <p><b>Attachments:</b></p>
                        @foreach($row as $key => $v)
                            <a class="d-block mb-1" download href="{{URL::to('')}}/Uploads/poquoteattachment/PO{{$row_id}}/{{$v}}">
                                <i class="bi bi-paperclip"></i> {{$v}}
                                <img src='{{URL::to('')}}/images/download.png' height="18" width="18">
                            </a>
                        @endforeach
                    @else
                        <p><b>Attachments:</b> No Files</p>
                    @endif
                </div>

                <!-- Column 2 -->
                <div class="col-md-4">
                    <h6 class="fw-bold text-primary mb-3">Quotation Info</h6>
                    <p><b>Quotation No:</b> {!! $quotation_no !!}</p>
                    <p><b>Quotation Date:</b> {!! $quotation_date !!}</p>
                    <p><b>Quotation Type:</b> {!! $quotation_type !!}</p>
                    <p><b>Quotation Status:</b> {!! $quote_status !!}</p>
                    <p><b>Organization:</b> {!! $organization_name !!}</p>
                </div>

                <!-- Column 3 -->
                <div class="col-md-4">
                    <h6 class="fw-bold text-primary mb-3">Reference Info</h6>
                    <p><b>Source:</b> {!! $source !!}</p>
                    <p><b>Reference No:</b> {!! $reference_number !!}</p>
                    <p><b>Created By:</b> {!! $created_by !!}</p>
                    <p><b>Quote Tax Total:</b> {!! $quote_tax_total !!}</p>
                    <p><b>Quote Grand Total:</b> {!! $quote_grand_total !!}</p>
                </div>
            </div>

            <!-- Additional Details -->
            <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">Additional Details</h6>
            <div class="row mb-4">
                <!-- Col 1 -->
                <div class="col-md-4">
                    <p><b>Payment Method:</b> {!! $payment_method_name !!}</p>
                    <p><b>Payment Term:</b> {!! $payment_term_name !!}</p>
                    <p><b>Supplier Quote Date:</b> {!! $supplier_quotation_date !!}</p>
                    <p><b>Delivery Term:</b> {!! $delivery_term_name !!}</p>
                    <p><b>Project Name:</b> {!! $project_name !!}</p>
                    <p><b>Bill to Address:</b><br> {!! $bill_to_address !!}</p>
                    <p><b>Ship to Address:</b><br> {!! $ship_to_address !!}</p>
                </div>

                <!-- Col 2 -->
                <div class="col-md-4">
                    <p><b>Supplier Ref No:</b> {!! $supplier_ref_no !!}</p>
                    <p><b>Insurance Term:</b> {!! $insurance_term_name !!}</p>
                    <p><b>Bill To Location:</b> {!! $bill_to_location !!}</p>
                    <p><b>Ship To Location:</b> {!! $ship_to_location !!}</p>
                    <p><b>Remarks:</b> {!! $remarks !!}</p>
                </div>

                <!-- Col 3 -->
                <div class="col-md-4">
                    <p><b>Packing Charges:</b> {!! $packing_charges !!}</p>
                    <p><b>Insurance Charges:</b> {!! $insurance_charges !!}</p>
                    <p><b>Unloading Charges:</b> {!! $unloading_charges !!}</p>
                    <p><b>Transport Charges:</b> {!! $transport_charges !!}</p>
                    <p><b>Other Freight Amount:</b> {!! $other_frieght_amount !!}</p>
                    <p><b>Other Tax Amount:</b> {!! $other_tax_amount !!}</p>
                </div>
            </div>

            <!-- Line Items -->
            <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">Line Items</h6>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Line No</th>
                            <th>Product Name</th>
                            <th>Part Number</th>
                            <th>Description</th>
                            <th>UOM</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Discount (%)</th>
                            <th>Discount Amt</th>
                            <th>HSN Code</th>
                            <th>Tax Group</th>
                            <th>Tax Amt</th>
                            <th>Line Total</th>
                            <th>Promised Date</th>
                            <th>Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vlinesdata as $key=>$value)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $value->product_code}} - {{ $value->concatenated_product}}</td>
                            <td>{{ $value->part_no}}</td>
                            <td>{{ $value->product_description}}</td>
                            <td>{{ $value->uom_code}}</td>
                            <td>{{ $value->qty}}</td>
                            <td>{{ $value->unit_price}}</td>
                            <td>{{ $value->discount_percentage}}</td>
                            <td>{{ $value->discount_amount}}</td>
                            <td>{{ $value->classification_code}}</td>
                            <td>{{ $value->tax_group_name}}</td>
                            <td>{{ $value->tax_amount}}</td>
                            <td>{{ $value->line_total}}</td>
                            <td>
                                @if($value->promised_date != "0000-00-00")
                                    {{ date(\Session::get('p_date_format'), strtotime($value->promised_date)) }}
                                @endif
                            </td>
                            <td>{{ $value->comments}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

    </div>
</div>
</form>







@endsection
