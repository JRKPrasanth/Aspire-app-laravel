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
            <div class="card-body card-block">                              
<form>
<div class="row">
<div class="col-md-12">
<div class="invoice-box" id="section-to-print">
<table cellpadding="0" cellspacing="0">
        <tbody>
            <h2 class="heads1">Purchase Quotation Details</h2>
            <tr class="information">
                <td colspan="6">
                    <table>
                        <tbody>

                            <tr>
                                <td>
                                    <p><b>Supplier Name:</b> {!! $supplier_name !!}</p>
                                    <br>
                                    <p><b>Supplier Site Name:</b> {!! $supplier_name !!}</p>
                                    <br>
                                    <p><b>Delivery Date:</b> {!! $delivery_date !!}</p>
                                    <br>
                                    <p><b>Frieght Carriers:</b> {!! $carrier_name !!}</p>
                                    <br>
                                    <p><b>Price list Name:</b> {!! $pricelist_name !!}</p>
                                    <br>
                               </td>
                                <td>
                                    <p><b>Quotation No:</b> {!! $quotation_no !!}</p>
                                    <br>
                                    <p><b>Quotation Date:</b> {!! $quotation_date !!}</p>
                                    <br>
                                    <p><b>Quotation Type:</b> {!! $quotation_type !!}</p>
                                    <br>
                                    <p><b>Quotation Status:</b> {!! $quote_status !!}</p>
                                    <br>
                                    <p><b>Organization:</b> {!!$organization_name!!}</p>
                                    <br>
                                </td>
                              <td class="text-right">
                                    <p><b>Source:</b> {!!$source!!}</p>
                                    <br>
                                    <p><b>Reference No:</b> {!!$reference_number!!}</p>
                                    <br>
                                    <p><b>Created By:</b> {!!$created_by!!}</p>
                                    <br>
                                    <p><b>Quote Tax Total:</b> {!!$quote_tax_total!!}</p>
                                    <br>
                                    <p><b>Quote Grand Total:</b> {!!$quote_grand_total!!}</p>
                                    <br>
                               </td>
                               
                            </tr>

                            

                            <tr>
                                <td>
                                    <p><b>Payment Method Name:</b> {!! $payment_method_name !!}</p>
                                    <br>
                                    <p><b>Payment Term Name:</b> {!! $payment_term_name !!}</p>
                                    <br>
                                    <p><b>Supplier Quote Date:</b> {!! $supplier_quotation_date !!}</p>
                                    <br>
                                     <p><b>Delivery Term Name:</b> {!! $delivery_term_name!!}</p>
                                    <br>
                                     <p><b>Project Name:</b> {!! $project_name!!}</p>
                                    <br>
                                </td>
                                <td>
                                     <p><b>Supplier Ref No:</b> {!! $supplier_ref_no !!}</p>
                                    <br>
                                     <p><b>Insurance Term Name:</b> {!! $insurance_term_name !!}</p>
                                    <br>
                                    <p><b>Bill To Location:</b> {!! $bill_to_location !!}</p>
                                    <br>
                                    <p><b>Ship To Location:</b> {!!$ship_to_location!!}</p>
                                    <br>
                                     <p><b>Remarks:</b> {!! $remarks !!}</p>
                                    <br>
                                </td>

                                <td class="text-right">
                                    
                                     <p><b>Packing Charges:</b> {!! $packing_charges !!}</p>
                                    <br>
                                     <p><b>Insurance Charges:</b> {!! $insurance_charges !!}</p>
                                    <br>
                                    <p><b>Unloading Charges:</b> {!! $unloading_charges !!}</p>
                                    <br>
                                    <p><b>Transport Charges:</b> {!! $transport_charges !!}</p>
                                    
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
                                                        <th>Part Number</th>
                            <th>Product Description</th>
                            <th>Uom Code</th>
                            <th>Qty</th>
                                                        <th>Price</th>
                                                        <th>Discount(%)</th>
                                                        <th>Discount Amount</th>
                                                        <th>HSN Code</th>
                                                        <th>Tax Group</th>
                                                        <th>Tax Amount</th>
                                                        <th>Line Total</th>
                            <th>Promised Date</th>
                                                        <th>Comments</th>
                            </tr>
                            </thead>
                            <tbody> <?php //dd($vlinesdata); ?>
                                @foreach ($vlinesdata as $key=>$value)
                                <tr>
                                   <td>{{ $key+1 }}</td>
                                   <td>{{ $value->concatenated_product}}</td>
                                   <td>{{ $value->part_no}}</td>
                                   <td>{{ $value->product_description}}</td>
                                   <td>{{ $value->uom_code}}</td>
                                   <td>{{ $value->qty}}</td>
                                   <td>{{ $value->unit_price}}</td>
                                   <td>{{ $value->discount_percentage}}</td>
                                   <td>{{ $value->discount_amount}}</td>
                                   <td>{{ $value->classification_code}}</td>
                                   <td>{{ $value->tax_group_name}}</td>
                                   <td>{{ $value->tax_amount}}</td>
                                   <td>{{ $value->line_total}}</td>
                                   <td>{{ $promised_date}}</td>
                                   
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


                    

</form>
                        </div>

    </div>
</div>
</div>
</div>
</body>
</html>




























                    




