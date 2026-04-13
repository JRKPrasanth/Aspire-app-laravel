<head>
<title>ERP</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/meanmenu.css') }}">
<link rel="stylesheet" href="{{ asset('css/style.css')}}">
<link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css')}}" />

<link rel="stylesheet" href="{{asset('css/jquery-ui.css')}}">
<link rel="stylesheet" href="{{asset('css/ui.jqgrid.css')}}">
<!---------------------- Date Picker ------------------------->
<link rel="stylesheet" href="{{asset('css/bootstrap-datetimepicker.min.css')}}">
<!----------------------- customize css ---------------------->
<link rel="stylesheet" href="{{asset('css/customize.css')}}">
<link rel="stylesheet" href="{{asset('css/sweet-alert.css')}}">


<link href="https://fonts.googleapis.com/css?family=Philosopher" rel="stylesheet">


<?php /* ?>

<link href="{{ asset('js/plugins/parsley/src/parsley.css') }}" rel="stylesheet">
<script src="{{ asset('js/plugins/parsley/dist/parsley.js') }}"></script>

<link href="<?php echo asset('js/plugins/parsley/src/parsley.css') ?>" rel="stylesheet">
<script src="<?php echo asset('js/plugins/parsley/dist/parsley.js')?>"></script>
<?php */ ?>





<script src="{{ asset('js/jquery-1.12.0.min.js')}}"></script>
<!-- bootstrap js -->
<script src="{{ asset('js/bootstrap.min.js')}}"></script>
<!-- Mega Menu js -->
<script src="{{ asset('js/jquery.meanmenu.js')}}"></script>
<!-- Select Live Search -->
<script src="{{ asset('js/bootstrap-select.min.js')}}"></script>
<!------------------------ Date Picker --------------------------->
<script src="{{ asset('js/bootstrap-datetimepicker.min.js')}}"></script>

<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqgrid/4.6.0/js/i18n/grid.locale-en.js"></script>
<script src="{{asset('js/jquery.jqgrid.min.js')}}"></script>
<script src="{{ asset('js/jquery.jCombo.min.js') }}"></script>
<script src="{{ asset('js/reCopy.js') }}"></script>



<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>


</head>
<div class="contentdata">
@yield('content')
</div>