@extends('layouts.header')
@section('content')




			<form>

					<div class="card">
					 <div class="card-header">
						 <!--<h2> PURCHASE REQUISITION</h2>-->
						 <span class="ui_close_btn"><a class="closeurl collapse-close pull-right btn-danger"></a></span>
					 </div>

						<div class="card-body card-block normalform">

						<div class="row">
              <div class="col-md-12">


<div class="invoice-box" id="section-to-print">

    <table cellpadding="0" cellspacing="0">
        <tbody>

            <h2 class="heads1">Purchase Requisition Details</h2>

            <tr class="information">
                <td colspan="6">
                    <table>
                        <tbody>

                            <tr>
                                <td>
                                    <p><b>Requisition No:</b> {!! $requisition_no !!}</p>
                                    <br>
                                    <p><b>Requisition Source:</b> {!! $requisition_source !!}</p>
                                    <br>
                                   
                                    
                                </td>
                                <td>
                                    <p><b>Requisition Status:</b> {!! $requisition_status !!}</p>
                                    <br>
                                   <p><b>Created By:</b> {!! $created_by !!}</p>
                                    <br>
                                  
                                </td>
                                <td class="text-right">
                                    <p><b>Requisition Date:</b> {!! $requisition_date !!}</p><br>
                                   <p><b>Requestor Name:</b> {!! $requestor_id !!}</p>
                                    
                                </td>
                            </tr>

                            <tr>
                                <td colspan="6" class="ref">
                                    <h4 class="head-style-1">Additional Details</h4></td>
                            </tr>


                            <tr>
                                <td>
                                   <p><b>Project Name:</b> {!! $project_name!!}</p>
                                    <br>
                                </td>
                                <td>
                                     <p><b>Remarks:</b> {!! $remarks !!}</p>
                                    <br>
                                 </td>
                                  <td>
                                    
                                     
                                   </td>

                                <td class="text-right">
                                    
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
							<th>Product Name</th>
                                                        <th>Product Description</th>
							<th>Uom Code</th>
							<th>Qty</th>
                                                        <th>Need By Date</th>
							<th>Comments</th>
							</tr>
							</thead>
							<tbody> <?php //dd($vlinesdata); ?>
								@foreach ($vlinesdata as $key=>$value)
								<tr>
                                   <td>{{ $key+1 }}</td>
                                   <td>{{ $value->product_code}}-{{ $value->concatenated_product}}</td>
                                   <td>{{ $value->product_description}}</td>
                                   <td>{{ $value->uom_code}}</td>
                                   <td>{{ $value->qty}}</td>
                                   <td><?php   if($value->need_by_date!="0000-00-00") echo date(\Session::get('p_date_format'), strtotime($value->need_by_date));?></td>
                                   <td>{{ $value->comments}}</td>


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
			</form>
		</div>
<script type="text/javascript">
    $(document).on('click','.closeurl',function()
    {
    var url="{{URL::to($return_url)}}";
      window.location.href=url;
    });
</script>

@endsection
