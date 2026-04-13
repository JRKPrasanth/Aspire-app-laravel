@extends('layouts.header')
@section('content')
<?php error_reporting(0);?>
<style type="text/css">
  .productsetting{
    margin:  10px, 0;
    padding: 10px;
    background-color: #fff;
    box-shadow: 0 4px 10px 0 rgba(0,0,0,0.2), 0 4px 20px 0 rgba(0,0,0,0.19);
  }

 
 .productsettingtitle {
    font-size: 13px;
    color: #000;
    letter-spacing: 1px;
    height: 30px;
    line-height: 30px;
    margin: 0;
    font-family: 'Prompt', sans-serif;
    transition: all 0.3s ease 0s;
 }
.ui_close_btn {
    border: 1px dashed #455986;
    padding: 10px;
    float: right;
    color: #fff;
    background: #455986;
    cursor: pointer;
 }
.select2-container{
  height: auto !important ;
}

.select2-selection__rendered {
 
  font-size: 11px !important;
}

.select2-container--default .select2-selection--multiple{
  border:none !important ;
}
.select2-container .select2-selection--multiple {
    box-sizing: border-box !important ;
    cursor: pointer !important ;
    display: block !important ;
    min-height: 27px !important ;
    user-select: none !important;
    -webkit-user-select: none !important ;
 }
.select2-container--default.select2-container--focus .select2-selection--multiple{
   border: none !important;
   word-wrap: break all !important;

}
.select2-selection--multiple{
    
    height:45px !important;

}

</style>
 
<h2 class="heads">Notification Setting</h2>


    <!--<form method="post" action="{{ URL::to('productsettingsave') }}" id="coloumnpermission" class="productsetting_form" data-parsley-validate>-->
{{ csrf_field() }}
<input type="hidden" value="" name="savestatus" id="savestatus" />
<div class="card">

<div class="card-body card-block">
  <div class="row">
       <!-- <div class="col-md-4">
        <div class="productsetting">
    <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-8">PURCHASE ENQUIRY</label>
                    <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="purchaseenquiry" ></a></span>
    </div>
  </div>
    </div> -->

	 <div class="col-md-9">
	 <div class="col-md-3">
	 Module Name
	 </div>
	 	 <div class="col-md-4">
	 <select class="select2 module_name" name="module_name">
	 <option > --Please Select-- </option>
	 <option value='1' >Purchase</option>
	 <option value='2'> Sales </option>
	 <option value='3'>Inventory</option>
	 <option value='4'>Production</option>
	 <option value='5'>Accounts</option>
	 </select>
	 </div>
	 
	 	 	 <div class="col-md-4">
	 
	 </div>
	 </div>

	 <div class="moduledata">
	 
	 
	 </div>
	
  
 


 </div>
</div>

</div>


        <!--Config Modal -->
<div id="config_modal" class="modal fade" role="dialog">
<div class="modal-dialog" style="width:60%;">
<!-- Modal content-->
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal">&times;</button>
<h4 class="modal-title">Notification Email</h4>
<h4 class="modal-title"></h4>

</div>
 <form  action=""  id="save" >    
<div class="modal-body ">
   
<div id="form_body">
<div class="row">
   <div class="col-md-6"> 
    <div class="form-group row">
    <label class="form-control-label col-md-9" for="product_group_id"><span style="color:red">*</span>User Name</label>
    <div class="col-md-50">s
            <select multiple="multiple" name='user_id[]' class='user_id select2' id="user_id"  style="width:100%;">
                {!! $user_id !!}
            </select>
    </div>
</div>
    
        <div class="form-group row">
    <label class="form-control-label col-md-7" for="product_group_id"><span style="color:red">*</span>Duartion</label>
    <div class="col-md-7">
        <input type="text" class="form-control duration" name="duration" >
    </div>
</div>
    
      <input type="hidden" name="type" class="type" value="">
      <input type="hidden" name="id" class="id" value="">
</div>
<div class="col-md-6">  
 
        <div class="form-group row display">
            <label class="form-control-label col-md-9" for="product_group_id"><span style="color:red">*</span>Email User Name</label>
            <div class="col-md-50">
                    <select multiple="multiple" name='email_user_id[]' class='email_user_id select2' id="email_user_id"  style="width:100%;">
                        {!! $user_id !!}
                    </select>
            </div>
        </div>
    
            <div class="form-group row display">
    <label class="form-control-label col-md-5" for="product_group_id"><span style="color:red">*</span>Email Duartion</label>
    <div class="col-md-7">
        <input type="text" class="form-control email_duration" name="email_duration" >
    </div>
</div>
    
    
</div>   
</div>
    
</div>
    <div align="center">
        <!--<button type="submit" class="btn save saveform">OK</button>-->
        <button name="submit" type="button" class="btn save " value="SAVE">OK</button>
         <button type="reset" class="reset hide" value="Reset" >Reset</button>
    </div>
	 </div>	
    </form> 
</div>
 </div>
</div>
        
        
 <!--Config Modal -->






<script type="text/javascript">
$( document ).ready(function() {
//$(".product_group_id").jCombo("{{ URL::to('jcomboform?table=m_product_groups_t:product_group_id:group_name')}}",
//{selected_value:"{{$value->product_group_id}}"});
     $('#savestatus').val('');
  
    $(document).on('click','.config',function(e){    
        
        var type=$(this).data('value');

        $('.type').val(type);
		$.get('editnotificationsetting?type='+type,function(data){
    	    $('.reset').trigger('click');
            $("#user_id").select2('val', 'All');
            $("#email_user_id").select2('val', 'All');
            console.log(data);

            if (data != 0) {		  
    			   
                var userarray=data.user_id.split(",");
                var emailuserarray=data.email_user_id.split(",");
        		$('.user_id').val(userarray);
        		$('.email_user_id').val(emailuserarray);
        		$('.user_id').trigger('change.select2');
        		$('.email_user_id').trigger('change.select2');
        		$('.duration').val(data.duration);	   
        		$('.email_duration').val(data.email_duration);	   
        		$('.id').val(data.id);	

                if(data.display == 1){
                    $('.display').hide();
                }else{
                    $('.display').show();
                }

            }
        }); 
   
        $('#config_modal').modal('show');
    });
    
  $(document).on('click','.save',function(e){
                e.preventDefault();
                var data;
                data = $("#save").serialize();
                $.post('userdata', data, function(data)
                {
                    if(data == 1){
                        notyMsg('success',"Inserted Successfully ");
                       
                       
                    }
                    else if(data == 2)
                    {
                        notyMsg('success','updated  successfully');
						 $('.reset').trigger('click');
                        $("#user_id").select2('val', 'All');
                        $("#email_user_id").select2('val', 'All');
                        //location.reload();
                    }
                });
               $('#config_modal').modal('hide');

            });
			
	 $(document).on('change','.module_name',function(e){

	 var id=$(this).val();
	 var url = "{{ URL::to('module_data') }}/"+id;
	  $.get(url, function(data) {
		
		$('.moduledata').html(data);
		
		  
	  });


	 });	 
			


});
    </script>
@endsection
