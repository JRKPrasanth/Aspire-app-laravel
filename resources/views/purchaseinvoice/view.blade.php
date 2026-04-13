@extends('layouts.header')
@section('content')
<h3 class="text-danger">PO Invoice Details</h3>
@include('layouts.breadcrumb')


<form>
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <?php if($url=="poinvoiceapproval") { echo "PO Invoice Approval Details"; } else { echo "PO Invoice Details"; } ?>
            </h5>
           <a href="{{ url('purchaseinvoice') }}" > <button type="button" class="btn btn-sm btn-danger closeurl">&times;</button></a>
        </div>

        <div class="card-body">
            <div id="section-to-print">
                <!-- Invoice Main Details -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <p><strong>Supplier Invoice Number:</strong> {!! $row[0]->bill_number !!}</p>
                        <p><strong>Supplier Invoice Date:</strong> {!! $row[0]->invoice_date !!}</p>
                        <p><strong>Invoice Status:</strong> {!! $row[0]->po_invoice_status !!}</p>
                        <p><strong>GRN Number:</strong> {!! $row[0]->grn_number !!}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Supplier Name:</strong> {!! $row[0]->supplier_name !!}</p>
                        <p><strong>Supplier Site Name:</strong> {!! $row[0]->supplier_site_name !!}</p>
                        <p><strong>Need To Close PO:</strong> {!! $row[0]->need_to_close !!}</p>
                        <p><strong>Invoice Tax Total:</strong> {!! $row[0]->invoice_tax_total !!}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>PO Number:</strong> 
                            @foreach ($po_num as $key=>$value) {{ $value->po_number }} @endforeach
                        </p>
                        <p><strong>PO Date:</strong> {!! $row[0]->po_date !!}</p>
                        <p><strong>Invoice Grand Total:</strong> {!! $row[0]->invoice_grand_total !!}</p>
                    </div>
                </div>

                <!-- Additional Details -->
                <h5 class="text-primary border-bottom pb-2">Additional Details</h5>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <p><strong>Project Name:</strong> {!! $row[0]->project_name !!}</p>
                        <p><strong>Transport Charges:</strong> {!! $row[0]->transport_charges !!}</p>
                        <p><strong>Packing Charges:</strong> {!! $row[0]->packing_charges !!}</p>
                        <p><strong>Unloading Charges:</strong> {!! $row[0]->unloading_charges !!}</p>
                        <p><strong>Insurance Charges:</strong> {!! $row[0]->insurance_charges !!}</p>
                        <p><strong>Supplier Invoice No:</strong> {!! $row[0]->supplier_invoice_no !!}</p>
                        <p><strong>Entry Date:</strong> {!! $row->supplier_invoice_date !!}</p>
                        <p><strong>Freight Carriers:</strong> {!! $row[0]->carrier_name !!}</p>

                        <!-- Attachments -->
                        <?php 
                        $rows = json_decode($choosefile);
                        if(!empty($rows)) {
                            foreach($rows as $key => $v) {
                                $downloadPath = ($url=="paymentrequest" || $url=="paymentrequestapproval")
                                    ? URL::to('uploads/product_image/'.$product_id.'/'.$v)
                                    : URL::to('uploads/purchaseinvoice/PO'.$po_invoice_id.'/'.$v);
                                echo "<p><strong>Attachment:</strong> 
                                    <a class='btn btn-sm btn-outline-primary' href='{$downloadPath}' download>
                                        {$v} <i class='bi bi-download'></i>
                                    </a></p>";
                            }
                        } else {
                            echo "<p><strong>Attachments:</strong> No Files</p>";
                        }
                        ?>
                    </div>

                    <div class="col-md-4">
                        <p><strong>DC Number:</strong> {!! $row[0]->dc_number !!}</p>
                        <p><strong>DC Date:</strong> {!! $row->dc_date !!}</p>
                        <p><strong>Other Tax Amount:</strong> {!! $row[0]->other_tax_amount !!}</p>
                        <p><strong>Other Freight Amount:</strong> {!! $row[0]->other_freight_amount !!}</p>
                        <p><strong>TDS Applicable:</strong> {!! $row[0]->tds_applicable !!}</p>
                        <p><strong>TDS Percentage:</strong> {!! $row[0]->tds_prcnt !!}</p>
                        <p><strong>TDS Amount:</strong> {!! $row[0]->tds_amount !!}</p>
                        <p><strong>TDS Account:</strong> {!! $row[0]->concatenated_segments !!}</p>
                        <p><strong>TCS Applicable:</strong> {!! $row[0]->tcs_applicable !!}</p>
                        <p><strong>TCS Amount:</strong> {!! $row[0]->tcs_amount !!}</p>
                    </div>

                    <div class="col-md-4">
                        <p><strong>Freight Term:</strong> {!! $row[0]->fob_point_name !!}</p>
                        <p><strong>Freight Amount:</strong> {!! $row[0]->freight_amount !!}</p>
                        <p><strong>Due Date:</strong> {!! $row->due_date !!}</p>
                        <p><strong>Invoice Currency:</strong> {!! $row[0]->invoice_currency_id !!}</p>
                        <p><strong>Invoice Pricelist:</strong> {!! $row[0]->pricelist_name !!}</p>
                        <p><strong>Payment Term:</strong> {!! $row[0]->payment_term_name !!}</p>
                        <p><strong>Payment Method:</strong> {!! $row[0]->payment_method_name !!}</p>
                        <p><strong>Delivery Term:</strong> {!! $row[0]->delivery_term_name !!}</p>
                        <p><strong>TCS Percentage:</strong> {!! $row[0]->tcs_prcnt !!}</p>
                    </div>
                </div>

                <!-- Line Items Table -->
                <h5 class="text-primary border-bottom pb-2">Line Items</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Line No</th>
                                <th>Product Name</th>
                                <th>UOM Code</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Discount(%)</th>
                                <th>Discount Amount</th>
                                <th>HSN Code</th>
                                <th>Tax Group</th>
                                <th>Tax Amount</th>
                                <th>Line Total</th>
                                <th>Comments</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($linedata as $key=>$value)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $value->product_code }} - {{ $value->product_id }}</td>
                                <td>{{ $value->uom_code_id }}</td>
                                <td>{{ $value->qty }}</td>
                                <td>{{ $value->unit_price }}</td>
                                <td>{{ $value->discount_percentage }}</td>
                                <td>{{ $value->discount_amount }}</td>
                                <td>{{ $value->hsn_code }}</td>
                                <td>{{ $value->tax_group_id }}</td>
                                <td>{{ $value->tax_amount }}</td>
                                <td>{{ $value->line_total }}</td>
                                <td>{{ $value->comments }}</td>
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