@extends('layouts.header')
@section('content')




			<form>

					<div class="card">
					 <div class="card-header">
						 <h2> Receipt Batches</h2>
						 <span class="ui_close_btn"><a href="{{URL::to('receiptbatches')}}" class="collapse-close pull-right btn-danger"></a></span>
					 </div>
              <div class="card-body">
              <div class="row">
    <div class="col-md-12">

        <div class="invoice-box" id="section-to-print">

            <table cellpadding="0" cellspacing="0">
                <tbody>

                    <h2 class="heads1"> Receipt Batches Details</h2>

                    <tr class="information">
                        <td colspan="6">
                            <table>
                                <tbody>

                                    <tr>
                                        <td>
                                            <p><b>Receipt Batch Name:</b> {!! $receipt_batch_name !!}</p>
                                            <br>
                                            <p><b>Receipt Batch Status:</b> {!! $receipt_batch_status !!}</p>
                                            <br>
                                            <p><b>Batch Date:</b> {!! $batch_date !!}</p>
                                            <br>
                                            



                                        </td>
                                        <td class="text-right">
                                            <p><b>Customer Name:</b> {!! $customer_name !!}</p>
                                            <br>
                                            <p><b>Customer Site Name:</b> {!! $customer_site_name !!}</p>
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
                                   <td>{{ $value->invoice_number}}</td>
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


