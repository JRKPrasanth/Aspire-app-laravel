@extends('layouts.header')
@section('content')
<form>
    <div class="card">
     <div class="card-header">
             <h2> ADVANCE RECEIPT FOR SO</h2>
             <span class="ui_close_btn"><a href="../advancereceipt" class="collapse-close pull-right btn-danger"></a></span>
     </div>
    <div class="card-body card-block normalform">
        <div class="row">
    <div class="col-md-12">

        <div class="invoice-box" id="section-to-print">

            <table cellpadding="0" cellspacing="0">
                <tbody>

                    <h2 class="heads1">ADVANCE RECEIPT FOR SO</h2>

                    <tr class="information">
                        <td colspan="6">
                            <table>
                                <tbody>

                                    <tr>
                                        <td>
                                            <p><b>Sales Order Number:</b> {!! $sales_order_no !!}</p>
                                            <br>
                                            <p><b>Customer Name:</b> {!! $customer_name !!}</p>
                                            <br>
                                            <p><b>Advance Date:</b> {!! $advance_date !!}</p>
                                            <br>
                                            <p><b>Receipt Type:</b> {!! $receipt_type_id !!}</p>
                                            <br>
                                            <p><b>Receipt Reference:</b> {!! $receipt_reference !!}</p>
                                            <br>
                                            <p><b>Remarks:</b> {!! $remarks !!}</p>
                                            <br>
                                        </td>
                                        <td class="text-right">
                                            <p><b>Advance Amount:</b> {!! $advance_amount!!}</p>
                                            <br>
                                            <p><b>Bank Name:</b> {!! $bank_name!!}</p>
                                            <br>
                                            <p><b>Account Number:</b> {!! $account_number!!}</p>
                                            <br>
                                             <p><b>Cheque Number:</b> {!! $cheque_no!!}</p>
                                            <br>
                                            <p><b>Concatenated Segments:</b> {!! $concatenated_segments !!}</p>
                                            <br>
                                        </td>
                                    </tr>

                                   

                                    
                                </tbody>
                            </table>
                        </td>
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


