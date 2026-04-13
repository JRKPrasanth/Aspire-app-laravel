@extends('layouts.header')
@section('content')

    <form>
			{{ csrf_field() }}
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header">
					<strong>OVERDUE DETAILS</strong>
					 <span class="ui_close_btn"><a href="../paymentdetails" class="collapse-close pull-right btn-danger"></a></span>
                                        </div>
					<div class="col-md-2"></div>
		
			<table  class="table table-bordered table-hover ">
				<tr>
                <th>Line No</th>
				<th>Date</th>
				<th>Supplier Name</th>
                <th>Age</th>
				<th>Bill Amount</th>
				<th>Balance Due</th>
				
				</tr>
                          
			 @foreach($data as $key=>$value)

				<tr>
				<td>{{$key+1}}</td>
				<td>{{$value->due_date}}</td>
				<td>{{$value->supplier_name}}</td> 
				<td>{{$duedate}} days</td>
				<td>{{$value->invoice_grand_total}}</td>
				<td>{{$value->balance_amount}}</td>
				</tr>
@endforeach


</table>
</form>

@endsection
