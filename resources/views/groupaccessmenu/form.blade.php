@extends('layouts.header')
@section('content')

<span class="ui_close_btn"></span>


<?php //include('tools_menu.php'); ?><h2 class='heads'>Group Access <span class="ui_close_btn"><a class="collapse-close pull-right btn-danger" onclick="location.href = '{{url('groupmenuaccess')}}'"></a></span></h2>



<form   id="groupaccess" data-parsley-validate>
{{ csrf_field() }}

<div class="card">

<div class="card-body card-block">




     <!------------------------------------- Body content start here ---------------------------->
     
  
   
   <div class="row">
       <div class="col-md-offset-3 col-md-6">





  <div class="form-group row">
  <label for="inputIsValid" class="form-control-label col-md-4">Group Name</label>
  <div class="col-md-6">
   <select name='group_name' rows='5' class='select2 form-control group_name' data-show-subtext="true" data-live-search="true"  required>
   {!! $group_name  !!}
   </select>
  </div>
  <div class="col-md-2 showinline">
                       <input type="hidden" name="a_group_menu_access_id" value="{{$id}}">
  </div>
  </div>


  </div>

    
</div>
  <div class="row">
    <div class="col-md-offset-4 col-md-6">
             <button name="button" type="button" class="btn save saveform" value="SAVE">Submit</button>
             <button type="button"  class="btn cancel reset" onclick="location.href = '{{url('groupmenuaccess')}}'">Cancel</button>
  </div>
  </div>

<div class="row">
<div class="col-md-12">
<hr class="xlg">
</div>
</div>

<div class="row">


 <div class="card-body card-block">
     <div class="col-md-12">
     
      <div class="col-md-3 head_menu">

 </div>

    <div class="col-md-3 sub_menu">
   
 </div>
     
     <div class="col-md-3 child_menu">
      

 </div>
     
     <div class="col-md-3 button_menu">
      

 </div>



     </div>
</div>




 </div>

<div class="row">
<div class="col-md-12">
<hr class="xlg">
</div>
</div>

</div>


<div id="preloader">
       <img src="https://jrlma.ca/wp-content/plugins/gallery-by-supsystic/src/GridGallery/Galleries/assets/img/loading.gif">
    </div>
  </div>
</form>



  <script>
$(document).ready(function(){
     <?php if($id!="") { ?>
    setTimeout(function(){
    $(".group_name").val('{{$group_id}}').change();
  }, 100); 
    
   <?php } ?> 
       
     $(document).on('change','.group_name',function(e){
    var id=$(this).val();
    var user_id=$('.group_name').select2('val');
    var url="{{URL::to('companyaccess')}}";
     $.get(url+'/'+id,function(data) {

        var div='';
        var divs='';
        var divss='';
        var html=' <div class="table-responsive "> <table class="table myTable">    <thead> <th>Sno</th><th>Head Menu Name</th><th><input type="checkbox" class="head" name="checkall1" id="sub_headmenu" value="1" > </th></thead>';
      var i=1;
     
     
   
      $.each(data.total_menu, function( key, value ) 
      {
      
     if(data.company_access_menu[value.menus_id]){   
           var   checked ='';
        if(data.group_access_menu[value.menus_id]) 
    {
       checked="checked";
    }
    var valcheck=value.menus_name;

           html+="<tr><td>"+i+"</td><td><strong><a  class='main_menu headmenus head"+value.menus_id+"'  href='' data-value="+value.menus_id+">"+value.menus_name+"</a></strong></td>  <td><input type='checkbox'name='menu_id["+value.menus_id+"]'  class='sub_headmenus"+value.menus_id+"' value='"+valcheck+"' "+checked+"></td></tr>";
        div+="<div id='sub_menu"+value.menus_id+"' class='sub_menu_hide'></div>";
        divs+="<div id='sub_head_menu"+value.menus_id+"'></div>";
        divss+="<div id='child_head_menu"+value.menus_id+"'></div>";
   i++; 
     }


 });

            $(".head_menu").html(html);
            $(".sub_menu").html(div);
            $(".child_menu").html(divs);
            $(".button_menu").html(divss);

     });

   });     
   <?php foreach($total_menu as $k=>$v) {
       $name=$v->menus_id;
       if(isset($useracceseditdata->$name)){
  ?>


     
       setTimeout(function(){

  $('.head'+{{$name}}).trigger('click');
},300);

  <?php } }  ?>

 $(document).on('click','.headmenus',function(e){

var sub_id=$(this).data('value');
var group_name=$('.group_name').val();

$(".sub_headmenus"+sub_id).prop('checked', true);


if($("#sub_menu"+sub_id).html()=='')
{
   
    var urls="{{URL::to('companyaccess')}}";
$.get(urls+'/'+group_name+'?id='+sub_id,function(data) {
    
         var div='';
         var divs='';
                           var i=1;
             var html=' <div class="table-responsive ">  <table class="table myTable">    <thead> <th > Sno </th> <th>Head Menu Name</th> <th><input type="checkbox"  name="checkall" id="sub_headmenu1" value="1" > </th></thead>';
         $.each(data.total_menu, function( key,value) 
         {

             if(data['company_access_menu'][value.menus_id]){   
           var   checked ='';
        if(data['group_access_menu'][value.menus_id]) 
    {
       checked="checked";
    }
    var valcheck=value.menus_name;
    
        
            html+="<tr><td>"+i+"</td><td><strong><a  class='subheadmenus subhea"+value.menus_id+"'  href='' data-value="+value.menus_id+">"+value.menus_name+"</a></strong></td>  <td><input type='checkbox'name='menu_id["+value.menus_id+"]' id='subhead"+value.menus_id+"' class='sub_headmenus' value='"+valcheck+"' "+checked+" ></td></tr>"
            div+="<div id='child_menu"+value.menus_id+"' class='child_menu_hide'></div>";
            divs+="<div id='button_menu"+value.menus_id+"' class='button_menu_hide'></div>";
                                                i++;
          }
         });

         $("#sub_menu"+sub_id).html(html);
         $(".sub_menu_hide").hide();
         $("#sub_menu"+sub_id).show();
         $("#sub_head_menu"+sub_id).html(div);
         $("#child_head_menu"+sub_id).html(divs);

      

         $.each(data.total_menu, function( key, value ) {
           $(".subhea"+value.menus_id).trigger('click');
           });
      


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

var primary="{{$id}}";

  $(document).on('click','.subheadmenus',function(e) {
    var sub_id=$(this).data('value');
    var group_name=$('.group_name').val();
 

    if($("#child_menu"+sub_id).html()=='')
    {
        var div='';
       var urls="{{URL::to('companyaccess')}}";
$.get(urls+'/'+group_name+'?id='+sub_id,function(data) {
         var html=' <div class="table-responsive ">  <table class="table myTable">    <thead>   <th > Sno </th><th>Head Menu Name </th><th><input type="checkbox"  name="checkall" id="sub_headmenu" value="1" > </th></thead>';

               $.each(data.total_menu, function( key, value ) {
 var menu_name=value.menus_name;
   
    
  if(data.company_access_menu[value.menus_id]){  
           var   checked ='';
        if(data.group_access_menu[value.menus_id]) 
    {
       checked="checked";
    }

             var valcheck=value.menus_name;
     html+="<tr id='menu_id"+value.menus_id+"'><td>"+parseInt(key+1)+"</td><td><strong><a  class='childmenus child"+value.menus_id+"' href=''  data-value="+value.controller_name+">"+value.menus_name+"</a></strong></td><td><input type='checkbox' name='menu_id["+value.menus_id+"]' class='sub_child_menus' value='"+valcheck+"' "+checked+"></td></tr>"
div+="<div id='button_names"+value.controller_name+"' class='button_menu_hides'></div>";
             }
 
    });
   

   



      $(".child_menu_hide").hide();
      $("#child_menu"+sub_id).html(html);
      $("#child_menu"+sub_id).show();

      $("#button_menu"+sub_id).html(div);
       $.each(data.total_menu, function( key, value ) {
 
        $(".child"+value.menus_id).trigger('click');
 
        });
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
var group_name=$('.group_name').select2('val');
    
 if($("#button_names"+sub_id).html()=='')
    {

            var urls="{{URL::to('buttonnameusers')}}";
    $.get(urls+'/'+sub_id+'?group_id='+group_name,function(html) {


      $("#button_names"+sub_id).html(html);
    $(".button_menu_hides").hide();
      $("#button_names"+sub_id).show();

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
$('.headmenus').prop("checked",false);
}else{
$('.headmenus').prop("checked",true);

}


})
  
  $(document).on('click','#sub_headmenu1',function(e){

var id=$(this).val();
if($(this).prop("checked") == true){
$('.sub_headmenus').prop("checked",true);
}else{
$('.sub_headmenus').prop("checked",false);

}


});
  
    $(document).on('click','#sub_headmenu',function(e){

      var id=$(this).val();
      if($(this).prop("checked") == true){
      $('.sub_child_menus').prop("checked",true);
      }else{
      $('.sub_child_menus').prop("checked",false);

      }
    });
  
    $(document).on('click','#child_button',function(e){
var id=$(this).val();
if($(this).prop("checked") == true){
$('.button_list').prop("checked",true);
}else{
$('.button_list').prop("checked",false);

}


})




  //Button level

  //

        $(document).on('click','.save',function(e){


       var saveurl="{{URL::to('groupaccess')}}";
validationrule('groupaccess');
               var form=$("#groupaccess");
    form.parsley();
  $('input[name=_token]').val("{{csrf_token()}}");
  var data = form.serialize();

       if( form.parsley().validate())

            {
                $.post(saveurl, data, function(data)
                {
                    var red_url     ="{{ url('groupmenuaccess') }}";
                    if(data == 1){
                       notyMsg("success","Saved Successfully");
                        //location.reload();
                         window.location.href=red_url;
                    }else{
                   notyMsg("success","Updated Successfully");
                     window.location.href=red_url;
        }

                });
                //}

            }

        });
    <?php 
    foreach($header as $k=>$v) {
      $name=$v->menus_name;
      if(isset($editdata->$name)){
    ?>


      setTimeout(function(){
                            
                       $('.head'+{{$editdata->$name}}).trigger('click');     
                            
                        },5000);
    

    <?php } }  ?>


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