
<html lang="{{ app()->getLocale() }}">


 <body class="sidebar-mini sidebar-collapse">
    <div class="wrapper boxed-wrapper">


<div id="app" class="container">

<div id="main">

@include('layouts.header')


<section id="content_wrapper">



    <div class="div_data">
@yield('content')
    </div>

</section>

</div>

</div>

</div>
<script>
$(document).ready(function () {

    // When clicking a menu link
    $(".product_group").on('click', function (e) {
        e.preventDefault();
        var val = $(this).attr('href');

        // Load content
        $.get(val, function (data) {
            $(".div_data").html(data);

            // Push new state so back button stays inside app
            history.pushState({ page: val }, '', val);
        });
    });
	

	
	
</script>



@include('layouts.php_js')
@include('layouts.php_js_validation')
@extends('layouts.footercontent')
</body>


</html>
