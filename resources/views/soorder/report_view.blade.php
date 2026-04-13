
<form>
{{ csrf_field() }}


<div class="card-body card-block normalform">
	<div class="row">
    <div class="col-md-12">

        <div class="invoice-box" id="section-to-print">

            <table cellpadding="0" cellspacing="0">
                <tbody>

                    <h2 class="heads1">Sales Order Details</h2>

                    <tr class="information">
                        <td colspan="6">
                            <table>
                                <tbody>

                                    <tr>
                                        <td>
                                            <p><b>Order No:</b> {!! $headerdata->sales_order_no !!}</p>
                                            <br>
                                            <p><b>Order Date:</b> {!! $headerdata->sales_order_date !!}</p>
                                            <br>
                                            <p><b>Order Type:</b> {!! $headerdata->order_type_id !!}</p>
                                            <br>
                                            <p><b>Customer:</b> {!! $headerdata->customer_name !!}</p>
                                            <br>
                                            <p><b>Order Total:</b> {!! $headerdata->order_total !!}</p>
                                            <br>
                                            

                                        </td>
                                        <td class="text-right">
                                        	<p><b>Pricelist:</b> {!! $headerdata->pricelist_name !!}</p>
                                            <br>

                                            <p><b>Organization:</b> {!! $headerdata->remarks !!}</p>
                                            <br>
                                            <p><b>Customer Po Number:</b> {!! $headerdata->customer_po_number !!}</p>
                                            <br>
                                            <p><b>Bill To Address:</b> {!! $headerdata->remarks !!}</p>
                                            <br>
                                            <p><b>Ship To Address:</b> {!! $headerdata->pricelist_name !!}</p>
                                            <br>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="6" class="ref">
                                            <h4 class="head-style-1">Additional Details</h4></td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <p><b>Project Name:</b> {!! $headerdata->project_name !!}</p>
                                            <br>
                                            <p><b>Tax Total:</b> {!! $headerdata->order_tax !!}</p>
                                            <br>
                                            
                                        </td>

                                        <td class="text-right">
                                        	<p><b>Salesperson Name:</b> {!! $headerdata->salesperson_name !!}</p>
                                            <br>

                                            <p><b>Remarks:</b> {!! $headerdata->remarks !!}</p>
                                            <br>
                                            
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
								<th>Product</th>
								<th>UOM Code</th>
								<th>Qty</th>
								<th>Unit Price</th>
								<th>Discount Percentage</th>
								<th>Duscount Amout</th>
								<th>Tax</th>
								<th>Tax Amount</th>
								<th>Line Total</th>
								<th>Comments</th>
								</tr>
								</thead>
					<tbody>
						<?php foreach ($linesdata as $key => $value): ?>
					<tr>
					     <td>{!! $key+1 !!}</td>
					     <td>{!! $value->concatenated_product !!}</td>
					     <td>{!! $value->uom_code !!}</td>
					     <td>{!! $value->qty !!}</td>
					     <td>{!! $value->unit_price !!}</td>
					     <td>{!! $value->discount_percentage !!}</td>
					     <td>{!! $value->discount_amount !!}</td>
					     <td>{!! $value->tax_group_name !!}</td>
					     <td>{!! $value->tax_amount !!}</td>
					     <td>{!! $value->line_total !!}</td>
					     <td>{!! $value->comments !!}</td>
						 </tr>
						<?php endforeach; ?>

					</tbody>
                        </table>
                    </tr>

                </tbody>
            </table>
        </div>

    </div>

</div>
</div>	

</form>	




