@extends('layouts.header')
@section('content')

					<div class="card">
					 <div class="card-header">
						 <h2> Purchase Order</h2>
						 <span class="ui_close_btn"><a href="../purchaseorder" class="collapse-close pull-right btn-danger"></a></span>
					 </div>
                                           <!-- <div class="col-lg-12">-->
						 <div class="card-body card-block normalform">
							 <form>
							 <div class="row">
                         <div class="col-md-12">

<div class="invoice-box" id="section-to-print">

    <table cellpadding="0" cellspacing="0">
        <tbody>

            <h2 class="heads1">Purchase Order Details</h2>

            <tr class="information">
                <td colspan="6">
                    <table>
                        <tbody>

                            <tr>
                                <td>
                                    <p><b>PO No:</b> {!! $po_number!!}</p>
                                    <br>
                                    <p><b>Delivery Date:</b> {!! $delivery_date !!}</p>
                                    <br>
                                </td>
                                <td class="text-right">
                                    <p><b>Supplier Name:</b> {!! $supplier_name !!}</p>
                                    <br>
                                   
                                </td>
                            </tr>

                            <tr>
                                <td colspan="6" class="ref">
                                    <h4 class="head-style-1">Additional Details</h4></td>
                            </tr>

                            <tr>
                                <td>
                                    <p><b>PO Date:</b> {!! $po_date !!}</p>
                                    <br>
                                    <p><b>PO Type:</b> {!! $po_type!!}</p>
                                    <br>
                                    <p><b>Project Name:</b> {!! $project_name!!}</p>
                                    <br>
                                    <p><b></b> </p>
                                    <br>
                                </td>

                                <td class="text-right">
                                    <p><b>PO Status:</b> {!! $po_status !!}</p><br>
                                    <p><b>Organization:</b> {!!$organization_name!!}</p>
                                    <br>
                                    <p><b>Payment Term Name:</b> {!! $payment_term_name !!}</p>
                                    <br>
                                    <p><b>Remarks:</b> {!! $remarks !!}</p><br>
                                    <p><b>Delivery Term Name:</b> {!! $delivery_term_name!!}</p><br>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </td>
            </tr>
            <tr class="heading">
                <table class="table table-bordered table-hover ">
<thead>
							<tr>
							<th>Line No</th>
							<th>Product Name</th>
							<th>Product Description</th>
							<th>Uom Code</th>
							<th>Qty</th>
                                                        <th>Price</th>
                                                        <th>Discount(%)</th>
                                                        <th>Discount Amount</th>
                                                        <th>Tax Group</th>
                                                        <th>Tax Amount</th>
                                                        <th>Line Total</th>
							<th>Promised Date</th>
							<th>Comments</th>
							</tr>
							</thead>
							<tbody> <?php //dd($vlinesdata); ?>
								@foreach ($vlinesdata as $key=>$value)
								<tr>
                                   <td>{{ $key+1 }}</td>
                                   <td>{{ $value->concatenated_product}}</td>
                                   <td>{{ $value->product_description}}</td>
                                   <td>{{ $value->uom_code}}</td>
                                   <td>{{ $value->qty}}</td>
                                   <td>{{ $value->unit_price}}</td>
                                   <td>{{ $value->discount_percentage}}</td>
                                   <td>{{ $value->discount_amount}}</td>
                                   <td>{{ $value->tax_group_name}}</td>
                                   <td>{{ $value->tax_amount}}</td>
                                   <td>{{ $value->line_total}}</td>
                                   <td>{{ $value->promised_date}}</td>
                                   <td>{{ $value->comments}}</td>


								</tr>
								@endforeach
							</tbody>
                </table>
            </tr>

        </tbody>
    </table>
</div>


							
						</div>
                                            <!--</div>-->
       
				</div>
				</form>
			</div>

		</div>


@endsection

