<!--to display saved records-->
@extends('layouts.header')
@section('content')
   <form>
			{{ csrf_field() }}
			
				<div class="card">

					
					<span class="ui_close_btn"><a href="{{ URL::to('machine') }}" class="collapse-close pull-right btn-danger"></a></span>
				<div class="card-body card-block normalform">
	<div class="row">
    <div class="col-md-12">

        <div class="invoice-box" id="section-to-print">

            <table cellpadding="0" cellspacing="0">
                <tbody>

                    <h2 class="heads1">Machine Details</h2>

                    <tr class="information">
                        <td colspan="6">
                            <table>
                                <tbody>

                                    <tr>
                                        <td>
                                            <p><b>Machine Code:</b> {!! $machine_code !!}</p>
                                            <br>
                                           <p><b>Remarks:</b> {!! $remarks !!}</p>
                                            <br> 
                                            
                                        </td>

   <td>
                                       <p><b>Machine Name:</b> {!! $machine_name !!}</p>
	    <br>
                                          <p><b>Created By:</b> {!! $created_by !!}</p> 
                                        </td>
                                        <td>
                                            <p><b>Assigned To:</b> {!! $assigned_name !!}</p>
                                            <br>
                                             <p><b>Electricity Cost:</b> {!! $electricity_cost !!}</p>
                                           
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
									<th>Product Type</th>
									<th>Machine Capacity</th>
									</tr>
								</thead>
								 @foreach($linesdata as $key=>$value)
									<tr>
									<td>{{$value->line_no}}</td>
									<td>{{$value->product_type_name}}</td>
									<td>{{$value->machine_capacity}}</td>
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


