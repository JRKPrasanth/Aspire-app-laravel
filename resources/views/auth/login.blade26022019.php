@extends('layouts_login.app')

@section('content')


    <link rel="stylesheet" href="{{ asset('css/style.min.css') }}">
       <div class="col-md-4" style="float: right;
    ">
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
                 <div class="panel-footer text-center" style="color:#fff;font-weight: bolder;font-size: 13px;text-shadow: 0px 1px 1px rgba(0,0,0,1)">Copyright © 2018 <img src="{{ asset('images/logo.png') }}" style="width: 35px;height: 30px !important;vertical-align: baseline;"> Technology Pvt Ltd</div>
            </div>
            </div>
        </div>
   




    </div>




</div>








<style type="text/css">

 

</style>



<script>
	(function(){
	$(".company_id").jCombologin("{{ URL::to('jcomboform?table=m_company_t:company_id:company_name')}}&order_by=company_name asc",
	 		{selected_value:""});
	});
    
</script>
<script>

window.onload = function () {
     // Array of Images
      var backgroundImg=["{{asset('images/profile_images/bg1.jpg')}}",
                        "{{asset('images/profile_images/bg2.jpg')}}",
                        "{{asset('images/profile_images/bg3.jpg')}}",
                        "{{asset('images/profile_images/bg4.jpg')}}",
                        "{{asset('images/profile_images/bg5.jpg')}}",
                        "{{asset('images/profile_images/bg6.jpg')}}"
                        ];

        setInterval(changeImage, 5000);
       function changeImage() {   
        var i = Math.floor((Math.random() * 6));
        //console.log(Math.random()*6);

        document.body.style.backgroundImage = "url('"+backgroundImg[i]+"')";

      }
    }

</script>
@endsection
