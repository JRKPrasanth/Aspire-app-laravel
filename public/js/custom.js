/********************* fullscreen js custom **********************/
$(document).ready(function(){
$(".left-sidebar").css("min-height","1000");

     $( "select.select2" ).each(function( index, element ) {
            $(element).before('<div class="sel2" id="moved'+index+'">');
            $("#moved"+index).append(element);
          });

  var Demo = function() {

  var chandru = function() {

      var selector = $('html');
      var ua = window.navigator.userAgent;
      var old_ie = ua.indexOf('MSIE ');
      var new_ie = ua.indexOf('Trident/');
      if ((old_ie > -1) || (new_ie > -1)) {
          selector = $('body');
      }
      var screenCheck = $.fullscreen.isNativelySupported();
      $('.request-fullscreen').on('click', function() {

          if (screenCheck) {
              if ($.fullscreen.isFullScreen()) {
                  $.fullscreen.exit();
              } else {
                  selector.fullscreen({
                      overflow: 'auto'
                  });
              }
          } else {
              alert('Your browser does not support fullscreen mode.')
          }
      });
  }
  return chandru();


  }();


})

/**********************************************/

 
$(document).ready(function() {

    $('nav.active-mobile-menu').meanmenu();
		$('.onclickrel').relCopy();

$('.ziehharmonika').ziehharmonika({
      collapsible: true,
     highlander: false
    });
$('.collapseIcon').trigger('click');


});



/*---------------------------------
2. Sticky Menu Active
-----------------------------------*/
$(window).scroll(function() {
if ($(this).scrollTop() >92){
$('.header-sticky').addClass("sticky");
$(".left-sidebar").css("top","-9%");
}
else{
$('.header-sticky').removeClass("sticky");
$(".left-sidebar").css("top","3.7%");
$(".left-sidebar").css("min-height","1000");
}
});

// Add slideUp animation to Bootstrap dropdown when collapsing.
$('.dropdown').on('show.bs.dropdown', function () {

   $(this).find('.dropdown-menu').first().stop(true, true).slideDown(200);
});

$('.dropdown').on('hide.bs.dropdown', function () {
   $(this).find('.dropdown-menu').first().stop(true, true).slideUp(200);
});

// -------------------- From Bottom to Top Button
       //Check to see if the window is top if not then display button
   $(window).on('scroll', function (){
     if ($(this).scrollTop() > 200) {
       $('.scroll-top').fadeIn();
     } else {
       $('.scroll-top').fadeOut();
     }
   });
       //Click event to scroll to top
   $('.scroll-top').on('click', function() {
     $('html, body').animate({scrollTop : 0},500);
     return false;
   });
/************************* header sidebar menu *********************/
$(document).ready(function() {


  var closeNavigate = function() {
        $("body").removeClass("m-opened-sidebar"), $("body").removeClass("m-body-fixed")
    },
    openNavigate = function() {
        $(window).width() < 760 && $("body").addClass("m-body-fixed"), $("body").addClass("m-opened-sidebar")
    },
    addWaveEffect = function(e, a) {
        var s = ".m-wave-effect",
            t = e.outerWidth(),
            n = a.offsetX,
            o = a.offsetY;
        e.prepend('<span class="m-wave-effect"></span>'), $(s).css({
            top: o,
            left: n
        }).animate({
            opacity: "0",
            width: 2 * t,
            height: 2 * t
        }, 500, function() {
            e.find(s).remove()
        })
    };

  
    $(".m-header-submenu").show(), $(".m-overlay, .m-sidebar-toggle-button").on("click", function() {
        closeNavigate()
    }),



     $(".m-toggle-sidebar").on("click", function() {
        $("body").hasClass("m-opened-sidebar") ? closeNavigate() : openNavigate()
    }), 



     $(".m-sidebar-pin-button").on("click", function() {
        $("body").toggleClass("m-pinned-sidebar")
    }),



     $(".m-search-toggle").on("click", function(e) {
        e.preventDefault(), $(".m-search-bar").toggleClass("active")
    }),



     $(".m-header-submenu").parent().find("a:first").on("click", function(e) {
        e.stopPropagation(), e.preventDefault(), $(this).parents(".m-header-navigation").find(".m-header-submenu").not($(this).parents("li").find(".m-header-submenu")).removeClass("active"), $(this).parents("li").find(".m-header-submenu").show().toggleClass("active")
    }), 



     $(".m-sidebar-navi a").on("click", function(e) {
        var a = $(this);
        $(this).next().is("ul") ? (e.preventDefault(), a.parent().hasClass("show") ? (a.parent().removeClass("show"), a.next().slideUp(200)) : (a.parent().parent().find(".show ul").slideUp(200), a.parent().parent().find("li").removeClass("show"), a.parent().toggleClass("show"), a.next().slideToggle(200))) : (a.parent().parent().find(".show ul").slideUp(200), a.parent().parent().find("li").removeClass("show"), a.parent().addClass("show"))
    }), 




     $(".m-material-button").on("click", function(e) {
        addWaveEffect($(this), e)
    }), 


$(".m-header-navigation>li").on("mouseleave", function(e) {
        if ($(".m-header-submenu").hasClass("active")) {
          $(".m-header-submenu").fadeOut("slow", function() {        
          $(this).removeClass("active")
          });
        };
    });

// $(".m-header-navigation>li").on("mouseenter", function(e) {
        
//           $(".m-header-submenu").addClass("active")
        
//     }),

     $(document).on("click", function(e) {
        var a = $(e.target);
        !0 === $(".m-header-submenu").hasClass("active") && !a.hasClass("m-submenu-toggle") && a.parents(".m-header-submenu").length < 1 && $(".m-header-submenu").removeClass("active"), a.parents(".m-search-bar").length < 1 && !a.hasClass("m-search-bar") && !a.parent().hasClass("m-search-toggle") && !a.hasClass("m-search-toggle") && $(".m-search-bar").removeClass("active")
    })
   
});


 
 



   
