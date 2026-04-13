
@extends('layouts.header')
@section('content')


<div class="container main_container">
	<div class="row">
	<div class="col-lg-12 col-md-12">
	</div>
	</div>

<?php //dd($values);?>

<div class="row">
	<div class="col-lg-1">
	</div>

    <form>
			{{ csrf_field() }}
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header">
					<strong> Invoice Details</strong>
					<span class="ui_close_btn"><a href="../salesinvoice" class="collapse-close pull-right btn-danger" onclick="../salesinvoice"></a></span>
					</div>
					
		
			<div class="">
                            <table  class="table table-bordered ">
                                    <tbody>
                                        <tr>
                                            
                                            <th>Invoice Number:</th>
                                            <td>{{$header_data[0]->invoice_number}}</td>
                                            <th>Invoice Type:</th>
                                            <td>{{$header_data[0]->invoice_type}}</td>
                                        </tr>
                                        <tr>
                                            <th>Invoice Date:</th>
                                            <td>{{$header_data[0]->invoice_date}}</td>
                                            <th>Customer:</th>
                                            <td>{{$header_data[0]->customer_name}}</td>
                                        </tr>
                                        <tr>
                                            <th>Invoice Status:</th>
                                            <td>{{$header_data[0]->invoice_status}}</td>
                                            <th>Project Name:</th>
                                            <td>{{$header_data[0]->project_name}}</td>
                                        </tr>
                                        <tr>
                                            <th>Price List:</th>
                                            <td>{{$header_data[0]->pricelist_name}}</td>
                                            <th>Sales Person:</th>
                                            <td>{{$header_data[0]->salesperson_name}}</td>
                                        </tr>
                                        <tr>
                                            <th>Bill to Address:</th>
                                            <td></td>
                                            <th>Remarks:</th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th>Ship to Address:</th>
                                            <td></td>
                                            <th>Invoice Tax Total :</th>
                                            <td></td>
                                        </tr>
                                       
                                        <tr>
                                            <th>Sales Person:</th>
                                            <td>{!! $header_data[0]->salesperson_name !!}</td>
                                            <th>Organization:</th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th>Remarks:</th>
                                            <td>{!! $header_data[0]->remarks !!}</td>
                                            <th>Invoice Grand Total:</th>
                                            <td>{!!$header_data[0]->invoice_grand_total !!}</td>
                                        </tr>
                                        
                                    </tbody>
				</table>
                        </div>
                

                                        </br></br>
        <h4>Invoice Lines Details</h4>
                    <table  class="table">
                        <tr>
                            <th>Line No</th>
                            <th>Product</th>
                            <th>UOM Code</th>
                            <th>Unit Price</th>
                            <th>Discount Price</th>
                            <th>Discount Amount</th>
                            <th>Tax Group</th>
                            <th>Tax Amount</th>
                            <th>Line Total</th>
                            <th>Comments</th>
                        </tr>
                        @foreach($vlinesdata as $key=>$value)
                        <tr>
                            <td><?php echo $value->line_no?></td>
                            <td><?php echo $value->concatenated_product?></td>
                            <td><?php echo $value->uom_code?></td>
                            <td><?php echo $value->unit_price?></td>
                            <td><?php echo $value->discount_percentage?></td>
                            <td><?php echo $value->discount_amount?></td>
                            <td><?php echo $value->tax_group_name?></td>
                            <td><?php echo $value->tax_amount?></td>
                            <td><?php echo $value->line_total?></td>
                            <td><?php echo $value->comments?></td>
                        </tr>					  
                        @endforeach
                    </table>
                </div>
                
</div>
</div>

@extends('layouts.footer')
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
