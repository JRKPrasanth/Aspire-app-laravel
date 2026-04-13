@extends('layouts.header')
@section('content')


			<form>
				
					<div class="card">
					 <div class="card-header">
						 <h2> Payment Batches</h2>
						 <span class="ui_close_btn"><a href="{{URL::to('paymentbatches')}}" class="collapse-close pull-right btn-danger"></a></span>
					 </div>
                    
                    <div class="card-body card-block">                
					    <div class="row">
    <div class="col-md-12">

        <div class="invoice-box" id="section-to-print">

            <table cellpadding="0" cellspacing="0">
                <tbody>

                    <h2 class="heads1">Payment Batches Details</h2>

                    <tr class="information">
                        <td colspan="6">
                            <table>
                                <tbody>

                                    <tr>
                                        <td>
                                            <p><b>Payment Batch Name:</b> {!! $payment_batch_name !!}</p>
                                            <br>
                                            <p><b>Payment Batch Status:</b> {!! $payment_batch_status !!}</p>
                                            <br>
                                            <p><b>Batch Date:</b> {!! $batch_date !!}</p>
                                            <br>

                                        </td>
                                        <td class="text-right">
                                            <p><b>Supplier Name:</b> {!! $supplier_name !!}</p>
                                            <br>
                                            <p><b>Supplier Site Name:</b> {!! $supplier_site_name !!}</p>
                                            <br>
                                            <p><b>Organization:</b> {!! $organization_name !!}</p>
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
							<th>Invoice Number</th>
							<th>Invoice Amount</th>
							<th>Invoice Due Date</th>
                                                        <th>Comments</th>
							
							</tr>
							</thead>
							<tbody> 
								@foreach ($vlinesdata as $key=>$value) 
								<tr>
                                   <td>{{ $key+1 }}</td>
                                   <td>{{ $value->bill_number}}</td>
                                   <td>{{ $value->invoice_amount}}</td>
                                   <td>{{ $value->invoice_due_date}}</td>
                                   <td>{{ $value->comments}}</td>
                                   
								</tr>
								@endforeach
							</tbody>
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
<style>
.ui_close_btn .btn-danger{
	margin-top: -12px;
	background: url("<?php echo URL::asset('images/closebutton.png') ?>") center center no-repeat;
	height: 24px;
	width: 24px;
	background-size: 24px;
	border: none;
}

.table-striped {
     background-color: #9baff1;
}



</style>

