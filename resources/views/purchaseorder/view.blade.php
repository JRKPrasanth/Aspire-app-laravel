@extends('layouts.header')
@section('content')
<h3 class="text-danger">Purchase Order Details</h3>
@include('layouts.breadcrumb')


<div class="card shadow-lg border-0 rounded-3">
  <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Purchase Order</h4>
   <a href="{{ $pageModule }}" ><button type="button" class="btn btn-danger btn-sm">
      <i class="bi bi-x-circle"></i> Close
	   </button></a>
  </div>

  <div class="card-body">
    <form>
      <div class="invoice-box p-3" id="section-to-print">
        <div class="row mb-4">
          <div class="col-md-4">
            <p><strong>Supplier Name:</strong> {!! $supplier_name !!}</p>
            <p><strong>Supplier Address:</strong> {!! $siteaddress.'-'.$city_name.'-'.$state_name.'-'.$country_name !!}</p>
            <p><strong>PO No:</strong> {!! $po_number!!}</p>
            <p><strong>PO Date:</strong> {!! $po_date !!}</p>
            <p><strong>PO Type:</strong> {!! $po_type!!}</p>
            @php $row = json_decode($attachements); @endphp
            @if(!empty($row))
              @foreach($row as $v)
                <p><strong>Attachments:</strong> 
                  <a class="text-decoration-none" target="_blank" href="{{URL::to('')}}/Uploads/purchaseorder/PO{{$row_id}}/{{$v}}">
                    {{$v}} <i class="bi bi-download"></i>
                  </a>
                </p>
              @endforeach
            @else
              <p><strong>Attachments:</strong> No Files</p>
            @endif
          </div>

          <div class="col-md-4">
            <p><strong>Source:</strong> {!! $source!!}</p>
            <p><strong>Reference Number:</strong> {!! $reference_number!!}</p>
            <p><strong>PO Status:</strong> {!! $po_status !!}</p>
            <p><strong>Created By:</strong> {!! $created_by!!}</p>
            <p><strong>Approved By:</strong> {!! $approved_by !!}</p>
          </div>

          <div class="col-md-4">
            <p><strong>Delivery Date:</strong> {!! $delivery_date !!}</p>
            <p><strong>Pricelist Name:</strong> {!! $pricelist_name !!}</p>
            <p><strong>PO Tax Total:</strong> {!! $po_tax_total!!}</p>
            <p><strong>PO Grand Total:</strong> {!! $po_grand_total!!}</p>
          </div>
        </div>

        <h4 class="text-secondary border-bottom pb-2 mb-3">Additional Details</h4>

        <div class="row mb-4">
          <div class="col-md-4">
            <p><strong>Project Name:</strong> {!! $project_name!!}</p>
            <p><strong>Delivery Term:</strong> {!! $delivery_term_name!!}</p>
            <p><strong>Payment Term:</strong> {!! $payment_term_name !!}</p>
            <p><strong>Payment Method:</strong> {!! $payment_method_name !!}</p>
            <p><strong>Insurance Term:</strong> {!! $insurance_term_name !!}</p>
            <p><strong>Bill to Address:</strong> {!! $bill_to_address !!}</p>
            <p><strong>Ship to Address:</strong> {!! $ship_to_address !!}</p>
            <p><strong>Other Freight Amount:</strong> {!! $other_frieght_amount !!}</p>
            <p><strong>Other Tax Amount:</strong> {!! $other_tax_amount !!}</p>
          </div>

          <div class="col-md-4">
            <p><strong>Freight Term:</strong> {!! $fob_point_name !!}</p>
            <p><strong>Freight Carriers:</strong> {!! $carrier_name !!}</p>
            <p><strong>Bill To Location:</strong> {!! $bill_to_location !!}</p>
            <p><strong>Ship To Location:</strong> {!! $ship_to_location!!}</p>
            <p><strong>Supplier Ref No:</strong> {!! $supplier_reference_no !!}</p>
            <p><strong>Currency:</strong> {!! $currency !!}</p>
          </div>

          <div class="col-md-4">
            <p><strong>Packing Charges:</strong> {!! $packing_charges !!}</p>
            <p><strong>Insurance Charges:</strong> {!! $insurance_charges !!}</p>
            <p><strong>Unloading Charges:</strong> {!! $unloading_charges !!}</p>
            <p><strong>Transport Charges:</strong> {!! $transport_charges !!}</p>
            <p><strong>Remarks:</strong> {!! $remarks !!}</p>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered table-striped align-middle">
            <thead class="table-primary">
              <tr>
                <th>Line No</th>
                <th>Product Name</th>
                <th>Part Number</th>
                <th>Description</th>
                <th>UOM</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Discount (%)</th>
                <th>Discount Amount</th>
                <th>HSN Code</th>
                <th>Tax Group</th>
                <th>Tax Amount</th>
                <th>Line SubTotal</th>
                <th>Line Total</th>
                <th>Promised Date</th>
                <th>Promised Alternate Date</th>
                <th>Comments</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($vlinesdata as $key=>$value)
              <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $value->product_code.'-'.$value->concatenated_product}}</td>
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
                <td>{{ $value->line_sub_total}}</td>
                <td>{{ $value->line_total}}</td>
                <td>{{ date('d-m-Y', strtotime($value->promised_date)) }}</td>
                <td>{{ date('d-m-Y', strtotime($value->promised_alternate_date)) }}</td>
                <td>{{ $value->comments}}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

      </div>
    </form>
  </div>
</div>
			


@endsection


