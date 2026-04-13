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
					<span class="ui_close_btn"><span class="ui_close_btn"><a href="{{ URL::to('fandf') }}" class="collapse-close pull-right btn-danger" onclick="fandf"></a></span></div>
                                           <!-- <div class="col-lg-12">-->
						 <div class="card-body card-block normalform">
							 <form>
							 <div class="row">
                         <div class="col-md-12">

<div class="invoice-box" id="section-to-print">

    <table cellpadding="0" cellspacing="0">
        <tbody>

            <h2 class="heads1">Full And Final Settlement Details</h2>

            <tr class="information">
                <td colspan="6">
                    <table>
                        <tbody>

                            <tr>
                              <td><p><b>Employee Name</b> {!! $data[0]->emp_id !!}</p>
                                    <br><p><b>Salary Amount</b> {!! $data[0]->sal_amount !!}</p>
                                    <br></td><td><p><b>Imprest Amount</b> {!! $data[0]->imp_amount !!}</p>
                                    <br><p><b>Balance To be paid</b> {!! $data[0]->balance_amount !!}</p>
                                    <br></td><td><p><b>Expense Amount</b> {!! $data[0]->exp_amount !!}</p>
                                    <br><p><b>Actual Paid</b> {!! $data[0]->paid_amount !!}</p>
                                    <br></td></tr>

                           

                        </tbody>
                    </table>
                </td>
            </tr>
            <tr class="heading">
               
            </tr>

        </tbody>
    </table>
</div></div>
                                            <!--</div>-->
       
				</div>
				</form>
			</div>

		</div>

@endsection

