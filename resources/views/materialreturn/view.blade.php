@extends('layouts.header')
@section('content')
   <form>
			{{ csrf_field() }}
				<div class="card">
					<div class="card-header">
					<span class="ui_close_btn"><a href="{{ URL::to($pageurl) }}" class="collapse-close pull-right btn-danger"></a></span>
					</div>
<div class="card-body card-block">
					<div class="row">
    <div class="col-md-12">
        <div class="invoice-box" id="section-to-print">
           <table cellpadding="0" cellspacing="0">
                <tbody>
                   <h2 class="heads1">Jobcard Completion Details</h2>
                    <tr class="information">
                        <td colspan="6">
                            <table>
                                <tbody>
                                 <tr>
                                        <td>
											 <p><b>Reference No:</b>{!! $header->reference_no !!}</p><br>
											 <p><b>Job No:</b>{!! $header->job_no !!}</p>
                                            <br>
                                            <p><b>Product:</b>{!! $header->product_code !!} - {!!$header->concatenated_product !!}</p>
                                            <br>
                                          <p><b>Batch No:</b>{{$header->batch_no}}</p><br>
                                          <p><b>Quality Check:</b>{{$header->quality_check}}</p><br>
                                          <p><b>EMP Working Hrs:</b>{{$header->total_working_hrs}}</p><br>
                                        </td>
										 <td>
                                            <p><b>Qa Trx Date:</b>{{$header->qatrx_date}}</p><br>
                                            <p><b>Job Date:</b>{{$header->job_date}}</p><br>
											  <p><b>Uom Code:</b>{!! $header->uom_code !!}</p><br>
											  <p><b>Activity Incharge:</b>{!! $header->first_name !!}</p><br>
											  <p><b>Store Move:</b>{!! $header->store_move !!}</p><br>
                                         </td>
                                        <td>
											 <p><b>Qa Status:</b>{{$header->qa_status}}</p><br>
											  <p><b>Job Qty:</b>{{ $header->job_qty }}</p><br>
											  <p><b>Production Qty:</b>{{ $header->production_qty }}</p><br>
											  <p><b>Remarks:</b>{{ $header->remarks }}</p><br>
											  <p><b>Move to Subinventory:</b>{{ $header->moveto_subinventory }}</p><br>
											  <p><b>Subinventory:</b>{{ $header->subinventory_name }}</p><br>
											  <p><b>Sublocator:</b>{{ $header->locator_code }}</p><br>
											
                                          
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
								<th>Uom Code</th>
								<th>Qty</th>
                                <th>Production Qty</th>
                                <th>Return Qty</th>
                                <th>Scrap Qty</th>
                                <th>Exceed Qty</th>
                                <th>Batch Number</th>
                                <th>Subinventory</th>
                                <th>Locator</th>
                                <th>Comments</th>
									</tr>
								</thead>
							 <tbody>
								 @foreach($linesdata as $key=>$value)
									<tr>
									<td>{!! $key+1 !!}</td>
					     <td>{!! $value->product_code."-".$value->concatenated_product !!}</td>
					     <td>{!! $value->uom_code !!}</td>
					     <td>{!! $value->qty !!}</td>
                         <td>{!! $value->production_qty !!}</td>
                         <td>{!! $value->return_qty !!}</td>
                          <td>{!! $value->scrap_qty!!}</td>
                          <td>{!! $value->exceed_qty!!}</td>
                          <td>{!! $value->batchno!!}</td>
                          <td>{!! $value->subinventory_name!!}</td>
                          <td>{!! $value->locator_code!!}</td>
                          <td>{!! $value->comments	!!}</td>

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
</div></div>
</form>


<?php
	function idname($displayname,$table,$condition,$value){
	$name=\DB::select("select $displayname from $table where $condition = '$value' ");
    if($name)
		   return $name[0]->$displayname;
		else
		   return "";
	}
?>
@endsection