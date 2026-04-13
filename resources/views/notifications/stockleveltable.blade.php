<div class="row">
		Dear Sir/Madam,<br>
		      &nbsp;&nbsp;&nbsp;&nbsp; Product Stock Level Details listed in below table. Kindly Check it.
	<h3>Stock Level</h3>
<table id="grid1" border="1">
<thead>
	<th>Product</th>
	<th>Minimum Stock</th>
	<th>Qoh</th>
	
	</thead>
	<tbody>
		<?php foreach($data as $key=>$val) { ?>
		<tr>
			<td>{{$val->concatenated_product}}</td>
			<td>{{$val->stock_level}}</td>
			<td>{{$val->qoh}}</td>
		
		</tr>
		<?php } ?>
	</tbody>
		
</table>
</div>