@extends('layouts.login')

@section('content')
<?php //dd('fgfg');?>
<form method="post" action="{{ url('Login/signin')}}" class="form-vertical" onsubmit="return validateDropDown('ddl');">

	

        <div class="sbox ">
           
	<div class="sbox-content sbox_content" style="overflow-y:hidden;height:auto">
	    	@if(Session::has('message'))
				{!! Session::get('message') !!}
			@endif
		<ul class="parsley-error-list">
			@foreach($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>		
		
	
	<div class="tab-content" >
		<div class="tab-pane active m-t" id="tab-sign-in">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
		
			<div class="form-group has-feedback animated fadeInLeft delayp1">
				<label>Email	</label>
				<input type="text" name="email" placeholder="Email Address" class="form-control log_in"/>
                                <label>User Name	</label>
                                </br>
                                <input type="text" name="username" placeholder="User Name" class="form-control log_in" />
                                
				<i class="icon-users form-control-feedback"></i>
			</div>
			
			<div class="form-group has-feedback  animated fadeInRight delayp1">
				<label>Password	</label>
				<input type="password" name="password" placeholder="Password" class="form-control log_in" required="true" value="" />
				
				<i class="icon-lock form-control-feedback"></i>
			</div>
                <div class="form-group has-feedback animated fadeInLeft delayp1">
                              
			</div>

			<div class="form-group has-feedback  animated fadeInRight delayp1">
				<label> Remember Me ?	</label>
                                <input type="checkbox" name="remember" value="1" />
				
			</div>


		

			

			<div class="form-group  has-feedback text-center  animated fadeInLeft delayp1" style=" margin-bottom:20px;" >
                            <button type="submit" class="btn btn-info btn-sm btn-block log_btn" ><i class="fa fa-sign-in"></i> Sign In</button>
                            <div class="clr"></div>
			</div>	</div>	 
  </div>
        </div></div>
		   </form>			
			
	

    
<script type="text/javascript">
$(document).ready(function() {
	

	});
</script>
@stop

