@extends('layouts.header')
@section('content')

<div class="card">
<div class="card-header">
<h2>Company Details</h2> 
<span class="ui_close_btn"><a href="../company" class="collapse-close pull-right btn-danger" onclick='location.href="{{ url($pageModule) }}"'></a></span>
</div>
<div class="card-body card-block normalform">
    <div class="row">
    <div class="col-md-12"> 
       
        <table  class="table table-bordered table-hover ">
            <tbody>
                <tr>
                    <td>Company Name:</td>
                    <td>{{$headerdata->company_name }}</td>
                </tr>
                <tr>
                        <td>Company Code:</td>
                        <td>{{ $headerdata->company_code }}</td>
                </tr>
                 <tr>
                        <td>Website Address:</td>
                        <td>{{ $headerdata->website_address }}</td>
                </tr>
                 <tr>
                        <td>Organization:</td>
                        <td>{{ $headerdata->organization_name }}</td>
                </tr>
                 <tr>
                        <td>GST No:</td>
                        <td>{{$headerdata->gst_no }}</td>
                </tr>
           
            </tbody>
        </table>
       
    </div>
   
    
    <div class="col-md-12">

    
    <h4>&nbsp;Location Details</h4>
<table  class="table table-bordered table-hover ">
			<thead>
			<tr>
			<th>Line No</th>
			<th>Location Name</th>
			<th>Description</th>
			
			</tr>
			</thead>
<tbody>
	<?php foreach ($linesdata as $key => $value): ?>
<tr>
     <td>{!! $key+1 !!}</td>
     <td>{!! $value->location_name !!}</td>
     <td>{!! $value->description !!}</td>
    
	 </tr>
	<?php endforeach; ?>

</tbody>
</table>
</div>
</div>
</div>  
</div>
@endsection