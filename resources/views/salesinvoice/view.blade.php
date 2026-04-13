@extends('layouts.header')
@section('content')
<h3 class="text-danger">Sales Invoice Details</h3>
@include('layouts.breadcrumb')



<form>
    {{ csrf_field() }}
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
            <h5 class="mb-0">Sales Invoice Details</h5>
			<a href="{{ URL::to($return_url) }}" class="btn btn-sm btn-danger"><i class="bi bi-x"></i></a>
        </div>

        <div class="card-body">
            <!-- Header Section -->
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h6 class="fw-bold border-bottom pb-1">Invoice Info</h6>
                    <p><b>Invoice Number:</b> {{$header_data[0]->invoice_number}}</p>
                    <p><b>Invoice Date:</b> {{$invocie_date}}</p>
                    <p><b>Freight Carrier:</b> {{$freightcarrier}}</p>
                    <p><b>Bill To Address:</b> {{$header_data->bill_to_address_id}}</p>
                    <p><b>Invoice Tax Total:</b> {{ number_format($header_data[0]->invoice_tax_total, \Session::get("decimal")) }}</p>
                    <p><b>Trade Discount:</b> {{ number_format($header_data[0]->trade_discount, \Session::get("decimal")) }}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <h6 class="fw-bold border-bottom pb-1">Customer Info</h6>
                    <p><b>Invoice Type:</b> {{$header_data[0]->invoice_type}}</p>
                    <p><b>Customer:</b> {{$header_data[0]->customer_name}}</p>
                    @if($header_data[0]->customer_name=="")
                        <p><b>Employee Name:</b> {!! $empname !!}</p>
                    @endif
                    <p><b>LR NO:</b> {{$header_data[0]->lr_no}}</p>
                    <p><b>Ship To Address:</b> {{$header_data->ship_to_address_id}}</p>
                    <p><b>Invoice Grand Total:</b> {{ number_format($header_data[0]->invoice_grand_total, \Session::get("decimal")) }}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <h6 class="fw-bold border-bottom pb-1">Attachments</h6>
                    <p><b>Project Name:</b> {{$header_data[0]->project_name}}</p>
                    <p><b>Price List:</b> {{$header_data[0]->pricelist_name}}</p>
                    <p><b>Remarks:</b> {{$header_data[0]->remarks}}</p>

                    @php $row = json_decode($header_data[0]->attachfile_name); @endphp
                    @if(!empty($row))
                        <b>Attachments:</b>
                        @foreach($row as $key => $v)
                            <p>{{ $key+1 }}. 
                                <a download href="{{URL::to('')}}/Uploads/soinvoiceupload/SOINV{{$header_data[0]->invoice_hdr_id}}/{{$v}}">
                                    {{$v}} <img src='{{URL::to('')}}/images/download.png' height="18px">
                                </a>
                            </p>
                        @endforeach
                    @else
                        <p><b>Attachment:</b> No Files</p>
                    @endif
                </div>
            </div>

            <!-- Additional Details -->
            <div class="mt-4">
                <h6 class="fw-bold border-bottom pb-1">Additional Details</h6>
                <div class="row">
                    <div class="col-md-4">
                        <p><b>Payment Term:</b> {{$pay_term}}</p>
                        <p><b>Cheque No:</b> {{$header_data[0]->cheque_no}}</p>
                        <p><b>Approved Date:</b> {{$header_data[0]->approved_date}}</p>
                        <p><b>Sales Person:</b> {{$header_data[0]->salesperson_name}}</p>
                        <p><b>Invoice Currency:</b> {{$currency}}</p>
                    </div>
                    <div class="col-md-4">
                        <p><b>Payment Method:</b> {{$pay_method}}</p>
                        <p><b>Cheque Amount:</b> {{$header_data[0]->cheque_amount}}</p>
                        <p><b>Approver Comments:</b> {{$header_data[0]->approver_comments}}</p>
                        <p><b>TDS Percentage:</b> {{$header_data[0]->tds_prcnt}}</p>
                        <p><b>TCS Percentage:</b> {{$header_data[0]->tcs_prcnt}}</p>
                    </div>
                    <div class="col-md-4">
                        <p><b>Created By:</b> {{$created}}</p>
                        <p><b>Cheque Date:</b> {{$header_data[0]->cheque_date}}</p>
                        <p><b>Cheque Received Date:</b> {{$header_data[0]->cheque_received_date}}</p>
                        <p><b>Packing Charges:</b> {{$header_data[0]->packaging_charges}}</p>
                        <p><b>Insurance Charges:</b> {{$header_data[0]->insurance_charges}}</p>
                    </div>
                </div>
            </div>

            <!-- Line Items -->
            <div class="mt-4">
                <h6 class="fw-bold border-bottom pb-2">Invoice Line Items</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped align-middle">
                        <thead class="table-primary freeze">
                            <tr>
                                <th>Line No</th>
                                <th>Product</th>
                                <th>UOM</th>
                                <th>Customer Part No</th>
                                <th>Batch No</th>
                                <th>Invoice Qty</th>
                                <th>Unit Price</th>
                                <th>Discount %</th>
                                <th>Tax Group</th>
                                <th>Line Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vlinesdata as $value)
                                <tr>
                                    <td>{{ $value->line_no }}</td>
                                    <td>{{ $value->product_code }} - {{ $value->concatenated_product }}</td>
                                    <td>{{ $value->uom_code }}</td>
                                    <td>{{ $value->part_no }}</td>
                                    <td>{{ $value->batch_no }}</td>
                                    <td>{{ $value->qty }}</td>
                                    <td>{{ number_format($value->unit_price, \Session::get("decimal")) }}</td>
                                    <td>{{ $value->discount_percentage }}</td>
                                    <td>{{ $value->tax_group_name }}</td>
                                    <td>{{ number_format($value->line_total, \Session::get("decimal")) }}</td>
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
