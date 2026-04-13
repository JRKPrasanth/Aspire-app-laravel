@extends('layouts.header')
@section('content')


<style>


.tab .nav-tabs{
    z-index: 90;
}



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
}*/



table tbody tr:nth-child(1) {
     background: #ccc; 
    background: none;
}
thead{
	background: #cccccc;
}
</style>



<div class="card">

<div class="tab row" role="tabpanel">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs col-lg-2 col-md-2 tabmenu" role="tablist">
        <li role="presentation" data-tab="tab1" class="active menu"><a href="#Section1" class="doctor" aria-controls="home" role="tab" data-toggle="tab">Doctors</a></li>
        <li role="presentation" data-tab="tab2" class="menu"><a href="#Section2" aria-controls="profile" role="tab" data-toggle="tab">Doctor Category</a></li>
        <li role="presentation" data-tab="tab3" class="menu"><a href="#Section3" aria-controls="messages" role="tab" data-toggle="tab">Doctor Area</a></li>
        <li role="presentation" data-tab="tab4" class="menu"><a href="#Section4" class="chemist"  aria-controls="messages" role="tab" data-toggle="tab">Chemist Details</a></li>
        <li role="presentation" data-tab="tab5" class="menu"><a href="#Section5" class="stockist"  aria-controls="messages" role="tab" data-toggle="tab">Stockist Details</a></li>
        <li role="presentation" data-tab="tab6" class="menu"><a href="#Section6" class="tourplan"  aria-controls="messages" role="tab" data-toggle="tab">Tour Plan Details</a></li>
        
    </ul>

    <!-- Tab panes -->
<div class="tab-content col-lg-10 col-md-10">
    
    <div role="tabpanel" class="tab-pane fade in active" id="Section1">
        <form id="official_form" data-parsley-validate>
            
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <fieldset>
                        <legend>Doctors Details</legend>

                          


                        <div class="col-md-12 form-group row doctordetails">
                            
                        </div>                       
                    </fieldset>
                </div>
            </div>

        </div>
        
        </form>
    </div>

    <div role="tabpanel" class="tab-pane fade" id="Section2">
        <div class="container">
        <form  id="personal_form" >
            {{csrf_field()}}
            
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <fieldset>
                    <legend>Doctor Category Report</legend>
                    <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-lg-4 col-md-4">Enter Month :<span class="req">*</span></label>
                            <div class="col-md-6 ">
                                <select  class="col-md-6 form-control month select2" name="month" id="month"  required style="width:100%;">
                                    {!! $month !!}
                                </select>
                            </div>
                        </div>
                    </div> 
                    </div>
                    <div class="col-md-12 form-group row catedetails">
                            
                    </div>   
                </fieldset>
            </div>
           
        </div>
       
        </form>
        </div>

    </div>
                
        <div role="tabpanel" class="tab-pane fade" id="Section3">
            <div class="container">
                <form id="contact_form"  >
                     {{csrf_field()}}
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <fieldset>
                                <legend>Doctor Area Report</legend>
                                <div class="col-md-12">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-md-4">Enter Month :<span class="req">*</span></label>
                                        <div class="col-md-6 ">
                                            <select  class="col-md-6 form-control montharea select2" name="montharea" id="montharea"  required style="width:100%;">
                                                {!! $month !!}
                                            </select>
                                        </div>
                                    </div>
                                </div> 
                                </div>
                                <div class="col-md-12 form-group row areadetails">
                                        
                                </div>  
                            </fieldset>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
    <div role="tabpanel" class="tab-pane fade " id="Section4">
        <form id="off_form" data-parsley-validate>
            
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <fieldset>
                        <legend>Chemist Details</legend>
                        <div class="col-md-12 form-group row chemistdetails">
                            
                        </div>                       
                    </fieldset>
                </div>
            </div>

        </div>
        
        </form>
    </div>

    <div role="tabpanel" class="tab-pane fade " id="Section5">
        <form id="stk_form" data-parsley-validate>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <fieldset>
                        <legend>Stockist Details</legend>
                        <div class="col-md-12 form-group row stockistdetails">
                            
                        </div>                       
                    </fieldset>
                </div>
            </div>

        </div>
        
        </form>
    </div> 
    
    <div role="tabpanel" class="tab-pane fade " id="Section6">
        <form id="stk_form" data-parsley-validate>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <fieldset>
                        <legend>Tour Plan Details</legend>
                        <div class="col-md-12 form-group row tourdetails">
                            
                        </div>                       
                    </fieldset>
                </div>
            </div>

        </div>
        
        </form>
    </div>       
                            
</div>

</div>
</div>
       
<!--************************- End content********************-->


<script>
      
$(document).ready(function()
{
    
    /******************Doctor area Start****************/

    $(document).on('change','.montharea',function(){
        var mth = $('.montharea option:selected').val();
        var url = "{{ URL::to('areachangerpt') }}/"+mth;
        
        $.get(url,function(data){
            var cate = "<table border='2'  style='table-layout:fixed;width:100%;'><thead><th>Doctor Name</th><th>Phone No</th><th>Email Id</th><th>Category</th><th>Area</th><th>Address</th><th>Date</th></thead><tbody>";
            var i=0;

            $.each(data,function(m,n){
                if(data[i]['created_at'] !=null ){
                    var dt = data[i]['created_at'];
                    var created_date = dt.split("-");
                    var dd = created_date[2].split(' ');
                    var nw_dt = dd[0]+"-"+created_date[1]+"-"+created_date[0];
                    cate +="<tr><td>"+data[i]['doctor_name']+"</td><td>"+data[i]['doctor_phone_number']+"</td><td>"+data[i]['doctor_email_id']+"</td><td>"+data[i]['grade']+"</td><td>"+data[i]['area_name']+"</td><td>"+data[i]['doctor_address']+"<td class=crtdate"+[i]+" >"+nw_dt+"</td></tr>";
                }
                i++;
            });
            cate +="</tbody></table>";
            $(".areadetails").html(cate);
        });
    });

    /******************Doctor area end****************/

    /******************Doctor Category Start****************/

    $(document).on('change','.month',function(){
        var mth = $('.month option:selected').val();
        var url = "{{ URL::to('doccategory') }}/"+mth;
        
        $.get(url,function(data){
            var cate = "<table border='2'  style='table-layout:fixed;width:100%;'><thead><th>Doctor Name</th><th>Phone No</th><th>Email Id</th><th>Category</th><th>Area</th><th>Address</th><th>Date</th></thead><tbody>";
            var i=0;
            $.each(data,function(m,n){
                var dt = data[i]['created_at'];
                var created_date = dt.split("-");
                var dd = created_date[2].split(' ');
                var nw_dt = dd[0]+"-"+created_date[1]+"-"+created_date[0];
                cate +="<tr><td>"+data[i]['doctor_name']+"</td><td>"+data[i]['doctor_phone_number']+"</td><td>"+data[i]['doctor_email_id']+"</td><td>"+data[i]['grade']+"</td><td>"+data[i]['area_name']+"</td><td>"+data[i]['doctor_address']+"<td class=crtdate"+[i]+" >"+nw_dt+"</td></tr>";
                i++;
            });
            cate +="</tbody></table>";
            $(".catedetails").html(cate);
        });
    });

    /******************Doctor Category end****************/

    /******************Doctor Details Start****************/

    var url = "{{ URL::to('doctorreport') }}";
    $.get(url, function(data) {
        var html = "<table border='2'  style='table-layout:fixed;width:100%;'><thead><th>Doctor Name</th><th>Phone No</th><th>Email Id</th><th>Category</th><th>Specialization</th><th>Area</th><th>Available Days</th><th>Address</th></thead><tbody>";
        var i=0;
        $.each(data['result'],function(j,v){
          
            html += "<tr><td>"+data['result'][i]['doctor_name']+"</td><td>"+data['result'][i]['doctor_phone_number']+"</td><td>"+data['result'][i]['doctor_email_id']+"</td><td>"+data['result'][i]['grade']+"</td><td class=spl"+i+"></td><td>"+data['result'][i]['area_name']+"</td><td class=days"+i+">";
             
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
     
               $('.days'+k).append(result);
                k++;
            });
        }, 500);
        setTimeout(function(){
            var k1=0;
            $.each(data['specialization'],function(x,y)
            {
                var html2 ='';
                $.each(data['specialization'][x],function(z,m){
                    html2 +=data['specialization'][x][z]['specialization']+",";
                 
                });
                var result2 = html2.replace(/,(\s+)?$/, '');
                $('.spl'+k1).append(result2);
                k1++;
            });
        }, 500);

        html +="</tbody></table>";
        $(".doctordetails").append(html);
    });

    $(document).on('click','.doctor',function(){

        var url = "{{ URL::to('doctorreport') }}";
        var html='';
        
        $.get(url, function(data) {
            
            var html = "<table border='2'  style='table-layout:fixed;width:100%;'><thead><th>Doctor Name</th><th>Phone No</th><th>Email Id</th><th>Category</th><th>Specialization</th><th>Area</th><th>Available Days</th><th>Address</th></thead><tbody>";
            var i=0;
            $.each(data['result'],function(j,v){
          
                html += "<tr><td>"+data['result'][i]['doctor_name']+"</td><td>"+data['result'][i]['doctor_phone_number']+"</td><td>"+data['result'][i]['doctor_email_id']+"</td><td>"+data['result'][i]['grade']+"</td><td class=spl"+i+"></td><td>"+data['result'][i]['area_name']+"</td><td class=days"+i+">";
                 
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
         
                   $('.days'+k).append(result);
                    k++;
                });
            }, 500);
            setTimeout(function(){
                var k1=0;
                $.each(data['specialization'],function(x,y)
                {
                    var html2 ='';
                    $.each(data['specialization'][x],function(z,m){
                        html2 +=data['specialization'][x][z]['specialization']+",";
                     
                    });
                    var result2 = html2.replace(/,(\s+)?$/, '');
                    $('.spl'+k1).append(result2);
                    k1++;
                });
            }, 500);
            
            html +="</tbody></table>";
            $(".doctordetails").html(html);
        });

    });

    /***********************Doctor Details End********************************/

    /*************Chemist Details Start *****************/

    $(document).on('click','.chemist',function(){

        var url = "{{ URL::to('chemistreport') }}";
        var html='';
        
        $.get(url, function(data) {
            
            var html = "<table border='2'  style='table-layout:fixed;width:99%;'><thead><th>Chemist Name</th><th>Phone No</th><th>Mobile No</th><th>Area</th><th>Doctor Name</th><th>Address</th></thead><tbody>";
            var i=0;
            $.each(data,function(j,v){
          
                html += "<tr><td>"+data[i]['chemist_name']+"</td><td>"+data[i]['chemist_phone']+"</td><td>"+data[i]['chemist_mobile']+"</td><td>"+data[i]['territory_name']+"</td><td>"+data[i]['doctor_name']+"</td><td>"+data[i]['chemist_address']+"</td></tr>";
                             
                i++; 
            });

            
            html +="</tbody></table>";
            $(".chemistdetails").html(html);
        });

    });

    /*************Chemist Details End *****************/

    /*************Stockist Details Start *****************/

    $(document).on('click','.stockist',function(){

        var url = "{{ URL::to('stockistreport') }}";
        var html='';
        
        $.get(url, function(data) {
            
            var html = "<table border='2'  style='table-layout:fixed;width:99%;'><thead><th>Stockist Name</th><th>Stockist Address</th><th>Area</th><th>Contact Person</th><th>Stockist Phone</th></thead><tbody>";
            var i=0;
            $.each(data,function(j,v){
          
                html += "<tr><td>"+data[i]['stockist_name']+"</td><td>"+data[i]['stockist_address']+"</td><td>"+data[i]['teritory_name']+"</td><td>"+data[i]['contact_person']+"</td><td>"+data[i]['stockist_phone']+"</td></tr>";
                             
                i++; 
            });

            
            html +="</tbody></table>";
            $(".stockistdetails").html(html);
        });

    });

    /*************Stockist Details End *****************/

     /*************Tourplan Details Start *****************/

    $(document).on('click','.tourplan',function(){

        var url = "{{ URL::to('tourplanreport') }}";
        var html='';
        
        $.get(url, function(data) {
            
            var html = "<table border='2'  style='table-layout:fixed;width:99%;'><thead><th>Tour Date</th><th>Employee Name</th><th>Area</th><th> Doctor Name</th><th>Status</th></thead><tbody>";
            var i=0;
            $.each(data,function(j,v){
          
                html += "<tr><td>"+data[i]['tour_date']+"</td><td>"+data[i]['first_name']+"</td><td>"+data[i]['tour_area']+"</td><td>"+data[i]['doctor_id']+"</td><td>"+data[i]['status']+"</td></tr>";
                             
                i++; 
            });

            
            html +="</tbody></table>";
            $(".tourdetails").html(html);
        });

    });

    /*************Tourplan Details End *****************/

});


</script>
@include('layouts.php_js_validation')
@endsection
