@extends('layouts.header')
@section('content')


<div id="accordion">
<div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a role="button">
                                       
                                        OVERDUE DETAILS
                                    </a>
                                </h4><span class="ui_close_btn"><a href="../receiptdetails" class="collapse-close pull-right btn-danger"></a></span>
                            </div>

</div>



    <form>
			{{ csrf_field() }}
			
				<div class="card">
					
				<div class="card-body">	
		
			<table  class="table table-bordered table-hover ">
				<tr>
                                <th>Line No</th>
				<th>Date</th>
				<th>Customer Name</th>
                                <th>Age</th>
				<th>Bill Amount</th>
				<th>Balance Due Amount</th>
				
				</tr>
                          
			 @foreach($data as $key=>$value)

				<tr>
				<td>{{$key+1}}</td>
				<td>{{$value->due_date}}</td>
				<td>{{$value->customer_name}}</td> 
				<td>{{$duedate}} days</td>
				<td>{{$value->invoice_grand_total}}</td>
				<td>{{$value->balance_amount}}</td>
				</tr>
@endforeach


</table>
</div>
</div>
</form>

@endsection
