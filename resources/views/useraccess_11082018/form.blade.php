@extends('layouts.header')
@section('content')


<style>
.table-responsive tbody tr td input{
 /* width: 242px !important; */
 width:auto !important;
}
</style>

<span class="ui_close_btn"></span>


<form   id="useraccess" data-parsley-validate>
{{ csrf_field() }}


<div class="card">
<div class="card-header">
<?php include('tools_menu.php');?><h2>User Access</h2>

<span class="ui_close_btn"><a class="collapse-close pull-right btn-danger cross" onclick="location.href = '{{url('useraccess')}}'"></a></span>
</div>

<div class="card-body card-block">



  <!---Toggle content start here------------------>
  <div class="row">
   <div class="col-md-12">


     <!------------------------------------- Body content start here ---------------------------->
     <div class="ziehharmonika">
   <h3>Details</h3>
   <div>
     <div class="row">
       <div class="col-md-12">





               <div class="form-group row">
                   <div class="col-md-2 showinline">

 </div>
 <label for="inputIsValid" class="form-control-label col-md-2">User Name</label>
 <div class="col-md-6">
   <select name='user_id' rows='5' class='form-control user_name' data-show-subtext="true" data-live-search="true"  required>
   {!! $username  !!}
   </select>
 </div>
                     <div class="col-md-2 showinline">
                     <input type="hidden" name="a_user_access_id" value="{{$id}}">
 </div>

  </div>


</div>
<div class="section col-md-offset-4">
<button name="button" type="button" class="btn save saveform" value="SAVE">Save</button>
<a class='btn cancel' onclick='location.href="{{ url('useraccess') }}"'>Cancel</a>
</div>
     </div>
   </div>
 </div>


   </div>
 </div>





</div>

 <div class="card-body card-block">
     <div class="col-md-12">
  <div class="col-md-6 head_menu">

 </div>

 <div class="col-md-6 sub_menu">

 </div>



     </div>
</div>
 <div class="card-body card-block">
     <div class="col-md-12">


      <div class="col-md-6 child_menu">

 </div>

      <div class="col-md-6 button_menu">

 </div>

     </div>
</div>






</div>



</form>



  <script>
$(document).ready(function(){
  <?php if($id!="") { ?>
    setTimeout(function(){
    $(".user_name").val('{{$access_id}}').change();
  }, 100);

  <?php } ?>
   $(document).on('change','.user_name',function(e){
    var id=$(this).val();
    var url="{{URL::to('groupaccess')}}";
     $.get(url+'/'+id,function(data) {

        var div='';
        var divs='';
        var divss='';
      var html=' <div class="table-responsive ">  <table class="table myTable">    <thead> <th style="display:none;"> Sno </th> <th>Head Menu Name</th> <th><input type="checkbox"  id="sub_headmenu" value="1" > </th></thead>';
           $.each(data.totalmenu, function( key, value ) {
              var menu_name=value.menus_name;
        if(data.menu[menu_name]) {

           html+="<tr><td>"+parseInt(key+1)+"</td><td><strong><a  class='headmenus head"+value.menus_id+"'  href='' data-value="+value.menus_id+">"+value.menus_name+"</a></strong></td>  <td><input type='checkbox'name='menu_id["+value.menus_name+"]'  class='sub_headmenus"+value.menus_id+"' value="+value.menus_id+"></td></tr>";
        div+="<div id='sub_menu"+value.menus_id+"' class='sub_menu_hide'></div>";
        divs+="<div id='sub_head_menu"+value.menus_id+"'></div>";
        divss+="<div id='child_head_menu"+value.menus_id+"'></div>";
   }



}
        );

            $(".head_menu").html(html);
            $(".sub_menu").html(div);
            $(".child_menu").html(divs);
            $(".button_menu").html(divss);

     });

   });

   <?php foreach($total_menu as $k=>$v) {
       $name=$v->menus_name;
       if(isset($useracceseditdata->$name)){
  ?>


       console.log('.head'+{{$useracceseditdata->$name}});
       setTimeout(function(){

  $('.head'+{{$useracceseditdata->$name}}).trigger('click');
},250);

  <?php } }  ?>

 $(document).on('click','.headmenus',function(e){

var sub_id=$(this).data('value');
var user_name=$('.user_name').val();

$(".sub_headmenus"+sub_id).prop('checked', true);
if($("#sub_menu"+sub_id).html()=='')
{
    var urls="{{URL::to('subheadname')}}";
$.get(urls+'/'+sub_id+'/'+user_name,function(data) {
    var div='';
    var divs='';
      var html=' <div class="table-responsive ">  <table class="table myTable">    <thead> <th style="display:none;"> Sno </th> <th>Head Menu Name</th> <th><input type="checkbox"  id="sub_headmenu" value="1" > </th></thead>';
           $.each(data.totalmenu, function( key, value ) {
               var menu_name=value.menus_name;

if(data.menu[menu_name]){


 html+="<tr><td>"+parseInt(key+1)+"</td><td><strong><a  class='subheadmenus subhea"+value.menus_id+"'  href='' data-value="+value.menus_id+">"+value.menus_name+"</a></strong></td>  <td><input type='checkbox'name='menu_id["+value.menus_name+"]' id='subhead"+value.menus_id+"' class='sub_headmenus' value="+value.menus_id+"  ></td></tr>"
div+="<div id='child_menu"+value.menus_id+"' class='child_menu_hide'></div>";
divs+="<div id='button_menu"+value.menus_id+"' class='button_menu_hide'></div>";
}
});

   $("#sub_menu"+sub_id).html(html);
   $(".sub_menu_hide").hide();
   $("#sub_menu"+sub_id).show();
   $("#sub_head_menu"+sub_id).html(div);
   $("#child_head_menu"+sub_id).html(divs);

   <?php  if($useracceseditdata){  ?>

   $.each(data.totalmenu, function( key, value ) {
       $(".subhea"+value.menus_id).trigger('click');
       });
   <?php } ?>


});
}
else
{
   $(".sub_menu_hide").hide();
   $("#sub_menu"+sub_id).show();
}


return false;
});

var menus="{{json_encode($useracceseditdata,TRUE)}}";
menus=$.parseJSON(menus.replace(/&quot;/g,'"'));
var primary="{{$id}}";

  $(document).on('click','.subheadmenus',function(e) {
    var sub_id=$(this).data('value');
    var user_name=$('.user_name').val();
   $("#subhead"+sub_id).prop('checked', true);

    if($("#child_menu"+sub_id).html()=='')
    {
            var urls="{{URL::to('subheadname')}}";
    $.get(urls+'/'+sub_id+'/'+user_name,function(data) {
  var div='';
         var html=' <div class="table-responsive ">  <table class="table myTable">    <thead>  <th>Head Menu Name </th>   </thead>';

               $.each(data.totalmenu, function( key, value ) {
 var menu_name=value.menus_name;
           if(primary==""){
if(data.menu[menu_name]){
     html+="<tr id='menu_id"+value.menus_id+"'><td>"+parseInt(key+1)+"</td><td><strong><a  class='childmenus child"+value.menus_id+"' href=''  data-value="+value.controller_name+">"+value.menus_name+"</a></strong></td></tr>"
div+="<div id='button_names"+value.controller_name+"' class='button_menu_hides'></div>";
}
} else {
var controller=value.controller_name;
if(menus[controller]){

     html+="<tr><td>"+parseInt(key+1)+"</td><td><strong><a  class='childmenus child"+value.menus_id+"' href=''  data-value="+value.controller_name+">"+value.menus_name+"</a></strong></td>  </tr>"
div+="<div id='button_names"+value.controller_name+"' class='button_menu_hides'></div>";
}else{
     html+="<tr><td>"+parseInt(key+1)+"</td><td><strong><a  class='childmenus child"+value.menus_id+"' href=''  data-value="+value.controller_name+">"+value.menus_name+"</a></strong></td>  </tr>"
div+="<div id='button_names"+value.controller_name+"' class='button_menu_hides'></div>";
}
}
    });
    <?php  if($useracceseditdata){  ?>

    $.each(data.totalmenu, function( key, value ) {
     setTimeout(function(){
        $(".child"+value.menus_id).trigger('click');
     },200);
        });
    
    <?php } ?>

      $(".child_menu_hide").hide();
      $("#child_menu"+sub_id).html(html);
      $("#child_menu"+sub_id).show();

      $("#button_menu"+sub_id).html(div);
    });
    }
    else
    {
      $(".child_menu_hide").hide();
      $("#child_menu"+sub_id).show();
    }

return false;
});
var premission="{{json_encode($premission,TRUE)}}";
premissions=$.parseJSON(premission.replace(/&quot;/g,'"'));
var primary="{{$id}}";

//console.log(premissions); 
  $(document).on('click','.childmenus',function(e) {

    var sub_id=$(this).data('value');

 if($("#button_names"+sub_id).html()=='')
    {

            var urls="{{URL::to('buttonname')}}";
    $.get(urls+'/'+sub_id,function(data) {

 if(data.length>0)
 {
         var html=' <div class="table-responsive ">  <table class="table myTable">';
    var per=[];

               $.each(data, function( key, value ) {
        html+="<tr><td>"+parseInt(key+1)+"</td><td>Button Name</td><td><strong><a  class='button'>"+value.button_name+"</a></strong></td><td><input type='checkbox' class='"+value.click_name+value.button_id+"' name='button["+value.module_name+"]["+value.click_name+"]"+"' value='"+value.button_id+"'></td></tr>"

    });

    
    
    
var controllers_name=data[0].module_name;
  
 var per=premissions[controllers_name]; 
    
  
    
    
      $("#button_names"+sub_id).html(html);
    $(".button_menu_hides").hide();
      $("#button_names"+sub_id).show();
    
    $.each(per,function(key,value)
         {
      console.log(key);
      console.log(value);
      $('.'+key+value).attr('checked','checked');
    });
    
}
else{
 $(".button_menu_hides").hide();
}
    });

    } else {
   $(".button_menu_hides").hide();

   $("#button_names"+sub_id).show();

  }


return false;
});







$(document).on('click','.head',function(e){
var id=$(this).val();
if($(this).prop("checked") == true){
$('.head'+id).prop("checked",true);
}else{
$('.head'+id).prop("checked",false);

}


})

$(document).on('click','.create',function(e){
var id=$(this).val();
if($(this).prop("checked") == true){
$('.create'+id).prop("checked",true);
}else{
$('.create'+id).prop("checked",false);

}


})

$(document).on('click','.edit',function(e){
var id=$(this).val();
if($(this).prop("checked") == true){
$('.edit'+id).prop("checked",true);
}else{
$('.edit'+id).prop("checked",false);

}


})

$(document).on('click','.delete',function(e){
var id=$(this).val();
if($(this).prop("checked") == true){
$('.delete'+id).prop("checked",true);
}else{
$('.delete'+id).prop("checked",false);

}


})

  $(document).on('click','.view',function(e){
var id=$(this).val();
if($(this).prop("checked") == true){
$('.view'+id).prop("checked",true);
}else{
$('.view'+id).prop("checked",false);

}


})









  $(document).on('click','.save',function(e){


       var saveurl="{{URL::to('saves')}}";
validationrule('save');
               var form=$("#useraccess");
    form.parsley();
  $('input[name=_token]').val("{{csrf_token()}}");
  var data = form.serialize();

       if( form.parsley().validate())

            {
                $.post(saveurl, data, function(data)
                {
                    if(data == 1){
                       notyMsg("success","Saved Successfully")
                       $('.cross').trigger('click');
                        //location.reload();
                    }else{
                        $('.cross').trigger('click');
                   notyMsg("success","Updated Successfully")
        }

                });
                //}

            }

        });









/*karthigaa purpose for hide product in labour condition*/

/*End*/
function changeClassName(className)
{
$('.' + className).each(function (index)
{
if (className == "bulk_line_no")
{
$(this).val(index + 1).attr("readonly", 1);
}

$(this).removeClass(className + '0');
$(this).addClass(className + index);
});
}

/************ karthigaa purpose to remove row action ********************/
function removeClass(className)
{
  var rowCount = $('.po_table tbody tr').length;
  for(var i=0;i<=rowCount;i++)
  {
  $('.po_table tbody tr').find('.'+className).removeClass(className+i);
  }
  $('.' + className).each(function (index)
  {
    if (className == "bulk_line_no")
    {
    $(this).val(index + 1).attr("readonly", 1);
    }
    $(this).addClass(className + index);
  });
}

$('.po_date').datepicker({format: 'yyyy-mm-dd', autoClose: true})


  });
  </script>
<script>
$(window).on('load',function(){
       //preloader
       var preLoder = $("#preloader");
       preLoder.fadeOut(500);
       var backtoTop = $('.back-to-top')
       backtoTop.fadeOut(100);
   });
</script>

@include('layouts.php_js_validation')
@endsection
