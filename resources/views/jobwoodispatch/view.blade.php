
@extends('layouts.header')
@section('content')



    <form>
			{{ csrf_field() }}
	
				<div class="card">
					<div class="card-header">
					
					<span class="ui_close_btn"><a href="{{ URL::to('jobworkoutorder') }}" class="collapse-close pull-right btn-danger"></a></span>
					</div>
					

<div class="card-body card-block">

<div class="row">
    <div class="col-md-12">

        <div class="invoice-box" id="section-to-print">

            <table cellpadding="0" cellspacing="0">
                <tbody>

                    <h2 class="heads1">Job Work Out Order</h2>

                    <tr class="information">
                        <td colspan="6">
                            <table>
                                <tbody>

                                    <tr>
                                        <td>
                                            <p><b>JobWork OutOrder Number:</b> {!! $joboutorder_no !!}</p>
                                            <br>
                                            <p><b>Return Date:</b> {!! $return_date !!}</p>
                                            <br>
                                        </td>
					<td>
                        		  <p><b>Joboutorder Status:</b> {!! $joboutorder_status!!}</p>
                                            <br>
                                            <p><b>Remarks:</b> {!! $remarks !!}</p>
                                            <br>


                                        </td>
                                        <td class="text-right">
                                            <p><b>SubContractor:</b> {!! $subcontract_name !!}</p><br>
                                        <p><b>Created By:</b> {!! $username !!}</p>
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
								<th>Quantity</th>
								<th>Need By Date</th>
								<th>Comments</th>
								</tr>
							</thead>
							 @foreach($vlinesdata as $key=>$value)
								<tr>
								<td>{{$value->line_no}}</td>
								<td>{{$value->concatenated_product}}</td>
								<td>{{$value->uom_code}}</td>
								<td>{{$value->qty}}</td>
								<td>{{$value->need_by_date}}</td>
								<td>{{ $value->comments}}</td>
								</tr>
				@endforeach
                        </table>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>



					</div>
					</div>
					
</form>




@endsection



