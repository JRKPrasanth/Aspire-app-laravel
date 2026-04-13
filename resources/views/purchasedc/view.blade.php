@extends('layouts.header')
@section('content')




			<form>
			

					<div class="card">
					 <div class="card-header">
						 <!--<h2>PURCHASE RETURN</h2>-->
						 <span class="ui_close_btn"><a class="collapse-close pull-right btn-danger closeurl"></a></span>
					 </div>


					 <div class="card-body card-block">




									<div class="row">
                                                      <div class="col-md-12">


<div class="invoice-box" id="section-to-print">

    <table cellpadding="0" cellspacing="0">
        <tbody> <h2 class="heads1">
Purchase DC
           </h2>

            <tr class="information">
                <td colspan="6">
                    <table>
                        <tbody>

                            <tr>
                                <td>
                                    <p><b>DC Number:</b> {!! $row[0]->dc_number!!}</p>
                                    <br>
                                    <p><b>DC Date:</b> {!! $row[0]->dc_date !!}</p>
                                    <br>
                                    <p><b>DC Status:</b> {!! $row[0]->dc_status !!}</p>
                                    <br>
                                 </td>
                                <td class="text-right">
                                    <p><b>GRN Number:</b> {!! $row[0]->grn_number !!}</p><br>
                                    <p><b>Supplier Name:</b> {!! $supplier_id!!}</p><br>
                                    <p><b>QC Number:</b> {!! $row[0]->qc_number !!}</p>
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
							<th>Product Name</th>
                                                        <th>Uom Code</th>
							<th> Qty</th>
                                                        <th>Comments</th>

							</tr>
							</thead>
							<tbody> <?php //dd($vlinesdata); ?>
								@foreach ($linedata as $key=>$value)
								<tr>
                                   <td>{{ $key+1 }}</td>
                                   <td>{{ $value->product_id}}</td>
                                   <td>{{ $value->uom_code_id}}</td>
                                   <td>{{ $value->qty}}</td>
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

				</div>
			</form>
<script type="text/javascript">
     $(document).on('click',".closeurl",function() {
     var url="{{URL::to($url)}}";
      window.location.href=url;
});
</script>



@endsection
