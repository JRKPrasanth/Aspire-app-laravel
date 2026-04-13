@extends('layouts.header')
@section('content')
<h3 class="text-danger"></h3>
@include('layouts.breadcrumb')


    <form>
			{{ csrf_field() }}
			
				<div class="card">


					<div class="card-header">
					<span class="ui_close_btn"><a href="{{URL::to($pageurl)}}" class="collapse-close pull-right btn-danger"></a></span>
					</div>



					<div class="card-body card-block">

                         <div class="row">
    <div class="col-md-12">

        <div class="invoice-box" id="section-to-print">

            <table cellpadding="0" cellspacing="0">
                <tbody>

                    <h2 class="heads1">Material Issue Details</h2>

                    <tr class="information">
                        <td colspan="6">
                            <table>
                                <tbody>
<tr>
                                        <td>
                                            <p><b>Product:</b> {!! $assembly_product !!}</p>
                                            <br>
                                            <p><b>Job No:</b> {!! $job_no !!}</p>
                                            <br>
                                            <p><b>Batch No:</b> {!! $batch_no !!}</p>
                                            <br>
                                            <p><b>Material Issue Date:</b> {!! date(\Session::get('p_date_format'),strtotime($mtl_issue_date)) !!}</p>
                                            <br>
                                            
                                            
                                        </td>
                                        <td>
                                             
                                            <p><b>Uom Code:</b> {!! $uom_code_id !!}</p>
                                            <br>
                                            <p><b>Job Qty:</b> {!! $job_qty !!}</p>
                                            <br>
                                            <p><b>Job Status:</b> {!! $job_status !!}</p>
                                            <br>
                                            <p><b>Remarks:</b> {!! $remarks !!}</p>
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
                                <th>Product</th>
                               
                                <th>Componant Qty</th>
                                <th>Needed Qty</th>
                                <th>Issued qty</th>
                                 <th>Balance qty</th>
                                  <th>Issue qty</th>
                                <th>Comments</th>
                                </tr>
                            </thead>
                             @foreach($vlinesdata as $key=>$value)
                                <tr>
                                <td>{{$value->line_no}}</td>
                                <td>{{$value->product_code.'-'.$value->concatenated_product}}</td>
                              
                                <td>{{$value->qty}}</td>
                                <td>{{$value->issue_qty}}</td>
                                 <td>{{$value->issued_qty}}</td>
                                  <td>{{$value->balance_qty}}</td>
                                   <td>{{$value->issueqty}}</td>
                                <td>{{ $value->comments}}</td>
                                </tr>
                @endforeach
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
