@extends('layouts.header')
@section('content')
<form>
    <div class="card">
     <div class="card-header">
             <h2>CREDIT NOTE</h2>
             <span class="ui_close_btn"><a href="{{URL::to('accountcreditnote')}}" class="collapse-close pull-right btn-danger"></a></span>
     </div>
    <div class="card-body card-block">
    <div class="row">
            <div class="col-md-12">
<div class="invoice-box" id="section-to-print">

    <table cellpadding="0" cellspacing="0">
        <tbody>

            <h2 class="heads1">Debit Note</h2>

            <tr class="information">
                <td colspan="6">
                    <table>
                        <tbody>

                            <tr>
                                <td>
                                    <p><b>Credit Number:</b> {!! $row[0]->credit_number!!}</p><br>
                                    <p><b>Credit Date:</b> {!! $row[0]->credit_date !!}</p><br>
                                    <p><b>Credit Status:</b> {!! $row[0]->credit_status !!}</p><br>
                                    <p><b>Customer Name:</b> {!! $customerid!!}</p><br>
                                    
                                    
                                    
                                </td>
                                <td class="text-right">
                                    <p><b>Customer Site Name:</b> {!! $ship_to_address!!}</p><br>
                                    <p><b>Invoice Number:</b> {!! $row[0]->invoice_number !!}</p><br>
                                    <p><b>Invoice Date:</b> {!! $row[0]->invoice_date !!}</p><br>
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
							<th>Product Name</th>
							<th>Uom Code</th>
                                                        <th>Return Qty</th>
                                                        <th>Price</th>
                                                        <th>Tax Group</th>
                                                        <th>Tax Amount</th>
                                                        <th>Line Total</th>
                                                        <th>Comments</th>

							</tr>
							</thead>
							<tbody> <?php //dd($vlinesdata); ?>
								@foreach ($linedata as $key=>$value)
								<tr>
                                   <td>{{ $key+1 }}</td>
                                   <td>{{ $value->product_id}}</td>
                                   <td>{{ $value->uom_code_id}}</td>
                                   <td>{{ $value->return_qty}}</td>
                                   <td>{{ $value->unit_price}}</td>
                                   <td>{{ $value->tax_group_id}}</td>
                                   <td>{{ $value->tax_amount}}</td>
                                   <td>{{ $value->line_total}}</td>
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
