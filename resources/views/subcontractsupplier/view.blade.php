@extends('layouts.header')
@section('content')

<style type="text/css">
	.invoice-box {
    background-color: #fff;
    margin: auto;
    padding: 15px;
    border: 1px solid #ccc;
    max-width: 1300px;
    box-shadow: 3px 3px 4px #ccc;
}
</style>

			<form>
				
					<div class="card">
					 <div class="card-header">
						 
						 <span class="ui_close_btn"><a href="../subcontractsupplier" class="collapse-close pull-right btn-danger"></a></span>
					 </div>
						<div class="card-body card-block normalform">
							 <div class="row">
    <div class="col-md-12">

        <div class="invoice-box" id="section-to-print">

            <table cellpadding="0" cellspacing="0">
                <tbody>

                    <h2 class="heads1">Subcontract Supplier</h2>

                    <tr>
                        <td colspan="6" class="ref">
                            <table>
                                <tbody>

                                    <tr>
                                        <td style="width:37%">
                                            <p><b>Subcontract Supplier Number:</b> {!! $subcontract_number !!}</p>
                                            <br>
                                            <p><b>Subcontract Supplier Name:</b> {!! $subcontract_name !!}</p>
                                            <br>
                                            <p><b>Subcontract Alternate Name:</b> {!! $subcontract_alternate_name!!}</p>
                                            <br>
                                          <p><b>Supplier Type Name:</b> {!!$suppliertype_name!!}</p>
                                            <br>
                                            <p><b>Default Payment Term:</b> {!!$payment_term_name!!}</p>
                                            <br>

                                        </td>
									
                                        <td style="width:37%">
                                            <p><b>PAN Number:</b> {!!$pan_number!!}</p>
                                            <br>
                                            <p><b>Default Payment Method:</b> {!!$payment_method_name!!}</p>
                                            <br>
                                            <p><b>Pricelist Name:</b> {!!$pricelist_name!!}</p>
                                            <br>
                                            <p><b>Delivery Terms:</b> {!!$delivery_term_name!!}</p>
                                            <br>
                                            <p><b>Insurance Terms:</b> {!!$insurance_term_name!!}</p>
                                            <br>
                                         </td>
										
                                        <td >
                                          
                                           <p><b>Account Structure:</b> {!!$supplier_account!!}</p>
                                            <br>
                                            <p><b>Active:</b> {!!$active!!}</p>
                                            <br>
                                            <p><b>Created By:</b> {!!$created_by!!}</p>
                                            <br>
                                           <p><b>Customer Name: {!! $customer_name !!}</b> </p><br>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </td>
                    </tr>
<!--			<tr>
                        <td colspan="6" class="ref"><h4 class="head-style-1">Payment Terms</h4></td>
                    	</tr>
                         <tr>
                             <td><p><b>if overdue payment is applicable :</b>{!! $overdue !!}</p></td>
                             <td><p><b>Calculation:</b>{!! $calculation_id !!} </p><br></td>
                        </tr>-->
                       
                         
                       
                        <td colspan="6" class="ref"><h4 class="head-style-1">Additional Details</h4></td>
                    	</tr>
                          <tr>
                            <td>
                                <p><b>Default Bank:</b>{!! $bank_name !!} </p><br>
                                <p><b>Tds Applicable:</b> {!!  $tds_applicable !!} </p><br>
                            </td>
                            <td>
				<p><b>Tds Percentage:</b> {!!  $tds_percentage !!} </p><br>
                                <p><b>TDS Account:</b> {!!$tds_account!!}</p><br>
                            </td>	
                            <td>	
				<p><b>Supplier Status:</b> {!! $supplier_status !!}</p><br>
                             
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
							<th>Subcontract Site No</th>
							<th>Subcontract Site Name</th>
							
							
							<th>Address</th>
							<th>Country</th>
							<th>State</th>
							<th>City</th>
							<th>Pincode</th>
							 <th>Contact Number</th>
							  <th>Contact Name</th>
							<th>GST Number</th>
							<th>Primary Address</th>
                                                        <th>Active</th>
							</tr>
							</thead>
							<tbody> <?php //dd($vlinesdata); ?>
								@foreach ($vlinesdata as $key=>$value) 
								<tr>
                                   
                                   <td>{{ $value->subcontract_site_number}}</td>
                                   <td>{{ $value->subcontract_site_name}}</td>
                                   <td>{{ $value->address}}</td>
                                   <td>{{ $value->country_name	}}</td>
                                   <td>{{ $value->state_name	}}</td>
                                   <td>{{ $value->city_name	}}</td>
                                   <td>{{ $value->pincode}}</td>
                                   <td>{{ $value->contact_number}}</td>
                                   <td>{{ $value->contact_person	}}</td>
                                   <td>{{ $value->gst_number	}}</td>
                                   <td>{{ $value->primary_address	}}</td>
                                   <td>{{ $value->active	}}</td>
                                   
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





