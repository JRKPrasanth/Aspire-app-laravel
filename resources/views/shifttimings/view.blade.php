@extends('layouts.header')
@section('content')

<form>
<div class="card">
<div class="card-header">
<h2>Department Details</h2> 
<span class="ui_close_btn"><a href="../employeedepartment" class="collapse-close pull-right btn-danger" onclick="../manufacturerpartno"></a></span>
</div>
<div class="card-body card-block normalform">
    <div class="row">
    <div class="col-md-12"> 
       
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
</form>
@endsection