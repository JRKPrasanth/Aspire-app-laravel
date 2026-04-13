@extends('layouts.header')
@section('content')
@include('layouts.php_js_validation')

<style type="text/css">
 /* .breadcrumb{
    display: none;
  }*/

</style>

<span class="ui_close_btn"></span>




<h2 class="heads">Institution</h2>

<div class="card">
<?php include('toolbar.php'); ?>

<div class="card-body card-block">
  
<form method="post" id="institution" class="institution"  enctype="multipart/form-data">

{{ csrf_field() }}
    <div class="col-md-12">
    <div class="col-md-4">

        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4"><span class="req">*</span>Name</label>
            <div class="col-md-8">
            <input class="form-control institution_id" id="institution_id" name="institution_id" size="16" type="hidden" >
                <input type="text" id="name" name="name"  class="form-control name"   required>
            <span class="btn btn-danger dup_name" style="display:none;"></span>
            </div>

        </div>
         <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4"><span class="req">*</span>Contact Name1</label>
            <div class="col-md-8">
           
                <input type="text" id="contact_name1" name="contact_name1"  class="form-control contact_name1"   required="">
            
            </div>

        </div>
        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4"><span class="req">*</span>Contact Name2</label>
            <div class="col-md-8">
           
                <input type="text" id="contact_name2" name="contact_name2"  class="form-control contact_name2"   required="">
            
            </div>

        </div>
      
   <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4"><span class="req">*</span>Associated Doctors</label>
            <div class="col-md-8">

                <input type="text"  id="associated_doctors" name="associated_doctors"  class="form-control associated_doctors" required="">
            </div>

        </div>

    </div>
    <div class="col-md-4">

        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4"><span class="req">*</span>Key Contact1</label>
            <div class="col-md-8">

                <input type="text"  id="key_contact1" name="key_contact1"  class="form-control key_contact1" required="">
            </div>

        </div>
        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4"><span class="req">*</span>Key Contact2</label>
            <div class="col-md-8">

                <input type="text"  id="key_contact2" name="key_contact2"  class="form-control key_contact2" required="">
            </div>

        </div>
       
       <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4"><span class="req">*</span>Desgination1</label>
            <div class="col-md-8">

                <input type="text"  id="desgination1" name="desgination1"  class="form-control desgination1" required="">
            </div>

        </div>
<div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4"><span class="req">*</span>Associated Distributors</label>
            <div class="col-md-8">

                <input type="text"  id="associated_distributors" name="associated_distributors"  class="form-control associated_distributors" required="">
            </div>  
    </div>
  </div>
    <div class="col-md-4">
       
 <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4"><span class="req">*</span>Mail1</label>
            <div class="col-md-8">

                <input type="email"  id="mail1" name="mail1"  class="form-control mail1" required="">
                <span class="btn btn-danger dup_name2" style="display:none;"></span>
                <span class="btn btn-danger email_vali" id="" style="display:none;"> Email format is example123@gmail.com</span>
            </div>

        </div>
       

       

   <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4"><span class="req">*</span>Desgination2</label>
            <div class="col-md-8">

                <input type="text"  id="desgination2" name="desgination2"  class="form-control desgination2" >
            </div>

        </div>
       
 <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4"><span class="req">*</span>Mail2</label>
            <div class="col-md-8">

                <input type="email"  id="mail2" name="mail2"  class="form-control mail2" >
                <span class="btn btn-danger dup_name2" style="display:none;"></span>
                <span class="btn btn-danger email_vali" id="" style="display:none;"> Email format is example123@gmail.com</span>
            </div>

        </div>
     
          <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4">Active</label>
            <div class="col-md-8">

                 <select name='active'  class='form-control active select2'  data-show-subtext="true" data-live-search="true"  >
               
                <option  value="YES" selected>Yes</option>
                <option  value="NO">No</option>
                    </select>
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
            <a class='btn cancel' onclick='location.href="{{ url("institution") }}"'>Cancel</a>
       </div>
    </div>
</div>
</div>
            </form>

<script>

        $(document).ready(function(){
/*Product Type Name to Uppercase Function*/
        $('.name').on('keyup',function(){
        this.value = this.value.toUpperCase();
        });
/*End*/
  
 $(document).on('click','.saveform',function() {

  var btnval    = $(this).val();
        $('#savestatus').val(btnval);
  var urls ="{{ URL::to('institutionsave')}}";
  

  validationrule('institution');
      
    var form = $('#institution');
    form.parsley().validate();
        if (form.parsley().isValid())
        {       
        if(btnval == "SAVENEW"){
          create_url = "{{ URL::to('createinstitution')}}";
        }else{
          create_url = "{{ URL::to('institution')}}";
        }
            var form_data = new FormData(document.getElementById('institution'));              
            $.ajax({
                  url: "{{ URL::to('institutionsave') }}",
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
                        notyMsgs('info','Institution Saved Successfully');
                        setTimeout(function(){
                            window.location.href=create_url;
                        }, 2000);
                    } else if(data==2){
                          notyMsgs('info','Institution Updated Successfully');
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
</script>
           @endsection           

