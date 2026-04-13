@extends('layouts_login.app')

@section('content')


    <link rel="stylesheet" href="{{ asset('css/style.min.css') }}">

<img class="mySlides" src="{{asset('images/profile_images/bg1.jpg')}}" style="width:100%;height:92vh;">
<img class="mySlides" src="{{asset('images/profile_images/bg2.jpg')}}" style="width:100%;height:92vh;">
<img class="mySlides" src="{{asset('images/profile_images/bg3.jpg')}}" style="width:100%;height:92vh;">
<img class="mySlides" src="{{asset('images/profile_images/bg4.jpg')}}" style="width:100%;height:92vh;">
 

  
        <div class="col-md-4" style="float: right;
    margin-top: -90vh;">
            <div class="panel panel-default">

            <div class="">
            <div class="service-icon">
                            
            </div>

                
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="control-label">E-Mail Address</label>

                            <div>
                                <input id="email" type="email" class="form-control" name="email" 
                                value="{{ old('email') }}" required autofocus style="color:#fff;font-weight:bolder;background: url(images/doctor.png);">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="control-label">Password</label>

                            <div>
                                <input id="password" type="password" class="form-control" name="password" required style="color:#fff;font-weight:bolder;background: url(images/key.png);" >

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        
                        <div class="form-group{{ $errors->has('company_id') ? ' has-error' : '' }}">
                            <label for="company" class="control-label">Company</label>

                            <div>
                                <select name='company_id' rows='5' class='form-control company_id select2'  data-show-subtext="true" data-live-search="true"  required>
              
               
               {!! config('global.CONT')->jCombologin('m_company_t','company_id','company_name','')!!}
								</select>
                                @if ($errors->has('company_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('company_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        
                        
                        
                        <div class="form-group">

                        <div class="c-checkbox">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span class="check_mark"></span> <label class="loginlink"> Remember Me</label></div>

                            


                        </div>

                        <div class="form-group">
                            <center>
                                <button type="submit" class="btn btn-primary custom">
                                    Login
                                </button><br>
                                <a class="loginlink" href="{{ route('password.request') }}">
                                    Forgot Your Password?
                                </a>
                            </center>
                           
                        </div>
                    </form>
                </div>
            </div>
            </div>
        </div>
   




    </div>




</div>








<style type="text/css">
 .mySlides{
	 display:none;
 }
 .footer {
    position: fixed;
    bottom: 0;
    z-index: 9999;
    width: 100%;
    background: #eee;
    height: 32px;
}
 .footer p {
    margin: 9px 0 !important;
    text-align: center !important;
    color: #000 !important;
    font-family: Rapid-Finger-101 !important;
    letter-spacing: 2px !important;
    font-size: 10px !important;
}
</style>



<script>
	(function(){
	$(".company_id").jCombologin("{{ URL::to('jcomboform?table=m_company_t:company_id:company_name')}}&order_by=company_name asc",
	 		{selected_value:""});
	});
    
</script>
<script>
var myIndex = 0;
carousel();

function carousel() {

    var i;
    var x = document.getElementsByClassName("mySlides");
    for (i = 0; i < x.length; i++) {
       x[i].style.display = "none";  
    }
    myIndex++;
    if (myIndex > x.length) {myIndex = 1}    
    x[myIndex-1].style.display = "block";  
    setTimeout(carousel, 6000); // Change image every 2 seconds
}
</script>
@endsection
