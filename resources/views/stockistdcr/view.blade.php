@extends('layouts.header')
@section('content')

<form>
    {{ csrf_field() }}
    
    <div class="card">
        <div class="card-header">
            <h2> Stockist DCR Details</h2>
            <span class="ui_close_btn">  <a href="../stockistdcr" class="collapse-close pull-right btn btn-xs btn-danger" onclick="stockistdcr"></a></span>
        </div>

        <div class="card-body">   
        <div class="row">
        <div class="col-md-12">



<div class="invoice-box" id="section-to-print">

    <table cellpadding="0" cellspacing="0">
        <tbody>

            <h2 class="heads1">Stockist DCR Details</h2>

            <tr class="information">
                <td colspan="6">
                    <table>

                        <tbody>

                            <tr>
                                <td>
                                    <p><b>Tour Plan Date:</b><?php echo date("d-m-Y",strtotime($stockist_dcr->tp_date));?></p> <br>
                                    <p><b>From Area:</b>{{$from_area}}</p> <br>
                                    <p><b>Stockist Name:</b> {{$stockist_name }}</p> <br>
                                </td>
                                <td class="text-right">
                                    <p><b>Order Number:</b> {{$stockist_dcr->order_no }}</p> <br>
                                    <p><b>Order Value:</b> {{$stockist_dcr->value }}</p> <br>
                                    <p><b>Remarks:</b> {{$stockist_dcr->remarks }}</p> <br>
                                </td>
                            </tr>
                            <tr class="heading">
                                <table class="table table-bordered table-hover ">
                                    <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($linesdata as $key=>$value)
                                        <tr>
                                            <td>{{ $value->concatenated_product}}</td>
                                            <td>{{ $value->pob_qty}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </tr>
                        </tbody>
                    </table>
                </td>
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

