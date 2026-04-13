@extends('layouts.header')
@section('content')

					<div class="card">
					 <div class="card-header">
						 <h2> JOURNAL ENTRY</h2>
						 <span class="ui_close_btn"><a href="../journalentry" class="collapse-close pull-right btn-danger"></a></span>
					 </div>
					 <div class="card-body">
					 	  <div class="row">
    <div class="col-md-12">

        <div class="invoice-box" id="section-to-print">

            <table cellpadding="0" cellspacing="0">
                <tbody>

                    <h2 class="heads1">JOURNAL ENTRY DETAILS</h2>

                    <tr class="information">
                        <td colspan="6">
                            <table>
                                <tbody>

                                    <tr>
                                        <td>
                                            <p><b>Journal Name:</b> {!! $journal_name !!}</p>
                                            <br>
                                            <p><b>Journal Date:</b> {!! $journal_date !!}</p>
                                            <br>
                                            
                                        </td>
                                        <td class="text-right">
                                            <p><b>Journal Type:</b> {!! $journal_type !!}</p>
                                            <br>
<!--                                            <p><b>Journal Reference:</b> {!! $journal_reference !!}</p>
                                            <br>-->
                                            <p><b>Journal Status:</b> {!! $journal_status!!}</p>
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
                                                             <th> Line No</th>
                                                            <th> Date</th>
				                        <th> Account</th>
				                        <th>Debit Amount</th>
				                        <th>Credit Amount</th>
							</tr>
							</thead>
				<tbody>
					
				       @foreach ($vlinesdata as $key=>$value) 
				<tr>
				      <td>{{ $key+1}}</td>
				      <td>{{ $value->journal_date}}</td>
				      <td>{{ $value->concatenated_segments}}</td>
				      <td>{{ $value->debit_amount}}</td>
                                      <td>{{ $value->credit_amount}}</td>
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
        	
        	
@endsection




