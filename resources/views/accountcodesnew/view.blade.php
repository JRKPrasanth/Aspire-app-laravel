@extends('layouts.header')
@section('content')



			<form>
				
					<div class="card">
					 <div class="card-header">
						 <h2> ACCOUNT CODES</h2>
						 <span class="ui_close_btn"><a href="../accountcodesnew" class="collapse-close pull-right btn-danger"></a></span>
					 </div>
					
                         
						<div class="card-body card-block normalform">
							 <div class="row">
    <div class="col-md-12">

        <div class="invoice-box" id="section-to-print">

            <table cellpadding="0" cellspacing="0">
                <tbody>

                    <h2 class="heads1">ACCOUNT CODES Details</h2>

                    <tr class="information">
                        <td colspan="6">
                            <table>
                                <tbody>

                                    <tr>
                                        <td>
                                            <p><b>Account Class Name:</b> {!! $account_class_name !!}</p>
                                            <br>
                                            <p><b>Main Account Code:</b> {!! $main_account_code !!}</p>
                                            <br>

                                        </td>
                                        <td class="text-right">
                                            <p><b>Active:</b> {!! $active !!}</p>
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
							<th>Account Code</th>
							<th>Account Code Meaning</th>
							<th>Description</th>
							<th>Active</th>
                                                        </tr>
							</thead>
                                                        <tbody>
                                @foreach ($vlinesdata as $key=>$value) 
                            <tr>
                                   <td>{{ $key+1 }}</td>
                                   <td>{{ $value->account_code}}</td>
                                   <td>{{ $value->account_code_meaning}}</td>
                                   <td>{{ $value->description}}</td>
                                   <td>{{ $value->active}}</td>
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

