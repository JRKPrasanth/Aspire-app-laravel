@extends("layouts.header")
@section("content")
<style type="text/css">
     .table1{
        table-layout: fixed;
    }
   
    .table1 th,.table1 td{
        min-width: 221px;

    }
    .invoice-box{
        max-width: 1120px;
    }
    .linetable{
        max-width: 1120px;
        background-color:#fff;
        border:0px solid #ddd;
        margin: auto;
        padding: 15px;
        overflow-x:auto;
    }
</style>
					<div class="card">
					 <div class="card-header">
					<span class="ui_close_btn"><span class="ui_close_btn"><a href="{{ URL::to('msalesreceipthdr') }}" class="collapse-close pull-right btn-danger" onclick="msalesreceipthdr"></a></span></div>
                                           <!-- <div class="col-lg-12">-->
						 <div class="card-body card-block normalform">
							 <form>
							 <div class="row">
                         <div class="col-md-12">

<div class="invoice-box" id="section-to-print">

    <table cellpadding="0" cellspacing="0">
        <tbody>

            <h2 class="heads1">msalesreceipt Details</h2>

            <tr class="information">
                <td colspan="6">
                    <table>
                        <tbody>

                            <tr>
                              <td><p><b>RECEIPT NUMBER</b> {!! $data[0]->receipt_number !!}</p>
                                    <br><p><b>RECEIPT AMOUNT</b> {!! $data[0]->receipt_amount !!}</p>
                                    <br><p><b>RECEIPT REFERENCE</b> {!! $data[0]->receipt_reference !!}</p>
                                    <br><p><b>BANK ID</b> {!! $data[0]->bank_id !!}</p>
                                    <br></td><td><p><b>RECEIPT DATE</b><?php echo date(\Session::get('p_date_format'),strtotime($data[0]->receipt_date)); ?></p>
                                    <br><p><b>ACCOUNT CODE</b> {!! $data[0]->account_code_id !!}</p>
                                    <br><p><b>CHEQUE NO</b> {!! $data[0]->cheque_no !!}</p>
                                    <br><p><b>REFERENCE NO</b> {!! $data[0]->reference_no !!}</p>
                                    <br></td><td><p><b>INVOICE AMOUNT</b> {!! $data[0]->invoice_amount !!}</p>
                                    <br><p><b>RECEIPT TYPE</b> {!! $data[0]->receipt_type_id !!}</p>
                                    <br><p><b>ACCOUNT NO</b> {!! $data[0]->account_no !!}</p>
                                    <br><p><b>BANK DATE</b><?php echo date(\Session::get('p_date_format'),strtotime($data[0]->bank_date)); ?></p>
                                    <br></td></tr>

                           

                        </tbody>
                    </table>
                </td>
            </tr>
            <tr class="heading">
               
            </tr>

        </tbody>
    </table>
</div><div class="linetable">
     <table class="table table-bordered table-hover table1 ">
<thead>
                            <tr>
                             <th>Line No</th><th>LINE NO</th><th>INVOICE NO</th><th>RECEIPT AMOUNT</th><th>BALANCE AMOUNT</th><th>INVOICE AMOUNT</th><th>EXPENSE AMOUNT</th>
                            </tr>
                            </thead>
                            <tbody> <?php //dd($vlinesdata); ?>
                                @foreach ($vlinesdata as $key=>$value)
                                <tr>
                                   <td>{{ $key+1 }}</td><td>{{ $value->line_no}}</td><td>{{ $value->invoice_hdr_id}}</td><td>{{ $value->receipt_amount}}</td><td>{{ $value->balance_amount}}</td><td>{{ $value->invoice_amount}}</td><td>{{ $value->expense_amount}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                </table>
</div></div>
                                            <!--</div>-->
       
				</div>
				</form>
			</div>

		</div>

@endsection

