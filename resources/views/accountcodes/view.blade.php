@extends('layouts.header')
@section('content')



			<form>
				
					<div class="card">
					 <div class="card-header">
						 <h2> ACCOUNT CODES</h2>
						 <span class="ui_close_btn"><a href="../accountcodes" class="collapse-close pull-right btn-danger"></a></span>
					 </div>
					
                         
						<div class="card-body card-block normalform">
							 <div  class="row">
                            <div class="col-md-12">
							<table  class="table table-bordered table-hover ">
							<tbody>
									<tr><td>Account Class Name:</td><td>{!! $account_class_name !!}</td></tr>
                                                                        <tr><td>Main Account Code:</td><td>{!! $main_account_code !!}</td></tr>
									<tr><td>Active:</td><td>{!! $active !!}</td></tr>
                                                        </tbody>
							</table>
						</div>
                          </div>                           
                              
                           <div class="row">              
						<div class="col-md-12">
						<h4 class="poquote">ACCOUNT CODES DETAILS </h4>
						<table  class="table table-bordered table-hover ">
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
					</div>
					</div>
						</div>	



					</div>
				

			</form>
		



@endsection

