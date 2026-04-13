


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
            {{ csrf_field() }}
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
                                    <p><b>LR NO:</b> {{$header_data[0]->lr_no}}</p><br>
                                    <?php $grandtot=number_format($header_data[0]->invoice_grand_total,\Session::get("decimal")) ?>
                                    <?php $grandtot1=str_replace(',', '',$grandtot) ?>
                                    <p><b>Invoice Grand Total:</b> {!! $grandtot1 !!}</p>
                                    <br>
                                </td>
                                <td class="text-right">
                                   
                                    <p><b>Project Name:</b> {{$header_data[0]->project_name}}</p>
                                    <br>
                                     <p><b>Price List:</b> {{$header_data[0]->pricelist_name}}</p>
                                   
                                         <br>
                                    <p><b>Remarks:</b>{{$header_data[0]->remarks}} </p>
                                </td>
                            </tr>

                            

  <tr>
                                <td>
                                    <p><b>Payment Term:</b> {{$pay_term}}</p>
                                   <br> <p><b>Approved Date:</b> {{$header_data[0]->approved_date}}</p>
                                   <br> <p><b>Sales Person:</b> {{$header_data[0]->salesperson_name}}</p>
                                   <br> <p><b>Invoice Currency:</b> {{$currency}}</p>
                                   
                                   
                                    
                                </td>
                        
                                <td>
                                    <p><b>Payment Method:</b> {{$pay_method}}</p>
                                    <br> <p><b>Approver Comments:</b> {{$header_data[0]->approver_comments}}</p>
                                    <br> <p><b>Project Name:</b> {{$header_data[0]->project_name}}</p>
                                    <br> <p><b>Delivery Term:</b> {{$delivery_term}}</p>
                                    
                                </td>

                                <td class="text-right">
                                    <p><b>Created By:</b>{{$created}} </p>
                                    <br>
                                     <p><b>Customer Comments:</b> {!! $header_data[0]->customer_comments !!}</p>
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
                            <?php if($header_data[0]->invoice_type=="STANDARD") { ?>
                            <th>Product</th>
                            <th>UOM Code</th>
                            <?PHP } else {?>
                            <th>Product Description</th>
                            <?php } ?>
                            <th>Unit Price</th>
                            <th>Discount Price</th>
                            <th>Discount Amount</th>
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
                        <?php if($header_data[0]->invoice_type=="STANDARD") { ?>
                            <td><?php echo $value->concatenated_product?></td>
                            <td><?php echo $value->uom_code?></td>
                        <?php } else {?>
                          <td><?php echo $value->description?></td>
                        <?php } ?>
                            <?php $price=number_format($value->unit_price,\Session::get("decimal")) ?>
                            <?php $price1=str_replace(',', '',$price) ?>
                            <td><?php echo $price1 ?></td>
                            <td><?php echo $value->discount_percentage ?></td>
                            <?php $disamt=number_format($value->discount_amount,\Session::get("decimal")) ?>
                            <?php $disamt1=str_replace(',', '',$disamt) ?>
                            <td><?php echo $disamt1 ?></td>
                            <td><?php echo $value->classification_code ?></td>
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
</div>
</div>
</div>
</body>
</html>




























                    




