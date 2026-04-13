@extends('layouts.header')
@section('content')

<form>
    {{ csrf_field() }}
    
    <div class="card">
        <div class="card-header">
            
            <span class="ui_close_btn">  <a href="../chemist" class="collapse-close pull-right btn btn-xs btn-danger" onclick="chemist"></a></span>
        </div>

        <div class="card-body">   
        <div class="row">
        <div class="col-md-12">



<div class="invoice-box" id="section-to-print">

    <table cellpadding="0" cellspacing="0">
        <tbody>

            <h2 class="heads1">Chemist Details</h2>

            <tr class="information">
                <td colspan="6">
                    <table>

                        <tbody>

                            <tr>
                                <td>
                                    <p><b>Chemist Name:</b>{{ $chemist->chemist_name }}</p> <br>
                                    <p><b>Chemist Type:</b>{{ $chemist->chemist_type }}</p> <br>
                                    <p><b>Chemist Phone Number:</b> {{$chemist->chemist_phone }}</p> <br>
                                    <p><b>Product Name:</b>{{$chemist->product_id}}</p> <br>
                                </td>
                                <td>
                                    <p><b>Chemist Mobile Number:</b> {{$chemist->chemist_mobile }}</p> <br>
                                    <p><b>Group Of Trade:</b>{{$chemist->group_of_trade}}</p> <br>
                                    <p><b>Doctor Name:</b>{{$chemist->doctor_id}}</p> <br>
                                    <p><b>Stockist Name:</b>{{$chemist->stockist_id}}</p> <br>
                                    
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
                            <th>Chemist Address</th>
                            <th>Country</th>
                            <th>State</th>
                            <th>area</th>
                            <th>City</th>
                            <th>Pincode</th>
                        </tr>
                    </thead>
                    <tbody> <?php //dd($linesdata); ?>
                        @foreach ($linesdata as $key=>$value) 
                            <tr>
                               <td>{{ $key+1 }}</td>
                               <td>{{ $value->chemist_address}}</td>
                               <td>{{ $value->country_name}}</td>
                               <td>{{ $value->state_name}}</td>
                               <td>{{ $value->area_name}}</td>
                               <td>{{ $value->city_name}}</td>
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

