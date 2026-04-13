@extends('layouts.header')
@section('content')

<form>
    {{ csrf_field() }}
    
    <div class="card">
        <div class="card-header">
            <!-- <h2> Expenses Details</h2> -->
            <span class="ui_close_btn">  <a href="../sfaexpenses" class="collapse-close pull-right btn btn-xs btn-danger" onclick="sfaexpenses"></a></span>
        </div>

        <div class="card-body card-block">   
        <div class="row">
        <div class="col-md-12">



<div class="invoice-box" id="section-to-print">

    <table cellpadding="0" cellspacing="0">
        <tbody>

            <h2 class="heads1">Expenses Details</h2>

            <tr class="information">
                <td colspan="6">
                    <table>

                        <tbody>
                            <tr>
                                <td>
                                    <p><b>Tour Date:</b>{{ $data->tour_date }}</p> <br>
                                    <p><b>No of Doctor Visits:</b>{{$data->doctor_count}}</p> <br>
                                    <p><b>Travelled From:</b>{{$data->from_area}}</p> <br>
                                    <p><b>Distance:</b> {{ $data->distance }}</p> <br>
                                    <p><b>Daily Allowance:</b> {{ $data->daily_allow }}</p> <br>
                                    <p><b>Total:</b> {{$data->line_total }}</p> <br>                                    
                                </td>
                                <td class="text-right">                                    
                                    <p><b>Town:</b> {{$data->city_name }}</p> <br>
                                    <p><b>No of Chemist Visits:</b> {{$data->chemist_count }}</p> <br>
                                    <p><b>Travelled To:</b> {{$data->to_area }}</p> <br>
                                    <p><b>Fare:</b> {{$data->fare }}</p> <br>
                                    <p><b>Post Telegrams:</b> {{$data->post_tele }}</p> <br>
                                </td>
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