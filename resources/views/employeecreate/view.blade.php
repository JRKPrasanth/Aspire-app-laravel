@extends('layouts.header')
@section('content')
<body>
<div class="container main_container">	
<!------------------------- breadcrumbs start here --------------------------->
<div class="row">
<div class="col-lg-12 col-md-12">		
</div>
</div>
<!---------------------------------------------------------------------------->	
<div class="row">
<div class="col-lg-1">
</div>
<form>
<div class="col-lg-12">
<div class="card">
<div class="card-header">
<strong>Department Details</strong> 
<span class="ui_close_btn"><a href="../employeedepartment" class="collapse-close pull-right btn-danger" onclick="../manufacturerpartno"></a></span>
</div>
<div class="card-body card-block normalform">
    <div class="col-md-offset-2 col-md-6"> 
       
        <table  class="table table-bordered table-hover ">
            <tbody>
                <tr>
                    <td>Department Name:</td>
                    <td>{{$values['department_name'] }}</td>
                </tr>
                <tr>
                        <td>Description:</td>
                        <td>{{ $values['description'] }}</td>
                </tr>
           
            </tbody>
        </table>
       
    </div>
   
    
    
</div>	
</div>
</div>
@extends('layouts.footer')
@endsection