<?php
error_reporting(0);
//dd($urlmenu);

setcookie("start", time () + 60*58);
if(isset($_GET['ref_id'])){


  $ref_id = $_GET['ref_id'];
      \DB::table('notifications_t')->where('notification_id',$ref_id)->update(['read/unread' => 'read']);
  }
date_default_timezone_set('Asia/Calcutta');
$end_time=date('Y-m-d H:i:s', strtotime("+1 minutes"));
$start_time=date('Y-m-d H:i:s');
 \Session::put('session_time',$end_time);

if(\Session::get('id'))
  {

  }
 else {

 $actual_link = URL::to('logout');

   echo '  <script>

            location.replace("'.$actual_link.'")

      </script>';
}?>

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

<!--script  src="{{ asset('css/bootstrap.min.js') }}"></script-->
<script  src="{{ asset('css/jquery.min.js') }}"></script>

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

<link rel="stylesheet" href="{{asset('dashboard/newone.css')}}">
<link rel="stylesheet" href="{{asset('css/ui.fancytree.min.css')}}">

<script src="{{asset('js/js/jquery.richtext.js')}}"></script>
<link rel="stylesheet" href="{{asset('css/css/richtextmin/richtext.min.css')}}">
<!---------------------- Date Picker ------------------------->

<!----------------------- customize css ---------------------->
<link rel="stylesheet" href="{{asset('css/customize.css')}}">
<link rel="stylesheet" href="{{asset('css/sweet-alert.css')}}">
<!-- ------------------------ Dashboard 1 -------------------- -->
<link rel="stylesheet" type="text/css" href="{{asset('dashboard/dashboard_2.css')}}">


<script src="{{ asset('js/jquery-1.12.0.min.js')}}"></script>
<script src="{{ asset('js/jquery.cookie.js')}}"></script>
<script src="{{ asset('js/bs-iconpicker/js/bootstrap-iconpicker.js') }}"></script>

<!-- Dashboard  -->

<script src="{{ asset('js/jquery.countTo.js') }}"></script>


<!-- bootstrap js -->
<script src="{{ asset('js/bootstrap.min.js')}}"></script>
<script src="{{ asset('js/jquery.jCombo.min.js') }}"></script>

<script src="{{ asset('js/jquery-ui.js')}}"></script>
<!-- Mega Menu js -->
<script src="{{ asset('js/jquery.meanmenu.js')}}"></script>




<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
<script src="{{ asset('js/select2.min.js') }}"></script>


<script src="{{ asset('js/reCopy.js') }}"></script>

<script src="{{ asset('js/jquery.aCollapTable.js')}}"></script>
<script src="{{asset('js/jquery.aCollapTable.min.js')}}"></script>

<link href="{{ asset('js/bs-iconpicker/css/bootstrap-iconpicker.min.css') }}" rel="stylesheet">
<script src="{{ asset('js/jquery-menu-editor.js?v5') }}"></script>


<!-- <script src="{{ asset('js/bs-iconpicker/js/jquery.fancytree-all.min.js') }}"></script> -->

<!------------------------ Toggle form ---------------------------->
<script src="{{ asset('js/ziehharmonika.js') }}"></script>



<script src="{{ asset('js/fullscreen.js')}}"></script>
<!-- <script src="{{ asset('js/moment.js')}}"></script> -->
<script src="{{ asset('js/bootstrap-datetimepicker.js')}}"></script>

<!---dashboard widget script--->
<!-- <script src="{{ asset('js/fullscreen.js')}}"></script> -->



<script src="{{ asset('js/widget/jquery.newsTicker.js')}}"></script>
<script src="{{ asset('js/widget/jquery.clock.js')}}"></script>

<!--jqgrid-->
<!--<script src="http://www.guriddo.net/demo/css/trirand/ui.jqgrid.css"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/free-jqgrid/4.14.1/plugins/min/ui.multiselect.js"></script>

<!--script src="http://www.guriddo.net/demo/js/trirand/src/jquery.jqGrid.js"></script>-->
<script src="{{ asset('js/pdfmake.min.js') }}"></script>
<script src="{{ asset('js/jqgridfont.js') }}"></script>
<link rel="stylesheet" href="{{asset('css/ui.jqgrid.css')}}">

<script src="https://cdnjs.cloudflare.com/ajax/libs/free-jqgrid/4.14.1/plugins/min/ui.multiselect.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/free-jqgrid/4.14.1/plugins/css/ui.multiselect.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="{{asset('js/grid.locale-en.js')}}"></script>
<script src="{{ asset('js/jquery.jqgrid.min.js') }}"></script>





<link rel="stylesheet" href="{{asset('css/ui.multiselect.css')}}">
<script src="{{ asset('js/sweet-alert.min.js') }}"></script>
<script src="{{ asset('js/sweet-alert.init.js') }}"></script>


<script src="{{ asset('js/notifIt.js') }}" type="text/javascript"></script>
<link href="{{ asset('css/notifIt.css') }}" type="text/css" rel="stylesheet">



<link href="{{ asset('css/bootstrap-datetimepicker.css') }}" rel="stylesheet">


<!---------------------------- Line table new -------------------------------->
<script src="{{ asset('dashboard/kidd.stickytable.min.js') }}"></script>
<link href="{{ asset('dashboard/kidd.stickytable.css') }}" type="text/css" rel="stylesheet">
<link href="{{ asset('dashboard/colpick.css') }}" type="text/css" rel="stylesheet">

<script src="{{ asset('js/custom.js')}}"></script>



<script  src="{{asset('js/plugins/parsley/src/parsley.js')}}"></script>
<link rel="stylesheet" href="{{asset('js/plugins/parsley/src/parsley.css')}}">
	<script src="{{ asset('js/moment.js')}}"></script>
<script src="{{ asset('js/daterangepicker.js')}}"></script>
<link href="{{ asset('css/daterangepicker.css') }}" type="text/css" rel="stylesheet">
<script src="{{ asset('js/highcharts.js')}}"></script>
<script src="{{ asset('js/exporting.js')}}"></script>
<script src="{{ asset('js/export-data.js')}}"></script>
<script src="{{ asset('js/offline-exporting.js')}}"></script>
	<!-- <script src="https://code.highcharts.com/modules/drilldown.js"></script> -->

<script src="{{ asset('js/jquery.countdownTimer.js') }}"></script>
	<script src="{{ asset('js/bootstrap-session-timeout.js') }}"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript">
var jQuery_1_9_1 = $.noConflict(true);
</script>
<script type="text/javascript">
      $(document).ready(function(){
        var nt_title = $('#nt-title').newsTicker({
          row_height: 18,
          max_rows: 1,
          duration: 5000,
          pauseOnHover: 0
          });
          $('#digital-clock').clock({
            offset: '+5',
            type: 'digital'
        });

        var monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];
        var dayNames = ["Sun, ", "Mon, ", "Tue, ", "Wed, ", "Thu, ", "Fri, ", "Sat, "]

        var newDate = new Date();
        newDate.setDate(newDate.getDate() + 1);
        $('#Date').html(dayNames[newDate.getDay()] + " " + newDate.getDate() + ' ' + monthNames[newDate.getMonth()] + ' ' + newDate.getFullYear());


        var url=0;
        <?php  if(!empty($pageMethod)){   ?>

            var url="{{URL::to($pageMethod)}}";
            $('a[href="'+url+'"]').removeClass('menu3css');
        <?php  } ?>

          $('a[href="'+url+'"]').addClass('menu3css');
     $('a[href="'+url+'"]').closest('.mega-parent').find('.three-d-box .front').addClass('active1');

      });

    </script>
</head>
<body class="fix-header fix-sidebar card-no-border">



<div class="modal fade" id="logout_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog " style="width:480px;">
		<div class="modal-content">
			<div class="modal-body">
				<div class="row">
					<div class="col-md-2">
						<i class="fa fa-warning" style="margin-top:1.1em;font-size:3.5em;color:#da4f49"></i>
					</div>
					<div class="col-md-10 session_popup">
						<h4>Your session is about to expire!</h4>
						<p style="font-size: 15px;">You will be logged out in <span id="timer" style="display: inline;font-size: 30px;font-style: bold"><span id="seconds_time"></span>
						<p style="font-size: 15px;">Do you want to stay signed in?</p>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="row text-center">
				<a href="javascript:;" onclick="window.location.reload();" class="btn btn-primary sign_in" aria-hidden="true">Yes, Keep me signed in</a>
				<a href="{{URL::to('logout') }}" class="btn btn-danger" aria-hidden="true">No, Sign me out</a>
			</div>
		</div>
	</div>
</div>

<!------------------------------------ Header menu start from here -------------------------------------->

 <header class="m-header">
    <div class="m-header-wrapper">
      <!--sidebar menu toggler start -->
      <div class="m-toggle-sidebar m-material-button">
        <i class="icon-login m-open-icon"></i>
        <i class="icon-logout m-close-icon"></i>
      </div>
      <!--sidebar menu toggler end -->
      <!--logo start -->
      <div class="m-logo-box">
        <h1>
          <a href="{{URL::to('home')}}" class="m-logo">
            <img class="img-fluid m-logo-mobile" src="{{ asset('images/jrks.png') }}" >
            <img class=" img-fluid m-logo-desktop img-responsive" src="{{ asset('images/jrks.png') }}" >
          </a>
        </h1>
		
		
		
      </div>
      <!--logo end -->

<div class="m-header-center m-with-seperator" style="
    display: inline-block;
    padding-left: 50%;
    padding-top: 19px;
    color: #eee;
    /* background-color: aqua; */
    float: centre;
">
<span style="background-color: #f93f34;padding: 7px;font-weight: 900;font-family: serif;border-radius: 13px;">FY 2022 - 23</span>    
<span style="
    background-color: #1c2c7780;
    padding: 7px;
    font-weight: 900;
    font-family: serif;
    border-radius: 13px;
" >Hi {{\Session::get('first_name')}}...!</span>
</div>
      <div class="m-header-right m-with-seperator">

        <!-- header right menu start -->
        <ul class="m-header-navigation">


          <li>
            <a href="{{URL::to('home')}}" class="m-material-button"><i class="icon-home"></i></a>
          </li>


          <li>
          <?php $user=\Session::get('id'); $comp=\Session::get('companyid'); $loc=\Session::get('location'); $count_no = \DB::table('notifications_t')->where('read/unread','unread')->where('company_id',$comp)->where('location_id',$loc)->where('user_id',$user)->select('*')->get(); ?>

            <a href="#" class="m-material-button m-submenu-toggle"><i class="icon-bell"><?php $count_noti = count($count_no); if( $count_noti > 0) { ?><span class="button__badge"> <?php  echo $count_noti; ?></span> <?php } ?></i></a>
            <div class="m-header-submenu m-notify-list">

                <div class="notification_home">
                  <?php if(count($count_no) > 0 ){ foreach ($count_no as $key => $value) { ?>

                    <div class="alert alert-success">
                        <a href="{{URL::to($value->reference_url.'?ref_id='.$value->notification_id)}}" style="color: green;" ><?php echo $value->description; ?> </a>

                    </div>
                  <?php  } } ?>

              </div>
            </div>
          </li>
          <li class="User">

            <a href="#" class="m-material-button m-submenu-toggle"><span class="m-user-avatar">
              <img src="{{ asset('images/profile_images/'.\Session::get('img')) }}" width="32" height="32"></span></a>
            <div class="m-header-submenu">
              <ul>
                <li><a href="{{URL::to('userprofile')}}/{{\Session::get('id')}}?pagemethod=myprofile">My profile</a></li>
                <li><a href="#">Activity</a></li>
                <li><a href="#">Messages</a></li>
                <li><a href="{{URL::to('logout')}}">Logout</a></li>
              </ul>
            </div>
          </li>
         <!--  <li class="m-hide-on-mobile" style="background: #345a7f;" >
            <a href="#" class="m-material-button" style="color:#fff"><i class="icon-grid"></i></a>
            <div class="m-header-submenu m-extra-menu">
              <a href="#">
                <i class="material-icons">&#xE030;</i>
                <span>Musics</span>
              </a>
              <a href="#">
                <i class="material-icons">&#xE04A;</i>
                <span>Videos</span>
              </a>
              <a href="#">
                <i class="material-icons">&#xE158;</i>
                <span>Emails</span>
              </a>
              <a href="#">
                <i class="material-icons">&#xE160;</i>
                <span>Reports</span>
              </a>
              <a href="#">
                <i class="material-icons">&#xE24E;</i>
                <span>Persons</span>
              </a>
              <a href="#">
                <i class="material-icons">&#xE251;</i>
                <span>Pictures</span>
              </a>
            </div>
          </li> -->
        </ul>
        <!-- header right menu end -->

      </div>

      <!--header search panel start -->
      <div class="m-search-bar">
        <form class="m-search-form">
          <div class="m-search-input-wrapper">
            <input type="text" name="qq" placeholder="search something..." class="m-search-input">
            <button type="submit" name="search" class="m-search-submit"><i class="material-icons">&#xE5C8;</i></button>
          </div>
          <span class="m-search-close m-search-toggle">
            <i class="material-icons">&#xE5CD;</i>
          </span>
        </form>
      </div>
    </div>
    <!--header main menu start-->
    <div id="sticky-header" class="main-menu-wrapper home2-menu header-sticky">
        <div class="row">

            <div class="">

                @include('layouts.menu')

    </div>
    <div class="mobile-Menu" style="display: none;">
       @include('layouts.mobilemenu')
    </div>
        </div>


    </div>
    <!--header main menu end-->

  </header>

<!-- header end -->
<!------------------------------ header menu end here ----------------------------->

<!-- Left sidebar menu start -->
  <div class="m-sidebar">
    <div class="m-sidebar-wrapper">

      <!-- side menu logo start -->
      <div class="m-sidebar-logo">
        <div class="m-sidebar-pin-button" title="Pin/Unpin Menu">
          <i class="material-icons m-fixed-icon">gps_fixed</i>
          <i class="material-icons m-not-fixed-icon">gps_not_fixed</i>
        </div>
        <div class="m-sidebar-toggle-button">
          <i class="material-icons">&#xE317;</i>
        </div>
      </div>
      <!-- side menu logo end -->

      <!-- sidebar menu start -->
      <nav class="m-sidebar-navi">
        <ul>
          <li class="show">
            <a href="#" class="m-material-button">
              <span class="m-icon"><i class="material-icons">&#xE88A;</i></span>
                      <span class="m-label">Dashborad</span>
                    </a>
                </li>
                <li>
            <a href="#" class="m-material-button">
              <span class="m-icon"><i class="material-icons">&#xE871;</i></span>
                      <span class="m-label">Layouts</span>
                      <span class="m-arrow-icon"><i class="material-icons">&#xE313;</i></span>
                    </a>
                    <ul>
                      <li>
                        <a href="#" class="m-material-button"><span class="m-label">Boxed Layout</span></a>
                      </li>
                      <li>
                        <a href="#" class="m-material-button"><span class="m-label">Full Layout</span></a>
                      </li>
                    </ul>
                </li>
                <li>
            <a href="#" class="m-material-button">
              <span class="m-icon"><i class="material-icons">&#xE89B;</i></span>
                      <span class="m-label">Reports</span>
                    </a>
                </li>


        </ul>
        <!-- sidebar menu end -->
      </nav>
      <!-- sidebar menu end -->
    </div>
  </div>
  <!-- Left sidebar menu end -->
  <div class="m-overlay"></div>
  <!--Main container start -->
  <main class="m-wrapper">


    <div class="container">

<div class="bread" style="    margin-top: -0.2em;
    margin-bottom: -0.5em;">@include('layouts.breadcrumb')</div>


    @yield('content')

    @extends('layouts.footer')

    </div>
  </main>
  <!--Main container end -->



<button class="scroll-top tran3s">
        <i class="fa fa-angle-up" aria-hidden="true"></i>
</button>



<script>
$(document).ready(function(){


/***************BreadCrumb Navigation *********************/

var url=0;
  var url="{{URL::to(strtolower($urlmenu['menu']))}}";
  var check="{{$urlmenu['menu']}}";
<?php    if(!empty($pageMethod)){ ?>
 $('a[href="'+url+'"]').removeClass('menu3css');
<?php  } ?>

if(check!='')
{


$('a[href="'+url+'"]').addClass('active1');

$('a[href="'+url+'"]').closest('.mega-parent').find('.menuhover').addClass('active1');


var html=' <li><a href="home">Home</a></li>';
	$('head title').text("ERP");
if($('a[href="'+url+'"]').text()!='')
{

	 var menu_text=$.trim($('a[href="'+url+'"]').text());
         menu_text=menu_text.slice(0,(menu_text.length)/2);
	$('head title').text("ERP | "+menu_text);
        
html+='<li><a href="#" onclick="return false;">'+$('a[href="'+url+'"]').closest('.mega-parent').find('.menuhover').html()+'</a></li>';
html+='<li><a href="#" onclick="return false;">'+$('a[href="'+url+'"]').closest('.single-mega-item').find('.mega-title').find('.mega_sub_menu').html()+'</a></li>';
html+='<li><a href="#" onclick="return false;">'+$('a[href="'+url+'"]').html()+'</a></li>';
}
else
{
     
     var url="{{$urlmenu['menu']}}";
     url=$('a:contains("'+url+'")').attr('href');
     $('a[href="'+url+'"]').closest('.mega-parent').find('.menuhover').addClass('active1');
     $('a[href="'+url+'"]').addClass('active1');
     var menu_text=$.trim($('a[href="'+url+'"]').text());
     menu_text=menu_text.slice(0,(menu_text.length)/2);
	$('head title').text("ERP | "+menu_text);
 html+='<li><a href="#" onclick="return false;">'+$('a[href="'+url+'"]').closest('.mega-parent').find('.menuhover').html()+'</a></li>';
html+='<li><a href="#" onclick="return false;">'+$('a[href="'+url+'"]').closest('.single-mega-item').find('.mega-title').find('.mega_sub_menu').html()+'</a></li>';
html+='<li><a href="#" onclick="return false;">'+$('a[href="'+url+'"]').html()+'</a></li>';   
}
	
    <?php  if(!empty($urlmenu['check'])){ ?>
html+="<li><a href='#' onclick='return false;'>{{$urlmenu['label']}}</a></li>";
<?php } ?>
$(".breadcrumb").html(html);
/***************End BreadCrumb Navigation**********************/
}
});

	 $('form').attr('autocomplete', 'off');
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

timeout:6500,
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
function showcolumn(tableid)
{
$(document).on('click',".showcolumn",function(){

jQuery('#'+tableid).jqGrid('columnChooser');
});
}
/***************To check product Already Selected ***********/
function pdtcheck(product_id,index)
  {
    var pdtcount = 0;
    $('.clone').each(function (ind, v)
    {
      var val = $(".bulk_product_id" + ind).val();
			if($.trim(val)!="" && val!=null)
	{
      if(index != ind)
      {
      if(val == product_id)
      {
        pdtcount++;
      }
      }
      }
    });
    return pdtcount;
  }
  /***************To check invoice Already Selected ***********/
function invcheck(invoice_hdr_id,index)
  {
    var pdtcount = 0;
    $('.clone').each(function (ind, v)
    {
      var val = $(".bulk_invoice_hdr_id" + ind).val();
			if($.trim(val)!="" && val!=null)
	{
      if(index != ind)
      {
      if(val == invoice_hdr_id)
      {
        pdtcount++;
      }
      }
      }
    });
    return pdtcount;
  }
	 /***************To check product type Already Selected in machine ***********/
	function prdtypecheck(product_type_id,index)
  {
    var pdtcount = 0;
    $('.bulk_product_type_id').each(function (ind, v)
    {
      var val = $(".bulk_product_type_id" + ind).val();

	if($.trim(val)!="" && val!=null)
	{

      if(index != ind)
      {
      if(val == product_type_id)
      {
        pdtcount++;
      }
      }
	}
    });
    return pdtcount;
  }
                    /***************To check Purchase invoice Already Selected ***********/
function poinvcheck(invoice_hdr_id,index)
  {
    var pdtcount = 0;
    $('.clone').each(function (ind, v)
    {
      var val = $(".bulk_po_invoice_id" + ind).val();
			if($.trim(val)!="" && val!=null)
	{
      if(index != ind)
      {
      if(val == invoice_hdr_id)
      {
        pdtcount++;
      }
      }
      }
    });
    return pdtcount;
  }
 /***************To check Batch Already Selected in material issue & material Receive***********/
	function batchnocheck(batch_number,index)
  {
    var bcount = 0;
    $('.clone').each(function (ind, v)
    {
      var val = $(".batch_number" + ind).val();
      if(index != ind)
      {
      if(val == batch_number)
      {
        bcount++;
      }
      }
    });
    return bcount;
  }
	/*
	$( "select.select2" ).each(function( index, element ) {
            $(element).before('<div class="sel2" id="moved'+index+'">');
            $("#moved"+index).append(element);
          });*/
 var dateToday = new Date();
  var data ="{{\Session::get('j_date_format')}}";
  
  var grid_startdate="{{\Session::get('js_griddate')}}";
  var grid_enddate="{{\Session::get('js_gridenddate')}}";
  console.log("start "+grid_startdate+" end "+grid_enddate);
  $( ".report_datepicker" ).datepicker({
      changeMonth: true,
      dateFormat: data,
      changeYear: true,
      minDate: "01-04-2019",
      //maxDate: "31-03-2024",
      //maxDate: null,
      onClose: function () {
        $(this).parsley().validate();
        }

    }).attr('readonly', 'readonly');


  $( ".datepicker" ).datepicker({
      changeMonth: true,
      dateFormat: data,
      changeYear: true,
      minDate: grid_startdate,
    //  minDate: "01-04-2021",
      //maxDate: "31-03-2024",
      //maxDate: null,
      maxDate: grid_enddate,
      onClose: function () {
        $(this).parsley().validate();
        }

    }).attr('readonly', 'readonly');
    
   /* $( ".enabledatepicker" ).datepicker({
      changeMonth: true,
      dateFormat: data,
      changeYear: true,
     // minDate: "01-04-2019",
      minDate: "01-04-2021",
      maxDate: "31-03-2024",
      //maxDate: null,
     // maxDate: 0,
      onClose: function () {
        $(this).parsley().validate();
        }

    }).attr('readonly', 'readonly');*/
	
$(".previousdates").datepicker({
    changeYear:true,
    changeMonth:true,
    dataFormat:data,
    maxDate:0
}).attr('readonly', 'readonly');
  $('.datetimepickers').datetimepicker({
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 1,
        format: "dd-mm-yy hh:ii:ss",
        minView: 0,
        maxView: 1,
        forceParse: 0,
        
  }).on('change',function(){
    $(this).parsley().destroy();
  });

  $('.timepicker').datetimepicker({
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 1,
        format: 'hh:ii',
        minView: 0,
        maxView: 1,
        forceParse: 0
  });
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
function change_date()
{
    $( ".datepicker" ).each(function(){
      var myDate = $(this).val();
var parsedDate = $.datepicker.parseDate("{{\Session::get('j_date_format')}}", myDate);
$(this).val($.datepicker.formatDate("yy-mm-dd", parsedDate));
   });
   
}

$(document).ready(function(){
  $( ".datepicker" ).each(function(){
      var myDate = $.trim($(this).val());
 if(myDate!='0000-00-00' && myDate!='')
 {
console.log(myDate);

var parsedDate = $.datepicker.parseDate("yy-mm-dd", myDate);
$(this).val($.datepicker.formatDate("{{\Session::get('j_date_format')}}", parsedDate));
 }
 else {
   $(this).val('');
 }

   });
 
});
</script>
<a href="#" id="scroll" style="display: none;"><span></span></a>
 <style type="text/css">
#scroll {
    position:fixed;
    right:7px;
    bottom:2.40em;
    cursor:pointer;
    width:30px;
    height:30px;
    background-color:rgba(0,0,0,0.5);
    text-indent:-9999px;
    display:none;
    /*-webkit-border-radius:60px;
    -moz-border-radius:60px;
    border-radius:60px*/
}
#scroll span {
    position:absolute;
    top:50%;
    left:50%;
    margin-left:-8px;
    margin-top:-12px;
    height:0;
    width:0;
    border:8px solid transparent;
    border-bottom-color:#ffffff;
}
#scroll:hover {
    background-color:rgba(0, 18, 103, 0.59);;
    opacity:1;filter:"alpha(opacity=100)";
    -ms-filter:"alpha(opacity=100)";
}

    </style>
    <!---------------------- Active menubar ---------------------->
    <script type="text/javascript">



    //$('.container').css("height", $(document).height(),"padding-bottom",);

$(document).ready(function(){

/*isac purpose: back button disable in all pages*/
history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
 /*end*/ 
    $(window).scroll(function(){
        if ($(this).scrollTop() > 80) {
            $('#scroll').fadeIn();
        } else {
            $('#scroll').fadeOut();
        }
    });
    $('#scroll').click(function(){
        $("html, body").animate({ scrollTop: 0 }, 600);
        return false;
    });
var end_actual_time = "{{$end_time}}";
var start_actual_time ="{{$start_time}}";
console.log(end_actual_time);

var interval=setInterval(function(){
start_actual_time = new Date(start_actual_time);
end_actual_time = new Date(end_actual_time);

var date=end_actual_time.getFullYear()+"-"+end_actual_time.getMonth()+1+"-"+end_actual_time.getDate()+" "+end_actual_time.getHours()+":"+end_actual_time.getMinutes()+":"+end_actual_time.getSeconds();


start_actual_time.setTime(start_actual_time.getTime() + (50000));



var diff = end_actual_time - start_actual_time;

var diffSeconds = diff / 1000;
var HH = Math.floor(diffSeconds / 3600);
var MM = Math.floor(diffSeconds % 3600) / 60;


if(HH < 0 && MM < 0)
{
    var url="{{URL::to('timeoutcheck')}}?time="+date;
$.get(url,function(data){
    if($.trim(data)=="1")
    {
      $('#logout_popup').modal('show');
      $('#seconds_time').countdowntimer({
      });

		var int=setInterval(function(){
			$(".sign_in").attr("disabled",true);
                      $.get('timeoutcheck?popup=ok',function(data){
                      });
	},60000);
    }
     else if($.trim(data)=="3")
    {
      $('#logout_popup').modal('show');
    $(".session_popup").html("your Session Has been Expired");
        clearInterval(interval);

    }
   else if($.trim(data['return'])=="4")
    {
     clearInterval(int);
      $('#logout_popup').modal('hide');
 end_actual_time=data['time'];
    }
});
}

}, 50000);

// function




});


//for select2 validation required using parsley
$(document).ready(function(){

// $(".select2").change(function() {
//             $(this).parsley().validate();
//  });

 $("select").on("select2:close", function () {  
        $(this).parsley().validate(); 
    });


 $('input').on("change", function () {
 
     
   });
   <?php 
//$rptmasterdate1=\DB::table('m_rpt_date_tbl')->select('date')->orderBy('id','DESC')->limit(1)->get();
$rptmasterdate1=\DB::table('m_rpt_date_tbl')->select('date')->where('comments','For Report')->orderBy('id','DESC')->limit(1)->get();
if(COUNT($rptmasterdate1)>0){
$rptmasterdate=date('d-m-Y',strtotime($rptmasterdate1[0]->date));
}else{
    $rptmasterdate="01-01-2022";
}
 ?>
 var strtdate ="{{$rptmasterdate}}";
 console.log(strtdate);
$("#start_date").datepicker({
    dateFormat: data,
   
   minDate:strtdate
}).datepicker("setDate",strtdate);

$("#end_date").datepicker({
  dateFormat: data,
  minDate:strtdate,
  maxDate:new Date()
}).datepicker("setDate", new Date());


var data = "<?php echo \Session('j_date_format'); ?>";
 						$('.start_date').datepicker({
    					changeMonth: true,
     					dateFormat: data,
     					changeYear: true,
      					minDate: "01-04-2019",
      					maxDate: 0,
      					onClose: function( selectedDate ) {
        				jQuery( "#end_date" ).datepicker( "option", "minDate", selectedDate );
        }
      
  });
						$('.end_date').datepicker({
						changeMonth: true,
						dateFormat: data,
						changeYear: true,
						maxDate: 0,
						onClose: function( selectedDate ) {
						jQuery( "#start_date" ).datepicker( "option", "maxDate", selectedDate );
						}
						      
  });   



//End for select2 validation required using parsley



    window.Parsley.on('form:validated', function(){
        var classes=$(".card").closest("form").attr('id');
    //console.log(classes);

     /*
    $('select').on('select2:select', function(evt) {

        $("#"+classes).parsley().validate();
    });
    */
    $(".has-submenu >.cg__subMenu >tr>td").addClass("hi");

});





});




 
    </script>


@if (session()->has('message'))
    <script>notyMsg("error","{{ session('message') }}");</script>
@endif


<style type="text/css">
/* css for printing view table without header and breadcrumb navigation  */
  @media print {
         .m-header,.bread{
          display: none !important;
         }
      }
</style>
<!--line table automatic scrolling to last row by default -->
<script type="text/javascript" src="{{asset('js/scroll.js')}}"></script>
<script type="text/javascript">
  $(document).on("select2:close", '.select2-hidden-accessible', function () { $(this).focus(function(){
    $(this).css("border","1px solid red");
  }); });
  $(document).ready(function(){
    $(".pq-loading-mask>div").text('');
    $(".loading.ui-state-default.ui-state-active").text('');
   
  });
</script>
</body>
</html>
