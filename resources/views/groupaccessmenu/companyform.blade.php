@extends('layouts.header')
@section('content')
<h2 class="text-danger">Company Access</h2>
@include('layouts.breadcrumb')

<form   id="companyaccess" data-parsley-validate>
{{ csrf_field() }}

	
<div class="card shadow-lg rounded-4 border-0">
<div class="card-body card-block">
	 <div class="row">
	<div class="col-md-offset-3 col-md-6">


	<div class="form-group row">
	<label for="inputIsValid" class="form-control-label col-md-4">Company Name</label>
	<div class="col-md-6">
	 <select name='company_name' rows='5' class='select2 form-control company_name' data-show-subtext="true" data-live-search="true"  required>
	 {!! $company_name  !!}
	 </select>
	</div>
	<div class="col-md-2 showinline">
											 <input type="hidden" name="a_company_menu_access_id" value="{{$id}}">
	</div>
	</div>


	</div>

    
</div>
	<div class="row">
		<div class="col-md-offset-4 col-md-6">
             <button name="button" type="button" class="btn save saveform" value="SAVE">Submit</button>
             <button type="button"  class="btn cancel reset" onclick="location.href = '{{url('companyaccess')}}'">Cancel</button>
  </div>
	</div>



<div class="row">

	<div class="col-md-3">

			<div class="table-responsive ">
			<table class="table myTable">
					<thead>
          <th>Sno</th>

					<th>
							Module Name
					</th>
					<th>

					</th>

					</thead>
					<tbody class='headmenu'>

					<?php  if(!$editdata) { foreach($header as $key=>$value) {  ?>
							<tr>
									<td><?php echo $key+1;  ?></td>
									<td><strong><a  class='headmenus   head{{$value->menus_id}}' href='' data-value="{{$value->menus_id}}"><?php echo $value->menus_name;  ?></a></strong></td>
									<td><input type="checkbox" class="header{{$value->menus_id}}" name="menu_id[{{$value->menus_id}}]" myval="{{$value->menus_id}}" value="{{$value->menus_name}}" ></td>

							</tr>

					<?php } } else {  foreach($header as $key=>$value) { $name=$value->menus_id; $checked=''; if(isset($editdata->$name)) { $checked="checked"; }?>

	<tr>
									<td><?php echo $key+1;  ?></td>
									<td><strong><a  class='headmenus head{{$value->menus_id}}' href='' data-value="{{$value->menus_id}}"><?php echo $value->menus_name;  ?></a></strong></td>
									<td><input type="checkbox" class="header{{$value->menus_id}}" name="menu_id[{{$value->menus_id}}]" myval="{{$value->menus_id}}" <?php echo $checked; ?> value="{{$value->menus_name}}" ></td>

							</tr>
					<?php } }?>

					</tbody>
			</table>
	</div>
	</div>
<div class="col-md-3 sub_menu">
              <?php foreach($header as $key=>$value) {
				
	?>
            <div id="sub_menu{{$value->menus_id}}" class="sub_menu_hide"></div>
              <?php } ?>
 </div>

 <div class="col-md-3 child_menu">
<?php foreach($header as $key=>$value) {  ?>
            <div id="sub_head_menu{{$value->menus_id}}"></div>
              <?php } ?>
 </div>

 <div class="col-md-3 button_names">
<?php foreach($header as $key=>$value) {  ?>
            <div id="button_names{{$value->menus_id}}"></div>
              <?php } ?>
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

@endsection
@push('scripts')

	<script>
$(document).ready(function(){
  var url="{{URL::to('subheadmenu')}}";

var menu="{{json_encode($editdata,TRUE)}}";



  $(document).on('click','.headmenus',function(e){

var sub_id=$(this).data('value');

 
$(".header"+sub_id).prop('checked', true);
if($("#sub_menu"+sub_id).html()=='')
{

$.get(url+'/'+sub_id,function(data) {
   
    var div='';
	var button_div='';
      var html=' <div class="table-responsive ">  <table class="table myTable">    <thead> <th> Sno </th> <th>Menu Name</th> <th><input type="checkbox"  name="subcheckbox" id="sub_headmenu" value="'+sub_id+'" > </th></thead>';

    $.each(data, function( key, value ) {
console.log(data);
                var menu_name=value.menus_id;
              	var checked='';
				if(menu[menu_name]) 
				{
						var checked="checked";
			    }
				 html+="<tr><td>"+parseInt(key+1)+"</td><td><strong><a  class='subheadmenus subhea"+value.menus_id+"'  href='' data-value="+value.menus_id+">"+value.menus_name+"</a></strong></td>  <td><input type='checkbox'name='menu_id["+value.menus_id+"]' id='subhead"+sub_id+"' class='sub_headmenus subhead"+sub_id+"' value='"+value.menus_name+"' "+checked+" ></td></tr>"
				div+="<div id='child_menu"+value.menus_id+"' class='child_menu_hide'></div>";
				button_div+="<div id='button_names"+value.menus_id+"'></div>";		
		});

   $("#sub_menu"+sub_id).html(html);
   $(".sub_menu_hide").hide();
   $("#sub_menu"+sub_id).show();
   $("#sub_head_menu"+sub_id).html(div);
	 $("#button_names"+sub_id).html(button_div);



$.each(data, function( key, value ) {



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
 $(document).on('click','#sub_headmenu',function(e){
 if($(this).is(':checked'))
{
	var id=$(this).val();
     $(".subhead"+id).prop('checked', true);
 }
 else{
	var id=$(this).val();
     $(".subhead"+id).prop('checked', false);
 }



});

 $(document).on('click','#sub_child_menu',function(e){
 if($(this).is(':checked'))
{
     var id=$(this).val();
     $(".subheads"+id).prop('checked', true);
 }
 else{
    var id=$(this).val();
     $(".subheads"+id).prop('checked', false);
 }



});
	
	 $(document).on('click','#button_menu',function(e){
 if($(this).is(':checked'))
{
      var id=$(this).val();
     $(".subheadss"+id).prop('checked', true);
 }
 else{
    var id=$(this).val();
     $(".subheadss"+id).prop('checked', false);
 }



});



  $(document).on('click','.subheadmenus',function(e)
  {
	
    var sub_id=$(this).data('value');
   $("#subhead"+sub_id).prop('checked', true);
var div='';
	   
    if($("#child_menu"+sub_id).html()=='')
    {
	
    $.get(url+'/'+sub_id,function(data) {
         var html=' <div class="table-responsive ">  <table class="table myTable">    <thead> <th> Sno </th> <th>Sub Menu Name</th> <th><input type="checkbox"  name="subchildcheckbox" id="sub_child_menu" value="'+sub_id+'" > </th></thead>';

               $.each(data, function( key, value ) {
 var menu_name=value.menus_id;
              var checked='';
if(menu[menu_name]) {
	var checked="checked"; 
}
			
				   
     html+="<tr><td>"+parseInt(key+1)+"</td><td><strong><a  class='submenus sub_tri"+value.menus_id+"' data-myvalue="+value.controller_name+" data-value="+value.menus_id+">"+value.menus_name+"</a></strong></td>  <td><input type='checkbox' name='menu_id["+value.menus_id+"]' class='sub_child_menus subheads"+sub_id+"' id='subheads"+sub_id+"' value='"+value.menus_name+"' "+checked+" ></td></tr>";
div+="<div id='button_names"+value.menus_id+"' class='button_menu_hides' ></div>";
    });

      $(".child_menu_hide").hide();
      $("#child_menu"+sub_id).html(html);
      $("#child_menu"+sub_id).show();
	  $("#button_names"+sub_id).html(div);
		
		
	

$.each(data, function( key, value ) {



    $(".sub_tri"+value.menus_id).trigger('click');


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
	//Button level
	  $(document).on('click','.submenus',function(e) {

          var sub_id=$(this).data('value');
		  var button=$(this).data('myvalue');
		  
		 

 if($("#button_names"+sub_id).html()=='')
    {
 
				<?php if($editdata){  ?>
		var id="id={{$id}}";

<?php } else { ?>
		var id="id=''";
		<?php } ?>
		
		
            var urls="{{URL::to('buttonname')}}";

    $.get(urls+'/'+button+"?"+id,function(data) {
		

 if(data['button'].length>0)
 {
         var html=' <div class="table-responsive ">  <table class="table myTable"><thead> <th> Sno </th> <th>Head Menu Name</th> <th><input type="checkbox"  name="subchildcheckbox" id="button_menu" value="'+sub_id+'" > </th></thead>';
    var per=[];
	 

/* test */
$.each(data['button'], function( key, value ) {
	
	    var menu_name=value.button_name;
	var module_name=value.module_name;
	var click_name=value.click_name; 
	var checked="";

		<?php if($editdata){  ?>
	
		 checked="checked";
<?php } else { ?>
		
		<?php } ?>
	  
        html+="<tr><td>"+parseInt(key+1)+"</td><td><strong><a  class='button'>"+value.button_name+"</a></strong></td><td><input type='checkbox' class='"+value.click_name+value.button_id+"  button_click subheadss"+sub_id+"' name='button["+value.module_name+"]["+value.click_name+"]"+"' id='subheadss"+sub_id+"' value='"+value.button_id+"' "+checked+"></td></tr>";

    });

 
      $("#button_names"+sub_id).html(html);
      $(".button_menu_hides").hide();
      $("#button_names"+sub_id).show();
	 
	 
 var per=data['check_button'][button];


    $.each(per,function(key,value)
         {
     
      $('.'+key+value).attr('checked','checked');
    });
	 
	 

}
else{
	 $("#button_names"+sub_id).html("<h4>Action Not Required</h4>");
	
	
 $(".button_menu_hides").hide();
	$("#button_names"+sub_id).show();
}
    });

    } else {
   $(".button_menu_hides").hide();

   $("#button_names"+sub_id).show();

  }


return false;
});
	//

        $(document).on('click','.save',function(e){


       var saveurl="{{URL::to('companyaccesssave')}}";
validationrule('companyaccess');
               var form=$("#companyaccess");
    form.parsley();
  $('input[name=_token]').val("{{csrf_token()}}");
  var data = form.serialize();

       if( form.parsley().validate())

            {
                $.post(saveurl, data, function(data)
                {
                    var red_url     ="{{ url('companyaccess') }}";
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
     	$name=$v->menus_id;
     	if(isset($editdata->$name)){
		?>


			setTimeout(function(){
                            
                       $('.head'+{{$name}}).trigger('click');     
                            
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

$(window).on('load',function(){
       //preloader
       var preLoder = $("#preloader");
       preLoder.fadeOut(500);
       var backtoTop = $('.back-to-top')
       backtoTop.fadeOut(100);
   });
		
</script>

@endpush
