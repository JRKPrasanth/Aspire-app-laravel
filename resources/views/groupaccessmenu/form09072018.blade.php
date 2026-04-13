@extends('layouts.header')
@section('content')
<body>
<span class="ui_close_btn"></span>

<!---------------------------------->

	<!---------------------------------->
<div class="row">
<form   id="groupaccess" data-parsley-validate>
{{ csrf_field() }}
<div class="col-lg-12">
<div class="card">
<div class="card-header">
<?php



include('tools_menu.php'); ?><h2>Group Access</h2>

<span class="ui_close_btn"><a class="collapse-close pull-right btn-danger" onclick="location.href = '{{url('groupmenuaccess')}}'"></a></span>
</div>
<div class="card-body card-block">

	<!----Toggle content start here----->
	<div class="row">
	 <div class="col-md-12">


		 <!------------------------------------- Body content start here ---------------------------->
		 <div class="ziehharmonika">
	 <h3>Details</h3>
	 <div>
		 <div class="row">
			 <div class="col-md-6">





							 <div class="form-group row">
	<label for="inputIsValid" class="form-control-label col-md-4">Group Name</label>
	<div class="col-md-6">
	 <select name='group_name' rows='5' class='form-control group_name' data-show-subtext="true" data-live-search="true"  required>
	 {!! $group_name  !!}
	 </select>
	</div>
	<div class="col-md-2 showinline">
											 <input type="hidden" name="a_group_menu_access_id" value="{{$id}}">
	</div>
	</div>


	</div>
	<div class="col-md-6">

			<div class="table-responsive ">
			<table class="table myTable">
					<thead>


					<th>
							Head Menu Name
					</th>
					<th>

					</th>

					</thead>
					<tbody class='headmenu'>

					<?php  if(!$editdata) { foreach($header as $key=>$value) {  ?>
							<tr>
									<td><?php echo $key+1;  ?></td>
									<td><strong><a  class='headmenus   head{{$value->menus_id}}' href='' data-value="{{$value->menus_id}}"><?php echo $value->menus_name;  ?></a></strong></td>
									<td><input type="checkbox" class="header{{$value->menus_id}}" name="menu_id[{{$value->menus_name}}]" value="{{$value->menus_id}}" ></td>

							</tr>

					<?php } } else {  foreach($header as $key=>$value) { $name=$value->menus_name; $checked=''; if(isset($editdata->$name)) { $checked="checked"; }?>

	<tr>
									<td><?php echo $key+1;  ?></td>
									<td><strong><a  class='headmenus head{{$value->menus_id}}' href='' data-value="{{$value->menus_id}}"><?php echo $value->menus_name;  ?></a></strong></td>
									<td><input type="checkbox" class="header{{$value->menus_id}}" name="menu_id[{{$value->menus_name}}]" <?php echo $checked; ?> value="{{$value->menus_id}}" ></td>

							</tr>
					<?php } }?>

					</tbody>




			</table>
	</div>
	</div>
	<div class="section col-md-offset-4">
						 <button name="button" type="button" class="btn save saveform" value="SAVE">Save</button>
						 <button type="button"  class="btn cancel reset" onclick="location.href = '{{url('groupmenuaccess')}}'">Cancel</button>
	</div>
		 </div>
	 </div>
	</div>
		 <!------------------------------------------------------------------------------------------>

	 </div>
	</div>
	<!----toggle content end here----->


</div>








 <div class="card-body card-block">
	<div class="col-md-6 sub_menu">
              <?php foreach($header as $key=>$value) {  ?>
            <div id="sub_menu{{$value->menus_id}}" class="sub_menu_hide"></div>
              <?php } ?>
 </div>

 <div class="col-md-6 child_menu">
<?php foreach($header as $key=>$value) {  ?>
            <div id="sub_head_menu{{$value->menus_id}}"></div>
              <?php } ?>
 </div>


</div>






<!-- karthigaa purpose supplier search jqgrid model-->

<!--end-->

	<!-- karthigaa purpose Product search jqgrid model-->


	<!--end-->

<div id="preloader">
       <img src="https://jrlma.ca/wp-content/plugins/gallery-by-supsystic/src/GridGallery/Galleries/assets/img/loading.gif">
    </div>

</div>




</div>


</form>
</div>
</body>

	<script>
$(document).ready(function(){
  var url="{{URL::to('subheadmenu')}}";

var menu="{{json_encode($editdata,TRUE)}}";
menu=$.parseJSON(menu.replace(/&quot;/g,'"'));;

  $(document).on('click','.headmenus',function(e){

var sub_id=$(this).data('value');

 
$(".header"+sub_id).prop('checked', true);
if($("#sub_menu"+sub_id).html()=='')
{

$.get(url+'/'+sub_id,function(data) {
    var div='';
      var html=' <div class="table-responsive ">  <table class="table myTable">    <thead> <th> Sno </th> <th>Head Menu Name</th> <th><input type="checkbox"  id="sub_headmenu" value="1" > </th></thead>';

    $.each(data, function( key, value ) {

               var menu_name=value.menus_name;
              var checked='';
if(menu[menu_name]) {

            var checked="checked";

   }
 html+="<tr><td>"+parseInt(key+1)+"</td><td><strong><a  class='subheadmenus subhea"+value.menus_id+"'  href='' data-value="+value.menus_id+">"+value.menus_name+"</a></strong></td>  <td><input type='checkbox'name='menu_id["+value.menus_name+"]' id='subhead"+value.menus_id+"' class='sub_headmenus' value="+value.menus_id+" "+checked+" ></td></tr>"
div+="<div id='child_menu"+value.menus_id+"' class='child_menu_hide'></div>";
});

   $("#sub_menu"+sub_id).html(html);
   $(".sub_menu_hide").hide();
   $("#sub_menu"+sub_id).show();
   $("#sub_head_menu"+sub_id).html(div);

<?php if($editdata){  ?>
 console.log(data);
$.each(data, function( key, value ) {

      console.log(".subhea"+value.menus_id);

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
 $(document).on('click','#sub_headmenu',function(e){
 if($(this).is(':checked'))
{
     $(".sub_headmenus").prop('checked', true);
 }
 else{
     $(".sub_headmenus").prop('checked', false);
 }



});

 $(document).on('click','#sub_child_menu',function(e){
 if($(this).is(':checked'))
{
     $(".sub_child_menus").prop('checked', true);
 }
 else{
     $(".sub_child_menus").prop('checked', false);
 }



});



  $(document).on('click','.subheadmenus',function(e){
    var sub_id=$(this).data('value');
   $("#subhead"+sub_id).prop('checked', true);

    if($("#child_menu"+sub_id).html()=='')
    {
    $.get(url+'/'+sub_id,function(data) {
         var html=' <div class="table-responsive ">  <table class="table myTable">    <thead> <th> Sno </th> <th>Head Menu Name</th> <th><input type="checkbox"  id="sub_child_menu" value="1" > </th></thead>';

               $.each(data, function( key, value ) {
 var menu_name=value.menus_name;
              var checked='';
if(menu[menu_name]) { var checked="checked"; }
     html+="<tr><td>"+parseInt(key+1)+"</td><td><strong><a  class='submenus'  data-value="+value.menus_id+">"+value.menus_name+"</a></strong></td>  <td><input type='checkbox' name='menu_id["+value.menus_name+"]' class='sub_child_menus' value="+value.menus_id+" "+checked+" ></td></tr>"

    });

      $(".child_menu_hide").hide();
      $("#child_menu"+sub_id).html(html);
      $("#child_menu"+sub_id).show();
    });
    }
    else
    {
      $(".child_menu_hide").hide();
      $("#child_menu"+sub_id).show();
    }

return false;
});

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
 <?php foreach($header as $k=>$v) {
     $name=$v->menus_name;
     if(isset($editdata->$name)){
?>


     console.log('.head'+{{$editdata->$name}});
$('.head'+{{$editdata->$name}}).trigger('click');


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
