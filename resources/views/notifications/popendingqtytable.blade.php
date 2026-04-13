<div class="row">
		Dear Sir/Madam,<br>
		      &nbsp;&nbsp;&nbsp;&nbsp; Purchase Order Over Due Product Details listed in below table. Kindly Check it.
	<h3>Stock Level</h3>
<table id="grid1" border="1">
<thead>
	<th>S.No</th>
	<th>Purchase Order No</th>
	<th>Purchase Order Date</th>
	<th>Supplier</th>
	<th>Promise Date</th>
	<th>Product</th>
	<th>Qty</th>
	<th>Pending Qty</th>
	
	</thead>
	<tbody>
		<?php foreach($data as $key=>$val) { ?>
		<tr>
			<td>{{$key+1}}</td>
			<td>{{$val->po_number}}</td>
			<td>{{$val->po_date}}</td>
			<td>{{$val->sup_name}}</td>
			<td>{{$val->promised_alternate_date}}</td>
			<td>{{$val->concatenated_product}}</td>
			<td>{{$val->qty}}</td>
			<td>{{$val->pending_qty}}</td>
		
		</tr>
		<?php } ?>
	</tbody>
		
</table>
</div>