<?php if(isset($_GET['ref_id'])){
  $ref_id = $_GET['ref_id'];
      \DB::table('notifications_t')->where('notification_id',$ref_id)->update(['read/unread' => 'read']);
  } ?>

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


<!---------------------- Date Picker ------------------------->

<!----------------------- customize css ---------------------->
<link rel="stylesheet" href="{{asset('css/customize.css')}}">
<link rel="stylesheet" href="{{asset('css/sweet-alert.css')}}">
<!-- ------------------------ Dashboard 1 -------------------- -->
<link rel="stylesheet" type="text/css" href="{{asset('dashboard/dashboard_2.css')}}">


<script src="{{ asset('js/jquery-1.12.0.min.js')}}"></script>






<!-- <script src="{{asset('js/plugins/parsley/src/parsley.min.js')}}"></script>
 -->
<!-- <script type="text/javascript" src="{{asset('js/plugins/parsley/src/i18n/es.js')}}"></script> -->



<!-- bootstrap js -->
<script src="{{ asset('js/bootstrap.min.js')}}"></script>


<!--<script src="{{asset('js/jquery.jqgrid.min.js')}}"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>-->
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

<script src="{{ asset('js/bs-iconpicker/js/bootstrap-iconpicker.js') }}"></script>
<!-- <script src="{{ asset('js/bs-iconpicker/js/jquery.fancytree-all.min.js') }}"></script> -->

<!------------------------ Toggle form ---------------------------->
<!--script src="{{ asset('js/ziehharmonika.js') }}"></script-->


<!--script src="{{ asset('js/fullscreen.js')}}"></script-->

<script src="{{ asset('js/bootstrap-datetimepicker.js')}}"></script>
<!--script src="{{ asset('js/custom.js')}}"></script-->
<!---dashboard widget script--->
<!-- <script src="{{ asset('js/fullscreen.js')}}"></script> -->



<script src="{{ asset('js/widget/jquery.newsTicker.js')}}"></script>
<script src="{{ asset('js/widget/jquery.clock.js')}}"></script>

<!--jqgrid-->
<!--<script src="http://www.guriddo.net/demo/css/trirand/ui.jqgrid.css"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/free-jqgrid/4.14.1/plugins/min/ui.multiselect.js"></script>

<!--<script src="http://www.guriddo.net/demo/js/trirand/src/jquery.jqGrid.js"></script>-->

<script src="{{ asset('js/pdfmake.min.js') }}"></script>
<script src="{{ asset('js/jqgridfont.js') }}"></script>
<link rel="stylesheet" href="{{asset('css/ui.jqgrid.css')}}">
<script src="{{ asset('js/jquery.jqgrid.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/free-jqgrid/4.14.1/plugins/min/ui.multiselect.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/free-jqgrid/4.14.1/plugins/css/ui.multiselect.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="http://www.guriddo.net/demo/js/trirand/i18n/grid.locale-en.js"></script>


<link rel="stylesheet" href="{{asset('css/ui.multiselect.css')}}">
<script src="{{ asset('js/sweet-alert.min.js') }}"></script>
<script src="{{ asset('js/sweet-alert.init.js') }}"></script>
<script src="{{ asset('js/notifIt.js') }}" type="text/javascript"></script>
<link href="{{ asset('css/notifIt.css') }}" type="text/css" rel="stylesheet">



<!---------------------------- Line table new -------------------------------->
<script src="{{ asset('dashboard/kidd.stickytable.min.js') }}"></script>
<link href="{{ asset('dashboard/kidd.stickytable.css') }}" type="text/css" rel="stylesheet">
<link href="{{ asset('dashboard/colpick.css') }}" type="text/css" rel="stylesheet">



<script  src="{{asset('js/plugins/parsley/src/parsley.js')}}"></script>
<link rel="stylesheet" href="{{asset('js/plugins/parsley/src/parsley.css')}}">

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
        <?php if(!empty($pageMethod)){ ?>

            var url="{{URL::to($pageMethod)}}";

            $('a[href="'+url+'"]').removeClass('menu3css');
        <?php  } ?>

          $('a[href="'+url+'"]').addClass('menu3css');
     $('a[href="'+url+'"]').closest('.mega-parent').find('.three-d-box .front').addClass('active1');

      });

    </script>
</head>
<body>
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
            <img class="m-logo-mobile" src="{{ asset('images/jrks.png') }}">
            <img class="m-logo-desktop" src="{{ asset('images/jrks.png') }}">
          </a>
        </h1>
      </div>
      <!--logo end -->
     

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
                <li><a href="#">My profile</a></li>
                <li><a href="#">Activity</a></li>
                <li><a href="#">Messages</a></li>
                <li><a href="logout">Logout</a></li>
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

            <div class="hidden-xs">

                @include('layouts.menu')
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
    @yield('content')
    @extends('layouts.footer')

    </div>
  </main>
  <!--Main container end -->






<button class="scroll-top tran3s">
        <i class="fa fa-angle-up" aria-hidden="true"></i>
</button>



<script>
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
  /***************To check invoice Already Selected ***********/
function invcheck(invoice_hdr_id,index)
  {
    var pdtcount = 0;
    $('.clone').each(function (ind, v)
    {
      var val = $(".bulk_invoice_hdr_id" + ind).val();
      if(index != ind)
      {
      if(val == invoice_hdr_id)
      {
        pdtcount++;
      }
      }
    });
    return pdtcount;
  }
	 /***************To check product type Already Selected in machine ***********/
	function prdtypecheck(product_type_id,index)
  {
    var pdtcount = 0;
    $('.clone').each(function (ind, v)
    {
      var val = $(".bulk_product_type_id" + ind).val();
      if(index != ind)
      {
      if(val == product_type_id)
      {
        pdtcount++;
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
      if(index != ind)
      {
      if(val == invoice_hdr_id)
      {
        pdtcount++;
      }
      }
    });
    return pdtcount;
  }
	/*
	$( "select.select2" ).each(function( index, element ) {
            $(element).before('<div class="sel2" id="moved'+index+'">');
            $("#moved"+index).append(element);
          });*/
 var dateToday = new Date();
  var data ="{{\Session::get('j_date_format')}}";
  $( ".datepicker" ).datepicker({
      changeMonth: true,
      dateFormat: data,
      changeYear: true,	  minDate: dateToday,
      maxDate: null,
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


 if(myDate!='0000-00-00')
 {
var parsedDate = $.datepicker.parseDate("yy-mm-dd", myDate);

$(this).val($.datepicker.formatDate("{{\Session::get('j_date_format')}}", parsedDate));
 }
 else {
   $(this).val('');
 }

   });
})
</script>

 <style type="text/css">


    </style>
    <!---------------------- Active menubar ---------------------->
    <script type="text/javascript">
      $(document).ready(function(){


/************************************/

     var url=0;
     <?php if(!empty($pageMethod)){ ?>

     var url="{{URL::to($pageMethod)}}";
    $('a[href="'+url+'"]').removeClass('menu3css');
     <?php  } ?>
   // alert('a[href="'+url+'"]');

     $('a[href="'+url+'"]').addClass('menu3css');
     $('a[href="'+url+'"]').closest('.mega-parent').find('.menuhover').addClass('active1');

/*************************************/
      })
    
    
    //$('.container').css("height", $(document).height(),"padding-bottom",);
    </script>



</body>
</html>
