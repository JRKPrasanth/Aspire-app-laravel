@extends('layouts.header')
@section('content')
<h3 class="text-danger">Purchase Return Details</h3>
@include('layouts.breadcrumb')




	<form>
    <div class="card shadow-sm border-0">
        <!-- Card Header -->
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <?php if($url=="purchasereturnapproval") { echo "Purchase Return Approval Details"; }
                else { echo "Purchase Return Details"; } ?>
            </h5>
            <a href="{{URL::to($url)}}" class="btn btn-danger btn-sm closeurl">
                <i class="bi bi-x-circle"></i>
            </a>
        </div>

        <!-- Card Body -->
        <div class="card-body">
            <div class="invoice-box" id="section-to-print">
                <!-- Header Details -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p><strong>Return Invoice Number:</strong> {!! $row[0]->return_invoice_number !!}</p>
                        <p><strong>Return Date:</strong> {!! $row[0]->return_date !!}</p>
                        <p><strong>Return Status:</strong> {!! $row[0]->return_status !!}</p>
                        <p><strong>GRN Number:</strong> {!! $row[0]->grn_number !!}</p>
                        <p><strong>Bill Number:</strong> {!! $row[0]->bill_number !!}</p>
                        <p><strong>Supplier Name:</strong> {!! $supplier_id !!}</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p><strong>PO Number:</strong> {!! $row[0]->po_number !!}</p>
                        <p><strong>QC Number:</strong> {!! $row[0]->qc_number !!}</p>
                        <p><strong>PO Date:</strong> {!! $row[0]->po_date !!}</p>
                        <p><strong>PO Grand Total:</strong> {!! $row[0]->po_grand_total !!}</p>
                        <p><strong>PO Tax Total:</strong> {!! $row[0]->po_tax_total !!}</p>
                    </div>
                </div>

                <!-- Line Items Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Line No</th>
                                <th>Product Name</th>
                                <th>UOM Code</th>
                                <th>Rejected Qty</th>
                                <th>Price</th>
                                <th>Discount (%)</th>
                                <th>Discount Amount</th>
                                <th>Tax Group</th>
                                <th>Tax Amount</th>
                                <th>Line Total</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($linedata as $key=>$value)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $value->product_id }}</td>
                                <td>{{ $value->uom_code_id }}</td>
                                <td>{{ $value->reject_qty }}</td>
                                <td>{{ $value->unit_price }}</td>
                                <td>{{ $value->discount_percentage }}</td>
                                <td>{{ $value->discount_amount }}</td>
                                <td>{{ $value->tax_group_id }}</td>
                                <td>{{ $value->tax_amount }}</td>
                                <td>{{ $value->line_total }}</td>
                                <td>{{ $value->reason }}</td>
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
