<!DOCTYPE html>
<html lang="en">

<head>

  <style type="text/css">
    body {
      margin: 8px;
    }

    .card {
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

    .invoice-box table tbody tr:nth-child(1) {
      background: #fff !important;
    }

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


    .invoice-box {
      background-color: #fff;
      margin: auto;
      padding: 15px;
      /* border: 1px solid #ccc;*/

      box-shadow: 3px 3px 4px #ccc;

    }

    .invoice-box table {
      width: 100%;
      /*text-align:left;*/
    }

    .invoice-box table td {
      padding: 3px;
      vertical-align: top;
    }


    .invoice-box b,
    strong {
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

    .table>caption+thead>tr:first-child>td,
    .table>caption+thead>tr:first-child>th,
    .table>colgroup+thead>tr:first-child>td,
    .table>colgroup+thead>tr:first-child>th,
    .table>thead:first-child>tr:first-child>td,
    .table>thead:first-child>tr:first-child>th {
      border-top: 0;
      background: #05234e;
      font-family: 'Prompt', sans-serif;
      font-weight: normal;
      color: #fff;
    }

    .table td,
    .table th {
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

    .table>tbody>tr>td,
    .table>tbody>tr>th,
    .table>tfoot>tr>td,
    .table>tfoot>tr>th,
    .table>thead>tr>td,
    .table>thead>tr>th {
      padding: 4px;
      line-height: 1.42857143;
    }

    .right {
      text-align: center;
      margin-top: -5%;
    }

    .photo {
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
                  <img src="{{ asset('images/jrks.png') }}" class="img-fluid"
                    style="width:60px;height:60px; vertical-align:middle;">
                </div>
                <div class="col-md-6 right">
                  <p><b>Dr.JRK’s Research and Pharmaceuticals Private Limited</b><br>
                    <span>&nbsp;(Dr.JRK’s Siddha Research and Pharmaceuticals Pvt Ltd.)</span>
                  </p>

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

                        <h2 class="heads1">Sales Order Details</h2>
                        <tr class="information">
                          <td colspan="6">
                            <table>
                              <tbody>

                                <tr>
                                  <td>
                                    <p><b>Order No:</b> {!! $headerdata->sales_order_no !!}</p>
                                    <br>
                                    <p><b>Order Type:</b> {!! $headerdata->order_type_id !!}</p>
                                    <br>
                                    <p><b>Pricelist:</b> {!! $headerdata->pricelist_name !!}</p>
                                    <br>
                                    <?php $tax = number_format($headerdata->order_tax, \Session::get("decimal")) ?>
                                    <?php $tax1 = str_replace(',', '', $tax) ?>
                                    <p><b>Tax Total:</b> {!! $tax1 !!}</p>

                                  </td>
                                  <td>

                                    <p><b>Order Date:</b> {{date("d-m-Y", strtotime($headerdata->delivery_date))}}</p>
                                    <br>
                                    <p><b>Customer:</b> {!! $headerdata->customer_name !!}</p>
                                    <br>
                                    <p><b>Delivery Date:</b> {{date("d-m-Y", strtotime($headerdata->sales_order_date))}}
                                    </p>
                                    <br>
                                    <?php $ordertot = number_format($headerdata->order_total, \Session::get("decimal")) ?>
                                    <?php $ordertot1 = str_replace(',', '', $ordertot) ?>
                                    <p><b>Order Total:</b> {!! $ordertot1 !!}</p>
                                    <br>
                                  </td>
                                  <td class="text-right">
                                    <p><b>Customer Po Number:</b> {!! $headerdata->customer_po_number !!}</p>
                                    <br>
                                    <p><b>Bill To Address:</b> {!! $headerdata->remarks !!}</p>
                                    <br>
                                    <p><b>Ship To Address:</b> {!! $headerdata->pricelist_name !!}</p>
                                    <br>
                                  </td>
                                </tr>





                                <tr>
                                  <td>
                                    <p><b>Comments:</b> {!! $headerdata->remarks !!}</p>
                                    <br>
                                    <p><b>Payment Method Name:</b> {!! $headerdata->payment_method_name !!}</p>
                                    <br>
                                    <p><b>Packaging Charges:</b> {!! $headerdata->packaging_charges !!}</p>
                                    <br>
                                    <p><b>Freight Term:</b> {!! $headerdata->fob_point_name !!}</p>
                                    <br>
                                  </td>
                                  <td>
                                    <p><b>Contact Number:</b> {!! $headerdata->contact_number !!}</p>
                                    <br>
                                    <p><b>Project:</b> {!! $headerdata->project_name !!}</p>
                                    <br>
                                    <p><b>Other Tax Amount:</b> {!! $headerdata->other_tax_amount !!}</p>
                                    <br>
                                    <p><b>Insurance Charges:</b> {!! $headerdata->insurance_charges !!}</p>
                                    <br>
                                    <p><b>Payment Term:</b> {!! $headerdata->payment_term_name !!}</p>
                                    <br>
                                  </td>
                                  <td class="text-right">
                                    <p><b>Contact Person:</b> {!! $headerdata->contact_person !!}</p>
                                    <br>
                                    <p><b>Delivery Name:</b> {!! $headerdata->delivery_term_name !!}</p>
                                    <br>
                                    <p><b>Other Freight Amount:</b> {!! $headerdata->other_frieght_amount !!}</p>
                                    <br>
                                    <p><b>Transport Charges:</b> {!! $headerdata->transport_charges !!}</p>
                                    <br>
                                    <p><b>Carrier Name:</b> {!! $headerdata->carrier_name !!}</p>
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
                                <th>Product</th>
                                <th>UOM Code</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Discount Percentage</th>
                                <th>Duscount Amout</th>
                                <th>Tax</th>
                                <th>Tax Amount</th>
                                <th>Line Total</th>
                                <th>Comments</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php foreach ($linesdata as $key => $value): ?>
                              <tr>
                                <td>{!! $key + 1 !!}</td>
                                <td>{!! $value->concatenated_product !!}</td>
                                <td>{!! $value->uom_code !!}</td>
                                <td>{!! $value->qty !!}</td>
                                <?php  $unitprice = number_format($value->unit_price, \Session::get("decimal")) ?>
                                <?php  $unitprice1 = str_replace(',', '', $unitprice) ?>
                                <td>{!! $unitprice1 !!}</td>
                                <?php  $disamt = number_format($value->discount_amount, \Session::get("decimal")) ?>
                                <?php  $disamt1 = str_replace(',', '', $disamt) ?>
                                <td>{!! $value->discount_percentage !!}</td>
                                <td>{!! $disamt1 !!}</td>
                                <td>{!! $value->tax_group_name !!}</td>
                                <?php  $taxamt = number_format($value->tax_amount, \Session::get("decimal")) ?>
                                <?php  $taxamt1 = str_replace(',', '', $taxamt) ?>
                                <td>{!! $taxamt1 !!}</td>
                                <?php  $lineamt = number_format($value->line_total, \Session::get("decimal")) ?>
                                <?php  $lineamt1 = str_replace(',', '', $lineamt) ?>
                                <td>{!! $lineamt1 !!}</td>
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