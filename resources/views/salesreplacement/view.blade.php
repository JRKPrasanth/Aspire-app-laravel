
@extends('layouts.header')
@section('content')



<style type="text/css">

    .invoice-box table td {
   padding: 10px;
  
}
</style>

    <form>
			{{ csrf_field() }}
			
				<div class="card">
					<div class="card-header">
					
					<span class="ui_close_btn"><a  class="collapse-close pull-right btn-danger closeurl" ></a></span>
					</div>
					
		
			
<div class="card-body card-block">


    <div class="row">
        <div class="col-md-12">


<div class="invoice-box" id="section-to-print">

    <table cellpadding="0" cellspacing="0">
        <tbody>

            <h2 class="heads1">Sales Invoice Details</h2>

            <tr class="information">
                <td colspan="6">
                    <table>
                        <tbody>


                            <tr>
                                <td>
                                    <p><b>Invoice Number:</b> {{$header_data[0]->invoice_number}}</p>
                                    <br>
                                     <p><b>Invoice Date:</b> {{$invocie_date}}</p>
                                    <br>     
                                    <p><b>Freight Carrier:</b> {{   $freightcarrier }}</p>
                                     <br>
                                     <p><b>Bill To Address:</b> {{$header_data->bill_to_address_id }}</p>
                                     <br>
                                    
                                    <?php $taxtot=number_format($header_data[0]->invoice_tax_total,\Session::get("decimal")) ?>
                                    <?php $taxtot1=str_replace(',', '',$taxtot) ?>
                                     <p><b>Invoice Tax Total :</b>{!! $taxtot1 !!} </p>
                                    <br> 
                                </td>
                                <td>
                                  
                                    <p><b>Invoice Type:</b> {{$header_data[0]->invoice_type}}</p>
                                    <br>
                                    <p><b>Customer:</b> {{$header_data[0]->customer_name}}</p>
                                         <br>
									 <?php if($header_data[0]->customer_name==""){ ?>
									 <p><b>Employee Name:</b> {!! $empname !!}</p>
                                    <br>
									  <?php } ?>
                                    <p><b>LR NO:</b> {{$header_data[0]->lr_no}}</p><br>
                                  
                                    <p><b>Ship To Address:</b> {{$header_data->ship_to_address_id}}</p><br>
									<?php  $grandtot=number_format($header_data[0]->invoice_grand_total,\Session::get("decimal")) ?>
	                                <?php $grandtot1=str_replace(',', '',$grandtot);?>
                                    <p><b>Invoice Grand Total:</b> {{ $grandtot1 }}</p>
                                    <br>
                                </td>
                                <td>
                                   
                                    <p><b>Project Name:</b> {{$header_data[0]->project_name}}</p>
                                    <br>
                                     <p><b>Price List:</b> {{$header_data[0]->pricelist_name}}</p>
                                   
                                         <br>
                                    <p><b>Remarks:</b>{{$header_data[0]->remarks}} </p>
                                    <br> 
                                       @php
                                    $row = json_decode($header_data[0]->attachfile_name);
                                    @endphp
                                    @if(!empty($row))
									<b>Attachment:</b>
                                        @foreach($row as $key => $v)
                                       <p> @php echo $key+1; @endphp.<a download href="{{URL::to('')}}/Uploads/soinvoiceupload/SOINV{{$header_data[0]->invoice_hdr_id}}/{{$v}}">{{$v}}&nbsp;<img src='{{URL::to('')}}/images/download.png' height="20px" width="20px" ></a></p>
                                        @endforeach
                                   @else
                                   <p><b>Attachment</b>:No Files</p>
                                   @endif
                                </td>
                            </tr>

                            <tr>
                                <td colspan="6" class="ref">
                                    <h4 class="head-style-1">Additional Details</h4></td>
                            </tr>

  <tr>
                                <td>
                                    <p><b>Payment Term:</b> {{$pay_term}}</p>
                                   <br> <p><b>Approved Date:</b> {{$header_data[0]->approved_date}}</p>
                                   <br> <p><b>Sales Person:</b> {{$header_data[0]->salesperson_name}}</p>
                                   <br> <p><b>Invoice Currency:</b> {{$currency}}</p>
                                   <br> <p><b>TDS Applicable:</b> {{$header_data[0]->tds_applicable}}</p>
                                   <br> <p><b>Discount:</b> {{$header_data->discount}}</p>
                                   
                                </td>
                        
                                <td>
                                    <p><b>Payment Method:</b> {{$pay_method}}</p>
                                    <br> <p><b>Approver Comments:</b> {{$header_data[0]->approver_comments}}</p>
                                    <br> <p><b>Project Name:</b> {{$header_data[0]->project_name}}</p>
                                    <br> <p><b>TDS Percentage:</b> {{$header_data[0]->tds_prcnt}}</p>
                                    <br> <p><b>Delivery Term:</b> {{$delivery_term}}</p>
                                    
                                </td>

                                <td >
                                    <p><b>Created By:</b>{{$created}} </p>
                                    <br>
                                     <p><b>Customer Comments:</b> {!! $header_data[0]->customer_comments !!}</p><br>
                                     <p><b>TDS Amount:</b> {!! $header_data[0]->tds_amount !!}</p><br>
                                     <p><b>Packing Charges:</b> {!! $header_data[0]->packaging_charges !!}</p><br>
                                     <p><b>Insurance Charges:</b> {!! $header_data[0]->insurance_charges !!}</p><br>
                                     <p><b>Transport Charges:</b> {!! $header_data[0]->transport_charges !!}</p>
                                    <br><p><b>Lr Date:</b> {{$header_data[0]->lr_date}}</p>
                                   
									
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
                            <th>UOM Code</th>
                            <th>Customer Part No</th>
                            <th>Batch Number</th>
							<?php if($header_data[0]->invoice_type=="LABOUR") { ?>
							<th>Product Description</th>
							<?php } ?>
							<?php  if ($header_data[0]->source == 'DISPATCH' && $header_data[0]->ar_sales_hdr_id!='' || ($header_data[0]->source == 'SALES ORDER')) { ?>
                            <th>So Qty</th>
							<?php }?>
							<?php if($header_data[0]->source == 'DISPATCH') { ?>
							 <th>Dispatch Qty</th>
							<?php } ?>
                            <th>Invoice Qty</th>
                            <th>Unit Price</th>
                            <th>MRP Price</th>
                            <th>Discount Price</th>
                            <th>Discount Amount</th>
							<th>Tax Exemption</th>
								<?php if($header_data[0]->invoice_type=="STANDARD") { ?>
                            <th>HSN Code</th>
							<?PHP } else {?>
							<th>SAC Code</th>
							<?php } ?>
                            <th>Tax Group</th>
                            <th>Tax Amount</th>
                            <th>Line Total</th>
                            <th>Comments</th>
                        </tr>
                        <thead>

                        <tbody>
                        @foreach($vlinesdata as $key=>$value)
                        <tr>
                            <td><?php echo $value->line_no?></td>
					
                            <td><?php echo $value->product_code . " - ".$value->concatenated_product ?></td>
                            <td><?php echo $value->uom_code?></td>
                            <td><?php echo $value->part_no ?></td>
                            <td><?php echo $value->batch_no ?></td>
						<?php if($header_data[0]->invoice_type=="LABOUR") { ?>
						  <td><?php echo $value->description?></td>
						<?php } ?>
							<?php  if ($header_data[0]->source == 'DISPATCH' && $header_data[0]->ar_sales_hdr_id!='' || ($header_data[0]->source == 'SALES ORDER')) { ?>
							    <td><?php echo $value->salesorder_qty ?></td>
							<?php } ?>
							<?php if($header_data[0]->source == 'DISPATCH') { ?>
							<td><?php echo $value->issues_qoh ?></td>
							<?php } ?>
							    <td><?php echo $value->qty ?></td>
							<?php $price=number_format($value->unit_price,\Session::get("decimal")) ?>
	                        <?php $price1=str_replace(',', '',$price) ?>
                            <td><?php echo $price1 ?></td>
                            <td><?php echo number_format($value->mrp,\Session::get("decimal"),'.','') ?></td>
                            <td><?php echo $value->discount_percentage ?></td>
							<?php $disamt=number_format($value->discount_amount,\Session::get("decimal")) ?>
	                        <?php $disamt1=str_replace(',', '',$disamt) ?>
                            <td><?php echo $disamt1 ?></td>
							 
                            <td><?php echo $value->classification_code ?></td>
							<td><?php echo $value->tax_excemption ?></td>
                            <td><?php echo $value->tax_group_name ?></td>
							<?php $taxamt=number_format($value->tax_amount,\Session::get("decimal")) ?>
	                        <?php $taxamt1=str_replace(',', '',$taxamt) ?>
                            <td><?php echo $taxamt ?></td>
							<?php $lintot=number_format($value->line_total,\Session::get("decimal")) ?>
	                        <?php $lintot1=str_replace(',', '',$lintot) ?>
                            <td><?php echo $lintot1 ?></td>
                            <td><?php echo $value->comments ?></td>
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
    $(document).on('click','.closeurl',function()
    {
    var url="{{URL::to($return_url)}}";
      window.location.href=url;
    });
</script>

@endsection
