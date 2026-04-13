

<style type="text/css">
    




</style>

			
		



<!DOCTYPE html>
<html lang="en">
<head>
  
  <style type="text/css">
  .invoice-box table td {
  
  width: calc(100%/3);
}
.invoice-box table td {
   padding: 10px;
}
.table tr td:nth-child(1),.table tr td:nth-child(4) {
  width: 150px;
}
.table tr td:nth-child(5),.table tr td:nth-child(6),.table tr td:nth-child(7),.table tr td:nth-child(8){
    width: 180px;
}
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

                    <h2 class="heads1">PO Invoice Details</h2>

                    <tr class="information">
                        <td colspan="6">
                            <table>
                                <tbody>

                                    <tr>
                                        <td>
                                            <p><b>Invoice Number:</b> {!! $row[0]->bill_number!!}</p>
                                            <br>
                                            <p><b>Invoice Date:</b> {!! $row[0]->invoice_date  !!}</p>
                                            <br>
                                            <p><b>Invoice Status:</b> {!! $row[0]->po_invoice_status  !!}</p>
                                            <br>
                                           

                                        </td>
                                        <td class="text-right">
                                            <p><b>Supplier Name:</b> {!! $row[0]->supplier_name !!}</p>
                                            <br>
                                            <p><b>Supplier Site Name:</b> {!! $row[0]->supplier_site_name !!}</p>
                                            <br>
                                            <p><b>GRN Number:</b> {!! $row[0]->grn_number!!}</p>
                                            <br>
                                            <p><b>Need To Close PO:</b> {!! $row[0]->need_to_close  !!}</p>
                                            <br>
                                           

                                        </td>
                                        <td class="text-right">
                                            
                                             <p><b>PO Number:</b> {!! $row[0]->po_number !!}</p>
                                            <br>
                                            <p><b>PO Date:</b> {!! $row[0]->po_date !!}</p>
                                            <br>
                                             <p><b>Invoice Tax Total:</b> {!! $row[0]->invoice_tax_total!!}</p>
                                            <br>
                                            <p><b>Invoice Grand Total:</b> {!! $row[0]->invoice_grand_total!!}</p>
                                            <br>

                                        </td>
                                    </tr>
                                     <!-- <tr>
                                        <td colspan="6" class="ref">
                                            <h4 class="head-style-1">Additional Details</h4></td>
                                    </tr> -->
                                    <tr  class="information">
                                         <td>
                                            <p><b>Project Name:</b>  {!! $row[0]->project_name!!}</p>
                                            <br>
                                            <p><b>Transport Charges:</b> {!! $row[0]->transport_charges!!}</p>
                                            <br>
                                             <p><b>Packing Charges:</b>{!! $row[0]->packing_charges!!} </p>
                                            <br>
                                            <p><b>Unloading Charges:</b>{!! $row[0]->unloading_charges!!} </p>
                                            <br>
                                            <p><b>Insurance Charges:</b>{!! $row[0]->insurance_charges!!} </p>
                                            <br>
                                              <p><b>Supplier Invoice No:</b> {!! $row[0]->supplier_invoice_no!!}</p>
                                            <br>
                                            <p><b>Supplier Invoice Date:</b> {!! $row->supplier_invoice_date !!}</p>
                                            <br>
                                        </td>
                                        
                                        <td class="text-right">
                                            <p><b>DC Number:</b> {!! $row[0]->dc_number !!}</p>
                                            <br>
                                            <p><b>DC Date:</b>{!! $row->dc_date !!}</p>
                                            <br>
                                            <p><b>Other Tax Amount:</b> {!! $row[0]->other_tax_amount !!}</p>
                                            <br>
                                             <p><b>Other Freight Amount:</b> {!! $row[0]->other_freight_amount !!}</p>
                                            <br>
                                            <p><b>TDS Applicable:</b> {!! $row[0]->tds_applicable !!}</p>
                                            <br>
                                            <p><b>TDS Percentage:</b>{!! $row[0]->tds_prcnt !!}</p>
                                            <br>
                                            <p><b>TDS Amount:</b>{!! $row[0]->tds_amount !!} </p>
                                            <br>
                                            <p><b>TDS Account:</b>{!! $row[0]->concatenated_segments !!} </p>
                                            <br>

                                        </td>

                                        <td class="text-right">
                                            <p><b>Freight Term:</b> {!! $row[0]->tds_applicable !!}</p>
                                            <br>
                                            <p><b>Freight Amount:</b>{!! $row[0]->freight_amount !!}</p>
                                            <br>
                                            <p><b>Due Date:</b>{!! $row->due_date !!} </p>
                                            <br>
                                            <p><b>Invoice Currency:</b>{!! $row[0]->invoice_currency_id !!} </p>
                                            <br>
                                            <p><b>Invoice Pricelist:</b>{!! $row[0]->pricelist_name !!} </p>
                                            <br>
                                            <p><b>Payment Term:</b>{!! $row[0]->payment_term_name !!} </p>
                                            <br>

                                           <p><b></b> </p>
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
                            <th>Qty</th>
                            <!--<th>GRN Qty</th>-->
                                                        <th>Price</th>
                                                        <th>Discount(%)</th>
                                                        <th>Discount Amount</th>
                                                        <th>HSN Code</th>
                                                        <th>Tax Group</th>
                                                        <th>Tax Amount</th>
                                                        <th>Line Total</th>
                                                     
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
                                   <!--<td>{{ $value->accept_qty}}</td>-->
                                   <td>{{ $value->unit_price}}</td>
                                   <td>{{ $value->discount_percentage}}</td>
                                   <td>{{ $value->discount_amount}}</td>
                                   <td>{{ $value->hsn_code}}</td>
                                   <td>{{ $value->tax_group_id}}</td>
                                   <td>{{ $value->tax_amount}}</td>
                                   <td>{{ $value->line_total}}</td>
                                  
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
</div>
</body>
</html>




























                    











