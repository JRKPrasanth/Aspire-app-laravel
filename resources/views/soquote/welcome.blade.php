








<!DOCTYPE html>
<html lang="en">
<head>
  
  <style type="text/css">
  body{
    margin: 8px;
  }
.card{
  margin: 0 auto;
  /*width: 80%;*/
}
.card-header {
    background: #fff;
    padding: 5px;
    padding-top: 0px;
    /* border: 1px solid #ccc; */
}
.card-body {
    padding: 0px 0;
    border: 1px solid #ccc
}

.invoice-box table tbody tr:nth-child(1) {background:#fff !important;}

.heads1 {
    text-align: center;
    padding: 6px;
    background: #30a1ea;
    color: #fff;
    margin-top: 0;
    font-size: 17px;
}

.head-style-1 {
  text-align: center;
  position: relative;
  line-height: 2;
}
.head-style-1::before {
  content: "";
  position: absolute;
  width: 60%;
  height: 1px;
  top: auto;
  left: 0;
  bottom: 0;
  right: 0;
  margin: 0 auto;
  background-color: #dfdfdf;
}
.head-style-1::after {
  content: "";
  position: absolute;
  width: 10%;
  height: 2px;
  top: auto;
  left: 0;
  right: 0;
  bottom: 0;
  margin: 0 auto;
  background-color: #30a1ea;
  transition: all 0.3s ease 0s;
}
.head-style-1:hover::after {
  width: 30%;
}


    .invoice-box{
        background-color: #fff;
    margin: auto;
    padding: 15px;
   /* border: 1px solid #ccc;*/
    
    box-shadow: 3px 3px 4px #ccc;
     
    }
    
    .invoice-box table{
        width:100%;
        /*text-align:left;*/
    }
    
    .invoice-box table td{
        padding:3px;
        vertical-align:top;
    }

   
.invoice-box b, strong {
    font-weight: normal;
    line-height: 15px;
    color: #767676;
    font-size: 15px;
    font-family: Bitstream-FuturaMdBTMedium;
    letter-spacing: 1px;
}
    
  .invoice-box p {
    margin: 0;
    font-weight: normal;
    line-height: 15px;
    color: #000;
    font-size: 13px;
    font-family: Bitstream-FuturaMdBTMedium;
    letter-spacing: 1px;
}
.table {
    max-width: 100%;
    overflow: hidden;
    overflow-x: hidden;
}
    
   .table>caption+thead>tr:first-child>td, .table>caption+thead>tr:first-child>th,
    .table>colgroup+thead>tr:first-child>td, .table>colgroup+thead>tr:first-child>th,
     .table>thead:first-child>tr:first-child>td, .table>thead:first-child>tr:first-child>th{
    border-top: 0;
    background: #05234e;
    font-family: 'Prompt', sans-serif;
    font-weight: normal;
    color: #fff;
   }
.table td, .table th {
    padding: 0rem;
    vertical-align: middle;
   
}
body {
    font-family: FiraSans-Book;
    font-size: 14px;
    line-height: 1.42857143;
    color: #333;
    background-color: #fff;
}   

.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
  padding: 4px;
    line-height: 1.42857143;
}
.right{
  text-align: center;
    margin-top: -5%;
}
.photo{
  text-align: center;
    margin-left: -32%;
}


</style>
</head>
<body>


  
<div class="container">
  <div class="row">
    <div class="col-md-12">
        <form>
    <div class="card">
           <div class="card-header  ">
            <div class="row">
              <div class="col-md-6 photo">
            <img src="{{ asset('images/jrks.png') }}" class="img-fluid" style="width:60px;height:60px; vertical-align:middle;">
             </div>
             <div class="col-md-6 right" >
             <p><b>Dr.JRK’s Research and Pharmaceuticals Private Limited</b><br>
              <span>&nbsp;(Dr.JRK’s Siddha Research and Pharmaceuticals Pvt Ltd.)</span></p>
             
             </div>
           
             
             </div>
           </div>
                                           <!-- <div class="col-lg-12">-->
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
                                    <p><b>Quote No:</b> {!! $headerdata->quote_no !!}</p>
                                    <br>
                                    <p><b>Quote Name:</b> {!! $headerdata->quote_name !!}</p>
                                    <br>
                                   <?php $tot=number_format($headerdata->quote_grand_total,\Session::get("decimal")) ?>
                                    <?php $tot1=str_replace(',', '',$tot) ?>
                                    <p><b>Quote Total:</b> {!! $tot1 !!}</p><br>
                                    
                                </td>
                                 <td>
                                    <p><b>Quote Type:</b> {!! $headerdata->quote_type !!}</p>
                                    <br>
                                     <p><b>Quote Date:</b> {{ $quotedate }}</p>
                                    <br>
                                   <?php $tax=number_format($headerdata->quote_tax_total,\Session::get("decimal")) ?>
                                    <?php $tax1=str_replace(',', '',$tax) ?>
                                    <p><b>Tax Total:</b> {!! $tax1 !!}</p><br>
                                </td>
                                <td>
                                   
                                     <p><b>Customer:</b> {!! $headerdata->customer_number !!}{!! $headerdata->customer_name !!}</p>
                                    <br>
                                     <p><b>PriceList:</b>{{ $pricelist_name }}</p>
                                    <br>
                                     <p><b>Remarks:</b> {!! $headerdata->remarks !!}</p><br>
                                </td>
                            </tr>

                            


                            <tr>
                                <td>
                                      <p><b>Freight Term:</b> {!! $freightterm !!}</p>
                                    <br>
                                    <p><b>Currency Code:</b> {!! $currency !!}</p><br>
                                    
                                    <p><b>Packaging Charges:</b> {!! $headerdata->packaging_charges !!}</p><br>
                                    
                                    <p><b>Title Of Work:</b> {!! $headerdata->tittle_of_work !!}</p><br>
                               <p><b>Payment Method:</b> {!! $paymentmethod!!}</p><br>
                                </td>
<td>                   
                                    <p><b>Payment Term:</b> {!! $paymentterm !!}</p>
                                    <br>
                                   <p><b>Project Name:</b> {!! $headerdata->project_name !!}</p>
                                    <br>
                                    <p><b>Transport Charges:</b> {!! $headerdata->transport_charges !!}</p><br>
                                
                                    <p><b>Quote Reference:</b> {!! $headerdata->quote_reference !!}</p><br>
                                 
                           
                                    <p><b>Frieght Carrier:</b> {!! $freight !!}</p><br>
                                </td>
                                <td >
                                      <p><b>Delivery Term:</b> {!! $delivery !!}</p><br>
                                   
                                    <p><b>Salesperson Name:</b> {!! $headerdata->salesperson_name !!}</p>
                                    <br>
                                        <p><b>Insurance Charges:</b> {!! $headerdata->insurance_charges !!}</p><br>
                                       <p><b>Quote Subject:</b> {!! $headerdata->quote_subject !!}</p><br>
                                   
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
           <th>UOM Code</th>
                <?php } else { ?>
            <th>Product Description</th>
        <?php } ?>
            <th>Qty</th>
            <th>Unit Price</th>
            <th width="1%">Discount Percentage</th>
            <th  width="1%">Discount Amout</th>
            <th>Tax</th>
            <th>Tax Amount</th>
            <th>Line Total</th>
            <th>Promised Date</th>
            <th width="15%">Comments</th>
            </tr>
            </thead>
<tbody>
    <?php foreach ($linesdata as $key => $value): ?>
<tr>
     <td>{!! $key+1 !!}</td>
    <?php if($headerdata->quote_type=="STANDARD") { ?>
     <td>{!! $value->concatenated_product !!}</td>
         <td>{!! $value->uom_code !!}</td>
    <?PHP } else { ?>
    <td>{!! $value->product_description !!}</td>
    <?php } ?>
     

     <td>{!! $value->qty !!}</td>
    <?php $price=number_format($value->unit_price,\Session::get("decimal")) ?>
     <?php $price1=str_replace(',', '',$price) ?>
     <td>{!! $price1 !!}</td>
     <td>{!! $value->discount_percentage !!}</td>
    <?php $disamt=number_format($value->discount_amount,\Session::get("decimal")) ?>
     <?php $disamt1=str_replace(',', '',$disamt) ?>
     <td>{!! $disamt1 !!}</td>
     <td>{!! $value->tax_group_name !!}</td>
      <?php $tax=number_format($value->tax_amount,\Session::get("decimal")) ?>
      <?php $tax1=str_replace(',', '',$tax) ?>
     <td>{!! $tax1 !!}</td>
    <?php $tot=number_format($value->line_total,\Session::get("decimal")) ?>
     <?php $tot1=str_replace(',', '',$tot) ?>
     <td>{!! $tot1 !!}</td>
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
</div>
</div>
</div>
</body>
</html>




























                    




