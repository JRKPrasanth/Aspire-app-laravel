@extends('layouts.header')
@section('content')

<form>
{{ csrf_field() }}
<div class="card">
<div class="card-header">
<!--<h2> Tax Group</h2>--> 
<span class="ui_close_btn"><a href="../taxgroup" class="collapse-close pull-right btn-danger" onclick="../taxgroup"></a></span>
</div>
<div class="card-body card-block normalform">
	<div class="row">
    <div class="col-md-12">

        <div class="invoice-box" id="section-to-print">

            <table cellpadding="0" cellspacing="0">
                <tbody>

                    <h2 class="heads1">Tax Group Details</h2>

                    <tr class="information">
                        <td colspan="6">
                            <table>
                                <tbody>

                                    <tr>
                                        <td>
                                            <p><b>Tax Group Name:</b> {!! $tax_group_name !!}</p>
                                            <br>
											 <p><b>Created By:</b> {!! $username !!}</p>
                                            <br>
                                       </td>
                                       <td>
                                            <p><b>Tax Percentage:</b> {!! $display_name !!}</p>
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
								<th>Tax Category</th>
					                        <th>Tax Code</th>
					                        <th>Input Tax Account</th>
                                                                <th>Output Tax Account</th>
					                        <th>Active</th>
								</tr>
								</thead>
					<tbody>
						
					       @foreach ($vlinesdata as $key=>$value) 
					<tr>
					     
					      <td>{{ $value->tax_category_name}}</td>
					      <td>{{ $value->tax_code_name}}</td>
					      <td>{{ $value->input_segments}}</td>
					      <td>{{ $value->output_segments}}</td>
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




