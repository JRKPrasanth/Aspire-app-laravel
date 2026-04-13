@extends('layouts.header')
@section('content')


<style>
    .invoice-box{
        background-color: #fff;
    margin: auto;
    padding: 15px;
    border: 1px solid #ccc;
    max-width: 1000px;
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
    color: #000;
    font-size: 15px;
    font-family: Bitstream-FuturaMdBTMedium;
    letter-spacing: 1px;
}
    
  .invoice-box p {
    margin: 0;
    font-weight: normal;
    line-height: 15px;
    color: #66676b;
    font-size: 13px;
    font-family: Bitstream-FuturaMdBTMedium;
    letter-spacing: 1px;
}
    
   

   
    .text-right{
        text-align: right;
    }
    .ref{
        text-align: center;
        padding-bottom: 10px !important;
        color: #14b4fc;
        font-weight: bold;
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


    </style>



<div class="card">
 <div class="card-header">
<h2> Openstock Details</h2> 
<span class="ui_close_btn"><a href="../openstockupload" class="collapse-close pull-right btn-danger" onclick="../openstockupload"></a></span>
</div>

<div class="card-body card-block">




<div class="row">
                <div class="col-md-12">
<form action="">
                    <div class="invoice-box" id="section-to-print">

                        <table cellpadding="0" cellspacing="0">
                            <tbody>

                                <h2 class="heads1">Openstock Details</h2>

                                <tr class="information">
                                    <td colspan="6">

                                        <table class="table table-hover ">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <p><b>Product name:</b> {{$values['item_name'] }}</p>
                                                        <br>
                                                        <p><b>Subinventory Name:</b> {{$values['subinventory_name'] }}</p>
                                                        <br>
                                                        <p><b>Batch Name:</b>{{ $values['batch_name']}}</p>
                                                        <br>
                                                        <p><b>Batch Date:</b> {{ $values['batch_date']}}</p>
                                                        <br>
                                                    </td>
                                                    <td class="text-right">
                                                        <p><b>Locator Code:</b> {{ $values['locator_code'] }}</p>
                                                        <br>
                                                        <p><b>Qty:</b> {{ $values['qty']}}</p>
                                                        <br>

                                                        <p><b>Batch Status:</b> {{ $values['batch_status']}}</p>
                                                        <br>
                                                        <p><b>Batch Comments:</b> {{ $values['batch_comments']}}</p>
                                                        <br>
                                                    </td>
                                                </tr>
                                                

                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                

                            </tbody>
                        </table>
                    </div>
                </form>
                </div>
            </div>

</div>
</div>



@endsection




 