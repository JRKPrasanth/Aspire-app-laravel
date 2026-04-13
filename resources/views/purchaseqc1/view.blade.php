@extends('layouts.header')
@section('content')


			<form>
					<div class="card">
					 <div class="card-header">
						 <h2>Quality Checking</h2>
						 <span class="ui_close_btn"><a href="{{URL::to('purchaseqc')}}" class="collapse-close pull-right btn-danger"></a></span>
					 </div>
                          <div class="card-body card-block">                 
							<div class="row">
                            <div class="col-md-12">
							<table  class="table table-bordered table-hover ">
							<tbody>         <tr><th>QC No:</th><td>{!! $row->qc_number!!}</td><th>QC Date:</th><td>{!! $row->qc_date  !!}</td></tr>   
                                                                        <tr><th>GRN Number:</th><td>{!! $row->grn_number !!}</td><th>QC Date:</th><td>{!! $row->qc_date  !!}</td></tr>
									<tr><th>PO No:</th><td>{!! $row->po_number!!}</td><th>Bill Number:</th><td>{!! $row->bill_number !!}</td></tr>
									
									
                                                                       
                                                        </tbody>
							</table>
						</div>
                        
                                            
						</div>
					
					<div class="row">
                            <div class="col-md-12">
						<h4 class="poquote">Product Details</h4>
						<table  class="table table-bordered table-hover ">
							<thead>
							<tr>
							<th>Line No</th>
							<th>Product Name</th>
							
							<th>Uom Code</th>
							<th>Qty</th>
                                                        <th>Received Qty</th>
                                                        <th>Accepted Qty</th>
                                                        <th>Rejected Qty</th>
                                                        <th>Reason</th>
                                                 
							</tr>
							</thead>
							<tbody> <?php //dd($vlinesdata); ?>
								@foreach ($linedata as $key=>$value) 
								<tr>
                                   <td>{{ $key+1 }}</td>
                                   <td>{{ $value->product_id}}</td>
                                
                                   <td>{{ $value->uom_code_id}}</td>
                                   <td>{{ $value->qty}}</td>
                                   <td>{{ $value->receive_qty}}</td>
                                   <td>{{ $value->accept_qty}}</td>
                                   <td>{{ $value->reject_qty}}</td>
                                   <td>{{ $value->reason}}</td>
                                   
                                   
                                   
								</tr>
								@endforeach
							</tbody>
						</table>	
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
/*td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}*/



</style>