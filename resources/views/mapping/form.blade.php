@extends('layouts.header')
@section('content')

<script  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCzvVOOlkO8F185YimtbF47H3efcT5e5jY"></script>
<style>

/*.panel-info {
    border-color: #5b75a6;
}
.custom-file-upload 
{
   color: #fff;
    border: transparent;
    display: inline-block;
    background: black;
    padding: 6px 12px !important;
    cursor: pointer;
}
.btnic
{
    text-align: center;
    background-color: DodgerBlue;
    border: none;
    color: white;
    padding: 12px 30px;
    cursor: pointer;
    font-size: 12px;
    background-color: #33a9ec1c;
}
.ver1
{
  border: 1px solid rgba(55, 48, 73, .3);
}

.ver1 table
{
  width:100%;
}
.column100.column1
{
 width: 265px;
 padding-left: 42px;
}

.row100.head th
{
    padding: 10px;
}

.table100.ver1 td {
 font-size: 12px;
 color: #808080;
 padding: 10px;
 }

.table100.ver1 th {
 font-size: 12px;
 color: #fff;
 text-transform: uppercase;
 padding: 10px;
 background-color:#112f7aad;
}
#myImg {

    width: 60px;
    height: 60px;
}

.add_button {
    margin-top: -23px;
    position: absolute;
    margin-left: -10px;
}

.remove_button {
    margin-top: -23px;
    position: absolute;
    margin-left: -10px;
}

.preview tbody tr > td:first-child {
    display: block;
}
.jackfruit {
        width: 100%;
    box-shadow: 2px 2px 5px 0px rgba(0,0,0,0.2);
}
.jackfruit th,td {
    padding: 7px;
    border: 1px solid #d6d6d6;
    font-size: 14px;
    word-wrap: break-word;
    text-transform: capitalize;
}
.jackfruit th {
    background-color: #455986;
    color: #fff;
}
table tbody tr:nth-child(1) {
    background: none;
}
table tbody tr:nth-child(even){
  background-color: #d8d9da;
}

.ui-datepicker {
    width: auto;
    padding: .2em .2em 0;
    display: none;
}
*/
</style>

<h3 class="heads" >Mapping </h3>

<div class="card">

<div class="tab row" role="tabpanel">
    <!-- Tab panes -->
<div class="tab-content col-lg-12 col-md-12">
    
    <div role="tabpanel" class="tab-pane fade in active" id="Section1">
        <form id="official_form" data-parsley-validate>
            <input type="hidden" name="chemist_dcr_id" id="chemist_dcr_id"  value=""/>
        
            <div class="row">
                
                    <fieldset>
                        
                        <div class="col-md-offset-1 col-md-11">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-lg-4 col-md-4">Employee Name :<span class="req">*</span></label>
                                    <div class="col-md-4">
                                        <select type="text" name="employee_id" class="employee_id form-control select2" id="employee_id">
                                            {!! $employee_id !!}
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-lg-4 col-md-4">Enter date :<span class="req">*</span></label>
                                    <div class="col-md-4 ">
                                        <input type="text" name="search_date" class="search_date form_date form-control" id="search_date">
                                    </div>
                                </div>
                            </div>
<!-- 
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label class="col-lg-4 col-md-4">From Time :<span class="req">*</span></label>
                                    <div class="col-md-6 ">
                                        <input type="text" name="from_time" class="from_time form_time form-control" id="from_time">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label class="col-lg-4 col-md-4">To Time :<span class="req">*</span></label>
                                    <div class="col-md-6 ">
                                        <input type="text" name="to_time" class="to_time form_time form-control" id="to_time">
                                    </div>
                                </div>
                            </div> -->
                        </div>
                                         
                        <div class="form-group text-center btn_hide">
                            <button type="button"  class="btn search" id="search">Search</button> 
                        </div>
                        
                    </fieldset>
                    <div id="map" style="width:100%;height:500px"></div>
                
            </div>

        
        
        </form>
    </div>
                     
</div>

</div>
</div>
<link rel="stylesheet" href="{{asset('css/bootstrap-datetimepicker.css')}}">
       
<!--************************- End content********************-->


<script>
        
$(document).ready(function()
{
    /******************Mapping Details Start****************/
   

    $(".search").click(function(){

        var emp_id=$("#employee_id").select2('val');
        var date=$(".search_date").val();
        var from_time=$(".from_time").val();
        var to_time=$(".to_time").val();
        if(emp_id == "")
        {
            emp_id=0;
        }
        if(date == "")
        {
            date=0;
        }
        // if(from_time == "")
        // {
        //     from_time=0;
        // }
        // if(to_time == "")
        // {
        //     to_time=0;
        // }
        $.get('{{ URL::to("locationdata") }}/'+emp_id+'/'+date,function(data){

            if(data==0)
            {
                mylocation();   
            } else {
                myMap(data);
            }
        });

    
    });

    /***********************Mapping Details End********************************/
    
    function initMap(data) {

    var markers=[];
    $.each(data['location_data'],function(index,val){
       markers[index]=[val[2],parseFloat(val[0]),parseFloat(val[1])];
       
    });
  
      var map;
    var bounds = new google.maps.LatLngBounds();
    var mapOptions = {
        mapTypeId: 'roadmap'
    };
                    
    // Display a map on the web page
    map = new google.maps.Map(document.getElementById("map"), mapOptions);
    map.setTilt(50);

     var infoWindowContent=[];
    $.each(data['emp_data'],function(index,val){
       if( val['photo'] == undefined){
           val['photo'] = "noname.jpg";
       }
       infoWindowContent[index]=['<div class="info_content"><h3>'+val['first_name']+' '+val['last_name']+'</h3><div class="col-md-12"><div class="col-md-3"><img src="uploads/users/'+val['photo']+'" width="50%"></div><div class="col-md-9"><p>Name:'+val['first_name']+'</br>Mobile Number:'+val['mobile_number']+'</p></div></div>' ];
       
    });

    var infoWindow = new google.maps.InfoWindow(), marker, i;

    for( i = 0; i < markers.length; i++ ) {
        var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
        bounds.extend(position);
        marker = new google.maps.Marker({
            position: position,
            map: map,
            title: markers[i][0]
        });

        google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
                infoWindow.setContent(infoWindowContent[i][0]);
                infoWindow.open(map, marker);
            }
        })(marker, i));

        map.fitBounds(bounds);
    }

    var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
        this.setZoom(14);
        google.maps.event.removeListener(boundsListener);
    });
      }
      


  function mylocation() {
var mapProp= {
    center:new google.maps.LatLng(13.0827,80.2707),
    zoom:5,
};
var map=new google.maps.Map(document.getElementById("map"),mapProp);
}    
    
         
  function myMap(data) {

console.log(data);
   var location=[];
  
var index1=0;
    $.each(data,function(index,val){
      var latt=parseFloat(val['s_latitude']);
    var long=parseFloat(val['s_longitude']);
    location[index]={lat:latt,lng:long};
    index1=index;
    });
  
         var latt=parseFloat(data[0]['s_latitude']);
        var long=parseFloat(data[0]['s_longitude']);
       var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 18,
          center: {lat: latt, lng: long},
          mapTypeId: 'terrain'
        });

    
        var flightPath = new google.maps.Polyline({
          path: location,
          geodesic: true,
          strokeColor: '#0088FF',
          strokeOpacity: 1.0,
          strokeWeight: 2
        });
  

        flightPath.setMap(map);
        
          var bounds = new google.maps.LatLngBounds();

             var infoWindowContent=[];
    $.each(data,function(index,val){
       infoWindowContent[index]=['<div class="info_content"><h3>'+val['s_timestamp']+'</h3>'];
       
    });

    var infoWindow = new google.maps.InfoWindow(), marker, i;
        console.log(infoWindowContent);
        
        
           var markers=[];
    $.each(data,function(index,val){
   
       markers[index]=[val['s_timestamp'],parseFloat(val['s_latitude']),parseFloat(val['s_longitude'])];
       
    });
   
    for( i = 0; i < markers.length; i++ ) {
        var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
        console.log(position);
 bounds.extend(position);
 if(i==0)
 {

  marker = new google.maps.Marker({
            position: position,
            map: map,
            icon:'/Uploads/map_image/checkin.png',
            title: markers[i][0]
        });   
  
 }
 else if(i==markers.length-1)
 {
     marker = new google.maps.Marker({
            position: position,
            map: map,
            icon:'/Uploads/map_image/checkout.png',
            title: markers[i][0]
        });    
 }
 else
 {
        marker = new google.maps.Marker({
            position: position,
            map: map,
            icon:'/Uploads/map_image/marker.png',
            title: markers[i][0]
        });
    }
         google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
                infoWindow.setContent(infoWindowContent[i][0]);
                infoWindow.open(map, marker);
            }
        })(marker, i));
          map.fitBounds(bounds);
    }

//        mapmarker(data)
    var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
        this.setZoom(14);
        google.maps.event.removeListener(boundsListener);
    });
        
    }
    

    function roadmap(data){

     var points='';
     $.each(data,function(index,val){
         points +=val.s_latitude+','+val.s_longitude+'|'
     });
    points= points.slice(0, -1);
     var s_latitude=parseFloat(data[0].s_latitude);
     var s_longitude=parseFloat(data[0].s_longitude);
     
     var map;
var drawingManager;
var placeIdArray = [];
var polylines = [];
var snappedCoordinates = [];

  var mapOptions = {
    zoom: 17,
    center: {lat: s_latitude, lng: s_longitude}
    
  };
  map = new google.maps.Map(document.getElementById('map'), mapOptions);

  map.controls[google.maps.ControlPosition.RIGHT_TOP].push(
      document.getElementById('bar'));
  var autocomplete = new google.maps.places.Autocomplete(
      document.getElementById('autoc'));
  autocomplete.bindTo('bounds', map);
  autocomplete.addListener('place_changed', function() {
    var place = autocomplete.getPlace();
    if (place.geometry.viewport) {
      map.fitBounds(place.geometry.viewport);
    } else {
      map.setCenter(place.geometry.location);
      map.setZoom(17);
    }
  });

  drawingManager = new google.maps.drawing.DrawingManager({
 
    drawingControlOptions: {
    
      drawingModes: [
        google.maps.drawing.OverlayType.POLYLINE
      ]
    },
    polylineOptions: {
      strokeColor: '#696969',
      strokeWeight: 2
    }
  });
  drawingManager.setMap(map);
  runSnapToRoad();




function runSnapToRoad() {
 

  $.get('https://roads.googleapis.com/v1/snapToRoads?path='+points+'&interpolate=true&key=AIzaSyDYhBEDJ4bZ876gl0jID4mAr1MqvZzdH3w',function(data) {
      console.log(data['snappedPoints'][0]['location']);
    processSnapToRoadResponse(data);
   
//    mapmarker(data);
     drawSnappedPolyline();

  });
}

function processSnapToRoadResponse(data) {
  snappedCoordinates = [];
  placeIdArray = [];
  for (var i = 0; i < data.snappedPoints.length; i++) {
    var latlng = new google.maps.LatLng(
        data.snappedPoints[i].location.latitude,
        data.snappedPoints[i].location.longitude);
    snappedCoordinates.push(latlng);
    placeIdArray.push(data.snappedPoints[i].placeId);
  }
}

function drawSnappedPolyline() {

  var snappedPolyline = new google.maps.Polyline({
    path: snappedCoordinates,
    strokeColor: 'blue',
    strokeWeight: 2
  });

  snappedPolyline.setMap(map);
//  polylines.push(snappedPolyline);
}
     
  
     
     
 }
           
 function mapmarker(data){
       var markers=[];
    $.each(data,function(index,val){
   
       markers[index]=['new',parseFloat(val['s_latitude']),parseFloat(val['s_longitude'])];
       
    });
  console.log(markers);
//  break;
      var map;
    var bounds = new google.maps.LatLngBounds();

                    
    // Display a map on the web page
    map = new google.maps.Map(document.getElementById("map"), mapOptions);

    for( i = 0; i < markers.length; i++ ) {
        var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
        bounds.extend(position);
        marker = new google.maps.Marker({
            position: position,
            map: map,
            icon:'/Uploads/map_image/marker.png',
            title: markers[i][0]
        });



        map.fitBounds(bounds);
    }
 
}     
    
  


    /**********Date & Time picker Start***/



  $('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        format: 'dd-mm-yyyy hh:ii',
        forceParse: 0,
        showMeridian: 1
    });
    $('.form_date').datetimepicker({
       // language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        format: 'dd-mm-yyyy',
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });
    $('.form_time').datetimepicker({
       // language:  'fr',
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


    /**********Date & Time picker End***/
});


</script>
@include('layouts.php_js_validation')
@endsection
