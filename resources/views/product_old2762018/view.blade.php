@extends('layouts.header')
@section('content')
<div class="container">
<div class="row">
<div class="col-md-12">
<div class="panel panel-visible" id="spy1">
<div class="panel-heading">
<strong>Product Details</strong>
<span class="ui_close_btn"><a href="../product" class="collapse-close pull-right btn-danger" onclick="../product"></a></span>
</div>
</div>
</div>
</div>
<div class="row">
<div class="col-md-12">
<form action="">
	<div class="col-md-6">
			<table  class="table table-bordered table-hover ">
			<thead>
			<tbody>
				 <tr><td style="color:#ccc;">.</td><td></td></tr>		
				 <tr><td>product code</td><td>{{ $values['product_code'] }}</td></tr>				
			    <tr><td>product Group</td><td>{{ $group }}</td></tr>
				<tr><td>product category</td><td>{{ $category}}</td></tr>
				<tr><td>product alternate name</td><td>{{ $values['product_alternate_name']}}</td></tr>
				<tr><td>concatenated product</td><td>{{ $values['concatenated_product']}}</td></tr>
				<tr><td>primary uom</td><td>{{ $uom }}</td></tr>
				<tr><td>trx uom</td><td>{{ $trxuom}}</td></tr>
				<tr><td>Product Pack Type</td><td>{{ $product_packtype_id}}</td></tr>
				<tr><td>Product Pack</td><td>{{ $product_pack_id}}</td></tr>
				<tr><td>Product Variant</td><td>{{ $product_variant_id}}</td></tr>

			</tbody>
			</table>
		</div>
		<div class="col-md-6">
			<table class="table table-bordered table-hover">
				<thead>
					<tbody>
				<tr><td style="color:#ccc;">.</td><td></td></tr>
				<tr><td>min order qty</td><td>{{ $values['min_order_qty']}}</td></tr>
				<tr><td>min order qty</td><td>{{ $values['min_order_qty']}}</td></tr>
				<tr><td>re order level</td><td>{{ $values['re_order_level']}}</td></tr>
				<tr><td>Hsn code</td><td>{{ $values['hsn_code']}}</td></tr>
				<tr><td>subinventory</td><td>{{ $subinv }}</td></tr>
				<tr><td>locator</td><td>{{ $locator}}</td></tr>
					<tr><td>serial control</td><td>{{ $values['serial_control']}}</td></tr>
				<tr><td>serial prefix</td><td>{{ $values['serial_prefix']}}</td></tr>
			</tbody>
		</table>
	</div>
</form>
</div>
</div>
@extends('layouts.footer')
</div>
@endsection
