
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
 $(".product_group").on('click',function(){
     var val=$(this).attr('href');
     $.get(val,function(data){
        $(".div_data").html(data);
     });
        return  false;
 }) ;

$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') } });
</script>
@include('layouts.php_js')
@include('layouts.php_js_validation')
@extends('layouts.footercontent')
</body>


</html>
