<div class="row">
		Dear Sir/Madam,<br>
		      &nbsp;&nbsp;&nbsp;&nbsp;  Invoice Payment Details are listed in below table. Kindly Check it.
	<h3>Sales Order Pending Qty Details</h3>
<table id="grid1" border="1">
<thead>
	<th>S.No</th>
	<th>Invoice No</th>
	<th>Invoice Date</th>
	<th>Customer</th>
	<th>Due Date</th>
	<th>Invoice Amount</th>
	<th>Paid Amount</th>
	<th>Balance Amount</th>
	
	
	</thead>
	<tbody>
		<?php foreach($data as $key=>$val) { ?>
		<tr>
			<td>{{$key+1}}</td>
			<td>{{$val->invoice_number}}</td>
			<td>{{$val->invoice_date}}</td>
			<td>{{$val->cus_name}}</td>
			<td>{{$val->due_date}}</td>
			<td>{{$val->invoice_grand_total}}</td>
			<td>{{$val->paid_amount}}</td>
			<td>{{$val->balance_amount}}</td>
		
		</tr>
		<?php } ?>
	</tbody>
		
</table>
</div>