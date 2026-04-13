@extends('layouts.header')
@section('content')

<style type="text/css">
/*.select2.loc_id {
  border: none !important;
}*/
/*.loc_id .select2-container{
  border: none !important;
}*/
#sel2 .loc_id .select2-container{
   border: none !important;
  
}
.select2-container--default .select2-selection--multiple{
  border: none;
}
.select2-container--default.select2-container--focus .select2-selection--multiple{
  border: none;
}
.select2-container{
    box-sizing: border-box;
    display: inline-block;
    margin: 0;
    background-color: #fff;
    border: 1px solid #375a80;
    border-radius: 5px;
    box-shadow: none;
    color: #000;
    text-align: center;
    max-width: 100%;
    transition: all 300ms linear 0s;
    position: relative;
    vertical-align: middle;
    height: auto;
}
.form-control{
  padding: 3px 12px;
}

</style>

<?php include('tools_menu.php');
?> 
<span class="ui_close_btn"></span>
<h2 class="heads">Institution DCR 
  <span class="ui_close_btn">
  <a class="collapse-close pull-right btn-danger close" onclick='location.href="{{ url('institutiondcr') }}"'></a>
</span>
</h2>
<form autocomplete="off" action="" id="user_form" class="user_form" data-parsley-validate  autocomplete="off" >
    {{ csrf_field() }}
    <input type="hidden" value="" name="savestatus" id="savestatus" />
   

<div class="card">

<div class="card-body card-block">


  <div class="row">

    <div class="col-md-4">


          <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red">*</span>Institution</label>
            <div class="col-md-8">
<input type="hidden"  name="institution_dcr_id" id="institution_dcr_id" value="<?php echo $institution_dcr_id; ?>" />
               <select name='institution_id' rows='5' class='select2 institution_id' data-show-subtext="true" data-live-search="true" required>

          {!!$institution_id!!}
          </select>
            </div>
           
        </div>

   <div class="form-group row pagemethod">
            <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red">*</span>Key Contact</label>
            <div class="col-md-8">

                <input type="text" id="keycontact1" name="keycontact1"  class="form-control keycontact1" value="{{$keycontact1}}" required >
            </div>
            
        </div>
      
        <div class="form-group row pagemethod">
            <label for="inputIsValid" class="form-control-label col-md-4">Area</label>
            <div class="col-md-8 comp">
          <select name='area' rows='5' class='select2' data-show-subtext="true" data-live-search="true" >{!! $area !!}
            
                </select>
            </div>
           
        </div>
      
    
    </div>
    <div class="col-md-4">
            <div class="form-group row pagemethod">
            <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red">*</span>Activity</label>
            <div class="col-md-8 sel2">
                <select name='activity_id' rows='5' class='form-control select2 activity_id'  required>{!! $activity_id !!}
                </select>
            </div>
           
         </div>

          <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red">*</span>Visit With</label>
            <div class="col-md-8">
              <select name='visit_with' rows='5' class='select2 visit_with' data-show-subtext="true" data-live-search="true" required>
          {!! $visit_with !!} 
          
          </select>
          
            </div>
            
        </div>
        
      
       <div class="form-group row pagemethod">
            <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red">*</span>Outcome</label>
            <div class="col-md-8">
          <select name='outcome_id' rows='5' class='select2 outcome_id' data-show-subtext="true" data-live-search="true" required>
          {!! $outcome_id !!} 
          
          </select>

            </div>
            
        </div>
        
             </div>
    
   
                <div class="col-md-4">
               

              <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4">Focus Product</label>
            <div class="col-md-8 comp ">
                <select multiple="multiple" name='focus_product[]' rows='5' class='select2 focus_product' data-show-subtext="true" data-live-search="true" required>
                  {!!$focus_product!!}
                </select>
            </div>
           
        </div>
       <div class="form-group row user_password">
            <label for="inputIsValid" class="form-control-label col-md-4">Timing</label>
             <div class="col-md-8">
          <input type="text" name='timing' rows='5' class='form-control timing timepicker' id="timing" value="{{$timing}}" required>
               
            </div>
           
        </div>


                    

                </div>

</div>



<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="form-group text-center">
            <input type="hidden" name="submit_type" class="submit_type" id="submit_type">
            
          
            <button name="submit" type="button" class="btn save saveform" value="SAVE">Save</button>
            <button name="submit" type="button" class="btn save saveform" value="SAVENEW">Save and New</button>
            <a class='btn cancel' onclick='location.href="{{ url("institutiondcr") }}"'>Cancel</a>
       </div>
    </div>
</div>



</div>

    
</div>



</form>

    
    



<script>
$(document).ready(function()
{
   

  jQuery('.mobile_no').keyup(function () { 
    this.value = this.value.replace(/[^0-9\.]/g,'');
});
 
      $('#savestatus').val('');
    $(document).on('click','.saveform',function() {

  var btnval    = $(this).val();
        $('#savestatus').val(btnval);
  var urls      ="{{ URL::to('institutiondcrsave')}}";
  

  validationrule('user_form');
      
    var form = $('#user_form');
    form.parsley().validate();
        if (form.parsley().isValid())
        {       
        if(btnval == "SAVENEW"){
          create_url = "{{ URL::to('createinstitutiondcr')}}";
        }else{
          create_url = "{{ URL::to('institutiondcr')}}";
        }
            var form_data = new FormData(document.getElementById('user_form'));              
            $.ajax({
                  url: "{{ URL::to('institutiondcrsave') }}",
                  type: "POST",
                  data: form_data,
                  enctype: 'multipart/form-data',
                  processData: false,  /*tell jQuery not to process the data*/
                  contentType: false,   /*tell jQuery not to set contentType*/
                  async:true,
                  xhr: function(){
                      var xhr = $.ajaxSettings.xhr();
                    if (xhr.upload) {
                        xhr.upload.addEventListener('progress', function(event) {
                                var percent = 0;
                                var position = event.loaded || event.position;
                                var total = event.total;
                                if (event.lengthComputable) {
                                        percent = Math.ceil(position / total * 100);
                                }
                                }, true);
                        }
          return xhr;

                }
                }).done(function(data,status)
                {
                    if(data == 1)
                    {
                        notyMsgs('info','Institution DCR Saved Successfully');
                        setTimeout(function(){
                            window.location.href=create_url;
                        }, 2000);
                    } else if(data==2){
                          notyMsgs('info','Institution DCR Updated Successfully');
                    	setTimeout(function(){
                          window.location.href=create_url;
                        }, 2000);
                    }
                    else
                    {
                      $(".alert-success").hide();
                      $(".alert-danger").fadeIn(800);

                    }
                }).fail(function(data,status)
                {
                  $(".alert-success").hide();
                  $(".alert-danger").fadeIn(800);

                });
    }
      
      
});
});
$(window).on('load',function(){
       var preLoder = $("#preloader");
       preLoder.fadeOut(500);
       var backtoTop = $('.back-to-top')
       backtoTop.fadeOut(100);
   });
</script>

@include('layouts.php_js_validation')
@endsection
