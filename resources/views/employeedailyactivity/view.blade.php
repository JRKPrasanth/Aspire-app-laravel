@extends('layouts.header')
@section('content')

					<div class="card">
					 <div class="card-header">
						 <!--<h2> Purchase Order</h2>-->
						 <span class="ui_close_btn"><a href="../empdailyactivity" class="collapse-close pull-right btn-danger"></a></span>
					 </div>
                                           <!-- <div class="col-lg-12">-->
						 <div class="card-body card-block normalform">
							 <form>
							 <div class="row">
                         <div class="col-md-12">

<div class="invoice-box" id="section-to-print">

    <table cellpadding="0" cellspacing="0">
        <tbody>

            <h2 class="heads1">Employee Daily Activity Details</h2>

            <tr class="information">
                <td colspan="6">
                    <table>
                        <tbody>

                            <tr>
                                <td>
                                     <p><b>Employee Name:</b> {!! $empname !!}</p>
                                    <br>
                                   
                                    <p><b>Description:</b> {!! $values->description !!}</p>
                                    <br>
                                     <p><b>Remarks:</b> {!! $values->remarks !!}</p>
                                    <br>
                                
                                </td>
                               
                                <td>
                                   <p><b>Activity Date:</b> {!! $values->activity_date !!}</p>
                                    <br>
                                    <p><b>Hour:</b> {!! $values->hour !!}</p><br>
                                  
                                   
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
				</form>
			</div>

		</div>


@endsection

