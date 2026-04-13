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
<span class="ui_close_btn"><a class="collapse-close pull-right btn-danger closeurl" ></a></span>
</div>


<div class="card-body card-block normalform">


<div class="row">
<div class="col-md-12">


<div class="invoice-box" id="section-to-print">

    <table cellpadding="0" cellspacing="0">
        <tbody>

            <h2 class="heads1">Sales Quote Details</h2>

            <tr class="information">
                <td colspan="6">
                    <table>
                        <tbody>

                            <tr>
                                <td>
                                    <p><b>Quote Number:</b> {!! $headerdata->quote_no !!}</p>
                                    <br>
                                    <p><b>Quote Name:</b> {!! $headerdata->quote_name !!}</p>
                                    <br>
                                   <?php $tot=number_format($headerdata->quote_grand_total,\Session::get("decimal")) ?>
	                                <?php $tot1=str_replace(',', '',$tot) ?>
                                    <p><b>Quote Total:</b> {!! $tot1 !!}</p><br>
                                     <br>
                                       @php
                                    $row = json_decode($headerdata->attachfile_name);
                                    @endphp
                                    @if(!empty($row))
									<b>Attachment:</b>
                                        @foreach($row as $key => $v)
                                       <p> @php echo $key+1; @endphp.<a download href="{{URL::to('')}}/uploads/soquoteupload/S{{$headerdata->quote_hdr_id}}/{{$v}}">{{$v}}&nbsp;<img src='{{URL::to('')}}/images/download.png' height="20px" width="20px" ></a></p>
                                        @endforeach
                                   @else
                                   <p><b>Attachment</b>:No Files</p>
                                   @endif
                                </td>
								 <td>
                                    <p><b>Quote Type:</b> {!! $headerdata->quote_type !!}</p>
                                    <br>
                                     <p><b>Quote Date:</b> {{ $quotedate }}</p>
                                    <br>
                                   <?php  $tax=number_format($headerdata->quote_tax_total,\Session::get("decimal"),'.','') ?>
	                                
                                    <p><b>Tax Total:</b> {!! $tax !!}</p><br>
                                </td>
                                <td>
                                   
                                     <p><b>Customer:</b> {!! $headerdata->customer_number !!}{!! $headerdata->customer_name !!}</p>
                                    <br>
									 <p><b>PriceList:</b>{{ $pricelist_name }}</p>
                                    <br>
                                     <p><b>Remarks:</b> {!! $headerdata->remarks !!}</p><br>
                                     <p><b>Bill to Address:</b> {!! $headerdata->bill_to_address_id !!}</p><br>
									 <p><b>Ship to Address:</b> {!! $headerdata->ship_to_address_id !!}</p><br>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="6" class="ref">
                                    <h4 class="head-style-1">Additional Details</h4></td>
                            </tr>


                            <tr>
                                <td>
									  <p><b>Freight Term:</b> {!! $freightterm !!}</p>
                                    <br>
                                    <p><b>Currency Code:</b> {!! $currency !!}</p><br>
									
                                    <p><b>Packaging Charges:</b> {!! $headerdata->packaging_charges !!}</p><br>
                                    <p><b>Other Freight Amount:</b> {!! $headerdata->other_frieght_amount !!}</p><br>
									
                                    <p><b>Quote Reference:</b> {!! $headerdata->quote_reference !!}</p><br>
							             <p><b>Frieght Carrier:</b> {!! $freight !!}</p><br>
                                </td>
                                <td>                   
                                    <p><b>Payment Term:</b> {!! $paymentterm !!}</p>
                                    <br>
                                   <p><b>Project Name:</b> {!! $headerdata->project_name !!}</p>
                                    <br>
                                    <p><b>Transport Charges:</b> {!! $headerdata->transport_charges !!}</p><br>
                                    <p><b>Other Tax Amount:</b> {!! $headerdata->other_tax_amount !!}</p><br>
                                
                                    
                                 <p><b>Quote Subject:</b> {!! $headerdata->quote_subject !!}</p><br>
                           <p><b>Discount:</b> {!! $discount !!}</p><br>
                                    
                                </td>
                                <td >
									  <p><b>Delivery Term:</b> {!! $delivery !!}</p><br>
                                   
                                    <p><b>Salesperson Name:</b> {!! $headerdata->first_name !!}</p>
                                    <br>
									    <p><b>Insurance Charges:</b> {!! $headerdata->insurance_charges !!}</p><br>
                                        <p><b>Title Of Work:</b> {!! $headerdata->tittle_of_work !!}</p><br>
									   <p><b>Payment Method:</b> {!! $paymentmethod!!}</p><br>
                                      
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
				<?php if($headerdata->quote_type=="STANDARD") { ?>
			<th width="25%">Product</th>
			<th width="25%">Customer Part No</th>
		   <th>UOM Code</th>
				<?php } else { ?>
			<th>Product Description</th>
		<?php } ?>
			<th>Qty</th>
			<th>Unit Price</th>
			<th width="1%">Discount Percentage</th>
			<th  width="1%">Discount Amout</th>
			<th>Tax Exemption</th>
				<th>HSN Code</th>
			<th>Tax Group</th>
			<th>Tax Amount</th>
			<th>Line Total</th>
			<th>Promised Date</th>
			<th width="15%">Comments</th>
			</tr>
			</thead>
<tbody>
	<?php foreach ($linesdata as $key => $value): //dd($value); ?>
<tr>
     <td>{!! $key+1 !!}</td>
	<?php if($headerdata->quote_type=="STANDARD") { ?>
     <td>{!! $value->product_code . " - " .$value->concatenated_product !!}</td>
     <td>{!! $value->part_no !!}</td>
	     <td>{!! $value->uom_code !!}</td>
	<?PHP } else { ?>
	<td>{!! $value->product_description !!}</td>
	<?php } ?>
     

     <td>{!! $value->qty !!}</td>
	<?php $price=number_format($value->unit_price,\Session::get("decimal"),'.','') ?>
	 <td>{!! $price !!}</td>
     <td>{!! $value->discount_percentage !!}</td>
	<?php $disamt=number_format($value->discount_amount,\Session::get("decimal"),'.','') ?>
	 <td>{!! $disamt !!}</td>
	<td>{!! $value->tax_excemption !!}</td>
     <td>{!! $value->classification_code !!}</td>
	 
     <td>{!! $value->tax_group_name !!}</td>
      <?php $tax=number_format($value->tax_amount,\Session::get("decimal"),'.','') ?>
	  
     <td>{!! $tax !!}</td>
	<?php $tot=number_format($value->line_total,\Session::get("decimal"),'.','') ?>
	 
     <td>{!! $tot !!}</td>
     <td><?php echo date(\Session::get('p_date_format'),strtotime($value->promised_date)); ?></td>
     <td>{!! $value->comments !!}</td>
	 </tr>
	<?php endforeach; ?>

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