<html>
<head>
<meta charset="utf-8">
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name', 'Laravel') }}</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="keywords" content="ERP" />
<meta name="description" content="ERP-APPLICATIONS">
<meta name="author" content="I-FIVE">

<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/fav.png') }}">
<link rel="stylesheet" href="{{ asset('css/style.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
<link href="{{ asset('js/bs-iconpicker/css/bootstrap-iconpicker.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/meanmenu.css') }}">
<link rel="stylesheet" href="{{ asset('css/style.css')}}">
<link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css')}}" />
<link rel="stylesheet" href="{{asset('css/jquery-ui.css')}}">
<link rel="stylesheet" href="{{asset('css/ui.jqgrid.css')}}">
<!---------------------- Date Picker ------------------------->

<!----------------------- customize css ---------------------->
<link rel="stylesheet" href="{{asset('css/customize.css')}}">
<link rel="stylesheet" href="{{asset('css/sweet-alert.css')}}">
<link href="https://fonts.googleapis.com/css?family=Prompt" rel="stylesheet">

<!-- ------------------------ Dashboard 1 -------------------- -->
<link rel="stylesheet" type="text/css" href="{{asset('dashboard/dashboard_2.css')}}">


<script src="{{ asset('js/jquery-1.12.0.min.js')}}"></script>
<script src="{{ asset('js/jquery-ui.js')}}"></script>
<!-- bootstrap js -->
<script src="{{ asset('js/bootstrap.min.js')}}"></script>
<!-- Mega Menu js -->
<script src="{{ asset('js/jquery.meanmenu.js')}}"></script>
<script src="{{asset('js/jquery.jqgrid.min.js')}}"></script>
<script src="{{ asset('js/jquery.jCombo.min.js') }}"></script>

<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
<script src="{{ asset('js/select2.min.js') }}"></script>
<!------------------------ Clone row Js --------------------------->
<script src="{{ asset('js/reCopy.js') }}"></script>

<!-------------------- Drag menus ------------------->
<link href="{{ asset('js/bs-iconpicker/css/bootstrap-iconpicker.min.css') }}" rel="stylesheet">
<script src="{{ asset('js/jquery-menu-editor.js?v5') }}"></script>
<script src="{{ asset('js/bs-iconpicker/js/iconset/iconset-fontawesome-4.2.0.min.js') }}"></script>
<script src="{{ asset('js/bs-iconpicker/js/bootstrap-iconpicker.js') }}"></script>
<!-------------------- running ticker --------------->
<script src="{{ asset('js/jquery.webticker.min.js') }}"></script>
<script src="{{ asset('js/fullscreen.js')}}"></script>
<!------------------------ Customize Js --------------------------->
<script src="{{ asset('js/custom.js')}}"></script>

<script type="text/javascript" src="http://uxliner.com/adminkit/demo/minisidebar/ltr/dist/js/adminkit.js"></script>

</head>


<body class="sidebar-mini sidebar-collapse">
<div class="wrapper boxed-wrapper">
 

  <!--  ------------Top header-------------- -->

  <header class="main-header"> 
    
    <nav class="navbar blue-bg navbar-static-top"> 

        <ul class="nav navbar-nav pull-left">
        <li><a class="sidebar-toggle" data-toggle="push-menu" href=""></a> </li>
        </ul>

    <div class="headermenus">

       <div class="topborder">
        <div class="row">
        <div class="col-md-1 col-sm-1">
            <div class="header-logo home2-header-logo home2-header-logo2">
                <a href="#"><img src="{{ asset('images/logo.png') }}" alt="Company Logo" class="logoimg" />
                </a>
            </div>
        </div>

        <div class="col-lg-8 col-md-8">
            <div class="card1">
               <div class="card-body1">
                <ul id="webticker-7">
                <li><i class="icon-people"></i>&nbsp;<span class="text-info"> UserName </span><span class="text-warning">&nbsp; M.Chandru</span></li>
                <li><i class="icon-map"></i>&nbsp;<span class="text-info"> Location </span><span class="text-warning">&nbsp; Triplicane,Chennai</span></li>
                <li><i class="icon-home"></i>&nbsp;<span class="text-info"> Organization </span><span class="text-warning"> &nbsp;i5 Tech pvt ltd...</span></li>
                </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-3">
            <ul class="navbar-nav my-lg-0">
              <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown"> <i class="icon-home"></i>
              </a>
                <ul class="dropdown-menu">
                  <div class="gx-card-header">
                  <div class="mr-auto">
                  <h3 class="card-heading">User Info</h3>
                  </div>
                  </div>
              <li><a href="#"><i class="icon-speedometer"></i>Dashboard</a></li>
              <li><a href="#"><i class="icon-info"></i>Profile</a></li>
              <li><a href="logout"><i class="icon-logout"></i>Logout</a></li>
              </ul>
            </li>

            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown"> <i class="icon-note"></i>
              <div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
              </a>
                <ul class="dropdown-menu">
                  <div class="gx-card-header">
                  <div class="mr-auto">
                  <h3 class="card-heading">Messages</h3>
                  </div>
                  </div>
                <li class="notifi">
                    <p>Fgram project’s new iOS app is launched today</p>
                </li>
                <li class="notifi">
                    <p>Fgram project’s new iOS app is launched today</p>
                </li>
                </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown"> <i class="icon-settings"></i>

                    </a>

                </li>

                <li class="nav-item dropdown">
                    <a class="request-fullscreen nav-link dropdown-toggle waves-effect waves-dark" href="#" data-toggle="dropdown"> <i class="icon-size-fullscreen"></i>

                    </a>

                </li>
            </ul>

        </div>
    </div>
</div>
</div>
<!-- ------------------- -------------------- -->

<!--header main menu start-->
<div id="sticky-header" class="main-menu-wrapper home2-menu header-sticky">

    <div class="row">

        <div class="col-md-12 col-sm-12 hidden-xs">
            @include('layouts.menu')

            <!---------------------------- Mobile Menu Start -------------------------------->
        </div>

    </div>

    <!--header main menu end-->
</div>
</nav>
</header>

 <!-- -------------------Sidebar--------------------- -->
  <aside class="main-sidebar"> 
    <!-- sidebar -->
    <div class="sidebar"> 
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="image text-center"><img src="images/img1.jpg" class="img-circle" alt="User Image"> </div>
        <div class="info">
          <p>Alexander Pierce</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a> </div>
      </div>
      
      <!-- sidebar menu -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">PERSONAL</li>
        
       
        <li class="treeview"> <a href="#"> <i class="ti-email"></i> <span>Inbox</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
          <ul class="treeview-menu">
            <li><a href="#"><i class="fa fa-angle-right"></i> Mailbox</a></li>
            
          </ul>
        </li>
        <li class="treeview"> <a href="#"> <i class="icon-frame"></i> <span>UI Elements</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
          <ul class="treeview-menu">
            <li><a href="#" class="active"><i class="fa fa-angle-right"></i> Cards</a></li>
            <li><a href="#"><i class="fa fa-angle-right"></i> User Cards</a></li>
           
          </ul>
        </li>
        <li class="header">FORMS, TABLE & WIDGETS</li>
        
        <li class="header">EXTRA COMPONENTS</li>
     
    
      
      </ul>
    </div>
    <!-- /.sidebar --> 
  </aside>
 <!-- ---------------------------------------------- -->

<div class="container content-wrapper">
@yield('content')
</div>


</div>

<!----------------------- Bottom to top ------------------------------->
<button class="scroll-top tran3s">
        <i class="fa fa-angle-up" aria-hidden="true"></i>
</button>



<script>
$(document).on('click','.contentonly',function() {

	var content = $(this).attr('href');
	$.get(content,function(data){
		$('.contentdata').html(data);
	});
	return false;

});


/***************To Show Success Notification ***********/
function notyMsg(status,msg){
notif({
type: status,
msg: msg,
/*width: "all",*/
position: "right"
});
}
/***************To Show Success Notification End ***********/
function notyMsgs(status,msg){
notif({
type: status,
msg: msg,
/*width: "all",*/
timeout:1800,
position: "right"
});
}

function cloneRow(cloneClass,cloneBody)
{
var cloned = $('.'+cloneClass).find('tr:eq(1)').clone();
cloned.find("input[type=text],input[type=hidden], textarea,input[type=date],select").val("");
cloned.appendTo('.'+cloneBody);
}
function notyMessage(status,message,red_url,create_url,edit_url)
{
swal(
  {
	title: status,
	text: message,
	type: status,
	/*timer: 2e3 */
	  showCancelButton: !0,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Ok",
		cancelButtonText: "Create",
		confirmButtonText: "Ok",
		closeOnConfirm: !1,
		//timer: 2e3,
		closeOnCancel: !1
  }
)
setTimeout(function () {
//window.location.href=red_url;
}, 2000);

$(document).on('click','.confirm',function() {
	window.location.href=red_url;
});

$(document).on('click','.cancel',function() {
	window.location.href=create_url;
});

$(document).on('click','.apply',function() {
	window.location.href=edit_url;
});

}
/***************To check product Already Selected ***********/
function pdtcheck(product_id,index)
	{
		var pdtcount = 0;
		$('.clone').each(function (ind, v)
		{
			var val = $(".bulk_product_id" + ind).val();
			if(index != ind) 
			{
			if(val == product_id)
			{
				pdtcount++;
			}
			}
		});
		return pdtcount;
	}
/***************To check product Already Selected End***********/
function notysuccess(message,status)
{
swal({
  position: 'top-right',
  type: status,
  title: message,
  showConfirmButton: false,
  timer:1500

});
}

</script>
<script src="{{ asset('js/sweet-alert.min.js') }}"></script>
<script src="{{ asset('js/sweet-alert.init.js') }}"></script>

<script src="{{ asset('js/notifIt.js') }}" type="text/javascript"></script>
<link href="{{ asset('css/notifIt.css') }}" type="text/css" rel="stylesheet">
</body>
</html>