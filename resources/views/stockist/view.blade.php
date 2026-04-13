@extends('layouts.header')
@section('content')

<form>
    {{ csrf_field() }}
    
    <div class="card">
        <div class="card-header">
            <h2> Stockist Details</h2>
            <span class="ui_close_btn">  <a href="../stockist" class="collapse-close pull-right btn btn-xs btn-danger" onclick="stockist"></a></span>
        </div>

        <div class="card-body">   
        <div class="row">
        <div class="col-md-12">



<div class="invoice-box" id="section-to-print">

    <table cellpadding="0" cellspacing="0">
        <tbody>

            <h2 class="heads1">Stockist Details</h2>

            <tr class="information">
                <td colspan="6">
                    <table>

                        <tbody>

                            <tr>
                                <td>
                                    <p><b>Stockist Name:</b>{{ $stockist->stockist_name }}</p> <br>
                                    <p><b>Contact Person:</b>{{$stockist->contact_person}}</p> <br>                                    
                                    <p><b>Stockist Phone Number:</b> {{$stockist->stockist_phone }}</p> <br>
                                                                        
                                </td>
                                <td class="text-right">
                                    <p><b>Chemist Name:</b> {{ $chemist_id }}</p> <br>
                                    <p><b>Super Stockist Name:</b> {{$super_stockist_id }}</p> <br>
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
                            <th>Address</th>
                            <th>Country Name</th>
                            <th>State Name</th>
                            <th>City Name</th>
                            <th>Area</th>
                            <th>Pincode</th>
                        </tr>
                    </thead>
                    <tbody> <?php //dd($vlinesdata); ?>
                    @foreach ($vlinesdata as $key=>$value) 
                         <tr>
                           <td>{{ $value->stockist_address}}</td>
                           <td>{{ $value->country_name}}</td>
                           <td>{{ $value->state_name}}</td>
                           <td>{{ $value->city_name}}</td>
                           <td>{{ $value->area_name}}</td>
                           <td>{{ $value->pincode}}</td>
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


@endsection	

