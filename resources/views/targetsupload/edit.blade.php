@extends('layouts.header')
@section('content')


<span class="ui_close_btn"></span>

<h2 class="heads">Product Warrenty</h2>

<div class="card">

            
                <div class="card-body card-block">
                  <form  action=""  id="save" >
                   
                    <input type="hidden" name="edit_id" value="{{$targets_id}}" id="edit_id" />
                {{ csrf_field()}}
           <div class="row">
<div class="col-md-12">


    <!--************************ Body content start here **********************-->
      
    <div class="col-md-4">
          
       
   <div class="form-group row">
      
            <label for="doctor_name" class="form-control-label col-md-4 ">
                <?php if($source=="SRTargets"){ ?>SR Name <?php }else if($source=="BRANCH TARGET") { ?>BRANCH NAME <?php }else{ ?>Awd<?php }?></label>
            <div class="col-md-6">
                <input class="form-control targets_id" id="targets_id" name="targets_id" type="hidden" value="{{ $targets_id }}" readonly>
            <select name='stockist_name' class='select2 stockist_name' id="stockist_name" > {!! $stockist_name !!}
            </select>
            </div>
        </div>

    </div>
    <div class="col-md-4">
        <div class="form-group row">
            <label for="last_name" class="form-control-label col-md-4">Town</label>
            <div class="col-md-6">
                   <select name='town_name' class='select2 town_name' id="town_name" > {!! $town_name !!}
            </select>
            </div>
        </div>
    </div>

    <div class="col-md-4">
       <div class="form-group row">
            <label for="last_name" class="form-control-label col-md-4">State</label>
            <div class="col-md-6">
                  <select name='state_name' class='select2 state_name' id="state_name" > {!! $state_name !!}
            </select>
              
            </div>
        </div>
       
        
    </div>
    
     <div class="col-md-4">
       <div class="form-group row">
            <label for="last_name" class="form-control-label col-md-4">Product</label>
            <div class="col-md-6">
                 <select id="product_name" name="product_name" class="form-control product_name select2"  >
                                    {!! $product_name !!}
                                </select>
            </div>
        </div>
       
        
    </div>
    
     <div class="col-md-4">
       <div class="form-group row">
            <label for="last_name" class="form-control-label col-md-4">Month</label>
            <div class="col-md-6">
                  <input type="text" name="month" class="form-control input-sm month" value="{{$month}}" >
            </div>
        </div>
       
        
    </div>  
    
    <div class="col-md-4">
       <div class="form-group row">
            <label for="last_name" class="form-control-label col-md-4">Target Qty</label>
            <div class="col-md-6">
                  <input type="text" name="target_qty" class="form-control input-sm target_qty" value="{{$target_qty}}" >
            </div>
        </div>
       
        
    </div>
    
     <div class="col-md-4">
       <div class="form-group row">
            <label for="last_name" class="form-control-label col-md-4">Year</label>
            <div class="col-md-6">
                  <input type="text" name="Year" class="form-control input-sm Year" value="{{$Year}}" >
            </div>
        </div>
       
        
    </div>
    

</div>
</div>
					  
					  
    <!-- lines start -->



    <!-- lines end -->
					  
					  
					  
                            <div class="col-md-12 text-center">
                    <button type="button" id="save" class="btn save">Save</button>
                    <button type="button" class="btn Cancel">Cancel</button>
                       
                    <?php include('toolbar.php'); ?>
                </div>
                        
                        
                     
                    </form>
                      </div>
					  
				</div>	

           <script>
               /************  save function start ***********/

            $(document).ready(function(){

            $(document).on('click', '.save', function () {
				
                var url ="{{URL::to('awduploadsave')}}";
                  <?php if($source=="SRTargets") { ?>
                var red_url ="{{URL::to('srtargets')}}";
                <?php }else{ ?>
                var red_url ="{{URL::to('awdtargets')}}";
                <?php } ?>
               // var red_url ="{{URL::to('awdtargets')}}";
				change_date();
                var form =$('#save');
                form.parsley().validate();
                if(form.parsley().isValid())
                {
                      var formdata =$('#save').serialize();
                    $.post(url,formdata,function(data)
                    {
                        var status = data.status;
                    var msg    = data.message;
                    var id     = data.id;
                    window.location.href=red_url;

                    });
                }


            });
				
				 /**************** change date format to save in php end***********/
       

function change_date()
{
    $( ".datepicker" ).each(function(){
      var myDate = $(this).val();
var parsedDate = $.datepicker.parseDate("{{\Session::get('j_date_format')}}", myDate);
$(this).val($.datepicker.formatDate("yy-mm-dd", parsedDate));
   });
   
}


            $(document).on('click', '.Cancel', function () {
                <?php if($source=="SRTargets") { ?>
                var url ="{{URL::to('srtargets')}}";
                <?php }else{ ?>
                var url ="{{URL::to('awdtargets')}}";
                <?php } ?>
                    window.location.href=url;


            });
        });
		 
 
           </script>        
         
	

      @include('layouts.php_js_validation')
@endsection          


    