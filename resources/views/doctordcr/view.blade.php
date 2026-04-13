@extends('layouts.header')
@section('content')
<style type="text/css">
    th{
        width: 100%/2;
    }
</style>
<form>
    {{ csrf_field() }}    
<div class="card">
        <div class="card-header">
            <h2> Doctor DCR Details</h2>
            <span class="ui_close_btn">  <a href="../doctordcr" class="collapse-close pull-right btn btn-xs btn-danger" onclick="doctordcr"></a></span>
        </div>

<div class="card-body">   
<div class="row">
<div class="col-md-12">
<div class="invoice-box" id="section-to-print">
    <table cellpadding="0" cellspacing="0">
        <tbody>
            <h2 class="heads1">Doctor DCR Details</h2>
            <tr class="information">
                <td colspan="6">
                    <table>
                        <tbody>
                            <tr>
                                <td> <?php //dd($doctordcr); ?>
                                    <p><b>Tour Plan Date:</b><?php echo date("d-m-Y",strtotime($doctordcr->tp_date));?></p> <br>
                                    <p><b>Divert Details:</b>{{$doctordcr->divert_detail}}</p> <br>
                                    <p><b>From Area:</b>{{$from_area}}</p> <br>
                                    <p><b>Doctor Name:</b> {{ $doctor_name }}</p> <br>
                                    <p><b>Focus Product:</b>{{$focus_product}}</p> <br>
                                    <p><b>Timing:</b> {{$doctordcr->timing }}</p> <br>                                    
                                </td>
                                <td class="text-right">
                                    <p><b>Activity:</b> {{$activity }}</p> <br>
                                    <p><b>Visit With:</b> {{$visit_with }}</p> <br>
                                    <p><b>Outcome:</b> {{$outcome_id }}</p> <br>
                                    <p><b>DR Reminder Reason:</b> {{$doctordcr->dcr_reminder_reason }}</p> <br>
                                    <p><b>DR Reminder Date:</b> <?php echo date("d-m-Y",strtotime($doctordcr->dcr_reminder_date ));?></p> <br>                                    
                                    <p><b>Remarks:</b> {{$doctordcr->remarks }}</p> <br>
                                </td>
                            </tr>
                            <tr class="heading">
                                <table class="table table-bordered table-hover ">
                                <h2 class="heads1">POB Details</h2>
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
                            <tr class="heading">
                                <table class="table table-bordered table-hover ">
                                <h2 class="heads1">Gift Details</h2>
                                    <thead>
                                    <tr>
                                        <th>Gift Product Name</th>
                                        <th>Gift Quantity</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($giftlinesdata as $key1=>$value1)
                                        <tr>
                                            <td>{{ $value1->concatenated_product}}</td>
                                            <td>{{ $value1->gift_qty}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </tr>
                            <tr class="heading">
                                <table class="table table-bordered table-hover ">
                                <h2 class="heads1">Sample Details</h2>
                                    <thead>
                                    <tr>
                                        <th>Sample Product Name</th>
                                        <th>Sample Quantity</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($samplelinesdata as $k=>$v)
                                        <tr>
                                            <td>{{ $v->concatenated_product}}</td>
                                            <td>{{ $v->prd_qty}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </tr>
                            <tr class="heading">
                                <table class="table table-bordered table-hover ">
                                <h2 class="heads1">Poster Details</h2>
                                    <thead>
                                    <tr>
                                        <th>Poster Product Name</th>
                                        <th>Poster Quantity</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($posterlinesdata as $k1=>$v1)
                                        <tr>
                                            <td>{{ $v1->concatenated_product}}</td>
                                            <td>{{ $v1->poster_qty}}</td>
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