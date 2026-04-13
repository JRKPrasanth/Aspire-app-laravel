<div class="row">
		Dear Sir/Madam,<br>
		      &nbsp;&nbsp;&nbsp;&nbsp; Sales Order Over Due Product Details are listed in below table. Kindly Check it.
	<h3>Sales Order Pending Qty Details</h3>
<table id="grid1" border="1">
<thead>
	<th>S.No</th>
	<th>Sales Order No</th>
	<th>Sales Order Date</th>
	<th>Customer</th>
	<th>Delivery Date</th>
	<th>Product</th>
	<th>Qty</th>
	<th>Pending Qty</th>
	
	
	</thead>
	<tbody>
		<?php foreach($data as $key=>$val) { ?>
		<tr>
			<td>{{$key+1}}</td>
			<td>{{$val->sales_order_no}}</td>
			<td>{{$val->sales_order_date}}</td>
			<td>{{$val->cus_name}}</td>
			<td>{{$val->delivery_date}}</td>
			<td>{{$val->concatenated_product}}</td>
			<td>{{$val->qty}}</td>
			<td>{{$val->pending_qty}}</td>
		
		</tr>
		<?php } ?>
	</tbody>
		
</table>
</div>