@extends('layouts.header')
@section('content')
<script type="text/javascript" src="{{ asset('js/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/fullcalendar.min.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('css/fullcalendar.css') }}">
<style type="text/css">
    .alert {
    padding: 0px !important;
    border: none !important;
}
.alert-info {
    color: #31708f;
    background-color: #d9edf7;
    border-color: #bce8f1;
}
.alert-labelled-cell {
    padding: 10px;
    display: table-cell;
    vertical-align: middle;
}
    .alert-labeled-row {
    display: table-row;
    padding: 0px;
}
.alert-label {
    vertical-align: middle;
    background: #BCE8F1;
    width: auto;
    padding: 10px 15px;
    height: 100%;
    font-size: 1.1em;
}
#exceeds_leave > div > div > span > i {
    font-size: 80px;
}
/*h2{
    margin-top: -4px;
    padding: 0 4px;
}*/
hr {
    margin-top: 20px;
    margin-bottom: 20px;
    border: 0;
    border-top: 1px solid #eee;
}
.alert-info hr {
    border-top-color: #a6e1ec;
}
.table{
    width: 100%;
    font-family: 'Saira Semi Condensed', sans-serif;
    font-size: 13px;
}
.btn{
    margin: 2px 5px
}
.fc-border-separate thead{
    background: #9675ce;
    color: #fff;
}
</style>
<div class="panel panel-visible" id="spy1">

    <div class="panel-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group row ">
                    <label for="year" class=" control-label col-md-4 text-left">
                        Employee Name
                    </label>
                    <div class="col-md-8">
                        <select name="emp_id" class="select2 emp_id" id="emp_id">
                            {!! $emp_id !!}
                        </select>
                    </div>

                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group row ">
                    <label for="year" class=" control-label col-md-4 text-left">
                        Year
                    </label>
                    <div class="col-md-8">
                        <select name="year" class="select2 year" id="year">
                          
                        </select>
                    </div>

                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group  row">
                    <label for="Description" class=" control-label col-md-4 text-left">
                        Month
                    </label>
                    <div class="col-md-8">
                        <select name="month" class="select2 month" id="month">
             
                        </select>
                    </div>

                </div>

            </div>

            <div class="col-md-3 ">
                <div class="btn-group">
                <button type="button" id="searid" name="searid" value="Search" class="btn search">Search</button>
                </div>
            </div>

        </div>

<div class="row">
        <div id='calendar'> </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <div class="detail">

                </div>
            </div>

        </div>
    </div>


    </div>

    
</div>









<script type="text/javascript">
$(document).ready(function()
{
     var min = 1900,
    max = new Date().getFullYear(),
    select = document.getElementById('year');

    for (var i = max; i>=min; i--)
    {
        var opt = document.createElement('option');
        opt.value = i;
        opt.innerHTML = i;
        select.appendChild(opt);
    }  
   // month jcombo and select current month
    var condition1 ='1=1';
		$("#month").jCombo("{{ URL::to('jcomboformlogin?table=month:id:description') }}&order_by=id asc"+'&parent='+condition1,
                {selected_value:'<?php echo date("m"); ?>'});
		
$('#calendar').fullCalendar({
      height: 450,

                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month'
                    },
                    // events: '',
                    // defaultDate: '{{ date("Y-m-d")}}',
                    // selectable: true,
                    // selectHelper: true,
                    //             eventRender: function (event, element)
                    // {
                    //     element.attr('href', '#');
                    // }

                });
		$('.do-quick-search').click(function () {
        $('#SximoTable').attr('action', '{{ URL::to("calendar/multisearch")}}');
        $('#SximoTable').submit();
    });

								
	 $('.search').click(function () 
            {
                    var filter = $('#emp_id').val();
                    var month = $('#month').val();
                    var year = $('#year').val();
                    if(filter!='' && month!='' &&  year!=''){
                    var newSource = "{{URL::to('calendarjsondata')}}?filter="+filter+"&month="+month+"&year="+year;
                       var  source = newSource;
                       
                    $('#calendar').fullCalendar('gotoDate',''+year+'-'+month+'-01');
                    $('#calendar').fullCalendar('removeEvents');
                    $('#calendar').fullCalendar('removeEventSource',source);
                    $('#calendar').fullCalendar('refetchEvents');
                    $('#calendar').fullCalendar('addEventSource', newSource);
                    $('#calendar').fullCalendar('refetchEvents');
               
                }else{
                    notyMsg("info","Please choose all fields");           
        }
            });

});
</script>






@endsection