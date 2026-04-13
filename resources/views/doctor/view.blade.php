@extends('layouts.header')
@section('content')

<form>
    {{ csrf_field() }}
    
    <div class="card">
        <div class="card-header">
            
            <span class="ui_close_btn">  <a href="../doctor" class="collapse-close pull-right btn btn-xs btn-danger" onclick="doctor"></a></span>
        </div>

        <div class="card-body card-block">   
        <div class="row">
        <div class="col-md-12">



<div class="invoice-box" id="section-to-print">

    <table cellpadding="0" cellspacing="0">
        <tbody>

            <h2 class="heads1">Doctor Details</h2>

            <tr class="information">
                <td colspan="6">
                    <table>

                        <tbody>

                            <tr>
                                <td>
                                    <p><b>Doctor First Name:</b>{{ $doctor->doctor_name }}</p> <br>
                                    <p><b>Doctor Type:</b>{{$doctor->doctor_type}}</p> <br>
                                    <p><b>Doctor Number:</b>{{$doctor->doctor_phone_number}}</p> <br>
                                    <p><b>Doctor Email ID:</b>{{$doctor->doctor_email_id}}</p> <br>
                                    <p><b>Gender:</b> {{ $doctor->gender }}</p> <br>
                                    <p><b>Marital Status:</b> {{ $doctor->marital_status }}</p> <br>
                                    <p><b>Category:</b> {{$grade }}</p> <br>
                                    <p><b>Degree:</b> {{$degree }}</p> <br>
                                    <p><b>Chemist:</b> {{$chemist }}</p> <br>
                                    <p><b>Stockist:</b> {{$stockist}}</p> <br>
                                    <p><b>Association:</b> {{$doctor->association}}</p> <br>
                       
                                    
                                </td>
                                <td class="text-right">
                                    
                                    <p><b>Doctor Last Name:</b> {{$doctor->last_name }}</p> <br>
                                    <p><b>Specialization:</b> {{$specialization }}</p> <br>
                                    <p><b>DOA:</b> {{$doctor->doa }}</p> <br>
                                    <p><b>DOB:</b> {{$doctor->dob }}</p> <br>
                                    <p><b>From Time:</b> {{$doctor->from_time }}</p> <br>
                                    <p><b>To Time:</b> {{$doctor->to_time }}</p> <br>
                                    <p><b>Days:</b> {{$days }}</p> <br>
                                    <p><b>Remarks:</b> {{$doctor->remarks }}</p> <br>
                                    <p><b>active:</b> {{$doctor->active }}</p> <br>
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
                            <th>Clinic OR Hospital Name</th>
                            <th>Address</th>
                            <th>Country Name</th>
                            <th>State Name</th>
                            <th>City Name</th>
                            <th>Area</th>
                            <th>Pincode</th>
                        </tr>
                    </thead>
                    <tbody> <?php //dd($vlinesdata); ?>
                        @foreach ($linesdata as $key=>$value) 
                            <tr>
                               <td>{{ $key+1 }}</td>
                               <td>{{ $value->clinic_name}}</td>
                               <td>{{ $value->doctor_address}}</td>
                               <td>{{ $value->country_id}}</td>
                               <td>{{ $value->state_id}}</td>
                               <td>{{ $value->city_id}}</td>
                               <td>{{ $value->area}}</td>
                               <td>{{ $value->pin_code}}</td>
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

