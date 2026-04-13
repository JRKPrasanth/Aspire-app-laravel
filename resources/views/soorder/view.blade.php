@extends('layouts.header')
@section('content')


<div class="card shadow-lg rounded-4 border-0">
  <!-- Card Header -->
  <div class="card-header bg-primary bg-gradient text-white d-flex justify-content-between align-items-center rounded-top-4">
    <h5 class="mb-0">
      <i class="bi bi-receipt me-2"></i> Sales Order Details
    </h5>
    <a class="btn btn-sm btn-danger rounded-pill" href="{{ URL::to($return_url) }}">
      <i class="bi bi-x-lg"></i> Close
    </a>
  </div>

  <!-- Card Body -->
  <div class="card-body">
    <div id="section-to-print">

      <!-- Header Info -->
      <div class="row g-4">
        <div class="col-md-4">
          <div class="p-3 border rounded-3 h-100">
            <p><b>Sales Order No:</b> {!! $headerdata->sales_order_no !!}</p>
            <p><b>Order Type:</b> {!! $headerdata->order_type_id !!}</p>
            <p><b>Pricelist:</b> {!! $headerdata->pricelist_name !!}</p>
            <p><b>Tax Total:</b> {{ number_format($headerdata->order_tax, \Session::get('decimal')) }}</p>

            @php $row = json_decode($headerdata->attachfile_name); @endphp
            <b>Attachments:</b>
            @if(!empty($row))
              <ul class="list-unstyled">
                @foreach($row as $key => $v)
                  <li>
                    {{ $key+1 }}. 
                    <a download href="{{ URL::to('') }}/uploads/salesorderupload/SO{{$row_id}}/{{$v}}">
                      {{ $v }}
                      <img src='{{ URL::to('') }}/images/download.png' height="18" width="18">
                    </a>
                  </li>
                @endforeach
              </ul>
            @else
              <p>No Files</p>
            @endif
          </div>
        </div>

        <div class="col-md-4">
          <div class="p-3 border rounded-3 h-100">
            <p><b>Order Date:</b> {{ date("d-m-Y", strtotime($headerdata->sales_order_date)) }}</p>
            <p><b>Customer:</b> {!! $headerdata->customer_name !!}</p>
            @if($headerdata->customer_name=="")
              <p><b>Employee Name:</b> {!! $empname !!}</p>
            @endif
            <p><b>Delivery Date:</b> {{ date("d-m-Y", strtotime($headerdata->sales_order_date)) }}</p>
            <p><b>Order Total:</b> {{ number_format($headerdata->order_total, \Session::get('decimal')) }}</p>
          </div>
        </div>

        <div class="col-md-4">
          <div class="p-3 border rounded-3 h-100">
            <p><b>Customer Po Number:</b> {!! $headerdata->customer_po_number !!}</p>
            <p><b>Bill To Address:</b> {!! $headerdata->remarks !!}</p>
            <p><b>Ship To Address:</b> {!! $headerdata->pricelist_name !!}</p>
          </div>
        </div>
      </div>

      <!-- Additional Details -->
      <div class="mt-4">
        <h5 class="text-secondary border-bottom pb-2">Additional Details</h5>
        <div class="row g-4">
          <div class="col-md-4">
            <ul class="list-unstyled">
              <li><b>Comments:</b> {!! $headerdata->remarks !!}</li>
              <li><b>Sales Person:</b> {!! $headerdata->first_name !!}</li>
              <li><b>Payment Method:</b> {!! $headerdata->payment_method_name !!}</li>
              <li><b>Packaging Charges:</b> {!! $headerdata->packaging_charges !!}</li>
              <li><b>Freight Term:</b> {!! $headerdata->fob_point_name !!}</li>
              <li><b>Discount:</b> {!! $headerdata->discount_name !!}</li>
            </ul>
          </div>
          <div class="col-md-4">
            <ul class="list-unstyled">
              <li><b>Contact Person:</b> {!! $headerdata->contact_person !!}</li>
              <li><b>Project:</b> {!! $headerdata->project_name !!}</li>
              <li><b>Other Tax:</b> {!! $headerdata->other_tax_amount !!}</li>
              <li><b>Insurance:</b> {!! $headerdata->insurance_charges !!}</li>
              <li><b>Payment Term:</b> {!! $headerdata->payment_term_name !!}</li>
            </ul>
          </div>
          <div class="col-md-4">
            <ul class="list-unstyled">
              <li><b>Contact Number:</b> {!! $headerdata->contact_number !!}</li>
              <li><b>Delivery Term:</b> {!! $headerdata->delivery_term_name !!}</li>
              <li><b>Other Freight:</b> {!! $headerdata->other_frieght_amount !!}</li>
              <li><b>Transport Charges:</b> {!! $headerdata->transport_charges !!}</li>
              <li><b>Carrier:</b> {!! $headerdata->carrier_name !!}</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Line Items Table -->
      <div class="mt-5">
        <h5 class="text-secondary border-bottom pb-2">Line Items</h5>
        <div class="table-responsive">
          <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th>Line No</th>
                <th>Product</th>
                <th>UOM</th>
                @if($headerdata->order_type_id=="LABOUR")
                  <th>Description</th>
                @endif
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Free Qty</th>
                <th>Discount %</th>
                <th>Discount Amt</th>
                <th>Tax Exemption</th>
                @if($headerdata->order_type_id=="LABOUR")
                  <th>SAC Code</th>
                @else
                  <th>HSN Code</th>
                @endif
                <th>Tax</th>
                <th>Tax Amt</th>
                <th>Line Total</th>
                <th>Comments</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($linesdata as $key => $value)
                <tr>
                  <td>{{ $key+1 }}</td>
                  <td>{{ $value->product_code ."-". $value->concatenated_product }}</td>
                  <td>{{ $value->uom_code }}</td>
                  @if($headerdata->order_type_id=="LABOUR")
                    <td>{{ $value->product_description }}</td>
                  @endif
                  <td>{{ $value->qty }}</td>
                  <td>{{ number_format($value->unit_price, \Session::get('decimal')) }}</td>
                  <td>{{ $value->free_qty }}</td>
                  <td>{{ $value->discount_percentage }}</td>
                  <td>{{ number_format($value->discount_amount, \Session::get('decimal')) }}</td>
                  <td>{{ $value->tax_excemption }}</td>
                  <td>{{ $value->classification_code }}</td>
                  <td>{{ $value->tax_group_name }}</td>
                  <td>{{ number_format($value->tax_amount, \Session::get('decimal')) }}</td>
                  <td>{{ number_format($value->line_total, \Session::get('decimal')) }}</td>
                  <td>{{ $value->comments }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</div>







@endsection