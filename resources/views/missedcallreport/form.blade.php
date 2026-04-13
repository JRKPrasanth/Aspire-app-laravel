@extends('layouts.header')
@section('content')


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
.ui-datepicker {
    width: auto;
    padding: .2em .2em 0;
    display: none;
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
}*/

table tbody tr:nth-child(1) {
    background: none;
}
table tbody tr:nth-child(even){
  background-color: #d8d9da;
}

/*.jackfruit th {
    background-color: #455986;
    color: #fff;
}
.doctordetails .jackfruit th,td {
    padding: 7px;
    border: 1px solid #d6d6d6;
    font-size: 14px;
    word-wrap: break-word;
    text-transform: capitalize;
}*/

</style>


<h3 class="heads" >Missed Call Report </h3>
<div class="card">

<!-- <div class="tab row" role="tabpanel"> -->
    <!-- Nav tabs -->
   <!--  <ul class="nav nav-tabs col-lg-2 col-md-2 tabmenu" role="tablist">
        <li role="presentation" data-tab="tab1" class="active menu"><a href="#Section1" class="doctor" aria-controls="home" role="tab" data-toggle="tab">Doctors</a></li>
        
        
    </ul> -->




    <!-- Tab panes -->
<div class=" col-lg-12 col-md-12">
    
    <div role="tabpanel" class="tab-pane fade in active" id="Section1">
        <form id="official_form" data-parsley-validate>
            <input type="hidden" name="edit_id" id="edit_id"  value=""/>
            <input type="hidden" name="form_name" id="form_name" value="official_details"/>
        
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    
                       
                        <div class="col-md-12">
                            <div class="col-md-offset-3 col-md-6">
                                <div class="form-group row">
                                    <label class="col-lg-4 col-md-4">Enter date :<span class="req">*</span></label>
                                    <div class="col-md-4 col-lg-3 ">
                                        <input type="text" name="sel_date" class="sel_date form_date form-control" id="sel_date">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 form-group row doctordetails">
                            
                        </div>                       
                    
                </div>
            </div>

        
        
        </form>
    </div>

                 
</div>

<!-- </div> -->
</div>
       <link rel="stylesheet" href="{{asset('css/bootstrap-datetimepicker.css')}}">
<!--************************- End content********************-->


<script>
      
$(document).ready(function()
{
    
    /******************Doctor Details Start****************/
    $(document).on('change','.sel_date',function(){
        var date = $('.sel_date').val();
        var url = "{{ URL::to('mcrreport') }}/"+date;
        $.get(url, function(data) {
            console.log(data);
            var html = "<table align='center' class='jackfruit' style='table-layout:fixed;width:100%;padding: 12px;border: 1px solid #d6d6d6;font-size: 13px;word-wrap: break-word; text-transform: capitalize; '><thead style='background-color: #455986;color: #fff;'><th>Doctor Name</th><th>Phone No</th><th>Email Id</th><th>Category</th><th>Area</th><th>Available Days</th><th>Address</th></thead><tbody>";
            var i=0;
            $.each(data['result'],function(j,v){
                html += "<tr><td>"+data['result'][i]['doctor_name']+"</td><td>"+data['result'][i]['doctor_phone_number']+"</td><td>"+data['result'][i]['doctor_email_id']+"</td><td>"+data['result'][i]['grade']+"</td><td>"+data['result'][i]['area_name']+"</td><td class=days"+i+">";
             
                html += "</td><td>"+data['result'][i]['doctor_address']+"</td></tr>";
        
                i++; 
            });

            setTimeout(function(){
                var k=0;
                $.each(data['days'],function(j,v)
                {
                  var html1 ='';
                   $.each(data['days'][j],function(k,l){
                        html1 +=data['days'][j][k]['days']+",";
                     
                   });
                    var result = html1.replace(/,(\s+)?$/, '');
         
                   $('.days'+k).html(result);
                    k++;
                });
            }, 500);

            html +="</tbody></table>";
            $(".doctordetails").html(html);

            if(data['status'] == "NULL"){
                notyMsg('info','Tour plan not assigned for this date');
            }else if(data['status'] == "DCRNULL"){
                notyMsg('info','No Dcr records for this date');
            }
        });

    });
    
    /***********************Doctor Details End********************************/



    $('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: true,
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
        autoclose: true,
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
        autoclose: true,
        todayHighlight: 1,
        startView: 1,
        format: 'hh:ii',
        minView: 0,
        maxView: 1,
        forceParse: 0
    });



});


</script>
@include('layouts.php_js_validation')
@endsection
