@extends('layouts.header')
@section('content')


<div class="row">
<div class="col-md-12">

<div class="card">


<div class="card-header">
<h2>Openstock Details</h2><a href="../openstockupload" class="collapse-close pull-right btn-danger" onclick="../openstockupload"></a>
</div>

<div class="card-body card-block">
<div class="row">
<div class="col-md-12">
<form action="">
<table  class="table table-bordered table-hover" style="width: 100%;">
			<tbody>
				
				<tr><td>Product name:</td><td>{{$values['item_name'] }}</td></tr>
				<tr><td>Subinventory Name:</td><td>{{$values['subinventory_name'] }}</td></tr>
				<tr><td>Locator Code:</td><td>{{ $values['locator_code'] }}</td></tr>
				<tr><td>Qty:</td><td>{{ $values['qty']}}</td></tr>
				<tr><td>Batch Name:</td><td>{{ $values['batch_name']}}</td></tr>
				<tr><td>Batch Date:</td><td><?php echo date(\Session::get('p_date_format'),strtotime($values->batch_date));?></td></tr>
				<tr><td>Batch Status:</td><td>{{ $values['batch_status']}}</td></tr>
				<tr><td>Batch Comments:</td><td>{{ $values['batch_comments']}}</td></tr>
			</tbody>
		</table>
</form>
</div>
</div>
</div>

</div>





</div>
</div>




@endsection